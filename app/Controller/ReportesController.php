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
 * Reportes
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class ReportesController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'RequestHandler'
	);

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		if (!empty($this->request->named['reset'])) {
			$this->Session->delete($this->_getSessionKey());
			$this->redirect(array('action' => 'index'));
		}

		$options = $this->Session->read($this->_getSessionKey('Options'));
		if (!$options) {
			$options = array(
				'data' => array()
			);
		}
		$carreras = $this->Reporte->Registro->getCarrerasList();

		if ($this->request->is('post')) {
			if (empty($carreras)) {
				$this->Flash->info('No se han encontrado registros.');
				$this->redirect(array('action' => 'index'));
			}

			$this->Reporte->create($this->request->data);
			if ($this->Reporte->validates()) {
				$options['data'] = $this->request->data['Reporte'];
			} else {
				$options['data'] = array();
			}
		}

		if (!$this->request->data) {
			if ($options['data']) {
				$this->request->data['Reporte'] = $options['data'];
			} else {
				$this->request->data = array(
					'Reporte' => array(
						'carrera_id' => key($carreras),
						'periodo' => array(
							'day' => '01',
							'month' => date('m'),
							'year' => date('Y')
						)
					)
				);
				$options['data'] = $this->request->data['Reporte'];
			}
		}

		$periodo = strtotime(implode('-', array_reverse($options['data']['periodo'])));
		$findOptions = array(
			'conditions' => array(
				'Asignatura.carrera_id' => $options['data']['carrera_id'],
				'Registro.entrada IS NOT NULL',
				'Registro.salida IS NOT NULL',
				'Registro.fecha >=' => date('Y-m-01', $periodo),
				'Registro.fecha <=' => date('Y-m-t', $periodo)
			),
			'fields' => array(
				'asignatura_id', 'usuario_id', 'Materia.nombre', 'docente',
				'sec_to_time(sum(time_to_sec(salida) - time_to_sec(entrada))) as horas'
			),
			'group' => array('asignatura_id', 'usuario_id'),
			'recursive' => 0
		);

		$this->set(array(
			'carreras' => $carreras,
			'rows' => $this->Reporte->Registro->find('all', $findOptions),
			'title_for_layout' => 'Asistencia - Reportes',
			'title_for_view' => 'Asistencia'
		));

		if ($this->request->ext === 'pdf') {
			if (empty($options['data']['carrera_id'])) {
				$this->Flash->info('No es posible crear un reporte vacío.');
				$this->redirect(array('action' => 'index'));
			}
			$this->_setupCakePdf('Reporte de Asistencia');
			$this->set(array(
				'data' => $options['data']
			));

			try {
				$this->render();
			} catch (Exception $e) {
				$this->Flash->error('No fue posible exportar el resultado debido a un error interno.');
				$this->redirect(array('action' => 'index'));
			}
		} else {
			$this->Session->write($this->_getSessionKey('Options'), $options);
		}
	}

/**
 * Genera una clave de sesión incluyendo el nombre de la acción actual como prefijo
 *
 * @param string $key Clave
 *
 * @return string Clave
 */
	protected function _getSessionKey($key = '') {
		return sprintf('Reporte.%s%s',
			Inflector::camelize($this->request->action),
			(!empty($key) ? ".$key" : "")
		);
	}

/**
 * Establece la configuración inicial de CakePdf y opciones comunes para los reportes
 *
 * @param string $title Título
 * @param string $orientation Orientación
 *
 * @return void
 */
	protected function _setupCakePdf($title = 'Reporte', $orientation = 'landscape') {
		$this->autoRender = false;

		$isWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$charset = (!$isWindows ? 'ASCII' : 'ISO-8859-1');
		$date = preg_replace_callback(
			"/[a-zA-Záéíóú]{3,}/u",
			function ($m) {
				return ucfirst($m[0]);
			},
			CakeTime::format(time(), '%A %d de %B de %Y')
		);

		$this->pdfConfig = array(
			'engine' => 'CakePdf.WkHtmlToPdf',
			'options' => array(
				'dpi' => 96,
				'footer-center' => iconv('UTF-8', $charset . '//TRANSLIT', 'Página [frompage] de [topage]'),
				'footer-font-name' => 'Arial',
				'footer-font-size' => '9',
				'footer-line' => false,
				'header-center' => $title,
				'header-font-name' => 'Arial',
				'header-font-size' => '9',
				'header-left' => 'Sistema de Registro de Asistencia y Temas',
				'header-line' => true,
				'header-right' => iconv('UTF-8', $charset . '//TRANSLIT', $date),
				'outline' => true,
				'print-media-type' => false
			),
			'margin' => array(
				'bottom' => 5,
				'left' => 3,
				'right' => 3,
				'top' => 5
			),
			'orientation' => $orientation,
			'page-size' => 'A4'
		);

		if (!Configure::check('CakePdf.binary') && !$isWindows) {
			$this->pdfConfig['binary'] = trim(shell_exec('which wkhtmltopdf'));
		}
	}
}
