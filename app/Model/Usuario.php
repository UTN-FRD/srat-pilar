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
App::uses('AuthComponent', 'Controller/Component');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Usuario
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Usuario extends AppModel {

/**
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * Nombre del campo utilizado
 * por el tipo de búsqueda `list`
 *
 * @var string
 */
	public $displayField = 'nombre_completo';

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array('legajo', 'apellido', 'nombre'),
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
		'legajo' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'range' => array(
				'rule' => array('range', 0, 16777216),
				'last' => true,
				'message' => 'El valor ingresado no es válido'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'El valor ingresado ya se encuentra en uso'
			)
		),
		'old_password' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validatePassword' => array(
				'rule' => 'validatePassword',
				'message' => 'El valor ingresado no es correcto'
			)
		),
		'password' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'format' => array(
				'rule' => '/^.*(?=.{6,})(?=.*\d)(?=.*[ÁÉÍÓÚÑáéíóúñA-Za-z]).*$/u',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'new_password' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'format' => array(
				'rule' => '/^.*(?=.{6,})(?=.*\d)(?=.*[ÁÉÍÓÚÑáéíóúñA-Za-z]).*$/u',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'reset' => array(
			'rule' => array('inList', array('0', '1')),
			'required' => true,
			'allowEmpty' => true,
			'message' => ''
		),
		'admin' => array(
			'rule' => array('inList', array('0', '1')),
			'required' => true,
			'allowEmpty' => true,
			'message' => ''
		),
		'estado' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('inList', array('0', '1')),
				'message' => 'El valor seleccionado no es válido'
			)
		),
		'apellido' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 25),
				'message' => 'El valor de este campo no debe superar los 25 caracteres'
			)
		),
		'nombre' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 40),
				'message' => 'El valor de este campo no debe superar los 40 caracteres'
			)
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'nombre_completo' => 'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
	);

/**
 * beforeValidate
 *
 * @param array $options Opciones
 *
 * @return bool `true` para continuar la operación de validación o `false` para cancelarla
 */
	public function beforeValidate($options = array()) {
		if (!$this->id) {
			$this->validator()->getField('new_password')->getRule('notBlank')->required = false;
			$this->validator()->getField('old_password')->getRule('notBlank')->required = false;
		} else {
			if (!isset($this->data[$this->alias]['old_password']) && !isset($this->data[$this->alias]['new_password'])) {
				$this->validator()->getField('new_password')->getRule('notBlank')->required = false;
				$this->validator()->getField('old_password')->getRule('notBlank')->required = false;

				if (empty($this->data[$this->alias]['password'])) {
					$this->validator()->getField('password')->getRule('notBlank')->allowEmpty = true;
				}
			} elseif (isset($this->data[$this->alias]['old_password'])) {
				$this->validator()->getField('new_password')->getRule('notBlank')->required = false;

				if (empty($this->data[$this->alias]['old_password']) && empty($this->data[$this->alias]['password'])) {
					$this->validator()->getField('old_password')->getRule('notBlank')->allowEmpty = true;
					$this->validator()->getField('password')->getRule('notBlank')->allowEmpty = true;
				}
			} else {
				$this->validator()->getField('old_password')->getRule('notBlank')->required = false;
				$this->validator()->getField('password')->getRule('notBlank')->required = false;
			}
		}

		if (!empty($this->data[$this->alias]['reset'])) {
			$this->data[$this->alias]['password'] = 'abc12345';
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
		if (!empty($this->data[$this->alias]['new_password'])) {
			$this->data[$this->alias]['password'] = $this->data[$this->alias]['new_password'];
			unset($this->data[$this->alias]['new_password']);
		}

		if (!empty($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = (new BlowfishPasswordHasher())->hash(
				$this->data[$this->alias]['password']
			);
		} else {
			unset($this->data[$this->alias]['password']);
		}

		return true;
	}

/**
 * afterSave
 *
 * @param bool $created Indica si se ha creado un registro
 * @param array $options Opciones
 *
 * @return void
 */
	public function afterSave($created, $options = array()) {
		if (!$created && $this->id == AuthComponent::user('id')) {
			$user = $this->read();
			unset($user[$this->alias]['password']);
			CakeSession::write(AuthComponent::$sessionKey, $user[$this->alias]);
		}
	}

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

/**
 * Valida que un valor coincide con la contraseña actual de un usuario
 *
 * @param array $check Nombre del campo y su valor
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function validatePassword($check) {
		if (!empty($check)) {
			return (new BlowfishPasswordHasher())->check(
				current($check),
				$this->field('password')
			);
		}
		return false;
	}

/**
 * Obtiene todos los cargos asociados a un usuario
 *
 * @param mixed $id Identificador
 *
 * @return array Cargos
 */
	public function getCargos($id = null) {
		$out = array();

		if ($id) {
			if (is_array($id)) {
				$id = $id[0];
			}
			$this->id = $id;
		}
		$id = $this->id;

		if ($id && $this->exists()) {
			$records = array();
			$this->Registro->virtualFields = array();
			$rows = $this->Registro->find('all', array(
				'conditions' => array(
					'Registro.fecha' => date('Y-m-d'),
					'Registro.usuario_id' => $id
				)
			));
			foreach ($rows as $row) {
				$records[$row['Registro']['asignatura_id']] = current($row);
			}

			$this->Cargo->virtualFields = array();
			$rows = $this->Cargo->find('all', array(
				'conditions' => array(
					'Cargo.usuario_id' => $id
				),
				'fields' => array(
					'Asignatura.*',
					'Carrera.nombre',
					'Materia.nombre'
				),
				'recursive' => 0
			));
			foreach ($rows as $rid => $row) {
				$asignaturaId = $row['Asignatura']['id'];
				if (!isset($records[$asignaturaId])) {
					$out['Registro'][$rid] = array(
						'asignatura_id' => $asignaturaId,
						'entrada' => null,
						'fecha' => date('Y-m-d'),
						'obs' => null,
						'salida' => null,
						'usuario_id' => $id
					);
				} else {
					$out['Registro'][$rid] = $records[$asignaturaId];
				}
				$out['Registro'][$rid] += array(
					'asignatura' => sprintf('%s: %s', $row['Carrera']['nombre'], $row['Materia']['nombre']),
					'check' => (isset($records[$asignaturaId]) && empty($out['Registro'][$rid]['salida']))
				);
			}
		}

		return $out;
	}
}
