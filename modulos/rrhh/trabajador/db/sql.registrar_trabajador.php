<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$cedula=$_POST['trabajador_db_nacionalidad']."".$_POST['trabajador_db_cedula'];
$fecha=date("Y-m-d H:m:s");
$tele=$_POST['trabajador_db_area_telef']."-".$_POST['trabajador_db_numero_telef'];
$tele_eme=$_POST['trabajador_db_area_cel']."-".$_POST['trabajador_db_numero_cel'];
if(!$sidx) $sidx =1;

$id_entrevista = '';
$entrevista = '';
if($_POST['trabajador_bd_id_entrevista']!=''){
	$id_entrevista = "'".$_POST['trabajador_bd_id_entrevista']."',";
	$entrevista = " id_entrevista, ";
}
$Sql="
			SELECT 
				count(id_persona) 
			FROM 
				persona
			WHERE
				cedula like '".trim($cedula)."'
			AND 
				id_organismo = $_SESSION[id_organismo]
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					persona
					(
						id_organismo,
						cedula,
						apellido,
						nombre,
						estado_civil,
						observaciones,
						ultimo_usuario,
						fecha_actualizacion,
						$entrevista
						sexo,
						estatus_eliminar
						
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$cedula',
						'$_POST[trabajador_db_apellido]',
						'$_POST[trabajador_db_nombre]',
						'$_POST[trabajador_db_estado_civil]',
						'$_POST[trabajador_db_observacion]',
						'$_SESSION[id_usuario]',
						'$fecha',
						$id_entrevista
						'$_POST[trabajador_db_sexo]',
						'1'
					)
			";

if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$Sql_2="
			SELECT 
				id_persona 
			FROM 
				persona
			WHERE
				cedula='$cedula'
			AND 
				id_organismo = $_SESSION[id_organismo]	
		";
	$row_2=& $conn->Execute($Sql_2);
	$id_per= $row_2->fields("id_persona");
	$sql_2="INSERT INTO 
					trabajador
					(
						id_organismo,
						id_persona,
						direccion_habitacion,
						telefono,
						telefono_emergencia,
						fecha_nacimiento,
						lugar_nacimiento,
						id_ps_nacimiento,
						ultimo_usuario,
						fecha_actualizacion,
						id_unidad,
						id_cargo,
						id_mn_habitacion,
						asignaciones,
						email,
						id_tipo_nomina,
						fecha_ingreso,
						anos_servicios
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$id_per',
						'$_POST[trabajador_db_direccion]',
						'$tele',
						'$tele_eme',
						'$_POST[trabajador_db_fecha_nac]',
						'$_POST[trabajador_db_lugar_nac]',
						'$_POST[trabajador_db_pais_nac]',
						'$_SESSION[id_usuario]',
						'$fecha',
						'$_POST[trabajador_db_id_area]',
						'$_POST[trabajador_db_id_cargo]',
						'$_POST[trabajador_db_municipio_ubica]',
						'$_POST[trabajador_db_asignacion]',
						'$_POST[trabajador_db_email]',
						'$_POST[trabajador_bd_id_tipo_nomina]',
						'$_POST[trabajador_db_fecha_ingreso]',
						'$_POST[trabajador_db_anos_servicios]'
					)
			";
		if ($conn->Execute($sql_2) == false) 
		{
			echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
		}
		else
		{
			echo ("Registrado");
		}
}
}
if ($row!=0){
	echo'Existe';
}
?>