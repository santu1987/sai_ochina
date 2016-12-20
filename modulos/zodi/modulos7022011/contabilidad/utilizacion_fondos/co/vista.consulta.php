<script type="text/javascript">
$('#contabilidad_utilizacion_fondos_nombre_con').focus();
$("#list_consulta_contabilidad_utilizacion_fondos").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/contabilidad/utilizacion_fondos/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Cuenta Contable','Nombre','Tipo','Comentario'],
   	colModel:[
			{name:'cuenta_contable',index:'cuenta_contable', width:100},
			{name:'nombre',index:'nombre',width:170},
			{name:'tipo',index:'tipo',width:170},
			{name:'comentario',index:'comentario',width:100}			
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_contabilidad_utilizacion_fondos'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function contabilidad_utilizacion_fondos_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(contabilidad_utilizacion_fondos_gridReload,500)
} 

function contabilidad_utilizacion_fondos_gridReload()
{ 
	var busq_nombre = jQuery("#contabilidad_utilizacion_fondos_nombre_con").val(); 
	/*var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	*/jQuery("#list_consulta_contabilidad_utilizacion_fondos").setGridParam({url:"modulos/contabilidad/utilizacion_fondos/co/sql.consulta.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
$('#contabilidad_utilizacion_fondos_nombre_con').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Auxiliares </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
				<label id="" for="contabilidad_utilizacion_fondos_nombre_con">Nombre:</label> &nbsp; <input type="text" name="contabilidad_utilizacion_fondos_nombre_con" id="contabilidad_utilizacion_fondos_nombre_con" onKeyDown="contabilidad_utilizacion_fondos_doSearch(arguments[0]||event)" />
			</div>
			<table id="list_consulta_contabilidad_utilizacion_fondos" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_contabilidad_utilizacion_fondos" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>