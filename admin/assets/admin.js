// Asks before destructive actions. Put data-confirm="Question?" on a form (checked on
// submit) or on a button/link (checked on click); cancelling stops the action.
document.addEventListener('submit', function (ev) {
  var form = ev.target;
  if (form.dataset.confirm && !confirm(form.dataset.confirm)) ev.preventDefault();
});

document.addEventListener('click', function (ev) {
  var el = ev.target.closest('[data-confirm]');
  if (el && el.tagName !== 'FORM' && !confirm(el.dataset.confirm)) ev.preventDefault();
});
