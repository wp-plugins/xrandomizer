<?php
/**
 * Project: randomizer
 * File: random_set_panel_element.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 17/1/2015
 * Time: 10:16 μμ
 * Since: 140914
 * Copyright: 2015 Panagiotis Vagenas
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
}
/* @var $callee \randomizer\menu_pages\panels\random_set */
/* @var $this \xd_v141226_dev\views */
/* @var int $index */
/* @var string $content */
/* @var bool $pined */
/* @var bool $disabled */
/* @var string $mode */

$elementFieldProps = array(
	'required'    => ! $callee->isDefault,
	'type'        => 'textarea',
	'name'        => '[elements][' . $index . '][content]',
	'title'       => $this->__( 'Add some text or HTML markup to this element' ),
	'placeholder' => $this->__( 'Add some text or HTML markup to this element' ),
	'name_prefix' => $callee->fieldNamePrefix,
	'classes'     => 'text-area form-control element-text-area',
	'id'          => 'elements-' . $callee->setIdx . '-' . $index,
	'rows'        => 7,
	'attrs'       => 'data-editor="' . $mode . '"'
);
?>
<div id="element-row-<?php echo $callee->slug . '-' . $index; ?>" class="form-group" data-index="<?php echo $index; ?>">
	<div class="col-sm-10 text-area-wrapper" data-method="<?php echo $mode; ?>">
		<?php echo $callee->menu_page->option_form_fields->markup( $callee->menu_page->option_form_fields->value( $content ), $elementFieldProps ); ?>
	</div>
	<?php
	$btnCtrlAttr = ' data-setid="' . $callee->setIdx . '" data-set="' . $callee->slug . '" data-index="' . $index . '" ';

	$pinedActive    = $pined ? 'active' : '';
	$disabledActive = $disabled ? 'active' : '';
	$pinedClass     = $pined ? 'fa-lock' : 'fa-unlock';
	?>
	<div class="col-sm-2 text-center element-control">
		<div class="row b-margin-sm">
			<div class="col-sm-6">
				<button type="button" <?php echo $btnCtrlAttr; ?> style="font-size: 1em;" value="1" name="pin"
				        class="btn btn-success element-pin <?php echo $pinedActive; ?>" title="Pin element">
					<i class="fa <?php echo $pinedClass; ?>"></i>
				</button>
			</div>
			<div class="col-sm-6 btn-group">
				<button <?php echo $btnCtrlAttr; ?>
					data-toggle="xd-v141226-dev-dropdown"
					style="font-size: 1em; float: none;"
					title="Add new element"
					class="btn btn-success element-add dropdown-toggle"
					type="button">
					<i class="fa fa-plus"></i>
				</button>
				<ul class="dropdown-menu" <?php echo $btnCtrlAttr; ?>>
					<?php
					foreach ( $this->©option->elementModes as $k => $v ) {
						echo '<li data-mode="' . $k . '"><a href="#">' . $v . '</a></li>';
					}
					?>
				</ul>
			</div>
		</div>


		<?php
		if ( $index != 0 ) {
			?>
			<div class="row b-margin-sm">
				<div class="col-sm-6">
					<button type="button" <?php echo $btnCtrlAttr; ?> style="font-size: 1em;"
					        class="btn btn-warning element-disable <?php echo $disabledActive; ?>"
					        title="Disable element">
						<i class="fa fa-power-off"></i>
					</button>
				</div>
				<div class="col-sm-6">
					<button type="button" <?php echo $btnCtrlAttr; ?> style="font-size: 1em;"
					        class="btn btn-danger element-delete" title="Delete element">
						<i class="fa fa-trash-o"></i>
					</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 btn-group">
					<button <?php echo $btnCtrlAttr; ?>
						data-toggle="xd-v141226-dev-dropdown"
						style="font-size: 1em; float: none;"
						title="Change Method"
						class="btn btn-primary element-change-method dropdown-toggle"
						type="button">
						Change Method <i class="fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu" <?php echo $btnCtrlAttr; ?>>
						<?php
						foreach ( $this->©option->elementModes as $k => $v ) {
							echo '<li data-mode="' . $k . '"><a href="#">' . $v . '</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		<?php
		} else {
			?>
			<div class="row">
				<div class="col-sm-12 btn-group">
					<button <?php echo $btnCtrlAttr; ?>
						data-toggle="xd-v141226-dev-dropdown"
						style="font-size: 1em; float: none;"
						title="Change Method"
						class="btn btn-primary element-change-method dropdown-toggle"
						type="button">
						Change Method <i class="fa fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu" <?php echo $btnCtrlAttr; ?>>
						<?php
						foreach ( $this->©option->elementModes as $k => $v ) {
							echo '<li data-mode="' . $k . '"><a href="#">' . $v . '</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		<?php
		}

		echo $callee->menu_page->option_form_fields->markup( $callee->menu_page->option_form_fields->value( $pined ), array(
			'type'        => 'hidden',
			'name'        => '[elements][' . $index . '][pined]',
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control pined',
			'id'          => 'pined-' . $callee->setIdx . '-' . $index
		) );

		echo $callee->menu_page->option_form_fields->markup( $callee->menu_page->option_form_fields->value( $disabled ), array(
			'type'        => 'hidden',
			'name'        => '[elements][' . $index . '][disabled]',
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control pined',
			'id'          => 'disabled-' . $callee->setIdx . '-' . $index
		) );

		$mode = empty( $mode ) ? 'html' : $mode;
		echo $callee->menu_page->option_form_fields->markup( $callee->menu_page->option_form_fields->value( $mode ), array(
			'type'        => 'hidden',
			'name'        => '[elements][' . $index . '][mode]',
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control pined',
			'id'          => 'mode-' . $callee->setIdx . '-' . $index
		) );
		?>
	</div>

</div>