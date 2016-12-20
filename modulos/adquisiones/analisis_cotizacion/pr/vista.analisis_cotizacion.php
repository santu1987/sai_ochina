<script>	
var dialog;
$("#analisis_cotizacion_rp_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.analisis_cotizacion3.php¿ano="+getObj('analisis_cotizacion_rp_cmb_ano').value+"@numero_coti="+getObj('analisis_cotizacion_rp_numero').value; 
		//alert(url);  getObj('analisis_cotizacion_rp_id_unidad').value
		openTab("Analisis por Numero Requesicion",url);
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
								loadtext: "Recuperando Información del Servidor",		
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
								loadtext: "Recuperando Información del Servidor",		
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
									//jQuery("#list_analisis_cot").setGridParam({url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.solicitud_analisis.php?nd='+nd+'&unidad='+getObj('analisis_cot_pr_id_unidad').value+'&requisicion='+ret.id,page:1}).trigger("reloadGrid");

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
$("#analisis_cot_pr_btn_consulta_producto").click(function() {
if(getObj('analisis_cot_pr_id_requisicion').value!="")	
{	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/analisis_cotizacion/pr/grid_analisis.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Requisici&oacute;n', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
					//	alert('modulos/adquisiones/analisis_cotizacion/co/cmb.sql.requisicion_items.php?nd='+nd+'&requisicion='+getObj('analisis_cot_pr_nro_requisicion').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.requisicion_items.php?nd='+nd+'&requisicion='+getObj('analisis_cot_pr_nro_requisicion').value,
								datatype: "json",
								colNames:['Id','Reglon','Producto'],
								colModel:[
									{name:'id',index:'id', width:60,sortable:false,resizable:false,hidden:true},
									{name:'renglon',index:'renglon', width:60,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('analisis_cot_pr_id_producto').value = ret.id;
									getObj('analisis_cot_pr_secuencia_producto').value = ret.renglon;
									getObj('analisis_cot_pr_producto').value = ret.asunto;
									
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/analisis_cotizacion/pr/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('analisis_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion);
									jQuery("#list_analisis_cot").setGridParam({url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.solicitud_analisis.php?nd='+nd+'&unidad='+getObj('analisis_cot_pr_id_unidad').value+'&requisicion='+ret.id,page:1}).trigger("reloadGrid");

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
//**************************************************************************************************************************************
var lastsel,idd,monto;

$("#list_analisis_cot").jqGrid({
	height: 315,
	width: 700,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.solicitud_analisis.php?unidad='+getObj('analisis_cot_pr_id_unidad').value+'&requisicion='+getObj('analisis_cot_pr_id_requisicion').value,
	datatype: "json",
   	colNames:['Id','N&ordm; Cotizacion','id_proveedor','Proveedor','Titulo','Tiempo Entrega','Lugar Entrega','Condiciones Pago','Validez Oferta'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
	   		{name:'cotizacion',index:'cotizacion', width:85,align:"center"},
			{name:'id_proveedor',index:'id_proveedor', width:80,hidden:true},
			{name:'proveedor',index:'proveedor', width:200,align:"center"},
			{name:'titulo',index:'titulo', width:200,hidden:true},
			{name:'tiempo_entrega',index:'tiempo_entrega', width:100,align:"center"},
			{name:'lugar_entrega',index:'lugar_entrega', width:100,align:"center"},
			{name:'condiciones_pago',index:'condiciones_pago', width:100},
			{name:'validez_oferta',index:'validez_oferta', width:100,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_analisis_cot'),
   	sortname: 'id_solicitud_cotizacione',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	subGrid: true,
	subGridUrl: 'modulos/adquisiones/analisis_cotizacion/co/cmb.sql.analisis_detalle.php',
	subGridModel: [{ name  : ['Secuencia','Proveedor','Precio','Pago','Garantia','Oferta','Tiempo','Total'], 
                    width : [55,200,60,70,70,70,70,70] } 
    ],


	onSelectRow: function(id){
		s = jQuery("#list_analisis_cot").getGridParam('selarrrow');
		var ret = jQuery("#list_analisis_cot").getRowData(id);
			//getObj('analisis_cot_numero_cotizacion').value = ret.cotizado;
		//alert(ret.cotizado);
		getObj('analisis_cot_req_select').value = s;
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
	},
	loadComplete: function(){
		var ids = jQuery("#list_analisis_cot").getDataIDs();
		xo = '';
		for(var i=0;i<ids.length;i++){
			var cl = ids[i];
			var ret = jQuery("#list_analisis_cot").getRowData(cl);
			if(ret.cotizado == 't'){
				jQuery("#list_analisis_cot").setSelection(cl);
				if (xo ==''){
					xo = cl;
					//alert(xo);
				}else{
					xo = xo +','+cl;
					//alert(xo);
				}
			}
		}
		getObj('analisis_cot_req_select').value = xo;
	},
	onSelectAll:function(id){
		s = jQuery("#list_analisis_cot").getGridParam('selarrrow');
		//alert(s);
		getObj('analisis_cot_req_select').value = s;
	}
}).navGrid("#pager_analisis_cot",{search :false,edit:false,add:false,del:false});

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

$('#analisis_cot_pr_codigo_unidad').change(consulta_automatica_unidad_ejecutora);

</script>
<div id="botonera">
	<img id="analisis_cot_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="analisis_cot_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="analisis_cot_pr_btn_consultar" name="analisis_cot_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="analisis_cot_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="analisis_cot_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
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
<tr>
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
</tr>
<tr>
	<td class="celda_consulta" colspan="2">
		<table id="list_analisis_cot" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_analisis_cot" class="scroll" style="text-align:center;"></div> 
		<br />
	</td>
</tr>
<tr>
	<td colspan="2" class="bottom_frame">&nbsp;</td>
</tr>	

</table>
</form>