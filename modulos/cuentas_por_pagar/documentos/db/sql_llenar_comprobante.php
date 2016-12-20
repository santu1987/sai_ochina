<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$numero_compromiso=$_POST[cuentas_por_pagar_db_compromiso_n];
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT 
						tipo,
						id_orden_compra_servicioe as id,
						\"orden_compra_servicioE\".id_unidad_ejecutora, 
						\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
						\"orden_compra_servicioE\".id_accion_especifica,
						\"orden_compra_servicioE\".tipo
						
					FROM 
						\"orden_compra_servicioE\"
					INNER JOIN
						organismo
					ON
						\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
					INNER JOIN
						\"orden_compra_servicioD\"
					ON
						\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
					where
						\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'
					GROUP BY
						tipo,

						id_orden_compra_servicioe ,

						\"orden_compra_servicioE\".id_unidad_ejecutora, 

						\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 

						\"orden_compra_servicioE\".id_accion_especifica,

						\"orden_compra_servicioE\".tipo
					    		
						";
				$row_orden_compra=& $conn->Execute($sql);
			//die($sql);
$conta_tor=0;
if(!$row_orden_compra->EOF)
{
	$id_unidad_ejecutora=$row_orden_compra->fields("id_unidad_ejecutora");
	$id_proyecto_accion_centralizada=$row_orden_compra->fields("id_proyecto_accion_centralizada");
	$id_accion_especifica=$row_orden_compra->fields("id_accion_especifica");
	$tipo=$row_orden_compra->fields("tipo");
	$requiere_proyecto=$_POST[cxp_comp_pr_activo4];
	$requiere_ue=$_POST[cxp_comp_pr_activo3];
	$requiere_uf=$_POST[cxp_comp_pr_activo2];
	/////////////////////////////////////////////////////////////////
	//verificamos cada requerimiento y consultamos
	//die($requiere_proyecto);
	if($requiere_proyecto=='1')
	{
		/*if($tipo=='1')
		{*/
			$sql_proyecto="SELECT id_proyecto, id_organismo, ano, id_jefe_proyecto, codigo_proyecto, 
       		nombre, comentario, fecha_actualizacion, ultimo_usuario, usuario_windows, 
       		serial_maquina
  			FROM 
				proyecto
			WHERE
				id_proyecto='$id_proyecto_accion_centralizada'
			";
			//die($sql_proyecto);
			$row_pro=& $conn->Execute($sql_proyecto);
			if(!$row_pro->EOF)
			{
				$nombre_proyecto=$row_pro->fields("nombre");
				$codigo_proyecto=$row_pro->fields("codigo_proyecto");
			}	
		//}	
	}
	if($requiere_ue=='1')
	{
		$sql_ue="SELECT id_unidad_ejecutora, id_organismo, nombre, comentario, ultimo_usuario, 
       fecha_actualizacion, usuario_windows, serial_maquina, jefe_unidad, 
       codigo_unidad_ejecutora, tipo_unidad, unidad_regional
  FROM unidad_ejecutora
  		WHERE
		id_unidad_ejecutora='$id_unidad_ejecutora';
		";
		$row_ue=& $conn->Execute($sql_ue);
		if(!$row_ue->EOF)
		{
			$nombre_ue=$row_ue->fields("nombre");
			$codigo_ue=$row_ue->fields("codigo_unidad_ejecutora");
		}
	}
	
	/////////////////////////////////////////////////////////////////
	

////////////////////////////
$response="bien"."*".$id_proyecto_accion_centralizada."*".$codigo_proyecto."*".$nombre_proyecto."*".$tipo."*".$id_unidad_ejecutora."*".$codigo_ue."*".$nombre_ue;
die($response);
///////////////////////////
}//fin de if(!$row_orden_compra->EOF)
else
die("error");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
?>