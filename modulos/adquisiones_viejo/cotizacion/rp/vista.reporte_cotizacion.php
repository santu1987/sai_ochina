<?php
session_start();
?>

<script>
var dialog;
$("#cotizacion_rp_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.solicitudcotizacion.php¿ano="+getObj('cotizacion_rp_cmb_ano').value+"@numero_coti="+getObj('cotizacion_rp_numero').value; 
		//alert(url);
		openTab("Cotizaciones por Unidad Ejecutora",url);
});

//------------------ partida ---------------------------
$("#cotizacion_rp_btn_consultar_numero_cotizacion").click(function() {
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
								url:'modulos/adquisiones/cotizacion/rp/sql.numero_cotizacion.php?nd='+nd+'&ano='+getObj("cotizacion_rp_cmb_ano").value,
								datatype: "json",
								colNames:['Cotizacion'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("cotizacion_rp_numero").value=ret.numero;
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
								sortname: 'numero_cotizacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
$("#cotizacion_rp_btn_consultar_unidad_ejecutora").click(function() {
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
								url:'modulos/adquisiones/cotizacion/rp/cmb.sql.unidad_ejecutora.php?nd='+nd,
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
									getObj("cotizacion_rp_id_unidad_ejecutora").value=ret.id;
									getObj("cotizacion_rp_unidad_ejecutora").value=ret.unidad_ejecutora;
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


$("#cotizacion_rp_opt_todas").click(function() {
	getObj('cotizacion_rp_numero').disabled='true';
	getObj('cotizacion_rp_tr_numero').style.display='none';
	getObj('cotizacion_rp_unidad_ejecutora').disabled='true';
	getObj('cotizacion_rp_tr_unidad_ejecutora').style.display='none';
});

$("#cotizacion_rp_opt_unidad").click(function() {
	getObj('cotizacion_rp_numero').value='';
	getObj('cotizacion_rp_numero').disabled='';
	getObj('cotizacion_rp_tr_numero').style.display='';
	getObj('cotizacion_rp_unidad_ejecutora').value='';
	getObj('cotizacion_rp_unidad_ejecutora').disabled='';
	getObj('cotizacion_rp_tr_unidad_ejecutora').style.display='';
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
	<img id="cotizacion_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_cotizacion" id="form_rp_cotizacion">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte Cotizaci&oacute;n </th>
		</tr>
		<!--<tr>
			<th>Selección</th>
			<td>
				<input id="cotizacion_rp_opt_todas" name="cotizacion_rp_opt" type="radio" value="0" checked="checked"> Todas
				<input id="cotizacion_rp_opt_unidad" name="cotizacion_rp_opt" type="radio" value="1"> 
				Una (Cotizaci&oacute;n)			
			</td>
		</tr>-->
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="cotizacion_rp_cmb_ano" id="cotizacion_rp_cmb_ano">
					<?
					$anio_inicio=2008;
					$anio_fin=2009;
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
		<tr id="cotizacion_rp_tr_numero" >
			<th>N&ordm; Cotizaci&oacute;n</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input  name="cotizacion_rp_numero" type="text" id="cotizacion_rp_numero" size="24" 
							message="Introduzca el Número de cotizaci&oacute;n." 
							disabled="disabled"  
						>
					</li>
					<li id="cotizacion_rp_btn_consultar_numero_cotizacion" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<!--<tr id="cotizacion_rp_tr_unidad_ejecutora" style="display:none">
			<th>Unidad Ejecutora</th>
			<td>
				<ul class="input_con_emergente">
					<li>
					<input  name="cotizacion_rp_id_unidad_ejecutora" type="hidden" id="cotizacion_rp_id_unidad_ejecutora" >
						<input  name="cotizacion_rp_unidad_ejecutora" type="text" id="cotizacion_rp_unidad_ejecutora" size="24" 
							message="Introduzca el Número de cotizaci&oacute;n." 
							disabled="disabled"  
						>
					</li>
					<li id="cotizacion_rp_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>-->
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>