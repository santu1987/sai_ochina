<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY tipo_documento_cxp.nombre";
	$rs_tipos_doc =& $conn->Execute($sql);
	while (!$rs_tipos_doc->EOF) {
		$opt_tipos_doc.="<option value='".$rs_tipos_doc->fields("id_tipo_documento")."' >".$rs_tipos_doc->fields("nombre")."</option>";
		$rs_tipos_doc->MoveNext();
	}?>
<script type='text/javascript'>
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#cuentas_por_pagar_db_reporte_proveedor_orden_btn_consultar_proveedor").click(function() {
getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value="";
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?nd='+nd,
								//url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
							
							
								datatype: "json",
								colNames:['Id','Codigo','Proveedor','rif','ret_iva','ret_islr'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false},
									{name:'ret_iva',index:'ret_iva', width:100,sortable:false,resizable:false},
									{name:'ret_islr',index:'ret_islr', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_db_reporte_orden_proveedor_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value = ret.nombre;
									rif=ret.rif;		
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
									dialog.hideAndUnload();
									jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_reporte_tipo').value,page:1}).trigger("reloadGrid");
			    					//url2='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_reporte_tipo').value;
									//setBarraEstado(url2);
									//alert(url2);
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
//--------------------------------------------------------------------------------------------------------------------------------------
//consultas automaticas
function consulta_automatica_proveedor_orden_reporte()
{
	$.ajax({
			url:'modulos/cuentas_por_pagar/orden_pago/rp/sql_grid_proveedor_codigo.php',
            data:dataForm('form_cuentas_por_pagar_db_reporte_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		   	var recordset=html;	
				alert(html);
				if(html=="vacio")
				{
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value="";
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value="";
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_codigo').value="";
				}
				else
				if(recordset)
				{
					recordset = recordset.split("*");
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value = recordset[0];
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value = recordset[1];
					rif=recordset[2];
					rif2 = rif.split("-");
					getObj('cuentas_por_pagar_db_reporte_orden_proveedor_rif').value=rif2[0];
				}
				else
				 {  
			   	getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value="";
				getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value="";
				getObj('cuentas_por_pagar_db_reporte_orden_proveedor_codigo').value="";
				}
				
			 }
		});	 	 
}
function consulta_automatica_benef_codigo_reporte()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/orden_pago/rp/slq_grid_beneficiario_codigo_cxp.php",
			data:dataForm('form_cuentas_por_pagar_db_reporte_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('cuentas_por_pagar_db_empleado_codigo').value = recordset[1];
				getObj('cuentas_por_pagar_orden_db_reporte_empleado_nombre').value=recordset[1];
									}
				else
			 {  
				getObj('cuentas_por_pagar_orden_db_reporte_empleado_nombre').value="";
				}
				
			 }
		});	 	 
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//consultas automaticas
function consulta_automatica_orden_reporte()
{var rif,fechas;
if(('cuentas_por_pagar_db_reporte_orden_numero_control').value!=" ")
{$.ajax({	url:'modulos/cuentas_por_pagar/orden_pago/rp/sql_grid_orden_codigo.php?busq_ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value,
            data:dataForm('form_cuentas_por_pagar_db_reporte_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;		
				recordset = recordset.split("*");
			    if((html!="")||(html!=null)||(html!="undefined")||(html!="A"))
				{var recordset=html;
						 if(recordset)
						{
									recordset = recordset.split("*");
										opcion=(recordset[12]);
										getObj('cuentas_por_pagar_orden_db_reporte_tipo').value=recordset[13];
										if(opcion=='1')
										{
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value = recordset[3];
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_codigo').value = recordset[4];
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value = recordset[5];
										rif=recordset[6];
										rif2 = rif.split("-");
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_rif').value=rif2[0];
									
										}else
										if(opcion=='2')
										{
										getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value = recordset[4];
										getObj('cuentas_por_pagar_orden_db_reporte_empleado_nombre').value = recordset[5];
										jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?beneficiario='+getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value,page:1}).trigger("reloadGrid");
										url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?beneficiario='+getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value;
										//setBarraEstado(url);
										}
										getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value=recordset[7];
										fechas=recordset[10],	
										fd=fechas.substr(0, 10);
										fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
										getObj('cuentas_por_pagar_db_reporte_orden_fecha_v').value=fds;
										documentos=recordset[2];
										documentos1=documentos.replace("{","");
										getObj('cuentas_por_pagar_db_reporte_facturas_oculto').value=documentos1.replace("}","");
									///	getObj('cuentas_por_pagar_db_orden_numero_control').value = recordset[1];
									//	jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
						//////////////////////////////////////////////////////
										getObj('cuentas_por_pagar_db_reporte_orden_btn_cancelar').style.display='';
										getObj('cuentas_por_pagar_db_reporte_orden_btn_imprimir').style.display='';
					///////////////////////////////////////////////////////
										
						}
						
						else 
						 {  
							limpiar_orden_reporte();
							}
						
				 }
				 
		    }
	});	
}		 	 
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function limpiar_orden_reporte()
{
	setBarraEstado("");
	clearForm('form_cuentas_por_pagar_db_reporte_orden_pago');
	getObj('cuentas_por_pagar_db_reporte_orden_btn_imprimir').style.display='';
	getObj('tr_proveedor_orden_cxp').style.display=none;
	getObj('cuentas_por_pagar_db_reporte_orden_fecha_v').value="<?=  date("d/m/Y"); ?>";	
	getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value="<?= date("Y"); ?>";
	<!--getObj('tr_empleado_orden_cxp').style.display='none';-->
	<!--getObj('tr_proveedor_orden_cxp').style.display='';-->
	getObj('cuentas_por_pagar_orden_db_reporte_tipo').value=0;
}

$("#cuentas_por_pagar_db_reporte_orden_btn_cancelar").click(function() {
	limpiar_orden_reporte();

});
$("#cuentas_por_pagar_db_reporte_orden_btn_imprimir").click(function() {
	
if(getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value!="")
{
		//url="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php";
		if(getObj('cuentas_por_pagar_orden_db_reporte_op_oculto').value=='1')
		{
			url="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php¿orden="+getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value+"@ano="+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value; 
		}
		else
			if(getObj('cuentas_por_pagar_orden_db_reporte_op_oculto').value=='2')
		{
			url="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php¿orden="+getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value+"@ano="+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value;  
		}
		//alert(url);
		//setBarraEstado(url);
		openTab("Orden pago",url);
		//limpiar_orden_reporte();
}		
});
//-------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_orden_db_reporte_btn_consultar_beneficiario").click(function() {

		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
			$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Beneficiario'],
								colModel:[
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false},
									{name:'beneficiario',index:'beneficiario', width:100,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value = ret.rif;
									getObj('cuentas_por_pagar_orden_db_reporte_empleado_nombre').value = ret.beneficiario;
									jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_reporte_tipo').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_reporte_tipo').value;
									//setBarraEstado(url);
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
$("#cuentas_por_pagar_db_reporte_orden_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/orden_pago/db/vista.grid_fecha_orden.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Ordenes de Pago', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor_orden= jQuery("#cuentas_por_pagar_db_reporte_orden_proveedor_consulta").val(); 
					var busq_fecha_orden= jQuery("#cuentas_por_pagar_db_orden_fecha_consulta").val(); 
					var busq_opcion= jQuery("#cuentas_por_pagar_orden_db_reporte_op_oculto").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion,page:1}).trigger("reloadGrid"); 
					url="modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion;
					
				}	
			
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
			
				$("#cuentas_por_pagar_db_reporte_orden_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_orden_dosearch();
												
					});
				$("#cuentas_por_pagar_db_orden_fecha_consulta").focus(function()
				{
					//if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_orden_rp_dosearch();
												
					});
						function consulta_orden_rp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_orden_rp_gridReload,500)
										}
						function consulta_orden_rp_gridReload()
						{
							var busq_proveedor_orden= jQuery("#cuentas_por_pagar_db_reporte_orden_proveedor_consulta").val(); 
							var busq_fecha_orden= jQuery("#cuentas_por_pagar_db_orden_fecha_consulta").val(); 
							var busq_opcion= jQuery("#cuentas_por_pagar_orden_db_reporte_op_oculto").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion,page:1}).trigger("reloadGrid"); 

		//					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion;
							//setBarraEstado(url);
					url="modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion;
						}

			}
		});
