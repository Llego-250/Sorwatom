(function () {
  const card   = document.getElementById('nl-progress');
  const el     = id => document.getElementById(id);
  const resume = el('nl-resume');
  const retry  = el('nl-retry');
  let running  = false;

  function render(s) {
    const waiting = s.pending + s.sending;
    const total   = s.sent + s.failed + waiting;
    const pct     = total ? Math.round((s.sent + s.failed) / total * 100) : 100;
    el('nl-sent').textContent    = s.sent;
    el('nl-pending').textContent = waiting;
    el('nl-failed').textContent  = s.failed;
    el('nl-bar-fill').style.width = pct + '%';
    card.querySelector('.nl-bar').setAttribute('aria-valuenow', pct);
    retry.hidden = running || waiting > 0 || s.failed === 0;
  }

  function message(text, type) {
    const m = el('nl-message');
    m.textContent = text;
    m.className = 'nl-message' + (type ? ' nl-message--' + type : '');
  }

  function finish(s) {
    if (s.failed > 0) {
      message(s.failed + ' email(s) failed.' + (s.last_error ? ' Reason: ' + s.last_error : '') + ' Fix the problem (see Email Check), then retry.', 'error');
      el('nl-check').hidden = false;
    } else if (s.sent === 0) {
      message('There are no active subscribers to email yet.', '');
    } else {
      message('Done — this post has been emailed to ' + s.sent + ' subscriber(s).', 'success');
    }
  }

  async function run() {
    running = true;
    resume.hidden = true;
    message('Sending… keep this page open.', '');
    const body = new FormData();
    body.append('csrf_token', card.dataset.csrf);
    body.append('action', 'batch');
    body.append('post', card.dataset.post);

    try {
      while (true) {
        const res  = await fetch('/admin/newsletter-send.php', { method: 'POST', body });
        const data = await res.json().catch(() => ({ ok: false, error: 'Unexpected server response (HTTP ' + res.status + ').' }));
        if (!data.ok) throw new Error(data.error || 'Sending failed.');
        render(data);
        if (data.pending === 0) { running = false; render(data); finish(data); return; }
      }
    } catch (err) {
      running = false;
      message(err.message + ' Sending is paused.', 'error');
      resume.hidden = false;
    }
  }

  resume.addEventListener('click', run);

  const initial = JSON.parse(card.dataset.status);
  render(initial);
  if (initial.pending + initial.sending > 0) run();
  else finish(initial);
})();
