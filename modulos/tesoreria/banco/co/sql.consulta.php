<?php
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
if(!$sidx) $sidx =1;
$sql_where = "WHERE 1=1";
if(isset($_GET['tesoreria_busqueda_banco']))
{
$busq_banco=strtoupper($_GET['tesoreria_busqueda_banco']);
	if($busq_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$busq_banco%')";
}
if(isset($_GET['busq_ano']))
{
	$busq_ano=$_GET['busq_ano'];
	if($busq_ano!="")
	{
		$sql_where.= " AND  (banco_cuentas.ayo) = '$busq_ano'";
	}
}
//************************************************************************


$Sql="
			SELECT 
				 count(banco_cuentas.id_cuenta_banco) 
			FROM 
				banco_cuentas
			INNER JOIN
				organismo
			ON
				banco_cuentas.id_organismo = organismo.id_organismo
			INNER JOIN
				banco
			ON
				banco_cuentas.id_banco = banco.id_banco
			$sql_where	
";
//die($Sql);
$row=& $conn->Execute($Sql);
/*,presupuesto_ley.partida,presupuesto_ley.generica,
				 presupuesto_ley.especifica,presupuesto_ley.sub_especifica*/

if (!$row->EOF)
{
	$count = $row->fields("count");
	
}
$limit = 15;
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
				banco.nombre,
				banco_cuentas.cuenta_banco,
				banco_cuentas.cuenta_contable_banco,
				banco_cuentas.estatus as estatus	 		
			FROM 
				banco_cuentas
			INNER JOIN
				organismo
			ON
				banco_cuentas.id_organismo = organismo.id_organismo
			INNER JOIN
				banco
			ON
				banco_cuentas.id_banco = banco.id_banco
			".$sql_where."
			ORDER BY 
					banco.nombre,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";

$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";	
	$responce->rows[$i]['id']=$row->fields("id_banco_cuentas");

	$responce->rows[$i]['cell']=array(													
															$row->fields("nombre"),
															$row->fields("cuenta_banco"),
															$row->fields("cuenta_contable_banco"),
															$estatus
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>