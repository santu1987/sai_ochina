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
if(isset($_GET['busq_banco']))
{
	$busq_banco=strtolower($_GET['busq_banco']);
	$where.="and lower(banco.nombre) like '%$busq_banco%'";
}
if(isset($_GET['busq_cuenta']))
{
	$busq_cuenta=strtolower($_GET['busq_cuenta']);
	$where.="and usuario_banco_cuentas.cuenta_banco like '%$busq_cuenta%'";
}
if(isset($_GET['busq_usuario']))
 {
	 $usuario=strtoupper($_GET['busq_usuario']);
	 $where.="AND upper(usuario.nombre) like '%$usuario%'";	
}
$Sql="
			SELECT 
				count(usuario_banco_cuentas.id_usuario_banco_cuentas) 
			FROM 
				usuario_banco_cuentas
			INNER JOIN 
				organismo 
			ON 
				usuario_banco_cuentas.id_organismo = organismo.id_organismo
			INNER JOIN 
				usuario 
			ON 
				usuario_banco_cuentas.id_usuario = usuario.id_usuario
			INNER JOIN 
				banco
			ON 
				usuario_banco_cuentas.id_banco = banco.id_banco
			WHERE
				usuario_banco_cuentas.id_organismo=$_SESSION[id_organismo]
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

// the actual query for the grid data
$Sql="
			SELECT distinct
				usuario_banco_cuentas.id_usuario_banco_cuentas,
				usuario_banco_cuentas.id_organismo,
				usuario_banco_cuentas.id_banco,
				banco.nombre as banco,
				usuario_banco_cuentas.cuenta_banco,
				usuario_banco_cuentas.estatus,
				usuario_banco_cuentas.comentarios,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.id_usuario		
			FROM 
				usuario_banco_cuentas
			INNER JOIN 
				banco
			ON 
				usuario_banco_cuentas.id_banco = banco.id_banco
			INNER JOIN 
				organismo 
			ON 
				usuario_banco_cuentas.id_organismo = organismo.id_organismo
			INNER JOIN 
				usuario 
			ON 
				usuario_banco_cuentas.id_usuario = usuario.id_usuario
			WHERE
				usuario_banco_cuentas.id_organismo=$_SESSION[id_organismo]
			$where				
			ORDER BY
				banco.nombre,usuario_banco_cuentas.cuenta_banco,usuario_banco_cuentas.estatus
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
	$nom=$row->fields("nombre");
	$ape=$row->fields("apellido");
	$nombre=$nom."  ". $ape;	
	if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
		if ($row->fields("estatus")=="2")
				$estatus="Inactivo";		
	$responce->rows[$i]['id']=$row->fields("id_usuario_banco_cuentas");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_usuario_banco_cuentas"),
															$row->fields("id_organismo"),
															$row->fields("id_banco"),
															$row->fields("banco"),
															substr($row->fields("banco"),0,15),
															$row->fields("id_usuario"),
															$nombre,
															$row->fields("cuenta_banco"),	
															$estatus,
															$row->fields("comentarios")
																								
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>