<?php
/**
 * The footer template
 *
 * @package Bunbukan
 */
?>

<footer id="colophon" class="site-footer">
	<div class="site-footer__separator"></div>
	<div class="site-footer__container">
		<div class="site-footer__content">
			<div class="site-footer__brand">
				<p class="site-title"><?php echo esc_html__('Bunbukan Brussels', 'bunbukan'); ?></p>
				<p><?php echo esc_html__('Traditional Okinawan Martial Arts in Brussels', 'bunbukan'); ?></p>
				
				<?php
				// Language selector (custom dropdown opening upward)
				if (function_exists('pll_the_languages')) :
					$languages = pll_the_languages(array('dropdown' => 0, 'raw' => 1, 'hide_if_empty' => 0));
					if (!empty($languages)) :
						$current_lang = pll_current_language('name');
				?>
				<div class="site-footer__language">
					<div class="language-dropdown" data-dropdown>
						<button type="button" class="language-dropdown__toggle" aria-expanded="false">
							<span><?php echo esc_html($current_lang); ?></span>
							<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="18 15 12 9 6 15"/>
							</svg>
						</button>
						<ul class="language-dropdown__menu">
							<?php foreach ($languages as $lang) : ?>
								<li>
									<a href="<?php echo esc_url($lang['url']); ?>" class="<?php echo $lang['current_lang'] ? 'is-active' : ''; ?>">
										<?php echo esc_html($lang['name']); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; else : ?>
				<?php
					$languages = array(
						'fr' => 'Français',
						'en' => 'English',
						'nl' => 'Nederlands',
						'ja' => '日本語',
					);
					$current_lang_code = substr(get_locale(), 0, 2);
					$current_lang_name = isset($languages[$current_lang_code]) ? $languages[$current_lang_code] : 'Français';
				?>
				<div class="site-footer__language">
					<div class="language-dropdown" data-dropdown>
						<button type="button" class="language-dropdown__toggle" aria-expanded="false">
							<span><?php echo esc_html($current_lang_name); ?></span>
							<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="18 15 12 9 6 15"/>
							</svg>
						</button>
						<ul class="language-dropdown__menu">
							<?php foreach ($languages as $code => $name) : ?>
								<li>
									<a href="<?php echo esc_url(add_query_arg('lang', $code)); ?>" class="<?php echo ($code === $current_lang_code) ? 'is-active' : ''; ?>">
										<?php echo esc_html($name); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
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
				
				<div class="site-footer__social">
					<p><strong><?php echo esc_html__('Follow Us', 'bunbukan'); ?></strong></p>
					<div class="site-footer__social-links">
						<a href="https://www.facebook.com/www.bunbukan.be" target="_blank" rel="noopener noreferrer" class="site-footer__social-link" aria-label="<?php echo esc_attr__('Facebook', 'bunbukan'); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
							</svg>
						</a>
						<a href="https://www.instagram.com/bunbukan.karate.eu" target="_blank" rel="noopener noreferrer" class="site-footer__social-link" aria-label="<?php echo esc_attr__('Instagram', 'bunbukan'); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="site-footer__bottom">
			<p>&copy; <?php echo date('Y'); ?> <?php echo esc_html__('Bunbukan Brussels', 'bunbukan'); ?>.
				<?php echo esc_html__('All rights reserved.', 'bunbukan'); ?></p>
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_template_part('template-parts/cookie-consent'); ?>

<?php wp_footer(); ?>

</body>

</html>