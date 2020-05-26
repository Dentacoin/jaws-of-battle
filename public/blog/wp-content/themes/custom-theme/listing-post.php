<a href="<?php echo get_permalink($post->ID); ?>" class="post-tile module">
    <?php
        $categories = get_the_category($post->ID);
        if (!empty($categories)) {
            $termMeta = get_term_meta($categories[0]->term_id, null, true);
            ?>
                <div style="background-color: <?php echo $termMeta['wpcf-category-color'][0]; ?>;" class="category-line padding-top-5 padding-bottom-5 text-center fs-20 lato-bold color-white"><?php echo $categories[0]->name; ?></div>
            <?php
        }
    ?>
    <div class="info-body padding-bottom-15">
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="width-100 fixed-image-height">
            <img data-defer-src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php echo get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true); ?>"/>
        </figure>
        <div class="padding-right-15 padding-left-15">
            <h3 class="lato-black fs-20 line-height-24 padding-bottom-10 padding-top-10"><?php echo $post->post_title; ?></h3>
            <p class="fs-16 padding-bottom-10"><?php echo mb_substr(strip_tags($post->post_content), 0, 100); ?>...</p>
            <div class="link-and-date fs-0">
                <span class="inline-block link fs-16">Read more <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                <span class="inline-block fs-16 date text-right"><?php echo getDateFormatted($post->post_date); ?></span>
            </div>
        </div>
    </div>
</a>
