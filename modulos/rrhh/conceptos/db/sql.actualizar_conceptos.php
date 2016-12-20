<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/*foreach($_POST as $variable => $valor){
	$_POST[$variable] = utf8_decode($_POST[$variable]);
	//echo $_POST[$variable]." ";
}*/

//Convirtiendo los limite_inf y limite_sup para poder actualizar en la base de datos
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
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_concepto) 
			FROM 
				conceptos
			WHERE
				upper(descripcion) like '".trim(strtoupper($_POST['conceptos_db_descripcion_concepto']))."'
			AND
				id_concepto <> $_POST[conceptos_db_id_concepto]
			AND 
				id_organismo = $_SESSION[id_organismo]	
";

$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				UPDATE 
					conceptos
				SET
					descripcion = trim('$_POST[conceptos_db_descripcion_concepto]'),
					asignacion_deduccion = '$_POST[conceptos_db_ad]',
					limite_inf = '$_POST[conceptos_db_limite_inferior]',
					limite_sup = '$_POST[conceptos_db_limite_superior]',
					observacion = '$_POST[conceptos_db_comentario]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$_POST[conceptos_db_fechact]',
					estatus = '$_POST[conceptos_db_aplica]',
					num_orden = '$_POST[conceptos_db_num_orden]'
				WHERE	
					id_concepto = $_POST[conceptos_db_id_concepto]
";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	//
	$sql = "UPDATE concep_cal_rrhh SET estatu='0' WHERE id_conceptos = $_POST[conceptos_db_id_concepto] AND id_organismo = $_SESSION[id_organismo]";
	$bus=& $conn->Execute($sql);
	for ($i=0; $i<$_POST['conceptos_db_tam']; $i++){
		if($_POST['conceptos_db_calculo'.$i]=='true'){

			$sql = "SELECT  
						COUNT(id_concep_cal_rrhh)
					FROM
						concep_cal_rrhh
					INNER JOIN
						conceptos
					ON
						concep_cal_rrhh.id_conceptos = conceptos.id_concepto
					INNER JOIN
						calculo_rrhh
					ON
						concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
					WHERE 
						concep_cal_rrhh.id_conceptos = $_POST[conceptos_db_id_concepto]
					AND
						calculo_rrhh.id_calculo_rrhh = '".$_POST['conceptos_db_valor'.$i]."'
					AND
						calculo_rrhh.id_organismo = $_SESSION[id_organismo]
					";
			$bus=& $conn->Execute($sql);
			$bus= substr($bus,7,2);
			if($bus!=0){
				$sql = "UPDATE concep_cal_rrhh SET estatu='1' WHERE id_conceptos = $_POST[conceptos_db_id_concepto] AND id_calculo_rrhh = '".$_POST['conceptos_db_valor'.$i]."' AND id_organismo = $_SESSION[id_organismo]";
			}
			if($bus==0){
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
						 '$_POST[conceptos_db_id_concepto]',
						 '".$_POST['conceptos_db_valor'.$i]."',
						 '1',
						 '$_SESSION[id_organismo]'
						 )";
			}
			$bus=& $conn->Execute($sql);
		}
		
	}
	//
	echo ("Actualizado");
}
}
if ($row!=0){
	echo'Existe';
}
?>