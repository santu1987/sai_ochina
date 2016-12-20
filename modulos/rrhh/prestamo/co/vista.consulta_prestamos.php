<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


?>
<script type="text/javascript">

$("#consulta_prestamo").jqGrid
({ 
	height: 250,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/rrhh/prestamo/co/sql.consulta_prestamos.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Cedula','Nombre','Apellido','Monto','Cuota','Saldo','Frecuencia','Fecha'],
	colModel:[
		{name:'cedula',index:'cedula', width:100},
		{name:'nombre',index:'nombre', width:100},
		{name:'apellido',index:'apellido', width:100},
		{name:'monto',index:'monto', width:70},
		{name:'cuota',index:'cuota', width:70},
		{name:'saldo',index:'saldo', width:70},
		{name:'frecuencia',index:'frecuencia', width:70},
		{name:'fecha',index:'fecha', width:70}
	],
	pager: jQuery('#consultaprestamo'),
   	rowNum:20,
   	imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
   	sortname: 'cedula',
    viewrecords: true,
    sortorder: "asc"
});
var timeoutHnd;
var flAuto = true;

function prestamo_doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(prestamo_gridReload,150)
}
function prestamo_gridReload(){
	var nombre_concepto = jQuery("#descripcion_concepto").val();
	jQuery("#consulta_prestamo").setGridParam({url:"modulos/rrhh/prestamo/co/sql.consulta_prestamos.php?nombre_concepto="+nombre_concepto,page:1}).trigger("reloadGrid"); 

}
function enableAutosubmit(state){
	flAuto = state;
	$("#consulta_co_btn_consultar").attr("enable",state);
}
$('#descripcion_concepto').numeric({nocaps:true,allow:' '});
</script>

</div>
<table style="width:300px;" class="cuerpo_formulario">
	<tr>
		<th width="300px" class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Prestamos </th>
	</tr>
	<tr>	
		<th bgcolor="#BADBFC">
			<div>Cedula:&nbsp; 
			  <input name="descripcion_concepto" type="text" id="descripcion_concepto" onkeydown="prestamo_doSearch(arguments[0]||event)" size="40" maxlength="40">  			   
	      </div>
			<table id="consulta_prestamo" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="consultaprestamo" class="scroll" style="text-align:center;"></div> 
		</th>
	</tr>
</table>