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
$user=$_SESSION['id_usuario'];

/* if(($contabilidad_comp_pr_numero_comprobante=="")&&($tipo_comprobante==""))
{
	die("error");
}*/
if(isset($_GET['numero_comprobante']))
{
	$numero_comprobante=$_GET['numero_comprobante'];
	//$tipo_comprobante=$_GET['tipo_comprobante'];
	$where="and movimientos_contables.numero_comprobante='$numero_comprobante'";

 /*	and
	movimientos_contables.id_tipo_comprobante='$tipo_comprobante*/
	if($user!='1')
	{
		$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."
		'";
	}	
}
else
$where.=" and 1=1";
$where.="and movimientos_contables.estatus!='3'";
//$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
/*** '".$_SESSION['id_usuario']."'";**/
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$Sql2="
					SELECT 
						count(movimientos_contables.id_movimientos_contables) 
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
				
					$row2=& $conn->Execute($Sql2);
		//die($Sql2);			
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
					movimientos_contables.id_movimientos_contables,
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
					movimientos_contables.id_auxiliar,
					movimientos_contables.id_unidad_ejecutora,
					movimientos_contables.id_proyecto,
					movimientos_contables.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					tipo_comprobante.codigo_tipo_comprobante,
					movimientos_contables.estatus,
					cuenta_contable_contabilidad.requiere_auxiliar,
					cuenta_contable_contabilidad.requiere_unidad_ejecutora,
					cuenta_contable_contabilidad.requiere_proyecto,
					cuenta_contable_contabilidad.requiere_utilizacion_fondos			
					
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
				inner join
					tipo_comprobante
				on
					tipo_comprobante.id=movimientos_contables.id_tipo_comprobante			
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				ORDER BY 
					 movimientos_contables.id_movimientos_contables,
					 movimientos_contables.debito_credito
				
					 
				LIMIT 
					$limit 
				OFFSET 
					$start ";
	
	$row_int=& $conn->Execute($Sql_int);
	//die($Sql_int);
	// constructing a JSON					 movimientos_contables.secuencia,

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while (!$row_int->EOF) 
	{
		$responce->rows[$i]['id']=$row_int->fields("id_movimientos_contables");
		$responce->rows[$i]['cell']=array(	
																$row_int->fields("id_movimientos_contables"),
																$row_int->fields("id_organismo"),
																$row_int->fields("ano_comprobante"),
																$row_int->fields("mes_comprobante"),
																$row_int->fields("id_tipo_comprobante"),
																substr($row_int->fields("numero_comprobante"),2,4),
																$row_int->fields("secuencia"),
																$row_int->fields("cuenta_contable"),
																$row_int->fields("descripcion_cuenta"),
																$row_int->fields("referencia"),
																number_format($row_int->fields("monto_debito"),2,',','.'),
																number_format($row_int->fields("monto_credito"),2,',','.'),
																$row_int->fields("fecha_comprobante"),
																$row_int->fields("id_auxiliar"),
																$row_int->fields("id_unidad_ejecutora"),
																$row_int->fields("id_proyecto"),
																$row_int->fields("id_utilizacion_fondos"),
																$row_int->fields("id_cc"),
																$row_int->fields("codigo_tipo_comprobante"),
																$row_int->fields("estatus"),
																$row_int->fields("requiere_auxiliar"),
																$row_int->fields("requiere_unidad_ejecutora"),
																$row_int->fields("requiere_proyecto"),
																$row_int->fields("requiere_utilizacion_fondos"),
																															$row_int->fields("descripcion"),

														);	
	
					$i++;
		$row_int->MoveNext();
	}


	// return the formated data
echo $json->encode($responce);

?>