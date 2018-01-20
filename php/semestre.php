<?php

	require "conexion.php";

	class Semestre{

		function Semestre(){
			$objConexion = new Conexion();
			if($objConexion->establecerConexion()){
				$sql = "select id, nombre FROM semestre";
				$resultado = $objConexion->consulta($sql);

				echo "<option value='<--Seleccione-->' msg='0'><--Seleccione--></option>";

				while($fila = $resultado->fetch_assoc()){
					echo "<option value='".$fila['nombre']."' msg='".$fila['id']."'>". $fila['nombre'] ."</option>";
				}

				$objConexion->liberarResult();
				$objConexion->cerrar();
			}
		}

	}

	$centroEstudio = new Semestre();

?>