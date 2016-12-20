<?php
	$pos = $_POST['posicion'];
	unlink('../../../../imagenes/save_cache/'.$_FILES['foto_desincorporacion'.$pos]['name']);
?>
<script language="javascript">
	parent.document.form_desin_foto.action='modulos/bienes/desincorporaciones/pr/vista.previa_foto.php';
	parent.document.form_desin_foto.target='form_vista_foto';
</script>