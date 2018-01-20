<?php
	//verificar si hay una sesión abierta, devuelve 1 si es asi, de lo contrario no devuelve nada.
	session_start();

	if(!isset($_SESSION['id'])){
		echo 0;
		die();
	}
?>

<header>
	<nav class="navbar custom-nav navbar-inverse navbar-static-top" role="navigation">
		<div class="container">
			<!-- amburguesa -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navegacion-fm">
					<span class="sr-only">desplegar / ocultar menu</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="index.html" class="navbar-brand"><?php echo $_SESSION["usuario"]; ?></a>
			</div>
			<!--inicia menu -->
			<div class="collapse navbar-collapse" id="navegacion-fm">
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" rele="button">
							Categorias <span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#" id="inscripciones">Inscripciones</a></li>
							<li class="divider"></li>
							<li><a href="#" id="asignaciones">Asignaciones</a></li>
						</ul>
					</li>
				</ul>

				<ul class="nav navbar-nav navbar-right" style="display: inline-block; float: left;">
						<li><a href="php/cerrar_sesion.php">cerrar sesión</a></li>
				</ul>
				
				<!-- contenido a la derecha -->
				<div class="navbar-right">
					<!-- busqueda -->
					<form action="" class="navbar-form" >
						<input type="button" class="btn btn-primary ocultar" value="Generar PDF" id="generarPdf">
					</form>
				</div>	

			</div>
		</div>
	</nav>
</header>

<!-- Inscripcion alumnos -->
<div class="container contenido" id="tabes">

</div>

<!-- Footer -->
<div class="footer">
	© 2018 Alfredo Monroy. All rights reserved.
</div>
