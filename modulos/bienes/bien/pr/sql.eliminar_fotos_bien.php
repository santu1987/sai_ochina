<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$total = $_POST['fotos_bien_pr_num'];
for($i=1; $i<=$total; $i++){
	
		if ($_POST["eliminar".$i]!=''){ 
			$sql = "DELETE from fotos_bienes WHERE upper(nombre) = '".strtoupper($_POST["eliminar_nombre_foto".$i])."'";
			$row=& $conn->Execute($sql);
			unlink ("../../../../imagenes/bienes/".$_POST["eliminar_nombre_foto".$i]);
		}
}
echo "<script>parent.document.form_foto.action='modulos/bienes/bien/pr/vista_previa.php';
parent.document.form_foto.fotos_bien_pr_limpiar.onclick();</script>";
?>