<?php
/**
 * Sends scheduled newsletter emails (see newsletter_run_scheduled in data/newsletter.php).
 * Called in the background by page visits (partials/footer.php) and every 15 minutes
 * by GitHub Actions (.github/workflows/newsletter-cron.yml). It only ever sends what
 * is due, so it's safe for anyone to call it.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

require_once __DIR__ . '/data/newsletter.php';

// Keep sending even if the visitor's page closes before the batch is done
ignore_user_abort(true);
@set_time_limit(60);

try {
    echo json_encode(['ok' => true] + newsletter_run_scheduled());
} catch (Throwable $e) {
    error_log('newsletter-cron: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false]);
}
