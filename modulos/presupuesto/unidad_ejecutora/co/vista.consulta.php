<script type="text/javascript">
$('#unidad_ejecutora_busqueda_nombre').focus();
$("#list_consulta_unidad_ejecutora").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/unidad_ejecutora/co/sql.unidad_ejecutora_consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Unidad Ejecutora','Comentario'],
   	colModel:[
	   		{name:'unidad_ejecutora_id',index:'unidad_ejecutora_id', width:50,hidden:true},
			{name:'unidad_ejecutora_unidad_ejecuccion',index:'unidad_ejecutora_unidad_ejecuccion', width:200},
			{name:'unidad_ejecutora_comentario',index:'unidad_ejecutora_comentario', width:200},
   	],
	pager: jQuery('#pager_consulta_unidad_ejecutora'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_unidad_ejecutora',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function unidad_ejecutora_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(unidad_ejecutora_gridReload,150)
} 

function unidad_ejecutora_gridReload()
{ 
	var unidad_ejecutora_busqueda_nombre = jQuery("#unidad_ejecutora_busqueda_nombre").val(); 
	jQuery("#list_consulta_unidad_ejecutora").setGridParam({url:"modulos/presupuesto/unidad_ejecutora/co/sql.unidad_ejecutora_consulta.php?unidad_ejecutora_busq_nombre="+unidad_ejecutora_busqueda_nombre}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{
	flAuto = state;
	jQuery("#unidad_ejecutora_co_btn_consultar").attr("enable",state); 
}

//$('#unidad_ejecutora_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Unidad Ejecutora </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			
				<label id="" nombre for="unidad_ejecutora_busqueda_nombre">Nombre:</label><input type="text" name="unidad_ejecutora_busqueda_nombre" id="unidad_ejecutora_busqueda_nombre" onkeydown="unidad_ejecutora_doSearch(arguments[0]||event)"  />
			</div>
			
			<table id="list_consulta_unidad_ejecutora" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_unidad_ejecutora" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>