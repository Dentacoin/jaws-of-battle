<?php
get_header();
$category = get_queried_object();
if (!empty($category)) {
    ?>
    <main class="page-homepage">
        <?php echo do_shortcode('[featured_post]'); ?>
        <section class="posts-list-section container">
            <div class="row categories-list">
                <div class="col-xs-12">

                </div>
            </div>
            <?php echo do_shortcode('[post_categories_list class="padding-top-30 padding-bottom-30 text-center" type="'.$category->slug.'"]'); ?>
            <?php echo do_shortcode('[posts_list class="padding-top-30 padding-bottom-50" posts_per_page="6" category="'.$category->slug.'"]'); ?>
        </section>
    </main>
    <?php get_footer();
} else {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
}