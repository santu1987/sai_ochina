<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

foreach($_POST as $variable => $valor){
	$_POST[$variable] = htmlentities($_POST[$variable]);
	//echo $_POST[$variable]." ";
}

//Convirtiendo los limite_inf y limite_sup para poder guardar en la base de datos
$_POST['conceptos_db_limite_inferior'] = str_replace('.','',$_POST['conceptos_db_limite_inferior']);
$_POST['conceptos_db_limite_inferior'] = str_replace(',','.',$_POST['conceptos_db_limite_inferior']);
$_POST['conceptos_db_limite_superior'] = str_replace('.','',$_POST['conceptos_db_limite_superior']);
$_POST['conceptos_db_limite_superior'] = str_replace(',','.',$_POST['conceptos_db_limite_superior']);
//
/*if ($_POST['conceptos_db_sso']=='true')
	$cal_sso = 1;
else
	$cal_sso = 0;
if($_POST['conceptos_db_lph']=='true')
	$cal_lph = 1;
else
	$cal_lph = 0;
if($_POST['conceptos_db_salario']=='true')
	$cal_salario = 1;
else
	$cal_salario = 0;	
if($_POST['conceptos_db_utilidades']=='true')
	$cal_utilidades = 1;
else
	$cal_utilidades = 0;
if($_POST['conceptos_db_prestaciones']=='true')
	$cal_prestaciones = 1;
else
	$cal_prestaciones = 0;
if($_POST['conceptos_db_forzozo']=='true')
	$cal_forzozo = 1;
else
	$cal_forzozo = 0;
if($_POST['conceptos_db_isrl']=='true')
	$cal_isrl = 1;
else
	$cal_isrl = 0;*/
//
$Sql="
			SELECT 
				count(id_concepto) 
			FROM 
				conceptos
			WHERE
				upper(descripcion) like '".trim(strtoupper($_POST['conceptos_db_descripcion_concepto']))."'
			AND 
				id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					conceptos
					(
						id_organismo,
						descripcion,
						asignacion_deduccion,
						limite_inf,
						limite_sup,
						observacion,
						ultimo_usuario,
						fecha_actualizacion,
						estatus,
						num_orden
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						trim('$_POST[conceptos_db_descripcion_concepto]'),
						'$_POST[conceptos_db_ad]',
						'$_POST[conceptos_db_limite_inferior]',
						'$_POST[conceptos_db_limite_superior]',
						'$_POST[conceptos_db_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[conceptos_db_fechact]',
						'$_POST[conceptos_db_aplica]',
						'$_POST[conceptos_db_num_orden]'
					)
			";
 		
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$sql = "SELECT	
				id_concepto
			FROM 
				conceptos
			WHERE
				upper(conceptos.descripcion) like '".strtoupper(trim($_POST['conceptos_db_descripcion_concepto']))."' 
			AND
				conceptos.id_organismo = $_SESSION[id_organismo]
	";
	$bus=& $conn->Execute($sql);
	$id_concepto = $bus->fields("id_concepto");
	for ($i=0; $i<$_POST['conceptos_db_tam']; $i++){
		if($_POST['conceptos_db_calculo'.$i]=='true'){
			$sql = "INSERT INTO 
					concep_cal_rrhh
					(
						id_conceptos,
						id_calculo_rrhh,
						estatu,
						id_organismo
					) 
					VALUES
					(
						'$id_concepto',
						'".$_POST['conceptos_db_valor'.$i]."',
						'1',
						'$_SESSION[id_organismo]'
					)";
			$bus=& $conn->Execute($sql);		
		}
	}
	echo ("Registrado");
}
}
if ($row!=0){
	echo'Existe';
}
?>