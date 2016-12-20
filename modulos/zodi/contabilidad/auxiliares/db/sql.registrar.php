<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$ano=substr($fecha,0,4);
$id_cuenta_contab=$_POST[contabilidad_auxiliares_db_id_cuenta_contable];
$sql = "SELECT id_auxiliares FROM auxiliares
 WHERE 
	cuenta_auxiliar='$_POST[contabilidad_auxiliares_db_cuenta_auxiliar]'
	"; 
//die($sql);
if (!$conn->Execute($sql)) die ('Error al Registrar: '.$conn->ErrorMsg());
$row= $conn->Execute($sql);
			
if($row->EOF){
$sql = "INSERT INTO 
					auxiliares
					(
						
						cuenta_auxiliar,
						comentarios,
						id_organismo,
						nombre,
						ultimo_usuario,
						ultima_modificacion
					) 
					VALUES
					(
						
						'$_POST[contabilidad_auxiliares_db_cuenta_auxiliar]',
						'$_POST[contabilidad_auxiliares_db_comentario]',
				        ".$_SESSION["id_organismo"].",
						'$_POST[contabilidad_auxiliares_db_nombre]',
						".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'						
					);
			";
			
			//die($sql);


	}						
else
	die("NoRegistro");
	
if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
die ('Error al Registrar: '.$conn->ErrorMsg());
else
{
	$sql_id = "SELECT id_auxiliares FROM auxiliares
	 WHERE 
		cuenta_auxiliar='$_POST[contabilidad_auxiliares_db_cuenta_auxiliar]'
		"; 
//	die($sql_id);
	if (!$conn->Execute($sql_id)) die ('Error al Registrar: '.$conn->ErrorMsg());
	$row2= $conn->Execute($sql_id);
				
	if(!$row2->EOF){
	$ids=$row2->fields('id_auxiliares');
	$sql2="INSERT INTO
					saldo_auxiliares
					(
						id_organismo,
						ano,
						cuenta_contable,
						cuenta_auxiliar,
						saldo_inicio,
						debe,
						haber,
						comentarios,
						ultimo_usuario,
						ultima_modificacion
					)
					values
					(
						 $_SESSION[id_organismo],
						 $ano,
						 '$_POST[contabilidad_auxiliares_db_id_cuenta_contable]',
						 '$ids',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '$_POST[contabilidad_auxiliares_db_comentario]',
						 ".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'			
						);
						";
	$Sql_intermedio="insert into
				rel_aux_cont				
					(
						id_auxiliar,
						id_contab
					)
					VALUES
					(
						
					'$ids',
					'$id_cuenta_contab'
					);
					$sql2
			";
	//die($Sql_intermedio);	
	if (!$conn->Execute($sql2)) 
	die ('Error al Registrar: '.$sql2);
	//	die ('Error al Registrar: '.$conn->ErrorMsg());
	else
		die("Registrado");
		}
}
	
?>