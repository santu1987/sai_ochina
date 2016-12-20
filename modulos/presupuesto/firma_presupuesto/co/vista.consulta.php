<script type="text/javascript">
$('#busqueda_nombre').focus();
$("#list_consulta_firma_presupuesto").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/firma_presupuesto/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['ID','Organismo','Nombre Autoriza','Nombre Autoriza Traspaso'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'organismo',index:'organismo', width:200,hidden:true},
			{name:'nomauto',index:'nomauto', width:200},
			{name:'nomautotras',index:'nomautotras', width:200}
   	],
	pager: jQuery('#pager_consulta_firma_presupuesto'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id_organismo',
    viewrecords: true,
    sortorder: "asc"
});

var timeoutHnd; 
var flAuto = true;

function firma_presupuesto_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(firma_presupuesto_gridReload,500)
} 

function firma_presupuesto_gridReload()
{ 
	var busqueda_nombre = jQuery("#busqueda_nombre").val(); 
	jQuery("#list_consulta_firma_presupuesto").setGridParam({url:"modulos/presupuesto/firma_presupuesto/co/sql.consulta.php?busq_nombre="+busqueda_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#firma_presupuesto_co_btn_consultar").attr("enable",state); 
}
$('#busqueda_codigo').numeric({nocaps:true});
//$('#busqueda_nombre').alpha({nocaps:false,allow:'´'});
</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Firma de Presupuestario</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
			<div class="div_busqueda">
				<label id="" nombre for="busqueda_nombre">Nombre:</label><input type="text" name="busqueda_nombre" id="busqueda_nombre" onkeydown="firma_presupuesto_doSearch(arguments[0]||event)" />
			</div>
			
			<table id="list_consulta_firma_presupuesto" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_firma_presupuesto" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>