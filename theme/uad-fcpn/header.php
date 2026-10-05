<?php
/**
 * Encabezado del sitio.
 *
 * @package UAD_FCPN
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'uad-fcpn' ); ?></a>

<div class="topbar">
	<div class="container topbar__inner">
		<span><?php echo esc_html( get_theme_mod( 'uad_topbar_text', 'Universidad Mayor de San Andrés · Facultad de Ciencias Puras y Naturales' ) ); ?></span>
		<span class="topbar__hours"><?php echo esc_html( get_theme_mod( 'uad_hours', 'Lunes a viernes · 08:30 a 16:30' ) ); ?></span>
	</div>
</div>

<header class="site-header" data-site-header>
	<div class="container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Ir al inicio', 'uad-fcpn' ); ?>">
			<span class="brand__logo">
				<?php
				$logo_id = get_theme_mod( 'custom_logo' );
				if ( $logo_id ) {
					echo wp_get_attachment_image( $logo_id, 'thumbnail', false, array( 'alt' => get_bloginfo( 'name' ) ) );
				} else {
					echo '<span aria-hidden="true">UAD</span>';
				}
				?>
			</span>
			<span class="brand__text">
				<strong><?php esc_html_e( 'Unidad de Administración', 'uad-fcpn' ); ?><br><?php esc_html_e( 'Desconcentrada', 'uad-fcpn' ); ?></strong>
				<small><?php esc_html_e( 'FCPN · UMSA', 'uad-fcpn' ); ?></small>
			</span>
		</a>

		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" data-menu-toggle>
			<span class="menu-toggle__open" aria-hidden="true">☰</span>
			<span class="menu-toggle__close" aria-hidden="true">×</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Abrir menú', 'uad-fcpn' ); ?></span>
		</button>

		<nav class="site-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'uad-fcpn' ); ?>" data-site-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__list',
					'fallback_cb'    => 'uad_fcpn_default_menu',
					'depth'          => 2,
				)
			);
			?>
		</nav>
	</div>
</header>
