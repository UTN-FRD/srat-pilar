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
 * Carreras
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class CarrerasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'nombre', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('nombre' => 'asc')
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
			'conditions' => $this->Carrera->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Carreras - Administrar',
			'title_for_view' => 'Carreras'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Carrera->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->Carrera->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->set(array(
			'title_for_layout' => 'Agregar - Carreras - Administrar',
			'title_for_view' => 'Agregar carrera'
		));
	}

/**
 * Editar
 *
 * @param int|null $id Identificador
 *
 * @return void
 *
 * @throws NotFoundException Si el registro no existe
 */
	public function admin_editar($id = null) {
		$this->Carrera->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Carrera->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Carrera->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->Carrera->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Carrera->read(array('id', 'nombre', 'obs'));
		}

		$this->set(array(
			'associated' => $this->Carrera->hasAssociations(),
			'title_for_layout' => 'Editar - Carreras - Administrar',
			'title_for_view' => 'Editar carrera'
		));
	}

/**
 * Eliminar
 *
 * @param int|null $id Identificador
 *
 * @return void
 *
 * @throws MethodNotAllowedException Si el método es diferente de DELETE
 * @throws NotFoundException Si el registro no existe o corresponde al primer usuario
 */
	public function admin_eliminar($id = null) {
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException;
		}

		$this->Carrera->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Carrera->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->Carrera->hasAssociations()) {
			$notify = 'record_delete_associated';
		} else {
			if ($this->Carrera->delete()) {
				$notify = 'record_deleted';
			}
		}
		$this->_notify($notify);
	}
}
