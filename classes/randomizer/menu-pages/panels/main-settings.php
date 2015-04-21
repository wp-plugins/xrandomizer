<?php
/**
 * User: vagenas
 * Date: 9/15/14
 * Time: 11:30 PM
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
	class main_settings extends panel {
		/**
		 * @var string Heading/title for this panel.
		 * @extenders Should be overridden by class extenders.
		 */
		public $heading_title = 'Main settings';

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

		public function __construct( $instance, $menu_page ) {
			parent::__construct( $instance, $menu_page );

			/**
			 * Add the content
			 */
			$this->content_body .= $this->header();
			$this->content_body .= $this->main();
			$this->content_body .= $this->footer();
		}

		protected function header() {
			$out = '';

			return $out;
		}

		protected function main() {
			$out = '';

			$before_element           = $this->©options->get( 'before_element' );
			$before_elementFieldProps = array(
				'required'    => false,
				'type'        => 'text',
				'name'        => '[before_element]',
				'title'       => $this->__( 'Before elements:' ),
				'placeholder' => $this->__( 'Enter everything you may want to appear before each element' ),
				'classes'     => 'form-control',
				'id'          => 'before-element'
			);
			$out                      = '<div class="form-horizontal">';

			$out .= '<div class="form-group">
			            <label class="control-label col-sm-3" for="before-element">' . $this->__( 'Before elements:' ) . '</label>
			            <div class="col-sm-9">'
			        . $this->menu_page->option_form_fields->markup( $this->menu_page->option_form_fields->value( $before_element ), $before_elementFieldProps ) . '
			            </div>
		            </div>';

			$after_element           = $this->©options->get( 'after_element' );
			$after_elementFieldProps = array(
				'required'    => false,
				'type'        => 'text',
				'name'        => '[after_element]',
				'title'       => $this->__( 'After elements:' ),
				'placeholder' => $this->__( 'Enter everything you may want to appear after each element' ),
				'classes'     => 'form-control',
				'id'          => 'after-element'
			);

			$out .= '<div class="form-group">
			            <label class="control-label col-sm-3" for="after-element">' . $this->__( 'After elements:' ) . '</label>
			            <div class="col-sm-9">'
			        . $this->menu_page->option_form_fields->markup( $this->menu_page->option_form_fields->value( $after_element ), $after_elementFieldProps ) . '
			            </div>
		            </div>';

			$customCSSFieldProps = array(
				'required'    => false,
				'type'        => 'textarea',
				'name'        => '[custom_css]',
				'title'       => $this->__( 'Custom CSS to be applied to elements' ),
				'placeholder' => $this->__( 'Custom CSS to be applied to elements' ),
				'classes'     => 'text-area form-control',
				'id'          => 'custom-css',
				'rows'        => 15,
				'attrs'       => 'data-editor="css"'
			);

			$customCSS = $this->©options->get( 'custom_css' );
			$out .= '<div class="form-group">';
			$out .= '<label class="control-label col-sm-3" for="custom-css">' . $this->__( 'Custom CSS to be applied to elements:' ) . '</label>';
			$out .= '<div class="col-sm-9 text-area-wrapper" data-method="css">';
			$out .= $this->menu_page->option_form_fields->markup( $this->menu_page->option_form_fields->value( $customCSS ), $customCSSFieldProps );
			$out .= '</div>';
			$out .= '</div>';
			$out .= '</div>';

			return $out;
		}

		protected function footer() {
			$out = '<script ype="text/javascript">var elementModes = ';
			$out .= $this->©var->to_js($this->©option->elementModes);
			$out .= '</script>';

			return $out;
		}
	}
}