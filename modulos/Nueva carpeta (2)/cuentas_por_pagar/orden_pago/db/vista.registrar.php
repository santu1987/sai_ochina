<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
$("#cuentas_x_pagar_db_orden_btn_guardar").click(function() {
	if($('#form_cuentas_x_pagar_db_orden').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/cuentas_por_pagar/orden_pago/db/sql.registrar.php",
			data:dataForm('form_cuentas_x_pagar_db_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_cuentas_x_pagar_db_orden');
								
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_cuentas_x_pagar_db_orden');
						
								}
					else
				{
					alert(html);
//					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			
			}
		});
	}
});

$("#cuentas_x_pagar_db_btn_consultar_proveedor").click(function() {

		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/orden_pago/db/grid_pago.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_x_pagar_db_proveedor_id').value = ret.id_proveedor;
									getObj('cuentas_x_pagar_db_proveedor_codigo').value = ret.codigo;
									getObj('cuentas_x_pagar_db_proveedor_nombre').value = ret.nombre;
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#cuentas_x_pagar_db_orden_monto').numeric({allow:',.'});
$('#cuentas_x_pagar_db_orden_base_imponible').numeric({allow:',.'});
$('#cuentas_x_pagar_db_orden_porcentaje_iva').numeric({allow:',.'});
$('#cuentas_x_pagar_db_orden_porcentaje_retencion_iva').numeric({allow:',.'});
$('#cuentas_x_pagar_db_orden_porcentaje_retencion_islr').numeric({allow:',.'});
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

 <div id="botonera">
	<!--<img id="tesoreria_banco_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="tesoreria_banco_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_banco_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="tesoreria_banco_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	--><img id="cuentas_x_pagar_db_orden_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_cuentas_x_pagar_db_orden" name="form_cuentas_x_pagar_db_orden">
<input type="hidden"  id="form_cuentas_x_pagar_db_vista_orden" name="form_cuentas_x_pagar_db_vista_orden"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Orden pago </th>
	</tr>
	<tr>
		<th>Proveedor</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="cuentas_x_pagar_db_proveedor_codigo" type="text" id="cuentas_x_pagar_db_proveedor_codigo"  maxlength="4" readonly
				onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
				message="Introduzca un Codigo para el proveedor."  size="5"
				/>
	
				<input type="text" name="cuentas_x_pagar_db_proveedor_nombre" id="cuentas_x_pagar_db_proveedor_nombre" size="60"
				message="Introduzca el nombre del Proveedor." disabled="disabled"/>
				<input type="hidden" name="cuentas_x_pagar_db_proveedor_id" id="cuentas_x_pagar_db_proveedor_id" readonly />
				</li> 
					<li id="cuentas_x_pagar_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>				</td>		
	</tr>
	<tr>
		<th>Monto </th>	
	    <td>	
		<input name="cuentas_x_pagar_db_orden_monto" type="text" id="cuentas_x_pagar_db_orden_monto" size="40" maxlength="60" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' " 
					/></td>
	</tr>
   <tr>
		<th>Base Imponible :		</th>	
	    <td>	
		<input name="cuentas_x_pagar_db_orden_base_imponible" type="text" id="cuentas_x_pagar_db_orden_base_imponible" size="40" maxlength="60" message="Introduzca el nombre de la sucursal bancaria. " 
					/></td>
   </tr>
   <tr>
		<th>Porcentaje Iva :</th>
	  <td><input name="cuentas_x_pagar_db_orden_porcentaje_iva" type="text" id="cuentas_x_pagar_db_orden_porcentaje_iva" size="40" maxlength="12" message="Introduzca el Porcentaje ." 
			/></td>
	</tr>
	<tr>
		<th>Porcentaje Retencion IVA :</th>
	    <td><input name="cuentas_x_pagar_db_orden_porcentaje_retencion_iva" type="text" id="cuentas_x_pagar_db_orden_porcentaje_retencion_iva" size="40"
				message="Introduzca el porcentaje de la retencion de IVA." 
				/></td>
	</tr>	
	<tr>
		<th>Porcentaje retencion ISLR </th>
	    <td><input name="cuentas_x_pagar_db_orden_porcentaje_retencion_islr" type="text" id="cuentas_x_pagar_db_orden_porcentaje_retencion_islr" size="40" maxlength="60" 
				message="Introduzca el porcentaje de la retencion del islr."  /></td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
<input  name="cuentas_x_pagar_db_orden_id" type="hidden" id="" />
</form>