<?php
session_start();
//verificando los valores de los check box
  $check = $_POST["check"]; 
  $e=0;
  foreach($check as $chk){ 
   $estatus[$e]=$chk;
	// echo($chk);
	 $e++; 
  }  
//verificando los valores de los INDICES DE LOS DOCUMENTOS
  $ide = $_POST["ide"]; 
  $e=0;
  foreach($ide as $id){ 
    $id_doc[$e]=$id;
	//echo($id_doc[$e]);
    $e++; 
	$cont2=$e;
  } 
  $id_prove=$_POST['proveedor_db_id'] ;
$msgExiste="<img align='absmiddle' src='imagenes/caution.gif' /> El Perfil ya Existe";

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$fecha = date("Y-m-d H:i:s");
////
$e_rif=0;
$e_nit=0;
$e_rnc=0;
if ($_POST['proveedor_db_rif_check']=="uno")   { $e_rif=1;}
if ($_POST['proveedor_db_nit_check']=="dos")	{$e_nit=1;}
 if ($_POST['proveedor_db_rnc_check']=="tres") {$e_rnc=1;}
////
$sql = "SELECT nombre FROM proveedor WHERE id_proveedor<>$_POST[proveedor_db_id] AND upper(nombre)='".strtoupper($_POST[proveedor_db_nombre_prove])."'";
$row=& $conn->Execute($sql);
$rif =  $_POST['proveedor_db_tipo'] ."-". $_POST['proveedor_db_rif'];

if($row->EOF)
	$sql = "		UPDATE proveedor  
						 SET
						 	nombre = '$_POST[proveedor_db_nombre_prove]',
							rif = '$rif',
							direccion='$_POST[proveedor_db_direccion]', 
							telefono='$_POST[proveedor_db_telefono]', 
							fax='$_POST[proveedor_db_fax]',
							nombre_persona_contacto='$_POST[proveedor_db_persona_contacto]', 
							cargo_persona_contacto='$_POST[proveedor_db_cargo_contacto]', 
							email_contacto='$_POST[proveedor_db_email_contacto]', 
							paginaweb= '$_POST[proveedor_db_pagina_web]', 
							rnc='$_POST[proveedor_db_rnc]', 
							id_ramo=$_POST[proveedor_db_ramo], 
							comentario='$_POST[proveedor_db_comentario]', 
							ultimo_usuario=".$_SESSION['id_usuario'].", 
							fecha_actualizacion='".$fecha."',
							fecha_vencimiento_rcn = '$_POST[rnc_db_fecha]',
							solvencia_laboral = '$_POST[proveedor_db_sol_laboral]',
							fecha_vencimiento_sol = '$_POST[rnc_db_fecha_sol]',
							fecha_vencimiento_rif = '$_POST[rnc_db_fecha_rif]',
							objeto_compania = '$_POST[proveedor_db_objetivo]',
							covertura_distribucion = '$_POST[proveedor_db_covertura_dis]'
						WHERE id_proveedor = $_POST[proveedor_db_id]
				";
else
	die ("NoActualizo");

if (!$conn->Execute($sql)) 
	//die ('Error al Actualizar: '.$conn->ErrorMsg()); 
	die ("NoActualizo");
else {
//
		///////////ciclo para buscar el valor de los documentos requeridos al proveedor///////////////
		$sql_proveedor="select  * from documento where (id_organismo = ".$_SESSION["id_organismo"].") AND (estatus='0') ORDER BY codigo_documento";
		$rs_proveedor=& $conn->Execute($sql_proveedor);
		if (!$rs_proveedor) 
			{
				die ("NoActualizo");
				//die ('Error al Actualizar: '.$conn->ErrorMsg());
			}
			else{
						$i=0;
						$cont=0;
						//$rs_proveedor=& $conn->Execute($sql_proveedor);
						while(!$rs_proveedor->EOF)
							{						
										$documento_id=$rs_proveedor->fields("id_documento_proveedor");
																
															if ($id_doc[$cont]!="") 
															{
																			$sql_doc="select * from documento_proveedor where (id_organismo = ".$_SESSION["id_organismo"].") AND (id_proveedor= $_POST[proveedor_db_id]) AND (id_documento_proveedor=$id_doc[$cont])";
																			
																			$rs_doc=& $conn->Execute($sql_doc);
																			if (!$rs_rs_doc )
																			{
																					$sql_doc2= "		UPDATE documento_proveedor
																										 SET
																											id_proveedor = '$_POST[proveedor_db_id]',
																											id_documento='$documento_id', 
																											 estatus='$estatus[$cont]'
																										 WHERE (id_documento_proveedor ='$id_doc[$cont]')
																								";
																					}
																}
																else
																	$sql_doc2="INSERT INTO documento_proveedor (id_organismo,id_proveedor,id_documento,estatus) VALUES(".$_SESSION['id_organismo'].", $id_prove,$documento_id,$estatus[$cont])";
																												
																	//echo("$sql_doc2");
																	if (!$conn->Execute($sql_doc2)) 
																	//die ('Error al Actualizar: '.$conn->ErrorMsg());
																	die ("NoActualizo");
														
													 	$cont++;		 
									$rs_proveedor->MoveNext();
									}	
								}		
			       die ('Actualizado');
			     }
?>