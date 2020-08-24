<?php

function job_add_styles_and_scripts() {
    //wp_enqueue_style('bootstrap-style',get_template_directory_uri() . '/assets/libs/bootstrap-3.3.7-dist/css/bootstrap.min.css', array(), '1.0.0', 'all');
    wp_enqueue_style('bootstrap-style',get_template_directory_uri() . '/assets/libs/bootstrap/css/bootstrap.min.css', array(), '1.0.0', 'all');

    wp_enqueue_style('slick-style',get_template_directory_uri() . '/assets/libs/slick-1.8.1/css/slick.css', array(), '1.0.0', 'all');

    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css', array(), '1.0.0', 'all');

    wp_enqueue_style('custom_style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.1', 'all' );

    wp_enqueue_style('custom_dir_style', get_template_directory_uri() . '/style.css', array(), '1.0.1', 'all' );


    //wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/assets/libs/bootstrap-3.3.7-dist/js/bootstrap.min.js', array('jquery'));
    wp_enqueue_script('bootstrap-script', get_template_directory_uri() . '/assets/libs/bootstrap/js/bootstrap.min.js', array('jquery'));

    wp_enqueue_script('slick-script', get_template_directory_uri() . '/assets/libs/slick-1.8.1/slick/slick.min.js', array( 'jquery' ) );

    wp_enqueue_script( 'bootbox_script', get_stylesheet_directory_uri(). '/assets/libs/bootbox.min.js', array(), false, true );

    wp_enqueue_script('custom_script_helper', get_template_directory_uri() . '/assets/js/basic.js', array(), '1.0.1', true);

    wp_enqueue_script('custom_script', get_template_directory_uri() . '/assets/js/index.js', array(), '1.0.1', true);

    if(empty($_COOKIE['performance_cookies']) && empty($_COOKIE['functionality_cookies']) && empty($_COOKIE['marketing_cookies']) && empty($_COOKIE['strictly_necessary_policy'])) {
        wp_enqueue_style('combined_cookie_style', 'https://dentacoin.com/assets/libs/dentacoin-package/css/style-cookie.css', array(), time(), 'all' );

        wp_enqueue_script('combined_cookie_script', 'https://dentacoin.com/assets/libs/dentacoin-package/js/init.js', array(), time(), true);
    }

    wp_localize_script( 'custom_script', 'MyAjax',
        array(
            // URL to wp-admin/admin-ajax.php to process the request
            'ajaxurl'          => admin_url( 'admin-ajax.php' ),

            // generate a nonce with a unique ID "myajax-post-comment-nonce"
            // so that you can check it later when an AJAX request is sent
            'postCommentNonce' => wp_create_nonce( 'myajax-post-comment-nonce' ),
        )
    );
}
add_action('wp_enqueue_scripts', 'job_add_styles_and_scripts');