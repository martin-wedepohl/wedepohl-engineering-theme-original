<?php
/**
 * WP_Rig\WP_Rig\Sticky_Header\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Sticky_Header;

use WP_Rig\WP_Rig\Component_Interface;
use WP_Rig\WP_Rig\Templating_Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use WP_Customize_Manager;
use function add_action;
use function is_admin;
use function boolval;
use function get_theme_mod;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;

/**
 * Class for managing sticky header.
 *
 * Exposes template tags:
 * * `wp_rig()->is_sticky_header()`
 * * `wp_rig()->scroll_mobile_sticky_header()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'sticky_header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register_sticky_header' ) );

		$shrink_header = boolval( get_theme_mod( 'shrink_header', 'true' ) );
		if ( $shrink_header ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_assets' ) );
		}
	}

	/**
	 * Enqueues and defer sticky JavaScript.
	 */
	public function action_enqueue_assets() {
		wp_enqueue_script(
			'wp-rig-sticky-header',
			get_theme_file_uri( '/assets/js/sticky.min.js' ),
			array(),
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/sticky.min.js' ) ),
			false
		);
		wp_script_add_data( 'wp-rig-sticky-header', 'defer', true );
		wp_script_add_data( 'wp-rig-sticky-header', 'precache', true );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `wp_rig()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return array(
			'is_sticky_header'            => array( $this, 'is_sticky_header' ),
			'scroll_mobile_sticky_header' => array( $this, 'scroll_mobile_sticky_header' ),
		);
	}

	/**
	 * Return if we want the header to be sticky on both the mobile and desktop
	 *
	 * @return bool If the header should be sticky or not
	 */
	public function is_sticky_header() : bool {
		$sticky_header = get_theme_mod( 'sticky_header' );
		$scroll_mobile = boolval( get_theme_mod( 'scroll_mobile', 'true' ) );
		$want_sticky   = 'sticky-header' === $sticky_header && ! $scroll_mobile;

		return $want_sticky;
	}

	/**
	 * Return if we want a scrollable mobile sticky header, but sticky desktop header
	 *
	 * @return bool If the mobile header should scroll or not
	 */
	public function scroll_mobile_sticky_header() : bool {
		$sticky_header = get_theme_mod( 'sticky_header' );
		$scroll_mobile = boolval( get_theme_mod( 'scroll_mobile', 'true' ) );
		$want_sticky   = 'sticky-header' === $sticky_header && $scroll_mobile;

		return $want_sticky;
	}

	/**
	 * Adds a setting and control for sticky header the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register_sticky_header( WP_Customize_Manager $wp_customize ) {
		$sticky_header_choices = array(
			'no-sticky-header' => __( 'Sticky Header off (default)', 'wp-rig' ),
			'sticky-header'    => __( 'Sticky Header on', 'wp-rig' ),
		);

		$wp_customize->add_section(
			'sticky_header_options',
			array(
				'title'    => __( 'Sticky Header Options', 'wp-rig' ),
				'panel'    => 'theme_panel_id',
			)
		);

		$wp_customize->add_setting(
			'sticky_header',
			array(
				'default'           => 'no-sticky-header',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) use ( $sticky_header_choices ) : string {
					foreach ( $sticky_header_choices as $key => $choice ) {
						if ( $input === $key ) {
							return $input;
						}
					}

					return '';
				},
			)
		);

		$wp_customize->add_control(
			'sticky_header',
			array(
				'label'       => __( 'Sticky Header', 'wp-rig' ),
				'section'     => 'sticky_header_options',
				'type'        => 'radio',
				'description' => __( 'Sticky Header stops the header (Logo/Site Branding and menu) from scrolling off the page.', 'wp-rig' ),
				'choices'     => $sticky_header_choices,
			)
		);

		$wp_customize->add_setting(
			'scroll_mobile',
			array(
				'default'           => 'checked',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) : bool {
					if ( isset( $input ) && true === $input ) {
						return true;
					}

					return false;
				},
			)
		);

		$wp_customize->add_control(
			'scroll_mobile',
			array(
				'label'       => __( 'Scroll Mobile Header', 'wp-rig' ),
				'section'     => 'sticky_header_options',
				'type'        => 'checkbox',
				'description' => __( 'Overrides Sticky Header for mobile devices.', 'wp-rig' ),
			)
		);
		$wp_customize->add_setting(
			'shrink_header',
			array(
				'default'           => 'checked',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) : bool {
					if ( isset( $input ) && true === $input ) {
						return true;
					}

					return false;
				},
			)
		);

		$wp_customize->add_control(
			'shrink_header',
			array(
				'label'       => __( 'Shrink Header', 'wp-rig' ),
				'section'     => 'sticky_header_options',
				'type'        => 'checkbox',
				'description' => __( 'Shrinks header on scroll.', 'wp-rig' ),
			)
		);
	}

}
