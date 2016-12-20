<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
$numeros_cheques = $_POST["tesoreria_estatus_db_id_cheque_oculto"]; 
$unidad=$_POST["tesoreria_nombre_unidad"];
$combo=$_POST['tesoreria_estatus_db_unidad'];
$fecha_proceso=$_POST['fecha_proceso'];
//die($combo);


$sql_prueba="			SELECT 
								* 
								FROM 
									cheques 
								INNER JOIN
									banco_cuentas
								ON
									cheques.cuenta_banco=banco_cuentas.cuenta_banco
								WHERE 
									cheques.id_organismo=$_SESSION[id_organismo]
								order by 
								id_cheques
								";
$row_prueba=& $conn->Execute($sql_prueba);
if(!$row_prueba->EOF)
{
			$vector=split(",",$numeros_cheques);
			sort($vector);
			$idem=0;		
				while((!$row_prueba->EOF)&&($vector[$idem]!=""))
				{//echo($vector[$idem]);
					 if($vector[$idem]==$row_prueba->fields("id_cheques"))
					 {
						//////////////////
						
					
						if($combo==1)
								$estatus='2';
						if($combo==2)
								$estatus='3';
						if($combo==3)
								$estatus='4';
						if($combo==4)
								$estatus='5';
						if($combo==5)
								$estatus='6';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'$fecha_proceso',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
									";
								
													
								
						
						//die($unidad);
						/////////////////	
						/*	$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario'].",
											estatus='$valor'
											
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";*/
												//die($sql);
												if (!$conn->Execute($sql)) 
												die ('Error al Actualizar: '.$conn->ErrorMsg());
												//die ("NoRegistro");
						$idem=$idem+1;	 
					 }
					$row_prueba->MoveNext();
							
				}
die("Registrado");
}	else
	die ("NoRegistro");
			
?>	