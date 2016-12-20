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





$Sql="SELECT 
			  sareta.moneda.nombre AS moneda,
			  sareta.cambio_moneda.fecha_cambio AS fecha,
			  sareta.cambio_moneda.valor,
			  sareta.cambio_moneda.obs
			FROM 
				sareta.cambio_moneda

LEFT OUTER JOIN sareta.moneda
ON  sareta.moneda.id_moneda= sareta.cambio_moneda.id_moneda
WHERE upper(sareta.moneda.nombre) like '".strtoupper($_GET['busq_nombre_cambio_moneda'])."%'	 
			 ORDER BY 
				sareta.moneda.nombre,sareta.cambio_moneda.fecha_cambio 
			 DESC;
		
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
			$this->Cell(0,10,'CAMBIOS DE MONEDAS',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);

			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(30,		6,			'FECHA DE CAMBIO',		0,0,'C',1);
			$this->Cell(60,	6,				'MONEDA',			0,0,'C',1);
			$this->Cell(25,	6,				'VALOR',			0,0,'C',1);
			$this->Cell(65,	6,				'COMENTARIO',			0,0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-18);
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
	$Cantidad_lineas_total=0;
	$esp1=0;
	$esp2=0;
	$esp3=0;
	$div=1;
	//contar las lineas de la descricion
		$Logitud_Max_Texto_comentario=0;
		$Carateres_en_linea_comentario=0;
		$Logitud_Max_Texto_comentario = strlen($row->fields("obs"));
		$Carateres_en_linea_comentario=55;
		$Cantidad_lineas_total_comentario=0;
		$resta_comentario=$Logitud_Max_Texto_comentario;
		do{
        $resta_comentario = $resta_comentario- ($Carateres_en_linea_comentario+1);
		$Cantidad_lineas_total_comentario +=  1 ;
		}	while($resta_comentario>=0);
	//contar las lineas de la delegación
		$Logitud_Max_Texto_delegacion=0;
		$Carateres_en_linea_delegacion=0;
		$Logitud_Max_Texto_delegacion = strlen($row->fields("moneda"));
		$Carateres_en_linea_delegacion=60;
		$Cantidad_lineas_total_delegacion=0;
		$resta_delegacion=$Logitud_Max_Texto_delegacion;
		do{
        $resta_delegacion = $resta_delegacion- ($Carateres_en_linea_delegacion+1);
		$Cantidad_lineas_total_delegacion +=  1 ;
		}	while($resta_delegacion>=0);
	//contar las lineas de la descripción
		$Logitud_Max_Texto_decripcion=0;
		$Carateres_en_linea_decripcion=0;
		$Logitud_Max_Texto_decripcion = strlen(number_format($row->fields("valor"),2,',','.'));
		$Carateres_en_linea_decripcion=18;
		$Cantidad_lineas_total_decripcion=0;
		$resta_decripcion=$Logitud_Max_Texto_decripcion;
		do{
        $resta_decripcion = $resta_decripcion- ($Carateres_en_linea_decripcion+1);
		$Cantidad_lineas_total_decripcion +=  1 ;
		}	while($resta_decripcion>=0);
		
		
		if($Cantidad_lineas_total_comentario>$Cantidad_lineas_total_delegacion && 
		$Cantidad_lineas_total_comentario>$Cantidad_lineas_total_decripcion){
		$Cantidad_lineas_total=$Cantidad_lineas_total_comentario;
		$esp1=6;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_comentario;
		$esp3=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_comentario;
		$div=1.3;
		}
		else if($Cantidad_lineas_total_delegacion>$Cantidad_lineas_total_comentario&& 
        $Cantidad_lineas_total_delegacion>$Cantidad_lineas_total_decripcion )
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_delegacion;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_delegacion;
		$esp2=6;
		$esp3=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_delegacion;
		$div=1.5;
		}
		else if($Cantidad_lineas_total_decripcion>$Cantidad_lineas_total_delegacion && 
        $Cantidad_lineas_total_decripcion>$Cantidad_lineas_total_comentario )
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_decripcion;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_decripcion;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_decripcion;
		$esp3=6;
		$div=1.5;
		}else if($Cantidad_lineas_total_decripcion=$Cantidad_lineas_total_delegacion && 
        $Cantidad_lineas_total_decripcion=$Cantidad_lineas_total_comentario )
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_decripcion;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_decripcion;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_decripcion;
		$esp3=6;
		$div=1;
		}
		
	$esp=6*$Cantidad_lineas_total;


										
											
											$pdf->Cell(10,$esp,$Cont,0,0,'C');
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x-10,$y+$esp,$x-10,$y1);
											$pdf->line($x,$y+$esp,$x,$y1);
											
											$pdf->MultiCell(30,$esp1,utf8_decode(substr($row->fields("fecha"),0,10)),0,1,'C',0);
											$pdf->SetXY($x+30,$y);
											$pdf->line($x+30,$y+$esp,$x+30,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(60,$esp1,utf8_decode($row->fields("moneda")),0,1,'C',0);
											$pdf->SetXY($x+60,$y);
											$pdf->line($x+60,$y+$esp,$x+60,$y1);
											
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp1,utf8_decode(number_format($row->fields("valor"),2,',','.')),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(65,$esp2/$div,utf8_decode(str_replace("\n"," ",$row->fields("obs"))),0,'J',0);
											$pdf->SetXY($x+65,$y);
																	
											$pdf->line($x1-10,$y+$esp,$x+65,$y+$esp);
											$pdf->line($x+65,$y+$esp,$x+65,$y1);
											$pdf->line($x1-10,$y,$x+65,$y);

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