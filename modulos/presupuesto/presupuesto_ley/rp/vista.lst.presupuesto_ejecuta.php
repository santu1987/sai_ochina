<?
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
	require('../../../../utilidades/fpdf153/fpdf.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$ii=0;
$desde=1;
$hasta=3;
while($desde<=$hasta){
	if ($ii == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
		$monto_comprometido = "monto_comprometido [".$desde."]";
		$monto_causado = "monto_causado [".$desde."]";
		$monto_pagado = "monto_pagado [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$monto_comprometido = $monto_comprometido .' + monto_comprometido ['.$desde.']';
		$monto_causado = $monto_causado.' + monto_causado ['.$desde.']';
		$monto_pagado = $monto_pagado.' + monto_pagado ['.$desde.']';
	}
$ii++;
$desde++;

}
//************************************************************************

$sql = "
SELECT 
	codigo_unidad_ejecutora,
	codigo_accion_especifica,
	partida, generica, especifica, sub_especifica, 
	($monoto) AS monto_presupuesto, 
	($traspasado)AS monto_traspasado, 
	($modificado )AS monto_modificado, 
	(
	($monoto)+ 
	($traspasado)+ 
	($modificado ) 
	) AS correjido,
	($monto_comprometido) AS monto_comprometido, 
	($monto_causado) AS monto_causado,
	($monto_pagado) AS monto_pagado,
	(
	(($monoto)+ 
	($traspasado)+ 
	($modificado ) )-
	monto_comprometido[1]
	) AS diponible
FROM 
	\"presupuesto_ejecutadoR\"
INNER JOIN
	unidad_ejecutora
ON
	(unidad_ejecutora.id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora)
INNER JOIN
	accion_especifica
ON
	(accion_especifica.id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica)
ORDER BY
	codigo_unidad_ejecutora,
	codigo_accion_especifica,
	partida, generica, especifica, sub_especifica
";
$row=& $conn->Execute($sql);

if (!$row->EOF)
{
	//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE PRESUPUESTO CORREJIDO',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.date('Y'),0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(21,				10,		'U.E.',					'B',0,'C',1);
			$this->Cell(21,				10,		'A. E. ',				'B',0,'C',1);
			$this->Cell(23,				10,		'PARTIDA',	'B',0,'C',1);
			$this->Cell(22,				10,		'APROBADO',	'B',0,'C',1);
			$this->Cell(22,				10,		'TRASPASADO',	'B',0,'C',1);
			$this->Cell(22,				10,		'MODIFICADO',	'B',0,'C',1);
			$this->Cell(22,				10,		'CORREJIDO',	'B',0,'C',1);
			$this->Cell(23,				10,		'COMPROMETIDO',	'B',0,'C',1);
			$this->Cell(22,				10,		'CAUSADO',	'B',0,'C',1);
			$this->Cell(22,				10,		'PAGADO',	'B',0,'C',1);
			$this->Cell(22,				10,		'DIPOSNIBLE',	'B',0,'C',1);
			
			$this->Ln(10);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(100,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(85,3,'Impreso por:  '.$_SESSION[apellido].' '.$_SESSION[nombre],0,0,'C');
			$this->Cell(75,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
	}
	
//*************************************************************************************************************
	//*************************************************************************************************************
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',8);
		$pdf->SetFillColor(255);
		while (!$row->EOF)
		{
			$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
			$pdf->Cell(21,				5,		$row->fields("codigo_unidad_ejecutora"),							'RL',0,'C',1);
			$pdf->Cell(21,				5,		$row->fields("codigo_accion_especifica"),							'RL',0,'C',1);
			$pdf->Cell(23,				5,		$partida,															'RL',0,'C',1);
			$pdf->Cell(22,				5,		number_format($row->fields("monto_presupuesto"),2,',','.'),			'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("monto_traspasado"),2,',','.'),			'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("monto_modificado"),2,',','.'),			'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("correjido"),2,',','.'),					'RL',0,'R',1);
			$pdf->Cell(23,				5,		number_format($row->fields("monto_comprometido"),2,',','.'),		'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("monto_causado"),2,',','.'),				'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("monto_pagado"),2,',','.'),				'RL',0,'R',1);
			$pdf->Cell(22,				5,		number_format($row->fields("diponible"),2,',','.'),					'RL',0,'R',1);
			$pdf->Ln(5);
			$row->MoveNext();
		}
}
$pdf->Output();
?>