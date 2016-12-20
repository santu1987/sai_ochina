<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM nivel_academico ORDER BY nombre";
$rs_nivel =& $conn->Execute($sql);
while (!$rs_nivel->EOF){
	$opt_nivel.="<option value='".$rs_nivel->fields("id_nivel_academico")."' >".$rs_nivel->fields("nombre")."</option>";
	$rs_nivel->MoveNext();
} 
?>
<script type="text/javascript">

$("#consulta_curriculum").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/curriculum/co/sql.consulta_curriculum.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['id_ramas','Rama','id_nivel'],
	colModel:[
		{name:'id_rama',index:'id_rama', width:150,hidden:true},
		{name:'nombre',index:'nombre', width:150},
		{name:'nivel',index:'nivel', hidden:true}
	],
	pager: jQuery('#consultacurriculum'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
	onSelectRow: function(id){
						var ret = jQuery("#consulta_curriculum").getRowData(id);
						var curricus= ret.nivel+" "+ret.nombre;
						var url= "modulos/rrhh/curriculum/co/consulta_individual_curriculum.php?id_rama="+ret.id_rama+"&curricus="+curricus;
						openTab("Curriculums "+curricus,url);
				  },
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function curriculum_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(curriculum_gridReload,150)
}
function curriculum_gridReload(){
	var nombre_ramas = jQuery("#nombre_curriculum").val();
	var nombre_nivel = jQuery("#nombre_nivel_academico").val();
	jQuery("#consulta_curriculum").setGridParam({url:"modulos/rrhh/curriculum/co/sql.consulta_curriculum.php?nomb_ramas="+nombre_ramas+"&nomb_nivel="+nombre_nivel,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#curriculum_co_btn_consultar").attr("enable",state);
}
$('#nombre_curriculum').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Rama </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>
            <strong>Nivel Academico</strong>: </td>                  
	             <select id="nombre_nivel_academico" name="nombre_nivel_academico" onchange="curriculum_doSearch(arguments[0]||event)">
	               <option value="">------------ SELECCIONE ------------</option>
	               <?= $opt_nivel?>
    </select>  
			   Rama &nbsp; <input name="nombre_curriculum" type="text" id="nombre_curriculum" onkeydown="curriculum_doSearch(arguments[0]||event)" size="20" maxlength="40">  			   
	      </div>
			<table id="consulta_curriculum" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultacurriculum" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>