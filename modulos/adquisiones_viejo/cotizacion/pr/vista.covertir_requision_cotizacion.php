<? if (!$_SESSION) session_start();?>
<script>
var dialog;
$("#covertir_req_cot_db_btn_imprimir").click(function() {
//alert(getObj('covertir_numero_cotizacion').value);
	if(getObj('covertir_numero_cotizacion').value != ""){
		url="pdf.php?p=modulos/adquisiones/cotizacion/rp/vista.lst.solicitudcotizacion.php¿ano="+getObj('covertir_req_cot_ano').value+"@numero_coti="+getObj('covertir_numero_cotizacion').value; 
		//alert(url);
		openTab("Cotizacion Lista",url);
	}
});
$("#covertir_req_cot_db_btn_guardar").click(function() {
	//alert(getObj('covertir_req_select').value);
	if($("#form_covertir_req_cot").jVal())
	{	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/adquisiones/cotizacion/pr/sql.covertir_requision_cotizacion.php",
			data:dataForm('form_covertir_req_cot'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			html = html.split("*");
				if (html[0]=="Registrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png />LA OPERACION SE REGISTRO CON EXITO<br>EL NUMERO DE LA COTIZACION ES "+html[1]+"</p></div>",true,true);
					//clearForm('form_covertir_req_cot');
					getObj('convertir_req_cot_proveedor_db_codigo').value = "";
					getObj('covertir_req_cot_proveedor').value = "";
					getObj('covertir_req_cot_titulo').value = "";
					
					
					jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+getObj('covertir_req_cot_nro_requi').value,page:1}).trigger("reloadGrid");
				}
				else if (html[0]=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					//setBarraEstado(mensaje[registro_existe]);
				}
				else
				{
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});

