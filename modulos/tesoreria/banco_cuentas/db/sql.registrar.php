<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST['tesoreria_banco_cuenta_db_n_cuenta'];
$sql = "SELECT banco_cuentas.id_cuenta_banco FROM banco_cuentas WHERE banco_cuentas.cuenta_banco ='".$cuenta."' AND banco_cuentas.id_organismo=$_SESSION[id_organismo]";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
$saldo= str_replace(".","",$_POST[tesoreria_banco_cuenta_saldo]);
$saldo_inicial = str_replace(".","",$_POST[tesoreria_banco_cuenta_saldo_inicial]);		
	
if($row->EOF){

			if ($_POST['tesoreria_banco_db_estatus']=="2")
			{
			$sql = "	
							INSERT INTO 
								banco_cuentas
								(
									id_organismo,
									id_banco,
									cuenta_banco,
									cuenta_contable_banco,
									estatus,
									usuario_inactiva,
									fecha_inactiva,
									ayo,
									saldo_inicial,
									saldo_actual,
									fecha_apertura,
									comentarios,
									ultimo_usuario,
									fecha_ultima_modificacion
								) 
								VALUES
								(
									".$_SESSION["id_organismo"].",
									'$_POST[tesoreria_banco_cuentas_id_banco]',
									'$_POST[tesoreria_banco_cuenta_db_n_cuenta]',
									'$_POST[tesoreria_banco_cuenta_db_cuenta_contable]',
									'$_POST[tesoreria_chequeras_db_estatus]',
									 ".$_SESSION['id_usuario'].",		
									'".date("Y-m-d H:i:s")."',
									'$_POST[tesoreria_banco_cuenta_db_ayo]',
									'".str_replace(",",".",$saldo_inicial)."',
									'".str_replace(",",".",$saldo)."',	
									'$_POST[tesoreria_banco_cuenta_db_fecha_v]',
									'$_POST[tesoreria_banco_db_comentarios]',
									".$_SESSION['id_usuario']."	,
									'".date("Y-m-d H:i:s")."'
								)
						";
			
			
			}
			else
			{
				$sql = "	
							INSERT INTO 
								banco_cuentas
								(
									id_organismo,
									id_banco,
									cuenta_banco,
									cuenta_contable_banco,
									estatus,
									comentarios,
									ayo,
									saldo_inicial,
									saldo_actual,
									fecha_apertura,
									ultimo_usuario,
									fecha_ultima_modificacion
								) 
								VALUES
								(
									".$_SESSION["id_organismo"].",
									'$_POST[tesoreria_banco_cuenta_id_banco]',
									'$_POST[tesoreria_banco_cuenta_db_n_cuenta]',
									'$_POST[tesoreria_banco_cuenta_db_cuenta_contable]',
									'$_POST[tesoreria_banco_cuenta_db_estatus]',
									'$_POST[tesoreria_banco_db_comentarios]',
    								'$_POST[tesoreria_banco_cuenta_db_ayo]',
									'".str_replace(",",".",$saldo_inicial)."',
									'".str_replace(",",".",$saldo)."',	
									'$_POST[tesoreria_banco_cuenta_db_fecha_v]',
									".$_SESSION['id_usuario']."	,
									'".date("Y-m-d H:i:s")."'
								)
						";
				}		
}						
else

die("NoRegistro");
	
if (!$conn->Execute($sql)) 
die ('Error al Registrar: '.$sql);
//die ('Error al Registrar: '.$conn->ErrorMsg());

else
	//die('Registrado'.$sql);
	die("Registrado");
?>