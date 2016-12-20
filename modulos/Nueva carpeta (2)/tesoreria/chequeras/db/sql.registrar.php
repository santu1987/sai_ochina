<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST[tesoreria_chequeras_cuenta_db_n_cuenta];
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sql_activo = "SELECT count(estatus) FROM  chequeras WHERE estatus='1' AND  id_banco= $_POST[tesoreria_chequeras_cuenta_id_banco] AND cuenta ='".$cuenta."' AND $_POST[tesoreria_chequeras_db_estatus]='1' AND
							chequeras.id_organismo=$_SESSION[id_organismo]";
			if (!$conn->Execute($sql_activo)) die ('Error al consultar: '.$conn->ErrorMsg());
			$row_activo= $conn->Execute($sql_activo);
						if(!$row_activo->EOF)
						{
							$count = $row_activo->fields("count");
							if($count>0)
							{
								$opcion="activa";
								$estatus=2;
							}else 
							{	
								$estatus=$_POST[tesoreria_chequeras_db_estatus];
							}
							//	die("chequera_activa");
						}
			///			
			$sql = "SELECT banco_cuentas.cuenta_banco FROM  banco_cuentas WHERE id_banco= $_POST[tesoreria_chequeras_cuenta_id_banco] AND cuenta_banco ='".$cuenta."' AND
							banco_cuentas.id_organismo=$_SESSION[id_organismo]";
			if (!$conn->Execute($sql))  die ('Error al Registrar: '.$conn->ErrorMsg());
			$row= $conn->Execute($sql);
			if(!$row->EOF){
			
							$sql = "	
										INSERT INTO 
											chequeras
											(
												id_organismo,
												id_banco,
												cuenta,
												primer_cheque,
												ultimo_emitido,
												cantidad_cheques,
												cantidad_emitidos,
												estatus,
												secuencia,
												comentarios,
												ultimo_usuario,
												fecha_ultima_modificacion
											) 
											VALUES
											(
												".$_SESSION["id_organismo"].",
												'$_POST[tesoreria_chequeras_cuenta_id_banco]',
												'$_POST[tesoreria_chequeras_cuenta_db_n_cuenta]',
												'$_POST[tesoreria_chequeras_primer_cheque]',
												'$_POST[tesoreria_chequeras_ultimo_emitido]',
												'$_POST[tesoreria_chequeras_cantidad_cheques]',
												'$_POST[tesoreria_chequeras_cantidad_cheques_emitidos]',
												'$estatus',
												'$_POST[tesoreria_chequeras_cuenta_db_ncheque_codigo]',
												'$_POST[tesoreria_chequeras_db_comentarios]',
												 ".$_SESSION['id_usuario'].",		
												'".date("Y-m-d H:i:s")."'
													)
									";
						
						
						}
					
							/*	$_POST[tesoreria_banco_db_estatus]',
									'$_POST[tesoreria_banco_db_usuario_inactiva]',
									'$_POST[tesoreria_banco_db_fecha_inactiva]',		
									estatus,
									usuario_inactiva,
									fecha_inactiva,	*/
			else
			
			die("NoRegistro");
				
			if (!$conn->Execute($sql)) 
			die ('Error al Registrar: '.$sql);
			//die ('Error al Registrar: '.$conn->ErrorMsg());
			else
				if($opcion=="activa"){die("chequera_activa");}
			
				die("Registrado");
				//die("$sql");
			
/*}else
die("cerrado");	*/			
?>