//-----------------------------------------------------------------------------------------------------------------------------------------
$("#covertir_req_cot_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/sql_grid_convertir_requisicion_cotizacion.php?nd='+nd,
								datatype: "json",
								colNames:['Numero Cotizacion','Ano','id_unidad_ejecutora','codigo_unidad_ejecutora','Unidad Ejecutora','id_proveedor','codigo_proveedor','Proveedor','comentario','id_requisicion','Numero Requisicion'],
								colModel:[
									{name:'numero_cotizacion',index:'numero_cotizacion', width:150,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_requi',index:'id_requi', width:100,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('covertir_req_cot_ano').value=ret.ano;
								    getObj('covertir_req_cot_unidad_ejecutora_id').value=ret.id_unidad_ejecutora;
									getObj('convertir_req_cot_unidad_ejecutora_db_codigo').value=ret.codigo_unidad_ejecutora;
									getObj('covertir_req_cot_unidad_ejecutora').value=ret.unidad_ejecutora;
									getObj('covertir_req_cot_proveedor_id').value = ret.id_proveedor;
									getObj('convertir_req_cot_proveedor_db_codigo').value = ret.codigo_proveedor;
									getObj('covertir_req_cot_proveedor').value = ret.proveedor;
									getObj('covertir_req_cot_titulo').value=ret.comentario;
//									getObj('covertir_req_cot_nro_requi').value=ret.numero_cotizacion;
									getObj('covertir_req_n_cot').value=ret.numero_cotizacion;
									getObj('covertir_req_cot_nro_requi_id').value=ret.id_requi;
									getObj('covertir_req_cot_nro_requi').value=ret.numero;
									getObj('covertir_numero_cotizacion').value = ret.numero_cotizacion;
									//seleccionar_itmes(ret.numero_cotizacion,ret.numero);

									//alert('modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad='+ret.id_unidad_ejecutora+'&requisicion='+ret.numero_cotizacion);
									jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad='+ret.id_unidad_ejecutora+'&requisicion='+ret.numero+'&cotizacion='+ret.numero_cotizacion,page:1}).trigger("reloadGrid");
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad='+ret.id_unidad_ejecutora+'&requisicion='+ret.numero_cotizacion);
									getObj('covertir_req_cot_db_btn_actualizar').style.display='';
									getObj('covertir_req_cot_db_btn_cancelar').style.display='';
									getObj('covertir_req_cot_db_btn_guardar').style.display='none';		
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}); 
//-------------------------------------------------------------------------------------------------------------------------------
$("#covertir_req_cot_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
//alert('aqui');
if($('#form_covertir_req_cot').jVal())
	{
		$.ajax (
		{
			url:'modulos/adquisiones/cotizacion/pr/sql.actualizar_cotizacion.php',
			data:dataForm('form_covertir_req_cot'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					clearForm('form_covertir_req_cot');
					getObj('covertir_req_cot_db_btn_actualizar').style.display='none';
					getObj('covertir_req_cot_db_btn_guardar').style.display='';
					getObj('covertir_req_cot_db_btn_cancelar').style.display='';
					jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad=0&requisicion=0',page:1}).trigger("reloadGrid");
														
					}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					setBarraEstado("");
					clearForm('form_covertir_req_cot');
					getObj('covertir_req_cot_db_btn_actualizar').style.display='none';
					getObj('covertir_req_cot_db_btn_guardar').style.display='';
					getObj('covertir_req_cot_db_btn_cancelar').style.display='';
					jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad=0&requisicion=0',page:1}).trigger("reloadGrid");

				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
//----------------------------------------------------------------------------------------------------------------------------

$("#covertir_req_cot_pr_btn_consultar_unidad_ejecutora").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.unidad_ejecutora.php?nd='+nd+'&ano='+getObj('covertir_req_cot_ano').value,
								datatype: "json",
								colNames:['Id','Codigo', 'Unidad Ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:200,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('covertir_req_cot_unidad_ejecutora_id').value = ret.id;
									getObj('convertir_req_cot_unidad_ejecutora_db_codigo').value = ret.codigo;
									getObj('covertir_req_cot_unidad_ejecutora').value = ret.nombre;
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

// -----------------------------------------------------------------------------------------------------------------------------------
$("#covertir_req_cot_pr_btn_consultar_nro_requi").click(function() {
if(getObj('covertir_req_cot_unidad_ejecutora_id').value!="")	
{	
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
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
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion.php?nd='+nd+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value,
								datatype: "json",
								colNames:['Numero Requisicion','Asunto'],
								colModel:[
									{name:'numero_requisicion',index:'numero_requisicion', width:60,sortable:false,resizable:false},
									{name:'asunto',index:'asunto', width:150,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('covertir_req_cot_nro_requi').value = ret.numero_requisicion;
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion);
									jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?nd='+nd+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+ret.numero_requisicion,page:1}).trigger("reloadGrid");

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
function consulta_automatica_requiscion()
{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_auto.php",
            data:dataForm('form_covertir_req_cot'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset){
					jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+getObj('covertir_req_cot_nro_requi').value,page:1}).trigger("reloadGrid");
					getObj('covertir_req_cot_nro_requi_id').value = recordset;
				}else{  
					getObj('covertir_req_cot_nro_requi').value ="";
					getObj('covertir_req_cot_nro_requi_id').value="";
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA REQUISICION NO EXISTE</p></div>",true,true);
				}
				
			 }
		});	 	 
}
// -----------------------------------------------------------------------------------------------------------------------------------
$("#covertir_req_cot_pr_btn_consultar_proveedor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/cotizacion/pr/grid_cotizacion.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/cotizacion/pr/cmb.sql.proveedor.php?nd='+nd+'&nro_requi='+getObj('covertir_req_cot_nro_requi').value,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('covertir_req_cot_proveedor_id').value = ret.id_proveedor;
									getObj('convertir_req_cot_proveedor_db_codigo').value = ret.codigo;
									getObj('covertir_req_cot_proveedor').value = ret.nombre;
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
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/sql_grid_unidad_ejecutora_codigo.php",
            data:dataForm('form_covertir_req_cot'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('covertir_req_cot_unidad_ejecutora').value = recordset[1];
				getObj('covertir_req_cot_unidad_ejecutora_id').value=recordset[0];
				
					}
				else
			 {  
			   	getObj('covertir_req_cot_unidad_ejecutora').value ="";
				getObj('covertir_req_cot_unidad_ejecutora_id').value="";
			   
				}
				
			 }
		});	 	 
}
//--------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proveedor()
{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/sql_grid_proveedor_codigo.php",
            data:dataForm('form_covertir_req_cot'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('covertir_req_cot_proveedor').value = recordset[1];
				getObj('covertir_req_cot_proveedor_id').value=recordset[0];
				
					}
				else
			 {  
			   	getObj('covertir_req_cot_proveedor').value = "";
				getObj('covertir_req_cot_proveedor_id').value="";
			
				}
				
			 }
		});	 	 
}

