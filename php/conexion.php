<?php
	/*
	class Conexion{
		private $servidor;
		private $usuario;
		private $password;
		private $db;
		private $conectar;

		function Conexion($s, $u, $p, $d){
			$this->servidor = $s;
			$this->usuario = $u;
			$this->password = $p;
			$this->db = $d;
			$this->conectarMysql();
			$this->seleccionarDb();
		}

		private function conectarMysql(){
			$this->conectar = mysql_connect($this->servidor, $this->usuario, $this->password) or die(mysql_errno());
		}

		private function seleccionarDb(){
			mysql_select_db($this->db) or die(mysql_error());
		}

		public function consultar($sql){
			$resultado = mysql_query($sql, $this->conectar);
			return $resultado;
		}

		public function nFilas($sql){
			return mysql_num_rows($sql);
		}

		public function nColumnas($sql){
			return mysql_num_fields($sql);
		}

		public function nombreCampo($sql, $i){
			return mysql_field_name($sql, $i);
		}
	}

	
	$objeto = new Conexion("localhost", "root", "", "centro_de_estudios");
	*/
	class Conexion{
		private $conexion;
		private $result;
		function establecerConexion(){
			$conectado;
			$this->conexion = new mysqli("localhost", "root", "", "centro_de_estudios");
			if($this->conexion->connect_errno){
				echo "fallo la conexion " . $this->conexion->connect_errno;
				return $conectado = false;
			}

			$this->conexion->set_charset("utf-8");
			return $conectado = true;

		}
			
		function consulta($sql){
			$this->result = $this->conexion->query($sql);
			return $this->result;
			/*
			while($fila = $resultado->fetch_assoc()){
				echo $fila['nombre'] . "<br>";
			}
			*/
		}
		
		//liberando la memoria, eliminando el result
		function liberarResult(){
			$this->result->free_result();
		}	

		function cerrar(){
			$this->conexion->close();
		}
	}
	//$obje = new Conexion();
	//$obje->establecerConexion();
?>