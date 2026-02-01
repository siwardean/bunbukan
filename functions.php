<?php
/**
 * Bunbukan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bunbukan
 */

if (!defined('BUNBUKAN_VERSION')) {
	define('BUNBUKAN_VERSION', '1.0.0');
}

/**
 * Load Pods configuration and helpers
 */
require_once get_template_directory() . '/inc/pods-config.php';
require_once get_template_directory() . '/inc/pods-helpers.php';
require_once get_template_directory() . '/inc/pods-migration-data.php';

/**
 * Asset helper: prefer theme asset, fallback to React bunbukan-eu asset if theme file missing.
 *
 * @param string $theme_rel Rel path from theme root, like '/assets/images/logo.png'
 * @param string $react_rel Rel path from WP root, like '/bunbukan-eu/public/images/logo-footer-bnbkn.png'
 * @return string URL or empty string
 */
function bunbukan_asset_url($theme_rel, $react_rel = '')
{
	$theme_rel = '/' . ltrim((string) $theme_rel, '/');
	$theme_abs = get_template_directory() . $theme_rel;
	if (file_exists($theme_abs)) {
		return get_template_directory_uri() . $theme_rel;
	}

	if ($react_rel) {
		$react_rel = '/' . ltrim((string) $react_rel, '/');
		$react_abs = ABSPATH . ltrim($react_rel, '/');
		if (file_exists($react_abs)) {
			return site_url($react_rel);
		}
	}

	return '';
}

/**
 * Sync assets from React build folder into theme assets (idempotent).
 *
 * We use this because copying binary assets via tooling may not always be available.
 * Sources are expected in: /bunbukan-eu/public/images and /bunbukan-eu/public/affiliations (relative to WP root).
 */
function bunbukan_sync_assets_from_react($force = false)
{
	$src_images = ABSPATH . 'bunbukan-eu/public/images/';
	$src_affiliations = ABSPATH . 'bunbukan-eu/public/affiliations/';
	$dst_images = get_template_directory() . '/assets/images/';
	$dst_affiliations = $dst_images . 'affiliations/';
	$dst_logos = $dst_images . 'logos/';

	if (!is_dir($src_images) && !is_dir($src_affiliations)) {
		return;
	}

	wp_mkdir_p($dst_images);
	wp_mkdir_p($dst_affiliations);
	wp_mkdir_p($dst_logos);

	$map = array(
		// React filename => Theme filename
		'logo-footer-bnbkn.png' => 'logo.png',
		'budo_club_stage_nakamoto.jpg' => 'instructors.jpg',
		'karate-1.jpg' => 'karate.jpg',
		'kobudo-demo.jpg' => 'kobudo.jpg',
		'dojo.jpg' => 'dojo.jpg',
		'alain_berckmans_kobudo05.jpg' => 'instructor-alain.jpg',
		'Bunbukan-Brussels-favicon.png' => 'favicon.png',
	);

	foreach ($map as $src => $dst) {
		$from = $src_images . $src;
		$to = $dst_images . $dst;
		if (!file_exists($from)) {
			continue;
		}
		if (!$force && file_exists($to)) {
			continue;
		}
		@copy($from, $to);
	}

	// Affiliation logos (new structure)
	$affiliations = array(
		'budo-club-berchem-ico-3.png',
		'vka-ico.png',
		'shitokai-ico-2.png',
		'dento-shito-ryu-ico-8.png',
		'ono-ha-itto-ryu-ico-7.png',
		'Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
		'sport-brussel-ico-4.png',
	);

	foreach ($affiliations as $logo) {
		$from = $src_affiliations . $logo;
		$to = $dst_affiliations . $logo;
		if (!file_exists($from)) {
			continue;
		}
		if (!$force && file_exists($to)) {
			continue;
		}
		@copy($from, $to);
	}

	// Main logo (keep in logos folder)
	$main_logo_from = $src_affiliations . 'budo-club-berchem-ico-3.png';
	$main_logo_to = $dst_logos . 'budo-club-berchem-ico-3.png';
	if (file_exists($main_logo_from) && ($force || !file_exists($main_logo_to))) {
		@copy($main_logo_from, $main_logo_to);
	}
}

add_action(
	'after_switch_theme',
	function () {
		bunbukan_sync_assets_from_react(false);
	}
);

