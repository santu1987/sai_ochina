<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$fecha=date("Y-m-d H:m:s");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_tipo_concepto) 
			FROM 
				tipo_concepto
			WHERE
				upper(descripcion) like '".trim(strtoupper($_POST['tipo_concepto_db_descripcion_tipo_concepto']))."'
			AND
				id_tipo_concepto <> $_POST[tipo_concepto_db_id_tipo_concepto]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					tipo_concepto
				SET
					descripcion = trim('$_POST[tipo_concepto_db_descripcion_tipo_concepto]'),
					observacion = '$_POST[tipo_concepto_db_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$fecha'
				WHERE	
					id_tipo_concepto = $_POST[tipo_concepto_db_id_tipo_concepto]
";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Actualizado");
}
}
if ($row!=0){
	echo'Existe';
}
?>