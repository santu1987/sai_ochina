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
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$where="and movimientos_contables.id='$id'"; 
}
else
$where=" and 1=1";
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$Sql2="
					SELECT 
						count(movimientos_contables.id) 
					FROM 
						movimientos_contables 
					inner join
						organismo
					on
						movimientos_contables.id_organismo=organismo.id_organismo
								
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
							$where
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
					movimientos_contables.id,
					movimientos_contables.id_organismo,
					movimientos_contables.ano_comprobante,
					movimientos_contables.mes_comprobante,
					movimientos_contables.id_tipo_comprobante,
					movimientos_contables.numero_comprobante,
					movimientos_contables.secuencia,
					movimientos_contables.cuenta_contable,
					movimientos_contables.descripcion,
					movimientos_contables.referencia,
					movimientos_contables.debito_credito,
					movimientos_contables.monto_debito,
					movimientos_contables.monto_credito,
					movimientos_contables.fecha_comprobante,
					movimientos_contables.codigo_auxiliar,
					movimientos_contables.codigo_unidad_ejecutora,
					movimientos_contables.codigo_proyecto,
					movimientos_contables.codigo_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id as id_cc				
					
				FROM 
					movimientos_contables
				inner join
						organismo
						on
						movimientos_contables.id_organismo=organismo.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
							
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				ORDER BY 
					 movimientos_contables.id
				LIMIT 
					$limit 
				OFFSET 
					$start ";
		//	die(Sql_int);
	$row_int=& $conn->Execute($Sql_int);
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if (!$row_int->EOF) 
	{
		
	$sql="SELECT numero_comprobante FROM numeracion_comprobante ";
	$rs_comprobante =& $conn->Execute($sql);
	$comprobante=$rs_comprobante->fields("numero_comprobante")+1;

		
		$responce->rows[$i]['id']=$row_int->fields("id");
		$responce =$row_int->fields("id")."*".$row_int->fields("id_organismo")."*".$row_int->fields("ano_comprobante")."*".$row_int->fields("mes_comprobante")."*".$row_int->fields("id_tipo_comprobante")."*".$comprobante."*".$row_int->fields("secuencia")."*".$row_int->fields("comentarios")."*".$row_int->fields("cuenta_contable")."*".$row_int->fields("descripcion")."*".$row_int->fields("referencia")."*".number_format($row_int->fields("monto_debito"),2,',','.')."*".number_format($row_int->fields("monto_credito"),2,',','.')."*".$row_int->fields("codigo_auxiliar")."*".$row_int->fields("codigo_unidad_ejecutora")."*".$row_int->fields("codigo_proyecto")."*".$row_int->fields("codigo_utilizacion_fondos")."*".$row_int->fields("id_cc");
		echo ($responce);
														
	}


	// return the formated data
//echo $json->encode($responce);

?>