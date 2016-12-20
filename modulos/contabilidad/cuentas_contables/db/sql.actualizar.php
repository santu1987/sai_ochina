<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sqlb = "SELECT id FROM cuenta_contable_contabilidad WHERE id <> $_POST[contabilidad_cuentas_contables_db_id] AND cuenta_contable ='$_POST[contabilidad_cuentas_contables_db_cuenta_contable]'";

if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);
$tiposc =$_POST[contabilidad_cuentas_contables_db_tipo_cuenta];
if($row->EOF)
{
	if($_POST[contabilidad_cuentas_contables_db_id_cuenta_presupuesto]!='')
	{
		$sql_presu="		UPDATE cuenta_contable_contabilidad  
								 SET
							id_cuenta_presupuesto='$_POST[contabilidad_cuentas_contables_db_id_cuenta_presupuesto]'
								WHERE id = '$_POST[contabilidad_cuentas_contables_db_id]'";
			
	}
	else
	{
		$sql_presu="";
		}
	if(($tiposc!='e')&&($_POST[contabilidad_cuentas_contables_db_id_cuenta_suma]!=''))
	{

	$sql_cuenta_suma="		UPDATE cuenta_contable_contabilidad  
								 SET
							id_cuenta_suma='$_POST[contabilidad_cuentas_contables_db_id_cuenta_suma]'
								WHERE id = '$_POST[contabilidad_cuentas_contables_db_id]'
	";
	}
	else
		{

				$sql_cuenta_suma="";	
		}
		$nombre_cuenta=utf8_decode($_POST[contabilidad_cuentas_contables_db_nombre]);

	$sql = "	
					UPDATE cuenta_contable_contabilidad  
						 SET
							nombre = '$nombre_cuenta',
							id_naturaleza_cuenta=$_POST[cuentas_contables_db_naturaleza],
							comentario = '$_POST[contabilidad_cuentas_contables_db_comentario]',
							requiere_auxiliar= '$_POST[contabilidad_cuentas_contables_db_requiere_auxiliar]',
							requiere_unidad_ejecutora= '$_POST[contabilidad_cuentas_contables_db_requiere_unidad_ejecutora]',
							requiere_proyecto= '$_POST[contabilidad_cuentas_contables_db_requiere_proyecto]',
							requiere_utilizacion_fondos= '$_POST[contabilidad_cuentas_contables_db_requiere_utf]',
							tipo='$_POST[contabilidad_cuentas_contables_db_tipo_cuenta]',							
							id_organismo=	 ".$_SESSION["id_organismo"].",
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."',
							columna='$_POST[contabilidad_cuentas_contables_db_columna]'		
							WHERE id = $_POST[contabilidad_cuentas_contables_db_id];
					$sql_cuenta_suma;
					$sql_presu		
							
				";
}
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>