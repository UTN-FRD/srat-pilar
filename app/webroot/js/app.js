/*!
 * Aplicación
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
	if ( $( "a.logout" ).length === 1 && $( ".admin-users" ).length === 0 ) {
		var idleCounter = 0,
		idleInterval = window.setInterval( function() {
			if ( ++idleCounter >= 120 ) {
				window.clearInterval( idleInterval );
				window.location = $( "a.logout" ).attr( "href" );
			}
		}, 1000 );

		$( document ).on( "keypress mousedown scroll wheel", function() {
			idleCounter = 0;
		} );

		var lastClientX = 0, lastClientY = 0;
		$( document ).on( "mousemove", function( e ) {
			if ( lastClientX !== e.clientX || lastClientY !== e.clientY ) {
				idleCounter = 0;
			}
			lastClientX = e.clientX;
			lastClientY = e.clientY;
		} );
	}
} );
