<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT id_utilizacion_fondos FROM utilizacion_fondos
 WHERE 
	upper(nombre) ='".strtoupper($_POST['contabilidad_ut_fondos_db_nombre'])."'"; 

if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
$op=$_POST[contabilidad_ut_fondos_db_tipo];
/*if($op='DETALLE')
	$op2=0;
if($op='TOTAL')
	$op2=1;
if($op='AUTOMÁTICA')
	$op2=2;
if($op='ENCABEZADO')
	$op2=3;		*/	
if($row->EOF){
$sql = "	
				INSERT INTO 
					utilizacion_fondos
					(
						cuenta_utilizacion_fondos,
						nombre,
						tipo,
						id_organismo,
						comentarios,
						ultimo_usuario,
						ultima_modificacion
					) 
					VALUES
					(
						'$_POST[contabilidad_ut_fondos_db_cuenta_contable]',
						'$_POST[contabilidad_ut_fondos_db_nombre]',
						'$_POST[contabilidad_ut_fondos_db_tipo]',
				        ".$_SESSION["id_organismo"].",
						'$_POST[contabilidad_ut_fondos_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'						
					)
			";
	
	}						
else
	die("NoRegistro");
	
if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
die ('Error al Registrar: '.$conn->ErrorMsg());
else
	die("Registrado");
?>