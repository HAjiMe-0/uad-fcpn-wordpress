<?php
/**
 * Funciones del tema UAD FCPN.
 *
 * @package UAD_FCPN
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UAD_FCPN_VERSION', '1.0.0' );

/**
 * Configuracion basica del tema.
 */
function uad_fcpn_setup() {
	load_theme_textdomain( 'uad-fcpn', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'uad-fcpn' ),
		)
	);
}
add_action( 'after_setup_theme', 'uad_fcpn_setup' );

/**
 * Recursos publicos.
 */
function uad_fcpn_enqueue_assets() {
	wp_enqueue_style(
		'uad-fcpn-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'uad-fcpn-style', get_stylesheet_uri(), array(), UAD_FCPN_VERSION );
	wp_enqueue_script(
		'uad-fcpn-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		UAD_FCPN_VERSION,
		true
	);
	wp_localize_script(
		'uad-fcpn-main',
		'uadDocuments',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'uad_document_search' ),
			'labels'  => array(
				'loading' => __( 'Buscando documentos...', 'uad-fcpn' ),
				'error'   => __( 'No fue posible realizar la búsqueda. Intenta nuevamente.', 'uad-fcpn' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'uad_fcpn_enqueue_assets' );

/**
 * Tipo de contenido y clasificaciones para la biblioteca documental.
 */
function uad_fcpn_register_documents() {
	register_post_type(
		'uad_documento',
		array(
			'labels'       => array(
				'name'                  => __( 'Documentos', 'uad-fcpn' ),
				'singular_name'         => __( 'Documento', 'uad-fcpn' ),
				'menu_name'             => __( 'Documentos', 'uad-fcpn' ),
				'add_new'               => __( 'Añadir documento', 'uad-fcpn' ),
				'add_new_item'          => __( 'Añadir documento', 'uad-fcpn' ),
				'edit_item'             => __( 'Editar documento', 'uad-fcpn' ),
				'new_item'              => __( 'Nuevo documento', 'uad-fcpn' ),
				'view_item'             => __( 'Ver documento', 'uad-fcpn' ),
				'search_items'          => __( 'Buscar documentos', 'uad-fcpn' ),
				'not_found'             => __( 'No se encontraron documentos.', 'uad-fcpn' ),
				'not_found_in_trash'    => __( 'No hay documentos en la papelera.', 'uad-fcpn' ),
				'all_items'             => __( 'Todos los documentos', 'uad-fcpn' ),
				'featured_image'        => __( 'Imagen del documento', 'uad-fcpn' ),
				'set_featured_image'    => __( 'Definir imagen', 'uad-fcpn' ),
				'remove_featured_image' => __( 'Quitar imagen', 'uad-fcpn' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'documentos' ),
			'menu_icon'    => 'dashicons-media-document',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);

	register_taxonomy(
		'uad_categoria_documento',
		array( 'uad_documento' ),
		array(
			'labels'            => array(
				'name'          => __( 'Categorías', 'uad-fcpn' ),
				'singular_name' => __( 'Categoría', 'uad-fcpn' ),
				'menu_name'     => __( 'Categorías', 'uad-fcpn' ),
				'add_new_item'  => __( 'Añadir categoría', 'uad-fcpn' ),
				'edit_item'     => __( 'Editar categoría', 'uad-fcpn' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tipo-documento' ),
		)
	);

	register_taxonomy(
		'uad_gestion_documento',
		array( 'uad_documento' ),
		array(
			'labels'            => array(
				'name'          => __( 'Gestiones', 'uad-fcpn' ),
				'singular_name' => __( 'Gestión', 'uad-fcpn' ),
				'menu_name'     => __( 'Gestiones', 'uad-fcpn' ),
				'add_new_item'  => __( 'Añadir gestión', 'uad-fcpn' ),
				'edit_item'     => __( 'Editar gestión', 'uad-fcpn' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'gestion-documental' ),
		)
	);
}
add_action( 'init', 'uad_fcpn_register_documents' );

/**
 * Crea las categorias iniciales al activar el tema.
 */
function uad_fcpn_seed_terms() {
	uad_fcpn_register_documents();

	$categories = array(
		'Instructivos',
		'Circulares',
		'Comunicados',
		'Reglamentos',
		'Normativas',
		'Otros documentos',
	);

	foreach ( $categories as $category ) {
		if ( ! term_exists( $category, 'uad_categoria_documento' ) ) {
			wp_insert_term( $category, 'uad_categoria_documento' );
		}
	}

	$current_year = wp_date( 'Y' );
	if ( ! term_exists( $current_year, 'uad_gestion_documento' ) ) {
		wp_insert_term( $current_year, 'uad_gestion_documento' );
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'uad_fcpn_seed_terms' );

/**
 * Campos propios de cada documento.
 */
function uad_fcpn_add_document_meta_box() {
	add_meta_box(
		'uad_document_data',
		__( 'Datos del documento', 'uad-fcpn' ),
		'uad_fcpn_document_meta_box_html',
		'uad_documento',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'uad_fcpn_add_document_meta_box' );

/**
 * Formulario del metabox.
 *
 * @param WP_Post $post Publicacion actual.
 */
function uad_fcpn_document_meta_box_html( $post ) {
	wp_nonce_field( 'uad_save_document', 'uad_document_nonce' );
	$number   = get_post_meta( $post->ID, '_uad_document_number', true );
	$date     = get_post_meta( $post->ID, '_uad_document_date', true );
	$keywords = get_post_meta( $post->ID, '_uad_document_keywords', true );
	$pdf_id   = absint( get_post_meta( $post->ID, '_uad_document_pdf_id', true ) );
	$pdf_url  = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';
	?>
	<div class="uad-admin-grid">
		<p>
			<label for="uad_document_number"><strong><?php esc_html_e( 'Número o código', 'uad-fcpn' ); ?></strong></label><br>
			<input class="widefat" type="text" id="uad_document_number" name="uad_document_number" value="<?php echo esc_attr( $number ); ?>" placeholder="Ej.: UAD N.o 012/2026">
		</p>
		<p>
			<label for="uad_document_date"><strong><?php esc_html_e( 'Fecha del documento', 'uad-fcpn' ); ?></strong></label><br>
			<input type="date" id="uad_document_date" name="uad_document_date" value="<?php echo esc_attr( $date ); ?>">
		</p>
		<p>
			<label for="uad_document_keywords"><strong><?php esc_html_e( 'Palabras clave', 'uad-fcpn' ); ?></strong></label><br>
			<input class="widefat" type="text" id="uad_document_keywords" name="uad_document_keywords" value="<?php echo esc_attr( $keywords ); ?>" placeholder="tramites, personal, vacaciones">
			<small><?php esc_html_e( 'Sepáralas con comas. También se incluirán en la búsqueda pública.', 'uad-fcpn' ); ?></small>
		</p>
		<div>
			<label><strong><?php esc_html_e( 'Archivo PDF', 'uad-fcpn' ); ?></strong></label>
			<input type="hidden" id="uad_document_pdf_id" name="uad_document_pdf_id" value="<?php echo esc_attr( $pdf_id ); ?>">
			<p>
				<button type="button" class="button button-secondary" id="uad_select_pdf"><?php esc_html_e( 'Seleccionar o subir PDF', 'uad-fcpn' ); ?></button>
				<button type="button" class="button-link-delete" id="uad_remove_pdf"<?php echo $pdf_id ? '' : ' hidden'; ?>><?php esc_html_e( 'Quitar archivo', 'uad-fcpn' ); ?></button>
			</p>
			<p id="uad_pdf_preview">
				<?php if ( $pdf_url ) : ?>
					<a href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( wp_basename( get_attached_file( $pdf_id ) ) ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	</div>
	<style>
		.uad-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:4px 24px}.uad-admin-grid>p:nth-child(3){grid-column:1/-1}@media(max-width:782px){.uad-admin-grid{grid-template-columns:1fr}}
	</style>
	<?php
}

/**
 * Guarda los campos propios.
 *
 * @param int $post_id ID del documento.
 */
function uad_fcpn_save_document_meta( $post_id ) {
	if ( ! isset( $_POST['uad_document_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uad_document_nonce'] ) ), 'uad_save_document' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'uad_document_number'   => '_uad_document_number',
		'uad_document_date'     => '_uad_document_date',
		'uad_document_keywords' => '_uad_document_keywords',
	);

	foreach ( $fields as $field => $meta_key ) {
		$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		if ( '' !== $value ) {
			update_post_meta( $post_id, $meta_key, $value );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	$pdf_id = isset( $_POST['uad_document_pdf_id'] ) ? absint( $_POST['uad_document_pdf_id'] ) : 0;
	if ( $pdf_id && 'application/pdf' === get_post_mime_type( $pdf_id ) ) {
		update_post_meta( $post_id, '_uad_document_pdf_id', $pdf_id );
	} else {
		delete_post_meta( $post_id, '_uad_document_pdf_id' );
	}
}
add_action( 'save_post_uad_documento', 'uad_fcpn_save_document_meta' );

/**
 * Selector de PDF dentro del editor.
 *
 * @param string $hook Pagina actual del administrador.
 */
function uad_fcpn_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'uad_documento' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'uad-fcpn-admin',
		get_template_directory_uri() . '/assets/js/admin.js',
		array( 'jquery' ),
		UAD_FCPN_VERSION,
		true
	);
	wp_localize_script(
		'uad-fcpn-admin',
		'uadAdmin',
		array(
			'title'  => __( 'Seleccionar documento PDF', 'uad-fcpn' ),
			'button' => __( 'Usar este PDF', 'uad-fcpn' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'uad_fcpn_admin_assets' );

/**
 * Columnas utiles en el listado administrativo.
 *
 * @param array $columns Columnas existentes.
 * @return array
 */
function uad_fcpn_document_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['uad_number'] = __( 'Número', 'uad-fcpn' );
			$new['uad_pdf']    = __( 'PDF', 'uad-fcpn' );
		}
	}
	return $new;
}
add_filter( 'manage_uad_documento_posts_columns', 'uad_fcpn_document_columns' );

/**
 * Contenido de columnas administrativas.
 *
 * @param string $column  Nombre de columna.
 * @param int    $post_id ID del documento.
 */
function uad_fcpn_document_column_content( $column, $post_id ) {
	if ( 'uad_number' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_uad_document_number', true ) ?: '—' );
	}
	if ( 'uad_pdf' === $column ) {
		$pdf_id = absint( get_post_meta( $post_id, '_uad_document_pdf_id', true ) );
		if ( $pdf_id ) {
			echo '<a href="' . esc_url( wp_get_attachment_url( $pdf_id ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Abrir PDF', 'uad-fcpn' ) . '</a>';
		} else {
			echo '—';
		}
	}
}
add_action( 'manage_uad_documento_posts_custom_column', 'uad_fcpn_document_column_content', 10, 2 );

/**
 * Amplia la busqueda documental a contenido, metadatos y taxonomias.
 * Solo se aplica a consultas creadas por este tema.
 *
 * @param string   $search Clausula de busqueda.
 * @param WP_Query $query  Consulta actual.
 * @return string
 */
function uad_fcpn_document_search_sql( $search, $query ) {
	$needle = $query->get( 'uad_document_search' );
	if ( ! $needle ) {
		return $search;
	}

	global $wpdb;
	$like = '%' . $wpdb->esc_like( $needle ) . '%';

	return $wpdb->prepare(
		" AND (
			{$wpdb->posts}.post_title LIKE %s
			OR {$wpdb->posts}.post_excerpt LIKE %s
			OR {$wpdb->posts}.post_content LIKE %s
			OR EXISTS (
				SELECT 1 FROM {$wpdb->postmeta} uad_pm
				WHERE uad_pm.post_id = {$wpdb->posts}.ID
				AND uad_pm.meta_key IN ('_uad_document_number','_uad_document_keywords','_uad_document_date')
				AND uad_pm.meta_value LIKE %s
			)
			OR EXISTS (
				SELECT 1
				FROM {$wpdb->term_relationships} uad_tr
				INNER JOIN {$wpdb->term_taxonomy} uad_tt ON uad_tt.term_taxonomy_id = uad_tr.term_taxonomy_id
				INNER JOIN {$wpdb->terms} uad_t ON uad_t.term_id = uad_tt.term_id
				WHERE uad_tr.object_id = {$wpdb->posts}.ID
				AND uad_tt.taxonomy IN ('uad_categoria_documento','uad_gestion_documento')
				AND uad_t.name LIKE %s
			)
		)",
		$like,
		$like,
		$like,
		$like,
		$like
	);
}

/**
 * Construye la consulta de la biblioteca.
 *
 * @param array $filters Filtros limpios.
 * @return WP_Query
 */
function uad_fcpn_get_documents_query( $filters = array() ) {
	$defaults = array(
		'query'    => '',
		'category' => '',
		'year'     => '',
		'page'     => 1,
	);
	$filters  = wp_parse_args( $filters, $defaults );
	$tax_query = array();

	if ( $filters['category'] ) {
		$tax_query[] = array(
			'taxonomy' => 'uad_categoria_documento',
			'field'    => 'slug',
			'terms'    => sanitize_title( $filters['category'] ),
		);
	}
	if ( $filters['year'] ) {
		$tax_query[] = array(
			'taxonomy' => 'uad_gestion_documento',
			'field'    => 'slug',
			'terms'    => sanitize_title( $filters['year'] ),
		);
	}

	$args = array(
		'post_type'           => 'uad_documento',
		'post_status'         => 'publish',
		'posts_per_page'      => 12,
		'paged'               => max( 1, absint( $filters['page'] ) ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'uad_document_search' => sanitize_text_field( $filters['query'] ),
	);

	if ( $tax_query ) {
		$args['tax_query'] = $tax_query;
	}

	add_filter( 'posts_search', 'uad_fcpn_document_search_sql', 10, 2 );
	$documents = new WP_Query( $args );
	remove_filter( 'posts_search', 'uad_fcpn_document_search_sql', 10 );

	return $documents;
}

/**
 * Devuelve los filtros presentes en la URL.
 *
 * @return array
 */
function uad_fcpn_get_document_filters() {
	return array(
		'query'    => isset( $_GET['buscar_documento'] ) ? sanitize_text_field( wp_unslash( $_GET['buscar_documento'] ) ) : '',
		'category' => isset( $_GET['categoria_documento'] ) ? sanitize_title( wp_unslash( $_GET['categoria_documento'] ) ) : '',
		'year'     => isset( $_GET['gestion_documento'] ) ? sanitize_title( wp_unslash( $_GET['gestion_documento'] ) ) : '',
		'page'     => isset( $_GET['pagina_documentos'] ) ? max( 1, absint( $_GET['pagina_documentos'] ) ) : 1,
	);
}

/**
 * Etiqueta con la cantidad de resultados.
 *
 * @param int $count Total.
 * @return string
 */
function uad_fcpn_results_label( $count ) {
	return 1 === (int) $count
		? __( '1 documento encontrado', 'uad-fcpn' )
		: sprintf( __( '%s documentos encontrados', 'uad-fcpn' ), number_format_i18n( $count ) );
}

/**
 * Obtiene la URL del PDF asociado.
 *
 * @param int $post_id ID del documento.
 * @return string
 */
function uad_fcpn_get_pdf_url( $post_id ) {
	$pdf_id = absint( get_post_meta( $post_id, '_uad_document_pdf_id', true ) );
	return $pdf_id ? (string) wp_get_attachment_url( $pdf_id ) : '';
}

/**
 * Tarjeta reutilizable de un documento.
 *
 * @param int $post_id ID del documento.
 */
function uad_fcpn_render_document_card( $post_id ) {
	$title      = get_the_title( $post_id );
	$number     = get_post_meta( $post_id, '_uad_document_number', true );
	$date       = get_post_meta( $post_id, '_uad_document_date', true );
	$pdf_url    = uad_fcpn_get_pdf_url( $post_id );
	$categories = get_the_terms( $post_id, 'uad_categoria_documento' );
	$years      = get_the_terms( $post_id, 'uad_gestion_documento' );
	$category   = ( $categories && ! is_wp_error( $categories ) ) ? $categories[0]->name : __( 'Documento', 'uad-fcpn' );
	$year       = ( $years && ! is_wp_error( $years ) ) ? $years[0]->name : '';
	$excerpt    = get_the_excerpt( $post_id );
	$meta       = array_filter( array( $category, $number, $year ? sprintf( __( 'Gestión %s', 'uad-fcpn' ), $year ) : '' ) );
	?>
	<article class="document-card">
		<div class="document-card__icon" aria-hidden="true">PDF</div>
		<div class="document-card__body">
			<p class="document-card__meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p>
			<h3><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( $title ); ?></a></h3>
			<?php if ( $excerpt ) : ?>
				<p class="document-card__excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 18 ) ); ?></p>
			<?php endif; ?>
			<?php if ( $date ) : ?>
				<time datetime="<?php echo esc_attr( $date ); ?>"><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $date ) ) ); ?></time>
			<?php endif; ?>
		</div>
		<div class="document-card__actions">
			<a class="text-link" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php esc_html_e( 'Ver ficha', 'uad-fcpn' ); ?></a>
			<?php if ( $pdf_url ) : ?>
				<a class="button button--small" href="<?php echo esc_url( $pdf_url ); ?>" download><?php esc_html_e( 'Descargar', 'uad-fcpn' ); ?></a>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * HTML de resultados o estado vacio.
 *
 * @param WP_Query $documents Consulta de documentos.
 * @return string
 */
function uad_fcpn_get_results_html( $documents ) {
	ob_start();
	if ( $documents->have_posts() ) {
		while ( $documents->have_posts() ) {
			$documents->the_post();
			uad_fcpn_render_document_card( get_the_ID() );
		}
		wp_reset_postdata();
	} else {
		?>
		<div class="documents-empty">
			<span aria-hidden="true">⌕</span>
			<h3><?php esc_html_e( 'No encontramos documentos', 'uad-fcpn' ); ?></h3>
			<p><?php esc_html_e( 'Prueba con otra palabra, categoría o gestión.', 'uad-fcpn' ); ?></p>
		</div>
		<?php
	}
	return (string) ob_get_clean();
}

/**
 * Paginacion accesible y compatible con JavaScript.
 *
 * @param int   $current Pagina actual.
 * @param int   $total   Total de paginas.
 * @param array $filters Filtros activos.
 * @return string
 */
function uad_fcpn_get_pagination_html( $current, $total, $filters = array() ) {
	if ( $total < 2 ) {
		return '';
	}

	$current = max( 1, (int) $current );
	$total   = max( 1, (int) $total );
	$start   = max( 1, $current - 2 );
	$end     = min( $total, $current + 2 );
	$base    = array(
		'buscar_documento'   => $filters['query'] ?? '',
		'categoria_documento' => $filters['category'] ?? '',
		'gestion_documento'  => $filters['year'] ?? '',
	);

	ob_start();
	?>
	<nav class="documents-pagination" aria-label="<?php esc_attr_e( 'Páginas de documentos', 'uad-fcpn' ); ?>">
		<?php if ( $current > 1 ) : ?>
			<a data-page="<?php echo esc_attr( $current - 1 ); ?>" href="<?php echo esc_url( add_query_arg( array_merge( $base, array( 'pagina_documentos' => $current - 1 ) ), home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Anterior', 'uad-fcpn' ); ?></a>
		<?php endif; ?>
		<?php for ( $page = $start; $page <= $end; $page++ ) : ?>
			<a data-page="<?php echo esc_attr( $page ); ?>" <?php echo $page === $current ? 'aria-current="page"' : ''; ?> href="<?php echo esc_url( add_query_arg( array_merge( $base, array( 'pagina_documentos' => $page ) ), home_url( '/' ) ) . '#documentos' ); ?>"><?php echo esc_html( $page ); ?></a>
		<?php endfor; ?>
		<?php if ( $current < $total ) : ?>
			<a data-page="<?php echo esc_attr( $current + 1 ); ?>" href="<?php echo esc_url( add_query_arg( array_merge( $base, array( 'pagina_documentos' => $current + 1 ) ), home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Siguiente', 'uad-fcpn' ); ?></a>
		<?php endif; ?>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Busqueda AJAX publica y autenticada.
 */
function uad_fcpn_ajax_search_documents() {
	check_ajax_referer( 'uad_document_search', 'nonce' );
	$filters = array(
		'query'    => isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '',
		'category' => isset( $_POST['category'] ) ? sanitize_title( wp_unslash( $_POST['category'] ) ) : '',
		'year'     => isset( $_POST['year'] ) ? sanitize_title( wp_unslash( $_POST['year'] ) ) : '',
		'page'     => isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1,
	);
	$documents = uad_fcpn_get_documents_query( $filters );

	wp_send_json_success(
		array(
			'html'       => uad_fcpn_get_results_html( $documents ),
			'pagination' => uad_fcpn_get_pagination_html( $filters['page'], (int) $documents->max_num_pages, $filters ),
			'count'      => (int) $documents->found_posts,
			'label'      => uad_fcpn_results_label( (int) $documents->found_posts ),
		)
	);
}
add_action( 'wp_ajax_uad_search_documents', 'uad_fcpn_ajax_search_documents' );
add_action( 'wp_ajax_nopriv_uad_search_documents', 'uad_fcpn_ajax_search_documents' );

/**
 * Valores editables desde Apariencia > Personalizar.
 *
 * @param WP_Customize_Manager $wp_customize Gestor de personalizacion.
 */
function uad_fcpn_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'uad_institution',
		array(
			'title'    => __( 'Datos institucionales UAD', 'uad-fcpn' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'uad_topbar_text' => array(
			'label'   => __( 'Texto de la barra superior', 'uad-fcpn' ),
			'default' => 'Universidad Mayor de San Andrés · Facultad de Ciencias Puras y Naturales',
		),
		'uad_email' => array(
			'label'    => __( 'Correo institucional', 'uad-fcpn' ),
			'default'  => 'uad@fcpn.edu.bo',
			'sanitize' => 'sanitize_email',
		),
		'uad_phone' => array(
			'label'   => __( 'Teléfono', 'uad-fcpn' ),
			'default' => '+591 (2) 261-0000',
		),
		'uad_address' => array(
			'label'   => __( 'Dirección', 'uad-fcpn' ),
			'default' => 'Facultad de Ciencias Puras y Naturales, La Paz, Bolivia',
		),
		'uad_hours' => array(
			'label'   => __( 'Horario', 'uad-fcpn' ),
			'default' => 'Lunes a viernes · 08:30 a 16:30',
		),
		'uad_hero_description' => array(
			'label'   => __( 'Descripción de portada', 'uad-fcpn' ),
			'default' => 'Consulta instructivos, circulares, comunicados, reglamentos y normativa administrativa desde un solo lugar.',
		),
	);

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => $field['sanitize'] ?? 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field['label'],
				'section' => 'uad_institution',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'uad_fcpn_customize_register' );

/**
 * Menu de respaldo para una instalacion nueva.
 */
function uad_fcpn_default_menu() {
	$items = array(
		'inicio'      => __( 'Inicio', 'uad-fcpn' ),
		'institucion' => __( 'Institución', 'uad-fcpn' ),
		'documentos'  => __( 'Documentos', 'uad-fcpn' ),
		'comunicados' => __( 'Comunicados', 'uad-fcpn' ),
		'contacto'    => __( 'Contacto', 'uad-fcpn' ),
	);
	echo '<ul class="site-nav__list">';
	foreach ( $items as $anchor => $label ) {
		echo '<li><a href="' . esc_url( home_url( '/#' . $anchor ) ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
