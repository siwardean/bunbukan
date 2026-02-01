<?php
/**
 * Pods Configuration for Bunbukan Theme
 * 
 * This file registers Pods fields for the front page programmatically
 * 
 * @package Bunbukan
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Check if a field name is reserved
 */
function bunbukan_is_reserved_field_name($name)
{
	$reserved_keywords = array(
		'id', 'ID', 'attachment', 'attachment_id', 'author', 'author_name',
		'category', 'link_category', 'name', 'p', 'page', 'paged', 'post',
		'post_format', 'post_mime_type', 'post_status', 'post_tag',
		'post_thumbnail', 'post_thumbnail_url', 'post_type', 's', 'search',
		'tag', 'taxonomy', 'term', 'terms', 'title', 'type',
		'calendar', 'cat', 'custom', 'day', 'hour', 'minute', 'monthnum',
		'nav_menu', 'nonce', 'offset', 'order', 'orderby', 'page_id',
		'pagename', 'posts', 'posts_per_page', 'preview', 'second', 'year',
	);

	return in_array(strtolower($name), $reserved_keywords, true);
}

/**
 * Register Pods fields for pages
 */
function bunbukan_setup_pods_fields()
{
	if (!function_exists('pods_api')) {
		return;
	}

	// Ne s'exécute que dans l'admin pour éviter les erreurs sur le front-end
	if (!is_admin()) {
		return;
	}

	try {
		$api = pods_api();

		// Vérifier si le Pod "page" existe et l'étendre si nécessaire
		$pod = $api->load_pod(array('name' => 'page'));

		if (empty($pod) || (is_array($pod) && isset($pod['type']) && $pod['type'] !== 'post_type')) {
			// Étendre le type de contenu "page"
			$pod_data = array(
				'name' => 'page',
				'type' => 'post_type',
				'label' => 'Page',
				'storage' => 'meta',
			);

			$pod_result = $api->save_pod($pod_data);
			if (is_wp_error($pod_result)) {
				error_log('Pods: Error extending page pod - ' . $pod_result->get_error_message());
				return;
			}
		}

		// Recharger le pod pour avoir toutes les données
		$pod = $api->load_pod(array('name' => 'page'));

		if (empty($pod) || is_wp_error($pod)) {
			return;
		}
	} catch (Exception $e) {
		error_log('Pods: Exception during pod setup - ' . $e->getMessage());
		return;
	}

	$pod_id = is_array($pod) ? $pod['id'] : (is_object($pod) ? $pod->get_id() : 0);
	if (empty($pod_id)) {
		return;
	}

	// Définir tous les champs à créer
	$fields = bunbukan_get_pods_fields_config();

	// Créer les groupes et les champs
	foreach ($fields as $group_name => $group_data) {
		try {
			$group_obj = null;

			// Créer le groupe si spécifié
			if (isset($group_data['group'])) {
				$group_name = $group_data['group']['name'];

				// Vérifier si le nom du groupe est réservé
				if (bunbukan_is_reserved_field_name($group_name)) {
					error_log('Pods Group Skipped (reserved): ' . $group_name);
					continue;
				}

				try {
					// Vérifier si le groupe existe déjà
					$existing_group = $api->load_group(array(
						'pod' => $pod,
						'name' => $group_name,
					));

					if (empty($existing_group)) {
						$group_params = array(
							'pod_data' => $pod,
							'name' => $group_name,
							'label' => $group_data['group']['label'],
							'weight' => isset($group_data['group']['weight']) ? $group_data['group']['weight'] : 0,
						);

						$group_id = $api->save_group($group_params);
						if ($group_id && !is_wp_error($group_id)) {
							$group_obj = $api->load_group(array('id' => $group_id));
						}
					} else {
						$group_obj = $existing_group;
					}
				} catch (Exception $e) {
					error_log('Pods: Exception creating group ' . $group_name . ' - ' . $e->getMessage());
					continue;
				}
			}

			// Créer les champs du groupe
			if (isset($group_data['fields']) && is_array($group_data['fields'])) {
				foreach ($group_data['fields'] as $field_config) {
					// Vérifier si le nom du champ est réservé
					if (bunbukan_is_reserved_field_name($field_config['name'])) {
						error_log('Pods Field Skipped (reserved): ' . $field_config['name']);
						continue;
					}

					// Vérifier si le champ existe déjà
					$existing_field = $api->load_field(array(
						'pod' => $pod,
						'name' => $field_config['name'],
					));

					// Si le champ existe déjà, on récupère son ID pour la mise à jour
					$existing_field_id = null;
					if (!empty($existing_field)) {
						if (is_array($existing_field) && isset($existing_field['id'])) {
							$existing_field_id = $existing_field['id'];
						} elseif (is_object($existing_field) && method_exists($existing_field, 'get_id')) {
							$existing_field_id = $existing_field->get_id();
						}
					}

					$field_params = array(
						'pod_data' => $pod,
						'name' => $field_config['name'],
						'label' => $field_config['label'],
						'type' => $field_config['type'],
						'weight' => isset($field_config['weight']) ? $field_config['weight'] : 0,
					);

					// Initialiser les options si nécessaire
					if (!isset($field_params['options'])) {
						$field_params['options'] = array();
					}

					// Ajouter la valeur par défaut si définie
					if (isset($field_config['default_value'])) {
						$field_params['options']['default'] = $field_config['default_value'];
						$field_params['options']['default_value'] = $field_config['default_value'];
					}

					// Assigner au groupe si spécifié
					if ($group_obj) {
						$field_params['group'] = $group_obj;
					}

					// Options spécifiques pour les champs de type file
					if ($field_config['type'] === 'file') {
						$field_params['options']['file_format_type'] = isset($field_config['file_format_type']) ? $field_config['file_format_type'] : 'single';
						$field_params['options']['file_type'] = isset($field_config['file_type']) ? $field_config['file_type'] : 'images';
						$field_params['file_format_type'] = isset($field_config['file_format_type']) ? $field_config['file_format_type'] : 'single';
						$field_params['file_type'] = isset($field_config['file_type']) ? $field_config['file_type'] : 'images';
					}

					// Ajouter l'ID si le champ existe déjà (pour mise à jour)
					if ($existing_field_id) {
						$field_params['id'] = $existing_field_id;
					}

					// Sauvegarder le champ
					try {
						$result = $api->save_field($field_params, true, false, true);
						if (is_wp_error($result)) {
							error_log('Pods Field Error: ' . $field_config['name'] . ' - ' . $result->get_error_message());
						}
					} catch (Exception $e) {
						error_log('Pods Field Exception: ' . $field_config['name'] . ' - ' . $e->getMessage());
					}
				}
			}
		} catch (Exception $e) {
			error_log('Pods: Exception in group ' . $group_name . ' - ' . $e->getMessage());
			continue;
		}
	}
}
add_action('pods_init', 'bunbukan_setup_pods_fields', 99);

