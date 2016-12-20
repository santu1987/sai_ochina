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

$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE id_usuario=".$_SESSION['id_usuario'];
					$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$delegacion=$row->fields("id_unidad_ejecutora");
					}



$Sql="
		SELECT 
				agencia_naviera.id_estado,						
				id_agencia_naviera,
				id_delegacion,
				nombre,
				rif,
				nit,
				direccion,
				estado.nom_es,
				codigo_area,
				zona,
				apartado,
				telefono1,
				telefono2,
				fax1,
				fax2,
				pag_web,
				correo_electronico,
				contacto,
				cedula,
				cargo,
				codigo_auxiliar,
				comentario
			FROM 
				sareta.agencia_naviera,estado 
			WHERE 
			 	agencia_naviera.id_estado=estado.id_es and agencia_naviera.id_agencia_naviera=agencia_naviera.id_agencia_naviera
			 	and 
			 	id_delegacion=".$delegacion."
			 	and
			 	upper(agencia_naviera.nombre) like '".strtoupper($_GET['busq_nombre_agencia_naviera'])."%'
			ORDER BY 
				agencia_naviera.id_agencia_naviera
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
			$this->Ln();
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'LISTADO DE AGENCIAS NAVIERAS',0,0,'C');
			$this->Ln(10);
			
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor($colores["fill_cabeceras_columnas"]);
			$this->SetTextColor($colores["text_cabeceras_columnas"]);
			$this->Cell(10,		6,			'N',		0,0,'C',1);
			$this->Cell(50,		6,			'NOMBRE',		0,0,'C',1);
			$this->Cell(25,	6,				'RIF',			0,0,'C',1);
			$this->Cell(25,6,				'NIT',			0,0,'C',1);
			$this->Cell(25,	6,				'ESTADO',			0,0,'C',1);
			$this->Cell(35,	6,				'TELF-FAX',			0,0,'C',1);
			$this->Cell(35,	6,				'CONTACTO',			0,0,'C',1);
			$this->Cell(72,	6,				'DIRECCION',			0,0,'C',1);
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
	while (!$row->EOF) 
	{
		
	$Cont+=1;
	$Cantidad_lineas_total=0;
	$esp1=0;
	$esp2=0;
	$esp3=0;
	$esp4=0;
	$div=1;
	//contar las lineas de la dire
		$Logitud_Max_Texto_direccion=0;
		$Carateres_en_linea_direccion=0;
		$Logitud_Max_Texto_direccion = strlen($row->fields("direccion"));
		$Carateres_en_linea_direccion=50;
		$Cantidad_lineas_total_direccion=0;
		$resta_direccion=$Logitud_Max_Texto_direccion;
		do{
        $resta_direccion = $resta_direccion- ($Carateres_en_linea_direccion+1);
		$Cantidad_lineas_total_direccion +=  1 ;
		}	while($resta_direccion>=0);
	//contar las lineas de la nombre
		$Logitud_Max_Texto_nombre=0;
		$Carateres_en_linea_nombre=0;
		$Logitud_Max_Texto_nombre = strlen($row->fields("nombre"));
		$Carateres_en_linea_nombre=25;
		$Cantidad_lineas_total_nombre=0;
		$resta_nombre=$Logitud_Max_Texto_nombre;
		do{
        $resta_nombre = $resta_nombre- ($Carateres_en_linea_nombre+1);
		$Cantidad_lineas_total_nombre +=  1 ;
		}	while($resta_nombre>=0);
	//contar las lineas del contacto
		$Logitud_Max_Texto_contacto=0;
		$Carateres_en_linea_contacto=0;
		$Logitud_Max_Texto_contacto = strlen($row->fields("contacto"));
		$Carateres_en_linea_contacto=13;
		$Cantidad_lineas_total_contacto=0;
		$resta_contacto=$Logitud_Max_Texto_contacto;
		do{
        $resta_contacto = $resta_contacto- ($Carateres_en_linea_contacto+1);
		$Cantidad_lineas_total_contacto +=  1 ;
		}	while($resta_contacto>=0);
		

//contar las lineas del TelFax

			$fax=$row->fields("fax1");
			$faxPaso="";
			if(!$fax==""){$faxPaso="Fax(".$fax.")";}
			$String="Cod(".$row->fields("codigo_area").")-Tel(".$row->fields("telefono1").") ".$faxPaso;
			$cantidad_Str=strlen($String);

		$Logitud_Max_Texto_tf=0;
		$Carateres_en_linea_tf=0;
		$Logitud_Max_Texto_tf =$cantidad_Str;
		$Carateres_en_linea_tf=25;
		$Cantidad_lineas_total_tf=0;
		$resta_tf=$Logitud_Max_Texto_tf;
		do{
        $resta_tf = $resta_tf- ($Carateres_en_linea_tf+1);
		$Cantidad_lineas_total_tf +=  1 ;
		}	while($resta_tf>=0);


			if($Cantidad_lineas_total_nombre>$Cantidad_lineas_total_direccion 
			&& $Cantidad_lineas_total_nombre>$Cantidad_lineas_total_contacto){
				$Cantidad_lineas_total=$Cantidad_lineas_total_nombre;
				$esp=6*$Cantidad_lineas_total;
				$esp4=$esp/$Cantidad_lineas_total_nombre;
				$esp3=$esp/$Cantidad_lineas_total_nombre;
				$esp2=$esp/$Cantidad_lineas_total_nombre;
				$esp1=6;
				$div=1.5;
			
			}
			elseif($Cantidad_lineas_total_direccion>$Cantidad_lineas_total_nombre 
			&& $Cantidad_lineas_total_direccion>$Cantidad_lineas_total_contacto){			
				$Cantidad_lineas_total=$Cantidad_lineas_total_direccion;
				$esp=6*$Cantidad_lineas_total;
				$esp1=$esp/$Cantidad_lineas_total_direccion;
				$esp2=6;
				$esp3=$esp/$Cantidad_lineas_total_direccion;
				$esp4=$esp/$Cantidad_lineas_total_direccion;
				$div=1.5;
				
			}
			elseif($Cantidad_lineas_total_tf>$Cantidad_lineas_total_nombre 
			&& $Cantidad_lineas_total_tf>$Cantidad_lineas_total_contacto 
			&& $Cantidad_lineas_total_tf>$Cantidad_lineas_total_direccion){
				$Cantidad_lineas_total=$Cantidad_lineas_total_tf;
				$esp=6*$Cantidad_lineas_total;
				$esp1=$esp/$Cantidad_lineas_total_tf;
				$esp2=$esp/$Cantidad_lineas_total_tf;
				$esp3=$esp/$Cantidad_lineas_total_tf;
				$esp4=6;
				$div=1.5;
			
			}
			elseif($Cantidad_lineas_total_contacto>$Cantidad_lineas_total_nombre 
			&& $Cantidad_lineas_total_contacto>$Cantidad_lineas_total_direccion 
			&& $Cantidad_lineas_total_contacto>$Cantidad_lineas_total_tf ){
				$Cantidad_lineas_total=$Cantidad_lineas_total_contacto;
				$esp=6*$Cantidad_lineas_total;
				$esp1=$esp/$Cantidad_lineas_total_contacto;
				$esp2=$esp/$Cantidad_lineas_total_contacto;
				$esp3=6;
				$esp4=$esp/$Cantidad_lineas_total_contacto;
				$div=1.5;
			
			}
			
			elseif($Cantidad_lineas_total_contacto=$Cantidad_lineas_total_nombre ){
				$Cantidad_lineas_total=$Cantidad_lineas_total_nombre;
				$esp=6*$Cantidad_lineas_total;
				$esp4=$esp/$Cantidad_lineas_total_nombre;
				$esp3=$esp/$Cantidad_lineas_total_nombre;
				$esp2=$esp/$Cantidad_lineas_total_nombre;
				$esp1=$esp/$Cantidad_lineas_total_nombre;
				$div=1;
				
			}
			
			
			
		
										
	
											$pdf->Cell(10,$esp,$Cont,0,0,'C');
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$x1=$x;
											$y1=$y;
											$pdf->line($x-10,$y+$esp,$x-10,$y1);
											$pdf->line($x,$y+$esp,$x,$y1);
											$pdf->line($x1-10,$y,$x+267,$y);
											
											$pdf->MultiCell(50,$esp1,utf8_decode($row->fields("nombre")),0,1,'C',0);
											$pdf->SetXY($x+50,$y);
											$pdf->line($x+50,$y+$esp,$x+50,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("rif")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp1/$div,$row->fields("nit"),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(25,$esp1/$div,utf8_decode($row->fields("nom_es")),0,1,'C',0);
											$pdf->SetXY($x+25,$y);
											$pdf->line($x+25,$y+$esp,$x+25,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(35,$esp4,utf8_decode($String),0,1,'C',0);
											$pdf->SetXY($x+35,$y);
											$pdf->line($x+35,$y+$esp,$x+35,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(35,$esp3,utf8_decode($row->fields("contacto")),0,1,'C',0);
											$pdf->SetXY($x+35,$y);
											$pdf->line($x+35,$y+$esp,$x+35,$y1);
											
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(72,$esp2,utf8_decode(str_replace("\n"," ",$row->fields("direccion"))),0,'J',0);
											$pdf->SetXY($x+72,$y);
											
											if($Cantidad_lineas_total==0){
											$pdf->line($x1-10,$y+$esp,$x+72,$y+$esp);
											$pdf->line($x+72,$y+6,$x+72,$y1);
											}else{													
											$pdf->line($x1-10,$y+$esp,$x+72,$y+$esp);
											$pdf->line($x+72,$y+$esp,$x+72,$y1);}

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