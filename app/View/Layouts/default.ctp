<?php
/**
 * Diseño predeterminado
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
?>
<!DOCTYPE html>
<html dir="ltr" lang="es">
	<head>
		<meta charset="utf-8" />
		<title><?php echo h($title_for_layout) ?> :: Sistema de Registro de Asistencia y Temas</title>

		<meta name="application-name" content="Sistema de Registro de Asistencia y Temas" />
		<meta name="author" content="Facultad Regional Delta" />

		<?php
		echo $this->fetch('meta');
		echo $this->Html->meta('icon');

		echo $this->Html->css(array(
			'bootstrap.min',
			'select2.min',
			'layout',
			'notify',
			'form',
			'table',
			'debug'
		));

		echo $this->Html->script(array(
			'jquery.min',
			'bootstrap.min',
			'select2.min',
			'select2_locale_es.min',
			'app',
			'form',
			'table'
		));

		echo $this->fetch('css');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<div class="page-wrapper">
			<?php echo $this->element('navbar') ?>

			<div class="page-container">
				<div class="notifications">
					<?php
					echo $this->Flash->render('auth');
					echo $this->Flash->render();
					?>
				</div>

				<div class="clearfix crumbs">
					<?php
					echo $this->Html->getCrumbList(
						array('class' => 'breadcrumb', 'lastClass' => 'active', 'separator' => '<span class="divider">&gt;</span>'),
						'Inicio'
					);
					?>

					<div class="date">
						<?php
						echo preg_replace_callback(
							"/[a-zA-Záéíóú]{3,}/u",
							function ($m) {
								return ucfirst($m[0]);
							},
							CakeTime::format(time(), '%A %d de %B de %Y, %H:%M')
						);
						?>
					</div>
				</div>

				<div class="page-content">
					<?php
					if (isset($tasks)):
						echo $this->Html->generateLinkList($tasks, array('class' => 'nav nav-pills page-tasks'));
					endif
					?>

					<h3 class="page-header">
						<?php echo (!empty($title_for_view) ? h($title_for_view) : h($title_for_layout)) ?>
					</h3>

					<div class="page-view clearfix">
						<?php echo $this->fetch('content') ?>
					</div>
				</div>
			</div>
		</div>

		<noscript class="noscript">
			La experiencia con esta aplicación puede verse afectada debido a que JavaScript está deshabilitado.
		</noscript>

		<?php echo $this->element('sql_dump') ?>
	</body>
</html>
