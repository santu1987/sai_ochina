<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$porcentaje= str_replace(',','.',$_POST['conceptos_fijos_pr_porcentaje']);
$where = " WHERE 1 = 1 ";
if(!$sidx) $sidx =1;
//Convirtiendo el monto para poder guardar en la base de datos
$_POST['conceptos_fijos_pr_monto'] = str_replace('.','',$_POST['conceptos_fijos_pr_monto']);
$_POST['conceptos_fijos_pr_monto'] = str_replace(',','.',$_POST['conceptos_fijos_pr_monto']);
//
$porcentaje = $_POST['conceptos_fijos_pr_porcentaje'];
$porcentaje = str_replace(',','.',$porcentaje);
//

//

if($_POST['conceptos_fijos_pr_id_concepto_fijo']!='')
	$id_concepto_fijo = $_POST['conceptos_fijos_pr_id_concepto_fijo'];
/*if($_POST['conceptos_fijos_pr_id_concepto_fijo']==''){
	$sql = "INSERT INTO 
				conceptos_fijos 
				(id_organismo, 
				 id_concepto, 
				 ultimo_usuario, 
				 fecha_actualizacion) 
			VALUES 	
				('$_SESSION[id_usuario]',
				'$_POST[conceptos_fijos_pr_id_concepto]',
				'$_SESSION[id_usuario]',
				'$_POST[conceptos_fijos_pr_fechact]' )";
	//echo $sql;
	if (!$conn->Execute($sql))			die('Error al Agregar Permisos: '.$conn->ErrorMsg());

	$sql = "SELECT 
				id_concepto_fijos 
			FROM
				conceptos_fijos
			INNER JOIN
				organismo
			ON
				conceptos_fijos.id_organismo = organismo.id_organismo
			WHERE
				conceptos_fijos.id_organismo = $_SESSION[id_organismo]	
			ORDER BY
				id_concepto_fijos DESC";
				
	$row=& $conn->Execute($sql);
	$id_concepto_fijo = $row->fields('id_concepto_fijos');
}	
//
//
	if($_POST['conceptos_fijos_pr_id_concepto_fijo']!=''){
	$sql = "UPDATE 
				conceptos_fijos 
			SET 
				estatus = '2' 
			WHERE 
				id_tipo_nomina = ".$_POST['id_tipo_nomina']." 
			AND 
				id_concepto_fijos = ".$_POST['conceptos_fijos_pr_id_concepto_fijo']." 
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
					conceptos_fijos
				ON
					trabajador.id_trabajador = conceptos_fijos.id_trabajador
				WHERE 
					trabajador.id_trabajador = ".$partem[$i]."
				AND
					conceptos_fijos.id_tipo_nomina = $_POST[id_tipo_nomina]
				AND
					conceptos_fijos.id_concepto = $_POST[conceptos_fijos_pr_id_concepto]
				AND
					trabajador.id_organismo = $_SESSION[id_organismo]";
		$row =& $conn->Execute($ver);			
		if($row->fields("exi")==0){
			$sql = "INSERT INTO 
						conceptos_fijos 
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
					VALUES ('$_POST[conceptos_fijos_pr_id_concepto]',
							$partem[$i],
							'$_POST[id_tipo_nomina]',
							'1',
							'$_SESSION[id_organismo]',
							'$porcentaje',
							'$_POST[conceptos_fijos_pr_monto]',
							'$_POST[conceptos_fijos_pr_comentario]',
							'$_SESSION[id_usuario]',
							'$_POST[conceptos_fijos_pr_fechact]')";
			
		}
		if($row->fields("exi")>0){
			$sql = "UPDATE 
						conceptos_fijos
					SET 
						estatus = '1', 
						ultimo_usuario = '$_SESSION[id_usuario]',
						fecha_actualizacion = '$_POST[conceptos_fijos_pr_fechact]'
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
				count(id_concepto_fijos) 
			FROM 
				conceptos_fijos
		".$where."
			AND
				id_trabajador = $_POST[conceptos_fijos_pr_id_trabajador]
			AND 
				conceptos_fijos.id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);
if ($row==0){
	$sql = "	
				INSERT INTO 
					conceptos_fijos 
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
						'$_POST[conceptos_fijos_pr_id_trabajador]',
						'$_POST[conceptos_fijos_pr_id_concepto]',
						'$_POST[conceptos_fijos_pr_id_frecuencia_concepto]',
						'$porcentaje',
						'$_POST[conceptos_fijos_pr_monto]',
						'$_POST[conceptos_fijos_pr_comentario]',
						'$_SESSION[id_usuario]',
						'$_POST[conceptos_fijos_pr_fechact]'
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