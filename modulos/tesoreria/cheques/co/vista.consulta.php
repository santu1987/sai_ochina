<script type="text/javascript">
$("#list_consulta_tesoreria_banco_usuario").jqGrid
({ 
	height:300,
	width: 700,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/tesoreria/cheques/co/sql.consulta.php?nd='+new Date().getTime()+"&tesoreria_busqueda_ano="+getObj('tesoreria_busqueda_ano').value,
	datatype: "json",
   	colNames:['id','Usuario','Proveedor','Id_Banco','Banco','Cuenta','Chequera','Cheque','Ordenes','Monto','Tipo','Estatus'],
   	colModel:[
			
			{name:'id',index:'id',width:100,hidden:true},
			{name:'nombre',index:'nombre',width:100,hidden:true},
			{name:'proveedor',index:'proveedor',width:180},
			{name:'id_banco',index:'id_banco',width:170,hidden:true},
			{name:'banco',index:'banco',width:140},
			{name:'n_cuenta',index:'n_cuenta',width:160},
			{name:'n_chequera',index:'n_chequera',width:82,hidden:true},
			{name:'n_cheque',index:'n_cheque', width:80},
			{name:'ordenes',index:'ordenes',width:20,hidden:true},
			{name:'monto',index:'monto',width:100},
			{name:'tipo',index:'tipo',width:100,hidden:true},
			{name:'estatus',index:'estatus',width:100}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_tesoreria_banco_usuario'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
	onSelectRow:function(id){			
						var ret=jQuery("#list_consulta_tesoreria_banco_usuario").getRowData(id);
					url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.archivo.phpøid_banco="+ret.id_banco+"@ncheque="+ret.n_cheque+"@ncuenta="+ret.n_cuenta+"@ordenes="+ret.ordenes+"@opcion="+ret.tipo;
								//	
						openTab("Cheques: "+ret.n_cheque,url);
						//alert(url);
				  },
    sortorder: "asc"

});

var timeoutHnd; 
var flAuto = true;

function tesoreria_banco_usuario_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_banco_usuario_gridReload,500)
} 

function tesoreria_banco_usuario_gridReload()
{ 
	  var n_cheques = jQuery("#tesoreria_n_cheques").val(); 
	  var tesoreria_busqueda_banco = jQuery("#tesoreria_banco_nombre_banco").val(); 
  	  var tesoreria_busqueda_cuenta = jQuery("#tesoreria_banco_numero_cuenta").val(); 
  	  var tesoreria_busqueda_proveedor = jQuery("#tesoreria_banco_nombre_proveedor").val(); 
  	  var tesoreria_busqueda_beneficiario = jQuery("#tesoreria_busqueda_beneficiario").val(); 
	  var tesoreria_busqueda_ano=jQuery("#tesoreria_busqueda_ano").val(); 

	  jQuery("#list_consulta_tesoreria_banco_usuario").setGridParam({url:"modulos/tesoreria/cheques/co/sql.consulta.php?n_cheques="+n_cheques+"&tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&tesoreria_busqueda_cuenta="+tesoreria_busqueda_cuenta+"&tesoreria_busqueda_proveedor="+tesoreria_busqueda_proveedor+"&tesoreria_busqueda_beneficiario="+tesoreria_busqueda_beneficiario+"&tesoreria_busqueda_ano="+tesoreria_busqueda_ano,page:1}).trigger("reloadGrid"); 
	  url="modulos/tesoreria/cheques/co/sql.consulta.php?n_cheques="+n_cheques+"&tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&tesoreria_busqueda_cuenta="+tesoreria_busqueda_cuenta+"&tesoreria_busqueda_proveedor="+tesoreria_busqueda_proveedor+"&tesoreria_busqueda_beneficiario="+tesoreria_busqueda_beneficiario+"&tesoreria_busqueda_ano="+tesoreria_busqueda_ano;
	 // alert(url);
	  //setBarraEstado(url);	
//alert("modulos/tesoreria/cheques/co/sql.consulta.php?tesoreria_busqueda_usuario="+tesoreria_busqueda_usuario);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}
$('#tesoreria_n_cheques').numeric({});
$('#tesoreria_banco_nombre_proveedor').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_nombre_banco').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄-'});
$('#tesoreria_banco_numero_cuenta').numeric({allow:'-'});
$('#tesoreria_busqueda_proveedor').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄-'});
$('#tesoreria_busqueda_beneficiario').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄-'});
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
</script>

<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Cheques </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
		 <label id="" for="tesoreria_banco_nombre_usuario">N cheque:</label> 
		 &nbsp; 
		 <input type="text" name="tesoreria_n_cheques" id="tesoreria_n_cheques"  maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_doSearch(arguments[0]||event)" />
		 <label id="" for="tesoreria_banco_nombre_banco">Banco:</label> &nbsp; <input type="text" name="tesoreria_banco_nombre_banco" id="tesoreria_banco_nombre_banco" maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_doSearch(arguments[0]||event)" />
		 <label id="" for="tesoreria_banco_numero_cuenta">Cuenta:</label> &nbsp; <input type="text" name="tesoreria_banco_numero_cuenta" id="tesoreria_banco_numero_cuenta" maxlength="20" size="20" onKeyDown="tesoreria_banco_usuario_doSearch(arguments[0]||event)" /><br/>
		 </div>
		 <div><br/>
		 <label id="" for="tesoreria_banco_nombre_proveedor">Proveedor:</label>
 		 <input type="text" name="tesoreria_banco_nombre_proveedor" id="tesoreria_banco_nombre_proveedor"  maxlength="25" size="25" onKeyDown="tesoreria_banco_usuario_doSearch(arguments[0]||event)" />
		 <label id="" for=" tesoreria_busqueda_beneficiario">Benef :</label>
		 <input type="text" name="tesoreria_busqueda_beneficiario" id="tesoreria_busqueda_beneficiario"  maxlength="25" size="25" onkeydown="tesoreria_banco_usuario_doSearch(arguments[0]||event)" />
		  <label id="" for="tesoreria_busqueda_ano_cheques_an">A&ntilde;o&nbsp; &nbsp; :</label>&nbsp;&nbsp;
		 <input type="text"  id="tesoreria_busqueda_ano" size="10" name="tesoreria_busqueda_ano" value="<? echo(date("Y")); ?>" onkeydown="tesoreria_banco_usuario_doSearch(arguments[0]||event)"/>
		 </div>
		<table id="list_consulta_tesoreria_banco_usuario" class="scroll" cellpadding="0" cellspacing="0" ></table> 
		<div id="pager_consulta_tesoreria_banco_usuario" class="scroll" style="text-align:center;"></div><br/>		</td>
	</tr>
</table>
