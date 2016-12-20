<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$fecha = date('Y');

$unidad =	 $_POST['modificacion_presupuesto_db_unidad_ejecutora'];
$unidad_es = $_POST['modificacion_presupuesto_db_accion_especifica_id'];
$mes =		 $_POST['modificacion_presupuesto_db_mes_cendente'];
$partida2 =	 $_POST['modificacion_presupuesto_db_partida_numero'];
$partida =	 explode(".",$partida2);

$Sql="


SELECT 
	".$mes.",
	modificacion_ley.id_modificacion_ley,
	modificacion_ley.monto,		
	traspaso_entre_partidas.monto_cedente as traspaso_monto,
	traspaso_entre_partidas.fecha_actualizacion as traspaso_fecha,
	traspaso_entre_partidas_receptora.fecha_actualizacion as traspaso_fecha_receptora,
	modificacion_ley.fecha_actualizacion as modificacion_fecha,
	clasificador_presupuestario.fecha_actualizacion,
	
	CASE WHEN (COALESCE(traspaso_entre_partidas.fecha_actualizacion,'20010101') > COALESCE(modificacion_ley.fecha_actualizacion,'20010101') or COALESCE(traspaso_entre_partidas_receptora.fecha_actualizacion,'20010101') > COALESCE(modificacion_ley.fecha_actualizacion,'20010101')) THEN 
		COALESCE(monto,".$mes.")  -
					(
						COALESCE(
							(SELECT sum(traspaso_entre_partidas.monto_cedente) FROM traspaso_entre_partidas WHERE 
							(traspaso_entre_partidas.anio=presupuesto_ley.anio) 
							AND
							(traspaso_entre_partidas.anio='".$fecha."') 
							AND
							(traspaso_entre_partidas.partida_cedente = '".$partida[0]."')
							AND
							(traspaso_entre_partidas.generica_cedente ='".$partida[1]."')
							AND
							(traspaso_entre_partidas.especifica_cedente='".$partida[2]."')
							AND
							(traspaso_entre_partidas.subespecifica_cedente='".$partida[3]."')
							AND
							(mes_modificado='".$mes."')),
							0
							)
					)
					+
					(
						COALESCE(
							(SELECT sum(traspaso_entre_partidas.monto_receptora) FROM traspaso_entre_partidas WHERE 
							(traspaso_entre_partidas.anio=presupuesto_ley.anio) 
							AND
							(traspaso_entre_partidas.anio='".$fecha."') 
							AND
							(traspaso_entre_partidas.partida_receptora = '".$partida[0]."')
							AND
							(traspaso_entre_partidas.generica_receptora ='".$partida[1]."')
							AND
							(traspaso_entre_partidas.especifica_receptora='".$partida[2]."')
							AND
							(traspaso_entre_partidas.subespecifica_receptora='".$partida[3]."')
							AND
							(mes_modificado='".$mes."')),
							0
							)
					)
	ELSE 
		COALESCE(monto,".$mes.") 
	END AS monto_mostrar 
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
LEFT JOIN 
	modificacion_ley 
ON
	clasificador_presupuestario.partida = modificacion_ley.partida
	AND
	clasificador_presupuestario.generica = modificacion_ley.generica
	AND
	clasificador_presupuestario.especifica = modificacion_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = modificacion_ley.sub_especifica 
	AND 
	mes_modificado='".$mes."'
LEFT JOIN
	 traspaso_entre_partidas 
ON 
	clasificador_presupuestario.partida = traspaso_entre_partidas.partida_cedente
	AND
	clasificador_presupuestario.generica = traspaso_entre_partidas.generica_cedente
	AND
	clasificador_presupuestario.especifica = traspaso_entre_partidas.especifica_cedente
	AND
	clasificador_presupuestario.subespecifica = traspaso_entre_partidas.subespecifica_cedente 
LEFT JOIN
	 traspaso_entre_partidas AS traspaso_entre_partidas_receptora   
ON 
	clasificador_presupuestario.partida = traspaso_entre_partidas_receptora.partida_receptora
	AND
	clasificador_presupuestario.generica = traspaso_entre_partidas_receptora.generica_receptora
	AND
	clasificador_presupuestario.especifica = traspaso_entre_partidas_receptora.especifica_receptora
	AND
	clasificador_presupuestario.subespecifica = traspaso_entre_partidas_receptora.subespecifica_receptora 	
WHERE
	(presupuesto_ley.id_unidad_ejecutora = ".$unidad.")
	AND
	(presupuesto_ley.id_accion_especifica =".$unidad_es.")
	AND
	(presupuesto_ley.id_organismo=$_SESSION[id_organismo])
	AND
	(presupuesto_ley.anio='".$fecha."') 
	AND
	(presupuesto_ley.partida = '".$partida[0]."')
	AND
	(presupuesto_ley.generica ='".$partida[1]."')
	AND
	(presupuesto_ley.especifica='".$partida[2]."')
	AND
	(presupuesto_ley.sub_especifica='".$partida[3]."')

ORDER BY
	traspaso_entre_partidas.fecha_actualizacion,
	modificacion_ley.fecha_actualizacion
DESC	

";
$row=& $conn->Execute($Sql);

if (!$row->EOF) 
{
		//	echo number_format($row->fields("monto_mostrar"),2,',','.');
		echo $Sql;
}
?>