<?php

require "conexion.php";

class Cursos{
	//datos a tomar: llenar, guardar actualizar.
	private $accion;
	private $objConexion;

	function Cursos(){
		$this->objConexion = new Conexion();
		$this->objConexion->establecerConexion();
	}

	//Funcion que llamara a las funciones: llenar, guardar y actualizar de acuerdo a la accion recibida por ajax.
	function ejecutarAcciones(){
		//recibimos la accion a tomar.
		$this->accion = $_POST['accion'];
		//llamando a la accion llenar.
		if($this->accion == "llenar"){
			$this->llenarCurso();
		}else if($this->accion == "eliminar"){ //eliminando Asignacion
			$this->eliminarCurso();
		}else{
			//llamando a la funcion guardarAlumnos le enviamos el parametro: Guardar o Actualizar
			$this->guardarCurso($this->accion);
		}
	}

	//Accion que retornara un json para llenar la dataTable
	function llenarCurso(){
		$carne = $_POST['buscar'];
		$sql = "select @rownum:=@rownum+1 n, E.carne, E.nombres, E.apellidos, C.nombre curso, CE.nombre centro, ".
 				" F.nombre facultad, SE.nombre semestre,  A.fecha_inscripcion inscripcion, S.nombre salon, A.calificacion ".
				" from asignaciones A, estudiante E, centro CE, curso C, facultad F, semestre SE, salon S, (SELECT @rownum:=0) r ".
				" where A.id_estudiante = E.carne and A.id_centro = CE.id and A.id_curso = C.id ".
				" and A.id_salon = S.id and C.id_facultad = F.id and C.id_semestre = SE.id ".
				" and E.carne = $carne ;";
		$resultado = $this->objConexion->consulta($sql);

		if(!$resultado){
			echo $this->arregloVacio();
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
			$this->objConexion->liberarResult();
			$this->objConexion->cerrar();
		}

	}

	//guardando o Actualizar Alumnos en base de datos
	function guardarCurso($acciones){
		//validacion de entradas
		$entradasTexto = true;
		$entradasNum = true;

		$carne = $_POST['carne'];
		$salon = $_POST['salon']; 
		$calificacion = $_POST['calificacion'];
		$ingreso = $_POST['ingreso'];
		$centro = $_POST['centro'];
		$curso = $_POST['curso']; 


		if($ingreso==""){
			$entradasTexto = false;
		}
		if(is_numeric($carne) && is_numeric($salon) && is_numeric($calificacion) && $centro!=0 && $curso!=0){
		}else{
			$entradasNum = false;
		}

		//Si las entradas son validas procedemos...
		if($entradasTexto && $entradasNum){
			
				//Si la accion recibida por POST es guardar, entonces guardamos
				if($acciones == 'guardar'){
					$sql = "insert INTO asignaciones (id_estudiante, id_centro, id_curso, fecha_inscripcion, calificacion, id_salon) ".
		               " VALUES ($carne, $centro, $curso, '$ingreso', $calificacion, $salon);";
		           
		        	$resultado = $this->objConexion->consulta($sql);

		        	if($resultado){
		        		echo "Asignacion guardada con Éxito.";
		        	}else{
		        		echo "Error, verifique los datos de entrada / verifique si la Asignacion ya existe.";
		        	}

	        		$this->objConexion->cerrar();
	        	}else{ // de lo contrario Actualizamos.
	        		//actualizar
	        		//Buscamos si el Alumno existe
	        		$existe = 0;
	        		$sqlExiste = "select id_estudiante from asignaciones where id_estudiante=$carne and id_centro=$centro and id_curso = $curso ;";
	        		$resultExiste = $this->objConexion->consulta($sqlExiste);

	        		while($fila = $resultExiste->fetch_assoc()){
						$existe = $fila['id_estudiante'];
					}
					//Si el alumno existe procedemos a actualizar.
	        		if($existe>1){
		        		$sql = "update asignaciones SET fecha_inscripcion = '$ingreso', calificacion = $calificacion, id_salon = $salon".
			               		" where id_estudiante = $carne and id_centro = $centro and id_curso = $curso ;";

						$resultado = $this->objConexion->consulta($sql);

			        	if($resultado){
			        		echo "Asignacion Actualizada con Éxito.";
			        	}else{
			        		echo "Error, verifique los datos de entrada / Actualizar";
			        	}

		        		$this->objConexion->cerrar();
		        	}else{
		        		$this->objConexion->cerrar();
		        		echo "La Asignacion no existe";
		        	}//fin if existe		
	        	}
	        
    	}else{
    		echo "Error, verifique los datos de entrada.";
    	}
	}

	function eliminarCurso(){
		$carne = $_POST['carne'];
		$centro = $_POST['centro'];
		$curso = $_POST['curso'];

		if(is_numeric($carne) && is_numeric($centro) && is_numeric($curso)){

			//Buscamos si el Alumno existe
	        $existe = 0;
	        $sqlExiste = "select id_estudiante from asignaciones where id_estudiante=$carne and id_centro=$centro and id_curso = $curso ;";
	        $resultExiste = $this->objConexion->consulta($sqlExiste);

	        while($fila = $resultExiste->fetch_assoc()){
				$existe = $fila['id_estudiante'];
			}
			//Si el alumno existe procedemos a actualizar.
	        if($existe>1){

				$sql = "delete FROM asignaciones WHERE id_estudiante = $carne and id_centro = $centro and id_curso = $curso;";

			    $resultado = $this->objConexion->consulta($sql);

			    if($resultado){
			        echo "Asignacion Eliminada con Éxito.";
			    }else{
			        echo "Error, verifique los datos de entrada / Carne, centro estudio y curso";
			    }

			    $this->objConexion->cerrar();
			}else{
				$this->objConexion->cerrar();
		        echo "La Asignación no existe";
			} //fin existe   
		}else{
			echo "Error, debe ingresar el carne, centro de estudios y curso.";
		}
	}

	function arregloVacio(){
		$vacio = array(
			"n" => "n/a",
			"carne" => "n/a",
			"nombres" => "n/a",
			"apellidos" => "n/a",
			"curso" => "n/a",
			"calificacion" => "n/a",
			"facultad" => "n/a",
			"semestre" => "n/a",
			"centro" => "n/a",
			"inscripcion" => "n/a",
			"salon" => "n/a"
			);

			$dat["data"][] = array_map("utf8_encode", $vacio);
			return json_encode($dat);
	}
}




	$cursos = new Cursos();
	$cursos->ejecutarAcciones();


?>