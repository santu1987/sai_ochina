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
$unidad = $_GET['unidad'];
$unidad_es = $_GET['unidad_es'];
//************************************************************************
if ($_GET['patidaa'] != "")
{
	list( $parti, $generica, $especifica, $subespecifica ) = split( '[.]', $_GET['patidaa']);
	
	$where = "AND (\"presupuesto_ejecutadoR\".partida = '".$parti."')";
}

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(DISTINCT id_clasi_presu)
 	 		FROM 
				clasificador_presupuestario
			INNER JOIN
				\"presupuesto_ejecutadoR\"
			ON
				clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida
				AND
				clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica
				AND
				clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica
				AND
				clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica 
			WHERE
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad.")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es.")
				AND
					(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
				$where
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
$mes= date('n');
$i = 0;
$desde =1;
$monto_precomprometido = 0;
/*while($desde<=$mes){
	if ($i == 0){*/
		$monoto = "monto_presupuesto [".$mes."]";
		$traspasado = "monto_traspasado [".$mes."]";
		$modificado = "monto_modificado [".$mes."]";
		$monto_precomprometido = "monto_precomprometido [".$mes."]";
	/*}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$monto_precomprometido = $monto_precomprometido." + monto_precomprometido [".$desde."]";
	}
$i++;
$desde++;
}*/
// the actual query for the grid data
$Sql="
			SELECT 
				DISTINCT clasificador_presupuestario.id_clasi_presu, clasificador_presupuestario.denominacion,  \"presupuesto_ejecutadoR\".partida, 
				\"presupuesto_ejecutadoR\".generica, \"presupuesto_ejecutadoR\".especifica, \"presupuesto_ejecutadoR\".sub_especifica ,
				($monoto)AS monto_presupuesto, 
				($traspasado)AS monto_traspasado,
				($modificado)AS monto_modificado
			FROM 
				clasificador_presupuestario
			INNER JOIN
				\"presupuesto_ejecutadoR\"
			ON
				clasificador_presupuestario.partida = \"presupuesto_ejecutadoR\".partida
				AND
				clasificador_presupuestario.generica = \"presupuesto_ejecutadoR\".generica
				AND
				clasificador_presupuestario.especifica = \"presupuesto_ejecutadoR\".especifica
				AND
				clasificador_presupuestario.subespecifica = \"presupuesto_ejecutadoR\".sub_especifica 
			WHERE
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad.")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es.")
				AND
					(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
					$where
			ORDER BY 
				 \"presupuesto_ejecutadoR\".partida, 
				\"presupuesto_ejecutadoR\".generica, \"presupuesto_ejecutadoR\".especifica, \"presupuesto_ejecutadoR\".sub_especifica 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$sqlyy="
SELECT 
	SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS precomprometido
FROM
	\"orden_compra_servicioD\"
INNER JOIN
	\"orden_compra_servicioE\"
ON
	\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
WHERE
	id_unidad_ejecutora = ".$unidad."
AND
	id_accion_especifica = ".$unidad_es."
AND
	partida = '".$row->fields("partida")."'
AND
	generica = '".$row->fields("generica")."'
AND
	especifica = '".$row->fields("especifica")."'
AND
	subespecifica = '".$row->fields("sub_especifica")."'
AND 
	disponible = 1
AND
	fecha_elabora BETWEEN '".date('Y')."-".date('n')."-01' AND '".date('Y-n-d')."'
";
//echo $sqlyy;
$rowyy=& $conn->Execute($sqlyy);
if (!$rowyy->EOF)
{
	$precomprometido = $rowyy->fields("precomprometido");
}else{
	$precomprometido = 0;
}
$signos = -1;
$partida = $row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
/*if($row->fields("monto_precomprometido") <0)
	$monto_pre= $row->fields("monto_precomprometido") * $signos ;
else
	$monto_pre= $row->fields("monto_precomprometido") ;*/
$monto = ($row->fields("monto_presupuesto")+$row->fields("monto_traspasado")+$row->fields("monto_modificado"))-$precomprometido;

	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_clasi_presu"),
															$partida,
															utf8_encode($row->fields("denominacion")),
															number_format($monto,2,',','.')
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>