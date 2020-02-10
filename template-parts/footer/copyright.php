<?php
/**
 * Template part for displaying the footer copyright
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="site-copyright">
	<?php
	/* translators: %1$s Copyright symbol, %2$s Company name, %3$s First year of company, %4$s Current year */
	printf( esc_html__( 'Copyright %1$s %2$s %3$s-%4$s', 'wp-rig' ), 'Wedepohl Engineering', '&copy;', '2002', esc_attr( get_the_time( 'Y' ) ) );
	?>
</div><!-- .site-copyright -->
