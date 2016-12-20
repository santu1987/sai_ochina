<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//Convirtiendo el monto para poder actualizarlo en la base de datos
$_POST['concepto_variable_pr_monto'] = str_replace('.','',$_POST['concepto_variable_pr_monto']);
$_POST['concepto_variable_pr_monto'] = str_replace(',','.',$_POST['concepto_variable_pr_monto']);
//
$porcentaje = '0.00';
if($_POST['concepto_variable_pr_porcentaje']!=''){
	$porcentaje = $_POST['concepto_variable_pr_porcentaje'];
	$porcentaje = str_replace(',','.',$porcentaje);
}
if(!$sidx) $sidx =1;

	$sql = "	
				UPDATE 
					concepto_variable
				SET
					id_concepto = '$_POST[concepto_variable_pr_id_concepto]',
					porcentaje = '$porcentaje',
					monto = '$_POST[concepto_variable_pr_monto]',
					observacion = '$_POST[concepto_variable_pr_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[concepto_variable_pr_fechact]'
				WHERE	
					id_concepto_variable = $_POST[concepto_variable_pr_id_concepto_variable]
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