<?php
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
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$Sql="
	SELECT 
		presupuesto_ley.partida, 
		presupuesto_ley.generica, 
		presupuesto_ley.especifica, 
		presupuesto_ley.sub_especifica, 
		(
			presupuesto_ley.enero+
			presupuesto_ley.febrero+
			presupuesto_ley.marzo+
			presupuesto_ley.abril+
			presupuesto_ley.mayo+
			presupuesto_ley.junio+
			presupuesto_ley.julio+
			presupuesto_ley.agosto+
			presupuesto_ley.septiembre+
			presupuesto_ley.octubre+
			presupuesto_ley.noviembre+
			presupuesto_ley.diciembre
		) AS monto_presupuesto
	FROM 
		presupuesto_ley 
	WHERE 
		(presupuesto_ley.id_organismo=$_SESSION[id_organismo])  
	AND 
	 	(presupuesto_ley.anio='".$_GET[ano]."') 
	ORDER BY 
		presupuesto_ley.anio,
		presupuesto_ley.partida, 
		presupuesto_ley.generica, 
		presupuesto_ley.especifica, 
		presupuesto_ley.sub_especifica,
		presupuesto_ley.id_unidad_ejecutora
";


$row=& $conn->Execute($Sql);


//************************************************************************
if (!$row->EOF)
{ 
	//************************************************************************
	$unidad_ejecutora=$row->fields("nombre");
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $unidad_ejecutora;
			
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
			$this->Ln();	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'PRESUPUESTO POR PARTIDA',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Arial','B',10);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(30,		6,		'Partida',					0,0,'C',1);
			$this->Cell(20,		6,		'Monto',					0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-60);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(10);
			$this->Cell(65,3,'Preparado Por: ' ,0,0,'C');
			$this->Cell(65,3,'Solicitado Por: ' ,0,0,'C');
			$this->Cell(65,3,'Vo Bo: ' ,0,0,'C');
			$this->Ln();
			$this->Cell(65,3,'TF. MAGLY LEON RODRIGUEZ' ,0,0,'C');
			$this->Cell(65,3,'TF. MAGLY LEON RODRIGUEZ' ,0,0,'C');
			$this->Cell(65,3,'TF. LUIS EDUARDO ARRCHEDERA' ,0,0,'C');
			$this->Ln();
			$this->Cell(65,3,'Solicitante' ,0,0,'C');
			$this->Cell(65,3,'Jefe Unidad Solicitante' ,0,0,'C');
			$this->Cell(65,3,'Jefe del Proyecto / A.C.' ,0,0,'C');
			$this->Ln(10);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
			$this->Cell(62,3,' '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'C');					
			$this->Ln();
			$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 
	/*
	$Sql="
SELECT 
		presupuesto_ley.id_unidad_ejecutora, 
		presupuesto_ley.anio, 
		unidad_ejecutora.nombre
	FROM 
		presupuesto_ley 
	INNER JOIN 
		unidad_ejecutora 
	ON 
		unidad_ejecutora.id_unidad_ejecutora=presupuesto_ley.id_unidad_ejecutora 
	INNER JOIN 
		accion_especifica 
	ON 
		accion_especifica.id_accion_especifica=presupuesto_ley.id_accion_especifica 
	WHERE 
		presupuesto_ley.id_organismo=$_SESSION[id_organismo]   
	ORDER BY 
		presupuesto_ley.id_unidad_ejecutora, 
		presupuesto_ley.anio";
	*/

	$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		$partida = 	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
		
		if($row->fields("id_proyecto") != 0)
			$SqlProyectoAccion="SELECT nombre AS proyectopccion FROM  proyecto WHERE (id_proyecto = ".$row->fields("id_proyecto").")";
		elseif($row->fields("id_proyecto") == 0)
			$SqlProyectoAccion="SELECT denominacion AS proyectopccion  FROM  accion_centralizada WHERE (id_accion_central = ".$row->fields("id_accion_central").") ";
		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);		
		
		
		$pdf->Cell(30,		6,	$partida,								0,0,'C',1);
		$pdf->Cell(20,		6,	$row->fields("monto_presupuesto"),		0,0,'C',1);
		$pdf->Ln(6);
		$row->MoveNext();
	}
	$sqlpartida = "SELECT sum  FROM vista_suma_partida ";
	$rowtotal=& $conn->Execute($sqlpartida);
	$pdf->Cell(170,		6,'Total',							0,0,'C',1);
	$pdf->Cell(20,		6,$rowtotal->fields("sum"),			'T',0,'C',1);
	$pdf->Ln(6);
	$pdf->Output();
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
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
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