<?php
/**
 * User: vagenas
 * Date: 9/11/14
 * Time: 10:20 PM
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
	class widget extends \WP_Widget {
		/**
		 * @var string Slug for this widget.
		 * @note Set to the basename of the class w/ dashes.
		 */
		public $slug = 'randomizer';

		/**
		 * @var string Name for this widget.
		 * @note Set to the name of the widget w/ dashes.
		 */
		public $name = 'XRandomizer Widget';

		/**
		 * @var array Options for this widget.
		 * @note Set options for this widget.
		 */
		public $options = array( 'description' => 'Displays XRandomizer Sets in Any Widget Area' );

		/**
		 * @var array Options for this widget.
		 * @note Set options for this widget. Currently only width is supported.
		 */
		public $controlOptions = array();

		/**
		 * Plugin framework
		 *
		 * @var framework
		 */
		protected $framework;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {

			global $randomizer;
			$this->framework = $randomizer;

			$this->options['description'] = $this->framework->__( $this->options['description'] );

			parent::__construct(
				$this->framework->__( $this->slug ),
				$this->framework->__( $this->name ),
				$this->options,
				$this->controlOptions
			);

		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 *            Widget arguments.
		 * @param array $instance
		 *            Saved values from database.
		 *
		 * @since 140901
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 */
		public function widget( $args, $instance ) {
			$instance = (array) $instance + (array) $this->framework->©options->get( 'widget', true );

			echo $args ['before_widget'];
			echo $args ['before_title'] . $instance ['name'] . $args ['after_title'];
			echo $this->framework->©randomizer->getRandomSetMarkUp( $instance['set'] );
			echo $args ['after_widget'];
		}

		/**
		 * Back-end widget form.
		 * Outputs the options form on admin
		 *
		 * @see \WP_Widget::form()
		 *
		 * @param array $instance
		 *            Previously saved values from database.
		 *
		 * @return string|void
		 * @since 140901
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 */
		public function form( $instance ) {
			$instance = (array) $instance + (array) $this->framework->©options->get( 'widget', true );
			?>
			<input type="text" value="<?php echo $instance['name']; ?>"
			       name="<?php echo $this->get_field_name( 'name' ); ?>"
			       id="<?php echo $this->get_field_id( 'name' ); ?>">
			<select name="<?php echo $this->get_field_name( 'set' ); ?>"
			        id="<?php echo $this->get_field_id( 'name' ); ?>">
				<?php
				foreach ( $this->framework->©options->get( 'sets' ) as $set ) {
					if ( $set['name'] == 'Default' ) {
						continue;
					}
					?>
					<option
						value="<?php echo $set['id']; ?>" <?php selected( $instance['set'], $set['id'] ); ?>><?php echo $set['name']; ?></option>
				<?php
				}
				?>
			</select>
		<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance
		 *            Values just sent to be saved.
		 * @param array $old_instance
		 *            Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 * @since 1.0.0
		 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
		 */
		public function update( $newInstance, $oldInstance ) {
			$oldInstance = (array) $oldInstance + (array) $this->framework->©options->get( 'widget', true );

			$newInstance['name'] = isset( $newInstance['name'] ) ? wp_strip_all_tags( (string) $newInstance['name'] ) : $oldInstance['name'];
			$newInstance['set']  = isset( $newInstance['set'] ) ? $this->framework->©string->with_underscores( $newInstance['set'] ) : $oldInstance['set'];

			return $newInstance;
		}
	}
}