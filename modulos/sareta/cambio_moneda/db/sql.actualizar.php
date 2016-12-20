<?php
	
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
session_start();

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql3 = "SELECT obs FROM sareta.cambio_moneda WHERE  id=".$_POST['id'];
	
if (!$conn->Execute($sql3)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql3);
$comentario=$row->fields("obs");

$valor =str_replace('.','',$_POST['sareta_cambio_moneda_db_valor']);
$dia=substr ($_POST['cambio_moneda_db_fecha_cambio'],0,2);
	$paso=substr ($_POST['cambio_moneda_db_fecha_cambio'],3);
	$mes=substr ($paso,0,2);
	$paso=substr ($paso,3);
	$ano=substr ($paso,0,4);
	$sql2 = "SELECT id FROM sareta.cambio_moneda WHERE  id_moneda=".$_POST['id_moneda']." and valor =".str_replace(',','.',$valor)." and fecha_cambio::text like '".$ano."-".$mes."-".$dia."%' ";
	
if (!$conn->Execute($sql2)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row= $conn->Execute($sql2);
	$id=$row->fields("id");
	
if(($_POST['sareta_bandera_db_vista_observacion']!=$comentario || $_POST['sareta_bandera_db_vista_observacion']==$comentario ) && ($id==$_POST['id'] || $id==$_POST[''] ))
{

					$sql = "
							 UPDATE sareta.cambio_moneda
								 SET
							 id_moneda=".$_POST['id_moneda'].",
							 valor=".str_replace(',','.',$valor).",
							 fecha_cambio='".str_replace('/','-',$_POST['cambio_moneda_db_fecha_cambio']).date(" H:i:s")."',
							 obs='".$_POST['cambio_moneda_db_comentario']."',
							 ultimo_usuario =".$_SESSION['id_usuario'].",
							 fecha_actualizacion='".date("Y-m-d H:i:s")."'
								WHERE id=$_POST[id]
								
								";
			
	if (!$conn->Execute($sql)){ 
		die ('Error al Actualizar: '.$conn->ErrorMsg());
	
	}else{
		die("Actualizado");
		}
}else{
	die('Existe');
}
?>