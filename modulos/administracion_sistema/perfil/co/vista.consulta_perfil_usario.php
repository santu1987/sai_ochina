<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM perfil";
$rs_perfil_usuario =& $conn->Execute($sql);
while (!$rs_perfil_usuario->EOF) {
	$opt_perfil_usuario.="<option value='".$rs_perfil_usuario->fields('nombre')."'>".$rs_perfil_usuario->fields('nombre')."</option>";
$rs_perfil_usuario->MoveNext();
}
?>
<script type="text/javascript">

$("#usuario_perfil").jqGrid
({ 
	height: 240,
	width: 550,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",		
		url:'modulos/administracion_sistema/perfil/co/sql.consulta_perfil_usuario.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Usuario'],
		colModel:[
			{name:'usuario',index:'usuario', width:400},
		],
	pager: jQuery('#usuarioperfil'),
   	rowNum:12,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'nombre',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function perfil_usuario_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(perfil_usuario_gridReload,150)
}
function perfil_usuario_gridReload(){
	var nombre_perfil_usuario = jQuery("#nombre_perfil_usuario").val();
	var usuario_perfil_usuario = jQuery("#usuario_perfil_usuario").val();
	jQuery("#usuario_perfil").setGridParam({url:"modulos/administracion_sistema/perfil/co/sql.consulta_perfil_usuario.php?nomb_per_usua="+nombre_perfil_usuario+"&usu_per_usua="+usuario_perfil_usuario,page:1}).trigger("reloadGrid"); 

}
/*function enableAutosubmit(state){
	flAuto = state;
	$("#perfilusuario_co_btn_consultar").attr("disabled",state);
}*/
$('#usuario_perfil_usuario').alpha({nocaps:true,allow:'´ '});
$('#nombre_perfil_usuario').alpha({nocaps:true,allow:'´'});

</script>

<table style="width:590;" class="cuerpo_formulario">
	<tr>
		<th width="590px" class="titulo_frame" colspan="3"><img src="imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil Usuario </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>Perfil&nbsp;
			  <label>
			  <select name="nombre_perfil_usuario" id="nombre_perfil_usuario" onchange="perfil_usuario_doSearch(arguments[0]||event)">
				<?=$opt_perfil_usuario?>
		      </select>
			  </label>
			  &nbsp;Usuario&nbsp; 
			   <input type="text" name="usuario_perfil_usuario" id="usuario_perfil_usuario" onkeydown="perfil_usuario_doSearch(arguments[0]||event)" />  
			   &nbsp;
			   
		  </div>
			<table id="usuario_perfil" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="usuarioperfil" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>