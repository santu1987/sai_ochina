<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//************************************************************************
$limit = 15;



if(!$sidx) $sidx =1;
$where = " WHERE 1 = 1 ";
$id_unidad = "";
if($_POST['aumento_sueldos_rp_id_unidad_ejecutora']!='')
	$id_unidad = $_POST['aumento_sueldos_rp_id_unidad_ejecutora'];
if($id_unidad!='')
	$where.= " AND trabajador.id_unidad = $id_unidad ";	
$cedula = strtolower($_POST["aumento_sueldos_rp_cedula_trabajador"]);
$sql = "SELECT 
			trabajador.id_trabajador,
			persona.cedula,
			persona.nombre,
			persona.apellido
		FROM
			persona
		INNER JOIN
			trabajador
		ON
			persona.id_persona = trabajador.id_persona
		".$where."
		AND
			lower(persona.cedula) like '%$cedula%' 
		AND
			persona.id_organismo = $_SESSION[id_organismo] ";

$row=& $conn->Execute($sql);
$arreglo = "";
if($row->fields("id_trabajador")!=''){
	$arreglo = $row->fields("id_trabajador")."*".$row->fields("cedula")."*".$row->fields("nombre")."*".$row->fields("apellido");
}
echo $arreglo;
?>