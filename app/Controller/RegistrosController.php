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
App::uses('AppController', 'Controller');

/**
 * Registros
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class RegistrosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('Carrera.nombre', 'Materia.nombre', 'docente', 'fecha', 'entrada', 'salida', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('fecha' => 'desc'),
			'recursive' => 0
		)
	);

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Registro->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Registros',
			'title_for_view' => 'Registros'
		));
	}
}
