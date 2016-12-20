<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM perfil";
$rs_perfil_programa =& $conn->Execute($sql);
while (!$rs_perfil_programa->EOF) {
	$opt_perfil_programa.="<option value='".$rs_perfil_programa->fields('nombre')."'>".$rs_perfil_programa->fields('nombre')."</option>";
$rs_perfil_programa->MoveNext();
}
?>
<script type="text/javascript">

$("#perfil_programa_co").jqGrid
({ 
	height: 240,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",			
		url:'modulos/administracion_sistema/perfil/co/sql.consulta_perfil_programa.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Programa'],
		colModel:[
			{name:'nombreprograma',index:'nombreprograma', width:300}
		],
	pager: jQuery('#perfilprogramaco'),
   	rowNum:12,
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombreprograma',
    viewrecords: true,
    sortorder: "desc"
});
var timeoutHnd;
var flAuto = true;

function perfil_programa_doSearch(ev){

	if(!flAuto)
		return;		
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(perfil_programa_perfil_programa_gridReload,150)
}

function perfil_programa_perfil_programa_gridReload(){
	var nombre_perfil_programa = jQuery("#nombre_perfil_programa").val();
	var nombre_perfil = jQuery("#nombre_perfil").val();
	jQuery("#perfil_programa_co").setGridParam({url:"modulos/administracion_sistema/perfil/co/sql.consulta_perfil_programa.php?nomb_per_prog="+nombre_perfil_programa+"&nom_per="+nombre_perfil,page:1}).trigger("reloadGrid"); 
}

//function enableAutosubmit(state){
	//flAuto = state;
	//$("#perfilprograma_co_btn_consultar").attr("disabled",state);
//}

$('#nombre_perfil').alpha({nocaps:true,allow:'´'});
$('#nombre_perfil_programa').alpha({nocaps:true,allow:'´ '});

</script>

<table style="width:590;" class="cuerpo_formulario">
	<tr>
		<th width="590px" class="titulo_frame" colspan="3"><img src="imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil Programa </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>Perfil 
			  <label>
			  <select name="nombre_perfil" id="nombre_perfil" onchange="perfil_programa_doSearch(arguments[0]||event)" >
			  <?=$opt_perfil_programa?>
		      </select>
			  </label>
			  &nbsp;&nbsp;
			   Programa &nbsp; 
			   <input name="nombre_perfil_programa" type="text" id="nombre_perfil_programa" onkeydown="perfil_programa_doSearch(arguments[0]||event)" size="40" maxlength="40" />  
			   &nbsp;
			     
		  </div>
			<!-- la tabla donde se creara el grid con clase 'scroll' -->
			<table id="perfil_programa_co" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="perfilprogramaco" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>
