<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= htmlspecialchars($metaTitle ?? 'TheGazette') ?></title>

    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl ?? '') ?>">

    <link rel="icon" href="/img/core-img/favicon.ico">

    <!-- 1) PRELOAD LCP IMAGE FIRST - before any CSS so browser starts download immediately -->
    <?php if (!empty($lcpImageUrl)): ?>
    <link rel="preload" as="image" href="<?= htmlspecialchars($lcpImageUrl) ?>" fetchpriority="high">
    <?php endif; ?>

    <!-- 2) Critical CSS only (render-blocking, absolute minimum for first paint) -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/core-style.css">
    <link rel="stylesheet" href="/css/responsive.css">

    <!-- 3) Inline critical CSS for hero slider to avoid dependency on owl.carousel.css -->
    <style>
    .owl-carousel{display:block;-webkit-tap-highlight-color:transparent;position:relative;z-index:1}
    .owl-carousel .owl-stage-outer{overflow:hidden;position:relative}
    .owl-carousel .owl-stage{position:relative;display:flex}
    .owl-carousel .owl-item{min-height:1px;position:relative;float:left;width:100%}
    </style>

    <!-- 4) Non-critical CSS: fonts, icons, animations loaded async -->
    <link rel="stylesheet" href="/css/font-awesome.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/owl.carousel.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/google-fonts.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/pe-icon-7-stroke.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/magnific-popup.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/animate.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/css/jquery-ui.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/owl.carousel.css">
        <link rel="stylesheet" href="/css/google-fonts.css">
        <link rel="stylesheet" href="/css/pe-icon-7-stroke.css">
        <link rel="stylesheet" href="/css/magnific-popup.css">
        <link rel="stylesheet" href="/css/animate.css">
        <link rel="stylesheet" href="/css/jquery-ui.min.css">
    </noscript>

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "TheGazette",
        "url": "<?= htmlspecialchars(($canonicalUrl ?? '') ? rtrim(preg_replace('#/[^/]*$#', '', $canonicalUrl ?? ''), '/') . '/' : '/') ?>"
    }
    </script>
    <?php if (!empty($schemaOrg)): ?>
    <script type="application/ld+json">
    <?= json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
    <?php endif; ?>
</head>

<body>
    <!-- Header Area Start -->
    <header class="header-area">
        <div class="top-header">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <!-- Breaking News Area -->
                    <div class="col-12 col-md-6">
                        <div class="breaking-news-area">
                            <span class="breaking-news-title">Breaking news</span>
                            <div id="breakingNewsTicker" class="ticker">
                                <ul>
                                    <?php
                                    $tickerItems = $allLatest ?? $latestArticles ?? [];
                                    if (!empty($tickerItems)):
                                        foreach ($tickerItems as $tickerArt): ?>
                                            <li><a href="/article/<?= htmlspecialchars($tickerArt['slug']) ?>"><?= htmlspecialchars($tickerArt['title']) ?></a></li>
                                    <?php endforeach;
                                    endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Stock News Area -->
                    <div class="col-12 col-md-6">
                        <div class="stock-news-area">
                            <div id="stockNewsTicker" class="ticker">
                                <ul>
                                    <li>
                                        <div class="single-stock-report">
                                            <div class="stock-values">
                                                <span>eur/usd</span>
                                                <span>1.1862</span>
                                            </div>
                                            <div class="stock-index minus-index">
                                                <span class="stock-index-value">0.18</span>
                                            </div>
                                        </div>
                                        <div class="single-stock-report">
                                            <div class="stock-values">
                                                <span>BTC/usd</span>
                                                <span>15.674.99</span>
                                            </div>
                                            <div class="stock-index plus-index">
                                                <span class="stock-index-value">8.60</span>
                                            </div>
                                        </div>
                                        <div class="single-stock-report">
                                            <div class="stock-values">
                                                <span>ETH/usd</span>
                                                <span>674.99</span>
                                            </div>
                                            <div class="stock-index minus-index">
                                                <span class="stock-index-value">13.60</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Middle Header Area -->
        <div class="middle-header">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="logo-area">
                            <a href="/"><img src="/img/core-img/logo.png" alt="TheGazette Logo" width="334" height="35" fetchpriority="high"></a>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="header-advert-area">
                            <a href="#" aria-label="Advertisement"><picture><source srcset="<?= \App\Core\ImageHelper::webpUrl('bg-img/top-advert.png', 728, 90) ?>" type="image/webp"><img src="/img/bg-img/top-advert.png" alt="Advertisement" width="728" height="90"></picture></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Header Area -->
        <div class="bottom-header">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12">
                        <div class="main-menu">
                            <nav class="navbar navbar-expand-lg">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#gazetteMenu" aria-controls="gazetteMenu" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars"></i> Menu</button>
                                <div class="collapse navbar-collapse" id="gazetteMenu">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/">Accueil</a>
                                        </li>
                                        <?php if (!empty($categories)):
                                            foreach ($categories as $cat): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/categorie/<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></a>
                                                </li>
                                        <?php endforeach;
                                        endif; ?>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="/" aria-label="Accueil - Page principale">Accueil</a>
                                                <a class="dropdown-item" href="/about">About Us</a>
                                                <a class="dropdown-item" href="/contact">Contact</a>
                                            </div>
                                        </li>
                                    </ul>
                                    <!-- Search Form -->
                                    <div class="header-search-form mr-auto">
                                        <form action="/">
                                            <input type="search" placeholder="Rechercher..." id="search" name="search">
                                            <input class="d-none" type="submit" value="submit">
                                        </form>
                                    </div>
                                    <button type="button" id="searchbtn" aria-label="Rechercher">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area End -->

    <main>
    <?= $content ?>
    </main>

    <!-- Footer Area Start -->
    <footer class="footer-area bg-img background-overlay" style="background-image: url(<?= resized('bg-img/4.jpg', 1200, 600) ?>);">
        <div class="top-footer-area section_padding_100_70">
            <div class="container">
                <div class="row">
                    <?php if (!empty($categories)):
                        $chunks = array_chunk($categories, ceil(count($categories) / 3));
                        $footerTitles = ['Catégories', 'Thèmes', 'Rubriques'];
                        foreach ($chunks as $i => $chunk): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="single-footer-widget">
                                <div class="footer-widget-title">
                                    <h2 class="font-pt"><?= $footerTitles[$i] ?? 'Plus' ?></h2>
                                </div>
                                <ul class="footer-widget-menu">
                                    <?php foreach ($chunk as $fCat): ?>
                                        <li><a href="/categorie/<?= htmlspecialchars($fCat['slug']) ?>"><?= htmlspecialchars($fCat['name']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach;
                    endif; ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <div class="single-footer-widget">
                            <div class="footer-widget-title">
                                <h2 class="font-pt">Pages</h2>
                            </div>
                            <ul class="footer-widget-menu">
                                <li><a href="/">Accueil</a></li>
                                <li><a href="/about">About Us</a></li>
                                <li><a href="/contact">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-footer-area">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center">
                    <div class="col-12">
                        <div class="copywrite-text">
                            <p>Copyright &copy; <?= date('Y') ?> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End -->

    <script src="/js/jquery/jquery-3.7.1.min.js" defer></script>
    <script src="/js/popper.min.js" defer></script>
    <script src="/js/bootstrap.min.js" defer></script>
    <script src="/js/plugins.js" defer></script>
    <script src="/js/active.js" defer></script>
</body>

</html>
