<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlrequicion = "SELECT 
					*
 				 FROM 
				 	 \"solicitud_cotizacionE\"
				INNER JOIN
					 requisicion_encabezado
				ON
					 \"solicitud_cotizacionE\".id_requisicion=requisicion_encabezado.id_requisicion_encabezado
				 INNER JOIN 
					organismo 
				ON
					\"solicitud_cotizacionE\".id_organismo=organismo.id_organismo 
				 WHERE
				 	(numero_requisicion ='".$_POST['covertir_req_cot_nro_requi']."')
				AND
					(\"solicitud_cotizacionE\".id_proveedor=$_POST[covertir_req_cot_proveedor_id])
				AND
					(\"solicitud_cotizacionE\".id_organismo=$_SESSION[id_organismo] )
				";
$rowrequi=& $conn->Execute($sqlrequicion);
if (!$rowrequi->EOF)
{
	//echo($sqlrequicion);
	die("Existe");	
}
$row=& $conn->Execute($sqlrequicion);
$sqlrequicion = "SELECT 
					*
 				 FROM 
				 	requisicion_encabezado
				 WHERE
				 	(numero_requisicion ='".$_POST['covertir_req_cot_nro_requi']."')
				";

$row=& $conn->Execute($sqlrequicion);

//***********************************************
$sqlNSolicitud = "SELECT count(id_solicitud_cotizacione) FROM \"solicitud_cotizacionE\" ";
$roww=& $conn->Execute($sqlNSolicitud);
if (!$roww->EOF)
{
	$count = $roww->fields("count");
	$count = $count + 1;
}

	if ($count < 10)
	{
		$numero_solicitud = date('y')."000".$count;
	}elseif ($count >= 10 && $count < 100)
	{
		$numero_solicitud = date('y')."00".$count;
	}elseif ($count >= 100 && $count < 1000)
	{
		$numero_solicitud = date('y')."0".$count;
	}elseif ($count >= 10000)
	{
		$numero_solicitud = date('y').$count;
	}


if(!$row->EOF){
$id_detalles = split( ",", $_POST['covertir_req_select'] );
$contador = count($id_detalles);  ///$_POST['covertir_req_cot_titulo']
		$id_requisicion = $row->fields("id_requisicion_encabezado");
		$sql = "
		INSERT INTO \"solicitud_cotizacionE\"(
					id_organismo, 
					id_proveedor, 
					ano, 
					fecha_cotizacion, 
					titulo, 
					id_usuario_elabora, 
					fecha_elabora_solicitud, 
					id_unidad_ejecutora, 
					anocsc, 
					id_requisicion, 
					ultimo_usuario, 
					fecha_actualizacion, 
					id_tipo_documento,
					numero_cotizacion,
					comentarios
					)
			VALUES (  
					".$_SESSION['id_organismo'].",
					".$_POST['covertir_req_cot_proveedor_id'].", 
					".$_POST['covertir_req_cot_ano'].", 
					'".$fecha."', 
					'".$row->fields("asunto")."',
					".$_SESSION['id_usuario'].", 
					'".$fecha."', 
					".$_POST['covertir_req_cot_unidad_ejecutora_id'].", 
					'".date('Y')."', 
					".$id_requisicion.",
					".$_SESSION['id_usuario'].", 
					'".$fecha."', 
					".$row->fields("id_tipo_documento").",
					'$numero_solicitud',
					'".$_POST['covertir_req_cot_titulo']."'
					);
					
					UPDATE 
						requisicion_encabezado 
					SET
						estatus = 2 
					 WHERE
						(numero_requisicion ='".$_POST['covertir_req_cot_nro_requi']."')
					";
					
		
		$i=0;
		while($i < $contador){
	$sqlrequidetalle = "SELECT 
						*
					 FROM 
						requisicion_detalle
					 WHERE
						(id_requisicion_detalle =".$id_detalles[$i].")
					";
					/*
					AND
						(numero_requision ='".$_POST["covertir_req_cot_nro_requi"]."')
					AND
						(id_unidad_ejecutora =".$_SESSION["id_unidad_ejecutora"].")
					*/
						
	$rowd= $conn->Execute($sqlrequidetalle);
	//echo ($sqlrequidetalle."<br>");

//		while (!$rowd->EOF){		
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
						'$numero_solicitud' ,
						'".$_POST['covertir_req_cot_nro_requi']."' 
						)
						";	
						//die ($sqlsolideta);
				if (!$conn->Execute($sqlsolideta)) 
					die ('Error al Registrar: '.$sqlsolideta);
			$i++;		
			$rowd->MoveNext();
		}
					
			if (!$conn->Execute($sql)) 
				die ('Error al Registrar: '.$sql);
			else
				die("Registrado*".$numero_solicitud);

}else{
//die($sqlrequicion );
	die("Existe");	
}
?>





