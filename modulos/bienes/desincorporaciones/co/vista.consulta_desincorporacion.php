<script type="text/javascript">

$("#consulta_desincorporaciones").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/desincorporaciones/co/sql.consulta_desincorporacion.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Fecha Desincorporaci&oacute;n','Descripcion','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'fecha_desincorporacion',index:'fecha_desincorporacion', width:150},
		{name:'descripcion_general',index:'descripcion_general', width:150},
		{name:'comentarios',index:'comentarios', width:150,hidden:true}
	],
	pager: jQuery('#consultadesincorporaciones'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function desincorporaciones_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(desincorporaciones_gridReload,150)
}
function desincorporaciones_gridReload(){
	var nombre_desin = jQuery("#nombre_desin").val();
	jQuery("#consulta_desincorporaciones").setGridParam({url:"modulos/bienes/desincorporaciones/co/sql.consulta_desincorporacion.php?nomb_desin="+nombre_desin,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#desincorporacion_co_btn_consultar").attr("enable",state);
}
$('#nombre_desin').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Desincorporaci&oacute;n </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_desin" type="text" id="nombre_desin" onkeydown="desincorporaciones_doSearch(arguments[0]||event)" size="40" maxlength="40">  
			   &nbsp;
			   
	      </div>
			<table id="consulta_desincorporaciones" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultadesincorporaciones" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>