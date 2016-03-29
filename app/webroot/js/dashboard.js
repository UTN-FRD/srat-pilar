/*!
 * Dashboard
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
	if ( $( ".form-asignaturas .table" ).length < 2 ) {
		$( "body" ).addClass( "page-small" );
	}

	$( ".form-asignaturas" ).on( "submit", function() {
		$( "textarea", this ).attr( "disabled", false )
		.attr( "readonly", true );

		return true;
	} );

	$( ".form-asignaturas input[type=checkbox]" ).each( function() {
		$( this ).on( "change", function() {
			$( this ).parents( "tbody" )
			.find( "textarea" )
			.attr( "disabled", !this.checked )
			.attr( "readonly", !this.checked );
		} );

		$( this ).trigger( "change" );
	} );
} );
