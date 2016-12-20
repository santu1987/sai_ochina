<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<div id="prueba" align="center" style="display:none; position:absolute; top:17%; left:6%; border:3px solid; border-color:#CCC; background:#FFF; z-index:2">
<img id="foto_logo" src="../../../../imagenes/iconos/logo1.jpg" onclick="change_picture();"/>
<div><img id="foto_loading" src="../../../../imagenes/iconos/ajax-loader.gif"/></div>
</div>
<div id="msj" align="center" style="width:auto; top:17%; left:6%; height:auto;" ><p><img align="absmiddle" src="../../../../imagenes/iconos/filetypes.png" style="width:80px" />&nbsp;&nbsp;&nbsp;Se Actualizaron las Fotos con Exito...</p></div>
<body>
</body>
</html>
<script language="javascript">
var timer;
var c=0;
function change_picture(){
		var text = document.foto_logo.src;
		var tam = text.length;
		var pos = text.lastIndexOf('/');
		text = text.substr(pos+1, tam-1);
		if (text=='logo1.jpg')
			document.foto_logo.src='../../../../imagenes/iconos/logo2.jpg';
		if (text=='logo2.jpg')
			document.foto_logo.src='../../../../imagenes/iconos/logo1.jpg';
		c++;
		if (c==4){
		clearInterval(timer);
		document.getElementById('prueba').style.display='none';
		document.getElementById('msj').style.display='';
		c=0;
		}
}
function tiempo(){
		document.getElementById('msj').style.display='none';
		document.getElementById('prueba').style.display='';
		timer =	setInterval("change_picture();",400);
	}
	tiempo();
    </script>