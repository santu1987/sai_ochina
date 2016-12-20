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
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript">

$("#consulta_entrevista").jqGrid
({ 
	height: 250,
	width: 600,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/entrevista/co/sql.consulta_entrevista.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Cedula','Nombre','Fecha Entrevista','Rama'],
	colModel:[
		{name:'cedula',index:'cedula', width:150},
		{name:'nombre',index:'nombre', width:150},
		{name:'fecha_entrevista',index:'fecha_entrevista', width:150},
		{name:'rama',index:'rama', width:150}
	],
	pager: jQuery('#consultaentrevista'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'cedula',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function entrevista_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(entrevista_gridReload,150)
}
function entrevista_gridReload(){
	var nombre_ramas = jQuery("#nombre_ramas").val();
	var nombre_nivel = jQuery("#nombre_nivel").val();
	var fecha_entrevista = jQuery("#fecha_entrevista").val();
	jQuery("#consulta_entrevista").setGridParam({url:"modulos/rrhh/entrevista/co/sql.consulta_entrevista.php?nombre_ramas="+nombre_ramas+"&nombre_nivel="+nombre_nivel+"&fecha_entrevista="+fecha_entrevista,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#entrevista_co_btn_consultar").attr("enable",state);
}
$('#nombre_ramas').alpha({nocaps:true,allow:' '});
$('#nombre_nivel').alpha({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Entrvistas</th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>
            <strong>Nivel Academico</strong>: </td>                  
	             <select id="nombre_nivel" name="nombre_nivel" onchange="entrevista_doSearch(arguments[0]||event)">
	               <option value="">------------ SELECCIONE ------------</option>
	               <?= $opt_nivel?>
    </select>
			   Ramas &nbsp; <input name="nombre_ramas" type="text" id="nombre_ramas" onkeydown="entrevista_doSearch(arguments[0]||event)" size="25" maxlength="40"> 
			   Fecha &nbsp; <input type="text" name="fecha_entrevista" id="fecha_entrevista" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}" onchange="entrevista_doSearch(arguments[0]||event)"/>
		<button type="reset" id="fecha_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "fecha_entrevista",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>

	      </div>
			<table id="consulta_entrevista" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultaentrevista" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>