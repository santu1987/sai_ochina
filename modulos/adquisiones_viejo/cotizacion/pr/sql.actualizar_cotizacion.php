<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");


$sql=" 	
			SELECT 	
			\"solicitud_cotizacionE\".numero_cotizacion,
			requisicion_encabezado.numero_requisicion
				FROM 
					\"solicitud_cotizacionE\"
			INNER JOIN	
					\"solicitud_cotizacionD\"
			ON	
					\"solicitud_cotizacionE\".numero_cotizacion=\"solicitud_cotizacionD\".numero_cotizacion
			INNER JOIN
					proveedor
			ON
					\"solicitud_cotizacionE\".id_proveedor = proveedor.id_proveedor
			INNER JOIN 
					organismo 
				ON
					\"solicitud_cotizacionE\".id_organismo=organismo.id_organismo 
			INNER JOIN 
					unidad_ejecutora 
				ON
					\"solicitud_cotizacionE\".id_unidad_ejecutora=unidad_ejecutora.id_unidad_ejecutora 
			INNER JOIN
				 requisicion_encabezado
			ON
				 \"solicitud_cotizacionE\".id_requisicion=requisicion_encabezado.id_requisicion_encabezado
			WHERE 
				(\"solicitud_cotizacionE\".id_organismo=$_SESSION[id_organismo] )
			AND
				(\"solicitud_cotizacionD\".numero_cotizacion='$_POST[covertir_req_n_cot]' )	
			AND
				(\"solicitud_cotizacionE\".numero_cotizacion='$_POST[covertir_req_n_cot]' )	
			AND
				(requisicion_encabezado.numero_requisicion='$_POST[covertir_req_cot_nro_requi]' )	
			AND
				(\"solicitud_cotizacionE\".estatus = 0 )
			ORDER BY 
				\"solicitud_cotizacionE\".numero_cotizacion
		 	 	";
	//echo($sql);
$row=& $conn->Execute($sql);
if(!$row->EOF)
{	
$id_detalles = split( ",", $_POST['covertir_req_select'] );
$contador = count($id_detalles);
$i=0;
$sqlborrar = "
DELETE FROM 
	\"solicitud_cotizacionD\"
WHERE 
	numero_cotizacion = '".$_POST[covertir_req_n_cot]."'
";
$rowborra= $conn->Execute($sqlborrar);
while($i < $contador){
	$sqlrequidetalle = "
		SELECT 
			*
		 FROM 
			requisicion_detalle
		 WHERE
			(id_requisicion_detalle =".$id_detalles[$i].")
		";
	$rowd= $conn->Execute($sqlrequidetalle);
	
	$sqlsolideta = "
				INSERT INTO \"solicitud_cotizacionD\"(
						id_organismo, 
						id_proveedor, 
						ano, 
						secuencia, 
						cantidad, 
						id_unidad_medida, 
						descripcion, 
						partida,
						generica,
						especifica,
						subespecifica,
						comentario, 
						ultimo_usuario, 
						fecha_actualizacion,
						numero_cotizacion,
						numero_requisicion
						)
				VALUES ( 
						".$_SESSION['id_organismo'].", 
						".$_POST['covertir_req_cot_proveedor_id'].",  
						".$_POST['covertir_req_cot_ano'].", 
						".$rowd->fields('secuencia').", 
						".$rowd->fields('cantidad').", 
						".$rowd->fields('id_unidad_medida').", 
						'".$rowd->fields('descripcion')."',						
						'".$rowd->fields('partida')."', 
						'".$rowd->fields('generica')."', 
						'".$rowd->fields('especifica')."', 
						'".$rowd->fields('subespecifica')."', 						 
						'".$rowd->fields('comentario')."', 
						".$_SESSION['id_usuario'].", 
						'".$fecha."',
						'".$_POST[covertir_req_n_cot]."' ,
						'".$_POST['covertir_req_cot_nro_requi']."' 
						)
						";	
						//die ($sqlsolideta);
				if (!$conn->Execute($sqlsolideta)) 
					die ('Error al Actualizar DETALLE: '.$sqlsolideta);
			$i++;		
			$rowd->MoveNext();
		}
				$sql2 = "		
				UPDATE \"solicitud_cotizacionE\"
						SET
							id_proveedor='$_POST[covertir_req_cot_proveedor_id]'
						WHERE 
							(\"solicitud_cotizacionE\".id_organismo=$_SESSION[id_organismo] )
						AND
							(\"solicitud_cotizacionE\".numero_cotizacion='$_POST[covertir_req_n_cot]' )
							";	
	}		
	
	
else{
	die ("NoActualizo");
}
	
	if ($conn->Execute($sql)) {
			die ('Actualizado');}
	else {
		die ('Error al Actualizar: '.$conn->ErrorMsg());
	
		}
?>