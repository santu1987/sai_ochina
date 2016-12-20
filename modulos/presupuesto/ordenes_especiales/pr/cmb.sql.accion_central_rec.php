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
$anio = 2011;
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="			SELECT 
				count(DISTINCT accion_centralizada.id_accion_central)
			FROM 
					accion_centralizada 
					INNER JOIN 
		presupuesto_ley 
	ON
		accion_centralizada.id_accion_central=presupuesto_ley.id_accion_central
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				AND					
					(presupuesto_ley.id_unidad_ejecutora=$unidad)
				AND
					(presupuesto_ley.anio='$anio')
";
			/*SELECT 
				count(DISTINCT accion_centralizada.id_accion_central)
			FROM 
					organismo 
				INNER JOIN 
					accion_centralizada 
				ON
					accion_centralizada.id_organismo=organismo.id_organismo 
				INNER JOIN 
					presupuesto_ley 
				ON
					accion_centralizada.id_accion_central=presupuesto_ley.id_accion_central 
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				AND
					(presupuesto_ley.id_unidad_ejecutora=$_SESSION[id_unidad_ejecutora])
*/

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

$Sql = "		SELECT 
					DISTINCT accion_centralizada.id_accion_central,
					accion_centralizada.denominacion  ,
					accion_centralizada.codigo_accion_central ,
					(select 
						sum(monto_presupuesto[7]+monto_presupuesto[8]+monto_presupuesto[9]+monto_presupuesto[10]+monto_presupuesto[11]+monto_presupuesto[12])AS sal
					from 
						\"presupuesto_ejecutadoR\" 
					WHERE 
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
					(\"presupuesto_ejecutadoR\".id_accion_centralizada=accion_centralizada.id_accion_central) AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
					)AS saldo,
					(select 
						sum(monto_traspasado[7]+monto_traspasado[8]+monto_traspasado[9]+monto_traspasado[10]+monto_traspasado[11]+monto_traspasado[12])AS sal
					from 
						\"presupuesto_ejecutadoR\" 
					WHERE 
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
					(\"presupuesto_ejecutadoR\".id_accion_centralizada=accion_centralizada.id_accion_central) AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
					)AS saldo_tras,
					(select 
						sum(monto_modificado[7]+monto_modificado[8]+monto_modificado[9]+monto_modificado[10]+monto_modificado[11]+monto_modificado[12])AS sal
					from 
						\"presupuesto_ejecutadoR\" 
					WHERE 
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
					(\"presupuesto_ejecutadoR\".id_accion_centralizada=accion_centralizada.id_accion_central) AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
					)AS saldo_modi,
					(select 
						sum(monto_comprometido[7]+monto_comprometido[8]+monto_comprometido[9]+monto_comprometido[10]+monto_comprometido[11]+monto_comprometido[12])AS sal
					from 
						\"presupuesto_ejecutadoR\" 
					WHERE 
					(\"presupuesto_ejecutadoR\".id_unidad_ejecutora=$unidad) AND
					(\"presupuesto_ejecutadoR\".id_accion_centralizada=accion_centralizada.id_accion_central) AND
					(\"presupuesto_ejecutadoR\".ano='$anio')
					)AS saldo_compro 
				FROM 	organismo 
	INNER JOIN 
					accion_centralizada 
				ON
		accion_centralizada.id_organismo=organismo.id_organismo  
	INNER JOIN 
		presupuesto_ley 
	ON
		accion_centralizada.id_accion_central=presupuesto_ley.id_accion_central
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				AND					
				(presupuesto_ley.id_unidad_ejecutora=$unidad)
			 AND
				(presupuesto_ley.anio='$anio')
				ORDER BY 
					accion_centralizada.codigo_accion_central 
					";

/*				SELECT 
					DISTINCT accion_centralizada.id_accion_central,
					accion_centralizada.denominacion,
					accion_centralizada.codigo_accion_central  
				FROM 
					organismo 
				INNER JOIN 
					accion_centralizada 
				ON
					accion_centralizada.id_organismo=organismo.id_organismo 
				INNER JOIN 
					presupuesto_ley 
				ON
					accion_centralizada.id_accion_central=presupuesto_ley.id_accion_central 
				WHERE 
					(accion_centralizada.id_organismo=$_SESSION[id_organismo] )
				AND
					(presupuesto_ley.id_unidad_ejecutora=$_SESSION[id_unidad_ejecutora])
				ORDER BY 
					accion_centralizada.denominacion */
	//echo $Sql;		
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
$saldo_actual = $row->fields("saldo")+$row->fields("saldo_tras")+$row->fields("saldo_modi")-$row->fields("saldo_compro");
	$responce->rows[$i]['id']=$row->fields("id_accion_central");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_accion_central"),
															$row->fields("codigo_accion_central"),
															$row->fields("denominacion"),
															number_format($saldo_actual,2,',','.')
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>