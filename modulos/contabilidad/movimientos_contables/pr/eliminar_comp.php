<script language="javascript">
$("#contabilidad_eliminar_pr_btn_consultar_tipo").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
					$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?nd='+nd,
								datatype: "json",
								colNames:['id','C&oacute;digo','Denominaci&oacute;n','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'comen',index:'comen', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_eliminar_pr_tipo').val(ret.codigo);
									$('#contabilidad_eliminar_pr_tipo_id').val(ret.id);
									//$('#cuentas_por_pagar_integracion_tipo_nombre').val(ret.nombre);
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
function limpiar_recuve()
{
	clearForm('form_el_mov');
	getObj('contabilidad_eliminar_pr_btn_guardar').style.display='none';

}
$("#contabilidad_eliminar_pr_btn_consultar_n_comprobante").click(function() {
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
					//getObj('contabilidad_eliminar_pr_fecha_boton_d').disabled="true";
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
			url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_numero_comprobante_el.php?nd='+nd,
			datatype: "json",
			colNames:['Id','N Comprobante','ncomp2','Fecha','desc'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'numero_comprobante',index:'numero_comprobante', width:120,sortable:false,resizable:false},
				{name:'numero_comprobante2',index:'numero_comprobante2', width:120,sortable:false,resizable:false,hidden:true},
				{name:'fecha',index:'fecha', width:200,sortable:false,resizable:false},
				{name:'desc',index:'desc', width:200,sortable:false,resizable:false}
	
	
			],
			pager: $('#pager_grid_'+nd),
			rowNum:20,
			rowList:[20,50,100],
			imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
			onSelectRow: function(id){
				var ret = jQuery("#list_grid_"+nd).getRowData(id);				
				$('#ids_comprobante').val(ret.id);
				$('#contabilidad_eliminar_pr_numero_comprobante').val(ret.numero_comprobante);
				$('#contabilidad_eliminar_pr_numero_comprobante2').val(ret.numero_comprobante2);
				$('#contabilidad_eliminar_pr_fecha ').val(ret.fecha);
				$('#contabilidad_eliminar_pr_desc').val(ret.desc);
				getObj('contabilidad_eliminar_pr_btn_guardar').style.display='';				
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

$("#contabilidad_eliminar_pr_btn_guardar").click(function() {
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />ESTA ABSOLUTAMENTE SEGURO QUE DESEA ELIMINAR EL REGISTRO SELECCIONADO?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
	
	
		setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url: "modulos/contabilidad/movimientos_contables/pr/sql.eliminar3.php",
						data:dataForm('form_el_mov'),
						type:'POST',
						cache: false,
						success: function(html)
						{//alert(html);
						recordset=html;
						recordset = recordset.split("*");
						alert(html);
						if (recordset[0]=="Eliminado")
						{
							
							
							setBarraEstado(mensaje[eliminacion_exitosa],true,true);
							//creando el valor del numero de comprobante
							clearForm('form_el_mov');
							getObj('contabilidad_eliminar_opcion').value='0';
							getObj('contabilidad_eliminar_pr_fecha').value="<?=  date("d/m/Y"); ?>";	

						}
							else if (html=="ExisteRelacion")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />IMPOSIBLE ELIMINAR</p></div>",true,true);
							}
							else
							{
								setBarraEstado(html,true,true);					
							}			
						}
					});
}});		
});

$("#contabilidad_eliminar_pr_btn_cancelar").click(function() {
 limpiar_recuve();
 });
