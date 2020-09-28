<?php
class Gurlpb_cache_settings
{

    static function page()
    {
        Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] started" );
        if ( ! get_option( 'gurlpb-random' ) ) {
            $str = base_convert( rand( 0, 1000000 ), 10, 36 ) . base_convert( rand( 0, 1000000 ), 10, 36 );
            update_option( 'gurlpb-random', sanitize_text_field( substr( $str, -8 ) ) );
        }
        ?>
        <div class="wrap">
            <h2><?php _e( 'Reset one Preview Box from cache', 'gurlpb' ); ?></h2>
            <?php _e( 'Reset data for one single preview box (force reload image and description).', 'gurlpb' ); ?>
            <br /><br />
            <form id="gurlpb_form" action="<?php echo Gurlpb_config::$_LOCAL? Gurlpb_config::$_GUTEURL_SRV_LOCAL : Gurlpb_config::$_GUTEURL_SRV?>/removelist-wp.php" method="post" target="gurlpb_iframe">
                <input type="hidden" name="d" value="<?php echo site_url();?>" />
                <input type="hidden" name="c" value="<?php echo get_option( 'gurlpb-random' ); ?>" />
                <input type="url" name="u" value="" style="font-size: 18px;" placeholder="http://..." />

                <input type="hidden" name="l" value="<?php echo substr( get_bloginfo ( 'language' ), 0, 2 ) ?>" />
                
                <br /><br />
                <input type="submit" name="a" class="button button-primary"  value="<?php _e( 'Reset URL', 'gurlpb' ); ?>" />
                <?php _e( 'or', 'gurlpb' ); ?> 
                <input type="submit" name="a" value="<?php _e( 'list all my URLs', 'gurlpb' ); ?>" class="button button-secondary" />
                
            </form>
            <br />
            <br />            
            <iframe name="gurlpb_iframe" width="100%" height="1000"></iframe>
        </div>
        <script type="text/javascript">
            document.gurlpb_remove_localStorage_values = function() {
                for ( var key in localStorage ) {
                    if ( key && key.substr( 0, 8 ) == 'guteurls' ) localStorage.removeItem( key );
                }
            }
            document.gurlpb_remove_localStorage_values();
            //document.getElementById('gurlpb_form').submit();
        </script>
    <?php

    }
}