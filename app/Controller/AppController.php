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
App::uses('Controller', 'Controller');

/**
 * Controlador de la aplicación
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class AppController extends Controller {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Security' => array('blackHoleCallback' => 'blackHole'),
		'Session',
		'Flash',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'legajo'),
					'passwordHasher' => 'Blowfish',
					'recursive' => -1,
					'scope' => array('estado' => 1),
					'userModel' => 'Usuario'
				)
			),
			'authError' => 'La operación solicitada ha sido rechazada debido a que no cuenta con suficientes privilegios.',
			'authorize' => 'Controller',
			'flash' => array(
				'element' => 'error',
				'key' => 'auth'
			),
			'loginAction' => array('controller' => 'usuarios', 'action' => 'login', 'admin' => false, 'plugin' => false),
			'loginRedirect' => array('controller' => 'usuarios', 'action' => 'dashboard', 'admin' => false, 'plugin' => false),
			'logoutRedirect' => array('controller' => 'usuarios', 'action' => 'login', 'admin' => false, 'plugin' => false),
			'unauthorizedRedirect' => array('controller' => 'usuarios', 'action' => 'dashboard', 'admin' => false, 'plugin' => false)
		)
	);

/**
 * Ayudantes
 *
 * @var array
 */
	public $helpers = array(
		'Flash',
		'Form' => array('className' => 'MyForm'),
		'Html' => array('className' => 'MyHtml'),
		'Session'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->response->disableCache();

		if ($this->Auth->user()) {
			if ($this->Auth->user('reset') && $this->request->controller !== 'usuarios') {
				$this->redirect(array(
					'controller' => 'usuarios', 'action' => 'restablecer', 'admin' => false, 'plugin' => false
				));
			}
		}
	}

/**
 * Comprueba si un usuario tiene acceso a las acciones de un controlador
 *
 * @param array $user Datos del usuario
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function isAuthorized($user = null) {
		if ($this->request->prefix === 'admin') {
			return (bool)$user['admin'];
		}
		return true;
	}

/**
 * Responde a solicitudes invalidadas por el componente Security
 *
 * @param null|string $type Tipo de error
 *
 * @return void
 */
	public function blackHole($type = null) {
		$this->Flash->error('Se ha rechazado la solicitud debido a que los datos recibidos no son válidos.');
		$this->redirect('');
	}
}
