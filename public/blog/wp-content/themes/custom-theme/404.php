<?php
get_header();
?>
    <section class="padding-top-100 padding-bottom-150">
        <figure itemscope="" itemtype="http://schema.org/ImageObject">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/404-page.svg" alt="404 image"/>
        </figure>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="padding-bottom-20 fs-20 lato-bold">PAGE NOT FOUND</div>
                    <div><a href="/" class="lato-bold fs-26">GO BACK TO HOME</a></div>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>