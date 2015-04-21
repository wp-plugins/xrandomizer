<?php

/**
 * User: vagenas
 * Date: 9/11/14
 * Time: 11:33 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 9/11/14 Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link -*-
 */

namespace randomizer;

if ( ! defined( 'WPINC' ) ) {
	exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
}

/**
 * @package randomizer
 * @author pan.vagenas <pan.vagenas@gmail.com>
 */
class installer extends \xd_v141226_dev\installer {

	/**
	 * Any additional activation routines.
	 *
	 * @extenders This should be overwritten by class extenders (when/if needed).
	 * @return boolean TRUE if all routines were successful, else FALSE if there were any failures.
	 */
	public function activations() {
		return true; // Indicate success.
	}

	/**
	 * Any additional deactivation routines.
	 *
	 * @extenders This should be overwritten by class extenders (when/if needed).
	 * @return boolean TRUE if all routines were successful, else FALSE if there were any failures.
	 */
	public function deactivations() {
		return true; // Indicate success.
	}

	/**
	 * Any additional uninstall routines.
	 *
	 * @extenders This should be overwritten by class extenders (when/if needed).
	 * @return boolean TRUE if all routines were successful, else FALSE if there were any failures.
	 */
	public function uninstallations() {
		return true; // Indicate success.
	}

}
