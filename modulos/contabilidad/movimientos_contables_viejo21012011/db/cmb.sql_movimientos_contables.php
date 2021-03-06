<?php session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//HACIENDO LLAMADO A CONTROLES
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
		$Sql2="
					SELECT 
						count(integracion_contable.id) 
					FROM 
						integracion_contable 
					inner join
						organismo
					on
						integracion_contable.id_organismo=integracion_contable.id_organismo
								
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
					";	 
					//die($Sql2);
					$row2=& $conn->Execute($Sql2);
					
	if (!$row2->EOF)
	{
		$count = $row2->fields("count");
	}
	// calculation of total pages for the query
	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	} 
	else
	{
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
	$Sql_int="
				SELECT 
					integracion_contable.id,
					integracion_contable.id_organismo,
					integracion_contable.ano_comprobante,
					integracion_contable.mes_comprobante,
					integracion_contable.id_tipo_comprobante,
					integracion_contable.numero_comprobante,
					integracion_contable.secuencia,
					integracion_contable.cuenta_contable,
					integracion_contable.descripcion,
					integracion_contable.referencia,
					integracion_contable.debito_credito,
					integracion_contable.monto_debito,
					integracion_contable.monto_credito,
					integracion_contable.fecha_comprobante,
					integracion_contable.codigo_auxiliar,
					integracion_contable.codigo_unidad_ejecutora,
					integracion_contable.codigo_proyecto,
					integracion_contable.codigo_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta				
					
				FROM 
					integracion_contable
				inner join
						organismo
						on
						integracion_contable.id_organismo=integracion_contable.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
							
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				ORDER BY 
					 integracion_contable.secuencia
				LIMIT 
					$limit 
				OFFSET 
					$start ";
			
	$row_int=& $conn->Execute($Sql_int);
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while (!$row_int->EOF) 
	{
		$responce->rows[$i]['id']=$row_int->fields("id");
		$responce->rows[$i]['cell']=array(	
																$row_int->fields("id"),
																$row_int->fields("codigo_organismo"),
																$row_int->fields("ano_comprobante"),
																$row_int->fields("mes_comprobante"),
																$row_int->fields("codigo_tipo_comprobante"),
																$row_int->fields("numero_comprobante"),
																$row_int->fields("secuencia"),
																$row_int->fields("comentarios"),
																$row_int->fields("cuenta_contable"),
																$row_int->fields("descripcion_cuenta"),
																$row_int->fields("referencia"),
																number_format($row_int->fields("monto_debito"),2,',','.'),
																number_format($row_int->fields("monto_credito"),2,',','.'),
																$row_int->fields("fecha_comprobante"),
																$row_int->fields("codigo_auxiliar"),
																$row_int->fields("codigo_unidad_ejecutora"),
																$row_int->fields("codigo_proyecto"),
																$row_int->fields("codigo_utilizacion_fondos")
															);	
	
					$i++;
		$row_int->MoveNext();
	}


	// return the formated data
echo $json->encode($responce);

?>