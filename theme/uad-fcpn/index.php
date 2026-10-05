<?php
/**
 * Plantilla general de publicaciones.
 *
 * @package UAD_FCPN
 */
get_header();
?>
<header class="page-header">
	<div class="container">
		<p class="eyebrow eyebrow--light"><?php esc_html_e( 'Actualidad institucional', 'uad-fcpn' ); ?></p>
		<h1><?php echo is_home() ? esc_html__( 'Comunicados y avisos', 'uad-fcpn' ) : esc_html( get_the_archive_title() ); ?></h1>
	</div>
</header>
<main id="contenido" class="page-shell">
	<div class="container post-list">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 35 ) ); ?></p>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<div class="content-card"><p><?php esc_html_e( 'No hay publicaciones disponibles.', 'uad-fcpn' ); ?></p></div>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>

