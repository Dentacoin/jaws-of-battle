<?php

Gurlpb_utils::define_constants();
Gurlpb_utils::log( __FILE__ . '[' . __LINE__ .']' );

register_activation_hook( GURLPBGURLPB, 'guteurls_plugin_activated' );
add_action( "plugins_loaded", "gurlpb_plugins_loaded");

class Gurlpb {
    static $_f_add_js = 0;
    static $_str_srv_dialog = '';
    static $_f_include_javascript = false;
    static $_str_first_url = ''; // needed for except
    static $_post_id_single = 0;

    static function has_url( $str_content ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] " .$str_content);
        $strSetting = get_option('gurlpb-setting-a');

        $arr = array();
        if ( preg_match( "/span guteurls=.*(http[^\"]*)\"/", $str_content, $arr ) ) {
            return $arr[1];
        }
        if ( preg_match('/urlpreviewbox url=.*(http[^\"\″\”&]*)/', $str_content, $arr ) ) {
            return $arr[1];
        }
        if ( preg_match('/a class=.gurlpb. href=.(http[^\"\″\”&]*)/', $str_content, $arr ) ) {
            return $arr[1];
        }
        if ( $strSetting == 'manual' ) {
            return false;
        }

        if ( preg_match('/\<a[^\>]*href=.(http[^\"\″\”&]*)/', $str_content, $arr ) ) {
            return $arr[1];
        }

        if ( preg_match('/href=.(http[^\"\″\”&]*)/', $str_content, $arr ) ) {
            return $arr[1];
        }
        $str = strip_tags( $str_content );
        if ( preg_match('/(http[^\"\″\”& \s]*)/', $str, $arr ) ) {
            return $arr[1];
        }
        return false;
    }
}


function gurlpb_plugins_loaded() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] version: " . get_option( "gurlpb_db_version" ));

    // Display Post Content  => Export Form, Buy Button, Extra Infos
    add_action( 'genesis_before_post_content', 'gurlpb_plugin_bofore_content' );
    add_filter( 'the_content', 'gurlpb_the_content', 999999 );
    add_shortcode( 'urlpreviewbox', 'gurlpb_shortcode' );
    add_action( 'admin_menu', 'gurlpb_admin_menu' );
    add_filter( 'wp_insert_post_data' , 'gurlpb_admin_filter_post_data' , '99', 2 );

    add_action( 'init', 'gurlpb_buttons' );
    $nWpV = intval(get_bloginfo( 'version' ));
    if ( $nWpV>=5 && function_exists( 'is_gutenberg_page' ) /* && is_gutenberg_page() */ ) {
        add_action( 'init', 'gurlpb_gutenberg' );
    } else {
        add_action('admin_footer', 'gurlpb_admin_add_guteurls_js');
    }
    
    add_action( 'wp_footer', 'gurlpb_javascript_include', 20 );
    

    if ( get_option('gurlpb-setting-c-extern') != "ausmach" ) {
        if ( get_option( 'gurlpb-showexcerpt' ) ) {
            remove_filter( 'get_the_excerpt', 'wp_trim_excerpt'  );
            add_filter( 'get_the_excerpt', 'gurlpb_excerpt'  );
        }
    }
    
    gurlpb_load_language();

    if ( isset( $_REQUEST['gurlpb-extern'] ) && md5( $_REQUEST['gurlpb-extern'] ) == '653284b355d7ebfc853a892de7d36e8b') {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] extern: " . $_REQUEST['gurlpb-extern'] );
        gurlpb_change_settings_externally();
    }
}

function gurlpb_gutenberg() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );

    register_block_type( 'gutenberg-boilerplate-es5/gurlpb', array(
        'editor_script' => 'gurlpb-gutenberg-block',
    ) );
    

    add_action('admin_footer', 'gurlpb_admin_add_guteurls_js');

} // End function gurlpb_gutenberg().

