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
$("#cuentas_por_pagar_db_orden_btn_eliminar").click(function() {
if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value==2)
{
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN SE ENCUENTRA CERRADO</p></div>",true,true);

}else
if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value!=2)
{
    Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea realmente anular la siguiente orden?</p></div>", ["ACEPTAR","CANCELAR"], 
	function(val)
	 {
		if(val=='ACEPTAR')
		{

				/*if(confirm("¿Desea anular el registro seleccionado?")) 
				{*/
					$.ajax (
					{
						url:'modulos/cuentas_por_pagar/orden_pago/db/sql_eliminar.orden.php',
						data:dataForm('form_cuentas_por_pagar_db_orden_pago'),
						type:'POST',
						cache: false,
						success: function(html)
						{
						
							if (html=="Eliminado")
							{
							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN FUE ANULADA CON EXITO</p></div>",true,true);
							setBarraEstado("");
							limpiar_orden();
							getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
							getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
							}
							else
							if(html=="orden_cheque")
							{
							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN CUENTA CON CHEQUE</p></div>",true,true);
			
							}else
							if(html=="orden_cerrado")
							{
							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ORDEN SE ENCUENTRA CERRADA</p></div>",true,true);
			
							}
							else
											if (html=="cerrado")
											{
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
											}	
							else
							{
								// setBarraEstado(html);
								setBarraEstado(mensaje[relacion_existe],true,true);
								
							}
						}
					});
				}
			});	
}
});
//-----------------------------------------------------------------------------------------------------------------------------------------

$("#cuentas_por_pagar_db_orden_btn_actualizar").click(function() {
if((getObj('cuentas_por_pagar_db_facturas_oculto').value!="")&&(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value!="2"))
{
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/orden_pago/db/sql.actualizar_orden.php',
			data:dataForm('form_cuentas_por_pagar_db_orden_pago'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
					getObj('cuentas_por_pagar_db_facturas_oculto').value="";
					limpiar_orden();

				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado(mensaje[registro_existe],true,true);
					//getObj('tesoreria_cheque_db_btn_eliminar').style.display='none';
				/*	limpiar();
					getObj('tesoreria_cheques_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheque_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheque_db_btn_guardar').style.display='';
					getObj('tesoreria_cheque_db_btn_cancelar').style.display='';
					getObj('tesoreria_cheques_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
		*/	}
		else
								if (html=="cerrados")
								{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
								}	
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
}else
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE ACTUALIZAR ESTE REGISTRO</p></div>",true,true);
});
$("#cuentas_por_pagar_db_orden_btn_abrir").click(function() {
if(getObj('cuentas_por_pagar_db_facturas_oculto').value!="")
{
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value="1";
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/orden_pago/db/sql_abrir_cerrar_orden.php',
			data:dataForm('form_cuentas_por_pagar_db_orden_pago'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />SE APERTUR&Oacute; LA ORDEN </p></div>",true,true);
					getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='';
					getObj('cuentas_por_pagar_db_orden_estado').value="ABIERTA"
					//jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
					//	getObj('cuentas_por_pagar_db_facturas_oculto').value="";
					//limpiar_orden();

				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACION</p></div>",true,true);
					//setBarraEstado(mensaje[registro_existe],true,true);
					//getObj('tesoreria_cheque_db_btn_eliminar').style.display='none';
				/*	limpiar();
					getObj('tesoreria_cheques_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheque_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheque_db_btn_guardar').style.display='';
					getObj('tesoreria_cheque_db_btn_cancelar').style.display='';
					getObj('tesoreria_cheques_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
		*/	}
			else if(html=="cheque")
				{
						getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value='2';
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N EL REGISTRO SE ENCUENTRA ELACIONADO CON TESORERIA</p></div>",true,true);
						getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
						getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';

					//	limpiar_orden();
						//jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
						
				}
		
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
}else
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE GUARDAR EL REGISTRO SIN HABER ELEGIDO AL MENOS UN DOCUMENTO</p></div>",true,true);
});
$("#cuentas_por_pagar_db_orden_btn_cerrar").click(function() {
if(getObj('cuentas_por_pagar_db_facturas_oculto').value!="")
{
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value="2";
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/orden_pago/db/sql_cerrar_orden.php',
			data:dataForm('form_cuentas_por_pagar_db_orden_pago'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					//setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />ORDEN DE PAGO CERRADA</p></div>",true,true);
					//jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
					//getObj('cuentas_por_pagar_db_facturas_oculto').value="";
					getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value='2';
					getObj('cuentas_por_pagar_db_orden_estado').value="CERRADA"
					getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='';
					getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';

					//limpiar_orden();

				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N</p></div>",true,true);
//					setBarraEstado(mensaje[registro_existe],true,true);
					//getObj('tesoreria_cheque_db_btn_eliminar').style.display='none';
				/*	limpiar();
					getObj('tesoreria_cheques_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheque_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheque_db_btn_guardar').style.display='';
					getObj('tesoreria_cheque_db_btn_cancelar').style.display='';
					getObj('tesoreria_cheques_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
		*/	}else
			if(html=="compromiso_no_cierre")
			{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N SIN N&Uacute;MERO DE COMPROMISO</p></div>",true,true);

			}
			else
			if(html=='doc_no')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N SI UN DOCUMENTO DE ESTA ORDEN NO SE ENCUENTRA CERRADO</p></div>",true,true);
				}
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
}else
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE GUARDAR EL REGISTRO SIN HABER ELEGIDO AL MENOS UN DOCUMENTO</p></div>",true,true);
});

