<script type="text/javascript">

$("#consulta_tipo_bien").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/TipoBien/co/sql.consulta_tipo_bien.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Nombre','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'comentarios',index:'comentarios', width:150}
	],
	pager: jQuery('#consultatipo_bien'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function tipo_bien_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(tipo_bien_gridReload,150)
}
function tipo_bien_gridReload(){
	var nombre_tipo_bien = jQuery("#nombre_tipo_bien").val();
	jQuery("#consulta_tipo_bien").setGridParam({url:"modulos/bienes/TipoBien/co/sql.consulta_tipo_bien.php?nomb_tipo_bien="+nombre_tipo_bien,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#tipo_bien_co_btn_consultar").attr("enable",state);
}
$('#nombre_tipo_bien').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Tipo de Bien</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Nombre &nbsp; <input name="nombre_tipo_bien" type="text" id="nombre_tipo_bien" onkeydown="tipo_bien_doSearch(arguments[0]||event)" size="40" maxlength="40">  
			   &nbsp;
			   
	      </div>
			<table id="consulta_tipo_bien" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultatipo_bien" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>