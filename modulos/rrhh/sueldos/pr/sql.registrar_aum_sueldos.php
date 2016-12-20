<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				id_aumento_sueldo,
				fecha_aumento,
				sueldo_aumento,
				observacion
			FROM 
				aumento_sueldo
			INNER JOIN
				trabajador
			ON
				aumento_sueldo.id_trabajador = trabajador.id_trabajador
			WHERE
				aumento_sueldo.id_trabajador = $_POST[aumento_sueldos_pr_id_trabajador]
			AND 
				aumento_sueldo.id_organismo = $_SESSION[id_organismo]	
";
if($_POST['aumento_sueldos_pr_comentario']=='')
	$comentario = "''";
if($_POST['aumento_sueldos_pr_comentario']!='')
	$comentario = $_POST['aumento_sueldos_pr_comentario'];
$_POST['aumento_sueldos_pr_nuevo_sueldo'] = str_replace('.','',$_POST['aumento_sueldos_pr_nuevo_sueldo']);
$_POST['aumento_sueldos_pr_nuevo_sueldo'] = str_replace(',','.',$_POST['aumento_sueldos_pr_nuevo_sueldo']);
$sueldo = $_POST['aumento_sueldos_pr_nuevo_sueldo'];
//$sueldo = str_replace('.','',$sueldo);
//$sueldo = str_replace(',','.',$sueldo);
$sueldo = '"'.$sueldo.'"';
$row=& $conn->Execute($Sql); 
if($row->fields("id_aumento_sueldo")==''){
	$sql = "INSERT INTO aumento_sueldo (id_organismo, id_trabajador, fecha_aumento, sueldo_aumento, observacion, ultimo_usuario, fecha_actualizacion) values ($_SESSION[id_organismo], $_POST[aumento_sueldos_pr_id_trabajador],'{".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento'].",".$_POST['aumento_sueldos_pr_fecha_aumento']."}','{".$sueldo.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0}','{".$comentario.",'','','','','','','','','','','','','','','','','','',''}',".$_SESSION[id_usuario].",'".$_POST['aumento_sueldos_pr_fechact']."')";
}
if($row->fields("id_aumento_sueldo")!=''){
	$cad = $row->fields("sueldo_aumento");
	$cad2 = $row->fields("fecha_aumento");
	$cad3 = $row->fields("observacion");
	$cad3 = str_replace('{','',$cad3);
	$cad3 = str_replace('}','',$cad3);
	$cad = str_replace('{','',$cad);
	$cad = str_replace('}','',$cad);
	$arreglo = split(',',$cad);
	$arreglo2 = split('"',$cad2);
	$arreglo3 = split(',',$cad3);
	$c=-1;
	//
	/*for($i=0; $i<=19; $i++){
		if($arreglo[$i]=="'"){
			$pos = $i;
			break;
		}
	}
	for($i=0; $i<=19; $i++){
	}*/
	//
	$pos=-1;
	for($i=0;$i<=19;$i++){
		//if($i%2!=0){
			if($arreglo[$i]=='0'){
				$pos=$i;
				break;
			}
		//}
	}
	for($i=0;$i<=19;$i++){
			$tam = strlen($arreglo[$i]);
			$sueldo = $arreglo[$i];
			if($arreglo3[$i]=="'")
				$arreglo3[$i] = "''";
			if ($i!=$pos){
				$suel.= '"'.$sueldo.'",'; 
				$observacion.= $arreglo3[$i].',';
			}
			if ($i==$pos){
				$sueldo = $_POST['aumento_sueldos_pr_nuevo_sueldo'];
				$suel.= '"'.$sueldo.'",';
				$observacion.= '"'.$comentario.'",';
				
			}
			
		//}
	}
	
	//
	/*for($i=0; $i<=19; $i++){
		if($arreglo3[$i]=="'"){
			$pos = $i;
			break;
		}
	}*/
	for($i=0; $i<=39; $i++){
		if($i%2!=0){
			$c++;
			if($c!=$pos){
				$fecha.= '"'.$arreglo2[$i].'",';
			}
			if($c==$pos){
				$fecha.= $_POST['aumento_sueldos_pr_fecha_aumento'].",";
			}
		}
	}
	
	//
	$suel = substr($suel, 0, strlen($suel)-1);
	$fecha = substr($fecha, 0, strlen($fecha)-1);
	$observacion = substr($observacion, 0, strlen($observacion)-1);
	$sql = "UPDATE aumento_sueldo SET fecha_aumento='{".$fecha."}', sueldo_aumento='{".$suel."}', observacion='{".$observacion."}' WHERE id_aumento_sueldo = ".$row->fields("id_aumento_sueldo");
	//echo $sql;
}
	
//$row= substr($row,7,2);
//if ($row==0){
if($pos!=-1){	
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
}
if($pos==-1)
	echo "Maximo";
	
	
?>