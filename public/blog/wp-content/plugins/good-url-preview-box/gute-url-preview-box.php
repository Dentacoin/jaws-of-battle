<?php
/*
Plugin Name: URL-Preview-Box
Plugin URI: https://guteurls.de/
Description: This plugin adds link preview box to your post. You just copied & pasted an URL into your posting. :-)
Author: Martin Schaedler
Author URI: https://hypnose54321.de/frankfurt/_contact.html
Version: 1.20
Text Domain: gurlpb
*/

include_once ( plugin_dir_path( __FILE__ ) . 'gurlpb-config.php' );
include_once ( plugin_dir_path( __FILE__ ) . 'gurlpb-utils.php' );



function gurlpb_plugin_activated() {
    add_option( 'Activated_Plugin', 'gurlpb_plugin_activated' );
}
register_activation_hook( __FILE__, 'gurlpb_plugin_activated' );


function gurlpb_plugin_init() {
    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'gurlpb_plugin_activated' ) {
        delete_option( 'Activated_Plugin' );
        include_once ( plugin_dir_path( __FILE__ ) . 'gurlpb-activated.php' );
    }
}
add_action( 'admin_init', 'gurlpb_plugin_init' );


function gurlpb_plugin_deactivated() {
    Gurlpb_utils::log( "gurlpb_plugin_deactivated" );
    include_once ( plugin_dir_path( __FILE__ ) . 'gurlpb-deactivated.php' );
}
register_deactivation_hook( basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ), 'gurlpb_plugin_deactivated' );


function fx_admin_notice_example_notice(){

    /* Check transient, if available display notice */
    if( get_transient( 'fx-admin-notice-example' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p>DISPLAY Terms & Private Policies, and then FAQ</strong>.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'fx-admin-notice-example' );
    }
}
add_action( 'admin_notices', 'fx_admin_notice_example_notice' );


include_once ( plugin_dir_path( __FILE__ ) . 'gurlpb-start.php' );
include_once ( plugin_dir_path( __FILE__ ) . 'blocks/gurlpb.php' );