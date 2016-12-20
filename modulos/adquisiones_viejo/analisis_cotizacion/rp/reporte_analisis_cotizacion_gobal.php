<?php
if (!$_SESSION) session_start();
$nombres = $_SESSION[nombre].' '.$_SESSION[apellido];
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

$nro_requisicion = $_GET['nro_requisicion'];
$id_requisicion = $_GET['id_requisicion'];
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// ?id_requisicion=1&nro_requisicion=090001
/*
	VERIFICA CUANTOS ITEMS TIENE LA REQUISION
*/
$sql_requi = "
SELECT 
	count(secuencia)
FROM 
	requisicion_detalle
WHERE
	id_organismo = ".$_SESSION['id_organismo']."
AND
	numero_requision = '$nro_requisicion'
";
$row_requi=& $conn->Execute($sql_requi);

if(!$row_requi->EOF){
	$nro_renglones = $row_requi->fields("count");
	//echo $nro_renglones;
}
/*
	VERIFICA CUANTOS COTIZACIONES TIENE LA REQUISION
*/
$sql_coti = "
SELECT 
	count(id_solicitud_cotizacione)
FROM 
	\"solicitud_cotizacionE\"
WHERE
	id_organismo = ".$_SESSION['id_organismo']."
AND
	id_requisicion = $id_requisicion
";
$row_coti=& $conn->Execute($sql_coti);

if(!$row_coti->EOF){
	$nro_cotizacion = $row_coti->fields("count");
	//echo $nro_renglones;
}
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////  CARGA DE DATOS BASICOS PARA GENERAR EL REPORTE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/*
	CARGA EL ENCABEZADO DE REQUICION
*/
$sql_requsicion = "
	SELECT 
		requisicion_encabezado.numero_requisicion,
		requisicion_encabezado.asunto,
		requisicion_encabezado.id_unidad_ejecutora
	FROM
		requisicion_encabezado
	WHERE
		numero_requisicion = '$nro_requisicion'
	ORDER BY 
		numero_requisicion	";

$row=& $conn->Execute($sql_requsicion);
if(!$row->EOF){
	$asunto = $row->fields("asunto");
	//echo $asunto;
}
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/*
	CARGA LOS PARAMETRO DE LA COTIZACION DONDE SE ENCUENTRAN LOS PUNTAJES PARA EL ANALISIS 
*/
$sql_co="
	SELECT 
		id_parametro_analisis_cotizacion, 
		id_organismo, 
		aspecto, 
		peso
	FROM 
		parametro_analisis_cotizacion
	ORDER BY
		id_parametro_analisis_cotizacion";
