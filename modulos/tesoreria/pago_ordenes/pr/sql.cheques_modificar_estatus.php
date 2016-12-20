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
						
						if($unidad=='2')//cheques contabilizados
						{
							/*$estatus='8';
							$valor='4';
							$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario'].",
											fecha_contab='".date("Y-m-d H:i:s")."',
											usuario_contab=".$_SESSION['id_usuario']."
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";*/
								if($_POST['tesoreria_estatus_db_unidad']=='1')
							{	
								$estatus='9';
								$valor='3';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
									";
							
								
							}else
							if($_POST['tesoreria_estatus_db_unidad']=='2')
							{
								$estatus='8';
								$valor='4';
								$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario'].",
											fecha_caja='".date("Y-m-d H:i:s")."',
											usuario_recibe_caja=".$_SESSION['id_usuario'].",
											fecha_pago='".date("Y-m-d H:i:s")."',
											usuario_pago=".$_SESSION['id_usuario']."
											
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";
							}
						
						
						}
						else
						if($unidad=='11')//cheques en tesoreria
						{
							if($_POST['tesoreria_estatus_db_unidad']=='1')
							{	
								$estatus='5';
								if($valor!='4')
								{
									$valor='3';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
									";
								}	
								
							}else
							if($_POST['tesoreria_estatus_db_unidad']=='2')
							{
								$estatus='6';
								$valor='4';
								$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario'].",
											fecha_caja='".date("Y-m-d H:i:s")."',
											usuario_recibe_caja=".$_SESSION['id_usuario'].",
											fecha_pago='".date("Y-m-d H:i:s")."',
											usuario_pago=".$_SESSION['id_usuario']."
											
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";
							}
							if($_POST['tesoreria_estatus_db_unidad']=='3')
							{
								$estatus='7';
								$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario'].",
											fecha_caja='".date("Y-m-d H:i:s")."',
											usuario_recibe_caja=".$_SESSION['id_usuario'].",
											fecha_pago='".date("Y-m-d H:i:s")."',
											usuario_pago=".$_SESSION['id_usuario']."
											
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";
							}
								
						}else
						if($unidad=='4')// cheques en admin y finanzas
						{
							if($_POST['tesoreria_estatus_db_unidad']=='1')
							{	
								$estatus='2';
								if($valor!='4')
								{
									$valor='2';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
											
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";
								}	
							}else
							if($_POST['tesoreria_estatus_db_unidad']=='2')
							{
								$estatus='3';
								if($valor!='4')
								{
									$valor='2';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
									";
								}	
							}
						}else
						if($unidad=='15')//cheques en direccion
						{
							$estatus='4';
							if($valor!='4')
								{
									$valor='2';
									$sql="UPDATE cheques
										SET
											estado[$estatus]='1',
											estado_fecha[$estatus]=	'".date("Y-m-d H:i:s")."',																
											fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
											ultimo_usuario=".$_SESSION['id_usuario']."
										WHERE
											 cheques.id_cheques='$vector[$idem]'
										AND
											cheques.id_organismo=".$_SESSION["id_organismo"]."
										
									";
								}	
						}
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
												//echo($sql);
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