// Optional manual sync: /wp-admin/?bunbukan_sync_assets=1
add_action(
	'admin_init',
	function () {
		if (!current_user_can('manage_options')) {
			return;
		}
		if (empty($_GET['bunbukan_sync_assets'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		bunbukan_sync_assets_from_react(true);
	}
);

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function bunbukan_setup()
{
	// Translations
	load_theme_textdomain('bunbukan', get_template_directory() . '/languages');

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support('post-thumbnails');

	// Custom Logo (Appearance → Customize → Site Identity)
	add_theme_support(
		'custom-logo',
		array(
			'height' => 160,
			'width' => 160,
			'flex-height' => true,
			'flex-width' => true,
		)
	);

	// Register navigation menus
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary Menu', 'bunbukan'),
			'menu-2' => esc_html__('Footer Menu', 'bunbukan'),
		)
	);

	/*
	 * Switch default core markup to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/*
	 * Hybrid Theme - Block Editor Support
	 */
	add_theme_support('wp-block-styles');
	add_theme_support('align-wide');
	add_theme_support('editor-styles');

	// Responsive embeds
	add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'bunbukan_setup');

/**
 * Custom image sizes.
 */
function bunbukan_image_sizes()
{
	add_image_size('fullwidth', 1200);
	add_image_size('instructor', 400, 400, true);
}
add_action('after_setup_theme', 'bunbukan_image_sizes');

/**
 * Enqueue scripts and styles.
 */
function bunbukan_scripts()
{
	// Styles
	$style_ver = BUNBUKAN_VERSION;
	$style_path = get_stylesheet_directory() . '/style.css';
	if (file_exists($style_path)) {
		$style_ver = (string) filemtime($style_path);
	}
	wp_enqueue_style('bunbukan-style', get_stylesheet_uri(), array(), $style_ver);
	wp_enqueue_style('modern-normalize', '//cdn.jsdelivr.net/npm/modern-normalize@3.0.1/modern-normalize.min.css', array(), BUNBUKAN_VERSION);

	// JavaScript
	if (file_exists(get_template_directory() . '/assets/js/script.js')) {
		$script_ver = BUNBUKAN_VERSION;
		$script_path = get_template_directory() . '/assets/js/script.js';
		if (file_exists($script_path)) {
			$script_ver = (string) filemtime($script_path);
		}
		wp_enqueue_script('bunbukan-script', get_template_directory_uri() . '/assets/js/script.js', array(), $script_ver, true);
	}

	// Ken Burns effect for about section
	if (file_exists(get_template_directory() . '/assets/js/ken-burns.js')) {
		$kb_script_ver = BUNBUKAN_VERSION;
		$kb_script_path = get_template_directory() . '/assets/js/ken-burns.js';
		if (file_exists($kb_script_path)) {
			$kb_script_ver = (string) filemtime($kb_script_path);
		}
		wp_enqueue_script('bunbukan-ken-burns', get_template_directory_uri() . '/assets/js/ken-burns.js', array(), $kb_script_ver, true);
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	// Cookie consent script
	if (file_exists(get_template_directory() . '/assets/js/cookie-consent.js')) {
		$cookie_script_ver = BUNBUKAN_VERSION;
		$cookie_script_path = get_template_directory() . '/assets/js/cookie-consent.js';
		if (file_exists($cookie_script_path)) {
			$cookie_script_ver = (string) filemtime($cookie_script_path);
		}
		wp_enqueue_script('bunbukan-cookie-consent', get_template_directory_uri() . '/assets/js/cookie-consent.js', array(), $cookie_script_ver, true);
	}
}
add_action('wp_enqueue_scripts', 'bunbukan_scripts');

/**
 * Remove Dojo and Affiliations items from primary navigation.
 */
function bunbukan_refine_primary_menu($items, $args)
{
	if (empty($args->theme_location) || 'menu-1' !== $args->theme_location) {
		return $items;
	}

	// Remove Dojo branch
	$items = preg_replace(
		'/<li[^>]*>\s*<a[^>]*href="[^"]*#dojo"[^>]*>.*?<\/a>\s*<\/li>/i',
		'',
		(string) $items
	);

	// Remove Affiliations branch
	$items = preg_replace(
		'/<li[^>]*>\s*<a[^>]*href="[^"]*#affiliations"[^>]*>.*?<\/a>\s*<\/li>/i',
		'',
		(string) $items
	);

	return $items;
}
add_filter('wp_nav_menu_items', 'bunbukan_refine_primary_menu', 20, 2);

/**
 * Fallback menu for primary navigation.
 */
function bunbukan_fallback_menu()
{
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url(home_url('/')) . '#home">' . esc_html__('Home', 'bunbukan') . '</a></li>';
	echo '<li><a href="' . esc_url(home_url('/')) . '#about">' . esc_html__('About', 'bunbukan') . '</a></li>';
	echo '<li><a href="' . esc_url(home_url('/')) . '#disciplines">' . esc_html__('Disciplines', 'bunbukan') . '</a></li>';
	echo '<li><a href="' . esc_url(home_url('/')) . '#instructors">' . esc_html__('Instructors', 'bunbukan') . '</a></li>';
	echo '<li><a href="' . esc_url(home_url('/')) . '#contact">' . esc_html__('Contact', 'bunbukan') . '</a></li>';
	echo '</ul>';
}

/**
 * Changes markup for search form.
 */
function bunbukan_search_form($form)
{
	$form = '<form method="get" class="search-form" action="' . home_url('/') . '" >
		<div role="search"><label for="Search">' . __('Search for:') . '</label>
			<input type="text" value="' . get_search_query() . '" name="s" id="Search" class="search-field">
			<input type="submit" id="searchsubmit" value="' . esc_attr__('Submit') . '" class="search-submit">
		</div>
	</form>';

	return $form;
}
add_filter('get_search_form', 'bunbukan_search_form', 40);

/**
 * Add meta box to display media used on the front page
 */
function bunbukan_add_page_media_meta_box()
{
	add_meta_box(
		'bunbukan-page-media',
		__('Médias utilisés sur la page', 'bunbukan'),
		'bunbukan_page_media_meta_box_callback',
		'page',
		'side',
		'low'
	);
}
add_action('add_meta_boxes', 'bunbukan_add_page_media_meta_box');

/**
 * Display media meta box content
 */
function bunbukan_page_media_meta_box_callback($post)
{
	$template = get_page_template_slug($post->ID);
	$is_front_page = (get_option('page_on_front') == $post->ID);
	$images_found = array();

	// Only show for front page
	if (!$is_front_page && $template !== 'front-page.php') {
		echo '<p style="padding: 10px; color: #666; font-style: italic;">Cette meta box n\'affiche les médias que pour la page d\'accueil.</p>';
		return;
	}

	// Hero Background
	$hero_bg = bunbukan_get_field('hero_background', $post->ID);
	$images_found[] = array(
		'label' => 'Image de fond Hero',
		'field_name' => 'hero_background',
		'image' => $hero_bg,
		'default' => get_template_directory_uri() . '/assets/images/hero/hero-background.gif',
	);

	// About Image 1
	$about_image = bunbukan_get_field('about_image', $post->ID);
	$images_found[] = array(
		'label' => 'Image À propos 1',
		'field_name' => 'about_image',
		'image' => $about_image,
		'default' => get_template_directory_uri() . '/assets/images/about/about-heritage.jpg',
	);

	// About Image 2 (slideshow)
	$about_image_2 = bunbukan_get_field('about_image_2', $post->ID);
	$images_found[] = array(
		'label' => 'Image À propos 2 (slideshow)',
		'field_name' => 'about_image_2',
		'image' => $about_image_2,
		'default' => get_template_directory_uri() . '/assets/images/about/about-masters.jpg',
	);

	// Karate Image
	$karate_image = bunbukan_get_field('karate_image', $post->ID);
	$images_found[] = array(
		'label' => 'Image Karate',
		'field_name' => 'karate_image',
		'image' => $karate_image,
		'default' => get_template_directory_uri() . '/assets/images/disciplines/discipline-karate.jpg',
	);

	// Karate Logo
	$karate_logo = bunbukan_get_field('karate_logo', $post->ID);
	$images_found[] = array(
		'label' => 'Logo Karate (fond)',
		'field_name' => 'karate_logo',
		'image' => $karate_logo,
		'default' => get_template_directory_uri() . '/assets/images/logos/logo-shitoryu.jpg',
	);

	// Kobudo Image
	$kobudo_image = bunbukan_get_field('kobudo_image', $post->ID);
	$images_found[] = array(
		'label' => 'Image Kobudō',
		'field_name' => 'kobudo_image',
		'image' => $kobudo_image,
		'default' => get_template_directory_uri() . '/assets/images/disciplines/discipline-kobudo.jpg',
	);

	// Kobudo Logo
	$kobudo_logo = bunbukan_get_field('kobudo_logo', $post->ID);
	$images_found[] = array(
		'label' => 'Logo Kobudō (fond)',
		'field_name' => 'kobudo_logo',
		'image' => $kobudo_logo,
		'default' => get_template_directory_uri() . '/assets/images/logos/logo-bunbukan-bg.png',
	);

	// Instructor Images
	for ($i = 1; $i <= 3; $i++) {
		$instructor_image = bunbukan_get_field("instructor_{$i}_image", $post->ID);
		$default_images = array(
			1 => '/assets/images/instructors/instructor-arnaud.png',
			2 => '/assets/images/instructors/instructor-alain.jpg',
			3 => '/assets/images/instructors/instructor-quentin.png',
		);
		$instructor_names = array(
			1 => 'Arnaud',
			2 => 'Alain',
			3 => 'Quentin',
		);
		$images_found[] = array(
			'label' => 'Photo Instructeur ' . $i . ' (' . $instructor_names[$i] . ')',
			'field_name' => "instructor_{$i}_image",
			'image' => $instructor_image,
			'default' => get_template_directory_uri() . $default_images[$i],
		);
	}

	// Affiliation Logos
	$affiliation_names = array(
		1 => 'Budo Club Berchem',
		2 => 'VKA',
		3 => 'Shitokai Belgium',
		4 => 'Dento Shito-Ryu',
		5 => 'Ono-ha Itto-Ryu',
		6 => 'Hontai Yoshin Ryu',
		7 => 'Sport Brussel',
	);
	$affiliation_logos = array(
		1 => '/assets/images/affiliations/budo-club-berchem-ico-3.png',
		2 => '/assets/images/affiliations/vka-ico.png',
		3 => '/assets/images/affiliations/shitokai-ico-2.png',
		4 => '/assets/images/affiliations/dento-shito-ryu-ico-8.png',
		5 => '/assets/images/affiliations/ono-ha-itto-ryu-ico-7.png',
		6 => '/assets/images/affiliations/Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
		7 => '/assets/images/affiliations/sport-brussel-ico-4.png',
	);

	for ($i = 1; $i <= 7; $i++) {
		$aff_logo = bunbukan_get_field("affiliation_{$i}_logo", $post->ID);
		$images_found[] = array(
			'label' => 'Logo ' . $affiliation_names[$i],
			'field_name' => "affiliation_{$i}_logo",
			'image' => $aff_logo,
			'default' => get_template_directory_uri() . $affiliation_logos[$i],
		);
	}

	if (empty($images_found)) {
		echo '<p style="padding: 10px; color: #666; font-style: italic;">Aucune image personnalisable détectée sur cette page.</p>';
		return;
	}

	?>
	<div style="padding: 10px 0; max-height: 500px; overflow-y: auto;">
		<?php foreach ($images_found as $item): ?>
			<div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #ddd;">
				<h4 style="margin: 0 0 8px 0; font-size: 12px; color: #333;"><?php echo esc_html($item['label']); ?></h4>

				<?php if ($item['image'] && is_array($item['image']) && isset($item['image']['url'])):
					$img_url = isset($item['image']['sizes']['thumbnail']) ? $item['image']['sizes']['thumbnail'] : $item['image']['url'];
					$edit_url = admin_url('post.php?post=' . $item['image']['ID'] . '&action=edit');
					?>
					<div style="margin-bottom: 8px;">
						<a href="<?php echo esc_url($item['image']['url']); ?>" target="_blank">
							<img src="<?php echo esc_url($img_url); ?>"
								alt="<?php echo esc_attr($item['image']['alt'] ?? ''); ?>"
								style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 3px; background: #fff;">
						</a>
					</div>
					<p style="margin: 5px 0; font-size: 11px; color: #666;">
						<strong>✅ Image personnalisée</strong><br>
						<a href="<?php echo esc_url($edit_url); ?>" target="_blank" style="font-size: 10px;">Modifier dans la médiathèque</a>
					</p>
				<?php elseif (isset($item['default']) && !empty($item['default'])): ?>
					<div style="margin-bottom: 8px;">
						<a href="<?php echo esc_url($item['default']); ?>" target="_blank">
							<img src="<?php echo esc_url($item['default']); ?>"
								alt="Image par défaut"
								style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 3px; background: #f5f5f5; opacity: 0.8;">
						</a>
					</div>
					<p style="margin: 5px 0; font-size: 11px; color: #999;">
						<em>📁 Image par défaut du thème</em><br>
						<span style="font-size: 10px;">Champ: <code><?php echo esc_html($item['field_name']); ?></code></span>
					</p>
				<?php else: ?>
					<p style="color: #999; font-style: italic; font-size: 11px; margin: 5px 0;">Aucune image définie.</p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<p style="margin: 10px 0 0 0; padding-top: 10px; border-top: 1px solid #ddd; font-size: 11px; color: #666;">
		<strong>💡 Astuce :</strong> Pour remplacer une image par défaut, remplissez le champ Pods correspondant ci-dessous.
	</p>
	<?php
}
