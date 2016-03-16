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
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

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
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'method' => 'orConditions',
			'type' => 'query'
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'docente' => 'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
	);

/**
 * Genera condiciones de búsqueda
 *
 * @param array $data Datos a buscar
 *
 * @return array
 */
	public function orConditions($data = array()) {
		$condition['OR'] = array();
		$value = current($data);
		$fields = array(
			'Carrera.nombre', 'Materia.nombre', 'Usuario.nombre', 'Usuario.apellido',
			'CONCAT(Usuario.nombre, " ", Usuario.apellido)', 'Registro.obs'
		);

		foreach ($fields as $field) {
			$condition['OR'][$field . ' LIKE'] = '%' . $value . '%';
		}

		if ((bool)preg_match('/(\d{2})\/(\d{2})\/(\d{4})$/', $value)) {
			$date = date_create_from_format('d/m/Y', $value);
			if ($date) {
				$condition['OR']['Registro.fecha LIKE'] = '%' . $date->format('Y-m-d') . '%';
			}
		}
		if (!isset($condition['OR']['Registro.fecha LIKE'])) {
			$condition['OR']['Registro.fecha LIKE'] = '%' . $value . '%';
		}

		return $condition;
	}
}