//**************************************************************************************************************************************
var lastsel,idd,monto;

$("#list_cotizacion_covercion").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?nd='+new Date().getTime()+'&unidad='+getObj('covertir_req_cot_unidad_ejecutora_id').value+'&requisicion='+getObj('covertir_req_cot_nro_requi').value,
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','id_unidad_medida','Unidad Medida','Descripcion','cotizado'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
	   		{name:'n_renglon',index:'n_renglon', width:50},
			{name:'cantidad',index:'cantidad', width:80},
			{name:'id_unidad_medida',index:'id_unidad_medida', width:200,hidden:true},
			{name:'nombre',index:'nombre', width:100},
			{name:'descripcion',index:'descripcion', width:200},
			{name:'cotizado',index:'cotizado', width:200,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_cotizaciones_covercion'),
   	sortname: 'id_solicitud_cotizacion',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	onSelectRow: function(id){
		s = jQuery("#list_cotizacion_covercion").getGridParam('selarrrow');
		var ret = jQuery("#list_cotizacion_covercion").getRowData(id);
			//getObj('covertir_numero_cotizacion').value = ret.cotizado;
		//alert(ret.cotizado);
		getObj('covertir_req_select').value = s;
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
	},
	loadComplete: function(){
		var ids = jQuery("#list_cotizacion_covercion").getDataIDs();
		xo = '';
		for(var i=0;i<ids.length;i++){
			var cl = ids[i];
			var ret = jQuery("#list_cotizacion_covercion").getRowData(cl);
			if(ret.cotizado == 't'){
				jQuery("#list_cotizacion_covercion").setSelection(cl);
				if (xo ==''){
					xo = cl;
					//alert(xo);
				}else{
					xo = xo +','+cl;
					//alert(xo);
				}
			}
		}
		getObj('covertir_req_select').value = xo;
	},
	onSelectAll:function(id){
		s = jQuery("#list_cotizacion_covercion").getGridParam('selarrrow');
		//alert(s);
		getObj('covertir_req_select').value = s;
	}
}).navGrid("#pager_cotizaciones_covercion",{search :false,edit:false,add:false,del:false});

////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

		
/*function seleccionar_itmes(coti,requi)
{
	$.ajax({
			url:"modulos/adquisiones/cotizacion/pr/cmb.sql.cotizacion_select.php?cotizacion="+coti+"&requisicion="requi,
            data:dataForm('form_covertir_req_cot'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				getObj('covertir_numero_seleccionar').value = recordset;
				}
			 }
		});	 	 
}		*/

//////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º

//**************************************************************************************************************************************
$("#covertir_req_cot_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('covertir_req_cot_db_btn_guardar').style.display='';
	getObj('covertir_req_cot_db_btn_actualizar').style.display='none';
	getObj('covertir_req_cot_db_btn_consultar').style.display='';
	jQuery("#list_cotizacion_covercion").setGridParam({url:'modulos/adquisiones/cotizacion/pr/cmb.sql.requisicion_detalle.php?unidad=0&requisicion=0',page:1}).trigger("reloadGrid");
	clearForm('form_covertir_req_cot');
	});


//-------------------------------------------------------------------------------------------------------------------------------
$('#convertir_req_cot_unidad_ejecutora_db_codigo').change(consulta_automatica_unidad_ejecutora);
$('#convertir_req_cot_proveedor_db_codigo').change(consulta_automatica_proveedor);
$('#covertir_req_cot_nro_requi').change(consulta_automatica_requiscion);

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="covertir_req_cot_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="covertir_req_cot_db_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"/>
	<img id="covertir_req_cot_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
	<img id="covertir_req_cot_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="covertir_req_cot_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form name="form_covertir_req_cot" id="form_covertir_req_cot">
