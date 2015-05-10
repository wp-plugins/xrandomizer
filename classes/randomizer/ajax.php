<?php
/**
 * Project: randomizer
 * File: ajax.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 6/5/2015
 * Time: 10:41 μμ
 * Since: 150501
 * Copyright: 2015 Panagiotis Vagenas
 */

namespace randomizer;

use randomizer\menu_pages\random_sets;

/**
 * Class ajax
 *
 * @package randomizer
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @since 150501
 * @property \randomizer\menu_pages\panels\random_set                       $©menu_pages__panels__random_set
 * @method \randomizer\menu_pages\panels\random_set                         ©menu_pages__panels__random_set()
 * @property \randomizer\menu_pages\random_sets                             $©menu_pages__random_sets
 * @method \randomizer\menu_pages\random_sets                               ©menu_pages__random_sets()
 */
class ajax extends \xd_v141226_dev\ajax {
	/**
	 * @param int    $setId
	 * @param int    $elemId
	 * @param string $mode
	 * @param string $content
	 * @param bool   $pined
	 * @param bool   $disabled
	 *
	 * @return bool
	 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
	 * @since 150501
	 */
	public function ®ajaxPrivateGetElementMarkup( $setId, $elemId, $mode = 'html', $content = '', $pined = false, $disabled = false ) {
		if ( ! $this->©user->is_super_admin() ) {
			$this->sendJSONError( 'Authorization failed', 401 );
		}
		$set  = $this->©menu_pages__random_sets->getRandomSetInstance( $setId );
		$html = $set->element( $elemId, $content, $pined, $disabled, $mode );
		$this->sendJSONSuccess( compact( 'html' ) );

		return true;
	}
}