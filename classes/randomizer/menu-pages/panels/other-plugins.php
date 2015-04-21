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

// For future use

namespace randomizer\menu_pages\panels {
	use xd_v141226_dev\menu_pages\panels\panel;

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	/**
	 * Menu Page Panel.
	 *
	 * @package WebSharks\Core
	 * @since 140914
	 * @assert ($GLOBALS[__NAMESPACE__])
	 */
	class other_plugins extends panel {
		/**
		 * Constructor.
		 *
		 * @param object|array $instance Required at all times.
		 *    A parent object instance, which contains the parent's ``$instance``,
		 *    or a new ``$instance`` array.
		 * @param \xd_v141226_dev\menu_pages\menu_page
		 *    $menu_page A menu page class instance.
		 */
		public function __construct( $instance, $menu_page ) {
			parent::__construct( $instance, $menu_page );

			$this->heading_title = $this->__( 'Other Plugins From Panagiotis Vagenas' );

			$this->content_body =
				'';
		}
	}
}