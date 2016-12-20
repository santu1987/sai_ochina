<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD
session_start();
require('../../../../utilidades/fpdf153/code128.php');

require_once('../../../../controladores/parametros.inc.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$colores=colores();
$encabezado=encabezado();

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla armador
$Sql="SELECT 
				sareta.armador.nombre,  
				sareta.armador.obs 
			FROM 
				sareta.armador
				WHERE 
				upper(sareta.armador.nombre) like '".strtoupper($_GET['busq_nombre_armador'])."%'
				or
				sareta.armador.nombre like '".$_GET['busq_nombre_armador']."%'
				ORDER BY 
				sareta.armador.nombre 
		";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{		
			global $colores;
			global $encabezado;

			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,utf8_decode($encabezado[1]),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode($encabezado[2]),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode($encabezado[3]),0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,utf8_decode($encabezado[4]),0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,utf8_decode($encabezado[5]),0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'LISTADO DE ARMADORES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(50,		6,			'NOMBRE',		0,0,'L',1);
			$this->Cell(130,	6,			utf8_decode('OBSERVACIÓN'),			0,0,'L',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(25,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(80,3,'Impreso por:',0,0,'R');
			$this->Cell(83,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(87,286,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$Cont=0;
	while (!$row->EOF) 
	{
		
		$Cont+=1;
		
		$Logitud_Max_Texto = strlen($row->fields("obs"));
		$Carateres_en_linea=110;
		$Cantidad_lineas_total=0;
		$resta=$Logitud_Max_Texto;
		do{
        $resta = $resta- ($Carateres_en_linea+1);
		$Cantidad_lineas_total +=  1 ;
		
	}	while($resta>=0);

		$esp=6*$Cantidad_lineas_total;
		
		
		if($Cantidad_lineas_total >1){
		$esp=6*$Cantidad_lineas_total;
		$pdf->Cell(10,		$esp,			$Cont,		1,0,'C',1);
		$pdf->Cell(50,		$esp,		utf8_decode($row->fields("nombre")),		1,0,'L',1);
		$pdf->MultiCell(130,	6,		utf8_decode(str_replace("\n"," ",$row->fields("obs"))),						1,'LBR','L');
		}else{
		$pdf->Cell(10,		6,			$Cont,		1,0,'C',1);
		$pdf->Cell(50,		6,		utf8_decode($row->fields("nombre")),		1,0,'L',1);
		$pdf->Cell(130,	6,		utf8_decode(str_replace("\n"," ",$row->fields("obs"))),						1,0,'L',1);
		$pdf->Ln(6);
		}
		$row->MoveNext();
	}
	
	
	
	$pdf->Output();
}else

{	
	require('../../../../utilidades/fpdf153/fpdf.php');
	class PDF extends FPDF
	{
			
		//Cabecera de página
		function Header()
		{		
			global $colores;
			global $encabezado;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,utf8_decode($encabezado[1]),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode($encabezado[2]),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode($encabezado[3]),0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,utf8_decode($encabezado[4]),0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,utf8_decode($encabezado[5]),0,0,'C');	
			
		
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