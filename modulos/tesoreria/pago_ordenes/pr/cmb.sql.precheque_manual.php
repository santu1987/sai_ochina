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
$opcion=$_GET['opcion'];
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
if($opcion=='1')
{
			$id_proveedor=$_GET['proveedor'];
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
							WHERE 
								(cheques.id_organismo=$_SESSION[id_organismo] )
							AND
								 (numero_cheque<0)
							AND
								cheques.id_proveedor='$id_proveedor'
							AND
								cheques.tipo_cheque='2'
							AND
								cheques.ordenes='{0}'	
								";			 
				 
}
	else
		if($opcion=='2')
		{
					$beneficiario=$_GET['beneficiario'];
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
										cheques.cedula_rif_beneficiario='$beneficiario'
									AND
										cheques.tipo_cheque='2'
									AND
										cheques.ordenes='{0}'	
										";			 
						 
		}
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
if($opcion=='1')
{
	$Sql = "	SELECT 
						id_cheques,
						banco.id_banco,
						banco.nombre AS banco,
						banco_cuentas.cuenta_banco,
						cheques.numero_cheque AS n_precheque,
						proveedor.id_proveedor,
						proveedor.codigo_proveedor,
						proveedor.nombre AS proveedor,
						cheques.concepto,
						cheques.monto_cheque,
						porcentaje_itf,
						cheques.ordenes,
						cheques.cedula_rif_beneficiario,
						cheques.nombre_beneficiario,
						cheques.base_imponible,
						cheques.porcentaje_islr
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
					WHERE 
						(cheques.id_organismo=$_SESSION[id_organismo] )
					AND
						 (numero_cheque<0) 
					AND
						cheques.id_proveedor='$id_proveedor'
					AND
						cheques.tipo_cheque='2'			 
					AND
										cheques.ordenes='{0}'	
										";	
}
	else
		if($opcion=='2')
		{			
					
					$Sql="
									SELECT 
										id_cheques,
										banco.id_banco,
										banco.nombre AS banco,
										banco_cuentas.cuenta_banco,
										cheques.numero_cheque AS n_precheque,
										cheques.concepto,
										cheques.monto_cheque,
										porcentaje_itf,
										cheques.ordenes,
										cheques.cedula_rif_beneficiario,
										cheques.nombre_beneficiario,	
										cheques.base_imponible,
										cheques.porcentaje_islr
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
										cheques.cedula_rif_beneficiario='$beneficiario'
									AND
										cheques.tipo_cheque='2'
									AND
										cheques.ordenes='{0}'	
										";					 
						 
		}

$row=& $conn->Execute($Sql);

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{

	//-------------------
			if($opcion=='1')
		{		
					$id_proveedor=$row->fields("id_proveedor");
					$codigo_proveedor=$row->fields("codigo_proveedor");
					$proveedor=$row->fields("proveedor");
		}	
		
			if($opcion=='2')
		{		
					$id_proveedor="";
					$codigo_proveedor=$row->fields("cedula_rif_beneficiario");
					$proveedor=$row->fields("nombre_beneficiario");
		}			
	//-------------------
	$responce->rows[$i]['id']=$row->fields("id_cheques");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_cheques"),
															$row->fields("n_precheque"),
															$row->fields("id_banco"),
															$row->fields("banco"),
															$row->fields("cuenta_banco"),
															$id_proveedor,
															$codigo_proveedor,
															$proveedor,
															number_format($row->fields("monto_cheque"),2,',','.'),
															$row->fields("concepto"),
															$row->fields("porcentaje_itf"),
															$opcion,
															number_format($row->fields("base_imponible"),2,',','.'),
															number_format($row->fields("porcentaje_islr"),2,',','.'),

															);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>