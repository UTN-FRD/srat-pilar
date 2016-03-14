<?php
/**
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
 * Dependencias
 */
App::uses('AppModel', 'Model');

/**
 * Registro
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Registro extends AppModel {

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Asignatura',
		'Usuario'
	);

/**
 * hasOne
 *
 * @var array
 */
	public $hasOne = array(
		'Carrera' => array(
			'conditions' => 'Carrera.id = Asignatura.carrera_id',
			'foreignKey' => false
		),
		'Materia' => array(
			'conditions' => 'Materia.id = Asignatura.materia_id',
			'foreignKey' => false
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'asignatura' => 'CONCAT(Carrera.nombre, ": ", Materia.nombre)',
		'docente' => 'CONCAT(Usuario.apellido, ", ", Usuario.nombre)'
	);
}
