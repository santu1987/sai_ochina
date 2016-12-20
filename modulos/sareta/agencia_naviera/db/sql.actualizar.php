<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

/*Se realiza el paso de los valores del formulario de 
zona, apartado , Codigo_auxiliar para validar que no se en cuentre vacio si es asi 
cambiarlo a un valor de cero (0)*/ 


$zona=$_POST[sareta_agencia_naviera_db_zona];
$apartado=$_POST[sareta_agencia_naviera_db_apartado];
$auxiliar=$_POST[sareta_agencia_naviera_db_codigo_auxiliar];

if($zona==""){$zona=0;}
if($apartado==""){$apartado=0;}
if($auxiliar==""){$auxiliar=0;}

/*El codigo siguiente valida que no se en cuentre vacio el codigo de area y el telefono.
de ser asi envia un mesaje de error a la vista para ser mostrada al usuario*/

		if($_POST[sareta_agencia_naviera_db_codigo_area]=="" && $_POST[sareta_agencia_naviera_db_telefono]==""){
			die ("area_telefono");
			}else{
				if($_POST[sareta_agencia_naviera_db_codigo_area]==""){
					die ("codigo_area");
				}else{
					if($_POST[sareta_agencia_naviera_db_telefono]==""){
					die ("no_telefono");
				}else{
					
					/*El codigo raliza una consulta para validar al usuario y su delegación*/
					
					$sql = "SELECT id_unidad_ejecutora FROM usuario WHERE id_usuario=".$_SESSION['id_usuario'];
					$row= $conn->Execute($sql);
					$delegacion=0;
					if(!$row->EOF){
					$delegacion=$row->fields("id_unidad_ejecutora");
				}
					
			/*El codigo realiza la valida que no exista una agencia naviera con el mismo rif*/	
					
					$sql = "SELECT * FROM sareta.agencia_naviera WHERE id_delegacion=".$delegacion." and upper(rif) = '".strtoupper($_POST['sareta_agencia_naviera_db_rif'])."' and id_agencia_naviera != $_POST[vista_id_agencia_naviera]";
					$row= $conn->Execute($sql);
						if($row->EOF){
										$sql="	
											UPDATE
											sareta.agencia_naviera
											SET
											
											  id_delegacion=".$delegacion.",
											  nombre='".strtoupper($_POST["sareta_agencia_naviera_db_nombre"])."',
											  rif='".strtoupper($_POST["sareta_agencia_naviera_db_rif"])."',
											  nit='".strtoupper($_POST["sareta_agencia_naviera_db_nit"])."',
											  direccion='$_POST[sareta_agencia_naviera_db_direccion]',
											  id_estado=$_POST[sareta_agencia_naviera_db_estado],
											  codigo_area=$_POST[sareta_agencia_naviera_db_codigo_area],
											  zona=".$zona.",
											  apartado=".$apartado.",
											  telefono1='$_POST[sareta_agencia_naviera_db_telefono]',
											  telefono2='$_POST[sareta_agencia_naviera_db_telefono1]',
											  fax1='$_POST[sareta_agencia_naviera_db_fax]',
											  fax2='$_POST[sareta_agencia_naviera_db_fax1]',
											  pag_web='$_POST[sareta_agencia_naviera_db_pag_web]',
											  correo_electronico='$_POST[sareta_agencia_naviera_db_correo]',
											  contacto='".strtoupper($_POST["sareta_agencia_naviera_db_contacto"])."',
											  cedula=$_POST[sareta_agencia_naviera_db_cedula],
											  cargo='".strtoupper($_POST["sareta_agencia_naviera_db_cargo"])."',
											  codigo_auxiliar=".$auxiliar.",
											  comentario='$_POST[sareta_agencia_naviera_db_obs]',
											  ultimo_usuario=".$_SESSION['id_usuario'].",
											  fecha_actualizacion='".date("Y-m-d H:i:s")."'
											WHERE 
											id_agencia_naviera=$_POST[vista_id_agencia_naviera]
";
								
					
				
						if (!$conn->Execute($sql)) 
							die ('Error al Actualizar: '.$conn->ErrorMsg());
						else
							die("Actualizado");
					}
					else
					{
						die ('El_rif_Exite');
					}
				}
			}
		}
?>