<?php
/**
 * Email templates — shared branded layout plus the contact-form notification.
 * Newsletter emails live in newsletter-template.php and reuse email_layout().
 *
 * Email clients ignore most modern CSS, so the layout is table-based with inline
 * styles. Colours mirror assets/css/tokens.css.
 */

/**
 * Branded shell: dark header with logo and badge, gold rule, optional full-width
 * image, white body, dark footer.
 *
 * $o keys: title, preheader, badge, logo (img src), body (HTML), footer (HTML),
 *          image (optional: ['src' => ..., 'alt' => ..., 'href' => ...])
 */
function email_layout(array $o): string
{
    $e = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title><?= $e($o['title']) ?></title>
<style>
  @media only screen and (max-width: 620px) {
    .wrap     { width: 100% !important; }
    .pad      { padding-left: 24px !important; padding-right: 24px !important; }
    .h1       { font-size: 24px !important; line-height: 30px !important; }
    .label    { display: block !important; width: auto !important; padding-bottom: 2px !important; border-bottom: 0 !important; }
    .value    { display: block !important; padding-top: 0 !important; }
    .btns     { width: 100% !important; }
    .btn-cell { display: block !important; width: 100% !important; text-align: center !important; }
    .btn-cell a { display: block !important; }
    .btn-gap  { display: block !important; height: 12px !important; width: auto !important; }
    .hero-img { width: 100% !important; height: auto !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background-color:#F0E8D6;-webkit-text-size-adjust:100%;">

<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;"><?= $e($o['preheader']) ?></div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#F0E8D6" style="background-color:#F0E8D6;">
<tr>
<td align="center" style="padding:32px 12px;">

  <table role="presentation" class="wrap" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;">

    <!-- Header -->
    <tr>
      <td class="pad" bgcolor="#0D1E12" style="background-color:#0D1E12;padding:28px 40px;border-radius:12px 12px 0 0;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td valign="middle">
            <img src="<?= $e($o['logo']) ?>" width="117" height="48" alt="SORWATOM" style="display:block;border:0;width:117px;height:48px;font-family:Georgia,'Times New Roman',serif;font-size:22px;font-weight:bold;letter-spacing:3px;color:#FFFFFF;">
          </td>
          <td valign="middle" align="right">
            <span style="display:inline-block;padding:6px 14px;border:1px solid #C8923A;border-radius:9999px;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#C8923A;"><?= $e($o['badge']) ?></span>
          </td>
        </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="4" bgcolor="#C8923A" style="background-color:#C8923A;height:4px;line-height:4px;font-size:0;">&nbsp;</td>
    </tr>
<?php if (!empty($o['image']['src'])): ?>

    <!-- Image -->
    <tr>
      <td bgcolor="#FFFFFF" style="background-color:#FFFFFF;font-size:0;line-height:0;">
        <a href="<?= $e($o['image']['href'] ?? '#') ?>"><img class="hero-img" src="<?= $e($o['image']['src']) ?>" width="600" alt="<?= $e($o['image']['alt'] ?? '') ?>" style="display:block;border:0;width:600px;max-width:100%;height:auto;"></a>
      </td>
    </tr>
<?php endif; ?>

    <!-- Body -->
    <tr>
      <td class="pad" bgcolor="#FFFFFF" style="background-color:#FFFFFF;padding:40px 40px 36px;">
<?= $o['body'] ?>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td class="pad" bgcolor="#0D1E12" style="background-color:#0D1E12;padding:24px 40px;border-radius:0 0 12px 12px;font-family:Helvetica,Arial,sans-serif;font-size:12px;line-height:19px;color:#E8E0D0;">
        <?= $o['footer'] ?>
        <br><span style="color:#8A9A8C;">Sorwatom &middot; Ndera - Mulindi, Kigali, Rwanda &middot; +250 787 160 000</span>
      </td>
    </tr>

  </table>

</td>
</tr>
</table>

</body>
</html>
<?php
    return ob_get_clean();
}

// Solid call-to-action button; $style 'outline' gives the secondary dark-outline look.
function email_button(string $href, string $label, string $style = 'solid'): string
{
    $e = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    if ($style === 'outline') {
        return '<td class="btn-cell" style="border:1px solid #0D1E12;border-radius:6px;">'
             . '<a href="' . $e($href) . '" style="display:inline-block;padding:13px 26px;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;color:#0D1E12;text-decoration:none;border-radius:6px;">' . $label . '</a>'
             . '</td>';
    }
    return '<td class="btn-cell" bgcolor="#C4472A" style="background-color:#C4472A;border-radius:6px;">'
         . '<a href="' . $e($href) . '" style="display:inline-block;padding:14px 28px;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;color:#FFFFFF;text-decoration:none;border-radius:6px;">' . $label . '</a>'
         . '</td>';
}

// ─── Contact-form notification ────────────────────────────────────────────────
// $d keys: name, company, email, mobile, inquiry, message (raw, unescaped strings)
//          received (DateTimeInterface)

function contact_inquiry_label(string $inquiry): string
{
    return ucwords(str_replace(['_', '-'], ' ', $inquiry));
}

// wa.me needs an international number without "+"; local Rwandan numbers start with 0.
function contact_whatsapp_digits(string $mobile): string
{
    $digits = preg_replace('/\D/', '', $mobile);
    if (strpos($digits, '0') === 0) {
        $digits = '250' . substr($digits, 1);
    }
    return strlen($digits) >= 9 ? $digits : '';
}

function contact_email_html(array $d, string $logoSrc): string
{
    $e = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    $type      = contact_inquiry_label($d['inquiry']);
    $firstName = explode(' ', $d['name'])[0];
    $received  = $d['received']->format('l, j F Y \a\t H:i') . ' (Kigali time)';
    $replyHref = 'mailto:' . $d['email'] . '?subject=' . rawurlencode('Re: Your inquiry to Sorwatom');
    $waDigits  = contact_whatsapp_digits($d['mobile']);

    $link = static fn($href, $text) => '<a href="' . $e($href) . '" style="color:#C4472A;text-decoration:none;">' . $e($text) . '</a>';

    $rows = ['Name' => $e($d['name'])];
    if ($d['company'] !== '') {
        $rows['Company'] = $e($d['company']);
    }
    $rows['Email'] = $link('mailto:' . $d['email'], $d['email']);
    if ($d['mobile'] !== '') {
        $rows['Phone'] = $link('tel:' . preg_replace('/[^\d+]/', '', $d['mobile']), $d['mobile']);
    }
    $rows['Inquiry'] = $e($type);

    ob_start();
?>
        <p style="margin:0 0 10px;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#C8923A;"><?= $e($type) ?> inquiry</p>
        <h1 class="h1" style="margin:0 0 10px;font-family:Georgia,'Times New Roman',serif;font-size:28px;line-height:34px;font-weight:normal;color:#0D1E12;">New message from <?= $e($d['name']) ?></h1>
        <p style="margin:0 0 32px;font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;color:#7A7A6E;">Received <?= $e($received) ?></p>

        <!-- Contact details -->
        <p style="margin:0 0 12px;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#7A7A6E;">Contact details</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #E5DDD0;margin-bottom:32px;">
<?php foreach ($rows as $label => $value): ?>
          <tr>
            <td class="label" width="120" valign="top" style="width:120px;padding:13px 0;border-bottom:1px solid #E5DDD0;font-family:Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#7A7A6E;"><?= $label ?></td>
            <td class="value" valign="top" style="padding:13px 0;border-bottom:1px solid #E5DDD0;font-family:Helvetica,Arial,sans-serif;font-size:15px;line-height:20px;color:#1A1A1A;word-break:break-word;"><?= $value ?></td>
          </tr>
<?php endforeach; ?>
        </table>

        <!-- Message -->
        <p style="margin:0 0 12px;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#7A7A6E;">Message</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:36px;">
          <tr>
            <td bgcolor="#FAF8F3" style="background-color:#FAF8F3;border-left:4px solid #C8923A;border-radius:0 8px 8px 0;padding:22px 24px;font-family:Helvetica,Arial,sans-serif;font-size:15px;line-height:24px;color:#1A1A1A;word-break:break-word;"><?= nl2br($e($d['message'])) ?></td>
          </tr>
        </table>

        <!-- Actions -->
        <table role="presentation" class="btns" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <?= email_button($replyHref, 'Reply to ' . $e($firstName) . ' &rarr;') ?>
<?php if ($waDigits !== ''): ?>
            <td class="btn-gap" width="12" style="width:12px;font-size:0;line-height:0;">&nbsp;</td>
            <?= email_button('https://wa.me/' . $waDigits, 'Message on WhatsApp', 'outline') ?>
<?php endif; ?>
          </tr>
        </table>
<?php
    $body = ob_get_clean();

    return email_layout([
        'title'     => "New $type inquiry",
        'preheader' => mb_strimwidth(preg_replace('/\s+/', ' ', $d['message']), 0, 110, '…'),
        'badge'     => 'New inquiry',
        'logo'      => $logoSrc,
        'body'      => $body,
        'footer'    => 'Sent from the contact form on <a href="https://www.sorwatom.com/contact" style="color:#C8923A;text-decoration:none;">sorwatom.com</a>.'
                     . ' Hitting reply sends your answer straight to ' . $e($d['name']) . '.',
    ]);
}

function contact_email_text(array $d): string
{
    $type = contact_inquiry_label($d['inquiry']);

    $lines = [
        'NEW ' . strtoupper($type) . ' INQUIRY',
        'Received ' . $d['received']->format('l, j F Y \a\t H:i') . ' (Kigali time)',
        '',
        'Name:     ' . $d['name'],
    ];
    if ($d['company'] !== '') {
        $lines[] = 'Company:  ' . $d['company'];
    }
    $lines[] = 'Email:    ' . $d['email'];
    if ($d['mobile'] !== '') {
        $lines[] = 'Phone:    ' . $d['mobile'];
    }
    $lines[] = '';
    $lines[] = 'MESSAGE';
    $lines[] = '-------';
    $lines[] = $d['message'];
    $lines[] = '';
    $lines[] = '--';
    $lines[] = 'Sent from the contact form on sorwatom.com. Reply to this email to answer ' . $d['name'] . ' directly.';

    return implode("\n", $lines);
}
