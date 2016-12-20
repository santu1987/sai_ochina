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

$("#tesoreria_integracion_reversar_pr_btn_reversar").click(function (){
if($('#form_tesoreria_rp_integracion_contable_reverso').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax(
		{
			url:"modulos/tesoreria/integracion_contable/pr/sql.reverso_integracion.php",
			data:dataForm('form_tesoreria_rp_integracion_contable_reverso'),
			type:'POST',
			cache: false,
			success:function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO CONCLUIDO</p></div>",true,true);
					clearForm('form_tesoreria_rp_integracion_contable_reverso');
					getObj('tesoreria_integracion_reverso_rp_fecha_desde').value= "<?=$fecha;?>";
					getObj('tesoreria_integracion_reverso_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";
				}
				else if (html=="no_reverso")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REVERSO FALLIDO</p></div>",true,true);
					clearForm('form_tesoreria_rp_integracion_contable_reverso');
					getObj('tesoreria_integracion_reverso_rp_fecha_desde').value= "<?=$fecha;?>";
					getObj('tesoreria_integracion_reverso_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";
				}
					else
				{
					alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				
//
				}
			
			}
		});
	}
	});
$("#tesoreria_integracion_reverso_rp_cancelar").click(function() {
setBarraEstado("");
clearForm('form_tesoreria_rp_integracion_contable_reverso');
getObj('tesoreria_integracion_reverso_rp_fecha_desde').value= "<?=$fecha;?>";
getObj('tesoreria_integracion_reverso_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";

});
$("#tesoreria_integracion_reverso_btn_consultar_cuenta").click(function() {

	
/////////////////////////////////////////////////////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/tesoreria/integracion_contable/rp/grid_comprobante_tes.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante_cxp").val(); 
					var ano=$("#tesoreria_reverso_integracion_ano").val();
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
					//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_comprobante_cxp_tipo").change(
					function()
					{
						dosearch();													
					}											
				);
				$("#consulta_comprobante_tes").keypress(
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
					var busq_cuenta= $("#consulta_comprobante_tes").val();
					var ano=$("#tesoreria_reverso_integracion_ano").val();
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
            //    alert(url);				
				}
			}
		}
	);

/////////////////////////////////////////////////////
		function crear_grid()
		{
							jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N Comprobante Int','','Fecha_comprobante'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha_comp',index:'fecha_comp', width:120,sortable:false,resizable:false,hidden:true}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#tesoreria_integracion_reverso_numero_id').val(ret.id);
				$('#tesoreria_integracion_reverso_numero_c_desde').val(ret.numero_comprobante);
				$('#tesoreria_integracion_reverso_numero_c_desde2').val(ret.numero_comprobante2);
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
$("#tesoreria_integracion_reverso_btn_consultar_cuenta_hasta").click(function() {

	
/////////////////////////////////////////////////////
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/tesoreria/integracion_contable/rp/grid_comprobante_tes.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Comprobantes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta_comprobante_cxp").val(); 
					var ano=$("#tesoreria_reverso_integracion_ano").val();
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
					//alert(url);
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#consulta_comprobante_cxp_tipo").change(
					function()
					{
						dosearch();													
					}											
				);
				$("#consulta_comprobante_tes").keypress(
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
					var busq_cuenta= $("#consulta_comprobante_tes").val();
					var ano=$("#tesoreria_reverso_integracion_ano").val();
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
            //    alert(url);				
				}
			}
		}
	);

