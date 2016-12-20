<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$id = $_GET['id'];
$requi = $_GET['requi'];
$partida = $_GET['partida'];
list( $partida, $generica, $especifica, $subespecifica ) = split( '[.]', $partida);

$requi = $_GET['requi'];
$sqlBus = "
SELECT 
		\"orden_compra_servicioD\".numero_pre_orden,
		\"orden_compra_servicioD\".descripcion,
		\"orden_compra_servicioD\".monto,
		\"orden_compra_servicioD\".cantidad,
		partida,
		generica,
		especifica,
		subespecifica
	FROM 
		\"orden_compra_servicioD\" 
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
WHERE 
	(id_orden_compra_serviciod = ".$id.")
AND
	\"orden_compra_servicioD\".numero_pre_orden = '$requi'
 	
	";
$row=& $conn->Execute($sqlBus);
;
$montodds =$row->fields("monto");
$cantidad =$row->fields("cantidad");
$partida =$row->fields("partida");
 $generica =$row->fields("generica"); 
$especifica =$row->fields("especifica"); 
$subespecifica =$row->fields("subespecifica");

$montodds = $montodds * $cantidad;
//$montodds = str_replace(",",".",$montos);
/*
die('aqui'.$montodds);
*/
			///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
		$unidad = $_GET['unidad'];
		if($_GET['accionc'] != '')
			$accionc = $_GET['accionc'];
		else
			$accionc = 0;
			
		if($_GET['proyecto'] != '')
			$proyecto = $_GET['proyecto'];
		else
			$proyecto = 0;
		$accione = $_GET['accione'];
			
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////
$bus_ll = "SELECT monto_precomprometido[".date("n")."] as monto FROM \"presupuesto_ejecutadoR\"
WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accionc.") AND
		(id_unidad_ejecutora = ".$unidad.") AND (id_accion_especifica = ".$accione.") AND
		(id_proyecto = ".$proyecto.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."'
		";
		
		$row_lla=& $conn->Execute($bus_ll);
//die($bus_ll);
if (!$row_lla->EOF)
{
	$montoxx = $row_lla->fields("monto");
	$montoxx = $montoxx - $montodds;
}else{
				die($conn->ErrorMsg());
			}
//die($bus_ll);
///////////////////////////////////////////////////////////////////////////// AQUI ////////////////////////////////			
			$actu="UPDATE 
			
		\"presupuesto_ejecutadoR\"
	SET 
		monto_precomprometido[".date("n")."]= '".$montoxx."'
	WHERE
		(id_organismo = ".$_SESSION['id_organismo'].") AND (id_accion_centralizada = ".$accionc.") AND
		(id_unidad_ejecutora = ".$unidad.") AND (id_accion_especifica = ".$accione.") AND
		(id_proyecto = ".$proyecto.") AND (ano = '".date("Y")."') AND
		partida = '".$partida."'  AND	generica = '".$generica."'  
		AND	especifica = '".$especifica."'  AND	sub_especifica = '".$subespecifica."'
	
		";
		//die($actu);
		if (!$conn->Execute($actu)){
				echo ('Error al Actulizar: '.$conn->ErrorMsg());
			}
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////				
///////////////////////////////////////////////////////////////////////////// FIN ////////////////////////////////	
/*	
*/

		$sql="
		DELETE 
		FROM 
			\"orden_compra_servicioD\"
		WHERE 
			id_orden_compra_serviciod = $id 
		AND
			numero_pre_orden = '$requi'";
		
			if (!$conn->Execute($sql)) {
				die ('Error al Eliminar: '.$conn->ErrorMsg());
			}else{
				
//***********************************************************************************
//***********************************************************************************
			$sqlBust = "
			SELECT 
					secuencia,
					id_orden_compra_serviciod,
					\"orden_compra_servicioD\".numero_pre_orden
				FROM 
					\"orden_compra_servicioD\" 
				INNER JOIN
					\"orden_compra_servicioE\"
				ON
					\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
			WHERE 
			
				\"orden_compra_servicioD\".numero_pre_orden = '$requi'
			ORDER BY
				secuencia 	
				";
			$rowr=& $conn->Execute($sqlBust);				
//die ($sqlBus);
//***********************************************************************************
//***********************************************************************************
				$i=1;				
				while(!$rowr->EOF){
				
				$actulizar = "
				UPDATE 
					\"orden_compra_servicioD\"
				SET  
					secuencia=".$i."
				WHERE 
					numero_pre_orden='$requi'
				and
					id_orden_compra_serviciod=".$rowr->fields("id_orden_compra_serviciod")."
					";
					if (!$conn->Execute($actulizar)) {
						die ('Error al Eliminar: '.$conn->ErrorMsg());
					}
				$i++;
				$rowr->MoveNext();
				}
				echo("Ok");
			}
	

?>