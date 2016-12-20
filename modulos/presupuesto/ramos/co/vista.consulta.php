<script type="text/javascript">
$('#ramos_busqueda_ramo').focus();
$("#list_consulta_ramos").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/ramos/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Ramo','Comentario'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'ramo',index:'ramo', width:200},
			{name:'comentario',index:'comentario', width:200}
   	],
	pager: jQuery('#pager_consulta_ramos'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_ramo',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function ramos_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(ramos_gridReload,150)
} 

function ramos_gridReload()
{ 
	var ramos_busqueda_ramo = jQuery("#ramos_busqueda_ramo").val(); 
	jQuery("#list_consulta_ramos").setGridParam({url:"modulos/presupuesto/ramos/co/sql.consulta.php?busq_ramo="+ramos_busqueda_ramo,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#ramos_co_btn_consultar").attr("enable",state); 
}
//$('#busqueda_ramo').alpha({nocaps:false,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Ramos </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda_ramo">
				<label id="" nombre for="ramos_busqueda_ramo">Nombre:</label><input type="text" name="ramos_busqueda_ramo" id="ramos_busqueda_ramo" onkeydown="ramos_doSearch(arguments[0]||event)" />
			</div>
			
			<table id="list_consulta_ramos" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_ramos" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>