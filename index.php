<?php
/**
 * The main template file
 *
 * @package Bunbukan
 */

// Show custom front page for the site "home" in every language (e.g. /, /ja/, /fr/, /en/).
// When no static front page is set for a language (e.g. Japanese), WordPress shows the blog index; we override to show front-page.php.
if ( is_home() ) {
	locate_template( 'front-page.php', true );
	return;
}

get_header();
?>

<main id="primary" class="site-main">

	<?php
	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</header>
			<?php
		endif;

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();

