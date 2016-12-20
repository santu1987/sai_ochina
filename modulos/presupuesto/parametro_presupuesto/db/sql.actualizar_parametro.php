<?php
session_start();
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");


$sql = "SELECT * FROM parametros_presupuesto WHERE id_organismo<> ".$_SESSION['id_organismo']." AND ano='".date("Y")."'";
$row=& $conn->Execute($sql);

if($row->EOF)
	$sql = "	
			UPDATE 
				parametros_presupuesto
   			SET 
				fecha_cierre_anteproyecto='".str_replace("/","-",$_POST[parametro_cierre_antepresupuesto_db_fecha_cierre_mes])."'
 WHERE id_organismo=".$_SESSION['id_organismo']." AND ano ='".date("Y")."'";

if ($conn->Execute($sql) === false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	//echo $sql;
}
else
{
	echo 'Actualizado';
}
?>