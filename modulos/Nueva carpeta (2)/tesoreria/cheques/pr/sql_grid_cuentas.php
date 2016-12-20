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

$ayo=date('Y');
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$where="WHERE 1=1 AND banco_cuentas.estatus='1' 
				  AND chequeras.estatus='1'
				  AND banco_cuentas.id_organismo=".$_SESSION["id_organismo"]."			

					 ";
/*
				    AND  usuario_banco_cuentas.id_usuario=".$_SESSION['id_usuario']."
									  AND usuario_banco_cuentas.estatus='1'	
									  				  AND banco_cuentas.ayo=$ayo



*/
// 	 
if(isset($_GET[banco]))
{
	$banco=$_GET[banco];
	$where.=" AND banco_cuentas.id_banco =$banco";
}

//************************************************************************
//$banco=$_POST['tesoreria_usuario_banco_cuentas_cuenta_id_banco'];

$limit = 15;
if(!$sidx) $sidx =1;

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
					chequeras
				ON
					banco_cuentas.cuenta_banco=chequeras.cuenta
			$where	
";
	/*
	INNER JOIN 
				usuario_banco_cuentas 
			ON 
				banco_cuentas.cuenta_banco =usuario_banco_cuentas.cuenta_banco
	*/   
   //echo($Sql);
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
						banco_cuentas.id_cuenta_banco,
						banco_cuentas.cuenta_banco,
						banco_cuentas.estatus
						FROM 
						banco_cuentas
					INNER JOIN 
						organismo 
					ON 
						banco_cuentas.id_organismo = organismo.id_organismo
					
					INNER JOIN 
						chequeras
					ON
						chequeras.cuenta=banco_cuentas.cuenta_banco
					$where			
";
/*
INNER JOIN 
						usuario_banco_cuentas 
					ON 
						banco_cuentas.cuenta_banco =usuario_banco_cuentas.cuenta_banco
*/
//echo($Sql);				
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
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
	$responce->rows[$i]['id']=$row->fields("id_cuenta_banco");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_cuenta_banco"),
															$row->fields("cuenta_banco"),
															$estatus														
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>