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
 * Cargos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class CargosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'Carrera.nombre', 'Materia.nombre', 'docente'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('Materia.nombre' => 'asc'),
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
			'conditions' => $this->Cargo->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Cargos - Administrar',
			'title_for_view' => 'Cargos'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Cargo->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect($this->request->here);
			} elseif (empty($this->Cargo->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		$this->set(array(
			'asignaturas' => $this->Cargo->Asignatura->find('list'),
			'usuarios' => $this->Cargo->Usuario->find('list'),
			'title_for_layout' => 'Agregar - Cargos - Administrar',
			'title_for_view' => 'Agregar cargo'
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
		$this->Cargo->id = $id;
		if (!$this->Cargo->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Cargo->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect(array('action' => 'index'));
			} elseif (empty($this->Cargo->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Cargo->read(array('id', 'asignatura_id', 'usuario_id'));
		}

		$this->set(array(
			'asignaturas' => $this->Cargo->Asignatura->find('list'),
			'usuarios' => $this->Cargo->Usuario->find('list'),
			'title_for_layout' => 'Editar - Cargos - Administrar',
			'title_for_view' => 'Editar cargo'
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

		$this->Cargo->id = $id;
		if (!$this->Cargo->exists()) {
			throw new NotFoundException;
		}

		if ($this->Cargo->delete()) {
			$this->Flash->success('La operación solicitada se ha completado exitosamente.');
		} else {
			$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
		}
		$this->redirect(array('action' => 'index'));
	}
}
