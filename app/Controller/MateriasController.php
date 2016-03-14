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
 * Materias
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class MateriasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
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
			'conditions' => $this->Materia->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Materias - Administrar',
			'title_for_view' => 'Materias'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Materia->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect($this->request->here);
			} elseif (empty($this->Materia->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		$this->set(array(
			'title_for_layout' => 'Agregar - Materias - Administrar',
			'title_for_view' => 'Agregar materia'
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
		$this->Materia->id = $id;
		if (!$this->Materia->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Materia->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect(array('action' => 'index'));
			} elseif (empty($this->Materia->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Materia->read();
		}

		$this->set(array(
			'associated' => $this->Materia->hasAssociations(),
			'title_for_layout' => 'Editar - Materias - Administrar',
			'title_for_view' => 'Editar materia'
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

		$this->Materia->id = $id;
		if (!$this->Materia->exists()) {
			throw new NotFoundException;
		}

		if ($this->Materia->hasAssociations()) {
			$this->Flash->warning('La operación solicitada no se ha completado debido a que el registro se encuentra asociado.');
		} else {
			if ($this->Materia->delete()) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}
		$this->redirect(array('action' => 'index'));
	}
}
