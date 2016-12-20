<script type="text/javascript">
$("#list_anular_cheques").jqGrid
({ 
	height:300,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/tesoreria/cheques/co/sql.consulta_cheques_anulados.php?nd='+new Date().getTime()+"&tesoreria_busqueda_ano_cheques_an="+getObj('tesoreria_busqueda_ano_cheques_an').value,
	datatype: "json",
   	colNames:['Usuario','Beneficiario','Id_Banco','Banco','Cuenta','Chequera','Cheque','Ordenes','Monto','tipo'],
   	colModel:[
			
			{name:'nombre',index:'nombre',width:100,hidden:true},
			{name:'proveedor',index:'proveedor',width:100},
			{name:'id_banco',index:'id_banco',width:170,hidden:true},
			{name:'banco',index:'banco',width:120},
			{name:'n_cuenta',index:'n_cuenta',width:170},
			{name:'n_chequera',index:'n_chequera',width:82},
			{name:'n_cheque',index:'n_cheque', width:80},
			{name:'ordenes',index:'ordenes',width:100,hidden:true},
			{name:'monto',index:'monto',width:100,hidden:true},
			{name:'tipo',index:'tipo',width:100,hidden:true}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_anular_cheques'),
   	sortname: 'estatus',
    viewrecords: true,
	onSelectRow:function(id){			
					var ret=jQuery("#list_anular_cheques").getRowData(id);
					url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.archivo.phpøid_banco="+ret.id_banco+"@ncheque="+ret.n_cheque+"@ncuenta="+ret.n_cuenta+"@ordenes="+ret.ordenes+"@opcion="+ret.tipo;
					openTab("Cheques: "+ret.n_cheque,url);
					//alert(url);
				  },
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function anular_cheque_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(anular_cheque_gridReload,500)
		
} 

function anular_cheque_gridReload()
{ 
	  var usu_cheanu = jQuery("#usu_cheanu").val(); 
	  var banco_cheanu = jQuery("#banco_cheanu").val(); 
  	  var cuenta_cheanu = jQuery("#cuenta_cheanu").val(); 
  	  var prov_cheanu = jQuery("#prov_cheanu").val(); 
	  var tesoreria_busqueda_beneficiario_anulado = jQuery("#tesoreria_busqueda_beneficiario_anulado").val(); 
  	  var tesoreria_busqueda_proveedor_anulado = jQuery("#tesoreria_busqueda_proveedor_anulado").val(); 
	  var tesoreria_busqueda_ano_cheques_an=jQuery("#tesoreria_busqueda_ano_cheques_an").val();

 jQuery("#list_anular_cheques").setGridParam({url:"modulos/tesoreria/cheques/co/sql.consulta_cheques_anulados.php?usu_cheanu="+usu_cheanu+"&banco_cheanu="+banco_cheanu+"&cuenta_cheanu="+cuenta_cheanu+"&prov_cheanu="+prov_cheanu+"&tesoreria_busqueda_proveedor_anulado="+tesoreria_busqueda_proveedor_anulado+"&tesoreria_busqueda_beneficiario_anulado="+tesoreria_busqueda_beneficiario_anulado+"&tesoreria_busqueda_ano_cheques_an="+tesoreria_busqueda_ano_cheques_an,page:1}).trigger("reloadGrid"); 
	url="modulos/tesoreria/cheques/co/sql.consulta_cheques_anulados.php?usu_cheanu="+usu_cheanu+"&banco_cheanu="+banco_cheanu+"&cuenta_cheanu="+cuenta_cheanu+"&prov_cheanu="+prov_cheanu+"&tesoreria_busqueda_proveedor_anulado="+tesoreria_busqueda_proveedor_anulado+"&tesoreria_busqueda_beneficiario_anulado="+tesoreria_busqueda_beneficiario_anulado+"&tesoreria_busqueda_ano_cheques_an="+tesoreria_busqueda_ano_cheques_an;
	//alert(url);
	//alert(tesoreria_busqueda_usuario_anular_cheques);
	  //+"tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&tesoreria_busqueda_cuenta="+tesoreria_busqueda_cuenta,page:1}).trigger("reloadGrid"); 
//alert("modulos/tesoreria/cheques/co/sql.consulta.php?tesoreria_busqueda_usuario="+tesoreria_busqueda_usuario);
} 
	
/*function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar_anular_chques").attr("disabled",state); 
}*/
$('#usu_cheanu').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#banco_cheanu').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#prov_cheanu').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#cuenta_cheanu').numeric({allow:'-'});
$('#tesoreria_busqueda_proveedor_anulado').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_busqueda_beneficiario_anulado').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});

//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta  Cheques Anulados</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="usu_cheanu">Usuario:</label> 
		 &nbsp; 
		 <input type="text" name="usu_cheanu" id="usu_cheanu"  maxlength="25" size="25" onKeyDown="anular_cheque_doSearch(arguments[0]||event)" />
         
		 <label id="" for="banco_cheanu">Banco:</label> &nbsp; <input type="text" name="banco_cheanu" id="banco_cheanu" maxlength="25" size="25" onKeyDown="anular_cheque_doSearch(arguments[0]||event)" />
         
		 <label id="" for="cuenta_cheanu">Cuenta:</label> &nbsp; <input type="text" name="cuenta_cheanu" id="cuenta_cheanu" maxlength="20" size="20" onKeyDown="anular_cheque_doSearch(arguments[0]||event)" /><br/>
<br/>
         
		 <label id="" for="prov_cheanu" style="display:none">Proveedor:</label>
 		 <input type="text" name="prov_cheanu" id="prov_cheanu"  maxlength="25" size="25" onKeyDown="anular_cheque_doSearch(arguments[0]||event)" style="display:none"/>
		 </div>
		 <label id="" for="tesoreria_busqueda_proveedor_anulado">Proveedor:</label>
 		 <input type="text" name="tesoreria_busqueda_proveedor_anulado" id="tesoreria_busqueda_proveedor_anulado"  maxlength="25" size="25" onKeyDown="anular_cheque_doSearch(arguments[0]||event)"  />
		 <label id="" for=" tesoreria_busqueda_beneficiario_anulado">Benef    :</label>
 		 <input type="text" name="tesoreria_busqueda_beneficiario_anulado" id="tesoreria_busqueda_beneficiario_anulado"  maxlength="25" size="25" onKeyDown="anular_cheque_doSearch(arguments[0]||event)"/>
		  <label id="" for="tesoreria_busqueda_ano_cheques_an">A&ntilde;o&nbsp; &nbsp; :</label>&nbsp;&nbsp;
		 <input type="text"  id="tesoreria_busqueda_ano_cheques_an" size="10" name="tesoreria_busqueda_ano_cheques_an" value="<? echo(date("Y")); ?>" onkeydown="anular_cheque_doSearch(arguments[0]||event)"/>
		 </div>
		<table id="list_anular_cheques" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_anular_cheques" class="scroll" style="text-align:center;"></div><br/> 
		</td>
	</tr>
</table>