<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$frecuencia=$_POST['valor'];
// sql para frecuencia mensual
$ano=date("Y");
if($frecuencia==12){
	if(!$sidx) $sidx =1;
	/*for($i=1;$i<=12;$i++){
	echo $_POST['nominas_db_procesada2'.$i];
	}*/
	$Sql="SELECT
			COUNT(id_tipo_nomina)
		  FROM
		  	nominas
		  WHERE
		  	id_tipo_nomina=$_POST[nominas_db_id_tn]
		  AND
		  	desde>='".$ano."-01-01'
		  AND
		  	hasta<='".$ano."-12-31'
	";
	$row=& $conn->Execute($Sql);
	$row= substr($row,7,2);
	if($row==0){
		for ($i=1;$i<=12;$i++){
			$desde=$_POST['desde_m'.$i];
			$hasta=$_POST['hasta_m'.$i];
			$pro=0;
			$sql = "INSERT INTO 
							nominas
							(
								id_organismo,
								procesada,
								desde,
								hasta,
								numero_nomina,
								ultimo_usuario,
								fecha_actualizacion,
								id_tipo_nomina
							) 
							VALUES
							(
								".$_SESSION["id_organismo"].",
								".$pro.",
								'".$desde."',
								'".$hasta."',
								".$i.",
								".$_SESSION['id_usuario'].",
								'".date("Y-m-d H:i:s")."',
								'$_POST[nominas_db_id_tn]'
							)
					";
			$conn->Execute($sql);	
	 }
	if ($conn->Execute($sql) == false) {
			echo $sql;
			echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
		}
	else
	{
		echo ("Registrado");
	}
	}else{echo "Existe";}
}
// sql para frecuencia quincenal
if($frecuencia==24){
	if(!$sidx) $sidx =1;
	/*for($i=1;$i<=24;$i++){
	echo $_POST['desde_q'.$i];
	echo $_POST['hasta_q'.$i];
	}*/
	$Sql="SELECT
			COUNT(id_tipo_nomina)
		  FROM
		  	nominas
		  WHERE
		  	id_tipo_nomina=$_POST[nominas_db_id_tn]
		  AND
		  	desde>='".$ano."-01-01'
		  AND
		  	hasta<='".$ano."-12-31'
	";
	$row=& $conn->Execute($Sql);
	$row= substr($row,7,2);
	if($row==0){
		for ($i=1;$i<=24;$i++){
			$desde=$_POST['desde_q'.$i];
			$hasta=$_POST['hasta_q'.$i];
			$pro=0;
			$sql = "INSERT INTO 
							nominas
							(
								id_organismo,
								procesada,
								desde,
								hasta,
								numero_nomina,
								ultimo_usuario,
								fecha_actualizacion,
								id_tipo_nomina
							) 
							VALUES
							(
								".$_SESSION["id_organismo"].",
								".$pro.",
								'".$desde."',
								'".$hasta."',
								".$i.",
								".$_SESSION['id_usuario'].",
								'".date("Y-m-d H:i:s")."',
								'$_POST[nominas_db_id_tn]'
							)
					";
			$conn->Execute($sql);	
	 }
	if ($conn->Execute($sql) == false) {
			echo $sql;
			echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
		}
	else
	{
		echo ("Registrado");
	}
	}else{echo "Existe";}
}
?>