<script type="text/javascript">

$("#list_consulta_impuesto").jqGrid
({ 
	height:315,
	width:650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/adquisiones/impuesto/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Impuesto','Comentario'],
   	colModel:[
	   		{name:'id',index:'id', width:40,hidden:true},
			{name:'nombre',index:'nombre', width:150},
			{name:'comentario',index:'comentario', width:150}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_impuesto'),
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function impuesto_co_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(impuesto_co_gridReload,150)
} 

function impuesto_co_gridReload()
{ 
	var busq_nombre = jQuery("#adquisiciones_co_busq_impuesto").val(); 
	jQuery("#list_consulta_impuesto").setGridParam({url:"modulos/adquisiones/impuesto/co/sql.consulta.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#impuesto_co_btn_consultar").attr("disabled",state); 
}
//$('#adquisiciones_co_busq_impuesto').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Impuesto </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="adquisiciones_co_busq_impuesto">Nombre:</label><input type="text" name="adquisiciones_co_busq_impuesto" id="adquisiciones_co_busq_impuesto" onkeydown="impuesto_co_doSearch(arguments[0]||event)" message="Introduzca el nombre del tipo de impuesto"/>
			</div>
			<table id="list_consulta_impuesto" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_impuesto" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>