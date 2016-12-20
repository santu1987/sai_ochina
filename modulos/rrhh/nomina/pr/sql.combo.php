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
	echo "<option value=''>-- SELECCIONE --</option>";
	$sql="SELECT *FROM 
				nominas 
		  INNER JOIN 
		  		tipo_nomina
		  ON
		  	nominas.id_tipo_nomina=tipo_nomina.id_tipo_nomina
		  WHERE nominas.id_tipo_nomina=$_POST[elegido] 
		  AND procesada=0 
		  ORDER BY id_nominas";
	$rs_nominas =& $conn->Execute($sql);
	while (!$rs_nominas->EOF){
		$fecha_desde=$rs_nominas->fields("desde");
		$fecha_hasta=$rs_nominas->fields("hasta");
		$dia_d=substr($fecha_desde,8,10);
		$mes_d=substr($fecha_desde,5,2);
		$ano_d=substr($fecha_desde,0,4);
		$fecha_d=$dia_d."/".$mes_d."/".$ano_d." ";
		$dia_h=substr($fecha_hasta,8,10);
		$mes_h=substr($fecha_hasta,5,2);
		$ano_h=substr($fecha_hasta,0,4);
		$fecha_hasta=$dia_h."/".$mes_h."/".$ano_h." ";
		$opt_nominas.="<option value='".$rs_nominas->fields("id_nominas")."' >".$rs_nominas->fields("numero_nomina").") ".$fecha_d." - ".$fecha_hasta."</option>";
		$rs_nominas->MoveNext();
	}
		$rpta= "
		$opt_nominas
		";	
	echo $rpta;	
}
?>