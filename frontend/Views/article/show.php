<?php
// Variables : $article, $related
$paragraphs = explode("\n\n", $article['content']);
$firstParagraphs = array_slice($paragraphs, 0, 2);
$lastParagraphs = array_slice($paragraphs, 2);
?>

<section class="single-post-area">
    <!-- Single Post Title -->
    <div class="single-post-title bg-img background-overlay" style="background-image: url(<?= resized('bg-img/' . (($article['id'] % 4) + 1) . '.jpg', 1200, 580) ?>);">
        <div class="container h-100">
            <div class="row h-100 align-items-end">
                <div class="col-12">
                    <div class="single-post-title-content">
                        <div class="gazette-post-tag">
                            <a href="/categorie/<?= htmlspecialchars($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
                        </div>
                        <h1 class="font-pt" style="color: #fff;"><?= htmlspecialchars($article['title']) ?></h1>
                        <p><?= date('F d, Y', strtotime($article['published_at'])) ?> | By <?= htmlspecialchars($article['author_name']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-post-contents">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="single-post-text">
                        <?php foreach ($firstParagraphs as $p): ?>
                            <p><?= nl2br(htmlspecialchars(trim($p))) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="single-post-thumb">
                        <?= img('blog-img/' . (($article['id'] % 25) + 1) . '.jpg', $article['title'], 1110) ?>
                    </div>
                </div>
                <?php if (!empty($lastParagraphs)): ?>
                <div class="col-12 col-md-8">
                    <div class="single-post-text">
                        <?php foreach ($lastParagraphs as $p): ?>
                            <p><?= nl2br(htmlspecialchars(trim($p))) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($article['excerpt'])): ?>
                <div class="col-12 col-md-10">
                    <div class="single-post-blockquote">
                        <blockquote>
                            <h6 class="font-pt mb-0">"<?= htmlspecialchars($article['excerpt']) ?>"</h6>
                        </blockquote>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Post meta -->
                <div class="col-12 col-md-8">
                    <div class="post-continue-reading-share d-sm-flex align-items-center justify-content-between mt-30 mb-30">
                        <div class="gazette-post-tag">
                            <a href="/categorie/<?= htmlspecialchars($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
                        </div>
                        <div class="post-share-btn-group">
                            <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <p class="text-muted"><i class="fa fa-eye"></i> <?= number_format($article['views']) ?> views</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<!-- Related Articles -->
<section class="gazette-catagory-posts-area section_padding_100 bg-gray">
    <div class="container">
        <div class="gazette-heading">
            <h4>Related Articles</h4>
        </div>
        <div class="row">
            <?php foreach ($related as $rel): ?>
            <div class="col-12 col-md-4">
                <div class="gazette-single-catagory-post">
                    <div class="single-catagory-post-thumb mb-15">
                        <?= img('blog-img/' . (($rel['id'] % 25) + 1) . '.jpg', $rel['title'], 350) ?>
                    </div>
                    <div class="gazette-post-tag">
                        <a href="/categorie/<?= htmlspecialchars($rel['category_slug']) ?>"><?= htmlspecialchars($rel['category_name']) ?></a>
                    </div>
                    <h5><a href="/article/<?= htmlspecialchars($rel['slug']) ?>" class="font-pt"><?= htmlspecialchars($rel['title']) ?></a></h5>
                    <span><?= date('M d, Y', strtotime($rel['published_at'])) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
