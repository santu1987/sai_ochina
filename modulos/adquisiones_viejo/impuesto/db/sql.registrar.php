<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$cad = $_POST["adquisiciones_impuesto_pr_partida_numero"];
$tam = strlen($cad);
	for($i=1; $i<=4; $i++){
		if ($i<=3){
			$pos = strpos($cad, ".");
			$result[$i] = substr($cad, 0, $pos);
			$cad = substr($cad, $pos+1, $tam);
		}
		$tam = strlen($cad);
		if ($i==4)
			$result[$i] = substr($cad, 0, $tam);
	}

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$sql = "SELECT id_impuesto FROM impuesto WHERE upper(nombre) ='".strtoupper($_POST['adquisiciones_impuesto_db_nombre'])."'";

if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
$sql = "	
				INSERT INTO 
					impuesto
					(
						codigo_impuesto,
						nombre,
						partida,
						generica, 
						especifica,
						sub_especifica,
						comentario,
						cuenta_contable
						id_organismo,
						fecha_actualizacion,
						ultimo_usuario
			
					) 
					VALUES
					(
						'$_POST[adquisiciones_impuesto_db_codigo]',
						'$_POST[adquisiciones_impuesto_db_nombre]',
						'$result[1]',
						'$result[2]',
						'$result[3]',
						'$result[4]',
						'$_POST[adquisiciones_impuesto_db_observacion]',
						'$_POST[adquisiciones_impuesto_db_cuenta_contable]',
				        ".$_SESSION["id_organismo"].",
						'".date("Y-m-d H:i:s")."',
						".$_SESSION['id_usuario']."						
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