<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$fecha=date("Y-m-d H:m:s");
$cedula=$_POST['trabajador_db_nacionalidad']."".$_POST['trabajador_db_cedula'];
$telefono=$_POST['trabajador_db_area_telef']."-".$_POST['trabajador_db_numero_telef'];
$cel=$_POST['trabajador_db_area_cel']."-".$_POST['trabajador_db_numero_cel'];
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id_persona) 
			FROM 
				persona
			WHERE 
				id_persona='$_POST[trabajador_db_id_persona]'
			AND
				id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row!=0){
	$sql = "	
				UPDATE 
					persona
				SET
					cedula = '$cedula',
					nombre = trim('$_POST[trabajador_db_nombre]'),
					apellido = '$_POST[trabajador_db_apellido]',
					estado_civil = '$_POST[trabajador_db_estado_civil]',
					observaciones = '$_POST[trabajador_db_observacion]',
					ultimo_usuario = '$_SESSION[id_usuario]',
					fecha_actualizacion = '$fecha',
					id_entrevista = '$_POST[trabajador_bd_id_entrevista]',
					sexo= '$_POST[trabajador_db_sexo]'
				WHERE	
					id_persona = $_POST[trabajador_db_id_persona]
";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	$sql_2 = "	
				UPDATE 
					trabajador
				SET
					id_persona = '$_POST[trabajador_db_id_persona]',
					direccion_habitacion = '$_POST[trabajador_db_direccion]',
					email = '$_POST[trabajador_db_email]',
					telefono = '$telefono',
					telefono_emergencia='$cel',
					fecha_nacimiento = '$_POST[trabajador_db_fecha_nac]',
					lugar_nacimiento = '$_POST[trabajador_db_lugar_nac]',
					id_ps_nacimiento='$_POST[trabajador_db_id_pais]',
					id_unidad = '$_POST[trabajador_db_id_area]',
					id_cargo = '$_POST[trabajador_db_id_cargo]',
					fecha_actualizacion = '$fecha',
					ultimo_usuario = '$_SESSION[id_usuario]',
					id_tipo_nomina= '$_POST[trabajador_bd_id_tipo_nomina]',
					asignaciones='$_POST[trabajador_db_asignacion]',
					fecha_ingreso='$_POST[trabajador_db_fecha_ingreso]',
					anos_servicios='$_POST[trabajador_db_anos_servicios]'
				WHERE	
					id_persona = $_POST[trabajador_db_id_persona]
		";
	if ($conn->Execute($sql_2) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
	}
	else
	{
		echo ("Actualizado");
	}
}
}
if ($row==0){
	echo'Existe';
}
?>