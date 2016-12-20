<?php
session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$ano=substr($fecha,0,4);

//



$sqlb = "SELECT id_auxiliares FROM auxiliares WHERE id_auxiliares = $_POST[contabilidad_vista_auxiliares] ";
//AND upper(nombre) ='".strtoupper($_POST['contabilidad_auxiliares_db_nombre'])."'
//die($sqlb);

if (!$conn->Execute($sqlb))
 die ('Error al Actualizar: '.$conn->ErrorMsg());
$row=& $conn->Execute($sqlb);

if(!$row->EOF)
{

					
			$sql_aux="SELECT auxiliares.id_auxiliares FROM auxiliares INNER JOIN saldo_auxiliares ON auxiliares.id_auxiliares=saldo_auxiliares.cuenta_auxiliar WHERE saldo_auxiliares.cuenta_auxiliar=$_POST[contabilidad_vista_auxiliares]  AND saldo_auxiliares.cuenta_contable=$_POST[contabilidad_auxiliares_db_id_cuenta_contable]";
					//die($sql_aux);
$row_aux_cont=& $conn->Execute($sql_aux);
if($row_aux_cont->EOF)//no existe la relacion la crea
{
	$Sql_intermedio="INSERT INTO
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
						 '$_POST[contabilidad_vista_auxiliares]',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '{0,0,0,0,0,0,0,0,0,0,0,0}',
						 '$_POST[contabilidad_auxiliares_db_comentario]',
						 ".$_SESSION['id_usuario'].",
						'".date("Y-m-d H:i:s")."'			
						);
						";
}else//si existe poronga
{
	$Sql_intermedio="";
	}
//	
	
	$sql = "	
					UPDATE auxiliares  
						 SET
							nombre = '$_POST[contabilidad_auxiliares_db_nombre]',
							comentarios = '$_POST[contabilidad_auxiliares_db_comentario]',	
cuenta_auxiliar='$_POST[contabilidad_auxiliares_db_cuenta_auxiliar]',					
							id_organismo=	 ".$_SESSION["id_organismo"].",
							ultimo_usuario = ".$_SESSION['id_usuario'].",
							ultima_modificacion ='".date("Y-m-d H:i:s")."'	
							WHERE id_auxiliares = $_POST[contabilidad_vista_auxiliares];
							$Sql_intermedio
							
				";
			
}
else
	die("NoActualizo");			
//die($sql);
if (!$conn->Execute($sql))
{ 
die($sql);
	die ('Error al Actualizar: '.$conn->ErrorMsg());
}
else
	die("Actualizado");
?>