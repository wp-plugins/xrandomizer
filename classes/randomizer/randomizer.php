<?php
/**
 * Created by PhpStorm.
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 4/10/2014
 * Time: 8:56 μμ
 */

namespace randomizer {

	if ( ! defined( 'WPINC' ) ) {
		exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
	}

	use WP_Query;
	use xd_v141226_dev\arrays;

	class randomizer extends framework {
		/**
		 * @var array
		 */
		protected $original = array();
		/**
		 * @var array
		 */
		protected $randomized = array();
		/**
		 * @var array
		 */
		protected $setOptions = array();
		/**
		 * @var bool
		 */
		protected $isDefault = true;
		/**
		 * @var string
		 */
		protected $setId = 'default';
		/**
		 * @var int
		 */
		protected $pinedCount = 0;

		/**
		 * @param $setId
		 *
		 * @return string
		 * @throws \xd_v141226_dev\exception
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 140914
		 */
		public function getRandomSetMarkUp( $setId ) {
			$this->init( $setId );

			$randomized = $this->randomizeArray();
			$before     = $this->©options->get( 'before_element' );
			$after      = $this->©options->get( 'after_element' );
			$out        = '';
			foreach ( $randomized as $element ) {
				if ( ! isset( $element['mode'] ) || ! method_exists( $this, $element['mode'] ) ) {
					continue;
				}

				$out .= $before . $this->{$element['mode']}( $element ) . $after;
			}

			return $out;
		}

		/**
		 * @param $element
		 *
		 * @return string
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150120
		 */
		protected function php( $element ) {
			ob_start();
			$eval = '?>' . $element['content'] . '<?php ';
			eval( $eval );

			return ob_get_clean();
		}

		/**
		 * @param $element
		 *
		 * @return mixed
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150120
		 */
		protected function html( $element ) {
			return $element['content'];
		}

		/**
		 * @param $element
		 *
		 * @return string
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150120
		 */
		protected function javascript( $element ) {
			return '<script type="text/javascript">' . $element['content'] . '</script>';
		}

		/**
		 * @param $element
		 *
		 * @return mixed
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150120
		 */
		protected function text( $element ) {
			return esc_html( $element['content'] );
		}

		/**
		 * @param $element
		 *
		 * @return string
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150120
		 */
		protected function markdown( $element ) {
			return $this->©markdown->parse( $element['content'] );
		}

		/**
		 * @param $element
		 *
		 * @return string
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 150501
		 */
		protected function banner( $element ) {
			$con = '';
			if ( isset( $element['content']['link'] ) && ! empty( $element['content']['link'] ) ) {
				$con = '<a href="' . $element['content']['link'] . '"';
				if ( isset( $element['content']['target'] ) && $element['content']['target'] ) {
					$con .= ' target="_blank"';
				}
				$con .= '>';
			}
			$con .= '<img src="' . $element['content']['image'] . '" style="max-width:100%;">';
			if ( isset( $element['content']['link'] ) && ! empty( $element['content']['link'] ) ) {
				$con .= '</a>';
			}
			return $con;
		}

		/**
		 * @param $setId
		 *
		 * @return bool
		 * @throws \xd_v141226_dev\exception
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 140914
		 */
		protected function init( $setId ) {
			$this->setId     = $setId;
			$this->isDefault = true;
			$sets            = $this->©options->get( 'sets' );
			$set             = reset( $sets );
			foreach ( $sets as $s ) {
				if ( $s['id'] === $this->setId ) {
					$set             = $s;
					$this->isDefault = false;
					break;
				}
			}
			$this->randomized = $this->original = &$set['elements'];
			$this->setOptions = &$set;
			$defSets          = $this->©options->get( 'sets', true );
			$this->setOptions = $this->setOptions + $defSets[0];

			return $this->isDefault;
		}

		/**
		 * @return array
		 */
		protected function randomizeArray() {
			$rotateThem = $this->setOptions['randomPolicy'] == 'rotate';
			// Remove disabled elements all the times and before any other action
			$this->removeDisabled();
			$pined = $this->countGetAndRemovePined();

			if ( $rotateThem ) {
				$cookieName = 'rzRotateIndex' . $this->setOptions['id'];
				$cookie     = \WP_Session::get_instance();

				$index = ( isset( $cookie[ $cookieName ] ) && ! empty( $cookie[ $cookieName ] ) ) ? (int) $cookie[ $cookieName ] : 0;

				$elToRandomize = count( $this->setOptions['elements'] ) - $this->pinedCount;
				if ( $elToRandomize ) {
					$index %= $elToRandomize;
				} else {
					$index = 0;
				}
				$cookie[ $cookieName ] = $index + 1;

				while ( $index -- > 0 ) {
					array_push( $this->randomized, array_shift( $this->randomized ) );
				}
			} else {
				$this->randomized = shuffle( $this->randomized ) ? $this->randomized : $this->original;
			}

			foreach ( $pined as $k => $element ) {
				if ( empty( $pined[ $k ] ) ) {
					$pined[ $k ] = array_shift( $this->randomized );
				}
			}

			$this->randomized = $pined;

			return $this->getSliced();
		}

		/**
		 * @return array
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 140914
		 */
		protected function countGetAndRemovePined() {
			$a = array();
			$c = 0;
			foreach ( $this->randomized as $k => $element ) {
				if ( $element['pined'] ) {
					// if pined add the element to array
					array_push( $a, $element );
					unset( $this->randomized[ $k ] );
					$c ++;
				} else {
					// else push an empty array, this indicates a place for non pined elements
					array_push( $a, array() );
				}
			}

			$this->pinedCount = $c;

			return $a;
		}

		/**
		 * @return $this
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 140914
		 */
		protected function removeDisabled() {
			foreach ( $this->randomized as $k => $element ) {
				if ( $element['disabled'] ) {
					unset( $this->randomized[ $k ] );
				}
			}

			return $this;
		}

		/**
		 * @return array
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 * @since 140914
		 */
		protected function getSliced() {
			return array_slice( $this->randomized, 0, $this->setOptions['numOfElmsToDspl'] > 0 ? $this->setOptions['numOfElmsToDspl'] : null );
		}

		/**
		 * @param \WP_Query $wpQuery
		 *
		 * @return \WP_Query
		 */
		protected function randomizeWP_Query( WP_Query $wpQuery ) {
			if ( ! isset( $wpQuery->posts ) ) {
				return $wpQuery;
			}
			$wpQuery->posts = $this->randomizeArray( $wpQuery->posts );

			return $wpQuery;
		}
	}
}