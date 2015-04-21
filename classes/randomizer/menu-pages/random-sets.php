<?php
/**
 * User: vagenas
 * Date: 9/14/14
 * Time: 11:11 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link -*-
 */
namespace randomizer\menu_pages {


	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	/**
	 * Menu Page.
	 *
	 * @package randomizer\menu_pages
	 * @since 140914
	 * @assert ($GLOBALS[__NAMESPACE__])
	 */
	class random_sets extends menu_page {
		public $updates_options = true;

		/**
		 * Constructor.
		 *
		 * @param object|array $instance Required at all times.
		 *    A parent object instance, which contains the parent's ``$instance``,
		 *    or a new ``$instance`` array.
		 */
		public function __construct( $instance ) {
			parent::__construct( $instance );

			$this->heading_title           = $this->__( 'Random Sets' );
			$this->sub_heading_description = sprintf( $this->__( 'Configure random sets for %1$s' ), esc_html( $this->instance->plugin_name ) );
		}

		/**
		 * Displays HTML markup producing content panels for this menu page.
		 */
		public function display_content_panels() {
			foreach ( $this->©options->get( 'sets' ) as $k => $v ) {
				$this->add_content_panel( $this->©menu_pages__panels__random_set( $this, $k, $v ), true );
			}

			$this->display_content_panels_in_order();
		}

		/**
		 * Displays HTML markup producing sidebar panels for this menu page.
		 *
		 * @extenders Can be overridden by class extenders (i.e. by each menu page),
		 *    so that custom sidebar panels can be displayed by this routine.
		 */
		public function display_sidebar_panels() {
			parent::display_sidebar_panels();
		}

		/**
		 * Displays HTML markup for notices, for this menu page.

		 */
		public function display_notices() {
		}
	}
}