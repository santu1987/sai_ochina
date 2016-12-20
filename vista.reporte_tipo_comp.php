<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//


</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_tipo_comprobante_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#contabilidad_tipo_comprobante_db_codigo_comprobante').numeric({});

$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
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
	<img id="contabilidad_tipo_comprobante_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_tipo_comprobante_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_tipo_comprobante_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_tipo_comprobante_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_tipo_comprobante_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_tipo_comprobante" name="form_contabilidad_db_tipo_comprobante">
	<input   type="hidden" name="contabilidad_tipo_comprobante_db_id"  id="contabilidad_tipo_comprobante_db_id" />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="3"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Relaci&oacute;n de Comprobantes Emitidos seg&uacute;n Tipo </th>
	</tr>
	<tr>
		<th>
			Tipo de Comprobante:		</th>
			
		<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="contabilidad_rel_comp_pr_tipo" id="contabilidad_rel_comp_pr_tipo"  size='12' maxlength="12" onchange="consulta_manual_tipo_comprobante()" 
				message="Introduzca el tipo de cuenta" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="contabilidad_rel_comp_pr_tipo_id" name="contabilidad_rel_comp_pr_tipo_id"  value=""
				 jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
			 </li>
			<li id="contabilidad_comp_btn_consultar_tipo" class="btn_consulta_emergente"></li>
			</ul>		</td>
	</tr>
		<tr>
		<th>
			Fecha:		</th>
		<td>
			  <input alt="date" type="text" name="contabilidad_comp_pr_fecha" id="contabilidad_comp_pr_fecha" size="10" value="<? echo ($fecha_comprobante);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" onchange="v_fecha();"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="contabilidad_comp_pr_fecha_oculto" id="contabilidad_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="contabilidad_comp_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_comp_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_comp_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_comp_pr_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>		</td>
	</tr>
	 <tr>
	 	<th height="22" colspan="2"></th>
      </tr>
	</table>	
</form>