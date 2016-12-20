<script type="text/javascript">
$('#accion_centralizada_busqueda_nombre').focus();
$("#list_consulta_accion_centralizada").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/accion_centralizada/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Acci&oacute;n Centralizada','Comentario'],
   	colModel:[
			{name:'accion_centralizada',index:'accion_centralizada', width:300},
			{name:'comentario',index:'comentario', width:300}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_accion_centralizada'),
   	sortname: 'id_accion_central',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function accion_centralizada_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(accion_centralizada_gridReload,500)
} 

function accion_centralizada_gridReload()
{ 
	var accion_centralizada_busqueda_nombre = jQuery("#accion_centralizada_busqueda_nombre").val();
	var accion_centralizada_busqueda_anio = jQuery("#accion_centralizada_busqueda_anio").val(); 
	//var busqueda_codigo = jQuery("#busqueda_codigo").val(); 
	jQuery("#list_consulta_accion_centralizada").setGridParam({url:"modulos/presupuesto/accion_centralizada/co/sql.consulta.php?accion_centralizada_busqueda_nombre="+accion_centralizada_busqueda_nombre+"&accion_centralizada_busqueda_anio="+accion_centralizada_busqueda_anio,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#accion_centralizada_co_btn_consultar").attr("enable",state); 
}
//$('#busqueda_codigo').numeric({nocaps:true});
$('#busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Acci&oacute;n Centralizada </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				
				<label id="" nombre for="accion_centralizada_busqueda_nombre">Nombre:</label><input type="text" name="accion_centralizada_busqueda_nombre" id="accion_centralizada_busqueda_nombre"  onkeydown="accion_centralizada_doSearch(arguments[0]||event)"  />&nbsp;&nbsp;<select name="accion_centralizada_busqueda_anio" id="accion_centralizada_busqueda_anio" onchange="accion_centralizada_doSearch(arguments[0]||event)">
				<option id="2010">2010</option>
				<option id="2011">2011</option>
				</select>
			</div>
			<table id="list_consulta_accion_centralizada" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_accion_centralizada" class="scroll" style="text-align:center;"></div>		</td>
	</tr>
</table>