function consulta_manual_mov_contables()
{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_mov_cod.php",
            data:dataForm('form_recuve_mov'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				recordset = recordset.split("*");
				//alert(html);
					if(html!="vacio")
					{
						$('#contabilidad_eliminar_pr_numero_comprobante').val(recordset[1]);
						jQuery("#list_comprobante_recuve").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_recuve.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('contabilidad_eliminar_pr_numero_comprobante2').value,page:1}).trigger("reloadGrid");
						//$('#numero_comprobante_cont_recuve').val(recordset[0]);
					}else
					if(html=="vacio")
					{
						getObj('contabilidad_eliminar_pr_numero_comprobante').value="";
						getObj('numero_comprobante_cont_recuve').value="";
					}
			 }
		});	 	 
}
/////////////////////////////////////////////////////
//-------------------------------------------------------------------------------------------------------------------------------------
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


$('#contabilidad_eliminar_pr_numero_comprobante').numeric({});

//$('#tesoreria_cheques_db_concepto').alpha({allow:' áéíóúÄÉÍÓÚáéíóúÁÉÍÓÚ0123456789,.-'});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
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
	
</script>

   <div id="botonera"><img id="contabilidad_eliminar_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"   /><img id="contabilidad_eliminar_pr_btn_guardar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_eliminar_pr_btn_imprimir_automatico"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />
	<img id="contabilidad_eliminar_pr_btn_imprimir"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" /></div>
<form method="post" id="form_el_mov" name="form_el_mov">
<input type="hidden"  id="tesoreria_vista_cheque" name="tesoreria_vista_cheque"/>
<input type="hidden" name="orden_pago_pr_cot_select" id="orden_pago_pr_cot_select"  />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Reversar Registros de Movimientos </th>
	</tr>
	
    <tr >
	<th>N&uacute;mero Comprobante:</th>
		<td>
		<ul class="input_con_emergente">
		  <li>
			<input type="text" id="contabilidad_eliminar_pr_numero_comprobante" name="contabilidad_eliminar_pr_numero_comprobante"    message="Introduzca n comprobante" size="12" maxlength="12" onblur="consulta_manual_mov_contables();"  
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'|', cArgs:['Codigo: '+$(this).val()]}" readonly/>
			<input type="hidden" id="contabilidad_eliminar_pr_numero_comprobante2" name="contabilidad_eliminar_pr_numero_comprobante2"    message="Introduzca n comprobante" size="12" maxlength="50" onblur="consulta_manual_mov_contables();"  />	
	
    <!-- onchange="consulta_automatica_comprobante()" onblur="consulta_automatica_comprobante()" -->
    		<input type="hidden" id="numero_comprobante_cont_recuve" name="numero_comprobante_cont_recuve" />
		    <input type="hidden" id="ids_comprobante" name="ids_comprobante" />
		  </li>
			<li id="contabilidad_eliminar_pr_btn_consultar_n_comprobante" class="btn_consulta_emergente"></li>
			</ul>	  </td>
	</tr>
    <tr>
    <th>Reversar a:</th>
    <td>
    <select name="contabilidad_eliminar_opcion" id="contabilidad_eliminar_opcion">
        <option id="0" value="0">-	SELECCIONE	-</option>
        <option id="1" value="1">INTEGRACION</option>
        <option id="2" value="2">ELIMINAR</option>
    </select>
    </td>
    </tr>
	<tr>
		<th>
			Fecha:
		</th>
		<td width="124">
		            <input alt="date" type="text" name="contabilidad_eliminar_pr_fecha" id="contabilidad_eliminar_pr_fecha" size="7" value="<? echo  date("d/m/Y") ;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" onchange="v_fecha();"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="contabilidad_eliminar_pr_fecha_oculto" id="contabilidad_eliminar_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="contabilidad_eliminar_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_eliminar_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_eliminar_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_eliminar_pr_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>
		</td>
		</tr>	      
 <tr>
 		
			<th>Descripci&oacute;n:</th>
			 <td>
			 <textarea  name="contabilidad_eliminar_pr_desc" cols="60" id="contabilidad_eliminar_pr_desc"  message="Introduzca una Descripci&oacute;n del asiento. Ejem:'Esta cuenta es ...' "   ><?php echo($descripcion_valor);?></textarea>			</td>
		</tr>
 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
  <input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>