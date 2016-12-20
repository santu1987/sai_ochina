<script type="text/javascript">

$("#consulta_custodio").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/custodio/co/sql.consulta_custodio.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'comentarios',index:'comentarios', width:150}
	],
	pager: jQuery('#consultacustodio'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function custodio_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(custodio_gridReload,150)
}
function custodio_gridReload(){
	var nombre_custodio = jQuery("#nombre_custodio").val();
	jQuery("#consulta_custodio").setGridParam({url:"modulos/bienes/custodio/co/sql.consulta_custodio.php?nomb_custodio="+nombre_custodio,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#custodio_co_btn_consultar").attr("enable",state);
}
$('#nombre_custodio').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Custodio </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_custodio" type="text" id="nombre_custodio" onkeydown="custodio_doSearch(arguments[0]||event)" size="40" maxlength="40">  
			   &nbsp;
			   
	      </div>
			<table id="consulta_custodio" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultacustodio" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>