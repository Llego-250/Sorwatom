<?php
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../data/blog.php';
require_once __DIR__ . '/../data/newsletter.php';

$id         = isset($_GET['id']) ? (int) $_GET['id'] : null;
$post       = $id ? blog_get_post_admin($id) : null;
$categories = blog_get_all_categories_admin();
$authors    = blog_get_authors();
$errors     = [];
$success    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $title  = trim($_POST['title']   ?? '');
    $slug   = trim($_POST['slug']    ?? '');
    $status = $_POST['status']       ?? 'draft';

    if (!$title) $errors[] = 'Title is required.';
    if (!$slug)  $errors[] = 'Slug is required.';

    if ($slug && empty($errors)) {
        $db   = get_db();
        $stmt = $db->prepare("SELECT id FROM blog_posts WHERE slug = :slug AND id != :id");
        $stmt->execute([':slug' => $slug, ':id' => $id ?? 0]);
        if ($stmt->fetch()) $errors[] = 'This slug is already used by another post.';
    }

    if (empty($errors)) {
        $pub = ($_POST['published_at'] ?? '') ?: null;
        if ($status === 'published' && !$pub) $pub = date('Y-m-d H:i:s');

        $tags = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));

        $id = blog_save_post([
            'slug'         => $slug,
            'title'        => $title,
            'excerpt'      => trim($_POST['excerpt']     ?? ''),
            'content'      => $_POST['content']          ?? '',
            'category_id'  => ($_POST['category_id']     ?? '') ?: null,
            'author_id'    => ($_POST['author_id']        ?? '') ?: null,
            'image_url'    => trim($_POST['image_url']   ?? ''),
            'status'       => $status,
            'published_at' => $pub,
            'tags'         => $tags,
        ], $id);

        $post    = blog_get_post_admin($id);
        $success = true;
    }
}

$title_val   = htmlspecialchars($post['title']        ?? '');
$slug_val    = htmlspecialchars($post['slug']         ?? '');
$excerpt_val = htmlspecialchars($post['excerpt']      ?? '');
$content_raw = $post['content']                       ?? '';
$image_val   = htmlspecialchars($post['image_url']    ?? '');
$image_raw   = $post['image_url']                     ?? '';
$tags_val    = htmlspecialchars(implode(', ', $post['tags'] ?? []));
$pub_val     = htmlspecialchars(substr($post['published_at'] ?? '', 0, 16));
$status_val  = $post['status']      ?? 'draft';
$cat_val     = $post['category_id'] ?? '';
$auth_val    = $post['author_id']   ?? '';

