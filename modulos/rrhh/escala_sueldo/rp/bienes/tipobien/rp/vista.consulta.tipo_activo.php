<? if (!$_SESSION) session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

//
//
//----------------------------------------------------------------


$("#tipo_activo_rp_btn_imprimir").click(function() {
	desde = getObj('tipo_activo_rp_fecha_desde').value;
	dd = desde.substr(0,2);
	md = desde.substr(3,2);
	ad = desde.substr(6,4);
	hasta = getObj('tipo_activo_rp_fecha_hasta').value;
	dh = hasta.substr(0,2);
	mh = hasta.substr(3,2);
	ah = hasta.substr(6,4);
	var err_fech = 0;
	if(getObj('tipo_activo_rp_fecha_desde').value!='' || getObj('tipo_activo_rp_fecha_hasta').value!=''){
		if(ah==ad){
			if(md==mh && dd>=dh){
				//alert("La fecha 'Hasta' tiene que ser mayor que la fecha 'Desde'");
				err_fech = 1
			}
			if(md>mh){
				err_fech = 1;
			}
		}
		if(ad>ah){
			err_fech = 1;
		}
		if(getObj('tipo_activo_rp_fecha_desde').value=='' && getObj('tipo_activo_rp_fecha_hasta').value!=''){
			err_fech = 1;
		}
	}
	if(getObj('tipo_activo_rp_id_tipo_activo').value!=''){
		if(err_fech!=0)
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha Hasta tiene que ser mayor que la fecha Desde</p></div>",true,true);
		if(err_fech==0){	
		url = "pdfb.php?p=modulos/bienes/tipobien/rp/vista.lst.tipo_activo.php!id_tipo="+getObj('tipo_activo_rp_id_tipo_activo').value+"@fecha_desde="+getObj('tipo_activo_rp_fecha_desde').value+"@fecha_hasta="+getObj('tipo_activo_rp_fecha_hasta').value;
		openTab("ACTIVOS POR TIPO DE ACTIVO",url);
		}
	}
	else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Tiene que Seleccionar un Tipo de Activo</p></div>",true,true);
});

//
//
$("#sitio_fisico_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/tipobien/rp/vista.grid_tipo_bien_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente por Tipo de Activo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_activo_rp_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/rp/sql_tipo_bien_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_activo_rp_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_activo_rp_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/rp/sql_tipo_bien_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/tipobien/rp/sql_tipo_bien_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Unidad','Comentario'],
								colModel:[
									{name:'id_tipo_bienes',index:'id_tipo_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tipo_activo_rp_id_tipo_activo').value=ret.id_tipo_bienes;
									getObj('tipo_activo_rp_nombre_tipo_activo').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_activo_rp_nombre").focus();
								$('#tipo_activo_rp_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

// ******************************************************************************
$("#tipo_activo_rp_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
limpiar();
setBarraEstado("");
});
//
function limpiar(){
	getObj('tipo_activo_rp_id_tipo_activo').value = '';
	getObj('tipo_activo_rp_nombre_tipo_activo').value = '';
	getObj('tipo_activo_rp_fecha_desde').value='';
	getObj('tipo_activo_rp_fecha_hasta').value='';
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
$('#tipo_activo_db_nombre_tipo_activo').alpha({allow:' '});
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
	<img id="tipo_activo_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    
	<img id="tipo_activo_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  /></div>
    

<form name="form_rp_tipo_activo" id="form_rp_tipo_activo">
<input type="hidden" name="tipo_activo_rp_id_tipo_activo" id="tipo_activo_rp_id_tipo_activo" />

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Activos por Tipo Activo			</th>
	</tr>
    <tr>
			<th>Tipo Activo</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="tipo_activo_rp_nombre_tipo_activo" type="text"  id="tipo_activo_rp_nombre_tipo_activo" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Tipo Activo Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Tipo de Activo: '+$(this).val()]}"/>
           </li>
				<li id="sitio_fisico_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    	 <tr>
			<th>Fechas</th>
		  <td>          Desde: 
          <input readonly="true" type="text" name="tipo_activo_rp_fecha_desde" id="tipo_activo_rp_fecha_desde" size="7"  jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_desde_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tipo_activo_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script> 
          Hasta: 
          <input readonly="true" type="text" name="tipo_activo_rp_fecha_hasta" id="tipo_activo_rp_fecha_hasta" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Hasta: '+$(this).val()]}"/>
		<button type="reset" id="fecha_hasta_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tipo_activo_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_hasta_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>