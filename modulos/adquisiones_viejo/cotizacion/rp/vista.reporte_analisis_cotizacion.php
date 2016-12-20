<?php
session_start();
?>

<script>
var dialog;
$("#analisis_cotizacion_rp_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.analisis_cotizacion3.php¿ano="+getObj('analisis_cotizacion_rp_cmb_ano').value+"@numero_coti="+getObj('analisis_cotizacion_rp_numero').value; 
		//alert(url);  getObj('analisis_cotizacion_rp_id_unidad').value
		openTab("Analisis por Numero Requesicion",url);
});

//------------------ partida ---------------------------
$("#analisis_cotizacion_rp_btn_consultar_numero_cotizacion").click(function() {
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
								url:'modulos/adquisiones/cotizacion/rp/cmb.sql.numero_requisicion.php?nd='+nd+'&ano='+getObj("analisis_cotizacion_rp_cmb_ano").value,
								datatype: "json",
								colNames:['Cotizacion','id_unidad','Unidad Ejecutora'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:10,sortable:false,resizable:false,hidden:true},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("analisis_cotizacion_rp_numero").value=ret.numero;
									getObj('analisis_cotizacion_rp_id_unidad').value= ret.nombre;
									
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
								sortname: 'numero_requisicion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
$("#analisis_cotizacion_rp_btn_consultar_unidad_ejecutora").click(function() {
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
									getObj("analisis_cotizacion_rp_id_unidad_ejecutora").value=ret.id;
									getObj("analisis_cotizacion_rp_unidad_ejecutora").value=ret.unidad_ejecutora;
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


$("#analisis_cotizacion_rp_opt_todas").click(function() {
	getObj('analisis_cotizacion_rp_numero').disabled='true';
	getObj('analisis_cotizacion_rp_tr_numero').style.display='none';
	getObj('analisis_cotizacion_rp_unidad_ejecutora').disabled='true';
	getObj('analisis_cotizacion_rp_tr_unidad_ejecutora').style.display='none';
});

$("#analisis_cotizacion_rp_opt_unidad").click(function() {
	getObj('analisis_cotizacion_rp_numero').value='';
	getObj('analisis_cotizacion_rp_numero').disabled='';
	getObj('analisis_cotizacion_rp_tr_numero').style.display='';
	getObj('analisis_cotizacion_rp_unidad_ejecutora').value='';
	getObj('analisis_cotizacion_rp_unidad_ejecutora').disabled='';
	getObj('analisis_cotizacion_rp_tr_unidad_ejecutora').style.display='';
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
	<img id="analisis_cotizacion_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_analisis_cotizacion" id="form_rp_analisis_cotizacion">
<input type="hidden" name="analisis_cotizacion_rp_id_unidad" id="analisis_cotizacion_rp_id_unidad" />
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte An&aacute;lisis Cotizaci&oacute;n </th>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="analisis_cotizacion_rp_cmb_ano" id="analisis_cotizacion_rp_cmb_ano">
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
		<tr id="analisis_cotizacion_rp_tr_numero" >
			<th>N&ordm; Requisici&oacute;n </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input  name="analisis_cotizacion_rp_numero" type="text" id="analisis_cotizacion_rp_numero" size="24" 
							message="Introduzca el Número de Requisici&oacute;n." 
							disabled="disabled"  
						>
					</li>
					<li id="analisis_cotizacion_rp_btn_consultar_numero_cotizacion" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>