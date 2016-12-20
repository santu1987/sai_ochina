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
if(isset($_GET["busq_banco"]))
$busq_banco =strtoupper($_GET["busq_banco"]);
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$where2="WHERE banco_cuentas.id_organismo=$_SESSION[id_organismo] ";
if($busq_banco!="")
{
$where2.= " AND  upper(banco.nombre) like '%$busq_banco%'";
}
$Sql="
			SELECT 
				count(banco_cuentas.id_cuenta_banco) 
			FROM 
				banco_cuentas
			INNER JOIN 
				organismo 
			ON 
				banco_cuentas.id_organismo = organismo.id_organismo
			
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
				banco_cuentas.id_cuenta_banco,
				organismo.id_organismo,
				banco.id_banco,
				banco.nombre,
				banco_cuentas.cuenta_banco,
				banco_cuentas.cuenta_contable_banco,
				banco_cuentas.comentarios,
				banco_cuentas.estatus AS estatus,
				banco_cuentas.ayo,
				banco_cuentas.saldo_inicial,
				banco_cuentas.saldo_actual,
				banco_cuentas.fecha_apertura				
			FROM 
				banco_cuentas
			INNER JOIN 
				 banco
			ON 
				banco_cuentas.id_banco =banco.id_banco
			INNER JOIN 
				organismo 
			ON 
				banco_cuentas.id_organismo = organismo.id_organismo
			$where2
			order by 
				banco.nombre,banco_cuentas.estatus,banco_cuentas.cuenta_banco,
				$sidx $sord 
		LIMIT 
			$limit 
		OFFSET 
			$start ;
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
	
	$fecha_apertura = substr($row->fields("fecha_apertura"),0,10);
	$fecha_apertura = substr($fecha_apertura,8,2)."".substr($fecha_apertura,4,4)."".substr($fecha_apertura,0,4);
	
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";	
	$responce->rows[$i]['id']=$row->fields("id_cuenta_banco");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_cuenta_banco"),
															$row->fields("id_organismo"),
         													$row->fields("id_banco"),
															substr($row->fields("nombre"),0,15),
															$row->fields("nombre"),
															$row->fields("cuenta_banco"),
															$row->fields("cuenta_contable_banco"),
															$row->fields("comentarios"),
															$estatus,
															$row->fields("ayo"),
															number_format($row->fields("saldo_inicial"),2,',','.'),
															number_format($row->fields("saldo_actual"),2,',','.'),
															$fecha_apertura
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>