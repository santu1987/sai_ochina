<script type="text/javascript">
$('#contabilidad_cuentas_contables_nombre').focus();
$("#list_consulta_contabilidad_cuenta").jqGrid
({ 
	height: 315,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/contabilidad/cuentas_contables/co/sql.consulta.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['id','Cuenta Contable','Nombre','Tipo','Suma','ax','cc','ub','utf','Cta Presupuesto'],
   	colModel:[
			{name:'id',index:'id', width:50,hidden:true},
			{name:'cuenta_contable',index:'cuenta_contable', width:50},
			{name:'nombre',index:'nombre',width:170},
			{name:'tipo',index:'tipo',width:20},
			{name:'suma',index:'suma',width:50},
			{name:'ax',index:'ax',width:10},
			{name:'cc',index:'cc',width:10},		
			{name:'ub',index:'ub',width:10},
			{name:'uf',index:'uf',width:10},
			{name:'cta_presupuesto',index:'cta_presupuesto',width:50}
		
   	],
   	rowNum:20,
   	rowList:[20,50,100],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_consulta_contabilidad_cuenta'),
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
	var busq_nombre = jQuery("#contabilidad_nombre_cuenta_con").val(); 
	var busq_cuenta = jQuery("#contabilidad_cuenta_cuenta_con").val(); 
	/*var presupuesto_ley_busqueda_codigo = jQuery("#presupuesto_ley_busqueda_codigo").val(); 
	var presupuesto_ley_busqueda_partida=jQuery("#presupuesto_ley_busqueda_partida").val(); 
	*/jQuery("#list_consulta_contabilidad_cuenta").setGridParam({url:"modulos/contabilidad/cuentas_contables/co/sql.consulta.php?busq_nombre="+busq_nombre+"&busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
	url="modulos/contabilidad/cuentas_contables/co/sql.consulta.php?busq_nombre="+busq_nombre+"&busq_cuenta="+busq_cuenta;
	//alert(url);
} 
	
function enableAutosubmit(state)
{ 
	flAuto = state; 
	jQuery("#tesoreria_co_btn_consultar").attr("disabled",state); 
}//$('#tesoreria_banco_nombre').apha({nocaps:true});
//$('#presupuesto_ley_busqueda_partida').numeric({allow:'.'});
//$('#presupuesto_ley_busqueda_nombre').alpha({nocaps:true,allow:'´'});
$('#contabilidad_nombre_cuenta_con').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_cuenta_cuenta_con').numeric({allow:'.'});

</script>
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Consulta Cuentas Contables</th>
	</tr>
	<tr>	
		<td class="celda_consulta">
		  <div class="div_busqueda">
				<label id="" for="contabilidad_nombre_cuenta_con">Nombre:</label> &nbsp; <input type="text" name="contabilidad_nombre_cuenta_con" id="contabilidad_nombre_cuenta_con" onKeyDown="contabilidad_cuenta_doSearch(arguments[0]||event)" 
                   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	
                Cuenta Contable:
                <input type="text" name="contabilidad_cuenta_cuenta_con" id="contabilidad_cuenta_cuenta_con" onKeyDown="contabilidad_cuenta_doSearch(arguments[0]||event)" 
                jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"
			   />
			</div>
			<table id="list_consulta_contabilidad_cuenta" class="scroll" cellpadding="0" cellspacing="0" ></table> 
			<div id="pager_consulta_contabilidad_cuenta" class="scroll" style="text-align:center;"></div> 
		</td>
	</tr>
</table>