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
$('#causado_pagado_partida').numeric({allow:'1234567890.,-'});
$("input, select, textarea").bind("focus", function(){
/////////////////////////////funcion de permite mostrar menages en pantalla
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
});
$("#causado_pagado_btn_consultar_hasta").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_comprobante.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante").val(); 
					var ano=$("#consulta_ano_comp").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano;
					//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_ano_comp").change(
					function()
					{
						
						dosearch();													
					}											
				);
				$("#consulta_comprobante").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}
								$("#consulta_comprobante_tipo").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);

				function gridReload()
				{
					var busq_cuenta= $("#consulta_comprobante").val();
					var ano=$("#consulta_ano_comp").val();
					var tipo=$("#consulta_comprobante_tipo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
					//alert(url);				
				}
			}
		}
	);

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
			colNames:['Id','N Comprobante','','fecha'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha',index:'fecha', width:200,sortable:false,resizable:false}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);	
				//alert(ret.numero_comprobante);			
				$('#causado_pagado_numero_id').val(ret.id);
				$('#causado_pagado_numero_cxp_hasta').val(ret.numero_comprobante);
				$('#causado_pagado_numero_cxp_hasta2').val(ret.numero_comprobante2);
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

$("#causado_pagado_btn_consultar_cuenta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_comprobante.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante").val(); 
					var ano=$("#consulta_ano_comp").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano;
					//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_ano_comp").change(
					function()
					{
						
						dosearch();													
					}											
				);
				$("#consulta_comprobante").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);
				$("#consulta_comprobante_tipo").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta_comprobante").val();
					var ano=$("#consulta_ano_comp").val();
					var tipo=$("#consulta_comprobante_tipo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
                	//alert(url);				
				}
			}
		}
	);

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
			colNames:['Id','N Comprobante','ncomp2','Fecha'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'Fecha',index:'Fecha', width:200,sortable:false,resizable:false}
	
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_numeracion_comprobante_db_id').val(ret.id);
				$('#causado_pagado_numero_cxp_desde').val(ret.numero_comprobante);
				$('#causado_pagado_numero_cxp_desde2').val(ret.numero_comprobante2);
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
$("#causado_pagado_rp_imprimir").click(function()
 {
	if ($('#form_cxp_rp_movimientos_contables_contable').jVal()){	
				
		desde_fecha=getObj('causado_pagado_rp_fecha_desde').value;
		hasta_fecha=getObj('causado_pagado_rp_fecha_hasta').value;
		desde_fecha_pagado=getObj('pagado_rp_fecha_desde').value;
		hasta_fecha_pagado=getObj('pagado_rp_fecha_hasta').value;
		
		partida=getObj('causado_pagado_partida').value;
		
		url="pdf.php?p=modulos/cuentas_por_pagar/documentos/rp/vista.lst.causado_pagado.php¿desde_fecha="+desde_fecha+"@hasta_fecha="+hasta_fecha+"@partida="+partida+"@desde_fecha_pagado="+desde_fecha_pagado+"@hasta_fecha_pagado="+hasta_fecha_pagado;
		openTab("CausadoPagado",url);
	//	alert(url);
		}
});
$("#causado_pagado_rp_cancelar").click(function() {
setBarraEstado("");
clearForm('form_cxp_rp_movimientos_contables_contable');
getObj('causado_pagado_rp_fecha_desde').value= "<?=$fecha;?>";
getObj('causado_pagado_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";

});
$('#causado_pagado_numero_cxp_desde').numeric({allow:',.-'});
$('#causado_pagado_numero_cxp_hasta').numeric({allow:',.-'});

</script>
<div id="botonera">
<img id="causado_pagado_rp_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="causado_pagado_rp_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
 	
	</div>
<form method="post" id="form_cxp_rp_movimientos_contables_contable" name="form_cxp_rp_movimientos_contables_contable">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Registros Causados/Pagados </th>
		</tr>
		<tr>
		<th colspan="4" align="center"><p align="center">&nbsp;</p>		</tr>
		
		</tr>
		<tr>
		<th colspan="1" align="center">
		Partida
		</th>
		<td colspan="1"><input type="text" name="causado_pagado_partida" id="causado_pagado_partida" style="text-align:right" message="Ingrese una partida. " jvalkey="{valid:/[,.-_0123456789]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}"/></td>
		
			<th>Compromiso:</th>
			<td>
					<input type="text" name="causado_pagado_compomiso" id="causado_pagado_comprmiso" valkey="{valid:/[,.-_0123456789]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}"/>
			</td>
			</td>
		</tr>
		<tr>	
				<th colspan="4" align="center" ><p align="center">Fecha :</p></th>
		</tr>
		<tr  >
			<th width="118">
				  Desde:			</th>
		<td width="124">
		            <input readonly="true" type="text" name="causado_pagado_rp_fecha_desde" id="causado_pagado_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="causado_pagado_rp_fecha_desde_oculto" id="causado_pagado_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
				  <button type="reset" id="tesoreria_cheques_usuarios_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "causado_pagado_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "tesoreria_cheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("causado_pagado_rp_fecha_desde").value.MMDDAAAA() );
										f2=new Date( getObj("causado_pagado_rp_fecha_hasta").value.MMDDAAAA() );
										if (f1 > f2) {
											//setBarraEstado(mensaje[fecha_impuesto],true,true);
											alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
											getObj("causado_pagado_rp_fecha_desde").value =getObj("causado_pagado_rp_fecha_desde_oculto").value;
											}
									}
							});
					</script>		</td>
		<th width="104">
					Hasta:		</th>
		<td width="120">	
					<input readonly="true" type="text" name="causado_pagado_rp_fecha_hasta" id="causado_pagado_rp_fecha_hasta" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
						jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
					  <input type="hidden"  name="causado_pagado_rp_fecha_hasta_oculto" id="causado_pagado_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
					  <button type="reset" id="causado_pagado_rp_fecha_boton_h">...</button>
					  <script type="text/javascript">
								Calendar.setup({
									inputField     :    "causado_pagado_rp_fecha_hasta",      // id of the input field
									ifFormat       :    "%d/%m/%Y",       // format of the input field
									showsTime      :    false,            // will display a time selector
									button         :    "causado_pagado_rp_fecha_boton_h",   // trigger for the calendar (button ID)
									singleClick    :    true,          // double-click mode
									onUpdate :function(date){
											f1=new Date( getObj("causado_pagado_rp_fecha_desde").value.MMDDAAAA() );
											f2=new Date( getObj("causado_pagado_rp_fecha_boton_h").value.MMDDAAAA() );
											if (f1 > f2) {
												//setBarraEstado(mensaje[fecha_impuesto],true,true);
												alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
												getObj("causado_pagado_rp_fecha_hasta").value =getObj("causado_pagado_rp_fecha_hasta_oculto").value;
												}
										}
								});
						</script>			</td>	
			</tr>
			<tr style=" display:none">	
			  <th height="25" colspan="4" align="center"  ><p align="center">Fecha Pagado:</p></th>
		</tr>
		<tr  style="display:none" >
			<th width="118">
				  Desde:			</th>
		<td width="124">
		            <input readonly="true" type="text" name="pagado_rp_fecha_desde" id="pagado_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="pagado_rp_fecha_desde_oculto" id="pagado_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
				  <button type="reset" id="pagado_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "pagado_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "pagado_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("pagado_rp_fecha_desde").value.MMDDAAAA() );
										f2=new Date( getObj("pagado_rp_fecha_hasta").value.MMDDAAAA() );
										if (f1 > f2) {
											//setBarraEstado(mensaje[fecha_impuesto],true,true);
											alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
											getObj("pagado_rp_fecha_desde").value =getObj("pagado_rp_fecha_desde_oculto").value;
											}
									}
							});
					</script>		</td>
		<th width="104" style="display:none">
					Hasta:		</th>
		<td width="120">	
					<input readonly="true" type="text" name="pagado_rp_fecha_hasta" id="pagado_rp_fecha_hasta" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
						jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
					  <input type="hidden"  name="pagado_rp_fecha_hasta_oculto" id="pagado_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
					  <button type="reset" id="pagado_rp_fecha_boton_h">...</button>
					  <script type="text/javascript">
								Calendar.setup({
									inputField     :    "pagado_rp_fecha_hasta",      // id of the input field
									ifFormat       :    "%d/%m/%Y",       // format of the input field
									showsTime      :    false,            // will display a time selector
									button         :    "pagado_rp_fecha_boton_h",   // trigger for the calendar (button ID)
									singleClick    :    true,          // double-click mode
									onUpdate :function(date){
											f1=new Date( getObj("pagado_rp_fecha_desde").value.MMDDAAAA() );
											f2=new Date( getObj("pagado_rp_fecha_boton_h").value.MMDDAAAA() );
											if (f1 > f2) {
												//setBarraEstado(mensaje[fecha_impuesto],true,true);
												alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
												getObj("pagado_rp_fecha_hasta").value =getObj("pagado_rp_fecha_hasta_oculto").value;
												}
										}
								});
						</script>			</td>	
			</tr>	
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>