<?php
// Variables disponibles : $latestArticles, $popularArticles, $allLatest, $categories, $articlesByCategory
$sliderArticles = array_slice($latestArticles, 0, 4);
$featuredArticle = $popularArticles[0] ?? null;
$todayPopular = array_slice($popularArticles, 1, 2);
$sidebarBreaking = array_slice($allLatest, 0, 2);
$sidebarDontMiss = array_slice($allLatest, 2, 3);
?>

<!-- Welcome Blog Slide Area Start -->
<section class="welcome-blog-post-slide owl-carousel">
    <?php foreach ($sliderArticles as $slide): ?>
    <div class="single-blog-post-slide bg-img background-overlay-5" style="background-image: url(<?= resized('bg-img/' . (($slide['id'] % 4) + 1) . '.jpg', 1200, 580) ?>);">
        <div class="single-blog-post-content">
            <div class="tags">
                <a href="/categorie/<?= htmlspecialchars($slide['category_slug']) ?>"><?= htmlspecialchars($slide['category_name']) ?></a>
            </div>
            <h2><a href="/article/<?= htmlspecialchars($slide['slug']) ?>" class="font-pt"><?= htmlspecialchars($slide['title']) ?></a></h2>
            <div class="date">
                <a href="/article/<?= htmlspecialchars($slide['slug']) ?>"><?= date('F d, Y', strtotime($slide['published_at'])) ?></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<!-- Welcome Blog Slide Area End -->

<!-- Latest News Marquee Area Start -->
<div class="latest-news-marquee-area">
    <div class="simple-marquee-container">
        <div class="marquee">
            <ul class="marquee-content-items">
                <?php foreach ($allLatest as $mArticle): ?>
                <li>
                    <a href="/article/<?= htmlspecialchars($mArticle['slug']) ?>"><span class="latest-news-time"><?= date('H:i', strtotime($mArticle['published_at'])) ?></span> <?= htmlspecialchars($mArticle['title']) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<!-- Latest News Marquee Area End -->

