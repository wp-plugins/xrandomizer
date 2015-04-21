<?php
/**
 * User: vagenas
 * Date: 9/11/14
 * Time: 10:10 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 9/11/14 Panagiotis Vagenas <pan.vagenas@gmail.com>
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
	class initializer extends \xd_v141226_dev\initializer {

		/**
		 * Add any additional hooks/filters needed by an extender.
		 *
		 * @extenders This should be overwritten by class extenders (when/if needed).
		 */
		public function after_setup_theme_hooks() {
			$this->add_action( 'widgets_init', '©initializer.register_widgets' );
			$this->addShortCode( $this->©string->with_underscores( $this->instance->plugin_root_ns_stub ), array(
				$this,
				'©shortcodes__shortcode.display'
			) );

			$this->add_action( 'wp_head', '©styles.customCSS' );

		}

		/**
		 * Register widgets
		 *
		 * @extenders Overwrite this to register your widgets
		 */
		public function register_widgets() {
			register_widget( '\randomizer\widget' );
		}

		protected function addShortCode( $tag, $func ) {
			add_shortcode( $tag, $func );
		}
	}
}