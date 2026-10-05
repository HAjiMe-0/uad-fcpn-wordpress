<?php
/**
 * Archivo de categoria o gestion documental.
 *
 * @package UAD_FCPN
 */
get_header();
?>
<header class="page-header">
	<div class="container">
		<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Biblioteca digital', 'uad-fcpn' ); ?></p>
		<h1><?php single_term_title(); ?></h1>
		<?php if ( term_description() ) : ?><div><?php echo wp_kses_post( term_description() ); ?></div><?php endif; ?>
	</div>
</header>
<main id="contenido" class="page-shell">
	<div class="container">
		<div class="documents-results">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php uad_fcpn_render_document_card( get_the_ID() ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="documents-empty"><h3><?php esc_html_e( 'No hay documentos en esta clasificacion', 'uad-fcpn' ); ?></h3></div>
			<?php endif; ?>
		</div>
		<?php the_posts_pagination(); ?>
	</div>
</main>
<?php get_footer(); ?>
