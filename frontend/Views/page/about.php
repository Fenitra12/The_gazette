<!-- Breadcrumb Area Start -->
<div class="breadcumb-area section_padding_50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breacumb-content d-flex align-items-center justify-content-between">
                    <h1 class="font-pt mb-0">About Us</h1>
                    <p class="editorial-post-date text-dark mb-0"><?= date('d F Y') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<section class="gazette-about-us-area section_padding_100_70">
    <div class="about-us-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="font-pt mb-30">Short History</h2>
                </div>
                <div class="col-12 col-md-6">
                    <p>TheGazette was founded with the mission of providing in-depth, reliable coverage of international conflicts and geopolitical events. Our team of experienced journalists and analysts brings you comprehensive reporting on the Iran conflict and Middle East tensions.</p>
                </div>
                <div class="col-12 col-md-6">
                    <p>We believe in the power of informed journalism to shape public understanding and promote dialogue. Our coverage spans diplomacy, humanitarian issues, economic impacts, and security matters related to the ongoing geopolitical crisis.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="team-area mt-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="font-pt mb-50">Our Team</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="single-team-area">
                        <?= img('bg-img/t1.jpg', 'Team member - Editor', 255) ?>
                        <div class="team-member-data">
                            <h3 class="font-pt">Jane Doe</h3>
                            <div class="team-member-designation-social-info d-flex align-items-cente justify-content-between">
                                <p class="font-pt mb-0">Editor</p>
                                <div class="social-info">
                                    <a href="#" aria-label="Jane Doe sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Jane Doe sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Jane Doe sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Jane Doe sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="single-team-area">
                        <?= img('bg-img/t2.jpg', 'Team member - Reporter', 255) ?>
                        <div class="team-member-data">
                            <h3 class="font-pt">John Smith</h3>
                            <div class="team-member-designation-social-info d-flex align-items-cente justify-content-between">
                                <p class="font-pt mb-0">Reporter</p>
                                <div class="social-info">
                                    <a href="#" aria-label="John Smith sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="John Smith sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="John Smith sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="John Smith sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="single-team-area">
                        <?= img('bg-img/t3.jpg', 'Team member - Analyst', 255) ?>
                        <div class="team-member-data">
                            <h3 class="font-pt">Sarah Johnson</h3>
                            <div class="team-member-designation-social-info d-flex align-items-cente justify-content-between">
                                <p class="font-pt mb-0">Analyst</p>
                                <div class="social-info">
                                    <a href="#" aria-label="Sarah Johnson sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Sarah Johnson sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Sarah Johnson sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Sarah Johnson sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="single-team-area">
                        <?= img('bg-img/t4.jpg', 'Team member - Photographer', 255) ?>
                        <div class="team-member-data">
                            <h3 class="font-pt">Mike Brown</h3>
                            <div class="team-member-designation-social-info d-flex align-items-cente justify-content-between">
                                <p class="font-pt mb-0">Photographer</p>
                                <div class="social-info">
                                    <a href="#" aria-label="Mike Brown sur Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Mike Brown sur LinkedIn"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Mike Brown sur Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="Mike Brown sur Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="gazette-cta-area bg-img background-overlay section_padding_100" style="background-image: url(<?= resized('blog-img/cta.jpg', 1200, 500) ?>);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="cta-content text-center">
                    <h2 class="font-pt">Join Our Team</h2>
                    <p>We are always looking for talented journalists, analysts, and photographers to join our mission of delivering quality news coverage.</p>
                    <a href="/contact" class="btn gazette-btn font-pt">Contact Us <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
