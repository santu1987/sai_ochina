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



$posi= strrpos($_GET['busq_nombre_buque'],";");


$buque= substr($_GET['busq_nombre_buque'],0,$posi);
$bandera= substr($_GET['busq_nombre_buque'],$posi+1,$posi2-1);




$tex=$_GET['busq_nombre_buque'];
$posi3= strrpos($tex,"*");
$clase= substr($tex,$posi3+1);

$posi2= strrpos($tex,"%");
$texcorteActi= substr($tex,0,$posi3);
$actividad= substr($texcorteActi,$posi2+1);

$posi= strrpos($texcorteActi,";");
$texcorteBan= substr($texcorteActi,0,$posi2);
$bandera= substr($texcorteBan,$posi+1);

$tex=$_GET['busq_nombre_buque'];
$posi= strrpos($tex,";");
$buque= substr($tex,0,$posi);


$Sql.="SELECT
			matricula,
			call_sign,
			buque.nombre 
				AS nombre_buque,	
			bandera.nombre 
				AS nombre_bandera,
			r_bruto,
			actividad.nombre 
				AS nombre_actividad,
			clase.nombre 
				AS clase_buque,
			buque.nacionalidad 
				AS nac,
			buque.pago_anual,
			ley.descripcion,
			buque.exonerado
		FROM  
			sareta.buque
		
		INNER JOIN
			sareta.bandera
		ON
			sareta.bandera.id=id_bandera
		INNER JOIN
			sareta.tipo_actividad 
			AS actividad
		ON
			actividad.id_tipo_actividad=id_actividad
		INNER JOIN
			sareta.clases_buques 
			AS  clase
		ON
			clase.id_clases_buques=id_clase
		INNER JOIN
			sareta.ley
		ON
			sareta.ley.id_ley=sareta.buque.id_ley
			WHERE  sareta.buque.nombre like upper('".$buque."%') 
			and  sareta.bandera.nombre like '%".$bandera."%'
			and  actividad.nombre like '%".$actividad."%'
			and  clase.nombre like '%".$clase."%'
		ORDER BY 
				sareta.buque.nombre
		
			" ;

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
			$this->Cell(0,10,'LISTADO DE BUQUES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(20,		6,			'MATRICULA',		0,0,'L',1);
			$this->Cell(20,	6,				'CALL SIGN',			0,0,'L',1);
			$this->Cell(25,		6,			'NOMBRE',		0,0,'L',1);
			$this->Cell(20,	6,				'BANDERA',			0,0,'L',1);
			$this->Cell(20,	6,				'R. BRUTO',			0,0,'L',1);
			$this->Cell(35,	6,				'ACTIVIDAD',			0,0,'L',1);
			$this->Cell(25,	6,				'CLASE',			0,0,'L',1);
			$this->Cell(20,	6,				'NAC/EXT',			0,0,'L',1);
			$this->Cell(20,	6,				'PAGO ANUAL',			0,0,'L',1);
			$this->Cell(42,	6,				'LEY',			0,0,'L',1);
			$this->Cell(20,	6,				'EXONERADO',			0,0,'L',1);
			
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
			$this->Cell(120,3,'Impreso por:',0,0,'R');
			$this->Cell(130,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(125,195,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF("L");
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$Cont=0;
	$cambio;
	while (!$row->EOF) 
	{
		
		$Cont+=1;
	
	//contar las lineas de la NOMBRE
		$Logitud_Max_Texto_nom=0;
		$Carateres_en_linea_nom=1;
		$Logitud_Max_Texto_nom = strlen($row->fields("nombre_buque"));
		$Carateres_en_linea_nom=15;
		$Cantidad_lineas_total_nom=0;
		$resta_nom=$Logitud_Max_Texto_nom;
		do{
        $resta_nom = $resta_nom- ($Carateres_en_linea_nom+1);
		$Cantidad_lineas_total_nom +=  1 ;
		}	while($resta_nom>=0);

//contar las lineas de la LEY
		$Logitud_Max_Texto_nombre_ley=0;
		$Carateres_en_linea_nombre_ley=1;
		$Logitud_Max_Texto_nombre_ley = strlen($row->fields("descripcion"));
		$Carateres_en_linea_nombre_ley=47;
		$Cantidad_lineas_total_nombre_ley=0;
		$resta_nombre_ley=$Logitud_Max_Texto_nombre_ley;
		do{
        $resta_nombre_ley = $resta_nombre_ley- ($Carateres_en_linea_nombre_ley+1);
		$Cantidad_lineas_total_nombre_ley +=  1 ;
		}	while($resta_nombre_ley>=0);

//contar las lineas de la actividad
		$Logitud_Max_Texto_nombre_act=0;
		$Carateres_en_linea_nombre_act=1;
		$Logitud_Max_Texto_nombre_act = strlen($row->fields("nombre_actividad"));
		$Carateres_en_linea_nombre_act=20;
		$Cantidad_lineas_total_nombre_act=0;
		$resta_nombre_act=$Logitud_Max_Texto_nombre_act;
		do{
        $resta_nombre_act = $resta_nombre_act- ($Carateres_en_linea_nombre_act+1);
		$Cantidad_lineas_total_nombre_act +=  1 ;
		}	while($resta_nombre_act>=0);


//contar las lineas de la clase 
		$Logitud_Max_Texto_nombre_cla=0;
		$Carateres_en_linea_nombre_cla=1;
		$Logitud_Max_Texto_nombre_cla = strlen($row->fields("clase_buque"));
		$Carateres_en_linea_nombre_cla=15;
		$Cantidad_lineas_total_nombre_cla=0;
		$resta_nombre_cla=$Logitud_Max_Texto_nombre_cla;
		do{
        $resta_nombre_cla = $resta_nombre_cla- ($Carateres_en_linea_nombre_cla+1);
		$Cantidad_lineas_total_nombre_cla +=  1 ;
		}	while($resta_nombre_cla>=0);
		
//contar las lineas de la bandera 
		$Logitud_Max_Texto_nombre_ban=0;
		$Carateres_en_linea_nombre_ban=1;
		$Logitud_Max_Texto_nombre_ban = strlen($row->fields("nombre_bandera"));
		$Carateres_en_linea_nombre_ban=10;
		$Cantidad_lineas_total_nombre_ban=0;
		$resta_nombre_ban=$Logitud_Max_Texto_nombre_ban;
		do{
        $resta_nombre_ban = $resta_nombre_ban- ($Carateres_en_linea_nombre_ban+1);
		$Cantidad_lineas_total_nombre_ban +=  1 ;
		}	while($resta_nombre_ban>=0);

if($Cantidad_lineas_total_nom>=$Cantidad_lineas_total_nombre_ley && $Cantidad_lineas_total_nom>=$Cantidad_lineas_total_nombre_act && $Cantidad_lineas_total_nom>=$Cantidad_lineas_total_nombre_cla && $Cantidad_lineas_total_nom>=$Cantidad_lineas_total_nombre_ban){
		$Cantidad_lineas_total=$Cantidad_lineas_total_nom;
		$esp1=6;
		$esp2=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nom;
		$div=1.3;
		}
		else if($Cantidad_lineas_total_nombre_ley>=$Cantidad_lineas_total_nombre_act && $Cantidad_lineas_total_nombre_ley>=$Cantidad_lineas_total_nombre_cla && $Cantidad_lineas_total_nombre_ley>=$Cantidad_lineas_total_nombre_nom && $Cantidad_lineas_total_nombre_ley>=$Cantidad_lineas_total_nombre_ban)
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_ley;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_ley;
		$esp2=6;
		$div=1.5;
		}
		else if($Cantidad_lineas_total_nombre_act>=$Cantidad_lineas_total_nombre_ley && $Cantidad_lineas_total_nombre_act>=$Cantidad_lineas_total_nombre_cla && $Cantidad_lineas_total_nombre_act>=$Cantidad_lineas_total_nombre_nom && $Cantidad_lineas_total_nombre_act>=$Cantidad_lineas_total_nombre_ban)
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_act;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_act;
		$esp2=6;
		$div=1.5;
		}
		else if($Cantidad_lineas_total_nombre_cla>=$Cantidad_lineas_total_nombre_nom && $Cantidad_lineas_total_nombre_cla>=$Cantidad_lineas_total_nombre_ley && $Cantidad_lineas_total_nombre_cla>=$Cantidad_lineas_total_nombre_act && $Cantidad_lineas_total_nombre_cla>=$Cantidad_lineas_total_nombre_ban)
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_cla;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_cla;
		$esp2=6;
		$div=1.5;
		}
		else
		{
		$Cantidad_lineas_total=$Cantidad_lineas_total_nombre_ban;
		$esp1=(6*$Cantidad_lineas_total)/$Cantidad_lineas_total_nombre_ban;
		$esp2=6;
		$div=1.5;
		}
		

		$esp=6*$Cantidad_lineas_total;
		
											$pdf->Cell(10,$esp,$Cont,1,0,'C');
									
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($row->fields("matricula")),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
												$pdf->line($x+20,$y+$esp,$x+20,$y);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
										
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($row->fields("call_sign")),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
												$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("nombre_buque")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
												$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($row->fields("nombre_bandera")),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
												$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($row->fields("r_bruto")),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
												$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
								
											$pdf->MultiCell(35,$esp2/$div,utf8_decode(str_replace("\n"," ",$row->fields("nombre_actividad"))),0,'J',0);
											$pdf->SetXY($x+35,$y);
											$pdf->line($x+35,$y+$esp,$x+35,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(25,$esp2/$div,utf8_decode(str_replace("\n"," ",$row->fields("clase_buque"))),0,'J',0);	
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
										
											if($row->fields("nac")=="t"){
											$cambio ="NACIONAL";
											}else{$cambio ="EXTRAJERO";
											}
										
										
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($cambio),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
											$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											if($row->fields("pago_anual")=="t"){
											$cambio ="SI";
											}else{$cambio ="NO";
											}
											
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($cambio),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
											$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											$pdf->MultiCell(42,$esp1/$div,utf8_decode($row->fields("descripcion")),0,'J',0);
											$pdf->SetXY($x+42,$y);
											$pdf->line($x+42,$y+$esp,$x+42,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											if($row->fields("exonerado")=="t"){
											$cambio ="SI";
											}else{$cambio ="NO";
											}
											
											$pdf->MultiCell(20,$esp1/$div,utf8_decode($cambio),0,1,'C',0);
											$pdf->SetXY($x+20,$y);
											$pdf->line($x+20,$y+$esp,$x+20,$y1);
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											
											if($Cantidad_lineas_total==1){
											$pdf->line($x1-20,$y+$esp,$x,$y+$esp);
											$pdf->line($x,$y+$esp,$x,$y1);
												$pdf->line($x1-20,$y,$x,$y);
											}else{													
											$pdf->line($x1-20,$y+$esp,$x,$y+$esp);
												$pdf->line($x,$y+$esp,$x,$y1);
												$pdf->line($x1-20,$y,$x,$y);
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