<script type="text/javascript">

$("#consulta_cargos").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/cargos/co/sql.consulta_cargos.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Descripcion','Observacion'],
	colModel:[
		{name:'descripcion',index:'descripcion', width:150},
		{name:'observacion',index:'observacion', width:150}
	],
	pager: jQuery('#consultasitio_fisico'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'descripcion',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function cargos_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(cargos_gridReload,150)
}
function cargos_gridReload(){
	var nombre_cargo = jQuery("#descripcion_cargo").val();
	jQuery("#consulta_cargos").setGridParam({url:"modulos/rrhh/cargos/co/sql.consulta_cargos.php?nombre_cargo="+nombre_cargo,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#cargos_co_btn_consultar").attr("enable",state);
}
$('#descripcion_cargo').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Cargos </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>Cargo&nbsp; 
			  <input name="descripcion_cargo" type="text" id="descripcion_cargo" onkeydown="cargos_doSearch(arguments[0]||event)" size="40" maxlength="40">  			   
	      </div>
			<table id="consulta_cargos" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultacargos" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>