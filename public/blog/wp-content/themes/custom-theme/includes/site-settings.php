<?php
function job_add_site_settings_dashboard_menu()    {
    add_menu_page(
        '',
        'General settings',
        'manage_options',
        'job_site_settings',
        'job_site_settings_content',
        'dashicons-admin-generic',
        4
    );
}

add_action('admin_menu', 'job_add_site_settings_dashboard_menu');

function job_site_settings_init()   {


    //==========================================WALLART================================================
    //SECTION
    add_settings_section(
        'additional_settings_section',
        __('General settings', 'dentaprime_city'),
        'site_settings_section_function',
        'job_additional_settings_section'
    );

    add_settings_field(
        'website_logo',
        __('Header logo', 'dentaprime_city'),
        'website_logo_function',
        'job_additional_settings_section',
        'additional_settings_section',
        [
            'label_for' => 'website_logo',
            'class' => 'website_logo_row',
            'website_logo_custom_data' => 'custom',
        ]
    );

    //REGISTER OPTIONS FOR EACH FIELD

    register_setting('job_additional_settings_section', 'website_logo_option');
}
/**
 * register our map_zoom_job_site_settings_init to the admin_init action hook
 */
add_action('admin_init', 'job_site_settings_init');

/**
 * custom option and settings:
 * callback functions
 */

//SETTINGS SECTION CALLBACK FUNCTION
function site_settings_section_function($args)    {
    //echo "Please set map options";
}

function website_logo_function($args){
    $options = get_option('website_logo_option');
    wp_enqueue_media();
    ?>
    <div>
        <input type="text" name="website_logo_option[<?php echo esc_attr($args['label_for']); ?>]" id="website-logo-url" class="regular-text" value="<?php echo $options['website_logo']; ?>">
        <input type="button" name="upload-btn" id="website-logo-upload-btn" class="button-secondary" value="Open media">
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#website-logo-upload-btn').click(function (e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
                    .on('select', function (e) {
                        // This will return the selected image from the Media Uploader, the result is an object
                        var uploaded_image = image.state().get('selection').first();
                        // We convert uploaded_image to a JSON object to make accessing it easier
                        var image_url = uploaded_image.toJSON().url;
                        // Let's assign the url value to the input field
                        $('#website-logo-url').val(image_url);
                    });
            });
        });
    </script>
    <?php
}

function job_site_settings_content()    {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('job_site_settings_messages', 'job_site_settings_message', __('Settings Saved', 'job_additional_settings_section'), 'updated');
    }

    settings_errors('job_site_settings_messages');

    ?>


    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post" id="job_additinal_settings">
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox" style="padding: 20px;"><?php
                settings_fields('job_additional_settings_section');
                do_settings_sections('job_additional_settings_section');
                ?></div>
            </div>
            <?php submit_button('Save changes'); ?>
        </form>
    </div>
    <?php
}