<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$fecha = date("Y-m-d H:i:s");
$clave = "";
$ced=$_POST['usuario_db_vista_cedula'];
if ($_POST['usuario_db_vista_clave']!=""){
	$clave = "usuario_db_vista_clave = md5(".$_POST['usuario_db_vista_clave']."),";
}
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$usu=strtoupper($_POST['usuario_db_vista_usuario']);
$SQL = "select count(id_usuario) from usuario where cedula='$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]' and id_usuario<>'$_POST[vista_id_usuario]'";
$ver=$conn->Execute($SQL);
$cad=substr($ver, 7,2);
$SQL2 = "select count(id_usuario) from usuario where upper(usuario) like '$usu' and id_usuario<>'$_POST[vista_id_usuario]'";
$ver2=$conn->Execute($SQL2);
$cad2=substr($ver2, 7,2);
if ($_POST['nomfoto']=="")
$foto = $_POST['vie_nomfoto'];
else{
$foto =$_POST['usuario_db_vista_nacionalidad'].$_POST['usuario_db_vista_cedula']."_".$_POST['nomfoto'].".png";
if ($_POST['vie_nomfoto']!="sombra.png")
unlink("../../../../imagenes/foto/".$_POST['vie_nomfoto']);
}

if(($cad==0)&&($cad2==0)){
 	$sql = "	
				UPDATE usuario  
					 SET
						nombre = '$_POST[usuario_db_vista_nombre]',
						usuario = '$_POST[usuario_db_vista_usuario]',
						apellido = '$_POST[usuario_db_vista_apellido]',
						cedula ='$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]',
						foto='$foto',
						fecha_desde = '$_POST[db_vista_usuario_fecha_desde]',
						fecha_hasta = '$_POST[db_vista_usuario_fecha_hasta]', 
						comentario = '$_POST[usuario_db_vista_obs]',
						ultimo_usuario = ".$_SESSION['id_usuario'].",
						fecha_actualizacion ='".$fecha."',
						id_unidad_ejecutora = '$_POST[usuario_db_vista_unidad_ejecutora]'
					WHERE id_usuario = $_POST[vista_id_usuario]
						
			";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
//	'$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]'
//$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]
}
else
{	
		die ('Actualizado');
}}
if ($cad>0){
	echo 'No Actualizo';
}elseif ($cad2>0){
	 echo 'No_Actualizo';
}
?>