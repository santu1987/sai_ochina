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

$("#cxp_integracion_btn_consultar_hasta").click(function() {
anos=getObj('cxp_integracion_numero_rp_ayo').value;

var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/cuentas_por_pagar/documentos/rp/grid_comprobante_cxp.php",
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
					var tipo= $("#consulta_comprobante_cxp_tipo").val();
					var ano=$("#cxp_integracion_numero_rp_ayo").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
				//	alert(url);
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
				$("#consulta_comprobante_cxp").keypress(
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
					var busq_cuenta= $("#consulta_comprobante_cxp").val();
					var tipo= $("#consulta_comprobante_cxp_tipo").val();
					var ano=$("#cxp_integracion_numero_rp_ayo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
                //	alert(url);				
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
			url:'modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?nd='+nd+"&anos="+anos,
			datatype: "json",
			colNames:['Id','N Comprobante','','Fecha'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha',index:'fecha', width:120,sortable:false,resizable:false}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);	
				//alert(ret.numero_comprobante);			
				$('#cxp_integracion_numero_id').val(ret.id);
				$('#cxp_integracion_numero_rp_hasta').val(ret.numero_comprobante);
				$('#cxp_integracion_numero_rp_hasta2').val(ret.numero_comprobante2);
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

$("#cxp_integracion_btn_consultar_cuenta").click(function() {
anos=getObj('cxp_integracion_numero_rp_ayo').value;
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/cuentas_por_pagar/documentos/rp/grid_comprobante_cxp.php",
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
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					var ano=$("#cxp_integracion_numero_rp_ayo").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
				//	alert(url);
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
				$("#consulta_comprobante_cxp").keypress(
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
					var busq_cuenta= $("#consulta_comprobante_cxp").val();
					var tipo=$("#consulta_comprobante_cxp_tipo").val();
					var ano=$("#cxp_integracion_numero_rp_ayo").val();
					//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?busq_cuenta="+busq_cuenta+"&ano="+ano+"&tipo="+tipo;
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
			url:'modulos/cuentas_por_pagar/documentos/rp/sql_grid_numero_comprobante.php?nd='+nd+"&anos="+anos,
			datatype: "json",
			colNames:['Id','N Comprobante Int','','fecha'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha',index:'fecha', width:120,sortable:false,resizable:false}

			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#contabilidad_numeracion_comprobante_db_id').val(ret.id);
				$('#cxp_integracion_numero_rp_desde').val(ret.numero_comprobante);
				$('#cxp_integracion_numero_rp_desde2').val(ret.numero_comprobante2);
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
$("#cxp_integracion_rp_imprimir").click(function()
 {
	if ($('#form_cxp_rp_integracion_contable').jVal()){	
				
		$desde_numero=getObj('cxp_integracion_numero_rp_desde2').value;
		$hasta_numero=getObj('cxp_integracion_numero_rp_hasta2').value;
		$desde_fecha=getObj('cxp_integracion_rp_fecha_desde').value;
		$hasta_fecha=getObj('cxp_integracion_rp_fecha_hasta').value;
		url="pdf.php?p=modulos/contabilidad/movimientos_contables/rp/vista.lst.comprobante_contabilidad.php¿desde_numero="+$desde_numero+"@hasta_numero="+$hasta_numero;
		openTab("IntegraCion Contable/cxp",url);
		alert(url);
		}
});
function cambio_cuadro()
{
	getObj('cxp_integracion_numero_rp_desde').value=""
	getObj('cxp_integracion_numero_rp_desde2').value="";
	getObj('cxp_integracion_numero_id2').value="";
	getObj('cxp_integracion_numero_id').value="";
	getObj('cxp_integracion_numero_rp_hasta').value="";
	getObj('cxp_integracion_numero_rp_hasta2').value="";
}
//conulta manual para el desde

function consulta_manual_mov_contables()
{
anos=getObj('cxp_integracion_numero_rp_ayo').value;
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_mov_cod.php",
            data:dataForm('form_cxp_rp_integracion_contable'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				//recordset = recordset.split("*");
				//alert(html);
					if(html!="vacio")
					{//alert(recordset);
						getObj('cxp_integracion_numero_rp_desde2').value=recordset;
						ncomp2=html;
					
					}else
					if(html=="vacio")
					{
						getObj('cxp_integracion_numero_rp_desde').value="";
						getObj('cxp_integracion_numero_rp_desde2').value="";
					}
			 }
		});	 	 
}
//consulta manual para el hasta
function consulta_manual_mov_contables2()
{
//alert("entro");
anos=getObj('cxp_integracion_numero_rp_ayo').value;
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_mov_cod2.php",
            data:dataForm('form_cxp_rp_integracion_contable'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
			//	alert(recordset);
					if(html!="vacio")
					{
						$('#cxp_integracion_numero_rp_hasta2').val(html);
					}else
					if(html=="vacio")
					{
						getObj('cxp_integracion_numero_rp_hasta').value="";
						getObj('cxp_integracion_numero_rp_hasta2').value="";
					}
			 }
		});	 	 
}
$("#cxp_integracion_rp_cancelar").click(function() {
setBarraEstado("");
clearForm('form_cxp_rp_integracion_contable');
getObj('cxp_integracion_rp_fecha_desde').value= "<?=$fecha;?>";
getObj('cxp_integracion_rp_fecha_hasta').value = "<?=date("d/m/Y");?>";

});
$('#cxp_integracion_numero_rp_desde').numeric({allow:',.-'});
$('#cxp_integracion_numero_rp_hasta').numeric({allow:',.-'});

</script>
<div id="botonera">
<img id="cxp_integracion_rp_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="cxp_integracion_rp_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
 	
	</div>
<form method="post" id="form_cxp_rp_integracion_contable" name="form_cxp_rp_integracion_contable">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Integraci&oacute;n CXP </th>
		</tr>
		<tr>
		<th colspan="4" align="center"><p align="center">N&uacute;mero Comprobante:</p>		</tr>
	<tr>
			<th>A&ntilde;o:</th>
			<td colspan="3">
				<select  name="cxp_integracion_numero_rp_ayo" id="cxp_integracion_numero_rp_ayo" onchange="cambio_cuadro();">
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
			<th width="118">
				Desde:			 </th>
			  <td width="124"> 
			 <ul class="input_con_emergente">
			 <li>
				<input type="text" name="cxp_integracion_numero_rp_desde" id="cxp_integracion_numero_rp_desde"  size='20' maxlength="20"
						message="Introduzca un número de comprobante ejm:50042"
				jval="{valid:/^[,.-_123456789]{1,7}$/,message:'Comprobante Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,7}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
				onblur="consulta_manual_mov_contables();"
				/>
				<input type="hidden" name="cxp_integracion_numero_rp_desde2"  id="cxp_integracion_numero_rp_desde2" />
			    <input type="hidden" id="cxp_integracion_numero_id2" name="cxp_integracion_numero_id2" />
			 </li>
			<li id="cxp_integracion_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
	  </ul> 	  </td>
		
			<th width="104">	  
			    Hasta:	  </th> 
			 <td width="120">
			 <ul class="input_con_emergente">
			 <li>
			    <input type="text" id="cxp_integracion_numero_rp_hasta" name="cxp_integracion_numero_rp_hasta" size="20" maxlength="20"
						message="Introduzca un número de comprobante ejm:50042"
						 onblur="consulta_manual_mov_contables2();"
				
				>
              <input type="hidden" name="cxp_integracion_numero_rp_hasta2" id="cxp_integracion_numero_rp_hasta2" />
			  <input type="hidden" id="cxp_integracion_numero_id" name="cxp_integracion_numero_id" />
			 </li>
			<li id="cxp_integracion_btn_consultar_hasta" class="btn_consulta_emergente"></li>
			</ul>	  </td>
	</tr>
		
		
		<tr>	
				<th colspan="4" align="center"  style="display:none"><p align="center">Fecha:</p></th>
		</tr>
		<tr  style="display:none">
			<th>
				  Desde:			</th>
		<td>
		            <input readonly="true" type="text" name="cxp_integracion_rp_fecha_desde" id="cxp_integracion_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="cxp_integracion_rp_fecha_desde_oculto" id="cxp_integracion_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
				  <button type="reset" id="cxp_cheques_usuarios_rp_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "cxp_integracion_rp_fecha_desde",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "cxp_cheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("cxp_integracion_rp_fecha_desde").value.MMDDAAAA() );
										f2=new Date( getObj("cxp_integracion_rp_fecha_hasta").value.MMDDAAAA() );
										if (f1 > f2) {
											//setBarraEstado(mensaje[fecha_impuesto],true,true);
											alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
											getObj("cxp_integracion_rp_fecha_desde").value =getObj("cxp_integracion_rp_fecha_desde_oculto").value;
											}
									}
							});
					</script>		</td>
		<th>
					Hasta:		</th>
		<td>	
					<input readonly="true" type="text" name="cxp_integracion_rp_fecha_hasta" id="cxp_integracion_rp_fecha_hasta" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
						jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
					  <input type="hidden"  name="cxp_integracion_rp_fecha_hasta_oculto" id="cxp_integracion_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
					  <button type="reset" id="cxp_integracion_rp_fecha_boton_h">...</button>
					  <script type="text/javascript">
								Calendar.setup({
									inputField     :    "cxp_integracion_rp_fecha_hasta",      // id of the input field
									ifFormat       :    "%d/%m/%Y",       // format of the input field
									showsTime      :    false,            // will display a time selector
									button         :    "cxp_integracion_rp_fecha_boton_h",   // trigger for the calendar (button ID)
									singleClick    :    true,          // double-click mode
									onUpdate :function(date){
											f1=new Date( getObj("cxp_integracion_rp_fecha_desde").value.MMDDAAAA() );
											f2=new Date( getObj("cxp_integracion_rp_fecha_boton_h").value.MMDDAAAA() );
											if (f1 > f2) {
												//setBarraEstado(mensaje[fecha_impuesto],true,true);
												alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
												getObj("cxp_integracion_rp_fecha_hasta").value =getObj("cxp_integracion_rp_fecha_hasta_oculto").value;
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