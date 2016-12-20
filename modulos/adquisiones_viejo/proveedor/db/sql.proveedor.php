<?php
session_start();
//foreach ($_POST as $key => $value) echo "$key=$value<br>";
//die();
//foreach ($_POST['check'] as $key => $value) echo "$key=$value<br>";

  $check = $_POST["check"]; 
  $e=0;
  foreach($check as $chk){ 
   $estatus[$e]=$chk;
	 //echo($chk);
	 $e++; 
  }  

$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> La Acci&oacute;n ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");

$sqlBus = "SELECT * FROM proveedor WHERE (upper(nombre) = '".strtoupper($_POST[proveedor_db_nombre_prove])."')";
$row=& $conn->Execute($sqlBus);
$rif =  $_POST['proveedor_db_tipo'] ."-". $_POST['proveedor_db_rif'];
if($row->EOF){
	$sql = "	
					INSERT INTO 
						proveedor
							(
							id_organismo, 
							codigo_proveedor,
							nombre, 
							direccion, 
							telefono, 
							fax, 
							rif, 
							nit, 
							nombre_persona_contacto, 
							cargo_persona_contacto, 
							email_contacto, 
							paginaweb, 
							rnc, 
							fecha_vencimiento_rcn,
							fecha_ingreso, 
							usuario_ingreso, 
							id_ramo, 
							comentario, 
							ultimo_usuario, 
							fecha_actualizacion,
							solvencia_laboral,
							fecha_vencimiento_sol,
							objeto_compania,
							covertura_distribucion,
							fecha_vencimiento_rif
							)
					VALUES	
						(
							".$_SESSION['id_organismo'].", 
							'$_POST[proveedor_db_codigo]',
							'$_POST[proveedor_db_nombre_prove]', 
							'$_POST[proveedor_db_direccion]', 
							'$_POST[proveedor_db_telefono]', 
							'$_POST[proveedor_db_fax]', 
							'$rif', 
							'$_POST[proveedor_db_nit]', 
							'$_POST[proveedor_db_persona_contacto]', 
							'$_POST[proveedor_db_cargo_contacto]', 
							'$_POST[proveedor_db_email_contacto]', 
							'$_POST[proveedor_db_pagina_web]', 
							'$_POST[proveedor_db_rnc]', 
							'$_POST[rnc_db_fecha]', 
							'".$fecha."',
							".$_SESSION['id_usuario'].", 
							$_POST[proveedor_db_ramo], 
							'$_POST[proveedor_db_comentario]',
							".$_SESSION['id_usuario'].",
							'".$fecha."',
							'$_POST[proveedor_db_sol_laboral]',
							'$_POST[rnc_db_fecha_sol]',
							'$_POST[proveedor_db_objetivo]',
							'$_POST[proveedor_db_covertura_dis]',
							'$_POST[rnc_db_fecha_rif]'
						)
				";
	}
	
	else
	die("Existe");
			if (!$conn->Execute($sql)) 
				die ('Error al Registrar: '.$sql);
				//$conn->ErrorMsg());
				else{
							//registra en la tabla documento proveedor
							$sql_prueba="SELECT * FROM proveedor WHERE  (upper(nombre) = '".strtoupper($_POST[proveedor_db_nombre_prove])."')";
							$row2=& $conn->Execute($sql_prueba);
							$id=$row2->fields("id_proveedor");
						  ///////////ciclo para buscar el valor de los documentos requeridos al proveedor///////////////
							$sql_proveedor="select  * from documento where (id_organismo = ".$_SESSION["id_organismo"].") AND (estatus='0') ORDER BY codigo_documento";
							$rs_proveedor=& $conn->Execute($sql_proveedor);
								if (!$rs_proveedor) 
									{
										die ('Error al Registrar: '.$sql_proveedor);
									}
									else{
												$i=0;
												//$rs_proveedor=& $conn->Execute($sql_proveedor);
												while(!$rs_proveedor->EOF)
													{						$documento_id=$rs_proveedor->fields("id_documento_proveedor");
																						
													//-
													$sql_reg_doc="INSERT INTO documento_proveedor (id_organismo,id_proveedor,id_documento,estatus) VALUES(".$_SESSION['id_organismo'].", $id,$documento_id,$estatus[$i])";
													if (!$conn->Execute($sql_reg_doc)) die($sql_reg_doc);
															
													$i=$i+1;	
													$rs_proveedor->MoveNext();
													}//die($sql_reg_doc);
							      			}
											die('Registrado');										
		    		   }												
								
?>