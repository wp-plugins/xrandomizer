<?php
/**
 * User: vagenas
 * Date: 9/15/14
 * Time: 11:32 PM
 *
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 9/15/14 Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link -*-
 */

namespace randomizer\menu_pages\panels {

	use xd_v141226_dev\menu_pages\panels\panel;

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	/**
	 * @package randomizer\menu_pages\panels
	 * @author pan.vagenas <pan.vagenas@gmail.com>
	 */
	class random_set extends panel {
		/**
		 * @var string Heading/title for this panel.
		 * @extenders Should be overridden by class extenders.
		 */
		public $heading_title = 'Random Set: ';

		/**
		 * @var string Content/body for this panel.
		 * @extenders Should be overridden by class extenders.
		 */
		public $content_body = '';

		/**
		 * @var string Additional documentation for this panel.
		 * @extenders Can be overridden by class extenders.
		 */
		public $documentation = '';

		/**
		 * @var string Field name prefix to use in field markups
		 */
		public $fieldNamePrefix;

		public $setIdx;

		/**
		 * @var array Set options
		 */
		public $setOptions = array();

		public $isDefault = true;

		/**
		 * @var array Default options
		 */
		protected $defaultOptions = array();

		public function __construct( $instance, $menu_page, $setId, $options ) {
			parent::__construct( $instance, $menu_page );

			/**
			 * Modify slug in order to display multiple sets
			 */
			$this->slug .= '-' . $setId;

			$this->setIdx     = $setId;
			$this->setOptions = $options;

			$this->defaultOptions = $this->©options->get( 'sets', true );
			$this->heading_title .= '<strong>' . $this->getOption( 'name' ) . '</strong>';

			$sets            = $this->©options->get( 'sets', true );
			$dif             = $this->©arrays->array_dif_assoc_deep( $options, $sets[0] );// array_diff_assoc($options, $this->©options->get( 'sets', true )[0]);
			$this->isDefault = empty( $dif );

			/**
			 * Prefix in order to assemble sets array
			 */
			$this->fieldNamePrefix = $this->menu_page->option_form_fields->name_prefix . '[' . $setId . ']';

			/**
			 * Add the content
			 */
			$this->content_body = $this->©view->view( $this, 'random_set_panel.php' );
		}

		public function element( $index, $content, $pined = false, $disabled = false, $mode = 'html' ) {
			return $this->©view->view( $this, 'random_set_panel_element.php', array(
				'index'    => $index,
				'content'  => $content,
				'pined'    => $pined,
				'disabled' => $disabled,
				'mode'     => $mode
			) );
		}

		public function getOption( $optionName ) {
			if ( isset( $this->setOptions[ $optionName ] ) ) {
				return $this->setOptions[ $optionName ];
			}

			return $this->defaultOptions[ $optionName ];
		}
	}
}