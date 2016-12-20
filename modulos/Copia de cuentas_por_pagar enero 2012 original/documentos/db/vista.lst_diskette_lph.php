<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					


$archivo = "N03212000045170178663";
$mes = substr($_GET['hasta'],3,2);
$ano = substr($_GET['hasta'],6,4);
//definiendo que los archivos a crear son txt
header('Content-type: application/txt');
// Creando en archivo con la extencion txt
header('Content-Disposition: attachment; filename="'.$archivo.''.$mes.''.$ano.'.txt"');
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

function redondeo($valor){
	$pos = strpos($valor,'.');
	if($pos!=''){
		$tam = strlen($valor);
		$res = $tam - $pos;
		if($tam >= 3)
			$valor = substr($valor, 0, $pos+3);
	}
	return $valor;
}

//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA

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
$id_concepto = '';
if($_GET['id_concepto']!='')
	$id_concepto = $_GET['id_concepto'];	

//
$cant=0;
$tipo_nomina = $_GET['tipo_nomina'];
$tipo_nomina = split('_',$tipo_nomina);
$cant = count($tipo_nomina);
//
$fecha_inicio ="";
if($_GET['desde']!=''){
	$fecha_desde = $_GET['desde'];
	$ano = substr($fecha_desde,6,4);
	$fecha_inicio = "01-".substr($fecha_desde,3,2)."-".substr($fecha_desde,6,4);
}
if($_GET['hasta']!='')
	$fecha_hasta = $_GET['hasta'];
$where = " WHERE 1 = 1 ";
//if($_GET['id_sitio']!='')
	//$id_sitio = $_GET['id_sitio'];
//if($id_sitio!='')
	//$where.= " AND bienes.id_sitio_fisico = 1 ";
//if($fecha_desde!='' && $fecha_hasta!='')
	//$where.= " AND bienes.fecha_compra BETWEEN '$fecha_desde' AND '$fecha_hasta' ";
	$nominas = $_GET['nominas'];
	$nominas--;
$Sql="SELECT 
				MIN(persona.cedula) as minimo,
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				trabajador.fecha_ingreso,
				aumento_sueldo.sueldo_aumento,
				historico_nomina.id_tipo_nomina
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
				historico_nomina
			ON
				trabajador.id_trabajador = historico_nomina.id_trabajador
			INNER JOIN
				nominas
			ON
				historico_nomina.id_nominas = nominas.id_nominas
			".$where."
			AND
				historico_nomina.ano_nomina = $ano
			AND
				nominas.numero_nomina BETWEEN $nominas AND ".$_GET['nominas']."
			AND
				historico_nomina.id_concepto = $id_concepto 
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
			GROUP BY
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				trabajador.fecha_ingreso,
				historico_nomina.id_tipo_nomina,
				aumento_sueldo.sueldo_aumento
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
	while (!$row->EOF) 
	{
		
		//ciclo para filtrar por tipo de nomina
		$exi_tipo=0;
		for($i=0; $i<$cant; $i++){
			if($row->fields('id_tipo_nomina')==$tipo_nomina[$i]){
				$exi_tipo = 1;
				break;
			}
		}
		if($exi_tipo!=0){
		//varible para la cantidad de trabajadores
		//calculando el sueldo del trabajador
		$ultimo_sueldo = 0;
		//sql para buscar el sueldo del trabajador
		$sql_sueldo = "SELECT 
							monto_concepto
						FROM
							historico_nomina
						INNER JOIN
							nominas
						ON
							historico_nomina.id_nominas=nominas.id_nominas
						WHERE
							nominas.numero_nomina BETWEEN $nominas AND ".$_GET['nominas']."	
						AND
							historico_nomina.id_trabajador = ".$row->fields('id_trabajador')."
						AND
							historico_nomina.id_concepto is null
						AND
							historico_nomina.ano_nomina = $ano
						AND
							historico_nomina.id_organismo = $_SESSION[id_organismo]";
		$row_sueldo =& $conn->Execute($sql_sueldo);
		while(!$row_sueldo->EOF){
			$sueldo = $row_sueldo->fields('monto_concepto');
			$pos = strpos($sueldo,'.');
			if($pos!=''){
				$tam = strlen($sueldo);
				$res = $tam - $pos;
				if($res >= 3)
					$sueldo = substr($sueldo,0,$pos+3); 
			}
			$ultimo_sueldo = $ultimo_sueldo + $sueldo;
			$row_sueldo->MoveNext();
		}
		//if($ultimo_sueldo!=0 || $ultimo_sueldo!=''){
		
		//
		//calculando los conceptos del trabajador
		$sql_con = "SELECT 
						historico_nomina.id_nomina,
						historico_nomina.id_concepto,
						historico_nomina.asignacion_deduccion,
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
						historico_nomina.id_concepto = $id_concepto 
					AND 
						upper(historico_nomina.asignacion_deduccion) = 'DEDUCCION'
					AND
						historico_nomina.id_organismo = $_SESSION[id_organismo]
					AND
						nominas.desde BETWEEN '$fecha_inicio' AND '$fecha_desde'
					";
		$row_con = $conn->Execute($sql_con);
		$total = 0; 
		while(!$row_con->EOF){
			$monto = $row_con->fields("monto_concepto");
			$monto = redondeo($monto);
			$total = $total+$monto;
			$row_con->MoveNext();
		}
		$total = $total * 100;
		//
		//
		
		//
		//
		//$total = $total + $ultimo_sueldo;
		//$total = round($total,2);
		//$total = $total_asig - $total_ded;
		//redondeando el total
		//$total = $total * 100;
		//$total = round($total);
		//$total = $total / 100;
		//formateando los decimales del total
		$pos = strpos($total,'.');
		if($pos=='')
			$total.='00';
		if($pos!=''){
			$tam = strlen($total);
			$res = $tam - $pos;
			if($res <= 2)
				$total.='0';
			if($res > 3)
				$total = substr($total, 0, $pos+3);  
			$total = str_replace('.','',$total);
		}
		//	
		//
		$cad='';
		$nom1='';
		$nom2='';
		$ape1='';
		$ape2='';
		$cedula = $row->fields("cedula");
		$cedula = split('-',$cedula);
		$cedula[1]=trim($cedula[1]);
		$nombre = $row->fields("nombre");
		$nombre = split(" ",$nombre);
		$cant_nom = count($nombre);
		$nom1 = $nombre[0];
		for($i=1; $i<$cant_nom; $i++){
			$nom2.= ' '.$nombre[$i]; 
		}
		$nom2 = trim($nom2);
		$apellido = $row->fields("apellido");
		$apellido = split(" ",$apellido);
		$cant_ape = count($apellido);
		$ape1 = $apellido[0];
		for($i=1; $i<$cant_ape; $i++){
			$ape2.= ' '.$apellido[$i]; 
		}
		$ape2 = trim($ape2);
		//transformando la fecha
		$fecha_ingreso = substr($row->fields("fecha_ingreso"),8,2)."-".substr($row->fields("fecha_ingreso"),5,2)."-".substr($row->fields("fecha_ingreso"),0,4);
		//
		 echo $cedula[0].",".$cedula[1].",".$nom1.",".$nom2.",".$ape1.",".$ape2.",".$total.",".substr($row->fields("fecha_ingreso"),8,2)."".substr($row->fields("fecha_ingreso"),5,2)."".substr($row->fields("fecha_ingreso"),0,4).",\r\n";
		 //echo $row->fields('cedula')."*".$row->fields('nombre')."*".$row->fields('apellido')."\r\n";
		}
		//
		$row->MoveNext();
	}
?>