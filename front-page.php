<?php
/**
 * Template Name: Accueil
 * Template for the Bunbukan home page
 *
 * @package Bunbukan
 */

get_header();

// Hero background image (prefer uploaded file; fallback to CSS background if missing)
$hero_bg_path = get_template_directory() . '/assets/images/shuri-castle.gif';
$hero_bg_url = '';
if (file_exists($hero_bg_path)) {
	$hero_bg_url = get_template_directory_uri() . '/assets/images/shuri-castle.gif';
}

// Helper for SVG dividers
if (!function_exists('bunbukan_render_divider')) {
	function bunbukan_render_divider($position = 'top')
	{
		$class = 'bb-brush-divider bb-brush-divider--' . $position;
		echo '<div class="' . esc_attr($class) . '"></div>';
	}
}
?>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section id="home" class="bb-hero" <?php echo $hero_bg_url ? 'style="background-image: url(\'' . esc_url($hero_bg_url) . '\');"' : ''; ?>>
		<div class="bb-hero__content">
			<?php
			// Bunbukan logo (front of coin)
			$bunbukan_logo_url = '';
			if (function_exists('has_custom_logo') && has_custom_logo()) {
				$custom_logo_id = get_theme_mod('custom_logo');
				$bunbukan_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
			}
			if (!$bunbukan_logo_url && function_exists('bunbukan_asset_url')) {
				$bunbukan_logo_url = bunbukan_asset_url('/assets/images/bunbukan.jpg') ?: bunbukan_asset_url('/assets/images/logoBBK32.jpg');
			}

			// Shito-Ryu logo (back of coin)
			$shitoryu_logo_url = '';
			if (function_exists('bunbukan_asset_url')) {
				$shitoryu_logo_url = bunbukan_asset_url('/assets/images/shito-ryu-logo.jpeg')
					?: bunbukan_asset_url('/assets/images/shito-ryu-logo.jpg')
					?: bunbukan_asset_url('/assets/images/shito-ryu-logo.png');
			}

			if ($bunbukan_logo_url || $shitoryu_logo_url):
				?>
				<div class="bb-hero__logo-spinner">
					<div class="bb-hero__logo-coin">
						<?php if ($bunbukan_logo_url): ?>
							<img src="<?php echo esc_url($bunbukan_logo_url); ?>"
								alt="<?php echo esc_attr__('Bunbukan logo', 'bunbukan'); ?>"
								class="bb-hero__logo bb-hero__logo--front" />
						<?php endif; ?>
						<?php if ($shitoryu_logo_url): ?>
							<img src="<?php echo esc_url($shitoryu_logo_url); ?>"
								alt="<?php echo esc_attr__('Shitō-Ryū Karate logo', 'bunbukan'); ?>"
								class="bb-hero__logo bb-hero__logo--back" />
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<h1 class="bb-hero__title japanese-font">
				<span class="gradient-text">文武館</span><br>
				<span>Bunbukan Brussels</span>
			</h1>

			<p class="bb-hero__subtitle">
				<?php echo esc_html__('Preserving the authentic traditions of Okinawan martial arts in Brussels', 'bunbukan'); ?>
			</p>

			<div class="bb-hero__disciplines">
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title"><?php echo esc_html__('Shitō-Ryū Karate', 'bunbukan'); ?></p>
					<p class="bb-hero__discipline-subtitle">
						<?php echo esc_html__('Direct lineage from Kenei Mabuni', 'bunbukan'); ?>
					</p>
				</div>
				<div class="bb-hero__divider"></div>
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title"><?php echo esc_html__('Ryūkyū Kobudō', 'bunbukan'); ?></p>
					<p class="bb-hero__discipline-subtitle">
						<?php echo esc_html__('Under Nakamoto Masahiro', 'bunbukan'); ?>
					</p>
				</div>
			</div>

			<a href="#contact"
				class="bb-btn bb-btn--primary"><?php echo esc_html__('Begin Your Journey', 'bunbukan'); ?></a>
		</div>
	</section>

	<!-- About Section - Option 3: Layered Panels -->
	<section id="about" class="bb-section">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text">
					<?php echo esc_html__('About Bunbukan Brussels', 'bunbukan'); ?>
				</h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-about__composition">
				<!-- Full-width image panel -->
				<div class="bb-about__image-panel bb-scroll-reveal bb-scroll-reveal--left">
					<?php
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

					// Single heritage image
					$about_img = bunbukan_find_image('DSC01113', array('/assets/images/', '/bunbukan-eu/public/images/'));
					?>

					<div class="bb-about__image">
						<?php if ($about_img): ?>
							<img src="<?php echo esc_url($about_img); ?>"
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
								class="bb-about__statement-script"><?php echo esc_html__('Heritage', 'bunbukan'); ?></span>
							<h3 class="bb-about__statement-title"><?php echo esc_html__('SINCE 1977', 'bunbukan'); ?>
							</h3>
							<p class="bb-about__statement-text">
								<?php echo esc_html__('As part of Budo Club Berchem, we preserve and transmit traditional martial arts taught at the Bunbukan Honbu Dōjō, while also continuing the Shitō-ryū karate lineage passed down by Kenei Mabuni.', 'bunbukan'); ?>
							</p>
						</div>
					</div>

					<div class="bb-about__statement bb-about__statement--alt bb-scroll-reveal bb-scroll-reveal--right"
						data-delay="300">
						<div class="bb-about__statement-content">
							<span
								class="bb-about__statement-script"><?php echo esc_html__('Mission', 'bunbukan'); ?></span>
							<h3 class="bb-about__statement-title"><?php echo esc_html__('THE WAY', 'bunbukan'); ?></h3>
							<p class="bb-about__statement-text">
								<?php echo esc_html__('Technical depth and character development. Respect for tradition while fostering personal growth through the path of martial arts.', 'bunbukan'); ?>
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
								<span class="bb-about__stat-number-value" data-count-target="50">0</span>
								<span class="bb-about__stat-number-suffix">+</span>
							</div>
							<p class="bb-about__stat-label"><?php echo esc_html__('Years Teaching', 'bunbukan'); ?></p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number">
								<span class="bb-about__stat-number-value" data-count-target="3">0</span>
							</div>
							<p class="bb-about__stat-label">
								<?php echo esc_html__('Experienced Instructors', 'bunbukan'); ?>
							</p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number bb-about__stat-number--percent">
								<span class="bb-about__stat-number-value" data-count-target="100">0</span>
								<span class="bb-about__stat-number-suffix">%</span>
							</div>
							<p class="bb-about__stat-label"><?php echo esc_html__('Authentic', 'bunbukan'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
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
	?>

	<!-- Disciplines Section - Two Sides of a Coin -->
	<section id="disciplines" class="bb-section bb-about">
		<?php bunbukan_render_divider('top'); ?>
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__('Karate & Kobudō', 'bunbukan'); ?>
				</h2>
				<div class="bb-section__divider"></div>
				<p class="bb-section__subtitle">
					<?php echo esc_html__("Karate and kobudō are two sides of the same coin. To understand one, you must practice the other.", 'bunbukan'); ?>
				</p>
			</div>

			<div class="bb-disciplines__stack">
				<?php
				// Logos for background overlays
				$karate_logo =
					bunbukan_attachment_url_by_slug('shito-ryu-logo')
					?: bunbukan_find_image('shito-ryu-logo', array('/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/'))
					?: bunbukan_find_image('shitoryu', array('/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/'));

				$kobudo_logo =
					bunbukan_attachment_url_by_slug('bunbukan-background-logo-copy')
					?: bunbukan_find_image('bunbukan-background-logo-copy', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'))
					?: bunbukan_find_image('bunbukan-bg-logo', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'))
					?: bunbukan_find_image('bunbukan-logo', array('/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/'));

				$karate_image =
					bunbukan_attachment_url_by_slug('makiwara-tsuki')
					?: bunbukan_find_image('makiwara-tsuki', array('/assets/images/', '/assets/images/disciplines/', '/bunbukan-eu/public/images/'));

				$kobudo_image =
					bunbukan_attachment_url_by_slug('makiwara-men-uchi')
					?: bunbukan_find_image('makiwara-men-uchi', array('/assets/images/', '/assets/images/disciplines/', '/bunbukan-eu/public/images/'));
				?>

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
							<h3 class="bb-disciplines__title-jp japanese-font">糸東流空手道</h3>
							<h4 class="bb-disciplines__title"><?php echo esc_html__('Shitō-Ryū Karate', 'bunbukan'); ?></h4>
							<p class="bb-disciplines__since"><?php echo esc_html__('Since 1979', 'bunbukan'); ?></p>
						<p class="bb-disciplines__description">
							<?php echo esc_html__('Shitō-ryū is a traditional Okinawan karate style, known for the richness of its kata and the precision of its techniques. Rooted in both Shuri and Naha traditions, it offers a complete practice that combines technical rigor, fluid movement, and a deep understanding of martial principles.', 'bunbukan'); ?>
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
							<h3 class="bb-disciplines__title-jp japanese-font">琉球古武道</h3>
							<h4 class="bb-disciplines__title"><?php echo esc_html__('Ryūkyū Kobudō', 'bunbukan'); ?></h4>
							<p class="bb-disciplines__since"><?php echo esc_html__('Since 2001', 'bunbukan'); ?></p>
						<p class="bb-disciplines__description bb-disciplines__description--left">
							<?php echo esc_html__('Ryūkyū Kobudō is the traditional weapon art of Okinawa, practiced alongside karate. At Bunbukan, this discipline develops coordination, control, and body awareness through the study of classical weapon forms and their applications, in an authentic and traditional approach.', 'bunbukan'); ?>
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
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__('Our Instructors', 'bunbukan'); ?>
				</h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-instructors__grid">
				<?php
				$instructors = array(
					array(
						'name' => 'Arnaud Vankeijenbergh',
						'title' => esc_html__('2nd Dan Shitō-Ryū, Technical Instructor (Karate)', 'bunbukan'),
						'description' => esc_html__('Assists in Karate instruction. Focused on precise technique and kata understanding.', 'bunbukan'),
						'image' => bunbukan_find_image('arnaud-enhanced', array('/assets/images/', '/bunbukan-eu/public/images/')),
						'style' => '--bb-instructor-scale: 1; --bb-instructor-translate: -4px; --bb-instructor-position: 50% 12%;',
					),
					array(
						'name' => 'Alain Berckmans',
						'title' => esc_html__('6th Dan Shitō-Ryū (Shihan), Chief Instructor', 'bunbukan'),
						'description' => esc_html__('Over 50 years of martial arts experience. Direct student of Kenei Mabuni and Nakamoto Masahiro.', 'bunbukan'),
						'image' => bunbukan_find_image('SAI-FINAL', array('/assets/images/', '/bunbukan-eu/public/images/')),
						'style' => '',
					),
					array(
						'name' => 'Quentin Moreau',
						'title' => esc_html__('2nd Dan Shitō-Ryū, Black Belt in Kobudō, Technical Instructor', 'bunbukan'),
						'description' => esc_html__('Assists in both Karate and Kobudō. Special focus on Okinawan weapons training.', 'bunbukan'),
						'image' => bunbukan_find_image('quentin-enhanced', array('/assets/images/', '/bunbukan-eu/public/images/')),
						'style' => '--bb-instructor-scale: 1; --bb-instructor-translate: -4px; --bb-instructor-position: 50% 12%;',
					),
				);

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
							<p class="bb-instructor-card__desc"><?php echo esc_html($instructor['description']); ?></p>
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
					<?php echo esc_html__('Contact', 'bunbukan'); ?>
				</h2>
				<div class="bb-section__divider"></div>
				<p class="bb-section__subtitle">
					<?php echo esc_html__('New members are always welcome. Come observe a class or join us for a free trial session.', 'bunbukan'); ?>
					<br />
					<?php echo esc_html__('You can choose to practice only one of the two disciplines.', 'bunbukan'); ?>
				</p>
			</div>

			<div class="bb-contact__wrapper">
				<!-- Training Schedule -->
				<div class="bb-about__card bb-contact__schedule bb-scroll-reveal">
					<h3 class="bb-about__card-title"><?php echo esc_html__('Training Schedule', 'bunbukan'); ?></h3>
					<div class="bb-dojo__schedule">
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html__('Wednesday', 'bunbukan'); ?></span>
								<span class="bb-dojo__time">18:30–20:00</span>
							</div>
							<span class="bb-dojo__discipline"><?php echo esc_html__('Karate', 'bunbukan'); ?></span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html__('Wednesday', 'bunbukan'); ?></span>
								<span class="bb-dojo__time">20:00–21:30</span>
							</div>
							<span
								class="bb-dojo__discipline bb-dojo__discipline--kobudo"><?php echo esc_html__('Ryūkyū Kobudō', 'bunbukan'); ?></span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day"><?php echo esc_html__('Friday', 'bunbukan'); ?></span>
								<span class="bb-dojo__time">19:00–20:30</span>
							</div>
							<span class="bb-dojo__discipline"><?php echo esc_html__('Karate', 'bunbukan'); ?></span>
						</div>
					</div>
					<p class="bb-contact__note">
						<?php echo esc_html__('Open to students aged 14 and up. All levels welcome.', 'bunbukan'); ?>
					</p>
				</div>

				<!-- Contact CTA -->
				<div class="bb-about__card bb-contact__cta bb-scroll-reveal bb-scroll-reveal--right" data-delay="200">
					<h3 class="bb-about__card-title"><?php echo esc_html__('Get in Touch', 'bunbukan'); ?></h3>
					<p class="bb-about__card-text">
						<?php echo esc_html__('Questions about training or want to arrange a visit? We would love to hear from you.', 'bunbukan'); ?>
					</p>
					<div class="bb-contact__actions">
						<a href="mailto:info@bunbukan.eu"
							class="bb-btn bb-btn--primary"><?php echo esc_html__('Send Email', 'bunbukan'); ?></a>
						<a href="https://www.google.com/maps/search/?api=1&query=Rue+des+Chalets+30,+1030+Schaerbeek"
							target="_blank" rel="noopener noreferrer"
							class="bb-btn bb-btn--outline"><?php echo esc_html__('View Map', 'bunbukan'); ?></a>
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
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__('Affiliations', 'bunbukan'); ?></h2>
				<div class="bb-section__divider"></div>
			</div>

			<?php
			$affiliations = array(
				array(
					'name' => 'Budo Club Berchem Brussels',
					'logo' => 'budo-club-berchem-ico-3.png',
					'url' => 'https://www.budoclubberchem.be/',
				),
				array(
					'name' => 'Vlaamse Karate Associatie (VKA)',
					'logo' => 'vka-ico.png',
					'url' => 'https://www.vka.be/',
				),
				array(
					'name' => 'Shitokai Belgium',
					'logo' => 'shitokai-ico-2.png',
					'url' => 'https://www.shitokai.be/',
				),
				array(
					'name' => 'Dento Shito-Ryu (Japan)',
					'logo' => 'dento-shito-ryu-ico-8.png',
					'url' => 'https://www.dento-shitoryu.jp/',
				),
				array(
					'name' => 'Ono-ha Itto-Ryu',
					'logo' => 'ono-ha-itto-ryu-ico-7.png',
					'url' => 'https://www.ono-ha-ittoryu.be/',
				),
				array(
					'name' => 'Hontai Yoshin Ryu',
					'logo' => 'Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
					'url' => 'https://www.hontaiyoshinryu.be/',
				),
				array(
					'name' => 'Sport Brussel',
					'logo' => 'sport-brussel-ico-4.png',
					'url' => 'https://www.sport.brussels/',
				),
			);
			?>

			<div class="bb-affiliations" data-bb-affiliations>

				<div class="bb-affiliations__viewport" data-bb-affiliations-viewport>
					<div class="bb-affiliations__track">
						<?php
						// Double the array for seamless infinite scroll
						$duplicated_affiliations = array_merge($affiliations, $affiliations);
						foreach ($duplicated_affiliations as $aff): ?>
							<?php
							$logo_theme = '/assets/images/affiliations/' . $aff['logo'];
							$logo_react = '/bunbukan-eu/public/affiliations/' . $aff['logo'];
							$logo_url = function_exists('bunbukan_asset_url') ? bunbukan_asset_url($logo_theme, $logo_react) : '';
							$logo_key = strtolower(pathinfo($aff['logo'], PATHINFO_FILENAME));
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




