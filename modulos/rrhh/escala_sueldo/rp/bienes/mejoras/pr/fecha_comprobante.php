<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<link href="Calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="Calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>

<body>
<form id="form1" name="form1" method="post" action="">
  <label>
    <input type="text" name="fecha" id="fecha" value="<?php echo date("d-m-Y");?>" onclick="displayCalendar(document.form1.fecha,'dd-mm-yyyy',this)" onchange="enviar()"/>
  </label>
</form>
</body>
</html>
<script language="javascript">
	if(parent.document.form_mejoras_bien.mejoras_bien_pr_fecha_comprobante.value!='')
		document.form1.fecha.value=parent.document.form_mejoras_bien.mejoras_bien_pr_fecha_comprobante.value;
	document.form1.fecha.style.display='none';	
	displayCalendar(document.form1.fecha,'dd-mm-yyyy',this);
	//document.form1.fecha.style.display = 'none';
	function enviar(){
		parent.document.form_mejoras_bien.mejoras_bien_pr_fecha_comprobante.value = document.form1.fecha.value;
		parent.document.getElementById('fecha_comprobante').style.visibility = 'hidden';
		parent.document.form_mejoras_bien.mejoras_bien_pr_fecha_comprobante.focus();
	}
</script>