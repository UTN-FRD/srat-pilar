/*!
 * Reportes
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo LICENCIA.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

$( function() {
	$( ".report-preview" ).removeClass( "table-row-numbers" );

	$( "#ReporteCarreraId:enabled" ).on( "change", function() {
		$( "#ReporteAdminIndexForm" ).submit();
	} );
} );
