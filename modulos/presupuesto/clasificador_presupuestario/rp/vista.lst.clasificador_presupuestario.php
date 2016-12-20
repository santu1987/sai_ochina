<?php
session_start();

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
				
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$nombre_elabora = $_SESSION[nombre]/*.' '.$_SESSION[apellido]*/;

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$central = $_GET['id'];
//$partida =explode(".",$partida_toda);
$anio = $_GET['anio'];
//if ($unidad_ejecutora !="" ){
$Sql="
	SELECT 
		partida, generica, especifica, subespecifica, 
		denominacion, grupo, tipo, cuenta_contable, comentario
	FROM 
		clasificador_presupuestario
	ORDER BY
		 partida, generica, especifica, subespecifica, grupo
	";	
//}
//echo $Sql;
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 

		
		//************************************************************************
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
				$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
				$this->Ln();			
				$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
				$this->Ln();
				/*$this->Ln();			
				$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
				$this->Ln();	
				$this->SetFont('Arial','B',14);
				$this->Cell(0,10,'CLASIFICADOR PRESUPUESTARIO DE RECURSOS Y EGRESOS',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(120,10,'Año: '.date('2009'),0,0,'L');
				//$this->SetFont('Arial','B',10);
				//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
				$this->Ln(8);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(30,		6,		'Partida',			0,0,'L',1);
				$this->Cell(150,	6,		'Denominación',		0,0,'L',1);
				$this->Cell(45,		6,		'Grupo',			0,0,'C',1);
				$this->Cell(40,		6,		'Tipo',				0,0,'C',1);
				$this->Ln(6);
			}
			//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(128,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			//$this->SetFont('barcode','',6);
			//$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->SetFont('Arial','I',9);
			$this->Cell(127,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
		}
		//************************************************************************
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(auto,25);
		$e=0;
		while (!$row->EOF) 
		{
		  $partidas = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
		  
			if ($row->fields("grupo") == 3)
				$grupo = 'RECURSOS';
			elseif ($row->fields("grupo") == 4)
				$grupo = 'EGRESOS';
			
			if ($row->fields("tipo") == 1)
				$tipo = 'TITULO';
			elseif ($row->fields("tipo") == 2)
				$tipo = 'DETALLE';

			$pdf->Cell(30,		6,	$partidas,							0,0,'L',1);
			$pdf->Cell(150,		6,	$row->fields("denominacion"),		0,0,'L',1);
			$pdf->Cell(45,		6,	$grupo,								0,0,'C',1);
			$pdf->Cell(40,		6,	$tipo,								0,0,'C',1);
			$pdf->Ln(6);
			$monto_partida = $monto_partida + $monto_presupuesto;
			
			
			$row->MoveNext();
		}
		
		

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
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
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