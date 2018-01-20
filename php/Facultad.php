<?php

	require "conexion.php";

	class Facultad{
		private $centro;

		function Facultad(){
			$this->centro = $_POST['centro'];
			$objConexion = new Conexion();
			$objConexion->establecerConexion();
			$sql = "select F.id id, F.nombre nombre FROM contiene C, facultad F WHERE C.id_facultad = F.id and C.id_centro = ".$this->centro.";";
			$resultado = $objConexion->consulta($sql);

			echo "<option value='<--Seleccione-->' msg='0'><--Seleccione--></option>";

			while($fila = $resultado->fetch_assoc()){
				echo "<option value='".$fila['nombre']."' msg='".$fila['id']."' >" . $fila['nombre'] ."</option>";
			}
			$objConexion->liberarResult();
			$objConexion->cerrar();
		}

	}

	$facultad = new Facultad();

?>