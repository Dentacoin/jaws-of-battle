<?php

function job_add_menu()  {
    add_theme_support('menus');

    register_nav_menu('footer', 'Footer Menu');
}
add_action('init', 'job_add_menu');
