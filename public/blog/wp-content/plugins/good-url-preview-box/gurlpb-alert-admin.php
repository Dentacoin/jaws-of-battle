<?php
class Gurlpb_alert_admin
{

    static function disabled()
    {
        ?>
        <div class="wrap">
            <div class="postbox-container" style="text-align: left; border: 1px solid #999; width:100%">
                <div class="postbox-container"  style="margin: 5px; padding: 3px; ">

                    <h2><?php _e( 'misuseOfPlugin', 'gurlpb' ); ?></h2>
                    <?php _e( 'misuseOfPlugin_PleasePurchaseOnlineAt https://guteurls.de', 'gurlpb' );  ?>
                </div>    
            </div>    
        </div>    
        <?php
    }

    static function email()
    {
        Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] started" );
            echo "0";
        if ( isset( $_POST['gurlpb-email'] ) ) {
            echo "1";
            $str = trim( $_POST['gurlpb-email'] );
            if ( ! $str ) {
                delete_option( 'gurlpb-email' );
            } else {
            echo "2";
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] update gurlpb-email" );
                update_option( 'gurlpb-email', sanitize_email( $_POST['gurlpb-email'] ) );
            }
        }
        $strEmail = get_option( 'gurlpb-email' );
        if ( ! $strEmail ) {
            $author = get_userdata( get_option( 'gurlpb-admin-id' ) );
            $strEmail = $author ? $author->user_email : '';            
        }
        ?>
        <div class="wrap">
            <div class="postbox-container" style="text-align: left; border: 1px solid #999; width:100%">
                <div class="postbox-container"  style="margin: 5px; padding: 3px; ">
            
                    <h2><?php _e( 'guteURLsNeedYourEmailAddress', 'gurlpb' ); ?></h2>
                    <form id="gurlpb_form" method="post">
                        <input type="hidden" name="d" value="<?php echo site_url();?>" />
                        <input type="hidden" name="c" value="<?php echo get_option( 'gurlpb-random' ); ?>" />
                        <?php _e( 'pleaseEnterYourContactEmailAddress', 'gurlpb' ); ?>
                        <input type="email" name="gurlpb-email" value="<?php echo $strEmail; ?>" style="font-size: 18px;" placeholder="your@email.com" />

                        <input type="hidden" name="l" value="<?php echo substr( get_bloginfo ( 'language' ), 0, 2 ) ?>" />
                        <br /><input type="submit" name="s" style="font-size: 28px;" />
                    </form>
                </div>
            </div>
            <br />&nbsp;<br />

        </div>
        <?php
    }
}