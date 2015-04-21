<?php
/**
 * Project: randomizer
 * File: random_set_panel.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 17/1/2015
 * Time: 12:58 μμ
 * Since: 140914
 * Copyright: 2015 Panagiotis Vagenas
 */

if ( ! defined( 'WPINC' ) ) {
	exit( 'Do NOT access this file directly: ' . basename( __FILE__ ) );
}
/* @var $callee \randomizer\menu_pages\panels\random_set */
/* @var $this \xd_v141226_dev\views */

$optionFormFields = &$callee->menu_page->option_form_fields;
$name             = $callee->getOption( 'name' );
$nameFieldProps   = array(
	'required'    => ! $callee->isDefault,
	'type'        => 'text',
	'name'        => '[name]',
	'title'       => $this->__( 'Name' ),
	'placeholder' => $this->__( 'Enter the name of the set' ),
	'name_prefix' => $callee->fieldNamePrefix,
	'classes'     => 'form-control',
	'id'          => 'name-' . $callee->setIdx
);
?>
	<div class="form-horizontal">
		<?php
		echo $optionFormFields->markup( $optionFormFields->value( $callee->isDefault ), array(
			'type'        => 'hidden',
			'name'        => '[isDefault]',
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control',
			'id'          => 'isDefault-' . $callee->setIdx
		) );
		?>
		<p class="bg-info text-center row"><?php echo $this->__( 'Set Options' ); ?></p>

		<div class="form-group row">
			<label class="control-label col-sm-3"
			       for="name-<?php echo $callee->setIdx; ?>"><?php echo $this->__( 'Name' ); ?></label>

			<div class="col-sm-9">
				<?php
				echo $optionFormFields->markup( $optionFormFields->value( $name ), $nameFieldProps );
				?>

			</div>
		</div>
		<?php
		// Randomize policy
		$randomPolicy     = $callee->getOption( 'randomPolicy' );
		$policyFieldProps = array(
			'required'    => ! $callee->isDefault,
			'type'        => 'select',
			'name'        => '[randomPolicy]',
			'title'       => $this->__( 'Randomize policy' ),
			'placeholder' => $this->__( 'Choose how the items will be displayed' ),
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control',
			'id'          => 'randomPolicy-' . $callee->setIdx,
			'options'     => array(
				array(
					'label' => $this->__( 'Random' ),
					'value' => 'random'
				),
				array(
					'label' => $this->__( 'Rotate' ),
					'value' => 'rotate'
				),
			)
		);
		?>

		<div class="form-group">
			<label class="control-label col-sm-3" for="randomPolicy-<?php echo $callee->setIdx; ?>">
				<?php echo $this->__( 'Randomize policy' ); ?>
			</label>

			<div class="col-sm-9">
				<?php echo $optionFormFields->markup( $optionFormFields->value( $randomPolicy ), $policyFieldProps ); ?>
			</div>
		</div>

		<?php
		// Element to display
		$numOfElmsToDspl    = $callee->getOption( 'numOfElmsToDspl' );
		$numOfElmsFieldOpts = array(
			'required'    => ! $callee->isDefault,
			'type'        => 'number',
			'name'        => '[numOfElmsToDspl]',
			'title'       => $this->__( 'Choose the number of elements you want to display (this should be <= of total elements and >= zero. Set to 0 to display all elements' ),
			'placeholder' => $this->__( 'Number of elements to display <i>(zero to display all)</i>' ),
			'name_prefix' => $callee->fieldNamePrefix,
			'classes'     => 'form-control',
			'id'          => 'numOfElmsToDspl-' . $callee->setIdx,
			'attrs'       => ' min="0" data-toggle="tooltip"'
		);
		?>

		<div class="form-group">
			<label class="control-label col-sm-3" for="numOfElmsToDspl-<?php echo $callee->setIdx; ?>">
				<?php echo $this->__( 'Number of elements to display <br><small>(zero to display all)</small>' ); ?>
			</label>

			<div class="col-sm-9">
				<?php echo $optionFormFields->markup( $optionFormFields->value( $numOfElmsToDspl ), $numOfElmsFieldOpts ); ?>
			</div>
		</div>
	</div>

<?php // Elements ?>
	<p class="bg-info text-center row"><?php echo $this->__( 'Set Elements' ); ?></p>
	<div class="form-horizontal">
		<?php
		foreach ( $callee->getOption( 'elements' ) as $k => $v ) {
			echo $callee->element( $k, $v['content'], (bool) $v['pined'], (bool) $v['disabled'], $v['mode'] );
		}

		if ( ! count( $callee->getOption( 'elements' ) ) ) {
			echo $callee->element( 0, '', false, false );
		}
		?>
	</div>
<?php
// Set related actions
if ( ! $callee->isDefault ) {
	?>
	<p class="bg-info text-center row"><?php echo $this->__( 'Set Actions' ); ?></p>
	<div class="text-right row-fluid">
		<button type="button"
		        class="btn btn-danger btn-sm col-sm-3 col-sm-offset-9 set-delete"
		        data-setidx="<?php echo $callee->setIdx; ?>"
		        data-setselector="panel--<?php echo $callee->slug; ?>">
			<?php echo $this->__( 'Delete Set' ); ?>
		</button>
<!--		<button type="button"-->
<!--		        class="btn btn-success btn-sm col-sm-3 set-add --><?php //echo $callee->isDefault ? 'col-sm-offset-9' : 'col-sm-offset-1'; ?><!--"-->
<!--		        data-setidx="--><?php //echo $callee->setIdx; ?><!--"-->
<!--		        data-setselector="panel----><?php //echo $callee->slug; ?><!--">-->
<!--			--><?php //echo $this->__( 'Add New Set' ); ?>
<!--		</button>-->
	</div>
<?php
}
?>
<script type="text/javascript">
	var elementModes = <?php echo $this->©var->to_js($this->©option->elementModes); ?>
</script>