<?php
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
if(isset($_GET['presupuesto_ley_busqueda_codigo']))
$busq_codigo =$_GET['presupuesto_ley_busqueda_codigo'];
if(isset($_GET['presupuesto_ley_busqueda_nombre']))
$busq_nombre =strtolower($_GET['presupuesto_ley_busqueda_nombre']);
if(isset($_GET['presupuesto_ley_busqueda_partida']))
$busq_partida =strtolower($_GET['presupuesto_ley_busqueda_partida']);

//************************************************************************

if(!$sidx) $sidx =1;
$sql_where = "WHERE 1=1";
if($busq_nombre!='')
	$sql_where.= " AND  ((lower(unidad_ejecutora.nombre) like '%$busq_nombre%') OR (lower(unidad_ejecutora.nombre) like '$busq_nombre%'))";
if($busq_codigo!='')
	$sql_where.= " AND presupuesto_ley.anio like '$busq_codigo%'";
if($busq_partida!='')
	{	
		/*$partida =substr($busq_partida,0,-9);
		$generica =substr($busq_partida,4,-6);
		$especifica=substr($busq_partida,7,-3);
		$sub_especifica =substr($busq_partida,10,11);
					
		if ($partida!=FALSE) and ($generica!=FALSE) and ($especifica!=FALSE) and  ($sub_especifica!=FALSE)
		$sql_where.= " AND partida like '%$partida%' AND generica like '%$generica%' AND especifica like '%$especifica%'  AND sub_especifica like '%$sub_especifica%'";
	}
		/**/
		$partida =substr($busq_partida,0,3);
		if ($partida!=FALSE) $sql_where.= " AND partida like '$partida'";
		
		$generica =substr($busq_partida,4,2);
		if ($generica!=FALSE) $sql_where.= " AND generica like '$generica'";
		
		$especifica=substr($busq_partida,7,2);
		if ($especifica!=FALSE)$sql_where.= " AND especifica like '$especifica'";
		
		$sub_especifica =substr($busq_partida,10,2);
		if ($sub_especifica!=FALSE)	$sql_where.= " AND sub_especifica like '$sub_especifica'";
	}
$Sql="
			SELECT 
				 count(presupuesto_ley.id_presupuesto_ley) 
			FROM 
				presupuesto_ley
			INNER JOIN
				organismo
			ON
				presupuesto_ley.id_organismo = organismo.id_organismo
			INNER JOIN
				accion_especifica
			ON
				presupuesto_ley.id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
				unidad_ejecutora
			ON
				presupuesto_ley.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
			$sql_where	
";

$row=& $conn->Execute($Sql);
/*,presupuesto_ley.partida,presupuesto_ley.generica,
				 presupuesto_ley.especifica,presupuesto_ley.sub_especifica*/

if (!$row->EOF)
{
	$count = $row->fields("count");
	
}
$limit = 15;
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
			SELECT 
				presupuesto_ley.*,
				unidad_ejecutora.nombre AS unidad, 
				accion_especifica.denominacion				
			FROM 
				presupuesto_ley
			INNER JOIN
				organismo
			ON
				presupuesto_ley.id_organismo = organismo.id_organismo
			INNER JOIN
				accion_especifica
			ON
				presupuesto_ley.id_accion_especifica = accion_especifica.id_accion_especifica
			INNER JOIN
				unidad_ejecutora
			ON
				presupuesto_ley.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
			".$sql_where."
			ORDER BY 
					presupuesto_ley.anio,unidad_ejecutora.nombre,$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
$monto = $row->fields("enero")+$row->fields("febrero")+$row->fields("marzo")+$row->fields("abril")+$row->fields("mayo")+$row->fields("junio")+$row->fields("julio")+$row->fields("agosto")+$row->fields("septiembre")+$row->fields("octubre")+$row->fields("noviembre")+$row->fields("diciembre");

	$responce->rows[$i]['id']=$row->fields("id_presupuesto_ley");

	$responce->rows[$i]['cell']=array(	
															$partida,
															$row->fields("anio"),
															$row->fields("unidad"),
															$row->fields("denominacion"),
															$monto 
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>