<?php
session_start();
?>

<script>
var dialog;
$("#presupuestoII_rp_btn_imprimir").click(function() {
	if(($('#form_pr_cierre_presupuesto_ley2').jVal()))
	{
		url="pdf.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_partida_nuevo.php¿anio="+getObj('presupuestoII_rp_cmb_ano').value/*+"@unidad_ejecutora="+getObj('presupuestoII_rp_id_unidad_ejecutora').value*/; 
		openTab("presupuesto por Partidas",url);
	}
});
$("#presupuestoII_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_pr_cierre_presupuesto_ley2');
});
//------------------ partida ---------------------------
$("#presupuesto_ley_pr_btn_consultar_unidad_ejecutora").click(function() {
if (getObj("presupuestoII_rp_opt_todas").checked  == false){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/pr/grid_unidad_ejecutora.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Ante poryecto por Partida', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/presupuesto_ley/pr/sql_grid.unidad_ejecutora.php?nd='+nd,
								datatype: "json",
								colNames:['Unidad'],
								colModel:[
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("presupuestoII_rp_id_unidad_ejecutora").value=id;							
									getObj("presupuestoII_rp_nombre_unidad_ejecutora").value=ret.nombre;
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
	}
});


$("#presupuestoII_rp_opt_todas").click(function() {
	getObj('presupuestoII_rp_id_unidad_ejecutora').disabled='true';
	getObj('presupuestoII_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('presupuestoII_rp_id_unidad_ejecutora').value='';
	getObj('presupuestoII_rp_nombre_unidad_ejecutora').value='';
	//getObj('presupuestoII_rp_tr_unidad_ejecutora').style.display='none';
});

$("#presupuesto_ley_pr_opt_unidad").click(function() {
	getObj('presupuestoII_rp_id_unidad_ejecutora').disabled='';
	getObj('presupuestoII_rp_nombre_unidad_ejecutora').disabled='';
//	getObj('presupuestoII_rp_tr_unidad_ejecutora').style.display='';
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
	<img id="presupuestoII_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="presupuestoII_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_pr_cierre_presupuesto_ley22" id="form_pr_cierre_presupuesto_ley2">
	<table class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Presupuesto 
			</th>
		</tr>
		<!--<tr>
			<th>Selección</th>
			<td>
				<input id="presupuestoII_rp_opt_todas" name="presupuesto_ley_pr_opt" type="radio" value="0" checked="checked"> Todas
				<input id="presupuesto_ley_pr_opt_unidad" name="presupuesto_ley_pr_opt" type="radio" value="1"> Una (UNIDAD EJECUTORA)			
			</td>
		</tr>-->
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="presupuestoII_rp_cmb_ano" id="presupuestoII_rp_cmb_ano">
					<?
					$anio_inicio=2010;
					$anio_fin=2011;
					while($anio_inicio <= $anio_fin)
					{
					if($anio_inicio==date('Y')+1)
						$selected = "selected";
					else
						$selected = "";
					?>
					<option <?=$selected?>  value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
				</select>			
			</td>
		</tr>
		<!--<tr id="presupuestoII_rp_tr_unidad_ejecutora" ><!--style="display:none"->
			<th>Unidad Ejecutora</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="presupuestoII_rp_id_unidad_ejecutora" id="presupuestoII_rp_id_unidad_ejecutora" disabled="disabled" />
						<input  name="presupuestoII_rp_nombre_unidad_ejecutora" type="text" id="presupuestoII_rp_nombre_unidad_ejecutora" size="24" 
							message="Introduzca un Nombre para la Unidad Ejecutora." 
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="presupuesto_ley_pr_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>
			</td>    
		</tr>-->
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>