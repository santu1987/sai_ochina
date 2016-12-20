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

$("#consulta_conceptos").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/conceptos/co/sql.consulta_conceptos.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Concepto','Asig/Deduc','Tipo Concepto','Porcentaje','Limite Inf','Limite Sup','Observacion'],
	colModel:[
		{name:'descripcion',index:'descripcion', width:150},
		{name:'asignacion_deduccion',index:'asignacion_deduccion', width:150},
		{name:'tipo_concepto',index:'tipo_concepto', width:120},
		{name:'porcentaje',index:'porcentaje', width:100},
		{name:'limite_inf',index:'limite_inf', width:100},
		{name:'limite_sup',index:'limite_sup', width:100},
		{name:'observacion',index:'obsevacion', width:150}
	],
	pager: jQuery('#consultaconceptos'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'descripcion',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function conceptos_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(conceptos_gridReload,150)
}
function conceptos_gridReload(){
	var nombre_concepto = jQuery("#descripcion_concepto").val();
	jQuery("#consulta_conceptos").setGridParam({url:"modulos/rrhh/conceptos/co/sql.consulta_conceptos.php?nombre_concepto="+nombre_concepto,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#consulta_co_btn_consultar").attr("enable",state);
}
$('#descripcion_concepto').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Conceptos </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>Concepto&nbsp; 
			  <input name="descripcion_concepto" type="text" id="descripcion_concepto" onkeydown="conceptos_doSearch(arguments[0]||event)" size="40" maxlength="40">  			   
	      </div>
			<table id="consulta_conceptos" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultaconceptos" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>