<?php
session_start();
?>

<script>
var dialog;
$("#orden_rp_btn_imprimir").click(function() {
	if(getObj('orden_rp_numero').value != ""){
		if(getObj("orden_especial").value ==0){
		url="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.orden_compra.php¿ano="+getObj("orden_rp_cmb_ano").value+"@numero_coti="+getObj('orden_rp_numero').value; 
		//urls="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.clasificacion.php¿ano="+getObj("orden_rp_cmb_ano").value+"@numero_coti="+getObj('orden_rp_numero').value; 
		//alert(url);
		}
		if(getObj("orden_especial").value ==1){
			url="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.orden_compra_especial.php¿ano="+getObj("orden_rp_cmb_ano").value+"@numero_coti="+getObj('orden_rp_numero').value; 
		}
		openTab("Reporte Orden de Compra",url);
		//openTab("Reporte Clasificador Orden de Compra",urls);
	}
});

//------------------ partida ---------------------------
$("#orden_rp_btn_consultar_numero_cotizacion").click(function() {
	if(getObj("orden_rp_id_unidad_ejecutora").value != ""){
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Ordenes', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/orden/rp/sql.numero_orden.php?nd='+nd+'&ano='+getObj("orden_rp_cmb_ano").value+'&unidad='+getObj("orden_rp_id_unidad_ejecutora").value,
								datatype: "json",
								colNames:['Ordenes','orden_especial'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'orden_especial',index:'orden_especial', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("orden_rp_numero").value=ret.numero;
									getObj("orden_especial").value=ret.orden_especial;
									
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
								sortname: 'numero_orden_compra_servicio',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
$("#orden_rp_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cotizaciones', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/orden/rp/cmb.sql.unidad_ejecutora.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("orden_rp_id_unidad_ejecutora").value=ret.id;
									getObj("orden_rp_unidad_ejecutora").value=ret.unidad_ejecutora;
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
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});


$("#orden_rp_opt_todas").click(function() {
	getObj('orden_rp_numero').disabled='true';
	getObj('orden_rp_tr_numero').style.display='none';
	getObj('orden_rp_unidad_ejecutora').disabled='true';
	getObj('orden_rp_tr_unidad_ejecutora').style.display='none';
});

$("#orden_rp_opt_unidad").click(function() {
	getObj('orden_rp_numero').value='';
	getObj('orden_rp_numero').disabled='';
	getObj('orden_rp_tr_numero').style.display='';
	getObj('orden_rp_unidad_ejecutora').value='';
	getObj('orden_rp_unidad_ejecutora').disabled='';
	getObj('orden_rp_tr_unidad_ejecutora').style.display='';
});

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
	<img id="orden_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_orden" id="form_rp_orden">
<input type="hidden" name="orden_especial" id="orden_especial" />
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte Orden de Compra </th>
		</tr>
		<!--<tr>
			<th>Selecci&oacute;n</th>
			<td>
				<input id="orden_rp_opt_todas" name="orden_rp_opt" type="radio" value="0" checked="checked"> Todas
				<input id="orden_rp_opt_unidad" name="orden_rp_opt" type="radio" value="1"> 
				Una (Orden)			
			</td>
		</tr>-->
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="orden_rp_cmb_ano" id="orden_rp_cmb_ano">
					<?
					$anio_inicio=2008;
					$anio_fin=2009;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>" selected="selected"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
				</select>			
			</td>
		</tr>
		<tr id="orden_rp_tr_unidad">
			<th>Unidad Solicitante </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input  name="orden_rp_unidad_ejecutora" type="text" id="orden_rp_unidad_ejecutora" size="24" 
							message="Introduzca la Unidad Solicitante." 
							disabled="disabled"  
						>
						<input type="hidden" name="orden_rp_id_unidad_ejecutora" id="orden_rp_id_unidad_ejecutora" />
					</li>
					<li id="orden_rp_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr id="orden_rp_tr_numero">
			<th>N&ordm; Orden  </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input  name="orden_rp_numero" type="text" id="orden_rp_numero" size="24" 
							message="Introduzca el Número de Orden." 
							disabled="disabled"  
						>
					</li>
					<li id="orden_rp_btn_consultar_numero_cotizacion" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>