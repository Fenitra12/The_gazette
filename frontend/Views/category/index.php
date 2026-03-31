<?php
// Variables : $category, $articles, $editorialArticles, $currentPage, $totalPages

$getImagePath = function (array $item, int $fallbackModulo, string $fallbackDir): string {
    $file = trim((string)($item['featured_image'] ?? ''));
    if ($file !== '') {
        return strpos($file, '/') !== false ? $file : 'blog-img/' . $file;
    }

    return $fallbackDir . '/' . (($item['id'] % $fallbackModulo) + 1) . '.jpg';
};
?>

<!-- Breadcrumb Area Start -->
<div class="breadcumb-area section_padding_50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breacumb-content d-flex align-items-center justify-content-between">
                    <h1 class="gazette-post-tag mb-0">
                        <a href="/categorie/<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['name']) ?></a>
                    </h1>
                    <p class="editorial-post-date text-dark mb-0"><?= date('F d, Y') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<?php if (!empty($editorialArticles)): ?>
<!-- Editorial Area Start -->
<section class="gazatte-editorial-area section_padding_100 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    <?php foreach ($editorialArticles as $editorial): ?>
                    <div class="editorial-post-single-slide">
                        <div class="row">
                            <div class="col-12 col-md-5">
                                <div class="editorial-post-thumb">
                                    <?= img($getImagePath($editorial, 25, 'blog-img'), $editorial['title'], 400, 267, false) ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-7">
                                <div class="editorial-post-content">
                                    <div class="gazette-post-tag">
                                        <a href="/categorie/<?= htmlspecialchars($editorial['category_slug']) ?>"><?= htmlspecialchars($editorial['category_name']) ?></a>
                                    </div>
                                    <h2><a href="/article/<?= htmlspecialchars($editorial['slug']) ?>" class="font-pt mb-15"><?= htmlspecialchars($editorial['title']) ?></a></h2>
                                    <p class="editorial-post-date mb-15"><?= date('F d, Y', strtotime($editorial['published_at'])) ?></p>
                                    <p><?= htmlspecialchars($editorial['excerpt']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Category Articles -->
<section class="catagory-welcome-post-area section_padding_100">
    <div class="container">
        <div class="row">
            <?php foreach ($articles as $i => $article):
                // Varier la disposition comme le template original
                if ($i < 3) {
                    $colClass = 'col-12 col-md-4';
                } elseif ($i < 5) {
                    $colClass = 'col-12 col-md-6';
                } else {
                    $colClass = 'col-12';
                }
            ?>
            <div class="<?= $colClass ?>">
                <div class="gazette-welcome-post <?= $i >= 5 ? 'd-md-flex align-items-center' : '' ?>">
                    <div class="blog-post-thumbnail <?= $i >= 5 ? '' : 'my-5' ?>">
                        <?= img($getImagePath($article, 25, 'blog-img'), $article['title'], ($i < 3 ? 350 : 540), ($i < 3 ? 233 : 360), $i !== 0) ?>
                    </div>
                    <div class="<?= $i >= 5 ? 'welcome-post-contents ml-30' : '' ?>">
                        <div class="gazette-post-tag">
                            <a href="/categorie/<?= htmlspecialchars($category['slug']) ?>"><?= htmlspecialchars($category['name']) ?></a>
                        </div>
                        <h2 class="font-pt"><?= htmlspecialchars($article['title']) ?></h2>
                        <p class="gazette-post-date <?= $i >= 5 ? 'mb-15' : '' ?>"><?= date('F d, Y', strtotime($article['published_at'])) ?></p>
                        <p><?= htmlspecialchars($article['excerpt']) ?></p>
                        <div class="post-continue-reading-share mt-30">
                            <div class="post-continue-btn">
                                <a href="/article/<?= htmlspecialchars($article['slug']) ?>" class="font-pt">Continue Reading <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="row">
            <div class="col-12">
                <div class="gazette-pagination-area">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="/categorie/<?= htmlspecialchars($category['slug']) ?>/<?= $p ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/categorie/<?= htmlspecialchars($category['slug']) ?>/<?= $currentPage + 1 ?>" aria-label="Next"><i class="fa fa-angle-right"></i></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
