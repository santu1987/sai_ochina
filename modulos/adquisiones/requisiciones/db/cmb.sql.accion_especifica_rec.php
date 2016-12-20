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
$anio = $_GET['anio'];
$proyecto = $_GET['proyecto'];
$accion_central = $_GET['accion_central'];
//die ($accion_central);
if($proyecto != ""){
	$sqll = "
			INNER JOIN 
				proyecto 
			ON 
				accion_especifica.id_proyecto = proyecto.id_proyecto
			WHERE
				(proyecto.id_proyecto =$proyecto)
			";
		$sqlk = " (\"presupuesto_ejecutadoR\".id_proyecto=proyecto.id_proyecto) AND";
}elseif($accion_central != ""){
	$sqll = "
			INNER JOIN 
				accion_centralizada 
			ON 
				accion_especifica.id_accion_central = accion_centralizada.id_accion_central
			WHERE
				(accion_centralizada.id_accion_central =$accion_central)
			";
			$sqlk = " (\"presupuesto_ejecutadoR\".id_accion_centralizada = accion_centralizada.id_accion_central) AND";
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(distinct (accion_especifica.id_accion_especifica))
			FROM 
				accion_especifica 
			INNER JOIN 
				organismo 
			ON 
				accion_especifica.id_organismo = organismo.id_organismo
			INNER JOIN 
				\"presupuesto_ejecutadoR\" 
			ON 
				\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
			$sqll
			AND	
				(accion_especifica.id_organismo =".$_SESSION['id_organismo'].")
			AND					
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad)
				AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
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

$Sql = "	
			SELECT 
				distinct (accion_especifica.id_accion_especifica),
				accion_especifica.denominacion, 
				accion_especifica.codigo_accion_especifica,
		(select 
			sum(monto_presupuesto[7]+monto_presupuesto[8]+monto_presupuesto[9]+monto_presupuesto[10]+monto_presupuesto[11]+monto_presupuesto[12])AS sal
		from 
			\"presupuesto_ejecutadoR\" 
		WHERE 
		(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
		$sqlk 
		(\"presupuesto_ejecutadoR\".ano='$anio') AND
		\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
		)AS saldo,
		(select 
			sum(monto_traspasado[7]+monto_traspasado[8]+monto_traspasado[9]+monto_traspasado[10]+monto_traspasado[11]+monto_traspasado[12])AS sal
		from 
			\"presupuesto_ejecutadoR\" 
		WHERE 
		(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
		$sqlk 
		(\"presupuesto_ejecutadoR\".ano='$anio') AND
		\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
		)AS saldo_tras,
		(select 
			sum(monto_modificado[7]+monto_modificado[8]+monto_modificado[9]+monto_modificado[10]+monto_modificado[11]+monto_modificado[12])AS sal
		from 
			\"presupuesto_ejecutadoR\" 
		WHERE 
		(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
		$sqlk 
		(\"presupuesto_ejecutadoR\".ano='$anio') AND
		\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
		)AS saldo_modi,
		(select 
			sum(monto_comprometido[7]+monto_comprometido[8]+monto_comprometido[9]+monto_comprometido[10]+monto_comprometido[11]+monto_comprometido[12])AS sal
		from 
			\"presupuesto_ejecutadoR\" 
		WHERE 
		(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
		$sqlk 
		(\"presupuesto_ejecutadoR\".ano='$anio') AND
		\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
		)AS saldo_compro
			FROM 
				accion_especifica 
			INNER JOIN 
				organismo 
			ON 
				accion_especifica.id_organismo = organismo.id_organismo
			INNER JOIN 
				\"presupuesto_ejecutadoR\" 
			ON 
				\"presupuesto_ejecutadoR\".id_accion_especifica = accion_especifica.id_accion_especifica
			".$sqll."
			AND
				(accion_especifica.id_organismo =".$_SESSION['id_organismo'].")
			AND					
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad)
				AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
			ORDER BY 
				accion_especifica.codigo_accion_especifica
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$saldo_actual = $row->fields("saldo")+$row->fields("saldo_tras")+$row->fields("saldo_modi")-$row->fields("saldo_compro");
	$responce->rows[$i]['id']=$row->fields("id_accion_especifica");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_accion_especifica"),
															$row->fields("codigo_accion_especifica"),
															$row->fields("denominacion"),
															number_format($saldo_actual,2,',','.')
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>