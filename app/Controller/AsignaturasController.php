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
 * Asignaturas
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class AsignaturasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'Carrera.nombre', 'Materia.nombre'),
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
			'conditions' => $this->Asignatura->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Asignaturas - Administrar',
			'title_for_view' => 'Asignaturas'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Asignatura->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect($this->request->here);
			} elseif (empty($this->Asignatura->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		$this->set(array(
			'carreras' => $this->Asignatura->Carrera->find('list'),
			'materias' => $this->Asignatura->Materia->find('list'),
			'title_for_layout' => 'Agregar - Asignaturas - Administrar',
			'title_for_view' => 'Agregar asignatura'
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
		$this->Asignatura->id = $id;
		if (!$this->Asignatura->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Asignatura->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect(array('action' => 'index'));
			} elseif (empty($this->Asignatura->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Asignatura->read(array('id', 'carrera_id', 'materia_id'));
		}

		$this->set(array(
			'associated' => $this->Asignatura->hasAssociations(),
			'carreras' => $this->Asignatura->Carrera->find('list'),
			'materias' => $this->Asignatura->Materia->find('list'),
			'title_for_layout' => 'Editar - Asignaturas - Administrar',
			'title_for_view' => 'Editar asignatura'
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

		$this->Asignatura->id = $id;
		if (!$this->Asignatura->exists()) {
			throw new NotFoundException;
		}

		if ($this->Asignatura->hasAssociations()) {
			$this->Flash->warning('La operación solicitada no se ha completado debido a que el registro se encuentra asociado.');
		} else {
			if ($this->Asignatura->delete()) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}
		$this->redirect(array('action' => 'index'));
	}
}
