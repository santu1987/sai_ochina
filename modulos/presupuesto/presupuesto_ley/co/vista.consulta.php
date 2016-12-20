<script type="text/javascript">
$('#presupuesto_ley_busqueda_codigo').focus();
$("#list_consulta_presupuesto_ley").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/presupuesto_ley/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Partida', 'A&ntilde;o','Unidad', 'Acci&oacute;n Especifica', 'Monto Presupuesto'],
   	colModel:[
			{name:'partida',index:'partida', width:100},
			{name:'anio',index:'anio', width:70},
			{name:'unidad',index:'unidad', width:190},
			{name:'accion',index:'accion', width:200},
			{name:'monto_presupuesto',index:'monto_presupuesto', width:140}
   	],
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_presupuesto_ley'),
   	sortname: 'anio',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function presupuesto_ley_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(presupuesto_ley_gridReload,500)
} 

function presupuesto_ley_gridReload()
{ 
	var presupuesto_ley_busqueda_nombre = jQuery("#presupuesto_ley_busqueda_nombre").val(); 
	var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	jQuery("#list_consulta_presupuesto_ley").setGridParam({url:"modulos/presupuesto/presupuesto_ley/co/sql.consulta.php?presupuesto_ley_busqueda_nombre="+presupuesto_ley_busqueda_nombre+"&presupuesto_ley_busqueda_codigo="+presupuesto_ley_busqueda_codigo+"&presupuesto_ley_busqueda_partida="+presupuesto_ley_busqueda_partida,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#presupuesto_ley_co_btn_consultar").attr("disabled",state); 
}
$('#presupuesto_ley_busqueda_codigo').numeric({nocaps:true});
$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Ateproyecto de Presupuesto  </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" for="presupuesto_ley_busqueda_codigo">A&ntilde;o:</label> &nbsp; <input type="text" name="presupuesto_ley_busqueda_codigo" id="presupuesto_ley_busqueda_codigo" onkeydown="presupuesto_ley_doSearch(arguments[0]||event)" />
				<label id="" nombre for="presupuesto_ley_busqueda_nombre">Unidad:</label>
				<input type="text" name="presupuesto_ley_busqueda_nombre" id="presupuesto_ley_busqueda_nombre" onkeydown="presupuesto_ley_doSearch(arguments[0]||event)" message="Introduzca el nombre de la unidad ejecutora"/>
			<label id="" for="presupuesto_ley_busqueda_partida">Partida:</label> 
			&nbsp; <input type="text" name="presupuesto_ley_busqueda_partida" id="presupuesto_ley_busqueda_partida" onkeydown="presupuesto_ley_doSearch(arguments[0]||event)" />
			</div>
			<table id="list_consulta_presupuesto_ley" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_presupuesto_ley" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>