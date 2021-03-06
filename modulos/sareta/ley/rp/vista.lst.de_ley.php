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
//selecionando la tabla bandera
$Sql="
			SELECT 
			sareta.tipo_tasa.id_tipo_tasa,
						id_ley,
						articulo,
  						paragrafo,
  						descripcion,
  						tipo_tasa.nombre,
  						tarifa,
  						tonelaje_inicial,
  						tonelaje_final,
  						activo,
  						sareta.ley.obs,
  						sareta.ley.ultimo_usuario
			FROM 
				sareta.ley,sareta.tipo_tasa 
			WHERE 
			 ley.id_tipo_tasa= tipo_tasa.id_tipo_tasa 
			 and 
			 ley.id_ley=ley.id_ley
			 and
			descripcion like '".$_GET['busq_nombre_ley']."%'
			ORDER BY 
				ley.id_ley
			;
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
			$this->Cell(0,10,'LISTADO DE LEYES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(10,		6,			'ART',		0,0,'C',1);
			$this->Cell(10,	6,				'PAR',			0,0,'C',1);
			$this->Cell(70,6,				'DESCRIPCION',			0,0,'L',1);
			$this->Cell(60,	6,				'TASA',			0,0,'L',1);
			$this->Cell(15,	6,				'TARIFA',			0,0,'C',1);
			$this->Cell(15,	6,				'ACTIVO',			0,0,'C',1);
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
		
		
		$Logitud_Max_Texto = strlen($row->fields("descripcion"));
		$Carateres_en_linea=45;
		$Cantidad_lineas_total=0;
		$resta=$Logitud_Max_Texto;
		do{
        $resta = $resta- ($Carateres_en_linea+1);
		$Cantidad_lineas_total +=  1 ;
	}	while($resta>=0);
	
	

		$esp=6*$Cantidad_lineas_total;
											
											$pdf->Cell(10,$esp,$Cont,1,0,'C');
									
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->MultiCell(10,$esp,utf8_decode($row->fields("articulo")),1,'C','L');
											$pdf->SetXY($x+10,$y);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(10,$esp,utf8_decode($row->fields("paragrafo")),1,'C','L');
											$pdf->SetXY($x+10,$y);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
										$pdf->line($x1,$y,$x+70,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(70,6,utf8_decode(str_replace("\n"," ",$row->fields("descripcion"))),0,'J',0);
											$pdf->SetXY($x+70,$y);
											
											if($Cantidad_lineas_total==1){
											$pdf->line($x1,$y+$esp,$x+70,$y+$esp);
											}else{													
											$pdf->line($x1,$y+$esp,$x+70,$y+$esp);
											}
											
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(60,$esp,utf8_decode($row->fields("nombre")),1,'LBR','L');
											$pdf->SetXY($x+60,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(15,$esp,utf8_decode($row->fields("tarifa")),1,'R','L');
											$pdf->SetXY($x+15,$y);
											
											
			$activo=$row->fields("activo");
			if(($activo=="t")){$activo="Si";}
			else{$activo="No";}
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(15,$esp,$activo,1,'C','L');
											$pdf->SetXY($x+15,$y);
		
		$pdf->Ln($esp);
		
	
		
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