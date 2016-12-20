<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//
function redondeo($valor){
	$pos = strpos($valor,'.');
	if($pos!=''){
		$tam = strlen($valor);
		$res = $tam - $pos;
		if($res >= 3)
			$valor = substr($valor,0,$pos+3);
	}
	return $valor;
}
//
//
//
function redondeo_exac($valor){
	$pos = strpos($valor,'.');
	if($pos!=''){
		$tam = strlen($valor);
		$tam = $tam - $pos;
		if($tam>=4){
			$cent = substr($valor,$pos+3,1);
			if($cent>=5){
				$valor = substr($valor,0,$pos+3);
				$valor = $valor +0.01;
			}
		else if($tam>=3)
			$valor = substr($valor,0,$pos+3);		
		}
	}
	return $valor;	
}

//
$id_concep_pat = "";
$id_concep_pat = $_GET['id_concep_pat'];
//

//
$cant=0;
$tipo_nomina = $_GET['tipo_nomina'];
$tipo_nomina = split('_',$tipo_nomina);
$cant = count($tipo_nomina);
//
//
$fecha_inicio = '01-'.substr($_GET['hasta'],3,2)."-".substr($_GET['hasta'],6,4); 

$frecuencia = 1;	
 if(strtoupper($_GET['frecuencia'])=='QUINCENAL')
 	$frecuencia = 2;
if(strtoupper($_GET['frecuencia'])=='MENSUAL')
	$frecuencia = 1;							
//selecionando la tabla monedas
$id_sitio = '';
$fecha_desde = '';
$fecha_hasta = '';
$mes = substr($_GET['hasta'],3,2);
/*if($mes=='01')
	$opt = 1;
if($mes!='01')
	$opt = 2;
	*/
if($_GET['desde']!='')
	$fecha_desde = $_GET['desde'];
if($_GET['hasta']!='')
	$fecha_hasta = $_GET['hasta'];
$ano_rep = substr($_GET['desde'],6,4);
$mes_rep = substr($_GET['hasta'],3,2);
$where = " WHERE 1 = 1 ";
//if($_GET['id_sitio']!='')
	//$id_sitio = $_GET['id_sitio'];
//if($id_sitio!='')
	//$where.= " AND bienes.id_sitio_fisico = 1 ";
//if($fecha_desde!='' && $fecha_hasta!='')
	//$where.= " AND bienes.fecha_compra BETWEEN '$fecha_desde' AND '$fecha_hasta' ";
$Sql="SELECT 
				persona.cedula,
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				trabajador.fecha_ingreso,
				aumento_sueldo.sueldo_aumento,
				trabajador.id_tipo_nomina,
				tipo_nomina.nombre as tipo
			FROM 
				trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona=persona.id_persona
			LEFT JOIN
				aumento_sueldo
			ON
				trabajador.id_trabajador = aumento_sueldo.id_trabajador
			INNER JOIN
				tipo_nomina
			ON
				trabajador.id_tipo_nomina = tipo_nomina.id_tipo_nomina
			".$where."
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
			ORDER BY
				 id_tipo_nomina, substr(cedula,3)::integer
		";
$row=& $conn->Execute($Sql);
$sql_exi = "SELECT 
				count(id_nomina) as exi
			FROM 
				historico_nomina
			".$where."
			AND
				historico_nomina.id_organismo = $_SESSION[id_organismo]
			";
