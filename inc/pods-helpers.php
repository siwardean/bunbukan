<?php
/**
 * Pods Helper Functions for Bunbukan Theme
 * 
 * These functions provide easy access to Pods field values with fallbacks
 * 
 * @package Bunbukan
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Get field value from Pods with default fallback
 * 
 * @param string $field_name Field name
 * @param int|string $post_id Post ID (default: current post)
 * @return mixed Field value
 */
function bunbukan_get_field($field_name, $post_id = null)
{
	if (null === $post_id) {
		$post_id = get_the_ID();
	}

	// Check if Pods is available
	if (!function_exists('pods')) {
		return null;
	}

	$pod = pods('page', $post_id);
	if (!$pod || !$pod->exists()) {
		return null;
	}

	$value = bunbukan_get_pods_field_value($pod, $field_name);

	return $value;
}

/**
 * Get field value from Pods and handle format conversion
 * 
 * @param object $pod Pods object
 * @param string $field_name Field name
 * @return mixed Field value
 */
function bunbukan_get_pods_field_value($pod, $field_name)
{
	// Get field data to check type
	$field_data = $pod->fields($field_name);
	$field_type = null;
	$file_format_type = null;

	if ($field_data && is_array($field_data)) {
		$field_type = isset($field_data['type']) ? $field_data['type'] : null;
		$file_format_type = isset($field_data['file_format_type']) ? $field_data['file_format_type'] : null;
	} elseif (is_object($field_data)) {
		if (method_exists($field_data, 'get_type')) {
			$field_type = $field_data->get_type();
		}
		if (method_exists($field_data, 'get_file_format_type')) {
			$file_format_type = $field_data->get_file_format_type();
		}
	}

	// Get field value from Pods
	$value = $pod->field($field_name, true);

	// Handle image/file fields - convert Pods format to ACF-like format
	if (in_array($field_type, array('file', 'picture'), true)) {
		// Handle multi-file fields (galleries)
		if ($file_format_type === 'multi' && is_array($value) && !empty($value)) {
			$gallery_array = array();
			foreach ($value as $item) {
				$attachment_id = null;

				if (is_numeric($item)) {
					$attachment_id = (int) $item;
				} elseif (is_array($item) && isset($item['ID'])) {
					$attachment_id = (int) $item['ID'];
				}

				if ($attachment_id) {
					$attachment = get_post($attachment_id);
					if ($attachment) {
						$image_sizes = array();
						$image_sizes['thumbnail'] = wp_get_attachment_image_url($attachment_id, 'thumbnail');
						$image_sizes['medium'] = wp_get_attachment_image_url($attachment_id, 'medium');
						$image_sizes['large'] = wp_get_attachment_image_url($attachment_id, 'large');
						$image_sizes['full'] = wp_get_attachment_image_url($attachment_id, 'full');

						$gallery_array[] = array(
							'ID' => $attachment_id,
							'url' => wp_get_attachment_url($attachment_id),
							'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
							'width' => '',
							'height' => '',
							'filename' => basename(get_attached_file($attachment_id)),
							'sizes' => $image_sizes,
						);
					}
				}
			}

			if (!empty($gallery_array)) {
				return $gallery_array;
			}
			return $value;
		}

		// Handle single file fields
		$attachment_id = null;

		if (is_numeric($value)) {
			$attachment_id = (int) $value;
		} elseif (is_array($value) && isset($value['ID'])) {
			$attachment_id = (int) $value['ID'];
		} elseif (is_array($value) && !empty($value)) {
			$first_item = reset($value);
			if (is_numeric($first_item)) {
				$attachment_id = (int) $first_item;
			} elseif (is_array($first_item) && isset($first_item['ID'])) {
				$attachment_id = (int) $first_item['ID'];
			}
		}

		if ($attachment_id) {
			$attachment = get_post($attachment_id);
			if ($attachment) {
				$image_sizes = array();
				$image_sizes['thumbnail'] = wp_get_attachment_image_url($attachment_id, 'thumbnail');
				$image_sizes['medium'] = wp_get_attachment_image_url($attachment_id, 'medium');
				$image_sizes['large'] = wp_get_attachment_image_url($attachment_id, 'large');
				$image_sizes['full'] = wp_get_attachment_image_url($attachment_id, 'full');

				return array(
					'ID' => $attachment_id,
					'url' => wp_get_attachment_url($attachment_id),
					'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
					'width' => '',
					'height' => '',
					'filename' => basename(get_attached_file($attachment_id)),
					'sizes' => $image_sizes,
				);
			}
		}

		// If we can't get attachment info, return false for file fields
		if ('file' === $field_type || 'picture' === $field_type) {
			return false;
		}
	}

	return $value;
}

