<script>
var dialog;
$("#pre_compromisos_co_btn_imprimir").click(function() {
		url="pdf.php?p=modulos/adquisiones/analisis_cotizacion/rp/reporte_analisis_cotizacion.php¿id_requisicion="+getObj('pre_compromisos_co_id_pre_compromiso').value+"@nro_requisicion="+getObj('pre_compromisos_co_nro_pre_compromiso').value; 
		//alert(url);
		openTab("Analisis de cotizacion",url);
});
//----------------------------------------------------------------------------------------------------------------------------

$("#pre_compromisos_co_btn_consulta_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/co/grid_compromiso.php", { },
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
								url:'modulos/presupuesto/comprometer_presupuesto/co/cmb.sql.unidad_ejecutora.php?nd='+nd,
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
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('pre_compromisos_co_id_unidad').value = ret.id;
									getObj('pre_compromisos_co_codigo_unidad').value = ret.codigo;
									getObj('pre_compromisos_co_unidad').value = ret.nombre;
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
			url:"modulos/presupuesto/comprometer_presupuesto/co/sql_grid_unidad_ejecutora_codigo.php",
            data:dataForm('form_pre_compromisos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('pre_compromisos_co_unidad').value = recordset[1];
				getObj('pre_compromisos_co_id_unidad').value=recordset[0];
				
					}
				else
			 {  
			   	getObj('pre_compromisos_co_unidad').value ="";
				getObj('pre_compromisos_co_id_unidad').value="";
			   
				}
				
			 }
		});	 	 
}
//----------------------------------------------------------------------------------------------------------------------------
function ordenar()
{
//		alert("modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?q=1&id="+getObj('pre_compromisos_co_id_pre_compromiso').value+"&requi="+getObj('pre_compromisos_co_nro_pre_compromiso').value+"&secuencia="+getObj('pre_compromiso_secuen').value+"&orden="+getObj('pre_compromisos_co_ordenar').value);
	jQuery("#list_pre_compromisos_d").setGridParam({url:"modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?q=1&id="+getObj('pre_compromisos_co_id_pre_compromiso').value+"&requi="+getObj('pre_compromisos_co_nro_pre_compromiso').value+"&secuencia="+getObj('pre_compromiso_secuen').value+"&orden="+getObj('pre_compromisos_co_ordenar').value,page:1})
		//.setCaption("Analisis de Renglon: "+ids) 
		.trigger('reloadGrid');	
	//jQuery("#list_pre_compromisos_d").setGridParam({url:"modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?orden="+getObj('pre_compromisos_co_ordenar').value,page:1}).trigger("reloadGrid"); 
}
//----------------------------------------------------------------------------------------------------------------------------
$("#pre_compromisos_co_btn_consulta_pre_compromisos").click(function() {
if(getObj('pre_compromisos_co_id_unidad').value!="")	
{	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/co/grid_compromiso.php", { },
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
								url:'modulos/presupuesto/comprometer_presupuesto/co/cmb.sql.pre_compromiso.php?nd='+nd+'&unidad='+getObj('pre_compromisos_co_id_unidad').value,
								datatype: "json",
								colNames:['Id','Numero Precompromiso','Asunto'],
								colModel:[
									{name:'id',index:'id', width:60,sortable:false,resizable:false,hidden:true},
									{name:'numero_precompromiso',index:'numero_precompromiso', width:60,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('pre_compromisos_co_id_pre_compromiso').value = ret.id;
									getObj('pre_compromisos_co_nro_pre_compromiso').value = ret.numero_precompromiso;
									dialog.hideAndUnload();
									//alert('modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?pre_compromiso='+getObj('pre_compromisos_co_nro_pre_compromiso').value);
									jQuery("#list_pre_compromisos").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso.php?pre_compromiso='+getObj('pre_compromisos_co_nro_pre_compromiso').value,page:1}).trigger("reloadGrid");
									jQuery("#list_pre_compromisos_d").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?pre_compromiso='+getObj('pre_compromisos_co_nro_pre_compromiso').value,page:1}).trigger("reloadGrid");

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

jQuery("#list_pre_compromisos").jqGrid({
	width: 690,
	height: 90,
   	url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso.php?pre_compromiso='+getObj('pre_compromisos_co_nro_pre_compromiso').value,
	datatype: "json",
   	colNames:['Nro','Descripcion', 'Medida', 'Cantidad', 'Monto', 'Impuesto', 'Total', 'Partidad'],
   	colModel:[
   		{name:'secuencia',index:'secuencia', width:20},
		{name:'descripcion',index:'descripcion', width:200},
   		{name:'medida',index:'medida', width:40},
   		{name:'cantidad',index:'cantidad',align:"right", width:50},
   		{name:'monto',index:'monto',align:"right", width:50},
   		{name:'impuesto',index:'impuesto',align:"right", width:40},
   		{name:'total',index:'total',align:"right", width:70},
   		{name:'partidad',index:'partidad', width:80}	
   	],
   	rowNum:5,
   	rowList:[5,10,15],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_pre_compromisos'),
   	sortname: 'secuencia',
    viewrecords: true,
    sortorder: "desc",
	multiselect: false,
	/*subGrid: true,
	subGridUrl: 'modulos/presupuesto/comprometer_presupuesto/co/cmb.sql.analisis_encabezado.php?q=2&requisicion='+getObj('consulta_analisis_cot_co_id_requisicion').value,
	subGridModel: [{ name  : ['Secuencia','Descripcion','Cantidad','Monto','Total','Partida'], 
					width : [55,250,60,70,70,70] } 
	],*/

//	caption: "Datos de la Requisici&oacute;n",
	
}).navGrid('#pager_pre_compromisos',{add:false,edit:false,del:false,search:false});

jQuery("#list_pre_compromisos_d").jqGrid({
	width: 500,
	height: 90,
   	url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?pre_compromiso='+getObj('pre_compromisos_co_nro_pre_compromiso').value,
	datatype: "json",
   	colNames:['Partidad','Monto','Disponibilida','Diferencia'],
   	colModel:[
   		{name:'partidad',index:'partidad', width:80},
		{name:'monto',index:'monto', width:150,align:"right"},
		{name:'disponibilida',index:'disponibilida', width:150,align:"right"},
		{name:'diferencia',index:'diferencia', width:100,align:"center"}
   	],
   	rowNum:5,
   	rowList:[5,10,15],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_pre_compromisos_d'),
   	sortname: 'partida',
    viewrecords: true,
    sortorder: "desc",
	multiselect: false,
	/*subGrid: true,
	subGridUrl: 'modulos/presupuesto/comprometer_presupuesto/co/cmb.sql.analisis_encabezado.php?q=2&requisicion='+getObj('consulta_analisis_cot_co_id_requisicion').value,
	subGridModel: [{ name  : ['Secuencia','Descripcion','Cantidad','Monto','Total','Partida'], 
					width : [55,250,60,70,70,70] } 
	],*/

//	caption: "Datos de la Requisici&oacute;n",
	
}).navGrid('#pager_pre_compromisos',{add:false,edit:false,del:false,search:false});
/*
jQuery("#list_pre_compromisos_dxx").jqGrid({
	height: 100,
	//width: 900,
   	url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php?pre_compromiso='+getObj('pre_compromisos_co_ordenar').value,
	datatype: "json",
   	colNames:['Partidad','Monto'],
   	colModel:[
   		{name:'partidad',index:'partidad', width:92,align:"right", sortable:false},		
   		{name:'monto',index:'monto', width:88,align:"right", sortable:false}
		
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_pre_compromisos_d'),
   	//sortname: 'total',
    viewrecords: true,
    sortorder: "asc",
	//multiselect: true,
	//caption:"Analisis de Cotizaci&oacute;n"
}).navGrid('#pager_pre_compromisos_d',{add:false,edit:false,del:false, search:false});*/
jQuery("#ms1").click( function() {
	var s;
	s = jQuery("#list_pre_compromisos_d").getMultiRow();
	alert(s);
});

$("#pre_compromisos_co_btn_cancelar").click(function() {
jQuery("#list_pre_compromisos").setGridParam({url:'modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso.php?pre_compromiso=0',page:1}).trigger("reloadGrid");
	clearForm('form_pre_compromisos');
	jQuery("#list_pre_compromisos_d").setGridParam({url:"modulos/presupuesto/comprometer_presupuesto/co/sql_precompromiso_grupo.php",page:1}).trigger("reloadGrid");
	
});

$('#pre_compromisos_co_codigo_unidad').change(consulta_automatica_unidad_ejecutora);
$('#pre_compromisos_co_ordenar').change(ordenar);
</script>
<div id="botonera">
	<img id="pre_compromisos_co_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="pre_compromisos_co_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form name="form_pre_compromisos" id="form_pre_compromisos">
<input type="hidden" name="pre_compromiso_req_select" id="pre_compromiso_req_select" />
<input type="hidden" name="pre_compromiso_secuen" id="pre_compromiso_secuen" />
<table class="cuerpo_formulario">
<tr>
	<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Precompromisos</th>
</tr>
<tr>
	<th>Unidad Solicitante</th>
	<td>
		<table class="clear" style="width:450px">
			<tr>
				<td>
					<input type="hidden" name="pre_compromisos_co_id_unidad" id="pre_compromisos_co_id_unidad" >
					<input type="text" name="pre_compromisos_co_codigo_unidad" id="pre_compromisos_co_codigo_unidad" size="6" maxlength="5"
					message="Introduzca un Codigo para la unidad solicitante." 
					jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}">
					<input type="text" name="pre_compromisos_co_unidad" id="pre_compromisos_co_unidad" size="65" maxlength="100" readonly>
				</td>
				<td style="width:5px"><img id="pre_compromisos_co_btn_consulta_unidad" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<th>Nro Precompromiso </th>
	<td>
		<table class="clear" style="width:90px">
			<tr>
				<td style="width:80px">
				<input type="hidden" name="pre_compromisos_co_id_pre_compromiso" id="pre_compromisos_co_id_pre_compromiso" >
				<input type="text" name="pre_compromisos_co_nro_pre_compromiso" id="pre_compromisos_co_nro_pre_compromiso" size="10" maxlength="9" readonly>	</td>
				<td style="width:5px"><img id="pre_compromisos_co_btn_consulta_pre_compromisos" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
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
				<input type="hidden" name="pre_compromisos_co_id_producto" id="pre_compromisos_co_id_producto" >
				<input type="hidden" name="pre_compromisos_co_secuencia_producto" id="pre_compromisos_co_secuencia_producto" >
				<input type="text" name="pre_compromisos_co_producto" id="pre_compromisos_co_producto" size="10" maxlength="9" readonly>	</td>
				<td style="width:5px"><img id="pre_compromisos_co_btn_consulta_producto" class="btn_consulta_emergente" src="imagenes/null.gif"  /></td>
			</tr>
		</table>
	</td>
</tr>-->
<tr>
	<td class="celda_consulta" colspan="2">
		<table id="list_pre_compromisos" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_pre_compromisos" class="scroll" style="text-align:center;"></div> 
		<br />
	</td>
</tr>
<tr>
	<td colspan="2" >&nbsp;</td>
</tr>
<tr>
	<td class="celda_consulta" colspan="2">
		<table id="list_pre_compromisos_d" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_pre_compromisos_d" class="scroll" style="text-align:center;width:100%" ></div> 
		<br />
	</td>
</tr>
<tr>
	<td colspan="2" class="bottom_frame">&nbsp;</td>
</tr>	

</table>
</form>
<table id="list_pre_compromisos" class="scroll" cellpadding="0" cellspacing="0"></table> 
		<div id="pager_pre_compromisos" class="scroll" style="text-align:center;"></div> 
		<br />
		
<!--<table id="list_pre_compromisos" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager_pre_compromisos" class="scroll" style="text-align:center;"></div>
<br />
Invoice Detail-->
<table id="list_pre_compromisos_d" align="center" class="scroll" cellpadding="0" cellspacing="0"></table>
<div align="center" id="pager_pre_compromisos_d" class="scroll" style="text-align:center;" ></div>

<!--<a href="javascript:void(0)" id="ms1">Get Selected id's</a>-->