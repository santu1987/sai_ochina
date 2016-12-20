<script type="text/javascript">

$("#list_consulta_proveedor").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/adquisiones/proveedor/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Proveedor','RIF', 'RNC','Persona Contacto','Tel&eacute;fono','Ramo'],
   	colModel:[
	   		{name:'id',index:'id', width:40,hidden:true},
			{name:'nombre',index:'nombre', width:150},
			{name:'rif',index:'rif', width:80},
			{name:'rnc',index:'rnc', width:80,hidden:true},
			{name:'contacto',index:'contacto', width:100},
			{name:'telefono',index:'telefono', width:80},
			{name:'ramo',index:'ramo', width:80}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_proveedor'),
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function proveedor_co_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proveedor_co_gridReload,150)
} 

function proveedor_co_gridReload()
{ 
	var busq_nombre = jQuery("#busqueda_nombre").val(); 
	var busqueda_ramo = jQuery("#busqueda_ramo").val(); 

	jQuery("#list_consulta_proveedor").setGridParam({url:"modulos/adquisiones/proveedor/co/sql.consulta.php?busq_nombre="+busq_nombre+"&busqueda_ramo="+busqueda_ramo,page:1}).trigger("reloadGrid"); 
} 

function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#proveedor_co_btn_consultar").attr("disabled",state); 
}
$('#busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Proveedores </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				
				<label id="" nombre for="busqueda_nombre">Proveedor:</label><input type="text" name="busqueda_nombre" id="busqueda_nombre" onkeydown="proveedor_co_doSearch(arguments[0]||event)" message="Introduzca el nombre del Proveedor"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label id="" nombre for="busqueda_ramo">Ramo:</label><input type="text" name="busqueda_ramo" id="busqueda_ramo" onkeydown="proveedor_co_doSearch(arguments[0]||event)" message="Introduzca el nombre del Ramo"/>
			</div>
			<table id="list_consulta_proveedor" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_proveedor" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>