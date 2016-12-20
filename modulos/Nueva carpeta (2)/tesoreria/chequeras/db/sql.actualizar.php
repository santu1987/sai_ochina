<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
/*$fecha2 = date("Y-m-d H:i:s");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlfecha_cierre = "SELECT  fecha_ultimo_cierre_anual,fecha_ultimo_cierre_mensual FROM parametros_tesoreria WHERE (id_organismo = ".$_SESSION['id_organismo'].") AND (ano = '".date("Y")."') ";
$row_fecha_cierre=& $conn->Execute($sqlfecha_cierre);
//echo $sqlfecha_cierre;
if(!$row_fecha_cierre->EOF){
	$fecha_cierre_anual = $row_fecha_cierre->fields('fecha_ultimo_cierre_anual');
	$fecha_cierre_mensual = $row_fecha_cierre->fields('fecha_ultimo_cierre_mensual');

}
list($dia1,$mes1,$ano1)=split("-",$fecha_cierre_mensual);
list($dia2,$mes2,$ano2)=split("-",$fecha2);
list($dia3,$mes3,$ano3)=split("-",$fecha_cierre_anual);
if((($dia2 <= $dia1) && ($mes2 <= $mes1) && ($ano2 <= $ano1))&&(($dia2 <= $dia3) && ($mes2 <= $mes3) && ($ano2 <= $ano2)))
{*/
			$cuenta= $_POST[tesoreria_chequeras_cuenta_db_n_cuenta];
			if($_POST[tesoreria_chequeras_db_estatus]=='1')
			{
									$sql_activo = "SELECT count(estatus) FROM  chequeras WHERE estatus='1' AND  id_banco= $_POST[tesoreria_chequeras_cuenta_id_banco]  AND  id_chequeras <> $_POST[tesoreria_chequeras_db_id] AND cuenta ='".$cuenta."' AND chequeras.id_organismo=$_SESSION[id_organismo]
			";
											if (!$conn->Execute($sql_activo)) echo($sql_activo);//die ('Error al Registrar: '.$conn->ErrorMsg());
											$row_activo= $conn->Execute($sql_activo);
														if(!$row_activo->EOF)
														{
															$count = $row_activo->fields("count");
															if($count>0)	die ("chequera_activa");
														}
			}
			$sql = "SELECT banco_cuentas.cuenta_banco FROM  banco_cuentas WHERE id_banco= $_POST[tesoreria_chequeras_cuenta_id_banco] AND cuenta_banco ='".$cuenta."' AND estatus!='3'";
			
			if (!$conn->Execute($sql)) die ('Error al actualizar: '.$conn->ErrorMsg());
					$row= $conn->Execute($sql);
			
			if(!$row->EOF)
							{
								$sql = "		UPDATE chequeras 
													 SET
														estatus=	'$_POST[tesoreria_chequeras_db_estatus]',
														ultimo_usuario=".$_SESSION['id_usuario'].", 
														fecha_ultima_modificacion='".$fecha."',
														comentarios='$_POST[tesoreria_chequeras_db_comentarios]'
													WHERE id_chequeras = $_POST[tesoreria_chequeras_db_id]
													AND
														 chequeras.id_organismo=$_SESSION[id_organismo]
			
											";
								}								
			else
			
					die ("NoActualizo");
			if (!$conn->Execute($sql)) {
				
				die ('Error al Actualizar: '.$conn->ErrorMsg());}
			else {
				die ('Actualizado');
				}
/*}else
die("cerrado");
*/?>