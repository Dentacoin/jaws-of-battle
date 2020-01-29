var basic = {
    options: {
        alert: null
    },
    init: function(opt) {
        basic.addCsrfTokenToAllAjax();
        //basic.stopMaliciousInspect();
    },
    cookies: {
        set: function(name, value) {
            if(name == undefined){
                name = "cookieLaw";
            }
            if(value == undefined){
                value = 1;
            }
            var d = new Date();
            d.setTime(d.getTime() + (10*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = name + "=" + value + "; " + expires + ";path=/";
            if(name == "cookieLaw"){
                $(".cookies_popup").slideUp();
            }
        },
        erase: function(name) {
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        },
        get: function(name) {
            if(name == undefined){
                var name = "cookieLaw";
            }
            name = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
            }
            return "";
        }
    },
    fixPlaceholders: function() {
        $("input[data-placeholder]").each(function(){
            if($(this).data("placeholders-fixed") == undefined){
                $(this).data("placeholders-fixed", true);

                basic.setInputsPlaceholder($(this));

                $focus_function = "if($(this).val()=='" + $(this).data("placeholder") + "'){ $(this).val(''); }";
                if ($(this).attr("onkeydown") != undefined) {
                    $focus_function = $(this).attr("onkeydown") + "; " + $focus_function;
                }
                $(this).attr("onkeydown", $focus_function);

                $blur_function = "if($(this).val()==''){ $(this).val('" + $(this).data("placeholder") + "'); }";
                if ($(this).attr("onblur") != undefined) {
                    $blur_function = $(this).attr("onblur") + "; " + $blur_function;
                }
                $(this).attr("onblur", $blur_function);
            }
        });
    },
    clearPlaceholders: function(extra_filter) {
        if(extra_filter == undefined){
            extra_filter = "";
        }
        $("input[data-placeholder]" + extra_filter).each(function(){
            if($(this).val() == $(this).data("placeholder")){
                $(this).val('');
            }
        })
    },
    setPlaceholders: function(){
        $("input[data-placeholder]").each(function(){
            basic.setInputsPlaceholder($(this));
        });
    },
    setInputsPlaceholder: function(input){
        if($(input).val()==""){
            $(input).val($(input).data("placeholder"));
        }
    },
    fixBodyModal: function() {
        if($(".modal-dialog").length>0 && !$("body").hasClass('modal-open')){
            $("body").addClass('modal-open');
        }
    },
    fixZIndexBackdrop: function() {
        if(jQuery('.bootbox').length > 1) {
            var last_z = jQuery('.bootbox').eq(jQuery('.bootbox').length - 2).css("z-index");
            jQuery('.bootbox').last().css({'z-index': last_z+2}).next('.modal-backdrop').css({'z-index': last_z+1});
        }
    },
    showAlert: function(message, class_name, vertical_center) {
        basic.realShowDialog(message, "alert", class_name, null, null, vertical_center);
    },
    showConfirm: function(message, class_name, params, vertical_center) {
        basic.realShowDialog(message, "confirm", class_name, params, null, vertical_center);
    },
    showDialog: function(message, class_name, type, vertical_center) {
        if(type === undefined){
            type = null;
        }
        basic.realShowDialog(message, "dialog", class_name, null, type, vertical_center);
    },
    realShowDialog: function(message, dialog_type, class_name, params, type, vertical_center) {
        if(class_name === undefined){
            class_name = "";
        }
        if(type === undefined){
            type = null;
        }
        if(vertical_center === undefined){
            vertical_center = null;
        }

        var atrs = {
            "message": message,
            "animate": false,
            "show": false,
            "className": class_name
        };

        if(dialog_type == "confirm" && params!=undefined && params.buttons == undefined){
            atrs.buttons = {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            }
        }
        if(params != undefined){
            for (var key in params) {
                atrs[key] = params[key];
            }
        }

        var dialog = eval("bootbox." + dialog_type)(atrs);
        dialog.on('hidden.bs.modal', function(){
            basic.fixBodyModal();
            if(type != null)    {
                $('.single-application figure[data-slug="'+type+'"]').parent().focus();
            }
        });
        dialog.on('shown.bs.modal', function(){
            if(vertical_center != null) {
                basic.verticalAlignModal();
            }
            basic.fixZIndexBackdrop();
        });
        dialog.modal('show');
    },
    verticalAlignModal: function(message) {
        $("body .modal-dialog").each(function(){
            $(this).css("margin-top", Math.max(20, ($(window).height() - $(this).height()) / 2));
        })
    },
    closeDialog: function (){
        bootbox.hideAll();
    },
    request: {
        initialize: false,
        result: null,
        submit: function (url, data, options, callback, curtain) {
            options = $.extend({
                type: 'POST',
                dataType: 'json',
                async: true
            }, options);
            if (basic.request.initialize && options.async == false) {
                console.log(['Please wait for parent request']);
            }
            else {
                basic.request.initialize = true;
                return $.ajax({
                    url: url,
                    data: data,
                    type: options.type,
                    dataType: options.dataType,
                    async: options.async,
                    beforeSend: function() {
                        if (curtain !== null) {
                            basic.addCurtain();
                        }
                    },
                    success: function (response) {
                        basic.request.result = response;
                        if (curtain !== null) {
                            basic.removeCurtain();
                        }
                        basic.request.initialize = false;
                        if (typeof callback === 'function') {
                            callback(response);
                        }
                    },
                    error: function(){
                        basic.request.initialize = false;
                    }
                });
            }
        },
        validate: function(form, callback, data){
            //if data is passed skip clearing all placeholders and removing messages. it's done inside the calling function
            if(data == undefined) {
                basic.clearPlaceholders();
                $(".input-error-message").remove();
                data = form.serialize();
            }
            return basic.request.submit(SITE_URL+"validate/", data, {async: false}, function(res){
                if (typeof callback === 'function') {
                    callback();
                }
            },  null);
        },
        markValidationErrors: function(validation_result, form){
            basic.setPlaceholders();
            if (typeof validation_result.all_errors == "undefined") {
                if (typeof validation_result.message != "undefined") {
                    basic.showAlert(validation_result.message);
                    return true;
                }
            } else {
                var all_errors = JSON.parse(validation_result.all_errors);
                for (var param_name in all_errors) {
                    //if there is error, but no name for it, pop it in alert
                    if(Object.keys(all_errors).length == 1 && $('[name="'+param_name+'"]').length == 0) {
                        basic.showAlert(all_errors[param_name]);
                        return false;
                    }

                    if(form == undefined){
                        var input = $('[name="'+param_name+'"]');
                    }else{
                        var input = form.find('[name="'+param_name+'"]');
                    }
                    basic.request.removeValidationErrors(input);
                    if (input.closest('.input-error-message-holder')) {
                        input.closest('.input-error-message-holder').append('<div class="input-error-message">'+all_errors[param_name]+'</div>');
                    } else {
                        input.after('<div class="input-error-message">'+all_errors[param_name]+'</div>');
                    }
                    //basic.setInputsPlaceholder(input);
                }
            }
        },
        removeValidationErrors: function(input){
            input.closest('.input-error-message-holder').find(".input-error-message").remove();
            input.parent().remove(".input-error-message");
        }
    },
    alert: function(message) {
        basic.options.alert(message);
    },
    addCurtain: function(){
        $("body").prepend('<div class="curtain"></div>');
    },
    removeCurtain: function(){
        $("body .curtain").remove();
    },
    validateEmail: function(email)   {
        return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
    },
    isInViewport: function(el) {
        var elementTop = $(el).offset().top;
        var elementBottom = elementTop + $(el).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    },
    isMobile: function() {
        var isMobile = false; //initiate as false
// device detection
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4)))  {
            isMobile = true;
        }
        return isMobile;
    },
    addCsrfTokenToAllAjax: function ()    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    },
    stopMaliciousInspect: function()  {
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.onkeydown = function(e) {
            if(event.keyCode == 123) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        }
    }
};
basic.init();

