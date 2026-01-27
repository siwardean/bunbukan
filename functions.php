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
