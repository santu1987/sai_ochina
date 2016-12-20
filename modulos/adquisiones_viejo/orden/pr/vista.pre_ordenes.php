<script>
var dialog;
$("#ordenes_pr_btn_guardar").click(function() {
if (getObj("ordenes_pr_orden_especial_no").checked  == true){
	getObj("ordenes_pr_especial").value = 0
}else if (getObj("ordenes_pr_orden_especial_si").checked  == true){
	getObj("ordenes_pr_especial").value = 1
}
		setBarraEstado(mensaje[esperando_respuesta]);
	if(getObj("ordenes_pr_nro_cotizacion").value != "") {
		if(getObj("ordenes_pr_reglon_pendiente").value == ""){
			$.ajax (
			{
			url: "modulos/adquisiones/orden/pr/sql.orden.php",
				data:dataForm('form_ordenes'),
				type:'POST',
				cache: false,
				success: function(html)
				{
				resultado = html.split(",");
					if (resultado[0]=="Registrado")
					{
						getObj("ordenes_pr_nro_pre_orden").value=resultado[1];
						//setBarraEstado(mensaje[registro_exitoso],true,true);
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA PREORDEN FUE CREADA CON EXITO,<BR>PREORDEN N&ordm; "+resultado[1]+"</p></div>",true,true);
						
						getObj('ordenes_pr_btn_pdf').style.display='';
						//getObj('ordenes_pr_btn_guardar').style.display='none';
						getObj('ordenes_pr_btn_cancelar').style.display='';
						getObj('ordenes_pr_btn_guardar').style.display='';
						getObj('ordenes_pr_btn_orden').style.display='none';
						getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='';
						getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='';
						getObj('ordenes_pr_btn_consulta_nro_orden').style.display='none';
						//getObj('cotizacones_pr_btn_anadir').style.display='';
						getObj('ordenes_pr_btn_anadir').style.display='';
		
		
						//getObj('ordenes_pr_btn_orden').style.display='';
						jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");
						clearForm('form_ordenes');
						setBarraEstado(html);
						
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
		}else{
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Hay un(os) reglon(es) que no tienen disponibilidad presupuestaria</p></div>",true,true);
		}
	}else{
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Debe seleccionar una Cotizaci&oacute;n</p></div>",true,true);
	}
});
$("#ordenes_pr_btn_orden").click(function() { 
		setBarraEstado(mensaje[esperando_respuesta]); 
	if((getObj("ordenes_pr_nro_cotizacion").value != "")&& (getObj("ordenes_pr_nro_orden").value == "")){
		if(getObj("ordenes_pr_reglon_pendiente").value == ""){
		$.ajax (
		{
		url: "modulos/adquisiones/orden/pr/sql.orden_final.php",
			data:dataForm('form_ordenes'),
			type:'POST', 
			cache: false,
			success: function(html)
			{
	
		
			resultado = html.split(",");
			
				if (resultado[0]=="Registrado")
				{
				//alert(resultado[0]);
					getObj('ordenes_pr_nro_orden').value = resultado[1];
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN FUE CREADA CON EXITO,<BR>ORDEN N&ordm; "+resultado[1]+"</p></div>",true,true);
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
		}else{
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Hay un(os) reglon(es) que no tienen disponibilidad presupuestaria</p></div>",true,true);
		}
	}else if(getObj("ordenes_pr_nro_orden").value != ""){
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Orden ya fue creada</p></div>",true,true);
	}else{
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Debe seleccionar una PreOrden</p></div>",true,true);
	}

});
//----------------------------------------------------------------
$("#ordenes_pr_btn_anadir").click(function() {
		//setBarraEstado(mensaje[esperando_respuesta]);
		if(getObj("ordenes_pr_nro_pre_orden").value ==""){
			url = "modulos/adquisiones/orden/pr/sql.actulizar.php";
		}else if(getObj("ordenes_pr_nro_pre_orden").value !=""){
			url = "modulos/adquisiones/orden/pr/sql.actulizar1.php";
		}//alert(url) ;
		monto1 = getObj("ordenes_pr_monto").value;
		monto1= monto1.replace('.','');
		monto1= eval(monto1.replace(',','.'));
		monto2 = getObj("ordenes_pr_mon").value;
		monto2= monto2.replace('.','');
		monto2= eval(monto2.replace(',','.'));
		
		cantidad1 = getObj("ordenes_pr_cantidad").value;
		cantidad1= eval(cantidad1.replace(",","."));
		cantidad2 = getObj("ordenes_pr_can").value;
		cantidad2= eval(cantidad2.replace(",","."));
		if(cantidad1 <= cantidad2) {
		if( monto1<= monto2){
		//alert(cantidad1+' '+ cantidad2) ;
		$.ajax (
		{
		url: url ,
			data:dataForm('form_ordenes'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					if(getObj("ordenes_pr_nro_pre_orden").value ==""){
						jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad='+getObj("ordenes_pr_id_unidad_ejecutora").value+'&cotizacion='+getObj("ordenes_pr_nro_cotizacion").value,page:1}).trigger("reloadGrid");
					}else{
						jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?unidad='+getObj("ordenes_pr_id_unidad_ejecutora").value+'&cotizacion='+getObj("ordenes_pr_nro_pre_orden").value,page:1}).trigger("reloadGrid");
					}
					getObj("ordenes_pr_producto").value="";
					getObj("ordenes_pr_partida").value="";
					getObj("ordenes_pr_cantidad").value="";
					getObj("ordenes_pr_monto").value="";
					getObj("ordenes_pr_iva").value="";
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_ordenes');
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
		}else{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL MONTO DEBE SER MENOR A LA QUE TIENE ACTUALMENTE</p></div>",true,true);
		}
		}else{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CANTIDA DEBE SER MENOR A LA QUE TIENE ACTUALMENTE</p></div>",true,true);
		}
});
//----------------------------------------------------------------
$("#ordenes_pr_btn_consulta_nro_cotizacion").click(function() {
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
								url:'modulos/adquisiones/orden/pr/cmb.sql.numero_cotizacion.php?nd='+nd,
								datatype: "json",
								colNames:['Cotizaci&oacute;n','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','id_requisicion'],
								colModel:[
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'idrequisicion',index:'idrequisicion', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("ordenes_pr_nro_cotizacion").value=ret.numero;
									getObj("ordenes_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("ordenes_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("ordenes_pr_id_proveedor").value=ret.idproveedor;
									getObj("ordenes_pr_proveedor").value=ret.proveedor;
									getObj("ordenes_pr_id_requisicion").value=ret.idrequisicion;
									getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='none';
									getObj('ordenes_pr_btn_consulta_nro_orden').style.display='none';
									getObj('ordenes_pr_btn_anadir').style.display='none';									
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero,page:1}).trigger("reloadGrid");
									
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
            data:dataForm('form_ordenes'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad='+recordset[3]+'&cotizacion='+getObj("ordenes_pr_nro_cotizacion").value,page:1}).trigger("reloadGrid");
		
					getObj("ordenes_pr_id_unidad_ejecutora").value=recordset[3];
					getObj("ordenes_pr_unidad_ejecutora").value=recordset[4];
					getObj("ordenes_pr_id_proveedor").value=recordset[1];
					getObj("ordenes_pr_proveedor").value=recordset[2];
					getObj("ordenes_pr_id_requisicion").value=recordset[5];	
					getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='none';
					getObj('ordenes_pr_btn_consulta_nro_orden').style.display='none';
					getObj('ordenes_pr_btn_anadir').style.display='';
				}else{
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad=0&cotizacion=0',page:1}).trigger("reloadGrid");
					getObj("ordenes_pr_id_unidad_ejecutora").value="";
					getObj("ordenes_pr_unidad_ejecutora").value="";
					getObj("ordenes_pr_id_proveedor").value="";
					getObj("ordenes_pr_proveedor").value="";
					getObj("ordenes_pr_id_requisicion").value="";	
					getObj('ordenes_pr_btn_anadir').style.display='none';
				}
				
			 }
		});	 	 
}
//-------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------

$("#ordenes_pr_btn_consulta_nro_preorden").click(function() {
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
								url:'modulos/adquisiones/orden/pr/cmb.sql.numero_preorden.php?nd='+nd,
								datatype: "json",
								colNames:['Pre Orden','Cotizacion','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','numero_requisicion','concepto','especial'],
								colModel:[
									{name:'preorden',index:'preorden', width:100,sortable:false,resizable:false},
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false,hidden:true},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'numero_requisicion',index:'numero_requisicion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especial',index:'especial', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("ordenes_pr_nro_pre_orden").value=ret.preorden;
									getObj("ordenes_pr_nro_orden").value="";
									getObj("ordenes_pr_nro_cotizacion").value=ret.numero;
									getObj("ordenes_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("ordenes_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("ordenes_pr_id_proveedor").value=ret.idproveedor;
									getObj("ordenes_pr_proveedor").value=ret.proveedor;
									getObj("ordenes_pr_id_requisicion").value=ret.numero_requisicion;
									getObj("ordenes_pr_concepto").value=ret.concepto;
									//alert(ret.especial);
									if(ret.especial ==1){
										getObj("ordenes_pr_orden_especial_si").checked=true;
									}else{
										getObj("ordenes_pr_orden_especial_no").checked=true;
									}
									getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='none';
									getObj('ordenes_pr_btn_consulta_nro_orden').style.display='none';
									getObj('ordenes_pr_btn_orden').style.display='';
									getObj('ordenes_pr_btn_guardar').style.display='none';
									getObj('ordenes_pr_btn_anadir').style.display='';
									getObj('ordenes_pr_btn_pdf').style.display='';
									
									
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
									
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'numero_pre_orden',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_orden_nro_pre_orden()
{

	$.ajax({
			url:"modulos/adquisiones/orden/pr/sql.grid.numero_preorden.php",
            data:dataForm('form_ordenes'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
					//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad='+recordset[3]+'&cotizacion='+getObj("ordenes_pr_nro_pre_orden").value);
					getObj("ordenes_pr_id_unidad_ejecutora").value=recordset[3];
					getObj("ordenes_pr_unidad_ejecutora").value=recordset[4];
					getObj("ordenes_pr_id_proveedor").value=recordset[1];
					getObj("ordenes_pr_proveedor").value=recordset[2];
					getObj("ordenes_pr_nro_cotizacion").value=recordset[0];
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?unidad='+recordset[3]+'&cotizacion='+getObj("ordenes_pr_nro_pre_orden").value,page:1}).trigger("reloadGrid");
					getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='none';
					getObj('ordenes_pr_btn_consulta_nro_orden').style.display='none';
					getObj('ordenes_pr_btn_orden').style.display='';
					getObj('ordenes_pr_btn_guardar').style.display='none';
					getObj('ordenes_pr_btn_anadir').style.display='none';
					
				}else{
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad=0&cotizacion=0',page:1}).trigger("reloadGrid");
					getObj("ordenes_pr_id_unidad_ejecutora").value="";
					getObj("ordenes_pr_unidad_ejecutora").value="";
					getObj("ordenes_pr_id_proveedor").value="";
					getObj("ordenes_pr_proveedor").value="";
					getObj("ordenes_pr_id_requisicion").value="";	
				}
				
			 }
		});	 	 
}
//-------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------

$("#ordenes_pr_btn_consulta_nro_orden").click(function() {
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
								colNames:['Orden','PreOrden','Cotizaci&oacute;n','idproveedor','Proveedor','id_unidad_ejecutora','Unidad Ejecutora','especial','concepto'],
								colModel:[
									{name:'orden',index:'preorden', width:100,sortable:false,resizable:false},
									{name:'preorden',index:'preorden', width:100,sortable:false,resizable:false,hidden:true},
									{name:'numero',index:'numero', width:100,sortable:false,resizable:false,hidden:true},
									{name:'idproveedor',index:'idproveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proveedor',index:'proveedor', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_ejecutora',index:'unidad_ejecutora', width:100,sortable:false,resizable:false},
									{name:'especial',index:'especial', width:100,sortable:false,resizable:false,hidden:true},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("ordenes_pr_nro_orden").value=ret.orden;
									getObj("ordenes_pr_nro_pre_orden").value=ret.preorden;
									getObj("ordenes_pr_nro_cotizacion").value=ret.numero;
									getObj("ordenes_pr_id_unidad_ejecutora").value=ret.id_unidad_ejecutora;
									getObj("ordenes_pr_unidad_ejecutora").value=ret.unidad_ejecutora;
									getObj("ordenes_pr_id_proveedor").value=ret.idproveedor;
									getObj("ordenes_pr_proveedor").value=ret.proveedor;
									getObj("ordenes_pr_concepto").value=ret.concepto;
									if(ret.especial == 1){
										getObj("ordenes_pr_orden_especial_si").checked  = true;
									}else if(ret.especial == 0){
										getObj("ordenes_pr_orden_especial_no").checked  = true;
									}
									getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='none';
									getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='none';
									getObj('ordenes_pr_btn_anadir').style.display='none'; 
									getObj('ordenes_pr_btn_orden').style.display='none';
									getObj('ordenes_pr_btn_guardar').style.display='none';
									getObj('ordenes_pr_btn_pdf').style.display='';
									dialog.hideAndUnload();
									//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.numero);
									jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?nd='+nd+'&unidad='+ret.id_unidad_ejecutora+'&cotizacion='+ret.preorden,page:1}).trigger("reloadGrid");
									
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'numero_orden_compra_servicio',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_orden_nro_orden()
{

	$.ajax({
			url:"modulos/adquisiones/orden/pr/sql.grid.numero_orden.php",
            data:dataForm('form_ordenes'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
					//alert('modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad='+recordset[3]+'&cotizacion='+getObj("ordenes_pr_nro_pre_orden").value);
					getObj("ordenes_pr_id_unidad_ejecutora").value=recordset[3];
					getObj("ordenes_pr_unidad_ejecutora").value=recordset[4];
					getObj("ordenes_pr_id_proveedor").value=recordset[1];
					getObj("ordenes_pr_proveedor").value=recordset[2];
					getObj("ordenes_pr_nro_cotizacion").value=recordset[0];
					getObj("ordenes_pr_nro_pre_orden").value=recordset[5];
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?unidad='+recordset[5]+'&cotizacion='+recordset[5],page:1}).trigger("reloadGrid");
					getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='none';
					getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='none';
					getObj('cotizacones_pr_btn_anadir').style.display='none';
				}else{
					jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?unidad=0&cotizacion=0',page:1}).trigger("reloadGrid");
					getObj("ordenes_pr_nro_pre_orden").value="";
					getObj("ordenes_pr_id_unidad_ejecutora").value="";
					getObj("ordenes_pr_unidad_ejecutora").value="";
					getObj("ordenes_pr_id_proveedor").value="";
					getObj("ordenes_pr_proveedor").value="";
					getObj("ordenes_pr_id_requisicion").value="";	
				}
				
			 }
		});	 	 
}
//--------------------------------------------------------------------------------------------------
$("#ordenes_pr_btn_partida").click(function() {

if(getObj('ordenes_pr_nro_cotizacion').value !="")
{
	urls='modulos/adquisiones/orden/pr/cmb.sql.partida.php?cotizacion='+getObj('ordenes_pr_nro_cotizacion').value;
		//alert(urls);  
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/orden/pr/grid_orden.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partidas', modal: true,center:false,x:0,y:0,show:false });								
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
								url:urls,
								datatype: "json",
								colNames:['Id','Partida', 'Denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									//getObj('requisiones_pr_accion_especifica_id').value = ret.id;
									getObj('ordenes_pr_partida').value = ret.partida;
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
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//********************************************************************************************

$("#ordenes_pr_btn_cancelar").click(function() {
	jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php',page:1}).trigger("reloadGrid");

	setBarraEstado("");
	getObj('ordenes_pr_btn_cancelar').style.display='';
	getObj('ordenes_pr_btn_guardar').style.display='';
		getObj('ordenes_pr_btn_orden').style.display='none';

	clearForm('form_ordenes');
	getObj('ordenes_pr_tipo_orden').value='3';
	getObj('ordenes_pr_btn_consulta_nro_cotizacion').style.display='';
	getObj('ordenes_pr_btn_consulta_nro_preorden').style.display='';
	getObj('ordenes_pr_btn_consulta_nro_orden').style.display='';
	getObj('cotizacones_pr_btn_anadir').style.display='';
	getObj('ordenes_pr_btn_anadir').style.display='';

});
//-------------------------------------------------------------------------------------------------------------------------
//**************************************************************************************************************************************
var lastsel,idd,monto;
$("#list_preorden").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/adquisiones/orden/pr/cmb.sql.cotizacion_detalle.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','N&ordm; Renglon','Cantidad','Cantidad1','id_unidad_medida','Unidad Medida','Descripcion','Monto','Monto2','Monto Total','Iva','Partida'],
   	colModel:[
	   		{name:'id',index:'id', width:50,hidden:true},
	   		{name:'ordenes_pr_idd',index:'ordenes_pr_idd', width:45},
			{name:'ordenes_pr_cantidad',index:'ordenes_pr_cantidad', width:50},
			{name:'ordenes_pr_can',index:'ordenes_pr_can', width:50,hidden:true},
			{name:'id_unidad_medida',index:'id_unidad_medida', width:200,hidden:true},
			{name:'ordenes_pr_unidad_medida',index:'ordenes_pr_unidad_medida', width:60},
			{name:'ordenes_pr_producto',index:'ordenes_pr_producto', width:200},
			{name:'ordenes_pr_monto',index:'ordenes_pr_monto', width:55,hidden:true},
			{name:'ordenes_pr_mon',index:'ordenes_pr_mon', width:55,hidden:true},
			{name:'monto',index:'monto', width:60},
			{name:'ordenes_pr_iva',index:'ordenes_pr_iva', width:50},
			{name:'ordenes_pr_partida',index:'ordenes_pr_partida', width:55,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_preorden'),
   	sortname: 'id_solicitud_cotizacion',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	//multikey: "ctrlKey",
//	footerrow : true,
//	userDataOnFooter : true,
	onSelectRow: function(id){
		s = jQuery("#list_preorden").getGridParam('selarrrow');
		//alert(s);
		alert('modulos/adquisiones/orden/pr/cmb.sql.disponibilida_presupuesto.php?id='+s);
		$.ajax({
			url:'modulos/adquisiones/orden/pr/cmb.sql.disponibilida_presupuesto.php?id='+s,
            data:dataForm('form_ordenes'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				recordset = recordset.split("*");
				//alert(recordset[0]);
				if (recordset[0] == 1){
				
				getObj("ordenes_pr_reglon_pendiente").value = recordset[1];
//					alert('El articulo del renglon '+recordset[1]+' no tiene disponibilidad presupuestaria');
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL ARTICULO DEL RENGL&Oacute;N "+recordset[1]+" NO TIENE DISPONIBILIDAD PRESUPUESTARIA</p></div>",true,true);
				}
				if (recordset[0] > 1){
				getObj("ordenes_pr_reglon_pendiente").value = recordset[1];
					//alert('Los articulos de los renglones '+recordset[1]+' no tienen disponibilidad presupuestaria');
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LOS ARTICULOS DE LOS RENGLONES "+recordset[1]+" NO TIENEN DISPONIBILIDAD PRESUPUESTARIA</p></div>",true,true);
				}
				setBarraEstado(html);
				/*getObj('cotizacones_pr_id_solicitud').value = recordset[0];
				getObj('cotizacones_pr_secuencia').value = recordset[1];
				getObj('cotizacones_pr_cantidad').value=recordset[2];*/
			 }
		});	
		getObj('ordenes_pr_cot_select').value = s;
		//alert(getObj('ordenes_pr_cot_select').value);
	},
	onSelectAll:function(id){
		s = jQuery("#list_preorden").getGridParam('selarrrow');
		//alert(s);
		$.ajax({
			url:'modulos/adquisiones/orden/pr/cmb.sql.disponibilida_presupuesto.php?id='+s,
            data:dataForm('form_ordenes'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				recordset = recordset.split("*");
				if (recordset[0] == 1){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL ARTICULO DEL RENGL&Oacute;N "+recordset[1]+" NO TIENE DISPONIBILIDAD PRESUPUESTARIA</p></div>",true,true);
				}
				if (recordset[0] > 1){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LOS ARTICULOS DE LOS RENGLONES "+recordset[1]+" NO TIENEN DISPONIBILIDAD PRESUPUESTARIA</p></div>",true,true);
				}
			 }
		});	
		getObj('ordenes_pr_cot_select').value = s;
	}
}).navGrid("#pager_preorden",{search :false,edit:false,add:false,del:false})
.navButtonAdd('#pager_preorden',{caption:"Editar",
	onClickButton:function(){
	if((getObj("ordenes_pr_nro_pre_orden").value != "") && (getObj("ordenes_pr_nro_orden").value == "")){
		var gsr = jQuery("#list_preorden").getGridParam('selrow');
		if(gsr){
			jQuery("#list_preorden").GridToForm(gsr,"#form_ordenes");
		} else {
			alert("Por Seleccione una Linea")
		}
	} else if((getObj("ordenes_pr_nro_pre_orden").value == "") && (getObj("ordenes_pr_nro_orden").value == "")){
			alert("Falta el numero de Preorden");
		}else{
			alert("Solo puede modificar y eliminar Items de la cuando todavia es PreOrden");
		}							
} 
}).navButtonAdd('#pager_preorden',{caption:"Eliminar",
	onClickButton:function(){
	//alert("AQUI"); 
		if((getObj("ordenes_pr_nro_pre_orden").value != "") && (getObj("ordenes_pr_nro_orden").value == "")){
				var gsr = jQuery("#list_preorden").getGridParam('selarrrow');
					//alert(gsr);
				if(gsr){
				if(confirm("¿Desea eliminar este item?")) 
				{
					$.ajax ({
					url: "modulos/adquisiones/orden/pr/sql.eliminar.php?id="+gsr+"&preorden="+getObj('ordenes_pr_nro_pre_orden').value,
						data:dataForm('form_ordenes'),
						type:'GET',
						cache: false,
						success: function(html)
						{
							if (html=="Ok")
							{
									//alert(gsr);
		
								setBarraEstado(mensaje[eliminacion_exitosa],true,true);
								jQuery("#list_preorden").setGridParam({url:'modulos/adquisiones/orden/pr/cmb.sql.orden_detalle.php?unidad='+getObj("ordenes_pr_id_unidad_ejecutora").value+'&cotizacion='+getObj("ordenes_pr_nro_pre_orden").value,page:1}).trigger("reloadGrid");
								//clearForm('form_pr_requisiones');
							}else if (html=="cotizacion_existe")
							{
								setBarraEstado(mensaje[convertirda],true,true);
							}else
							{
								setBarraEstado(html);
							}
						}
					});	
				}
				}else {
					alert("Por Seleccione una Linea");
				}						
		} else if((getObj("ordenes_pr_nro_pre_orden").value == "") && (getObj("ordenes_pr_nro_orden").value == "")){
			alert("Falta el numero de Preorden");
		}else{
			alert("Ya fue covertida en Orden no se muede eliminar el item");
		}	
	}
});


//**************************************************************************************************************************************

$("#ordenes_pr_btn_pdf").click(function() {
	if(getObj('ordenes_pr_nro_orden').value != ""){
		url="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.orden_compra.php¿numero_coti="+getObj('ordenes_pr_nro_orden').value; 
		//alert(getObj('ordenes_pr_cot_select').value);
		openTab("Reporte Orden de Compra",url);
	}else if(getObj('ordenes_pr_nro_pre_orden').value != ""){
		url="pdf.php?p=modulos/adquisiones/orden/rp/vista.lst.pre_orden_compra.php¿numero_coti="+getObj('ordenes_pr_nro_pre_orden').value; 
		//alert(getObj('ordenes_pr_cot_select').value);
		openTab("Reporte Orden de Compra",url);
	}
});
//**************************************************************************************************************************************

$('#ordenes_pr_nro_cotizacion').change(consulta_automatica_orden_nro_cotizacion);
$('#ordenes_pr_nro_pre_orden').change(consulta_automatica_orden_nro_pre_orden);
$('#ordenes_pr_nro_orden').change(consulta_automatica_orden_nro_orden);


</script>

<div id="botonera">
	<img id="ordenes_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="ordenes_pr_btn_pdf" class="btn_imprimir"src="imagenes/null.gif" style="display:none"  />		
	<!--<img id="ordenes_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>-->
	<img id="ordenes_pr_btn_orden" class="btn_orden" src="imagenes/null.gif"    style="display:none" />
	<img id="ordenes_pr_btn_guardar" class="btn_preorden" src="imagenes/null.gif"  />
</div>

<form name="form_ordenes" id="form_ordenes">
<input type="hidden" name="ordenes_pr_id_requisicion" id="ordenes_pr_id_requisicion"  />
<input type="hidden" name="ordenes_pr_cot_select" id="ordenes_pr_cot_select"  />
<input type="hidden" name="ordenes_pr_idd" id="ordenes_pr_idd"  />
<input type="hidden" name="ordenes_pr_especial" id="ordenes_pr_especial"  />
<input type="hidden" name="ordenes_pr_can" id="ordenes_pr_can"  />
<input type="hidden" name="ordenes_pr_mon" id="ordenes_pr_mon"  />
<input type="hidden" name="ordenes_pr_reglon_pendiente" id="ordenes_pr_reglon_pendiente"  />

	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:8px;" align="absmiddle" /><!--Convertir Cotizaci&oacute;n a -->Convertir Orden de Compra/Servicio</th>
		</tr>
		<tr>
			<th>N&ordm; de Cotizaci&oacute;n</th>
			<td>
				<input type="text" name="ordenes_pr_nro_cotizacion" id="ordenes_pr_nro_cotizacion" size="9" maxlength="8" 
				onchange="consulta_automatica_orden_nro_cotizacion" onclick="consulta_automatica_orden_nro_cotizacion"
				message="Introduzca la Número de Cotización."  />
				<img id="ordenes_pr_btn_consulta_nro_cotizacion" class="btn_consulta_emergente" src="imagenes/null.gif"  />
			</td>
		</tr>
		<tr>
			<th>N&ordm; de Pre Orden</th>
			<td>
				<input type="text" name="ordenes_pr_nro_pre_orden" id="ordenes_pr_nro_pre_orden" size="9" maxlength="8"  
				onchange="consulta_automatica_orden_nro_pre_orden" onclick="consulta_automatica_orden_nro_pre_orden"
				message="Introduzca la Número de la PreOrden."   />
				<img id="ordenes_pr_btn_consulta_nro_preorden" class="btn_consulta_emergente" src="imagenes/null.gif"  />
			</td>
		</tr>
		<tr>
			<th>N&ordm; de Orden</th>
			<td>
				<input type="text" name="ordenes_pr_nro_orden" id="ordenes_pr_nro_orden" size="9" maxlength="8"  
				onchange="consulta_automatica_orden_nro_orden" onclick="consulta_automatica_orden_nro_orden"
				message="Introduzca la Número de la Orden."   />
				<img id="ordenes_pr_btn_consulta_nro_orden" class="btn_consulta_emergente" src="imagenes/null.gif"  />
			</td>
		</tr>
		<tr>
			<th>Tipo de Orden</th>
			<td>
				<select name="ordenes_pr_tipo_orden" id="ordenes_pr_tipo_orden" style="min-width:95px; width:95px;">
					<option value="3">Compra</option>
					<option value="4">Servicio</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Orden Especial</th>
			<td>
				SI&nbsp;<input type="radio" name="ordenes_pr_orden_especial" id="ordenes_pr_orden_especial_si" value="1" />&nbsp;&nbsp;&nbsp;
				NO&nbsp;<input type="radio" name="ordenes_pr_orden_especial" id="ordenes_pr_orden_especial_no" value="0" checked="checked" />&nbsp;
			</td>
		</tr>
		<tr>
			<th>Unidad Ejecutora</th>
			<td>
				<input type="hidden" name="ordenes_pr_id_unidad_ejecutora" id="ordenes_pr_id_unidad_ejecutora"/>
				<input type="text"   name="ordenes_pr_unidad_ejecutora"    id="ordenes_pr_unidad_ejecutora"   size="100" maxlength="100" />
			</td>
		</tr>
		<tr>
			<th>Proveedor</th>
			<td>
				<input type="hidden" name="ordenes_pr_id_proveedor" id="ordenes_pr_id_proveedor"/>
				<input type="text"   name="ordenes_pr_proveedor"    id="ordenes_pr_proveedor"   size="100" maxlength="100" />
			</td>
		</tr>
		<tr>
			<th>Concepto</th>
			<td>
				<input type="text"   name="ordenes_pr_concepto"    id="ordenes_pr_concepto"  message="Introduzca el Concepto de la Orden."  size="100" maxlength="100" />
			</td>
		</tr>
		<tr>
			<th>Comentarios</th>
			<td>
				<textarea name="ordenes_pr_comentraios" id="ordenes_pr_comentraios" cols="97"></textarea>
			</td>
		</tr>
		<tr>
			<th colspan="2" bgcolor="#4c7595">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="2">
				<table class="clear" width="100%" border="0">
					<tr>
						<th>Descripcion</th>
						<td colspan="4">
							<input name="ordenes_pr_producto"  id="ordenes_pr_producto" type="text" readonly
							message="Nombre del Producto/Servicio" size="70"
							jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789]{2,120}$/, message:'Producto/Servicio Invalido', styleType:'cover'}"
							jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789]/, cFunc:'alert', cArgs:['Producto/Servicio: '+$(this).val()]}">
						</td>
						<td width="20%" rowspan="3" align="center">
						<img style="vertical-align:middle" id="ordenes_pr_btn_anadir" src="imagenes/actuliza40.png"   /></td>
						
					</tr>
					 <tr>
						<th width="20%"style="text-align:center;">Partida</th>
						<th width="20%"style="text-align:center;">Cantidad</th>
						<th width="20%"style="text-align:center;">Monto</th>
						<th width="20%"style="text-align:center;">Valor Impuesto</th>
				  </tr>
				   <tr>
						<td width="20%"align="center">
							<input type="text" name="ordenes_pr_partida" id="ordenes_pr_partida"
							message="Introduzca la partida." maxlength="12" size="12" readonly />
							<!--<img id="ordenes_pr_btn_partida" class="btn_consulta_emergente" src="imagenes/null.gif"  />-->
						</td>
						<td width="20%" align="center">
							<input type="text" name="ordenes_pr_cantidad" id="ordenes_pr_cantidad"
							message="Introduzca la cantidad del producto." maxlength="8" size="10"
							jval="{valid:/^[0123456789]{1,10}$/, message:'Producto Invalido', styleType:'cover'}"
							jvalkey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['Producto: '+$(this).val()]}" />        </td>
						<td width="20%" align="center"><input name="ordenes_pr_monto" type="text" id="ordenes_pr_monto" style="text-align:right" size="15" alt="signed-decimal"/></td>
						<td width="20%" align="center"><input name="ordenes_pr_iva" readonly type="text" id="ordenes_pr_iva" style="text-align:right" size="6" maxlength="5" alt="signed-decimal-im"/></td>
				  </tr>
				</table>
			</th>
		</tr>
		<tr>
			<td class="celda_consulta" colspan="2">
				<table id="list_preorden" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_preorden" class="scroll" style="text-align:center;"></div> 
				<br />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>	
	</table>
</form>
	