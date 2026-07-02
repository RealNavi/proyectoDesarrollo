<?php
/**
 * Renderiza el carrusel de testimonios de clientes.
 *
 * @var array $attributes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$titulo = isset( $attributes['titulo'] ) ? trim( $attributes['titulo'] ) : '';

$query = new WP_Query(
	array(
		'post_type'      => 'testimonio',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( ! $query->have_posts() ) {
	wp_reset_postdata();
	return;
}

wp_enqueue_style( 'pipa-testimonios-style' );
wp_enqueue_script( 'pipa-testimonios-carousel' );

static $pipa_testimonios_instance = 0;
$pipa_testimonios_instance++;
$carousel_id = 'pipa-testimonios-' . $pipa_testimonios_instance;
?>
<div class="pipa-testimonios-section">
	<?php if ( $titulo ) : ?>
		<h2 class="pipa-testimonios-titulo"><?php echo esc_html( $titulo ); ?></h2>
	<?php endif; ?>

	<div id="<?php echo esc_attr( $carousel_id ); ?>" class="pipa-testimonios-carousel" data-pipa-carousel>
		<div class="pipa-testimonios-track">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();

				$texto        = function_exists( 'get_field' ) ? get_field( 'testimonio_texto' ) : get_the_excerpt();
				$calificacion = function_exists( 'get_field' ) ? (int) get_field( 'testimonio_calificacion' ) : 5;
				$calificacion = max( 1, min( 5, $calificacion ?: 5 ) );
				?>
				<div class="pipa-testimonio-slide">
					<?php if ( has_post_thumbnail() ) : ?>
						<img class="pipa-testimonio-foto" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
					<?php else : ?>
						<div class="pipa-testimonio-foto pipa-testimonio-foto--placeholder">&#128100;</div>
					<?php endif; ?>
					<div class="pipa-testimonio-estrellas" aria-hidden="true"><?php echo str_repeat( '&#9733;', $calificacion ) . str_repeat( '&#9734;', 5 - $calificacion ); ?></div>
					<?php if ( $texto ) : ?>
						<p class="pipa-testimonio-texto">&ldquo;<?php echo esc_html( $texto ); ?>&rdquo;</p>
					<?php endif; ?>
					<p class="pipa-testimonio-nombre"><?php the_title(); ?></p>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<button type="button" class="pipa-carousel-arrow pipa-carousel-arrow--prev" aria-label="<?php esc_attr_e( 'Testimonio anterior', 'pipa-testimonios' ); ?>">&#8249;</button>
		<button type="button" class="pipa-carousel-arrow pipa-carousel-arrow--next" aria-label="<?php esc_attr_e( 'Siguiente testimonio', 'pipa-testimonios' ); ?>">&#8250;</button>

		<div class="pipa-carousel-dots"></div>
	</div>
</div>
