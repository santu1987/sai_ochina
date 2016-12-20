<script type="text/javascript">
$('#contabilidad_tipo_comp_nombre').focus();
$("#list_consulta_contabilidad_tipo_comp").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/contabilidad/tipo_comprobante/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['id','C&oacute;digo','Nombre'],
   	colModel:[
			{name:'id',index:'id', width:50,hidden:true},
			{name:'cod',index:'cod', width:50},
			{name:'dec',index:'desc',width:170}
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_contabilidad_tipo_comp'),
   	sortname: 'estatus',
    viewrecords: true,
    sortorder: "asc",
});

var timeoutHnd; 
var flAuto = true;

function contabilidad_cuenta_doSearch(ev)
{ 
	if(!flAuto) return; 
// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(contabilidad_cuenta_gridReload,500)
} 

function contabilidad_cuenta_gridReload()
{ 
	var busq = jQuery("#contabilidad_tipo_comp_nombre").val(); 
	//var busq_cuenta = jQuery("#contabilidad_cuenta_cuenta_con").val(); 
	/*var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	*/jQuery("#list_consulta_contabilidad_tipo_comp").setGridParam({url:"modulos/contabilidad/tipo_comprobante/co/sql.consulta.php?busq="+busq,page:1}).trigger("reloadGrid"); 
	url="modulos/contabilidad/tipo_comprobante/co/sql.consulta.php?busq="+busq;
	//alert(url);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
$('#contabilidad_tipo_comp_nombre').numeric({allow:'.'});

</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Tipo Comprobante</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
				<label id="" for="contabilidad_tipo_comp_nombre">Cod:</label> &nbsp; <input type="text" name="contabilidad_tipo_comp_nombre" id="contabilidad_tipo_comp_nombre" onKeyDown="contabilidad_cuenta_doSearch(arguments[0]||event)" 
                     jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"
			   />                  
		  </div>
			<table id="list_consulta_contabilidad_tipo_comp" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_contabilidad_tipo_comp" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>