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
$ano = date('Y');
if(!$sidx) $sidx =1;

$numero_requision = $_GET['numero_requision'];

$where = 'WHERE (1=1)';

if ($numero_requision != "")
	$where.= " AND (numero_requision = '".$numero_requision."') AND (id_unidad_ejecutora = ".$_SESSION["id_unidad_ejecutora"].")";
else
	$where.= " AND (numero_requision = '0') AND (id_unidad_ejecutora = ".$_SESSION["id_unidad_ejecutora"].")";



$Sql="
		SELECT 
			requisicion_detalle.id_requisicion_detalle,
			requisicion_detalle.secuencia,
				requisicion_detalle.cantidad, 	 
				requisicion_detalle.id_unidad_medida, 	 
				requisicion_detalle.descripcion,
				requisicion_detalle.numero_requision,
				unidad_medida.nombre 
		FROM 
			requisicion_detalle
		INNER JOIN
			unidad_medida
		ON
			unidad_medida.id_unidad_medida = requisicion_detalle.id_unidad_medida
		$where	
		ORDER BY 
			$sidx $sord 
		LIMIT 
			$limit 
		OFFSET 
			$start ;
";
$row=& $conn->Execute($Sql);
while (!$row->EOF) {

	$opt_modulo.=(($opt_modulo)?",":"").'"'.$row->fields('id_requisicion_detalle').'":"'.$row->fields('secuencia').' '.$row->fields('descripcion').'"';
	$row->MoveNext();
}
?>
{<?=$opt_modulo?>}