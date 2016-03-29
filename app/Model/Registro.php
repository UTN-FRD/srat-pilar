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
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'asignatura_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Asignatura'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'usuario_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Usuario'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'entrada' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validTime' => array(
				'rule' => 'time',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'salida' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validTime' => array(
				'rule' => 'time',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'obs' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 65535),
				'message' => 'El valor ingresado es demasiado grande'
			)
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
 * beforeValidate
 *
 * @param array $options Opciones
 *
 * @return bool `true` para continuar la operación de validación o `false` para cancelarla
 */
	public function beforeValidate($options = array()) {
		if (empty($this->data[$this->alias]['check'])) {
			$this->data = $this->validate = array();
		} else {
			if (!isset($this->data[$this->alias]['id'])) {
				if (!empty($this->data[$this->alias]['obs'])) {
					$this->data[$this->alias]['entrada'] = date('H:i:s');
				} else {
					$this->data = $this->validate = array();
				}
			}
		}

		return true;
	}

/**
 * beforeSave
 *
 * @param array $options Opciones
 *
 * @return bool `true` para continuar la operación de guardado o `false` para cancelarla
 */
	public function beforeSave($options = array()) {
		if (!isset($this->data[$this->alias]['id'])) {
			$this->data[$this->alias]['salida'] = null;
		} else {
			$this->data[$this->alias]['salida'] = date('H:i:s');
		}

		return true;
	}

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
