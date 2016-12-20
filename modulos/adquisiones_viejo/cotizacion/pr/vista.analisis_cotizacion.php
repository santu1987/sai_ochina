<script>
var dialog;

$("#analisis_cotizacion_pr_btn_consulta_nro_cotizacion").click(function() {
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
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.numero_cotizacion.php?nd='+nd,
								datatype: "json",
								colNames:['Cotizaci&oacute;n','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','id_solicitud_cotizacione','titulo'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'id_solicitud_cotizacione',index:'id_solicitud_cotizacione', width:100,sortable:false,resizable:false,hidden:true},
									{name:'titulo',index:'titulo', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("analisis_cotizacion_pr_numero").value=ret.numero;
									getObj("analisis_cotizacion_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("analisis_cotizacion_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("analisis_cotizacion_pr_id_proveedor").value=ret.idproveedor;
									getObj("analisis_cotizacion_pr_proveedor").value=ret.proveedor;
									getObj("analisis_cotizacion_pr_id").value=ret.id_solicitud_cotizacione;
									getObj("analisis_cotizacion_pr_titulo").value=ret.titulo;
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
</script>
<form name="form_analisis_cotizacion" id="form_analisis_cotizacion">
<input type="hidden" name="analisis_cotizacion_pr_id" id="analisis_cotizacion_pr_id" />

	<table  class="cuerpo_formulario">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Anal&iacute;sis de Cotizaci&oacute;n 
			</th>
		</tr>
		<tr>
			<th>N&uacute;mero de Cotizaci&oacute;n</th>
			<td>
				<input type="text" name="analisis_cotizacion_pr_numero" id="analisis_cotizacion_pr_numero"  readonly size="8" />
				<img id="analisis_cotizacion_pr_btn_consulta_nro_cotizacion" class="btn_consulta_emergente" src="imagenes/null.gif"  />
			</td>
		</tr>
		<tr>
			<th>Unidad Ejecutora</th>
			<td>
				<input type="hidden" name="analisis_cotizacion_pr_id_unidad_ejecutora" id="analisis_cotizacion_pr_id_unidad_ejecutora" />
				<input type="text"   name="analisis_cotizacion_pr_unidad_ejecutora"    id="analisis_cotizacion_pr_unidad_ejecutora"  readonly size="63" />
			</td>
		</tr>
		<tr>
			<th>Proveedor</th>
			<td><input type="hidden" name="analisis_cotizacion_pr_id_proveedor" id="analisis_cotizacion_pr_id_proveedor"  />
				<input type="text" name="analisis_cotizacion_pr_proveedor" id="analisis_cotizacion_pr_proveedor" readonly size="63" />
			</td>
		</tr>
		<tr>
			<th>T&iacute;tulo</th>
			<td><textarea name="analisis_cotizacion_pr_titulo" id="analisis_cotizacion_pr_titulo"  cols="60" rows="2"  ></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>