/////////////////////////////////////////////////////
		function crear_grid()
		{
							jQuery("#list_grid_"+nd).jqGrid
		({
			width:350,
			height:300,
			recordtext:"Registro(s)",
			loadtext: "Recuperando Información del Servidor",		
			url:'modulos/tesoreria/integracion_contable/rp/sql_grid_numero_comprobante.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N Comprobante Int','','Fecha_comprobante'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha_comp',index:'fecha_comp', width:120,sortable:false,resizable:false,hidden:true}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#tesoreria_integracion_reverso_numero_id').val(ret.id);
				$('#tesoreria_integracion_reverso_numero_c_hasta').val(ret.numero_comprobante);
				$('#tesoreria_integracion_reverso_numero_c_hasta2').val(ret.numero_comprobante2);
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
$('#tesoreria_integracion_reverso_numero_c_desde').numeric({allow:',.-'});
$('#tesoreria_integracion_reverso_numero_c_hasta').numeric({allow:',.-'});

</script>
<div id="botonera">
<img id="tesoreria_integracion_reverso_rp_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="tesoreria_integracion_reversar_pr_btn_reversar" src="imagenes/iconos/reversar.png"  style="width:100px height:100px"/>

	</div>
<form method="post" id="form_tesoreria_rp_integracion_contable_reverso" name="form_tesoreria_rp_integracion_contable_reverso">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reverso Integraci&oacute;n </th>
		</tr>
		<tr>
			<th colspan="4" align="center"><p align="center">Numero Comprobante:</p>
		</tr>
		<tr>
			<th>A&ntilde;o:</th>
			<td colspan="3">
				<select  name="tesoreria_reverso_integracion_ano" id="tesoreria_reverso_integracion_ano" onchange="cambio_cuadro();">
					<?
					$anio_inicio=date("Y");
					$anio_fin=$anio_inicio+1;
					$anio_ant=$anio_inicio-1;
					$anio_inicio=$anio_ant;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
		  </select>			</td>
			</tr>
	<tr>
	<tr>
			<th width="118">
				Desde:			 </th>
			  <td width="124"> 
			 <ul class="input_con_emergente">
			 <li>
			  	    <input  type="text" id="tesoreria_integracion_reverso_numero_c_desde"  name="tesoreria_integracion_reverso_numero_c_desde" size="20" maxlength="20"
					message="Introduzca un número de comprobante ejm:50042"
				jval="{valid:/^[,.-_123456789]{1,7}$/,message:'Número Comprobante Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,7}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
					>
             <input type="hidden" id="tesoreria_integracion_reverso_numero_c_desde2" name="tesoreria_integracion_reverso_numero_c_desde2" />       
			 <input type="hidden" id="tesoreria_integracion_reverso_numero_id" name="tesoreria_integracion_reverso_numero_id" />
			 </li>
			<li id="tesoreria_integracion_reverso_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
			</ul>		
 	  </td>
		
			<th width="104">	  
			    Hasta:	  </th> 
			 <td width="120">
			 <ul class="input_con_emergente">
			 <li>
			    <input type="text" id="tesoreria_integracion_reverso_numero_c_hasta" name="tesoreria_integracion_reverso_numero_c_hasta" size="20" maxlength="20"
				message="Introduzca un número de comprobante ejm:50042">
                <input type="hidden" id="tesoreria_integracion_reverso_numero_c_hasta2" name="tesoreria_integracion_reverso_numero_c_hasta2" />
				<input type="hidden" id="tesoreria_integracion_reverso_numero_id" name="tesoreria_integracion_reverso_numero_id" />
			 </li>
			<li id="tesoreria_integracion_reverso_btn_consultar_cuenta_hasta" class="btn_consulta_emergente"></li>
			</ul>	
      </td>
			 
	</tr>
		
		
		<tr  style="display:none">	
				<th colspan="4" align="center"><p align="center">Fecha:</p></th>
				
		</tr>
		<tr  style="display:none">
			<th>
				  Desde:
			</th>
		<td>
		            <input readonly="true" type="text" name="tesoreria_integracion_reverso_rp_fecha_desde" id="tesoreria_integracion_reverso_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="tesoreria_integracion_reverso_rp_fecha_desde_oculto" id="tesoreria_integracion_reverso_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
				  <button type="reset" id="tesoreria_cheques_usuarios_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "tesoreria_integracion_reverso_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "tesoreria_cheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("tesoreria_integracion_reverso_rp_fecha_desde").value.MMDDAAAA() );
										f2=new Date( getObj("tesoreria_integracion_reverso_rp_fecha_hasta").value.MMDDAAAA() );
										if (f1 > f2) {
											//setBarraEstado(mensaje[fecha_impuesto],true,true);
											alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
											getObj("tesoreria_integracion_reverso_rp_fecha_desde").value =getObj("tesoreria_integracion_reverso_rp_fecha_desde_oculto").value;
											}
									}
							});
					</script>
		</td>
		<th>
					Hasta:
		</th>
		<td>	
					<input readonly="true" type="text" name="tesoreria_integracion_reverso_rp_fecha_hasta" id="tesoreria_integracion_reverso_rp_fecha_hasta" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
						jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
					  <input type="hidden"  name="tesoreria_integracion_reverso_rp_fecha_hasta_oculto" id="tesoreria_integracion_reverso_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
					  <button type="reset" id="tesoreria_integracion_reverso_rp_fecha_boton_h">...</button>
					  <script type="text/javascript">
								Calendar.setup({
									inputField     :    "tesoreria_integracion_reverso_rp_fecha_hasta",      // id of the input field
									ifFormat       :    "%d/%m/%Y",       // format of the input field
									showsTime      :    false,            // will display a time selector
									button         :    "tesoreria_integracion_reverso_rp_fecha_boton_h",   // trigger for the calendar (button ID)
									singleClick    :    true,          // double-click mode
									onUpdate :function(date){
											f1=new Date( getObj("tesoreria_integracion_reverso_rp_fecha_desde").value.MMDDAAAA() );
											f2=new Date( getObj("tesoreria_integracion_reverso_rp_fecha_boton_h").value.MMDDAAAA() );
											if (f1 > f2) {
												//setBarraEstado(mensaje[fecha_impuesto],true,true);
												alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
												getObj("tesoreria_integracion_reverso_rp_fecha_hasta").value =getObj("tesoreria_integracion_reverso_rp_fecha_hasta_oculto").value;
												}
										}
								});
						</script>
		  </td>	
	  </tr>	
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>