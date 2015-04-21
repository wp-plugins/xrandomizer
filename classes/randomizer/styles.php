<?php
/**
 * Created by PhpStorm.
 * User: vagenas
 * Date: 11/10/2014
 * Time: 6:06 πμ
 */

namespace randomizer {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	class styles extends \xd_v141226_dev\styles {
		/**
		 * Builds the initial set of front-side components.
		 *
		 * @extenders Can be extended to add additional front-side components.
		 * @return array An array of any additional front-side components.
		 */
		public function front_side_components() {
			return array();
		}

		/**
		 * Builds the initial set of stand-alone components.
		 *
		 * @extenders Can be extended to add additional stand-alone components.
		 * @return array An array of any additional stand-alone components.
		 */
		public function stand_alone_components() {
			return array();
		}

		/**
		 * Builds the initial set of menu page components.
		 *
		 * @extenders Can be extended to add additional menu page components.
		 * @return array An array of any additional menu page components.
		 */
		public function menu_page_components() {
			$this->register( array(
				$this->instance->ns_with_dashes . '--menu-pages-random-sets' => array(
					'deps' => array(),
					'url'  => $this->©url->to_plugin_dir_file( '/client-side/styles/random-sets.min.css' ),
					'ver'  => $this->instance->core_version_with_dashes
				)
			) );

			return array(
				$this->instance->ns_with_dashes . '--menu-pages-random-sets'
			);
		}

		public function customCSS() {
			$css = $this->©options->get( 'custom_css' );
			if ( ! empty( $css ) ) {
				echo '<style type="text/css" class="rz-apply-inline">' . $css . '</style>';
			}
		}
	}
}