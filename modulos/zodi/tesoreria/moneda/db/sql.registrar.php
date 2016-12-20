<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$cad = $_POST["tesoreria_moneda_pr_partida_numero"];
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
$sql = "SELECT id_moneda FROM moneda WHERE upper(nombre) ='".strtoupper($_POST['tesoreria_moneda_db_nombre'])."'";

if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
$sql = "	
				INSERT INTO 
					moneda
					(
						codigo_moneda,
						nombre,
						comentario,
						id_organismo,
						fecha_actualizacion,
						ultimo_usuario
			
					) 
					VALUES
					(
						'$_POST[tesoreria_moneda_db_codigo]',
						'$_POST[tesoreria_moneda_db_nombre]',
						'$_POST[tesoreria_moneda_db_observacion]',
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