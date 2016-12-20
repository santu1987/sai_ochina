<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$ayo=$_POST[contabilidad_cierre_ano];
$mes=date("m");
$sql_prueba="select * from parametros_contabilidad where ano=$ayo and id_organismo='$_SESSION[id_organismo]'";
if (!$conn->Execute($sql_prueba)) 
		die ('Error al registrar: '.$sql_prueba);
$row=$conn->Execute($sql_prueba);
if(!$row->EOF)
{
	$mes2=$row->fields("ultimo_mes");
	if($mes_comp!=$mes)
	{
			
			
				//die($mes2);	
				if($mes2==12)
				{
					$mes2=1;

				}
				else
				{
				$mes2=$mes2+1;
				}		
				$tres="000";
				
				//////////////////////////////////////////////// 
				$numero_comprobante_nuevo=$mes2.$tres;
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
														$sql_pago="UPDATE parametros_contabilidad
																	SET
																		ultimo_usuario=".$_SESSION['id_usuario']."	,
																		ultimo_mes='$mes2',
																		fecha_cierre_mensual='".date("Y-m-d H:i:s")."',
																		ultima_modificacion='".date("Y-m-d H:i:s")."'
																	WHERE 
																			ano='$ayo'
																	AND
																		id_organismo=".$_SESSION["id_organismo"]."
																";
																/*UPDATE
																		tipo_comprobante
																	set
																		numero_comprobante='$numero_comprobante_nuevo'	
																WHERE 
																			ayo='$ayo'
																	AND
																		id_organismo=".$_SESSION["id_organismo"]."
																die($sql_pago);				*/
																	if (!$conn->Execute($sql_pago)) 
																		die ('Error al registrar: '.$sql_pago);
											
																	
				die("Actualizado");
	}
	else
	die("mes_cerrado");
}else
	die("NoActualizo");
?>