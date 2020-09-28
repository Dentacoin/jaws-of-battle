<?php

/**
 * @see http://codex.wordpress.org/Creating_Tables_with_Plugins
 */
global $wpdb;

$installed_ver = get_option( "gurlpb_db_version" );

if ( $installed_ver != Gurlpb_config::$_VERSION ) {
    // Gurlpb_utils::log( __FILE__ . '[' . __LINE__ . ']: update database' );
}

if ( ! get_option( 'gurlpb-admin-id' ) ) {
    update_option( 'gurlpb-admin-id', get_current_user_id() );
    update_option( 'gurlpb-setting-a', 'manual' );
    update_option( 'gurlpb-hide-setting-a', 'true' );
    update_option( 'gurlpb-showexcerpt', '1' );
    update_option( 'gurlpb-boxes', '0' );

} elseif ( get_option( 'gurlpb-showexcerpt' ) !== '1' && get_option( 'gurlpb-showexcerpt' ) !== '0' ) {
    update_option( 'gurlpb-showexcerpt', '1' );
}
if ( ! get_option( 'gurlpb-random' ) ) {
    $str = base_convert( rand( 0, 1000000 ), 10, 36 ) . base_convert( rand( 0, 1000000 ), 10, 36 );
    update_option( 'gurlpb-random', sanitize_text_field( substr( $str, -8 ) ) );
}

// @see gute-url-preview-box.php 
set_transient( 'fx-admin-notice-example', true, 5 );
 
