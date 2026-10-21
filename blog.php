<?php
require_once __DIR__ . '/partials/_lang.php';
require_once __DIR__ . '/data/blog.php';

$per_page    = 9;
$active_cat  = $_GET['category'] ?? 'all';
$page        = max(1, (int) ($_GET['page'] ?? 1));

$categories  = blog_get_categories();
$total_posts = blog_count_posts($active_cat);
$total_pages = (int) ceil($total_posts / $per_page);
$featured    = ($page === 1 && $active_cat === 'all') ? blog_get_featured() : null;

// Skip featured post from the grid on page 1 to avoid duplication
$grid_posts = blog_get_posts($page, $per_page + ($featured ? 1 : 0), $active_cat);
if ($featured && $page === 1) array_shift($grid_posts);

$page_title       = __r('meta.blog.title');
$page_description = __r('meta.blog.desc');
$page_css         = ['pages/blog.css'];
$body_class       = 'page-blog';
$current_page     = 'blog';
include 'partials/_head.php';
?>
<body class="<?= htmlspecialchars($body_class) ?>">

<?php include 'partials/nav.php'; ?>

<main id="main-content">

  <!-- ============================================================
       HERO
       ============================================================ -->
  <section class="hero hero--full" aria-label="<?= __t('nav.journal') ?>">
    <div class="hero__bg hero__bg--pattern" aria-hidden="true"></div>
    <div class="hero__content">
      <div class="container">
        <p class="eyebrow eyebrow--light"><?= __t('blog.hero.eyebrow') ?></p>
        <h1 class="hero__title"><?= __r('blog.hero.title') ?></h1>
        <p class="hero__subtitle"><?= __t('blog.hero.subtitle') ?></p>
      </div>
    </div>
  </section>


  <!-- ============================================================
       FILTER + POSTS
       ============================================================ -->
  <section class="section" aria-labelledby="blog-heading">
    <div class="container">

      <!-- Category filter -->
      <div class="blog-filter" role="group" aria-label="<?= __t('a11y.filter_blog') ?>">
        <a href="/blog" class="filter-pill <?= $active_cat === 'all' ? 'active' : '' ?>"><?= __t('blog.filter.all') ?></a>
        <?php foreach ($categories as $cat): ?>
        <a href="/blog?category=<?= urlencode($cat['slug']) ?>"
           class="filter-pill <?= $active_cat === $cat['slug'] ? 'active' : '' ?>">
          <?= htmlspecialchars($cat['name']) ?>
          <span class="filter-pill__count"><?= $cat['post_count'] ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Featured post (page 1, no category filter only) -->
      <?php if ($featured):
        // Strip HTML from content, grab first ~320 chars as a body preview
        $feat_preview = '';
        if (!empty($featured['content'])) {
            $stripped = preg_replace('/\s+/', ' ', trim(strip_tags($featured['content'])));
            $feat_preview = mb_substr($stripped, 0, 320);
            if (mb_strlen($stripped) > 320) $feat_preview .= '…';
        }
      ?>
      <a href="/blog/<?= htmlspecialchars($featured['slug']) ?>"
         class="featured-post reveal"
         aria-label="<?= __t('a11y.featured', ['title' => $featured['title']]) ?>">
        <div class="featured-post__img-wrap">
          <?php if (!empty($featured['image_url'])): ?>
          <img src="<?= htmlspecialchars($featured['image_url']) ?>"
               alt="" loading="eager" fetchpriority="high">
          <?php else: ?>
          <div class="post-card__img-placeholder" aria-hidden="true"></div>
          <?php endif; ?>
          <span class="featured-post__label"><?= __t('blog.featured.label') ?></span>
        </div>
        <div class="featured-post__body">
          <?php if (!empty($featured['category_name'])): ?>
          <span class="post-card__category featured-post__cat"
                style="background:<?= htmlspecialchars($featured['category_color'] ?? '#0d1e12') ?>">
            <?= htmlspecialchars($featured['category_name']) ?>
          </span>
          <?php endif; ?>
          <h2 class="featured-post__title"><?= htmlspecialchars($featured['title']) ?></h2>
          <?php if ($feat_preview): ?>
          <p class="featured-post__preview"><?= htmlspecialchars($feat_preview) ?></p>
          <?php elseif (!empty($featured['excerpt'])): ?>
          <p class="featured-post__excerpt"><?= htmlspecialchars($featured['excerpt']) ?></p>
          <?php endif; ?>
          <div class="featured-post__meta">
            <time datetime="<?= htmlspecialchars($featured['published_at']) ?>">
              <?= htmlspecialchars($featured['date_formatted']) ?>
            </time>
            <span aria-hidden="true">·</span>
            <span><?= __t($featured['reading_time'] === 1 ? 'blog.card.min_read_one' : 'blog.card.min_read', ['n' => $featured['reading_time']]) ?></span>
          </div>
          <span class="featured-post__read-more"><?= __t('blog.featured.read_more') ?></span>
        </div>
      </a>
      <?php endif; ?>

      <!-- Post grid -->
      <?php if (empty($grid_posts) && !$featured): ?>
      <p class="blog-empty"><?= __t('blog.empty') ?></p>
      <?php elseif (!empty($grid_posts)): ?>
      <div class="blog-grid" id="blog-grid">
        <?php foreach ($grid_posts as $i => $post): ?>
        <article class="post-card reveal" data-delay="<?= ($i % 3) + 1 ?>">
          <a href="/blog/<?= htmlspecialchars($post['slug']) ?>"
             class="post-card__link"
             aria-label="<?= __t('a11y.read', ['title' => $post['title']]) ?>">

            <div class="post-card__img-wrap">
              <?php if (!empty($post['image_url'])): ?>
              <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="" loading="lazy">
              <?php else: ?>
              <div class="post-card__img-placeholder" aria-hidden="true"></div>
              <?php endif; ?>
            </div>

            <div class="post-card__body">
              <?php if (!empty($post['category_name'])): ?>
              <span class="post-card__category"
                    style="background:<?= htmlspecialchars($post['category_color'] ?? '#0d1e12') ?>">
                <?= htmlspecialchars($post['category_name']) ?>
              </span>
              <?php endif; ?>
              <div class="post-card__meta">
                <time datetime="<?= htmlspecialchars($post['published_at']) ?>">
                  <?= htmlspecialchars($post['date_formatted']) ?>
                </time>
                <span class="post-card__dot" aria-hidden="true">·</span>
                <span><?= __t($post['reading_time'] === 1 ? 'blog.card.min_read_one' : 'blog.card.min_read', ['n' => $post['reading_time']]) ?></span>
              </div>
              <h2 class="post-card__title"><?= htmlspecialchars($post['title']) ?></h2>
              <p class="post-card__excerpt"><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
              <span class="post-card__read-more" aria-hidden="true"><?= __t('blog.card.read_more') ?></span>
            </div>

          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <nav class="pagination" aria-label="<?= __t('a11y.pagination') ?>">
        <?php if ($page > 1): ?>
        <a href="/blog?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
           class="pagination__btn"><?= __t('blog.pagination.prev') ?></a>
        <?php endif; ?>
        <span class="pagination__info"><?= __t('blog.pagination.info', ['page' => $page, 'total' => $total_pages]) ?></span>
        <?php if ($page < $total_pages): ?>
        <a href="/blog?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
           class="pagination__btn"><?= __t('blog.pagination.next') ?></a>
        <?php endif; ?>
      </nav>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/_scripts.php'; ?>
</body>
</html>