$row_exi =& $conn->Execute($sql_exi);
$exi = $row_exi->fields('exi');
//************************************************************************
//if (!$row->EOF)
//{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $mes;
			global $exi;
			global $fecha_desde;
			global $fecha_hasta;
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			//$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode('MINISTERIO DEL PODER POPULAR PARA LA DEFENSA'),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,utf8_decode('VICEMINISTRO DE SERVICIOS'),0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('DIRECCIÓN GENERAL DE EMPRESAS Y SERVICIOS'),0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('OFICINA COORDINADORA DE HIDROGRAFÍA Y NAVEGACIÓN'),0,0,'C');	
			
			$this->Ln(10);
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'PROVISION DE PRESTACIONES DE ANTIGUEDAD AL '.$fecha_hasta,0,1,'C');
			$this->SetFont('Arial','B',10);
			$x = $this->GetX();
			$y = $this->GetY();
			/*if($fecha_desde!='' && $fecha_hasta!=''){
				$this->SetXY($x+130,$y);
				$this->Cell(35,10,"DE LA FECHA DEL: ",0,0,'L');
				$this->Cell(20,10,$fecha_desde,0,0,'L');
				$this->Cell(20,10,"HASTA EL: ",0,0,'L');
				$this->Cell(20,10,$fecha_hasta,0,0,'L');
			}*/
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(255) ;
			$this->SetTextColor(0);
			$this->Cell(25,	6,			'CEDULA',			'TB',0,'C',1);
			$this->Cell(60,		6,			'NOMBRE Y APELLIDOS',		'TB',0,'C',1);
			$this->Cell(27,	6,			utf8_decode('FEC ING'),			'TB',0,'C',1);
			$this->Cell(20,	6,			utf8_decode('N° DIAS'),			'TB',0,'C',1);
			$this->Cell(22,	6,			'DEVENGOS',			'TB',0,'C',1);
			$this->Cell(20,	6,			'A PAGAR',			'TB',1,'C',1);
			$this->Ln(6);
			}
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-25);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(40,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(60,3,'Impreso por:',0,0,'R');
			$this->Cell(85,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(80,278,strtoupper($_SESSION['usuario']),40,6);
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
	$sub_neto_devengar = 0;
	$sub_neto_pagar = 0;
	$neto_devengar = 0;
	$neto_pagar = 0;
	$c = 0;
	
	$tipo = $row->fields('tipo');
	$id_tipo_nomina = $row->fields('id_tipo_nomina');
	while (!$row->EOF) 
	{
		
		//transformando la fecha
		$fecha_ingreso = substr($row->fields("fecha_ingreso"),8,2)."-".substr($row->fields("fecha_ingreso"),5,2)."-".substr($row->fields("fecha_ingreso"),0,4);
		//
		//calculando la antiguedad de la persona
		$di = substr($row->fields("fecha_ingreso"),8,2);
		$mi = substr($row->fields("fecha_ingreso"),5,2);
		$ai = substr($row->fields("fecha_ingreso"),0,4);
		$total_anos =0;
		$da = 26;
		$ma = substr($_GET['desde'],3,2);
		$aa = substr($_GET['desde'],6,4);
		$fecha_ini = mktime(0,0,0,$mi,$di,$ai);
		$fecha_fin = mktime(0,0,0,$ma,$da,$aa);
		$total_anos = ($fecha_fin - $fecha_ini)/31536000;
		$total_meses = ($fecha_fin - $fecha_ini)/2592000;
		$pos = strpos($total_anos,'.');
		$total_anos = substr($total_anos,0,$pos);
		
		$pos = strpos($total_meses,'.');
		if($pos!='')
		$total_meses = substr($total_meses,0,$pos);
		//	
		
		
		//ciclo para filtrar por tipo de nomina
		$exi_tipo=0;
		for($i=0; $i<$cant; $i++){
			if($row->fields('id_tipo_nomina')==$tipo_nomina[$i]){
				$exi_tipo = 1;
				break;
			}
		}
		//
		if($exi_tipo==1){
		//varible para la cantidad de trabajadores
		//calculando el sueldo del trabajador
		$ultimo_sueldo = 0;
		$sql_sueldo = "SELECT 
						historico_nomina.monto_concepto						
					FROM
						historico_nomina
					INNER JOIN
						nominas
					ON
						historico_nomina.id_nominas = nominas.id_nominas
					WHERE 
						historico_nomina.id_trabajador = ".$row->fields("id_trabajador")."
					AND
						historico_nomina.id_concepto is null
					AND
						nominas.desde BETWEEN '$fecha_inicio' AND '$fecha_desde'
					AND
						historico_nomina.id_organismo = $_SESSION[id_organismo]	
				";
			$row_sueldo =& $conn->Execute($sql_sueldo);
			$ultimo_sueldo=0;
			while(!$row_sueldo->EOF){
				$sueldo = $row_sueldo->fields('monto_concepto');
				$sueldo = redondeo($sueldo);
				$ultimo_sueldo=$ultimo_sueldo+$sueldo;
				$row_sueldo->MoveNext();
			}
		/*$suel = $row->fields("sueldo_aumento");
		$suel = str_replace('{','',$suel);
		$suel = str_replace('}','',$suel);
		$sueldo = split(',',$suel);
		for($i=0; $i<=19; $i++){
			if($sueldo[$i]!=0)
				$ultimo_sueldo = $sueldo[$i];  
		}*/
		//$ultimo_sueldo = $ultimo_sueldo / $frecuencia;
		//
		//calculando los conceptos del trabajador
		
		if($tipo!=$row->fields('tipo')){
			$sql_con_tra = "SELECT 
								id_con_cuen,
								id_concepto,
								id_clasi_presu,
								id_cuenta_contable
							FROM
								conceptos_cuenta
							WHERE
								id_concepto = $id_concep_pat
							AND
								id_tipo_nomina = $id_tipo_nomina
							AND
								id_organismo = $_SESSION[id_organismo]";
			$row_con_tra =& $conn->Execute($sql_con_tra);
			$del_pro_pres = "DELETE FROM provision_prestaciones WHERE id_tipo_nomina = $id_tipo_nomina AND fecha = '$fecha_hasta' AND estatu = 1 AND id_organismo = $_SESSION[id_organismo]";
			$conn->Execute($del_pro_pres);
			$sql_pro_pres = "INSERT INTO provision_prestaciones (
								id_concepto,
								monto_concepto,
								id_tipo_nomina,
								fecha,
								id_con_cuen,
								estatu,
								id_organismo) 
							VALUES (
								$id_concep_pat,
								'$sub_neto_pagar',
								$id_tipo_nomina,
								'$fecha_hasta',
								".$row_con_tra->fields('id_con_cuen').",
								1,
								$_SESSION[id_organismo]
								)";
			$conn->Execute($sql_pro_pres);
			//$pdf->Cell(140,		6,			$sql_pro_pres,		0,1,'L',1);
			$pdf->Cell(140,		6,			utf8_decode('SUB-TOTAL: '),		0,0,'L',1);
			$pdf->Cell(18,		6,			$sub_neto_devengar,		0,0,'L',1);
			$pdf->Cell(40,		6,			$sub_neto_pagar,		0,1,'L',1);
			//$pdf->Ln(7);
			$pdf->Cell(18,		6,			utf8_decode('TIPO DE NOMINA: '.$tipo),		0,1,'L',1);
			$pdf->Cell(18,		6,			utf8_decode('N° TRABAJADORES: '.$c),		0,1,'L',1);
			
			//$pdf->Cell(157,		6,			utf8_decode('SUB-TOTAL PAGAR: '),		0,0,'L',1);
			
			$tipo = $row->fields('tipo');
			$id_tipo_nomina = $row->fields('id_tipo_nomina');
			$c=0;
			
			$neto_devengar = $neto_devengar + $sub_neto_devengar;
			$neto_pagar = $neto_pagar + $sub_neto_pagar;
			//$sub_neto_devengar = $total;
			//$sub_neto_pagar = $pagar;
			$sub_neto_devengar=0;
			$sub_neto_pagar=0;
			$pdf->AddPage();
			
		}	
		
		
		$sql_con = "SELECT 
						historico_nomina.id_nomina,
						historico_nomina.id_concepto,
						historico_nomina.asignacion_deduccion,
						historico_nomina.cedula,
						historico_nomina.monto_concepto,
						conceptos.descripcion,
						conceptos.estatus
					FROM
						historico_nomina
					INNER JOIN
						conceptos
					ON
						historico_nomina.id_concepto = conceptos.id_concepto
					INNER JOIN
						nominas
					ON
						historico_nomina.id_nominas = nominas.id_nominas
					WHERE 
						historico_nomina.id_trabajador = ".$row->fields("id_trabajador")."
					AND 
						upper(historico_nomina.asignacion_deduccion) = 'ASIGNACION'
					AND
						conceptos.estatus != 2
					AND
						historico_nomina.id_organismo = $_SESSION[id_organismo]
					AND
						nominas.desde BETWEEN '$fecha_inicio' AND '$fecha_desde'
					";
		$row_con = $conn->Execute($sql_con);
		$total = 0; 
		$pagar = 0;
		if($total_meses>3 && $ultimo_sueldo!=0){
			$c++;
		while(!$row_con->EOF){
				$monto = $row_con->fields("monto_concepto");
				if($row_con->fields('estatus')!=2)
					$monto = redondeo($monto);
				//if($row_con->fields('estatus')==2)
					//$monto = round($monto,2);
					//$monto = redondeo_exac($monto);
				$total = $total + $monto;
			$row_con->MoveNext();
		}
		//
		//
		$sql_con_est = "SELECT 
						historico_nomina.cedula,
						historico_nomina.monto_concepto,
						conceptos.descripcion
					FROM
						historico_nomina
					INNER JOIN
						conceptos
					ON
						historico_nomina.id_concepto = conceptos.id_concepto
					INNER JOIN
						nominas
					ON
						historico_nomina.id_nominas = nominas.id_nominas
					WHERE 
						historico_nomina.id_trabajador = ".$row->fields("id_trabajador")."
					AND 
						upper(historico_nomina.asignacion_deduccion) = 'ASIGNACION'
					AND
						conceptos.estatus = 2
					AND
						historico_nomina.id_organismo = $_SESSION[id_organismo]
					AND
						nominas.desde BETWEEN '$fecha_desde' AND '$fecha_hasta'
					";
		$row_con_est = $conn->Execute($sql_con_est);
		while(!$row_con_est->EOF){
				$monto = $row_con_est->fields("monto_concepto");
				$monto = $monto*2;	
				$monto = redondeo_exac($monto);
				$total = $total + $monto;
			$row_con_est->MoveNext();
		}
		//
		//
		//$monto = round($monto,2);
		//$total = round($total,2);
		//$monto = redondeo_exac($monto);
		$total = redondeo_exac($total);
		$total = $total + $ultimo_sueldo;
		//$total = $total_asig - $total_ded;
		//redondeando el total
		//$total = $total * 100;
		//$total = round($total);
		//$total = $total / 100;
		$pagar = $total;
		$total = redondeo($total);		
		$sub_neto_devengar = $sub_neto_devengar + $total;	
		//
		$cad='';
		$cedula = $row->fields("cedula");
		$cedula = split('-',$cedula);
		$cedula[1]=trim($cedula[1]);
		/*$tam = strlen($cedula[1]);
		$tam = 10 - $tam;
		for($i=1; $i<=$tam; $i++){
			$cad.='0'; 
		}*/
		
		//formula para calcular provision prestaciones
		$num_dias = 5;
		if($mes==$mi && $total_anos >= 1){
			for($i=1; $i<$total_anos; $i++){
				$num_dias = $num_dias + 2;  
			}
		}
		if($num_dias > 35)
			$num_dias = 35;
		
		$pagar = ($pagar / 30) * $num_dias;
		//$pagar = round($pagar,2);
		//$pagar = $pagar * 100;
		//$pagar = round($pagar);
		//$pagar = $pagar / 100;
		//$pagar = redondeo($pagar);
		$pagar = redondeo_exac($pagar);
		/*if($row->fields('id_trabajador')==60)
		$pagar = $pagar-0.01;
		if($row->fields('id_trabajador')==44)
		$pagar = $pagar+0.01;
		if($row->fields('id_trabajador')==127)
		$pagar = $pagar-0.01;
		if($row->fields('id_trabajador')==142)
		$pagar = $pagar-0.01;
		if($row->fields('id_trabajador')==153)
		$pagar = $pagar+0.01;*/
		$sub_neto_pagar = $sub_neto_pagar + $pagar;

		$y = $pdf->Gety();
		
		$pdf->Cell(20,		6,			$cedula[1],		0,0,'L',1);
		$pdf->Cell(70,		6,			$row->fields("nombre").",".$row->fields("apellido"),		0,0,'L','L');
		$pdf->Cell(30,		6,			$fecha_ingreso,		0,0,'L',1);
		$pdf->Cell(20,		6,			$num_dias,		0,0,'L',1);
		//formateando los decimales del total
		$pos = strpos($total,'.');
		if($pos=='')
			$total.=',00';
		if($pos!=''){
			$tam = strlen($total);
			$res = $tam - $pos;
			if($res <= 2)
				$total.='0';
			if($res > 3)
				$total = substr($total, 0, $pos+3);
			$total = str_replace('.',',',$total);  
		}
		//
		$pdf->Cell(18,		6,			$total,		0,0,'L',1);
		//
		//
		
		$pos = strpos($pagar,'.');
		if($pos=='')
			$pagar.=',00';
		if($pos!=''){
			$tam = strlen($pagar);
			$res = $tam - $pos;
			if($res <= 2)
				$pagar.='0';
			if($res > 3)
				$pagar = substr($pagar, 0, $pos+3);
			$pagar = str_replace('.',',',$pagar);  
		}
		//
		$pdf->Cell(18,		6,			$pagar,		0,1,'L',1);
		
		}
		//$pdf->Ln(16);
		if($y>='250.00125')
			$pdf->AddPage();
		//
		
		//
		//if($row->EOF)
			//$tipo = $row->fields('tipo');
		
		}
		$row->MoveNext();
		
		if($row->EOF){
			$sql_con_tra = "SELECT 
								id_con_cuen,
								id_concepto,
								id_clasi_presu,
								id_cuenta_contable
							FROM
								conceptos_cuenta
							WHERE
								id_concepto = $id_concep_pat
							AND
								id_tipo_nomina = $id_tipo_nomina
							AND
								id_organismo = $_SESSION[id_organismo]";
			$row_con_tra =& $conn->Execute($sql_con_tra);
			$del_pro_pres = "DELETE FROM provision_prestaciones WHERE id_tipo_nomina = $id_tipo_nomina AND fecha = '$fecha_hasta' AND estatu = 1 AND id_organismo = $_SESSION[id_organismo]";
			$conn->Execute($del_pro_pres);
			$sql_pro_pres = "INSERT INTO provision_prestaciones (
								id_concepto,
								monto_concepto,
								id_tipo_nomina,
								fecha,
								id_con_cuen,
								estatu,
								id_organismo) 
							VALUES (
								$id_concep_pat,
								'$sub_neto_pagar',
								$id_tipo_nomina,
								'$fecha_hasta',
								".$row_con_tra->fields('id_con_cuen').",
								1,
								$_SESSION[id_organismo]
								)";
			$conn->Execute($sql_pro_pres);
			$pdf->Cell(140,		6,			utf8_decode('SUB-TOTAL: '),		0,0,'L',1);
			$pdf->Cell(18,		6,			$sub_neto_devengar,		0,0,'L',1);
			$pdf->Cell(40,		6,			$sub_neto_pagar,		0,1,'L',1);
			//$pdf->Ln(7);
			$pdf->Cell(18,		6,			utf8_decode('TIPO DE NOMINA: '.$tipo),		0,1,'L',1);
			$pdf->Cell(18,		6,			utf8_decode('N° TRABAJADORES: '.$c),		0,1,'L',1);
			//$pdf->Cell(157,		6,			utf8_decode('SUB-TOTAL PAGAR: '),		0,0,'L',1);
			$c=0;
			$neto_devengar = $neto_devengar + $sub_neto_devengar;
			$neto_pagar = $neto_pagar + $sub_neto_pagar;
			//$sub_neto_devengar = 0;
			//$sub_neto_pagar = 0;
			$pdf->Cell(140,		6,			'TOTAL GENERAL: ',		0,0,'L',1);
			$pdf->Cell(17,		6,			$neto_devengar,		0,0,'L',1);
			$pdf->Cell(17,		6,			$neto_pagar,		0,1,'L',1);
		}
		
	}
	
	/*$pdf->SetFont('arial','',10);
	$pdf->Ln(10);
	$y = $pdf->GetY(); 
	if($y>='226.00125')
			$pdf->AddPage();
	$pdf->Cell(10,		6,			"",		0,0,'C',1);
	$pdf->Cell(52,		6,			utf8_decode("N° TRABAJADORES:			").$c,		1,0,'L',1);
	//$pdf->Ln(6);
	//$pdf->Cell(10,		6,			"",		0,0,'C',1);
	//$pdf->Cell(52,		6,			"TOTAL A PAGAR:			".$neto,		0,0,'L',1);

	/*if($exi!=0){
		$x = $pdf->GetX();
		$y = $pdf->GetY();
	$pdf->SetFont('Times','B',8);
	$pdf->SetLineWidth(0.3);
	$pdf->SetFillColor(230) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(250,	6,			'TOTAL',			1,0,'R',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(30,	6,			substr($row_total->fields("total"),1,strlen($row_total->fields("total"))),			1,0,'C',1);
	}*/
	if($exi==0){
		$pdf->Ln(60);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,70,285,70);
		$pdf->Cell(250,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",178,80,50);
		$pdf->Line(10,140,285,140);
	}
	$pdf->Output();
//}
?>