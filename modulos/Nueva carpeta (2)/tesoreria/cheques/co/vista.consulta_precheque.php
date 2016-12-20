<script type="text/javascript">
$("#list_consulta_tesoreria_banco_usuario_precheque").jqGrid
({ 
	height:300,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/tesoreria/cheques/co/sql.consulta_precheque.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Usuario','Proveedor','Id_Banco','Banco','Cuenta','Precheque','Ordenes','Monto'],
   	colModel:[
			
			{name:'nombre',index:'nombre',width:100},
			{name:'proveedor',index:'proveedor',width:100},
			{name:'id_banco',index:'id_banco',width:170,hidden:true},
			{name:'banco',index:'banco',width:120},
			{name:'n_cuenta',index:'n_cuenta',width:170},
			{name:'n_cheque',index:'n_cheque', width:80},
			{name:'ordenes',index:'ordenes',width:100,hidden:true},
			{name:'monto',index:'monto',width:100}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_tesoreria_banco_usuario_precheque'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function tesoreria_banco_usuario_precheque_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_banco_usuario_precheque_gridReload,500)
} 

function tesoreria_banco_usuario_precheque_gridReload()
{ 
	  var tesoreria_busqueda_usuario_precheque = jQuery("#tesoreria_banco_nombre_usuario_precheque").val(); 
	  var tesoreria_busqueda_banco_precheque= jQuery("#tesoreria_banco_nombre_banco_precheque").val(); 
  	  var tesoreria_busqueda_cuenta_precheque= jQuery("#tesoreria_banco_numero_cuenta_precheque").val(); 
	  var tesoreria_busqueda_proveedor_precheque = jQuery("#tesoreria_busqueda_proveedor_precheque").val(); 
  	  var tesoreria_busqueda_beneficiario_precheque = jQuery("#tesoreria_busqueda_beneficiario_precheque").val(); 

	  jQuery("#list_consulta_tesoreria_banco_usuario_precheque").setGridParam({url:"modulos/tesoreria/cheques/co/sql.consulta_precheque.php?tesoreria_busqueda_usuario_precheque="+tesoreria_busqueda_usuario_precheque+"&tesoreria_busqueda_banco_precheque="+tesoreria_busqueda_banco_precheque+"&tesoreria_busqueda_cuenta_precheque="+tesoreria_busqueda_cuenta_precheque+"&tesoreria_busqueda_proveedor_precheque="+tesoreria_busqueda_proveedor_precheque+"&tesoreria_busqueda_beneficiario_precheque="+tesoreria_busqueda_beneficiario_precheque+"&tesoreria_busqueda_proveedor_precheque="+tesoreria_busqueda_proveedor_precheque,page:1}).trigger("reloadGrid"); 
	  url="modulos/tesoreria/cheques/co/sql.consulta_precheque.php?tesoreria_busqueda_usuario_precheque="+tesoreria_busqueda_usuario_precheque+"&tesoreria_busqueda_banco_precheque="+tesoreria_busqueda_banco_precheque+"&tesoreria_busqueda_cuenta_precheque="+tesoreria_busqueda_cuenta_precheque+"&tesoreria_busqueda_proveedor_precheque="+tesoreria_busqueda_proveedor_precheque+"&tesoreria_busqueda_beneficiario_precheque="+tesoreria_busqueda_beneficiario_precheque+"&tesoreria_busqueda_proveedor_precheque="+tesoreria_busqueda_proveedor_precheque;
	  //setBarraEstado(url);
	  //+"tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&tesoreria_busqueda_cuenta="+tesoreria_busqueda_cuenta,page:1}).trigger("reloadGrid"); 
//alert("modulos/tesoreria/cheques/co/sql.consulta.php?tesoreria_busqueda_usuario="+tesoreria_busqueda_usuario);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
$('#tesoreria_banco_nombre_usuario_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_nombre_proveedor_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_nombre_banco_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄-'});
$('#tesoreria_banco_numero_cuenta_precheque').numeric({allow:'-'});
$('#tesoreria_busqueda_beneficiario_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_busqueda_proveedor_precheque').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});



</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Precheques </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="tesoreria_banco_nombre_usuario_precheque">Usuario:</label> 
		 &nbsp; 
		 <input type="text" name="tesoreria_banco_nombre_usuario_precheque" id="tesoreria_banco_nombre_usuario_precheque"  maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_precheque_doSearch(arguments[0]||event)" />
		 <label id="" for="tesoreria_banco_nombre_banco_precheque">Banco :</label>
		 <input type="text" name="tesoreria_banco_nombre_banco_precheque" id="tesoreria_banco_nombre_banco_precheque" maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_precheque_doSearch(arguments[0]||event)" />
		 <label id="" for="tesoreria_banco_numero_cuenta_precheque">Cuenta:</label>&nbsp; 
		 <input type="text" name="tesoreria_banco_numero_cuenta_precheque" id="tesoreria_banco_numero_cuenta_precheque" maxlength="20" size="20" onKeyDown="tesoreria_banco_usuario_precheque_doSearch(arguments[0]||event)" /><br/>
		 </div>
		<label id="" for="tesoreria_banco_nombre_proveedor">Proveedor:</label>
 		 <input type="text" name="tesoreria_busqueda_proveedor_precheque" id="tesoreria_busqueda_proveedor_precheque"  maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_precheque_doSearch(arguments[0]||event)"  />
		 <label id="" for=" tesoreria_busqueda_beneficiario_precheque">Benef    :</label>
 		 <input type="text" name="tesoreria_busqueda_beneficiario_precheque" id="tesoreria_busqueda_beneficiario_precheque"  maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_precheque_doSearch(arguments[0]||event)"/>
		 </div>
		
		<table id="list_consulta_tesoreria_banco_usuario_precheque" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_consulta_tesoreria_banco_usuario_precheque" class="scroll" style="text-align:center;"></div><br/> 
		</td>
	</tr>
</table>