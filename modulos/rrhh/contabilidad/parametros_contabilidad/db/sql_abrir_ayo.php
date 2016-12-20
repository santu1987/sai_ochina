<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
////////////////////////////////////////calculando la fecha del cierre anual/////////////////////////////////////////////////////////
	$dia_f=20;
	$mes_f=11;
	$ayo_f=date("Y");
	$mes_f2=date("m");
if($mes_f2=='01')
{
	$ayo_ant=$ayo_f-1;	
	$mes_f2='12';
}
else
{
	$ayo_ant=$ayo_f;	
	$mes_f2=$mes_f2-1;
}


$fecha_f=date("d/m/Y",mktime(0,0,0,$mes_f2,$dia_f,$ayo_ant));
//die($fecha_f);
////////////////////////////////////////////////////////////////////////////////////////
$sql_prueba="select * from parametros_contabilidad where ultimo_mes=12 and ano='$ayo_ant' and id_organismo='$_SESSION[id_organismo]'";
//die($sql_prueba);
if (!$conn->Execute($sql_prueba)) 
		die ('Error al registrar: '.$sql_prueba);
$row=$conn->Execute($sql_prueba);
//die($sql_prueba);
if(!$row->EOF)
{
	
														$sql_pago="UPDATE parametros_contabilidad
																	SET
																		
																		ultimo_usuario=".$_SESSION['id_usuario']."	,
																		fecha_cierre_mensual='".$fecha_f."',
																		fecha_cierre_anual='".$fecha_f."',
																		ultima_modificacion='".date("Y-m-d H:i:s")."',
																		comentarios='$_POST[contabilidad_apertura_contable_comentarios]'
																	WHERE 
																			ano='$ayo_ant'
																		
																	AND
																		id_organismo=".$_SESSION["id_organismo"]."
																
																	";		//die($sql_pago);		
																	if (!$conn->Execute($sql_pago)) 
																		die ('Error al registrar: '.$sql_pago);
											
																	
				die("Actualizado");
	}
	else
	die("error");
?>
