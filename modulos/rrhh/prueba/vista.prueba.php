<? if (!$_SESSION) session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script>
var dialog;
$("#sitio_fisico_rp_btn_imprimir").click(function() {
		url = "pdfb.php?p=modulos/rrhh/prueba/vista.lst.prueba.php!monto="+getObj('monto').value+"@dias="+getObj('dias').value;
		openTab("Prueba",url);
	setBarraEstado("");
});
</script>
<div id="botonera">
	<img id="sitio_fisico_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
<form name="form_rp_sitio_fisico" id="form_rp_sitio_fisico">
  <table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Prueba	Funciones		</th>
	</tr>
        <tr>
			<th>Monto:</th>
		  <td><label>
		    <input type="text" name="monto" id="monto" />
	      </label></td>
		</tr>
    <tr>
  <th>Dias:</th>
          <td><label>
            <input type="text" name="dias" id="dias" />
          </label></td>
    </tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>