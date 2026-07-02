<?php
/**
 * Plugin Name: Pipa - Productos Destacados
 * Description: Bloque de Gutenberg que carga y muestra los productos existentes marcados con el estado "Destacado".
 * Version: 1.0.0
 * Author: Pipa
 * Text Domain: pipa-productos-destacados
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIPA_DESTACADOS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PIPA_DESTACADOS_URL', plugin_dir_url( __FILE__ ) );

function pipa_destacados_register_block() {
	wp_register_script(
		'pipa-productos-destacados-editor',
		PIPA_DESTACADOS_URL . 'editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render' ),
		filemtime( PIPA_DESTACADOS_PATH . 'editor.js' ),
		true
	);

	register_block_type( PIPA_DESTACADOS_PATH );
}
add_action( 'init', 'pipa_destacados_register_block' );

function pipa_destacados_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'cantidad' => 0 ), $atts, 'productos_destacados' );

	$attributes = array( 'cantidad' => intval( $atts['cantidad'] ) );

	ob_start();
	include PIPA_DESTACADOS_PATH . 'render.php';
	return ob_get_clean();
}
add_shortcode( 'productos_destacados', 'pipa_destacados_shortcode' );