$row_otro=& $conn->Execute($sql_co);
if(!$row_otro->EOF){
	$aspecto = $row_otro->fields("aspecto");
	$peso = $row_otro->fields("peso");
	//echo $asunto;
}
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////FIN  CARGA DE DATOS BASICOS PARA GENERAR EL REPORTE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $asunto, $nro_requisicion;
			//global $proveedor1, $proveedor2, $proveedor3;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,8,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,4,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,4,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,4,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,4,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,4,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();			
			$this->Cell(0,4,'RIF G-20000451-7',0,0,'C');
			$this->Ln(8);
			$this->SetFillColor(200) ;
			$this->SetFont('Arial','B',10);
			$this->Cell(150,6,'ANALISIS DE COTIZACION'	,'LTB',0,'C',1);
			$this->Cell(127,6,'FECHA: '.date('d-m-Y')	,'RTB',0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','B',7);
			$this->Cell(277,10,'REFERENCIA '.$nro_requisicion.' '.$asunto,1,0,'L');
			/*$this->SetFont('arial','',9);
			$this->Cell(49,10, ''	,1,0,'C');
			$this->Cell(49,10, $proveedor2	,1,0,'C');
			$this->Cell(49,10, $proveedor3	,1,0,'C');*/
			$this->Ln(10);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			//$this->Cell(13,		10,		'Reglon',										1,0,'C',1);
			$this->Cell(93,		10,		'Proveedor',									1,0,'C',1);
			$y=$this->GetY();
			$this->MultiCell(25,		5,		'Puntaje segun el Precio',				1,'C',1);
			$this->SetXY(128,$y);
			$this->MultiCell(35,		5,		'Puntaje segun el Condiciones de pago',	1,'C',1);
			$this->SetXY(162,$y);
			$this->MultiCell(30,		5,		'Puntaje segun la Garantia',			1,'C',1);
			$this->SetXY(192,$y);
			$this->MultiCell(32,		5,		'Puntaje segun la Validez de la Oferta',1,'C',1);
			$this->SetXY(224,$y);
			$this->MultiCell(31,		5,		'Puntaje segun el Tiempo de Entrega',	1,'C',1);
			$this->SetXY(255,$y);
			$this->Cell(32,		10,		'Puntaje Total',		1,0,'C',1);
			$this->Ln(10);
		}
		//Pie de página
		function Footer()
		{
			global  $jefe_unidad, $unidad;
			//Posición: a 2,5 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont('Arial','',9);
			//Número de página
		/*	$this->Cell(50,3,	strtoupper( $_SESSION[nombre].' '.$_SESSION[apellido]) ,				0,0,'C');
			$this->Cell(70,3,	'TF. HECTOR TORREALBA' ,					0,0,'C');
			$this->Cell(70,3,	'CF. ERICA COROMOTO  VIRGUEZ' ,				0,0,'C');
			$this->Cell(70,3,	'CN. MAURICIO PIETROBON SANZONE' ,			0,0,'C');*/
			$this->Ln();
			$this->Cell(50,3,'COMPRADOR '. strtoupper( $_SESSION[nombre].' '.$_SESSION[apellido]),									0,0,'C');
			/*$this->Cell(70,3,'JEFE DE ADQUISICIONES' ,						0,0,'C');
			$this->Cell(70,3,'DIRECTOR DE ADMINISTRACION Y FINANZAS',		0,0,'C');
			$this->Cell(70,3,'DIRECTOR GENERAL DE OCHINA' ,					0,0,'C');*/
			//$nombres = "";
		}
	}
		$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,30);

/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//////////////////////////////////////////////  CARGA DE DATOS PARA REALIZAR EL ANALISIS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/*
	DATOS DE LA COTIZACION
