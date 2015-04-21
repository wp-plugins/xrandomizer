<?php
/**
 * User: vagenas
 * Date: 9/14/14
 * Time: 10:43 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link -*-
 */


namespace randomizer {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/core/stub.php';
# --------------------------------------------------------------------------------------------------------------
	# XDaRk Core framework class definition.
	# --------------------------------------------------------------------------------------------------------------
	/**
	 * @property \randomizer\initializer                                        $©initializer
	 * @method \randomizer\initializer                                          ©initializer()
	 * @property \randomizer\installer                                          $©installer
	 * @method \randomizer\installer                                            ©installer()
	 * @property \randomizer\menu_pages                                         $©menu_pages
	 * @property \randomizer\menu_pages                                         $©menu_page
	 * @method \randomizer\menu_pages                                           ©menu_pages()
	 * @method \randomizer\menu_pages                                           ©menu_page()
	 * @property \randomizer\menu_pages\menu_page                               $©menu_pages__menu_page
	 * @method \randomizer\menu_pages\menu_page                                 ©menu_pages__menu_page()
	 * @property \randomizer\menu_pages\main_page                               $©menu_pages__main_page
	 * @method \randomizer\menu_pages\main_page                                 ©menu_pages__main_page()
	 * @property \randomizer\menu_pages\panels\donations                        $©menu_pages__panels__donations
	 * @method \randomizer\menu_pages\panels\donations                          ©menu_pages__panels__donations()
	 * @property \randomizer\menu_pages\panels\main_settings                    $©menu_pages__panels__main_settings
	 * @method \randomizer\menu_pages\panels\main_settings                      ©menu_pages__panels__main_settings()
	 * @property \randomizer\menu_pages\panels\other_plugins                    $©menu_pages__panels__other_plugins
	 * @method \randomizer\menu_pages\panels\other_plugins                      ©menu_pages__panels__other_plugins()
	 * @property \randomizer\menu_pages\panels\random_set                       $©menu_pages__panels__random_set
	 * @method \randomizer\menu_pages\panels\random_set                         ©menu_pages__panels__random_set()
	 * @property \randomizer\options                                            $©options
	 * @property \randomizer\options                                            $©option
	 * @method \randomizer\options                                              ©options()
	 * @method \randomizer\options                                              ©option()
	 * @property \randomizer\shortcodes\shortcode                               $©shortcodes__shortcode
	 * @method \randomizer\shortcodes\shortcode                                 ©shortcodes__shortcode()
	 */
	class framework extends \xd__framework {


		public function __construct( $instance ) {
			parent::__construct( $instance );
		}
	}

	$GLOBALS[ __NAMESPACE__ ] = new framework(
		array(
			'plugin_root_ns' => __NAMESPACE__,
			// The root namespace
			'plugin_var_ns'  => 'rz',
			// A shorter namespace alias (or the same as `plugin_root_ns` if you like).
			'plugin_cap'     => 'manage_options',
			// The WordPress capability (or role) required to manage plugin options.
			'plugin_name'    => 'XRandomizer',
			//
			'plugin_version' => '150120',
			// The current version of plugin (must be in `YYMMDD` format).
			'plugin_site'    => 'http://example.com',
			'plugin_dir'     => dirname( dirname( dirname( __FILE__ ) ) ) // Your plugin directory.
		)
	);
}