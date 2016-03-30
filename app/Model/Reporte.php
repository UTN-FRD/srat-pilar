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
 * Reporte
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Reporte extends AppModel {

/**
 * Tabla
 *
 * @var bool|string
 */
	public $useTable = false;

/**
 * Esquema
 *
 * @var array
 */
	protected $_schema = array(
		'carrera_id' => array(
			'default' => null,
			'length' => 10,
			'null' => false,
			'type' => 'integer'
		),
		'periodo' => array(
			'default' => null,
			'length' => null,
			'null' => false,
			'type' => 'date'
		)
	);

/**
 * hasOne
 *
 * @var array
 */
	public $hasOne = array(
		'Registro' => array(
			'foreignKey' => false
		),
		'Carrera' => array(
			'foreignKey' => false
		)
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
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Carrera'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'periodo' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'valid' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'El período seleccionado no es válido'
			)
		)
	);
}
