<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

			$Sql="
						SELECT 
							tipo_documento_cxp.id_tipo_documento
						FROM 
							tipo_documento_cxp
						INNER JOIN 
							organismo 
						ON 
							tipo_documento_cxp.id_organismo =organismo.id_organismo
						WHERE
							tipo_documento_cxp.nombre='$_POST[cuentas_por_cobrar_db_tipo_documento]'
						or
							tipo_documento_cxp.siglas='$_POST[cuentas_por_cobrar_db_siglas_documento]'			
					";
			
$row=& $conn->Execute($Sql);
if ($row->EOF)
{
			
	$sql = "	
							INSERT INTO 
								tipo_documento_cxp
								(
									nombre,
									siglas,
									comentarios,
									id_organismo,
									ultimo_usuario,
									fecha_ultima_modificacion
									
							    	) 
								VALUES
								(
								   	'$_POST[cuentas_por_cobrar_db_tipo_documento]',
                 					'$_POST[cuentas_por_cobrar_db_siglas_documento]',
									'$_POST[cuentas_por_cobrar_db_tipo_documento_comentarios]',
									".$_SESSION["id_organismo"].",
									".$_SESSION['id_usuario']."	,
									'".date("Y-m-d H:i:s")."'																						
								)
						";
						
	if (!$conn->Execute($sql)) 
	//die ('Error al Registrar: '.$sql);
		die ('Error al Registrar: '.$conn->ErrorMsg());
	else
	die("Registrado");
	//die("$sql");					
	}else
	die("Noregistro");

	
?>