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
$beneficiario = $_POST[cuentas_por_pagar_orden_db_reporte_empleado_codigo];

$where.="AND cedula_rif_beneficiario='$beneficiario'";
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(documentos_cxp.cedula_rif_beneficiario)
			FROM 
				documentos_cxp
			INNER JOIN		
				organismo 
			ON
					documentos_cxp.id_organismo=organismo.id_organismo 
			WHERE 
					(documentos_cxp.id_organismo=$_SESSION[id_organismo] )
			$where
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} 
else {
	$total_pages = 0;
}

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

$Sql = "	SELECT
				documentos_cxp.cedula_rif_beneficiario,documentos_cxp.beneficiario
				FROM 
				documentos_cxp
			INNER JOIN		
				organismo 
			ON
					documentos_cxp.id_organismo=organismo.id_organismo 
			WHERE 
					(documentos_cxp.id_organismo=$_SESSION[id_organismo] )
			$where		
			";
$row=& $conn->Execute($Sql);
// constructing a JSON
/*$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;*/
$i=0;
if (!$row->EOF) 
{
	if($row->fields("beneficiario")!=NULL)
	{
		//$responce->rows[$i]['id']=$row->fields("cedula_rif_beneficiario");
		$responce =$row->fields("cedula_rif_beneficiario")."*". $row->fields("beneficiario");
		
	}
	die ($responce);
}
// return the formated data
//echo $json->encode($responce);
?>