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
//************************************************************************	$_SESSION["id_unidad_ejecutora"]
$cotizacion = $_GET['numero_coti'];
$ano = $_GET['ano'];
//$unidad = $_GET['unidad'];
$unidad_id = $_SESSION["id_unidad_ejecutora"];
$where = "WHERE (1=1) ";
if ($cotizacion != "")
	$where = $where . " AND	(numero_requisicion = '$cotizacion') AND (id_unidad_ejecutora = $unidad_id)";
if ($ano != "")
	$where = $where . " AND	(ano = '$ano') ";

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$sql_requsicion = "
	SELECT 
		requisicion_encabezado.numero_requisicion,
		requisicion_encabezado.asunto,
		requisicion_encabezado.id_unidad_ejecutora
	FROM
		requisicion_encabezado
	".$where."
	ORDER BY 
		numero_requisicion	";

$row=& $conn->Execute($sql_requsicion);

//-----------------------------------------------------
	$sqlunida="SELECT nombre, jefe_unidad FROM  unidad_ejecutora WHERE (id_unidad_ejecutora = ".$unidad_id.") ORDER BY id_unidad_ejecutora ";
	$rowunida= $conn->Execute($sqlunida);

	$jefe_unidad = $rowunida->fields("jefe_unidad");
	$unidad = $rowunida->fields("nombre");

$sql_proveedor1 = "SELECT 
	proveedor.nombre,
	tiempo_entrega,
	lugar_entrega,
	condiciones_pago,
	validez_oferta
FROM 
	requisicion_encabezado
INNER JOIN
	\"solicitud_cotizacionE\"
ON
	requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion ";
$sql_proveedor2 = "INNER JOIN
	proveedor
ON
	\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
WHERE
	(requisicion_encabezado.numero_requisicion = '$cotizacion')
AND 
	(requisicion_encabezado.id_unidad_ejecutora = $unidad_id)
ORDER BY 
	numero_cotizacion";
	$sql_proveedor =$sql_proveedor1 . $sql_proveedor2 ;
