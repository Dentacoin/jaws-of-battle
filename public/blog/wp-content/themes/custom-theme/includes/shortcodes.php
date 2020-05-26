<?php

function postsList($attrs = [])   {
    global $wp_query;
    if (empty($attrs['posts_per_page'])) {
        $attrs['posts_per_page'] = 6;
    }

    ob_start();
    global $post;
    ?>
    <div class="row shortcode posts-list fs-0 <?php echo $attrs['class']; ?>">
        <?php
        if (!empty(get_query_var('page'))) {
            $current_page = get_query_var('page');
        } else if (!empty($wp_query->query['paged'])) {
            $current_page = $wp_query->query['paged'];
        } else {
            $current_page = 1;
        }

        $wpQueryArgs = array(
            'post_type' => 'post',
            'posts_per_page' => $attrs['posts_per_page'],
            'paged' => $wp_query->query['paged']
        );
        if (!empty($attrs['category'])) {
            $wpQueryArgs['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $attrs['category']
                )
            );
        }

        $query = new WP_Query($wpQueryArgs);
        $posts = $query->posts;

        foreach($posts as $post) {
            ?>
            <div class="col-xs-12 col-sm-4 padding-bottom-50 inline-block-top">
                <?php get_template_part('listing', 'post'); ?>
            </div>
            <?php
        }
        wp_reset_query();

        echo '<nav class="module module-pagination fs-20 lato-black padding-top-30">';
        echo paginate_links(array(
            'total' => $query->max_num_pages,
            'current' => $current_page,
            'prev_text' => __(' <i class="fa fs-20 line-height-30 fa-angle-double-left"></i> '),
            'next_text' => __(' <i class="fa fs-20 line-height-30 fa-angle-double-right"></i> '),
            'type' => 'list',
            'end_size' => 3,
            'mid_size' => 2,
        ));
        echo '</nav>';
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('posts_list', 'postsList');

function postCategoriesList($attrs = [])   {
    ob_start();
    $categories = get_categories(array(
        'hide_empty' => false,
        'taxonomy' => 'category'
    ));

    if (!empty($categories)) {
        ?>
        <nav class="shortcode categories-list <?php echo $attrs['class']; ?>" id="categories-list">
            <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                <li class="inline-block-top">
                    <a href="<?php echo site_url(); ?>#categories-list" <?php if (!empty($attrs['type']) && $attrs['type'] == 'all') { ?> class="active" <?php } ?> itemprop="url"><span itemprop="name">ALL</span></a>
                </li>
                <?php
                foreach ($categories as $category) {
                    $termMeta = get_term_meta($category->term_id, null, true);
                    ?>
                        <li class="inline-block-top">
                            <a style="background-color: <?php echo $termMeta['wpcf-category-color'][0]; ?>;" href="<?php echo get_category_link($category->term_id); ?>#categories-list" <?php if (!empty($attrs['type']) && $attrs['type'] == $category->slug) { ?> class="active" <?php } ?> itemprop="url"><span itemprop="name"><?php echo $category->name; ?></span></a>
                        </li>
                    <?php
                }
                ?>
            </ul>
        </nav>
        <?php
    }

    return ob_get_clean();
}
add_shortcode('post_categories_list', 'postCategoriesList');

function getFeaturedPost($attrs = [])   {
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'meta_key' => 'wpcf-featured',
        'meta_value'  => true
    ));
    $posts = $query->posts;

    if (!empty($posts)) {
        ?>
        <section class="featured-post-container padding-top-90 padding-bottom-50 padding-bottom-xs-0">
            <div class="container">
                <div class="row desktop-title">
                    <div class="col-xs-12">
                        <h1 class="fs-40 fs-xs-26 lato-black text-center color-white padding-top-15 padding-bottom-30">THE PLACE FOR REAL DENTAWARRIORS</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-offset-1 featured-post">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="featured-post-image">
                            <img data-defer-src="<?php echo get_the_post_thumbnail_url($posts[0]); ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id($posts[0]), '_wp_attachment_image_alt', true); ?>"/>
                        </figure>
                        <a href="<?php echo get_permalink($posts[0]->ID); ?>" class="post-tile module featured">
                            <?php
                            $categories = get_the_category($posts[0]->ID);
                            if (!empty($categories)) {
                                $termMeta = get_term_meta($categories[0]->term_id, null, true);
                                ?>
                                <div style="background-color: <?php echo $termMeta['wpcf-category-color'][0]; ?>;" class="category-line padding-top-5 padding-bottom-5 text-center fs-20 lato-bold color-white"><?php echo $categories[0]->name; ?></div>
                                <?php
                            }
                            ?>
                            <div class="info-body padding-top-15 padding-bottom-20 padding-left-30 padding-right-30">
                                <h2 class="lato-black fs-24 fs-sm-18 fs-xs-20 line-height-28 padding-bottom-10 color-black"><?php echo $posts[0]->post_title; ?></h2>
                                <p class="fs-16 padding-bottom-15"><?php echo mb_substr(strip_tags($posts[0]->post_content), 0, 100); ?>...</p>
                                <div class="link-and-date fs-0">
                                    <span class="inline-block link fs-16">Read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                                    <span class="inline-block fs-16 date text-right"><?php echo getDateFormatted($posts[0]->post_date); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mobile-title">
                    <div class="col-xs-12">
                        <h1 class="fs-40 fs-xs-26 lato-black text-center color-black padding-top-25 padding-bottom-15">THE PLACE FOR REAL DENTAWARRIORS</h1>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }

    wp_reset_query();
    return ob_get_clean();
}
add_shortcode('featured_post', 'getFeaturedPost');