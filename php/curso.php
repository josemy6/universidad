<?php

	require "conexion.php";

	class Curso{

		function Curso(){
			$idFacultad = $_POST['facultad'];
			$idSemestre = $_POST['semestre'];
			$objConexion = new Conexion();
			if($objConexion->establecerConexion()){
				$sql = "select id, nombre FROM curso WHERE id_facultad = $idFacultad and id_semestre = $idSemestre";
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

	$centroEstudio = new Curso();

?>