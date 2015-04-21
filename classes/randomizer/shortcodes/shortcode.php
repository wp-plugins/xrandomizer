<?php
/**
 * Created by PhpStorm.
 * User: vagenas
 * Date: 11/10/2014
 * Time: 11:23 μμ
 */

namespace randomizer\shortcodes {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	class shortcode extends \xd_v141226_dev\shortcodes\shortcode {
		protected $content;

		/**
		 * Gets default shortcode attributes.
		 *
		 * @note This should be overwritten by class extenders.
		 * @return array Default shortcode attributes.
		 */
		public function attr_defaults() {
			$sets = $this->©options->get( 'sets', true );

			return array( isset( $sets[0] ) ? $sets[0] : array() );
		}

		/**
		 * Gets all shortcode attribute keys, interpreted as boolean values.
		 *
		 * @note This should be overwritten by class extenders.
		 * @return array Boolean attribute keys.
		 */
		public function boolean_attr_keys() {
			return array();
		}

		public function display( $attrs ) {
			if ( ! isset( $attrs['set'] ) ) {
				return '';
			}
			$this->initialize( $attrs['set'] );

			return $this->content;
		}

		public function initialize( $setName ) {
			$this->content = $this->©randomizer->getRandomSetMarkUp( $this->©options->getSetIdFromSetName( $setName ) );

			return $this;
		}
	}
}