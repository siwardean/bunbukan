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
$hero_bg_url  = '';
if ( file_exists( $hero_bg_path ) ) {
	$hero_bg_url = get_template_directory_uri() . '/assets/images/shuri-castle.gif';
}
?>

<main id="primary" class="site-main">

	<!-- Hero Section -->
	<section id="home" class="bb-hero" <?php echo $hero_bg_url ? 'style="background-image: url(\'' . esc_url( $hero_bg_url ) . '\');"' : ''; ?>>
		<div class="bb-hero__content">
			<?php
			// Bunbukan logo (front of coin)
			$bunbukan_logo_url = '';
			if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$bunbukan_logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : '';
			}
			if ( ! $bunbukan_logo_url && function_exists( 'bunbukan_asset_url' ) ) {
				$bunbukan_logo_url = bunbukan_asset_url( '/assets/images/bunbukan.jpg' ) ?: bunbukan_asset_url( '/assets/images/logoBBK32.jpg' );
			}
			
			// Shito-Ryu logo (back of coin)
			$shitoryu_logo_url = '';
			if ( function_exists( 'bunbukan_asset_url' ) ) {
				$shitoryu_logo_url = bunbukan_asset_url( '/assets/images/shito-ryu-logo.jpeg' ) 
					?: bunbukan_asset_url( '/assets/images/shito-ryu-logo.jpg' )
					?: bunbukan_asset_url( '/assets/images/shito-ryu-logo.png' );
			}
			
			if ( $bunbukan_logo_url || $shitoryu_logo_url ) :
				?>
				<div class="bb-hero__logo-spinner">
					<div class="bb-hero__logo-coin">
						<?php if ( $bunbukan_logo_url ) : ?>
							<img src="<?php echo esc_url( $bunbukan_logo_url ); ?>" alt="<?php echo esc_attr__( 'Bunbukan logo', 'bunbukan' ); ?>" class="bb-hero__logo bb-hero__logo--front" />
						<?php endif; ?>
						<?php if ( $shitoryu_logo_url ) : ?>
							<img src="<?php echo esc_url( $shitoryu_logo_url ); ?>" alt="<?php echo esc_attr__( 'Shitō-Ryū Karate logo', 'bunbukan' ); ?>" class="bb-hero__logo bb-hero__logo--back" />
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			
			<h1 class="bb-hero__title japanese-font">
				<span class="gradient-text">武館</span><br>
				<span>Bunbukan Brussels</span>
			</h1>
			
			<p class="bb-hero__subtitle">
				Preserving the authentic traditions of Okinawan martial arts in Brussels
			</p>
			
			<div class="bb-hero__disciplines">
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title">Shitō-Ryū Karate</p>
					<p class="bb-hero__discipline-subtitle">Direct lineage from Kenei Mabuni</p>
				</div>
				<div class="bb-hero__divider"></div>
				<div class="bb-hero__discipline">
					<p class="bb-hero__discipline-title">Ryūkyū Kobudō</p>
					<p class="bb-hero__discipline-subtitle">Under Nakamoto Masahiro</p>
				</div>
			</div>
			
			<a href="#contact" class="bb-btn bb-btn--primary"><?php echo esc_html__( 'Begin Your Journey', 'bunbukan' ); ?></a>
		</div>
	</section>

	<!-- About Section - Option 3: Layered Panels -->
	<section id="about" class="bb-section bb-about">
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__( 'About Bunbukan Brussels', 'bunbukan' ); ?></h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-about__composition">
				<!-- Full-width image panel -->
				<div class="bb-about__image-panel bb-scroll-reveal bb-scroll-reveal--left">
					<?php
					// Helper function to find image with any extension
					if ( ! function_exists( 'bunbukan_find_image' ) ) {
						function bunbukan_find_image( $base_name, $search_paths = array() ) {
							$extensions = array( '.svg', '.SVG', '.jpg', '.jpeg', '.png', '.gif', '.webp', '.JPG', '.JPEG', '.PNG', '.GIF' );
							
							foreach ( $search_paths as $path ) {
								foreach ( $extensions as $ext ) {
									$full_path = $path . $base_name . $ext;
									if ( function_exists( 'bunbukan_asset_url' ) ) {
										$url = bunbukan_asset_url( $full_path, '' );
										if ( $url ) {
											return $url;
										}
									}
								}
							}
							return '';
						}
					}

					// Single heritage image
					$about_img = bunbukan_find_image( 'DSC01113', array( '/assets/images/', '/bunbukan-eu/public/images/' ) );
					?>

					<div class="bb-about__image">
						<?php if ( $about_img ) : ?>
							<img src="<?php echo esc_url( $about_img ); ?>" alt="Bunbukan Brussels heritage" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>

				<!-- Heritage & Mission - Dramatic Statement Section -->
				<div class="bb-about__statements">
					<div class="bb-about__statement bb-scroll-reveal bb-scroll-reveal--left">
						<div class="bb-about__statement-kanji">
							<span class="bb-about__kanji">伝統</span>
						</div>
						<div class="bb-about__statement-content">
							<span class="bb-about__statement-script">Heritage</span>
							<h3 class="bb-about__statement-title">SINCE 1977</h3>
							<p class="bb-about__statement-text">
								Part of Budo Club Berchem, we preserve and transmit traditional martial arts as practiced at the Bunbukan Honbu Dōjō in Okinawa.
							</p>
						</div>
					</div>

					<div class="bb-about__statement bb-about__statement--alt bb-scroll-reveal bb-scroll-reveal--right" data-delay="300">
						<div class="bb-about__statement-content">
							<span class="bb-about__statement-script">Mission</span>
							<h3 class="bb-about__statement-title">THE WAY</h3>
							<p class="bb-about__statement-text">
								Technical depth and character development. Respect for tradition while fostering personal growth through the path of martial arts.
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
							<div class="bb-about__stat-number">1977</div>
							<p class="bb-about__stat-label"><?php echo esc_html__( 'Since', 'bunbukan' ); ?></p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number">All</div>
							<p class="bb-about__stat-label"><?php echo esc_html__( 'Levels', 'bunbukan' ); ?></p>
						</div>
						<div class="bb-about__stat">
							<div class="bb-about__stat-number bb-about__stat-number--percent">
								<span class="bb-about__stat-number-value" data-count-target="100">0</span>
								<span class="bb-about__stat-number-suffix">%</span>
							</div>
							<p class="bb-about__stat-label"><?php echo esc_html__( 'Authentic', 'bunbukan' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	// Media helper: get attachment URL by slug (post_name).
	if ( ! function_exists( 'bunbukan_attachment_url_by_slug' ) ) {
		function bunbukan_attachment_url_by_slug( $slug ) {
			$slug = sanitize_title( (string) $slug );
			if ( ! $slug ) {
				return '';
			}
			$att = get_page_by_path( $slug, OBJECT, 'attachment' );
			if ( ! $att ) {
				return '';
			}
			$url = wp_get_attachment_url( $att->ID );
			return $url ? (string) $url : '';
		}
	}
	?>

	<!-- Disciplines Section - Two Sides of a Coin -->
	<section id="disciplines" class="bb-disciplines">
		<div class="bb-disciplines__header">
			<h2 class="bb-section__title gradient-text"><?php echo esc_html__( 'Karate & Kobudō', 'bunbukan' ); ?></h2>
			<div class="bb-section__divider"></div>
		</div>
		<?php
		// Logos for background overlays (prefer preprocessed logo assets; no photo backgrounds)
		$karate_logo =
			bunbukan_attachment_url_by_slug( 'shito-ryu-logo' )
			?: bunbukan_find_image( 'shito-ryu-logo', array( '/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/' ) )
			?: bunbukan_find_image( 'shitoryu', array( '/assets/images/logos/', '/assets/images/', '/bunbukan-eu/public/images/' ) );

		$kobudo_logo =
			bunbukan_attachment_url_by_slug( 'bunbukan-background-logo-copy' )
			?: bunbukan_find_image( 'bunbukan-background-logo-copy', array( '/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/' ) )
			?: bunbukan_find_image( 'bunbukan-bg-logo', array( '/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/' ) )
			?: bunbukan_find_image( 'bunbukan-logo', array( '/assets/images/', '/assets/images/logos/', '/bunbukan-eu/public/images/' ) );
		?>
		
		<!-- Karate Side (Grey) -->
		<div class="bb-disciplines__side bb-disciplines__side--karate" <?php echo $karate_logo ? 'style="--bb-disciplines-logo: url(\'' . esc_url( $karate_logo ) . '\');"' : ''; ?>>
			<div class="bb-disciplines__content bb-scroll-reveal bb-scroll-reveal--left">
				<h3 class="bb-disciplines__title-jp japanese-font">糸東流空手道</h3>
				<h4 class="bb-disciplines__title">Shitō-Ryū Karate</h4>
				<p class="bb-disciplines__since">Since 1979</p>
				<ul class="bb-disciplines__features">
					<li><strong>Kihon</strong> <span>Fundamentals</span></li>
					<li><strong>Kata</strong> <span>Traditional forms</span></li>
					<li><strong>Bunkai</strong> <span>Applications</span></li>
					<li><strong>Kumite</strong> <span>Sparring</span></li>
				</ul>
			</div>
		</div>
		
		<!-- Center Divider -->
		<div class="bb-disciplines__divider">
			<div class="bb-disciplines__divider-line"></div>
			<span class="bb-disciplines__divider-text gradient-text">二つの道</span>
			<div class="bb-disciplines__divider-line"></div>
		</div>
		
		<!-- Kobudo Side (Black) -->
		<div class="bb-disciplines__side bb-disciplines__side--kobudo" <?php echo $kobudo_logo ? 'style="--bb-disciplines-logo: url(\'' . esc_url( $kobudo_logo ) . '\');"' : ''; ?>>
			<div class="bb-disciplines__content bb-scroll-reveal bb-scroll-reveal--right">
				<h3 class="bb-disciplines__title-jp japanese-font">琉球古武道</h3>
				<h4 class="bb-disciplines__title">Ryūkyū Kobudō</h4>
				<p class="bb-disciplines__since">Since 2001</p>
				<ul class="bb-disciplines__features">
					<li><strong>Bō</strong> <span>Six-foot staff</span></li>
					<li><strong>Sai</strong> <span>Three-pronged weapon</span></li>
					<li><strong>Tonfa</strong> <span>Side-handle baton</span></li>
					<li><strong>Nunchaku</strong> <span>Flail weapon</span></li>
					<li><em>+ more traditional weapons</em></li>
				</ul>
			</div>
		</div>
	</section>

	<!-- Instructors Section -->
	<section id="instructors" class="bb-section bb-about">
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__( 'Our Instructors', 'bunbukan' ); ?></h2>
				<div class="bb-section__divider"></div>
			</div>

			<div class="bb-instructors__wrapper">
				<?php
				$alain_img = bunbukan_find_image( 'SAI-FINAL', array( '/assets/images/', '/bunbukan-eu/public/images/' ) );
				?>
				<?php if ( $alain_img ) : ?>
					<div class="bb-about__image-panel bb-scroll-reveal bb-scroll-reveal--left" style="height: 650px; margin-bottom: 0;">
						<div class="bb-about__image">
							<img src="<?php echo esc_url( $alain_img ); ?>" alt="Alain Berckmans" class="bb-instructor-portrait" loading="lazy" />
						</div>
					</div>
				<?php endif; ?>
				
				<div class="bb-about__card bb-scroll-reveal bb-scroll-reveal--right" style="padding: 3rem;">
					<h3 style="font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; font-family: var(--font-display); text-transform: uppercase; letter-spacing: 0.04em;">Alain Berckmans</h3>
					<p style="font-size: 1.25rem; color: #dc2626; margin-bottom: 2rem; font-weight: 600;">6th Dan Shitō-Ryū (Shihan)<br>Chief Instructor</p>
					<p style="color: rgba(255,255,255,0.92); line-height: 1.8; font-size: 1.125rem; margin-bottom: 1.5rem;">Over 50 years of martial arts experience. Direct student of Kenei Mabuni and Nakamoto Masahiro.</p>
					<p style="color: rgba(255,255,255,0.85); line-height: 1.8;">Alain Berckmans has dedicated his life to preserving and transmitting the authentic traditions of Okinawan martial arts, maintaining direct lineages to the founders of both Shitō-Ryū Karate and Ryūkyū Kobudō.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Affiliations Section -->
	<section id="affiliations" class="bb-section">
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__( 'Affiliations', 'bunbukan' ); ?></h2>
				<div class="bb-section__divider"></div>
			</div>

			<?php
			$affiliations = array(
				array(
					'name' => 'Budo Club Berchem Brussels',
					'logo' => 'budo-club-berchem-ico-3.png',
					'url'  => 'https://www.budoclubberchem.be/',
				),
				array(
					'name' => 'Vlaamse Karate Associatie (VKA)',
					'logo' => 'vka-ico.png',
					'url'  => 'https://www.vka.be/',
				),
				array(
					'name' => 'Shitokai Belgium',
					'logo' => 'shitokai-ico-2.png',
					'url'  => 'https://www.shitokai.be/',
				),
				array(
					'name' => 'Dento Shito-Ryu (Japan)',
					'logo' => 'dento-shito-ryu-ico-8.png',
					'url'  => 'https://www.dento-shitoryu.jp/',
				),
				array(
					'name' => 'Ono-ha Itto-Ryu',
					'logo' => 'ono-ha-itto-ryu-ico-7.png',
					'url'  => 'https://www.ono-ha-ittoryu.be/',
				),
				array(
					'name' => 'Hontai Yoshin Ryu',
					'logo' => 'Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
					'url'  => 'https://www.hontaiyoshinryu.be/',
				),
				array(
					'name' => 'Sport Brussel',
					'logo' => 'sport-brussel-ico-4.png',
					'url'  => 'https://www.sport.brussels/',
				),
			);
			?>

			<div class="bb-affiliations" data-bb-affiliations>
				<button class="bb-slider__nav bb-slider__nav--prev" type="button" aria-label="Previous affiliation" data-bb-affiliations-prev>
					<span aria-hidden="true">‹</span>
				</button>

				<div class="bb-affiliations__viewport" data-bb-affiliations-viewport>
					<div class="bb-affiliations__track">
					<?php foreach ( $affiliations as $aff ) : ?>
						<?php
						$logo_theme = '/assets/images/affiliations/' . $aff['logo'];
						$logo_react = '/bunbukan-eu/public/affiliations/' . $aff['logo'];
						$logo_url   = function_exists( 'bunbukan_asset_url' ) ? bunbukan_asset_url( $logo_theme, $logo_react ) : '';
						$logo_key   = strtolower( pathinfo( $aff['logo'], PATHINFO_FILENAME ) );
						?>
						<div class="bb-affiliation-slide">
							<?php if ( ! empty( $aff['url'] ) ) : ?>
								<a href="<?php echo esc_url( $aff['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="bb-affiliation-card">
									<?php if ( $logo_url ) : ?>
										<img class="bb-affiliation-card__logo" data-logo="<?php echo esc_attr( $logo_key ); ?>" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $aff['name'] ); ?>" loading="lazy" />
									<?php endif; ?>
									<p class="bb-affiliation-card__name"><?php echo esc_html( $aff['name'] ); ?></p>
								</a>
							<?php else : ?>
								<div class="bb-affiliation-card">
									<?php if ( $logo_url ) : ?>
										<img class="bb-affiliation-card__logo" data-logo="<?php echo esc_attr( $logo_key ); ?>" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $aff['name'] ); ?>" loading="lazy" />
									<?php endif; ?>
									<p class="bb-affiliation-card__name"><?php echo esc_html( $aff['name'] ); ?></p>
								</div>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<button class="bb-slider__nav bb-slider__nav--next" type="button" aria-label="Next affiliation" data-bb-affiliations-next>
					<span aria-hidden="true">›</span>
				</button>
			</div>
		</div>
	</section>

	<!-- Contact Section -->
	<section id="contact" class="bb-section bb-about">
		<div class="bb-section__container">
			<div class="bb-section__header">
				<h2 class="bb-section__title gradient-text"><?php echo esc_html__( 'Start Your Journey', 'bunbukan' ); ?></h2>
				<div class="bb-section__divider"></div>
				<p class="bb-section__subtitle">
					<?php echo esc_html__( 'New members are always welcome. Come observe a class or join us for a free trial session.', 'bunbukan' ); ?>
				</p>
			</div>

			<div class="bb-contact__wrapper">
				<!-- Training Schedule -->
				<div class="bb-about__card bb-contact__schedule bb-scroll-reveal">
					<h3 class="bb-about__card-title"><?php echo esc_html__( 'Training Schedule', 'bunbukan' ); ?></h3>
					<div class="bb-dojo__schedule">
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day">Wednesday</span>
								<span class="bb-dojo__time">18:30–20:00</span>
							</div>
							<span class="bb-dojo__discipline">Karate</span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day">Wednesday</span>
								<span class="bb-dojo__time">20:00–21:30</span>
							</div>
							<span class="bb-dojo__discipline bb-dojo__discipline--kobudo">Ryūkyū Kobudō</span>
						</div>
						<div class="bb-dojo__schedule-row">
							<div class="bb-dojo__schedule-day">
								<span class="bb-dojo__day">Friday</span>
								<span class="bb-dojo__time">19:00–20:30</span>
							</div>
							<span class="bb-dojo__discipline">Karate</span>
						</div>
					</div>
					<p class="bb-contact__note"><?php echo esc_html__( 'Open to students aged 14 and up. All levels welcome.', 'bunbukan' ); ?></p>
				</div>
				
				<!-- Contact CTA -->
				<div class="bb-about__card bb-contact__cta bb-scroll-reveal bb-scroll-reveal--right" data-delay="200">
					<h3 class="bb-about__card-title"><?php echo esc_html__( 'Get in Touch', 'bunbukan' ); ?></h3>
					<p class="bb-about__card-text">
						<?php echo esc_html__( 'Questions about training or want to arrange a visit? We would love to hear from you.', 'bunbukan' ); ?>
					</p>
					<div class="bb-contact__actions">
						<a href="mailto:info@bunbukan.eu" class="bb-btn bb-btn--primary"><?php echo esc_html__( 'Send Email', 'bunbukan' ); ?></a>
						<a href="https://www.google.com/maps/search/?api=1&query=Rue+des+Chalets+30,+1030+Schaerbeek" target="_blank" rel="noopener noreferrer" class="bb-btn bb-btn--outline"><?php echo esc_html__( 'View Map', 'bunbukan' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();

