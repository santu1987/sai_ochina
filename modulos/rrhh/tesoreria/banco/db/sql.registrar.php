<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$nom=$_POST['tesoreria_banco_db_nombre'];
$suc=$_POST['tesoreria_banco_db_sucursal'];
$nombre_banco=$nom."-".$suc;
$nombre_banco=strtoupper($nombre_banco);

/*$sql = "SELECT id_banco FROM banco WHERE upper(nombre) ='".strtoupper($nombre_banco)."' AND banco.id_organismo=$_SESSION[id_organismo]";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
*/
			if ($_POST['tesoreria_banco_db_estatus']=="2")
			{
			$sql = "	
							INSERT INTO 
								banco
								(
									id_organismo,
									nombre,
									sucursal,
									direccion,
									codigoarea,
									telefono,
									fax,
									persona_contacto,
									cargo_contacto,
									email_contacto,
									pagina_banco,
									estatus,
									usuario_inactiva,
									fecha_inactiva,
									comentarios,
									ultimo_usuario,
									fecha_ultima_modificacion
								) 
								VALUES
								(
									".$_SESSION["id_organismo"].",
								   	'$nombre_banco',
									'$_POST[tesoreria_banco_db_sucursal]',
									'$_POST[tesoreria_banco_db_direccion]',
									'$_POST[tesoreria_banco_db_codigoarea]',
									'$_POST[tesoreria_banco_db_telefono]',
									'$_POST[tesoreria_banco_db_fax]',
									'$_POST[tesoreria_banco_db_persona_contacto]',
									'$_POST[tesoreria_banco_db_cargo_contacto]',
									'$_POST[tesoreria_banco_db_email_contacto]',
									'$_POST[tesoreria_banco_db_pagina_web]',
									'$_POST[tesoreria_banco_db_estatus]',
									 ".$_SESSION['id_usuario'].",		
									'".date("Y-m-d H:i:s")."',
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
									banco
									(
										id_organismo,
										nombre,
										sucursal,
										direccion,
										codigoarea,
										telefono,
										fax,
										persona_contacto,
										cargo_contacto,
										email_contacto,
										pagina_banco,
										estatus,
										comentarios,
										ultimo_usuario,
										fecha_ultima_modificacion
									) 
									VALUES
									(
										".$_SESSION["id_organismo"].",
										'$nombre_banco',
										'$_POST[tesoreria_banco_db_sucursal]',
										'$_POST[tesoreria_banco_db_direccion]',
										'$_POST[tesoreria_banco_db_codigoarea]',
										'$_POST[tesoreria_banco_db_telefono]',
										'$_POST[tesoreria_banco_db_fax]',
										'$_POST[tesoreria_banco_db_persona_contacto]',
										'$_POST[tesoreria_banco_db_cargo_contacto]',
										'$_POST[tesoreria_banco_db_email_contacto]',
										'$_POST[tesoreria_banco_db_pagina_web]',
										'$_POST[tesoreria_banco_db_estatus]',
										'$_POST[tesoreria_banco_db_comentarios]',
										".$_SESSION['id_usuario']."	,
										'".date("Y-m-d H:i:s")."'
															
									)
							";
				}		
/*}							
else
	die("NoRegistro");
	
*/if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql);
//die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
	//die("$sql");
?>