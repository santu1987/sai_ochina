<?php
//session_start();
require('../../../../utilidades/fpdf153/fpdf.php');

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$ano = $_GET['ano'];
$unidad_ejecutora = $_GET['unidad_ejecutora'];
//************************************************************************
$where = "WHERE (1=1) ";
if($unidad_ejecutora !=""){
	$where = $where ." AND (id_unidad_ejecutora = $unidad_ejecutora) AND (ano = $ano) ";
}
if($ano !=""){
	$where = $where ." AND (ano = $ano) ";
}	
//************************************************************************
$Sqll="
SELECT 
	\"plan_comprasE\".id_unidad_ejecutora, 
	\"plan_comprasE\".ano,
	unidad_ejecutora.nombre AS unidad_ejecutora,
	\"plan_comprasE\".responsable
FROM 
	\"plan_comprasE\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"plan_comprasE\".id_unidad_ejecutora
	".$where ."
ORDER BY 
	id_unidad_ejecutora
";
//die ($Sql);
$roww=& $conn->Execute($Sqll);

//************************************************************************
if (!$roww->EOF)
{
	$Sql="
		SELECT 
	
			\"plan_comprasD\".id_unidad_ejecutora,
			demanda.codigo_demanda,
			demanda.nombre AS demanda,
			detalle_demanda.codigo_detalle_demanda,
			\"plan_comprasD\".cantidad,
			\"plan_comprasD\".valor,
			\"plan_comprasD\".fecha_propuesta,
			\"plan_comprasD\".tipo_compra  
		FROM 
			\"plan_comprasD\"
		INNER JOIN
			unidad_ejecutora
		ON
			unidad_ejecutora.id_unidad_ejecutora = \"plan_comprasD\".id_unidad_ejecutora  
		 
		INNER JOIN
			detalle_demanda
		ON
			detalle_demanda.id_detalle_demanda = \"plan_comprasD\".id_detalle_demanda 
		INNER JOIN
			demanda
		ON
			detalle_demanda.id_demanda = demanda.id_demanda 
		WHERE
			\"plan_comprasD\".id_unidad_ejecutora = ".$roww->fields('id_unidad_ejecutora')."
		ORDER BY 
			codigo_demanda, codigo_detalle_demanda
	";
	$row=& $conn->Execute($Sql);	
	//die ($Sql);
	$Sqlsum="
		SELECT 
			sum(cantidad) AS cantidad,
			sum(valor) AS valor
		FROM 
			\"plan_comprasD\"
		WHERE
			id_unidad_ejecutora = ".$roww->fields('id_unidad_ejecutora')."
		GROUP BY
			id_unidad_ejecutora
	";
	$rowsum=& $conn->Execute($Sqlsum);
	
	$id_unidad_ejecutora = $roww->fields('id_unidad_ejecutora');
	$ano = $roww->fields('ano');
	$unidad_ejecutora = $roww->fields('unidad_ejecutora');
	$responsable = $roww->fields('responsable');
	$nombre = $row->fields('demanda');

	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $ano,$unidad_ejecutora,$responsable, $nombre;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			$this->Ln();			
			$this->SetFont('Arial','B',13);
			$this->Cell(267,	8,			'GASTOS DE FUNCIONAMIENTO VIGENCIA AÑO '.$ano,		0,0,'C');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(267,	8,			'UNIDAD ORIGEN: '.strtoupper($unidad_ejecutora),		0,0,'L');
			$this->Ln(8);
			$this->Cell(267,	8,			'Responsable: '.strtoupper($responsable),				0,0,'L');
			$this->Ln(10);
			//$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(190) ;
			$this->SetTextColor(0);
			$this->Cell(268,	8,			'TIPO DEMANDA: '.strtoupper($nombre),		0,0,'L',1);
			$this->Ln(8);
			$y=$this->GetY();
			$this->MultiCell(70,5,			'DETALLE DE LA DEMANDA',	0,'C',1);
			$this->SetXY(78,$y);
			$y=$this->GetY();
			$this->MultiCell(50,5,			'CANTIDA ESTIMADA',			0,'C',1);
			$this->SetXY(125,$y);
			$y=$this->GetY();
			$this->MultiCell(53,5,			'VALOR ESTIMADO',			0,'C',1);
			$this->SetXY(178,$y);
			$y=$this->GetY();
			$this->MultiCell(50,5,			'FECHA PROPUESTA',			0,'C',1);
			$this->SetXY(228,$y);
			$y=$this->GetY();
			$this->MultiCell(50,5,			'TIPO DE COMPRA',			0,'C',1);
			$this->SetXY(267,$y);
			$this->Ln(10);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-30);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(89,3,'' ,0,0,'L');
			$this->Ln(5);
			$this->Cell(89,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(89,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->Ln();
		}
	}
//***************************************************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,30);
	$i=0;
	while (!$row->EOF) 
	{
	$i++;
		$pdf->Cell(67,		6,	$row->fields("codigo_demanda").$row->fields("codigo_detalle_demanda"),	0,0,'C',1);
		$pdf->Cell(50,		6,	number_format($row->fields("cantidad"),0,',','.'),				0,0,'C',1);
		$pdf->Cell(50,		6,	number_format($row->fields("valor"),2,',','.'),					0,0,'C',1);
		$pdf->Cell(50,		6,	$row->fields("fecha_propuesta"),		0,0,'C',1);
		if ($row->fields("tipo_compra") == 1){	
			$tipo = 'Nacional';
		}else{
			$tipo = 'Internacional';
		}
			$pdf->Cell(50,		6,				$tipo,					0,0,'C',1);
		$pdf->Ln(6);
		
		$tamano = $tamano + 6;
		$row->MoveNext();
	}
	
	if ($tamano<72)
	{
	$t = 72-$tamano;
			$pdf->Cell(149,		$t,	' ',		0,0,'L',0);
			$pdf->Cell(42,		$t,	'',			0,0,'R',0);
			$pdf->Cell(42,		$t,	'',			0,0,'R',1);
			$pdf->Cell(42,		$t,	'',			0,0,'R',1);
			$pdf->Ln($t);

	}else{
		$pdf->AddPage('L');
	}
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(190) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(67,		6,	'TOTAL TIPO DEMANDA',	0,0,'C',1);
		$pdf->Cell(50,		6,	number_format($rowsum->fields("cantidad"),0,',','.'),				0,0,'C',1);
		$pdf->Cell(50,		6,	number_format($rowsum->fields("valor"),2,',','.'),					0,0,'C',1);
		$pdf->Cell(50,		6,	'',		0,0,'C',0);
		$pdf->Cell(50,		6,	'',					0,0,'C',0);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(255) ;
		$pdf->SetTextColor(0);
		$pdf->Ln(6);
	$pdf->Output();
	
	//*************************************************************************************************************
}else{
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de página
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(200);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
?>