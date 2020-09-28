<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage MB
 * @since MB 1.0
 */
?>
    <footer class="padding-bottom-30 padding-top-110 margin-top-20 margin-top-xs-0">
        <div class="container">
            <div class="row fs-0 padding-bottom-35">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block col-xs-6 text-right">
                    <a href="https://play.google.com/store/apps/details?id=com.DentaCare.JawsOfBattle&hl=en_US" target="_blank">
                        <img data-defer-src="<?php echo RESOURCES_PREFIX; ?>wp-content/uploads/2020/05/google-play-badge.svg" class="max-width-150 width-100 margin-right-10" alt="Google play download button"/>
                    </a>
                </figure>
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block col-xs-6 text-left">
                    <a href="https://testflight.apple.com/join/hOg8An1t" target="_blank">
                        <img data-defer-src="<?php echo RESOURCES_PREFIX; ?>wp-content/uploads/2020/05/app-store.svg" class="max-width-150 width-100" alt="App store download button"/>
                    </a>
                </figure>
            </div>
            <div class="row fs-0 border-top padding-top-40">
                <div class="col-xs-12 col-md-3 inline-block text-center-xs text-center-sm padding-bottom-xs-20 padding-bottom-sm-20">
                    <figure itemscope="" itemtype="http://schema.org/Organization">
                        <a itemprop="url" href="//dentacoin.com" class="fs-14">
                            <img data-defer-src="<?php echo RESOURCES_PREFIX; ?>wp-content/uploads/2020/05/round-logo-white.svg" itemprop="logo" class="max-width-30" alt="Dentacoin logo"/>
                            <span class="color-main padding-left-10 inline-block">Powered by Dentacoin</span>
                        </a>
                    </figure>
                </div>
                <?php
                $footerMenu = wp_get_nav_menu_items('Footer');
                if (!empty($footerMenu)) {
                    ?>
                    <nav class="col-xs-12 col-md-6 text-center inline-block padding-bottom-xs-20 padding-bottom-sm-20">
                        <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement" class="fs-14">
                            <?php
                            $passedFirstLoop = false;
                            for ($i = 0, $len = sizeof($footerMenu); $i < $len; $i+=1) {
                                if ($passedFirstLoop) {
                                    ?>
                                    <li class="inline-block-top separator">|</li>
                                    <?php
                                } else {
                                    $passedFirstLoop = true;
                                }
                                ?>
                                <li class="inline-block-top <?php echo $footerMenu[$i]->classes[0]; ?>">
                                    <a href="<?php if(!empty($footerMenu[$i]->url)) {echo $footerMenu[$i]->url; } else { echo 'javascript:void(0);';} ?>" itemprop="url"><span itemprop="name"><?php echo $footerMenu[$i]->title; ?></span></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </nav>
                    <?php
                }
                ?>
                <div class="col-xs-12 col-md-3 inline-block text-right socials text-center-xs text-center-sm" itemscope="" itemtype="http://schema.org/Organization">
                    <link itemprop="url" href="<?php echo site_url(''); ?>">
                    <ul class="inline-block">
                        <li class="inline-block">
                            <a itemprop="sameAs" target="_blank" href="https://www.facebook.com/dentacare.jaws/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </li>
                        <li class="inline-block telegram">
                            <a itemprop="sameAs" target="_blank" href="https://t.me/dentacoin"><i class="fa fa-telegram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row color-main">
                <div class="col-xs-12 text-center fs-14 padding-top-20">
                    © <?php echo date('Y'); ?> Dentacoin Foundation. All rights reserved.
                    <div><a href="//dentacoin.com/assets/uploads/dentacoin-foundation.pdf" class="text-decoration" target="_blank">Verify Dentacoin Foundation</a> | <a href="//dentacoin.com/privacy-policy" target="_blank" class="text-decoration">Privacy Policy</a></div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- .page -->
<?php
    wp_footer();

    if (is_single()) {
        ?>
        <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "NewsArticle",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo get_permalink($post); ?>"
      },
      "headline": "<?php echo $post->post_title; ?>",
      "description": "<?php substr($post->post_content, 0, 130); ?>...",
        <?php
            $imageUrl = get_the_post_thumbnail_url($post->ID);
            if (!empty($imageUrl)) {
                list($width, $height) = getimagesize($imageUrl);
                ?>
                  "image": {
                    "@type": "ImageObject",
                    "url": "<?php echo $imageUrl; ?>",
                    "width": "<?php echo $width; ?>",
                    "height": "<?php echo $height; ?>"
                    },
                <?php
            }
            ?>
      "author": {
        "@type": "Person",
        "name": "<?php echo get_the_author(); ?>"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Dentacoin Blog",
        "logo": {
          "@type": "ImageObject",
          "url": "https://blog.dentacoin.com/wp-content/themes/hestia-child/assets/images/one-line-logo-black.png",
          "width": "2018",
          "height": "442"
        }
      },
      "datePublished": "<?php echo $post->post_date; ?>"
    }
    </script>
        <?php
    }
?>
</body>
</html>
