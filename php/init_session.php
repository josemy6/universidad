<?php
	//verificar si hay una sesión abierta, devuelve 1 si es asi, de lo contrario no devuelve nada.
	session_start();

	if($_SESSION['id'] == null){

	}else{
		echo 1;
	}

?>