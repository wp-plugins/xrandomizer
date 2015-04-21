<?php
/**
 * User: vagenas
 * Date: 9/14/14
 * Time: 10:21 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link -*-
 */

namespace randomizer {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	/**
	 * @package randomizer
	 * @author pan.vagenas <pan.vagenas@gmail.com>
	 */
	class menu_pages extends \xd_v141226_dev\menu_pages {

		/**
		 * Handles WordPress® `admin_menu` hook.
		 *
		 * @extenders Should be overridden by class extenders, if a plugin has menu pages.
		 * @attaches-to WordPress® `admin_menu` hook.
		 * @hook-priority Default is fine.
		 * @return null Nothing.
		 */
		public function admin_menu() {
			$this->add( $this->default_menu_pages() );
		}

		/**
		 * Handles WordPress® `network_admin_menu` hook.
		 *
		 * @extenders Should be overridden by class extenders, if a plugin has menu pages.
		 * @attaches-to WordPress® `network_admin_menu` hook.
		 * @hook-priority Default is fine.
		 * @return null Nothing.
		 */
		public function network_admin_menu() {
			/**
			 * No global preferences so display admin menu
			 */
			$this->admin_menu();
		}

		/**
		 * Default (core-driven) menu pages.
		 *
		 * @return array Default (core-driven) menu pages.
		 * @see add() for further details about how to add menu pages.
		 */
		public function default_menu_pages() {

			$main_page_slug // A standard in the core.
				= $this->instance->plugin_root_ns_stub_with_dashes;

			// We want the menu page slug to come out the same in all versions.
			return array(
				$main_page_slug => array(
					'menu_title' => $this->instance->plugin_name,
					'icon'       => $this->©url->to_template_dir_file( '/client-side/images/icon-16x16.png' )
				),
				'random_sets'   => array(
					'is_under_slug' => $main_page_slug,
					'menu_title'    => $this->__( 'Random Sets' ),
				),
			);
		}
	}
}