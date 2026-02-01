<?php
/**
 * Template Name: Accueil
 * Template for the Bunbukan home page
 *
 * @package Bunbukan
 */

get_header();

// Get the front page ID for Pods fields
$page_id = get_option('page_on_front');
if (!$page_id) {
	$page_id = get_the_ID();
}

// Helper for SVG dividers
if (!function_exists('bunbukan_render_divider')) {
	function bunbukan_render_divider($position = 'top')
	{
		$class = 'bb-brush-divider bb-brush-divider--' . $position;
		echo '<div class="' . esc_attr($class) . '"></div>';
	}
}

// Helper function to find image with any extension
if (!function_exists('bunbukan_find_image')) {
	function bunbukan_find_image($base_name, $search_paths = array())
	{
		$extensions = array('.svg', '.SVG', '.jpg', '.jpeg', '.png', '.gif', '.webp', '.JPG', '.JPEG', '.PNG', '.GIF');

		foreach ($search_paths as $path) {
			foreach ($extensions as $ext) {
				$full_path = $path . $base_name . $ext;
				if (function_exists('bunbukan_asset_url')) {
					$url = bunbukan_asset_url($full_path, '');
					if ($url) {
						return $url;
					}
				}
			}
		}
		return '';
	}
}

// Media helper: get attachment URL by slug (post_name).
if (!function_exists('bunbukan_attachment_url_by_slug')) {
	function bunbukan_attachment_url_by_slug($slug)
	{
		$slug = sanitize_title((string) $slug);
		if (!$slug) {
			return '';
		}
		$att = get_page_by_path($slug, OBJECT, 'attachment');
		if (!$att) {
			return '';
		}
		$url = wp_get_attachment_url($att->ID);
		return $url ? (string) $url : '';
	}
}

// ============================================================================
// GET PODS FIELD VALUES WITH FALLBACKS
// ============================================================================

// Hero Section
$hero_bg_url = bunbukan_get_image_field('hero_background', '/assets/images/shuri-castle.gif', $page_id);
$hero_title_jp = bunbukan_get_text_field('hero_title_jp', '文武館', $page_id);
$hero_title_en = bunbukan_get_text_field('hero_title_en', 'Bunbukan Brussels', $page_id);
$hero_subtitle = bunbukan_get_wysiwyg_field('hero_subtitle', 'Preserving the authentic traditions of Okinawan martial arts in Brussels', $page_id);
$hero_discipline_1_title = bunbukan_get_text_field('hero_discipline_1_title', 'Shitō-Ryū Karate', $page_id);
$hero_discipline_1_subtitle = bunbukan_get_text_field('hero_discipline_1_subtitle', 'Direct lineage from Kenei Mabuni', $page_id);
$hero_discipline_2_title = bunbukan_get_text_field('hero_discipline_2_title', 'Ryūkyū Kobudō', $page_id);
$hero_discipline_2_subtitle = bunbukan_get_text_field('hero_discipline_2_subtitle', 'Under Nakamoto Masahiro', $page_id);
$hero_cta_text = bunbukan_get_text_field('hero_cta_text', 'Begin Your Journey', $page_id);

