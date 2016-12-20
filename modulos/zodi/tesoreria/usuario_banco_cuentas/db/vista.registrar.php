<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
$("#tesoreria_usuario_banco_cuentas_db_btn_consultar").click(function() {
/*	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/usuario_banco_cuentas/db/grid_usuario_banco_cuentas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Banco/Cuentas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/usuario_banco_cuentas/db/grid_us_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Usuarios/Bancos/Cuentas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
							var busq_banco= jQuery("#tesoreria-consultas-busq_banco2").val(); 
							var busq_cuenta= jQuery("#tesoreria-consultas-busq_cuentas2").val(); 
							var busq_usuario= jQuery("#tesoreria-consultas-busq_us2").val(); 
											jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario_banco_cuentas.php?busq_banco="+busq_banco+"&busq_cuenta="+busq_cuenta+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-consultas-busq_banco2").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_banco_us_dosearch();
					});
				$("#tesoreria-consultas-busq_cuentas2").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_banco_us_dosearch();
					});	
				$("#tesoreria-consultas-busq_us2").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_banco_us_dosearch();
					});		
				
						function consulta_banco_us_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_banco_us_gridReload,500)
										}
						function consulta_banco_us_gridReload()
						{
							var busq_banco= jQuery("#tesoreria-consultas-busq_banco2").val(); 
							var busq_cuenta= jQuery("#tesoreria-consultas-busq_cuentas2").val(); 
							var busq_usuario= jQuery("#tesoreria-consultas-busq_us2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario_banco_cuentas.php?busq_banco="+busq_banco+"&busq_cuenta="+busq_cuenta+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario_banco_cuentas.php?"+busq_banco+"&busq_usuario="+busq_usuario;
						}

			}
		});						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario_banco_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Id_banco','Banco_largo','Banco','id_usuario','usuario','N∫ Cuenta','Estatus','Comentarios'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idbanco',index:'idbanco', width:50,sortable:false,resizable:false,hidden:true},
									{name:'banco',index:'banco', width:130,sortable:false,resizable:false,hidden:true},
									{name:'banco_n',index:'banco_n', width:130,sortable:false,resizable:false},
									{name:'id_usuario',index:'id_usuario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'usuario',index:'usuario', width:120,sortable:false,resizable:false},
									{name:'ncuenta',index:'ncuenta', width:130,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_usuario_banco_cuentas_db_id').value=ret.id;
									getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value=ret.idbanco;
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_nombre').value=ret.banco;
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').value=ret.ncuenta;
									getObj('tesoreria_usuario_banco_cuentas_db_comentarios').value=ret.comentarios;
									getObj('tesoreria_usuario_banco_cuentas_db_btn_eliminar').style.display="";
									getObj('tesoreria_usuario_banco_cuentas_db_btn_cancelar').style.display='';
									getObj('tesoreria_usuario_banco_cuentas_db_btn_actualizar').style.display='';
									getObj('tesoreria_usuario_banco_cuentas_db_btn_guardar').style.display='none';	
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="";	
									getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value=ret.idbanco;
									getObj('tesoreria_usuario_banco_cuentas_db_id_usuario').value=ret.id_usuario;
									getObj('tesoreria_usuario_banco_cuentas_db_usuario').value=ret.usuario;
										
										if(ret.estatus=='Activo')
						     	    { 
										getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_act').checked="checked";
										getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";
									}else
									{
									getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_inact').checked="checked";
									getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="2";
									}				
								dialog.hideAndUnload();
									$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'fecha',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#tesoreria_db_btn_consultar_banco_usuario_cuentas").click(function() {
if(getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value!="")
{
	urls='modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value;

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/usuario_banco_cuentas/db/grid_usuario_banco_cuentas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Banco',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Banco','N∫ Cuenta','Estatus'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'banco',index:'banco', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false}
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
									//$('#form_tesoreria_db_usuario_banco_cuentas').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'fecha',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
$("#tesoreria_db_btn_consultar_banco_usuario_banco_cuentas").click(function() {
/*	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/usuario_banco_cuentas/db/grid_usuario_banco_cuentas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/usuario_banco_cuentas/db/grid_banco.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_rel_banco_us_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_rel_banco_us_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_rel_banco_us_dosearch();
					});
				
						function consulta_rel_banco_us_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_rel_banco_us_gridReload,500)
										}
						function consulta_rel_banco_us_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_rel_banco_us_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_banco.php="+busq_banco;
						}

			}
		});	

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo ¡rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuenta'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:130,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:130,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'cuenta',index:'cuenta', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value=ret.id;
									
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_nombre').value=ret.nombre;
									getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').value=ret.cuenta;
									//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="";
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
								sortname: 'fecha',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#tesoreria_usuario_banco_cuentas_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/usuario_banco_cuentas/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_usuario_banco_cuentas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_eliminar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_actualizar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_guardar').style.display='';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_cancelar').style.display='';
					clearForm('form_tesoreria_db_usuario_banco_cuentas');
					getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";									
					getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_inact').checked="";
					//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="disabled";
}
				else if (html=="NoActualizo")
				{
					setBarraEstado(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
								}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#tesoreria_usuario_banco_cuentas_db_btn_guardar").click(function() {
	if($('#form_tesoreria_db_usuario_banco_cuentas').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/usuario_banco_cuentas/db/sql.registrar.php",
			data:dataForm('form_tesoreria_db_usuario_banco_cuentas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_usuario_banco_cuentas');
					getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";	
					//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="disabled";
	
				}
				else if (html=="NoRegistro")
				{
						
						setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
						clearForm(form_tesoreria_db_usuario_banco_cuentas);
						getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";	
						//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="disabled";
		
							}
					else
				{
					
					setBarraEstado(html);
					//getObj('tesoreria_banco_db_direccion').value=html;
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			
			}
		});
	}
});
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_usuario_banco_cuentas_db_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/tesoreria/usuario_banco_cuentas/db/sql.eliminar.php",
			data:dataForm('form_tesoreria_db_usuario_banco_cuentas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					clearForm('form_tesoreria_db_usuario_banco_cuentas');
					getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";	
					getObj('tesoreria_usuario_banco_cuentas_db_btn_cancelar').style.display='';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_actualizar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_eliminar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_guardar').style.display='';	
					
				}
				else
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$("#tesoreria_usuario_banco_cuentas_db_btn_cancelar").click(function() {
	setBarraEstado("");
    getObj('tesoreria_usuario_banco_cuentas_db_btn_eliminar').style.display='none';
	getObj('tesoreria_usuario_banco_cuentas_db_btn_actualizar').style.display='none';
	getObj('tesoreria_usuario_banco_cuentas_db_btn_guardar').style.display='';
	getObj('tesoreria_usuario_banco_cuentas_db_btn_consultar').style.display='';
	getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_act').checked="checked";
	getObj('tesoreria_usuario_banco_cuentas_db_estatus_opt_inact').checked="";
	clearForm('form_tesoreria_db_usuario_banco_cuentas');
	getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";	
	//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="disabled";
	
});


//consultas automaticas
function consulta_automatica_banco_usuario_banco_cuentas()
{
		$.ajax({
			url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_codigo.php",
            data:dataForm('form_tesoreria_db_usuario_banco_cuentas'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
					recordset = recordset.split("*");
					getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value=recordset[0];				
					getObj('tesoreria_usuario_banco_cuentas_cuenta_db_nombre').value=recordset[3];
					//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta').disabled="";	
					//getObj('tesoreria_usuario_banco_cuentas_cuenta_db_codigo').value=recordset[4];
			 	}
				 else
				 {
				 	setBarraEstado("");
				    getObj('tesoreria_usuario_banco_cuentas_db_btn_eliminar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_consultar').style.display='';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_actualizar').style.display='none';
					getObj('tesoreria_usuario_banco_cuentas_db_btn_guardar').style.display='';	
					getObj('tesoreria_usuario_banco_cuentas_cuenta_id_banco').value="";				
				//	getObj('tesoreria_usuario_banco_cuentas_cuenta_db_nombre').value="";
					
				 }
			 }
		});	 	 
	
}
//
$("#tesoreria_usuario_banco_cuentas_db_btn_consultar_usuario").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/usuario_banco_cuentas/db/grid_usuario_banco_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario_cuentas").val(); 
					var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario_cuentas2").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-consultas-busq_nombre_usuario_cuentas").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_cuentas_dosearch();
												
					});
				$("#tesoreria-consultas-busq_nombre_usuario_cuentas2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_cuentas_dosearch();
												
					});	
					function tesoreria_usuario_cuentas_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_cuentas_gridReload,500)
										}
						function tesoreria_usuario_cuentas_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario_cuentas").val();
							var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario_cuentas2").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
							
						}
			}
		});
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/usuario_banco_cuentas/db/grid_usuario_banco_cuentas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
										function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario','Unidad'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_usuario_banco_cuentas_db_id_usuario').value = ret.id;
									getObj('tesoreria_usuario_banco_cuentas_db_usuario').value = ret.nombre;
							
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
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	
});
						
						
						
						////////////////////////
								
//
$("#tesoreria_usuario_banco_cuentas_db_estatus_opt_act").click(function(){
		getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="1";
		
	});
$("#tesoreria_usuario_banco_cuentas_db_estatus_opt_inact").click(function(){
		getObj('tesoreria_usuario_banco_cuentas_db_estatus').value="2";
	});
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#tesoreria_usuario_banco_cuentas_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_usuario_banco_cuentas_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_usuario_banco_cuentas_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
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
	
</script>

<div id="botonera">
	<img id="tesoreria_usuario_banco_cuentas_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="tesoreria_usuario_banco_cuentas_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_usuario_banco_cuentas_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="tesoreria_usuario_banco_cuentas_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_usuario_banco_cuentas_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_tesoreria_db_usuario_banco_cuentas" name="form_tesoreria_db_usuario_banco_cuentas">
  <input type="hidden"  id="tesoreria_usuario_banco_cuentas_db_id" name="tesoreria_usuario_banco_cuentas_db_id"/>
  <table   class="cuerpo_formulario">
  
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Relaci&oacute;n Usuarios Banco/Cuenta </th>
	</tr>
	<tr>
		  <th>Usuario</th>
		<td> 
		<ul class="input_con_emergente">
		<li><input name="tesoreria_usuario_banco_cuentas_db_usuario" type="text" id="tesoreria_usuario_banco_cuentas_db_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" readonly
			jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		<input type="hidden" id="tesoreria_usuario_banco_cuentas_db_id_usuario" name="tesoreria_usuario_banco_cuentas_db_id_usuario"/>
		
		<li id="tesoreria_usuario_banco_cuentas_db_btn_consultar_usuario" class="btn_consulta_emergente"></li>
		</ul>
		</td>
	</tr>	
	</tr>	  
		  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_usuario_banco_cuentas_cuenta_db_nombre" type="text" id="tesoreria_usuario_banco_cuentas_cuenta_db_nombre"   value="" size="50" maxlength="80" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_usuario_banco_cuentas_cuenta_id_banco" name="tesoreria_usuario_banco_cuentas_cuenta_id_banco"/>
		</li>
		<li id="tesoreria_db_btn_consultar_banco_usuario_banco_cuentas" class="btn_consulta_emergente"></li></ul>	</td>
		
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	     <td>
		 <ul class="input_con_emergente">		
		<li>
		<input name="tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta" type="text" id="tesoreria_usuario_banco_cuentas_cuenta_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el N˙mero de cuenta. "  readonly  
			jVal="{valid:/^[0123456789]{1,30}$/, message:'N&uacute;mero de Cuenta Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
    	</li>
		<li id="tesoreria_db_btn_consultar_banco_usuario_cuentas" class="btn_consulta_emergente"></li>	</ul></td>
		
	 <tr>
		<th>Comentarios:</th>
		<td><textarea  name="tesoreria_usuario_banco_cuentas_db_comentarios" cols="60" id="tesoreria_usuario_banco_cuentas_db_comentarios" message="Introduzca un comentario."></textarea>		</td>
	</tr>
		<tr> 
		<th>Estatus:</th>
		<td>
		   	<input id="tesoreria_usuario_banco_cuentas_db_estatus_opt_act" name="tesoreria_usuario_banco_cuentas_db_estatus_opt"  type="radio" value="1" checked="checked" />Activo
	      	<input id="tesoreria_usuario_banco_cuentas_db_estatus_opt_inact" name="tesoreria_usuario_banco_cuentas_db_estatus_opt"  type="radio" value="2" />Inactivo
          <input type="hidden" id="tesoreria_usuario_banco_cuentas_db_estatus" name="tesoreria_usuario_banco_cuentas_db_estatus"  value="1" /></td>
	  </tr>
	  <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
   	 </tr>
</table>
<input  name="tesoreria_usuario_banco_cuentas_db_id2" type="hidden" id="" />
</form>