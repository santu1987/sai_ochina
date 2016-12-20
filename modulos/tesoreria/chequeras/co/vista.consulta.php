<script type="text/javascript">
$('#tesoreria_banco_nombre_chequera').focus();
$("#list_consulta_tesoreria_banco_chequeras").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/tesoreria/chequeras/co/sql.consulta.php?nd='+new Date().getTime()+"&tesoreria_busqueda_chequeras_ano="+getObj('tesoreria_busqueda_chequeras_ano').value,
	datatype: "json",
   	colNames:['Banco','N Cuenta','N Chequera','Cantidad de cheques','Estatus'],
   	colModel:[
			{name:'banco',index:'banco', width:180},
			{name:'n_cuenta',index:'n_cuenta',width:170},
			{name:'n_chequera',index:'n_chequera',width:170},
			{name:'cantidad',index:'cantidad',width:170},
			{name:'estatus',index:'estatus',width:100}			
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_tesoreria_banco_chequeras'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function tesoreria_banco_chequeras_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_banco_chequeras_gridReload,500)
} 

function tesoreria_banco_chequeras_gridReload()
{ 
	var tesoreria_busqueda_banco_chequera = jQuery("#tesoreria_banco_nombre_chequera").val(); 
	var tesoreria_busqueda_ncuenta= jQuery("#tesoreria_banco_numero_cuenta").val(); 
	var tesoreria_busqueda_nchequera=jQuery("#tesoreria_banco_numero_chequera").val(); 
	var tesoreria_busqueda_chequeras_ano=jQuery("#tesoreria_busqueda_ano_chequeras").val();
    jQuery("#list_consulta_tesoreria_banco_chequeras").setGridParam({url:"modulos/tesoreria/chequeras/co/sql.consulta.php?tesoreria_busqueda_banco_chequera="+tesoreria_busqueda_banco_chequera+"&tesoreria_busqueda_ncuenta="+tesoreria_busqueda_ncuenta+"&tesoreria_busqueda_nchequera="+tesoreria_busqueda_nchequera+"&tesoreria_busqueda_chequeras_ano="+tesoreria_busqueda_chequeras_ano,page:1}).trigger("reloadGrid"); 
// alert("modulos/tesoreria/chequeras/co/sql.consulta.php?tesoreria_busqueda_banco_chequera="+tesoreria_busqueda_banco_chequera+"&tesoreria_busqueda_ncuenta="+tesoreria_busqueda_ncuenta+"&tesoreria_busqueda_nchequera="+tesoreria_busqueda_nchequera);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}

$('#tesoreria_banco_nombre_chequera').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_nombre_banco_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_numero_cuenta').numeric({allow:'-'});
$('#tesoreria_banco_numero_chequera').numeric({allow:'-'});


</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Relaci&oacute;n Bancos Cuentas </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="tesoreria_banco_nombre_chequera">Banco:</label> &nbsp; <input type="text" name="tesoreria_banco_nombre_chequera" id="tesoreria_banco_nombre_chequera" onKeyDown="tesoreria_banco_chequeras_doSearch(arguments[0]||event)" />
				<label id="" nombre for="tesoreria_banco_numero_cuenta">N Cuenta:</label>
				<input type="text" name="tesoreria_banco_numero_cuenta" id="tesoreria_banco_numero_cuenta" onkeydown="tesoreria_banco_chequeras_doSearch(arguments[0]||event)" message="Introduzca el numero de cuenta"/>
			<label id="" for="tesoreria_banco_numero_chequera">N Chequera:</label> 
			&nbsp; <input type="text" name="tesoreria_banco_numero_chequera" id="tesoreria_banco_numero_chequera" onkeydown="tesoreria_banco_chequeras_doSearch(arguments[0]||event)" />
			</div>
			 <label id="" for="tesoreria_busqueda_ano_chequeras">A&ntilde;o&nbsp; &nbsp; :</label>&nbsp;&nbsp;
		 <input type="text"  id="tesoreria_busqueda_chequeras_ano" size="10" name="tesoreria_busqueda_chequeras_ano" value="<? echo(date("Y")); ?>" onkeydown="tesoreria_banco_chequeras_doSearch(arguments[0]||event)"/>
			<table id="list_consulta_tesoreria_banco_chequeras" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_tesoreria_banco_chequeras" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>