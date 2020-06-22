<?php
get_header();
$post_data = get_post_meta($post->ID);
$thisPost = $post;
?>
    <main class="single-post-container">
        <section class="section-post-image padding-top-120">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="post-image">
                            <img data-defer-src="<?php echo get_the_post_thumbnail_url($post); ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id($post), '_wp_attachment_image_alt', true); ?>"/>
                        </figure>
                    </div>
                </div>
            </div>
            <?php
            $categories = get_the_category($post->ID);
            if (!empty($categories)) {
                $termMeta = get_term_meta($categories[0]->term_id, null, true);
                ?>
                <div style="background-color: <?php echo $termMeta['wpcf-category-color'][0]; ?>;" class="padding-top-10 padding-bottom-10 padding-left-15 padding-right-15 fs-20 color-white text-center"><span class="lato-black"><?php echo $categories[0]->name; ?></span> - <span class="lato-bold"><?php echo getDateFormatted($post->post_date); ?></span></div>
                <?php
            }
            ?>
        </section>
        <section class="container-fluid section-post-content padding-bottom-50">
            <div class="row breadcrumbs">
                <div class="col-xs-12 col-md-10 col-md-offset-1 lato-bold">
                    <nav>
                        <ul itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li class="inline-block" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a href="<?php echo site_url(''); ?>" itemscope="" itemtype="http://schema.org/Thing" itemprop="url"><span itemprop="title">HOME</span></a></li>

                            <?php
                            if (!empty($categories)) {
                                ?>
                                <li class="inline-block" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a href="<?php echo get_category_link($categories[0]->term_id); ?>#categories-list" itemscope="" itemtype="http://schema.org/Thing" itemprop="url"><span itemprop="title"><?php echo $categories[0]->name; ?></span></a></li>
                                <?php
                            }
                            ?>
                            <li class="inline-block" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="title"><?php echo mb_substr(strip_tags($post->post_title), 0, 15); ?>...</span><meta itemprop="url" content="CURRENT URL"></li></ul>
                    </nav>
                </div>
            </div>
            <div class="row padding-top-20 add-display-flex-and-position-relative fs-0">
                <div class="col-xs-3 sticky-socials inline-block-top fs-16">
                    <?php dynamic_sidebar('add-to-any-widget'); ?>
                </div>
                <div class="col-xs-12 col-md-6 post-content inline-block-top">
                    <h1 class="fs-40 fs-xs-36 text-center lato-black padding-bottom-30"><?php echo $post->post_title; ?></h1>
                    <div class="padding-bottom-50 fs-16 post-content-field">
                        <?php
                        if(have_posts()) :
                            while(have_posts()) :
                                the_post();
                                the_content();
                            endwhile;
                        endif;
                        ?>
                    </div>
                    <hr>
                    <div class="socials padding-top-5 padding-bottom-5">
                        <h4 class="fs-16 padding-bottom-5 lato-bold">Share this post on:</h4>
                        <?php dynamic_sidebar('add-to-any-widget'); ?>
                    </div>
                    <?php
                    if (!empty($categories)) {
                        ?>
                        <div class="categories padding-top-5 padding-bottom-5">
                            <h4 class="fs-16 lato-bold inline-block padding-right-5">Categories:</h4>
                            <nav class="inline-block">
                                <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                                    <?php
                                        foreach ($categories as $category) {
                                            $termMeta = get_term_meta($category->term_id, null, true);
                                            ?>
                                            <li class="inline-block-top">
                                                <a href="<?php echo get_category_link($category->term_id); ?>#categories-list" itemprop="url" style="background-color: <?php echo $termMeta['wpcf-category-color'][0]; ?>;"><span itemprop="name"><?php echo $category->name; ?></span></a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="padding-top-5 padding-bottom-5">
                        <h4 class="fs-16 lato-bold inline-block padding-right-5">Published by:</h4>
                        <span class="inline-block fs-16 author-name"><?php the_author_meta( 'display_name', $post->post_author ); ?></span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 inline-block-top sticky-categories fs-16">
                    <div class="categories-wrapper">
                        <?php
                        $allCategories = get_categories(array(
                            'hide_empty' => false,
                            'taxonomy' => 'category'
                        ));

                        if (!empty($allCategories)) {
                            ?>
                            <div class="padding-bottom-20 categories">
                                <h4 class="fs-18 lato-bold padding-bottom-15">CATEGORIES:</h4>
                                <nav>
                                    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                                        <li>
                                            <a href="<?php echo site_url(); ?>#categories-list" class="fs-16" itemprop="url"><span itemprop="name">ALL</span></a>
                                        </li>
                                        <?php
                                        foreach ($allCategories as $allCategory) {
                                            ?>
                                            <li>
                                                <a href="<?php echo get_category_link($allCategory->term_id); ?>#categories-list" class="fs-16" itemprop="url"><span itemprop="name"><?php echo $allCategory->name; ?></span></a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="newsletter-tile color-white">
                            <div id="mc_embed_signup">
                                <form action="https://dentacoin.us16.list-manage.com/subscribe/post?u=61ace7d2b009198ca373cb213&amp;id=aa76d21410" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                    <div id="mc_embed_signup_scroll">
                                        <label for="mce-EMAIL" class="fs-24 lato-bold color-white">Subscribe to our newsletter</label>
                                        <div class="padding-top-10 padding-bottom-10">
                                            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Enter your email" required>
                                        </div>
                                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_61ace7d2b009198ca373cb213_aa76d21410" tabindex="-1" value=""></div>
                                        <div class="fs-14 lato-light padding-bottom-10">By subscribing to our newsletter, you agree to Dentacoin B.V. Privacy Policy.</div>
                                        <div class="clear">
                                            <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button lato-bold fs-16">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        if (!empty($categories)) {
            $wpQueryArgs = array(
                'post_type' => 'post',
                'posts_per_page' => 5,
                'post__not_in' => array($thisPost->ID),
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $categories[0]->slug
                    )
                )
            );

            $query = new WP_Query($wpQueryArgs);
            $relatedPosts = $query->posts;
            if (!empty($relatedPosts)) {
                ?>
                <section class="container section-related-posts">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="title-row padding-bottom-40 padding-top-30 padding-left-20 padding-right-20">
                                <h3 class="lato-bold fs-24 padding-right-10"><span class="inline-block"></span> RELATED POSTS</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="related-posts-slider">
                                <?php

                                foreach($relatedPosts as $post) {
                                    ?>
                                    <div class="padding-left-15 padding-right-15 padding-bottom-20">
                                        <?php get_template_part('listing', 'post'); ?>
                                    </div>
                                    <?php
                                }
                                wp_reset_query();
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row show-all">
                        <div class="col-xs-12 text-center padding-top-30 padding-bottom-50">
                            <a href="<?php echo get_category_link($categories[0]->term_id); ?>#categories-list" class="fs-18 lato-bold">Show all</a>
                        </div>
                    </div>
                </section>
                <?php
            }
        }
        ?>
    </main>
<?php get_footer(); ?>