*/
$se=0;
/*while($se < $nro_cotizacion){
$se++;*/	
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/*
	CUENTA EL NUMERO DE COTIZACIONES PARA UN ITEM DE LA REQUISION 
*/
$Sqlcoun = "	
	SELECT 
		count(\"solicitud_cotizacionE\".id_solicitud_cotizacione)
	FROM 
		\"solicitud_cotizacionE\"
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor

	WHERE
		\"solicitud_cotizacionE\".id_organismo = 1
	AND
		\"solicitud_cotizacionE\".ano = '2009'
	AND
		(\"solicitud_cotizacionE\".id_requisicion = $id_requisicion)	
	
				";
	//echo $Sqll.'<br>';
	$row_con_coti=& $conn->Execute($Sqlcoun);
	$coti_item = $row_con_coti->fields("count");
/////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
/*
	TRAE LOS DATOS DE LA COTIZACIONES PARA UN ITEM DE LA REQUISION 
*/
	$Sqll = "	
	SELECT 
		\"solicitud_cotizacionE\".id_solicitud_cotizacione, 
		\"solicitud_cotizacionE\".numero_cotizacion, 
		\"solicitud_cotizacionE\".id_proveedor, 
		proveedor.nombre AS proveedor,
		\"solicitud_cotizacionE\".id_requisicion, 
		\"solicitud_cotizacionE\".titulo, 
		\"solicitud_cotizacionE\".tiempo_entrega, 
		\"solicitud_cotizacionE\".lugar_entrega, 
		\"solicitud_cotizacionE\".condiciones_pago, 
		\"solicitud_cotizacionE\".validez_oferta,
		\"solicitud_cotizacionE\".garantia,
		(SELECT SUM(monto*cantidad) 
		FROM \"solicitud_cotizacionD\"
		WHERE (\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion)
		)AS monto
	FROM 
		\"solicitud_cotizacionE\"
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
	WHERE
		\"solicitud_cotizacionE\".id_organismo = 1
	AND
		\"solicitud_cotizacionE\".ano = '2009'
	AND
		(\"solicitud_cotizacionE\".id_requisicion = $id_requisicion)	
	ORDER BY
		id_solicitud_cotizacione
				";
	//echo $Sqll.'<br>';
	$row_coti=& $conn->Execute($Sqll);
	$i=0;
	while(!$row_coti->EOF){
	$i++;
		$id_solicitud_cotizacione[$i] = $row_coti->fields("id_solicitud_cotizacione");
		$numero_cotizacion[$i] = $row_coti->fields("numero_cotizacion");
		$id_proveedor[$i] = $row_coti->fields("id_proveedor");
		$proveedor[$i] = $row_coti->fields("proveedor");
		$titulo[$i] = $row_coti->fields("titulo");
		//echo ($proveedor[$i].'<br>');
		$tiempo_entrega[$i] = $row_coti->fields("tiempo_entrega");
		$lugar_entrega[$i] = $row_coti->fields("lugar_entrega");
		$condiciones_pago[$i] = $row_coti->fields("condiciones_pago");
		$validez_oferta[$i] = $row_coti->fields("validez_oferta");
		$garantia[$i] = $row_coti->fields("garantia");
		$monto[$i] = $row_coti->fields("monto");
		
		$row_coti->MoveNext();
	}
///////////////////////////////////////// PUNTAJE POR PRECIO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	
	for($t=1;$t<=$coti_item;$t++){
	$o=0;
	$punto_precio[$t]=$row_otro->fields("peso");;
		for($w=1;$w<=$coti_item;$w++){
			if($monto[$t]<=$monto[$w]){
			}else{
				$o++;
				//echo 'entro '.$o.'<br>';
				$punto_precio[$t]=$row_otro->fields("peso")-$o;
				//echo($punto_precio[$t].'<br>');
			}
			//echo($monto[$t].'&nbsp;'.$monto[$w].'<br>');
		}
		$punto_total[$t]=$punto_total[$t]+$punto_precio[$t];
		//}
		//$row_otro->MoveNext();
		//echo $punto_total[$t]."<br>";
	//for($t=1;$t<=$coti_item;$t++){
	$row_otro->MoveNext();	
///////////////////////////////////// PUNTAJE POR CONDICIONES DE PAGO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	
$o=0;
$punto_conpago[$t]=$row_otro->fields("peso");
		for($w=1;$w<=$coti_item;$w++){
			if($condiciones_pago[$t]<=$condiciones_pago[$w]){
				$o++;
				//echo('conpago '.$conpago[$t].'= '.$conpago[$w].'<br>');
				$punto_conpago[$t]=($row_otro->fields("peso")+1)-$o;
				
				if($punto_conpago[$t] <=0)
				{
					$punto_conpago[$t] = 1;
				}
			}
		}
		$punto_total[$t]=$punto_total[$t]+$punto_conpago[$t];
		//echo $punto_conpago[$t]."<br>";

$row_otro->MoveNext();
///////////////////////////////////// PUNTAJE POR GARANTIA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	

		$o=0;
		$punto_garantia[$t]=$row_otro->fields("peso");
			for($w=1;$w<=$coti_item;$w++){
				if($garantia[$t]<=$garantia[$w]){
					if($garantia[$t]!=$garantia[$w])
						$o++;
					//echo 'entro '.$o.'<br>';
					$punto_garantia[$t]=$row_otro->fields("peso")-$o;
					if($punto_garantia[$t] <=0)
					{
						$punto_garantia[$t] = 1;
					}
					//echo('puntos '.$punto_valoferta[$cp].'<br>');
				}
			}
			$punto_total[$t]=$punto_total[$t]+$punto_garantia[$t];	
			//echo $punto_garantia[$t]."<br>";
	//	echo $proveedor[$t].':&nbsp;&nbsp;'.$punto_precio[$t].'&nbsp;'.$punto_tiem_entraga[$t].'&nbsp;'.$punto_conpago[$t].'&nbsp;'.$punto_valoferta[$t].'&nbsp;'.$punto_garantia[$t]."<br>";
	$row_otro->MoveNext();		
///////////////////////////////////// PUNTAJE POR VALIDEZ DE LA OFERTA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	
		$o=0;
		$punto_valoferta[$t]=3;
			for($w=1;$w<=$coti_item;$w++){
				if($validez_oferta[$t]<=$validez_oferta[$w]){
					if($validez_oferta[$t]!=$validez_oferta[$w])
						$o++;
					$punto_valoferta[$t]=$row_otro->fields("peso")-$o;
					if($punto_valoferta[$t] <=0)
					{
						$punto_valoferta[$t] = 1;
					}
				}
			}
			$punto_total[$t]=$punto_total[$t]+$punto_valoferta[$t];
		//	echo $punto_valoferta[$t]."<br>";
		$row_otro->MoveNext();
		///////////////////////////////////// PUNTAJE POR TIEMPO DE ENTREGA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	
		$o=0;
		$punto_tiem_entraga[$t]=2;
		for($w=1;$w<=$coti_item;$w++){
			if($tiempo_entrega[$t]<=$tiempo_entrega[$w]){

			}else{
				$o++;
				//echo 'entro '.$o.'<br>';
				$punto_tiem_entraga[$t]=$row_otro->fields("peso")-$o;
				if($punto_tiem_entraga[$t] <=0)
				{
					$punto_tiem_entraga[$t] = 1;
				}
			
			}
		}
		$punto_total[$t]=$punto_total[$t]+$punto_tiem_entraga[$t];
		//echo $punto_tiem_entraga[$t]."<br>";

/////////////////////////////////////////
/////////////////////////////////////////
///////////////////////////////////// CONVERTIR RESULTADO EN UN ARREGLO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºº	
$row_otro->MoveFirst();
$prove[$t] = $proveedor[$t].'*'.$punto_precio[$t].'*'.$punto_conpago[$t].'*'.$punto_garantia[$t].'*'.$punto_valoferta[$t].'*'.$punto_tiem_entraga[$t].'*'.$punto_total[$t];
	//	echo $punto_total[$t]."<br>";
	//echo $prove[$t].'<br>';
	}
	//echo '<br>';
	//die();
	for($cp=1;$cp<=$coti_item;$cp++){
				for($w=1;$w<=$coti_item;$w++){

				$arrcp=explode ( "*", $prove[$cp]);
				$arrw=explode ( "*", $prove[$w]);
					if($arrcp[6]>=$arrw[6]){
						$temp= $prove[$cp];
						$prove[$cp]= $prove[$w];
						$prove[$w]= $temp;
					}
				}
		}
		
		$x=1;
		while ($x <= $coti_item ){
			//list($proveedor, $precio, $pago, $garantia, $oferta, $tiempo, $total) = explode ( "*", $prove[$x]);
			$arr2=explode ( "*", $prove[$x]);
		if($x ==1)
			$pdf->SetFont('arial','b',9);
		else
			$pdf->SetFont('arial','',9);
		//$pdf->Cell(13,		6,	$x,				'LRBT',0,'C',0);
		$pdf->Cell(93,		6,	$arr2[0],		'LRBT',0,'L',0);
		$pdf->Cell(25,		6,	$arr2[1],		'LRBT',0,'C',0);
		$pdf->Cell(34,		6,	$arr2[2],		'LRBT',0,'C',0);
		$pdf->Cell(30,		6,	$arr2[3],		'LRBT',0,'C',0);
		$pdf->Cell(32,		6,	$arr2[4],		'LRBT',0,'C',0);
		$pdf->Cell(31,		6,	$arr2[5],		'LRBT',0,'C',0);
		$pdf->Cell(32,		6,	$arr2[6],		'LRBT',0,'C',0);
		$pdf->Ln(6);
			$x++;
		} 
		
		$pdf->SetFont('arial','',9);
		

	$pdf->Ln(6);
	$punto_total = '';
	
	//echo "------------------------------------<br>";
	
//}
$pdf->Output();
?>