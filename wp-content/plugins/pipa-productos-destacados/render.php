<?php
/**
 * Renderiza los productos marcados con el estado "Destacado".
 *
 * @var array $attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cantidad       = isset( $attributes['cantidad'] ) ? intval( $attributes['cantidad'] ) : 0;
$posts_per_page = $cantidad > 0 ? $cantidad : -1;

$query = new WP_Query(
	array(
		'post_type'      => 'producto',
		'posts_per_page' => $posts_per_page,
		'tax_query'      => array(
			array(
				'taxonomy' => 'estado',
				'field'    => 'slug',
				'terms'    => 'destacado',
			),
		),
	)
);

if ( ! $query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<div class="wp-block-pipa-productos-destacados pipa-grid pipa-grid--productos pipa-grid--destacados">
	<?php
	while ( $query->have_posts() ) :
		$query->the_post();

		$imagen      = function_exists( 'get_field' ) ? get_field( 'imagen' ) : null;
		$descripcion = function_exists( 'get_field' ) ? get_field( 'descripcion' ) : '';
		$precio      = function_exists( 'get_field' ) ? get_field( 'precio' ) : '';
		$titulo      = ( function_exists( 'get_field' ) ? get_field( 'titulo' ) : '' ) ?: get_the_title();
		$meta        = $precio ? '<span class="pipa-precio">$' . esc_html( $precio ) . '</span>' : '';

		if ( function_exists( 'pipa_render_card' ) ) :
			echo pipa_render_card( $imagen, $descripcion, $meta );
		else :
			?>
			<div class="pipa-card">
				<?php if ( $imagen ) : ?>
					<div class="pipa-card-image-wrap">
						<img src="<?php echo esc_url( $imagen['url'] ); ?>" alt="<?php echo esc_attr( $titulo ); ?>" class="pipa-card-img">
					</div>
				<?php endif; ?>
				<div class="pipa-card-body">
					<h3 class="pipa-card-title"><?php echo esc_html( $titulo ); ?></h3>
					<?php if ( $descripcion ) : ?>
						<p class="pipa-card-desc"><?php echo esc_html( $descripcion ); ?></p>
					<?php endif; ?>
					<?php if ( $meta ) : ?>
						<div class="pipa-card-meta"><?php echo $meta; ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endif;
	endwhile;
	wp_reset_postdata();
	?>
</div>
