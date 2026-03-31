<?php
// Variables : $article, $related
// Le contenu est en HTML depuis TinyMCE, on l'affiche directement
$content = $article['content'];
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
                    <div class="single-post-text article-content">
                        <?= $content ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="single-post-thumb">
                        <?= img('blog-img/' . (($article['id'] % 25) + 1) . '.jpg', $article['title'], 1110, 740) ?>
                    </div>
                </div>

                <?php if (!empty($article['excerpt'])): ?>
                <div class="col-12 col-md-10">
                    <div class="single-post-blockquote">
                        <blockquote>
                            <p class="font-pt mb-0">"<?= htmlspecialchars($article['excerpt']) ?>"</p>
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
                            <a href="#" aria-label="Partager sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
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
            <h2>Related Articles</h2>
        </div>
        <div class="row">
            <?php foreach ($related as $rel): ?>
            <div class="col-12 col-md-4">
                <div class="gazette-single-catagory-post">
                    <div class="single-catagory-post-thumb mb-15">
                        <?= img('blog-img/' . (($rel['id'] % 25) + 1) . '.jpg', $rel['title'], 350, 233) ?>
                    </div>
                    <div class="gazette-post-tag">
                        <a href="/categorie/<?= htmlspecialchars($rel['category_slug']) ?>"><?= htmlspecialchars($rel['category_name']) ?></a>
                    </div>
                    <h3><a href="/article/<?= htmlspecialchars($rel['slug']) ?>" class="font-pt"><?= htmlspecialchars($rel['title']) ?></a></h3>
                    <span><?= date('M d, Y', strtotime($rel['published_at'])) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
