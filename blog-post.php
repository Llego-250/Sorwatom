<?php
require_once __DIR__ . '/partials/_lang.php';
require_once __DIR__ . '/data/blog.php';

$slug = $_GET['slug'] ?? '';
$post = blog_get_by_slug($slug);

if (!$post) {
    http_response_code(404);
    include '404.php';
    exit;
}

$related = blog_get_related((int) $post['id'], (int) $post['category_id']);

$page_title       = htmlspecialchars($post['title']) . ' — ' . __r('meta.blog_post_suffix');
$page_description = htmlspecialchars($post['excerpt'] ?? '');
$page_css         = ['pages/blog.css'];
$page_js          = ['blog-post.js'];
$body_class       = 'page-blog-post';
$current_page     = 'blog';

// Open Graph
$og_type        = 'article';
$og_title       = $post['title'];
$og_description = $post['excerpt'] ?? '';
$og_image       = !empty($post['image_url']) ? $post['image_url'] : SITE_URL . '/assets/img/logo.png';
$og_url         = SITE_URL . '/blog/' . $post['slug'];

include 'partials/_head.php';

// JSON-LD structured data
$json_ld = [
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => $post['title'],
    'description'   => $post['excerpt'] ?? '',
    'datePublished' => $post['published_at'],
    'dateModified'  => $post['updated_at'] ?? $post['published_at'],
    'author'        => ['@type' => 'Person', 'name' => $post['author_name'] ?? 'Sorwatom'],
    'publisher'     => ['@type' => 'Organization', 'name' => 'Sorwatom',
                        'logo' => ['@type' => 'ImageObject', 'url' => SITE_URL . '/assets/img/logo.png']],
];
if (!empty($post['image_url'])) $json_ld['image'] = $post['image_url'];
?>
<script type="application/ld+json"><?= json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- ============================================================
       POST HERO
       ============================================================ -->
  <section class="hero hero--full" aria-label="<?= __t('a11y.article_header') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>

    <div class="hero__content">
      <div class="container">

        <?php if (!empty($post['category_name'])): ?>
        <span class="post-hero__cat"
              style="background:<?= htmlspecialchars($post['category_color'] ?? '#c8923a') ?>">
          <?= htmlspecialchars($post['category_name']) ?>
        </span>
        <?php endif; ?>

        <h1 class="post-hero__title"><?= htmlspecialchars($post['title']) ?></h1>

        <div class="post-hero__meta">
          <?php if (!empty($post['author_name'])): ?>
          <span class="post-hero__author"><?= htmlspecialchars($post['author_name']) ?></span>
          <span class="post-hero__sep" aria-hidden="true">·</span>
          <?php endif; ?>
          <time datetime="<?= htmlspecialchars($post['published_at']) ?>">
            <?= htmlspecialchars($post['date_formatted']) ?>
          </time>
        </div>

      </div>
    </div>
  </section>


  <!-- ============================================================
       POST BODY
       ============================================================ -->
  <article class="section post-body" aria-label="<?= __t('a11y.article_content') ?>" style="background:white;border-top:1px solid var(--col-border);">
    <div class="container container--narrow">

      <!-- Featured image -->
      <?php if (!empty($post['image_url'])): ?>
      <figure class="post-featured-img">
        <img src="<?= htmlspecialchars($post['image_url']) ?>"
             alt="<?= htmlspecialchars($post['title']) ?>"
             loading="eager" fetchpriority="high" decoding="async">
      </figure>
      <?php endif; ?>

      <!-- Tags -->
      <?php if (!empty($post['tags'])): ?>
      <div class="post-tags cluster" aria-label="<?= __t('a11y.tags') ?>">
        <?php foreach ($post['tags'] as $tag): ?>
        <span class="badge badge--outline"><?= htmlspecialchars($tag) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Content -->
      <div class="post-content">
        <?= $post['content'] ?>
      </div>

      <!-- Share -->
      <div class="post-share" aria-label="<?= __t('a11y.share_article') ?>">
        <span class="post-share__label"><?= __t('blog.post.share') ?></span>
        <a href="https://wa.me/?text=<?= rawurlencode($post['title'] . ' — ' . SITE_URL . '/blog/' . $post['slug']) ?>"
           target="_blank" rel="noopener" class="share-btn share-btn--whatsapp"
           aria-label="<?= __t('a11y.share_whatsapp') ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          <?= __t('blog.post.share_whatsapp') ?>
        </a>
        <button type="button" class="share-btn share-btn--copy"
                data-copy-label="<?= __t('blog.post.copy_link') ?>"
                data-copied-label="<?= __t('blog.post.copied') ?>"
                aria-label="<?= __t('a11y.copy_link') ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
            <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
          </svg>
          <?= __t('blog.post.copy_link') ?>
        </button>
      </div>

      <!-- Author bio -->
      <?php if (!empty($post['author_name'])): ?>
      <div class="author-bio">
        <div class="author-bio__avatar" aria-hidden="true">
          <?php if (!empty($post['author_avatar'])): ?>
          <img src="<?= htmlspecialchars($post['author_avatar']) ?>"
               alt="<?= htmlspecialchars($post['author_name']) ?>">
          <?php else: ?>
          <span><?= htmlspecialchars(substr($post['author_name'], 0, 1)) ?></span>
          <?php endif; ?>
        </div>
        <div class="author-bio__body">
          <p class="author-bio__name"><?= htmlspecialchars($post['author_name']) ?></p>
          <?php if (!empty($post['author_bio'])): ?>
          <p class="author-bio__text"><?= htmlspecialchars($post['author_bio']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Back link -->
      <div class="post-footer-nav">
        <a href="/blog" class="btn--text"><?= __t('blog.post.back') ?></a>
      </div>

    </div>
  </article>


  <!-- ============================================================
       RELATED POSTS
       ============================================================ -->
  <?php if (!empty($related)): ?>
  <section class="section section--sm related-posts"
           style="background:var(--col-surface-2);"
           aria-labelledby="related-heading">
    <div class="container">
      <p class="eyebrow"><?= __t('blog.post.related_eyebrow') ?></p>
      <h2 id="related-heading" style="margin-bottom:var(--space-xl);">
        <?= __r('blog.post.related_heading') ?>
      </h2>
      <div class="blog-grid blog-grid--related">
        <?php foreach ($related as $i => $r): ?>
        <article class="post-card reveal" data-delay="<?= $i + 1 ?>">
          <a href="/blog/<?= htmlspecialchars($r['slug']) ?>"
             class="post-card__link"
             aria-label="<?= __t('a11y.read', ['title' => $r['title']]) ?>">
            <div class="post-card__img-wrap">
              <?php if (!empty($r['image_url'])): ?>
              <img src="<?= htmlspecialchars($r['image_url']) ?>" alt="" loading="lazy">
              <?php else: ?>
              <div class="post-card__img-placeholder" aria-hidden="true"></div>
              <?php endif; ?>
              <?php if (!empty($r['category_name'])): ?>
              <span class="post-card__category"
                    style="background:<?= htmlspecialchars($r['category_color'] ?? '#0d1e12') ?>">
                <?= htmlspecialchars($r['category_name']) ?>
              </span>
              <?php endif; ?>
            </div>
            <div class="post-card__body">
              <time class="post-card__date"
                    datetime="<?= htmlspecialchars($r['published_at']) ?>">
                <?= htmlspecialchars($r['date_formatted']) ?>
              </time>
              <h3 class="post-card__title"><?= htmlspecialchars($r['title']) ?></h3>
              <span class="post-card__read-more" aria-hidden="true"><?= __t('blog.card.read_more') ?></span>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>
</body>
</html>
