<?php
session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

var dialog;
$("#from_cheques_conformados_rp_btn_imprimir").click(function() {
		if((getObj('cheques_conformados_banco_nombre').value!=""))
				{
				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_conformados.php¿desde="+getObj('cheques_conformados_fecha_desde').value+"@hasta="+getObj('cheques_conformados_fecha_hasta').value+"@banco="+getObj('cheques_conformados_banco_nombre').value+"@id_banco="+getObj('cheques_conformados_id_banco').value;
				openTab("Cheques Conformados",url);
				}
				else{
					alert('El nombre del Banco es obligatorios');
				}
});
$("#from_cheques_conformados_rp_btn_cancelar").click(function() {
	getObj('cheques_conformados_id_banco').value = "";
	getObj('cheques_conformados_banco_nombre').value = "";
	//getObj('cheques_conformados_agencia').value="";
	getObj("cheques_conformados_fecha_desde").value = "<?php echo date("d/m/Y"); ?>";
	getObj("cheques_conformados_fecha_hasta").value = "<?php echo date("d/m/Y"); ?>";	
});
$("#cheques_conformados_consultar_banco").click(function() {

	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_cheques_conformados_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
						var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/rp/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_cheques_conformados_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_banco_cuenta_banco-busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				
						function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
										}
						function consulta_doc_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/rp/sql_grid_cheques_conformados_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/rp/sql_grid_cheques_conformados_banco.php?busq_banco="+busq_banco;
					//alert(url);		
						}

			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_cheques_conformados_banco.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Nombre','Sucursal','Comentario'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'sucursal',index:'sucursal', width:200,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:200,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cheques_conformados_id_banco').value = ret.id;
									getObj('cheques_conformados_banco_nombre').value = ret.nombre;
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
								sortname: 'id_banco',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
//	$('#tesoreria_precheques_proveedores_rp_codigo').change(consulta_automatica_proveedor);
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="from_cheques_conformados_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="from_cheques_conformados_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="from_rp_cheques_conformados" id="from_rp_cheques_conformados">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="2"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Cheques conformados</th>
    </tr>
    <!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_chequeras_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
	<th>Banco:</th>
				<td>
			  <ul class="input_con_emergente">
				<li>
						<input name="cheques_conformados_banco_nombre" type="text" id="cheques_conformados_banco_nombre"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' " readonly  
							/>
						<input type="hidden"  id="cheques_conformados_id_banco" name="cheques_conformados_id_banco"/>
				</li>
				<li id="cheques_conformados_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
	<tr>
	  <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cheques_conformados_fecha_desde" id="cheques_conformados_fecha_desde" size="7" value="<?php echo date("d/m/Y");  ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cheques_conformados_fecha_desde_oculto" id="cheques_conformados_fecha_desde_oculto" value="<?php echo date("d/m/Y");  ?>"/>
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cheques_conformados_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cheques_conformados_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("cheques_conformados_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("cheques_conformados_fecha_desde").value =getObj("cheques_conformados_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cheques_conformados_fecha_hasta" id="cheques_conformados_fecha_hasta" size="7" value="<?php echo date("d/m/Y");  ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cheques_conformados_fecha_hasta_oculto" id="cheques_conformados_fecha_hasta_oculto" value="<?php echo date("d/m/Y");  ?>" />
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cheques_conformados_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cheques_conformados_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("cheques_conformados_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("cheques_conformados_fecha_hasta").value =getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    </tr>	  
    <tr>
      <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
</form>