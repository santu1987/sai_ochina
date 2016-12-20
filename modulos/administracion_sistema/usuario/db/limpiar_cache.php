<?php
	unlink('../../../../imagenes/save_cache/'.$_FILES['foto']['name']);
	echo "<script>parent.document.form_foto.action='modulos/administracion_sistema/usuario/db/foto.php'; parent.document.form_foto.target='resultado';</script>";
?>