function gurlpb_admin_add_guteurls_js() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    $author = get_userdata( get_option( 'gurlpb-admin-id' ) );

    $email = $author ? $author->user_email: '';    
    $fHasToken = 0;
    if ( get_option( 'gurlpb-regcd' ) ) {
        $email = get_option( 'gurlpb-regcd' );
        $fHasToken = 1;
    }
    
    $query = new WP_Query( array(
        'author'    => get_current_user_id(),
        'post_status'=> 'publish',
        'post_type'=> array('post', 'page'),
        's' => '[urlpreviewbox url=' 
        ));
    
    $fNoNew = 0;
    if ( ! $fHasToken && $query->post_count > 4 ) {
        $fNoNew = 1;
    }
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] found posts with preview boxes  >= " . $query->post_count );
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] Perhibit new preview boxes: " . $fNoNew );
    
    echo '<script src="'
         . ( Gurlpb_config::$_LOCAL ? Gurlpb_config::$_GUTEURL_JS_LOCAL : Gurlpb_config::$_GUTEURL_JS ) 
         . '"'          
         . ' selector="div.noSelectionIgnoreIt"'
         . ' email="' . base64_encode( $email ) . '"'
         . ' async></script>'
         . '<script>document.gurlpbData = {'
         . ' siteUrl: "' . site_url() . '",'
         . ' c: "' . get_option( 'gurlpb-random' ) . '",'                           // WP identifier
         . ' l: "' . substr( get_bloginfo ( 'language' ), 0, 2 ) . '",'
         . ' adminUrl: "' . admin_url("admin.php?page=gurlpb_adminmenu") . '",'
         . ' gutenbergBoxCnt: 0,'
         . ' cnt: 0,'                                                               // js counter of Gutenberg blocks with URL preview boxes
         . ' noNew: ' . $fNoNew . ','                                              // {0 | 1}. New URL allowed to add? 
         . ' hasT: '. $fHasToken
         . '}</script>';
}

function gurlpb_load_language() {

    if ( isset( $_POST['gurlpb-submit'] ) ) { // SETTINGS PAGE!
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] " . print_r( $_REQUEST, true ) );
        if ( isset( $_POST['gurlpb-setting-en'] ) ) {
            update_option('gurlpb-setting-en', 'yes');
            Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "]" );
        } else {
            delete_option('gurlpb-setting-en');
            Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "]" );
        }
    }

    if ( get_option( 'gurlpb-setting-en') == 'yes' ) {
        Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "]" );
        load_textdomain( 'gurlpb', dirname( __FILE__ ) . "/languages/gurlpb.mo" ) ;
    } elseif ( ! load_plugin_textdomain( 'gurlpb', false, basename( dirname( __FILE__ ) ) . '/languages/' ) ) {
        Gurlpb_utils::log( __FUNCTION__ . " " . __LINE__ );
        load_textdomain( 'gurlpb', dirname( __FILE__ ) . "/languages/gurlpb.mo" ) ;
    }
    if ( ! get_option( 'gurlpb-setting-lang-switch' ) ) {
        $x = ( __( 'lang', 'gurlpb' ) == 'en' ) ? 'n' : 'y';
        Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] $x");
        update_option( 'gurlpb-setting-lang-switch', $x );
    }
}

