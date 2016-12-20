<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlbus = "
SELECT 
	denominacion 
FROM 
	clasificador_presupuestario 
WHERE 
	(id_clasi_presu<>".$_POST[clasificador_presupuestario_db_id].") 
AND 
	((upper(denominacion)='".strtoupper($_POST[clasificador_presupuestario_db_denominacion])."') 
OR 
	(partida='".$_POST[clasificador_presupuestario_db_partida]."'
AND
	generica='".$_POST[clasificador_presupuestario_db_generica]."'
AND
	especifica='".$_POST[clasificador_presupuestario_db_especifica]."'
AND
	subespecifica='".$_POST[clasificador_presupuestario_db_subespecifica]."'))";
$row=& $conn->Execute($sqlbus);

if($row->EOF)
	$sql = "	
					UPDATE clasificador_presupuestario  
						 SET
							partida = '$_POST[clasificador_presupuestario_db_partida]',
							generica = '$_POST[clasificador_presupuestario_db_generica]',						
							especifica = '$_POST[clasificador_presupuestario_db_especifica]',
							subespecifica ='$_POST[clasificador_presupuestario_db_subespecifica]',
							denominacion = '$_POST[clasificador_presupuestario_db_denominacion]',
							grupo = $_POST[clasificador_presupuestario_db_grupo],						
							tipo = '$_POST[clasificador_presupuestario_db_tipo]',
							cuenta_contable = '$_POST[clasificador_presupuestario_db_cuenta_contable]',
							comentario ='$_POST[clasificador_presupuestario_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE
							id_clasi_presu = $_POST[clasificador_presupuestario_db_id]
							
				";

else
	$repetido=true;
	
if (!$conn->Execute($sql)||$repetido) {
	echo (($repetido)?$msgExiste:'Error al Actualizar: '.$conn->ErrorMsg().'<br />');
		//echo ($sqlbus);
}
else
{
	echo 'Actualizado';
			//echo ($sqlbus);
}
?>