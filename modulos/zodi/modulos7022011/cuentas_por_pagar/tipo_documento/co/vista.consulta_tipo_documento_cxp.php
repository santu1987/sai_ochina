<script type="text/javascript">
$("#list_tipo_documento_cxp").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/tipo_documento/db/sql_grid_tipo_documento.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['id_tipo_documento','Documento','Siglas','comentarios'],
   	colModel:[
			{name:'id_tipo_documento',index:'id_tipo_documento', width:20,hidden:true},
			{name:'documento',index:'documento', width:180},
			{name:'siglas',index:'siglas',width:170},
			{name:'comentarios',index:'comentarios',width:170}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_tipo_documento_cxp'),
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
		    <table id="list_tipo_documento_cxp" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_tipo_documento_cxp" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>