<?php
session_start();
?>
<link rel="stylesheet" type="text/ccs" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.ccs" title="Aqua"/>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-setup.js"></script>
<script type="text/javascript">


$("#tesoreria_integracion_db_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_tesoreria_integracion_contable');//


});	

$("#tesoreria_db_btn_consultar_integracion_tipo").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/integracion_contable/pr/sql_grid_tipo_comprobante.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#tesoreria_integracion_contable_tipo').val(ret.codigo);
									$('#tesoreria_integracion_contable_tipo_id').val(ret.id);
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}	
});
$("#tesoreria_integracion_db_btn_guardar").click(function (){
if($('#form_tesoreria_integracion_contable').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax(
		{
			url:"modulos/tesoreria/integracion_contable/pr/sql.integrar.php",
			data:dataForm('form_tesoreria_integracion_contable'),
			type:'POST',
			cache: false,
			success:function(html)
			{
			setBarraEstado(html);
		//	alert(html);
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_integracion_contable');
					
				}
				else if (html=="registros_integrados")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N REGISTROS YA INTEGRADOS</p></div>",true,true);
					clearForm('form_tesoreria_integracion_contable');		
				}
				else if (html=="NoRegistro")
				{
					clearForm('form_tesoreria_integracion_contable');		
				}
				else if(html=="valor_iva")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REGISTRE LA CUENTA CONTABLE DEL IVA PARA REALIZAR LA INTEGRACI&Oacute;N</p></div>",true,true);	
				}
					else
				{
					alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
//
				}
			}
		});
	}



/*$('#tesoreria_banco_db_telefono').numeric({allow:'/-'});
$('#tesoreria_banco_db_codigoarea').numeric({allow:'/-'});
$('#tesoreria_banco_db_fax').numeric({allow:'/-'});
$('#tesoreria_banco_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$("input, select, textarea").bind("focus", function(){
*/	
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
	<img id="tesoreria_integracion_db_btn_cancelar" class="btn_cancelar" src="imagenes/null.gif"/>
	<img id="tesoreria_integracion_db_btn_guardar" src="imagenes/iconos/integrar.png"  style="width:100px height:100px"/>
	<img id="tesoreria_integracion_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif" style="display:none"/>

</div>
<form method="post" id="form_tesoreria_integracion_contable" name="form_tesoreria_integracion_contable">
<input type="hidden" id="tesoreria_integracion_contable_id" name="tesoreria_integracion_contable_id"/>
	
	<table class="cuerpo_formulario">	
		<tr>
				<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmidel"/>Integraci&oacute;n Contable</th>		
		</tr>
		<tr>
			<th>Comentarios:</th>
			<td>
				<textarea id="tesoreria_integracion_contable_comentarios" name="tesoreria_integracion_contable_comentarios" cols="60"/>			</td>
		</tr>	
         <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>
   </form> 
