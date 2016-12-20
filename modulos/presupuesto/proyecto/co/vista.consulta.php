<script type="text/javascript">

$('#proyecto_busqueda_proyecto').focus();
$("#list_consulta_proyecto").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/proyecto/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Nombre','Comentario'],
   	colModel:[
	   		{name:'proyecto_id',index:'proyecto_id', width:50,hidden:true},
			{name:'proyecto_nombre',index:'proyecto_nombre', width:200},
			{name:'comentario',index:'comentario', width:200}
   	],
	pager: jQuery('#pager_consulta_proyecto'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_proyecto',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function proyecto_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(proyecto_gridReload,150)
} 

function proyecto_gridReload()
{ 
	var proyecto_busqueda_proyecto = jQuery("#proyecto_busqueda_proyecto").val(); 
	var proyecto_busqueda_busqueda_anio = jQuery("#proyecto_busqueda_busqueda_anio").val(); 
	jQuery("#list_consulta_proyecto").setGridParam({url:"modulos/presupuesto/proyecto/co/sql.consulta.php?busq_proyecto="+proyecto_busqueda_proyecto+"&busq_anio="+proyecto_busqueda_busqueda_anio,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#proyecto_co_btn_consultar").attr("enable",state); 
}
//$('#proyecto_busqueda_proyecto').alpha({nocaps:true,allow:'´'})
//$('#proyecto_busqueda_proyecto').alpha({nocaps:true,allow:'´'});
</script>

<div id="botonera">
	<img id="proyecto_co_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onclick="proyecto_gridReload()"  />
</div>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Proyecto</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="proyecto_busqueda_proyecto">Nombre:</label><input type="text" name="proyecto_busqueda_proyecto" id="proyecto_busqueda_proyecto" onkeydown="proyecto_doSearch(arguments[0]||event)" />&nbsp;&nbsp;<select name="proyecto_busqueda_busqueda_anio" id="proyecto_busqueda_busqueda_anio" onchange="proyecto_doSearch(arguments[0]||event)">
				<option id="2010">2010</option>
				<option id="2011">2011</option>
				</select>
			</div>
			
			<table id="list_consulta_proyecto" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_proyecto" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>