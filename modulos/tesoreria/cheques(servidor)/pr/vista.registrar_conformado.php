<script language="javascript">
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
</script>
<script language="javascript" type="text/javascript">
//------------------ Marcaras de edicion de campos de entrada -----------------////

var dialog;
//----------------------------------------------------------------------------------------------------

//----------------------------------------------------------------


$("#cheque_conformado_btn_guardar").click(function() {										  
	if($('#form_db_cheques_conformados').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql_actualizar_conformado.php",
			data:dataForm('form_db_cheques_conformados'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_campos_cheques_conformados();
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#cheques_conformado_numero").change(function() {
	//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/tesoreria/cheques/pr/sql_consulta_automatica.php",
			data:dataForm('form_db_cheques_conformados'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if(html!='' ){
					arreglo = html.split('*');
					
					//alert(arreglo);
					getObj('fila2').style.display='';
					getObj('fila3').style.display='';
					getObj('fila4').style.display='';
					getObj('fila5').style.display='';
					getObj('fila6').style.display='';
					getObj('id_cheque').value=arreglo[0];
					getObj('num_cuenta').value=arreglo[1];
					getObj('beneficiario').value=arreglo[2];
					getObj('monto_cheque').value=arreglo[3];
					getObj('fecha_emitido').value=arreglo[4];
					getObj('fecha_pagado').value=arreglo[5];
					getObj('contacto').value=arreglo[6];
					getObj('contacto').focus();
					//consulta_automatica();
				}
				else if(html==''){
					setBarraEstado(mensaje[no_cheque],true,true);
					limpiar_campos_cheques_conformados();	
				}
			}
		});
});

$("#cheques_conformados_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
	limpiar_campos_cheques_conformados();
});
//
function limpiar_campos_cheques_conformados(){
	getObj('cheques_conformado_numero').value='';
	getObj('fila2').style.display='none';
	getObj('fila3').style.display='none';
	getObj('fila4').style.display='none';
	getObj('fila5').style.display='none';
	getObj('fila6').style.display='none';
	getObj('id_cheque').value='';
	getObj('num_cuenta').value='';
	getObj('beneficiario').value='';
	getObj('monto_cheque').value='';
	getObj('fecha_emitido').value='';
	getObj('fecha_pagado').value='';
	getObj('contacto').value='';
}
</script>
<div id="botonera">
	<img id="cheques_conformados_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="cheque_conformado_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_cheques_conformados" id="form_db_cheques_conformados">
  <table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Conformar	Cheques		</th>
	</tr>
    	<tr>
			<th width="173">Nº de Cheque:</th>
		  <td colspan="3"><input name="cheques_conformado_numero" type="text"  id="cheques_conformado_numero" size="15" maxlength="10" />
	      <input type="hidden" name="id_cheque" id="id_cheque" /></td>
		</tr>
    	<tr id="fila2" style="display:none">
			<th width="173">Nº de Cuenta:</th>
		  <td colspan="3"><label>
		    <input name="num_cuenta" type="text" disabled="disabled" id="num_cuenta" size="15" readonly="readonly" />
	      </label></td>
		</tr>
        <tr id="fila3" style="display:none">
			<th width="173">Beneficiario:</th>
		  <td colspan="3"><label>
		    <input name="beneficiario" type="text" disabled="disabled" id="beneficiario" size="50" readonly="readonly" />
		  </label></td>
		</tr>
        <tr id="fila4" style="display:none">
			<th>Monto:</th>
		  <td colspan="3"><input name="monto_cheque" type="text" disabled="disabled" id="monto_cheque" size="15" readonly="readonly" /></td>
		</tr>
        <tr id="fila5" style="display:none">
			<th>Fecha Emitido:</th>
		  <td width="117"><input name="fecha_emitido" type="text" disabled="disabled" id="fecha_emitido" size="7" readonly="readonly" />
             
		  </td>
		  <th width="107">Fecha Pagado:</th>
		  <td width="317"><input name="fecha_pagado" type="text" disabled="disabled" id="fecha_pagado" size="7" readonly="readonly" /></td>
		</tr>
        <tr id="fila6" style="display:none">
			<th>Persona Contacto:</th>
		  <td colspan="3"><input name="contacto" type="text"  id="contacto" size="30" maxlength="60" message="Introduzca la Descripción del Concepto " /></td>
		</tr>
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>