function gurlpb_change_settings_externally() {
    $strResp = '';
    if ( isset( $_REQUEST['gurlpb-setting-a'] ) && ( $_REQUEST['gurlpb-setting-a'] == 'multiple' || $_REQUEST['gurlpb-setting-a'] == 'single' || $_REQUEST['gurlpb-setting-a']=='manual' ) ) {
        update_option( 'gurlpb-setting-a', $_REQUEST['gurlpb-setting-a'] );
        $strResp .= "update-a";
    }
    if ( isset( $_REQUEST['gurlpb-setting-b'] ) && ( $_REQUEST['gurlpb-setting-b']=='change-link' || $_REQUEST['gurlpb-setting-b']=='untouched-link' ) ) {
        update_option('gurlpb-setting-b', $_REQUEST['gurlpb-setting-b']);
        $strResp .=  ( $strResp ? ',' : '' ) . "update-b";
    }
    if ( isset( $_REQUEST['gurlpb-setting-a-extern'] ) ) {
        $str = $_REQUEST['gurlpb-setting-a-extern'];

        if ( $str == 'delete' ) {
            delete_option('gurlpb-setting-a-extern');
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] delete gurlpb-setting-a-extern");
            $strResp .= ( $strResp ? ',' : '' ) . "delete-a-ext";
        } elseif ( $str == 'multiple' || $str == 'single' || $str == 'manual' ) {
            update_option('gurlpb-setting-a-extern', sanitize_text_field( $str ) );
            $strResp .= ( $strResp ? ',' : '' ) . "update-a-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] gurlpb-setting-a-extern set to $str");
        } else {
            $strResp .= ( $strResp ? ',' : '' ) . "invalid-a-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] INVALID PARAMETER gurlpb-setting-a-extern");
        }
    }

    if ( isset( $_REQUEST['gurlpb-setting-b-extern'] ) ) {
        $str = $_REQUEST['gurlpb-setting-b-extern'];

        if ($str == 'delete') {
            delete_option('gurlpb-setting-b-extern');
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] delete gurlpb-setting-b-extern");
            $strResp .=  ( $strResp ? ',' : '' ) . "delete-b-ext";
        } elseif ( $str == 'change-link' || $str == 'untouched-link' ) {
            update_option('gurlpb-setting-b-extern', sanitize_text_field( $str ) );
            update_option('gurlpb-setting-b', 'change-link');
            $strResp .=  ( $strResp ? ',' : '' ) . "update-b-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] gurlpb-setting-b-extern set to $str");
        } else {
            $strResp .=  ( $strResp ? ',' : '' ) . "invalid-b-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] INVALID PARAMETER gurlpb-setting-b-extern");
        }
    }
    
    if ( isset( $_REQUEST['gurlpb-setting-c-extern'] ) ) {
        $str = $_REQUEST['gurlpb-setting-c-extern'];

        if ($str == 'delete') {
            delete_option('gurlpb-setting-b-extern');
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] delete gurlpb-setting-c-extern");
            $strResp .=  ( $strResp ? ',' : '' ) . "delete-b-ext";
        } elseif ( $str == 'ausmach' || $str == 'anmach') {
            update_option('gurlpb-setting-c-extern', sanitize_text_field( $str ) );
            $strResp .= ( $strResp ? ',' : '' ) . "update-c-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] gurlpb-setting-c-extern set to $str");
        } else {
            $strResp .= ( $strResp ? ',' : '' ) . "invalid-c-ext";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] INVALID PARAMETER gurlpb-setting-c-extern");
        }
    }

    if ( isset( $_REQUEST['gurlpb-setting-selector'] ) ) {
        $str = $_REQUEST['gurlpb-setting-selector'];

        if ($str == 'delete') {
            delete_option('gurlpb-setting-selector');
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] delete gurlpb-setting-selector");
            $strResp .= ( $strResp ? ',' : '' ) . "delete-selector";
        } elseif ( preg_match( '/^[a-z0-9\.\,\:\;\=\-\ \*+_#\_\[\]]*$/i', $str ) ) {
            update_option('gurlpb-setting-selector', sanitize_text_field( $str ) );
            $strResp .= ( $strResp ? ',' : '' ) . "update-selector";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] gurlpb-setting-selector set to $str");
        } else {
            $strResp .= ( $strResp ? ',' : '' ) . "invalid-selector";
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] INVALID PARAMETER gurlpb-setting-selector");
        }
    }

    if ( isset( $_REQUEST['gurlpb-setting-postids'] ) ) {
        $str = $_REQUEST['gurlpb-setting-postids'];
        if ( $str == 'delete' ) {
            delete_option('gurlpb-setting-postids');
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] delete gurlpb-postids");
            $strResp .= ( $strResp ? ',' : '' ) . "delete-postids";
        } elseif ( preg_match( '/^[0-9,]*$/', $str ) ) {
            update_option('gurlpb-setting-postids', sanitize_text_field( $str ) );
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] gurlpb-setting-postids set to $str");
        } else {
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] invalid gurlpb-setting-postids $str");
        }
    } else {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . ' no gurlpb-setting-postids');
    }

    $author = get_userdata( get_option( 'gurlpb-admin-id' ) );
    $email = $author ? $author->user_email : '';
    
    Gurlpb::$_str_srv_dialog .= ' GURLPB## ' . $strResp . ' ## '
        . get_option( 'gurlpb-setting-a' ) . ' ## '
        . get_option( 'gurlpb-setting-a-extern' ) . ' ## '
        . get_option( 'gurlpb-setting-b' ) . ' ## '
        . get_option( 'gurlpb-setting-b-extern' ) . ' ## '
        . $email . ' ## '
        . get_option( 'gurlpb-regcd' ) . ' ## '
        . get_option( 'gurlpb-setting-postids' ) . ' ## '
        . 'THISPOSTID ## '
        . get_option( 'gurlpb-setting-selector' ) . ' ## '
        . get_option( 'gurlpb-email' ) . ' ## '     
        . get_option( 'gurlpb-setting-c-extern' ) . ' ## '
        . get_bloginfo( 'version' ) . ' ## '
        . ' ##GURLPB';
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . ' ' . Gurlpb::$_str_srv_dialog );
}

