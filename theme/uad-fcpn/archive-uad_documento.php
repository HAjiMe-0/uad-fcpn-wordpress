<?php
/**
 * Archivo de documentos.
 *
 * @package UAD_FCPN
 */
get_header();
?>
<header class="page-header">
	<div class="container">
		<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Biblioteca digital', 'uad-fcpn' ); ?></p>
		<h1><?php esc_html_e( 'Documentos institucionales', 'uad-fcpn' ); ?></h1>
		<p><?php esc_html_e( 'Repositorio de instructivos, circulares, comunicados, reglamentos y normativa.', 'uad-fcpn' ); ?></p>
	</div>
</header>
<main id="contenido" class="page-shell">
	<div class="container">
		<p><a class="button button--primary" href="<?php echo esc_url( home_url( '/#documentos' ) ); ?>"><?php esc_html_e( 'Usar búsqueda avanzada', 'uad-fcpn' ); ?></a></p>
		<div class="documents-results">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php uad_fcpn_render_document_card( get_the_ID() ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="documents-empty"><h3><?php esc_html_e( 'No hay documentos publicados', 'uad-fcpn' ); ?></h3></div>
			<?php endif; ?>
		</div>
		<?php the_posts_pagination(); ?>
	</div>
</main>
<?php get_footer(); ?>
