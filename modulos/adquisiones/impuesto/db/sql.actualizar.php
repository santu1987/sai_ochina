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
$sqlb = "SELECT id_impuesto FROM impuesto WHERE id_impuesto <> $_POST[adquisiciones_vista_impuesto] AND upper(nombre) ='".strtoupper($_POST['adquisiciones_impuesto_db_nombre'])."'";
if (!$conn->Execute($sqlb)) die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if($row->EOF)
	$sql = "	
					UPDATE impuesto  
						 SET
							nombre = '$_POST[adquisiciones_impuesto_db_nombre]',
							partida = '$result[1]',
							generica = '$result[2]',
							especifica = '$result[3]',
							sub_especifica = '$result[4]',
							comentario = '$_POST[adquisiciones_impuesto_db_observacion]',							
							id_organismo=	 ".$_SESSION["id_organismo"].",
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							fecha_actualizacion ='".date("Y-m-d H:i:s")."',
							cuenta_contable='$_POST[adquisiciones_impuesto_db_cuenta_contable]'	
							WHERE id_impuesto = $_POST[adquisiciones_vista_impuesto]
							
				";
else
	die("NoActualizo");			

if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());
else
	die("Actualizado");
?>