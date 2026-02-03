<?php
/**
 * The header template
 *
 * @package Bunbukan
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php
	// Favicon using Bunbukan Brussels Icon
	$favicon_url = function_exists( 'bunbukan_asset_url' )
		? bunbukan_asset_url( '/assets/images/logos/Bunbukan-Brussels-Icon.png' )
		: '';
	if ( $favicon_url ) :
		?>
		<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_url ); ?>">
		<link rel="apple-touch-icon" href="<?php echo esc_url( $favicon_url ); ?>">
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'bunbukan' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="header-container">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
				<?php
				if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
					the_custom_logo();
				} else {
					// Use Bunbukan Brussels Icon logo
					$logo_url = function_exists( 'bunbukan_asset_url' )
						? bunbukan_asset_url( '/assets/images/logos/Bunbukan-Brussels-Icon.png' )
						: '';
					if ( $logo_url ) :
						?>
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="site-logo-img" />
						<?php
					endif;
				}
				?>
			</a>

			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'bunbukan_fallback_menu',
					)
				);
				?>
			</nav>

			<?php
			// Polylang language switcher disabled – language choice is in the footer only.
			/*
			if ( function_exists( 'pll_the_languages' ) ) :
				?>
				<div class="bb-lang-switcher" aria-label="<?php echo esc_attr__( 'Language switcher', 'bunbukan' ); ?>">
					<?php pll_the_languages( array( 'show_flags' => 1, 'show_names' => 0, 'dropdown' => 0 ) ); ?>
				</div>
				<?php
			endif;
			*/
			?>

			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" id="nav-toggle">
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'bunbukan' ); ?></span>
				<span aria-hidden="true">☰</span>
			</button>
		</div>
	</header><!-- #masthead -->

