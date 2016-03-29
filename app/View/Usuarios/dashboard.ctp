<?php
/**
 * Dashboard
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
 * CSS
 */
$this->Html->css('dashboard', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script('dashboard', array('inline' => false));
?>
<?php if (empty($this->request->data['Registro'])): ?>
	<div class="alert alert-info">
		No hay asignaturas asociadas a su usuario.
	</div>
<?php else: ?>
	<?php
	echo $this->Form->create('Registro', array('class' => 'form-asignaturas'));
	foreach ($this->request->data['Registro'] as $rid => $row):
		$class = ($rid % 2 === 0 ? 'left' : 'right');
		if (!empty($row['id'])):
			echo $this->Form->hidden(sprintf('Registro.%d.id', $rid));
		endif;
		echo $this->Form->hidden(sprintf('Registro.%d.asignatura', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.asignatura_id', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.usuario_id', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.fecha', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.entrada', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.salida', $rid));
		?>
		<table class="table table-bordered table-condensed table-<?php echo $class ?>">
			<thead>
				<tr>
					<th>Asignatura</th>
					<th>Entrada</th>
					<th>Salida</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="check-container">
							<?php
								echo $this->Form->checkbox(sprintf('Registro.%d.check', $rid));
								echo $this->Form->label(sprintf('Registro.%d.check', $rid), $row['asignatura']);
							?>
						</div>
					</td>
					<td>
						<?php
						if (!empty($row['entrada'])):
							echo date('H:i', strtotime($row['entrada']));
						else:
							echo '-';
						endif;
						?>
					</td>
					<td>
						<?php
						if (!empty($row['salida'])):
							echo date('H:i', strtotime($row['salida']));
						else:
							echo '-';
						endif;
						?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php
						echo $this->Form->input(sprintf('Registro.%d.obs', $rid), array(
							'error' => false,
							'label' => false,
							'required' => false,
							'rows' => 2
						))
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	endforeach;
	?>
	<div class="clearfix">
	</div>
	<?php echo $this->Form->buttons(array('Guardar cambios' => array('type' => 'submit'))) ?>
<?php endif ?>
