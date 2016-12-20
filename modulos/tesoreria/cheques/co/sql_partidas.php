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
//die($Sql);
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
								$i_ord=0;$i_ord2=0;

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
     									$opt_partidas.=(($opt_partidas)?",":"").'"'.$i_ord2.'":"'.$row_orden->fields("partida").'"';
										
										
										
										///procedimiento para buscar elementos parecidos dentro del mismo vector
											//$vector_partidas[$i_ord]=$row_orden->fields("partida");
										///
										$i_ord2=$i_ord2+1;
										$row_orden->MoveNext();
	
									}			
									$i_ord=$i_ord+1;
						

								}
	$row->MoveNext();
}
?>
{<?=$opt_partidas?>}