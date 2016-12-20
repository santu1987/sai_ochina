<script type="text/javascript">

$("#consulta_modulo").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/administracion_sistema/modulo/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Observacion','Icono'],
	colModel:[
		{name:'modulo.nombre',index:'modulo.nombre', width:150},
		{name:'obs',index:'obs', width:150},
		{name:'icono',index:'icono', width:150}
	],
	pager: jQuery('#consultamodulo'),
   	rowNum:20,
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'modulo.nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function modulo_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(modulo_gridReload,150)
}
function modulo_gridReload(){
	var nombre_modulo = jQuery("#nombre_modulo").val();
	jQuery("#consulta_modulo").setGridParam({url:"modulos/administracion_sistema/modulo/co/sql.consulta.php?nomb_modulo="+nombre_modulo,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#modulo_co_btn_consultar").attr("enable",state);
}
$('#nombre_modulo').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Módulo </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_modulo" type="text" id="nombre_modulo" onkeydown="modulo_doSearch(arguments[0]||event)" size="40" maxlength="40">  
			   &nbsp;
			   
	      </div>
			<table id="consulta_modulo" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultamodulo" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>



