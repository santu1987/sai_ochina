<script type="text/javascript">
$('#tesoreria_banco_nombre').focus();
$("#list_consulta_tesoreria_banco").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",
	url:'modulos/tesoreria/banco/co/sql.consulta.php?nd='+new Date().getTime()+"&busq_ano="+getObj("tesoreria_busqueda_ano_bc").value,
	datatype: "json",
   	colNames:['Banco','N Cuenta','Cuenta Contable','Estatus'],
   	colModel:[
			{name:'banco',index:'banco', width:100},
			{name:'n_cuenta',index:'n_cuenta',width:170},
			{name:'cuenta_contable',index:'cuenta_contable',width:170},
			{name:'estatus',index:'estatus',width:100}			
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_tesoreria_banco'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function tesoreria_banco_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(tesoreria_banco_gridReload,500)
} 

function tesoreria_banco_gridReload()
{ 
	var tesoreria_busqueda_banco = jQuery("#tesoreria_banco_nombre").val(); 
	var busq_ano= jQuery("#tesoreria_busqueda_ano_bc").val();
	/*var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	*/jQuery("#list_consulta_tesoreria_banco").setGridParam({url:"modulos/tesoreria/banco/co/sql.consulta.php?tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&busq_ano="+busq_ano,page:1}).trigger("reloadGrid"); 
url="modulos/tesoreria/banco/co/sql.consulta.php?tesoreria_busqueda_banco="+tesoreria_busqueda_banco+"&busq_ano="+busq_ano;
//alert(url);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}
//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'¥'});
$('#tesoreria_banco_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});

</script>


<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Relaci&oacute;n Bancos Cuentas </th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
				<label id="" for="tesoreria_banco_nombre">Banco:</label> &nbsp; <input type="text" name="tesoreria_banco_nombre" id="tesoreria_banco_nombre" onKeyDown="tesoreria_banco_doSearch(arguments[0]||event)" />
			<label id="" for="tesoreria_busqueda_ano_bc">A&ntilde;o&nbsp; &nbsp; :</label>&nbsp;&nbsp;
		 <input type="text"  id="tesoreria_busqueda_ano_bc" size="10" name="tesoreria_busqueda_ano_bc" value="<? echo(date("Y")); ?>" onkeydown="tesoreria_banco_doSearch(arguments[0]||event)"/>
			</div>
			<table id="list_consulta_tesoreria_banco" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_tesoreria_banco" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>