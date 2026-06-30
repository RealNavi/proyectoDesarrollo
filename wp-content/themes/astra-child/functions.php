<?php

// Traer los estilos
function enqueue_styles(){
    wp_enqueue_style('parent_styles', get_template_directory_uri().'/style.css');
    
    wp_enqueue_style('astra_child_styles', get_stylesheet_directory_uri().'/assets/css/styles.css',[],'1.0.0');
}

add_action('wp_enqueue_scripts','enqueue_styles');


function enqueue_scripts(){    
    wp_enqueue_script('menu-toggle', get_stylesheet_directory_uri().'/assets/js/menu-toggle.js',[],'1.0.0');
}

add_action('wp_enqueue_scripts','enqueue_scripts');

// Registrar ubicación del menú
function register_menus() {
    register_nav_menus([
        'header-menu-hijo' => __( 'Header Menu Hijo', 'astra-child' ),
    ]);
}
add_action( 'init', 'register_menus' );

