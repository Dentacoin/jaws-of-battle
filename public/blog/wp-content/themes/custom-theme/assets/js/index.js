jQuery(window).on('resize', function(){
    makePostsTitlesAndDescriptionsSameHeight();
});

jQuery(window).on('load', function(){
    makePostsTitlesAndDescriptionsSameHeight();
});

jQuery(window).on('scroll', function()  {
    onDesktopScrollMakeStickySidebarSinglePostPage();
});

jQuery(document).ready(function()   {
    checkIfCookie();
});

//load images after website load
if(jQuery('img[data-defer-src]').length) {
    jQuery(window).on('scroll', function(){
        loadDeferImages();
    });
}

if(jQuery('iframe[data-defer-src]').length) {
    jQuery(window).on('scroll', function(){

    });
}

if(jQuery('img[data-defer-package]').length) {
    jQuery(window).on('scroll', function(){

    });
}

function loadDeferImages() {
    jQuery('body').addClass('overflow-hidden');
    var window_width = jQuery(window).width();
    jQuery('body').removeClass('overflow-hidden');

    for(var i = 0, len = jQuery('img[data-defer-src]').length; i < len; i+=1) {
        if(basic.isInViewport(jQuery('img[data-defer-src]').eq(i)) && jQuery('img[data-defer-src]').eq(i).attr('src') == undefined) {
            if(window_width < 500 && jQuery('img[data-defer-src]').eq(i).attr('data-xss-image') != undefined) {
                jQuery('img[data-defer-src]').eq(i).attr('src', jQuery('img[data-defer-src]').eq(i).attr('data-xss-image'));
            } else if(window_width < 768 && jQuery('img[data-defer-src]').eq(i).attr('data-xs-image') != undefined) {
                jQuery('img[data-defer-src]').eq(i).attr('src', jQuery('img[data-defer-src]').eq(i).attr('data-xs-image'));
            } else if(window_width < 992 && jQuery('img[data-defer-src]').eq(i).attr('data-sm-image') != undefined) {
                jQuery('img[data-defer-src]').eq(i).attr('src', jQuery('img[data-defer-src]').eq(i).attr('data-sm-image'));
            } else {
                console.log(jQuery('img[data-defer-src]').eq(i).attr('data-defer-src'));
                jQuery('img[data-defer-src]').eq(i).attr('src', jQuery('img[data-defer-src]').eq(i).attr('data-defer-src'));
            }
        }
    }
}
loadDeferImages();

function makePostsTitlesAndDescriptionsSameHeight() {
    if (jQuery('.shortcode.posts-list .post-tile.module').length > 0 || jQuery('.related-posts-slider .post-tile.module').length > 0) {
        var selector;
        if (jQuery('.shortcode.posts-list .post-tile.module').length > 0) {
            selector = jQuery('.shortcode.posts-list');
        } else if (jQuery('.related-posts-slider .post-tile.module').length > 0) {
            selector = jQuery('.related-posts-slider');
        }
        
        selector.find('.post-tile.module .info-body h3').outerHeight('auto');
        selector.find('.post-tile.module .info-body p').outerHeight('auto');

        var titlesHeight = 0;
        var descHeight = 0;
        for (var i = 0, len = selector.find('.post-tile.module').length; i < len; i+=1) {
            if (selector.find('.post-tile.module').eq(i).find('.info-body h3').outerHeight() > titlesHeight) {
                titlesHeight = selector.find('.post-tile.module').eq(i).find('.info-body h3').outerHeight();
            }

            if (selector.find('.post-tile.module').eq(i).find('.info-body p').outerHeight() > descHeight) {
                descHeight = selector.find('.post-tile.module').eq(i).find('.info-body p').outerHeight();
            }
        }

        selector.find('.post-tile.module .info-body h3').outerHeight(titlesHeight);
        selector.find('.post-tile.module .info-body p').outerHeight(descHeight);
    }
}

