<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage MB
 * @since MB 1.0
 */

global $post;
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta name=”robots” content=”noodp,noydir” />
    <title><?php wp_title(''); ?></title>
    <script>
        var HOME_URL = "<?php echo site_url(); ?>";
        var TEMPLATE_URL = "<?php echo get_template_directory_uri(); ?>";
    </script>
    <?php wp_head(); ?>
    <?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Speed Insights') === false): ?>
        <!-- Google Tag Manager -->

        <!-- End Google Tag Manager -->
    <?php endif;
    ?>
</head>
<body <?php body_class(); ?>>
<div class="page-content">
    <header class="container-fluid">
        <div class="row fs-0">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-5 col-sm-6 inline-block padding-left-xs-10">
                <a href="<?php echo site_url(''); ?>">
                    <img data-defer-src="<?php echo get_option('website_logo_option')['website_logo']; ?>" class="max-width-180 width-100" alt="Logo"/>
                </a>
            </figure>
            <div class="col-xs-7 col-sm-6 inline-block text-right padding-right-50 padding-right-xs-10 padding-left-xs-0 mobile-app-download-btns">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                    <a href="https://play.google.com/apps/testing/com.DentaCare.JawsOfBattle" target="_blank">
                        <img data-defer-src="<?php echo RESOURCES_PREFIX; ?>wp-content/uploads/2020/05/google-play-badge.svg" class="max-width-150 width-100 margin-right-10" alt="Google play download button"/>
                    </a>
                </figure>
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                    <a href="https://testflight.apple.com/join/hOg8An1t" target="_blank">
                        <img data-defer-src="<?php echo RESOURCES_PREFIX; ?>wp-content/uploads/2020/05/app-store.svg" class="max-width-150 width-100" alt="App store download button"/>
                    </a>
                </figure>
            </div>
        </div>
    </header>