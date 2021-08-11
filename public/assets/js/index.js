console.log('Don\'t touch the code. Or do ... ¯\\_(ツ)_/¯');
window.scrollTo(0, 0);

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
        if ($('body').hasClass('home')) {
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
    },
    changePassword: function() {
        if ($('body').hasClass('change-password')) {
            $('form.change-password-form').on('submit', function(event) {
                event.preventDefault();
                var this_form_native = this;
                var this_form = $(this_form_native);

                if (this_form.find('#password').val().trim() == '') {
                    basic.showAlert('Please enter password', '', true);
                } else if (this_form.find('#password').val().trim().length < 6 || this_form.find('#password').val().trim().length > 30) {
                    basic.showAlert('Password must include minimum 6 symbols and maximum 30 symbols.', '', true);
                } else if (this_form.find('#password').val().trim() != this_form.find('#repeat-password').val().trim()) {
                    basic.showAlert('Please make sure both passwords are same.', '', true);
                } else {
                    this_form_native.submit();
                }
            });
        }
    }
};
pagesData.homepage();
pagesData.changePassword();

if (typeof(dcnCookie) != 'undefined') {
    dcnCookie.init({
        'google_app_id' : 'UA-97167262-6'
    });
}

if ($('.bottom-fixed-promo-banner').length) {
    $('.bottom-fixed-promo-banner .close-banner').click(function() {
        $('footer').removeClass('extra-bottom-padding');
        $('.bottom-fixed-promo-banner').remove();

        var now = new Date();
        var time = now.getTime();
        time += 7200 * 1000;
        now.setTime(time);
        document.cookie = 'hide-holiday-calendar-banner=1; expires=' + now.toUTCString() + ';domain=dentacoin.com;path=/;';
    });
}