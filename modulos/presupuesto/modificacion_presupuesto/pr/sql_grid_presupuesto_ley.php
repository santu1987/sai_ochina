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
//
if(isset($_GET["busq_partida"]))
$busq_partida =$_GET["busq_partida"];
$where_emergente = "WHERE 1=1";
if($busq_partida!='')
	{	
			$partida =substr($busq_partida,0,3);
		if ($partida!=FALSE) $where_emergente.= " AND modificacion_ley.partida like '%$partida%'";
		
		$generica =substr($busq_partida,3,2);
		if ($generica!=FALSE) $where_emergente.= " AND modificacion_ley.generica like '%$generica%'";
		
		$especifica=substr($busq_partida,5,2);
		if ($especifica!=FALSE)$where_emergente.= " AND modificacion_ley.especifica like '%$especifica%'";
		
		$sub_especifica =substr($busq_partida,7,2);
		if ($sub_especifica!=FALSE)	$where_emergente.= " AND modificacion_ley.sub_especifica like '%$sub_especifica%'";
	
	}	
	//*/
$Sql="
			SELECT 
				count(id_modificacion_ley)
 	 		FROM 
				modificacion_ley
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora = modificacion_ley.id_unidad_ejecutora	
			INNER JOIN
				accion_especifica
			ON
				accion_especifica.id_accion_especifica = modificacion_ley.id_accion_especifica";

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
				modificacion_ley.id_modificacion_ley,
				modificacion_ley.id_unidad_ejecutora,
				modificacion_ley.id_proyecto,
				modificacion_ley.id_accion_centralizada,
				modificacion_ley.id_accion_especifica,
				modificacion_ley.partida,
				modificacion_ley.generica,
				modificacion_ley.especifica,
				modificacion_ley.sub_especifica,
				modificacion_ley.monto,
				modificacion_ley.monto_total,
				modificacion_ley.comentario,
				modificacion_ley.mes_modificado,
				modificacion_ley.ano,
				unidad_ejecutora.codigo_unidad_ejecutora,
				unidad_ejecutora.nombre,
				accion_especifica.denominacion,
				accion_especifica.codigo_accion_especifica,
				clasificador_presupuestario.denominacion AS clasifica
			FROM 
				modificacion_ley
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora = modificacion_ley.id_unidad_ejecutora	
			INNER JOIN
				accion_especifica
			ON
				accion_especifica.id_accion_especifica = modificacion_ley.id_accion_especifica
			INNER JOIN
				clasificador_presupuestario
			ON
				clasificador_presupuestario.partida = modificacion_ley.partida
				AND
				clasificador_presupuestario.generica = modificacion_ley.generica
				AND
				clasificador_presupuestario.especifica = modificacion_ley.especifica
				AND
				clasificador_presupuestario.subespecifica = modificacion_ley.sub_especifica
                 ".$where_emergente."
			ORDER BY 
				$sidx $sord 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$partida =$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");


$sqlproyecto = "SELECT nombre,codigo_proyecto FROM proyecto WHERE (id_proyecto =".$row->fields("id_proyecto")." ) ";
$row_proyecto=& $conn->Execute($sqlproyecto);
if(!$row_proyecto->EOF){
	$proyecto = $row_proyecto->fields("nombre");
	$codigo_proyecto = $row_proyecto->fields("codigo_proyecto");
}else{
	$proyecto = "";
	$codigo_proyecto="";
}
//***************
$sqlaccion_central = "SELECT denominacion,codigo_accion_central FROM accion_centralizada WHERE (id_accion_central =".$row->fields("id_accion_centralizada")." ) ";
$row_accion_central=& $conn->Execute($sqlaccion_central);
if(!$row_accion_central->EOF){
	$accion_central = $row_accion_central->fields("denominacion");
	$codigo_accion_central = $row_accion_central->fields("codigo_accion_central");
	
}else{
	$accion_central = "";
	$codigo_accion_central = "";
}	
//*****************

