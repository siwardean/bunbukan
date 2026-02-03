<?php
/**
 * Pods Data Migration - Pre-fill Pods fields with default content
 * 
 * This function populates all Pods fields with the default values from the site
 * 
 * @package Bunbukan
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Pre-fill all Pods fields with the current content
 * 
 * This function should be called once to migrate existing content to Pods
 */
function bunbukan_prefill_pods_fields()
{
	if (!function_exists('pods')) {
		return;
	}

	// Only run in admin
	if (!is_admin()) {
		return;
	}

	// Front Page (Page d'accueil)
	$front_page_id = get_option('page_on_front');
	if ($front_page_id) {
		bunbukan_prefill_homepage_fields($front_page_id);
	}
}

/**
 * Pre-fill homepage fields
 */
function bunbukan_prefill_homepage_fields($page_id)
{
	$pod = pods('page', $page_id);
	if (!$pod || !$pod->exists()) {
		return;
	}

	// ============================================================================
	// SECTION HERO
	// ============================================================================
	$hero_fields = array();

	if (!$pod->field('hero_title_jp')) {
		$hero_fields['hero_title_jp'] = '文武館';
	}
	if (!$pod->field('hero_title_en')) {
		$hero_fields['hero_title_en'] = 'Bunbukan Brussels';
	}
	if (!$pod->field('hero_subtitle')) {
		$hero_fields['hero_subtitle'] = 'Preserving the authentic traditions of Okinawan martial arts in Brussels';
	}
	if (!$pod->field('hero_discipline_1_title')) {
		$hero_fields['hero_discipline_1_title'] = 'Shitō-Ryū Karate';
	}
	if (!$pod->field('hero_discipline_1_subtitle')) {
		$hero_fields['hero_discipline_1_subtitle'] = 'Direct lineage from Kenei Mabuni';
	}
	if (!$pod->field('hero_discipline_2_title')) {
		$hero_fields['hero_discipline_2_title'] = 'Ryūkyū Kobudō';
	}
	if (!$pod->field('hero_discipline_2_subtitle')) {
		$hero_fields['hero_discipline_2_subtitle'] = 'Under Nakamoto Masahiro';
	}
	if (!$pod->field('hero_cta_text')) {
		$hero_fields['hero_cta_text'] = 'Begin Your Journey';
	}

	if (!empty($hero_fields)) {
		$pod->save($hero_fields);
	}

	// ============================================================================
	// SECTION ABOUT
	// ============================================================================
	$about_fields = array();

	if (!$pod->field('about_title')) {
		$about_fields['about_title'] = 'About Bunbukan Brussels';
	}
	if (!$pod->field('about_heritage_label')) {
		$about_fields['about_heritage_label'] = 'Heritage';
	}
	if (!$pod->field('about_heritage_title')) {
		$about_fields['about_heritage_title'] = 'SINCE 1977';
	}
	if (!$pod->field('about_heritage_text')) {
		$about_fields['about_heritage_text'] = 'As part of Budo Club Berchem, we preserve and transmit traditional martial arts taught at the Bunbukan Honbu Dōjō, while also continuing the Shitō-ryū karate lineage passed down by Kenei Mabuni.';
	}
	if (!$pod->field('about_mission_label')) {
		$about_fields['about_mission_label'] = 'Mission';
	}
	if (!$pod->field('about_mission_title')) {
		$about_fields['about_mission_title'] = 'THE WAY';
	}
	if (!$pod->field('about_mission_text')) {
		$about_fields['about_mission_text'] = 'Technical depth and character development. Respect for tradition while fostering personal growth through the path of martial arts.';
	}
	if (!$pod->field('about_stat_1_value')) {
		$about_fields['about_stat_1_value'] = '50';
	}
	if (!$pod->field('about_stat_1_suffix')) {
		$about_fields['about_stat_1_suffix'] = '+';
	}
	if (!$pod->field('about_stat_1_label')) {
		$about_fields['about_stat_1_label'] = 'Years Teaching';
	}
	if (!$pod->field('about_stat_2_value')) {
		$about_fields['about_stat_2_value'] = '3';
	}
	if (!$pod->field('about_stat_2_suffix')) {
		$about_fields['about_stat_2_suffix'] = '';
	}
	if (!$pod->field('about_stat_2_label')) {
		$about_fields['about_stat_2_label'] = 'Experienced Instructors';
	}
	if (!$pod->field('about_stat_3_value')) {
		$about_fields['about_stat_3_value'] = '100';
	}
	if (!$pod->field('about_stat_3_suffix')) {
		$about_fields['about_stat_3_suffix'] = '%';
	}
	if (!$pod->field('about_stat_3_label')) {
		$about_fields['about_stat_3_label'] = 'Authentic';
	}

	if (!empty($about_fields)) {
		$pod->save($about_fields);
	}

	// ============================================================================
	// SECTION DISCIPLINES
	// ============================================================================
	$disciplines_fields = array();

	if (!$pod->field('disciplines_title')) {
		$disciplines_fields['disciplines_title'] = 'Karate & Kobudō';
	}
	if (!$pod->field('disciplines_subtitle')) {
		$disciplines_fields['disciplines_subtitle'] = 'Karate and kobudō are two sides of the same coin. To understand one, you must practice the other.';
	}
	// Karate
	if (!$pod->field('karate_title_jp')) {
		$disciplines_fields['karate_title_jp'] = '糸東流空手道';
	}
	if (!$pod->field('karate_title')) {
		$disciplines_fields['karate_title'] = 'Shitō-Ryū Karate';
	}
	if (!$pod->field('karate_since')) {
		$disciplines_fields['karate_since'] = 'Since 1979';
	}
	if (!$pod->field('karate_description')) {
		$disciplines_fields['karate_description'] = 'Shitō-ryū is a traditional Okinawan karate style, known for the richness of its kata and the precision of its techniques. Rooted in both Shuri and Naha traditions, it offers a complete practice that combines technical rigor, fluid movement, and a deep understanding of martial principles.';
	}
	// Kobudo
	if (!$pod->field('kobudo_title_jp')) {
		$disciplines_fields['kobudo_title_jp'] = '琉球古武道';
	}
	if (!$pod->field('kobudo_title')) {
		$disciplines_fields['kobudo_title'] = 'Ryūkyū Kobudō';
	}
	if (!$pod->field('kobudo_since')) {
		$disciplines_fields['kobudo_since'] = 'Since 2001';
	}
	if (!$pod->field('kobudo_description')) {
		$disciplines_fields['kobudo_description'] = 'Ryūkyū Kobudō is the traditional weapon art of Okinawa, practiced alongside karate. At Bunbukan, this discipline develops coordination, control, and body awareness through the study of classical weapon forms and their applications, in an authentic and traditional approach.';
	}

	if (!empty($disciplines_fields)) {
		$pod->save($disciplines_fields);
	}

	// ============================================================================
	// SECTION INSTRUCTORS
	// ============================================================================
	$instructors_fields = array();

	if (!$pod->field('instructors_title')) {
		$instructors_fields['instructors_title'] = 'Our Instructors';
	}
	// Instructor 1 (Arnaud)
	if (!$pod->field('instructor_1_name')) {
		$instructors_fields['instructor_1_name'] = 'Arnaud Vankeijenbergh';
	}
	if (!$pod->field('instructor_1_title')) {
		$instructors_fields['instructor_1_title'] = '2nd Dan Shitō-Ryū, Technical Instructor (Karate)';
	}
	if (!$pod->field('instructor_1_description')) {
		$instructors_fields['instructor_1_description'] = 'Assists in Karate instruction. Focused on precise technique and kata understanding.';
	}
	// Instructor 2 (Alain)
	if (!$pod->field('instructor_2_name')) {
		$instructors_fields['instructor_2_name'] = 'Alain Berckmans';
	}
	if (!$pod->field('instructor_2_title')) {
		$instructors_fields['instructor_2_title'] = '6th Dan Shitō-Ryū (Shihan), Chief Instructor';
	}
	if (!$pod->field('instructor_2_description')) {
		$instructors_fields['instructor_2_description'] = 'Over 50 years of martial arts experience. Direct student of Kenei Mabuni and Nakamoto Masahiro.';
	}
	// Instructor 3 (Quentin)
	if (!$pod->field('instructor_3_name')) {
		$instructors_fields['instructor_3_name'] = 'Quentin Moreau';
	}
	if (!$pod->field('instructor_3_title')) {
		$instructors_fields['instructor_3_title'] = '2nd Dan Shitō-Ryū, Black Belt in Kobudō, Technical Instructor';
	}
	if (!$pod->field('instructor_3_description')) {
		$instructors_fields['instructor_3_description'] = 'Assists in both Karate and Kobudō. Special focus on Okinawan weapons training.';
	}

	if (!empty($instructors_fields)) {
		$pod->save($instructors_fields);
	}

	// ============================================================================
	// SECTION CONTACT
	// ============================================================================
	$contact_fields = array();

	if (!$pod->field('contact_title')) {
		$contact_fields['contact_title'] = 'Contact';
	}
	if (!$pod->field('contact_subtitle')) {
		$contact_fields['contact_subtitle'] = 'New members are always welcome. Come observe a class or join us for a free trial session.';
	}
	if (!$pod->field('contact_subtitle_2')) {
		$contact_fields['contact_subtitle_2'] = 'You can choose to practice only one of the two disciplines.';
	}
	// Schedule
	if (!$pod->field('schedule_title')) {
		$contact_fields['schedule_title'] = 'Training Schedule';
	}
	if (!$pod->field('schedule_1_day')) {
		$contact_fields['schedule_1_day'] = 'Wednesday';
	}
	if (!$pod->field('schedule_1_time')) {
		$contact_fields['schedule_1_time'] = '18:30–20:00';
	}
	if (!$pod->field('schedule_1_discipline')) {
		$contact_fields['schedule_1_discipline'] = 'Karate';
	}
	if (!$pod->field('schedule_2_day')) {
		$contact_fields['schedule_2_day'] = 'Wednesday';
	}
	if (!$pod->field('schedule_2_time')) {
		$contact_fields['schedule_2_time'] = '20:00–21:30';
	}
	if (!$pod->field('schedule_2_discipline')) {
		$contact_fields['schedule_2_discipline'] = 'Ryūkyū Kobudō';
	}
	if (!$pod->field('schedule_3_day')) {
		$contact_fields['schedule_3_day'] = 'Friday';
	}
	if (!$pod->field('schedule_3_time')) {
		$contact_fields['schedule_3_time'] = '19:00–20:30';
	}
	if (!$pod->field('schedule_3_discipline')) {
		$contact_fields['schedule_3_discipline'] = 'Karate';
	}
	if (!$pod->field('schedule_note')) {
		$contact_fields['schedule_note'] = 'Open to students aged 14 and up. All levels welcome.';
	}
	// Contact CTA
	if (!$pod->field('contact_cta_title')) {
		$contact_fields['contact_cta_title'] = 'Get in Touch';
	}
	if (!$pod->field('contact_cta_text')) {
		$contact_fields['contact_cta_text'] = 'Questions about training or want to arrange a visit? We would love to hear from you.';
	}
	if (!$pod->field('contact_email')) {
		$contact_fields['contact_email'] = 'info@bunbukan.eu';
	}
	if (!$pod->field('contact_email_button')) {
		$contact_fields['contact_email_button'] = 'Send Email';
	}
	if (!$pod->field('contact_address')) {
		$contact_fields['contact_address'] = 'Rue des Chalets 30, 1082 Berchem-Sainte-Agathe';
	}
	if (!$pod->field('contact_map_button')) {
		$contact_fields['contact_map_button'] = 'View Map';
	}

	if (!empty($contact_fields)) {
		$pod->save($contact_fields);
	}

	// ============================================================================
	// SECTION AFFILIATIONS
	// ============================================================================
	$affiliations_fields = array();

	if (!$pod->field('affiliations_title')) {
		$affiliations_fields['affiliations_title'] = 'Affiliations';
	}
	// Affiliation 1
	if (!$pod->field('affiliation_1_name')) {
		$affiliations_fields['affiliation_1_name'] = 'Budo Club Berchem Brussels';
	}
	if (!$pod->field('affiliation_1_url')) {
		$affiliations_fields['affiliation_1_url'] = 'https://budo-club-berchem.be/';
	}
	// Affiliation 2
	if (!$pod->field('affiliation_2_name')) {
		$affiliations_fields['affiliation_2_name'] = 'Vlaamse Karate Associatie (VKA)';
	}
	if (!$pod->field('affiliation_2_url')) {
		$affiliations_fields['affiliation_2_url'] = 'https://vlaamse-karate-associatie.be/';
	}
	// Affiliation 3
	if (!$pod->field('affiliation_3_name')) {
		$affiliations_fields['affiliation_3_name'] = 'Shitokai Belgium';
	}
	if (!$pod->field('affiliation_3_url')) {
		$affiliations_fields['affiliation_3_url'] = 'https://www.shitokai.be/';
	}
	// Affiliation 4
	if (!$pod->field('affiliation_4_name')) {
		$affiliations_fields['affiliation_4_name'] = 'Dento Shito-Ryu (Japan)';
	}
	if (!$pod->field('affiliation_4_url')) {
		$affiliations_fields['affiliation_4_url'] = 'https://www.dento-shitoryu.org/en';
	}
	// Affiliation 5
	if (!$pod->field('affiliation_5_name')) {
		$affiliations_fields['affiliation_5_name'] = 'Ono-ha Itto-Ryu';
	}
	if (!$pod->field('affiliation_5_url')) {
		$affiliations_fields['affiliation_5_url'] = 'https://www.onohaittoryu.be/';
	}
	// Affiliation 6
	if (!$pod->field('affiliation_6_name')) {
		$affiliations_fields['affiliation_6_name'] = 'Hontai Yoshin Ryu';
	}
	if (!$pod->field('affiliation_6_url')) {
		$affiliations_fields['affiliation_6_url'] = 'https://www.hontaiyoshinryu.be/';
	}
	// Affiliation 7
	if (!$pod->field('affiliation_7_name')) {
		$affiliations_fields['affiliation_7_name'] = 'Sport Brussel';
	}
	if (!$pod->field('affiliation_7_url')) {
		$affiliations_fields['affiliation_7_url'] = 'https://www.vgc.be/';
	}
	// Affiliation 8
	if (!$pod->field('affiliation_8_name')) {
		$affiliations_fields['affiliation_8_name'] = 'Commune de Berchem-Sainte-Agathe';
	}
	if (!$pod->field('affiliation_8_url')) {
		$affiliations_fields['affiliation_8_url'] = 'https://berchem.brussels/';
	}

	if (!empty($affiliations_fields)) {
		$pod->save($affiliations_fields);
	}
}

