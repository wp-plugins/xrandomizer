<?php
/**
 * User: vagenas
 * Date: 9/14/14
 * Time: 10:41 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/gpl-3.0.txt>.
 */


namespace randomizer {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	/* ----------------------------------------------------------------------------*
	 * Session functionality
	 * ---------------------------------------------------------------------------- */
	if ( ! defined( 'WP_SESSION_COOKIE' ) ) {
		define( 'WP_SESSION_COOKIE', 'wp_session' );
	}

	if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/session_manager/class-recursive-arrayaccess.php' );
	}

	// Only include the functionality if it's not pre-defined.
	if ( ! class_exists( 'WP_Session' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/session_manager/class-wp-session.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/session_manager/wp-session.php' );
	}

	// Start plugin
	require_once dirname( __FILE__ ) . '/classes/randomizer/framework.php';
}