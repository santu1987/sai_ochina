<script type="text/javascript">
$('#clasificador_presupuestario_busqueda_nombre').focus();
$("#list_consulta_clasificador_presupuestario").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/clasificador_presupuestario/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Partida','Nombre','Grupo'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'partida',index:'partida', width:200},
			{name:'nombre',index:'nombre', width:200},
			{name:'grupo',index:'grupo', width:200}
   	],
	pager: jQuery('#pager_consulta_clasificador_presupuestario'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'denominacion',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function clasificador_presupuestario_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(clasificador_presupuestario_gridReload,500)
} 

function clasificador_presupuestario_gridReload()
{ 
	var clasificador_presupuestario_busqueda_nombre=jQuery("#clasificador_presupuestario_busqueda_nombre").val(); 
	var clasificador_presupuestario_busqueda_partida=jQuery("#clasificador_presupuestario_busqueda_partida").val(); 
	jQuery("#list_consulta_clasificador_presupuestario").setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/co/sql.consulta.php?clasificador_presupuestario_busqueda_nombre="+clasificador_presupuestario_busqueda_nombre+"&clasificador_presupuestario_busqueda_partida="+clasificador_presupuestario_busqueda_partida,page:1}).trigger("reloadGrid"); 
	//alert(clasificador_presupuestario_busqueda_partida);	
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#clasificador_presupuestario_co_btn_consultar").attr("enable",state); 
}
$('#clasificador_presupuestario_busqueda_partida').numeric({allow:'.'});
//$('#busqueda_nombre').alpha({nocaps:false,allow:'´'});
</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Clasificador Presupuestario</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="clasificador_presupuestario_busqueda_nombre">Nombre:</label><input type="text" name="clasificador_presupuestario_busqueda_nombre" id="clasificador_presupuestario_busqueda_nombre" onkeydown="clasificador_presupuestario_doSearch(arguments[0]||event)" />
				<label id="" for="clasificador_presupuestario_busqueda_partida">Partida:</label> 
			&nbsp; <input type="text" name="clasificador_presupuestario_busqueda_partida" id="clasificador_presupuestario_busqueda_partida" onkeydown="clasificador_presupuestario_doSearch(arguments[0]||event)" />

			</div>
			
			<table id="list_consulta_clasificador_presupuestario" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_clasificador_presupuestario" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>