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
if(isset($_GET['proveedor']))
{
	$id_proveedor=$_GET['proveedor'];
	if($id_proveedor!="")
	{
		$where.="AND
					cheques.id_proveedor='$id_proveedor'";
	}
}
/*if(isset($_GET['beneficiario']))
{
	$beneficiario=$_GET['beneficiario'];
	$where.="AND
					cheques.cedula_rif_beneficiario='$beneficiario'";
}*/
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
				SELECT 
					count(numero_cheque)
				FROM 
					cheques
				INNER JOIN 
					organismo
				ON
					cheques.id_organismo=organismo.id_organismo
				INNER JOIN 
					banco
				ON
					cheques.id_banco=banco.id_banco
				INNER JOIN 
					banco_cuentas
				ON
					cheques.cuenta_banco=banco_cuentas.cuenta_banco
				WHERE 
					(cheques.id_organismo=$_SESSION[id_organismo] )
				AND
					 (numero_cheque<0)
				
				AND
					cheques.tipo_cheque='1'
				AND
					cheques.ordenes!='{0}'
				AND		
				cheques.id_organismo=$_SESSION[id_organismo]
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
					id_cheques,
					banco.id_banco,
					banco.nombre AS banco,
					banco_cuentas.cuenta_banco,
					cheques.numero_cheque AS n_precheque,
					cheques.id_proveedor,
					cheques.concepto,
					cheques.monto_cheque,
					porcentaje_itf,
					cheques.ordenes,
					cheques.porcentaje_islr as islr,
					cheques.sustraendo,
					cheques.benef_nom										
				FROM 
					cheques
				INNER JOIN 
					organismo
				ON
					cheques.id_organismo=organismo.id_organismo
				INNER JOIN 
					banco
				ON
					cheques.id_banco=banco.id_banco
				INNER JOIN 
					banco_cuentas
				ON
					cheques.cuenta_banco=banco_cuentas.cuenta_banco
				WHERE 
					(cheques.id_organismo=$_SESSION[id_organismo] )
				AND
					 (numero_cheque<0) 
				
				AND
					cheques.tipo_cheque='2'	
				AND
					cheques.estatus='1'	
				AND
					cheques.ordenes!='{0}'	
				$where			 
				";

$row=& $conn->Execute($Sql);

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{

	$responce->rows[$i]['id']=$row->fields("id_cheques");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_cheques"),
															$row->fields("n_precheque"),
															$row->fields("id_banco"),
															$row->fields("banco"),
															$row->fields("cuenta_banco"),
															$row->fields("id_proveedor"),
															number_format($row->fields("monto_cheque"),2,',','.'),
															$row->fields("concepto"),
															$row->fields("porcentaje_itf"),
															$row->fields("ordenes"),
															number_format($row->fields("islr"),2,',','.'),	
															$row->fields("sustraendo"),
															$row->fields("benef_nom")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>