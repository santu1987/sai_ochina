<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_usuario) 
			FROM 
				usuario
";

$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}
$count++;
$usu=strtoupper($_POST['usuario_db_vista_usuario']);
$SQL = "select count(id_usuario) from usuario where cedula='$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]'";
$ver= $conn->Execute($SQL);
$cad=substr($ver, 7,2);
$SQL2 = "select count(id_usuario) from usuario where upper(usuario) like '$usu'";
$ver2= $conn->Execute($SQL2);
$cad2=substr($ver2, 7,2);
if ($_POST['nomfoto']=="")
$foto = "sombra.png";
else
$foto =$_POST['usuario_db_vista_nacionalidad'].$_POST['usuario_db_vista_cedula']."_".$_POST['nomfoto'].".png";
 if (($cad==0)&&($cad2==0)){
	$sql = "	
				INSERT INTO 
					usuario 
					(
						usuario,
						nombre,
						apellido,
						cedula,
						estatus,
						clave,
						foto,
						fecha_desde,
						fecha_hasta,
						comentario,
						id_unidad_ejecutora
					) 
					VALUES
					(
						'$_POST[usuario_db_vista_usuario]',
						'$_POST[usuario_db_vista_nombre]',
						'$_POST[usuario_db_vista_apellido]',
						'$_POST[usuario_db_vista_nacionalidad]$_POST[usuario_db_vista_cedula]',
						1,
						md5('$_POST[usuario_db_vista_clave]'),
						'$foto',
						'$_POST[db_vista_usuario_fecha_desde]',
						'$_POST[db_vista_usuario_fecha_hasta]',
						'$_POST[usuario_db_vista_obs]',
						'$_POST[usuario_db_vista_unidad_ejecutora]'
					)
			";
	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
}
if ($cad>0){
	echo'NO Registro';
}else{
if ($cad2>0){
	// echo  $sql;
	echo 'No_Registro';
}}
?>