<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Dependencias
 */
App::uses('AppShell', 'Console');

/**
 * Registros
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class RegistrosShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Registro');
}
