<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if($_POST['elegido']==0){
	echo "<option value=''>-- SELECCIONE --</option>";
}
else{
	$sql="SELECT *FROM municipio WHERE id_es=$_POST[elegido] ORDER BY id_mn";
	$rs_municipio =& $conn->Execute($sql);
	while (!$rs_municipio->EOF){
		$opt_municipio.="<option value='".$rs_municipio->fields("id_mn")."' >".$rs_municipio->fields("nom_mn")."</option>";
		$rs_municipio->MoveNext();
	}
		$rpta= "
		$opt_municipio
		";	
	echo $rpta;	
}
?>