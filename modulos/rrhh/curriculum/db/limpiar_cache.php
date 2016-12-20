<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<?php
echo "hola ";
unlink("../../../../imagenes/save_cache/".$_FILES['curriculum_db_imagen']['name']);
echo "<script>parent.document.form_db_foto.action='modulos/rrhh/curriculum/db/vista.previa.php'; parent.document.form_db_foto.target='vista_previa';</script>";
?>
<body>
</body>
</html>