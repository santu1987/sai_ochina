<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$("#list_documentos_partidas_cxp").jqGrid
({ 
	height: 250,
	width: 750,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta_partidas.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id documentos','Organismo','Documento','tipo_id','Partidas','Total_Partidas'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'id_organismo',index:'id_organismo', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'tipo',index:'tipo', width:25,sortable:false,resizable:false},
									{name:'tipo_id',index:'tipo_id', width:20,sortable:false,resizable:false},
									{name:'partidas',index:'partidas', width:20,sortable:false,resizable:false},
									{name:'total',index:'total', width:60,sortable:false,resizable:false}
								],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_documentos_partidas_cxp'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd; 
var flAuto = true;
function cuentas_por_pagar_partidas_doc_doSearch(ev)
{	if(!flAuto) return; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(cuentas_por_pagar_doc_partida_gridReload,500)
} 
function cuentas_por_pagar_doc_partida_gridReload()
{ 
	  var cuentas_por_pagar_busqueda_fecha_partida = jQuery("#cuentas_por_pagar_busqueda_fecha_partidas").val(); 
  	  var cuentas_por_pagar_busqueda_tipo_partida = jQuery("#cuentas_por_pagar_busqueda_tipo_partidas").val(); 
	  jQuery("#list_documentos_partidas_cxp").setGridParam({url:"modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta_partidas.php?cuentas_por_pagar_busqueda_tipo_partida="+cuentas_por_pagar_busqueda_tipo_partida+"&cuentas_por_pagar_busqueda_fecha_partida="+cuentas_por_pagar_busqueda_fecha_partida,page:1}).trigger("reloadGrid"); 
	  url="modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta_partidas.php?cuentas_por_pagar_busqueda_tipo_partida="+cuentas_por_pagar_busqueda_tipo_partida+"&cuentas_por_pagar_busqueda_fecha_partida="+cuentas_por_pagar_busqueda_fecha_partida;
	//setBarraEstado(url);
} 
</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Documentos Seg&uacute;n Partidas </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="cuentas_por_pagar_busqueda_proveedor"></label>
		 <label id="" for="cuentas_por_pagar_busqueda_fecha">
		 Fecha:</label> 
		<!-- &nbsp; <input type="text" name="cuentas_por_pagar_db_fecha_consulta" id="cuentas_por_pagar_db_fecha_consulta" maxlength="25" size="25" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" />
		 <a id="vista_calendario_fecha_doc" href="modulos/cuentas_por_pagar/documentos/co/fecha_documento.php" target="fecha_doc_iframe" onClick="mostrar_fecha_doc()"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/></a>	 
-->		<label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_busqueda_fecha_partidas" id="cuentas_por_pagar_busqueda_fecha_partidas" size="7" value="<? echo $fecha ?>" onKeyDown="cuentas_por_pagar_partidas_doc_doSearch(arguments[0]||event)" onChange="cuentas_por_pagar_partidas_doc_doSearch(arguments[0]||event)" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cuentas_por_pagar_busqueda_fecha_partidas" id="cuentas_por_pagar_busqueda_fecha_partidas" value="<? echo $fecha ?>"/>
	      <button type="reset" id="cuentas_por_pagar_db_fecha_boton_partidas_d" >...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_busqueda_fecha_partidas",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_db_fecha_boton_partidas_d",   // trigger for the calendar (button ID)
						singleClick    :    true         // double-click mode
					});
			</script>
	      </label>
		 Documentos
		 <label id="" for="cuentas_por_pagar_busqueda_tipo_partidas">:</label>
		 <input type="text" name="cuentas_por_pagar_busqueda_tipo_partidas" id="cuentas_por_pagar_busqueda_tipo_partidas" maxlength="20" size="20" onKeyDown="cuentas_por_pagar_partidas_doc_doSearch(arguments[0]||event)" /><br/>
		  </div>
		  <div><br/>
		<table id="list_documentos_partidas_cxp" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_documentos_partidas_cxp" class="scroll" style="text-align:center;"></div><br/>		</td>
	</tr>	
</table>
</script>
