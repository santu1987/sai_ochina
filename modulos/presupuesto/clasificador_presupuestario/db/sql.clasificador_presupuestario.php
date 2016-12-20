<?php
session_start();

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Clasificacion Presupuestaria ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM clasificador_presupuestario 
	WHERE 
		((partida = '".$_POST[clasificador_presupuestario_db_partida]."') AND 
		(generica = '".$_POST[clasificador_presupuestario_db_generica]."') AND
		(especifica = '".$_POST[clasificador_presupuestario_db_especifica]."') AND
		(subespecifica = '".$_POST[clasificador_presupuestario_db_subespecifica]."')) OR
		(upper(denominacion) = '".strtoupper($_POST[clasificador_presupuestario_db_denominacion])."')";
$row=& $conn->Execute($sqlBus);

if($row->EOF)
$sql = "	
				INSERT INTO 
					clasificador_presupuestario 
					(
						partida ,
						generica,
						especifica,
						subespecifica,
						denominacion,
						grupo,
						tipo,
						cuenta_contable,
						comentario,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_POST[clasificador_presupuestario_db_partida]',
						'$_POST[clasificador_presupuestario_db_generica]',
						'$_POST[clasificador_presupuestario_db_especifica]',
						'$_POST[clasificador_presupuestario_db_subespecifica]',
						'$_POST[clasificador_presupuestario_db_denominacion]',
						'$_POST[clasificador_presupuestario_db_grupo]',
						'$_POST[clasificador_presupuestario_db_tipo]',
						'$_POST[clasificador_presupuestario_db_cuenta_contable]',
						'$_POST[clasificador_presupuestario_db_comentario]',						
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