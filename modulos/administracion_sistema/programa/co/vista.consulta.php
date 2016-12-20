<script type="text/javascript">

$("#list_consulta_programa").jqGrid
({ 
	height: 240,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/administracion_sistema/programa/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Nombre','Pagina','Icono'],
   	colModel:[
			{name:'nombre',index:'nombre', width:200},
			{name:'pagina',index:'pagina', width:200},
			{name:'icono',index:'icono', width:200}
   	],
	pager: jQuery('#pager_consulta_programa'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function programa_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(programaprograma_gridReload,150)
} 

function programaprograma_gridReload()
{ 
	var busqueda_nombre = jQuery("#busqueda_nombre").val();  
	jQuery("#list_consulta_programa").setGridParam({url:"modulos/administracion_sistema/programa/co/sql.consulta.php?busq_nombre="+busqueda_nombre,page:1}).trigger("reloadGrid"); 
} 
	
/*function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#programa_co_btn_consultar").attr("disabled",state); 
}*/
$('#busqueda_nombre').alpha({nocaps:true,allow:'´ '});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Programa </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="codigo" for="busqueda_codigo"></label>
				<label id="" nombre for="busqueda_nombre">Nombre </label><input name="busqueda_nombre" type="text" id="busqueda_nombre" onkeydown="programa_doSearch(arguments[0]||event)" size="60" maxlength="60" />
				
			</div>
			
			<table id="list_consulta_programa" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_programa" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>