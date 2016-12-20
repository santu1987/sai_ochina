<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php
	echo "Hola";
	$type = $_FILES[$_POST['pos']]['type'];
	$size = $_FILES[$_POST['pos']]['size'];
	if ($type!="image/jpeg" /*&& $type!="image/png" && $type!="image/bmp"*/){
		$option = 0;
		echo "<script>parent.document.form_foto.error_formato.onclick();</script>";
	}
	else if ($size>=1000000){
		$option = 0;
		echo "<script>parent.document.form_foto.error_tamano.onclick();</script>";
	}
	if ($type=="image/jpeg" /*|| $type=="image/png" || $type=="image/bmp"*/ && $size<1000000){
	$option = 1;
	move_uploaded_file($_FILES[$_POST['pos']]['tmp_name'],'../../../../imagenes/save_cache/'.$_FILES[$_POST['pos']]['name']);
	}
?>

<form id="form_cache_foto" name="form_cache_foto" method="post" action="limpiar_cache.php">
  <input type="hidden" name="pos" id="pos" value="<?php echo $_POST['pos'];?>"/>
  <input type="hidden" name="nombre" id="nombre" value="<?php echo $_FILES[$_POST['pos']]['name'];?>"/>
  <input type="hidden" name="option" id="option" value="<?php echo $option;?>"/>
</form>
<script language="javascript">
if (document.form_cache_foto.option.value==1){
	var text = document.form_cache_foto.pos.value;
	var tam = text.length;
	var pos = text.lastIndexOf('_');
	text = text.substr(pos+1,tam);
	val=eval('parent.document.form_foto.vista_foto'+text);
	val.src = 'imagenes/save_cache/'+document.form_cache_foto.nombre.value;
	val=eval('parent.document.form_foto.nombre_foto'+text);
	val.value = "foto_"+text;
	//alert(parent.document.form_foto.vista_foto1.src);
	//text--;
	/*if (parent.document.form_foto.vista_foto.length){
	alert(parent.document.form_foto.vista_foto.length);
		parent.document.form_foto.vista_foto[text].src = 'save_cache/'+document.form_cache_foto.nombre.value;}
	else
		parent.document.form_foto.vista_foto.src = 'save_cache/'+document.form_cache_foto.nombre.value;*/
		parent.document.form_limpiar_cache.nombre.value = document.form_cache_foto.nombre.value;
		parent.document.getElementById('limpiar_foto').src='limpiar_cache.php';
		parent.document.form_limpiar_cache.submit();
		}
	if(document.form_cache_foto.option.value==0){
	var text = document.form_cache_foto.pos.value;
	var tam = text.length;
	var pos = text.lastIndexOf('_');
	text = text.substr(pos+1,tam);
	val=eval('parent.document.form_foto.vista_foto'+text);
	val.src = 'imagenes/iconos/sombra.bmp';
	val=eval('parent.document.form_foto.nombre_foto'+text);
	val.value = '';
	}	
</script>
</body>
</html>