/**
 * Get text field value with default and translation
 * 
 * @param string $field_name Field name
 * @param string $default Default value (translatable)
 * @param int|string $post_id Post ID
 * @return string Field value
 */
function bunbukan_get_text_field($field_name, $default = '', $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if (!empty($value)) {
		return esc_html($value);
	}

	if (!empty($default)) {
		return esc_html__($default, 'bunbukan');
	}

	return '';
}

/**
 * Get WYSIWYG field value with default and translation
 * 
 * @param string $field_name Field name
 * @param string $default Default value (translatable)
 * @param int|string $post_id Post ID
 * @return string Field value (sanitized HTML)
 */
function bunbukan_get_wysiwyg_field($field_name, $default = '', $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if (!empty($value)) {
		return wp_kses_post($value);
	}

	if (!empty($default)) {
		return esc_html__($default, 'bunbukan');
	}

	return '';
}

/**
 * Get image field URL with fallback
 * 
 * @param string $field_name Field name
 * @param string $fallback_path Fallback path relative to theme
 * @param int|string $post_id Post ID
 * @return string Image URL
 */
function bunbukan_get_image_field($field_name, $fallback_path = '', $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if (!empty($value)) {
		// Handle array format (Pods file field)
		if (is_array($value)) {
			if (isset($value['url'])) {
				return esc_url($value['url']);
			}
			if (isset($value['sizes']['full'])) {
				return esc_url($value['sizes']['full']);
			}
		}
		// Handle ID format
		if (is_numeric($value)) {
			$url = wp_get_attachment_url($value);
			if ($url) {
				return esc_url($url);
			}
		}
		// Handle URL string
		if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
			return esc_url($value);
		}
	}

	// Fallback to theme asset
	if (!empty($fallback_path)) {
		if (function_exists('bunbukan_asset_url')) {
			$url = bunbukan_asset_url($fallback_path);
			if ($url) {
				return esc_url($url);
			}
		}
		// Direct fallback
		$theme_path = get_template_directory() . $fallback_path;
		if (file_exists($theme_path)) {
			return esc_url(get_template_directory_uri() . $fallback_path);
		}
	}

	return '';
}

/**
 * Get number field value with default
 * 
 * @param string $field_name Field name
 * @param int $default Default value
 * @param int|string $post_id Post ID
 * @return int Field value
 */
function bunbukan_get_number_field($field_name, $default = 0, $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if ($value !== null && $value !== '' && is_numeric($value)) {
		return intval($value);
	}

	return intval($default);
}

/**
 * Get URL field value with default
 * 
 * @param string $field_name Field name
 * @param string $default Default URL
 * @param int|string $post_id Post ID
 * @return string URL
 */
function bunbukan_get_url_field($field_name, $default = '', $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if (!empty($value) && filter_var($value, FILTER_VALIDATE_URL)) {
		return esc_url($value);
	}

	if (!empty($default)) {
		return esc_url($default);
	}

	return '';
}

/**
 * Get email field value with default
 * 
 * @param string $field_name Field name
 * @param string $default Default email
 * @param int|string $post_id Post ID
 * @return string Email
 */
function bunbukan_get_email_field($field_name, $default = '', $post_id = null)
{
	$value = bunbukan_get_field($field_name, $post_id);

	if (!empty($value) && is_email($value)) {
		return sanitize_email($value);
	}

	if (!empty($default)) {
		return sanitize_email($default);
	}

	return '';
}

/**
 * Check if Pods is active
 * 
 * @return bool
 */
function bunbukan_has_pods()
{
	return function_exists('pods');
}

/**
 * Strip p tags from WYSIWYG content for inline display
 * 
 * @param string $content HTML content
 * @return string Content without wrapping p tags
 */
function bunbukan_strip_p_tags($content)
{
	$content = preg_replace('/^<p[^>]*>/', '', $content);
	$content = preg_replace('/<\/p>$/', '', $content);
	return trim($content);
}

/**
 * Get instructor data with fallbacks
 * 
 * @param int $index Instructor index (1, 2, or 3)
 * @param int|string $post_id Post ID
 * @return array Instructor data
 */
