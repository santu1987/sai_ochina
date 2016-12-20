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
$where="AND 1=1";
if(isset($_GET['banco']))
{
	$id_banco=$_GET['banco'];
	$where.="AND	cheques.id_banco='$id_banco'";
}
if(isset($_GET['cuenta']))
{
	$cuenta=$_GET['cuenta'];
	$where.="AND	cheques.cuenta_banco='$cuenta'";
}	
if($_GET['proveedor']!='')
{
	$poveedor=$_GET['proveedor'];
	$where.="AND cheques.id_proveedor='$poveedor'";
}	
if(isset($_GET['empleado']))
{
	$empleado=$_GET['empleado'];
	$where.="AND cheques.cedula_rif_beneficiario='$empleado'";
}	
if(isset($_GET['op']))
{
	$op=$_GET['op'];
}	


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
					(cheques.id_organismo='$_SESSION[id_organismo]' )
				AND
					 (numero_cheque>0)
			    AND
					cheques.estatus!='5'
				$where	
";
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
					cheques.estatus,
					cheques.estado,
					cheques.estado_fecha,
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
					 (numero_cheque>0)
				AND
					cheques.estatus!='5'
				$where						 
				order by
					ncheque
					";
$row=& $conn->Execute($Sql);
//

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//-rutinas para identificar los valores de origen del cheque com son:compromisos,partidas,facturas y ordenes
								$ordenes=$row->fields("ordenes");
								$ord1=str_replace("{","",$ordenes);
								$ord2=str_replace("}","",$ord1);
								$ordenes_pago= split(",",$ord2);
								$contador_ordenes=count($ordenes_pago);
								$i_ord=0;
								while($i_ord < $contador_ordenes)
								{
									$orden_individual=$ordenes_pago[$i_ord];
									$sql_doc_det="SELECT 
													   documentos_cxp.numero_documento,
													   documentos_cxp.numero_compromiso,
													   doc_cxp_detalle.partida
												  FROM 
													documentos_cxp
												  INNER JOIN
													doc_cxp_detalle
												  ON
													doc_cxp_detalle.id_doc=documentos_cxp.id_documentos
												WHERE
													orden_pago='$orden_individual'
												
												ORDER BY
													numero_documento	
													";
									$row_orden=& $conn->Execute($sql_doc_det);	
									while(!$row_orden->EOF)
									{
									//die($sql_doc_det);	
										$partidas_orden=$partidas_orden.";".$row_orden->fields("partida");
										$compromiso_orden=$compromiso_orden.";".$row_orden->fields("numero_compromiso");
										$facturas=$facturas.";".$row_orden->fields("numero_documento");
										
										$row_orden->MoveNext();
	
									}			
									$i_ord=$i_ord+1;
						

								}
//////////////////////////
//-
if($row->fields("id_proveedor")==true)
{
		$id_proveedor=$row->fields("id_proveedor");
		$sql_prove="select nombre,codigo_proveedor from proveedor where id_proveedor='$id_proveedor'";
		$row_prove=& $conn->Execute($sql_prove);
		$beneficiario=$row_prove->fields("nombre");
		$codigo=$row_prove->fields("codigo_proveedor");
		
		$tipo='1';
		

}
else
{

	$beneficiario=$row->fields("nombre_beneficiario");
	$codigo=$row->fields("cedula_rif_beneficiario");
	$id_proveedor=0;
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
	$responce->rows[$i]['id']=$row->fields("id_cheques");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_cheques"),
															$row->fields("id_banco"),
															$row->fields("banco"),
															$row->fields("cuenta_banco"),
															$row->fields("secuencia"),
															$n_cheque,
															$beneficiario,
															number_format($row->fields("monto_cheque"),2,',','.'),
															$row->fields("ordenes"),
															$tipo,
															$row->fields("estatus"),
															$row->fields("estado"),
															$codigo,
															$id_proveedor,
															$row->fields("estado_fecha"),
															$row->fields("benef_nom"),
															$partidas_orden,
															$compromiso_orden,
															$facturas
						);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>