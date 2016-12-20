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
//selecionando la tabla clases_buques
$Sql="SELECT 
				sareta.clases_buques.nombre, 
				sareta.clases_buques.abreviatura, 
				sareta.clases_buques.obs 
			FROM 
				sareta.clases_buques
				WHERE 
				upper(sareta.clases_buques.nombre) like '".strtoupper($_GET['busq_nombre_clases_buques'])."%'
				or
				sareta.clases_buques.nombre like '".$_GET['busq_nombre_clases_buques']."%'
				ORDER BY 
				sareta.clases_buques.nombre 
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
			$this->Cell(0,10,'LISTADO DE CLASES DE BUQUES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(50,		6,			'NOMBRE',		0,0,'L',1);
			$this->Cell(10,	6,			'ABR',			0,0,'L',1);
			$this->Cell(120,	6,			utf8_decode('OBSERVACIÓN'),			0,0,'L',1);
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
	$pdf->SetFillColor($colores["fill_celdas"]);
	$pdf->SetTextColor($colores["text_celdas"]);
	$Cont=0;
	while (!$row->EOF) 
	{
		
		$Cont+=1;
	
	//contar las lineas de la obs
		$Logitud_Max_Texto_obs=0;
		$Carateres_en_linea_obs=0;
		$Logitud_Max_Texto_obs = strlen($row->fields("obs"));
		$Carateres_en_linea_obs=100;
		$Cantidad_lineas_total_obs=0;
		$resta_obs=$Logitud_Max_Texto_obs;
		do{
        $resta_obs = $resta_obs- ($Carateres_en_linea_obs+1);
		$Cantidad_lineas_total_obs +=  1 ;
		}	while($resta_obs>=0);

//contar las lineas de la descricion
		$Logitud_Max_Texto_nombre=0;
		$Carateres_en_linea_nombre=0;
		$Logitud_Max_Texto_nombre = strlen($row->fields("nombre"));
		$Carateres_en_linea_nombre=20;
		$Cantidad_lineas_total_nombre=0;
		$resta_nombre=$Logitud_Max_Texto_nombre;
		do{
        $resta_nombre = $resta_nombre- ($Carateres_en_linea_nombre+1);
		$Cantidad_lineas_total_nombre +=  1 ;
		}	while($resta_nombre>=0);




if($Cantidad_lineas_total_obs>=$Cantidad_lineas_total_nombre){
		$Cantidad_lineas_total=$Cantidad_lineas_total_obs;
		$esp1=6;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_obs;
		$div=1.3;
		}
		else 
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre;
		$esp2=6;
		$div=1.5;
		}

		$esp=6*$Cantidad_lineas_total;
		
											$pdf->Cell(10,$esp,$Cont,1,0,'C');
									
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(50,$esp1/$div,utf8_decode($row->fields("nombre")),0,1,'C',0);
											$pdf->SetXY($x+50,$y);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->MultiCell(10,$esp,utf8_decode($row->fields("abreviatura")),1,1,'C',0);
											$pdf->SetXY($x+10,$y);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(120,$esp2/$div,utf8_decode(str_replace("\n"," ",$row->fields("obs"))),0,1,'C',0);
											$pdf->SetXY($x+120,$y);
											
											if($Cantidad_lineas_total==1){
											$pdf->line($x1-50,$y+$esp,$x+120,$y+$esp);
												$pdf->line($x+120,$y+$esp,$x+120,$y1);
												$pdf->line($x1-50,$y,$x+120,$y);
											}else{													
											$pdf->line($x1-50,$y+$esp,$x+120,$y+$esp);
												$pdf->line($x+120,$y+$esp,$x+120,$y1);
												$pdf->line($x1-50,$y,$x+120,$y);
											}
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