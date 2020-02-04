console.log('Don\'t touch the code. Or do ... ¯\\_(ツ)_/¯');
window.scrollTo(0, 0);
checkIfCookie();

$(document).ready(function() {

});

$(window).on('load', function() {

});

$(window).on("load", function()   {

});

$(window).on('resize', function(){

});

$(window).on('scroll', function()  {

});

var mobile_os = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    }
};

if(mobile_os.iOS()) {
    $('.android-btn').remove();
} else if(mobile_os.Android()) {
    $('.ios-btn').remove();
}

var pagesData = {
    homepage: function() {
        $(window).on('scroll', function()  {
            introSectionBackgroundPositionSetup();
        });

        $(window).on('load', function()  {
            introSectionBackgroundPositionSetup();
        });

        function introSectionBackgroundPositionSetup() {
            $('.below-absolute-content').css({'top' : $('.moving-image').height() + 'px'}).height($('.section-intro').height() - $('.moving-image').height()).fadeIn();

            if ($(document).scrollTop() == 0) {
                $('.moving-image').removeClass('second-sprite third-sprite fourth-sprite not-fixed');
                $('.frame-figure').removeClass('not-fixed');
                $('.absolute-content').removeClass('not-fixed');
                $('.moving-image').addClass('first-sprite');
                $('.hidden-frame').show();

                $('.below-absolute-content').css({'top' : $('.moving-image').height() + 'px', 'position' : 'fixed'});
            } else if ($(document).scrollTop() <= 100) {
                $('.moving-image').removeClass('first-sprite third-sprite fourth-sprite not-fixed');
                $('.frame-figure').removeClass('not-fixed');
                $('.absolute-content').removeClass('not-fixed');
                $('.moving-image').addClass('second-sprite');
                $('.hidden-frame').show();

                $('.below-absolute-content').css({'top' : $('.moving-image').height() + 'px', 'position' : 'fixed'});
            } else if ($(document).scrollTop() <= 200) {
                $('.moving-image').removeClass('first-sprite second-sprite fourth-sprite not-fixed');
                $('.frame-figure').removeClass('not-fixed');
                $('.absolute-content').removeClass('not-fixed');
                $('.section-intro').removeClass('no-overflow-hidden');
                $('.moving-image').addClass('third-sprite');
                $('.hidden-frame').show();

                $('.below-absolute-content').css({'top' : $('.moving-image').height() + 'px', 'position' : 'fixed'});
            } else if ($(document).scrollTop() <= 300) {
                $('.moving-image').removeClass('first-sprite second-sprite third-sprite');
                $('.frame-figure').removeClass('not-fixed');
                $('.absolute-content').removeClass('not-fixed');
                $('.moving-image').addClass('fourth-sprite');
                $('.hidden-frame').show();

                $('.below-absolute-content').css({'top' : $('.moving-image').height() + 'px', 'position' : 'fixed'});
            } else {
                $('.moving-image').removeClass('first-sprite second-sprite third-sprite');
                $('.absolute-content').addClass('not-fixed');
                $('.moving-image').addClass('fourth-sprite');
                $('.frame-figure').addClass('not-fixed');
                $('.hidden-frame').hide();

                $('.below-absolute-content').css({'top' : ($('.moving-image').height() + 300) + 'px', 'position' : 'absolute'});
            }
        }

        if($('.card-types-slider').length > 0) {
            $('.card-types-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 8000,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        }

        if($('.featured-cards-slider').length > 0) {
            $('.featured-cards-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 8000,
                adaptiveHeight: true
            });
        }

        $('body').addClass('over-hidden');
        if($(window).width() < 1200) {
            $('.featured-cards-slider .single-slide').each(function() {
                $(this).css({'background-image' : 'url('+$(this).attr('data-mobile-background')+')'});
            });
        }
        $('body').removeClass('over-hidden');
    }
};

function router() {
    if($('body').hasClass('home')) {
        pagesData.homepage();
    }
}
router();

//init cookie events only if exists
function checkIfCookie()    {
    if ($('.privacy-policy-cookie').length > 0)  {
        $('.privacy-policy-cookie .accept-all').click(function()    {
            basic.cookies.set('performance_cookies', 1);
            basic.cookies.set('functionality_cookies', 1);
            basic.cookies.set('marketing_cookies', 1);
            basic.cookies.set('strictly_necessary_policy', 1);

            window.location.reload();
        });

        $('.adjust-cookies').click(function() {
            $('.customize-cookies').remove();

            $('.privacy-policy-cookie').append('<div class="customize-cookies"><button class="close-customize-cookies close-customize-cookies-popup">×</button><div class="text-center"><img src="/assets/images/cookie-icon.svg" alt="Cookie icon" class="cookie-icon"/></div><div class="text-center padding-top-10 padding-bottom-20 fs-20">Select cookies to accept:</div><div class="cookies-options-list"><ul><li><div class="pretty p-svg p-curve"><input checked disabled type="checkbox" id="strictly-necessary-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="strictly-necessary-cookies"><span>Strictly necessary</span> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Cookies essential to navigate around the website and use its features. Without them, you wouldn’t be able to use basic services like signup or login."></i></label></div></div></li><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="functionality-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="functionality-cookies">Functionality cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="These cookies allow users to customise how a website looks for them; they can remember usernames, preferences, etc."></i></label></div></div></li></ul><ul><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="performance-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="performance-cookies">Performance cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="These cookies collect data for statistical purposes on how visitors use a website, they don’t contain personal data and are used to improve user experience."></i></label></div></div></li><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="marketing-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="marketing-cookies">Marketing cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Marketing cookies are used e.g. to deliver advertisements more relevant to you or limit the number of times you see an advertisement."></i></label></div></div></li></ul></div><div class="text-center actions"><a href="javascript:void(0);" class="white-light-blue-btn white-border margin-right-10 close-customize-cookies-popup">CANCEL</a><a href="javascript:void(0);" class="light-blue-white-btn white-border custom-cookie-save">SAVE</a></div><div class="custom-triangle"></div></div>');

            initTooltips();

            $('.close-customize-cookies-popup').click(function() {
                $('.customize-cookies').remove();
            });

            $('.custom-cookie-save').click(function() {
                basic.cookies.set('strictly_necessary_policy', 1);

                if($('#functionality-cookies').is(':checked')) {
                    basic.cookies.set('functionality_cookies', 1);
                }

                if($('#marketing-cookies').is(':checked')) {
                    basic.cookies.set('marketing_cookies', 1);
                }

                if($('#performance-cookies').is(':checked')) {
                    basic.cookies.set('performance_cookies', 1);
                }

                window.location.reload();
            });
        });
    }
}

// init bootstrap tooltips
function initTooltips() {
    if($('[data-toggle="tooltip"]')) {
        $('[data-toggle="tooltip"]').tooltip();
    }
}