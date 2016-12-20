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
$sql3 = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql3);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}


$Sql="
			SELECT 
				  id,
				  nombre ,
				  factor ,
				  vida_propia ,
				  pago_inmediato ,
				  pago_posterior ,
				  calculo_mora ,
				  sareta.tipo_documento.id_numero_control,
				  numero_control.descripcion,
				  ultimo_numero ,
				  obs
			FROM 
				sareta.tipo_documento 
			INNER JOIN
				sareta.numero_control AS numero_control 
			ON
				numero_control.id_numero_control= tipo_documento.id_numero_control
			where sareta.tipo_documento.nombre like '".$_GET['busq_nombre_tipo_documento']."%' 
			and sareta.tipo_documento.id_delegacion=".$delegacion." 
			ORDER BY 
				sareta.tipo_documento.nombre
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
			$this->Cell(0,10,'LISTADO DE TIPO DE DOCUMENTOS',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(34,		6,			'NOMBRE',		0,0,'C',1);
			$this->Cell(15,	6,				'FACTOR',			0,0,'C',1);
			$this->Cell(20,6,				'VIDA PROPIA',			0,0,'L',1);
			$this->Cell(24,	6,				'PG. INMEDIATO',			0,0,'L',1);
			$this->Cell(24,	6,				'PG. POSTERIOR',			0,0,'C',1);
			$this->Cell(25,	6,				'CALC. DE MORA',			0,0,'C',1);
			$this->Cell(20,	6,				'SEC. ACTIVA',			0,0,'C',1);
			$this->Cell(19,	6,utf8_decode('ULTIMO Nº'),			0,0,'C',1);
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
	$esp=6;
	$esp1=6;
	$esp2=6;
	while (!$row->EOF) 
	{
		
		$factor=$row->fields("factor");
		if(($factor=="t")){	$factor="SUMA";}
		else{$factor="RESTA"; }
		
		$vida_propia=$row->fields("vida_propia");
		if(($vida_propia=="t")){$vida_propia="SI";}
		else{$vida_propia="NO"; }
		
		$Pg_intermedio=$row->fields("pago_inmediato");
		if(($Pg_intermedio=="t")){	$Pg_intermedio="SI";}
		else{$Pg_intermedio="NO"; }
		
		$Pg_posterior=$row->fields("pago_posterior");
		if(($Pg_posterior=="t")){$Pg_posterior="SI";}
		else{$Pg_posterior="NO"; }
		
		$calculo_mora=$row->fields("calculo_mora");
		if(($calculo_mora=="t")){	$calculo_mora="SI";}
		else{$calculo_mora="NO"; }
		
				
		$Cont+=1;
		
	//contar las lineas de la obs
		$Logitud_Max_Texto_nombre=0;
		$Carateres_en_linea_nombre=0;
		$Logitud_Max_Texto_nombre = strlen($row->fields("nombre"));
		$Carateres_en_linea_nombre=15;
		$Cantidad_lineas_total_nombre=0;
		$resta_nombre=$Logitud_Max_Texto_nombre;
		do{
        $resta_nombre = $resta_nombre- ($Carateres_en_linea_nombre+1);
		$Cantidad_lineas_total_nombre +=  1 ;
		}	while($resta_nombre>=0);

//contar las lineas de la descricion
		$Logitud_Max_Texto_desc=0;
		$Carateres_en_linea_desc=0;
		$Logitud_Max_Texto_desc = strlen($row->fields("descripcion"));
		$Carateres_en_linea_desc=8;
		$Cantidad_lineas_total_desc=1;
		$resta_desc=$Logitud_Max_Texto_desc;
		do{
        $resta_desc = $resta_desc- ($Carateres_en_linea_desc+1);
		$Cantidad_lineas_total_desc +=  1 ;
		}	while($resta_desc>=0);


	
	if($Cantidad_lineas_total_nombre > $Cantidad_lineas_total_desc){
			$esp=6*$Cantidad_lineas_total_nombre;
		$esp1=6;
		$esp2=6;
		}else{
			$esp=6*$Cantidad_lineas_total_desc;
		$esp1=6;
		$esp2=6;
			
			}
	
	
											$pdf->Cell(10,$esp,$Cont,1,0,'C');
									
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+34,$y);
											
											$pdf->MultiCell(34,$esp1,utf8_decode($row->fields("nombre")),0,'C',0);
											$pdf->SetXY($x+34,$y);
											$pdf->line($x+34,$y+$esp,$x+34,$y1);
											$pdf->line($x1,$y+$esp,$x+34,$y+$esp);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(15,$esp,utf8_decode($factor),1,'C','L');
											$pdf->SetXY($x+15,$y);
								// dibuja cuadro de vida propia			
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+20,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(20,$esp,utf8_decode($vida_propia),0,'C',0);
											$pdf->SetXY($x+20,$y);
											$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$pdf->line($x1,$y+$esp,$x+20,$y+$esp);
											
							// dibuja cuadro de Pg_intermedio		
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+24,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(24,$esp,utf8_decode($Pg_intermedio),0,'C',0);
											$pdf->SetXY($x+24,$y);
											$pdf->line($x+24,$y+$esp,$x+24,$y1);
											$pdf->line($x1,$y+$esp,$x+24,$y+$esp);
										
							// dibuja cuadro de Pg_posterior		
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+24,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(24,$esp,utf8_decode($Pg_posterior),0,'C',0);
											$pdf->SetXY($x+24,$y);
											$pdf->line($x+24,$y+$esp,$x+24,$y1);
											$pdf->line($x1,$y+$esp,$x+24,$y+$esp);
											
											
							// dibuja cuadro de calculo_mora		
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+25,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp,utf8_decode($calculo_mora),0,'C',0);
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$pdf->line($x1,$y+$esp,$x+25,$y+$esp);
											
											
							// dibuja cuadro de descripcion		
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+20,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(20,$esp2,utf8_decode($row->fields("descripcion")),0,'C',0);
											$pdf->SetXY($x+20,$y);
											$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$pdf->line($x1,$y+$esp,$x+20,$y+$esp);
								
								// dibuja cuadro de descripcion		
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x1,$y,$x+19,$y);
											
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(19,$esp2,utf8_decode($row->fields("ultimo_numero")),0,'C',0);
											$pdf->SetXY($x+19,$y);
											$pdf->line($x+19,$y+$esp,$x+19,$y1);
											$pdf->line($x1,$y+$esp,$x+19,$y+$esp);
		
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