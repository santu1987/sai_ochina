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
//Convirtiendo el monto para poder guaradalo en la base de datos
$_POST['concepto_variable_pr_monto'] = str_replace('.','',$_POST['concepto_variable_pr_monto']);
$_POST['concepto_variable_pr_monto'] = str_replace(',','.',$_POST['concepto_variable_pr_monto']);	
//
$porcentaje = $_POST['concepto_variable_pr_porcentaje'];
$porcentaje = str_replace(',','.',$porcentaje);
//

//

if($_POST['concepto_variable_pr_id_concepto_variable']!='')
	$id_concepto_variable = $_POST['concepto_variable_pr_id_concepto_variable'];
/*if($_POST['concepto_variable_pr_id_concepto_variable']==''){
	$sql = "INSERT INTO 
				concepto_variable 
				(id_organismo, 
				 id_concepto, 
				 ultimo_usuario, 
				 fecha_actualizacion) 
			VALUES 	
				('$_SESSION[id_usuario]',
				'$_POST[concepto_variable_pr_id_concepto]',
				'$_SESSION[id_usuario]',
				'$_POST[concepto_variable_pr_fechact]' )";
	//echo $sql;
	if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());

	$sql = "SELECT 
				id_concepto_variable 
			FROM
				concepto_variable
			INNER JOIN
				organismo
			ON
				concepto_variable.id_organismo = organismo.id_organismo
			WHERE
				concepto_variable.id_organismo = $_SESSION[id_organismo]	
			ORDER BY
				id_concepto_variable DESC";
				
	$row=& $conn->Execute($sql);
	$id_concepto_variable = $row->fields('id_concepto_variable');
}	
//
//
	if($_POST['concepto_variable_pr_id_concepto_variable']!=''){
	$sql = "UPDATE 
				concepto_variable 
			SET 
				estatus = '2' 
			WHERE 
				id_tipo_nomina = ".$_POST['id_tipo_nomina']." 
			AND 
				id_concepto_variable = ".$_POST['concepto_variable_pr_id_concepto_variable']." 
			AND 
				id_organismo = ".$_SESSION['id_organismo'];
	if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());
	}
*/	
//
//

$trabajadores = $_POST['trabajadorSelect'];
$partem = explode(",",$trabajadores); 
$n = count($partem);

if($trabajadores!=''){
	for($i=0; $i<$n; $i++){
		$ver = "SELECT 
					count(trabajador.id_trabajador) as exi
				FROM 
					trabajador
				INNER JOIN
					concepto_variable
				ON
					trabajador.id_trabajador = concepto_variable.id_trabajador
				WHERE 
					trabajador.id_trabajador = ".$partem[$i]."
				AND
					concepto_variable.id_tipo_nomina = $_POST[id_tipo_nomina]
				AND
					concepto_variable.id_concepto = $_POST[concepto_variable_pr_id_concepto]
				AND
					trabajador.id_organismo = $_SESSION[id_organismo]";
		$row =& $conn->Execute($ver);			
		if($row->fields("exi")==0){
			$sql = "INSERT INTO 
						concepto_variable 
						(id_concepto,
						 id_trabajador, 
						 id_tipo_nomina, 
						 estatus, 
						 id_organismo, 
						 porcentaje,
						 monto,
						 observacion,
						 ultimo_usuario,
						 fecha_actualizacion) 
					VALUES ('$_POST[concepto_variable_pr_id_concepto]',
							$partem[$i],
							'$_POST[id_tipo_nomina]',
							'1',
							'$_SESSION[id_organismo]',
							'$porcentaje',
							'$_POST[concepto_variable_pr_monto]',
							'$_POST[concepto_variable_pr_comentario]',
							'$_SESSION[id_usuario]',
							'$_POST[concepto_variable_pr_fechact]')";
			
		}
		if($row->fields("exi")>0){
			$sql = "UPDATE 
						concepto_variable
					SET 
						estatus = '1', 
						ultimo_usuario = '$_SESSION[id_usuario]',
						fecha_actualizacion = '$_POST[concepto_variable_pr_fechact]'
					WHERE 
						id_organismo = '$_SESSION[id_organismo]' 
					AND 
						id_tipo_nomina = $_POST[id_tipo_nomina] 
					AND 
						id_trabajador = $partem[$i] 
					";
		}
		
		//echo $sql;
	//
	if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());
	//
	}
	
}
echo "Registrado";


/*$Sql="
			SELECT 
				count(id_concepto_variable) 
			FROM 
				concepto_variable
		".$where."
			AND
				id_trabajador = $_POST[concepto_variable_pr_id_trabajador]
			AND 
				concepto_variable.id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					concepto_variable 
					(
						id_organismo,
						id_trabajador,
						id_concepto,
						id_frecuencia_concepto,
						porcentaje,
						monto,
						observacion,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[concepto_variable_pr_id_trabajador]',
						'$_POST[concepto_variable_pr_id_concepto]',
						'$_POST[concepto_variable_pr_id_frecuencia_concepto]',
						'$porcentaje',
						'$_POST[concepto_variable_pr_monto]',
						'$_POST[concepto_variable_pr_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[concepto_variable_pr_fechact]'
					)
			";
if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
}
if ($row!=0){
	echo'Existe';
}*/
?>