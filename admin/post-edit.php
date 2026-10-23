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

    // "Other" in the Category / Author lists comes with the name to add
    $new_names = [];
    foreach (['category', 'author'] as $field) {
        if (($_POST[$field . '_id'] ?? '') !== 'new') continue;
        $name = trim(preg_replace('/\s+/', ' ', (string) ($_POST[$field . '_new'] ?? '')));
        if ($name === '')               $errors[] = "Enter a name for the new $field.";
        elseif (mb_strlen($name) > 100) $errors[] = "The new $field name can be at most 100 characters.";
        else                            $new_names[$field] = $name;
    }

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

        $category_id = isset($new_names['category'])
            ? blog_find_or_create_category($new_names['category'])
            : (($_POST['category_id'] ?? '') ?: null);
        $author_id = isset($new_names['author'])
            ? blog_find_or_create_author($new_names['author'])
            : (($_POST['author_id'] ?? '') ?: null);

        $id = blog_save_post([
            'slug'         => $slug,
            'title'        => $title,
            'excerpt'      => trim($_POST['excerpt']     ?? ''),
            'content'      => $_POST['content']          ?? '',
            'category_id'  => $category_id,
            'author_id'    => $author_id,
            'image_url'    => trim($_POST['image_url']   ?? ''),
            'status'       => $status,
            'published_at' => $pub,
            'auto_email'   => empty($_POST['auto_email']) ? 0 : 1,
            'tags'         => $tags,
        ], $id);

        $post       = blog_get_post_admin($id);
        $categories = blog_get_all_categories_admin();
        $authors    = blog_get_authors();
        $success    = true;
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
$auto_val    = !empty($post['auto_email']);
$auto_sent   = $post['auto_emailed_at'] ?? null;
$cat_val     = $post['category_id'] ?? '';
$auth_val    = $post['author_id']   ?? '';
// A failed save keeps "Other" selected with the name that was typed
$cat_new     = '';
$auth_new    = '';
if ($errors && ($_POST['category_id'] ?? '') === 'new') { $cat_val  = 'new'; $cat_new  = (string) ($_POST['category_new'] ?? ''); }
if ($errors && ($_POST['author_id']   ?? '') === 'new') { $auth_val = 'new'; $auth_new = (string) ($_POST['author_new']   ?? ''); }

$nl_flash  = $_SESSION['nl_flash'] ?? null;
unset($_SESSION['nl_flash']);
$scheduled = $post && blog_is_scheduled($post);
$nl_status = ($id && $status_val === 'published' && !$scheduled) ? newsletter_post_status($id) : null;
$nl_active = $nl_status ? newsletter_active_count() : 0;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $id ? 'Edit Post' : 'New Post' ?> — Sorwatom Admin</title>
<link rel="stylesheet" href="<?= asset_url('/assets/css/tokens.css') ?>">
<link rel="stylesheet" href="<?= asset_url('/admin/assets/admin.css') ?>">
<script src="<?= asset_url('/admin/assets/admin.js') ?>" defer></script>
</head>
<body class="admin-body">

<?php include __DIR__ . '/_nav.php'; ?>

<main class="admin-main">
  <div class="admin-container admin-container--wide">

    <div class="page-header">
      <h1><?= $id ? 'Edit Post' : 'New Post' ?></h1>
      <?php if ($id && $status_val === 'published' && !$scheduled): ?>
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
                   pattern="[a-z0-9\-]+" placeholder="your-post-slug"
                   data-manual-slug="<?= $id ? 'true' : 'false' ?>">
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

            <div class="rich-toolbar" id="rich-toolbar">
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
              <p class="field-hint">Kigali time. Pick a future date to schedule a published post — it appears on the site then.</p>
            </div>
            <div class="field">
              <label class="field-check">
                <input type="checkbox" name="auto_email" value="1" <?= $auto_val ? 'checked' : '' ?>>
                Email subscribers automatically when it goes live
              </label>
              <p class="field-hint">
                <?php if ($auto_sent): ?>
                Started automatically on <?= date('d M Y, H:i', strtotime($auto_sent)) ?>. Saving again won’t resend it.
                <?php else: ?>
                Sent once, to everyone subscribed at that moment. For a post that is already live, it starts within a few minutes.
                <?php endif; ?>
              </p>
            </div>
            <div class="sidebar-actions">
              <button type="submit" class="btn-primary btn-full">Save Post</button>
              <?php if ($id): ?>
              <button type="submit" form="delete-form" class="btn-delete btn-full"
                      style="margin-top:.5rem"
                      data-confirm="Delete this post permanently?">Delete Post</button>
              <?php endif; ?>
            </div>
          </div>

          <!-- ── Newsletter ── -->
          <div class="sidebar-card">
            <h3>Newsletter</h3>
            <?php if ($scheduled): ?>
            <p class="nl-summary">Scheduled for <strong><?= date('d M Y, H:i', strtotime($post['published_at'])) ?></strong>.
              <?= $auto_val
                  ? 'It will be emailed to all subscribers automatically when it goes live.'
                  : 'You can email it to subscribers once it’s live — or tick “Email subscribers automatically” above.' ?></p>
            <?php elseif (!$nl_status): ?>
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
                    data-confirm="<?= htmlspecialchars('Email this post to ' . $nl_status['remaining'] . ' subscriber(s)? Make sure your latest changes are saved.') ?>">
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
              <p class="field-hint">Emails not arriving? <a href="/admin/email-check.php">Run the Email Check</a></p>
            </div>
            <?php endif; ?>
          </div>

          <div class="sidebar-card">
            <h3>Category</h3>
            <div class="field">
              <select name="category_id" data-other="category-new">
                <option value="">— none —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $cat_val == $c['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
                <?php endforeach; ?>
                <option value="new" <?= $cat_val === 'new' ? 'selected' : '' ?>>Other — add a new category…</option>
              </select>
              <input type="text" name="category_new" id="category-new" class="field-other" maxlength="100"
                     value="<?= htmlspecialchars($cat_new) ?>" placeholder="New category name"
                     aria-label="New category name"<?= $cat_val === 'new' ? '' : ' hidden' ?>>
            </div>
          </div>

          <div class="sidebar-card">
            <h3>Author</h3>
            <div class="field">
              <select name="author_id" data-other="author-new">
                <option value="">— none —</option>
                <?php foreach ($authors as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $auth_val == $a['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['name']) ?>
                </option>
                <?php endforeach; ?>
                <option value="new" <?= $auth_val === 'new' ? 'selected' : '' ?>>Other — add a new author…</option>
              </select>
              <input type="text" name="author_new" id="author-new" class="field-other" maxlength="100"
                     value="<?= htmlspecialchars($auth_new) ?>" placeholder="New author name"
                     aria-label="New author name"<?= $auth_val === 'new' ? '' : ' hidden' ?>>
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
                     placeholder="tomatoes, recipe, news">
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

<script src="<?= asset_url('/admin/assets/post-edit.js') ?>"></script>
</body>
</html>
