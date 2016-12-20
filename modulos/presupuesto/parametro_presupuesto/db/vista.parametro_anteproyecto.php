<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script>
var dialog;
$("#parametro_cierre_antepresupuesto_db_guardar").click(function() {
	
	if(($('#form_parametro_cierre_antepresupuesto').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/presupuesto/parametro_presupuesto/db/sql.parametro_presupuesto.php",
			data:dataForm('form_parametro_cierre_antepresupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_parametro_cierre_antepresupuesto');
					getObj('parametro_cierre_antepresupuesto_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#parametro_cierre_antepresupuesto_db_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_parametro_cierre_antepresupuesto').jVal())
	{
		$.ajax (
		{
			url: "modulos/presupuesto/parametro_presupuesto/db/sql.actualizar_parametro.php",
			data:dataForm('form_parametro_cierre_antepresupuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('parametro_cierre_antepresupuesto_db_actualizar').style.display='none';
					clearForm('form_parametro_cierre_antepresupuesto');
					getObj('parametro_cierre_antepresupuesto_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
</script>
<div id="botonera">
	<img id="parametro_cierre_antepresupuesto_db_actualizar" class="btn_actualizar"src="imagenes/null.gif"  />		
   <!-- <img id="parametro_cierre_antepresupuesto_db_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" />-->
</div>
<form name="form_parametro_cierre_antepresupuesto" id="form_parametro_cierre_antepresupuesto">
	<table class="cuerpo_formulario">
      <tr>
            <th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Parametros de cierre de Ante Proyecto de Presupuesto</th>
      </tr>
      <tr>	
      	<th>Fecha de cierre AnteProyecto </th>
        <td>
        <input name="parametro_cierre_antepresupuesto_db_fecha_cierre_mes" type="text" id="parametro_cierre_antepresupuesto_db_fecha_cierre_mes" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_cierre_antepresupuesto_db_fecha_mes_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_cierre_antepresupuesto_db_fecha_cierre_mes",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_cierre_antepresupuesto_db_fecha_mes_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
        </td>
      </tr>
      <tr>
        <td colspan="2" class="bottom_frame">&nbsp;
       <tr>
	</table>
    <span class="bottom_frame"><span class="titulo_frame">
	</span></span>
</form>