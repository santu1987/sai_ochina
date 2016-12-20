<script>
var dialog;
$("#orden_pr_btn_imprimir").click(function() {
	if(getObj('orden_pr_numero').value != ""){
		url="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.orden_compra.php¿ano=2009@numero_coti="+getObj('orden_pr_numero').value; 
		urls="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.clasificacion.php¿ano=2009@numero_coti="+getObj('orden_pr_numero').value; 
		//alert(url);
		openTab("Reporte Orden de Compra",url);
		openTab("Reporte Clasificador Orden de Compra",urls);
	}
});

$("#orden_pr_btn_guardar").click(function() {
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/orden/pr/sql.orden_final.php",
			data:dataForm('form_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			resultado = html.split(",");
				if (resultado[0]=="Registrado")
				{
					getObj('orden_pr_numero').value = resultado[1];
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN FUE CREADA CON EXISTO,<BR>ORDEN N&ordm; "+resultado[1]+"</p></div>",true,true);
					//jQuery("#list_orden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
					//clearForm('form_orden');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
});


//----------------------------------------------------------------

$("#orden_pr_btn_consulta_nro_cotizacion").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/orden/pr/grid_orden.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Orden de Compra/Servicio', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/orden/pr/cmb.sql.numero_orden.php?nd='+nd,
								datatype: "json",
								colNames:['Cotizaci&oacute;n','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','id_requisicion','pre_orden'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'idrequisicion',index:'idrequisicion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'pre_orden',index:'pre_orden', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("orden_pr_nro_cotizacion").value=ret.numero;
									getObj("orden_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("orden_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("orden_pr_id_proveedor").value=ret.idproveedor;
									getObj("orden_pr_proveedor").value=ret.proveedor;
									getObj("orden_pr_id_requisicion").value=ret.idrequisicion;
									getObj("orden_pr_nro_pre_orden").value=ret.pre_orden;
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									jQuery("#list_orden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero,page:1}).trigger("reloadGrid");
									
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
//------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_orden_nro_cotizacion()
{
	$.ajax({
			url:"modulos/adquisiones/orden/pr/sql.grid.numero_cotizacion.php",
            data:dataForm('form_orden'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
			
				getObj("orden_pr_id_unidad_ejecutora").value=recordset[3];
				getObj("orden_pr_unidad_ejecutora").value=recordset[4];
				getObj("orden_pr_id_proveedor").value=recordset[1];
				getObj("orden_pr_proveedor").value=recordset[2];
				getObj("orden_pr_id_requisicion").value=recordset[5];	
					}
				else
			 {  
			   	getObj("orden_pr_id_unidad_ejecutora").value="";
				getObj("orden_pr_unidad_ejecutora").value="";
				getObj("orden_pr_id_proveedor").value="";
				getObj("orden_pr_proveedor").value="";
				getObj("orden_pr_id_requisicion").value="";	
				}
				
			 }
		});	 	 
}
//-------------------------------------------------------------------------------------------------------------------------
$("#orden_pr_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('orden_pr_btn_cancelar').style.display='';
	getObj('orden_pr_btn_guardar').style.display='';
	jQuery("#list_orden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php',page:1}).trigger("reloadGrid");
	clearForm('form_orden');
	getObj('orden_pr_tipo_orden').value='3';
});
//-------------------------------------------------------------------------------------------------------------------------
//**************************************************************************************************************************************
var lastsel,idd,monto;
$("#list_orden").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','id_unidad_medida','Unidad Medida','Descripcion','Monto'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
	   		{name:'n_renglon',index:'n_renglon', width:45},
			{name:'cantidad',index:'cantidad', width:50},
			{name:'id_unidad_medida',index:'id_unidad_medida', width:200,hidden:true},
			{name:'nombre',index:'nombre', width:60},
			{name:'descripcion',index:'descripcion', width:200},
			{name:'monto',index:'monto', width:55}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_orden'),
   	sortname: 'numero_orden_compra_servicio',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
	/*onSelectRow: function(id){
		s = jQuery("#list_orden").getGridParam('selarrrow');
		//alert(s);
		getObj('orden_pr_cot_select').value = s;
	},*/
}).navGrid("#pager_orden",{search :false,edit:false,add:false,del:false});

//**************************************************************************************************************************************

$('#orden_pr_nro_cotizacion').change(consulta_automatica_orden_nro_cotizacion);

</script>

<div id="botonera">
	<img id="orden_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="orden_pr_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
	<img id="orden_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_orden" id="form_orden">
<input type="hidden" name="orden_pr_numero" id="orden_pr_numero"  />
<input type="hidden" name="orden_pr_id_requisicion" id="orden_pr_id_requisicion"  />
<input type="hidden" name="orden_pr_cot_select" id="orden_pr_cot_select"  />
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /><!--Convertir Cotizaci&oacute;n a -->Orden de Compra/Servicio</th>
		</tr>
		<tr>
			<th>N&uacute;mero de Pre Orden</th>
			<td>
				<input type="text" name="orden_pr_nro_pre_orden" id="orden_pr_nro_pre_orden" size="9" maxlength="8"  />
			</td>
		</tr>
		<tr>
			<th>N&uacute;mero de Cotizaci&oacute;n</th>
			<td>
				<input type="text" name="orden_pr_nro_cotizacion" id="orden_pr_nro_cotizacion" size="9" maxlength="8" 
				onchange="consulta_automatica_orden_nro_cotizacion" onclick="consulta_automatica_orden_nro_cotizacion"
				message="Introduzca la Número de Cotización."  />
				<img id="orden_pr_btn_consulta_nro_cotizacion" class="btn_consulta_emergente" src="imagenes/null.gif"  />
			</td>
		</tr>
		<tr>
			<th>Unidad Ejecutora</th>
			<td>
				<input type="hidden" name="orden_pr_id_unidad_ejecutora" id="orden_pr_id_unidad_ejecutora"/>
				<input type="text"   name="orden_pr_unidad_ejecutora"    id="orden_pr_unidad_ejecutora"   size="60" maxlength="60" />
			</td>
		</tr>
		<tr>
			<th>Proveedor</th>
			<td>
				<input type="hidden" name="orden_pr_id_proveedor" id="orden_pr_id_proveedor"/>
				<input type="text"   name="orden_pr_proveedor"    id="orden_pr_proveedor"   size="60" maxlength="60" />
			</td>
		</tr>
		<tr>
			<th>Comentarios</th>
			<td>
				<textarea name="orden_pr_comentraios" id="orden_pr_comentraios" cols="57"></textarea>
			</td>
		</tr>
		<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_orden" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_orden" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>