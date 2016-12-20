<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//Concirtiendo el monto para poder actualizar en la base de datos
$_POST['conceptos_fijos_pr_monto'] = str_replace('.','',$_POST['conceptos_fijos_pr_monto']);
$_POST['conceptos_fijos_pr_monto'] = str_replace(',','.',$_POST['conceptos_fijos_pr_monto']);
//
$porcentaje = '0.00';
if($_POST['conceptos_fijos_pr_porcentaje']!=''){
	$porcentaje = $_POST['conceptos_fijos_pr_porcentaje'];
	$porcentaje = str_replace(',','.',$porcentaje);
}
if(!$sidx) $sidx =1;

	$sql = "	
				UPDATE 
					conceptos_fijos
				SET
					id_concepto = '$_POST[conceptos_fijos_pr_id_concepto]',
					porcentaje = '$porcentaje',
					monto = '$_POST[conceptos_fijos_pr_monto]',
					observacion = '$_POST[conceptos_fijos_pr_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[conceptos_fijos_pr_fechact]'
				WHERE	
					id_concepto_fijos = $_POST[conceptos_fijos_pr_id_concepto_fijo]
				AND	
					id_organismo = '$_SESSION[id_organismo]'
";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
?>