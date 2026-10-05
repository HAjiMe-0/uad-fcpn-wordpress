<?php
/**
 * Portada institucional y biblioteca documental.
 *
 * @package UAD_FCPN
 */

$filters    = uad_fcpn_get_document_filters();
$documents  = uad_fcpn_get_documents_query( $filters );
$categories = get_terms(
	array(
		'taxonomy'   => 'uad_categoria_documento',
		'hide_empty' => false,
		'orderby'    => 'name',
	)
);
$years      = get_terms(
	array(
		'taxonomy'   => 'uad_gestion_documento',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'DESC',
	)
);
$doc_count  = wp_count_posts( 'uad_documento' );
$total_docs = isset( $doc_count->publish ) ? (int) $doc_count->publish : 0;
$email      = get_theme_mod( 'uad_email', 'uad@fcpn.edu.bo' );
$phone      = get_theme_mod( 'uad_phone', '+591 (2) 261-0000' );

get_header();
?>
<main id="contenido">
	<section class="hero" id="inicio">
		<div class="container hero__grid">
			<div class="hero__copy">
				<p class="eyebrow eyebrow--light"><span></span><?php esc_html_e( 'Portal institucional FCPN', 'uad-fcpn' ); ?></p>
				<h1><?php esc_html_e( 'Unidad de Administración', 'uad-fcpn' ); ?> <em><?php esc_html_e( 'Desconcentrada', 'uad-fcpn' ); ?></em></h1>
				<p class="hero__description"><?php echo esc_html( get_theme_mod( 'uad_hero_description', 'Consulta instructivos, circulares, comunicados, reglamentos y normativa administrativa desde un solo lugar.' ) ); ?></p>
				<div class="hero__actions">
					<a class="button button--light" href="#documentos"><?php esc_html_e( 'Buscar documentos', 'uad-fcpn' ); ?> <span aria-hidden="true">→</span></a>
					<a class="button button--ghost" href="#institucion"><?php esc_html_e( 'Conocer la unidad', 'uad-fcpn' ); ?></a>
				</div>
			</div>
			<aside class="hero-card" aria-label="<?php esc_attr_e( 'Biblioteca digital', 'uad-fcpn' ); ?>">
				<div class="hero-card__icon" aria-hidden="true">⌕</div>
				<p class="hero-card__label"><?php esc_html_e( 'Acceso directo', 'uad-fcpn' ); ?></p>
				<h2><?php esc_html_e( 'Biblioteca documental', 'uad-fcpn' ); ?></h2>
				<p><?php esc_html_e( 'Encuentra documentos por título, número, asunto, categoría, gestión o palabra clave.', 'uad-fcpn' ); ?></p>
				<a href="#documentos"><?php esc_html_e( 'Iniciar una búsqueda', 'uad-fcpn' ); ?> <span aria-hidden="true">↗</span></a>
			</aside>
		</div>
	</section>

	<section class="stats" aria-label="<?php esc_attr_e( 'Datos del portal', 'uad-fcpn' ); ?>">
		<div class="container stats__grid">
			<div><strong><?php echo esc_html( number_format_i18n( $total_docs ) ); ?></strong><span><?php esc_html_e( 'Documentos publicados', 'uad-fcpn' ); ?></span></div>
			<div><strong><?php echo esc_html( is_wp_error( $categories ) ? 0 : count( $categories ) ); ?></strong><span><?php esc_html_e( 'Categorías documentales', 'uad-fcpn' ); ?></span></div>
			<div><strong><?php echo esc_html( wp_date( 'Y' ) ); ?></strong><span><?php esc_html_e( 'Gestión vigente', 'uad-fcpn' ); ?></span></div>
			<div><strong>24/7</strong><span><?php esc_html_e( 'Consulta digital', 'uad-fcpn' ); ?></span></div>
		</div>
	</section>

	<section class="section quick-access" aria-labelledby="quick-title">
		<div class="container">
			<header class="section-heading">
				<p class="eyebrow"><?php esc_html_e( 'Información administrativa', 'uad-fcpn' ); ?></p>
				<h2 id="quick-title"><?php esc_html_e( 'Accesos documentales', 'uad-fcpn' ); ?></h2>
				<p><?php esc_html_e( 'Ingresa directamente al tipo de documento que necesitas.', 'uad-fcpn' ); ?></p>
			</header>
			<div class="quick-grid">
				<?php
				$icons = array( '▤', '◎', '◉', '§', '▦' );
				if ( ! is_wp_error( $categories ) ) :
					foreach ( array_slice( $categories, 0, 5 ) as $index => $term ) :
						$url = add_query_arg( 'categoria_documento', $term->slug, home_url( '/' ) ) . '#documentos';
						?>
						<a class="quick-card" href="<?php echo esc_url( $url ); ?>">
							<span class="quick-card__icon" aria-hidden="true"><?php echo esc_html( $icons[ $index ] ?? '◇' ); ?></span>
							<span class="quick-card__content">
								<strong><?php echo esc_html( $term->name ); ?></strong>
								<small><?php echo esc_html( sprintf( _n( '%s documento', '%s documentos', $term->count, 'uad-fcpn' ), number_format_i18n( $term->count ) ) ); ?></small>
							</span>
							<span class="quick-card__arrow" aria-hidden="true">→</span>
						</a>
						<?php
					endforeach;
				endif;
				?>
				<a class="quick-card quick-card--accent" href="#documentos">
					<span class="quick-card__icon" aria-hidden="true">⌕</span>
					<span class="quick-card__content">
						<strong><?php esc_html_e( 'Búsqueda avanzada', 'uad-fcpn' ); ?></strong>
						<small><?php esc_html_e( 'Título, número o palabra clave', 'uad-fcpn' ); ?></small>
					</span>
					<span class="quick-card__arrow" aria-hidden="true">→</span>
				</a>
			</div>
		</div>
	</section>

	<section class="section documents-section" id="documentos" aria-labelledby="documents-title">
		<div class="container">
			<header class="section-heading section-heading--left documents-heading">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Biblioteca digital', 'uad-fcpn' ); ?></p>
					<h2 id="documents-title"><?php esc_html_e( 'Búsqueda de documentos', 'uad-fcpn' ); ?></h2>
					<p><?php esc_html_e( 'Consulta el repositorio administrativo actualizado de la unidad.', 'uad-fcpn' ); ?></p>
				</div>
				<span class="documents-heading__seal" aria-hidden="true">UAD</span>
			</header>

			<form class="documents-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>#documentos" data-document-search>
				<div class="search-field">
					<label class="screen-reader-text" for="document-query"><?php esc_html_e( 'Buscar documentos', 'uad-fcpn' ); ?></label>
					<span aria-hidden="true">⌕</span>
					<input id="document-query" name="buscar_documento" type="search" value="<?php echo esc_attr( $filters['query'] ); ?>" placeholder="<?php esc_attr_e( 'Buscar por título, número, asunto o palabra clave', 'uad-fcpn' ); ?>" autocomplete="off">
				</div>
				<div class="select-field">
					<label for="document-category"><?php esc_html_e( 'Categoría', 'uad-fcpn' ); ?></label>
					<select id="document-category" name="categoria_documento">
						<option value=""><?php esc_html_e( 'Todas', 'uad-fcpn' ); ?></option>
						<?php if ( ! is_wp_error( $categories ) ) : ?>
							<?php foreach ( $categories as $term ) : ?>
								<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $filters['category'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="select-field">
					<label for="document-year"><?php esc_html_e( 'Gestión', 'uad-fcpn' ); ?></label>
					<select id="document-year" name="gestion_documento">
						<option value=""><?php esc_html_e( 'Todas', 'uad-fcpn' ); ?></option>
						<?php if ( ! is_wp_error( $years ) ) : ?>
							<?php foreach ( $years as $term ) : ?>
								<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $filters['year'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<button class="button button--primary documents-search__submit" type="submit"><?php esc_html_e( 'Buscar', 'uad-fcpn' ); ?></button>
			</form>

			<div class="documents-summary">
				<strong data-results-count aria-live="polite"><?php echo esc_html( uad_fcpn_results_label( (int) $documents->found_posts ) ); ?></strong>
				<span data-search-status role="status" aria-live="polite"></span>
			</div>
			<div class="documents-results" data-document-results aria-live="polite">
				<?php echo uad_fcpn_get_results_html( $documents ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div data-document-pagination>
				<?php echo uad_fcpn_get_pagination_html( $filters['page'], (int) $documents->max_num_pages, $filters ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</section>

	<section class="section notices-section" id="comunicados" aria-labelledby="notices-title">
		<div class="container">
			<header class="section-heading">
				<p class="eyebrow"><?php esc_html_e( 'Actualidad', 'uad-fcpn' ); ?></p>
				<h2 id="notices-title"><?php esc_html_e( 'Comunicados y avisos', 'uad-fcpn' ); ?></h2>
				<p><?php esc_html_e( 'Información relevante de la Unidad de Administración Desconcentrada.', 'uad-fcpn' ); ?></p>
			</header>
			<div class="notices-grid">
				<?php
				$notices = new WP_Query(
					array(
						'post_type'           => 'post',
						'post_status'         => 'publish',
						'posts_per_page'      => 2,
						'ignore_sticky_posts' => true,
					)
				);
				if ( $notices->have_posts() ) :
					while ( $notices->have_posts() ) :
						$notices->the_post();
						?>
						<article class="notice-card">
						<div class="notice-card__top"><span><?php esc_html_e( 'Información', 'uad-fcpn' ); ?></span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></div>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
							<a class="text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leer comunicado', 'uad-fcpn' ); ?> →</a>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<article class="notice-card">
						<div class="notice-card__top"><span><?php esc_html_e( 'Importante', 'uad-fcpn' ); ?></span><time><?php echo esc_html( sprintf( __( 'Gestión %s', 'uad-fcpn' ), wp_date( 'Y' ) ) ); ?></time></div>
						<h3><?php esc_html_e( 'Repositorio documental disponible', 'uad-fcpn' ); ?></h3>
						<p><?php esc_html_e( 'Los nuevos instructivos, circulares y reglamentos se publicarán en la biblioteca digital de este portal.', 'uad-fcpn' ); ?></p>
						<a class="text-link" href="#documentos"><?php esc_html_e( 'Ir a documentos', 'uad-fcpn' ); ?> →</a>
					</article>
					<article class="notice-card">
						<div class="notice-card__top"><span><?php esc_html_e( 'Atención', 'uad-fcpn' ); ?></span><time><?php echo esc_html( sprintf( __( 'Gestión %s', 'uad-fcpn' ), wp_date( 'Y' ) ) ); ?></time></div>
						<h3><?php esc_html_e( 'Recepción y seguimiento de documentación', 'uad-fcpn' ); ?></h3>
						<p><?php esc_html_e( 'Para consultas sobre presentacion, recepcion y seguimiento de tramites, comunicate con la unidad.', 'uad-fcpn' ); ?></p>
						<a class="text-link" href="#contacto"><?php esc_html_e( 'Ver datos de contacto', 'uad-fcpn' ); ?> →</a>
					</article>
					<?php
				endif;
				?>
			</div>
		</div>
	</section>

	<section class="section institution" id="institucion" aria-labelledby="institution-title">
		<div class="container institution__grid">
			<div>
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Nuestra institución', 'uad-fcpn' ); ?></p>
				<h2 id="institution-title"><?php esc_html_e( 'Facultad de Ciencias Puras y Naturales', 'uad-fcpn' ); ?></h2>
				<p class="institution__lead"><?php esc_html_e( 'Universidad Mayor de San Andrés', 'uad-fcpn' ); ?></p>
				<p><?php esc_html_e( 'La Unidad de Administración Desconcentrada apoya la gestión institucional y facilita el acceso transparente y oportuno a la documentación administrativa de la Facultad.', 'uad-fcpn' ); ?></p>
				<div class="institution__features">
					<span>✓ <?php esc_html_e( 'Gestión administrativa', 'uad-fcpn' ); ?></span>
					<span>✓ <?php esc_html_e( 'Transparencia documental', 'uad-fcpn' ); ?></span>
					<span>✓ <?php esc_html_e( 'Información institucional', 'uad-fcpn' ); ?></span>
					<span>✓ <?php esc_html_e( 'Atención a usuarios', 'uad-fcpn' ); ?></span>
				</div>
			</div>
			<div class="institution__visual" aria-label="<?php esc_attr_e( 'Facultad de Ciencias Puras y Naturales', 'uad-fcpn' ); ?>">
				<span class="institution__watermark" aria-hidden="true">UMSA</span>
				<strong>FCPN</strong>
				<span><?php esc_html_e( 'Ciencia, conocimiento y servicio', 'uad-fcpn' ); ?></span>
			</div>
		</div>
	</section>

	<section class="section contact-section" id="contacto" aria-labelledby="contact-title">
		<div class="container">
			<header class="section-heading">
				<p class="eyebrow"><?php esc_html_e( 'Atención', 'uad-fcpn' ); ?></p>
				<h2 id="contact-title"><?php esc_html_e( 'Contactanos', 'uad-fcpn' ); ?></h2>
				<p><?php esc_html_e( 'Para consultas relacionadas con documentación y procesos administrativos.', 'uad-fcpn' ); ?></p>
			</header>
			<div class="contact-grid">
				<article class="contact-card"><span aria-hidden="true">⌖</span><h3><?php esc_html_e( 'Dirección', 'uad-fcpn' ); ?></h3><p><?php echo esc_html( get_theme_mod( 'uad_address', 'Facultad de Ciencias Puras y Naturales, La Paz, Bolivia' ) ); ?></p></article>
				<article class="contact-card"><span aria-hidden="true">@</span><h3><?php esc_html_e( 'Correo institucional', 'uad-fcpn' ); ?></h3><p><a href="mailto:<?php echo esc_attr( sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a></p></article>
				<article class="contact-card"><span aria-hidden="true">◔</span><h3><?php esc_html_e( 'Teléfono y horario', 'uad-fcpn' ); ?></h3><p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><br><?php echo esc_html( get_theme_mod( 'uad_hours', 'Lunes a viernes · 08:30 a 16:30' ) ); ?></p></article>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
