<?php
/**
 * Template name: Homepage
 */
get_header();
?>
<main class="page-homepage">
    <?php echo do_shortcode('[featured_post]'); ?>
    <section class="posts-list-section container">
        <?php echo do_shortcode('[post_categories_list class="padding-top-30 padding-bottom-30 padding-top-xs-10 padding-bottom-xs-10 text-center" type="all"]'); ?>
        <?php echo do_shortcode('[posts_list class="padding-top-30 padding-bottom-50 padding-bottom-xs-0" posts_per_page="6"]'); ?>
    </section>
</main>
<?php get_footer(); ?>