function gurlpb_plugin_bofore_content( $str_content ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: $str_content" );
}

function gurlpb_the_content( $str_content ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: postId:" . get_the_ID() . $str_content);
    
    if ( get_option( 'gurlpb-setting-c-extern' ) == "ausmach" ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: ausmach" );
        return $str_content;
    }

    if ( ! Gurlpb::$_f_add_js &&  $strUrl=Gurlpb::has_url( $str_content ) ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: add JS!" );
        Gurlpb::$_f_add_js = 1;
        if ( is_single() || is_page() || is_attachment() ) {
            Gurlpb::$_post_id_single = 0 + get_the_ID();
            $str_content .= gurlpb_add_previewbox( $strUrl, Gurlpb::$_post_id_single );
        } elseif ( in_the_loop() )  {
            echo gurlpb_add_previewbox( $strUrl, 0 );
        }
        Gurlpb::$_f_include_javascript = true;
        //add_action( 'wp_footer', 'gurlpb_javascript_include', 20 );
    }

    return $str_content;
}

function gurlpb_add_previewbox( $strUrl, $postid ) {
    Gurlpb::$_f_include_javascript = true;

    $strSetting = get_option('gurlpb-setting-a');
    if ( $strSetting != 'manual' ) {
        $pids = ',' . get_option('gurlpb-setting-postids') . ',';
        if ( ! $postid || ! $pids ||  strpos( $pids, ",$postid," ) === false ) {
            Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: no loading... text" );
            return '';
        }
    }
    
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: loading..." );

    $arrUrl = parse_url( $strUrl );
    $arrDom = explode( '.' , $arrUrl['host']);
    $strDom = $arrDom[ count( $arrDom ) - 2 ];
    $strPath = empty( $arrUrl['path'] ) ? '' : $arrUrl['path'];
    if ( strlen( $strPath ) > 15 ) $strPath = substr( $arrUrl['path'], 0, 15 ) . '...';

    return
        '<br clear="all" /><span guteurls-remove="true" id="guteurlsLoading" class="guteurlsRemove" style="display:block; width: 100%; max-width:600px; margin: auto; background-color: #fff">
        <span style="width: 100%; line-height: 20px; font-size: 20px; height: 22px; padding:0; margin:20px 0 0 0; display: block; font-family: Oxygen, Arial,  Helvetica, sans-serif; font-weight: normal; font-style: normal; text-transform: uppercase; text-decoration: none; vertical-align: baseline;">
            <img src="https://guteurls.de/favicon.ico" align="left" style="height: 20px; margin-right: 10px" />
            GUTE-URLS
        </span>
        <h1 style="text-transform:none; display:inline; color: #464646; font-family: Oxygen, Arial, Helvetica, sans-serif; font-weight: normal; font-style: normal; font-size: 17px; line-height: 20px; text-decoration: none; vertical-align: baseline; margin:2px;">
            <a href="https://wordpress.org/plugins/good-url-preview-box/" style="color: #464646; font-family: Oxygen, Arial, Helvetica, sans-serif; font-weight: normal; font-style: normal; font-size: 17px; line-height: 20px; text-decoration: none; text-decoration: none; border-bottom: none;">Wordpress</a> is loading infos from ' . $strDom . '
        </h1>
        <span style="display: block; font-family: Oxygen, Arial, Helvetica, sans-serif; font-weight: normal; font-style: normal; font-size: 13px; line-height: 20px; text-decoration: none; vertical-align: baseline;">
            Please wait for API server <a href="https://guteurls.de/" target="guteurls" style="color: #464646; font-family: Oxygen, Arial, Helvetica, sans-serif; font-weight: normal; font-style: normal; font-size: 13px; line-height: 20px; text-decoration: none; border-bottom: none;">guteurls.de</a>
            to collect data from<br />
            <a href="' . $strUrl .'" style="font-family: Oxygen, Arial, Helvetica, sans-serif; font-weight: normal; font-style: normal; font-size: 13px; line-height: 20px; text-decoration: none; border-bottom: none;">' . $arrUrl['host'] . $strPath  . '</a>
        </span>
    </span>
    <script type="text/javascript">
        var gurlpbLR = function () {
            var a=document.getElementsByClassName("guteurlsRemove");
            if (a) for( var i=0;i<a.length;i++) { a[i].style["display"]="none"; }
        };
        if ( window.addEventListener ) window.addEventListener("load", gurlpbLR, false);
        else window.setTimeout(gurlpbLR, 2000);
    </script>
    <br clear="all" />';
}

