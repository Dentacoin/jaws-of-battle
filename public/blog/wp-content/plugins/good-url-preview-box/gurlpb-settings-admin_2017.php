<?php
class Gurlpb_settings_admin_2017
{
    static function page()
    {
        Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] started" . get_option( 'gurlpb-setting-lang-switch' ) );
        $user_id = get_option( 'gurlpb-admin-id' );
        $user_info = get_userdata( $user_id );

        if ( isset( $_POST['gurlpb-submit'] ) ) {
            if ( isset( $_POST['gurlpb-setting-a'] ) && ( $_POST['gurlpb-setting-a'] == 'single' || $_POST['gurlpb-setting-a'] == 'multiple' || $_POST['gurlpb-setting-a'] == 'manual' ) ) {
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] update gurlpb-setting-a" );
                update_option( 'gurlpb-setting-a', sanitize_text_field( $_POST['gurlpb-setting-a'] ) );
            }
            if ( isset( $_POST['gurlpb-setting-b'] ) && ( $_POST['gurlpb-setting-b'] == 'change-link' || $_POST['gurlpb-setting-b'] == 'untouched-link' ) ) {
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] update gurlpb-setting-b" );
                update_option('gurlpb-setting-b', sanitize_text_field( $_POST['gurlpb-setting-b'] ) );
            }
            if ( isset( $_POST['gurlpb-regcd'] ) && preg_match("/^[0-9a-zA-Z-_]*/", $_POST['gurlpb-regcd'] ) ) {
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] gurlpb-regcd" );
                if ( strpos( $_POST['gurlpb-regcd'], '42x' ) ) {
                    update_option( 'gurlpb-regcd', sanitize_text_field( $_POST['gurlpb-regcd'] ) );
                } else if ( ! $_POST['gurlpb-regcd'] ) {
                    delete_option( 'gurlpb-regcd' );
                } else {
                    ?><script type="text/javascript">window.alert( '<?php _e( 'Invalid-licence-key', 'gurlpb' ); ?>' );</script><?php
                }
            }
            if ( isset( $_POST['gurlpb-bgcolortype'] ) ) {
                $bgcolortype = 0 + $_POST['gurlpb-bgcolortype'];
                if ( $bgcolortype == 1 ) {
                    delete_option('gurlpb-bgcolor');
                } elseif ( $bgcolortype == 2 ) {
                    $strBgcolor = empty( $_POST['gurlpb-bgcolor'] ) ? '' : trim( $_POST['gurlpb-bgcolor'] );
                    if ( strlen( $strBgcolor ) == 7 && preg_match('/[#][0-9abcdef]*/i', $strBgcolor ) ) {
                        update_option( 'gurlpb-bgcolor', sanitize_text_field( $strBgcolor ) );
                    }
                } elseif ( $bgcolortype == 3 ) {
                    update_option( 'gurlpb-bgcolor', sanitize_text_field( 'none' ) );
                }
            }
            if ( isset( $_POST['gurlpb-typegif'] ) ) {
                $typegif = 0 + $_POST['gurlpb-typegif'];
                if ( $typegif == 1 ) {
                    delete_option('gurlpb-loadinggif');
                } elseif ( $typegif == 2 ) {
                    Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] gurlpb-bgcolor" . $_POST['gurlpb-bgcolor'] );
                    Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] gurlpb-bgcolor" . print_r( $_POST, true ));
                    $strLoadingGif = empty( $_POST['gurlpb-gif'] ) ? '' : trim( $_POST['gurlpb-gif'] );
                    if ( filter_var( $strLoadingGif, FILTER_VALIDATE_URL) !== false ) {
                        update_option( 'gurlpb-loadinggif', sanitize_text_field( $strLoadingGif ) );
                    }
                } elseif ( $typegif == 3 ) {
                    update_option( 'gurlpb-loadinggif', sanitize_text_field( 'https://guteurls.de/images/empty.gif' ) );
                }
            }
            if ( isset( $_POST['gurlpb-clickTextRadio'] ) ) {
                $nClickTextRadio = $_POST['gurlpb-clickTextRadio'] == 1 ? 1 : 2;
                $strClickText = ( $nClickTextRadio == 2 ? trim( strip_tags( $_POST[ 'gurlpb-clickText' ] ) ) : '' ); 
                if ( $strClickText == '' ) {
                    delete_option('gurlpb-clickText');
                    Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] delete_option clickText: " . $_POST[ 'gurlpb-clickText' ]);
                } else {
                    update_option( 'gurlpb-clickText', sanitize_text_field( $strClickText ) );
                    Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] update_option clickText" );
                }
            }
            if ( isset( $_POST['gurlpb-showexcerpt'] ) ) {
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] gurlpb-except 1" );
                update_option('gurlpb-showexcerpt', '1' );
            } else {
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] gurlpb-except 0" );
                update_option('gurlpb-showexcerpt', '0' );
            }
            if ( isset( $_POST['gurlpb-zoom'] ) ) {
                $n_zoom = 0 + $_POST['gurlpb-zoom'];
                if ( $n_zoom > 0.4 && $n_zoom < 2 ) {
                    update_option('gurlpb-zoom', sanitize_text_field( $n_zoom ) );
                }
            }
            if ( isset( $_POST['gurlpb-maxLines'] ) ) {
                $nMaxLines = 0 + $_POST['gurlpb-maxLines'];
                if ( $nMaxLines > 0 && $nMaxLines <=12 ) {
                    update_option('gurlpb-maxLines', sanitize_text_field( $nMaxLines ) );
                } else {
                    delete_option('gurlpb-maxLines');
                }
            }
            if ( isset( $_POST['gurlpb-maxImgHeight'] ) ) {
                $nMaxImgHeight = 0 + $_POST['gurlpb-maxImgHeight'];
                Gurlpb_utils::log(__FUNCTION__ . '[' . __LINE__ . "] nMaxImgHeight $nMaxImgHeight" );
                if ( $nMaxImgHeight > 0 && $nMaxImgHeight <=800 ) {
                    update_option('gurlpb-maxImgHeight', sanitize_text_field( $nMaxImgHeight ) );
                } else {
                    delete_option('gurlpb-maxImgHeight');
                }
            }
            if ( isset( $_POST['gurlpb-hideimages'] ) ) {
                update_option('gurlpb-hideimages', '1' );
            } else {
                delete_option('gurlpb-hideimages');
            }
            echo '<script type="text/javascript">localStorage.clear()</script>';
        }


        $strBgcolor = get_option( 'gurlpb-bgcolor' );
        if ( $strBgcolor == '' ) {
            $bgcolortype = 1;
        } elseif ( $strBgcolor == 'none' ) {
            $bgcolortype = 3;
        } else {
            $bgcolortype = 2;
        }

        $strLoadingGif = get_option( 'gurlpb-loadinggif' );
        if ( $strLoadingGif == '' ) {
            $typegif = 1;
        } elseif ( $strLoadingGif == 'https://guteurls.de/images/empty.gif' ) {
            $typegif = 3;
        } else {
            $typegif = 2;
        }
        
        $strClickText = trim( strip_tags( get_option( 'gurlpb-clickText' ) ) );
        $nClickTextRadio = ( $strClickText == '' ? 1 : 2 );

        $strZoom = get_option( 'gurlpb-zoom' );
        $strMaxLines = 0 + get_option( 'gurlpb-maxLines' );
        $strImgHeight = 0 + get_option( 'gurlpb-maxImgHeight' );;
        ?>
        <div class="wrap">
            
            <form id="formGurlpb" method="post" >
                <input type="hidden" name="gurlpb-submit" value="1" />
                <h2>URL Preview <?php esc_html_e( 'Settings', 'gurlpb' ); ?></h2>                
                <hr />
                <?php                    
                    if ( get_option( 'gurlpb-setting-lang-switch' ) == 'y' ) {
                        ?>
                            <input type="checkbox" name="gurlpb-setting-en"
                                  <?php echo ( ( get_option( 'gurlpb-setting-en' ) == 'yes' ) ? 'checked="checked"' : '' ) ?>
                                   value="yes"
                                   onchange="this.form.submit()" />
                            <?php _e('in-english', 'gurlpb'); ?>
                            <br />
                            <hr />
                        <?php
                    }
                ?>

                <div class="postbox-container" style="text-align: left; border: 1px solid #999; width:100%">
                    <div class="postbox-container"  style="margin: 5px; padding: 3px; ">
                        <?php _e( 'This-plugin-is-for-free-usage-for-no-commercial-websites-with-few-pageviews', 'gurlpb' ); ?><br />

                        <?php printf( __( 'Else-purchase-click here %1$s.', 'gurlpb' ), 'https://guteurls.de/plan.php' ) ?><br />

                        <input type="text" name="gurlpb-regcd" value="<?php echo get_option( 'gurlpb-regcd' ); ?>" placeholder="Licence Key"> <input type="submit"> <?php _e( 'optional', 'gurlpb' ); ?>
                    </div>
                </div>
                <br class="clear"/>
                <br class="clear"/>



                <?php if ( get_option( 'gurlpb-hide-setting-a' ) ) { ?>
                    <div  style="text-align: left; border: 1px solid #999; padding: 10px">
                        <div class="postbox-container"  style="font-size: 30px; font-weight: bold;">
                            <?php _e( 'How to', 'gurlpb' ); ?>
                        </div>
                        <br class="clear"/><br />
                        <?php _e( 'setting-manual-URL', 'gurlpb' ); ?><br />
                        <?php _e( 'setting-manual-URL2', 'gurlpb' ); ?><br />
                        <br >
                        ----<br /><br />
                        <img src="<?php echo plugins_url( 'gurlpb_editor-help.jpg', __FILE__ );?>" xstyle="display:block; margin-right:auto;margin-left:auto;"/>
                        <div xstyle="margin-left:auto;margin-right:auto;text-align: center">
                            <?php _e( 'setting-manual-URL3', 'gurlpb' ); ?><br />
                        </div>
                        <br class="clear"/>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/CpecfL_FIzM" frameborder="0" allowfullscreen ></iframe>
                        <br class="clear"/>
                    </div>
                <?php } else { ?>

                    <div class="postbox-container"  style="margin: 5px; padding: 3px; font-size: 30px; font-weight: bold;">
                        <?php _e( 'How-many-URL-Previews?', 'gurlpb' ); ?>
                    </div>
                    <br class="clear"/>
                    <div class="postbox-container" style="margin: 5px; padding: 3px; border: 1px solid #999">
                        <input id="inputSingle" type="radio" name="gurlpb-setting-a"
                               <?php if ( get_option('gurlpb-setting-a') == 'single' ) echo 'checked="checked"'; ?>
                               onclick="this.form.submit();"
                               value="single" />
                        <?php _e( 'Add-maximal-one', 'gurlpb' ); ?><br />
                        <?php _e( 'and-add-it.', 'gurlpb' ); ?><br /><br />
                        <a href="javascript:document.getElementById( 'inputSingle' ).checked = 'checked'; document.getElementById( 'formGurlpbOneMany' ).submit()">
                            <img src="<?php echo plugins_url( 'gurlpb-single.jpg', __FILE__ );?>"/></a><br />
                    </div>
                    <div class="postbox-container" style="margin: 5px; padding: 3px; border: 1px solid #999">
                        <input id="inputMutiple" type="radio" name="gurlpb-setting-a"
                                <?php if ( get_option('gurlpb-setting-a') == 'multiple' ) echo 'checked="checked"'; ?>
                               onclick="this.form.submit();"
                               value="multiple" />
                        <?php _e( 'Add-one-URL,', 'gurlpb' ); ?><br />
                        <?php _e( 'one-per-P-Tag.', 'gurlpb' ); ?><br /><br />
                        <a href="javascript:document.getElementById( 'inputMutiple' ).checked = 'checked'; document.getElementById( 'formGurlpbOneMany' ).submit()">
                            <img src="<?php echo plugins_url( 'gurlpb-multiple.jpg', __FILE__ );?>" /></a><br />
                    </div>
                    <div class="postbox-container" style="margin: 5px; padding: 3px; border: 1px solid #999">
                        <input id="inputMutiple" type="radio" name="gurlpb-setting-a"
                            <?php if ( get_option('gurlpb-setting-a') == 'manual' ) echo 'checked="checked"'; ?>
                               onclick="this.form.submit();"
                               value="manual" />
                        <?php _e( 'setting-manual-URL', 'gurlpb' ); ?><br />
                        <?php _e( 'setting-manual-URL2', 'gurlpb' ); ?><br />

                        <img src="<?php echo plugins_url( 'gurlpb_editor-help.jpg', __FILE__ );?>" style="display:block; margin-right:auto;margin-left:auto;"/>
                        <div style="margin-left:auto;margin-right:auto;text-align: center">
                            <?php _e( 'setting-manual-URL3', 'gurlpb' ); ?><br />
                        </div>

                        <br /><br />
                    </div>
                <?php } ?>


                <br class="clear"/>
                <hr />



                <div class="postbox-container" style="text-align: left; border: 1px solid #999">
                    <div class="postbox-container"  style="margin: 5px; padding: 3px; font-size: 30px; font-weight: bold;">
                        <?php _e( 'Change-article-link?', 'gurlpb' ); ?>
                    </div>
                    <br class="clear"/>
                    <div class="postbox-container" style="text-align: left; margin: 5px; padding: 3px;">
                        <br />
                        <b><?php _e( 'Your-title-is-clickable-to', 'gurlpb' ); ?></b><br /><br />
                        <input id="inputChange" type="radio" name="gurlpb-setting-b"
                            <?php if ( get_option('gurlpb-setting-b') == 'change-link' ) echo 'checked="checked"'; ?>
                               onclick="this.form.submit();"
                               value="change-link" />
                        <?php _e( 'the-first-found-URL.', 'gurlpb' ); ?><br />
                        <br />
                        <input id="inputNoChange" type="radio" name="gurlpb-setting-b"
                            <?php if ( get_option('gurlpb-setting-b') != 'change-link' ) echo 'checked="checked"'; ?>
                               onclick="this.form.submit();"
                               value="untouched-link" />
                        <?php _e( 'your-own-article.', 'gurlpb' ); ?><br />
                        <br />

                    </div><br class="clear"/>
                    <div class="postbox-container" style="margin: 5px; padding: 3px;">
                        <img src="<?php echo plugins_url( 'gurlpb-title-link.jpg', __FILE__ );?>" /><br />
                    </div>
                </div>


                <br class="clear"/>
                <hr />

                <div class="postbox-container" style="text-align: left; border: 1px solid #999">
                    <div class="postbox-container"  style="margin: 5px; padding: 3px; font-size: 30px; font-weight: bold;">
                        <?php _e( 'Expert_settings', 'gurlpb' ); ?>
                    </div>
                    <br class="clear"/>
                    <div class="postbox-container" style="text-align: left; margin: 5px; padding: 3px;">

                        <b><?php _e( 'Optional. You can let these values empty.', 'gurlpb' ); ?></b><br /><br />
                        <table>
                            <tr>
                                <td>
                                    bgcolor:
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-bgcolortype" <?=$bgcolortype==1?'checked="checked"':''?> value="1" />
                                    <?php _e( 'Standard. White background color.', 'gurlpb' ); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-bgcolortype" id="gurlpb-radioColor2" <?=$bgcolortype==2?'checked="checked"':''?> value="2" />
                                    <input type="color" name="gurlpb-bgcolor" value="<?=($bgcolortype==2 && $strBgcolor)?$strBgcolor:''?>" placeholder="#fffff" size="8" onclick="document.getElementById('gurlpb-radioColor2').checked='checked';"/>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-bgcolortype" <?=$bgcolortype==3?'checked="checked"':''?> value="3" />
                                    <?php _e( 'Transparent', 'gurlpb' ); ?>
                                    <br /><br />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Loading gif:
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-typegif" <?=$typegif==1?'checked="checked"':''?>  value="1" />
                                    <?php _e( 'Standard animation.', 'gurlpb' ); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-typegif" id="gurlpb-radioGif2" <?=$typegif==2?'checked="checked"':''?> value="2" />
                                    <input name="gurlpb-gif" value="<?=($typegif==2 && $strLoadingGif)?$strLoadingGif:''?>" placeholder="https://........gif" size="40" onclick="document.getElementById('gurlpb-radioGif2').checked='checked';" />
                                    <br />
                                    <span style="font-size: small">
                                        &nbsp; &nbsp; &nbsp;
                                        (<?php _e( 'Use a gif from', 'gurlpb' ); ?>)
                                    </span>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-typegif" <?=$typegif==3?'checked="checked"':''?> value="3" />
                                    <?php _e( 'No animation', 'gurlpb' ); ?>
                                    <br /><br />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Clickable-text', 'gurlpb' ); ?>:
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-clickTextRadio" id="gurlpb-clickTextRadio1" <?=$nClickTextRadio<2?'checked="checked"':''?>  value="1" />
                                    <?php _e( 'Default-text.', 'gurlpb' ); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input type="radio" name="gurlpb-clickTextRadio" id="gurlpb-clickTextRadio2" <?=$nClickTextRadio==2?'checked="checked"':''?> value="2" />
                                    <input name="gurlpb-clickText" value="<?=($nClickTextRadio==2 && $strClickText)?$strClickText:''?>" placeholder="<?php _e( 'Example-Click-here', 'gurlpb' ); ?>" size="30" onclick="document.getElementById('gurlpb-clickTextRadio2').checked='checked';" />
                                    <br />
                                    <br />
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <?php _e( 'Show URL preview box in excerpt', 'gurlpb' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="gurlpb-showexcerpt" <?=get_option( 'gurlpb-showexcerpt' ) ? 'checked="checked"' : ''?> value="1" />
                                    <?php _e( 'Yes', 'gurlpb' ); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                        <?php _e( 'Hide images', 'gurlpb' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="gurlpb-hideimages" <?=get_option( 'gurlpb-hideimages' ) ? 'checked="checked"' : ''?> value="1" />
                                    <?php _e( 'Yes', 'gurlpb' ); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Url preview box size', 'gurlpb' ); ?>
                                </td>
                                <td>

                                    <select name="gurlpb-zoom">
                                        <option <?=$strZoom == '0.5' ? 'selected="selected"': ''?> value="0.5"><?php _e( 'tiny', 'gurlpb' ); ?></option>
                                        <option <?=$strZoom == '0.8' ? 'selected="selected"': ''?> value="0.8"><?php _e( 'small', 'gurlpb' ); ?></option>
                                        <option <?= ( ! $strZoom || $strZoom == '1' ) ? 'selected="selected"': ''?> value="1"><?php _e( 'standard', 'gurlpb' ); ?></option>
                                        <option <?=$strZoom == '1.2' ? 'selected="selected"': ''?> value="1.2"><?php _e( 'tall', 'gurlpb' ); ?></option>
                                    </select>                                    

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Max image height', 'gurlpb' ); ?>
                                </td>
                                <td>
                                    <select name="gurlpb-maxImgHeight">
                                        <option <?=( ! $strImgHeight ) ? 'selected="selected"': ''?> value=""><?php _e( 'Full image (default)', 'gurlpb' ); ?></option>
                                        <option <?= $strImgHeight == '100' ? 'selected="selected"': ''?> value="100">100px</option>
                                        <option <?= $strImgHeight == '150' ? 'selected="selected"': ''?> value="150">150px</option>
                                        <option <?= $strImgHeight == '200' ? 'selected="selected"': ''?> value="200">200px</option>
                                        <option <?= $strImgHeight == '250' ? 'selected="selected"': ''?> value="250">250px</option>
                                        <option <?= $strImgHeight == '300' ? 'selected="selected"': ''?> value="300">300px</option>
                                        <option <?= $strImgHeight == '350' ? 'selected="selected"': ''?> value="350">350px</option>
                                        <option <?= $strImgHeight == '400' ? 'selected="selected"': ''?> value="400">400px</option>
                                        <option <?= $strImgHeight == '500' ? 'selected="selected"': ''?> value="500">500px</option>
                                        <option <?= $strImgHeight == '800' ? 'selected="selected"': ''?> value="800">800px</option>
                                    </select>                                    

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php _e( 'Description-length', 'gurlpb' ); ?>
                                </td>
                                <td>                                    
                                    <select name="gurlpb-maxLines">
                                        <option <?=( $strMaxLines == '' || $strMaxLines == '0' ) ? 'selected="selected"': ''?> value="0"><?php _e( 'Full-text-default', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '1' ? 'selected="selected"': ''?> value="1"><?php _e( 'Max.', 'gurlpb' ); ?> 1 <?php _e( 'row', 'gurlpb' ); ?></option> 
                                        <option <?= $strMaxLines == '2' ? 'selected="selected"': ''?> value="2"><?php _e( 'Max.', 'gurlpb' ); ?> 2 <?php _e( 'rows', 'gurlpb' ); ?></option> 
                                        <option <?= $strMaxLines == '3' ? 'selected="selected"': ''?> value="3"><?php _e( 'Max.', 'gurlpb' ); ?> 3 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '4' ? 'selected="selected"': ''?> value="4"><?php _e( 'Max.', 'gurlpb' ); ?> 4 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '5' ? 'selected="selected"': ''?> value="5"><?php _e( 'Max.', 'gurlpb' ); ?> 5 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '6' ? 'selected="selected"': ''?> value="6"><?php _e( 'Max.', 'gurlpb' ); ?> 6 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '7' ? 'selected="selected"': ''?> value="7"><?php _e( 'Max.', 'gurlpb' ); ?> 7 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '8' ? 'selected="selected"': ''?> value="8"><?php _e( 'Max.', 'gurlpb' ); ?> 8 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '9' ? 'selected="selected"': ''?> value="9"><?php _e( 'Max.', 'gurlpb' ); ?> 9 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '10' ? 'selected="selected"': ''?> value="10"><?php _e( 'Max.', 'gurlpb' ); ?> 10 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '11' ? 'selected="selected"': ''?> value="11"><?php _e( 'Max.', 'gurlpb' ); ?> 11 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                        <option <?= $strMaxLines == '12' ? 'selected="selected"': ''?> value="12"><?php _e( 'Max.', 'gurlpb' ); ?> 12 <?php _e( 'rows', 'gurlpb' ); ?></option>
                                    </select>                                    
                                           
                                </td>
                            </tr>                            
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <br />
                                    <input type="submit">
                                    <br /><br />
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>
                <br class="clear"/>
                <hr />

            </form>

            <hr />
        </div>
    <?php

    }
}