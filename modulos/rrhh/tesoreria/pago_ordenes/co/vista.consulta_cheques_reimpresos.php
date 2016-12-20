<script type="text/javascript">
$("#list_reimprimir_cheques").jqGrid
({ 
	height:400,
	width: 940,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/tesoreria/cheques/co/sql.consulta_cheques_reimpresos.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Usuario','Beneficiario','Ch Emitidos','Id_Banco','Banco','Cuenta','Ch Reimp','Id_Banco_reimpreso','Banco Reimpreso','Cuenta Reimpreso','Chequera','Ordenes','Monto'],
   	colModel:[
			
			{name:'nombre',index:'nombre',width:100},
			{name:'proveedor',index:'proveedor',width:170},
			{name:'n_cheque',index:'n_cheque', width:120},
			{name:'id_banco',index:'id_banco',width:50,hidden:true},
			{name:'banco',index:'banco',width:200},
			{name:'n_cuenta',index:'n_cuenta',width:200},
			{name:'n_cheque_r',index:'n_cheque_r', width:120},
			{name:'id_banco_r',index:'id_banco_r',width:50,hidden:true},
			{name:'banco_r',index:'banco_r',width:200},
			{name:'n_cuenta_r',index:'n_cuenta_r',width:200},
			{name:'n_chequera',index:'n_chequera',width:50,hidden:true},
			{name:'ordenes',index:'ordenes',width:50,hidden:true},
			{name:'monto',index:'monto',width:100}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_reimprimir_cheques'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function reimprimir_cheque_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(reimprimir_cheque_gridReload,500)
		
} 

function reimprimir_cheque_gridReload()
{ 
	  var usu_chereimp = jQuery("#usu_chereimp").val(); 
	  var banco_chereimp = jQuery("#banco_chereimp").val(); 
  	  var cuenta_chereimp = jQuery("#cuenta_chereimp").val(); 
  	  var prov_chereimp = jQuery("#prov_chereimp").val(); 
	  var tesoreria_busqueda_proveedor_reim = jQuery("#tesoreria_busqueda_proveedor_reim").val(); 
  	  var tesoreria_busqueda_beneficiario_reim = jQuery("#tesoreria_busqueda_beneficiario_reim").val(); 




 jQuery("#list_reimprimir_cheques").setGridParam({url:"modulos/tesoreria/cheques/co/sql.consulta_cheques_reimpresos.php?usu_chereimp="+usu_chereimp+"&banco_chereimp="+banco_chereimp+"&cuenta_chereimp="+cuenta_chereimp+"&prov_chereimp="+prov_chereimp,page:1}).trigger("reloadGrid"); 
	//alert(tesoreria_busqueda_usuario_anular_cheques);
	  //+"tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&tesoreria_busqueda_cuenta="+tesoreria_busqueda_cuenta,page:1}).trigger("reloadGrid"); 
//alert("modulos/tesoreria/cheques/co/sql.consulta.php?tesoreria_busqueda_usuario="+tesoreria_busqueda_usuario);
} 
	
/*function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar_anular_chques").attr("disabled",state); 
}*/
$('#usu_chereimp').alpha({allow:' áéíóúÄÉÍÓÚ.- '});
$('#banco_chereimp').alpha({allow:' áéíóúÄÉÍÓÚ-'});
$('#prov_chereimp').alpha({allow:' áéíóúÄÉÍÓÚ'});
$('#cuenta_chereimp').numeric({allow:'-'});
$('#tesoreria_busqueda_proveedor_reim').alpha({allow:' áéíóúÄÉÍÓÚ.- '});
$('#tesoreria_busqueda_beneficiario_reim').alpha({allow:' áéíóúÄÉÍÓÚ.- '});

//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Cheques Reimpresos</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="usu_chereimp">Usuario:</label> 
		 &nbsp; 
		 <input type="text" name="usu_chereimp" id="usu_chereimp"  maxlength="25" size="25" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)" />
         
		 <label id="" for="banco_chereimp">Banco:</label> &nbsp; <input type="text" name="banco_chereimp" id="banco_chereimp" maxlength="25" size="25" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)" />
         
		 <label id="" for="cuenta_chereimp">Cuenta:</label> &nbsp; <input type="text"  name="cuenta_chereimp" id="cuenta_chereimp" maxlength="20" size="20" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)" /><br/>
		 <label id="" for="prov_chereimp" style="display:none">Proveedor:</label>
 		 <input type="text" name="prov_chereimp" id="prov_chereimp"  maxlength="25" size="25" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)" style="display:none"/>
		 </div>
		 <label id="" for="tesoreria_busqueda_proveedor_reim">Proveedor:</label>
 		 <input type="text" name="tesoreria_busqueda_proveedor_reim" id="tesoreria_busqueda_proveedor_reim"  maxlength="25" size="25" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)"  />
		 <label id="" for=" tesoreria_busqueda_beneficiario_reim">Benef    :</label>
 		 <input type="text" name="tesoreria_busqueda_beneficiario_reim" id="tesoreria_busqueda_beneficiario_reim"  maxlength="25" size="25" onKeyDown="reimprimir_cheque_doSearch(arguments[0]||event)"/>
		 </div>
		<table id="list_reimprimir_cheques" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_reimprimir_cheques" class="scroll" style="text-align:center;"></div><br/> 
		</td>
	</tr>
</table>