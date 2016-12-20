<script language="javascript" type="text/javascript" src="Calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link href="Calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<form method="post" name="form_fecha" id="form_fecha">
    <input type="text" id="fecha" name="fecha" onchange="enviar();"/>
</form>
<script language="javascript">
	if (parent.document.form_pr_cotizacion.cotizacion_pr_fecha.value!='')
		document.form_fecha.fecha.value = parent.document.form_pr_cotizacion.cotizacion_pr_fecha.value;
	//if (document.form_fecha.fecha.click){
		displayCalendar(document.form_fecha.fecha,'dd-mm-yyyy',this);
		document.form_fecha.fecha.style.display = 'none';
	//}
	function enviar(){
		parent.document.form_pr_cotizacion.cotizacion_pr_fecha.value = document.form_fecha.fecha.value;
		parent.document.getElementById('Calendario').style.visibility='hidden';
		parent.document.form_pr_cotizacion.cotizacion_pr_fecha.focus();
		//document.form_fecha.submit();
	}
</script>