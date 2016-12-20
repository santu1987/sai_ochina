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
$id_banco=$_POST['tesoreria_cheque_reimpresion2_pr_banco_id_banco'];
$cuenta=$_POST['tesoreria_cheque_reimpresion2_pr_n_cuenta'];
$cheque=$_POST['tesoreria_cheque_reimpresion2_pr_n_cheque'];
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
					 (numero_cheque>0)
				AND
					cheques.id_banco='$id_banco'
				AND
					cheques.cuenta_banco='$cuenta'
				AND
					cheques.numero_cheque='$cheque'
				 
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
					cheques.numero_cheque AS ncheque,
					cheques.secuencia AS secuencia,
					cheques.id_proveedor,
					cheques.monto_cheque,
					cheques.ordenes,
					cheques.nombre_beneficiario,
					cheques.cedula_rif_beneficiario									
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
					cheques.estatus='2'	
				AND
					 (numero_cheque>0)
				AND
					cheques.id_banco='$id_banco'
				AND
					cheques.cuenta_banco='$cuenta'
				AND
					cheques.numero_cheque='$cheque'";

$row=& $conn->Execute($Sql);
//

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//-
if($row->fields("id_proveedor")==true)
{
		$id_proveedor=$row->fields("id_proveedor");
		$sql_prove="select nombre from proveedor where id_proveedor='$id_proveedor'";
		$row_prove=& $conn->Execute($sql_prove);
		$beneficiario=$row_prove->fields("nombre");
		$tipo='1';
}
else
{

	$beneficiario=$row->fields("nombre_beneficiario");
	$tipo='2';

}
//-
$primer=strlen($row->fields("ncheque"));
		$n_cheque=$row->fields("ncheque");
						switch($primer)
									{
										case 1:
										$n_cheque='00000'.$n_cheque;
										break;
										case 2:
										$n_cheque='0000'.$n_cheque;
										break;
										case 3:
										$n_cheque='000'.$n_cheque;
										break;
										case 4:
										$n_cheque='00'.$n_cheque;
										break;
										case 5:
										$n_cheque='0'.$n_cheque;
										break;
										case 6:
										$n_cheque=$n_cheque;
										break;
										
									}

	$responce=$row->fields("id_cheques")."*".$row->fields("id_banco")."*".
$row->fields("banco")."*".$row->fields("cuenta_banco")."*".$row->fields("secuencia")."*".
$n_cheque."*".$beneficiario."*".number_format($row->fields("monto_cheque"),2,',','.')."*";
}
echo ($responce);
?>