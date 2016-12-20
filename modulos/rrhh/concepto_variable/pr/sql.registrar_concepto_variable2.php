<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$porcentaje= str_replace(',','.',$_POST['concepto_variable_pr_porcentaje']);
$where = " WHERE 1 = 1 ";
if(!$sidx) $sidx =1;
//Convirtiendo el monto para poder guardarlo en la base de datos
$_POST['concepto_variable_pr_monto'] = str_replace('.','',$_POST['concepto_variable_pr_monto']);
$_POST['concepto_variable_pr_monto'] = str_replace(',','.',$_POST['concepto_variable_pr_monto']);
//
$porcentaje = '0.00';
if($_POST['concepto_variable_pr_porcentaje']!=''){
	$porcentaje = $_POST['concepto_variable_pr_porcentaje'];
	$porcentaje = str_replace(',','.',$porcentaje);
}

//
$sql = " SELECT
			COUNT(id_concepto_variable) as exi
		FROM
			concepto_variable
		INNER JOIN
			conceptos
		ON
			concepto_variable.id_concepto = conceptos.id_concepto
		INNER JOIN
			tipo_nomina
		ON
			concepto_variable.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			concepto_variable.id_trabajador = trabajador.id_trabajador
		WHERE
			concepto_variable.id_tipo_nomina = $_POST[id_tipo_nomina]
		AND
			conceptos.id_concepto = $_POST[concepto_variable_pr_id_concepto]
		AND
			trabajador.id_trabajador = $_POST[concepto_variable_pr_id_trabajador]
		";	
$exi=& $conn->Execute($sql);
$exi= $exi->fields("exi");
if($exi==0){
	$sql = "INSERT INTO 
				concepto_variable 
				(id_organismo, 
				 id_concepto,
				 porcentaje,
				 monto,
				 observacion,
				 estatus,
				 id_tipo_nomina,
				 id_trabajador,
				 ultimo_usuario, 
				 fecha_actualizacion) 
			VALUES 
			('$_SESSION[id_usuario]',
			'$_POST[concepto_variable_pr_id_concepto]',
			'$porcentaje',
			'$_POST[concepto_variable_pr_monto]',
			'$_POST[concepto_variable_pr_comentario]',
			'1',
			'$_POST[id_tipo_nomina]',
			'$_POST[concepto_variable_pr_id_trabajador]',
			'$_SESSION[id_usuario]',
			'$_POST[concepto_variable_pr_fechact]')";
		
//
//
if ($conn->Execute($sql) == false) {
	echo $sql;
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
}

if($exi!=0){
	echo ("Existe");
}
?>