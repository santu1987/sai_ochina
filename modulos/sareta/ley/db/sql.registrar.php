<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT * FROM sareta.ley  WHERE descripcion='".$_POST['sareta_ley_db_vista_descripcion']."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
if($row->EOF){

	$valor =str_replace('.','',$_POST['sareta_ley_db_vista_tarifa']);
		$valor1 =str_replace('.','',$_POST['sareta_ley_db_vista_tonelaje_inicial']);
			$valor2 =str_replace('.','',$_POST['sareta_ley_db_vista_tonelaje_final']);
			if(str_replace(',','.',$valor1)<= str_replace(',','.',$valor2)){
$sql = "	
				INSERT INTO 
					sareta.ley 
					(
  						articulo,
  						paragrafo,
  						descripcion,
  						id_tipo_tasa,
  						tarifa,
  						tonelaje_inicial,
  						tonelaje_final,
  						activo,
  						obs,
  						ultimo_usuario,
  						fecha_creacion,
  						fecha_actualizacion
					) 
					VALUES
					(
						$_POST[sareta_ley_db_vista_articulo],
						$_POST[sareta_ley_db_vista_paragrafo],
						'$_POST[sareta_ley_db_vista_descripcion]',
						$_POST[sareta_ley_db_vista_codigo_tasa],
						".str_replace(',','.',$valor).",
						".str_replace(',','.',$valor1).",
						".str_replace(',','.',$valor2).",
						$_POST[sareta_ley_db_vista_activo],
						'$_POST[sareta_ley_db_vista_obs]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'".date("Y-m-d H:i:s")."'
						
					)";
					}else{
					die("vorlor_inicial_erroneo");
					}
	
			}else{
			die("NoRegistro");
			}

	
if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Registrado");
?>