<!-- Main Content Area Start -->
<section class="main-content-wrapper section_padding_100">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-9">
                <?php if ($featuredArticle): ?>
                <!-- Gazette Welcome Post -->
                <div class="gazette-welcome-post">
                    <div class="gazette-post-tag">
                        <a href="/categorie/<?= htmlspecialchars($featuredArticle['category_slug']) ?>"><?= htmlspecialchars($featuredArticle['category_name']) ?></a>
                    </div>
                    <h1 class="font-pt"><?= htmlspecialchars($featuredArticle['title']) ?></h1>
                    <p class="gazette-post-date"><?= date('F d, Y', strtotime($featuredArticle['published_at'])) ?></p>
                    <div class="blog-post-thumbnail my-5">
                        <?= img('blog-img/' . (($featuredArticle['id'] % 25) + 1) . '.jpg', $featuredArticle['title'], 850, 0, false) ?>
                    </div>
                    <p><?= htmlspecialchars($featuredArticle['excerpt']) ?></p>
                    <div class="post-continue-reading-share d-sm-flex align-items-center justify-content-between mt-30">
                        <div class="post-continue-btn">
                            <a href="/article/<?= htmlspecialchars($featuredArticle['slug']) ?>" class="font-pt">Continue Reading <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                        </div>
                        <div class="post-share-btn-group">
                            <a href="#" aria-label="Partager sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Partager sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($todayPopular)): ?>
                <div class="gazette-todays-post section_padding_100_50">
                    <div class="gazette-heading">
                        <h2>today's most popular</h2>
                    </div>
                    <?php foreach ($todayPopular as $tp): ?>
                    <div class="gazette-single-todays-post d-md-flex align-items-start mb-50">
                        <div class="todays-post-thumb">
                            <?= img('blog-img/' . (($tp['id'] % 25) + 1) . '.jpg', $tp['title'], 250) ?>
                        </div>
                        <div class="todays-post-content">
                            <div class="gazette-post-tag">
                                <a href="/categorie/<?= htmlspecialchars($tp['category_slug']) ?>"><?= htmlspecialchars($tp['category_name']) ?></a>
                            </div>
                            <h3><a href="/article/<?= htmlspecialchars($tp['slug']) ?>" class="font-pt mb-2"><?= htmlspecialchars($tp['title']) ?></a></h3>
                            <span class="gazette-post-date mb-2"><?= date('F d, Y', strtotime($tp['published_at'])) ?></span>
                            <p><?= htmlspecialchars($tp['excerpt']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-lg-3 col-md-6">
                <div class="sidebar-area">
                    <!-- Breaking News Widget -->
                    <div class="breaking-news-widget">
                        <div class="widget-title">
                            <h2>breaking news</h2>
                        </div>
                        <?php foreach ($sidebarBreaking as $bn): ?>
                        <div class="single-breaking-news-widget">
                            <?= img('blog-img/' . (($bn['id'] % 25) + 1) . '.jpg', $bn['title'], 270) ?>
                            <div class="breakingnews-title">
                                <p>breaking news</p>
                            </div>
                            <div class="breaking-news-heading gradient-background-overlay">
                                <h3 class="font-pt"><a href="/article/<?= htmlspecialchars($bn['slug']) ?>" style="color:#fff;"><?= htmlspecialchars($bn['title']) ?></a></h3>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Don't Miss Widget -->
                    <div class="donnot-miss-widget">
                        <div class="widget-title">
                            <h2>Don't miss</h2>
                        </div>
                        <?php foreach ($sidebarDontMiss as $dm): ?>
                        <div class="single-dont-miss-post d-flex mb-30">
                            <div class="dont-miss-post-thumb">
                                <?= img('blog-img/' . (($dm['id'] % 25) + 1) . '.jpg', $dm['title'], 70, 70) ?>
                            </div>
                            <div class="dont-miss-post-content">
                                <a href="/article/<?= htmlspecialchars($dm['slug']) ?>" class="font-pt"><?= htmlspecialchars($dm['title']) ?></a>
                                <span><?= date('M d, Y', strtotime($dm['published_at'])) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Advert Widget -->
                    <div class="advert-widget">
                        <div class="widget-title">
                            <h2>Advert</h2>
                        </div>
                        <div class="advert-thumb mb-30">
                            <a href="#" aria-label="Advertisement"><img src="/img/bg-img/add.png" alt="Advertisement" width="350" height="350" loading="lazy"></a>
                        </div>
                    </div>

                    <!-- Subscribe Widget -->
                    <div class="subscribe-widget">
                        <div class="widget-title">
                            <h2>subscribe</h2>
                        </div>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="email" name="email" placeholder="Your Email">
                                <button type="submit">subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Posts Area Start -->
    <div class="gazette-catagory-posts-area">
        <div class="container">
            <div class="row">
                <?php
                $catChunks = array_chunk($categories, ceil(count($categories) / 3));
                foreach ($catChunks as $catGroup):
                ?>
                <div class="col-12 col-md-4">
                    <?php foreach ($catGroup as $ci => $cat):
                        $catArticles = $articlesByCategory[$cat['id']] ?? [];
                    ?>
                        <?php foreach ($catArticles as $j => $cArticle): ?>
                        <div class="gazette-single-catagory-post">
                            <?php if ($j === 0): ?>
                            <div class="single-catagory-post-thumb mb-15">
                                <?= img('blog-img/' . (($cArticle['id'] % 25) + 1) . '.jpg', $cArticle['title'], 350) ?>
                            </div>
                            <div class="gazette-post-tag">
                                <a href="/categorie/<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a>
                            </div>
                            <?php endif; ?>
                            <h3><a href="/article/<?= htmlspecialchars($cArticle['slug']) ?>" class="font-pt"><?= htmlspecialchars($cArticle['title']) ?></a></h3>
                            <span><?= date('M d, Y', strtotime($cArticle['published_at'])) ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<!-- Category Posts Area End -->

<!-- Editorial Area Start -->
<section class="gazatte-editorial-area section_padding_100 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="editorial-post-slides owl-carousel">
                    <?php foreach (array_slice($allLatest, 0, 4) as $editorial): ?>
                    <div class="editorial-post-single-slide">
                        <div class="row">
                            <div class="col-12 col-md-5">
                                <div class="editorial-post-thumb">
                                    <?= img('blog-img/' . (($editorial['id'] % 25) + 1) . '.jpg', $editorial['title'], 400) ?>
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
<!-- Editorial Area End -->
