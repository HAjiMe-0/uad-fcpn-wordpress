<?php
/**
 * Ficha individual de un documento.
 *
 * @package UAD_FCPN
 */
get_header();
the_post();

$post_id    = get_the_ID();
$number     = get_post_meta( $post_id, '_uad_document_number', true );
$date       = get_post_meta( $post_id, '_uad_document_date', true );
$keywords   = get_post_meta( $post_id, '_uad_document_keywords', true );
$pdf_url    = uad_fcpn_get_pdf_url( $post_id );
$categories = get_the_terms( $post_id, 'uad_categoria_documento' );
$years      = get_the_terms( $post_id, 'uad_gestion_documento' );
$category   = ( $categories && ! is_wp_error( $categories ) ) ? $categories[0]->name : '';
$year       = ( $years && ! is_wp_error( $years ) ) ? $years[0]->name : '';
?>
<header class="page-header">
	<div class="container">
		<p class="eyebrow eyebrow--light"><?php echo esc_html( $category ?: __( 'Documento institucional', 'uad-fcpn' ) ); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php if ( $number ) : ?><p><?php echo esc_html( $number ); ?></p><?php endif; ?>
	</div>
</header>
<main id="contenido" class="page-shell">
	<div class="container document-detail">
		<article <?php post_class( 'content-card' ); ?>>
			<?php if ( has_excerpt() ) : ?><p><strong><?php echo esc_html( get_the_excerpt() ); ?></strong></p><?php endif; ?>
			<?php the_content(); ?>
			<p><a class="text-link" href="<?php echo esc_url( home_url( '/#documentos' ) ); ?>">← <?php esc_html_e( 'Volver a la biblioteca documental', 'uad-fcpn' ); ?></a></p>
		</article>
		<aside class="document-detail__meta" aria-label="<?php esc_attr_e( 'Datos del documento', 'uad-fcpn' ); ?>">
			<div class="document-card__icon" aria-hidden="true">PDF</div>
			<dl>
				<?php if ( $category ) : ?><dt><?php esc_html_e( 'Categoría', 'uad-fcpn' ); ?></dt><dd><?php echo esc_html( $category ); ?></dd><?php endif; ?>
				<?php if ( $number ) : ?><dt><?php esc_html_e( 'Número', 'uad-fcpn' ); ?></dt><dd><?php echo esc_html( $number ); ?></dd><?php endif; ?>
				<?php if ( $year ) : ?><dt><?php esc_html_e( 'Gestión', 'uad-fcpn' ); ?></dt><dd><?php echo esc_html( $year ); ?></dd><?php endif; ?>
				<?php if ( $date ) : ?><dt><?php esc_html_e( 'Fecha', 'uad-fcpn' ); ?></dt><dd><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $date ) ) ); ?></dd><?php endif; ?>
				<?php if ( $keywords ) : ?><dt><?php esc_html_e( 'Palabras clave', 'uad-fcpn' ); ?></dt><dd><?php echo esc_html( $keywords ); ?></dd><?php endif; ?>
			</dl>
			<?php if ( $pdf_url ) : ?>
				<a class="button button--primary" href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Ver PDF', 'uad-fcpn' ); ?></a>
				<a class="button button--small" href="<?php echo esc_url( $pdf_url ); ?>" download><?php esc_html_e( 'Descargar archivo', 'uad-fcpn' ); ?></a>
			<?php else : ?>
				<p><?php esc_html_e( 'El archivo PDF aun no fue adjuntado.', 'uad-fcpn' ); ?></p>
			<?php endif; ?>
		</aside>
	</div>
</main>
<?php get_footer(); ?>
