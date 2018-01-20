<?php

	require "conexion.php";

	class CentroEstudio{

		function CentroEstudio(){
			$objConexion = new Conexion();
			$objConexion->establecerConexion();
			$sql = "select id, nombre FROM centro";
			$resultado = $objConexion->consulta($sql);

			echo "<option value='<--Seleccione-->' msg='0'><--Seleccione--></option>";

			while($fila = $resultado->fetch_assoc()){
				echo "<option value='".$fila['nombre']."' msg='".$fila['id']."'>". $fila['nombre'] ."</option>";
			}

			$objConexion->liberarResult();
			$objConexion->cerrar();
		}

	}

	$centroEstudio = new CentroEstudio();

?>