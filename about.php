<?php
ob_start();
session_start();
$pageTitle = 'about';
include "init.php";

?>
<div class="hero-wrap hero-wrap-2 content service-image"
     data-stellar-background-ratio="0.5">
    <div class="service-name">
        <?= $pageTitle ?>
    </div>
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start">
            <div class="col-md-8 ftco-animate text-center text-md-left mb-5">
                <p class="breadcrumbs mb-0"><span class="mr-4"><a style="color: white;">Home<i
                                    class="ml-3 fas fa-angle-double-right	"></i></a></span> <a
                            href="about.php">عن مستر ايمن</a></p>
            </div>
        </div>
    </div>
</div>
<section class="ftco-section contact-section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-6 col-md-6">
                <h2 class="h2 mt-5">من هو مستر ايمن</h2>
                <p class="about">Enjaz Fawry is a one stop business solution who believes in ourselves and with our
                    experience over the
                    years in this business we provide professional and excellent service for setting up a new business
                    and
                    incorporation of new companies and related matters in Mainland and in various Free Zones
                    Authority.</p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <img src="layouts/img/6.jpg" class="img-fluid mt-5">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h2 class="h2 mt-5">ما يمكننا فعله</h2>
                <p class="about">Enjaz Fawry have always valued our clients and guided them to choose the right location
                    for setting up a new business and incorporate company complying with all legal procedures from
                    various authorities. Whether it is in Mainland or Free Zones we assist clients for new company
                    incorporation in UAE as we are highly experienced and professionals with cost effective price
                    structure for all type of services rendered to our clients. Keeping In mind most of the clients are
                    from across the globe, all services are given with professional standards, abiding by the most
                    efficient, economical and accurate method.
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h2 class="h2 mt-5">ليه تختار مستر ايمن</h2>
                <p class="about">Enjaz Fawry have always thrived to improve the quality and productivity at
                    International standards while engaging with Clients in incorporating new Company and rendering
                    various other PRO related services. We not only assist in setting up a license but we also advise
                    clients globally to be an advisory partner for incorporation of new license in UAE. With our Local
                    Partner who is our pillar have always being at our side in giving guidance and vast knowledge which
                    in turn we share to our clients.
            </div>
        </div>

    </div>
</section>


<?php include $tpl . 'footer.php'; ?>
