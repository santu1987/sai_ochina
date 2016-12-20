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
				  
				  AND banco_cuentas.id_organismo=".$_SESSION["id_organismo"]."			

					 ";
// 	 
if(isset($_GET[banco]))
{
	$banco=$_GET[banco];
	$where.=" AND banco_cuentas.id_banco='$banco'";
}
//
if(isset($_GET[busq_cuenta]))
{
	$busq_cuenta=$_GET[busq_cuenta];
	$where.=" AND banco_cuentas.cuenta_banco like '%$busq_cuenta%'";
}
//
if(isset($_GET['busq_ano']))
{
	$busq_ano=$_GET['busq_ano'];
	if($busq_ano!="")
	{
		$where.="AND banco_cuentas.ayo='$busq_ano'";
	}else
	$where.="AND banco_cuentas.ayo='$ayo'";
}else
$where.="AND banco_cuentas.ayo='$ayo'";
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
		
$row=& $conn->Execute($Sql);

// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//----------------------------------------------------------------------------------------------------
			$cuenta=$row->fields("cuenta_banco");
			$Sql2="SELECT distinct
					banco_cuentas.id_cuenta_banco,
					banco_cuentas.cuenta_banco,
					banco_cuentas.estatus	FROM 
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
			AND
				chequeras.estatus='1'
			AND
				chequeras.ultimo_emitido!='0'
			AND
				banco_cuentas.cuenta_banco='$cuenta'		
			";	 
			$row_nuevo=& $conn->Execute($Sql2);
			if(!$row_nuevo->EOF)
				{
					$cuenta_banco_cheques=$row_nuevo->fields("cuenta_banco");
				}	
						else
						{
							$cuenta_banco_cheques="";
						}
//-----------------------------------------------------------------------------------------------------
					//-
					if ($row->fields("estatus")=="1")
							$estatus="Activo";
					else
						if ($row->fields("estatus")=="2")
								$estatus="Inactivo";
						$responce->rows[$i]['id']=$row->fields("id_cuenta_banco");
					
						$responce->rows[$i]['cell']=array(	
															$row->fields("id_cuenta_banco"),
															$row->fields("cuenta_banco"),
															$estatus,
															$cuenta_banco_cheques														
														);
						$i++;
						$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>