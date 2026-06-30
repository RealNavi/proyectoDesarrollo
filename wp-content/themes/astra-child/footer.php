<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php astra_content_bottom(); ?>

	</div><!-- ast-container -->
	</div><!-- #content -->

<?php astra_content_after(); ?>

<!-- FOOTER SIMPLE PIPA -->
<footer class="site-footer-simple">

	<div class="footer-container-simple">

		<div class="site-logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<h1 class="site-title"><?php bloginfo('name'); ?></h1>
			<?php endif; ?>
		</div>

		<div class="footer-right">
			<?php
			wp_nav_menu([
				'theme_location' => 'header-menu-hijo',
				'container' => false,
				'items_wrap' => '<ul>%3$s</ul>'
			]);
			?>
		</div>

	</div>

	<div class="footer-bottom-simple">
		<p>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados.</p>
	</div>

</footer>

</div><!-- #page -->

<?php astra_body_bottom(); ?>
<?php wp_footer(); ?>

</body>
</html>