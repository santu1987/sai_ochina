<script type="text/javascript">
$("#list_documentos_cxp").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+new Date().getTime(),
	datatype: "json",
   		colNames:['&ordm;Id documentos','Año','id_proveedor','Codigo','Proveedor','Rif','Tipo Doc','Nºdoc','Nºcontrol','Fecha.V.','Monto.B.','Base imp','%IVA','%RET.IVA','%RET.ISLR','NºComp','desc documento','Tipo doc','Total Fact'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'nombre_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'rif_proveedor',index:'rif_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:40,sortable:false,resizable:false,hidden:true},
									{name:'numero_documento',index:'numero_documento', width:40,sortable:false,resizable:false,hidden:true},
									{name:'numero_control',index:'numero_control', width:50,sortable:false,resizable:false},
									{name:'fecha_vencimiento',index:'fecha_vencimiento', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto_bruto',index:'monto_bruto', width:50,sortable:false,resizable:false},
									{name:'base_imponible',index:'base_imponible', width:50,sortable:false,resizable:false},
									{name:'porcentaje_iva',index:'porcentaje_iva', width:30,sortable:false,resizable:false},
									{name:'porcentaje_ret_iva',index:'porcentaje_ret_iva', width:60,sortable:false,resizable:false},
									{name:'porcentaje_ret_islr',index:'porcentaje_ret_islr', width:60,sortable:false,resizable:false},
									{name:'numero_compromiso',index:'numero_compromiso', width:40,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:60,sortable:false,resizable:false,hidden:true},
									{name:'total',index:'total', width:60,sortable:false,resizable:false}

   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_documentos_cxp'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

/*function cuentas_por_pagar_tipo_docuemnto_consulta_doSearch(ev)
{ 
	if(!flAuto) return; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(cuentas_por_pagar_tipo_docuemnto_consulta_gridReload,500)
} 

function cuentas_por_pagar_tipo_docuemnto_consulta_gridReload()
{ 
	jQuery("#list_tipo_documento_cxp").setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/co/sql.consulta.php",page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}*/
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Tipo de Documentos  </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		    <table id="list_documentos_cxp" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_documentos_cxp" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>