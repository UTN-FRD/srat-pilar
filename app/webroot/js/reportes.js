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

	if ( $( "#ReporteCarreraId" ).is( ":disabled" ) ) {
		$( ".field-period" ).attr( "disabled", true );

		$( "#ReporteAdminIndexForm" ).on( "submit", function() {
			$( ".field-period", this ).attr( "disabled", false )
			.attr( "readonly", true );

			return true;
		} );
	}

	$( "#ReporteCarreraId:enabled" ).on( "change", function() {
		$( "#ReporteAdminIndexForm" ).submit();
	} );
} );
