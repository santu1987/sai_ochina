<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$cedula="$_POST[entrevista_db_nacionalidad]"."$_POST[entrevista_db_cedula]";
$db=dbconn("pgsql");
$id_curri=$_POST['entrevista_db_id_curriculum'];
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_entrevista) 
			FROM 
				entrevista
			WHERE
				upper(entrevista.cedula) like '".trim(strtoupper($_POST['entrevista_db_cedula']))."'
			AND 
				entrevista.id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					entrevista
					(
						id_organismo,
						id_curriculos,
						cedula,
						nombre,
						observaciones,
						ultimo_usuario,
						fecha_actualizacion,
						fecha_entrevista
					) 
					VALUES
					(
						".$_SESSION["id_organismo"].",
						'$id_curri',
						'$_POST[entrevista_db_cedula]',
						trim('$_POST[entrevista_db_nombre_entrevista]'),
						'$_POST[entrevista_db_comentario]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."',
						'$_POST[entrevista_db_fecha]'
					)
			";
		
if ($conn->Execute($sql) == false) {
	echo 'Error';
	//echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
}
if ($row!=0){
	echo'Existe';
}
?>