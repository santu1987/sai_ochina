<?php
session_start();
?>
<link rel="stylesheet" type="text/ccs" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.ccs" title="Aqua"/>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-setup.js"></script>
<script type="text/javascript">


$("#contabilidad_apertura_db_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_contabilidad_apertura_contable');//


});	


$("#contabilidad_apertura_db_btn_guardar").click(function (){
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax(
		{
			url:"modulos/contabilidad/parametros_contabilidad/db/sql_abrir_mes.php",
			data:dataForm('form_contabilidad_apertura_contable'),
			type:'POST',
			cache: false,
			success:function(html)
			{
			setBarraEstado(html);
			//alert(html);
			
				if (html=="Actualizado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />M&Oacute;DULO ABIERTO</p></div>",true,true);
					clearForm('form_contabilidad_apertura_contable');
				}
				else if (html=="no_ultimo_mes")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N</p></div>",true,true);
					clearForm('form_contabilidad_apertura_contable');		
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZ&Oacute; LA OPERACI&Oacute;N </p></div>",true,true);	
				}
					else
				{
					alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
//
				}
			}
		});




	if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	
</script>
<div id="botonera">
	<img id="contabilidad_apertura_db_btn_cancelar" class="btn_cancelar" src="imagenes/null.gif"/>
	<img id="contabilidad_apertura_db_btn_guardar"  src="imagenes/iconos/abrir_orden_cxp.png"/>
	<img id="contabilidad_apertura_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif" style="display:none"/>

</div>
<form method="post" id="form_contabilidad_apertura_contable" name="form_contabilidad_apertura_contable">
<input type="hidden" id="contabilidad_apertura_contable_id" name="contabilidad_apertura_contable_id"/>
	
	<table class="cuerpo_formulario">	
		<tr>
				<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmidel"/>Reverso ultimo mes cerrado</th>		
		</tr>
		<tr>
			<th>Comentarios:</th>
			<td>
				<textarea id="contabilidad_apertura_contable_comentarios" name="contabilidad_apertura_contable_comentarios" cols="60"/>			</td>
		</tr>	
         <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>
</form>