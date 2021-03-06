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
 * Usuarios
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UsuariosController extends AppController {

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
			'order' => array('legajo' => 'asc')
		)
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->Auth->user()) {
			if (in_array($this->request->action, array('dashboard', 'logout'))) {
				$this->Auth->authError = false;
			} elseif ($this->request->action === 'login') {
				$this->Security->csrfCheck = false;
			}
			$this->Auth->allow('docentes', 'login');
		} else {
			if ($this->Auth->user('reset')) {
				if (!in_array($this->request->action, array('login', 'logout', 'restablecer'))) {
					$this->redirect(array('action' => 'restablecer', 'admin' => false));
				}
			}
		}
	}

/**
 * Dashboard
 *
 * @return void
 */
	public function dashboard() {
		if ($this->request->is('post')) {
			$data = $this->request->data['Registro'];
			if ($this->Usuario->Registro->validateMany($data)) {
				$data = array_filter($data);
				if (!empty($data)) {
					if ($this->Usuario->Registro->saveMany($data, array('validate' => false))) {
						$this->Flash->success('La operación solicitada se ha completado exitosamente.');
						$this->redirect($this->request->here);
					} else {
						$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
					}
				} else {
						$this->Flash->info('La operación se ha completado exitosamente pero no se han realizado cambios.');
						$this->redirect($this->request->here);
				}
			} elseif (!empty($this->Usuario->Registro->validationErrors)) {
				$this->Flash->warning('La operación no se ha completado debido a que uno o más valores no son válidos.');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Usuario->getCargos($this->Auth->user('id'));
		}

		$this->set('title_for_layout', 'Asignaturas');
	}

/**
 * Inicio de sesión
 *
 * @return void
 */
	public function login() {
		if ($this->Auth->user()) {
			$this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}

			$this->request->data['Usuario']['password'] = null;
			$this->Auth->flash('Los datos ingresados no son correctos.');
		}

		$this->set('title_for_layout', 'Inicio de sesión');
	}

/**
 * Cierre de sesión
 *
 * @return void
 */
	public function logout() {
		$logoutRedirect = $this->Auth->logout();
		foreach (array_keys($this->Session->read()) as $key) {
			$this->Session->delete($key);
		}
		$this->redirect($logoutRedirect);
	}

/**
 * Docentes
 *
 * @return void
 */
	public function docentes() {
		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => array_merge(array('id >' => 1, $this->Usuario->parseCriteria($this->Prg->parsedParams()))),
			'fields' => array('apellido', 'legajo', 'nombre')
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Docentes',
			'title_for_view' => 'Docentes'
		));
	}

/**
 * Perfil
 *
 * @return void
 */
	public function perfil() {
		if ($this->request->is('put')) {
			$this->Usuario->whitelist = array('apellido', 'nombre', 'old_password', 'password');
			if ($this->Usuario->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect($this->request->here);
			} elseif (empty($this->Usuario->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		if (!$this->request->data) {
			$this->Usuario->id = $this->Auth->user('id');
			$this->request->data = $this->Usuario->read(array('id', 'apellido', 'legajo', 'nombre'));
		}

		$this->set(array(
			'title_for_layout' => 'Perfil',
			'title_for_view' => 'Perfil'
		));
	}

/**
 * Restablecer contraseña
 *
 * @return void
 */
	public function restablecer() {
		if (!$this->Auth->user('reset')) {
			$this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('put')) {
			$this->Usuario->whitelist = array('password', 'reset');
			if ($this->Usuario->save($this->request->data)) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
				$this->redirect(array('action' => 'dashboard'));
			} elseif (empty($this->Usuario->validationErrors)) {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}

		if (!$this->request->data) {
			$this->request->data['Usuario'] = array(
				'id' => $this->Auth->user('id'),
				'reset' => 0
			);
		}

		$this->set(array(
			'title_for_layout' => 'Restablecer contraseña',
			'title_for_view' => 'Restablecer contraseña'
		));
	}

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Usuario->parseCriteria($this->Prg->parsedParams()),
			'fields' => array('id', 'admin', 'apellido', 'estado', 'legajo', 'nombre', 'acceso')
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Usuarios - Sistema',
			'title_for_view' => 'Usuarios'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if (empty($this->request->data['refresh'])) {
				if ($this->Usuario->save($this->request->data)) {
					$this->Flash->success('La operación solicitada se ha completado exitosamente.');
					$this->redirect($this->request->here);
				} elseif (empty($this->Usuario->validationErrors)) {
					$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
				}
			} else {
				unset($this->request->data['Usuario']['password']);
			}
		}

		$this->set(array(
			'title_for_layout' => 'Agregar - Usuarios - Sistema',
			'title_for_view' => 'Agregar usuario'
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
		$this->Usuario->id = $id;
		if (!$this->Usuario->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if (empty($this->request->data['refresh'])) {
				$user = $this->Usuario->save($this->request->data);
				if ($user) {
					if ($user['Usuario']['id'] == $this->Auth->user('id')) {
						if (!$user['Usuario']['estado']) {
							return $this->redirect(
								array('controller' => 'usuarios', 'action' => 'logout', 'admin' => false)
							);
						}
					}
					$this->Flash->success('La operación solicitada se ha completado exitosamente.');
					$this->redirect(array('action' => 'index'));
				} elseif (empty($this->Usuario->validationErrors)) {
					$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
				}
			} else {
				unset($this->request->data['Usuario']['password']);
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Usuario->read(array(
				'id', 'admin', 'reset', 'apellido', 'estado', 'legajo', 'nombre'
			));
		}

		$this->set(array(
			'title_for_layout' => 'Editar - Usuarios - Sistema',
			'title_for_view' => 'Editar usuario'
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

		$this->Usuario->id = $id;
		if ((int)$id == 1 || !$this->Usuario->exists()) {
			throw new NotFoundException;
		}

		if ($this->Usuario->hasAssociations()) {
			$this->Flash->warning('La operación solicitada no se ha completado debido a que el registro se encuentra asociado.');
		} else {
			if ($this->Usuario->delete()) {
				$this->Flash->success('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->Flash->warning('La operación solicitada no se ha completado debido a un error inesperado.');
			}
		}
		$this->redirect(array('action' => 'index'));
	}

/**
 * afterIdentify
 *
 * @param CakeEvent $event Evento
 *
 * @return void
 */
	public function afterIdentify($event) {
		$this->Usuario->id = $event->data['user']['id'];
		$this->Usuario->saveField('acceso', date("Y-m-d H:i:s"));
	}

/**
 * Devuelve una lista de todos los eventos que se dispararán en el controlador durante su ciclo de vida
 *
 * @return array
 */
	public function implementedEvents() {
		return parent::implementedEvents() + array(
			'Auth.afterIdentify' => 'afterIdentify'
		);
	}
}
