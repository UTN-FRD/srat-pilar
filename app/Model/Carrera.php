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
 * Carrera
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Carrera extends AppModel {

/**
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array('nombre', 'obs'),
			'type' => 'like'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Asignatura'
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'nombre' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'last' => true,
				'message' => 'El valor de este campo no debe superar los 50 caracteres'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'El valor ingresado ya se encuentra en uso'
			)
		),
		'obs' => array(
			'rule' => array('maxLength', 255),
			'required' => true,
			'allowEmpty' => true,
			'message' => 'El valor de este campo no debe superar los 255 caracteres'
		)
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
			if (empty($query['order'])) {
				$query['order'] = array('nombre' => 'asc');
			}
		}
		return parent::_findList($state, $query, $results);
	}
}
