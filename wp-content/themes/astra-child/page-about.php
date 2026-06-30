<?php
/**
 * Template: About Page Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="about-page">

	<section class="about-hero">
		<div class="about-container">

			<h1><?php the_title(); ?></h1>

			<p class="about-subtitle">
				<?php echo get_bloginfo('description'); ?>
			</p>

		</div>
	</section>

	<section class="about-content">
		<div class="about-container">

			<?php
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
			?>

		</div>
	</section>

</main>

<?php get_footer(); ?>