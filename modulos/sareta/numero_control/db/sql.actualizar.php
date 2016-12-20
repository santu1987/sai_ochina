<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);



	if($_POST['sareta_numero_control_db_vista_Ninicial']<=
			  $_POST['sareta_numero_control_db_vista_Nfinal'] &&
				$_POST['sareta_numero_control_db_vista_Ninicial']<=
					$_POST['sareta_numero_control_db_vista_Nactual']
					){
		if($_POST['sareta_numero_control_db_vista_Nfinal']>=
			  $_POST['sareta_numero_control_db_vista_Ninicial'] &&
				$_POST['sareta_numero_control_db_vista_Nfinal']>=
					$_POST['sareta_numero_control_db_vista_Nactual']
					){
			
//-----------------------------------------------------------------------------------------------
$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF){
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}
			$sql = "SELECT numero_inicial, numero_final, descripcion, nombre
 			FROM sareta.numero_control
 				INNER JOIN
					unidad_ejecutora AS delegacion
				ON
					delegacion.id_unidad_ejecutora=id_delegacion 
					WHERE id_numero_control != $_POST[vista_id_numero_control]";
			$row0= $conn->Execute($sql);

$VarError=0;

while (!$row0->EOF) 
{	
	
	if($_POST['sareta_numero_control_db_vista_Ninicial']<= $row0->fields("numero_inicial")
				&& $_POST['sareta_numero_control_db_vista_Nfinal']>= $row0->fields("numero_final"))
	{
		die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El (N&deg; Inicial) y (N&deg; Final) que ingreso contienen dentro de su limites a un rango ya existente para la Delegaci&oacute;n ".$row0->fields("nombre")." Registrado como:".$row0->fields("descripcion")." (N&ordm; Inicial ".$row0->fields("numero_inicial")." - N&ordm; Final ".$row0->fields("numero_final").") </p></div>");
		
		}
		else if($_POST['sareta_numero_control_db_vista_Ninicial']>= $row0->fields("numero_inicial")
					&& $_POST['sareta_numero_control_db_vista_Nfinal']<= $row0->fields("numero_final"))
		{
			die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El (N&deg; Inicial) y (N&deg; Final) que ingreso se encuentran dentro de un rango ya existente para la Delegaci&oacute;n ".$row0->fields("nombre")." Registrado como:".$row0->fields("descripcion")." (N&ordm; Inicial ".$row0->fields("numero_inicial")." - N&ordm; Final ".$row0->fields("numero_final").") </p></div>");
			
			}else if($_POST['sareta_numero_control_db_vista_Nfinal']>= $row0->fields("numero_inicial")
				&& $_POST['sareta_numero_control_db_vista_Nfinal']<= $row0->fields("numero_final"))
				{
				
				die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El (N&deg; Final) coincide con un rango ya existente para la Delegaci&oacute;n ".$row0->fields("nombre")." Registrado como:".$row0->fields("descripcion")." (N&ordm; Inicial ".$row0->fields("numero_inicial")." - N&ordm; Final ".$row0->fields("numero_final").") </p></div>");
				
				}else if($_POST['sareta_numero_control_db_vista_Ninicial']>= $row0->fields("numero_inicial") && 
			  	$_POST['sareta_numero_control_db_vista_Ninicial']<= $row0->fields("numero_final"))
					{
					
					die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El (N&deg; Inicial) coincide con un rango ya existente para la Delegaci&oacute;n ".$row0->fields("nombre")." Registrado como:".$row0->fields("descripcion")." (N&ordm; Inicial ".$row0->fields("numero_inicial")." - N&ordm; Final ".$row0->fields("numero_final").") </p></div>");
					
					}

$row0->MoveNext();
}
//-----------------------------------------------------------------------------------------------
			if($VarError==0){
			
				
		//condi.. para validar si el estatus a modificar es el ultimo activo
		 
		
		
				$sql = "SELECT id_numero_control,descripcion,
				numero_inicial,numero_final,numero_actual,
				estatus,comentario FROM sareta.numero_control WHERE 
				id_delegacion=".$delegacion." and estatus=true";
				$row2= $conn->Execute($sql);
						
				if(($_POST['sareta_numero_control_db_vista_activo']=='true'
				   		  &&
						  $_POST['vista_id_numero_control']== $row2->fields("id_numero_control"))
						 ||
						  ( 
						  $_POST['sareta_numero_control_db_vista_descripcion']!=
						  $row2->fields("descripcion")
						  ||
						  $_POST['sareta_numero_control_db_vista_Ninicial']!=
						  $row2->fields("numero_inicial")
						  ||
						  $_POST['sareta_numero_control_db_vista_Nfinal']!=
						  $row2->fields("numero_final")
						  ||
						  $_POST['sareta_numero_control_db_vista_Nactual']!=
						  $row2->fields("numero_actual")
						  ||
						  $_POST['sareta_numero_control_db_vista_comentario']!=
						  $row2->fields("comentario")))
				{
					
						
							if($_POST['sareta_numero_control_db_vista_activo']=='true'){
								$sql = "	
								UPDATE sareta.numero_control  
									 SET
									 estatus=false
								WHERE id_delegacion= ".$delegacion."
								";
							if (!$conn->Execute($sql)) {
							die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
							}
							}
							$sql = "UPDATE sareta.numero_control 
									 SET
									 	  descripcion=upper('$_POST[sareta_numero_control_db_vista_descripcion]'),
												  numero_inicial=$_POST[sareta_numero_control_db_vista_Ninicial],
												  numero_final=$_POST[sareta_numero_control_db_vista_Nfinal],
												  numero_actual=$_POST[sareta_numero_control_db_vista_Nactual],
												  estatus=$_POST[sareta_numero_control_db_vista_activo],
												  comentario='$_POST[sareta_numero_control_db_vista_comentario]',
												  ultimo_usuario=".$_SESSION['id_usuario'].",
												  fecha_actualizacion='".date("Y-m-d H:i:s")."'
												  WHERE id_numero_control = $_POST[vista_id_numero_control]
												";
					
						if (!$conn->Execute($sql)) 
						die ('Error al Actualizar: '.$conn->ErrorMsg());
						else
						die("Actualizado");
				}
				else
				{
				die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El estatus no puede ser modificado debido a que no hay numero de contro Activo.</p></div>");
				}//fin de la condi.. para validar si es el ultimo activo
			}
				
			
			
			
		}
		else
		{
		die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El (N&deg; Final) tiene que ser Mayor al \n (N&deg; Inicial) y Mayor (N&deg; Actual)</p></div>");
		}
		
	}
	else
	{
	die("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El (N&deg; Inicial) tiene que ser Menor al \n (N&deg; Final) y Menor o Igual al (N&deg; Actual)</p></div>");
	}

?>