<input type="hidden" name="covertir_req_select" id="covertir_req_select" />
<input type="hidden" name="covertir_numero_cotizacion" id="covertir_numero_cotizacion" />
	<table class="cuerpo_formulario">
		<tr>
		  <th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Convertir Requisici&oacute;n Cotizaci&oacute;n
			<input type="hidden" name="covertir_req_n_cot" id="covertir_req_n_cot" /></th>
		</tr>
		<tr>
			<th>A&ntilde;o
</th>
			<td>
				<select name="covertir_req_cot_ano" id="covertir_req_cot_ano"  style="min-width:60px; width:60px;">
					<option value="<?=date('Y')-1?>"><?=date('Y')-1?></option>
					<option value="<?=date('Y')?>" selected="selected"><?=date('Y')?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Unidad Solicitante </th>
			<td>
				<table  width="100%" class="clear">
					<tr>
						<td style="width:390px">
							<input name="convertir_req_cot_unidad_ejecutora_db_codigo" type="text" id="convertir_req_cot_unidad_ejecutora_db_codigo"  
							value=""  size="5" maxlength="4"  		
							onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
							message="Introduzca un Codigo para la unidad solicitante." 
							jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}" />
									
							<input type="text" name="covertir_req_cot_unidad_ejecutora" id="covertir_req_cot_unidad_ejecutora" size="60"
							message="Introduzca el nombre de la unidad solicitante." />
						</td>
						<td>
								<input type="hidden" name="covertir_req_cot_unidad_ejecutora_id" id="covertir_req_cot_unidad_ejecutora_id" />
								<img class="btn_consulta_emergente" id="covertir_req_cot_pr_btn_consultar_unidad_ejecutora" src="imagenes/null.gif" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>N&ordm; Requisici&oacute;n</th>
			<td>
				<ul class="input_con_emergente">
				<li>
				  <input type="text" name="covertir_req_cot_nro_requi" id="covertir_req_cot_nro_requi" size="10"
				message="Introduzca el N&ordm; Requisicion." onclick="consulta_automatica_requiscion" onchange="consulta_automatica_requiscion"
				jVal="{valid:/^[0123456789.]{1,10}$/, message:'Número de requisición invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" />
				<input type="hidden" name="covertir_req_cot_nro_requi_id" id="covertir_req_cot_nro_requi_id" readonly />
				</li>
					<li id="covertir_req_cot_pr_btn_consultar_nro_requi" class="btn_consulta_emergente"></li>
				</ul>
			</td>
		</tr>
		<tr>
			<th>Proveedor</th>
			<td>
			<table  width="100%" class="clear">
					<tr>
						<td style="width:390px">
							<input name="convertir_req_cot_proveedor_db_codigo" type="text" id="convertir_req_cot_proveedor_db_codigo"  maxlength="4"
							onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
							message="Introduzca un Codigo para el proveedor."  size="5"
							jVal="{valid:/^[0-9]{4}$/, message:'Codigo Invalido', styleType:'cover'}"
							jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" >
				
							<input type="text" name="covertir_req_cot_proveedor" id="covertir_req_cot_proveedor" size="60"
							message="Introduzca el nombre del Proveedor." readonly />
						</td>
						<td>
							<input type="hidden" name="covertir_req_cot_proveedor_id" id="covertir_req_cot_proveedor_id" readonly />
							<img class="btn_consulta_emergente" id="covertir_req_cot_pr_btn_consultar_proveedor" src="imagenes/null.gif" />
						</td>
					</tr>
				</table>
				</td>
		</tr>
		<tr>
			<th>Comentario</th>
			<td>
				<textarea name="covertir_req_cot_titulo" id="covertir_req_cot_titulo"  cols="65" rows="2"  message="Introduzca un comentario para la cotizacion."></textarea>
			</td>
		</tr>
		<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_cotizacion_covercion" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_cotizaciones_covercion" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		<tr>
	</table>
</form>