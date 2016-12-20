<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM estatus_bienes";
$rs_estatus =& $conn->Execute($sql);
while (!$rs_estatus->EOF){
	$opt_estatus.="<option value='".$rs_estatus->fields("id_estatus_bienes")."' >".$rs_estatus->fields("nombre")."</option>";
	$rs_estatus->MoveNext();
} 
?>
<script type="text/javascript">
$("#consulta_bienes").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/bien/co/sql.consulta_bienes.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['','Bien','Codigo','Serial','Unidad','Sitio Fisico','Custodio'],
	colModel:[
		{name:'id_bienes',index:'id_bienes', hidden:true},
		{name:'bien',index:'bien', width:200},
		{name:'codigo_bienes',index:'codigo_bienes', width:120},
		{name:'serial_bien',index:'serial_bien', width:100,hidden:true},
		{name:'unidad',index:'unidad', width:220},
		{name:'sitio',index:'sitio', width:220},
		{name:'custodio',index:'custodio', width:120}
	],
	pager: jQuery('#consultabienes'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'bien',
    viewrecords: true,
	onSelectRow: function(id){
						var ret = jQuery("#consulta_bienes").getRowData(id);
						var url= "modulos/bienes/bien/co/consulta_individaul_bienes.php?id_bienes="+ret.id_bienes+"&codigo_bienes="+ret.codigo_bienes+"&serial_bien="+ret.serial_bien+"&bien="+ret.bien;
						openTab("Bien: "+ret.bien,url);
				  },
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function bienes_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(bienes_gridReload,150)
}
function bienes_gridReload(){
	var nombre_bienes = jQuery("#nombre_bienes").val();
	var estatus_bienes = getObj('estatus_bienes').value;
	jQuery("#consulta_bienes").setGridParam({url:"modulos/bienes/bien/co/sql.consulta_bienes.php?nomb_bienes="+nombre_bienes+"&estatus_bienes="+estatus_bienes,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#bienes_co_btn_consultar").attr("enable",state);
}
$('#nombre_bienes').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Bienes</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			   Estatus
			     <label>
			       <select name="estatus_bienes" id="estatus_bienes" onchange="bienes_doSearch(arguments[0]||event)" style="widows:100px">
                   <option value="">----- Todos -----</option>
				   <?= $opt_estatus;?>
		           </select>
	          </label>
		      Nombre &nbsp; 
		     <input name="nombre_bienes" type="text" id="nombre_bienes" onkeydown="bienes_doSearch(arguments[0]||event)" size="30" maxlength="30">  
	      &nbsp;</div>
			<table id="consulta_bienes" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultabienes" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>