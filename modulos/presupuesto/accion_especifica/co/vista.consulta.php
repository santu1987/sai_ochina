<script type="text/javascript">
$('#accion_especifica_db_busqueda_nombre').focus();
$("#list_consulta_accion_especifica").jqGrid
({ 
	height: 400,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/accion_especifica/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Codigo','Acci&oacute;n Especifica','Comentario'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'codigo',index:'codigo', width:50},
			{name:'accion_especifica',index:'accion_especifica', width:200},
			{name:'comentario',index:'comentario', width:100}
   	],
	pager: jQuery('#pager_consulta_accion_especifica'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_accion_especifica',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function accion_especifica_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(accion_especifica_gridReload,500)
} 

function accion_especifica_gridReload()
{ 
	var busqueda_nombre = jQuery("#accion_especifica_db_busqueda_nombre").val(); 
	jQuery("#list_consulta_accion_especifica").setGridParam({url:"modulos/presupuesto/accion_especifica/co/sql.consulta.php?busq_nombre_accion_especifica="+busqueda_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#accion_especifica_co_btn_consultar").attr("enable",state); 
}
//$('#accion_especifica_db_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Acci&oacute;n Especifica </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
				<label id="" nombre for="busqueda_nombre">Nombre:</label><input type="text" name="accion_especifica_db_busqueda_nombre" id="accion_especifica_db_busqueda_nombre" onkeydown="accion_especifica_doSearch(arguments[0]||event)" />
			</div>
			
			<table id="list_consulta_accion_especifica" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_accion_especifica" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>