<script>
var dialog;
$("#analisis_cot_pr_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/analisis_cotizacion/rp/reporte_analisis_cotizacion.php�id_requisicion="+getObj('analisis_cot_pr_id_requisicion').value+"@nro_requisicion="+getObj('analisis_cot_pr_nro_requisicion').value; 
		//alert(url);
		openTab("Analisis de cotizacion",url);
});
//----------------------------------------------------------------------------------------------------------------------------

$("#analisis_cot_pr_btn_consulta_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/analisis_cotizacion/pr/grid_analisis.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.unidad_ejecutora.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo', 'Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('analisis_cot_pr_id_unidad').value = ret.id;
									getObj('analisis_cot_pr_codigo_unidad').value = ret.codigo;
									getObj('analisis_cot_pr_unidad').value = ret.nombre;
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
//-------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/adquisiones/analisis_cotizacion/co/sql_grid_unidad_ejecutora_codigo.php",
            data:dataForm('form_analisis_cot'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('analisis_cot_pr_unidad').value = recordset[1];
				getObj('analisis_cot_pr_id_unidad').value=recordset[0];
				
					}
				else
			 {  
			   	getObj('analisis_cot_pr_unidad').value ="";
				getObj('analisis_cot_pr_id_unidad').value="";
			   
				}
				
			 }
		});	 	 
}
//----------------------------------------------------------------------------------------------------------------------------