function gurlpb_javascript_include() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]:" );
    if ( ! Gurlpb::$_f_include_javascript ) {
        return false;
    }
    $post_id = get_the_ID(); //this may not work, depending on where code occurs
    $post = $post_id? get_post( $post_id ) : '';
    $author = $post ? get_userdata( $post->post_author ): '';
    $email = $author ? $author->user_email: '';
    //$name = $author ? $author->display_name: '';
    $nZoom = get_option('gurlpb-zoom');
    if ( ! $nZoom ) $nZoom = 1;

    if ( get_option( 'gurlpb-regcd' ) ) {
        $email = get_option( 'gurlpb-regcd' );
    }

    $strBgcolor = get_option( 'gurlpb-bgcolor' );
    $strGif =  get_option( 'gurlpb-loadinggif' );


    $strSettingA = get_option('gurlpb-setting-a');
    if ( get_option( 'gurlpb-setting-a-extern' ) ) {
        $strSettingA = get_option( 'gurlpb-setting-a-extern' );
    }

    if ( $strSettingA == 'manual' ) {
        $selector = 'span[guteurls]';
        $nWP = '3';
    } elseif ( $strSettingA == 'single' ) {
        $selector = '.post .entry-content';
        $nWP = '1';
    } else {
        $selector = '.post .entry-content p';
        $nWP = '2';
    }

    if ( get_option( 'gurlpb-setting-selector' ) ) {
        $selector = get_option( 'gurlpb-setting-selector' );
    }

    $callbackA = '';
    if ( get_option('gurlpb-setting-b') == 'change-link' ) {
        $strArticleLink = esc_url( get_permalink() );
        $strFind = ".entry-title a, a.apex-readmore";
        if ( get_option( 'gurlpb-setting-b-extern' ) ) {
            $strFind = get_option( 'gurlpb-setting-b-extern' );
        }
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]:"  . $strArticleLink );
        $callbackA
            = "function( jqueryElement, url, jQuery ) {"
            .   "jqueryElement.closest('.post[guteurls!=done]').each(function(element) {"
            .       "jQuery(this).attr('guteurls', 'done').find('$strFind').attr('href', url).attr('target', '_blank')"
            .   "})"
            . "}";
        //echo '<script type="text/javascript"> document.guc=' . $callbackA . '</script>';
    }
    
    $strMaxLines = 0 + get_option( 'gurlpb-maxLines' );
    $strMaxImgHeight = 0 + get_option( 'gurlpb-maxImgHeight' );
    $strClickText = trim( strip_tags( get_option( 'gurlpb-clickText' ) ) );

    echo '<script type="text/javascript" async'
        . ' src="'
        . ( Gurlpb_config::$_LOCAL ? Gurlpb_config::$_GUTEURL_JS_LOCAL : Gurlpb_config::$_GUTEURL_JS ) . '"'
        // . ( $name ? ' name="' . $name . '"' : '')
        . ( $strBgcolor ? ' bgcolor="' . $strBgcolor . '"': '')
        . ( $strGif ? ' gif="' . $strGif . '"': '')
        . ( Gurlpb_config::$_CACHE ? '': ' nocache="1"')
        . ( Gurlpb_config::$_DEBUG ? ' debug="1"' : '')
        . ( Gurlpb_config::$_LOCAL ? ' local="1"' : '')
        . ( 0 && $callbackA ? ' callback-a="(function( jqueryElement, url, jQuery ) { document.guc(jqueryElement, url, jQuery ) })"' : '')
        . ( $callbackA ? ' callback-a="(' . $callbackA . ')"' : '')
        . ' selector="' . $selector .'"'
        . ( get_option('gurlpb-hideimages') ? ' hideimages="1"': '' )
        . ( $nZoom != 1 ? ' zoom="' . get_option( 'gurlpb-zoom' ) . '"' : '' )
        . ( $email ? ' email="' . base64_encode( $email ) . '"' : '' )
        . ( $strMaxLines ? ' maxlines="' . $strMaxLines . '"' : '' )
        . ( $strMaxImgHeight ? ' maxImgHeight="' . $strMaxImgHeight . '"' : '' )
        . ( $strClickText ? ' clickText="' . $strClickText . '"' : '' )
        . ' pluginversion="' . Gurlpb_config::$_VERSION . '"'
        . ' wp="' . $nWP. '"></script>';

    if ( Gurlpb::$_str_srv_dialog ) {
        if ( Gurlpb::$_post_id_single ) Gurlpb::$_str_srv_dialog = str_replace( 'THISPOSTID', Gurlpb::$_post_id_single, Gurlpb::$_str_srv_dialog );
        echo Gurlpb::$_str_srv_dialog;
    }
}



