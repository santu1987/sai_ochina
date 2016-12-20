<script type="text/javascript">
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT * FROM perfil";
$rs_perfil =& $conn->Execute($sql);
while (!$rs_perfil->EOF) {
	$opt_perfil.="<option value='".$rs_perfil->fields('id_perfil')."'>".$rs_perfil->fields('nombre')."</option>";
$rs_perfil->MoveNext();
}
?>
$("#perfil_modulo").jqGrid
({ 
	height: 240,
	width: 590,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",			
		url:'modulos/administracion_sistema/perfil/co/sql.consulta_perfil_modulo.php?nd='+new Date().getTime(),
		datatype: "json",
		colNames:['Modulo'],
		colModel:[
			{name:'nombremodulo',index:'nombremodulo', width:250},
		],
	pager: jQuery('#perfilmodulo'),
   	rowNum:12,
   	//rowList:[20,50,100],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function perfil_modulo_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(perif_modulol_gridReload,150)
}
function perif_modulol_gridReload(){
	var id_perfil = jQuery("#id_perfil").val();
	
	jQuery("#perfil_modulo").setGridParam({url:"modulos/administracion_sistema/perfil/co/sql.consulta_perfil_modulo.php?id_per_mod="+id_perfil,page:1}).trigger("reloadGrid"); 

	
}
function enableAutosubmit(state){
	flAuto = state;
	$("#perfilmodulo_co_btn_consultar").attr("enable",state);
}
</script>

<table style="width:590;" class="cuerpo_formulario">
	<tr>
		<th width="590px" class="titulo_frame" colspan="3"><img src="imagenes/iconos/kappfinder28x28.png" style="padding-right:5px;" align="absmiddle" />Consulta Perfil M&oacute;dulo</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>  
			Perfil&nbsp;&nbsp;<select name="id_perfil"id="id_perfil" onchange="perfil_modulo_doSearch(arguments[0]||event)">
			  <option value="0">--SELECCIONE--</option>
					<?=$opt_perfil?>
				</select>  
 
			&nbsp;&nbsp;
            
  </div>			
			  
			<table id="perfil_modulo" class="scroll" cellpadding="0" cellspacing="0"></table>
			<!-- el div donde radicaran los botones de control del grid -->
			<div id="perfilmodulo" class="scroll" style="text-align:center;"></div>
		</th>
	</tr>
</table>
