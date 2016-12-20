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
//$codigo = $_POST['tesoreria_cheque_manual_pr_proveedor_codigo'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
	$codigo = $_POST['cuentas_por_pagar_retenciones_proveedor_codigo'];

		$Sql="
					SELECT 
						count(proveedor.id_proveedor)
					FROM 
							organismo 
						INNER JOIN 
							proveedor 
						ON
							proveedor.id_organismo=organismo.id_organismo 
						WHERE 
						codigo_proveedor= '".$codigo."'
						AND	
						(proveedor.id_organismo=$_SESSION[id_organismo] )
					";

/*if($opcion=='2')
{$codigo = $_POST['tesoreria_cheque_manual_pr_empleado_codigo'];

			$Sql="
					SELECT 
						count(cheques.cedula_rif_beneficiario)
					FROM 
							cheques
					INNER JOIN 
							organismo 
						ON
							cheques.id_organismo=organismo.id_organismo 
						WHERE 
						cheques.cedula_rif_beneficiario= '".$codigo."'
						AND	
						(cheques.id_organismo=$_SESSION[id_organismo] )
					";
}									
*/$row=& $conn->Execute($Sql);
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

// the actual query for the grid data
/*if($opcion=='1')
{	*/
		$Sql="	
						SELECT 
							proveedor.id_proveedor,
							proveedor.nombre as proveedor,
							proveedor.rif,
							proveedor.ret_iva,
							proveedor.ret_islr
						FROM 
							organismo 
						INNER JOIN 
							proveedor 
						ON
							proveedor.id_organismo=organismo.id_organismo 
						WHERE 
							(proveedor.id_organismo=$_SESSION[id_organismo] )
							AND
							codigo_proveedor= '".$codigo."'	
						ORDER BY 
							proveedor.nombre 
							";
/*}
if($opcion=='2')
{
	$Sql="	
						SELECT 
							cheques.cedula_rif_beneficiario,
							cheques.nombre_beneficiario	
						FROM 
							cheques 
						INNER JOIN 
							organismo 
						ON
							cheques.id_organismo=organismo.id_organismo 
						WHERE 
							(cheques.id_organismo=$_SESSION[id_organismo] )
							AND
							cedula_rif_beneficiario= '".$codigo."'	
						ORDER BY 
							cheques.nombre_beneficiario	
							";
}
*/$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

if (!$row->EOF) 
{
/*if($opcion=='1')
{*/	
	$id_proveedor=$row->fields("id_proveedor");
	$proveedor=$row->fields("proveedor");
	$rif=$row->fields("rif");
/*}
if($opcion=='2')
{	
	$id_proveedor="";
	$proveedor=$row->fields("nombre_beneficiario");
	$rif="";
}*/
	$responce->rows[$i]['id']=$row->fields("id_proveedor");
	$responce =$id_proveedor."*". $proveedor."*". $rif."*".number_format($row->fields("ret_iva"),2,',','.')."*".number_format($row->fields("ret_islr"),2,',','.');
	echo ($responce);
//	  echo $json->encode($responce  );
    
}


?>