jQuery(window).on('load', function()   {

});

jQuery(window).on('resize', function(){

});

jQuery(document).ready(function()   {
    addHTMLEditor();
    initDataTable();
});

jQuery(window).on('scroll', function () {

});

function initDataTable()    {
    if($('table.table.table-without-reorder').length > 0) {
        if($('table.table.table-without-reorder').hasClass('media-table'))  {
            $('table.table.table-without-reorder.media-table').DataTable().on('draw.dt', function (){
                var pagination_id = null;
                if($(this).attr('data-id-in-action') != undefined) {
                    pagination_id = $(this).attr('data-id-in-action');
                }
                var close_button;
                if($(this).attr('data-close-btn') == 'false')   {
                    close_button = false;
                }else if($(this).attr('data-close-btn') == 'true')   {
                    close_button = true;
                }
                useMediaEvent(pagination_id, close_button);
            });
        }else {
            $('table.table.table-without-reorder').DataTable({
                sort: false
            });
        }
    }
    if($('table.table.table-with-reorder').length > 0) {
        var table = $('table.table.table-with-reorder').DataTable({
            rowReorder: true
        });
        $('table.table.table-with-reorder').addClass('sortable');
        table.on('row-reorder', function(e, diff, edit) {
            var order_object = {};
            for(let i = 0, len = diff.length; i < len; i+=1) {
                order_object[$(diff[i].node).data('id')] = diff[i].newPosition;
            }
            $.ajax({
                type: 'POST',
                url: SITE_URL + '/'+$('table.table.table-with-reorder').attr('data-action')+'/update-order',
                data: {
                    'order_object' : order_object
                },
                dataType: 'json',
                success: function (response) {
                    if(response.success)    {
                        basic.showAlert(response.success, '', true);
                    }
                }
            });
        });
    }
}

