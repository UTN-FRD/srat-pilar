<?php
/**
 * Editar
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo LICENCIA.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Administrar');
$this->Html->addCrumb('Cargos', array('action' => 'index'));
$this->Html->addCrumb('Editar');
?>
<?php echo $this->Form->create('Cargo', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	echo $this->Form->input('asignatura_id', array(
		'class' => 'combobox span8'
	));

	echo $this->Form->input('usuario_id', array(
		'class' => 'combobox span5',
		'label' => 'Docente'
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
