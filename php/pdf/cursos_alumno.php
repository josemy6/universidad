<?php
//dompPdf
require '../vendor/autoload.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;

require "../conexion.php";

$objConexion = new Conexion();
$objConexion->establecerConexion();

$carne = $_GET['carne'];

$header = "<!DOCTYPEhtml>
		<html lang='es'>
			<head>
				<meta charset='UTF-8'>
				<link rel='stylesheet' href='css/estilos.css'>
			</head>
			<body>";

$header .= "<div class='cabeza'><div><img src='img/logo_um_pdf.png'></div><div class='titulo'><h3>Asignaciones</h3></div></div><p><b>Carne: </b>".$carne."</p>";

$header_tabla = "<table>
			<thead>
				<tr>
					<th>Curso</th>
					<th>Nota</th>
					<th>Facultad</th>
					<th>Semestre</th>
					<th>Centro</th>
					<th>Inscripción</th>
				</tr>	
			</thead>
			<tbody>";
			
$contenido_tabla = "";
$fin_tabla = "";			

		$sql = "select nombres, apellidos from estudiante where carne = $carne";	
		$resultado = $objConexion->consulta($sql);

		$sql1 = "select E.carne, C.nombre curso, CE.nombre centro, F.nombre facultad, SE.nombre semestre, ".
 				" A.fecha_inscripcion inscripcion, A.calificacion ".
				" from asignaciones A, estudiante E, centro CE, curso C, facultad F, semestre SE, salon S ".
				" where A.id_estudiante = E.carne and A.id_centro = CE.id and A.id_curso = C.id ".
				" and A.id_salon = S.id and C.id_facultad = F.id and C.id_semestre = SE.id ".
				" and E.carne = $carne ;";
		$resultado1 = $objConexion->consulta($sql1);

		if(!$resultado1 && !$resultado){
			echo "error | debe introducir el número de carne";
		}else{
			$hayContenido = false;
			while($fila = $resultado->fetch_assoc()){
				$header .= "<p><b>Nombres: </b>".$fila['nombres']."</p><p><b>Apellidos: </b>".$fila['apellidos']."</p>";
			}
			while($fila = $resultado1->fetch_assoc()){
				$contenido_tabla .= "<tr><td>".$fila['curso']."</td><td>".$fila['calificacion']."</td><td>".$fila['facultad']."</td>".
				"<td>".$fila['semestre']."</td><td>".$fila['centro']."</td><td>".$fila['inscripcion']."</td></tr>";
				$hayContenido = true;
			}
			if($hayContenido){
				$fin_tabla .= "</tbody></table> </body> </html>";
				$html = $header.$header_tabla.$contenido_tabla.$fin_tabla;
				
				
				// instantiate and use the dompdf class
				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);

				// (Optional) Setup the paper size and orientation
				$dompdf->setPaper('A4', 'landscape');

				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				$pdf = $dompdf->output();

				$filename = "Cursos_Alumno.pdf";

				$dompdf->stream($filename, array("Attachment"=>0));
			}else{
				echo "error | No existen asignaciones con este carne.";
			}
			$objConexion->liberarResult();
			$objConexion->cerrar();
		}




?>
