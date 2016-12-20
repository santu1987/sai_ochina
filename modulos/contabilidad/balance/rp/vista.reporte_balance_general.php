<? session_start();
if(date("d")=="31")
{
	$dia=date("d")-1;
	$mes=date("m")-1;
	$ayo=date("Y");
}
	else
	{
		$dia=date("d");	
	}
if(date("m")=="1")
{
	$mes="12";
	$ayo=date("Y")-1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$("#balance_general_btn_consultar_hasta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/rp/grid_pagar.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N° Comprobante'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);	
				//alert(ret.numero_comprobante);			
				$('#balance_general_numero_id').val(ret.id);
				$('#balance_general_numero_cxp_hasta').val(ret.numero_comprobante);
				dialog.hideAndUnload();
			},
					 			
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#balance_general_btn_consultar_cuenta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/rp/grid_pagar.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Comprobantes Contables', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N° Comprobante Int'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false}
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_numeracion_comprobante_db_id').val(ret.id);
				$('#balance_general_numero_cxp_desde').val(ret.numero_comprobante);
				dialog.hideAndUnload();
			},
					 			
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#balance_general_rp_imprimir").click(function(){
		$fecha=getObj('balance_general_rp_fecha_desde').value;
		url="pdf.php?p=modulos/contabilidad/balance/rp/vista.lst.balance_general.php?fecha="+$fecha;
		openTab("BalanceGeneral",url);
		//alert(url);
		
});
$("#balance_general_rp_cancelar").click(function() {
setBarraEstado("");
clearForm('form_cxp_rp_movimientos_contables_contable');
getObj('balance_general_rp_fecha_desde').value= "<?=$fecha;?>";
getObj('balance_general_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";

});
$('#balance_general_numero_cxp_desde').numeric({allow:',.-'});
$('#balance_general_numero_cxp_hasta').numeric({allow:',.-'});

</script>
<div id="botonera">
<img id="balance_general_rp_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="balance_general_rp_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
 	
	</div>
<form method="post" id="balance_general_contable" name="balance_general_contable">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />BALANCE GENERAL </th>
		</tr>
		<tr>	
				<th colspan="4" align="center"  ><p align="center">Fecha:</p></th>
		</tr>
		<tr>
			<th width="118">
				  Hasta:			</th>
		<td width="124">
		            <input readonly="true" type="text" name="balance_general_rp_fecha_desde" id="balance_general_rp_fecha_desde" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="balance_general_rp_fecha_desde_oculto" id="balance_general_rp_fecha_desde_oculto" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>"/>
				  <button type="reset" id="balance_general_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "balance_general_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "balance_general_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("balance_general_rp_fecha_desde").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_general_rp_fecha_hasta").value.MMDDAAAA() );
										/*if (f1 > f2) {
											//setBarraEstado(mensaje[fecha_impuesto],true,true);
											alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
											getObj("balance_general_rp_fecha_desde").value =getObj("balance_general_rp_fecha_desde_oculto").value;
											}*/
									}
							});
					</script>		</td>
			
	  </tr>	
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>