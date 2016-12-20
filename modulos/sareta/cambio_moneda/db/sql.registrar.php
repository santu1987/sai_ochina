<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$valor =str_replace('.','',$_POST['sareta_cambio_moneda_db_valor']);
	
	$dia=substr ($_POST['cambio_moneda_db_fecha_cambio'],0,2);
	$paso=substr ($_POST['cambio_moneda_db_fecha_cambio'],3);
	$mes=substr ($paso,0,2);
	$paso=substr ($paso,3);
	$ano=substr ($paso,0,4);
	$sql2 = "SELECT id FROM sareta.cambio_moneda WHERE  id_moneda=".$_POST['id_moneda']." and valor =".str_replace(',','.',$valor)." and fecha_cambio::text like '".$ano."-".$mes."-".$dia."%' ";
	
if (!$conn->Execute($sql2)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql2);
	if($row->EOF){

					$sql = "	
							INSERT INTO 
								sareta.cambio_moneda
								  (  
								  id_moneda,
								  valor,
								  fecha_cambio,
								  obs,
								  ultimo_usuario,
								  fecha_creacion,
								  fecha_actualizacion
								  )
							 VALUES (
							 ".$_POST['id_moneda'].",
							 ".str_replace(',','.',$valor).",
							 '".str_replace('/','-',$_POST['cambio_moneda_db_fecha_cambio']).date(" H:i:s")."',
							 '".$_POST['cambio_moneda_db_comentario']."',
							 ".$_SESSION['id_usuario'].",
							 '".date("Y-m-d H:i:s")."',
							 '".date("Y-m-d H:i:s")."'
				
									)
								";
	
				
		if (!$conn->Execute($sql)) 
			die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
		else
	
			echo 'Registrado';
	}else{
		die('Existe');
	}
?>