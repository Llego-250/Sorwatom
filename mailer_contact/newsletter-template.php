<?php
/**
 * Newsletter emails — a new blog post, and the welcome email for new subscribers.
 * Both use the shared email_layout() from template.php.
 *
 * $p (post view) keys: title, excerpt, category, author, date, reading_time,
 *                      url, image (absolute URL or '')
 */

require_once __DIR__ . '/template.php';

function newsletter_footer_html(string $unsubUrl): string
{
    $e    = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
    $host = parse_url($unsubUrl, PHP_URL_HOST);

    return 'You’re receiving this because you subscribed to Sorwatom news at ' . $e($host) . '.'
         . ' <a href="' . $e($unsubUrl) . '" style="color:#C8923A;text-decoration:underline;">Unsubscribe</a>';
}

function newsletter_post_html(array $p, string $unsubUrl, string $logoSrc): string
{
    $e = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    $meta = array_filter([
        $p['author'] !== '' ? 'By ' . $p['author'] : '',
        $p['date'],
        $p['reading_time'] . ' min read',
    ]);

    ob_start();
?>
<?php if ($p['category'] !== ''): ?>
        <p style="margin:0 0 10px;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#C8923A;"><?= $e($p['category']) ?></p>
<?php endif; ?>
        <h1 class="h1" style="margin:0 0 12px;font-family:Georgia,'Times New Roman',serif;font-size:30px;line-height:37px;font-weight:normal;color:#0D1E12;"><a href="<?= $e($p['url']) ?>" style="color:#0D1E12;text-decoration:none;"><?= $e($p['title']) ?></a></h1>
        <p style="margin:0 0 24px;font-family:Helvetica,Arial,sans-serif;font-size:13px;line-height:20px;color:#7A7A6E;"><?= $e(implode(' · ', $meta)) ?></p>

        <p style="margin:0 0 32px;font-family:Georgia,'Times New Roman',serif;font-size:17px;line-height:28px;color:#1A1A1A;"><?= $e($p['excerpt']) ?></p>

        <table role="presentation" class="btns" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <?= email_button($p['url'], 'Read the full story &rarr;') ?>
          </tr>
        </table>
<?php
    return email_layout([
        'title'     => $p['title'],
        'preheader' => mb_strimwidth($p['excerpt'], 0, 110, '…'),
        'badge'     => 'From the journal',
        'logo'      => $logoSrc,
        'image'     => $p['image'] !== '' ? ['src' => $p['image'], 'alt' => $p['title'], 'href' => $p['url']] : null,
        'body'      => ob_get_clean(),
        'footer'    => newsletter_footer_html($unsubUrl),
    ]);
}

function newsletter_post_text(array $p, string $unsubUrl): string
{
    $lines = [];
    if ($p['category'] !== '') {
        $lines[] = strtoupper($p['category']);
    }
    $lines[] = $p['title'];
    $lines[] = implode(' · ', array_filter([$p['author'] !== '' ? 'By ' . $p['author'] : '', $p['date'], $p['reading_time'] . ' min read']));
    $lines[] = '';
    $lines[] = $p['excerpt'];
    $lines[] = '';
    $lines[] = 'Read the full story: ' . $p['url'];
    $lines[] = '';
    $lines[] = '--';
    $lines[] = 'You’re receiving this because you subscribed to Sorwatom news.';
    $lines[] = 'Unsubscribe: ' . $unsubUrl;

    return implode("\n", $lines);
}

/**
 * $latest: up to 3 recent posts, each ['title', 'category', 'url'] — may be empty.
 */
function newsletter_welcome_html(array $latest, string $blogUrl, string $unsubUrl, string $logoSrc): string
{
    $e = static fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    ob_start();
?>
        <p style="margin:0 0 10px;font-family:Helvetica,Arial,sans-serif;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#C8923A;">Welcome</p>
        <h1 class="h1" style="margin:0 0 16px;font-family:Georgia,'Times New Roman',serif;font-size:30px;line-height:37px;font-weight:normal;color:#0D1E12;">You’re on the list!</h1>
        <p style="margin:0 0 16px;font-family:Helvetica,Arial,sans-serif;font-size:15px;line-height:25px;color:#1A1A1A;">Thank you for subscribing to the Sorwatom newsletter.</p>
        <p style="margin:0 0 32px;font-family:Helvetica,Arial,sans-serif;font-size:15px;line-height:25px;color:#1A1A1A;">Whenever we publish something new on our journal, from recipes made in the Sorwatom kitchen to harvest updates from our farmers and news from across the Great Lakes, it will arrive right here in your inbox.</p>
<?php if ($latest): ?>

        <p style="margin:0 0 4px;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#7A7A6E;">Catch up on recent stories</p>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<?php foreach ($latest as $post): ?>
          <tr>
            <td style="padding:16px 0;border-bottom:1px solid #E5DDD0;">
<?php if ($post['category'] !== ''): ?>
              <p style="margin:0 0 4px;font-family:Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;color:#C8923A;"><?= $e($post['category']) ?></p>
<?php endif; ?>
              <a href="<?= $e($post['url']) ?>" style="font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:25px;color:#0D1E12;text-decoration:none;"><?= $e($post['title']) ?> <span style="color:#C4472A;">&rarr;</span></a>
            </td>
          </tr>
<?php endforeach; ?>
        </table>
<?php endif; ?>

        <table role="presentation" class="btns" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <?= email_button($blogUrl, 'Visit the journal &rarr;') ?>
          </tr>
        </table>
<?php
    return email_layout([
        'title'     => 'Welcome to the Sorwatom newsletter',
        'preheader' => 'Recipes, harvest updates and news from Sorwatom, straight to your inbox.',
        'badge'     => 'Welcome',
        'logo'      => $logoSrc,
        'body'      => ob_get_clean(),
        'footer'    => newsletter_footer_html($unsubUrl),
    ]);
}

function newsletter_welcome_text(array $latest, string $blogUrl, string $unsubUrl): string
{
    $lines = [
        'YOU’RE ON THE LIST!',
        '',
        'Thank you for subscribing to the Sorwatom newsletter.',
        '',
        'Whenever we publish something new on our journal, from recipes made in the Sorwatom kitchen to harvest updates from our farmers and news from across the Great Lakes, it will arrive right here in your inbox.',
    ];
    if ($latest) {
        $lines[] = '';
        $lines[] = 'CATCH UP ON RECENT STORIES';
        foreach ($latest as $post) {
            $lines[] = '- ' . $post['title'] . ': ' . $post['url'];
        }
    }
    $lines[] = '';
    $lines[] = 'Visit the journal: ' . $blogUrl;
    $lines[] = '';
    $lines[] = '--';
    $lines[] = 'You’re receiving this because you subscribed to Sorwatom news.';
    $lines[] = 'Unsubscribe: ' . $unsubUrl;

    return implode("\n", $lines);
}
