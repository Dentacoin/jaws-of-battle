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

if (typeof(dcnCookie) != 'undefined') {
    dcnCookie.init({
        'google_app_id' : 'UA-97167262-2'
    });
}

// doing this interval to fix broken url review plugin
if (jQuery('body').hasClass('single')) {
    var updatedLinkPreview = false;
    var updateLinkPreview = setInterval(function() {
        if (updatedLinkPreview) {
            clearInterval(updateLinkPreview);
        } else {
            if (jQuery('.guteurlsBox p').length && jQuery('.guteurlsBox p').html().length > 250) {
                jQuery('.guteurlsBox p').html(jQuery('.guteurlsBox p').html().substring(0, 250) + '...');
            }
        }
    }, 500);
}