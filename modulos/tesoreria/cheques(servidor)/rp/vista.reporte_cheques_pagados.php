<?php
session_start();
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

var dialog;
$("#from_cheques_pagados_rp_btn_imprimir").click(function() {
		if((getObj('cheques_pagados_banco_nombre').value!="")&&(getObj('cheques_pagados_firmante').value!=""))
				{

				url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_pagados.php¿desde="+getObj('cheques_pagados_fecha_desde').value+"@hasta="+getObj('cheques_pagados_fecha_hasta').value+"@banco="+getObj('cheques_pagados_banco_nombre').value+"@agencia="+getObj('cheques_pagados_agencia').value+"@atencion="+getObj('cheques_pagados_atencion').value+"@gerente="+getObj('cheques_pagados_gerente').value+"@firmante="+getObj('cheques_pagados_firmante').value+"@id_banco="+getObj('cheques_pagados_id_banco').value+"@cargo="+getObj('cheques_pagados_cargo').value;
				openTab("Cheques Pagados",url);
				}
				else{
					alert('El nombre del Banco y el nombre del Firmante son obligatorios');
				}
});
$("#from_cheques_pagados_rp_btn_cancelar").click(function() {
	getObj('cheques_pagados_id_banco').value = "";
	getObj('cheques_pagados_banco_nombre').value = "";
	getObj('cheques_pagados_agencia').value="";
	getObj("cheques_pagados_fecha_desde").value = "<?php echo date("d/m/Y"); ?>";
	getObj("cheques_pagados_fecha_hasta").value = "<?php echo date("d/m/Y"); ?>";	
});
$("#cheques_pagados_consultar_banco").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_cheques_pagados_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_cheques_pagados_banco.php?nd='+nd,
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
									getObj('cheques_pagados_id_banco').value = ret.id;
									getObj('cheques_pagados_banco_nombre').value = ret.nombre;
									getObj('cheques_pagados_agencia').value=ret.sucursal;
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
	$('#tesoreria_precheques_proveedores_rp_codigo').change(consulta_automatica_proveedor);
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="from_cheques_pagados_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="from_cheques_pagados_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="from_rp_cheques_pagados" id="from_rp_cheques_pagados">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="2"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Cheques Pagados</th>
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
						<input name="cheques_pagados_banco_nombre" type="text" id="cheques_pagados_banco_nombre"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' " readonly  
							/>
						<input type="hidden"  id="cheques_pagados_id_banco" name="cheques_pagados_id_banco"/>
				</li>
				<li id="cheques_pagados_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
	<tr>
	  <th>Agencia :</th>
	  <td><input name="cheques_pagados_agencia" type="text" id="cheques_pagados_agencia" size="50" /></td>
    <tr>
		<th>Atenci&oacute;n: </th>	
	     <td><label>
	       <input name="cheques_pagados_atencion" type="text" id="cheques_pagados_atencion" size="50" />
	       </label>
	    </td>
	<tr>
	<th>Gerente:</th>
		  <td>
			<label>
			  <input name="cheques_pagados_gerente" type="text" id="cheques_pagados_gerente" size="50" />
      </label></td>
	</tr>
	<tr>
	  <th>Firmante:</th>
	      <td><label>
	        <input name="cheques_pagados_firmante" type="text" id="cheques_pagados_firmante" size="50" />
	      </label></td>
	</tr>
	<tr>
	  <th>Cargo:</th>
	      <td><label>
	        <input name="cheques_pagados_cargo" type="text" id="cheques_pagados_cargo" size="50" />
	      </label></td>
	</tr>
	<tr>
	  <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cheques_pagados_fecha_desde" id="cheques_pagados_fecha_desde" size="7" value="<?php echo date("d/m/Y");  ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cheques_pagados_fecha_desde_oculto" id="cheques_pagados_fecha_desde_oculto" value="<?php echo date("d/m/Y");  ?>"/>
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cheques_pagados_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cheques_pagados_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("cheques_pagados_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("cheques_pagados_fecha_desde").value =getObj("cheques_pagados_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cheques_pagados_fecha_hasta" id="cheques_pagados_fecha_hasta" size="7" value="<?php echo date("d/m/Y");  ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cheques_pagados_fecha_hasta_oculto" id="cheques_pagados_fecha_hasta_oculto" value="<?php echo date("d/m/Y");  ?>" />
	      <button type="reset" id="tesoreria_precheques_usuarios_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cheques_pagados_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_precheques_usuarios_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cheques_pagados_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("cheques_pagados_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("cheques_pagados_fecha_hasta").value =getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value;
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