/**
 * Get all Pods fields configuration
 * 
 * @return array Fields configuration organized by groups
 */
function bunbukan_get_pods_fields_config()
{
	return array(
		// ============================================================================
		// SECTION HERO
		// ============================================================================
		'bunbukan_hero' => array(
			'group' => array(
				'name' => 'bunbukan_hero',
				'label' => 'Section Hero (Page d\'accueil)',
				'weight' => 0,
			),
			'fields' => array(
				array(
					'name' => 'hero_background',
					'label' => 'Image de fond Hero',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 0,
				),
				array(
					'name' => 'hero_title_jp',
					'label' => 'Titre Japonais',
					'type' => 'text',
					'default_value' => '文武館',
					'weight' => 1,
				),
				array(
					'name' => 'hero_title_en',
					'label' => 'Titre Principal',
					'type' => 'text',
					'default_value' => 'Bunbukan Brussels',
					'weight' => 2,
				),
				array(
					'name' => 'hero_subtitle',
					'label' => 'Sous-titre',
					'type' => 'wysiwyg',
					'default_value' => 'Preserving the authentic traditions of Okinawan martial arts in Brussels',
					'weight' => 3,
				),
				array(
					'name' => 'hero_discipline_1_title',
					'label' => 'Discipline 1 - Titre',
					'type' => 'text',
					'default_value' => 'Shitō-Ryū Karate',
					'weight' => 4,
				),
				array(
					'name' => 'hero_discipline_1_subtitle',
					'label' => 'Discipline 1 - Sous-titre',
					'type' => 'text',
					'default_value' => 'Direct lineage from Kenei Mabuni',
					'weight' => 5,
				),
				array(
					'name' => 'hero_discipline_2_title',
					'label' => 'Discipline 2 - Titre',
					'type' => 'text',
					'default_value' => 'Ryūkyū Kobudō',
					'weight' => 6,
				),
				array(
					'name' => 'hero_discipline_2_subtitle',
					'label' => 'Discipline 2 - Sous-titre',
					'type' => 'text',
					'default_value' => 'Under Nakamoto Masahiro',
					'weight' => 7,
				),
				array(
					'name' => 'hero_cta_text',
					'label' => 'Texte du bouton',
					'type' => 'text',
					'default_value' => 'Begin Your Journey',
					'weight' => 8,
				),
			),
		),

		// ============================================================================
		// SECTION ABOUT
		// ============================================================================
		'bunbukan_about' => array(
			'group' => array(
				'name' => 'bunbukan_about',
				'label' => 'Section À propos (Page d\'accueil)',
				'weight' => 10,
			),
			'fields' => array(
				array(
					'name' => 'about_title',
					'label' => 'Titre Section',
					'type' => 'text',
					'default_value' => 'About Bunbukan Brussels',
					'weight' => 0,
				),
				array(
					'name' => 'about_image',
					'label' => 'Image Principale',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 1,
				),
				array(
					'name' => 'about_heritage_label',
					'label' => 'Heritage - Label',
					'type' => 'text',
					'default_value' => 'Heritage',
					'weight' => 2,
				),
				array(
					'name' => 'about_heritage_title',
					'label' => 'Heritage - Titre',
					'type' => 'text',
					'default_value' => 'SINCE 1977',
					'weight' => 3,
				),
				array(
					'name' => 'about_heritage_text',
					'label' => 'Heritage - Texte',
					'type' => 'wysiwyg',
					'default_value' => 'As part of Budo Club Berchem, we preserve and transmit traditional martial arts taught at the Bunbukan Honbu Dōjō, while also continuing the Shitō-ryū karate lineage passed down by Kenei Mabuni.',
					'weight' => 4,
				),
				array(
					'name' => 'about_mission_label',
					'label' => 'Mission - Label',
					'type' => 'text',
					'default_value' => 'Mission',
					'weight' => 5,
				),
				array(
					'name' => 'about_mission_title',
					'label' => 'Mission - Titre',
					'type' => 'text',
					'default_value' => 'THE WAY',
					'weight' => 6,
				),
				array(
					'name' => 'about_mission_text',
					'label' => 'Mission - Texte',
					'type' => 'wysiwyg',
					'default_value' => 'Technical depth and character development. Respect for tradition while fostering personal growth through the path of martial arts.',
					'weight' => 7,
				),
				array(
					'name' => 'about_stat_1_value',
					'label' => 'Statistique 1 - Valeur',
					'type' => 'number',
					'default_value' => '50',
					'weight' => 8,
				),
				array(
					'name' => 'about_stat_1_suffix',
					'label' => 'Statistique 1 - Suffixe',
					'type' => 'text',
					'default_value' => '+',
					'weight' => 9,
				),
				array(
					'name' => 'about_stat_1_label',
					'label' => 'Statistique 1 - Label',
					'type' => 'text',
					'default_value' => 'Years Teaching',
					'weight' => 10,
				),
				array(
					'name' => 'about_stat_2_value',
					'label' => 'Statistique 2 - Valeur',
					'type' => 'number',
					'default_value' => '3',
					'weight' => 11,
				),
				array(
					'name' => 'about_stat_2_suffix',
					'label' => 'Statistique 2 - Suffixe',
					'type' => 'text',
					'default_value' => '',
					'weight' => 12,
				),
				array(
					'name' => 'about_stat_2_label',
					'label' => 'Statistique 2 - Label',
					'type' => 'text',
					'default_value' => 'Experienced Instructors',
					'weight' => 13,
				),
				array(
					'name' => 'about_stat_3_value',
					'label' => 'Statistique 3 - Valeur',
					'type' => 'number',
					'default_value' => '100',
					'weight' => 14,
				),
				array(
					'name' => 'about_stat_3_suffix',
					'label' => 'Statistique 3 - Suffixe',
					'type' => 'text',
					'default_value' => '%',
					'weight' => 15,
				),
				array(
					'name' => 'about_stat_3_label',
					'label' => 'Statistique 3 - Label',
					'type' => 'text',
					'default_value' => 'Authentic',
					'weight' => 16,
				),
			),
		),

		// ============================================================================
		// SECTION DISCIPLINES
		// ============================================================================
		'bunbukan_disciplines' => array(
			'group' => array(
				'name' => 'bunbukan_disciplines',
				'label' => 'Section Disciplines (Page d\'accueil)',
				'weight' => 20,
			),
			'fields' => array(
				array(
					'name' => 'disciplines_title',
					'label' => 'Titre Section',
					'type' => 'text',
					'default_value' => 'Karate & Kobudō',
					'weight' => 0,
				),
				array(
					'name' => 'disciplines_subtitle',
					'label' => 'Sous-titre Section',
					'type' => 'wysiwyg',
					'default_value' => 'Karate and kobudō are two sides of the same coin. To understand one, you must practice the other.',
					'weight' => 1,
				),
				// Karate
				array(
					'name' => 'karate_title_jp',
					'label' => 'Karate - Titre Japonais',
					'type' => 'text',
					'default_value' => '糸東流空手道',
					'weight' => 2,
				),
				array(
					'name' => 'karate_title',
					'label' => 'Karate - Titre',
					'type' => 'text',
					'default_value' => 'Shitō-Ryū Karate',
					'weight' => 3,
				),
				array(
					'name' => 'karate_since',
					'label' => 'Karate - Depuis',
					'type' => 'text',
					'default_value' => 'Since 1979',
					'weight' => 4,
				),
				array(
					'name' => 'karate_description',
					'label' => 'Karate - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Shitō-ryū is a traditional Okinawan karate style, known for the richness of its kata and the precision of its techniques. Rooted in both Shuri and Naha traditions, it offers a complete practice that combines technical rigor, fluid movement, and a deep understanding of martial principles.',
					'weight' => 5,
				),
				array(
					'name' => 'karate_image',
					'label' => 'Karate - Image',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 6,
				),
				array(
					'name' => 'karate_logo',
					'label' => 'Karate - Logo de fond',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 7,
				),
				// Kobudo
				array(
					'name' => 'kobudo_title_jp',
					'label' => 'Kobudō - Titre Japonais',
					'type' => 'text',
					'default_value' => '琉球古武道',
					'weight' => 8,
				),
				array(
					'name' => 'kobudo_title',
					'label' => 'Kobudō - Titre',
					'type' => 'text',
					'default_value' => 'Ryūkyū Kobudō',
					'weight' => 9,
				),
				array(
					'name' => 'kobudo_since',
					'label' => 'Kobudō - Depuis',
					'type' => 'text',
					'default_value' => 'Since 2001',
					'weight' => 10,
				),
				array(
					'name' => 'kobudo_description',
					'label' => 'Kobudō - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Ryūkyū Kobudō is the traditional weapon art of Okinawa, practiced alongside karate. At Bunbukan, this discipline develops coordination, control, and body awareness through the study of classical weapon forms and their applications, in an authentic and traditional approach.',
					'weight' => 11,
				),
				array(
					'name' => 'kobudo_image',
					'label' => 'Kobudō - Image',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 12,
				),
				array(
					'name' => 'kobudo_logo',
					'label' => 'Kobudō - Logo de fond',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 13,
				),
			),
		),

		// ============================================================================
		// SECTION INSTRUCTORS
		// ============================================================================
		'bunbukan_instructors' => array(
			'group' => array(
				'name' => 'bunbukan_instructors',
				'label' => 'Section Instructeurs (Page d\'accueil)',
				'weight' => 30,
			),
			'fields' => array(
				array(
					'name' => 'instructors_title',
					'label' => 'Titre Section',
					'type' => 'text',
					'default_value' => 'Our Instructors',
					'weight' => 0,
				),
				// Instructor 1 (Arnaud)
				array(
					'name' => 'instructor_1_name',
					'label' => 'Instructeur 1 - Nom',
					'type' => 'text',
					'default_value' => 'Arnaud Vankeijenbergh',
					'weight' => 1,
				),
				array(
					'name' => 'instructor_1_title',
					'label' => 'Instructeur 1 - Titre',
					'type' => 'text',
					'default_value' => '2nd Dan Shitō-Ryū, Technical Instructor (Karate)',
					'weight' => 2,
				),
				array(
					'name' => 'instructor_1_description',
					'label' => 'Instructeur 1 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Assists in Karate instruction. Focused on precise technique and kata understanding.',
					'weight' => 3,
				),
				array(
					'name' => 'instructor_1_image',
					'label' => 'Instructeur 1 - Photo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 4,
				),
				// Instructor 2 (Alain - Chief)
				array(
					'name' => 'instructor_2_name',
					'label' => 'Instructeur 2 - Nom',
					'type' => 'text',
					'default_value' => 'Alain Berckmans',
					'weight' => 5,
				),
				array(
					'name' => 'instructor_2_title',
					'label' => 'Instructeur 2 - Titre',
					'type' => 'text',
					'default_value' => '6th Dan Shitō-Ryū (Shihan), Chief Instructor',
					'weight' => 6,
				),
				array(
					'name' => 'instructor_2_description',
					'label' => 'Instructeur 2 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Over 50 years of martial arts experience. Direct student of Kenei Mabuni and Nakamoto Masahiro.',
					'weight' => 7,
				),
				array(
					'name' => 'instructor_2_image',
					'label' => 'Instructeur 2 - Photo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 8,
				),
				// Instructor 3 (Quentin)
				array(
					'name' => 'instructor_3_name',
					'label' => 'Instructeur 3 - Nom',
					'type' => 'text',
					'default_value' => 'Quentin Moreau',
					'weight' => 9,
				),
				array(
					'name' => 'instructor_3_title',
					'label' => 'Instructeur 3 - Titre',
					'type' => 'text',
					'default_value' => '2nd Dan Shitō-Ryū, Black Belt in Kobudō, Technical Instructor',
					'weight' => 10,
				),
				array(
					'name' => 'instructor_3_description',
					'label' => 'Instructeur 3 - Description',
					'type' => 'wysiwyg',
					'default_value' => 'Assists in both Karate and Kobudō. Special focus on Okinawan weapons training.',
					'weight' => 11,
				),
				array(
					'name' => 'instructor_3_image',
					'label' => 'Instructeur 3 - Photo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 12,
				),
			),
		),

		// ============================================================================
		// SECTION CONTACT
		// ============================================================================
		'bunbukan_contact' => array(
			'group' => array(
				'name' => 'bunbukan_contact',
				'label' => 'Section Contact (Page d\'accueil)',
				'weight' => 40,
			),
			'fields' => array(
				array(
					'name' => 'contact_title',
					'label' => 'Titre Section',
					'type' => 'text',
					'default_value' => 'Contact',
					'weight' => 0,
				),
				array(
					'name' => 'contact_subtitle',
					'label' => 'Sous-titre',
					'type' => 'wysiwyg',
					'default_value' => 'New members are always welcome. Come observe a class or join us for a free trial session.',
					'weight' => 1,
				),
				array(
					'name' => 'contact_subtitle_2',
					'label' => 'Sous-titre 2',
					'type' => 'text',
					'default_value' => 'You can choose to practice only one of the two disciplines.',
					'weight' => 2,
				),
				// Schedule
				array(
					'name' => 'schedule_title',
					'label' => 'Horaires - Titre',
					'type' => 'text',
					'default_value' => 'Training Schedule',
					'weight' => 3,
				),
				array(
					'name' => 'schedule_1_day',
					'label' => 'Horaire 1 - Jour',
					'type' => 'text',
					'default_value' => 'Wednesday',
					'weight' => 4,
				),
				array(
					'name' => 'schedule_1_time',
					'label' => 'Horaire 1 - Heure',
					'type' => 'text',
					'default_value' => '18:30–20:00',
					'weight' => 5,
				),
				array(
					'name' => 'schedule_1_discipline',
					'label' => 'Horaire 1 - Discipline',
					'type' => 'text',
					'default_value' => 'Karate',
					'weight' => 6,
				),
				array(
					'name' => 'schedule_2_day',
					'label' => 'Horaire 2 - Jour',
					'type' => 'text',
					'default_value' => 'Wednesday',
					'weight' => 7,
				),
				array(
					'name' => 'schedule_2_time',
					'label' => 'Horaire 2 - Heure',
					'type' => 'text',
					'default_value' => '20:00–21:30',
					'weight' => 8,
				),
				array(
					'name' => 'schedule_2_discipline',
					'label' => 'Horaire 2 - Discipline',
					'type' => 'text',
					'default_value' => 'Ryūkyū Kobudō',
					'weight' => 9,
				),
				array(
					'name' => 'schedule_3_day',
					'label' => 'Horaire 3 - Jour',
					'type' => 'text',
					'default_value' => 'Friday',
					'weight' => 10,
				),
				array(
					'name' => 'schedule_3_time',
					'label' => 'Horaire 3 - Heure',
					'type' => 'text',
					'default_value' => '19:00–20:30',
					'weight' => 11,
				),
				array(
					'name' => 'schedule_3_discipline',
					'label' => 'Horaire 3 - Discipline',
					'type' => 'text',
					'default_value' => 'Karate',
					'weight' => 12,
				),
				array(
					'name' => 'schedule_note',
					'label' => 'Note Horaires',
					'type' => 'text',
					'default_value' => 'Open to students aged 14 and up. All levels welcome.',
					'weight' => 13,
				),
				// Contact CTA
				array(
					'name' => 'contact_cta_title',
					'label' => 'CTA - Titre',
					'type' => 'text',
					'default_value' => 'Get in Touch',
					'weight' => 14,
				),
				array(
					'name' => 'contact_cta_text',
					'label' => 'CTA - Texte',
					'type' => 'wysiwyg',
					'default_value' => 'Questions about training or want to arrange a visit? We would love to hear from you.',
					'weight' => 15,
				),
				array(
					'name' => 'contact_email',
					'label' => 'Email',
					'type' => 'email',
					'default_value' => 'info@bunbukan.eu',
					'weight' => 16,
				),
				array(
					'name' => 'contact_email_button',
					'label' => 'Texte bouton Email',
					'type' => 'text',
					'default_value' => 'Send Email',
					'weight' => 17,
				),
				array(
					'name' => 'contact_address',
					'label' => 'Adresse',
					'type' => 'text',
					'default_value' => 'Rue des Chalets 30, 1030 Schaerbeek',
					'weight' => 18,
				),
				array(
					'name' => 'contact_map_button',
					'label' => 'Texte bouton Map',
					'type' => 'text',
					'default_value' => 'View Map',
					'weight' => 19,
				),
			),
		),

		// ============================================================================
		// SECTION AFFILIATIONS
		// ============================================================================
		'bunbukan_affiliations' => array(
			'group' => array(
				'name' => 'bunbukan_affiliations',
				'label' => 'Section Affiliations (Page d\'accueil)',
				'weight' => 50,
			),
			'fields' => array(
				array(
					'name' => 'affiliations_title',
					'label' => 'Titre Section',
					'type' => 'text',
					'default_value' => 'Affiliations',
					'weight' => 0,
				),
				// Affiliation 1
				array(
					'name' => 'affiliation_1_name',
					'label' => 'Affiliation 1 - Nom',
					'type' => 'text',
					'default_value' => 'Budo Club Berchem Brussels',
					'weight' => 1,
				),
				array(
					'name' => 'affiliation_1_url',
					'label' => 'Affiliation 1 - URL',
					'type' => 'website',
					'default_value' => 'https://www.budoclubberchem.be/',
					'weight' => 2,
				),
				array(
					'name' => 'affiliation_1_logo',
					'label' => 'Affiliation 1 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 3,
				),
				// Affiliation 2
				array(
					'name' => 'affiliation_2_name',
					'label' => 'Affiliation 2 - Nom',
					'type' => 'text',
					'default_value' => 'Vlaamse Karate Associatie (VKA)',
					'weight' => 4,
				),
				array(
					'name' => 'affiliation_2_url',
					'label' => 'Affiliation 2 - URL',
					'type' => 'website',
					'default_value' => 'https://www.vka.be/',
					'weight' => 5,
				),
				array(
					'name' => 'affiliation_2_logo',
					'label' => 'Affiliation 2 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 6,
				),
				// Affiliation 3
				array(
					'name' => 'affiliation_3_name',
					'label' => 'Affiliation 3 - Nom',
					'type' => 'text',
					'default_value' => 'Shitokai Belgium',
					'weight' => 7,
				),
				array(
					'name' => 'affiliation_3_url',
					'label' => 'Affiliation 3 - URL',
					'type' => 'website',
					'default_value' => 'https://www.shitokai.be/',
					'weight' => 8,
				),
				array(
					'name' => 'affiliation_3_logo',
					'label' => 'Affiliation 3 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 9,
				),
				// Affiliation 4
				array(
					'name' => 'affiliation_4_name',
					'label' => 'Affiliation 4 - Nom',
					'type' => 'text',
					'default_value' => 'Dento Shito-Ryu (Japan)',
					'weight' => 10,
				),
				array(
					'name' => 'affiliation_4_url',
					'label' => 'Affiliation 4 - URL',
					'type' => 'website',
					'default_value' => 'https://www.dento-shitoryu.jp/',
					'weight' => 11,
				),
				array(
					'name' => 'affiliation_4_logo',
					'label' => 'Affiliation 4 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 12,
				),
				// Affiliation 5
				array(
					'name' => 'affiliation_5_name',
					'label' => 'Affiliation 5 - Nom',
					'type' => 'text',
					'default_value' => 'Ono-ha Itto-Ryu',
					'weight' => 13,
				),
				array(
					'name' => 'affiliation_5_url',
					'label' => 'Affiliation 5 - URL',
					'type' => 'website',
					'default_value' => 'https://www.ono-ha-ittoryu.be/',
					'weight' => 14,
				),
				array(
					'name' => 'affiliation_5_logo',
					'label' => 'Affiliation 5 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 15,
				),
				// Affiliation 6
				array(
					'name' => 'affiliation_6_name',
					'label' => 'Affiliation 6 - Nom',
					'type' => 'text',
					'default_value' => 'Hontai Yoshin Ryu',
					'weight' => 16,
				),
				array(
					'name' => 'affiliation_6_url',
					'label' => 'Affiliation 6 - URL',
					'type' => 'website',
					'default_value' => 'https://www.hontaiyoshinryu.be/',
					'weight' => 17,
				),
				array(
					'name' => 'affiliation_6_logo',
					'label' => 'Affiliation 6 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 18,
				),
				// Affiliation 7
				array(
					'name' => 'affiliation_7_name',
					'label' => 'Affiliation 7 - Nom',
					'type' => 'text',
					'default_value' => 'Sport Brussel',
					'weight' => 19,
				),
				array(
					'name' => 'affiliation_7_url',
					'label' => 'Affiliation 7 - URL',
					'type' => 'website',
					'default_value' => 'https://www.sport.brussels/',
					'weight' => 20,
				),
				array(
					'name' => 'affiliation_7_logo',
					'label' => 'Affiliation 7 - Logo',
					'type' => 'file',
					'file_format_type' => 'single',
					'file_type' => 'images',
					'weight' => 21,
				),
			),
		),
	);
}

