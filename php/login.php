<?php
	require "conexion.php";
	session_start();

	$idUsuario = 0;

	$usuario = $_POST['usuario'];
	$password = $_POST['password'];

	if($usuario!=null && $password!=null){
		$objConexion = new Conexion();
		$objConexion->establecerConexion();
		$sql = "select id from usuarios where usuario = '$usuario' and password = '$password'";	
		$resultado = $objConexion->consulta($sql);

		if($resultado){
			while($fila = $resultado->fetch_assoc()){
				$idUsuario = $fila['id'];
			}
			if($idUsuario > 0){
				$_SESSION['id'] = $idUsuario;
				$_SESSION['usuario'] = $usuario;
				echo "1";
			}else{
				echo "Usuario o contraseña invalido.";
			}
		}else{
			echo "error | verifique los datos de entrada";
		}

		$objConexion->liberarResult();
		$objConexion->cerrar();

	}else{
		echo "Error, verifique los datos de entrada";
	}	



?>