<?php
/**
 * WP_Rig\WP_Rig\Static_Pages\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Static_Pages;

use WP_Rig\WP_Rig\Component_Interface;
use function update_option;
use function wp_insert_post;
use function get_page_by_title;

/**
 * Class for adding the Home and Blog static pages.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'static_pages';
	}

	/**
	 * Adds the Home and Blog pages if they don't exist
	 * and sets the static pages.
	 */
	public function initialize() {
		// Use a static front page.
		$home_page = 'Home';
		$blog_page = 'Blog';

		$home = get_page_by_title( $home_page );
		if ( null === $home ) {
			$new_post = array(
				'post_title'   => $home_page,
				'post_content' => 'Enter home page content',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
			$home     = wp_insert_post( $new_post );
		}
		update_option( 'page_on_front', $home->ID );
		update_option( 'show_on_front', 'page' );

		// Set the blog page.
		$blog = get_page_by_title( $blog_page );
		if ( null === $blog ) {
			$new_post = array(
				'post_title'   => $blog_page,
				'post_content' => 'Enter blog page content',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
			$blog     = wp_insert_post( $new_post );
		}
		update_option( 'page_for_posts', $blog->ID );
	}

}