// About Section
$about_title = bunbukan_get_text_field('about_title', 'About Bunbukan Brussels', $page_id);
$about_image = bunbukan_get_image_field('about_image', '', $page_id);
if (!$about_image) {
	$about_image = bunbukan_find_image('DSC01113', array('/assets/images/', '/bunbukan-eu/public/images/'));
}
$about_heritage_label = bunbukan_get_text_field('about_heritage_label', 'Heritage', $page_id);
$about_heritage_title = bunbukan_get_text_field('about_heritage_title', 'SINCE 1977', $page_id);
$about_heritage_text = bunbukan_get_wysiwyg_field('about_heritage_text', 'As part of Budo Club Berchem, we preserve and transmit traditional martial arts taught at the Bunbukan Honbu Dōjō, while also continuing the Shitō-ryū karate lineage passed down by Kenei Mabuni.', $page_id);
$about_mission_label = bunbukan_get_text_field('about_mission_label', 'Mission', $page_id);
$about_mission_title = bunbukan_get_text_field('about_mission_title', 'THE WAY', $page_id);
$about_mission_text = bunbukan_get_wysiwyg_field('about_mission_text', 'Technical depth and character development. Respect for tradition while fostering personal growth through the path of martial arts.', $page_id);
$about_stat_1_value = bunbukan_get_number_field('about_stat_1_value', 50, $page_id);
$about_stat_1_suffix = bunbukan_get_text_field('about_stat_1_suffix', '+', $page_id);
$about_stat_1_label = bunbukan_get_text_field('about_stat_1_label', 'Years Teaching', $page_id);
$about_stat_2_value = bunbukan_get_number_field('about_stat_2_value', 3, $page_id);
$about_stat_2_suffix = bunbukan_get_text_field('about_stat_2_suffix', '', $page_id);
$about_stat_2_label = bunbukan_get_text_field('about_stat_2_label', 'Experienced Instructors', $page_id);
$about_stat_3_value = bunbukan_get_number_field('about_stat_3_value', 100, $page_id);
$about_stat_3_suffix = bunbukan_get_text_field('about_stat_3_suffix', '%', $page_id);
$about_stat_3_label = bunbukan_get_text_field('about_stat_3_label', 'Authentic', $page_id);

// Disciplines Section
$disciplines_title = bunbukan_get_text_field('disciplines_title', 'Karate & Kobudō', $page_id);
$disciplines_subtitle = bunbukan_get_wysiwyg_field('disciplines_subtitle', "Karate and kobudō are two sides of the same coin. To understand one, you must practice the other.", $page_id);
$karate_title_jp = bunbukan_get_text_field('karate_title_jp', '糸東流空手道', $page_id);
$karate_title = bunbukan_get_text_field('karate_title', 'Shitō-Ryū Karate', $page_id);
$karate_since = bunbukan_get_text_field('karate_since', 'Since 1979', $page_id);
$karate_description = bunbukan_get_wysiwyg_field('karate_description', 'Shitō-ryū is a traditional Okinawan karate style, known for the richness of its kata and the precision of its techniques. Rooted in both Shuri and Naha traditions, it offers a complete practice that combines technical rigor, fluid movement, and a deep understanding of martial principles.', $page_id);
$karate_image = bunbukan_get_image_field('karate_image', '', $page_id);
$karate_logo = bunbukan_get_image_field('karate_logo', '', $page_id);
$kobudo_title_jp = bunbukan_get_text_field('kobudo_title_jp', '琉球古武道', $page_id);
$kobudo_title = bunbukan_get_text_field('kobudo_title', 'Ryūkyū Kobudō', $page_id);
$kobudo_since = bunbukan_get_text_field('kobudo_since', 'Since 2001', $page_id);
$kobudo_description = bunbukan_get_wysiwyg_field('kobudo_description', 'Ryūkyū Kobudō is the traditional weapon art of Okinawa, practiced alongside karate. At Bunbukan, this discipline develops coordination, control, and body awareness through the study of classical weapon forms and their applications, in an authentic and traditional approach.', $page_id);
$kobudo_image = bunbukan_get_image_field('kobudo_image', '', $page_id);
$kobudo_logo = bunbukan_get_image_field('kobudo_logo', '', $page_id);

