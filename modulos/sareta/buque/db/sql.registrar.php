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
if($row->EOF)
	{
	$sql = "SELECT * FROM sareta.buque WHERE call_sign='".$_POST['sareta_buque_db_call_sign']."'";
	if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql);
	if($row->EOF)
			{
			$sql = "SELECT * FROM sareta.ley WHERE 
				id_ley=$_POST[id_ley]";
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF){
									$inicial=$row1->fields("tonelaje_inicial");
									$final=$row1->fields("tonelaje_final");
									
									}
$valor =str_replace('.','',$_POST['sareta_buque_db_rb']);

			if(str_replace(',','.',$valor)>=$inicial && str_replace(',','.',$valor)<=$final){
$sql = "	
				INSERT INTO 
					 sareta.buque
					(
						matricula,
					    call_sign,
					    nombre,
					    id_bandera,
					    r_bruto,
					    id_actividad,
					    id_clase,
					    nacionalidad,
					    pago_anual,
					    id_ley,
					    exonerado,
						comentario,
						ultimo_usuario,
						fecha_creacion,
						fecha_actualizacion
					) 
					VALUES
					(
						upper('$_POST[sareta_buque_db_matricula]'),
						upper('$_POST[sareta_buque_db_call_sign]'),
						upper('$_POST[sareta_buque_db_nombre]'),
						$_POST[id_bandera],
						".str_replace(',','.',$valor).",
						$_POST[id_actividad],
					 	$_POST[id_clase],
						$_POST[buque_db_Nac],
						$_POST[buque_db_Pago_anual],
						$_POST[id_ley],
						$_POST[buque_db_exonerado],
						'$_POST[sareta_buque_db_vista_observacion]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'".date("Y-m-d H:i:s")."'
						
					)";
					}
					else
					{
						die("arqueo_bruto_no_coresponde");
					}
			}else
			{
				die("NoRegistroCall_sign");
			}
	}else
	{
	die("NoRegistroMatricula");
	}


if (!$conn->Execute($sql)) 
	die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
else
	die("Registrado");
?>