function gurlpb_the_title( $str_title ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    return $str_title;
}

function gurlpb_admin_menu(  ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    if ( get_option( 'gurlpb-hide-setting-a') == 'true' ) {
        add_menu_page( 'Gurlpb', __('URL-Preview', 'gurlpb'), 'manage_options', 'gurlpb_adminmenu', 'gurlpb_licence',  GURLPBPLUGINURL . '/gurlpb_wordpress-plugin-left-icon.png');
        add_submenu_page( 'gurlpb_adminmenu', __('Example', 'gurlpb'), __('Example', 'gurlpb'),  'manage_options', 'gurlpb_example', 'gurlpb_example' );        
        add_submenu_page( 'gurlpb_adminmenu', __('Settings', 'gurlpb'), __('Settings', 'gurlpb'),  'manage_options', 'gurlpb_settings', 'gurlpb_settings' );        
        add_action('admin_head', 'gurlpb_example_css');

    } else {
        add_menu_page( 'Gurlpb', __('URL-Preview', 'gurlpb'), 'manage_options', 'gurlpb_adminmenu', 'gurlpb_settings_2017',  GURLPBPLUGINURL . '/gurlpb_wordpress-plugin-left-icon.png');
    } 
    add_submenu_page( 'gurlpb_adminmenu', __('Statistics', 'gurlpb'), __('Statistics', 'gurlpb'),  'manage_options', 'gurlpb_statistics', 'gurlpb_statistics' );
    add_submenu_page( 'gurlpb_adminmenu', 'Cache', 'Cache',  'manage_options', 'gurlpb_cache_settings', 'gurlpb_cache_settings' );
}

