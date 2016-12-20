<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$nacionalidad=$_POST[ficha_personal_nacionalidad];
$ced=$_POST[ficha_personal_cedula];
$ci=$nacionalidad.$ced;
$id_afectado=$_POST[id_persona];
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
											$sql_persona="
														SELECT *
														 		FROM afectado;
														where						   
																	afectado.ci='$ci'
																AND
																			";	
																		//	die($sql_persona);	   
														$row_afectado=& $conn->Execute($sql_persona);
														//$total_renglon=0;
														$ant='0';
														if($row_afectado->EOF)
														{
															die("NoRegistro");

														}
														else
														{
														///ACTUALIZANDO CONDICION FISICA
														$id_condicion=$row_afectado->fields("frk_condicion_fisica");
														if(($id_condicion=="")||($id_condicion==NULL))
														{
															$sql_condicion_fisica="INSERT INTO
																						condicion_fisica
																						(
																							alergias,
																							tratamiento,
																							grupo_sanguineo
																						)
																						values
																						(
																							'$_POST[ficha_personal_grupo_sanguineo]',
																							'$_POST[ficha_personal_alergico]',
																							'$_POST[ficha_personal_tratamiento]',
																						)
																						";
																						if (!$conn->Execute($sql_condicion_fisica)) 
																								die ('Error al Registrar: '.$sql_condicion_fisica);
																						else
																						{
																							$sql_condicion_fisica_consulta="select * from condicion_fisica order by id_condicion_fisica ";
																							$row_condicion_fisica=& $conn->Execute($sql_condicion_fisica_consulta);
																								if(!$row_condicion_fisica->EOF)
																								{
																									$id_condicion=$row_condicion_fisica->fields('id_condicion_fisica');	
																								}
																						}								
														}
														else
														{
															$sql_condicion_fisica="
																					UPDATE condicion_fisica
																						   SET 
																						   			alergias='$_POST[ficha_personal_alergico]',
																									tratamiento='$_POST[ficha_personal_tratamiento]',
																									grupo_sanguineo='$_POST[ficha_personal_grupo_sanguineo]'
																						 WHERE 
																						 		id_condicion_fisica='$id_condicion'
																						 ;
																					";
																					if (!$conn->Execute($sql_condicion_fisica))
																					{
																						die ('Error al Actualizar: '.$conn->ErrorMsg());
																					}
																					
				else {
														}
														///ACTUALIZANDO VEHICULO
														$id_vehiculo=$row_afectado->fields("frk_vehiculo");
														if(($id_vehiculo=="")||($id_vehiculo==NULL))
														{
																$sql_vehiculo="INSERT INTO
																						vehiculo
																						(
																							 placas,
																							 modelo,
																							 marca,
																							 color

																						)
																						values
																						(
																							'$_POST[ficha_personal_placas]',
																							'$_POST[ficha_personal_modelo_vehiculo]',
																							'$_POST[ficha_personal_marca_vehiculo]',
																							'$_POST[ficha_personal_color_vehiculo]',
																						)
																						";
																						if (!$conn->Execute($sql_vehiculo)) 
																								die ('Error al Registrar: '.$sql_vehiculo);
																						else
																						{
																							$sql_vehiculo_consulta="select * from vehiculo where placas='$_POST[ficha_personal_placas]' ";
																							$row_vehiculo=& $conn->Execute($sql_vehiculo_consulta);
																								if(!$row_vehiculo->EOF)
																								{
																									$id_vehiculo=$row_vehiculo->fields('id_vehiculo');	
																								}
																						}			
														}
														else
														{
														
																$sql_vehiculo="
																					UPDATE vehiculo
																						   SET 
																						   			placas='$_POST[ficha_personal_placas]',
																									modelo='$_POST[ficha_personal_modelo_vehiculo]',
																									marca='$_POST[ficha_personal_marca_vehiculo]',
																									color='$_POST[ficha_personal_color_vehiculo]'
																						 WHERE 
																						 		id_vehiculo='$id_vehiculo'
																						 ;
																	
															";
																					if (!$conn->Execute($sql_vehiculo))
																					{
																						die ('Error al Actualizar: '.$conn->ErrorMsg());
																					}
														}
														///ACTUALIZANDO PROFESION
														$id_profesion=$row_afectado->fields("frk_profesion");
														if(($id_profesion=="")||($id_profesion==NULL))
														{
															$sql_profesion="INSERT INTO
																							profesion
																							(
																								 profesion
																								 nombre_empresa,
																								 direccion
	
																							)
																							values
																							(
																								'$_POST[ficha_personal_oficio]',
																								'$_POST[ficha_personal_empresa]',
																								'$_POST[ficha_personal_dir_trabajo]'
																							)
																							";
																						if (!$conn->Execute($sql_profesion)) 
																								die ('Error al Registrar: '.$sql_profesion);
																						else
																						{
																							$sql_profesion_consulta="select * from profesion order by id_profesion ";
																							$row_profesion=& $conn->Execute($sql_profesion_consulta);
																								if(!$row_profesion->EOF)
																								{
																									$id_profesion=$row_profesion->fields('id_profesion');	
																								}
																						}			
														}
														else
														{
														
																$sql_profesion="
																					UPDATE profesion
																						   SET 
																						   			profesion='$_POST[ficha_personal_oficio]',
																								    nombre_empresa='$_POST[ficha_personal_empresa]',
																								    direccion='$_POST[ficha_personal_dir_trabajo]'
																						 WHERE 
																						 		id_profesion='$id_profesion'
																						 ;
																	
															";
																					if (!$conn->Execute($sql_vehiculo))
																					{
																						die ('Error al Actualizar: '.$conn->ErrorMsg());
																					}
														}
														//////
														
														///////////// actualizando al afectado
														$sql = "	
																UPDATE 
																	afectado
																SET	
																		ci='$ci',
																		nombre='$_POST[ficha_personal_nombre]',
																		apellido='$_POST[ficha_personal_apellido]',
																		fecha_nacimiento='$_POST[ficha_personal_fecha]',
																		nacionalidad='$nacionalidad',,
																		fec_registrado='".date("Y-m-d H:i:s")."',
																		telefono_afect='$_POST[ficha_personal_tlf_fam]', 
																		direccion='$_POST[ficha_personal_dirfamiliar]',
																		telefono_local='$_POST[ficha_personal_tlf_fam]', 
																		frk_condicion_fisica='$id_condicion', 
																	    frk_vehiculo='$id_vehiculo',
																		frk_profesion='$id_profesion'
																where
																		id_afectado='$id_afectado'
																and
																		afectado.ci='$ci'				
																
															";
														}
					
					if (!$conn->Execute($sql)) 
						die ('Error al Registrar: '.$sql);
				
					else
					{
							
							$responce="Registrado";

							die($responce);
					}
					//die($sql);
						
					
?>