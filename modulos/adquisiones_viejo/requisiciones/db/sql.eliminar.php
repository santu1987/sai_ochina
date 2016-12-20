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

$sqlBus = "
SELECT 
		numero_requision,
		estatus,
		descripcion
	FROM 
		requisicion_detalle 
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_detalle.numero_requision = requisicion_encabezado.numero_requisicion
WHERE 
	(id_requisicion_detalle = ".$id.")
AND
	numero_requision = '$requi'
 	
	";
$row=& $conn->Execute($sqlBus);



if($row->fields("estatus")==1){

		$sql="
		DELETE 
		FROM 
			requisicion_detalle
		WHERE 
			id_requisicion_detalle = $id 
		AND
			numero_requision = '$requi'";
		
			if (!$conn->Execute($sql)) {
				die ('Error al Eliminar: '.$conn->ErrorMsg());
			}else{
				
//***********************************************************************************
//***********************************************************************************
			$sqlBust = "
			SELECT 
					secuencia,
					id_requisicion_detalle,
					numero_requision
				FROM 
					requisicion_detalle 
				INNER JOIN
					requisicion_encabezado
				ON
					requisicion_detalle.numero_requision = requisicion_encabezado.numero_requisicion
			WHERE 
			
				numero_requision = '$requi'
			ORDER BY
				secuencia 	
				";
			$rowr=& $conn->Execute($sqlBust);				

//***********************************************************************************
//***********************************************************************************
				$i=1;				
				while(!$rowr->EOF){
				
				$actulizar = "
				UPDATE 
					requisicion_detalle
				SET  
					secuencia=".$i."
				WHERE 
					numero_requision='$requi'
				and
					id_requisicion_detalle=".$rowr->fields("id_requisicion_detalle")."
					";
					if (!$conn->Execute($actulizar)) {
						die ('Error al Eliminar: '.$conn->ErrorMsg());
					}
				$i++;
				$rowr->MoveNext();
				}
				echo("Ok");
			}
	
}elseif($row->fields("estatus")==2){
	die('cotizacion_existe');
}else{
	die('nada: '.$sqlBus);
}
?>