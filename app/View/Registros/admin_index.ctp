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
 * CSS
 */
$this->Html->css('registros', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Registros');

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('Carrera.nombre', 'Carrera'),
	$this->Paginator->sort('Materia.nombre', 'Materia'),
	$this->Paginator->sort('Usuario.nombre', 'Docente'),
	$this->Paginator->sort('Registro.fecha', 'Fecha'),
	$this->Paginator->sort('Registro.entrada', 'Entrada'),
	$this->Paginator->sort('Registro.salida', 'Salida'),
	'Observaciones'
);

/*
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$rows[$rid] = array(
			$start++,
			h($row['Carrera']['nombre']),
			h($row['Materia']['nombre']),
			h($row['Registro']['docente']),
			date('d/m/Y', strtotime($row['Registro']['fecha'])),
			(!empty($row['Registro']['entrada']) ? date('H:i', strtotime($row['Registro']['entrada'])) : ''),
			(!empty($row['Registro']['salida']) ? date('H:i', strtotime($row['Registro']['salida'])) : ''),
			nl2br(h($row['Registro']['obs']))
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', array(
	'class' => 'registros',
	'headers' => $headers,
	'rows' => $rows,
	'tasks' => false
));
