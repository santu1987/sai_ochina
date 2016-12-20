<? if (!$_SESSION) session_start();
?>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script>
$("#activar_filtro_2").click(function() {
	document.form_rp_filtro_codbar.filtro_codbar_bienes.disabled = true;
	document.form_rp_filtro_codbar.filtro_codbar_bienes.style.backgroundColor = '#999';
	getObj('filtro_codbar_btn_consulta_emergente').style.visibility='hidden';
	/* document.getElementById('filtro_codbar_btn_consulta_emergente').style.display='none'; */
	document.form_rp_filtro_codbar.fecha_desde1.disabled = false;
	document.form_rp_filtro_codbar.fecha_hasta1.disabled = false;
	document.form_rp_filtro_codbar.fecha_desde_filtro.style.backgroundColor = '#DBEAF5';
	document.form_rp_filtro_codbar.fecha_hasta_filtro.style.backgroundColor = '#DBEAF5';
	getObj('filtro_codbar_id_bienes').value='';
	getObj('filtro_codbar_bienes').value='';
});
$("#activar_filtro_1").click(function() {
	getObj('filtro_codbar_btn_consulta_emergente').style.visibility='visible';
	document.form_rp_filtro_codbar.filtro_codbar_bienes.disabled = false;
	document.form_rp_filtro_codbar.filtro_codbar_bienes.style.backgroundColor = '#DBEAF5';
	document.form_rp_filtro_codbar.fecha_desde1.disabled = true;
	document.form_rp_filtro_codbar.fecha_desde_filtro.style.backgroundColor = '#999';
	document.form_rp_filtro_codbar.fecha_hasta1.disabled = true;
	document.form_rp_filtro_codbar.fecha_hasta_filtro.style.backgroundColor = '#999';
	getObj('fecha_desde_filtro').value='';
	getObj('fecha_hasta_filtro').value='';
});
var dialog;
//-----------------------------------------------------------------------------------------------
$("#filtro_codbar_btn_imprimir").click(function() {
	if(getObj('filtro_codbar_bienes').value=="" && getObj('fecha_desde_filtro').value==""){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Debe Seleccionar un Activo!</p></div>",true,true);	
	}
	else{
		if(getObj('filtro_codbar_id_bienes').value!=""){
			url="modulos/bienes/bien/rp/vista_impresion_codigo_barra.php?id_bienes="+getObj('filtro_codbar_id_bienes').value;
		}
		else{
			url="modulos/bienes/bien/rp/vista_impresion_codigo_barra.php?fecha_desde="+getObj('fecha_desde_filtro').value+"&fecha_hasta="+getObj('fecha_hasta_filtro').value;
		}
		openTab('Lista Cod. Barra',url);
	}
	//alert('llevame a la pagina codbar');
});
$("#filtro_codbar_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/rp/vista.grid_reporte_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bienes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#filtro_codbar_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#filtro_codbar_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#filtro_codbar_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/rp/sql_reporte_bien_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('filtro_codbar_id_bienes').value=ret.id_bienes;
									getObj('filtro_codbar_bienes').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#filtro_codbar_nombre").focus();
								$('#filtro_codbar_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
/// ******************************************************************************
$("#filtro_codbar_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('filtro_codbar_id_bienes').value = '';
	getObj('filtro_codbar_bienes').value = '';
}
//
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
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
//
</script>
<div id="botonera">
	<img id="filtro_codbar_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="filtro_codbar_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_filtro_codbar" id="form_rp_filtro_codbar">
  <table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="6">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle"/> Impresion	Codigo de Barra</th>
	</tr>
    <tr>
			<th width="63"><p>
			  <label>
			    <input name="activar_filtro" type="radio" id="activar_filtro_1" value="1" checked="checked" onclick="botones();" />
			    &nbsp;Bien:</label>
			  <br />
			  <br />
		    </p></th>
		  <td colspan="4">          <ul class="input_con_emergente">
				<li>
           <input name="filtro_codbar_bienes" type="text"  id="filtro_codbar_bienes" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="filtro_codbar_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    <tr>
      <th width="63"><label>
        <input type="radio" name="activar_filtro" value="2" id="activar_filtro_2" onclick="habilita();"/>
      &nbsp;Desde:</label></th>
      <td width="42">
      	<input readonly="true" type="text" name="fecha_desde_filtro" id="fecha_desde_filtro" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}" onChange="fecha_depre();" disabled="false" style="background-color:#999"/>
          <button type="reset" id="fecha_desde1" disabled="false"> ...</button>
        <script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha_desde_filtro",      
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde1",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
      </td>
      <th width="45">Hasta:</th>
      <td width="209">
      <input readonly="true" type="text" name="fecha_hasta_filtro" id="fecha_hasta_filtro" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}" onChange="fecha_depre();" disabled="false" style="background-color:#999"/>
        <button type="reset" id="fecha_hasta1" disabled="false"> ...</button>
        <script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha_hasta_filtro",      
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_hasta1",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
      </td>
    </tr>
		<tr>
			<td colspan="6" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
  <input type="hidden" name="filtro_codbar_id_bienes" id="filtro_codbar_id_bienes" />
</form>