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
				<p class="site-title"><?php echo esc_html__('Bunbukan Brussels', 'bunbukan'); ?></p>
				<p><?php echo esc_html__('Traditional Okinawan Martial Arts in Brussels', 'bunbukan'); ?></p>
			</div>

			<nav class="footer-navigation">
				<?php
				if (has_nav_menu('menu-2')) {
					wp_nav_menu(
						array(
							'theme_location' => 'menu-2',
							'menu_id' => 'footer-menu',
							'container' => false,
							'depth' => 1,
						)
					);
				} else {
					echo '<ul id="footer-menu" class="footer-menu-fallback">';
					echo '<li><a href="' . esc_url(home_url('/')) . '#home">' . esc_html__('Home', 'bunbukan') . '</a></li>';
					echo '<li><a href="' . esc_url(home_url('/')) . '#about">' . esc_html__('About', 'bunbukan') . '</a></li>';
					echo '<li><a href="' . esc_url(home_url('/')) . '#disciplines">' . esc_html__('Disciplines', 'bunbukan') . '</a></li>';
					echo '<li><a href="' . esc_url(home_url('/')) . '#instructors">' . esc_html__('Instructors', 'bunbukan') . '</a></li>';
					echo '<li><a href="' . esc_url(home_url('/')) . '#contact">' . esc_html__('Contact', 'bunbukan') . '</a></li>';
					echo '</ul>';
				}
				?>
			</nav>

			<div class="site-footer__contact">
				<p><strong><?php echo esc_html__('Location', 'bunbukan'); ?></strong></p>
				<p><?php echo esc_html__('Bunbukan Brussels', 'bunbukan'); ?></p>
				<p><?php echo esc_html__('Part of Budo Club Berchem', 'bunbukan'); ?></p>
				<p>Rue des Chalets 30</p>
				<p>1030 Schaerbeek, Belgium</p>
			</div>
		</div>

		<div class="site-footer__bottom">
			<p>&copy; <?php echo date('Y'); ?> <?php echo esc_html__('Bunbukan Brussels', 'bunbukan'); ?>.
				<?php echo esc_html__('All rights reserved.', 'bunbukan'); ?></p>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>