<?php
session_start();
?>

<script>
var dialog;

$("#tesoreria_banco_cuentas_rp_btn_imprimir").click(function() {
url="pdf.php?p=modulos/tesoreria/banco_cuentas/rp/vista.lst.banco_cuentas.php¿id_banco="+getObj('tesoreria_banco_cuentas_rp_id_banco').value+"@cuenta="+getObj('tesoreria_banco_cuentas_rp_n_cuenta').value+"@nombre="+getObj('tesoreria_banco_cuentas_rp_nombre').value+"@fecha="+getObj('tesoreria_banco_cuentas_rp_ayo').value; 
		/*url="pdf.php?p=modulos/tesoreria/banco_cuentas/rp/vista.lst.banco_cuentas.php¿id_banco="+getObj('tesoreria_banco_cuentas_rp_id_banco').value+"@cuenta="+getObj('tesoreria_banco_cuentas_rp_n_cuenta').value+"@nombre="+getObj('tesoreria_banco_cuentas_rp_nombre').value+"@fecha="+getObj('tesoreria_banco_cuentas_rp_ayo').value;*/ 
		openTab("Banco",url);
		//alert(url);
});
$("#tesoreria_banco_cuentas_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_banco_cuentas');
});
//-----------------------------------------------------------------------------------------------------
$("#tesoreria_banco_cuentas_rp_btn_consultar_banco").click(function() {

/*		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/banco_cuentas/rp/grid_banco_cuenta.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,50);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/banco_cuentas/rp/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
					var fecha=jQuery("#tesoreria_banco_cuentas_rp_ayo").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/rp/sql_grid_banco.php?busq_banco="+busq_banco+"&fecha="+fecha,page:1}).trigger("reloadGrid"); 
					url="modulos/tesoreria/banco_cuentas/rp/sql_grid_banco.php?busq_banco="+busq_banco+"&fecha="+fecha;
					getObj("tesoreria_banco_cuentas_rp_n_cuenta").value=url;
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_banco_cuenta_banco-busqueda_bancos").keypress(function(key)
				{
					if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_banco_cuentas_dosearch();
					});
				function consulta_banco_cuentas_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_banco_cuentas_gridReload,500)
										}
						function consulta_banco_cuentas_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
							var fecha=jQuery("#tesoreria_banco_cuentas_rp_ayo").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/rp/sql_grid_banco.php?busq_banco="+busq_banco+"&fecha="+fecha,page:1}).trigger("reloadGrid"); 
						}
			}
		});						
						function crear_grid()
						{		
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/banco_cuentas/rp/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo Área','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','saldo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:160,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:130,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'saldo_actual',index:'saldo_actual', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_banco_cuentas_rp_id_banco').value=ret.id;
									getObj('tesoreria_banco_cuentas_rp_nombre').value=ret.nombre;
									getObj('tesoreria_banco_cuentas_rp_n_cuenta').value=ret.cuentas;
								
									
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_banco_cuentas_rp_n_cuenta_btn_consultar_cuentas_chequeras").click(function() {
if(getObj('tesoreria_banco_cuentas_rp_id_banco').value!="")
{
	var nd=new Date().getTime();
	urls='modulos/tesoreria/banco_cuentas/rp/sql_grid_cuenta_cheque.php?nd='+nd+'&banco='+getObj('tesoreria_banco_cuentas_rp_id_banco').value;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/banco_cuentas/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Nº Cuenta','Estatus','CuentaNuevo','saldo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false},
									{name:'cuentan',index:'cuentan', width:50,sortable:false,resizable:false,hidden:true},
									{name:'saldo',index:'saldo', width:50,sortable:false,resizable:false,hidden:true}
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									dialog.hideAndUnload();
									getObj('tesoreria_banco_cuentas_rp_n_cuenta').value=ret.ncuenta;
				 			
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
//-------------------------------------------------------------------------------
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
	<img id="tesoreria_banco_cuentas_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="tesoreria_banco_cuentas_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_banco_cuentas" id="form_rp_banco_cuentas">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n Bancos Cuentas</th>
	  </tr>
		<!--<tr>
			<th>Selección</th>
			<td>
				<input id="tesoreria_banco_cuentas_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
		<tr>
		<th>A&ntilde;o
		</th>
		<td>
			<select  name="tesoreria_banco_cuentas_rp_ayo" id="tesoreria_banco_cuentas_rp_ayo">
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
		<tr id="tesoreria_banco_cuentas_rp_tr_banco" >
			<th>Banco:</th>
				<td>
			  <ul class="input_con_emergente">
				<li>
						<input name="tesoreria_banco_cuentas_rp_nombre" type="text" id="tesoreria_banco_cuentas_rp_nombre"   value="" size="50" maxlength="80" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' " 
								jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ ,-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}" readonly						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
						<input type="hidden"  id="tesoreria_banco_cuentas_rp_id_banco" name="tesoreria_banco_cuentas_rp_id_banco"/>
				</li>
				<li id="tesoreria_banco_cuentas_rp_btn_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
		</tr>
		<tr>
			<th>N&uacute;mero de Cuenta:</th>
			<td>	
				<ul class="input_con_emergente">
				<li>
						<input name="tesoreria_banco_cuentas_rp_n_cuenta" type="text" id="tesoreria_banco_cuentas_rp_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. " readonly=""/>
				</li>
				<li id="tesoreria_banco_cuentas_rp_n_cuenta_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
				</ul>		</td>
		</tr>
		
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>