$("#analisis_cot_pr_btn_consulta_requisicion").click(function() {
if(getObj('analisis_cot_pr_id_unidad').value!="")	
{	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/analisis_cotizacion/pr/grid_analisis.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requisici&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.requisicion.php?nd='+nd+'&unidad='+getObj('analisis_cot_pr_id_unidad').value,
								datatype: "json",
								colNames:['Id','Numero Requisicion','Asunto'],
								colModel:[
									{name:'id',index:'id', width:60,sortable:false,resizable:false,hidden:true},
									{name:'numero_requisicion',index:'numero_requisicion', width:60,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('analisis_cot_pr_id_requisicion').value = ret.id;
									getObj('analisis_cot_pr_nro_requisicion').value = ret.numero_requisicion;
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/analisis_cotizacion/pr/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('analisis_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion);
									jQuery("#list_analisis_cot").setGridParam({url:'modulos/adquisiones/analisis_cotizacion/pr/sql_master_analisis.php?requisicion='+getObj('analisis_cot_pr_id_requisicion').value+'&nro_requisicion='+getObj('analisis_cot_pr_nro_requisicion').value,page:1}).trigger("reloadGrid");

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
}
});
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

jQuery("#list_analisis_cot").jqGrid({
	width: 690,
	height: 90,
   	url:'modulos/adquisiones/analisis_cotizacion/pr/sql_master_analisis.php?requisicion='+getObj('analisis_cot_pr_id_requisicion').value+'&nro_requisicion='+getObj('analisis_cot_pr_nro_requisicion').value,
	datatype: "json",
   	colNames:['id','Requisicion', 'Secuencia', 'Descripcion','Cantidad'],
   	colModel:[
   		{name:'id',index:'id', width:55,hidden:true},
   		{name:'requisicion',index:'requisicion', width:70,hidden:true},
   		{name:'secuencia',index:'secuencia', width:40},
   		{name:'descripcion',index:'descripcion', width:230},
   		{name:'cantidad',index:'cantidad', width:50}		
   	],
   	rowNum:5,
   	rowList:[5,10,15],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_analisis_cot'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	multiselect: false,
	/*subGrid: true,
	subGridUrl: 'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.analisis_encabezado.php?q=2&requisicion='+getObj('consulta_analisis_cot_co_id_requisicion').value,
	subGridModel: [{ name  : ['Secuencia','Descripcion','Cantidad','Monto','Total','Partida'], 
					width : [55,250,60,70,70,70] } 
	],*/

//	caption: "Datos de la Requisici&oacute;n",
	onSelectRow: function(ids) {
	var ret = jQuery("#list_analisis_cot").getRowData(ids);
		if(ids == null) {
			ids=0;
			if(jQuery("#list_analisis_cot_d").getGridParam('records') >0 )
			{
				jQuery("#list_analisis_cot_d").setGridParam({url:"modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php?q=1&id="+ret.id+"&requi="+ret.requisicion+"&secuencia="+ret.secuencia,page:1})
			//	.setCaption("Analisis de Renglon: "+ids)
				.trigger('reloadGrid');
				//alert("modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php?q=1&id="+ids+"&requi="+ret.requisicion+"&secuencia="+ret.secuencia);
			}
		} else {
			jQuery("#list_analisis_cot_d").setGridParam({url:"modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php?q=1&id="+ret.id+"&requi="+ret.requisicion+"&secuencia="+ret.secuencia,page:1})
			//.setCaption("Analisis de Renglon: "+ids)
			.trigger('reloadGrid');	
			//alert("modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php?q=1&id="+ret.id+"&requi="+ret.requisicion+"&secuencia="+ret.secuencia);		
		}
	}
}).navGrid('#pager_analisis_cot',{add:false,edit:false,del:false,search:false});

jQuery("#list_analisis_cot_d").jqGrid({
	height: 100,
	//width: 900,
   	url:'modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php?q=1&id=0',
	datatype: "json",
   	colNames:['id','Proveedor', 'Puntos Precio', 'Puntos Pago','Puntos Garantia', 'Puntos Oferta', 'Puntos Tiempo', 'Puntos Total'],
   	colModel:[
   		{name:'id',index:'id', width:5,hidden:true},
   		{name:'proveedor',index:'proveedor', width:135},
   		{name:'precio',index:'precio', width:88, align:"right"},
   		{name:'pago',index:'pago', width:80, align:"right"},		
   		{name:'garantia',index:'garantia', width:95,align:"right", sortable:false},
   		{name:'oferta',index:'oferta', width:88, align:"right"},		
   		{name:'tiempo',index:'tiempo', width:92,align:"right", sortable:false},		
   		{name:'total',index:'total', width:88,align:"right", sortable:false}
		
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_analisis_cot_d'),
   	//sortname: 'total',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
	//caption:"Analisis de Cotizaci&oacute;n"
}).navGrid('#pager_analisis_cot_d',{add:false,edit:false,del:false, search:false});
jQuery("#ms1").click( function() {
	var s;
	s = jQuery("#list_analisis_cot_d").getMultiRow();
	alert(s);
});

$("#analisis_cot_pr_btn_cancelar").click(function() {
jQuery("#list_analisis_cot").setGridParam({url:'modulos/adquisiones/analisis_cotizacion/pr/sql_master_analisis.php?requisicion=0&nro_requisicion=0',page:1}).trigger("reloadGrid");
	clearForm('form_analisis_cot');
	jQuery("#list_analisis_cot_d").setGridParam({url:"modulos/adquisiones/analisis_cotizacion/pr/sql_detalle_analisis.php",page:1}).trigger("reloadGrid");
	
});

$('#analisis_cot_pr_codigo_unidad').change(consulta_automatica_unidad_ejecutora);
</script>
<div id="botonera">
	<img id="analisis_cot_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="analisis_cot_pr_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form name="form_analisis_cot" id="form_analisis_cot">
<input type="hidden" name="analisis_cot_req_select" id="analisis_cot_req_select" />

<table class="cuerpo_formulario">
<tr>
	<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Analisis Cotizaci&oacute;n</th>
</tr>
<tr>
	<th>Unidad Solicitante</th>
	<td>
		<table class="clear" style="width:450px">
			<tr>
				<td>
					<input type="hidden" name="analisis_cot_pr_id_unidad" id="analisis_cot_pr_id_unidad" >
					<input type="text" name="analisis_cot_pr_codigo_unidad" id="analisis_cot_pr_codigo_unidad" size="6" maxlength="5"
					message="Introduzca un Codigo para la unidad solicitante." 
					jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}">
					<input type="text" name="analisis_cot_pr_unidad" id="analisis_cot_pr_unidad" size="65" maxlength="100" readonly>
				</td>
				<td style="width:5px"><img id="analisis_cot_pr_btn_consulta_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<th>Nro Requisici&oacute;n</th>
	<td>
		<table class="clear" style="width:90px">
			<tr>
				<td style="width:80px">
				<input type="hidden" name="analisis_cot_pr_id_requisicion" id="analisis_cot_pr_id_requisicion" >
				<input type="text" name="analisis_cot_pr_nro_requisicion" id="analisis_cot_pr_nro_requisicion" size="10" maxlength="9" readonly>	</td>
				<td style="width:5px"><img id="analisis_cot_pr_btn_consulta_requisicion" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
			</tr>
		</table>
	</td>
</tr>
<!--<tr>
	<th>Producto</th>
	<td>
		<table class="clear" style="width:90px">
			<tr>
				<td style="width:80px">
				<input type="hidden" name="analisis_cot_pr_id_producto" id="analisis_cot_pr_id_producto" >
				<input type="hidden" name="analisis_cot_pr_secuencia_producto" id="analisis_cot_pr_secuencia_producto" >
				<input type="text" name="analisis_cot_pr_producto" id="analisis_cot_pr_producto" size="10" maxlength="9" readonly>	</td>
				<td style="width:5px"><img id="analisis_cot_pr_btn_consulta_producto" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
			</tr>
		</table>
	</td>
</tr>-->
<tr>
	<td class="celda_consulta" colspan="2">
		<table id="list_analisis_cot" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_analisis_cot" class="scroll" style="text-align:center;"></div> 
		<br />
	</td>
</tr>
<tr>
	<td colspan="2" >&nbsp;</td>
</tr>
<tr>
	<td class="celda_consulta" colspan="2">
		<table id="list_analisis_cot_d" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_analisis_cot_d" class="scroll" style="text-align:center;width:100%" ></div> 
		<br />
	</td>
</tr>
<tr>
	<td colspan="2" class="bottom_frame">&nbsp;</td>
</tr>	

</table>
</form>
<table id="list_analisis_cot" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_analisis_cot" class="scroll" style="text-align:center;"></div> 
		<br />
		
<!--<table id="list_analisis_cot" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager_analisis_cot" class="scroll" style="text-align:center;"></div>
<br />
Invoice Detail-->
<table id="list_analisis_cot_d" align="center" class="scroll" cellpadding="0" cellspacing="0"></table>
<div align="center" id="pager_analisis_cot_d" class="scroll" style="text-align:center;" ></div>

<!--<a href="javascript:void(0)" id="ms1">Get Selected id's</a>-->