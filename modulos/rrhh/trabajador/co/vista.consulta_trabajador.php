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

$("#consulta_ramas").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/ramas/co/sql.consulta_ramas.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Rama','Observacion'],
	colModel:[
		{name:'nombre',index:'nombre', width:150},
		{name:'observaciones',index:'observaciones', width:150}
	],
	pager: jQuery('#consultaramas'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function ramas_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(ramas_gridReload,150)
}
function ramas_gridReload(){
	var nombre_ramas = jQuery("#nombre_ramas").val();
	var nombre_nivel = jQuery("#nombre_nivel_academico").val();
	jQuery("#consulta_ramas").setGridParam({url:"modulos/rrhh/ramas/co/sql.consulta_ramas.php?nomb_ramas="+nombre_ramas+"&nomb_nivel="+nombre_nivel,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#ramas_co_btn_consultar").attr("enable",state);
}
$('#nombre_ramas').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Rama </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div><strong>C&eacute;dula:</strong>
			  <label>
			    <input type="text" name="textfield" id="textfield" />
			  </label>
			  </td>                  
	             &nbsp;Cargo:
	             <select id="nombre_cargo" name="nombre_cargo" onchange="ramas_doSearch(arguments[0]||event)">
	               <option value="">------------ SELECCIONE ------------</option>
	               <?= $opt_cargo?>
    </select> 
&nbsp;Area Trabajo:             
<select id="nombre_unidad_ejecutora" name="nombre_unidad_ejecutora" onchange="ramas_doSearch(arguments[0]||event)">
  <option value="">------------ SELECCIONE ------------</option>
  <?= $opt_unidad?>
</select>
		  </div>
			<table id="consulta_ramas" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultaramas" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>