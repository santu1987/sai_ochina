<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM perfil";
$rs_perfil_organismo =& $conn->Execute($sql);
while (!$rs_perfil_organismo->EOF) {
	$opt_perfil_organismo.="<option value='".$rs_perfil_organismo->fields('nombre')."'>".$rs_perfil_organismo->fields('nombre')."</option>";
$rs_perfil_organismo->MoveNext();
}
?>
<script type="text/javascript">

$("#organismo_perfil").jqGrid
({ 
	height: 240,
	width: 550,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",		
		url:'modulos/administracion_sistema/perfil/co/sql.consulta_perfil_organismo.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Organismo'],
		colModel:[
			{name:'organismo',index:'organismo', width:250},
		],
	pager: jQuery('#organismoperfil'),
   	rowNum:12,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'organismo',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function perfil_organismo_perfil_organismo_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(perfil_organismo_gridReload,150)
}
function perfil_organismo_gridReload(){
	var nombre_perfil_organismo = jQuery("#nombre_perfil_organismo").val();
	var organismo_organismo = jQuery("#organismo_organismo").val();
	jQuery("#organismo_perfil").setGridParam({url:"modulos/administracion_sistema/perfil/co/sql.consulta_perfil_organismo.php?nomb_per_organismo="+nombre_perfil_organismo+"&organismo_per_organismo="+organismo_organismo,page:1}).trigger("reloadGrid"); 

}

$('#organismo_organismo').alpha({nocaps:true,allow:'´ '});
$('#nombre_perfil_organismo').alpha({nocaps:true,allow:'´'});

</script>

<table style="width:590;" class="cuerpo_formulario">
	<tr>
		<th width="590px" class="titulo_frame" colspan="3"><img src="imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil Organismo </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
		  <div>Perfil&nbsp;
			  <label>
			  <select name="nombre_perfil_organismo" id="nombre_perfil_organismo" onchange="perfil_organismo_perfil_organismo_doSearch(arguments[0]||event)">
				<?=$opt_perfil_organismo?>
		      </select>
			  </label>
			  &nbsp;Organismo&nbsp;
              <input name="organismo_organismo" type="text" id="organismo_organismo" onkeydown="perfil_organismo_perfil_organismo_doSearch(arguments[0]||event)" size="40" />  
	      &nbsp;</div>
			<table id="organismo_perfil" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="organismoperfil" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>