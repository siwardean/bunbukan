<?php
/**
 * The footer template
 *
 * @package Bunbukan
 */
?>

	<footer id="colophon" class="site-footer">
		<div class="site-footer__container">
			<div class="site-footer__content">
				<div class="site-footer__brand">
					<p class="site-title"><?php bloginfo( 'name' ); ?></p>
					<p><?php echo esc_html__( 'Traditional Okinawan Martial Arts in Brussels', 'bunbukan' ); ?></p>
				</div>

				<nav class="footer-navigation">
					<?php
					if ( has_nav_menu( 'menu-2' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'menu-2',
								'menu_id'        => 'footer-menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
					} else {
						echo '<ul id="footer-menu" class="footer-menu-fallback">';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#home">Home</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#about">About</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#disciplines">Disciplines</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#instructors">Instructors</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '#contact">Contact</a></li>';
						echo '</ul>';
					}
					?>
				</nav>

				<div class="site-footer__contact">
					<p><strong>Location</strong></p>
					<p>Bunbukan Brussels</p>
					<p>Part of Budo Club Berchem</p>
					<p>Rue des Chalets 30</p>
					<p>1030 Schaerbeek, Belgium</p>
				</div>
			</div>

			<div class="site-footer__bottom">
				<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

