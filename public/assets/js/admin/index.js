basic.addCsrfTokenToAllAjax();

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