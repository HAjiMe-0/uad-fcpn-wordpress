<?php
/**
 * Pie del sitio.
 *
 * @package UAD_FCPN
 */
$email = get_theme_mod( 'uad_email', 'uad@fcpn.edu.bo' );
$phone = get_theme_mod( 'uad_phone', '+591 (2) 261-0000' );
?>
<footer class="site-footer">
	<div class="container footer-grid">
		<div class="footer-about">
			<div class="footer-mark" aria-hidden="true">UAD</div>
			<h2><?php esc_html_e( 'Unidad de Administración Desconcentrada FCPN', 'uad-fcpn' ); ?></h2>
			<p><?php esc_html_e( 'Portal de información administrativa y documentación institucional de la Facultad de Ciencias Puras y Naturales.', 'uad-fcpn' ); ?></p>
		</div>
		<div>
			<h3><?php esc_html_e( 'Navegación', 'uad-fcpn' ); ?></h3>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#inicio' ) ); ?>"><?php esc_html_e( 'Inicio', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#institucion' ) ); ?>"><?php esc_html_e( 'Institución', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#documentos' ) ); ?>"><?php esc_html_e( 'Documentos', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contacto' ) ); ?>"><?php esc_html_e( 'Contacto', 'uad-fcpn' ); ?></a></li>
			</ul>
		</div>
		<div>
			<h3><?php esc_html_e( 'Documentación', 'uad-fcpn' ); ?></h3>
			<ul>
				<li><a href="<?php echo esc_url( add_query_arg( 'categoria_documento', 'instructivos', home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Instructivos', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( add_query_arg( 'categoria_documento', 'circulares', home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Circulares', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( add_query_arg( 'categoria_documento', 'reglamentos', home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Reglamentos', 'uad-fcpn' ); ?></a></li>
				<li><a href="<?php echo esc_url( add_query_arg( 'categoria_documento', 'normativas', home_url( '/' ) ) . '#documentos' ); ?>"><?php esc_html_e( 'Normativas', 'uad-fcpn' ); ?></a></li>
			</ul>
		</div>
		<div>
			<h3><?php esc_html_e( 'Contacto', 'uad-fcpn' ); ?></h3>
			<ul>
				<li><?php echo esc_html( get_theme_mod( 'uad_address', 'Facultad de Ciencias Puras y Naturales, La Paz, Bolivia' ) ); ?></li>
				<li><a href="mailto:<?php echo esc_attr( sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a></li>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Unidad de Administración Desconcentrada FCPN', 'uad-fcpn' ); ?></span>
		<span><?php esc_html_e( 'Universidad Mayor de San Andrés · UMSA', 'uad-fcpn' ); ?></span>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
