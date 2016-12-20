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
$where = " WHERE  puerto_hasta.nombre like '%".$_GET['busq_nombre_distancia']."%' or puerto_desde.nombre like '%".$_GET['busq_nombre_distancia']."%'";
$Sql.="SELECT
	id_distancia 
		AS id,
	bandera_desde.id 
		AS id_org,	
	bandera_desde.nombre 
		AS puerto_org,
	puerto_desde.id_puerto 
		AS id_puerto_org,
	puerto_desde.nombre 
		AS nombre_pto_org,
	bandera_hasta.id 
		AS id_rec,
	bandera_hasta.nombre 
		AS puerto_rec,
	puerto_hasta.id_puerto 
		AS id_puerto_rec,
	puerto_hasta.nombre 
		AS nombre_pto_rec,
		millas,
		comentario
FROM  
	sareta.distancia_puerto
INNER JOIN
	sareta.bandera AS bandera_desde
ON
	bandera_desde.id=id_bandera_desde
INNER JOIN 
	sareta.bandera AS bandera_hasta
ON
	bandera_hasta.id=id_bandera_hasta
INNER JOIN
	sareta.puerto AS puerto_desde
ON
	puerto_desde.id_puerto=id_puerto_desde
INNER JOIN 
	sareta.puerto AS puerto_hasta
ON
	puerto_hasta.id_puerto=id_puerto_hasta
			".$where;

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
			$this->SetFont('Arial','B',16);
			$this->Ln();
			$this->Cell(0,10,'LISTADO DE DISTANCIA ENTRE PUERTOS',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(25,		6,			'BANDERA ORG',		0,0,'L',1);
			$this->Cell(25,	6,			'PTO. ORIGEN',			0,0,'L',1);
			$this->Cell(25,		6,			'BANDERA REC',		0,0,'L',1);
			$this->Cell(25,	6,			'PTO. RECALADA',			0,0,'L',1);
			$this->Cell(25,	6,			'MILLAS',			0,0,'L',1);
			$this->Cell(55,	6,			utf8_decode('COMENTARIO'),			0,0,'L',1);
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
			$this->Code128(87,286,utf8_decode(strtoupper($_SESSION['usuario'])),40,6);
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
	
	//contar las lineas de la obs
		$Logitud_Max_Texto_obs=0;
		$Carateres_en_linea_obs=1;
		$Logitud_Max_Texto_obs = strlen($row->fields("comentario"));
		$Carateres_en_linea_obs=47;
		$Cantidad_lineas_total_obs=0;
		$resta_obs=$Logitud_Max_Texto_obs;
		do{
        $resta_obs = $resta_obs- ($Carateres_en_linea_obs+1);
		$Cantidad_lineas_total_obs +=  1 ;
		}	while($resta_obs>=0);

//contar las lineas de la nombre
		$Logitud_Max_Texto_nombre_org=0;
		$Carateres_en_linea_nombre_org=1;
		$Logitud_Max_Texto_nombre_org = strlen($row->fields("nombre_pto_org"));
		$Carateres_en_linea_nombre_org=20;
		$Cantidad_lineas_total_nombre_org=0;
		$resta_nombre_org=$Logitud_Max_Texto_nombre_org;
		do{
        $resta_nombre_org = $resta_nombre_org- ($Carateres_en_linea_nombre_org+1);
		$Cantidad_lineas_total_nombre_org +=  1 ;
		}	while($resta_nombre_org>=0);

//contar las lineas de la nombre
		$Logitud_Max_Texto_nombre_rec=0;
		$Carateres_en_linea_nombre_rec=1;
		$Logitud_Max_Texto_nombre_rec = strlen($row->fields("nombre_pto_rec"));
		$Carateres_en_linea_nombre_rec=20;
		$Cantidad_lineas_total_nombre_rec=0;
		$resta_nombre_rec=$Logitud_Max_Texto_nombre_rec;
		do{
        $resta_nombre_rec = $resta_nombre_rec- ($Carateres_en_linea_nombre_rec+1);
		$Cantidad_lineas_total_nombre_rec +=  1 ;
		}	while($resta_nombre_rec>=0);



if($Cantidad_lineas_total_obs>=$Cantidad_lineas_total_nombre_org && $Cantidad_lineas_total_obs>=$Cantidad_lineas_total_nombre_rec){
		$Cantidad_lineas_total=$Cantidad_lineas_total_obs;
		$esp1=6;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_obs;
		$div=1.3;
		}
		else if($Cantidad_lineas_total_nombre_org>=$Cantidad_lineas_total_nombre_rec)
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_org;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_org;
		$esp2=6;
		$div=1.5;
		}
		else
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_rec;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_rec;
		$esp2=6;
		$div=1.5;
		}
		

		$esp=6*$Cantidad_lineas_total;
		
											$pdf->Cell(10,$esp,$Cont,1,0,'C');
									
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("puerto_org")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
										
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("nombre_pto_org")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("puerto_rec")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("nombre_pto_rec")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("millas")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(55,$esp2/$div,utf8_decode(str_replace("\n"," ",$row->fields("comentario"))),0,'J',0);
											$pdf->SetXY($x+55,$y);
											
											if($Cantidad_lineas_total==1){
											$pdf->line($x1-25,$y+$esp,$x+55,$y+$esp);
												$pdf->line($x+55,$y+$esp,$x+55,$y1);
												$pdf->line($x1-25,$y,$x+55,$y);
											}else{													
											$pdf->line($x1-25,$y+$esp,$x+55,$y+$esp);
												$pdf->line($x+55,$y+$esp,$x+55,$y1);
												$pdf->line($x1-25,$y,$x+55,$y);
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