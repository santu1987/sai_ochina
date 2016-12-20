<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


$valor =str_replace('.','',$_POST['sareta_ley_db_vista_tarifa']);
		$valor1 =str_replace('.','',$_POST['sareta_ley_db_vista_tonelaje_inicial']);
			$valor2 =str_replace('.','',$_POST['sareta_ley_db_vista_tonelaje_final']);

if(str_replace(',','.',$valor1)<= str_replace(',','.',$valor2)){
	$sql = "	
					UPDATE sareta.ley  
						 SET
						 articulo=$_POST[sareta_ley_db_vista_articulo],
  						paragrafo=$_POST[sareta_ley_db_vista_paragrafo],
  						descripcion='$_POST[sareta_ley_db_vista_descripcion]',
  						id_tipo_tasa=$_POST[sareta_ley_db_vista_codigo_tasa],
  						tarifa=".str_replace(',','.',$valor).",
  						tonelaje_inicial=".str_replace(',','.',$valor1).",
  						tonelaje_final=".str_replace(',','.',$valor2).",
  						activo=$_POST[sareta_ley_db_vista_activo],
  						obs='$_POST[sareta_ley_db_vista_obs]',
  						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".date("Y-m-d H:i:s")."'
						WHERE id_ley = $_POST[vista_id_ley]
							
				";
				

					}else{
					die("vorlor_inicial_erroneo");
					}
if (!$conn->Execute($sql)) 
	die ('Error al Actualizar: '.$conn->ErrorMsg());

else
	die("Actualizado");
?>