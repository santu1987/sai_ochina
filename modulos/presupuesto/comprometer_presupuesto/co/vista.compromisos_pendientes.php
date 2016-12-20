<script type="text/javascript">
$('#compromisos_pendientes_busqueda_nombre').focus();
$("#list_consulta_compromisos_pendientes").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/presupuesto/comprometer_presupuesto/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Orden','Cotizacion','Requisicion','Unidad','Fecha de Elaboracion','Tipo de Orden'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
			{name:'orden',index:'orden', width:40},
			{name:'cotizacion',index:'cotizacion', width:48},
			{name:'requisicion',index:'requisicion', width:52},
			{name:'unidad',index:'unidad', width:200},
			{name:'fecha',index:'fecha', width:70},
			{name:'tipo',index:'tipo', width:62}
   	],
	pager: jQuery('#pager_consulta_clasificador_presupuestario'),
   	rowNum:20,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'numero_orden_compra_servicio',
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
	var compromisos_pendientes_busqueda_nombre=jQuery("#compromisos_pendientes_busqueda_nombre").val(); 
	var compromisos_pendientes_busqueda_orden=jQuery("#compromisos_pendientes_busqueda_orden").val(); 
	jQuery("#list_consulta_compromisos_pendientes").setGridParam({url:"modulos/presupuesto/comprometer_presupuesto/co/sql.consulta.php?unidad="+compromisos_pendientes_busqueda_nombre+"&orden="+compromisos_pendientes_busqueda_orden,page:1}).trigger("reloadGrid"); 
	//alert(compromisos_pendientes_busqueda_orden);	
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#clasificador_presupuestario_co_btn_consultar").attr("enable",state); 
}
$('#compromisos_pendientes_busqueda_orden').numeric({allow:'.'});
//$('#busqueda_nombre').alpha({nocaps:false,allow:'´'});
</script>
<form name="form_compromisos_pendientes" id="form_compromisos_pendientes">
	<table class="cuerpo_formulario">
    	<tr>
            <th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Compromisos Pendientes</th>
        </tr>
    	<tr>
            <td class="celda_consulta">
            	<div class="div_busqueda">
                	<label id="" nombre for="compromisos_pendientes_busqueda_nombre">Unidad:</label>
               		<input type="text" name="compromisos_pendientes_busqueda_nombre" id="compromisos_pendientes_busqueda_nombre" onkeydown="clasificador_presupuestario_doSearch(arguments[0]||event)" />
					<label id="" for="compromisos_pendientes_busqueda_orden">Orden nro:</label> 
			&nbsp; <input type="text" name="compromisos_pendientes_busqueda_orden" id="compromisos_pendientes_busqueda_orden" onkeydown="clasificador_presupuestario_doSearch(arguments[0]||event)" />

                </div>
                
                <table id="list_consulta_compromisos_pendientes" class="scroll" cellpadding="0" cellspacing="0" ></table> 
                <div id="pager_consulta_compromisos_pendientes" class="scroll" style="text-align:center;"></div> 

            </td>
        </tr>
    </table>
</form>