/**
 * Add migration menu page
 */
add_action('admin_menu', 'bunbukan_add_migration_menu');
function bunbukan_add_migration_menu()
{
	add_submenu_page(
		'tools.php',
		'Migration Pods Bunbukan',
		'Migration Pods',
		'manage_options',
		'bunbukan-pods-migration',
		'bunbukan_migration_page'
	);
}

/**
 * Migration page callback
 */
function bunbukan_migration_page()
{
	if (isset($_POST['bunbukan_migrate_data']) && check_admin_referer('bunbukan_migrate')) {
		try {
			bunbukan_prefill_pods_fields();
			echo '<div class="notice notice-success"><p>✅ Migration terminée ! Les champs ont été pré-remplis avec le contenu par défaut.</p>';
			echo '<p><strong>Champs pré-remplis automatiquement :</strong></p>';
			echo '<ul style="margin-left: 20px;">';
			echo '<li>✅ Section Hero (titres, sous-titres, disciplines, bouton)</li>';
			echo '<li>✅ Section À propos (heritage, mission, statistiques)</li>';
			echo '<li>✅ Section Disciplines (Karate et Kobudō - titres, descriptions)</li>';
			echo '<li>✅ Section Instructeurs (3 instructeurs - noms, titres, descriptions)</li>';
			echo '<li>✅ Section Contact (horaires, coordonnées, CTA)</li>';
			echo '<li>✅ Section Affiliations (7 affiliations - noms, URLs)</li>';
			echo '</ul></div>';
		} catch (Exception $e) {
			echo '<div class="notice notice-error"><p>❌ Erreur lors de la migration : ' . esc_html($e->getMessage()) . '</p></div>';
		}
	}
	?>
	<div class="wrap">
		<h1>Migration Pods - Pré-remplir les Champs</h1>

		<?php if (!function_exists('pods')): ?>
			<div class="notice notice-error">
				<p><strong>❌ Le plugin Pods n'est pas installé ou activé.</strong></p>
				<p>Pour utiliser cette fonctionnalité, veuillez :</p>
				<ol>
					<li>Aller dans <strong>Extensions > Ajouter</strong></li>
					<li>Rechercher "<strong>Pods - Custom Content Types and Fields</strong>"</li>
					<li>Installer et activer le plugin</li>
				</ol>
			</div>
		<?php else: ?>
			<div class="notice notice-info">
				<p>✅ Le plugin Pods est actif.</p>
			</div>

			<p>Cette fonction va remplir tous les champs Pods avec le contenu par défaut du site Bunbukan.</p>
			<p><strong>Note :</strong> Cette action ne remplacera que les champs vides. Les champs déjà remplis ne seront
				pas modifiés.</p>

			<h2>Comment utiliser Pods pour éditer le contenu</h2>
			<ol>
				<li><strong>Assurez-vous qu'une page d'accueil est définie</strong> dans Réglages > Lecture</li>
				<li><strong>Éditez cette page</strong> - les champs Pods apparaîtront sous l'éditeur</li>
				<li><strong>Remplissez les champs</strong> pour personnaliser le contenu de chaque section</li>
				<li>Les traductions sont gérées par Polylang via les fichiers .po/.mo</li>
			</ol>

			<form method="post">
				<?php wp_nonce_field('bunbukan_migrate'); ?>
				<p>
					<button type="submit" name="bunbukan_migrate_data" class="button button-primary button-large">
						🚀 Pré-remplir tous les champs Pods
					</button>
				</p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}
