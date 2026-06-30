<?php
/**
 * Header for Astra Theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php if ( apply_filters( 'astra_header_profile_gmpg_link', true ) ) : ?>
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php endif; ?>

<?php wp_head(); ?>
<?php astra_head_bottom(); ?>
</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>

<?php astra_body_top(); ?>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content">
	<?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<header class="site-header-custom">

	<div class="header-container">

		<div class="site-logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<h1 class="site-title"><?php bloginfo('name'); ?></h1>
			<?php endif; ?>
		</div>

		<button class="menu-toggle-btn"
			aria-label="Abrir menú"
			aria-expanded="false">
			<span class="hamburger"></span>
		</button>
		<nav id="site-navigation" class="main-navigation">

			<?php
			wp_nav_menu([
				'theme_location' => 'header-menu-hijo',
				'menu_id'        => 'primary_menu',
				'container'      => false,
				'depth'          => 3
			]);
			?>

		</nav>

	</div>

</header>

<div class="menu-overlay"></div>

<div id="content" class="site-content">
	<div class="ast-container">

		<?php astra_content_top(); ?>