/**
 * Filtrer les groupes Pods pour n'afficher que ceux correspondant au template de page
 */
add_filter('pods_meta_groups_get', 'bunbukan_filter_pods_groups_by_page_template', 10, 4);
function bunbukan_filter_pods_groups_by_page_template($groups, $pod_type, $pod_name, $page_id)
{
	// Ne filtrer que pour le pod 'page' dans l'admin
	if ($pod_type !== 'post_type' || $pod_name !== 'page' || !is_admin()) {
		return $groups;
	}

	// Récupérer le template de page actuel
	global $post;

	// Essayer d'obtenir l'ID de la page depuis plusieurs sources
	if (empty($page_id) && isset($_GET['post'])) {
		$page_id = intval($_GET['post']);
	} elseif (empty($page_id) && $post) {
		$page_id = $post->ID;
	}

	// Si on n'a toujours pas d'ID, retourner tous les groupes
	if (empty($page_id)) {
		return $groups;
	}

	$page_template = get_page_template_slug($page_id);

	// Si c'est la page d'accueil (front page)
	$is_front_page = (get_option('page_on_front') == $page_id);
	if ($is_front_page || $page_template === 'front-page.php') {
		$page_template = 'front-page.php';
	}

	// Définir les préfixes de champs autorisés pour chaque template
	$allowed_field_prefixes = array();

	if ($page_template === 'front-page.php' || $is_front_page) {
		// Page d'accueil - tous les champs bunbukan
		$allowed_field_prefixes = array(
			'hero_', 'about_', 'disciplines_', 'karate_', 'kobudo_',
			'instructors_', 'instructor_', 'contact_', 'schedule_',
			'affiliations_', 'affiliation_'
		);
	}

	// Si aucun template spécifique n'est détecté, retourner tous les groupes
	if (empty($allowed_field_prefixes)) {
		return $groups;
	}

	// Filtrer les groupes
	$filtered_groups = array();
	foreach ($groups as $key => $group) {
		$group_fields = array();

		if (is_array($group) && isset($group['fields']) && is_array($group['fields'])) {
			$group_fields = $group['fields'];
		} elseif (is_object($group) && method_exists($group, 'get_fields')) {
			$group_fields = $group->get_fields();
		}

		$has_allowed_field = false;

		if (empty($group_fields)) {
			continue;
		}

		foreach ($group_fields as $field) {
			$field_name = null;

			if (is_array($field) && isset($field['name'])) {
				$field_name = $field['name'];
			} elseif (is_object($field)) {
				if (isset($field->name)) {
					$field_name = $field->name;
				} elseif (method_exists($field, 'get_name')) {
					$field_name = $field->get_name();
				}
			}

			if ($field_name) {
				foreach ($allowed_field_prefixes as $prefix) {
					if (strpos($field_name, $prefix) === 0) {
						$has_allowed_field = true;
						break 2;
					}
				}
			}
		}

		if ($has_allowed_field) {
			$filtered_groups[$key] = $group;
		}
	}

	return $filtered_groups;
}
