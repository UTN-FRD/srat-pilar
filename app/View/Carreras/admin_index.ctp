<?php
/**
 * Índice
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
$this->Html->addCrumb('Carreras');

/**
 * Tareas
 */
$this->set('tasks', array(
	array(
		'text' => 'Agregar',
		'url' => array(
			'action' => 'agregar'
		)
	)
));

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('nombre', 'Nombre'),
	$this->Paginator->sort('obs', 'Descripción'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['Carrera']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Carrera']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			h($row['Carrera']['nombre']),
			nl2br(h($row['Carrera']['obs'])),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));