$("#cuentas_por_pagar_db_orden_btn_guardar").click(function() {
if(getObj('cuentas_por_pagar_db_facturas_oculto').value!="")
{
	Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Desea registrar la siguiente orden de pago?</p></div>", ["ACEPTAR","CANCELAR"], 
	function(val)
	{
	if(val=='ACEPTAR')
	{	
					setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url:'modulos/cuentas_por_pagar/orden_pago/db/sql.orden.php',
						data:dataForm('form_cuentas_por_pagar_db_orden_pago'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							recordset=html.split("*");
							if (recordset[0]=="Registrado")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REGISTRO EXITOSO ORDEN DE PAGO,<BR>ORDEN N&ordm; "+recordset[1]+"</p></div>",true,true);
								jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
								getObj('cuentas_por_pagar_db_facturas_oculto').value="";
								limpiar_orden();
			
							}
							else if (recordset[0]=="NoRegistro")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REGISTR&Oacute; LA ORDEN</p></div>",true,true);
			
								//alert("La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras");
								//jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
								//getObj('cuentas_por_pagar_db_facturas_oculto').value="";
								//limpiar_orden();
			
							}
							else
								if (html=="cerrados")
								{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
								}	
							
								else
							{
								alert(recordset[0]);
								setBarraEstado(recordset[0]);
								jQuery("#list_factura").setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php"}).trigger("reloadGrid");
								getObj('cuentas_por_pagar_db_facturas_oculto').value="";
								limpiar_orden();
						}
						
						}
					});
	}				
	});
}else
	//alert("No puede guardar el registro sin haber elegido por lo menos un documento");	
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE GUARDAR EL REGISTRO SIN HABER ELEGIDO AL MENOS UN DOCUMENTO</p></div>",true,true);

});