///////////////////////////////////////////////////////////////////////////////////////////////////
	/*	var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/orden_pago/db/grid_pago.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
/*////////////////////////////////////////////////////////////////////////////////////////////////////						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/orden_pago/rp/cmb.sql.proveedor_orden.php?nd='+nd+"&busq_ano="+getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value,
								
								//url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
							
							
								datatype: "json",
								colNames:['id_orden','Orden de Pago','Facturas','id_proveedor','Codigo','Proveedor','rif','ano','fecha_1','comentarios','Fecha','estatus','opcion','tipo'],
								colModel:[
									{name:'id_orden',index:'id_orden', width:100,sortable:false,resizable:false,hidden:true},
									{name:'orden',index:'orden', width:150,sortable:false,resizable:false},
									{name:'documentos',index:'documentos', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha1',index:'fecha1', width:100,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false,hidden:true},
									{name:'opcion',index:'opcion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true}
							
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_reporte_orden_abrir_cerrar').value=ret.estatus;							rif=ret.rif;
									rif2 = rif.split("-");
//									getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
									getObj('cuentas_por_pagar_db_reporte_ayo_orden_pago').value=ret.ano;
									fechas=ret.fecha,	
     								fd=fechas.substr(0, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									getObj('cuentas_por_pagar_db_reporte_orden_fecha_v').value=fds;
								//	getObj('cuentas_por_pagar_db_reporte_ordenes_comentarios').value=ret.comentarios;
									documentos=ret.documentos;
									documentos1=documentos.replace("{","");
									getObj('cuentas_por_pagar_db_reporte_facturas_oculto').value=documentos1.replace("}","");
									getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value = ret.orden;
									getObj('cuentas_por_pagar_orden_db_reporte_tipo').value=ret.tipo;
									if(ret.opcion=='1')
									{
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_id').value = ret.id_proveedor;
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_codigo').value = ret.codigo;
										getObj('cuentas_por_pagar_db_reporte_orden_proveedor_nombre').value = ret.nombre;
									}
										else
									if(ret.opcion=='2')
									{
										getObj('cuentas_por_pagar_orden_db_reporte_empleado_codigo').value=ret.codigo;
										getObj('cuentas_por_pagar_orden_db_reporte_empleado_nombre').value=ret.nombre
									}	
///////////////////////////////////////////////////////
							//		jQuery("#").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
//
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

//--------------------------------------------------------------------------------------------------

$("#cuentas_por_pagar_db_reporte_orden_btn_cancelar").click(function() {
	limpiar_orden_reporte();

});
$('#cuentas_por_pagar_orden_db_reporte_empleado_codigo').blur(consulta_automatica_benef_codigo_reporte)
$('#cuentas_por_pagar_db_reporte_orden_proveedor_codigo').blur(consulta_automatica_proveedor_orden_reporte);
$('#cuentas_por_pagar_db_reporte_orden_numero_control').blur(consulta_automatica_orden_reporte);
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#cuentas_por_pagar_db_reporte_orden_numero_control').numeric({allow:'-'});
$('#cuentas_por_pagar_db_reporte_orden_proveedor_codigo').numeric({});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("#cuentas_por_pagar_orden_db_reporte_radio1").click(function(){
		getObj('cuentas_por_pagar_orden_db_reporte_op_oculto').value="1"
		getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value=""
	});
$("#cuentas_por_pagar_orden_db_reporte_radio2").click(function(){
		getObj('cuentas_por_pagar_orden_db_reporte_op_oculto').value="2"
		getObj('cuentas_por_pagar_db_reporte_orden_numero_control').value=""
	});
	

	
</script>
   <div id="botonera">
   	<img id="cuentas_por_pagar_db_reporte_orden_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="cuentas_por_pagar_db_reporte_orden_btn_imprimir"  class="btn_imprimir" src="imagenes/null.gif"  />

	</div>
<form method="post" id="form_cuentas_por_pagar_db_reporte_orden_pago" name="form_cuentas_por_pagar_db_reporte_orden_pago">
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Orden de Pago </th>
	</tr><tr>
		<th>
		  A&ntilde;o :	  </th>
	  <td>
		  <select  name="cuentas_por_pagar_db_reporte_ayo_orden_pago" id="cuentas_por_pagar_db_reporte_ayo_orden_pago" >
				  <?
					$anio_inicio=date("Y");
					$anio_fin=date("Y")+1;
					while($anio_inicio <= $anio_fin)
					{
					?>
				  <option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
				  <?
						$anio_inicio++;
					}
					?>
	    </select>	  </td>
	</tr>
	    <th>Orden de Pago: </th>
	 	    <td>
				<ul class="input_con_emergente">
				<li>
		    	<input name="cuentas_por_pagar_db_reporte_orden_numero_control" type="text" id="cuentas_por_pagar_db_reporte_orden_numero_control"   value="" size="5" maxlength="5" message="Ingrese el Numero de control"  onblur="consulta_automatica_orden_reporte"  
				jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		    	<input type="hidden"  id="cuentas_por_pagar_db_reporte_orden_numero_control_oculto" name="cuentas_por_pagar_db_reporte_orden_numero_control_oculto"/>
				</li> 
					<li id="cuentas_por_pagar_db_reporte_orden_btn_consultar" class="btn_consulta_emergente"></li>
				</ul>				</td>	
<tr style="display:none">
<th style="display:none">Beneficiario</th>
	  <td><label>
	     <input name="cuentas_por_pagar_orden_db_reporte_radio" type="radio" id="cuentas_por_pagar_orden_db_reporte_radio1" onClick="getObj('tr_empleado_orden_cxp').style.display='none'; getObj('tr_proveedor_orden_cxp').style.display='';" value="1" checked="CHECKED"/>
	    Prooveedor</label>
	    &nbsp;&nbsp;
	    <label>
          <input name="cuentas_por_pagar_orden_db_reporte_radio" type="radio" id="cuentas_por_pagar_orden_db_reporte_radio2"  onclick="getObj('tr_empleado_orden_cxp').style.display=''; getObj('tr_proveedor_orden_cxp').style.display='none';" value="0" />
      Empleado</label></br>
      <input type="hidden" name="cuentas_por_pagar_orden_db_reporte_op_oculto" id="cuentas_por_pagar_orden_db_reporte_op_oculto" value="1" /></td>
</tr>
<tr style="display:none">
<th>Tipo de Documento </th>
<td>
<select id="cuentas_por_pagar_orden_db_reporte_tipo" name="cuentas_por_pagar_orden_db_reporte_tipo">
	<option value="0">---- SELECCIONE -----</option>
					<?= $opt_tipos_doc; ?>
</select></td>
</tr>
<tr id="tr_proveedor_orden_cxp" style="display:none">
		<th>Proveedor:</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="cuentas_por_pagar_db_reporte_orden_proveedor_codigo" type="text" id="cuentas_por_pagar_db_reporte_orden_proveedor_codigo"  
				message="Introduzca un Codigo para el proveedor."  size="5" maxlength="6"
				jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
				
	
				<input type="text" name="cuentas_por_pagar_db_reporte_orden_proveedor_nombre" id="cuentas_por_pagar_db_reporte_orden_proveedor_nombre" size="45"
				message="Introduzca el nombre del Proveedor." readonly />
				<input type="hidden" name="cuentas_por_pagar_db_reporte_orden_proveedor_id" id="cuentas_por_pagar_db_reporte_orden_proveedor_id" readonly />
				<input type="hidden" name="cuentas_por_pagar_db_reporte_orden_proveedor_rif" id="cuentas_por_pagar_db_reporte_orden_proveedor_rif" readonly />
				</li> 
					<li id="cuentas_por_pagar_db_reporte_proveedor_orden_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>				</td>		
	</tr>
<tr id="tr_empleado_orden_cxp"  style="display:none">
<th>Empleado</th>
      <td >		<ul class="input_con_emergente">
	  <li><input name="cuentas_por_pagar_orden_db_reporte_empleado_codigo" type="text" id="cuentas_por_pagar_orden_db_reporte_empleado_codigo"
				 onchange="consulta_automatica_benef_codigo_reporte" onBlur="consulta_automatica_benef_codigo_reporte"  size="5"  maxlength="5" 
				message="Introduzca un Codigo para el Empleado."
				jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Código Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}" 
				/>
	    <input name="cuentas_por_pagar_orden_db_reporte_empleado_nombre" type="text" id="cuentas_por_pagar_orden_db_reporte_empleado_nombre" size="45" maxlength="60"
				message="Introduzca el nombre del Empleado." />
		  <label>		    </label>
	     
	      <input type="hidden" name="textprue3" id="textprue3" />
	  </li> 
	  		<li id="cuentas_por_pagar_orden_db_reporte_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>	
</tr>		
<tr style="display:none">		
<th>Fecha :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_db_reporte_orden_fecha_v" id="cuentas_por_pagar_db_reporte_orden_fecha_v" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha : '+$(this).val()]}"/>
	      
	      <button type="reset" id="cuentas_por_pagar_db_reporte_ordenes_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_db_reporte_orden_fecha_v",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_db_reporte_ordenes_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cuentas_por_pagar_db_reporte_orden_fecha_v").value.MMDDAAAA() );
						}
					});
			</script>
			
	      <input type="hidden" name="cuentas_por_pagar_db_reporte_facturas_oculto" id="cuentas_por_pagar_db_reporte_facturas_oculto" readonly=""/>
	      <input type="hidden" name="cuentas_por_pagar_db_reporte_orden_abrir_cerrar" id="cuentas_por_pagar_db_reporte_orden_abrir_cerrar" readonly="" value="1"/>
	      </label></td>
	</tr>
	<tr>
		
    </tr>
   <tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="cuentas_por_pagar_db_reporte_numero_compromiso" type="hidden" id="cuentas_por_pagar_db_reporte_numero_compromiso" />
</form>