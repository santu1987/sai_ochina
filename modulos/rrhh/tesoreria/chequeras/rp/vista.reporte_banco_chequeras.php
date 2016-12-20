<?php
session_start();
?>

<script>
var dialog;

$("#tesoreria_banco_chequeras_rp_btn_imprimir").click(function() {
if(($('#form_rp_banco_chequeras').jVal()))
	{
		url="pdf.php?p=modulos/tesoreria/chequeras/rp/vista.lst.banco_chequeras.php¿id_banco="+getObj('tesoreria_banco_chequeras_rp_id_banco').value+"@nombre="+"@n_cuenta="+getObj('tesoreria_banco_chequeras_rp_n_cuenta').value+"@fecha="+getObj('tesoreria_banco_chequeras_rp_ayo').value; 
		openTab("Banco/Chequeras",url);
	 }
});
$("#tesoreria_banco_chequeras_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_banco_chequeras');
});




//------------------------------------------------------------------------------
$("#tesoreria_banco_chequeras_rp_btn_consultar_banco").click(function() {
/*	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/banco_cuentas/rp/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/

var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/chequeras/rp/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/rp/sql_grid_banco.php?busq_banco="+busq_banco+'&fecha='+getObj('tesoreria_banco_chequeras_rp_ayo').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_banco-busqueda_bancos").keypress(function(key)
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
							var busq_banco= jQuery("#tesoreria_banco-busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/chequeras/rp/sql_grid_banco.php?busq_banco="+busq_banco+'&fecha='+getObj('tesoreria_banco_chequeras_rp_ayo').value,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/chequeras/rp/sql_grid_banco.php?busq_banco="+busq_banco+'&fecha='+getObj('tesoreria_banco_chequeras_rp_ayo').value;
							setBarraEstado(url);
						}

			}
		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:450,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/chequeras/rp/sql_grid_banco.php?nd='+nd+'&fecha='+getObj('tesoreria_banco_chequeras_rp_ayo').value,
								datatype: "json",
     							colNames:['Id','Nombre','Sucursal','Direccion','Estatus','Cuentas'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'sucursal',index:'sucursal', width:200,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:200,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:200,sortable:false,resizable:false,hidden:true},
                                    {name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true}
							],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_banco_chequeras_rp_id_banco').value = ret.id;
									getObj('tesoreria_banco_chequeras_rp_nombre').value = ret.nombre;
									getObj('tesoreria_banco_chequeras_rp_n_cuenta').value=ret.cuentas;
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
});						
//-------------------------------------------------------------------
$("#tesoreria_banco_chequeras_rp_btn_consultar_cuentas").click(function() {

/*if(getObj('tesoreria_banco_chequeras_rp_id_banco').value!="")
{
	*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/chequeras/rp/grid_banco_cuenta.php", { },
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
								url:'modulos/tesoreria/chequeras/rp/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_banco_chequeras_rp_id_banco').value+'&fecha='+getObj('tesoreria_banco_chequeras_rp_ayo').value,
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
									getObj('tesoreria_banco_chequeras_rp_n_cuenta').value=ret.ncuenta;
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
//}
});
//-------------------------------------------------------------------
//

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
	<img id="tesoreria_banco_chequeras_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="tesoreria_banco_chequeras_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_banco_chequeras" id="form_rp_banco_chequeras">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Relaci&oacute;n Bancos Chequeras</th>
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
			A&ntilde;o
		</th>
		<td>
			<select  name="tesoreria_banco_chequeras_rp_ayo" id="tesoreria_banco_chequeras_rp_ayo">
					<?
					$anio_inicio=2010;
					$anio_fin=2011;
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
		<tr id="tesoreria_banco_chequeras_rp_tr_banco" >
			<th>Banco:</th>
				<td>
			  <ul class="input_con_emergente">
				<li>
						<input name="tesoreria_banco_chequeras_rp_nombre" type="text" id="tesoreria_banco_chequeras_rp_nombre"   value="" size="50" maxlength="80" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' " 
						 readonly jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
						<input type="hidden"  id="tesoreria_banco_chequeras_rp_id_banco" name="tesoreria_banco_chequeras_rp_id_banco"/>
				</li>
				<li id="tesoreria_banco_chequeras_rp_btn_consultar_banco" class="btn_consulta_emergente"></li>
			</ul>			</td>
		</tr>
		<tr>
		<th>N&ordm; Cuenta: </th>	
	     <td>
		 
		 <ul class="input_con_emergente">		
		<li>
		  <input name="tesoreria_banco_chequeras_rp_n_cuenta" type="text" id="tesoreria_banco_chequeras_rp_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. "  readonly />  
		</li>
		<li id="tesoreria_banco_chequeras_rp_btn_consultar_cuentas" class="btn_consulta_emergente"></li>	</ul></td>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>