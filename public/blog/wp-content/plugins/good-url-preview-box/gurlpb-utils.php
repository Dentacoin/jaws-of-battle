<?php
include_once (  plugin_dir_path( __FILE__ ) . 'gurlpb-config.php' );


class Gurlpb_utils {


    static function define_constants() {
       Gurlpb_utils::log( "\n\n\n\n\n\n\n\n\n\n========= :-) " . __METHOD__ . " === NEW CALL ===" );

        $arr_url_parts = explode( "/", __FILE__ );

        $strName = $arr_url_parts[ count( $arr_url_parts ) -2 ]; // subdir name eg. 'gurlpb'  or 'bloggingForMoney'

        // absolute unix path
        define( 'GURLPB' , plugin_dir_path( __FILE__ ) );

        // absolute http path
        define( 'GURLPBPLUGINURL', plugins_url() . '/' . $strName );

        // first plugin file
        define( 'GURLPBGURLPB' , GURLPBPLUGINURL . "/$strName.php" );

        define( '__GURLPB' , 'gurlpb' ); // Dictionary / translation
    }

    static function log( $message ) {
        if ( WP_DEBUG === true && Gurlpb_config::$_DEBUG ) {
            if( is_array( $message ) || is_object( $message ) ){
                error_log( print_r( $message, true ) );
            } else {
                error_log( $message );
            }
        }
    }

    static function is_file( $strFile ) {
        if ( $strFile ) Gurlpb_utils::log( __METHOD__ . '[' . __LINE__ . "]: started ($strFile)" );
        return ( isset( $strFile ) && $strFile ) ? false : true;
    }

    static function is_url( $strUrl ) {
        if ( ! $strUrl ) return false;
        if ( preg_match('/[ \"\'\<\>\n\r\t]/' , $strUrl ) ) return false;
        if ( preg_match('/^https?:\/\/.*[a-z0-9]\.[a-z0-9]/i',  $strUrl ) ) return true;
        return false;
    }

}
Gurlpb_utils::log( __FILE__ );
