<?php
/**
 * Reporte de asistencia
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
?>
<table class="details">
	<tbody>
		<tr>
			<td>
				<div>
					<span>Carrera:</span>
					<?php echo h($carreras[$data['carrera_id']]) ?>
				</div>
				<div>
					<span>Período:</span>
					<?php echo ucfirst(CakeTime::format(strtotime(implode('-', array_reverse($data['periodo']))), '%B de %Y')) ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?php
$records = array();
foreach($rows as $row):
	$records[$row['Materia']['nombre']][$row['Registro']['docente']] = $row[0];
endforeach;
unset($rows, $row);
ksort($records);
foreach ($records as &$record):
	ksort($record);
endforeach;

$pushRow = function ($row) use (&$rows) {
	if (empty($rows)):
		$rows[] = array();
	endif;
	$index = count($rows) - 1;
	if (count($rows[$index]) == 25):
		$rows[] = array();
		$index++;
	endif;
	$rows[$index][] = $row;
};

$rows = array();
foreach ($records as $materia => $docentes):
	foreach ($docentes as $docente => $values):
		$pushRow(array_merge(
			array(h($materia), h($docente)),
			$values
		));
	endforeach;
endforeach;

$count = count($rows);
foreach ($rows as $rid => $chunk):
	if ($rid):
		echo '<br />';
	endif;
	?>
	<table class="report">
		<thead>
			<tr>
				<th class="row1">Materia</th>
				<th class="row2">Docente</th>
				<th class="row3">Horas</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($chunk as $row):
			?>
			<tr>
				<td class="row1"><?php echo $row[0] ?></td>
				<td class="row2"><?php echo $row[1] ?></td>
				<td class="row3"><?php echo $row['horas'] ?></td>
			</tr>
			<?php
		endforeach;
		?>
		</tbody>
	</table>
	<?php
	if ($rid < ($count - 1)):
		echo '<div class="page-break"></div>';
	endif;
endforeach;
