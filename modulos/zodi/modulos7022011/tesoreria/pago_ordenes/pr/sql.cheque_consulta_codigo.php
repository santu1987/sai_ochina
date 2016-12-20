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
$opcion=$_POST['tesoreria_cheques_reimpresion_pr_tipo'];
$sql_where="WHERE(cheques.id_organismo=$_SESSION[id_organismo] )
				AND
					cheques.estatus='2'	
				AND	 (numero_cheque>0)";
if(isset($_POST['tesoreria_cheque_reimpresion_pr_n_cheque']))
{
	$numero_cheque=$_POST['tesoreria_cheque_reimpresion_pr_n_cheque'];
	$sql_where.=" AND cheques.numero_cheque='$numero_cheque'";
}
if(isset($_POST['tesoreria_cheque_reimpresion_pr_banco_id']))
{
	$id_banco=$_POST['tesoreria_cheque_reimpresion_pr_banco_id'];
	$sql_where.=" AND cheques.id_banco='$id_banco'";
}
if(isset($_POST['tesoreria_cheque_reimpresion_pr_n_cuenta']))
{
	$cuenta=$_POST['tesoreria_cheque_reimpresion_pr_n_cuenta'];
	$sql_where.=" AND cheques.cuenta_banco='$cuenta'";
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
if($opcion=='1')
{
		$Sql="
						SELECT 
							count(numero_cheque)
						FROM 
							cheques
						INNER JOIN 
							proveedor 
						ON
							cheques.id_proveedor=proveedor.id_proveedor
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
						$sql_where
 
";
}
else
{
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
						$sql_where
 
";
}
//die($Sql);
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
							cheques.cedula_rif_beneficiario,
							cheques.tipo_cheque									
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
						$sql_where	
							";
/*if($opcion=='1')
{
	
		$Sql = "	SELECT 
							id_cheques,
							banco.id_banco,
							banco.nombre AS banco,
							banco_cuentas.cuenta_banco,
							cheques.numero_cheque AS ncheque,
							cheques.secuencia AS secuencia,
							proveedor.id_proveedor,
							proveedor.codigo_proveedor,
							proveedor.nombre AS proveedor,
							cheques.monto_cheque,
							cheques.ordenes									
						FROM 
							cheques
						INNER JOIN 
							proveedor 
						ON
							cheques.id_proveedor=proveedor.id_proveedor
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
						$sql_where	
							";
}
else
{
	$Sql = "	SELECT 
							id_cheques,
							banco.id_banco,
							banco.nombre AS banco,
							banco_cuentas.cuenta_banco,
							cheques.numero_cheque AS ncheque,
							cheques.secuencia AS secuencia,
							cheques.nombre_beneficiario,
							cheques.cedula_rif_beneficiario	,
							cheques.monto_cheque,
							cheques.ordenes									
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
						$sql_where	
							";
}*/
							
$row=& $conn->Execute($Sql);
//die($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
if (!$row->EOF) 
{
		//-
		$id_proveedor=$row->fields("id_proveedor");
		$tipo=$row->fields("tipo_cheque");
		if(($id_proveedor==NULL)||($id_proveedor==""))
		{
				$beneficiario=$row->fields("nombre_beneficiario");
				//$tipo=$row->fields("tipo_cheque");
				/*$id_proveedor=$row->fields("id_proveedor");
				$sql_prove="select nombre from proveedor where id_proveedor='$id_proveedor'";
				$row_prove=& $conn->Execute($sql_prove);
				$beneficiario=$row_prove->fields("nombre");
				$tipo=$row->fields("tipo_cheque");
				*/
		
		}
		else
			{
				$sql_prove="select nombre from proveedor where id_proveedor='$id_proveedor'";
				$row_prove=& $conn->Execute($sql_prove);
				$beneficiario=$row_prove->fields("nombre");
			//	$tipo=$row->fields("tipo_cheque");
			/*$beneficiario=$row->fields("nombre_beneficiario");
			$tipo=$row->fields("tipo_cheque");*/
		
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
		
		
			$responce->rows[$i]['id']=$row->fields("id_cheques");
			$responce =$row->fields("id_cheques")."*". $beneficiario."*".$row->fields("monto_cheque")."*".$row->fields("ordenes")."*".$row->fields("secuencia")."*".$row->fields("tipo_cheque");
echo ($responce);
}else
{
	$responce="";
	echo ($responce);
}

?>