function addHTMLEditor(){
    if($('.ckeditor-init').length > 0)   {
        $('.ckeditor-init').each(function() {
            var options = $.extend({
                    height: 350,
                    allowedContent: true,
                    disallowedContent: 'script',
                    contentsCss : ['/dist/css/front-libs-style.css', '/assets/css/style.css']
                }, {on: {
                        pluginsLoaded: function() {
                            var editor = this,
                                config = editor.config;
                            config.removeButtons = 'Image';

                            //registering command to call the callery
                            editor.addCommand("openGalleryCommand", {
                                exec:function() {
                                    openMedia(null, null, null, editor);
                                }
                            });

                            //adding button to the ckeditor which interrupts with command
                            editor.ui.addButton("GalleryButton", {
                                label: "Gallery",
                                command: "openGalleryCommand",
                                toolbar: "insert",
                                icon: "/assets/images/logo.svg"
                            });
                        }, instanceReady: function() {
                            // Use line breaks for block elements, tables, and lists.
                            var dtd = CKEDITOR.dtd;
                            for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
                                this.dataProcessor.writer.setRules( e, {
                                    indent: true,
                                });
                            }
                        }
                    }},
                options);
            CKEDITOR.replace($(this).attr('id'), options);
            //CKEDITOR.addCss('body{background:blue;}');
        });
    }
}

//opening media popup with all the images in the DB
function openMedia(id, close_btn, type, editor)    {
    var data = {};
    if(id === undefined) {
        id = null;
    }
    if(close_btn === undefined) {
        close_btn = false;
    }
    if(type === undefined) {
        type = null;
    }else {
        data['type'] = type;
    }
    if(editor === undefined) {
        editor = null;
    }
    $.ajax({
        type: 'POST',
        url: SITE_URL + '/media/open',
        data: data,
        dataType: 'json',
        success: function (response) {
            if(response.success) {
                basic.showDialog(response.success, 'media-popup');
                initDataTable();
                $('table.table.table-without-reorder.media-table').attr('data-id-in-action', id).attr('data-close-btn', close_btn);
                saveImageAltsEvent();
                initUploadMediaLogic();
                useMediaEvent(id, close_btn, editor);
            }else {
                basic.showAlert('<div class="text-center">No images exist in the media.</div>', '', true);
            }
        }
    });
}

//on click append image to post before saving the post
function useMediaEvent(id, close_btn, editor) {
    $('.media-popup .use-media').click(function()   {
        var type = $(this).attr('data-type');
        if(editor != null)  {
            if(type == 'file') {
                editor.insertHtml('<a href="'+$(this).closest('tr').attr('data-src')+'">'+$(this).closest('tr').attr('data-src')+'</a>');
            }else if(type == 'image')   {
                editor.insertHtml('<img class="small-image" alt="'+$(this).closest('tr').attr('data-alt')+'" src="'+$(this).closest('tr').attr('data-src')+'"/>');
            }
        }else {
            if(type == 'file')  {
                if(id != null)	{
                    $('.media[data-id="'+id+'"] .image-visualization').html('<a href="'+$(this).closest('tr').attr('data-src')+'">'+$(this).closest('tr').attr('data-src')+'</a>');
                    $('.media[data-id="'+id+'"] input.hidden-input-image').val($(this).closest('tr').attr('data-id'));
                    $('.media[data-id="'+id+'"] input.hidden-input-url').val($(this).closest('tr').attr('data-src'));
                }else {
                    $('.image-visualization').html('<a href="'+$(this).closest('tr').attr('data-src')+'">'+$(this).closest('tr').attr('data-src')+'</a>');
                    $('input.hidden-input-image').val($(this).closest('tr').attr('data-id'));
                    $('input.hidden-input-url').val($(this).closest('tr').attr('data-src'));
                }
            }else if(type == 'image')    {
                if(id != null)	{
                    $('.media[data-id="'+id+'"] .image-visualization').html('<img class="small-image" src="'+$(this).closest('tr').attr('data-src')+'"/>');
                    $('.media[data-id="'+id+'"] input.hidden-input-image').val($(this).closest('tr').attr('data-id'));
                }else {
                    $('.image-visualization').html('<img class="small-image" src="'+$(this).closest('tr').attr('data-src')+'"/>');
                    $('input.hidden-input-image').val($(this).closest('tr').attr('data-id'));
                }
            }
            if(close_btn) {
                $('.image-visualization').append('<span class="inline-block-top remove-image"><i class="fa fa-times" aria-hidden="true"></i></span>');
            }
        }
        basic.closeDialog();
    });
}

