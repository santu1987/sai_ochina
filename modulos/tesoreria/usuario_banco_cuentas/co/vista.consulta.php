<script type="text/javascript">
$("#list_consulta_tesoreria_usuario_banco_cuenta").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/tesoreria/usuario_banco_cuentas/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Usuario','Banco','Nº Cuenta','Estatus/Banco','Estatus/Cuenta'],
   	colModel:[
			{name:'usuario',index:'usuario', width:180},
			{name:'banco',index:'banco', width:180},
			{name:'n_cuenta',index:'n_cuenta',width:170},
			{name:'estatusb',index:'estatusb',width:110},
			{name:'estatusc',index:'estatusc',width:110}			
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_tesoreria_usuario_banco_cuenta'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function tesoreria_usuario_banco_cuenta_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_usuario_banco_cuenta_gridReload,500)
} 

function tesoreria_usuario_banco_cuenta_gridReload()
{ 
	jQuery("#list_consulta_tesoreria_usuario_banco_cuenta").setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/co/sql.consulta.php",page:1}).trigger("reloadGrid"); 
// alert("modulos/tesoreria/chequeras/co/sql.consulta.php?tesoreria_busqueda_banco_chequera="+tesoreria_busqueda_banco_chequera+"&tesoreria_busqueda_ncuenta="+tesoreria_busqueda_ncuenta+"&tesoreria_busqueda_nchequera="+tesoreria_busqueda_nchequera);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Relaci&oacute;n Usuario Bancos Cuentas </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		    <table id="list_consulta_tesoreria_usuario_banco_cuenta" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_tesoreria_usuario_banco_cuenta" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>