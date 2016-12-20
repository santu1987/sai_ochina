<?php
session_start();

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Clasificacion Presupuestaria ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM firma_presupuesto WHERE (upper(nombre_autoriza) = '".strtoupper($_POST[firma_presupuesto_db_nombre_auto])."') AND (id_organismo = ".$_SESSION['id_organismo'].")";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
$sql = "	
				INSERT INTO 
					firma_presupuesto 
					(
						id_organismo,
						nombre_autoriza ,
						cargo_autoriza ,
						grado_autoriza, 
						nombre_auto_traspaso ,
						cargo_auto_traspaso ,
						grado_auto_traspaso ,
						comentario,
						ultimo_usuario,
						fecha_actualizacion

					) 
					VALUES
					(
						".$_SESSION['id_organismo'].",
						'$_POST[firma_presupuesto_db_nombre_auto]',
						'$_POST[firma_presupuesto_db_cargo_auto]',
						'$_POST[firma_presupuesto_db_grado_auto]',
						'$_POST[firma_presupuesto_db_nombre_auto_tras]',
						'$_POST[firma_presupuesto_db_cargo_auto_tras]',
						'$_POST[firma_presupuesto_db_gardo_auto_tras]',
						'$_POST[firma_presupuesto_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".$fecha."'
					)
			";
else
	die("Existe");

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>