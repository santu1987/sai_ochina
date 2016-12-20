<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql = "SELECT * FROM sareta.buque WHERE matricula='".$_POST['sareta_buque_db_matricula']."'";
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);

if($row->fields("id")==$_POST['vista_id_buque'] ||  $row->fields("id")==''){

	$sql = "SELECT * FROM sareta.buque WHERE call_sign='".$_POST['sareta_buque_db_call_sign']."'";
	if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql);
	
	if($row->fields("id")==$_POST['vista_id_buque'] ||  $row->fields("id")==''){

			$valor =str_replace('.','',$_POST['sareta_buque_db_rb']);
			$sql = "SELECT * FROM sareta.ley WHERE 
				id_ley=$_POST[id_ley]";
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF){
									$inicial=$row1->fields("tonelaje_inicial");
									$final=$row1->fields("tonelaje_final");
									
									}
			if(str_replace(',','.',$valor)>=$inicial && str_replace(',','.',$valor)<=$final){
$sql = "	
				UPDATE
					 sareta.buque
					SET
						matricula=upper('$_POST[sareta_buque_db_matricula]'),
					    call_sign=upper('$_POST[sareta_buque_db_call_sign]'),
					    nombre=upper('$_POST[sareta_buque_db_nombre]'),
					    id_bandera=$_POST[id_bandera],
					    r_bruto=".str_replace(',','.',$valor).",
					    id_actividad=$_POST[id_actividad],
					    id_clase=$_POST[id_clase],
					    nacionalidad=$_POST[buque_db_Nac],
					    pago_anual=$_POST[buque_db_Pago_anual],
					    id_ley=$_POST[id_ley],
					    exonerado=$_POST[buque_db_exonerado],
						comentario='$_POST[sareta_buque_db_vista_observacion]',
						ultimo_usuario=".$_SESSION['id_usuario'].",
						fecha_actualizacion='".date("Y-m-d H:i:s")."'
					WHERE id = $_POST[vista_id_buque]
						
					";
					}else{
					die("arqueo_bruto_no_coresponde");
					}	
					
			}else{
			die("NoActualizoCall_sign");
			}
	}else{
	die("NoActualizoMatricula");
	}


if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Actualizado");
?>









