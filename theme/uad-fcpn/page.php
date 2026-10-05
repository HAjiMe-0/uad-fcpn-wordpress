<?php
/**
 * Pagina interior.
 *
 * @package UAD_FCPN
 */
get_header();
the_post();
?>
<header class="page-header">
	<div class="container">
		<p class="eyebrow eyebrow--light"><?php esc_html_e( 'FCPN · UMSA', 'uad-fcpn' ); ?></p>
		<h1><?php the_title(); ?></h1>
	</div>
</header>
<main id="contenido" class="page-shell">
	<div class="container">
		<article <?php post_class( 'content-card' ); ?>><?php the_content(); ?></article>
	</div>
</main>
<?php get_footer(); ?>