$row_proveedor= $conn->Execute($sql_proveedor);
if (!$row->EOF)
{ 	
	$c=0;
if(!$row_proveedor->EOF){

	while (!$row_proveedor->EOF) 
	{
		
		if ($c == 0) {
			$proveedor1 = $row_proveedor->fields('nombre');
			$tiempo_entrega1 = $row_proveedor->fields('tiempo_entrega');
			$lugar_entrega1 = $row_proveedor->fields('lugar_entrega');
			$condiciones_pago1 = $row_proveedor->fields('condiciones_pago');
			$validez_oferta1 = $row_proveedor->fields('validez_oferta');
		}
		if ($c == 1) {
			$proveedor2 = $row_proveedor->fields('nombre');
			$tiempo_entrega2 = $row_proveedor->fields('tiempo_entrega');
			$lugar_entrega2 = $row_proveedor->fields('lugar_entrega');
			$condiciones_pago2 = $row_proveedor->fields('condiciones_pago');
			$validez_oferta2 = $row_proveedor->fields('validez_oferta');
		}
		if ($c == 2) {
			$proveedor3 = $row_proveedor->fields('nombre');
			$tiempo_entrega3 = $row_proveedor->fields('tiempo_entrega');
			$lugar_entrega3 = $row_proveedor->fields('lugar_entrega');
			$condiciones_pago3 = $row_proveedor->fields('condiciones_pago');
			$validez_oferta3 = $row_proveedor->fields('validez_oferta');
		}
			$c++;
		$row_proveedor->MoveNext();
	}
}

//************************************************************************
/*if (!$row->EOF)
{*/ 
	//************************************************************************
	$numero_requisicion=$row->fields("numero_requisicion");
	$asunto=$row->fields("asunto");

	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $asunto, $numero_requisicion;
			global $proveedor1, $proveedor2, $proveedor3;
			
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
			$this->Cell(125,6,'FECHA: '.date('d-m-Y')	,'RTB',0,'C',1);
			$this->Ln(6);
			$this->SetFont('Arial','B',7);
			$this->Cell(128,10,'REFERENCIA '.$numero_requisicion.' '.$asunto,1,0,'L');
			$this->SetFont('arial','',9);
			$this->Cell(49,10, $proveedor1	,1,0,'C');
			$this->Cell(49,10, $proveedor2	,1,0,'C');
			$this->Cell(49,10, $proveedor3	,1,0,'C');
			$this->Ln(10);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(13,		6,		'Reglon',			1,0,'C',1);
			$this->Cell(17,		6,		'Cantidad',			1,0,'C',1);
			$this->Cell(17,		6,		'Uni. Med.',		1,0,'C',1);
			$this->Cell(81,		6,		'  Descripcion',	1,0,'L',1);
			$this->Cell(20,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(8,		6,		'IVA',				1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Cell(20,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(8,		6,		'IVA',				1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Cell(20,		6,		'Precio Unit.',		1,0,'C',1);
			$this->Cell(8,		6,		'IVA',				1,0,'C',1);
			$this->Cell(21,		6,		'Precio Total',		1,0,'C',1);
			$this->Ln(6);
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
			$this->Cell(50,3,	strtoupper( $_SESSION[nombre].' '.$_SESSION[apellido]) ,				0,0,'C');
			$this->Cell(70,3,	'TF. HECTOR TORREALBA' ,					0,0,'C');
			$this->Cell(70,3,	'CF. ERICA COROMOTO  VIRGUEZ' ,				0,0,'C');
			$this->Cell(70,3,	'CN. MAURICIO PIETROBON SANZONE' ,			0,0,'C');
			$this->Ln();
			$this->Cell(50,3,'COMPRADOR' ,									0,0,'C');
			$this->Cell(70,3,'JEFE DE ADQUISICIONES' ,						0,0,'C');
			$this->Cell(70,3,'DIRECTOR DE ADMINISTRACION Y FINANZAS',		0,0,'C');
			$this->Cell(70,3,'DIRECTOR GENERAL DE OCHINA' ,					0,0,'C');
			//$nombres = "";
		}
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 

	$total=0;
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(180);
	$pdf->SetAutoPageBreak(auto,30);
	
	$sql_datos= "SELECT 
		requisicion_detalle.numero_requision,
		requisicion_detalle.secuencia,
		requisicion_detalle.cantidad,
		unidad_medida.nombre,
		requisicion_detalle.descripcion
	FROM 
		requisicion_detalle
	INNER JOIN
		unidad_medida
	ON
		requisicion_detalle.id_unidad_medida = unidad_medida.id_unidad_medida
	WHERE
		(numero_requision = '$cotizacion')
	ORDER BY 
		numero_requision, secuencia";
	
	$row_requi=& $conn->Execute($sql_datos);
	$monto1 = 0; 
	$monto2 = 0;
	$monto3 = 0;
	$porcen1 =0;
	$porcen2 =0;
	$porcen3 =0;
	$iva1 = 0;
	$iva2 = 0;
	$iva3 = 0;
	while(!$row_requi->EOF)	{	
		$i++;
		$cadena = strlen($row_requi->fields('descripcion'));
		$coordenada= ceil($cadena / 60)*6;
		
			$pdf->Cell(13,		$coordenada,	$row_requi->fields('secuencia'),	'LRB',0,'C',0);
			$pdf->Cell(17,		$coordenada,	$row_requi->fields('cantidad'),		'LRB',0,'C',0);
			$pdf->Cell(17,		$coordenada,	$row_requi->fields('nombre'),		'LRB',0,'C',0);
			$y=$pdf->GetY();
			$x=$pdf->GetX();
			$x = $x +81;
			$y = $y +0.1;
			$pdf->SetFont('arial','',8);
			$pdf->MultiCell(81,	6,	$row_requi->fields('descripcion'),		'LRB'	,'L');
			$pdf->SetXY($x,$y);
			$pdf->SetFont('arial','',9);
	$sql_analisis="SELECT 
		\"solicitud_cotizacionD\".monto,
		\"solicitud_cotizacionD\".impuesto,
		(\"solicitud_cotizacionD\".cantidad * \"solicitud_cotizacionD\".monto)AS total
	FROM 
		requisicion_encabezado
	INNER JOIN
		\"solicitud_cotizacionE\"
	ON
		requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
	INNER JOIN
		\"solicitud_cotizacionD\"
	ON
		\"solicitud_cotizacionE\".numero_cotizacion = \"solicitud_cotizacionD\".numero_cotizacion
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"solicitud_cotizacionD\".id_proveedor
	WHERE
		(requisicion_encabezado.numero_requisicion = '$cotizacion')
	AND
		(descripcion = '".$row_requi->fields('descripcion')."')
	AND
		(nombre = '$proveedor1')
	ORDER BY 
		requisicion_encabezado.numero_requisicion";//$proveedor1
				$row_analisis=& $conn->Execute($sql_analisis);
				
				$pdf->Cell(20,		$coordenada,	number_format($row_analisis->fields('monto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(8,		$coordenada,	number_format($row_analisis->fields('impuesto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(21,		$coordenada,	number_format($row_analisis->fields('total'),2,',','.'),	'LRB',0,'C',0);
				$monto1 = $monto1 + $row_analisis->fields('total');
				//$row_analisis->MoveNext();
				if (($row_analisis->fields('impuesto')<>0) or($row_analisis->fields('impuesto')<>"") ){
					$porcen1 = ($row_analisis->fields('impuesto')/100);
					$iva1 = $iva1 + ($row_analisis->fields('total')*$porcen1);
				}
		
$sql_analisis="SELECT 
		\"solicitud_cotizacionD\".monto,
		\"solicitud_cotizacionD\".impuesto,
		(\"solicitud_cotizacionD\".cantidad * \"solicitud_cotizacionD\".monto)AS total
	FROM 
		requisicion_encabezado
	INNER JOIN
		\"solicitud_cotizacionE\"
	ON
		requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
	INNER JOIN
		\"solicitud_cotizacionD\"
	ON
		\"solicitud_cotizacionE\".numero_cotizacion = \"solicitud_cotizacionD\".numero_cotizacion
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"solicitud_cotizacionD\".id_proveedor
	WHERE
		(requisicion_encabezado.numero_requisicion = '$cotizacion')
	AND
		(descripcion = '".$row_requi->fields('descripcion')."')
	AND
		(nombre = '$proveedor2')
	ORDER BY 
		requisicion_encabezado.numero_requisicion";//$proveedor1
				$row_analisis2=& $conn->Execute($sql_analisis);
				
				$pdf->Cell(20,		$coordenada,	number_format($row_analisis2->fields('monto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(8,		$coordenada,	number_format($row_analisis2->fields('impuesto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(21,		$coordenada,	number_format($row_analisis2->fields('total'),2,',','.'),	'LRB',0,'C',0);
				$monto2 = $monto2 + $row_analisis2->fields('total');
				if (($row_analisis2->fields('impuesto')<>0) or($row_analisis2->fields('impuesto')<>"") ){
					$porcen2 = ($row_analisis2->fields('impuesto')/100);
					$iva2 = $iva2 + ($row_analisis2->fields('total')*$porcen2);
				}
				//$row_analisis->MoveNext();
$sql_analisis="SELECT 
		\"solicitud_cotizacionD\".monto,
		\"solicitud_cotizacionD\".impuesto,
		(\"solicitud_cotizacionD\".cantidad * \"solicitud_cotizacionD\".monto)AS total
	FROM 
		requisicion_encabezado
	INNER JOIN
		\"solicitud_cotizacionE\"
	ON
		requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
	INNER JOIN
		\"solicitud_cotizacionD\"
	ON
		\"solicitud_cotizacionE\".numero_cotizacion = \"solicitud_cotizacionD\".numero_cotizacion
	INNER JOIN
		proveedor
	ON
		proveedor.id_proveedor = \"solicitud_cotizacionD\".id_proveedor
	WHERE
		(requisicion_encabezado.numero_requisicion = '$cotizacion')
	AND
		(descripcion = '".$row_requi->fields('descripcion')."')
	AND
		(nombre = '$proveedor3')
	ORDER BY 
		requisicion_encabezado.numero_requisicion";//$proveedor1
				$row_analisis3=& $conn->Execute($sql_analisis);				
				$pdf->Cell(20,		$coordenada,	number_format($row_analisis3->fields('monto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(8,		$coordenada,	number_format($row_analisis3->fields('impuesto'),2,',','.'),	'LRB',0,'C',0);
				$pdf->Cell(21,		$coordenada,	number_format($row_analisis3->fields('total'),2,',','.'),	'LRB',0,'C',0);
				$monto3 = $monto3 + $row_analisis3->fields('total');
				if (($row_analisis3->fields('impuesto')<>0) or($row_analisis3->fields('impuesto')<>"") ){
					$porcen3 = ($row_analisis3->fields('impuesto')/100);
					$iva3 = $iva3 + ($row_analisis3->fields('total')*$porcen3);
				}
				
				$pdf->Ln($coordenada);
				$tamano = $tamano + $coordenada;
		$row_requi->MoveNext();
	}
	if ($tamano<60)
	{
	$t = 60-$tamano;
			$pdf->Cell(128,		$t,	' ',		'LRT',0,'L',0);
			$pdf->Cell(49,		$t,	'',			'LTR',0,'R',0);
			$pdf->Cell(49,		$t,	'',			'LTR',0,'R',1);
			$pdf->Cell(49,		$t,	'',			'LTR',0,'R',1);
			$pdf->Ln($t);

	}else{
		$pdf->AddPage('L');
	}
	
	/*$iva2 = $monto2*0.12;
	$iva3 = $monto3*0.12;*/
	$total1 = $iva1+$monto1;
	$total2 = $iva2+$monto2;
	$total3 = $iva3+$monto3;
			$pdf->Cell(128,		6,	'SUB-TOTAL:',						'LRT',0,'L',0);
			$pdf->Cell(49,		6,	number_format($monto1,2,',','.'),	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	number_format($monto2,2,',','.'),	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	number_format($monto3,2,',','.'),	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'IVA',		'LTR',0,'L',1);
			$pdf->Cell(49,		6,	number_format($iva1,2,',','.'),		'LTR',0,'R',1);
			$pdf->Cell(49,		6,	number_format($iva2,2,',','.'),		'LTR',0,'R',1);
			$pdf->Cell(49,		6,	number_format($iva3,2,',','.'),		'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'TOTAL:',							'LTR',0,'L',0);
			$pdf->Cell(49,		6,	number_format($total1,2,',','.'),	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	number_format($total2,2,',','.'),	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	number_format($total3,2,',','.'),	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->SetDrawColor(80);
			
			$pdf->SetFont('arial','',9);
			$pdf->Cell(128,		6,	'TIEMPO DE ENTREGA',				'LRT',0,'C',0);
			$pdf->Cell(49,		6,	$tiempo_entrega1,	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	$tiempo_entrega2,	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	$tiempo_entrega3,	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'VALIDEZ DE LA OFERTA',				'LRT',0,'C',0);
			$pdf->Cell(49,		6,	$validez_oferta1,	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	$validez_oferta2,	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	$validez_oferta3,	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'MARCAY/O NORMA DE CALIDA',			'LRT',0,'C',0);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'CONDICIONES DE PAGO',				'LRT',0,'C',0);
			$pdf->Cell(49,		6,	$condiciones_pago1,	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	$condiciones_pago2,	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	$condiciones_pago3,	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->Cell(128,		6,	'GARANTIA',							'LRT',0,'C',0);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',0);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',1);
			$pdf->Cell(49,		6,	'	',	'LTR',0,'R',1);
			$pdf->Ln(5);
			$pdf->MultiCell(275,5,	'RECOMENDACIONES: ',	1,'L');
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
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
		$pdf->Cell(190,		6,'No se encontraron datos ',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
?>