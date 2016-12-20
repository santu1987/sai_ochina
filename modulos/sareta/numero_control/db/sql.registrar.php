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
					";
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
			
				$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE 
				id_usuario=".$_SESSION['id_usuario'];
									$row1= $conn->Execute($sql);
									$delegacion=0;
									if(!$row1->EOF)
									{
									$delegacion=$row1->fields("id_unidad_ejecutora");
									}
				
		
				
				
					if($_POST[sareta_numero_control_db_vista_activo]=='true')
					{
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
				$sql = "INSERT INTO 
									sareta.numero_control 
									(
									  id_delegacion,
									  descripcion,
									  numero_inicial,
									  numero_final,
									  numero_actual,
									  estatus,
									  comentario,
									  ultimo_usuario,
									  fecha_creacion,
									  fecha_actualizacion
									) 
									VALUES
									(
										".$delegacion.",
										upper('$_POST[sareta_numero_control_db_vista_descripcion]'),
										$_POST[sareta_numero_control_db_vista_Ninicial],
										$_POST[sareta_numero_control_db_vista_Nfinal],
										$_POST[sareta_numero_control_db_vista_Nactual],
										$_POST[sareta_numero_control_db_vista_activo],
										'$_POST[sareta_numero_control_db_vista_comentario]',
										".$_SESSION['id_usuario'].",
										'".date("Y-m-d H:i:s")."',
										'".date("Y-m-d H:i:s")."'	
									)";
					
				if (!$conn->Execute($sql)) 
					die ('Error al Registrar: '.$sql/*$conn->ErrorMsg()*/);
				else
					die("Registrado");
					
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