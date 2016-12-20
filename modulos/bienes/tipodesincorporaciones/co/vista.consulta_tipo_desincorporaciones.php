<script type="text/javascript">

$("#consulta_tipo_desin").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/Tipodesincorporaciones/co/sql.consulta_tipo_desincorporaciones.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'comentarios',index:'comentarios', width:150}
	],
	pager: jQuery('#consultatipo_desin'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function tipo_desin_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(tipo_desin_gridReload,150)
}
function tipo_desin_gridReload(){
	var nombre_tipo_desin = jQuery("#nombre_tipo_desincorporaciones").val();
	jQuery("#consulta_tipo_desin").setGridParam({url:"modulos/bienes/Tipodesincorporaciones/co/sql.consulta_tipo_desincorporaciones.php?nomb_tipo_desincorporaciones="+nombre_tipo_desin,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#tipo_desin_co_btn_consultar").attr("enable",state);
}
$('#nombre_tipo_desincorporaciones').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Tipo Desincorporaciones</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_tipo_desincorporaciones" type="text" id="nombre_tipo_desincorporaciones" onkeydown="tipo_desin_doSearch(arguments[0]||event)" size="40" maxlength="40">  
			   &nbsp;
			   
	      </div>
			<table id="consulta_tipo_desin" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultatipo_desin" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>