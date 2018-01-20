<?php
require "conexion.php";

class Alumno{
	//datos a tomar: llenar, guardar actualizar.
	private $accion;
	private $parametro;
	private $objConexion;

	function Alumno(){
		$this->objConexion = new Conexion();
		$this->objConexion->establecerConexion();
	}

	function ejecutarAcciones(){
		//recibimos la accion a tomar.
		$this->accion = $_POST['accion'];
		$this->parametro = $_POST['parametro'];

		if($this->accion == "nombres"){
			$this->busquedaNombres($this->parametro);
		}else if($this->accion == "carne"){
			$this->busquedaCarne($this->parametro);
		}else{
			//Busqueda por apellidos
			$this->busquedaApellidos($this->parametro);
		}
	}

	function busquedaNombres($nombres){
		$sql = "select E.carne, E.nombres, E.apellidos, F.id id_facultad, F.nombre facultad, CEN.id id_centro,".
				" CEN.nombre centro, E.fecha_nacimiento, ES.fecha_inscripcion, E.sexo, E.telefono from estudiante E INNER JOIN estudia ES ON ".
				" E.carne = ES.id_estudiante INNER JOIN facultad F ON F.id = ES.id_facultad ".
				" INNER JOIN centro CEN ON CEN.id = ES.id_centro WHERE E.nombres like '%$nombres%';";
		$resultado = $this->objConexion->consulta($sql);

		if(!$resultado){
			echo "No se pudo acceder a los datos.";
		}else{
			$hayContenido = false;
			while($fila = $resultado->fetch_assoc()){
				/*echo "<tr class='t-par'><td>".$fila['carne']."</td> <td>".$fila['nombres']."</td> <td>".$fila['apellidos']."</td>
				<td>".$fila['sexo']."</td> <td>".$fila['telefono']."</td> <td>".$fila['fecha_nacimiento']."</td> 
				<td>".$fila['fecha_inscripcion']."</td> <td>".$fila['centro']."</td> <td>".$fila['facultad']."</td></tr>";*/
				$arreglo["data"][] = array_map("utf8_encode", $fila);
				$hayContenido = true;
			}
			if($hayContenido){
				echo json_encode($arreglo);
			}else{
				echo $this->arregloVacio();
			}
		}
		$this->objConexion->liberarResult();
		$this->objConexion->cerrar();
	}

	function busquedaApellidos($apellidos){
		$sql = "select E.carne, E.nombres, E.apellidos, F.id id_facultad, F.nombre facultad, CEN.id id_centro,".
				" CEN.nombre centro, E.fecha_nacimiento, ES.fecha_inscripcion, E.sexo, E.telefono from estudiante E INNER JOIN estudia ES ON ".
				" E.carne = ES.id_estudiante INNER JOIN facultad F ON F.id = ES.id_facultad ".
				" INNER JOIN centro CEN ON CEN.id = ES.id_centro WHERE E.apellidos like '%$apellidos%';";
		$resultado = $this->objConexion->consulta($sql);

		if(!$resultado){
			echo "No se pudo acceder a los datos.";
		}else{
			$hayContenido = false;
			while($fila = $resultado->fetch_assoc()){
				$arreglo["data"][] = array_map("utf8_encode", $fila);
				$hayContenido = true;
			}
			if($hayContenido){
				echo json_encode($arreglo);
			}else{
				echo $this->arregloVacio();
			}
		}
		$this->objConexion->liberarResult();
		$this->objConexion->cerrar();
	}

	function busquedaCarne($carne){
		$sql = "select E.carne, E.nombres, E.apellidos, F.id id_facultad, F.nombre facultad, CEN.id id_centro,".
				" CEN.nombre centro, E.fecha_nacimiento, ES.fecha_inscripcion, E.sexo, E.telefono from estudiante E INNER JOIN estudia ES ON ".
				" E.carne = ES.id_estudiante INNER JOIN facultad F ON F.id = ES.id_facultad ".
				" INNER JOIN centro CEN ON CEN.id = ES.id_centro WHERE E.carne like '%$carne%';";
		$resultado = $this->objConexion->consulta($sql);

		if(!$resultado){
			echo "No se pudo acceder a los datos.";
		}else{
			$hayContenido = false;
			while($fila = $resultado->fetch_assoc()){
				$arreglo["data"][] = array_map("utf8_encode", $fila);
				$hayContenido = true;
			}
			if($hayContenido){
				echo json_encode($arreglo);
			}else{
				echo $this->arregloVacio();
			}
			
		}
		$this->objConexion->liberarResult();
		$this->objConexion->cerrar();
	}

	function arregloVacio(){
		$vacio = array(
			"carne" => "n/a",
			"nombres" => "n/a",
			"apellidos" => "n/a",
			"sexo" => "n/a",
			"telefono" => "n/a",
			"fecha_nacimiento" => "n/a",
			"fecha_inscripcion" => "n/a",
			"centro" => "n/a",
			"facultad" => "n/a"
			);

			$dat["data"][] = array_map("utf8_encode", $vacio);
			return json_encode($dat);
	}

}	

$alumno = new Alumno();
$alumno->ejecutarAcciones();
?>