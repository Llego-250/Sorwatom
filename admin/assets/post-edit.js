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

// Clicking the toolbar must not take focus (and the selection) away from the editor
document.getElementById('rich-toolbar').addEventListener('mousedown', function (ev) {
  ev.preventDefault();
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
var manualSlug = slugInput.dataset.manualSlug === 'true';

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

// ─── Category / Author: "Other" shows a field for the new name ─────────────
document.querySelectorAll('select[data-other]').forEach(function (select) {
  const input = document.getElementById(select.dataset.other);
  function sync() {
    const other = select.value === 'new';
    input.hidden   = !other;
    input.required = other;
    if (other && document.activeElement === select) input.focus();
  }
  select.addEventListener('change', sync);
  sync();
});
