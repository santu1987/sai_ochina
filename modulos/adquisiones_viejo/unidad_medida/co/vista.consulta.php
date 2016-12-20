<script type="text/javascript">

$("#list_consulta_unidad_medida").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/adquisiones/unidad_medida/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Unidad de Medida','Comentario'],
   	colModel:[
	   		{name:'id',index:'id', width:40,hidden:true},
			{name:'nombre',index:'nombre', width:150},
			{name:'comentario',index:'comentario', width:150}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_unidad_medida'),
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(gridReload,150)
} 

function gridReload()
{ 
	var busq_nombre = jQuery("#busqueda_nombre").val(); 
	jQuery("#list_consulta_unidad_medida").setGridParam({url:"modulos/adquisiones/unidad_medida/co/sql.consulta.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#unidad_medida_co_btn_consultar").attr("disabled",state); 
}
$('#busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Unidad Medida </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="busqueda_nombre">Nombre:</label><input type="text" name="busqueda_nombre" id="busqueda_nombre" onkeydown="doSearch(arguments[0]||event)" message="Introduzca el nombre de la unidad ejecutora"/>
			</div>
			<table id="list_consulta_unidad_medida" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_unidad_medida" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>