//removing image from posts listing pages
function removeImage()  {
    $(document).on('click', '.remove-image', function()    {
        $(this).closest('.media').find('.hidden-input-image').val('');
        $(this).closest('.media').find('.image-visualization').html('');
    });
}
removeImage();

function deleteMedia() {
    $(document).on('click', '.delete-media', function()    {
        var this_btn = $(this);
        $.ajax({
            type: 'POST',
            url: SITE_URL + '/media/delete/'+this_btn.closest('tr').attr('data-id'),
            dataType: 'json',
            success: function (response) {
                if(response.success)    {
                    basic.showAlert(response.success, '', true);
                    this_btn.closest('tr').remove();
                } else if(response.error) {
                    basic.showAlert(response.error, '', true);
                }
            }
        });
    });
}
deleteMedia();

//saving image alts on media listing pages
function saveImageAltsEvent()   {
    if($('.save-image-alts').length > 0)    {
        $('.save-image-alts').click(function()  {
            var alts_object = {};
            for(let i = 0, len = $('.media-table tbody tr').length; i < len; i+=1)  {
                alts_object[$('.media-table tbody tr').eq(i).attr('data-id')] = $('.media-table tbody tr').eq(i).find('.alt-attribute').val().trim();
            }
            $.ajax({
                type: 'POST',
                url: SITE_URL + '/media/update-media-alts',
                data: {
                    'alts_object' : alts_object
                },
                dataType: 'json',
                success: function (response) {
                    if(response.success)    {
                        basic.showAlert(response.success, '', true);
                    }
                }
            });
        });
    }
}
saveImageAltsEvent();

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

function generateUrl(str)  {
    var str_arr = str.toLowerCase().split('');
    var cyr = [
        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','_'
    ];
    var lat = [
        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya', '-', '-'
    ];
    for(var i = 0; i < str_arr.length; i+=1)  {
        for(var y = 0; y < cyr.length; y+=1)    {
            if(str_arr[i] == cyr[y])    {
                str_arr[i] = lat[y];
            }
        }
    }
    return str_arr.join('').replace(/-+/g, '-');
}

if($('.add-edit-menu-element select[name="type"]').length > 0) {
    $('.add-edit-menu-element select[name="type"]').on('change', function() {
        var type = $(this).val();
        $.ajax({
            type: 'POST',
            url: SITE_URL + '/menus/change-url-option',
            data: {
                'type' : type
            },
            dataType: 'json',
            success: function (response) {
                if(response.success) {
                    $('.add-edit-menu-element .type-result').html(response.success);
                }
            }
        });
    });
}

function initUploadMediaLogic() {
    if($('form#upload-media').length) {
        $('form#upload-media').submit(function(event) {
            event.preventDefault();
            var this_form = this;

            $.ajax({
                type: 'POST',
                url: SITE_URL + '/media/ajax-upload',
                data: new FormData($(this_form)[0]),
                async: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if(response.success) {
                        basic.showAlert(response.success, '', true);

                        if($('.media-table').length) {
                            $('.media-table tbody').prepend(response.html_with_images);

                            if($('table.table.table-without-reorder.media-table').attr('data-id-in-action') != undefined && $('table.table.table-without-reorder.media-table').attr('data-close-btn') != undefined) {
                                useMediaEvent($('table.table.table-without-reorder.media-table').attr('data-id-in-action'), $('table.table.table-without-reorder.media-table').attr('data-close-btn'), null);
                            }
                        }
                    } else if(response.error) {
                        basic.showAlert(response.error, '', true);
                    }
                    $(this_form).find('input[name="images[]"]').val('');
                }
            });
        });
    }
}
initUploadMediaLogic();