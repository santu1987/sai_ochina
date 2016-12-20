<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<?php
	$type = $_FILES['curriculum_db_imagen']['type'];
	$size = $_FILES['curriculum_db_imagen']['size'];
	//
	//
	if($_POST['opt']==1){
	if ($type!="image/jpeg" /*&& $type!="image/png" && $type!="image/bmp"*/){
		$option = 0;
		echo "<script>parent.document.form_db_foto.err_formato.onclick(); parent.document.form_db_foto.foto_curriculum.src='../../../../imagenes/curriculos/knode2.png'; parent.document.form_db_foto.curriculum_db_imagen.value='';  parent.document.form_db_curriculum.curriculum_db_nombre_img.value='';</script>";
	}
	else if ($size>=1000000){
		$option = 0;
		echo "<script>parent.document.form_db_foto.err_tamano.onclick(); parent.document.form_db_foto.foto_curriculum.src='../../../../imagenes/curriculos/knode2.png'; parent.document.form_db_foto.curriculum_db_imagen.value='';  parent.document.form_db_curriculum.curriculum_db_nombre_img.value='';</script>";
	}
	if ($type=="image/jpeg" /*|| $type=="image/png" || $type=="image/bmp"*/ && $size<1000000){
	$option = 1;
	move_uploaded_file($_FILES['curriculum_db_imagen']['tmp_name'],'../../../../imagenes/save_cache/'.$_FILES['curriculum_db_imagen']['name']);
	echo "<script>parent.document.form_db_foto.foto_curriculum.src='imagenes/save_cache/".$_FILES['curriculum_db_imagen']['name']."';</script>";
	
	
	echo "<script> parent.document.form_db_curriculum.curriculum_db_nombre_img.value='".$_FILES['curriculum_db_imagen']['name']."'; parent.document.form_db_foto.action='modulos/rrhh/curriculum/db/limpiar_cache.php'; parent.document.form_db_foto.target='limpiar_cache'; parent.document.form_db_foto.submit();</script>";
	}
	
	}
	//
	//
	if($_POST['opt']==2){
		move_uploaded_file($_FILES['curriculum_db_imagen']['tmp_name'],'../../../../imagenes/curriculos/'.$_FILES['curriculum_db_imagen']['name']);
		//
		if($_POST['foto_ant']!=''){
			unlink('../../../../imagenes/curriculos/'.$_POST['foto_ant']);
		}
		//
	}
?>
<body>
</body>
</html>