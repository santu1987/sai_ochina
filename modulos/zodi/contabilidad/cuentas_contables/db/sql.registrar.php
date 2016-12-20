<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$ano=substr($fecha,0,4);

$sql = "
		SELECT 
			id_auxiliares 
		FROM 
			auxiliares
		WHERE 
			upper(nombre) ='".strtoupper($_POST['contabilidad_auxiliares_db_nombre'])."'"; 

if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
if($_POST[contabilidad_cuentas_contables_db_id_cuenta_presupuesto]!="")
{
	$partida=$_POST[contabilidad_cuentas_contables_db_id_cuenta_presupuesto];
}else
{
	$partida=0;
}
$sql = "	
		INSERT INTO 
			cuenta_contable_contabilidad
			(
				id_organismo,
				cuenta_contable,
				nombre,
				tipo,
				".(($_POST[contabilidad_cuentas_contables_db_id_cuenta_suma])?"id_cuenta_suma,":"")."
				id_cuenta_presupuesto,				
				requiere_auxiliar,
				requiere_unidad_ejecutora,
				requiere_proyecto,
				requiere_utilizacion_fondos,
				id_naturaleza_cuenta,
				comentario,
				ultimo_usuario,
				fecha_actualizacion
			) 
			VALUES
			(
				".$_SESSION["id_organismo"].",
				'$_POST[contabilidad_cuentas_contables_db_cuenta_contable]',
				'$_POST[contabilidad_cuentas_contables_db_nombre]',
				'$_POST[contabilidad_cuentas_contables_db_tipo_cuenta]',
				".(($_POST[contabilidad_cuentas_contables_db_id_cuenta_suma])?"$_POST[contabilidad_cuentas_contables_db_id_cuenta_suma],":"")."
				$partida,
				'$_POST[contabilidad_cuentas_contables_db_requiere_auxiliar]',
				'$_POST[contabilidad_cuentas_contables_db_requiere_unidad_ejecutora]',
				'$_POST[contabilidad_cuentas_contables_db_requiere_proyecto]',
				'$_POST[contabilidad_cuentas_contables_db_requiere_utf]',
				'$_POST[cuentas_contables_db_naturaleza]',
				'$_POST[contabilidad_cuentas_contables_db_comentario]',
				".$_SESSION['id_usuario'].",
				'".date("Y-m-d H:i:s")."'						
			)
	";

if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$conn->ErrorMsg());
else
{
$sql_id = "
		SELECT 
			id 
		FROM 
			cuenta_contable_contabilidad
		WHERE 
			cuenta_contable='$_POST[contabilidad_cuentas_contables_db_cuenta_contable]'
		AND
			id_organismo=$_SESSION[id_organismo]	
			"; 

if (!$conn->Execute($sql_id)) die ('Error al Registrar: '.$conn->ErrorMsg());
		else
		{
		$row_id= $conn->Execute($sql_id);
		$ids=$row_id->fields("id");
			$sql2="INSERT INTO
							saldo_contable
							(
								id_organismo,
								ano,
								cuenta_contable,
								saldo_inicio,
								debe,
								haber,
								comentarios,
								ultimo_usuario,
								ultima_modificacion
								
							
							)
							values
							(
								 $_SESSION[id_organismo],
								 $ano,
								 '$ids',
								 '{0,0,0,0,0,0,0,0,0,0,0,0}',
								 '{0,0,0,0,0,0,0,0,0,0,0,0}',
								 '{0,0,0,0,0,0,0,0,0,0,0,0}',
								 '$_POST[contabilidad_cuentas_contables_db_comentario]',
								 ".$_SESSION['id_usuario'].",
								'".date("Y-m-d H:i:s")."'			
								)";
								//die($sql2);
			if (!$conn->Execute($sql2)) 
			die ('Error al Registrar: '.$conn->ErrorMsg().$sql2);
				else
					die("Registrado");
		}			
}
?>