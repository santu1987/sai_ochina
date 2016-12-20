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
if(isset($_GET['tesoreria_busqueda_banco_chequera']))
{
$busq_banco=strtoupper($_GET['tesoreria_busqueda_banco_chequera']);
	if($busq_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$busq_banco%')";
}
if(isset($_GET['tesoreria_busqueda_ncuenta']))
$busq_cuenta =$_GET['tesoreria_busqueda_ncuenta'];
if(isset($_GET['tesoreria_busqueda_nchequera']))
$busq_chequera =$_GET['tesoreria_busqueda_nchequera'];
///************************************************************************


if($busq_cuenta!='')
	$sql_where.= " AND  ((banco_cuentas.cuenta_banco) like '%$busq_cuenta%')";
if($busq_chequera!='')
	$sql_where.= " AND  ((chequeras.secuencia)='$busq_chequera')";


$Sql="
			SELECT 
				 count(chequeras.id_chequeras) 
			FROM 
				chequeras
			INNER JOIN
				organismo
			ON
				chequeras.id_organismo =chequeras.id_organismo
			INNER JOIN
				banco
			ON
				chequeras.id_banco = banco.id_banco
			INNER JOIN
				banco_cuentas
			ON
				chequeras.cuenta = banco_cuentas.cuenta_banco
			$sql_where	
";

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
				banco.nombre as nombre,
				banco_cuentas.cuenta_banco,
				chequeras.secuencia,
				chequeras.cantidad_cheques,
				chequeras.estatus as estatus	 		
			FROM 
				chequeras
			INNER JOIN
				organismo
			ON
				chequeras.id_organismo =chequeras.id_organismo
			INNER JOIN
				banco
			ON
				chequeras.id_banco = banco.id_banco
			INNER JOIN
				banco_cuentas
			ON
				chequeras.cuenta = banco_cuentas.cuenta_banco
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
	else
	if ($row->fields("estatus")=="3")
			$estatus="Agotado";			
	$responce->rows[$i]['id']=$row->fields("id_banco_cuentas");

	$responce->rows[$i]['cell']=array(													
															$row->fields("nombre"),
															$row->fields("cuenta_banco"),
															$row->fields("secuencia"),
															$row->fields("cantidad_cheques"),
															$estatus
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>