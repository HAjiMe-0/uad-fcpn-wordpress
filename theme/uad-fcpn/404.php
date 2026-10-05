<?php
/**
 * Pagina no encontrada.
 *
 * @package UAD_FCPN
 */
get_header();
?>
<main id="contenido" class="page-shell">
	<div class="container">
		<div class="content-card">
			<p class="eyebrow">404</p>
			<h1><?php esc_html_e( 'Pagina no encontrada', 'uad-fcpn' ); ?></h1>
			<p><?php esc_html_e( 'El contenido que buscas no existe o fue movido.', 'uad-fcpn' ); ?></p>
			<a class="button button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Volver al inicio', 'uad-fcpn' ); ?></a>
		</div>
	</div>
</main>
<?php get_footer(); ?>

