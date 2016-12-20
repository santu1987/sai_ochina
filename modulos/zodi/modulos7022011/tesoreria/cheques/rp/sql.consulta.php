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
///************************************************************************
$Sql="
			SELECT 
				 count(usuario_banco_cuentas.id_usuario_banco_cuentas) 
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
			INNER JOIN 
				banco_cuentas
			ON 
				usuario_banco_cuentas.cuenta_banco=banco_cuentas.cuenta_banco
			ORDER BY
				banco.nombre,usuario_banco_cuentas.cuenta_banco,usuario_banco_cuentas.estatus
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
				usuario_banco_cuentas.id_usuario_banco_cuentas,
				usuario_banco_cuentas.id_organismo,
				usuario_banco_cuentas.id_banco,
				banco.nombre as banco,
				usuario_banco_cuentas.cuenta_banco,
				usuario_banco_cuentas.estatus,
				usuario_banco_cuentas.comentarios,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.id_usuario,
				banco.estatus AS estatusb,
				banco_cuentas.estatus AS estatusc 		
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
			INNER JOIN 
				banco_cuentas
			ON 
				usuario_banco_cuentas.cuenta_banco=banco_cuentas.cuenta_banco
			ORDER BY
				banco.nombre,usuario_banco_cuentas.cuenta_banco,usuario_banco_cuentas.estatus		
				
";

$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$nom=$row->fields("nombre");
	$ape=$row->fields("apellido");
	$nombre_usuario=$nom."  ". $ape;
	//-------------------------------
	if ($row->fields("estatusb")=="1")
		$estatusb="Activo";
	else
	if ($row->fields("estatusb")=="2")
			$estatusb="Inactivo";
	if ($row->fields("estatusc")=="1")
		$estatusb="Activo";
	else
	if ($row->fields("estatusc")=="2")
			$estatusb="Inactivo";		
	//----------------------------------			
	$responce->rows[$i]['id']=$row->fields("id_usuario");

	$responce->rows[$i]['cell']=array(													
															$nombre_usuario,
															$row->fields("banco"),
															$row->fields("cuenta_banco"),
															$estatusb,
															$estatusc
															
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>