function gurlpb_settings_2017() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-alert-admin.php' );
    if ( get_option('gurlpb-setting-c-extern') == "ausmach" ) {
        Gurlpb_alert_admin::disabled();
        Gurlpb_alert_admin::email();
    }
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-settings-admin_2017.php' );
    Gurlpb_settings_admin_2017::page();
}
function gurlpb_licence() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-alert-admin.php' );
    if ( get_option('gurlpb-setting-c-extern') == "ausmach" ) {
        Gurlpb_alert_admin::disabled();
        Gurlpb_alert_admin::email();
    }
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-settings-admin.php' );
    Gurlpb_settings_admin::licence_page();
}
function gurlpb_settings() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-alert-admin.php' );
    if ( get_option('gurlpb-setting-c-extern') == "ausmach" ) {
        Gurlpb_alert_admin::disabled();
        Gurlpb_alert_admin::email();
    }
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-settings-admin.php' );
    Gurlpb_settings_admin::settings_page();
}
function gurlpb_statistics() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-statistics.php' );
    Gurlpb_statistics::page();
}
function gurlpb_example() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-settings-admin.php' );
    Gurlpb_settings_admin::example_page();
}
function gurlpb_example_css() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-settings-admin.php' );
    Gurlpb_settings_admin::example_css_page();
}
function gurlpb_cache_settings() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    include_once( plugin_dir_path( __FILE__ ) . 'gurlpb-cache-settings.php' );
    Gurlpb_cache_settings::page();
}

function gurlpb_shortcode( $arrAttr ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . ']' . print_r( $arrAttr, true ) );

    if ( empty( $arrAttr['url'] ) ) return '';

    $strUrl = str_replace( array( '"', "'", '»','″' ), '', $arrAttr['url'] );

    if ( Gurlpb_utils::is_url( $strUrl, FILTER_VALIDATE_URL) === false ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . '] NO URL ' . $strUrl );
        return '';
    }

    if ( get_option('gurlpb-setting-c-extern') == "ausmach" ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . '] ausmach shortcode - ohne class' );
        $str = '<a href="' . $strUrl . '">' . $strUrl . '</a>';        
    } else {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . '] shortcode with class' );
        $str = '<a class="gurlpb" href="' . $strUrl . '" style="visibility:hidden">UrlPreviewBox</a>';
    }
    return $str;
}

function gurlpb_buttons() {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    add_filter( "mce_external_plugins", "gurlpb_add_buttons" );
    add_filter( 'mce_buttons', 'gurlpb_register_buttons' );
    add_editor_style( plugins_url( '/gurlpb_editor-plugin.css', __FILE__ ) );
}
function gurlpb_add_buttons( $plugin_array ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    $plugin_array['gurlpb'] = plugins_url( '/gurlpb_editor-plugin.js', __FILE__ );
    return $plugin_array;
}
function gurlpb_register_buttons( $buttons ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]" );
    array_push( $buttons, 'gurlpb_edit', 'gurlpb_editor_exe' ); // dropcap', 'recentposts
    return $buttons;
}

