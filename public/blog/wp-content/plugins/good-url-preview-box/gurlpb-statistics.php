<?php
class Gurlpb_statistics
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
            <form id="gurlpb_form" action="<?php echo Gurlpb_config::$_LOCAL? Gurlpb_config::$_GUTEURL_SRV_LOCAL : Gurlpb_config::$_GUTEURL_SRV?>/statistics-wp.php" method="post" target="gurlpb_iframe">
                <input type="hidden" name="d" value="<?php echo site_url();?>" />
                <input type="hidden" name="c" value="<?php echo get_option( 'gurlpb-random' ); ?>" />
                <input type="hidden" name="l" value="<?php echo substr( get_bloginfo ( 'language' ), 0, 2 ) ?>" />
            </form>
            <iframe name="gurlpb_iframe" width="100%" height="5000"></iframe>
        </div>
        <script type="text/javascript">
            document.getElementById('gurlpb_form').submit();
        </script>
    <?php

    }
}