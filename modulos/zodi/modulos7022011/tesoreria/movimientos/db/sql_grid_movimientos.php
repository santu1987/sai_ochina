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
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//************************************************************************
$limit = 20;
if(!$sidx) $sidx =1;
$where2="WHERE movimientos_cuentas.id_organismo=$_SESSION[id_organismo] ";
if(isset($_GET['busq_banco']))
{
	$busq_banco=strtolower($_GET['busq_banco']);
	$where2.="and lower(banco.nombre) like '%$busq_banco%'";
}
if(isset($_GET['busq_cuenta']))
{
	$busq_cuenta=strtolower($_GET['busq_cuenta']);
	$where2.="and movimientos_cuentas.cuenta_banco like '%$busq_cuenta%'";
}
$Sql="SELECT 
				count(movimientos_cuentas.id_movimientos_cuentas) 
				FROM 
				movimientos_cuentas
			INNER JOIN 
				 banco
			ON 
				movimientos_cuentas.id_banco =banco.id_banco
			INNER JOIN 
				banco_cuentas
			ON 
				movimientos_cuentas.cuenta_banco =banco_cuentas.cuenta_banco	
			INNER JOIN 
				organismo 
			ON 
				movimientos_cuentas.id_organismo = organismo.id_organismo
			$where2	
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

// the actual query for the grid data
$Sql="
			SELECT 
				movimientos_cuentas.id_movimientos_cuentas,
				organismo.id_organismo,
				movimientos_cuentas.id_banco,
				banco.nombre,
				movimientos_cuentas.cuenta_banco,
				movimientos_cuentas.referencia,
				movimientos_cuentas.fecha_proceso,
				movimientos_cuentas.monto,
				banco_cuentas.saldo_actual
			FROM 
				movimientos_cuentas
			INNER JOIN 
				 banco
			ON 
				movimientos_cuentas.id_banco =banco.id_banco
			INNER JOIN 
				banco_cuentas
			ON 
				movimientos_cuentas.cuenta_banco =banco_cuentas.cuenta_banco	
			INNER JOIN 
				organismo 
			ON 
				movimientos_cuentas.id_organismo = organismo.id_organismo
			$where2
			order by 
				banco.nombre,movimientos_cuentas.cuenta_banco
";
		
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	
	$fecha_proceso = substr($row->fields("fecha_proceso"),0,10);
	$fecha_proceso = substr($fecha_proceso,8,2)."".substr($fecha_proceso,4,4)."".substr($fecha_proceso,0,4);
	
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";	
	$responce->rows[$i]['id']=$row->fields("id_movimientos_cuentas");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_movimientos_cuentas"),
															$row->fields("id_organismo"),
         													$row->fields("id_banco"),
															$row->fields("nombre"),
															substr($row->fields("nombre"),0,15), 
															$row->fields("cuenta_banco"),
															$row->fields("referencia"),
															number_format($row->fields("monto"),2,',','.'),
															$fecha_proceso,
															number_format($row->fields("saldo_actual"),2,',','.'),
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>