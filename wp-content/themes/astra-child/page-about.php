<?php
/**
 * Template Name: About
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();

$custom_logo_id = get_theme_mod('custom_logo');
?>

<main class="about-page">
	<section class="about-hero">
		<div class="about-container">
			<?php
			if ($custom_logo_id) {
				echo wp_get_attachment_image($custom_logo_id, 'full', false, [
					'class' => 'about-logo',
					'alt' => get_bloginfo('name')
				]);
			}
			?>
			<h1>Sobre Nosotros</h1>
			<p class="about-intro">
				En Pipa Foods trabajamos para brindar una experiencia de compra confiable, cercana y sencilla,
				ofreciendo productos de calidad para cada uno de nuestros clientes.
			</p>
		</div>
	</section>
	<section class="about-section">
		<div class="about-container about-grid">
			<div class="about-card">
				<h2>Misión</h2>
				<p>
					Brindar a nuestros clientes una experiencia de compra confiable, sencilla y cercana, ofreciendo una
					amplia selección de productos de calidad que satisfagan sus necesidades, respaldados por un servicio
					personalizado, innovación constante y un compromiso con la excelencia.
				</p>
			</div>
			<div class="about-card">
				<h2>Visión</h2>
				<p>
					Ser una tienda referente en el mercado por la calidad de nuestros productos, la innovación en
					nuestros servicios y la confianza que generamos en nuestros clientes, consolidándonos como una marca
					reconocida por ofrecer soluciones que mejoran su experiencia de compra y fomentan relaciones
					duraderas.
				</p>
			</div>
		</div>
	</section>
	<section class="contact-section">
		<div class="about-container">
			<h2>Contacto</h2>
			<div class="contact-grid">
				<div class="contact-card">
					<h3>Teléfono</h3>
					<p>+506 8888 8888</p>
				</div>
				<div class="contact-card">
					<h3>Correo</h3>
					<p>contact@pipafoods.com</p>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>