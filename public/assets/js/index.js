basic.init();

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

function checkIfCookie()    {
    if($('.privacy-policy-cookie').length > 0)  {
        $('.privacy-policy-cookie .accept').click(function()    {
            basic.cookies.set('privacy_policy', 1);
            $('.privacy-policy-cookie').hide();
        });
    }
}
checkIfCookie();

function initCaptchaRefreshEvent()  {
//refreshing captcha on trying to log in admin
    if($('.refresh-captcha').length > 0)    {
        $('.refresh-captcha').click(function()  {
            $.ajax({
                type: 'GET',
                url: '/refresh-captcha',
                dataType: 'json',
                success: function (response) {
                    $('.captcha-container span').html(response.captcha);
                }
            });
        });
    }
} 
initCaptchaRefreshEvent();

//PAGES LOGIC
if($('body').hasClass('home')) {
    if($('.moving-phones-container').length) {
        $('body').addClass('overflow-hidden');
        if($(window).width() > 768) {
            setTimeout(function() {
                $('.moving-phones-container').animate({
                    'left' : '0'
                }, 1500, null, function() {
                    $('.first-phone').addClass('right-rotation');
                    $('.second-phone').addClass('right-rotation');
                    $('.third-phone').addClass('left-rotation');

                    $('.moving-phones-container').addClass('move-back-top');
                });
            }, 1000);
        }
        $('body').removeClass('overflow-hidden');
    }

    if($('.testimonials-slider').length > 0) {
        $('.testimonials-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 8000,
            adaptiveHeight: true
        });
    }

    if($('.oral-care-journey-slider .init-slider').length > 0) {
        $('.oral-care-journey-slider .init-slider').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3
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
} else if($('body').hasClass('forgotten-password')) {
    $('form#forgotten-password').on('submit', function(event) {
        var this_form = $(this);
        if(this_form.find('input[type="email"]').val().trim() == '' || !basic.validateEmail(this_form.find('input[type="email"]').val().trim())) {
            basic.showAlert('Please try again with valid email.', '', true);
            event.preventDefault();
        }
    });
} else if($('body').hasClass('withdraw-dentacare-dcn')) {
    //facebook application init
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1500240286681345',
            cookie: true,
            xfbml: true,
            version: 'v2.10'
        });
        FB.AppEvents.logPageView();
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = '//connect.facebook.net/bg_BG/sdk.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    //binding click event for facebook login btn
    $('body').on('click', '.facebook-dentacare-btn', function(rerequest) {
        //asking users only for email
        var obj = {
            scope: 'email'
        };
        if(rerequest){
            obj.auth_type = 'rerequest';
        }
        FB.login(function (response) {
            if(response.authResponse) {
                var fb_token = response.authResponse.accessToken;


                $('.response-layer').show();
                setTimeout(function() {
                    FB.api('/me?fields=id,email,name,permissions', function (response) {
                        //console.log(response);
                        var logger_email;
                        var logger_fb_id = response.id;
                        if(response.email == null) {
                            basic.showAlert('Please go to your facebook account privacy settings and make your email public. Without giving us access to your email we cannot proceed with the login.', '', true);
                            $('.response-layer').hide();
                            return true;
                        } else{
                            logger_email = response.email;
                        }

                        $.ajax({
                            type: 'POST',
                            url: '/social-authenticate-dentacare-user',
                            dataType: 'json',
                            data: {
                                email: logger_email,
                                user_id: logger_fb_id,
                                token: fb_token
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                successfulUserLogin(response);
                            }
                        });
                    });
                }, 1000);
            }
        }, obj);
    });

    //binding click event for google login btn
    $('body').on('click', '.google-dentacare-btn', function() {
        if(basic.cookies.get('social-allowed') == '') {
            basic.showAlert('Coming soon.', '', true);
            return false;
        }
    });

    $('form#dentacare-sign-in').on('submit', function(event) {
        event.preventDefault();
        var this_form = $(this);
        this_form.find('.error-handle').remove();
        var form_fields = this_form.find('.form-field');
        var submit_form = true;

        for(var i = 0, len = form_fields.length; i < len; i+=1) {
            if(form_fields.eq(i).val().trim() == '') {
                customErrorHandle(form_fields.eq(i).closest('.field-parent'), 'This field is required.');
                submit_form = false;
            } else if(form_fields.eq(i).attr('name') == 'email' && !basic.validateEmail(form_fields.eq(i).val().trim())) {
                customErrorHandle(form_fields.eq(i).closest('.field-parent'), 'Please use valid email address.');
                submit_form = false;
            }
        }

        if(submit_form) {
            $('.response-layer').show();
            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: '/authenticate-dentacare-user',
                    dataType: 'json',
                    data: {
                        email: this_form.find('input[name="email"]').val().trim(),
                        password: this_form.find('input[name="password"]').val().trim()
                    },
                    success: function (response) {
                        successfulUserLogin(response);
                    }
                });
            }, 1000);
        }
    });
}

