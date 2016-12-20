<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM unidad_ejecutora ORDER BY nombre";
$rs_unidad =& $conn->Execute($sql);
while (!$rs_unidad->EOF){
	$opt_unidad.="<option value='".$rs_unidad->fields("id_unidad_ejecutora")."' >".$rs_unidad->fields("nombre")."</option>";
	$rs_unidad->MoveNext();
} 
?>
<script type="text/javascript">

$("#consulta_sitio_fisico").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/bienes/sitiofisico/co/sql.consulta_sitio_fisico.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Unidad','Sitio Fisico','Observacion'],
	colModel:[
		{name:'nomb',index:'nomb', width:150},
		{name:'sitio_fisico.nombre',index:'sitio_fisico.nombre', width:150},
		{name:'comentarios',index:'comentarios', width:150}
	],
	pager: jQuery('#consultasitio_fisico'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function sitio_fisico_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(sitio_fisico_gridReload,150)
}
function sitio_fisico_gridReload(){
	var nombre_sitio_fisico = jQuery("#nombre_sitio_fisico").val();
	var nombre_unidad = jQuery("#nombre_unidad_sitio").val();
	jQuery("#consulta_sitio_fisico").setGridParam({url:"modulos/bienes/sitiofisico/co/sql.consulta_sitio_fisico.php?nomb_sitio_fisico="+nombre_sitio_fisico+"&nomb_unidad="+nombre_unidad,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#sitio_fisico_co_btn_consultar").attr("enable",state);
}
$('#nombre_sitio_fisico').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Sitio Fisico </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>
            <strong>Unidad</strong>: </td>                  
	             <select id="nombre_unidad_sitio" name="nombre_unidad_sitio" onchange="sitio_fisico_doSearch(arguments[0]||event)">
	               <option value="">------------ SELECCIONE ------------</option>
	               <?= $opt_unidad?>
    </select>  
			   Nombre &nbsp; <input name="nombre_sitio_fisico" type="text" id="nombre_sitio_fisico" onkeydown="sitio_fisico_doSearch(arguments[0]||event)" size="40" maxlength="40">  			   
	      </div>
			<table id="consulta_sitio_fisico" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultasitio_fisico" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>