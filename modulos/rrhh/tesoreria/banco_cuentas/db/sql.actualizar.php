<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$cuenta= $_POST['tesoreria_banco_cuenta_db_n_cuenta'];
$saldo= str_replace(".","",$_POST[tesoreria_banco_cuenta_saldo]);
$saldo_inicial = str_replace(".","",$_POST[tesoreria_banco_cuenta_saldo_inicial]);		

$sql = "SELECT cuenta_banco FROM banco_cuentas WHERE banco_cuentas.id_cuenta_banco<>$_POST[tesoreria_vista_banco_cuenta] AND banco_cuentas.cuenta_banco ='".$cuenta."' AND banco_cuentas.id_organismo=$_SESSION[id_organismo]";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "		UPDATE banco_cuentas 
						 SET
						 	cuenta_contable_banco='$_POST[tesoreria_banco_cuenta_db_cuenta_contable]',
						 	ayo='$_POST[tesoreria_banco_cuenta_db_ayo]',
							estatus=	'$_POST[tesoreria_banco_cuenta_db_estatus]',
							comentarios='$_POST[tesoreria_banco_db_comentarios]',
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_ultima_modificacion='".$fecha."',
							
							fecha_apertura='$_POST[tesoreria_banco_cuenta_db_fecha_v]'							
						WHERE id_cuenta_banco = $_POST[tesoreria_vista_banco_cuenta]
						AND
							id_organismo=$_SESSION[id_organismo]
					";

			//	id_banco='$_POST[tesoreria_vista_banco_cuenta]',
			//				cuenta_banco='$_POST[tesoreria_banco_cuenta_db_n_cuenta]',
			//saldo_inicial='".str_replace(",",".",$saldo_inicial)."',
			//				saldo_actual='".str_replace(",",".",$saldo)."',								


else
	//die($sql);
	die ("NoActualizo");
if (!$conn->Execute($sql)) {
	echo($sql);
	die ('Error al Actualizar: '.$conn->ErrorMsg());}
else {
//die($sql);
	die ('Actualizado');
	}
?>