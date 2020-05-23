<?php
get_header();
?>

    <section class="padding-top-50 padding-bottom-100 blog-container">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="roboto-bold fs-36">БЛОГ</h1>
                    <p class="fs-20 roboto-bold gray-color padding-top-10 padding-bottom-10">Бъдете винаги информирани с последните дентални тенденции</p>
                </div>
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="row">
                        <div class="col-xs-12 col-md-4 sidebar">
                            <?php echo do_shortcode('[blog_sidebar]'); ?>
                        </div>
                        <div class="col-xs-12 col-md-8 posts-list">
                            <?php
                            foreach($posts as $post) {
                                ?>
                                <div class="col-xs-12 col-sm-4 padding-bottom-50 inline-block-top">
                                    <?php get_template_part('listing', 'post'); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
get_footer(); ?>