<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<?php
	echo "Hola: ".$_FILES['foto']['name'];
	unlink('../../../../imagenes/save_cache/'.$_FILES['foto']['name']);
	echo "<script>parent.document.form_foto.action='modulos/zodi/ficha_personal/db/foto.php'; parent.document.form_foto.target='resultado';</script>";
?>
<body>
</body>
</html>