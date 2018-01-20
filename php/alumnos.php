<?php

require "conexion.php";

class Alumno{
	//datos a tomar: llenar, guardar actualizar.
	private $accion;
	private $objConexion;

	function Alumno(){
		$this->objConexion = new Conexion();
		$this->objConexion->establecerConexion();
	}

	//Funcion que llamara a las funciones: llenar, guardar y actualizar de acuerdo a la accion recibida por ajax.
	function ejecutarAcciones(){
		//recibimos la accion a tomar.
		$this->accion = $_POST['accion'];
		//llamando a la accion llenar.
		if($this->accion == "llenar"){
			$this->llenarAlumno();
		}else if($this->accion == "eliminar"){ //eliminando alumno
			$this->eliminarAlumno();
		}else{
			//llamando a la funcion guardarAlumnos le enviamos el parametro: Guardar o Actualizar
			$this->guardarAlumno($this->accion);
		}
	}

	//Accion que retornara un json para llenar la dataTable
	function llenarAlumno(){
		$sql = " select @rownum:=@rownum+1 n, E.carne, E.nombres, E.apellidos, F.id id_facultad, F.nombre facultad, CEN.id id_centro,".
				" CEN.nombre centro, E.fecha_nacimiento, ES.fecha_inscripcion, E.sexo, E.telefono from estudiante E INNER JOIN estudia ES ON ".
				" E.carne = ES.id_estudiante INNER JOIN facultad F ON F.id = ES.id_facultad ".
				" INNER JOIN centro CEN ON CEN.id = ES.id_centro, (SELECT @rownum:=0) r ";
		$resultado = $this->objConexion->consulta($sql);

		if($resultado){
			while($fila = $resultado->fetch_assoc()){
				/*echo "<tr class='t-par'><td>".$fila['carne']."</td> <td>".$fila['nombres']."</td> <td>".$fila['apellidos']."</td>
				<td>".$fila['sexo']."</td> <td>".$fila['telefono']."</td> <td>".$fila['fecha_nacimiento']."</td> 
				<td>".$fila['fecha_inscripcion']."</td> <td>".$fila['centro']."</td> <td>".$fila['facultad']."</td></tr>";*/
				$arreglo["data"][] = array_map("utf8_encode", $fila);

			}
			echo json_encode($arreglo);

		}else{
			echo "No se pudo acceder a los datos.";
		}
		$this->objConexion->liberarResult();
		$this->objConexion->cerrar();
	}

