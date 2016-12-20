<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>
<form id="formu_iframe" name="formu_iframe" method="post" action="">
  <input type="hidden" name="iframe_id_bienes" id="iframe_id_bienes" value="<?php echo $_GET['id_bienes'];?>"/>
  <input type="hidden" name="iframe_fecha_desde" id="iframe_fecha_desde" value="<?php echo $_GET['fecha_desde'];?>"/>
  <input type="hidden" name="iframe_fecha_hasta" id="iframe_fecha_hasta" value="<?php echo $_GET['fecha_hasta'];?>"/>
<script language="javascript" type="text/javascript">
var id_bienes=document.formu_iframe.iframe_id_bienes.value;
var fecha_desde=document.formu_iframe.iframe_fecha_desde.value;
var fecha_hasta=document.formu_iframe.iframe_fecha_hasta.value;
var src;
if(id_bienes!=""){
	src="modulos/bienes/bien/rp/lista_codigo_barra.php?id_bienes="+id_bienes;
	document.getElementById('frame_lista').src=src;
	//alert(src);
}
else{
	src="modulos/bienes/bien/rp/lista_codigo_barra.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta;
	document.getElementById('frame_lista').src=src;
	//alert(src);
}
</script>
</form>
<iframe name="frame_lista" id="frame_lista"  style=" margin-top:0px; border:#FFF ridge 0px" width="100%" height="390px">

</iframe>
</body>
</html>