<?php

//ENABLE PAGE FEATURED IMAGE
add_theme_support('post-thumbnails');

//transliterating cyrillic to latin letters
function job_transliterate($str)    {
    return str_replace(array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я', ' '), array('a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya', '-'), $str);
}

//transliterate post_name on saving and editing posts
function job_transliterate_post_slug_on_save($post_ID, $post, $update) {
    $post->post_name = job_transliterate(mb_strtolower(urldecode($post->post_name)));

    remove_action('save_post', 'job_transliterate_post_slug_on_save', 10);
    wp_update_post($post);
    add_action('save_post', 'job_transliterate_post_slug_on_save', 10, 3);
}
add_action('save_post', 'job_transliterate_post_slug_on_save', 10, 3);


//transliterate post_name on saving and editing terms
function job_transliterate_term_slug_on_save($term_id, $tt_id, $taxonomy) {
    $term = get_term($term_id, $taxonomy);
    $initial_slug = $term->slug = urldecode($term->slug);
    $term->slug = mb_strtolower(job_transliterate($term->slug));

    if($initial_slug != $term->slug) {
        //remove action because it goes in infinite loop
        remove_action('edit_term', 'job_transliterate_term_slug_on_save', 10);
        wp_update_term($term_id, $taxonomy, array(
            'slug' => $term->slug
        ));
    }
    return true;
}
add_action('edit_term', 'job_transliterate_term_slug_on_save', 10, 3);
add_action('create_term', 'job_transliterate_term_slug_on_save', 10, 3);

//allow .svg ext upload in admin
function opt_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'opt_mime_types');

//allow .svg ext upload in admin
function job_custom_upload_mimes($existing_mimes = array()) {
    // add the file extension to the array
    $existing_mimes['svg'] = 'mime/type';
    // call the modified list of extensions
    return $existing_mimes;
}
add_filter('upload_mimes', 'job_custom_upload_mimes');

//Stops the WP api requests for retrieving the users data
add_filter('rest_endpoints', function($endpoints){
    if (isset( $endpoints['/wp/v2/users'])) {
        unset( $endpoints['/wp/v2/users']);
    }
    if (isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

//removing wordpress version from wp_head
remove_action('wp_head', 'wp_generator');

//removing qTranslate plugin version from wp_head
remove_action('wp_head','qtranxf_wp_head_meta_generator');

function getDateFormatted($date) {
    setlocale(LC_ALL, 'en_EN');
    return mb_convert_encoding(strftime('%B %d %Y', strtotime($date)), 'utf-8', 'Windows-1251');
}

//ADD VISIBLE FEATURED VALUE FOR POSTS LIST IN ADMIN
function job_posts_featured_th($defaults) {
    $defaults['featured'] = 'Featured';
    return $defaults;
}

function job_posts_featured_td_content($column_name, $post_ID) {
    if ($column_name == 'featured') {
        $visibility_checkbox = get_post_meta($post_ID, 'wpcf-featured', true);
        if((int)$visibility_checkbox == true)   {
            echo 'Yes';
        }else {
            echo 'No';
        }
    }
}
add_filter('manage_posts_columns', 'job_posts_featured_th');
add_action('manage_posts_custom_column', 'job_posts_featured_td_content', 10, 2);

add_action( 'pre_get_posts', 'job_pre_get_posts' );
function job_pre_get_posts( $query ) {
    if ( ! $query->is_main_query() || $query->is_admin() )
        return false;

    if ( $query->is_category() ) {
        $query->set('post_type', 'post');
        $query->set('posts_per_page', -1);
    }
    return $query;
}

function job_custom_widget_setup() {
    register_sidebar(
        array(
            'name' => 'AddToAny widget',
            'id' => 'add-to-any-widget',
            'class' => 'add-to-any-widget',
            'description' => 'Standard Sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h1 class="widget-title">',
            'after_title' => '</h1>'
        )
    );
}
add_action('widgets_init', 'job_custom_widget_setup');