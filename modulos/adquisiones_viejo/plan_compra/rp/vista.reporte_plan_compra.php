<?php
session_start();
?>

<script>
var dialog;
$("#plan_compra_rp_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/plan_compra/rp/vista.lst.gasto_funcionamiento.php¿ano="+getObj('plan_compra_rp_cmb_ano').value+"@unidad_ejecutora="+getObj('plan_compra_rp_id_unidad_ejecutora').value; 
		//alert(url);
		openTab("Plan de Compras",url);
});


//-------------------------------------------------------------------------------------------------------------------------
$("#plan_compra_rp_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/db/grid_plan_compra.php", { },
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
								url:'modulos/adquisiones/plan_compra/co/cmb.sql.unidad_ejecutora.php?nd='+nd,
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
									getObj("plan_compra_rp_id_unidad_ejecutora").value=ret.id;
									getObj("plan_compra_rp_unidad_ejecutora").value=ret.unidad_ejecutora;
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


$("#plan_compra_rp_opt_todas").click(function() {
	getObj('plan_compra_rp_numero').disabled='true';
	getObj('plan_compra_rp_tr_numero').style.display='none';
	getObj('plan_compra_rp_unidad_ejecutora').disabled='true';
	getObj('plan_compra_rp_tr_unidad_ejecutora').style.display='none';
});

$("#plan_compra_rp_opt_unidad").click(function() {
	getObj('plan_compra_rp_tr_unidad_ejecutora').style.display='';
	getObj('plan_compra_rp_id_unidad_ejecutora').value='';
	getObj('plan_compra_rp_id_unidad_ejecutora').disabled='';
	getObj('plan_compra_rp_unidad_ejecutora').value='';
	getObj('plan_compra_rp_unidad_ejecutora').disabled='';
	getObj('plan_compra_rp_cmb_ano').style.display='';
	
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
	<img id="plan_compra_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_plan_compra" id="form_rp_plan_compra">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte Plan de Compra </th>
		</tr>
		<tr>
			<th>Selecci&oacute;n</th>
			<td>
				<input id="plan_compra_rp_opt_todas" name="plan_compra_rp_opt" type="radio" value="0" checked="checked"> Todas
				<input id="plan_compra_rp_opt_unidad" name="plan_compra_rp_opt" type="radio" value="1">  Un (Plan de Compra)			
			</td>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="plan_compra_rp_cmb_ano" id="plan_compra_rp_cmb_ano">
					<?
					$anio_inicio=2009;
					$anio_fin=2010;
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

		<tr id="plan_compra_rp_tr_unidad_ejecutora" style="display:none">
			<th>Unidad Ejecutora</th>
			<td>
				<ul class="input_con_emergente">
					<li>
					<input  name="plan_compra_rp_id_unidad_ejecutora" type="hidden" id="plan_compra_rp_id_unidad_ejecutora" >
						<input  name="plan_compra_rp_unidad_ejecutora" type="text" id="plan_compra_rp_unidad_ejecutora" size="24" 
							message="Introduzca el Número de cotizaci&oacute;n." 
							disabled="disabled"  
						>
					</li>
					<li id="plan_compra_rp_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>