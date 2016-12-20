<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$procesada=$_POST['precesada_modifica'];
if($procesada=="No"){ $procesada=0;}
else {$procesada=1;}
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$exi = 0;				
$Sql="
			SELECT 
				count(id_nominas) 
			FROM 
				nominas
			WHERE 
				id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row!=0){
	$sql = "	
				UPDATE 
					nominas
				SET
					desde='$_POST[nominas_db_fecha_desdem]',
					hasta='$_POST[nominas_db_fecha_hastam]',
					procesada='$procesada',
					ultimo_usuario=".$_SESSION['id_usuario'].", 
					fecha_actualizacion='".date("Y-m-d H:i:s")."'
				WHERE
					id_nominas = $_POST[nominas_db_id_nominas]
			";	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
}
if ($row=0){
	echo'Existe';
}
?>