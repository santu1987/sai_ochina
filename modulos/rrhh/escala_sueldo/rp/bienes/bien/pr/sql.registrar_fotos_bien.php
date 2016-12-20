<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1; 
//
	$total = $_POST['fotos_bien_pr_num'];
	for($i=1; $i<=$total; $i++){
		$tmp = $_FILES['foto_'.$i]['tmp_name'];
		$type = $_FILES['foto_'.$i]['type'];
		$size = $_FILES['foto_'.$i]['size'];
		if($type=="image/jpeg" /*|| $type=="image/png" || $type=="image/bmp"*/){
			$nombre = "foto_".$_POST['fotos_bien_pr_id_bienes']."_".$_FILES['foto_'.$i]['name'];
			$foto_vie = $_POST['eliminar_nombre_foto_vie'.$i];
			if($_POST['eliminar_nombre_foto'.$i]!='')	
				unlink('../../../../imagenes/bienes/'.$nombre);
			if($_POST['eliminar_nombre_foto_vie'.$i]!=''){
				unlink('../../../../imagenes/bienes/'.$foto_vie);
				$sql = "DELETE FROM fotos_bienes WHERE nombre = '$foto_vie' ";
				$row=& $conn->Execute($sql);
			}
			move_uploaded_file($tmp,'../../../../imagenes/bienes/'.$nombre);
			$Sql = "SELECT COUNT(id_fotos_bienes) FROM fotos_bienes where upper(nombre) like  '".strtoupper($nombre)."'";
			$row=& $conn->Execute($Sql);
			$row= substr($row,7,2);
			if ($row==0){
			$sql = "INSERT INTO 
					fotos_bienes 
					(
						id_organismo,
						id_bienes,
						nombre,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[fotos_bien_pr_id_bienes]',
						'$nombre',
						'$_SESSION[id_usuario]',
						'$_POST[fotos_bien_pr_fechact]'
					)
			";
			if ($conn->Execute($sql) == false) {
				echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
			}
			/*else
			{
				echo ("Registrado");
			}*/
			}
		}
	}
	echo "<script>parent.document.form_foto.action='modulos/bienes/bien/pr/vista_previa.php';
parent.document.form_foto.fotos_bien_pr_guardar.onclick();</script>";
?>