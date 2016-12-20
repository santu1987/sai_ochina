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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_traspaso_entre_partida)
 	 		FROM 
				traspaso_cendente
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

// the actual query for the grid data
$Sql="
			SELECT 
				*
			FROM 
				traspaso_cendente 
   
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$Sql2="
			SELECT 
				*
			FROM 
				traspaso_receptor 
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
$roww=& $conn->Execute($Sql2);

// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$partida = $row->fields("partida_cedente").".".$row->fields("generica_cedente").".".$row->fields("especifica_cedente").".".$row->fields("subespecifica_cedente");

$partidas = $roww->fields("partida_receptora").".".$roww->fields("generica_receptora").".".$roww->fields("especifica_receptora").".".$roww->fields("subespecifica_receptora");

$sqlx = 'SELECT denominacion FROM accion_centralizada WHERE (id_accion_central = '.$row->fields("id_accion_centralizada_cedente").')';
$row_accion=& $conn->Execute($sqlx);
$accion_central = $row_accion->fields("denominacion");

$sqlp = 'SELECT nombre FROM proyecto WHERE (id_proyecto = '.$row->fields("id_proyecto_cedente").')';
$row_proyecto=& $conn->Execute($sqlp);
$proyecto = $row_proyecto->fields("nombre");

$sqlx2 = 'SELECT denominacion FROM accion_centralizada WHERE (id_accion_central = '.$roww->fields("id_accion_centralizada_receptora").')';
$row_accion2=& $conn->Execute($sqlx2);
$accion_central_re = $row_accion2->fields("denominacion");

$sqlp2 = 'SELECT nombre FROM proyecto WHERE (id_proyecto = '.$roww->fields("id_proyecto_receptora").')';
$row_proyecto2=& $conn->Execute($sqlp2);
$proyecto_re = $row_proyecto2->fields("nombre");


$Sql_mes="SELECT 
				".$row->fields("mes_cedente")." AS monto_actual
			FROM 
				clasificador_presupuestario
			INNER JOIN
				presupuesto_ley
			ON
				clasificador_presupuestario.partida = presupuesto_ley.partida
				AND
				clasificador_presupuestario.generica = presupuesto_ley.generica
				AND
				clasificador_presupuestario.especifica = presupuesto_ley.especifica
				AND
				clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
			WHERE
				(presupuesto_ley.id_unidad_ejecutora = ".$row->fields("id_unidad_cedente").")
				AND
				(presupuesto_ley.id_accion_especifica =".$row->fields("id_accion_especifica_cedente").")
				AND
				(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
				AND
				(presupuesto_ley.anio='".date('Y')."') 
				AND
				(presupuesto_ley.partida = '".$row->fields("partida_cedente")."')
				AND
				(presupuesto_ley.generica ='".$row->fields("generica_cedente")."')
				AND
				(presupuesto_ley.especifica='".$row->fields("especifica_cedente")."')
				AND
				(presupuesto_ley.sub_especifica='".$row->fields("subespecifica_cedente")."')
";

$row_mes=& $conn->Execute($Sql_mes);
$Sql_mes_re="SELECT 
				".$roww->fields("mes_receptora")." AS monto_actual_re
			FROM 
				clasificador_presupuestario
			INNER JOIN
				presupuesto_ley
			ON
				clasificador_presupuestario.partida = presupuesto_ley.partida
				AND
				clasificador_presupuestario.generica = presupuesto_ley.generica
				AND
				clasificador_presupuestario.especifica = presupuesto_ley.especifica
				AND
				clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
			WHERE
				(presupuesto_ley.id_unidad_ejecutora = ".$roww->fields("id_unidad_receptora").")
				AND
				(presupuesto_ley.id_accion_especifica =".$roww->fields("id_accion_especifica_receptora").")
				AND
				(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
				AND
				(presupuesto_ley.anio='".date('Y')."') 
				AND
				(presupuesto_ley.partida = '".$roww->fields("partida_receptora")."')
				AND
				(presupuesto_ley.generica ='".$roww->fields("generica_receptora")."')
				AND
				(presupuesto_ley.especifica='".$roww->fields("especifica_receptora")."')
				AND
				(presupuesto_ley.sub_especifica='".$roww->fields("subespecifica_receptora")."')
";

$row_mes_re=& $conn->Execute($Sql_mes_re);
/*echo($Sql_mes_re);
echo("<br>");*/
	$responce->rows[$i]['id']=$row->fields("id_traspaso_entre_partida");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_traspaso_entre_partida"),
															$row->fields("id_unidad_cedente"),
															$row->fields("id_proyecto_cedente"),
															$row->fields("id_accion_centralizada_cedente"),
															$row->fields("id_accion_especifica_cedente"),
															$row->fields("comentario"),
															$row->fields("mes_cedente"),
															$row->fields("denominacion"),
															$row->fields("nombre"),
															$row->fields("accion_especifica"),
															$roww->fields("id_unidad_receptora"),
															$roww->fields("id_proyecto_receptora"),
															$roww->fields("id_accion_centralizada_receptora"),
															$roww->fields("id_accion_especifica_receptora"),
															number_format($roww->fields("monto_receptora"),2,',','.'),
															$roww->fields("mes_receptora"),
															$roww->fields("denominacion"),
															$roww->fields("nombre"),
															$roww->fields("accion_especifica"),	
															$partida,
															$partidas,
															$accion_central,
															$proyecto,
															$accion_central_re,
															$proyecto_re,
															number_format($row_mes->fields("monto_actual"),2,',','.'),
															number_format($row_mes->fields("monto_actual_re"),2,',','.')
														);
	$i++;
	$row->MoveNext();
	$roww->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>