	//guardando o Actualizar Alumnos en base de datos
	function guardarAlumno($acciones){
		//validacion de entradas
		$entradasTexto = true;
		$entradasNum = true;
		//recibe un string $idCentro, $idFacultad
		$idCentro = null;
		$idFacultad = null;
		//Recibe un char: F, M.
		$genero;
		$carne = $_POST['carne'];
		$nombres = $_POST['nombres'];
		$apellidos = $_POST['apellidos'];
		if(($_POST['genero'])==1){$genero="F";}else{$genero="M";}
		$telefono = $_POST['telefono'];
		$nacimiento = $_POST['nacimiento'];
		$ingreso = $_POST['ingreso'];
		$centro = $_POST['centro'];
		$facultad = $_POST['facultad']; 

		/*$carne = "99199999";
		$nombres = "penelope";
		$apellidos = "mono";
		$genero = 1;
		$telefono = "333333";
		$nacimiento = "2014-02-01";
		$ingreso = "2014-02-02";
		$centro = "centro capital";
		$facultad = "ingenieria en sistemas"; */

		if($nombres=="" || $apellidos=="" || $genero=="" || $nacimiento=="" || $ingreso==""){
			$entradasTexto = false;
		}
		if(is_numeric($carne) && is_numeric($telefono) && $centro!=0 && $facultad!=0){
		}else{
			$entradasNum = false;
		}

		//Si las entradas son validas procedemos...
		if($entradasTexto && $entradasNum){
			/*//obteniendo el id del Centro y de la Facultad para ser guardado en la tabla estudia.
			$sql1 = "select F.id facultad, C.id centro from facultad F, centro C, contiene CO where F.id = CO.id_facultad ".
			" and C.id = CO.id_centro and F.nombre = '$facultad' and C.nombre = '$centro'; ";
			$resultado1 = $this->objConexion->consulta($sql1);

	        while($fila = $resultado1->fetch_assoc()){
					$idCentro = $fila['centro'];
					$idFacultad = $fila['facultad'];
			} */

			
				//Si la accion recibida por POST es guardar, entonces guardamos
				if($acciones == 'guardar'){
					$sql = "insert INTO estudiante (carne, nombres, apellidos, telefono, sexo, fecha_nacimiento) ".
		               " VALUES ($carne, '$nombres', '$apellidos', $telefono, '$genero', '$nacimiento');";
		           
		        	$resultado = $this->objConexion->consulta($sql);


		        	$sql2 = "insert INTO estudia (id_centro, id_facultad, id_estudiante, fecha_inscripcion) ".
		                	" VALUES ($centro, $facultad, $carne, '$ingreso');";

		        	$resultado2 = $this->objConexion->consulta($sql2);

		        	if($resultado && $resultado2){
		        		echo "Alumno guardado con Éxito.";
		        	}else{
		        		echo "Error, verifique los datos de entrada / verifique si el alumno ya existe.";
		        	}

	        		$this->objConexion->cerrar();
	        	}else{ // de lo contrario Actualizamos.
	        		//actualizar
	        		//Buscamos si el Alumno existe
	        		$existe = 0;
	        		$sqlExiste = "select carne from estudiante where carne = $carne ;";
	        		$resultExiste = $this->objConexion->consulta($sqlExiste);

	        		while($fila = $resultExiste->fetch_assoc()){
						$existe = $fila['carne'];
					}
					//Si el alumno existe procedemos a actualizar.
	        		if($existe>1){
		        		$sql = "update estudiante SET nombres='$nombres', apellidos='$apellidos', telefono = $telefono, sexo='$genero', ".
			               		" fecha_nacimiento='$nacimiento' where carne = $carne;";

						$resultado = $this->objConexion->consulta($sql);

						$sql2 = "update estudia SET id_centro=$centro, id_facultad=$facultad, fecha_inscripcion='$ingreso' ".
			                	" where id_estudiante = $carne  ";

			        	$resultado2 = $this->objConexion->consulta($sql2);

			        	if($resultado && $resultado2){
			        		echo "Alumno Actualizado con Éxito.";
			        	}else{
			        		echo "Error, verifique los datos de entrada / Actualizar";
			        	}

		        		$this->objConexion->cerrar();
		        	}else{
		        		$this->objConexion->cerrar();
		        		echo "El alumno no existe";
		        	}//fin if existe		
	        	}
	        
    	}else{
    		echo "Error, verifique los datos de entrada.";
    	}
	}

	function eliminarAlumno(){
		$carne = $_POST['carne'];

		if(is_numeric($carne)){

			//Buscamos si el Alumno existe
	        $existe = 0;
	        $sqlExiste = "select carne from estudiante where carne = $carne ;";
	        $resultExiste = $this->objConexion->consulta($sqlExiste);

	        while($fila = $resultExiste->fetch_assoc()){
				$existe = $fila['carne'];
			}
			//Si el alumno existe procedemos a actualizar.
	        if($existe>1){

				$sql = "delete FROM estudia WHERE id_estudiante = $carne;";
				$sql1 = "delete FROM estudiante WHERE carne = $carne;";

			    $resultado = $this->objConexion->consulta($sql);
			    $resultado1 = $this->objConexion->consulta($sql1);

			    if($resultado && $resultado1){
			        echo "Alumno Eliminado con Éxito.";
			    }else{
			        echo "Error, verifique los datos de entrada / Carne";
			    }

			    $this->objConexion->cerrar();
			}else{
				$this->objConexion->cerrar();
		        echo "El alumno no existe";
			} //fin existe   
		}else{
			echo "Error, verifique los datos de entrada | carne";
		}
	}
}




	$alumno = new Alumno();
	$alumno->ejecutarAcciones();


?>