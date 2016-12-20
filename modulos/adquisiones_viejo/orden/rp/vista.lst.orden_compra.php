<?php
if (!$_SESSION) session_start();
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
$cotizacion = $_GET['numero_coti'];
$unidad_ejecutora = $_SESSION["id_unidad_ejecutora"];


$ano = $_GET['ano'];
$where = "WHERE (1=1) ";
if ($cotizacion != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".numero_orden_compra_servicio = '$cotizacion') ";
if ($ano != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".ano = '$ano') ";
//*************************************************************************
$clsificacion = substr ($cotizacion, 0, 3);
if ($clsificacion == '403')
	$tipo_ordn = 'ORDEN DE SERVICIO';
else
	$tipo_ordn = 'ORDEN DE COMPRA';

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$Sql="SELECT 
	proveedor.id_proveedor,  proveedor.nombre, proveedor.telefono, proveedor.fax,  proveedor.direccion,  
	\"orden_compra_servicioE\".numero_requisicion,\"orden_compra_servicioE\".numero_precompromiso, \"orden_compra_servicioE\".numero_orden_compra_servicio,
	\"orden_compra_servicioE\".fecha_orden_compra_servicio, \"orden_compra_servicioE\".tiempo_entrega, 
	\"orden_compra_servicioE\".condiciones_pago, \"orden_compra_servicioE\".concepto, \"orden_compra_servicioE\".lugar_entrega,
	\"orden_compra_servicioE\".numero_cotizacion, \"orden_compra_servicioE\".comentarios
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	proveedor
ON
	\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
	".$where."
	 ORDER BY id_orden_compra_servicioe";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
$sql_firma = "
SELECT 
	id_firmas_orden_compra_servicio,  
	nombre_director_administracion, cargo_director_administracion,
	nombre_director_ochina, cargo_director_ochina,
	nombre_jefe_adquisiciones, cargo_jefe_adquisiciones,
	nombre_ministro_defensa, cargo_ministro_defensa
FROM 
	firmas_orden_compra_servicio
WHERE
	id_organismo = 1
";
$row_firma=& $conn->Execute($sql_firma);
	//************************************************************************
	$fecha_orden_compra_servicio=split('-',$row->fields("fecha_orden_compra_servicio"));
	
	$proveedor=$row->fields("proveedor");
	$numero_cotizacion=$row->fields("numero_cotizacion");
	$numero_precompromiso=$row->fields("numero_precompromiso");	
	$numero_orden_compra_servicio=$row->fields("numero_orden_compra_servicio");
	$numero_requisicion=$row->fields("numero_requisicion");
	$direccion=$row->fields("direccion");
	$telefono=$row->fields("telefono");
	$titulo=$row->fields("concepto");
	$tiempo_entrega=$row->fields("tiempo_entrega");
	$lugar_entrega=$row->fields("lugar_entrega");
	$condiciones_pago=$row->fields("condiciones_pago");
	$comentarios=$row->fields("comentarios");
	$director_ochina =$row_firma->fields("cargo_director_ochina")." ".$row_firma->fields("nombre_director_ochina");
	$director_administracion =$row_firma->fields("cargo_director_administracion")." ".$row_firma->fields("nombre_director_administracion");
	$jefe_adquisiciones =$row_firma->fields("cargo_jefe_adquisiciones")." ".$row_firma->fields("nombre_jefe_adquisiciones");

	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proveedor;
			global $numero_cotizacion, $numero_orden_compra_servicio, $numero_precompromiso; 
			global $numero_requisicion, $clsificacion;
			global $direccion, $tiempo_entrega, $lugar_entrega, $condiciones_pago;
			global $telefono,$titulo,$comentarios,$tipo_ordn,$fecha_orden_compra_servicio;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',10);
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
			$this->Ln(5);
			$this->SetFont('Arial','B',12);
			$this->Cell(0,8,$tipo_ordn,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			$this->Cell(175,8,'Nº Orden  ',0,0,'R');
			//$this->SetFont('Arial','',10); $numero_precompromiso
			$this->Cell(15,8,$numero_orden_compra_servicio,0,0,'R');
			$this->Ln(5);
			$this->Cell(175,8,'Nº Pre-compromiso  ',0,0,'R');
			$this->Cell(15,8,$numero_precompromiso,0,0,'R');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,9,'Proveedor ','LT',0,'L');
			$this->SetFont('Arial','',9);
			$this->Cell(110,9,$proveedor,'TR',0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(45,9,'Requisición ','LT',0,'R');
			$this->SetFont('Arial','',10);
			$this->Cell(15,9,$numero_requisicion,'RT',0,'R');
			$this->Ln(9);
			$this->SetFont('Arial','',8.5);
			$y=$this->GetY();
			$this->MultiCell(130,16,$direccion.'. Telf. '.$telefono,'LBR','L');
			$this->SetXY(140,$y);
			$this->MultiCell(60,8,'Telf. (0212) 303-8761 3038762   Fax. 303-8761 3038762','LBR','L'); 
			$this->SetFont('Arial','B',10);
			$this->Cell(49,8,'TIEMPO DE ENTREGA',1,0,'L');
			$this->Cell(47,8,'CONTABILIDAD C/O',1,0,'L');
			$this->Cell(47,8,'CONTABILIDAD',1,0,'L');
			$this->Cell(47,8,'PRESUPUESTO',1,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','',10);
			$this->Cell(49,8,$tiempo_entrega,1,0,'L');
			$this->Cell(47,8,'',1,0,'L');
			$this->Cell(47,8,'',1,0,'L');
			$this->Cell(47,21,'',1,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(49,7,'SOLICITUD DE COTIZACIÓN','LRT',0,'L');
			$this->Cell(47,7,'SU PRESUPUESTO','LRT',0,'L');
			$this->Cell(47,7,'LUGAR DE ENTREGA','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',9);
			$this->Cell(49,6,$numero_cotizacion,'LRB',0,'L');
			$this->Cell(47,6,'','LRB',0,'L');
			$this->Cell(47,6,$lugar_entrega,'LRB',0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','B',10);
			$this->Cell(80,7,'CONDICIONES DE PAGO','LRT',0,'L');
			$this->Cell(110,7,'ENTREGAR A:','LRT',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',9);
			$this->Cell(80,7,$condiciones_pago,'LRB',0,'L');
			$this->Cell(110,19,'','LRB',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','B',10);
			$this->Cell(80,6,'CLASIFICACIÓN PRESUPUESTARIA',1,0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','',10);
			$this->Cell(80,6,$clsificacion,1,0,'L');
			$this->Ln(6);
			$this->SetFont('Arial','B',10);
			$this->Cell(190,7,'CONCEPTO DE LA ORDEN','LTR',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',9);
			$this->MultiCell(190,7,$titulo,'LBR','L'); 
			//$this->Ln(7);
			$this->Cell(190,5,'SÍRVASE DESPACHAR EL SIGUIENTE MATERIAL DE ACUERDO A LAS INSTRUCCIONES ESPECIFICADAS',1,0,'C'); 
			$this->Ln(5);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(12,		5,		'Reglon',			1,0,'C',1);
			$this->Cell(17,		5,		'Cantidad',			1,0,'C',1);
			$this->Cell(20,		5,		'Uni. Med.',		1,0,'C',1);
			$this->Cell(89,		5,		'Descripción',		1,0,'L',1);
			$this->Cell(10,		5,		'%IVA',				1,0,'C',1);
			$this->Cell(21,		5,		'Precio U.',		1,0,'C',1);
			$this->Cell(21,		5,		'Total Bs.',		1,0,'C',1);
			$this->Ln(5);
		}
		//Pie de página
	/*	function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-45);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
			$this->Cell(65,3,'Observaciones ' ,0,0,'L');
			$this->Ln(20);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->SetFont('barcode','',6);
			$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			$this->SetFont('Arial','I',9);
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			/*$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}*/
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 

	$total=0;
	$pdf=new PDF('P','mm','Legal');
//	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(0);
	//$pdf->SetAutoPageBreak(auto,10);
	$i=0;
	
		$SqlProyectoAccion=
			"SELECT 
				secuencia,descripcion, cantidad, nombre, monto, impuesto
			FROM 
				\"orden_compra_servicioD\"
			INNER JOIN
				unidad_medida
			ON	
				\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
			WHERE
				(numero_cotizacion = '".$numero_cotizacion."')
			ORDER BY
				secuencia
		";
		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
		$Sqlcontar=
			"SELECT 
				count(secuencia) AS cuenta
			FROM 
				\"orden_compra_servicioD\"
			INNER JOIN
				unidad_medida
			ON	
				\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
			WHERE
				(numero_cotizacion = '".$numero_cotizacion."')
			
		";
		$rowCuenta=& $conn->Execute($Sqlcontar);
		if (($rowCuenta->fields('cuenta')< 16 )){
			$paginas = ceil($rowCuenta->fields('cuenta') /15.5);
			$medida = 60;
		}elseif (($rowCuenta->fields('cuenta')> 15 ) ){
			$paginas = ceil($rowCuenta->fields('cuenta') /15.5);
			$medida = 120;
		}/*elseif (($rowCuenta->fields('cuenta')> 30 ) && ($rowCuenta->fields('cuenta')< 46 )){
			$paginas = ceil($rowCuenta->fields('cuenta') /15.5);
			$medida = 180;
		}elseif (($rowCuenta->fields('cuenta')> 45 ) && ($rowCuenta->fields('cuenta')< 61 )){
			$paginas = ceil($rowCuenta->fields('cuenta') /15.5);
			$medida = 220;
		}*/
		$coordenada = 4;
		$total = 0;
		$iva = 0;
		$jj = 0;
	while (!$rowProyectoAccion->EOF) 
	{
		$jj++;
		$subtotal = $rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad');
		$pdf->Cell(12,			$coordenada,	$jj/*$rowProyectoAccion->fields('secuencia')*/,		'LR',0,'C');
		$pdf->Cell(17,			$coordenada,	$rowProyectoAccion->fields('cantidad'),			'L',0,'C');
		$pdf->Cell(20,			$coordenada,	$rowProyectoAccion->fields('nombre'),			'L',0,'C');
		$y=$pdf->GetY();
		$pdf->MultiCell(89,	$coordenada,	$rowProyectoAccion->fields('descripcion'),		'LR','L');
		$pdf->SetXY(148,$y);
		$pdf->Cell(10,			$coordenada,	number_format($rowProyectoAccion->fields('impuesto'),2,',','.'),			'L',0,'R');
		$pdf->Cell(21,			$coordenada,	number_format($rowProyectoAccion->fields('monto'),2,',','.'),			'L',0,'R');
		$pdf->Cell(21,			$coordenada,	number_format($subtotal,2,',','.'),			'LR',0,'R');
		
		$pdf->Ln($coordenada);
		$tamano = $tamano + $coordenada;
		$total = $total + $subtotal;
		//$iva = ($rowProyectoAccion->fields('impuesto') / 100);
		$ivas=  ($rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad'))*$rowProyectoAccion->fields('impuesto')/100;
		$iva = $iva +$ivas;
		if($tamano >= 120){
			$pdf->SetFont('arial','B',7);
			$pdf->Cell(190,		4,	'VAN '.number_format($total,2,',','.'),			1,0,'R',0);
			$tamano = 0;
			$pdf->AddPage('');
			$pdf->SetFont('arial','B',7);
			$pdf->Cell(190,		4,	'VIENEN '.	number_format($total,2,',','.'),			1,0,'R',0);
			$pdf->Ln($coordenada);
		}	
		$rowProyectoAccion->MoveNext();
	}
	if(($rowCuenta->fields('cuenta')>15)&&($rowCuenta->fields('cuenta')<30)){
			$pdf->SetFont('arial','B',7);
			$pdf->Cell(190,		4,	'VAN '.number_format($total,2,',','.'),			1,0,'R',0);
			$tamano = 0;
			$pdf->AddPage('');
			$pdf->SetFont('arial','B',7);
			$pdf->Cell(190,		4,	'VIENEN '.	number_format($total,2,',','.'),			1,0,'R',0);
			$pdf->Ln(4.1);
	}
	/*$pdf->Cell(148,		5,	'aqui '.$tamano,		'LR','L',0);
	$pdf->Ln(5);
	if ($paginas <=1){
		$medida = 60;	
	}elseif ($paginas >1){
		$medida = 120;
	}*/
		if ($tamano<=60)
		{
		$t = 60-$tamano;
				$pdf->Cell(12,		$t,	' ',		'LR',0,'L',0);
				$pdf->Cell(17,		$t,	'',			'LR',0,'R',0);
				$pdf->Cell(20,		$t,	'',			'LR',0,'R',1);
				$pdf->Cell(89,		$t,	'',			'LR','L',0);
				$pdf->Cell(10,		$t,	'',			'L',0,'L',0);
				$pdf->Cell(21,		$t,	'',			'L',0,'L',0);
				$pdf->Cell(21,		$t,	'',			'LR',0,'L',0);
			$pdf->Ln($t);
	
		}/*else{
			$pdf->SetFont('arial','B',7);
			$pdf->Cell(169,		4,	'VAN '.$medida,			1,'C',0);
			$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'L',0);
			$pdf->AddPage('');
		}*/
	//}
	/*if (($tamano <= 120)){*/
		$pdf->SetFont('arial','',7);
		$pdf->Cell(148,		4,	'',			1,'L',0);
		$pdf->Cell(21,		4,	'SUB-TOTAL ',			1,0,'L',0);
		$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'R',0);
		$pdf->Ln(4);
	/*}elseif ($tamano>$medida){
		$pdf->SetFont('arial','B',7);
		$pdf->Cell(169,		4,	'VIENEN',			1,'L',0);
		$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'L',0);
		$pdf->Ln(4);
	}*/
	$pdf->Cell(74,		4,	'CONFORMADO POR',			1,0,'L',0);
	$pdf->Cell(74,		4,	'APROBADO POR',				1,0,'L',0);
	$pdf->Cell(21,		9,	'I.V.A ',					'L',0,'L',0);
	$pdf->Cell(21,		9,	number_format($iva,2,',','.'),					'RT',0,'R',0);
	$pdf->Ln(4);
	$total = $total + $iva;
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
	$pdf->Cell(21,		5,	' ',			'L',0,'L',0);
	$pdf->Cell(21,		5,	' ',			'R',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		5,	$director_administracion,			'LBR',0,'L',0);
	$pdf->Cell(74,		5,	$director_ochina ,				'LBR',0,'L',0);
	$pdf->Cell(21,		5,	'TOTAL',							'LB',0,'L',0);
	$pdf->Cell(21,		5,	number_format($total,2,',','.'),	'RB',0,'R',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		4,	'DIRECTOR ADMON. FINANZAS',			'LBR',0,'L',0);
	$pdf->Cell(74,		4,	'DIRECTOR DE OCHINA',				'LBR',0,'L',0);
	$pdf->Cell(21,		4,	'',							'LB',0,'L',0);
	$pdf->Cell(21,		4,	'',	'RB',0,'L',0);
	$pdf->Ln(4);
	
	$pdf->Cell(74,		4,	'ELABORADO POR',			1,0,'L',0);
	$pdf->Cell(74,		4,	'REVISADO POR',				1,0,'L',0);
	$pdf->SetFont('arial','',5.5);
	$pdf->Cell(42,		4,	'ACEPTACIÓN DEL PROV. FECHA Y FIRMA ',	'LBR',0,'L',0);
	$pdf->SetFont('arial','',7);
	$pdf->Ln(4);
	$pdf->Cell(74,		4,	'',			'LR',0,'L',0);
	$pdf->Cell(74,		4,	'',			'LR',0,'L',0);
	$pdf->Cell(21,		4,	' ',			'L',0,'L',0);
	$pdf->Cell(21,		8,	' ',			'R',0,'L',0);
	$pdf->Ln(4);
	$pdf->Cell(74,		5,	'TSU',			'LBR',0,'L',0);
	$pdf->Cell(74,		5,	$jefe_adquisiciones,				'LBR',0,'L',0);
	$pdf->Cell(42,		5,	'',							'LR',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		4,	'COMPRADOR',			'LBR',0,'L',0);
	$pdf->Cell(74,		4,	'JEFE DE DIVISIÓN DE ADQUISICIONES',	'LBR',0,'L',0);
	$pdf->Cell(42,		4,	'',							'LBR',0,'L',0);
	$pdf->Ln(4);
	//*************************************************************************************
	$sql_disppnible = "
	SELECT 
		SUM(cantidad*monto) ,
		partida, generica,  especifica,  subespecifica
	FROM 
		\"orden_compra_servicioD\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
	WHERE
		(numero_orden_compra_servicio = '$cotizacion')
	GROUP BY
		  partida, generica, especifica,  subespecifica
	";
	$row_disppnible=& $conn->Execute($sql_disppnible);
	
	if (!$row_disppnible->EOF)
	{
		$mes= date('n');
		$i = 0;
		$desde =1;
		$monto_comprometido = 0;
		while($desde<=$fecha_orden_compra_servicio[1]){
			if ($i == 0){
				$monoto = "monto_presupuesto [".$desde."]";
				$traspasado = "monto_traspasado [".$desde."]";
				$modificado = "monto_modificado [".$desde."]";
				$monto_comprometido = "monto_comprometido [".$desde."]";
			}else{
				$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
				$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
				$modificado = $modificado.' + monto_modificado ['.$desde.']';
				$monto_comprometido = $monto_comprometido." + monto_comprometido [".$desde."]";
			}
		$i++;
		$desde++;
		//echo $monto_precomprometido.'<br>';
		}
	
	$pdf->SetFont('arial','B',7);
	$part = $row_disppnible->fields('partida').".".$row_disppnible->fields('generica').".".$row_disppnible->fields('especifica').".".$row_disppnible->fields('subespecifica');
			$pdf->Ln(3);
			$pdf->Cell(50,		4,	'PARTIDA',					1,0,'C',0);
			$pdf->Cell(45,		4,	'PRESUPUESTO DISPONIBLE',	1,0,'C',0);
			$pdf->Cell(45,		4,	'MONTO PRE-COMPROMISO',		1,0,'C',0);
			//$pdf->Cell(21,		5,	'TOTAL',					1,0,'L',0);
			$pdf->Ln(4);
			$pdf->SetFont('arial','',7);
		while (!$row_disppnible->EOF) 
		{
			$sql_saldo ="
	SELECT 
		($monoto) as monto_presupuesto, ($traspasado)as monto_traspasado, ($modificado) as monto_modificado, ($monto_comprometido) as monto_comprometido
	FROM 
		\"presupuesto_ejecutadoR\"
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		(\"orden_compra_servicioE\".id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
		AND
		\"orden_compra_servicioE\".id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
		)
	WHERE
		(numero_orden_compra_servicio = '$cotizacion')
	AND
		(partida = '".$row_disppnible->fields('partida')."')
	AND
		(generica = '".$row_disppnible->fields('generica')."')
	AND
		(especifica = '".$row_disppnible->fields('especifica')."')
	AND
		(sub_especifica = '".$row_disppnible->fields('subespecifica')."')

	";
	$row_saldo=& $conn->Execute($sql_saldo);
	
	if (!$row_saldo->EOF)
	{
		$montoo = $row_saldo->fields('monto_presupuesto')+ $row_saldo->fields('monto_traspasado')+ $row_saldo->fields('monto_modificado')+ $row_saldo->fields('monto_comprometido');
	}
	$part = $row_disppnible->fields('partida').".".$row_disppnible->fields('generica').".".$row_disppnible->fields('especifica').".".$row_disppnible->fields('subespecifica');
			$pdf->Cell(50,		4,	$part ,																1,0,'L',0);
			$pdf->Cell(45,		4,	number_format($montoo,2,',','.'),									1,0,'R',0);
			$pdf->Cell(45,		4,	number_format($row_disppnible->fields('sum'),2,',','.'),			1,0,'R',0);
			//$pdf->Cell(21,		5,	number_format($montoo - $row_disppnible->fields('sum'),2,',','.'),	1,0,'L',0);
			$pdf->Ln(4);
			$row_disppnible->MoveNext();
		}
	}
	$pdf->SetFont('arial','B',6);
	$pdf->Cell(21,		4,	'CLÁUSULA PENAL:',					'B',0,'L',0);
	$pdf->SetFont('arial','',6);
	$pdf->Cell(233,		4,	'Queda establecida la cláusula penal según la cual el Proveedor, deberá pagar a Servicio Autónomo Ochina el 2% sobre el monto total de la presente Orden de Servicio por cada',	0,0,'L',0);
	$pdf->Ln(4);
	$pdf->Cell(250,		4,	'dia de retardo en la entrega de la mercancía; dicho pago no excederá al 10% del valor total de la presente Orden de Servicio',					0,0,'L',0);
	$pdf->Ln(4);
	$pdf->SetFont('arial','B',6);
	$pdf->Cell(22,		4,	'OBSERVACIONES:',					'B',0,'L',0);
	$pdf->SetFont('arial','',6);
	$pdf->Cell(233,		4,	'A) Las facturas deben indicar el No. de la Orden de Servicio',	0,0,'L',0);
	$pdf->Ln(4);
	$pdf->Cell(21,		4,	'',					0,0,'L',0);
	$pdf->Cell(233,		4,	'B) El proveedor al facturar, debe anexar el origen de la Orden de Servicio',					0,0,'L',0);
	
		$pdf->Output();
}
?>