$nl_flash  = $_SESSION['nl_flash'] ?? null;
unset($_SESSION['nl_flash']);
$nl_status = ($id && $status_val === 'published') ? newsletter_post_status($id) : null;
$nl_active = $nl_status ? newsletter_active_count() : 0;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $id ? 'Edit Post' : 'New Post' ?> — Sorwatom Admin</title>
<link rel="stylesheet" href="/assets/css/tokens.css">
<link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container admin-container--wide">

    <div class="page-header">
      <h1><?= $id ? 'Edit Post' : 'New Post' ?></h1>
      <?php if ($id && $status_val === 'published'): ?>
      <a href="/blog/<?= $slug_val ?>" target="_blank" class="btn-ghost">View Post ↗</a>
      <?php endif; ?>
    </div>

    <?php if ($success): ?>
    <div class="alert alert-success">Post saved successfully.</div>
    <?php endif; ?>
    <?php if ($nl_flash): ?>
    <div class="alert alert-<?= $nl_flash[0] === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($nl_flash[1]) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
    <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="post" id="post-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="content" id="content-hidden">

      <div class="edit-layout">

        <!-- ── Main column ── -->
        <div class="edit-main">

          <div class="field">
            <label for="title">Title <span class="req">*</span></label>
            <input type="text" id="title" name="title" value="<?= $title_val ?>" required
                   placeholder="Post title…" class="input-lg">
          </div>

          <div class="field">
            <label for="slug">Slug <span class="req">*</span>
              <span class="label-hint">— URL: /blog/<em id="slug-preview"><?= $slug_val ?: 'your-post-slug' ?></em></span>
            </label>
            <input type="text" id="slug" name="slug" value="<?= $slug_val ?>" required
                   pattern="[a-z0-9\-]+" placeholder="your-post-slug">
          </div>

          <div class="field">
            <label for="excerpt">Excerpt
              <span class="label-hint">— shown on listing cards (1–2 sentences)</span>
            </label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      placeholder="A short description shown on the blog listing page…"><?= $excerpt_val ?></textarea>
          </div>

          <!-- ── Rich Text Editor ── -->
          <div class="field field--editor">
            <label>Content <span class="req">*</span></label>

            <div class="rich-toolbar" id="rich-toolbar" onmousedown="return false">
              <!-- Text style -->
              <button type="button" class="rt-btn rt-bold"      title="Bold (Ctrl+B)"><b>B</b></button>
              <button type="button" class="rt-btn rt-italic"    title="Italic (Ctrl+I)"><em>I</em></button>
              <button type="button" class="rt-btn rt-underline" title="Underline (Ctrl+U)"><u>U</u></button>
              <button type="button" class="rt-btn rt-strike"    title="Strikethrough"><s>S</s></button>
              <span class="rt-sep"></span>
              <!-- Block -->
              <button type="button" class="rt-btn rt-h2"    title="Heading 2">H2</button>
              <button type="button" class="rt-btn rt-h3"    title="Heading 3">H3</button>
              <button type="button" class="rt-btn rt-para"  title="Paragraph">P</button>
              <span class="rt-sep"></span>
              <!-- Lists & quote -->
              <button type="button" class="rt-btn rt-quote" title="Blockquote">&#10077;</button>
              <button type="button" class="rt-btn rt-ul"    title="Bullet list">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><circle cx="2" cy="3" r="1.4"/><rect x="5" y="2" width="10" height="2" rx="1"/><circle cx="2" cy="8" r="1.4"/><rect x="5" y="7" width="10" height="2" rx="1"/><circle cx="2" cy="13" r="1.4"/><rect x="5" y="12" width="10" height="2" rx="1"/></svg>
                List
              </button>
              <button type="button" class="rt-btn rt-ol"    title="Numbered list">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><rect x="5" y="2" width="10" height="2" rx="1"/><rect x="5" y="7" width="10" height="2" rx="1"/><rect x="5" y="12" width="10" height="2" rx="1"/><text x="0" y="4.5" font-size="5">1.</text><text x="0" y="9.5" font-size="5">2.</text><text x="0" y="14.5" font-size="5">3.</text></svg>
                List
              </button>
              <span class="rt-sep"></span>
              <!-- Link & image -->
              <button type="button" class="rt-btn" id="btn-link" title="Insert / remove link">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                Link
              </button>
              <button type="button" class="rt-btn" id="btn-ins-img" title="Insert image">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Image
              </button>
              <span class="rt-sep"></span>
              <!-- Misc -->
              <button type="button" class="rt-btn rt-hr"    title="Horizontal rule">&#8212;</button>
              <button type="button" class="rt-btn rt-clear" title="Remove formatting" style="color:var(--ad-red)">&#10005;</button>
            </div>

            <div id="editor" class="rich-editor" contenteditable="true" spellcheck="true"><?= $content_raw ?></div>
          </div>

        </div><!-- /.edit-main -->

        <!-- ── Sidebar ── -->
        <aside class="edit-sidebar">

          <div class="sidebar-card">
            <h3>Publish</h3>
            <div class="field">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="draft"     <?= $status_val === 'draft'     ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= $status_val === 'published' ? 'selected' : '' ?>>Published</option>
              </select>
            </div>
            <div class="field">
              <label for="published_at">Publish Date</label>
              <input type="datetime-local" id="published_at" name="published_at" value="<?= $pub_val ?>">
            </div>
            <div class="sidebar-actions">
              <button type="submit" class="btn-primary btn-full">Save Post</button>
              <?php if ($id): ?>
              <button type="submit" form="delete-form" class="btn-delete btn-full"
                      style="margin-top:.5rem"
                      onclick="return confirm('Delete this post permanently?')">Delete Post</button>
              <?php endif; ?>
            </div>
          </div>

          <!-- ── Newsletter ── -->
          <div class="sidebar-card">
            <h3>Newsletter</h3>
            <?php if (!$nl_status): ?>
            <p class="nl-summary">Publish and save this post to email it to subscribers.</p>
            <?php else:
              $nl_waiting = $nl_status['pending'] + $nl_status['sending'];
            ?>
            <p class="nl-summary">
              <?php if ($nl_status['sent'] > 0): ?>
              Emailed to <strong><?= $nl_status['sent'] ?></strong> subscriber<?= $nl_status['sent'] === 1 ? '' : 's' ?>
              <?php if ($nl_status['last_sent_at']): ?><br><span class="nl-muted">Last sent <?= date('d M Y, H:i', strtotime($nl_status['last_sent_at'])) ?></span><?php endif; ?>
              <?php else: ?>
              Not emailed to subscribers yet.
              <?php endif; ?>
              <?php if ($nl_status['failed'] > 0): ?><br><span class="nl-error"><?= $nl_status['failed'] ?> failed</span><?php endif; ?>
            </p>

            <?php if ($nl_waiting > 0): ?>
            <a href="/admin/newsletter-send.php?post=<?= $id ?>" class="btn-primary btn-full">Resume sending (<?= $nl_waiting ?> left)</a>
            <?php elseif ($nl_status['remaining'] > 0): ?>
            <button type="submit" form="newsletter-queue-form" class="btn-primary btn-full"
                    onclick="return confirm('Email this post to <?= $nl_status['remaining'] ?> subscriber(s)? Make sure your latest changes are saved.')">
              Email to <?= $nl_status['remaining'] ?> subscriber<?= $nl_status['remaining'] === 1 ? '' : 's' ?>
            </button>
            <p class="field-hint">Sends the saved version of the post.<?= $nl_status['sent'] > 0 ? ' Only subscribers who haven’t received it yet are emailed.' : '' ?></p>
            <?php else: ?>
            <p class="field-hint"><?= $nl_active > 0 ? 'Every active subscriber has received this post.' : 'No active subscribers yet.' ?></p>
            <?php endif; ?>

            <div class="nl-test">
              <label for="nl-test-email">Send a test to</label>
              <div class="nl-test__row">
                <input type="email" id="nl-test-email" name="test_email" form="newsletter-test-form"
                       value="<?= htmlspecialchars($_SESSION['nl_test_email'] ?? '') ?>" placeholder="you@example.com" required>
                <button type="submit" form="newsletter-test-form" class="btn-ghost">Send</button>
              </div>
            </div>
            <?php endif; ?>
          </div>

          <div class="sidebar-card">
            <h3>Category</h3>
            <div class="field">
              <select name="category_id">
                <option value="">— none —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $cat_val == $c['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="sidebar-card">
            <h3>Author</h3>
            <div class="field">
              <select name="author_id">
                <option value="">— none —</option>
                <?php foreach ($authors as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $auth_val == $a['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- ── Featured Image ── -->
          <div class="sidebar-card">
            <h3>Featured Image</h3>
            <div class="field">
              <label class="img-drop-zone" for="feat-img-file" id="feat-drop-zone">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <span id="feat-drop-label">Click to upload or drag &amp; drop</span>
                <span class="img-drop-hint">JPEG · PNG · WebP · GIF — max 8 MB</span>
              </label>
              <input type="file" id="feat-img-file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none">

              <div id="feat-img-preview" class="feat-img-preview"<?= $image_raw ? '' : ' style="display:none"' ?>>
                <img id="feat-img-thumb" src="<?= htmlspecialchars($image_raw) ?>" alt="">
                <button type="button" id="feat-img-remove" class="feat-img-remove" title="Remove">&#10005;</button>
              </div>

              <input type="text" name="image_url" id="feat-img-url" value="<?= $image_val ?>"
                     placeholder="…or paste a URL">
              <p class="field-hint">URL updates automatically after upload</p>
            </div>
          </div>

          <div class="sidebar-card">
            <h3>Tags</h3>
            <div class="field">
              <input type="text" name="tags" value="<?= $tags_val ?>"
                     placeholder="tomatoes, recipe, harvest">
              <p class="field-hint">Comma-separated</p>
            </div>
          </div>

        </aside><!-- /.edit-sidebar -->

      </div><!-- /.edit-layout -->
    </form>

    <?php if ($id): ?>
    <form id="delete-form" method="post" action="/admin/post-delete.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= $id ?>">
    </form>
    <?php endif; ?>

    <?php if ($nl_status): ?>
    <form id="newsletter-queue-form" method="post" action="/admin/newsletter-send.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="action" value="queue">
      <input type="hidden" name="post" value="<?= $id ?>">
    </form>
    <form id="newsletter-test-form" method="post" action="/admin/newsletter-send.php" style="display:none">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
      <input type="hidden" name="action" value="test">
      <input type="hidden" name="post" value="<?= $id ?>">
    </form>
    <?php endif; ?>

  </div>
</main>

<!-- ── Inline image modal ─────────────────────────────────────────────── -->
<div id="ins-img-modal" class="ins-modal" style="display:none" role="dialog" aria-modal="true" aria-label="Insert image">
  <div class="ins-modal-inner">
    <h4>Insert Image</h4>
    <div class="field">
      <label>Image URL</label>
      <input type="text" id="iim-url" placeholder="/assets/img/blog/photo.webp">
    </div>
    <div class="field">
      <label class="img-drop-zone img-drop-zone--sm" for="iim-file" id="iim-drop-zone">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span id="iim-drop-label">Or upload a new image</span>
      </label>
      <input type="file" id="iim-file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none">
      <div id="iim-preview" style="display:none">
        <img id="iim-prev-img" src="" alt="" class="img-preview" style="max-height:120px;margin-top:.5rem;">
      </div>
    </div>
    <div class="field">
      <label>Alt text <span class="label-hint">(optional but recommended)</span></label>
      <input type="text" id="iim-alt" placeholder="Describe the image for screen readers">
    </div>
    <div class="ins-modal-actions">
      <button type="button" id="iim-insert" class="btn-primary">Insert</button>
      <button type="button" id="iim-cancel" class="btn-ghost">Cancel</button>
    </div>
  </div>
</div>
<div id="ins-modal-backdrop" class="ins-modal-backdrop" style="display:none"></div>

<script>
// ─── CSRF token ────────────────────────────────────────────────────────────
const csrfToken = document.querySelector('[name=csrf_token]').value;

// ─── Rich-text editor ──────────────────────────────────────────────────────
const editorEl = document.getElementById('editor');
const hiddenEl = document.getElementById('content-hidden');

// Ensure a paragraph exists on empty editor
if (!editorEl.innerHTML.trim()) editorEl.innerHTML = '<p><br></p>';

// Sync HTML to hidden field on save
document.getElementById('post-form').addEventListener('submit', function () {
  hiddenEl.value = editorEl.innerHTML;
});

// Map toolbar buttons to execCommand calls
const cmdMap = {
  'rt-bold':      ['bold'],
  'rt-italic':    ['italic'],
  'rt-underline': ['underline'],
  'rt-strike':    ['strikeThrough'],
  'rt-h2':        ['formatBlock', 'h2'],
  'rt-h3':        ['formatBlock', 'h3'],
  'rt-para':      ['formatBlock', 'p'],
  'rt-quote':     ['formatBlock', 'blockquote'],
  'rt-ul':        ['insertUnorderedList'],
  'rt-ol':        ['insertOrderedList'],
  'rt-hr':        ['insertHorizontalRule'],
  'rt-clear':     ['removeFormat'],
};

Object.entries(cmdMap).forEach(function([cls, args]) {
  const btn = document.querySelector('.' + cls);
  if (!btn) return;
  btn.addEventListener('click', function () {
    editorEl.focus();
    document.execCommand(args[0], false, args[1] || null);
  });
});

// Link button — insert or remove
document.getElementById('btn-link').addEventListener('click', function () {
  editorEl.focus();
  const sel = window.getSelection();
  if (sel && sel.anchorNode) {
    // Check if inside a link already
    const anchor = sel.anchorNode.parentElement.closest('a');
    if (anchor) { document.execCommand('unlink'); return; }
  }
  const url = prompt('Link URL (leave blank to remove):', 'https://');
  if (url === null) return;
  if (!url) { document.execCommand('unlink'); return; }
  document.execCommand('createLink', false, url);
});

// Active state highlight on toolbar
function syncToolbarState() {
  const stateButtons = {
    'rt-bold':      'bold',
    'rt-italic':    'italic',
    'rt-underline': 'underline',
    'rt-strike':    'strikeThrough',
    'rt-ul':        'insertUnorderedList',
    'rt-ol':        'insertOrderedList',
  };
  Object.entries(stateButtons).forEach(function([cls, cmd]) {
    var btn = document.querySelector('.' + cls);
    if (btn) {
      try { btn.classList.toggle('rt-active', document.queryCommandState(cmd)); } catch (e) {}
    }
  });
}

editorEl.addEventListener('keyup', syncToolbarState);
editorEl.addEventListener('mouseup', syncToolbarState);
document.addEventListener('selectionchange', syncToolbarState);

// Tab key inserts 4 spaces instead of losing focus
editorEl.addEventListener('keydown', function (ev) {
  if (ev.key === 'Tab') {
    ev.preventDefault();
    document.execCommand('insertHTML', false, '&nbsp;&nbsp;&nbsp;&nbsp;');
  }
});

// ─── Image upload helper ────────────────────────────────────────────────────
async function uploadImage(file, labelEl) {
  var span = labelEl.querySelector('span:not(.img-drop-hint)');
  var prev = span ? span.textContent : '';
  if (span) span.textContent = 'Uploading…';
  var fd = new FormData();
  fd.append('image', file);
  fd.append('csrf_token', csrfToken);
  try {
    var res  = await fetch('/admin/image-upload.php', { method: 'POST', body: fd });
    var data = await res.json();
    if (span) span.textContent = prev;
    if (data.error) { alert(data.error); return null; }
    return data.url;
  } catch (err) {
    if (span) span.textContent = prev;
    alert('Upload failed: ' + err.message);
    return null;
  }
}

// ─── Featured image sidebar ─────────────────────────────────────────────────
var featFile      = document.getElementById('feat-img-file');
var featDropZone  = document.getElementById('feat-drop-zone');
var featDropLabel = document.getElementById('feat-drop-label');
var featPreview   = document.getElementById('feat-img-preview');
var featThumb     = document.getElementById('feat-img-thumb');
var featUrlInput  = document.getElementById('feat-img-url');
var featRemoveBtn = document.getElementById('feat-img-remove');

featFile.addEventListener('change', async function () {
  if (!featFile.files[0]) return;
  var url = await uploadImage(featFile.files[0], featDropZone);
  if (url) {
    featUrlInput.value = url;
    featThumb.src = url;
    featPreview.style.display = '';
    featDropLabel.textContent = 'Change image';
  }
});

featUrlInput.addEventListener('input', function () {
  var url = featUrlInput.value.trim();
  if (url) { featThumb.src = url; featPreview.style.display = ''; }
  else     { featPreview.style.display = 'none'; }
});

featRemoveBtn.addEventListener('click', function () {
  featUrlInput.value = '';
  featThumb.src = '';
  featPreview.style.display = 'none';
  featFile.value = '';
  featDropLabel.textContent = 'Click to upload or drag & drop';
});

// Drag & drop on featured image zone
featDropZone.addEventListener('dragover', function (ev) {
  ev.preventDefault(); featDropZone.classList.add('drag-over');
});
featDropZone.addEventListener('dragleave', function () {
  featDropZone.classList.remove('drag-over');
});
featDropZone.addEventListener('drop', async function (ev) {
  ev.preventDefault(); featDropZone.classList.remove('drag-over');
  var file = ev.dataTransfer.files[0];
  if (!file) return;
  var url = await uploadImage(file, featDropZone);
  if (url) {
    featUrlInput.value = url;
    featThumb.src = url;
    featPreview.style.display = '';
    featDropLabel.textContent = 'Change image';
  }
});

// ─── Inline image insertion modal ──────────────────────────────────────────
var insModal    = document.getElementById('ins-img-modal');
var insBackdrop = document.getElementById('ins-modal-backdrop');
var iimUrl      = document.getElementById('iim-url');
var iimFile     = document.getElementById('iim-file');
var iimDropZone = document.getElementById('iim-drop-zone');
var iimPreview  = document.getElementById('iim-preview');
var iimPrevImg  = document.getElementById('iim-prev-img');
var iimAlt      = document.getElementById('iim-alt');
var savedRange  = null;

function saveRange() {
  var sel = window.getSelection();
  if (sel && sel.rangeCount > 0) savedRange = sel.getRangeAt(0).cloneRange();
}

function restoreRange() {
  if (!savedRange) return;
  var sel = window.getSelection();
  sel.removeAllRanges();
  sel.addRange(savedRange);
}

document.getElementById('btn-ins-img').addEventListener('mousedown', function (ev) {
  ev.preventDefault();
  saveRange();
});

document.getElementById('btn-ins-img').addEventListener('click', function () {
  iimUrl.value = ''; iimAlt.value = '';
  iimPreview.style.display = 'none';
  document.getElementById('iim-drop-label').textContent = 'Or upload a new image';
  insModal.style.display = '';
  insBackdrop.style.display = '';
  setTimeout(function () { iimUrl.focus(); }, 50);
});

function closeInsModal() {
  insModal.style.display = 'none';
  insBackdrop.style.display = 'none';
}

document.getElementById('iim-cancel').addEventListener('click', closeInsModal);
insBackdrop.addEventListener('click', closeInsModal);

document.addEventListener('keydown', function (ev) {
  if (ev.key === 'Escape' && insModal.style.display !== 'none') closeInsModal();
});

iimFile.addEventListener('change', async function () {
  if (!iimFile.files[0]) return;
  var url = await uploadImage(iimFile.files[0], iimDropZone);
  if (url) {
    iimUrl.value = url;
    iimPrevImg.src = url;
    iimPreview.style.display = '';
  }
});

iimUrl.addEventListener('input', function () {
  var url = iimUrl.value.trim();
  iimPrevImg.src = url;
  iimPreview.style.display = url ? '' : 'none';
});

document.getElementById('iim-insert').addEventListener('click', function () {
  var url = iimUrl.value.trim();
  if (!url) { iimUrl.focus(); return; }
  var alt = iimAlt.value.trim();
  editorEl.focus();
  restoreRange();
  document.execCommand('insertHTML', false,
    '<img src="' + url + '" alt="' + alt.replace(/"/g, '&quot;') + '" style="max-width:100%;height:auto;border-radius:4px;display:block;margin:.75em 0;">');
  closeInsModal();
});

// ─── Slug auto-generate ────────────────────────────────────────────────────
var titleInput = document.getElementById('title');
var slugInput  = document.getElementById('slug');
var slugPrev   = document.getElementById('slug-preview');
var manualSlug = <?= $id ? 'true' : 'false' ?>;

function toSlug(s) {
  return s.toLowerCase().trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

titleInput.addEventListener('input', function () {
  if (!manualSlug) {
    var s = toSlug(titleInput.value);
    slugInput.value = s;
    slugPrev.textContent = s || 'your-post-slug';
  }
});

slugInput.addEventListener('input', function () {
  manualSlug = true;
  slugInput.value = toSlug(slugInput.value) || slugInput.value;
  slugPrev.textContent = slugInput.value || 'your-post-slug';
});
</script>
</body>
</html>
