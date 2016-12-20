
<script type="text/javascript">

$("#consulta_nivel").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/nivel_academico/co/sql.consulta_nivel_academico.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nivel','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'observaciones',index:'observaciones', width:150}
	],
	pager: jQuery('#consultanivel'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function nivel_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(nivel_gridReload,150)
}
function nivel_gridReload(){
	var nombre_nivel = jQuery("#nombre_nivel").val();
	jQuery("#consulta_nivel").setGridParam({url:"modulos/rrhh/nivel_academico/co/sql.consulta_nivel_academico.php?nomb_nivel="+nombre_nivel,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#nivel_co_btn_consultar").attr("enable",state);
}
$('#nombre_nivel').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Nivel Academico</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div> 
			   Nivel Academico &nbsp; <input name="nombre_nivel" type="text" id="nombre_nivel" onkeydown="nivel_doSearch(arguments[0]||event)" size="40" maxlength="40">  			   
	      </div>
			<table id="consulta_nivel" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultanivel" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>