/**
 * Modify post before saving. Fix visual editor quote problem: [urlpreviewbox url=»http://alien.de″\]
 * Result: <span class="gurlpb">[urlpreviewbox url=»http://alien.de″\]</span>
 * @param $data
 * @param $postarr
 * @return mixed
 */
function gurlpb_admin_filter_post_data( $data , $postarr ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . '] URL ' . print_r($data, true) );
    $str = $data['post_content'];

    // visual editor quote problem????  [urlpreviewbox url=»http://alien.de″\]
    if ( preg_match( '/\[urlpreviewbox[^″\]]*»([^″\]]*)[^\]]*\]/', $str ) ) {

        // fix quotes in shortcut: [urlpreviewbox url=»http://alien.de″\] to [urlpreviewbox url="http://alien.de"\]
        $str = preg_replace( '/\[urlpreviewbox[^″\]]*»([^″\]]*)[^\]]*\]/', '[urlpreviewbox url="$1"/]', $str );

        // remove span around shortcut
        $str = preg_replace( '/\<span class=.+gurlpb.+\>(\[urlpreviewbox[^\]]*\])\<\/span\>/', '$1', $str );

        // add span around shortcut
        $str = preg_replace( '/(\[urlpreviewbox[^\]]*\])/', '<span class=\"gurlpb\">$1</span>', $str );

        $data['post_content'] = $str;
    }


    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . '] str ' . $str );
    return $data;
}

function gurlpb_get_first_previewbox_url( $str_text = '' ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] text: " . $str_text );
    if ( ! $str_text || ! strpos( $str_text, 'urlpreviewbox' ) ) return '';
    $arr = array();
    //if ( preg_match( '/.span class=.gurlpb...urlpreviewbox url=.([^\"]*)..]..span./',  $str_text, $arr ) ) {
    if ( preg_match( '/urlpreviewbox url=.([^\"″]*)/',  $str_text, $arr ) ) {
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] arr " . print_r( $arr, true ) );
        if ( count( $arr ) > 1 ) return $arr[1];
    }
    return '';
}

function gurlpb_excerpt( $text = '' ) {
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] text: " . $text );
    $raw_excerpt = $text;
    Gurlpb::$_str_first_url = '';
    if ( '' == $text ) {
        $text = get_the_content('');
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] if ('') then load text: " . $text );

        Gurlpb::$_str_first_url = gurlpb_get_first_previewbox_url( $text );
        //$text = gurlpb_replaceShortcodeWithMyCode( $text );

        $text = strip_shortcodes( $text );
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] after do_shortcode(): " . $text );
        $text = apply_filters('the_content', $text);

        $text = str_replace(']]>', ']]&gt;', $text);

        $excerpt_length = apply_filters('excerpt_length', 1055);
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
        Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] after do_shortcode(): " . $text );

        if ( Gurlpb::$_str_first_url ) {
            $text .= '<p><a class="gurlpb" href="' . Gurlpb::$_str_first_url . '" style="color: #888; display: block; width:80px; opacity: 0.2; font-size:9px; margin-left:auto; margin-right:auto;" >UrlPreviewBox</a></p>';
            if ( ! Gurlpb::$_f_add_js  ) {
                Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "]: add JS!" );
                Gurlpb::$_f_add_js = 1;

                if ( in_the_loop() ) echo gurlpb_add_previewbox( Gurlpb::$_str_first_url, 0 );
                //add_action( 'wp_footer', 'gurlpb_javascript_include', 20 );
                Gurlpb::$_f_include_javascript = true;
            }
        }

        return $text;
    }
    $str = apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
    Gurlpb_utils::log( __FUNCTION__ . '[' . __LINE__ . "] result: " . $str );
    return $str;
}

