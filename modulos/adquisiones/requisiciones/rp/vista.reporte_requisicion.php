<?php
session_start();
?>

<script>
var dialog;
$("#requisiciones_db_btn_imprimir").click(function() {

		url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion.php¿ano="+getObj('requisiciones_pr_cmb_ano').value+"@numero_requi="+getObj('requisiciones_rp_numero').value; 
	//	url="pdf.php?p=modulos/adquisiones/requisiciones/rp/vista.lst.requisicion_listado.php¿ano="+getObj('requisiciones_pr_cmb_ano').value; 
			

		openTab("Requisiciones por Unidad Ejecutora",url);
});

//------------------ partida ---------------------------
$("#requisiciones_db_btn_consultar_numero").click(function() {
	var nd=new Date().getTime();
	//alert('modulos/adquisiones/requisiciones/rp/sql.numero_requisicion.php?nd='+nd+'&ano='+getObj("requisiciones_pr_cmb_ano").value);
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/requisiciones/db/grid_requisiciones.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requisiciones', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/requisiciones/rp/sql.numero_requisicion.php?nd='+nd+'&ano='+getObj("requisiciones_pr_cmb_ano").value,
								datatype: "json",
								colNames:['Requisici&oacute;n'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("requisiciones_rp_numero").value=ret.numero;
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


$("#requisicion_pr_opt_todas").click(function() {
	getObj('requisiciones_rp_numero').disabled='true';
	getObj('requisiciones_rp_tr_unidad_ejecutora').style.display='none';
});
$("#requisicion_pr_opt_unidad").click(function() {
	getObj('requisiciones_rp_numero').disabled='false';
	getObj('requisiciones_rp_tr_unidad_ejecutora').style.display='';
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
	<img id="requisiciones_db_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_pr_cierre_requisiciones" id="form_pr_cierre_requisiciones">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Reporte Requisiciones </th>
		</tr>
		<!--<tr>
			<th>Selección</th>
			<td>
			<input id="requisicion_pr_opt_todas" name="requisicion_pr_opt" type="radio" value="0" checked="checked"> Todas
			<input id="requisicion_pr_opt_unidad" name="requisicion_pr_opt" type="radio" value="1"> 
				Una (Requisici&oacute;n)			
			</td>
		</tr>-->
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="requisiciones_pr_cmb_ano" id="requisiciones_pr_cmb_ano">
					<?
					$anio_inicio=2010;
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
		<tr id="requisiciones_rp_tr_unidad_ejecutora" >
			<th>N&ordm; Requisici&oacute;n</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input  name="requisiciones_rp_numero" type="text" id="requisiciones_rp_numero" size="24" 
							message="Introduzca el Número de requisicion." 
							disabled="disabled"  
						>
					</li>
					<li id="requisiciones_db_btn_consultar_numero" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr id="requisiciones_rp_tr_unidad_ejecutora" style="display:none">
			<th>Unidad Ejecutora</th>
			<td>
				<ul class="input_con_emergente">
					<li>
					<input  name="requisiciones_rp_id_unidad_ejecutora" type="hidden" id="requisiciones_rp_id_unidad_ejecutora" >
						<input  name="requisiciones_rp_unidad_ejecutora" type="text" id="requisiciones_rp_unidad_ejecutora" size="24" 
							message="Introduzca el Número de cotizaci&oacute;n." 
							disabled="disabled"  
						>
					</li>
					<li id="requisiciones_rp_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>