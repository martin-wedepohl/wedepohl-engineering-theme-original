<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

	<footer id="colophon" class="site-footer">
		<?php get_template_part( 'template-parts/footer/copyright' ); ?>
		<?php get_template_part( 'template-parts/footer/social' ); ?>
		<?php get_template_part( 'template-parts/footer/privacy' ); ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
