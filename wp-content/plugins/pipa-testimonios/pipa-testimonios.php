<?php
/**
 * Plugin Name: Pipa - Testimonios
 * Description: Registra el CPT "Testimonio" (editable desde WordPress como Productos o Entradas) y un bloque de Gutenberg / shortcode que muestra un carrusel con los testimonios de clientes.
 * Version: 1.0.0
 * Author: Pipa
 * Text Domain: pipa-testimonios
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIPA_TESTIMONIOS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PIPA_TESTIMONIOS_URL', plugin_dir_url( __FILE__ ) );

function pipa_testimonios_register_cpt() {
	register_post_type(
		'testimonio',
		array(
			'label'        => __( 'Testimonios', 'pipa-testimonios' ),
			'labels'       => array(
				'name'               => __( 'Testimonios', 'pipa-testimonios' ),
				'singular_name'      => __( 'Testimonio', 'pipa-testimonios' ),
				'add_new_item'       => __( 'Agregar Testimonio', 'pipa-testimonios' ),
				'edit_item'          => __( 'Editar Testimonio', 'pipa-testimonios' ),
				'all_items'          => __( 'Todos los Testimonios', 'pipa-testimonios' ),
				'search_items'       => __( 'Buscar Testimonios', 'pipa-testimonios' ),
				'not_found'          => __( 'No se encontraron testimonios', 'pipa-testimonios' ),
			),
			'public'       => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'thumbnail', 'custom-fields' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'testimonio' ),
		)
	);
}
add_action( 'init', 'pipa_testimonios_register_cpt' );

function pipa_testimonios_register_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_pipa_testimonios',
			'title'    => __( 'Datos del Testimonio', 'pipa-testimonios' ),
			'fields'   => array(
				array(
					'key'      => 'field_pipa_testimonio_texto',
					'label'    => __( 'Testimonio', 'pipa-testimonios' ),
					'name'     => 'testimonio_texto',
					'type'     => 'textarea',
					'required' => 1,
				),
				array(
					'key'           => 'field_pipa_testimonio_calificacion',
					'label'         => __( 'Calificación (1 a 5)', 'pipa-testimonios' ),
					'name'          => 'testimonio_calificacion',
					'type'          => 'number',
					'default_value' => 5,
					'min'           => 1,
					'max'           => 5,
					'step'          => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'testimonio',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'pipa_testimonios_register_fields' );

function pipa_testimonios_register_assets() {
	wp_register_style( 'pipa-testimonios-style', PIPA_TESTIMONIOS_URL . 'assets/css/carousel.css', array(), filemtime( PIPA_TESTIMONIOS_PATH . 'assets/css/carousel.css' ) );
	wp_register_script( 'pipa-testimonios-carousel', PIPA_TESTIMONIOS_URL . 'assets/js/carousel.js', array(), filemtime( PIPA_TESTIMONIOS_PATH . 'assets/js/carousel.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'pipa_testimonios_register_assets' );

function pipa_testimonios_register_block() {
	wp_register_script(
		'pipa-testimonios-editor',
		PIPA_TESTIMONIOS_URL . 'editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render' ),
		filemtime( PIPA_TESTIMONIOS_PATH . 'editor.js' ),
		true
	);

	register_block_type( PIPA_TESTIMONIOS_PATH );
}
add_action( 'init', 'pipa_testimonios_register_block' );

function pipa_testimonios_shortcode( $atts ) {
	$atts       = shortcode_atts( array( 'titulo' => '' ), $atts, 'testimonios_carousel' );
	$attributes = array( 'titulo' => $atts['titulo'] );

	ob_start();
	include PIPA_TESTIMONIOS_PATH . 'render.php';
	return ob_get_clean();
}
add_shortcode( 'testimonios_carousel', 'pipa_testimonios_shortcode' );
