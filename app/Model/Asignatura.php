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
 * Asignatura
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Asignatura extends AppModel {

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
		'Carrera',
		'Materia'
	);

/**
 * Nombre del campo utilizado
 * por el tipo de búsqueda `list`
 *
 * @var string
 */
	public $displayField = 'asignatura';

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array('Carrera.nombre', 'Materia.nombre'),
			'type' => 'like'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo',
		'Registro'
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'carrera_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Carrera'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'materia_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Materia'),
				'last' => true,
				'message' => 'El valor seleccionado no existe'
			),
			'isUnique' => array(
				'rule' => array('validateUnique', array('carrera_id')),
				'message' => 'La materia seleccionada ya se encuentra asociada a la carrera seleccionada'
			)
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'asignatura' => 'CONCAT(Carrera.nombre, ": ", Materia.nombre)'
	);

/**
 * Método auxiliar para manejar el antes y después de la operación find('list')
 *
 * @param string $state Estado de la búsqueda, 'before' o 'after'
 * @param array $query Opciones de la consulta
 * @param array $results Resultado de la consulta
 *
 * @return array Opciones de la consulta si `$state` es igual a 'before' o el resultado
 * de la consulta en caso contrario
 */
	protected function _findList($state, $query, $results = array()) {
		if ($state === 'before') {
			if ($this->displayField === 'asignatura') {
				if (empty($query['order'])) {
					$query['order'] = array('Materia.nombre' => 'asc');
				}
				$query['recursive'] = 0;
			}
		}
		return parent::_findList($state, $query, $results);
	}
}