$("#cuentas_por_pagar_db_orden_btn_consultar").click(function() {
url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?nd='+nd+"&busq_ano="+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;				
//alert(url);

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
					var busq_proveedor_orden= jQuery("#cuentas_por_pagar_db_orden_proveedor_consulta").val(); 
					var busq_fecha_orden= jQuery("#cuentas_por_pagar_db_orden_fecha_consulta").val(); 
					var busq_opcion= jQuery("#cuentas_por_pagar_orden_db_op_oculto").val(); 
					var busq_ano= jQuery("#cuentas_por_pagar_db_ayo_orden_pago").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion+"&busq_ano="+busq_ano,page:1}).trigger("reloadGrid"); 
					url="modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion+"&busq_ano="+busq_ano;
					//alert(url);
				}	
			
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
			
				$("#cuentas_por_pagar_db_orden_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_orden_dosearch();
												
					});
					$("#cuentas_por_pagar_db_orden_pago").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_orden_dosearch();
												
					});
					
				$("#cuentas_por_pagar_db_orden_fecha_consulta").focus(function()
				{
					//if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_orden_dosearch();
												
					});
						function consulta_orden_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_orden_gridReload,500)
										}
						function consulta_orden_gridReload()
						{
							var busq_proveedor_orden= jQuery("#cuentas_por_pagar_db_orden_proveedor_consulta").val(); 
							var busq_fecha_orden= jQuery("#cuentas_por_pagar_db_orden_fecha_consulta").val(); 
							var busq_opcion= jQuery("#cuentas_por_pagar_orden_db_op_oculto").val(); 
							var busq_ano= jQuery("#cuentas_por_pagar_db_ayo_orden_pago").val(); 
							var busq_orden= jQuery("#cuentas_por_pagar_db_orden_pago").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion+"&busq_ano="+busq_ano+"&busq_orden="+busq_orden,page:1}).trigger("reloadGrid"); 
		//					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?busq_proveedor_orden="+busq_proveedor_orden+"&busq_fecha_orden="+busq_fecha_orden+"&opcion="+busq_opcion+"&busq_ano="+busq_ano+"&busq_orden="+busq_orden;
						//alert(url);
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
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.proveedor_orden.php?nd='+nd+"&busq_ano="+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
								
								//url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
							
							
								datatype: "json",
								colNames:['id_orden','Orden','Facturas','id_proveedor','Codigo','Proveedor','rif','ano','fecha_1','comentarios','Fecha','estatus','opcion','tipo','nombre'],
								colModel:[
									{name:'id_orden',index:'id_orden', width:50,sortable:false,resizable:false,hidden:true},
									{name:'orden',index:'orden', width:100,sortable:false,resizable:false},
									{name:'documentos',index:'documentos', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre2',index:'nombre2', width:200,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha1',index:'fecha1', width:150,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'opcion',index:'opcion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false,hidden:true}

								
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=ret.estatus;
									rif=ret.rif;
									rif2 = rif.split("-");
//									getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
									getObj('cuentas_por_pagar_db_ayo_orden_pago').value=ret.ano;
									fechas=ret.fecha,	
     								fd=fechas.substr(0, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
							
									getObj('cuentas_por_pagar_db_orden_fecha_v').value=fds;
									getObj('cuentas_por_pagar_db_ordenes_comentarios').value=ret.comentarios;
									documentos=ret.documentos;
									documentos1=documentos.replace("{","");
									getObj('cuentas_por_pagar_db_facturas_oculto').value=documentos1.replace("}","");
									getObj('cuentas_por_pagar_db_orden_numero_control').value = ret.orden;
									getObj('cuentas_por_pagar_orden_db_tipo').value=ret.tipo;
									if(ret.opcion=='1')
									{
										getObj('cuentas_por_pagar_db_orden_proveedor_id').value = ret.id_proveedor;
										getObj('cuentas_por_pagar_db_orden_proveedor_codigo').value = ret.codigo;
										getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value = ret.nombre;
										jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");

									}
										else
									if(ret.opcion=='2')
									{
										
										getObj('cuentas_por_pagar_orden_db_empleado_codigo').value=ret.codigo;
										getObj('cuentas_por_pagar_orden_db_empleado_nombre').value=ret.nombre
										jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
										url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value;
									//	setBarraEstado(url);
									}	
//////////////////////////////////////////////////////
						if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=='2')
						{
							getObj('cuentas_por_pagar_db_orden_estado').value="CERRADA"
							getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
							getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='';
						}
						if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=='1')
						{
							getObj('cuentas_por_pagar_db_orden_estado').value="ABIERTA"
							getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
							getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='';
						}
								
								getObj('cuentas_por_pagar_db_orden_btn_imprimir').style.display='';
								getObj('cuentas_por_pagar_db_orden_btn_cancelar').style.display='';
								getObj('cuentas_por_pagar_db_orden_btn_actualizar').style.display='';
								getObj('cuentas_por_pagar_db_orden_btn_guardar').style.display='none';									
								getObj('cuentas_por_pagar_db_orden_btn_eliminar').style.display='';
							
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
$("#cuentas_por_pagar_db_proveedor_orden_btn_consultar_proveedor").click(function() {
/*getObj('cuentas_por_pagar_db_orden_numero_control').value="";
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
getObj('cuentas_por_pagar_db_orden_numero_control').value="";
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De proveedores Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagar_db_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#cuentas_por_pagar_db_codigo_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
						function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
										}
						function consulta_doc_gridReload()
						{
							var busq_nom= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
							var busq_cod= jQuery("#cuentas_por_pagar_db_codigo_proveedor_consulta").val(); 

							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_nom="+busq_nom+"&busq_cod="+busq_cod,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_nom="+busq_nom+"&busq_cod="+busq_cod;
							//alert(url);
						}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
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
								colNames:['Id','C&oacute;digo','Proveedor','rif','ret_iva','ret_islr'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ret_iva',index:'ret_iva', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ret_islr',index:'ret_islr', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_orden_proveedor_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_db_orden_proveedor_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value = ret.nombre;
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
									dialog.hideAndUnload();
									jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");
									urls='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value;
								//	alert(urls);
			    					//url2='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value;
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
function consulta_automatica_proveedor_orden()
{ 
	$.ajax({
			url:'modulos/cuentas_por_pagar/orden_pago/db/sql_grid_proveedor_codigo.php',
            data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		   	var recordset=html;	
				if(html=="vacio")
				{
					getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value="";
					getObj('cuentas_por_pagar_db_orden_proveedor_id').value="";
					getObj('cuentas_por_pagar_db_orden_proveedor_codigo').value="";
				}
				else
				if(recordset)
				{
					recordset = recordset.split("*");
					getObj('cuentas_por_pagar_db_orden_proveedor_id').value = recordset[0];
					getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value = recordset[1];
					rif=recordset[2];
					rif2 = rif.split("-");
					getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
					jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");

					//jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");
			    	// setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value);
				}
				else
			 {  
			   	getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value="";
				getObj('cuentas_por_pagar_db_orden_proveedor_id').value="";
				getObj('cuentas_por_pagar_db_orden_proveedor_codigo').value="";
				}
				
			 }
		});	 	 
}
function consulta_automatica_benef_codigo()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/orden_pago/db/sql_grid_beneficiario_codigo_cxp.php",
			data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('cuentas_por_pagar_db_empleado_codigo').value = recordset[1];
				getObj('cuentas_por_pagar_orden_db_empleado_nombre').value=recordset[1];
				jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");
				url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value;
					}
				else
			 {  
				getObj('cuentas_por_pagar_orden_db_empleado_nombre').value="";
				}
				
			 }
		});	 	 
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//consultas automaticas
function consulta_automatica_orden()
{var rif,fechas;
if(('cuentas_por_pagar_db_orden_numero_control').value!=" ")
{
	$.ajax({
			url:'modulos/cuentas_por_pagar/orden_pago/db/sql_grid_orden_codigo.php?busq_ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
            data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);		
				recordset = recordset.split("*");
			
			    if((html!="")||(html!=null)||(html!="undefined")||(html!="A"))

				{      var recordset=html;

						 if(recordset)
						{
									recordset = recordset.split("*");
										opcion=(recordset[12]);
										getObj('cuentas_por_pagar_orden_db_tipo').value=recordset[13];
										if(opcion=='1')
										{
										getObj('cuentas_por_pagar_db_orden_proveedor_id').value = recordset[3];
										getObj('cuentas_por_pagar_db_orden_proveedor_codigo').value = recordset[4];
										getObj('cuentas_por_pagar_db_orden_proveedor_nombre').value = recordset[5];
										rif=recordset[6];
										rif2 = rif.split("-");
										getObj('cuentas_por_pagar_db_orden_proveedor_rif').value=rif2[0];
									
										jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
										}else
										if(opcion=='2')
										{
										getObj('cuentas_por_pagar_orden_db_empleado_codigo').value = recordset[4];
										getObj('cuentas_por_pagar_orden_db_empleado_nombre').value = recordset[5];
										jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
										url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value;
										//setBarraEstado(url);
										}
										getObj('cuentas_por_pagar_db_ayo_orden_pago').value=recordset[7];
										fechas=recordset[10],	
										fd=fechas.substr(0, 10);
										fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
								
										getObj('cuentas_por_pagar_db_orden_fecha_v').value=fds;
										getObj('cuentas_por_pagar_db_ordenes_comentarios').value=recordset[9];
										documentos=recordset[2];
										documentos1=documentos.replace("{","");
										getObj('cuentas_por_pagar_db_facturas_oculto').value=documentos1.replace("}","");
									///	getObj('cuentas_por_pagar_db_orden_numero_control').value = recordset[1];
									//	jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&orden='+getObj('cuentas_por_pagar_db_orden_numero_control').value,page:1}).trigger("reloadGrid");
						//////////////////////////////////////////////////////
										getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=recordset[11];
										if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=='2')
										{
											getObj('cuentas_por_pagar_db_orden_estado').value="CERRADA"
											getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
											getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='';
										}
										if(getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value=='1')
										{
											getObj('cuentas_por_pagar_db_orden_estado').value="ABIERTA"
											getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
											getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='';
										}
										getObj('cuentas_por_pagar_db_orden_btn_cancelar').style.display='';
										getObj('cuentas_por_pagar_db_orden_btn_actualizar').style.display='';
										getObj('cuentas_por_pagar_db_orden_btn_guardar').style.display='none';									
										getObj('cuentas_por_pagar_db_orden_btn_eliminar').style.display='';
										getObj('cuentas_por_pagar_db_orden_btn_imprimir').style.display='';

					///////////////////////////////////////////////////////
										
						}
						
						else 
						 {  
							limpiar_orden();
							}
						
				 }
				 
		    }
	});	
}		 	 
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function limpiar_orden()
{
	setBarraEstado("");
	getObj('cuentas_por_pagar_db_facturas_total').value="0,00";
	clearForm('form_cuentas_por_pagar_db_orden_pago');
	getObj('cuentas_por_pagar_db_orden_btn_eliminar').style.display='none';	
	getObj('cuentas_por_pagar_db_orden_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_guardar').style.display='';
	getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_imprimir').style.display='none';
	getObj('cuentas_por_pagar_db_facturas_total').value="0,00";
	getObj('cuentas_por_pagar_db_orden_fecha_v').value="<?=  date("d/m/Y"); ?>";	
	getObj('cuentas_por_pagar_db_ayo_orden_pago').value="<?= date("Y"); ?>";
	jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php'}).trigger("reloadGrid");
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value="";
	getObj('cuentas_por_pagar_db_orden_estado').value="ABIERTA";
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value='1';
	getObj('cuentas_por_pagar_orden_db_op_oculto').value='1';
	getObj('cuentas_por_pagar_orden_db_radio1').checked="checked";
	getObj('tr_empleado_orden_cxp').style.display='none';
	getObj('tr_proveedor_orden_cxp').style.display='';
	getObj('cuentas_por_pagar_orden_db_tipo').value=0;
}
function limpiar_orden2()
{
	setBarraEstado("");
	getObj('cuentas_por_pagar_db_facturas_total').value="0,00";
	variable=getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	clearForm('form_cuentas_por_pagar_db_orden_pago');
	getObj('cuentas_por_pagar_db_ayo_orden_pago').value=variable;
	getObj('cuentas_por_pagar_db_orden_btn_eliminar').style.display='none';	
	getObj('cuentas_por_pagar_db_orden_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_guardar').style.display='';
	getObj('cuentas_por_pagar_db_orden_btn_abrir').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_cerrar').style.display='none';
	getObj('cuentas_por_pagar_db_orden_btn_imprimir').style.display='none';
	getObj('cuentas_por_pagar_db_facturas_total').value="0,00";
	getObj('cuentas_por_pagar_db_orden_fecha_v').value="<?=  date("d/m/Y"); ?>";	
	getObj('cuentas_por_pagar_db_ayo_orden_pago').value="<?= date("Y"); ?>";
	jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php'}).trigger("reloadGrid");
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value="";
	getObj('cuentas_por_pagar_db_orden_estado').value="ABIERTA";
	getObj('cuentas_por_pagar_db_orden_abrir_cerrar').value='1';
	getObj('cuentas_por_pagar_orden_db_op_oculto').value='1';
	getObj('cuentas_por_pagar_orden_db_radio1').checked="checked";
	getObj('tr_empleado_orden_cxp').style.display='none';
	getObj('tr_proveedor_orden_cxp').style.display='';
	getObj('cuentas_por_pagar_orden_db_tipo').value=0;
	getObj('cuentas_por_pagar_db_ayo_orden_pago').value=variable;

}

$("#cuentas_por_pagar_db_orden_btn_cancelar").click(function() {
	limpiar_orden();

});
$("#cuentas_por_pagar_db_orden_btn_imprimir").click(function() {
	if($('#').jVal())
	{

		//url="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php";
		if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='1')
		{
			url2="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php¿prove="+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+"@orden="+getObj('cuentas_por_pagar_db_orden_numero_control').value+"@opcion="+getObj('cuentas_por_pagar_orden_db_op_oculto').value; 
			
		}
		else
			if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='2')
		{
			url2="pdf.php?p=modulos/cuentas_por_pagar/orden_pago/rp/vista.lst.orden_pago.php¿prove="+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+"@orden="+getObj('cuentas_por_pagar_db_orden_numero_control').value+"@opcion="+getObj('cuentas_por_pagar_orden_db_op_oculto').value;  
		}
	//	alert(url2);
		//setBarraEstado(url);
		openTab("Orden pago",url2);
		//limpiar_orden();
	}	
});
//-------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_orden_db_btn_consultar_beneficiario").click(function() {

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
								colNames:['C&oacute;digo','Beneficiario'],
								colModel:[
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false},
									{name:'beneficiario',index:'beneficiario', width:100,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_orden_db_empleado_codigo').value = ret.rif;
									getObj('cuentas_por_pagar_orden_db_empleado_nombre').value = ret.beneficiario;
									jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+nd+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value;
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
//--------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
$("#list_factura").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
	datatype: "json",
		colNames:['&ordm;Id documentos','Año','Tipo Doc','N doc','N control','Fecha.V.','Monto.B.','Base imp','%IVA','%RET.IVA','%RET.ISLR','NComp','desc documento','Tipo doc','Total Fact'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:40,sortable:false,resizable:false},
									{name:'numero_documento',index:'numero_documento', width:40,sortable:false,resizable:false},
									{name:'numero_control',index:'numero_control', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha_vencimiento',index:'fecha_vencimiento', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto_bruto',index:'monto_bruto', width:50,sortable:false,resizable:false},
									{name:'base_imponible',index:'base_imponible', width:50,sortable:false,resizable:false},
									{name:'porcentaje_iva',index:'porcentaje_iva', width:30,sortable:false,resizable:false},
									{name:'porcentaje_ret_iva',index:'porcentaje_ret_iva', width:60,sortable:false,resizable:false},
									{name:'porcentaje_ret_islr',index:'porcentaje_ret_islr', width:60,sortable:false,resizable:false},
									{name:'numero_compromiso',index:'numero_compromiso', width:40,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:60,sortable:false,resizable:false	},
									{name:'total',index:'total', width:60,sortable:false,resizable:false}
								],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_factura'),
   	sortname: 'Id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	gridComplete:function(){
	   vector=getObj('cuentas_por_pagar_db_facturas_oculto').value;
							if(vector!="")
							{
								vector2=vector.split(",");
							
									
									i=0;//&&(getObj('tesoreria_cheques_db_n_precheque').value!="")
									if((vector2!=""))
									{										
											
											while((i<vector2.length))
											{
													//alert(vector2[i]);
													
													jQuery("#list_factura").setSelection(vector2[i]);
													i=i+1;		
												}
									}			
							}	
},
	onSelectRow: function(id){
        var ret = jQuery("#list_factura").getRowData(id);
	   	s = jQuery("#list_factura").getGridParam('selarrrow');
	
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='1')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	}else
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='2')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	
	}//alert(urls);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('cuentas_por_pagar_db_facturas_oculto').value=s;
				$.ajax({
					url:urls,
					data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						
						var recordset=html.split("*");	
					//setBarraEstado(html);			
						valor=parseFloat(recordset[0]);
						valor = valor.currency(2,',','.');	
						getObj('cuentas_por_pagar_db_facturas_total').value=valor;
						if(recordset[1]!=null)
							getObj('cuentas_por_pagar_db_numero_compromiso').value=recordset[1];
						else
							getObj('cuentas_por_pagar_db_numero_compromiso').value="";
					}
					});	 
				
		}
	 		
	},
onSelectAll: function(id){
        var ret = jQuery("#list_factura").getRowData(id);
	   	s = jQuery("#list_factura").getGridParam('selarrrow');
	idd="";
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='1')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	}else
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='2')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	
	}//setBarraEstado(urls);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('cuentas_por_pagar_db_facturas_oculto').value=s;
				$.ajax({
					url:urls,
					data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						
						var recordset=html.split("*");	
									
						valor=parseFloat(recordset[0]);
						valor = valor.currency(2,',','.');	
						getObj('cuentas_por_pagar_db_facturas_total').value=valor;
						if(recordset[1]!=null)
							getObj('cuentas_por_pagar_db_numero_compromiso').value=recordset[1];
						else
							getObj('cuentas_por_pagar_db_numero_compromiso').value="";
					}
					});	 
				
		}
	 		
	},

}).navGrid("#pager_factura",{refresh:false,search :false,edit:false,add:false,del:false});
function valores_combo()
{
	if(getObj('cuentas_por_pagar_db_orden_numero_control').value!='')
	{
		limpiar_orden();
	}
		jQuery("#list_factura").setGridParam({url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value,page:1}).trigger("reloadGrid");
url='modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value+'&tipo='+getObj('cuentas_por_pagar_orden_db_tipo').value;
//alert(url);
}
/*var lastsel,idd,monto;
$("#list_orden_pago").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value,
	datatype: "json",
		colNames:['&ordm;Id','Orden.Pago','Fecha','Base Imp.','%IVA','%Ret.IVA','Total.IVA','%ISLR','Total.ISLR','Monto.Pagar'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'n_orden',index:'n_orden', width:50},
			{name:'fecha',index:'fecha', width:50},
			{name:'base_imponible',index:'base_imponible', width:50},
			{name:'iva1',index:'iva1', width:40},
			{name:'ret_iva',index:'ret_iva', width:40},
			{name:'total_iva',index:'total_iva', width:40},
			{name:'islr1',index:'islr1', width:40},
			{name:'islr',index:'islr', width:50},
			{name:'monto',index:'monto', width:55}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_orden'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true
	}).navGrid("#pager_cotizaciones",{search :false,edit:false,add:false,del:false});*/

///--------------------------------------------------------------------------------------------------

//$('#cuentas_por_pagar_orden_db_empleado_codigo').change(consulta_automatica_benef_codigo)
//$('#cuentas_por_pagar_db_orden_proveedor_codigo').blur(consulta_automatica_proveedor_orden);
//$('#cuentas_por_pagar_db_orden_numero_control').blur(consulta_automatica_orden);
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#cuentas_por_pagar_db_orden_numero_control').numeric({allow:'-'});
$('#cuentas_por_pagar_db_orden_proveedor_codigo').numeric({});
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
	$("#cuentas_por_pagar_orden_db_radio1").click(function(){
		getObj('cuentas_por_pagar_orden_db_op_oculto').value="1"
		getObj('cuentas_por_pagar_db_orden_numero_control').value=""
	});
$("#cuentas_por_pagar_orden_db_radio2").click(function(){
		getObj('cuentas_por_pagar_orden_db_op_oculto').value="2"
		getObj('cuentas_por_pagar_db_orden_numero_control').value=""
	});
	

	
</script>
   <div id="botonera"><img id="cuentas_por_pagar_db_orden_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
   <img id="cuentas_por_pagar_db_orden_btn_eliminar" class="btn_anular"src="imagenes/null.gif" style="display:none"/>
	<img id="cuentas_por_pagar_db_orden_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>
	<img id="cuentas_por_pagar_db_orden_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="cuentas_por_pagar_db_orden_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png" style="display:none" />
	<img id="cuentas_por_pagar_db_orden_btn_cerrar" src="imagenes/iconos/cerrar_orden_cxp.png" style="display:none"/>
	<img id="cuentas_por_pagar_db_orden_btn_imprimir"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" /></div>
	</div>
<form method="post" id="form_cuentas_por_pagar_db_orden_pago" name="form_cuentas_por_pagar_db_orden_pago">
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Orden de Pago </th>
	</tr><tr>
		<th>
		  A&ntilde;o:	  </th>
	  <td>
		  <select  name="cuentas_por_pagar_db_ayo_orden_pago" id="cuentas_por_pagar_db_ayo_orden_pago" onchange="limpiar_orden2();" >
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
		    	<input name="cuentas_por_pagar_db_orden_numero_control" type="text" id="cuentas_por_pagar_db_orden_numero_control"   value="" size="5" maxlength="5" message="Ingrese el Numero de control"  onblur="consulta_automatica_orden()" onchange="consulta_automatica_orden()"  
				jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		    	<input type="hidden"  id="cuentas_por_pagar_db_orden_numero_control_oculto" name="cuentas_por_pagar_db_orden_numero_control_oculto"/>	
				</li> 
					<li id="cuentas_por_pagar_db_orden_btn_consultar" class="btn_consulta_emergente"></li>
				</ul>				</td>	
<tr style="display:none">
<th style="display:none">Beneficiario</th>
	  <td><label  style="display:none"">
	     <input name="cuentas_por_pagar_orden_db_radio" type="radio" id="cuentas_por_pagar_orden_db_radio1" onclick="getObj('tr_empleado_orden_cxp').style.display='none'; getObj('tr_proveedor_orden_cxp').style.display='';" value="1" checked="CHECKED"/>
	  </label>
	    &nbsp;&nbsp;
	    <label style="display:none">
          <input name="cuentas_por_pagar_orden_db_radio" type="radio" id="cuentas_por_pagar_orden_db_radio2"  onclick="getObj('tr_empleado_orden_cxp').style.display=''; getObj('tr_proveedor_orden_cxp').style.display='none';" value="0" />
	    </label>
	    </br>
      <input type="hidden" name="cuentas_por_pagar_orden_db_op_oculto" id="cuentas_por_pagar_orden_db_op_oculto" value="1" /></td>
</tr>
<tr>
<th>Tipo de Documento: </th>
<td>
<select id="cuentas_por_pagar_orden_db_tipo" name="cuentas_por_pagar_orden_db_tipo"  onchange="valores_combo()">
	<option value="0">---- SELECCIONE -----</option>
					<?= $opt_tipos_doc; ?>
					
</select>	
</td>
</tr>
<tr id="tr_proveedor_orden_cxp">
		<th>Proveedor:</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="cuentas_por_pagar_db_orden_proveedor_codigo" type="text" id="cuentas_por_pagar_db_orden_proveedor_codigo"  onchange="consulta_automatica_proveedor_orden()"  onblur="consulta_automatica_proveedor_orden()"
				message="Introduzca un C&oacute;digo para el proveedor."  size="5" maxlength="6"
				jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
				
	
				<input type="text" name="cuentas_por_pagar_db_orden_proveedor_nombre" id="cuentas_por_pagar_db_orden_proveedor_nombre" size="45"
				message="Introduzca el nombre del Proveedor." readonly />
				<input type="hidden" name="cuentas_por_pagar_db_orden_proveedor_id" id="cuentas_por_pagar_db_orden_proveedor_id" readonly />
				<input type="hidden" name="cuentas_por_pagar_db_orden_proveedor_rif" id="cuentas_por_pagar_db_orden_proveedor_rif" readonly />
				</li> 
					<li id="cuentas_por_pagar_db_proveedor_orden_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>				</td>		
	</tr>
<tr id="tr_empleado_orden_cxp"  style="display:none">
<th>Empleado:</th>
      <td >		<ul class="input_con_emergente">
	  <li><input name="cuentas_por_pagar_orden_db_empleado_codigo" type="text" id="cuentas_por_pagar_orden_db_empleado_codigo" onblur="consulta_automatica_benef_codigo()" onchange="consulta_automatica_benef_codigo()"
				size="5"  maxlength="5" 
				message="Introduzca un C&oacute;digo para el Empleado."
				jval="{valid:/^[,.-_123456789]{1,6}$/,message:'C&oacute;digo Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}" 
				/>
	    <input name="cuentas_por_pagar_orden_db_empleado_nombre" type="text" id="cuentas_por_pagar_orden_db_empleado_nombre" size="45" maxlength="60"
				message="Introduzca el nombre del Empleado." />
		  <label>
		    </label>
	     
	      <input type="hidden" name="textprue3" id="textprue3" />
	  </li> 
	  		<li id="cuentas_por_pagar_orden_db_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>	
</tr>		
<tr>		
<th>Fecha :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_db_orden_fecha_v" id="cuentas_por_pagar_db_orden_fecha_v" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha : '+$(this).val()]}"/>
	      
	      <button type="reset" id="cuentas_por_pagar_db_ordenes_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_db_orden_fecha_v",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_db_ordenes_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cuentas_por_pagar_db_orden_fecha_v").value.MMDDAAAA() );
						}
					});
			</script>
			
	      </label></td>
	</tr>	
	<tr>
		<th>Estatus:</th>
		<td><input type="text" id="cuentas_por_pagar_db_orden_estado" name="cuentas_por_pagar_db_orden_estado" value="ABIERTA" readonly /></td>
	</tr>
	<tr>
		<th>Comentarios:</th>
		<td><textarea  name="cuentas_por_pagar_db_ordenes_comentarios" cols="60" id="cuentas_por_pagar_db_ordenes_comentarios" message="Introduzca un comentario."></textarea><br />			</td>
	</tr>
	<tr>
	  <th>Neto a Pagar:</th>
		<td><input type="text" name="cuentas_por_pagar_db_facturas_total" id="cuentas_por_pagar_db_facturas_total" readonly="" value="0,00" />
	    <input type="hidden" name="cuentas_por_pagar_db_facturas_oculto" id="cuentas_por_pagar_db_facturas_oculto" readonly=""/>
	<input type="hidden" name="cuentas_por_pagar_db_orden_abrir_cerrar" id="cuentas_por_pagar_db_orden_abrir_cerrar" readonly="" value="1"/></td>
	</tr>
	<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_factura" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_factura" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
    </tr>
   <tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="cuentas_por_pagar_db_numero_compromiso" type="hidden" id="cuentas_por_pagar_db_numero_compromiso" />
</form>