<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ars
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'ars' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'ars' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'ars' ), 'ars', '<a href="http://underscores.me/" rel="designer">Underscores.me</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<!--build:js /src/js/main.min.js -->
<script src="<?php bloginfo('stylesheet_directory'); ?>/src/js/init.js"></script>
<!-- endbuild -->


<?php wp_footer(); ?>

</body>
</html>
