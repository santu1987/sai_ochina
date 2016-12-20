<?php
session_start();

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Clasificacion Presupuestaria ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM cuenta_contable 
	WHERE 
		((partida = '".$_POST[cuenta_contable_db_partida]."') AND 
		(generica = '".$_POST[cuenta_contable_db_generica]."') AND
		(especifica = '".$_POST[cuenta_contable_db_especifica]."') AND
		(subespecifica = '".$_POST[cuenta_contable_db_subespecifica]."')) OR
		(upper(denominacion) = '".strtoupper($_POST[cuenta_contable_db_denominacion])."')";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
$sql = "	
				INSERT INTO 
					cuenta_contable 
					(
						partida ,
						generica,
						especifica,
						subespecifica,
						denominacion,
						grupo,
						tipo,
						clasificacion_presupuestaria,
						comentario,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_POST[cuenta_contable_db_partida]',
						'$_POST[cuenta_contable_db_generica]',
						'$_POST[cuenta_contable_db_especifica]',
						'$_POST[cuenta_contable_db_subespecifica]',
						'$_POST[cuenta_contable_db_denominacion]',
						'$_POST[cuenta_contable_db_grupo]',
						'$_POST[cuenta_contable_db_tipo]',
						'$_POST[cuenta_contable_db_cuenta_contable]',
						'$_POST[cuenta_contable_db_comentario]',						
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