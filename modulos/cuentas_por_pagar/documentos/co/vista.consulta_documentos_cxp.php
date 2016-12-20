<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$("#list_documentos_cxp").jqGrid
({ 
	height: 250,
	width: 750,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id documentos','Organismo','A&ntilde;o','id_proveedor','C&oacute;digo','Proveedor','Rif','Tipo Doc','N documento','N control','Fecha.V.','Monto Bruto','Base imponible','%IVA','%RET.IVA','%RET.ISLR','Ret1','Ret2','NºCompr','desc documento','Tipo doc','estatus','Monto'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'id_organismo',index:'id_organismo', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false},
									{name:'id_proveedor',index:'id_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'nombre_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:60,sortable:false,resizable:false},
									{name:'rif_proveedor',index:'rif_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp1',index:'tipo_documento_cxp1', width:40,sortable:false,resizable:false,hidden:true},
									{name:'numero_documento',index:'numero_documento', width:30,sortable:false,resizable:false,hidden:true},
									{name:'numero_control',index:'numero_control', width:50,sortable:false,resizable:false},
									{name:'fecha_vencimiento',index:'fecha_vencimiento', width:40,sortable:false,resizable:false},
									{name:'monto_bruto',index:'monto_bruto', width:60,sortable:false,resizable:false},
									{name:'base_imponible',index:'base_imponible', width:60,sortable:false,resizable:false},
									{name:'porcentaje_iva',index:'porcentaje_iva', width:30,sortable:false,resizable:false},
									{name:'porcentaje_ret_iva',index:'porcentaje_ret_iva', width:40,sortable:false,resizable:false},
									{name:'porcentaje_ret_islr',index:'porcentaje_ret_islr', width:40,sortable:false,resizable:false},
									{name:'ret1',index:'ret1', width:40,sortable:false,resizable:false},
									{name:'ret2',index:'ret2', width:40,sortable:false,resizable:false},
									{name:'numero_compromiso',index:'numero_compromiso', width:40,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:60,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:60,sortable:false,resizable:false}

								],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_documentos_cxp'),
   	sortname: 'Fecha.V',
    viewrecords: true,
	onSelectRow:function(id){
				
						var ret=jQuery("#list_documentos_cxp").getRowData(id);
						var url="modulos/cuentas_por_pagar/documentos/co/consulta_individual_documentos_est.php?id_factura="+ret.id+"&id_proveedor="+ret.id_proveedor;
						openTab("Factura N: "+ret.numero_documento,url);
				  },
    sortorder: "asc"
});
var timeoutHnd; 
var flAuto = true;
function cuentas_por_pagar_doc_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(cuentas_por_pagar_doc_gridReload,500)
} 
function cuentas_por_pagar_doc_gridReload()
{ 
	  var cuentas_por_pagar_busqueda_proveedor = jQuery("#cuentas_por_pagar_busqueda_proveedor").val(); 
	  var cuentas_por_pagar_busqueda_fecha = jQuery("#cuentas_por_pagar_busqueda_fecha").val(); 
  	  var cuentas_por_pagar_busqueda_tipo = jQuery("#cuentas_por_pagar_busqueda_tipo").val(); 
   	  var cuentas_por_pagar_busqueda_estatus = jQuery("#cuentas_por_pagar_busqueda_estatus").val(); 
	  jQuery("#list_documentos_cxp").setGridParam({url:"modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta.php?cuentas_por_pagar_busqueda_proveedor="+cuentas_por_pagar_busqueda_proveedor+"&cuentas_por_pagar_busqueda_fecha="+cuentas_por_pagar_busqueda_fecha+"&cuentas_por_pagar_busqueda_tipo="+cuentas_por_pagar_busqueda_tipo+"&cuentas_por_pagar_busqueda_estatus="+cuentas_por_pagar_busqueda_estatus,page:1}).trigger("reloadGrid"); 
	  url="modulos/cuentas_por_pagar/documentos/co/cmb.sql.documentos_consulta.php?cuentas_por_pagar_busqueda_proveedor="+cuentas_por_pagar_busqueda_proveedor+"&cuentas_por_pagar_busqueda_fecha="+cuentas_por_pagar_busqueda_fecha+"&cuentas_por_pagar_busqueda_tipo="+cuentas_por_pagar_busqueda_tipo+"&cuentas_por_pagar_busqueda_estatus="+cuentas_por_pagar_busqueda_estatus;
	//setBarraEstado(url);	
} 
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Documentos Cuentas Por Pagar </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="cuentas_por_pagar_busqueda_proveedor">Proveedor:</label> 
		 &nbsp; 
		 <input type="text" name="cuentas_por_pagar_busqueda_proveedor" id="cuentas_por_pagar_busqueda_proveedor"  maxlength="25" size="25" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" />
		 <label id="" for="cuentas_por_pagar_busqueda_fecha">
		 Fecha:</label> 
		<!-- &nbsp; <input type="text" name="cuentas_por_pagar_db_fecha_consulta" id="cuentas_por_pagar_db_fecha_consulta" maxlength="25" size="25" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" />
		 <a id="vista_calendario_fecha_doc" href="modulos/cuentas_por_pagar/documentos/co/fecha_documento.php" target="fecha_doc_iframe" onClick="mostrar_fecha_doc()"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/></a>	 
-->
		<label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_busqueda_fecha" id="cuentas_por_pagar_busqueda_fecha" size="7" value="<? echo $fecha ?>" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" onchange="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cuentas_por_pagar_db_fecha_consulta_oculto" id="cuentas_por_pagar_db_fecha_consulta_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="cuentas_por_pagar_db_fecha_boton_d" >...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_busqueda_fecha",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_db_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true         // double-click mode
						
							
					});
			</script>
	      </label>
		 Tipo
		 <label id="" for="cuentas_por_pagar_busqueda_tipo">:</label>
		 <input type="text" name="cuentas_por_pagar_busqueda_tipo" id="cuentas_por_pagar_busqueda_tipo" maxlength="20" size="20" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" /><br/>
		
		Estatus&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;    
		<label id="" for="cuentas_por_pagar_busqueda_estatus">:</label>
		  <select name="cuentas_por_pagar_busqueda_estatus" id="cuentas_por_pagar_busqueda_estatus" onchange="cuentas_por_pagar_doc_doSearch(arguments[0]||event)">
            <option id="0">SELECCIONE</option>
            <option id="1">Emitidos</option>
            <option id="2">Anulados</option>
          </select>
		  </div>
		  <div><br/>
		 
		<table id="list_documentos_cxp" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_documentos_cxp" class="scroll" style="text-align:center;"></div><br/>		</td>
	</tr>	
</table>


</script>