// Fallbacks for discipline images/logos
if (!$karate_logo) {
	$karate_logo = bunbukan_attachment_url_by_slug('shito-ryu-logo')
		?: bunbukan_find_image('shito-ryu-logo', array('/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/'))
		?: bunbukan_find_image('shitoryu', array('/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/'));
}
if (!$kobudo_logo) {
	$kobudo_logo = bunbukan_attachment_url_by_slug('bunbukan-background-logo-copy')
		?: bunbukan_find_image('bunbukan-background-logo-copy', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'))
		?: bunbukan_find_image('bunbukan-bg-logo', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'))
		?: bunbukan_find_image('bunbukan-logo', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'));
}
if (!$karate_image) {
	$karate_image = bunbukan_attachment_url_by_slug('makiwara-tsuki')
		?: bunbukan_find_image('makiwara-tsuki', array('/assets/images/', '/assets/images/disciplines/', '/bunbukan-eu/public/images/'));
}
if (!$kobudo_image) {
	$kobudo_image = bunbukan_attachment_url_by_slug('makiwara-men-uchi')
		?: bunbukan_find_image('makiwara-men-uchi', array('/assets/images/', '/assets/images/disciplines/', '/bunbukan-eu/public/images/'));
}

// Instructors Section
$instructors_title = bunbukan_get_text_field('instructors_title', 'Our Instructors', $page_id);
$instructor_1 = bunbukan_get_instructor(1, $page_id);
$instructor_2 = bunbukan_get_instructor(2, $page_id);
$instructor_3 = bunbukan_get_instructor(3, $page_id);

// Fallback for instructor images using bunbukan_find_image
if (!$instructor_1['image']) {
	$instructor_1['image'] = bunbukan_find_image('arnaud-enhanced', array('/assets/images/', '/bunbukan-eu/public/images/'));
}
if (!$instructor_2['image']) {
	$instructor_2['image'] = bunbukan_find_image('SAI-FINAL', array('/assets/images/', '/bunbukan-eu/public/images/'));
}
if (!$instructor_3['image']) {
	$instructor_3['image'] = bunbukan_find_image('quentin-enhanced', array('/assets/images/', '/bunbukan-eu/public/images/'));
}

$instructors = array($instructor_1, $instructor_2, $instructor_3);

// Contact Section
$contact_title = bunbukan_get_text_field('contact_title', 'Contact', $page_id);
$contact_subtitle = bunbukan_get_wysiwyg_field('contact_subtitle', 'New members are always welcome. Come observe a class or join us for a free trial session.', $page_id);
$contact_subtitle_2 = bunbukan_get_text_field('contact_subtitle_2', 'You can choose to practice only one of the two disciplines.', $page_id);
$schedule_title = bunbukan_get_text_field('schedule_title', 'Training Schedule', $page_id);
$schedule_1 = bunbukan_get_schedule_row(1, $page_id);
$schedule_2 = bunbukan_get_schedule_row(2, $page_id);
$schedule_3 = bunbukan_get_schedule_row(3, $page_id);
$schedule_note = bunbukan_get_text_field('schedule_note', 'Open to students aged 14 and up. All levels welcome.', $page_id);
$contact_cta_title = bunbukan_get_text_field('contact_cta_title', 'Get in Touch', $page_id);
$contact_cta_text = bunbukan_get_wysiwyg_field('contact_cta_text', 'Questions about training or want to arrange a visit? We would love to hear from you.', $page_id);
$contact_email = bunbukan_get_email_field('contact_email', 'info@bunbukan.eu', $page_id);
$contact_email_button = bunbukan_get_text_field('contact_email_button', 'Send Email', $page_id);
$contact_address = bunbukan_get_text_field('contact_address', 'Rue des Chalets 30, 1030 Schaerbeek', $page_id);
$contact_map_button = bunbukan_get_text_field('contact_map_button', 'View Map', $page_id);

// Affiliations Section
$affiliations_title = bunbukan_get_text_field('affiliations_title', 'Affiliations', $page_id);
$affiliations = array();
for ($i = 1; $i <= 7; $i++) {
	$affiliations[] = bunbukan_get_affiliation($i, $page_id);
}

?>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section id="home" class="bb-hero" <?php echo $hero_bg_url ? 'style="background-image: url(\'' . esc_url($hero_bg_url) . '\');"' : ''; ?>>
		<div class="bb-hero__content">
			<?php
			// Bunbukan logo (back of coin)
			$bunbukan_logo_url = '';
			if (function_exists('has_custom_logo') && has_custom_logo()) {
				$custom_logo_id = get_theme_mod('custom_logo');
				$bunbukan_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
			}
			if (!$bunbukan_logo_url && function_exists('bunbukan_asset_url')) {
				$bunbukan_logo_url = bunbukan_asset_url('/assets/images/bunbukan.jpg')
					?: bunbukan_asset_url('/assets/images/logos/Bunbukan-Brussels-Icon.png')
					?: bunbukan_asset_url('/assets/images/logoBBK32.jpg');
			}

			// Shito-Ryu logo (front of coin)
			$shitoryu_logo_url = '';
			if (function_exists('bunbukan_asset_url')) {
				$shitoryu_logo_url = bunbukan_asset_url('/assets/images/shito-ryu-logo.jpg')
					?: bunbukan_asset_url('/assets/images/shito-ryu-logo.png')
					?: bunbukan_asset_url('/assets/images/logos/shito-ryu-logo.jpg');
			}

			if ($bunbukan_logo_url || $shitoryu_logo_url):
				?>
				<div class="bb-hero__logo-spinner">
					<div class="bb-hero__logo-coin">
						<?php if ($shitoryu_logo_url): ?>
							<img src="<?php echo esc_url($shitoryu_logo_url); ?>"
								alt="<?php echo esc_attr__('Shitō-Ryū Karate logo', 'bunbukan'); ?>"
								class="bb-hero__logo bb-hero__logo--front" />
						<?php endif; ?>
						<?php if ($bunbukan_logo_url): ?>
							<img src="<?php echo esc_url($bunbukan_logo_url); ?>"
								alt="<?php echo esc_attr__('Bunbukan logo', 'bunbukan'); ?>"
								class="bb-hero__logo bb-hero__logo--back" />
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<h1 class="bb-hero__title japanese-font">
				<span class="gradient-text"><?php echo esc_html($hero_title_jp); ?></span><br>
				<span><?php echo esc_html($hero_title_en); ?></span>
			</h1>

			<p class="bb-hero__subtitle">
				<?php echo wp_kses_post(bunbukan_strip_p_tags($hero_subtitle)); ?>
			</p>

			<div class="bb-hero__disciplines">
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title"><?php echo esc_html($hero_discipline_1_title); ?></p>
					<p class="bb-hero__discipline-subtitle">
						<?php echo esc_html($hero_discipline_1_subtitle); ?>
					</p>
				</div>
				<div class="bb-hero__divider"></div>
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title"><?php echo esc_html($hero_discipline_2_title); ?></p>
					<p class="bb-hero__discipline-subtitle">
						<?php echo esc_html($hero_discipline_2_subtitle); ?>
					</p>
				</div>
			</div>

			<a href="#contact"
				class="bb-btn bb-btn--primary"><?php echo esc_html($hero_cta_text); ?></a>
		</div>
	</section>

	<!-- About Section - Option 3: Layered Panels -->
	<section id="about" class="bb-section">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text">
					<?php echo esc_html($about_title); ?>
				</h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-about__composition">
				<!-- Full-width image panel -->
				<div class="bb-about__image-panel bb-scroll-reveal bb-scroll-reveal--left">
					<div class="bb-about__image">
						<?php if ($about_image): ?>
							<img src="<?php echo esc_url($about_image); ?>"
								alt="<?php echo esc_attr__('Bunbukan Brussels heritage', 'bunbukan'); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>

				<!-- Heritage & Mission - Dramatic Statement Section -->
				<div class="bb-about__statements">
					<div class="bb-about__statement bb-scroll-reveal bb-scroll-reveal--left">
						<div class="bb-about__statement-kanji">
							<span class="bb-about__kanji bb-about__kanji--multi">伝統</span>
						</div>
						<div class="bb-about__statement-content">
							<span
								class="bb-about__statement-script"><?php echo esc_html($about_heritage_label); ?></span>
							<h3 class="bb-about__statement-title"><?php echo esc_html($about_heritage_title); ?>
							</h3>
							<p class="bb-about__statement-text">
								<?php echo wp_kses_post(bunbukan_strip_p_tags($about_heritage_text)); ?>
							</p>
						</div>
					</div>

					<div class="bb-about__statement bb-about__statement--alt bb-scroll-reveal bb-scroll-reveal--right"
						data-delay="300">
						<div class="bb-about__statement-content">
							<span
								class="bb-about__statement-script"><?php echo esc_html($about_mission_label); ?></span>
							<h3 class="bb-about__statement-title"><?php echo esc_html($about_mission_title); ?></h3>
							<p class="bb-about__statement-text">
								<?php echo wp_kses_post(bunbukan_strip_p_tags($about_mission_text)); ?>
							</p>
						</div>
						<div class="bb-about__statement-kanji">
							<span class="bb-about__kanji">道</span>
						</div>
					</div>
				</div>

				<!-- Stats panel - single glass bar -->
				<div class="bb-about__stats-panel bb-scroll-reveal bb-scroll-reveal--up" data-delay="200">
					<div class="bb-about__stats">
						<div class="bb-about__stat">
							<div class="bb-about__stat-number">
								<span class="bb-about__stat-number-value" data-count-target="<?php echo intval($about_stat_1_value); ?>">0</span>
								<?php if ($about_stat_1_suffix): ?>
									<span class="bb-about__stat-number-suffix"><?php echo esc_html($about_stat_1_suffix); ?></span>
								<?php endif; ?>
							</div>
							<p class="bb-about__stat-label"><?php echo esc_html($about_stat_1_label); ?></p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number">
								<span class="bb-about__stat-number-value" data-count-target="<?php echo intval($about_stat_2_value); ?>">0</span>
								<?php if ($about_stat_2_suffix): ?>
									<span class="bb-about__stat-number-suffix"><?php echo esc_html($about_stat_2_suffix); ?></span>
								<?php endif; ?>
							</div>
							<p class="bb-about__stat-label">
								<?php echo esc_html($about_stat_2_label); ?>
							</p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number bb-about__stat-number--percent">
								<span class="bb-about__stat-number-value" data-count-target="<?php echo intval($about_stat_3_value); ?>">0</span>
								<?php if ($about_stat_3_suffix): ?>
									<span class="bb-about__stat-number-suffix"><?php echo esc_html($about_stat_3_suffix); ?></span>
								<?php endif; ?>
							</div>
							<p class="bb-about__stat-label"><?php echo esc_html($about_stat_3_label); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Disciplines Section - Two Sides of a Coin -->
	<section id="disciplines" class="bb-section bb-about">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html($disciplines_title); ?>
				</h2>
				<div class="bb-section__divider"></div>
				<p class="bb-section__subtitle">
					<?php echo wp_kses_post(bunbukan_strip_p_tags($disciplines_subtitle)); ?>
				</p>
			</div>

			<div class="bb-disciplines__stack">
				<!-- Karate Row -->
				<div class="bb-discipline-row bb-discipline-row--karate">
					<div class="bb-discipline-row__image bb-discipline-row__image--karate bb-scroll-reveal bb-scroll-reveal--left">
						<?php if ($karate_image): ?>
							<img src="<?php echo esc_url($karate_image); ?>"
								alt="<?php echo esc_attr__('Karate training at makiwara', 'bunbukan'); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
					<div class="bb-about__card bb-discipline-card"
						<?php echo $karate_logo ? 'style="--bb-disciplines-logo: url(\'' . esc_url($karate_logo) . '\');"' : ''; ?>>
						<div class="bb-discipline-card__bg-logo"></div>
						<div class="bb-discipline-card__content">
							<h3 class="bb-disciplines__title-jp japanese-font"><?php echo esc_html($karate_title_jp); ?></h3>
							<h4 class="bb-disciplines__title"><?php echo esc_html($karate_title); ?></h4>
							<p class="bb-disciplines__since"><?php echo esc_html($karate_since); ?></p>
						<p class="bb-disciplines__description">
							<?php echo wp_kses_post(bunbukan_strip_p_tags($karate_description)); ?>
						</p>
						</div>
					</div>
				</div>

				<!-- Kobudo Row (mirror) -->
				<div class="bb-discipline-row bb-discipline-row--kobudo">
					<div class="bb-about__card bb-discipline-card bb-discipline-card--kobudo"
						<?php echo $kobudo_logo ? 'style="--bb-disciplines-logo: url(\'' . esc_url($kobudo_logo) . '\');"' : ''; ?>>
						<div class="bb-discipline-card__bg-logo"></div>
						<div class="bb-discipline-card__content">
							<h3 class="bb-disciplines__title-jp japanese-font"><?php echo esc_html($kobudo_title_jp); ?></h3>
							<h4 class="bb-disciplines__title"><?php echo esc_html($kobudo_title); ?></h4>
							<p class="bb-disciplines__since"><?php echo esc_html($kobudo_since); ?></p>
						<p class="bb-disciplines__description bb-disciplines__description--left">
							<?php echo wp_kses_post(bunbukan_strip_p_tags($kobudo_description)); ?>
						</p>
						</div>
					</div>
					<div class="bb-discipline-row__image bb-discipline-row__image--kobudo bb-scroll-reveal bb-scroll-reveal--right">
						<?php if ($kobudo_image): ?>
							<img src="<?php echo esc_url($kobudo_image); ?>"
								alt="<?php echo esc_attr__('Kobudō training at makiwara', 'bunbukan'); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Instructors Section -->
	<section id="instructors" class="bb-section">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html($instructors_title); ?>
				</h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-instructors__grid">
				<?php
				foreach ($instructors as $index => $instructor):
					$delay = $index * 200; // Stagger animation
					// Add class based on instructor name for mobile ordering
					$instructor_class = '';
					if (strpos($instructor['name'], 'Alain') !== false) {
						$instructor_class = 'bb-instructor-card--alain';
					} elseif (strpos($instructor['name'], 'Arnaud') !== false) {
						$instructor_class = 'bb-instructor-card--arnaud';
					} elseif (strpos($instructor['name'], 'Quentin') !== false) {
						$instructor_class = 'bb-instructor-card--quentin';
					}
					?>
					<div class="bb-about__card bb-instructor-card <?php echo esc_attr($instructor_class); ?> bb-scroll-reveal"
						style="transition-delay: <?php echo intval($delay); ?>ms">
						<div class="bb-instructor-card__image-wrapper">
							<img src="<?php echo esc_url($instructor['image']); ?>"
								alt="<?php echo esc_attr($instructor['name']); ?>" class="bb-instructor-card__image"
								style="<?php echo esc_attr($instructor['style']); ?>" loading="lazy" />
						</div>
						<div class="bb-instructor-card__content">
							<h3 class="bb-instructor-card__name"><?php echo esc_html($instructor['name']); ?></h3>
							<p class="bb-instructor-card__title"><?php echo esc_html($instructor['title']); ?></p>
							<p class="bb-instructor-card__desc"><?php echo wp_kses_post(bunbukan_strip_p_tags($instructor['description'])); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Contact Section -->
	<section id="contact" class="bb-section bb-about">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text">
					<?php echo esc_html($contact_title); ?>
				</h2>
				<div class="bb-section__divider"></div>
				<p class="bb-section__subtitle">
					<?php echo wp_kses_post(bunbukan_strip_p_tags($contact_subtitle)); ?>
					<br />
					<?php echo esc_html($contact_subtitle_2); ?>
				</p>
			</div>

			<div class="bb-contact__wrapper">
				<!-- Training Schedule -->
				<div class="bb-about__card bb-contact__schedule bb-scroll-reveal">
					<h3 class="bb-about__card-title"><?php echo esc_html($schedule_title); ?></h3>
					<div class="bb-dojo__schedule">
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html($schedule_1['day']); ?></span>
								<span class="bb-dojo__time"><?php echo esc_html($schedule_1['time']); ?></span>
							</div>
							<span class="bb-dojo__discipline"><?php echo esc_html($schedule_1['discipline']); ?></span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html($schedule_2['day']); ?></span>
								<span class="bb-dojo__time"><?php echo esc_html($schedule_2['time']); ?></span>
							</div>
							<span
								class="bb-dojo__discipline bb-dojo__discipline--kobudo"><?php echo esc_html($schedule_2['discipline']); ?></span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html($schedule_3['day']); ?></span>
								<span class="bb-dojo__time"><?php echo esc_html($schedule_3['time']); ?></span>
							</div>
							<span class="bb-dojo__discipline"><?php echo esc_html($schedule_3['discipline']); ?></span>
						</div>
					</div>
					<p class="bb-contact__note">
						<?php echo esc_html($schedule_note); ?>
					</p>
				</div>

				<!-- Contact CTA -->
				<div class="bb-about__card bb-contact__cta bb-scroll-reveal bb-scroll-reveal--right" data-delay="200">
					<h3 class="bb-about__card-title"><?php echo esc_html($contact_cta_title); ?></h3>
					<p class="bb-about__card-text">
						<?php echo wp_kses_post(bunbukan_strip_p_tags($contact_cta_text)); ?>
					</p>
					<div class="bb-contact__actions">
						<a href="mailto:<?php echo esc_attr($contact_email); ?>"
							class="bb-btn bb-btn--primary"><?php echo esc_html($contact_email_button); ?></a>
						<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($contact_address); ?>"
							target="_blank" rel="noopener noreferrer"
							class="bb-btn bb-btn--outline"><?php echo esc_html($contact_map_button); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- Affiliations Section -->
	<section id="affiliations" class="bb-section" style="position: relative; z-index: 5;">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html($affiliations_title); ?></h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-affiliations" data-bb-affiliations>

				<div class="bb-affiliations__viewport" data-bb-affiliations-viewport>
					<div class="bb-affiliations__track">
						<?php
						// Double the array for seamless infinite scroll
						$duplicated_affiliations = array_merge($affiliations, $affiliations);
						foreach ($duplicated_affiliations as $aff): ?>
							<?php
							$logo_url = $aff['logo'];
							$logo_key = strtolower(pathinfo($logo_url, PATHINFO_FILENAME));
							?>
							<div class="bb-affiliation-slide">
								<?php if (!empty($aff['url'])): ?>
									<a href="<?php echo esc_url($aff['url']); ?>" target="_blank" rel="noopener noreferrer"
										class="bb-affiliation-card">
										<?php if ($logo_url): ?>
											<img class="bb-affiliation-card__logo" data-logo="<?php echo esc_attr($logo_key); ?>"
												src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($aff['name']); ?>"
												loading="lazy" />
										<?php endif; ?>
										<p class="bb-affiliation-card__name"><?php echo esc_html($aff['name']); ?></p>
									</a>
								<?php else: ?>
									<div class="bb-affiliation-card">
										<?php if ($logo_url): ?>
											<img class="bb-affiliation-card__logo" data-logo="<?php echo esc_attr($logo_key); ?>"
												src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($aff['name']); ?>"
												loading="lazy" />
										<?php endif; ?>
										<p class="bb-affiliation-card__name"><?php echo esc_html($aff['name']); ?></p>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

			</div>
		</div>
		<?php bunbukan_render_divider('bottom'); ?>
	</section>

</main><!-- #main -->

<?php
get_footer();
