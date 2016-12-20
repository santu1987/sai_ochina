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
$cotizacion = $_GET['numero_requi'];
$pre = $_GET['pre'];
$unidad_ejecutora = $_SESSION["id_unidad_ejecutora"];


$ano = $_GET['ano'];
$where = "WHERE (1=1) ";
if ($cotizacion != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".numero_orden_compra_servicio = '$cotizacion') ";
if ($ano != "")
	$where = $where . " AND	(\"orden_compra_servicioE\".ano = '$ano') ";
//*************************************************************************
$clsificacion = substr ($cotizacion, 0, 2);

	$tipo_ordn = 'ASIGNACIÓN DE COMPROMISO';

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$Sql="SELECT 
	proveedor.id_proveedor,  proveedor.nombre, proveedor.telefono, proveedor.fax,  proveedor.direccion,  
	\"orden_compra_servicioE\".numero_requisicion,\"orden_compra_servicioE\".numero_precompromiso, \"orden_compra_servicioE\".compromiso_anterior, \"orden_compra_servicioE\".numero_orden_compra_servicio,
	\"orden_compra_servicioE\".fecha_orden_compra_servicio, \"orden_compra_servicioE\".tiempo_entrega,\"orden_compra_servicioE\".id_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora,unidad_ejecutora.codigo_unidad_ejecutora,
	\"orden_compra_servicioE\".condiciones_pago, \"orden_compra_servicioE\".concepto, \"orden_compra_servicioE\".lugar_entrega,codigo_accion_especifica,  accion_especifica.denominacion AS accion_especifica,
	\"orden_compra_servicioE\".numero_cotizacion, \"orden_compra_servicioE\".comentarios, \"orden_compra_servicioE\".tipo, \"orden_compra_servicioE\".id_proyecto_accion_centralizada
FROM 
	\"orden_compra_servicioE\"
INNER JOIN
	proveedor
ON
	\"orden_compra_servicioE\".id_proveedor = proveedor.id_proveedor
INNER JOIN
	unidad_ejecutora
ON
	\"orden_compra_servicioE\".id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON
	\"orden_compra_servicioE\".id_accion_especifica = accion_especifica.id_accion_especifica
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
////* ********************************************************************************
if($row->fields("tipo") == 1){
	$SQL_PROYECT_ACC ="
	SELECT
		codigo_proyecto  as pro_acc, 
		nombre as nombre
	FROM 
		proyecto
	WHERE
		ano = '".date('Y')."'
	AND
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		id_proyecto = ".$row->fields("id_proyecto_accion_centralizada");
}else{
	$SQL_PROYECT_ACC ="
	SELECT
		codigo_accion_central as pro_acc, 
		denominacion as nombre
	FROM 
		accion_centralizada
	WHERE
		ano = '".date('Y')."'
	AND
		id_organismo = ".$_SESSION['id_organismo']."
	AND
		id_accion_central = ".$row->fields("id_proyecto_accion_centralizada");
}
$row_pro_acc=& $conn->Execute($SQL_PROYECT_ACC);
//die($SQL_PROYECT_ACC);
	//************************************************************************ 
	$fecha_orden_compra_servicio=split('-',$row->fields("fecha_orden_compra_servicio"));
	list($ano,$mes , $dia) = $fecha_orden_compra_servicio;
	$proveedor=utf8_decode($row->fields("nombre"));
	$numero_cotizacion=$row->fields("numero_cotizacion");
	$numero_precompromiso=$row->fields("numero_precompromiso");
	$compromiso_anterior=$row->fields("compromiso_anterior");	
	
	$unidad_ejecutora=$row->fields("unidad_ejecutora");
	$codigo_unidad=$row->fields("codigo_unidad_ejecutora");
	$codigo_accion_especifica=$row->fields("codigo_accion_especifica");
	$accion_especifica_nom=$row->fields("accion_especifica");
	
	$pro= $row_pro_acc->fields("nombre");
	$codigo_pro=$row_pro_acc->fields("pro_acc");
	
	$numero_orden_compra_servicio=$row->fields("numero_orden_compra_servicio");
	$numero_requisicion=$row->fields("numero_requisicion");
	$direccion=($row->fields("direccion"));
	$telefono=$row->fields("telefono");
	$titulo=($row->fields("concepto"));
	$tiempo_entrega=$row->fields("tiempo_entrega");
	$lugar_entrega=$row->fields("lugar_entrega");
	$condiciones_pago=$row->fields("condiciones_pago");
	$comentarios=$row->fields("comentarios");
	$director_ochina =$row_firma->fields("cargo_director_ochina")." ".utf8_decode($row_firma->fields("nombre_director_ochina"));
	$director_administracion =$row_firma->fields("cargo_director_administracion")." ".utf8_decode($row_firma->fields("nombre_director_administracion"));
	$jefe_adquisiciones =$row_firma->fields("cargo_jefe_adquisiciones")." ".utf8_decode($row_firma->fields("nombre_jefe_adquisiciones"));
	//************************************************************************
	//************************************************************************
	$SqlProyectoAccion=
			"SELECT 
				secuencia,descripcion, cantidad, nombre, monto, impuesto,partida, generica,  especifica,  subespecifica
			FROM 
				\"orden_compra_servicioD\"
			INNER JOIN
				unidad_medida
			ON	
				\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
			WHERE
				(\"orden_compra_servicioD\".numero_pre_orden = '".$pre."')
			ORDER BY
				secuencia
		";
		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
		$partidaCC = $rowProyectoAccion->fields('partida');
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $proveedor,$ano,$mes , $dia, $codigo_pro, $codigo_unidad, $codigo_accion_especifica, $unidad_ejecutora, $accion_especifica_nom;
			global $numero_cotizacion, $numero_orden_compra_servicio, $numero_precompromiso, $compromiso_anterior; 
			global $numero_requisicion, $clsificacion, $pro;
			global $direccion, $tiempo_entrega, $lugar_entrega, $condiciones_pago;
			global $telefono,$titulo,$comentarios,$tipo_ordn,$fecha_orden_compra_servicio,$partidaCC;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln(5);
			$this->SetFont('Arial','B',12);
			$this->Cell(0,8,$tipo_ordn,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',10);
			//$this->Cell(170,8,'Nº Orden  ',0,0,'R');
			//$this->SetFont('Arial','',10); $numero_precompromiso
		//	$this->Cell(20,8,$numero_orden_compra_servicio,0,0,'R');
		//	$this->Ln(5);
			$this->Cell(170,8,'Nº Compromiso Cuenta de Orden  ',0,0,'R');
			$this->Cell(20,8,$compromiso_anterior,0,0,'R');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(20,9,'Proveedor','LT',0,'L');
			$this->SetFont('Arial','B',9);
			$this->Cell(110,9,utf8_decode($proveedor),'TR',0,'L');
			$this->SetFont('Arial','B',10);
			$this->Cell(45,9,'Nº Orden '.$numero_orden_compra_servicio ,'LT',0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(15,9,/*$numero_requisicion*/'','RT',0,'R');
			$this->Ln(9);
			$this->SetFont('Arial','',9);
			$con_pro = strlen($direccion) + strlen($telefono);
			$y=$this->GetY();
			if ($con_pro <= 64)
				$this->MultiCell(130,16,$direccion.'. Telf. '.$telefono,'LBR','TL');
			elseif ($con_pro <= 180)
				$this->MultiCell(130,8,substr($direccion,0,100).'. Telf. '.$telefono,'LBR','L');
			$y3=$this->GetY();
			$x2=$this->GetX();
			$this->SetXY(140,$y);
			$this->MultiCell(60,8,'Fecha de la Orden: '.substr($dia,0,2)."-".$mes ."-". $ano.'                               ','LR','LT');
			
			 //$this->Ln(8);
			$this->SetFont('Arial','B',10);
			
			$y=$this->GetY();
			$this->SetFont('Arial','B',10);
			$this->Cell(23,8,'UNIDAD ',1,0,'L');
			$this->SetFont('Arial','',10);
			$this->Cell(107,8,$codigo_unidad.' '.utf8_decode($unidad_ejecutora),1,0,'L');
			$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(23,8,'PROYECTO ',1,0,'L');
			$this->SetFont('Arial','',10);
			$contar_proyecto=strlen($codigo_pro) +strlen($pro);
			$y=$this->GetY();
			if($contar_proyecto <=64)
				$this->MultiCell(107,8,$codigo_pro.' '.utf8_decode($pro),1,'L',0);
			else
				$this->MultiCell(107,4,$codigo_pro.' '.utf8_decode($pro),1,'L',0);
			//$this->Cell(100,8,$codigo_pro.' '.utf8_decode($pro),1,0,'L');
			//$this->Ln(8);
			$this->SetFont('Arial','B',10);
			$this->Cell(23,8,'ACCION ',1,0,'L');
			$this->SetFont('Arial','',10);
			$contar_proyecto=strlen($codigo_accion_especifica) +strlen($accion_especifica_nom);
			$y=$this->GetY();
			if($contar_proyecto <=64)
				$this->MultiCell(107,8,$codigo_accion_especifica.' '.utf8_decode($accion_especifica_nom),1,'L',0);
			else
				$this->MultiCell(107,4,$codigo_accion_especifica.' '.utf8_decode($accion_especifica_nom),1,'L',0);
			//$this->Cell(100,8,$codigo_pro.' '.utf8_decode($pro),1,0,'L');
			//$this->Ln(8);
			//$this->MultiCell(65,4,'PROYECTO  '.$codigo_pro.'ACCION        '.$codigo_accion_especifica,'LRT','L');
			//$this->SetXY(75,$y);
			/*$this->SetFont('Arial','B',9);			
			$this->Cell(65,14,'                              ','LRT',0,'TL');
			$this->SetFont('Arial','B',10);
			$this->Cell(60,14,'','LR',0,'BL');
			$this->Ln(7);*/
		/*	$this->SetFont('Arial','',9);
			$this->Cell(130,7,$condiciones_pago,'LRB',0,'L');
			$this->Cell(60,7,'','LR',0,'L');
			$this->Cell(50,13,'','LR',0,'L');
			$this->Ln(7);*/
			$this->SetFont('Arial','B',10);
			$this->Cell(65,6,'PARTIDA PRESUPUESTARIA',1,0,'L');
			/*$this->Ln(6);
			$this->SetFont('Arial','',10);*/
			$this->Cell(65,6,$partidaCC,1,0,'L');
			
			$this->Ln(6);
			$this->SetFont('Arial','B',10);
			$this->Cell(190,7,'CONCEPTO DE LA ORDEN','LTR',0,'L');
			$this->Ln(7);
			$this->SetFont('Arial','',9);
			$this->MultiCell(190,7,$titulo,'LBR','L'); 
			$y4=$this->GetY();
			$this->Line($x2+190,$y3,$x2+190,$y4);
			//$this->Ln(7);
		//	$this->Cell(190,5,'SÍRVASE DESPACHAR EL SIGUIENTE MATERIAL DE ACUERDO A LAS INSTRUCCIONES ESPECIFICADAS',1,0,'C'); 
		//	$this->Ln(5);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(200) ;
			$this->SetTextColor(0);
			$this->Cell(12,		5,		'Reglon',			1,0,'C',1);
			$this->Cell(16,		5,		'Cantidad',			1,0,'C',1);
			$this->Cell(19,		5,		'Uni. Med.',		1,0,'C',1);
			$this->Cell(91,		5,		'Descripción',		1,0,'L',1);
			$this->Cell(31,		5,		'Clasificación',				1,0,'C',1);
			//$this->Cell(21,		5,		'Precio U.',		1,0,'C',1);
			$this->Cell(21,		5,		'Total Bs.',		1,0,'C',1);
			$this->Ln(5);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',9);
			//Número de página
		//	$this->Cell(65,3,'Observaciones ' ,0,0,'L');
		//	$this->Ln(20);
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
		//	$this->SetFont('barcode','',6);
		//	$this->Cell(65,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		//	$this->SetFont('Arial','I',9);
		//	$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
		//	$this->Ln();
			/*$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');*/
		}
	}
	//************************************************************************
	//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 

	$total=0;
	//$pdf=new PDF('P','mm','Legal');
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(0);
	//$pdf->SetAutoPageBreak(auto,10);
	$i=0;
	
		
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
					(\"orden_compra_servicioD\".numero_pre_orden = '".$pre."')
			
		";
		$rowCuenta=& $conn->Execute($Sqlcontar);
		if (($rowCuenta->fields('cuenta')< 18 )){
			$paginas = ceil($rowCuenta->fields('cuenta') /15.5);
			$medida = 80;
		}elseif (($rowCuenta->fields('cuenta')> 17 ) ){
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
		$coordenadaxs = 4;
		$total = 0;
		$iva = 0;
		$y1=$pdf->GetY();
		$x1=$pdf->GetX();
		$jj = 0;
	while (!$rowProyectoAccion->EOF) 
	{
	if((strlen($rowProyectoAccion->fields('descripcion')) >46)&& (strlen($rowProyectoAccion->fields('descripcion')) < 72))
		$coordenada = 8;
	elseif((strlen($rowProyectoAccion->fields('descripcion')) >=72)&& (strlen($rowProyectoAccion->fields('descripcion')) < 105))
		$coordenada = 12;
	elseif((strlen($rowProyectoAccion->fields('descripcion')) >=105)&& (strlen($rowProyectoAccion->fields('descripcion')) < 154))
		$coordenada = 16;
	elseif((strlen($rowProyectoAccion->fields('descripcion')) >=154)&& (strlen($rowProyectoAccion->fields('descripcion')) < 200))
		$coordenada = 20;

		$jj++;
		$subtotal = $rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad');
		$pdf->Cell(12,			4,	$jj/*$rowProyectoAccion->fields('secuencia')*/,		'LR',0,'C');
		$pdf->Cell(16,			4,	$rowProyectoAccion->fields('cantidad'),			'L',0,'C');
		$pdf->Cell(19,			4,	$rowProyectoAccion->fields('nombre'),			'L',0,'C');
		$y=$pdf->GetY();
		$pdf->MultiCell(91,	$coordenadaxs,	utf8_decode($rowProyectoAccion->fields('descripcion')),		'LR','L');
		$pdf->SetXY(148,$y);
		$pdf->Cell(31,			4,	$rowProyectoAccion->fields('partida').".".$rowProyectoAccion->fields('generica').".".$rowProyectoAccion->fields('especifica').".".$rowProyectoAccion->fields('subespecifica'),			'L',0,'R');
		//$pdf->Cell(21,			$coordenada,	number_format($rowProyectoAccion->fields('monto'),2,',','.'),			'L',0,'R');
		$pdf->Cell(21,			4,	number_format($subtotal,2,',','.'),			'LR',0,'R');
		
		$pdf->Ln($coordenada);
		$tamano = $tamano + $coordenada;
		$coordenada=4;
		$total = $total + $subtotal;
		//$iva = ($rowProyectoAccion->fields('impuesto') / 100);
		$ivas=  ($rowProyectoAccion->fields('monto')*$rowProyectoAccion->fields('cantidad'))*$rowProyectoAccion->fields('impuesto')/100;
		$iva = $iva +$ivas;
		if($tamano >= 130){
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
	$y2=$pdf->GetY();
	$pdf->Line($x1,$y1,$x1,$y2);
	$pdf->Line($x1+12,$y1,$x1+12,$y2);
	$pdf->Line($x1+28,$y1,$x1+28,$y2);
	$pdf->Line($x1+47,$y1,$x1+47,$y2);
	$pdf->Line($x1+138,$y1,$x1+138,$y2);
	$pdf->Line($x1+169,$y1,$x1+169,$y2);
	$pdf->Line($x1+190,$y1,$x1+190,$y2);
	if(($rowCuenta->fields('cuenta')>25)&&($rowCuenta->fields('cuenta')<30)){// NUMERO DE LINEAS POR PAGINAS
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
				$pdf->Cell(16,		$t,	'',			'LR',0,'R',0);
				$pdf->Cell(19,		$t,	'',			'LR',0,'R',1);
				$pdf->Cell(91,		$t,	'',			'LR','L',0);
				$pdf->Cell(31,		$t,	'',			'L',0,'L',0);
				//$pdf->Cell(21,		$t,	'',			'L',0,'L',0);
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
		$pdf->SetFont('arial','',9);
		$pdf->Cell(74,		4,	'ELABORADO POR',			1,0,'L',0);
		$pdf->Cell(74,		4,	'REVISADO POR',			1,0,'L',0);
		$pdf->Cell(21,		4,	'SUB-TOTAL ',			1,0,'L',0);
		$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'R',0);
		$pdf->Ln(4);
		$pdf->SetFont('arial','',9);
		$pdf->Cell(74,		5,	'',			'LR','L',0);
		$pdf->Cell(74,		5,	'',			'LR','L',0);
		$pdf->Cell(21,		5,	'IVA ',			1,0,'L',0);
		$pdf->Cell(21,		5,	number_format('0',2,',','.'),			1,0,'R',0);
		$pdf->Ln(5);
	/*}elseif ($tamano>$medida){
		$pdf->SetFont('arial','B',7);
		$pdf->Cell(169,		4,	'VIENEN',			1,'L',0);
		$pdf->Cell(21,		4,	number_format($total,2,',','.'),			1,0,'L',0);
		$pdf->Ln(4);
	}*/
	$pdf->Cell(74,		5,	'',				'LRB'	,0,'L',0);
	$pdf->Cell(74,		5,	'',				'LRB',0,'L',0);
	$pdf->Cell(21,		9,	'TOTAL',							'RLB',0,'L',0);
	$pdf->Cell(21,		9,	number_format($total,2,',','.'),	'LRB',0,'R',0);
	$total = $total + $iva;
	/*$pdf->Cell(74,		5,	'',			'LR',0,'L',0);
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
	$pdf->Ln(4);*/
	
	//$pdf->Cell(42,		5,	'',							'LR',0,'L',0);
	$pdf->Ln(5);
	$pdf->Cell(74,		4,	'ANALISTA DE PRESUPUESTO',			'LBR',0,'L',0);
	$pdf->Cell(74,		4,	'JEFE DE DIVISIÓN DE PRESUPUESTO',	'LBR',0,'L',0);
	//$pdf->Cell(42,		4,	'',							'LBR',0,'L',0);
	$pdf->Ln(7);
		
	$pdf->Cell(63,		3,	'CONTABILIDAD',			1,0,'C',0);
	$pdf->Cell(63,		3,	'CONTABILIDAD',				1,0,'C',0);
	$pdf->Cell(64,		3,	'CONTABILIDAD',							1,0,'C',0);
	$pdf->Ln(3);
	$pdf->Cell(63,		6,	'',			'LR',0,'L',0);
	$pdf->Cell(63,		6,	'',			'LR',0,'L',0);
	$pdf->Cell(64,		6,	' ',			'RL',0,'L',0);
	
	$pdf->Ln(6);
		
	$pdf->Cell(63,		6,	'',			'LBR',0,'L',0);
	$pdf->Cell(63,		6,	'',				'LBR',0,'L',0);
	$pdf->Cell(64,		6,	'',							'LBR',0,'L',0);
	$pdf->Ln(7);

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
			//$pdf->Ln(3);
			//$pdf->Cell(50,		4,	'PARTIDA',					1,0,'C',0);
			//$pdf->Cell(45,		4,	'PRESUPUESTO DISPONIBLE',	1,0,'C',0);
			//$pdf->Cell(45,		4,	'MONTO PRE-COMPROMISO',		1,0,'C',0);
			//$pdf->Cell(21,		5,	'TOTAL',					1,0,'L',0);
			//$pdf->Ln(4);
			
			//$pdf->SetFont('arial','',7);
//			$pdf->Cell(50,		4,	'403.18.01.00',					1,0,'L',0);
			//$pdf->Cell(45,		4,	'',	1,0,'R',0);
	//		$pdf->Cell(45,		4,	number_format($iva,2,',','.'),		1,0,'R',0);
		//	$pdf->Ln(4);
			
			
		/*while (!$row_disppnible->EOF) 
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
			//$pdf->Cell(45,		4,	number_format($montoo,2,',','.'),									1,0,'R',0);
			$pdf->Cell(45,		4,	number_format($row_disppnible->fields('sum'),2,',','.'),			1,0,'R',0);
			//$pdf->Cell(21,		5,	number_format($montoo - $row_disppnible->fields('sum'),2,',','.'),	1,0,'L',0);
			$pdf->Ln(4);
			$row_disppnible->MoveNext();
		}*/
	}
	/*$pdf->Ln(4);
	//$pdf->SetFont('arial','B',6);
	$pdf->SetFont('arial','B',8);
	$pdf->Cell(27,		4,	'UNIDAD EJECUTORA:',					'B',0,'L',0);
	$pdf->MultiCell(233,		4,	$codigo_unidad_ejecutora." ".utf8_decode($unidad_ejecutora),	0,'L',0);
	//$pdf->Ln(4);
	$pdf->Cell(27,		4,	'PROYECTO - A.C. :',					'B',0,'L',0);
	$pdf->MultiCell(233,		4,	utf8_decode($comentarios),	0,'L',0);
	$pdf->Cell(27,		4,	'ACCION ESPEFICICA :',					'B',0,'L',0);
	$pdf->MultiCell(233,		4,	utf8_decode($comentarios),	0,'L',0);

	$pdf->Ln(4);*/
	$pdf->SetFont('arial','B',8);
	$pdf->Cell(27,		4,	'OBSERVACIONES:',					'B',0,'L',0);
	$pdf->SetFont('arial','',8);
	$pdf->MultiCell(233,		4,	utf8_decode($comentarios),	0,'L',0);
	
		$pdf->Output();
}
?>