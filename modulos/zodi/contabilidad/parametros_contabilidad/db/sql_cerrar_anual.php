<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ayo=$_POST[contabilidad_cierre_ano];
$mes=date('m');
$sql_prueba="select * from parametros_contabilidad where ano=$ayo and id_organismo='$_SESSION[id_organismo]'";
if (!$conn->Execute($sql_prueba)) 
		die ('Error al registrar: '.$sql_prueba);
$row=$conn->Execute($sql_prueba);
if(!$row->EOF)
{
	//------------------- VERIFICANDO SI LAS facturas no fueron agregadas a otra orden de pago//----------------------------//
	//////////////////////////////////////////////////////////////////////////////////////////////////
											/*$sql_pago="UPDATE parametros_contabilidad
														SET
															
															ultimo_usuario=".$_SESSION['id_usuario']."	,
															ultimo_mes='$mes',
															ultima_modificacion='".date("Y-m-d H:i:s")."'
														WHERE 
																ano='$ayo'
}AND
															id_organismo=".$_SESSION["id_organismo"]."
													";				
															
														if (!$conn->Execute($sql_pago)) 
															die ('Error al registrar: '.$sql_pago);*/
								
			////////////////////////////////////////////////////////-ACTUALIZANDO LOS DOCUMENTOS SELECCIONADOS-///////////////////////////////////////////////////////////
														
	die("Actualizado");
}else
	die("NoActualizo");
?>