<?php

require_once("../libs/mpdf60/mpdf.php");

$mpdf = new mPDF('C', 'A4');
				$mpdf->writeHTML("<h3>Hola</h3>");
				$mpdf->Output("reporte.pdf", "I");
				?>