<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<a href="#" onclick="tiempo();">..::Prueba::..</a>
<div id="prueba" align="center" style="position:absolute; top:50%; left:40%; border:3px solid; border-color:#CCC; display:none">
<img id="foto_logo" src="logo/logo1.jpg" onclick="change_picture();"/>
<div><img id="foto_loading" src="logo/ajax-loader.gif"/></div>
</div>
<script language="javascript" type="text/javascript">
	document.getElementById('prueba').style.opacity='0.75';
	var c=0;
	var timer;
	function change_picture(){
		var text = document.foto_logo.src;
		var tam = text.length;
		var pos = text.lastIndexOf('/');
		text = text.substr(pos+1, tam-1);
		if (text=='logo1.jpg')
			document.foto_logo.src='logo/logo2.jpg';
		if (text=='logo2.jpg')
			document.foto_logo.src='logo/logo1.jpg';
		c++;
		if (c==4){
		clearInterval(timer);
		document.getElementById('prueba').style.display='none';
		alert('Fin');
		}
	}
		function tiempo(){
			c=0;
			document.getElementById('prueba').style.display='';
			timer =	setInterval("change_picture();",1000);
		}
</script>
<body>
</body>
</html>