function bunbukan_get_instructor($index, $post_id = null)
{
	$defaults = array(
		1 => array(
			'name' => 'Arnaud Vankeijenbergh',
			'title' => '2nd Dan Shitō-Ryū, Technical Instructor (Karate)',
			'description' => 'Assists in Karate instruction. Focused on precise technique and kata understanding.',
			'image' => '/assets/images/instructors/instructor-arnaud.png',
			'style' => '--bb-instructor-scale: 1; --bb-instructor-translate: -4px; --bb-instructor-position: 50% 12%;',
		),
		2 => array(
			'name' => 'Alain Berckmans',
			'title' => '6th Dan Shitō-Ryū (Shihan), Chief Instructor',
			'description' => 'Over 50 years of martial arts experience. Direct student of Kenei Mabuni and Nakamoto Masahiro.',
			'image' => '/assets/images/instructors/instructor-alain.jpg',
			'style' => '',
		),
		3 => array(
			'name' => 'Quentin Moreau',
			'title' => '2nd Dan Shitō-Ryū, Black Belt in Kobudō, Technical Instructor',
			'description' => 'Assists in both Karate and Kobudō. Special focus on Okinawan weapons training.',
			'image' => '/assets/images/instructors/instructor-quentin.png',
			'style' => '--bb-instructor-scale: 1; --bb-instructor-translate: -4px; --bb-instructor-position: 50% 12%;',
		),
	);

	$default = isset($defaults[$index]) ? $defaults[$index] : $defaults[1];

	return array(
		'name' => bunbukan_get_text_field("instructor_{$index}_name", $default['name'], $post_id),
		'title' => bunbukan_get_text_field("instructor_{$index}_title", $default['title'], $post_id),
		'description' => bunbukan_get_wysiwyg_field("instructor_{$index}_description", $default['description'], $post_id),
		'image' => bunbukan_get_image_field("instructor_{$index}_image", $default['image'], $post_id),
		'style' => $default['style'],
	);
}

/**
 * Get affiliation data with fallbacks
 * 
 * @param int $index Affiliation index (1-7)
 * @param int|string $post_id Post ID
 * @return array Affiliation data
 */
function bunbukan_get_affiliation($index, $post_id = null)
{
	$defaults = array(
		1 => array(
			'name' => 'Budo Club Berchem Brussels',
			'url' => 'https://www.budoclubberchem.be/',
			'logo' => '/assets/images/affiliations/budo-club-berchem-ico-3.png',
		),
		2 => array(
			'name' => 'Vlaamse Karate Associatie (VKA)',
			'url' => 'https://www.vka.be/',
			'logo' => '/assets/images/affiliations/vka-ico.png',
		),
		3 => array(
			'name' => 'Shitokai Belgium',
			'url' => 'https://www.shitokai.be/',
			'logo' => '/assets/images/affiliations/shitokai-ico-2.png',
		),
		4 => array(
			'name' => 'Dento Shito-Ryu (Japan)',
			'url' => 'https://www.dento-shitoryu.jp/',
			'logo' => '/assets/images/affiliations/dento-shito-ryu-ico-8.png',
		),
		5 => array(
			'name' => 'Ono-ha Itto-Ryu',
			'url' => 'https://www.ono-ha-ittoryu.be/',
			'logo' => '/assets/images/affiliations/ono-ha-itto-ryu-ico-7.png',
		),
		6 => array(
			'name' => 'Hontai Yoshin Ryu',
			'url' => 'https://www.hontaiyoshinryu.be/',
			'logo' => '/assets/images/affiliations/Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
		),
		7 => array(
			'name' => 'Sport Brussel',
			'url' => 'https://www.sport.brussels/',
			'logo' => '/assets/images/affiliations/sport-brussel-ico-4.png',
		),
	);

	$default = isset($defaults[$index]) ? $defaults[$index] : $defaults[1];

	return array(
		'name' => bunbukan_get_text_field("affiliation_{$index}_name", $default['name'], $post_id),
		'url' => bunbukan_get_url_field("affiliation_{$index}_url", $default['url'], $post_id),
		'logo' => bunbukan_get_image_field("affiliation_{$index}_logo", $default['logo'], $post_id),
	);
}

/**
 * Get schedule row data with fallbacks
 * 
 * @param int $index Schedule row index (1, 2, or 3)
 * @param int|string $post_id Post ID
 * @return array Schedule row data
 */
function bunbukan_get_schedule_row($index, $post_id = null)
{
	$defaults = array(
		1 => array(
			'day' => 'Wednesday',
			'time' => '18:30–20:00',
			'discipline' => 'Karate',
		),
		2 => array(
			'day' => 'Wednesday',
			'time' => '20:00–21:30',
			'discipline' => 'Ryūkyū Kobudō',
		),
		3 => array(
			'day' => 'Friday',
			'time' => '19:00–20:30',
			'discipline' => 'Karate',
		),
	);

	$default = isset($defaults[$index]) ? $defaults[$index] : $defaults[1];

	return array(
		'day' => bunbukan_get_text_field("schedule_{$index}_day", $default['day'], $post_id),
		'time' => bunbukan_get_text_field("schedule_{$index}_time", $default['time'], $post_id),
		'discipline' => bunbukan_get_text_field("schedule_{$index}_discipline", $default['discipline'], $post_id),
	);
}