function initSlickSlider() {
    if (jQuery('.related-posts-slider').length) {
        jQuery('.related-posts-slider').slick({
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
}
initSlickSlider();

function onDesktopScrollMakeStickySidebarSinglePostPage() {
    if (jQuery('body').hasClass('single')) {
        if (jQuery(window).width() > 992) {
            var stopper;
            if (jQuery('.section-related-posts').length) {
                stopper = jQuery('.section-related-posts');
            } else {
                stopper = jQuery('footer');
            }

            if (jQuery(window).scrollTop() > jQuery('.post-content').offset().top) {
                if (jQuery(window).scrollTop() + jQuery(window).height() > stopper.offset().top) {
                    console.log('reached stopper');
                    jQuery('.sticky-socials').css({'position' : 'absolute', 'left' : '0', 'top' : 'auto', 'bottom' : '0'});
                    jQuery('.sticky-categories').css({'position' : 'absolute', 'right' : '0', 'top' : 'auto', 'bottom' : '0'});
                    jQuery('.add-display-flex-and-position-relative').css({'display' : 'flex', 'position' : 'relative'});
                } else {
                    console.log('still not reached the stopper');
                    jQuery('.sticky-socials').css({'position' : 'fixed', 'left' : '0', 'top' : jQuery('header').outerHeight() + 'px', 'bottom' : 'auto'});
                    jQuery('.sticky-categories').css({'position' : 'fixed', 'right' : '0', 'top' : jQuery('header').outerHeight() + 'px', 'bottom' : 'auto'});
                    jQuery('.add-display-flex-and-position-relative').css({'display' : 'block'});
                }
                jQuery('.post-content').addClass('col-md-offset-3');
            } else {
                console.log('scroll bigger than post-content offset');
                jQuery('.sticky-socials').css({'position' : 'static', 'left' : 'auto', 'top' : 'auto'});
                jQuery('.sticky-categories').css({'position' : 'static', 'right' : 'auto', 'top' : 'auto'});
                jQuery('.post-content').removeClass('col-md-offset-3');
            }
        }
    }
}

function checkIfCookie()    {
    if (jQuery('.privacy-policy-cookie').length > 0)  {
        jQuery('.privacy-policy-cookie .accept-all').click(function()    {
            basic.cookies.set('performance_cookies', 1);
            basic.cookies.set('functionality_cookies', 1);
            basic.cookies.set('marketing_cookies', 1);
            basic.cookies.set('strictly_necessary_policy', 1);

            window.location.reload();
        });

        console.log(jQuery('.adjust-cookies').length, 'jQuery(\'.adjust-cookies\')');

        jQuery('.adjust-cookies').click(function() {
            jQuery('.customize-cookies').remove();

            jQuery('.privacy-policy-cookie').append('<div class="customize-cookies"><button class="close-customize-cookies close-customize-cookies-popup">×</button><div class="text-center"><img src="/wp-content/themes/hestia-child/assets/images/cookie-icon.svg" alt="Cookie icon" class="cookie-icon"/></div><div class="text-center padding-bottom-20 fs-20">Select cookies to accept:</div><div class="cookies-options-list"><ul><li class="custom-checkbox-style"><input type="checkbox" class="custom-checkbox-input" id="performance-cookies"/><label class="dentacoin-login-gateway-fs-15 custom-checkbox-label" for="performance-cookies">Performance cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="These cookies collect data for statistical purposes on how visitors use a website, they don’t contain personal data and are used to improve user experience."></i></label></li><li class="custom-checkbox-style"><input type="checkbox" class="custom-checkbox-input" id="marketing-cookies"/><label class="dentacoin-login-gateway-fs-15 custom-checkbox-label" for="marketing-cookies">Marketing cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Marketing cookies are used e.g. to deliver advertisements more relevant to you or limit the number of times you see an advertisement."></i></label></li></ul></div><div class="text-center actions"><a href="javascript:void(0);" class="cancel-btn margin-right-10 close-customize-cookies-popup">CANCEL</a><a href="javascript:void(0);" class="save-btn custom-cookie-save">SAVE</a></div><div class="custom-triangle"></div></div>');

            initCustomCheckboxes();
            initTooltips();

            jQuery('.close-customize-cookies-popup').click(function() {
                jQuery('.customize-cookies').remove();
            });

            jQuery('.custom-cookie-save').click(function() {
                basic.cookies.set('strictly_necessary_policy', 1);

                if(jQuery('#marketing-cookies').is(':checked')) {
                    console.log('set marketing');
                    basic.cookies.set('marketing_cookies', 1);
                }

                if(jQuery('#performance-cookies').is(':checked')) {
                    console.log('set performance');
                    basic.cookies.set('performance_cookies', 1);
                }

                window.location.reload();
            });
        });
    }
}