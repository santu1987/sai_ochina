<?php
session_start();
if(date("m")=="12")
{
	$mes="01";
	$ayo=date("Y")+1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,date("d"),$ayo));
?>

<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


var dialog;

$("#form_cheques_banco_rp_btn_imprimir").click(function() {
if(($('#form_rp_cheques_banco').jVal()))
	{

		url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.banco_cheques.php¿id_banco="+getObj('tesoreria_cheques_banco_rp_id_banco').value+"@desde="+getObj('tesoreria_cheques_banco_rp_fecha_desde').value+"@hasta="+getObj('tesoreria_cheques_banco_rp_fecha_hasta').value+"@cuenta="+getObj('tesoreria_cheques_banco_rp_n_cuenta').value;
		openTab("Cheques/Bancos",url);
	 }
});
$("#form_cheques_banco_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_cheques_banco');
	getObj("tesoreria_cheques_banco_rp_ayo").value = "<?=  date("Y"); ?>";
	getObj("tesoreria_cheques_banco_rp_fecha_desde").value = "<?=  date("d/m/Y"); ?>";
	getObj("tesoreria_cheques_banco_rp_fecha_hasta").value = "<?=  $fecha; ?>";
	getObj("tesoreria_cheques_banco_rp_fecha_desde_oculto").value="<?=  date("d/m/Y"); ?>";
	getObj("tesoreria_cheques_banco_rp_fecha_hasta_oculto").value="<?=  $fecha; ?>";
});
//------------------------------------------------------------------------------
$("#tesoreria_cheques_banco_rp_btn_consultar_banco").click(function() {

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
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
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_banco.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Nombre','Sucursal','Direccion','Estatus','Cuenta'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'sucursal',index:'sucursal', width:200,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:200,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:200,sortable:false,resizable:false,hidden:true},
									{name:'cuenta',index:'cuenta', width:200,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_banco_rp_id_banco').value = ret.id;
									getObj('tesoreria_cheques_banco_rp_nombre').value = ret.nombre;
									getObj('tesoreria_cheques_banco_rp_n_cuenta').value = ret.cuenta;									
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});//
$("#tesoreria_cheques_banco_rp_btn_consultar_cuentas").click(function() {
if(getObj('tesoreria_cheques_banco_rp_id_banco').value!="")
{
	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/rp/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_cheques_banco_rp_id_banco').value+'&fecha='+getObj('tesoreria_cheques_banco_rp_ayo').value,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Nº Cuenta','Estatus'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_banco_rp_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
									//$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
								
							});
						}
}
});

//-------------------------------------------------------------------
/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="form_cheques_banco_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_cheques_banco_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_cheques_banco" id="form_rp_cheques_banco">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="2"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n Cheques Emitidos Bancos </th>
    </tr>
    <!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_chequeras_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
	<tr>
		<th>
			Año
		de Creaci&oacute;n de cuenta: </th>
		<td>
			<select  name="tesoreria_cheques_banco_rp_ayo" id="tesoreria_cheques_banco_rp_ayo">
					<?
					$anio_inicio=date("Y");
					$anio_fin=date("Y")+1;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
		  </select>
		</td>
	</tr>		
    <tr>
      <th>Banco:</th>
				<td>
			  <ul class="input_con_emergente">
				<li>
						<input name="tesoreria_cheques_banco_rp_nombre" type="text" id="tesoreria_cheques_banco_rp_nombre"   value="" size="50" maxlength="80" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' " 
								jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}" readonly						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
						<input type="hidden"  id="tesoreria_cheques_banco_rp_id_banco" name="tesoreria_cheques_banco_rp_id_banco"/>
				</li>
				<li id="tesoreria_cheques_banco_rp_btn_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
	<tr>
		<th>N&ordm; Cuenta: </th>	
	     <td>
		 
		 <ul class="input_con_emergente">		
		<li>
		  <input name="tesoreria_cheques_banco_rp_n_cuenta" type="text" id="tesoreria_cheques_banco_rp_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. "  readonly />  
		</li>
		<li id="tesoreria_cheques_banco_rp_btn_consultar_cuentas" class="btn_consulta_emergente"></li>	</ul></td>
		
	<tr>
	  <th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="tesoreria_cheques_banco_rp_fecha_desde" id="tesoreria_cheques_banco_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_cheques_banco_rp_fecha_desde_oculto" id="tesoreria_cheques_banco_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="tesoreria_cheques_banco_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_cheques_banco_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_cheques_banco_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_cheques_banco_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_cheques_banco_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_cheques_banco_rp_fecha_desde").value =getObj("tesoreria_cheques_banco_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
		  </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="tesoreria_cheques_banco_rp_fecha_hasta" id="tesoreria_cheques_banco_rp_fecha_hasta" size="7" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_cheques_banco_rp_fecha_hasta_oculto" id="tesoreria_cheques_banco_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="tesoreria_cheques_banco_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_cheques_banco_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_cheques_banco_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_cheques_banco_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_cheques_banco_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_cheques_banco_rp_fecha_hasta").value =getObj("tesoreria_cheques_usuarios_rp_fecha_hasta_oculto").value;
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