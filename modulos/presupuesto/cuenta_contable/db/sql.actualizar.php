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
	cuenta_contable 
WHERE 
	(id_cuenta_contable<>".$_POST[cuenta_contable_db_id].") 
AND 
	((upper(denominacion)='".strtoupper($_POST[cuenta_contable_db_denominacion])."') 
OR 
	(partida='".$_POST[cuenta_contable_db_partida]."'
AND
	generica='".$_POST[cuenta_contable_db_generica]."'
AND
	especifica='".$_POST[cuenta_contable_db_especifica]."'
AND
	subespecifica='".$_POST[cuenta_contable_db_subespecifica]."'))";
$row=& $conn->Execute($sqlbus);

if($row->EOF)
	$sql = "	
					UPDATE cuenta_contable  
						 SET
							partida = '$_POST[cuenta_contable_db_partida]',
							generica = '$_POST[cuenta_contable_db_generica]',						
							especifica = '$_POST[cuenta_contable_db_especifica]',
							subespecifica ='$_POST[cuenta_contable_db_subespecifica]',
							denominacion = '$_POST[cuenta_contable_db_denominacion]',
							grupo = $_POST[cuenta_contable_db_grupo],						
							tipo = '$_POST[cuenta_contable_db_tipo]',
							clasificacion_presupuestaria = '$_POST[cuenta_contable_db_cuenta_contable]',
							comentario ='$_POST[cuenta_contable_db_comentario]',
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion = '".$fecha."'
						WHERE
							id_cuenta_contable = $_POST[cuenta_contable_db_id]
							
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