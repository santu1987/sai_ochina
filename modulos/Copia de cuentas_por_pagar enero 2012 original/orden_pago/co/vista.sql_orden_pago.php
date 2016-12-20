<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$("#list_orden_pago").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/orden_pago/co/cmb.sql.proveedor_orden.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['id_orden','Orden de Pago','Facturas','id_proveedor','Codigo','Proveedor','rif','ano','fecha_1','comentarios','Fecha','estatus'],
								colModel:[
									{name:'id_orden',index:'id_orden', width:100,sortable:false,resizable:false,hidden:true},
									{name:'orden',index:'orden', width:100,sortable:false,resizable:false},
									{name:'documentos',index:'documentos', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha1',index:'fecha1', width:100,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false,hidden:true}
									
								
								],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_orden_pago'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function cuentas_por_pagar_orden_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(cuentas_por_pagar_orden_gridReload,500)
} 

function cuentas_por_pagar_orden_gridReload()
{ 
	  var cuentas_por_pagar_orden_busqueda_proveedor = jQuery("#cuentas_por_pagar_orden_busqueda_proveedor").val(); 
	  var cuentas_por_pagar_orden_busqueda_fecha = jQuery("#cuentas_por_pagar_orden_busqueda_fecha").val(); 
     jQuery("#list_orden_pago").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/co/cmb.sql.proveedor_orden.php?cuentas_por_pagar_orden_busqueda_proveedor="+cuentas_por_pagar_orden_busqueda_proveedor+"&cuentas_por_pagar_orden_busqueda_fecha="+cuentas_por_pagar_orden_busqueda_fecha,page:1}).trigger("reloadGrid"); 
		url="modulos/cuentas_por_pagar/orden_pago/co/cmb.sql.proveedor_orden.php?cuentas_por_pagar_orden_busqueda_proveedor="+cuentas_por_pagar_orden_busqueda_proveedor+"&cuentas_por_pagar_orden_busqueda_fecha="+cuentas_por_pagar_orden_busqueda_fecha;	  //alert(url);
	  //setBarraEstado(url);	

} 
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Orden De Pago </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		    <td class="celda_consulta">
			 <div class="div_busqueda">
			 <label id="" for="cuentas_por_pagar_orden_busqueda_proveedor">Proveedor:</label> 
			 &nbsp; 
			 <input type="text" name="cuentas_por_pagar_orden_busqueda_proveedor" id="cuentas_por_pagar_orden_busqueda_proveedor"  maxlength="25" size="25" onKeyDown="cuentas_por_pagar_orden_doSearch(arguments[0]||event)" />
			 <label id="" for="cuentas_por_pagar_orden_busqueda_fecha">
			 Fecha:</label> 
			<!-- &nbsp; <input type="text" name="cuentas_por_pagar_db_fecha_consulta" id="cuentas_por_pagar_db_fecha_consulta" maxlength="25" size="25" onKeyDown="cuentas_por_pagar_doc_doSearch(arguments[0]||event)" />
			 <a id="vista_calendario_fecha_doc" href="modulos/cuentas_por_pagar/documentos/co/fecha_documento.php" target="fecha_doc_iframe" onClick="mostrar_fecha_doc()"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/></a>	 
	-->
			<label>
			  <input readonly="true" type="text" name="cuentas_por_pagar_orden_busqueda_fecha" id="cuentas_por_pagar_orden_busqueda_fecha" size="7" value="<? echo $fecha ?>" onKeyDown="cuentas_por_pagar_orden_doSearch(arguments[0]||event)" onchange="cuentas_por_pagar_orden_doSearch(arguments[0]||event)" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
				jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
			  <input type="hidden"  name="cuentas_por_pagar_orden_db_fecha_consulta_oculto" id="cuentas_por_pagar_orden_db_fecha_consulta_oculto" value="<? echo $fecha ?>"/>
			  <button type="reset" id="cuentas_por_pagar_orden_db_fecha_boton_d" >...</button>
			  <script type="text/javascript">
						Calendar.setup({
							inputField     :    "cuentas_por_pagar_orden_busqueda_fecha",      // id of the input field
							ifFormat       :    "%d/%m/%Y",       // format of the input field
							showsTime      :    false,            // will display a time selector
							button         :    "cuentas_por_pagar_orden_db_fecha_boton_d",   // trigger for the calendar (button ID)
							singleClick    :    true         // double-click mode
							
								
						});
				</script>
			  </label>
			<br/>
			 </div>
			 <div><br/>
		    <table id="list_orden_pago" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_orden_pago" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>