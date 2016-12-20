<script type="text/javascript">
$('#parametro_presupuesto_busqueda_nombre').focus();
$("#list_consulta_parametro_presupuesto").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/parametro_presupuesto/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Organismo','A&ntilde;o', 'Cierre Mes', 'Cierre Anual'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'organismo',index:'organismo', width:200},
			{name:'anio',index:'anio', width:200},
			{name:'cierre_mes',index:'cierre_mes', width:200},
			{name:'cierre_anual',index:'cierre_anual', width:200}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_parametro_presupuesto'),
   	sortname: 'id_organismo',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function parametro_presupuesto_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(parametro_presupuesto_gridReload,150)
} 

function parametro_presupuesto_gridReload()
{ 
	var parametro_presupuesto_busqueda_nombre = jQuery("#parametro_presupuesto_busqueda_nombre").val(); 
	jQuery("#list_consulta_parametro_presupuesto").setGridParam({url:"modulos/presupuesto/parametro_presupuesto/co/sql.consulta.php?parametro_presupuesto_busqueda_nombre="+parametro_presupuesto_busqueda_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#parametro_presupuesto_co_btn_consultar").attr("enable",state); 
}
//$('#busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Par&aacute;metro Presupuesto</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="parametro_presupuesto_busqueda_nombre">Nombre:</label><input type="text" name="parametro_presupuesto_busqueda_nombre" id="parametro_presupuesto_busqueda_nombre" onkeydown="parametro_presupuesto_doSearch(arguments[0]||event)" />
			</div>
			<table id="list_consulta_parametro_presupuesto" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_parametro_presupuesto" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>