$Sqlmes="
			SELECT ".$row->fields("mes_modificado")." AS mes
			FROM 
				presupuesto_ley
			WHERE
				(presupuesto_ley.id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora") .")
				AND
				(presupuesto_ley.id_accion_especifica =".$row->fields("id_accion_especifica").")
				AND
				(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
				AND
				(presupuesto_ley.anio='".$row->fields("ano")."')
				AND
				(presupuesto_ley.partida = '".$row->fields("partida")."')
				AND
				(presupuesto_ley.generica ='".$row->fields("generica")."')
				AND
				(presupuesto_ley.especifica='".$row->fields("especifica")."')
				AND
				(presupuesto_ley.sub_especifica='".$row->fields("sub_especifica")."') 
 
";
$rowmes=& $conn->Execute($Sqlmes);
//
$sql_modifi = "
	SELECT  
		max(fecha_actualizacion) AS hora_modi, monto_total
	FROM 
		modificacion_ley
	WHERE
		(id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").")
	AND
		(id_accion_especifica =".$row->fields("id_accion_especifica").")
	AND
		(id_organismo=$_SESSION[id_organismo])
	AND
		(ano='".$row->fields("ano")."') 
	AND
	(partida = '".$row->fields("partida")."')
	AND
	(generica ='".$row->fields("generica")."')
	AND
	(especifica='".$row->fields("especifica")."')
	AND
	(sub_especifica='".$row->fields("sub_especifica")."') 
	AND
	(mes_modificado='".$row->fields("mes_modificado")."')	
	GROUP BY monto_total
		";
$row_modifi=& $conn->Execute($sql_modifi);
//*********************************************************
$sql_traspaso_cedente = "  
  SELECT   
	SUM(monto_cedente) AS monto_cedente, 
         MAX(fecha_actualizacion) AS hora_cedente
  FROM traspaso_entre_partidas
  WHERE
	(id_unidad_cedente = ".$row->fields("id_unidad_ejecutora").")
  AND
	(id_accion_especifica_cedente = ".$row->fields("id_accion_especifica").")
  AND
	(id_organismo=$_SESSION[id_organismo])
  AND
	(anio = '".$row->fields("ano")."')
  AND
	(partida_cedente = '".$row->fields("partida")."')
  AND
	(generica_cedente = '".$row->fields("generica")."')
  AND
	(especifica_cedente = '".$row->fields("especifica")."')
  AND
	(subespecifica_cedente = '".$row->fields("sub_especifica")."')
  AND
	(mes_cedente = '".$row->fields("mes_modificado")."')";
$row_traspaso_cedente=& $conn->Execute($sql_traspaso_cedente);
if (!$row_traspaso_cedente->EOF) {
	$monto_cedente = $row_traspaso_cedente->fields("monto_cedente");
	$hora_cedente = $row_traspaso_cedente->fields("hora_cedente");
	}
//*********************************************************
$sql_traspaso_receptora = "  
  SELECT   
	SUM(monto_cedente) AS monto_receptor , 
         MAX(fecha_actualizacion) AS hora_receptor
  FROM traspaso_entre_partidas
  WHERE
	(id_unidad_receptora = ".$row->fields("id_unidad_ejecutora").")
  AND
	(id_accion_especifica_receptora = ".$row->fields("id_accion_especifica").")
  AND
	(id_organismo=$_SESSION[id_organismo])
  AND
	(anio = '".$row->fields("ano")."')
  AND
	(partida_receptora = '".$row->fields("partida")."')
  AND
	(generica_receptora = '".$row->fields("generica")."')
  AND
	(especifica_receptora = '".$row->fields("especifica")."')
  AND
	(subespecifica_receptora = '".$row->fields("sub_especifica")."')
  AND
	(mes_receptora = '".$row->fields("mes_modificado")."')";
$row_traspaso_receptora=& $conn->Execute($sql_traspaso_receptora);
if (!$row_traspaso_receptora->EOF) {
	$monto_receptor = $row_traspaso_receptora->fields("monto_receptor");
	$hora_receptor = $row_traspaso_receptora->fields("hora_receptor");
	}
//*************************************

if (!$row_modifi->EOF) 
{
	$monto_modi = $row_modifi->fields("monto_total");
	$hora_modi = $row_modifi->fields("hora_modi");
}
if (!$rowmes->EOF){ 
	$monto_pre = $rowmes->fields("mes");

if ($monto_cedente != "" && $monto_receptor != "")
	$monto_trans = $monto_cedente - ($monto_receptor + $monto_pre);
//****************************************************
if (!$rowmes->EOF ||!$row_traspaso_receptora->EOF  ||!$row_traspaso_cedente->EOF  ) 
	if (($hora_modi > $hora_receptor) && ($hora_modi >$hora_cedente))
		$monto_mes = $row_modifi->fields("monto_total");
	if (($hora_receptor > $hora_modi) && ($hora_receptor >$hora_cedente))
		$monto_mes = $monto_receptor;
	if (($hora_cedente > $hora_receptor) && ($hora_cedente >$hora_modi))
		$monto_mes = $monto_cedente;
}elseif (!$rowmes->EOF){
	$monto_mes = $rowmes->fields("mes");
}
	//$monto_to = $monto_mes + $row->fields("monto");
	$responce->rows[$i]['id']=$row->fields("id_modificacion_ley");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_modificacion_ley"),
															$row->fields("id_proyecto"),
															$row->fields("id_accion_centralizada"),
															$row->fields("id_accion_especifica"),
															number_format($row->fields("monto"),2,',','.'),
															$row->fields("comentario"),
															$row->fields("mes_modificado"),
															$row->fields("ano"),
															$row->fields("id_unidad_ejecutora"),
															$row->fields("codigo_unidad_ejecutora"),
															$row->fields("nombre"),
															$codigo_accion_central,
															substr($accion_central,0,40),
															$accion_central,
															$codigo_proyecto,
															substr($proyecto,0,40),
															$proyecto,
															$row->fields("denominacion"),
															$partida,
															number_format($monto_mes,2,',','.'),
															number_format($row->fields("monto_total"),2,',','.'),
															$row->fields("clasifica"),
															$hora_modi,
															$hora_receptor,
															$hora_cedente,
															$row->fields("codigo_accion_especifica")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>