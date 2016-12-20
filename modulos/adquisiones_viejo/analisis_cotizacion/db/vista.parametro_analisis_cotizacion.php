<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>
<script>
$("#parametro_analisis_db_btn_guardar").click(function(){
	if($('#form_parametro_analisis_cotizacion').jVal())
	{
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/analisis_cotizacion/db/sql.parametro_analisis_cotizacion.php",
			data:dataForm('form_parametro_analisis_cotizacion'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_parametro_analisis_cotizacion');
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});
//************************************************************************
$("#parametro_analisis_db_btn_actualizar").click(function(){
	if($('#form_parametro_analisis_cotizacion').jVal())
	{
		//setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/analisis_cotizacion/db/sql.actualizar.php",
			data:dataForm('form_parametro_analisis_cotizacion'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_parametro_analisis_cotizacion');
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});
//************************************************************************
//************************************************************************
$("#parametro_analisis_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/analisis_cotizacion/db/grid_analisis.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:300,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.parametro_analisis.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Aspecto', 'Peso'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:250,sortable:false,resizable:false},
									{name:'peso',index:'peso', width:50,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('parametro_analisis_db_id').value = ret.id;
									getObj('parametro_analisis_db_aspecto').value = ret.nombre;
									getObj('parametro_analisis_db_peso').value = ret.peso;
									getObj('parametro_analisis_db_btn_actualizar').style.display='';
									getObj('parametro_analisis_db_btn_guardar').style.display='none';
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
								sortname: 'id_parametro_analisis_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//************************************************************************
//************************************************************************
$("#parametro_analisis_db_btn_cancelar").click(function() {
	getObj('parametro_analisis_db_btn_actualizar').style.display='none';
	getObj('parametro_analisis_db_btn_guardar').style.display='';
	clearForm('form_parametro_analisis_cotizacion');
});
//************************************************************************

var timeoutHnd; 
var flAuto = true;

function proyecto_doSearch(ev)
{ 
	if(!flAuto) return; 
 var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proyecto_gridReload,150)
}
</script>
<div id="botonera">
	<img id="parametro_analisis_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="parametro_analisis_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="parametro_analisis_db_btn_consultar" name="parametro_analisis_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="parametro_analisis_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="parametro_analisis_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_parametro_analisis_cotizacion" id="form_parametro_analisis_cotizacion">
<input type="hidden" name="parametro_analisis_db_id" id="parametro_analisis_db_id">
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Partametros Analisis Cotizaci&oacute;n</th>
	</tr>
	<tr>
		<th>Aspecto</th>
		<td><input type="text" name="parametro_analisis_db_aspecto" id="parametro_analisis_db_aspecto" size="50"></td>
	</tr>
	<tr>
		<th>Peso</th>
		<td>
			<input type="text" name="parametro_analisis_db_peso" id="parametro_analisis_db_peso" style="text-align:right" size="8" alt="integer-c">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>	

</table>
</form>