function successfulUserLogin(response) {
    $('.response-layer').hide();
    basic.closeDialog();
    if(response.success) {
        if(response.upgradeable_content) {
            $('.upgradeable-html').html(response.upgradeable_content);

            $('form#dentacare-withdraw').on('submit', function(event) {
                event.preventDefault();
                var this_withdraw_form = $(this);
                this_withdraw_form.find('.error-handle').remove();
                var withdraw_form_fields = this_withdraw_form.find('.form-field');
                var submit_withdraw_form = true;

                if(this_withdraw_form.attr('data-stoppage') == 'true') {
                    customErrorHandle(this_withdraw_form, 'You don\'t have any DCN balance at the moment.');
                    submit_withdraw_form = false;
                }

                for (var y = 0, withdraw_form_len = withdraw_form_fields.length; y < withdraw_form_len; y+=1) {
                    if (withdraw_form_fields.eq(y).val().trim() == '') {
                        customErrorHandle(withdraw_form_fields.eq(y).closest('.field-parent'), 'This field is required.');
                        submit_withdraw_form = false;
                    } else if(withdraw_form_fields.eq(y).attr('name') == 'dentacare-address' && withdraw_form_fields.eq(y).val().trim().length != 42) {
                        customErrorHandle(withdraw_form_fields.eq(y).closest('.field-parent'), 'Please use valid Wallet Address.');
                        submit_withdraw_form = false;
                    }
                }

                if (submit_withdraw_form) {
                    $('.response-layer').show();

                    setTimeout(function() {
                        $.ajax({
                            type: 'POST',
                            url: '/submit-withdraw-dentacare-dcn',
                            dataType: 'json',
                            data: {
                                token: response.token,
                                amount: response.amount,
                                address: this_withdraw_form.find('input[name="dentacare-address"]').val().trim()
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (withdraw_response) {
                                $('.response-layer').hide();
                                basic.closeDialog();

                                if(withdraw_response.success) {
                                    this_withdraw_form.find('input[name="dentacare-address"]').val('');
                                    basic.showAlert(withdraw_response.success, '', true);
                                } else if(withdraw_response.error) {
                                    basic.showAlert(withdraw_response.error, '', true);
                                }
                            }
                        });
                    }, 1000);
                }
            });

        } else {
            basic.showAlert(response.success, '', true);
        }
    } else if(response.error) {
        basic.showAlert(response.error, '', true);
    }
}

//LOGGED IN LOGIC
if($('body').hasClass('logged-in')) {
    var add_overflow_hidden_on_hidden_box_show = false;
    var sm_screen_width = false;
    $('body').addClass('overflow-hidden');
    if($(window).width() < 992) {
        add_overflow_hidden_on_hidden_box_show = true;
        if($(window).width() > 767) {
            sm_screen_width = true;
        }
    }
    $('body').removeClass('overflow-hidden');

    if(sm_screen_width) {
        $(document).on('click', 'body', function(){
            if(!$('.hidden-box-parent').find(event.target).length) {
                $('.logged-user-right-nav .hidden-box').removeClass('show-this');
                $('.logged-user-right-nav .up-arrow').removeClass('show-this');
            }
        });
    }

    if(add_overflow_hidden_on_hidden_box_show) {
        $('.logged-user-right-nav .user-name, .logged-user-right-nav .header-avatar').click(function() {
            $('.logged-user-right-nav .hidden-box').toggleClass('show-this');
            if(sm_screen_width) {
                $('.logged-user-right-nav .up-arrow').toggleClass('show-this');
            } else {
                $('body').toggleClass('overflow-hidden');
            }
        });
    } else {
        $('.logged-user-right-nav > .hidden-box-parent').hover(function () {
            $('.logged-user-right-nav .hidden-box').addClass('show-this');
            $('.logged-user-right-nav .up-arrow').addClass('show-this');
        }, function () {
            $('.logged-user-right-nav .hidden-box').removeClass('show-this');
            $('.logged-user-right-nav .up-arrow').removeClass('show-this');
        });
    }

    $('.logged-user-right-nav .close-btn a').click(function() {
        $('.logged-user-right-nav .hidden-box').removeClass('show-this');
        if(add_overflow_hidden_on_hidden_box_show) {
            $('body').removeClass('overflow-hidden');

            if(sm_screen_width) {
                $('.logged-user-right-nav .up-arrow').removeClass('show-this');
            }
        }
    });
}

//on button click next time when you hover the button the color is bugged until you click some other element (until you move out the focus from this button)
function fixButtonsFocus() {
    $(document).on('click', '.light-blue-white-btn', function() {
        $(this).blur();
    });
    $(document).on('click', '.white-light-blue-btn', function() {
        $(this).blur();
    });
}
fixButtonsFocus();

function hidePopupOnBackdropClick() {
    $(document).on('click', '.bootbox', function(){
        var classname = event.target.className;
        classname = classname.replace(/ /g, '.');

        if(classname && !$('.' + classname).parents('.modal-dialog').length) {
            if($('.bootbox.login-signin-popup').length) {
                $('.hidden-login-form').html(hidden_popup_content);
            }
            if($('.bootbox.login-signin-popup').length) {
                $('.hidden-login-form').html(hidden_popup_content);
            }
            bootbox.hideAll();
        }
    });
}
hidePopupOnBackdropClick();

var hidden_popup_content = $('.hidden-login-form').html();
//call the popup for login/sign for patient and dentist
function bindLoginSigninPopupShow() {
    $(document).on('click', '.show-login-signin', function() {
        openLoginSigninPopup();
    });
}
bindLoginSigninPopupShow();

function openLoginSigninPopup() {
    basic.closeDialog();
    $('.hidden-login-form').html('');
    basic.showDialog(hidden_popup_content, 'login-signin-popup', null, true);

    $('.login-signin-popup .dentist .form-register .address-suggester').removeClass('dont-init');

    initAddressSuggesters();

    $('.login-signin-popup .popup-header-action a').click(function() {
        $('.login-signin-popup .popup-body > .inline-block').addClass('custom-hide');
        $('.login-signin-popup .popup-body .'+$(this).attr('data-type')).removeClass('custom-hide');
    });

    $('.login-signin-popup .call-sign-up').click(function() {
        $('.login-signin-popup .form-login').hide();
        $('.login-signin-popup .form-register').show();
    });

    $('.login-signin-popup .call-log-in').click(function() {
        $('.login-signin-popup .form-login').show();
        $('.login-signin-popup .form-register').hide();
    });

    // ====================== PATIENT LOGIN/SIGNUP LOGIC ======================

    //login
    $('.login-signin-popup .patient .form-register #privacy-policy-registration-patient').on('change', function() {
        if($(this).is(':checked')) {
            $('.login-signin-popup .patient .form-register .facebook-custom-btn').removeAttr('custom-stopper');
            $('.login-signin-popup .patient .form-register .civic-custom-btn').removeAttr('custom-stopper');
        } else {
            $('.login-signin-popup .patient .form-register .facebook-custom-btn').attr('custom-stopper', 'true');
            $('.login-signin-popup .patient .form-register .civic-custom-btn').attr('custom-stopper', 'true');
        }
    });

    $(document).on('civicCustomBtnClicked', function (event) {
        $('.login-signin-popup .patient .form-register .step-errors-holder').html('');
    });

    $(document).on('civicRead', async function (event) {
        $('.response-layer').show();
    });

    $(document).on('receivedFacebookToken', async function (event) {
        $('.response-layer').show();
    });

    $(document).on('facebookCustomBtnClicked', function (event) {
        $('.login-signin-popup .patient .form-register .step-errors-holder').html('');
    });

    $(document).on('customCivicFbStopperTriggered', function (event) {
        customErrorHandle($('.login-signin-popup .patient .form-register .step-errors-holder'), 'Please agree with our privacy policy.');
    });
    // ====================== /PATIENT LOGIN/SIGNUP LOGIC ======================

    // ====================== DENTIST LOGIN/SIGNUP LOGIC ======================
    //DENTIST LOGIN
    $('.login-signin-popup form#dentist-login').on('submit', async function(event) {
        var this_form_native = this;
        var this_form = $(this_form_native);
        event.preventDefault();
        //clear prev errors
        if($('.login-signin-popup form#dentist-login .error-handle').length) {
            $('.login-signin-popup form#dentist-login .error-handle').remove();
        }

        var form_fields = this_form.find('.form-field');
        var submit_form = true;
        for(var i = 0, len = form_fields.length; i < len; i+=1) {
            if(form_fields.eq(i).attr('type') == 'email' && !basic.validateEmail(form_fields.eq(i).val().trim())) {
                customErrorHandle(form_fields.eq(i).closest('.field-parent'), 'Please use valid email address.');
                submit_form = false;
            } else if(form_fields.eq(i).attr('type') == 'password' && form_fields.eq(i).val().length < 6) {
                customErrorHandle(form_fields.eq(i).closest('.field-parent'), 'Passwords must be min length 6.');
                submit_form = false;
            }

            if(form_fields.eq(i).val().trim() == '') {
                customErrorHandle(form_fields.eq(i).closest('.field-parent'), 'This field is required.');
                submit_form = false;
            }
        }

        //check if existing account
        var check_account_response = await $.ajax({
            type: 'POST',
            url: '/check-dentist-account',
            dataType: 'json',
            data: {
                email: $('.login-signin-popup form#dentist-login input[name="email"]').val().trim(),
                password: $('.login-signin-popup form#dentist-login input[name="password"]').val().trim()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if(submit_form) {
            if(check_account_response.success) {
                fireGoogleAnalyticsEvent('DentistLogin', 'Click', 'Dentist Login');
                this_form_native.submit();
            } else if(check_account_response.error) {
                customErrorHandle(this_form.find('input[name="password"]').closest('.field-parent'), check_account_response.message);
            }
        }
    });

    //DENTIST REGISTER
    $('.login-signin-popup .dentist .form-register .prev-step').click(function() {
        var current_step = $('.login-signin-popup .dentist .form-register .step.visible');
        var current_prev_step = current_step.prev();
        current_step.removeClass('visible');
        if(current_prev_step.hasClass('first')) {
            $(this).hide();
        }
        current_prev_step.addClass('visible');

        $('.login-signin-popup .dentist .form-register .next-step').val('Next');
        $('.login-signin-popup .dentist .form-register .next-step').attr('data-current-step', current_prev_step.attr('data-step'));
    });

    //SECOND STEP INIT LOGIC
    $('.login-signin-popup .step.second .user-type-container .user-type').click(function() {
        $('.login-signin-popup .step.second .user-type-container .user-type').removeClass('active');
        $(this).addClass('active');
        $('.login-signin-popup .step.second .user-type-container [name="user-type"]').val($(this).attr('data-type'));
    });

    //THIRD STEP INIT LOGIC
    $('.login-signin-popup #dentist-country').on('change', function() {
        $('.login-signin-popup .step.third .phone .country-code').html('+'+$(this).find('option:selected').attr('data-code'));
    });

    //FOURTH STEP INIT LOGIC
    styleAvatarUploadButton('.bootbox.login-signin-popup .dentist .form-register .step.fourth .avatar .btn-wrapper label');
    initCaptchaRefreshEvent();

    //DENTIST REGISTERING FORM
    $('.login-signin-popup .dentist .form-register .next-step').click(async function() {
        var this_btn = $(this);

        switch(this_btn.attr('data-current-step')) {
            case 'first':
                var first_step_inputs = $('.login-signin-popup .dentist .form-register .step.first .form-field');
                var errors = false;
                $('.login-signin-popup .dentist .form-register .step.first').parent().find('.error-handle').remove();
                for(var i = 0, len = first_step_inputs.length; i < len; i+=1) {
                    if(first_step_inputs.eq(i).attr('type') == 'email' && !basic.validateEmail(first_step_inputs.eq(i).val().trim())) {
                        customErrorHandle(first_step_inputs.eq(i).closest('.field-parent'), 'Please use valid email address.');
                        errors = true;
                    } else if(first_step_inputs.eq(i).attr('type') == 'email' && basic.validateEmail(first_step_inputs.eq(i).val().trim())) {
                        //coredb check if email is free
                        var check_email_if_free_response = await checkIfFreeEmail(first_step_inputs.eq(i).val().trim());
                        if(check_email_if_free_response.error) {
                            customErrorHandle(first_step_inputs.eq(i).closest('.field-parent'), 'The email has already been taken.');
                            errors = true;
                        }
                    }

                    if(first_step_inputs.eq(i).attr('type') == 'password' && first_step_inputs.eq(i).val().length < 6) {
                        customErrorHandle(first_step_inputs.eq(i).closest('.field-parent'), 'Passwords must be min length 6.');
                        errors = true;
                    }

                    if(first_step_inputs.eq(i).val().trim() == '') {
                        customErrorHandle(first_step_inputs.eq(i).closest('.field-parent'), 'This field is required.');
                        errors = true;
                    }
                }

                if($('.login-signin-popup .dentist .form-register .step.first .form-field.password').val().trim() != $('.login-signin-popup .step.first .form-field.repeat-password').val().trim()) {
                    customErrorHandle($('.login-signin-popup .step.first .form-field.repeat-password').closest('.field-parent'), 'Both passwords don\'t match.');
                    errors = true;
                }

                if(!errors) {
                    fireGoogleAnalyticsEvent('DentistRegistration', 'ClickNext', 'DentistRegistrationStep1');

                    $('.login-signin-popup .dentist .form-register .step').removeClass('visible');
                    $('.login-signin-popup .dentist .form-register .step.second').addClass('visible');
                    $('.login-signin-popup .prev-step').show();

                    this_btn.attr('data-current-step', 'second');
                    this_btn.val('Next');
                }
                break;
            case 'second':
                var second_step_inputs = $('.login-signin-popup .dentist .form-register .step.second .form-field.required');
                var errors = false;
                $('.login-signin-popup .dentist .form-register .step.second').find('.error-handle').remove();

                //check form-field fields
                for(var i = 0, len = second_step_inputs.length; i < len; i+=1) {
                    if(second_step_inputs.eq(i).is('select')) {
                        //IF SELECT TAG
                        if(second_step_inputs.eq(i).val().trim() == '') {
                            customErrorHandle(second_step_inputs.eq(i).closest('.field-parent'), 'This field is required.');
                            errors = true;
                        }
                    } else if(second_step_inputs.eq(i).is('input')) {
                        //IF INPUT TAG
                        if(second_step_inputs.eq(i).val().trim() == '') {
                            customErrorHandle(second_step_inputs.eq(i).closest('.field-parent'), 'This field is required.');
                            errors = true;
                        }
                    }
                }

                //check if latin name accepts only LATIN characters
                if(!/^[a-z A-Z]+$/.test($('.login-signin-popup .dentist .form-register .step.second input[name="latin-name"]').val().trim())) {

                    customErrorHandle($('.login-signin-popup .dentist .form-register .step.second input[name="latin-name"]').closest('.field-parent'), 'This field should contain only latin characters.');
                    errors = true;
                }

                //check if privacy policy checkbox is checked
                if(!$('.login-signin-popup .dentist .form-register .step.second #privacy-policy-registration').is(':checked')) {
                    customErrorHandle($('.login-signin-popup .dentist .form-register .step.second .privacy-policy-row'), 'Please agree with our <a href="//dentacoin.com/privacy-policy" target="_blank">Privacy policy</a>.');
                    errors = true;
                }

                if(!errors) {
                    fireGoogleAnalyticsEvent('DentistRegistration', 'ClickNext', 'DentistRegistrationStep2');

                    $('.login-signin-popup .dentist .form-register .step').removeClass('visible');
                    $('.login-signin-popup .dentist .form-register .step.third').addClass('visible');

                    this_btn.attr('data-current-step', 'third');
                    this_btn.val('Next');
                }
                break;
            case 'third':
                var third_step_inputs = $('.login-signin-popup .dentist .form-register .step.third .form-field.required');
                var errors = false;
                $('.login-signin-popup .dentist .form-register .step.third').find('.error-handle').remove();

                for(var i = 0, len = third_step_inputs.length; i < len; i+=1) {
                    if(third_step_inputs.eq(i).is('select')) {
                        //IF SELECT TAG
                        if(third_step_inputs.eq(i).val().trim() == '') {
                            customErrorHandle(third_step_inputs.eq(i).closest('.field-parent'), 'This field is required.');
                            errors = true;
                        }
                    } else if(third_step_inputs.eq(i).is('input')) {
                        //IF INPUT TAG
                        if(third_step_inputs.eq(i).val().trim() == '') {
                            customErrorHandle(third_step_inputs.eq(i).closest('.field-parent'), 'This field is required.');
                            errors = true;
                        }
                        if(third_step_inputs.eq(i).attr('type') == 'url' && !basic.validateUrl(third_step_inputs.eq(i).val().trim())) {
                            customErrorHandle(third_step_inputs.eq(i).closest('.field-parent'), 'Please enter your website URL starting with http:// or https://.');
                            errors = true;
                        }else if(third_step_inputs.eq(i).attr('type') == 'number' && !basic.validatePhone(third_step_inputs.eq(i).val().trim())) {
                            customErrorHandle(third_step_inputs.eq(i).closest('.field-parent'), 'Please use valid numbers.');
                            errors = true;
                        }
                    }
                }

                var validate_phone = await validatePhone($('.login-signin-popup .dentist .form-register .step.third input[name="phone"]').val().trim(), $('.login-signin-popup .dentist .form-register .step.third select[name="country-code"]').val());
                if(has(validate_phone, 'success') && !validate_phone.success) {
                    customErrorHandle($('.login-signin-popup .dentist .form-register .step.third input[name="phone"]').closest('.field-parent'), 'Please use valid phone.');
                    errors = true;
                }

                if(!errors) {
                    fireGoogleAnalyticsEvent('DentistRegistration', 'ClickNext', 'DentistRegistrationStep3');

                    $('.login-signin-popup .dentist .form-register .step').removeClass('visible');
                    $('.login-signin-popup .dentist .form-register .step.fourth').addClass('visible');

                    this_btn.attr('data-current-step', 'fourth');
                    this_btn.val('Create account');
                }
                break;
            case 'fourth':
                $('.login-signin-popup .dentist .form-register .step.fourth').find('.error-handle').remove();
                var errors = false;
                //checking if empty avatar
                if($('.dentist .form-register .step.fourth #custom-upload-avatar').val().trim() == '') {
                    customErrorHandle($('.step.fourth .step-errors-holder'), 'Please select avatar.');
                    errors = true;
                }

                //checking if no specialization checkbox selected
                if($('.login-signin-popup .dentist .form-register .step.fourth [name="specializations[]"]:checked').val() == undefined) {
                    customErrorHandle($('.login-signin-popup .step.fourth .step-errors-holder'), 'Please select specialization/s.');
                    errors = true;
                }

                //check captcha
                if(!$('.login-signin-popup .dentist .form-register .step.fourth .captcha-parent').length || !$('.login-signin-popup .dentist .form-register .step.fourth #register-captcha').length) {
                    errors = true;
                    window.location.reload();
                } else {
                    var check_captcha_response = await checkCaptcha($('.login-signin-popup .dentist .form-register .step.fourth #register-captcha').val().trim());
                    if(check_captcha_response.error) {
                        customErrorHandle($('.login-signin-popup .step.fourth .step-errors-holder'), 'Please enter correct captcha.');
                        errors = true;
                    }
                }

                if(!errors) {
                    fireGoogleAnalyticsEvent('DentistRegistration', 'ClickNext', 'DentistRegistrationComplete');

                    //submit the form
                    $('.response-layer').show();
                    $('.login-signin-popup form#dentist-register').submit();
                }
                break;
        }
    });
    return false;
    // ====================== /DENTIST LOGIN/SIGNUP LOGIC ======================
}

//INIT LOGIC FOR ALL STEPS
function customErrorHandle(el, string) {
    el.append('<div class="error-handle">'+string+'</div>');
}

function onEnrichProfileFormSubmit() {
    $(document).on('submit', '.enrich-profile-container #enrich-profile', function(event) {
        var errors = false;
        var this_form = $(this);
        this_form.find('.error-handle').remove();
        if(this_form.find('[name="description"]').val().trim() == '') {
            errors = true;
            customErrorHandle(this_form.find('[name="description"]').parent(), 'Please enter short description.');
        }

        if(!errors) {
            if($('.enrich-profile-container').attr('data-type') == 'dentist') {
                fireGoogleAnalyticsEvent('DentistRegistration', 'ClickSave', 'DentistDescr');
            } else if($('.enrich-profile-container').attr('data-type') == 'clinic') {
                fireGoogleAnalyticsEvent('DentistRegistration', 'ClickSave', 'ClinicDescr');
            }
        } else {
            event.preventDefault();
        }
    });
}
onEnrichProfileFormSubmit();

function styleAvatarUploadButton(label_el)    {
    if(jQuery(".upload-file.avatar").length) {
        jQuery(".upload-file.avatar").each(function(key, form){
            var this_file_btn_parent = jQuery(this);
            if(this_file_btn_parent.attr('data-current-user-avatar')) {
                this_file_btn_parent.find('.btn-wrapper').append('<label for="custom-upload-avatar" role="button" style="background-image:url('+this_file_btn_parent.attr('data-current-user-avatar')+');"><div class="inner"><i class="fa fa-plus fs-0" aria-hidden="true"></i><div class="inner-label fs-0">Add profile photo</div></div></label>');
            } else {
                this_file_btn_parent.find('.btn-wrapper').append('<label for="custom-upload-avatar" role="button"><div class="inner"><i class="fa fa-plus" aria-hidden="true"></i><div class="inner-label">Add profile photo</div></div></label>');
            }

            var inputs = document.querySelectorAll('.inputfile');
            Array.prototype.forEach.call(inputs, function(input) {
                var label    = input.nextElementSibling,
                    labelVal = label.innerHTML;

                input.addEventListener('change', function(e) {
                    readURL(this, label_el);

                    var fileName = '';
                    if(this.files && this.files.length > 1)
                        fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                    else
                        fileName = e.target.value.split('\\').pop();

                    /*if(fileName) {
                        if(load_filename_to_other_el)    {
                            $(this).closest('.form-row').find('.file-name').html('<i class="fa fa-file-text-o" aria-hidden="true"></i>' + fileName);
                        }else {
                            label.querySelector('span').innerHTML = fileName;
                        }
                    }else{
                        label.innerHTML = labelVal;
                    }*/
                });
                // Firefox bug fix
                input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
                input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });
            });
        });
    }
}

function readURL(input, label_el) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //SHOW THE IMAGE ON LOAD
            $(label_el).css({'background-image' : 'url("'+e.target.result+'")'});
            $(label_el).find('.inner i').addClass('fs-0');
            $(label_el).find('.inner .inner-label').addClass('fs-0');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

//return from CoreDB if email is taken
async function checkIfFreeEmail(email) {
    return await $.ajax({
        type: 'POST',
        url: '/check-email',
        dataType: 'json',
        data: {
            email: email
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

async function checkCaptcha(captcha) {
    return await $.ajax({
        type: 'POST',
        url: '/check-captcha',
        dataType: 'json',
        data: {
            captcha: captcha
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

async function validatePhone(phone, country_code) {
    return await $.ajax({
        type: 'POST',
        url: 'https://api.dentacoin.com/api/phone/',
        dataType: 'json',
        data: {
            phone: phone,
            country_code: country_code
        }
    });
}

function apiEventsListeners() {
    //login
    $(document).on('successResponseCoreDBApi', async function (event) {
        if(event.response_data.token) {
            var custom_form_obj = {
                token: event.response_data.token,
                id: event.response_data.data.id,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if($('input[type="hidden"][name="route"]').length && $('input[type="hidden"][name="slug"]').length) {
                custom_form_obj.route = $('input[type="hidden"][name="route"]').val();
                custom_form_obj.slug = $('input[type="hidden"][name="slug"]').val();
            }

            //check if CoreDB returned address for this user and if its valid one
            if(basic.objHasKey(custom_form_obj, 'address') != null && innerAddressCheck(custom_form_obj.address)) {
                //var current_dentists_for_logging_user = await App.assurance_methods.getWaitingContractsForPatient(custom_form_obj.address);
                //if(current_dentists_for_logging_user.length > 0) {
                //custom_form_obj.have_contracts = true;
                //}
            }

            if(event.response_data.new_account) {
                //REGISTER
                if(event.platform_type == 'facebook') {
                    fireGoogleAnalyticsEvent('PatientRegistration', 'ClickFB', 'Patient Registration FB');
                } else if(event.platform_type == 'civic') {
                    fireGoogleAnalyticsEvent('PatientRegistration', 'ClickNext', 'Patient Registration Civic');
                }
            } else {
                //LOGIN
                if(event.platform_type == 'facebook') {
                    fireGoogleAnalyticsEvent('PatientLogin', 'Click', 'Login FB');
                } else if(event.platform_type == 'civic') {
                    fireGoogleAnalyticsEvent('PatientLogin', 'Click', 'Login Civic');
                }
            }

            customJavascriptForm('/patient-login', custom_form_obj, 'post');
        }
    });

    $(document).on('errorResponseCoreDBApi', function (event) {
        var error_popup_html = '';
        if(event.response_data.errors) {
            for(var key in event.response_data.errors) {
                error_popup_html += event.response_data.errors[key]+'<br>';
            }
        }

        $('.response-layer').hide();
        basic.showAlert(error_popup_html, '', true);
    });
}
apiEventsListeners();

function customJavascriptForm(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function bindGoogleAlikeButtonsEvents() {
    //google alike style for label/placeholders
    $('body').on('click', '.custom-google-label-style label', function() {
        $(this).addClass('active-label');
        if($('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
            $(this).parent().find('input').addClass('blue-green-border');
        }
    });

    $('body').on('keyup change focusout', '.custom-google-label-style input', function() {
        var value = $(this).val().trim();
        if (value.length) {
            $(this).closest('.custom-google-label-style').find('label').addClass('active-label');
            if($(this).closest('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                $(this).addClass('blue-green-border');
            }
        } else {
            $(this).closest('.custom-google-label-style').find('label').removeClass('active-label');
            if($(this).closest('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                $(this).removeClass('blue-green-border');
            }
        }
    });
}
bindGoogleAlikeButtonsEvents();

//check if object has property
function has(object, key) {
    return object ? hasOwnProperty.call(object, key) : false;
}

// =================================== GOOGLE ANALYTICS TRACKING LOGIC ======================================

function fireGoogleAnalyticsEvent(category, action, label, value) {
    var event_obj = {
        'event_action' : action,
        'event_category': category,
        'event_label': label
    };

    if(value != undefined) {
        event_obj.value = value;
    }

    //gtag('event', label, event_obj);
}

// =================================== /GOOGLE ANALYTICS TRACKING LOGIC ======================================