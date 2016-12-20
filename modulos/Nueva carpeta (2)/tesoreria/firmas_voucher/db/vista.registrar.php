<?php
session_start();

?>
<script type='text/javascript'>
var dialog;

$("#tesoreria_firma_voucher_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	var fecha_actual=new Date()
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/firmas_voucher/db/grid_firma_voucher.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Firmas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/firmas_voucher/db/sql_grid_firma_voucher.php?nd='+nd,
								datatype: "json",
								colNames:['Id','CÛdigo director','Director','CÛdigo Administracion','Administrador','CÛdigo Jefe Finanzas','Jefe Finanazas','Comentarios','Fecha','Estatus'],
								colModel:[
									{name:'id',index:'id', width:70,sortable:false,resizable:false,hidden:true},
									{name:'codigo_director',index:'codigo_director', width:50,sortable:false,resizable:false,hidden:true},
									{name:'director',index:'director', width:70,sortable:false,resizable:false},
									{name:'codigo_administracion',index:'codigo_administracion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'administracion',index:'administracion', width:70,sortable:false,resizable:false},
								    {name:'codigo_jefe_finanzas',index:'codigo_jefe_finanzas', width:50,sortable:false,resizable:false,hidden:true},
									{name:'jefe_finanzas',index:'jefe_finanzas', width:70,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'fecha',index:'fecha', width:40,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:40,sortable:false,resizable:false}
 
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									fd=ret.fecha.substr(0, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value=fds; 
									getObj('tesoreria_vista_banco_firmas_voucher').value = ret.id;
									getObj('tesoreria_firmas_voucher_db_id_director').value = ret.codigo_director;
									getObj('tesoreria_firmas_voucher_db_director').value = ret.director;
									getObj('tesoreria_firmas_voucher_db_id_director_administracion').value=ret.codigo_administracion;
     								getObj('tesoreria_firmas_voucher_db_director_administracion').value=ret.administracion;	
									getObj('tesoreria_firmas_voucher_db_id_jefe_finanzas').value=ret.codigo_jefe_finanzas;
									getObj('tesoreria_firmas_voucher_db_jefe_finanzas').value=ret.jefe_finanzas;
										getObj('tesoreria_firma_voucher_db_btn_cancelar').style.display='';
									getObj('tesoreria_firma_voucher_db_btn_actualizar').style.display='';
									getObj('tesoreria_firma_voucher_db_btn_guardar').style.display='none';		
								 	if(ret.estatus=='ACTIVO')
										{ 
											getObj('tesoreria_firmas_voucher_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_firmas_voucher_db_estatus').value="1";
											getObj('tesoreria_firmas_voucher_db_estatus_opt_act').disabled="";		
											getObj('tesoreria_firmas_voucher_db_estatus_opt_inact').disabled="";
									
										}else
										if(ret.estatus=='INACTIVO')
										{
										getObj('tesoreria_firmas_voucher_db_estatus_opt_inact').checked="checked";
										getObj('tesoreria_firmas_voucher_db_estatus').value="2";
										getObj('tesoreria_firmas_voucher_db_estatus_opt_act').disabled="";		
										getObj('tesoreria_firmas_voucher_db_estatus_opt_inact').disabled="";
										}	
									dialog.hideAndUnload();
								$('#form_tesoreria_db_firmas_voucher').jVal();
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

$("#tesoreria_firma_voucher_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	var fecha_actual=new Date();
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/firmas_voucher/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_firmas_voucher'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
						setBarraEstado("");
					//	getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
						getObj('tesoreria_firma_voucher_db_btn_actualizar').style.display='none';
						getObj('tesoreria_firma_voucher_db_btn_guardar').style.display='';
						getObj('tesoreria_firma_voucher_db_btn_consultar').style.display='';
						clearForm('form_tesoreria_db_firmas_voucher');
						getObj('tesoreria_firmas_voucher_db_estatus').value='1';	
						getObj('tesoreria_firmas_voucher_db_estatus_opt_act').checked='checked';
						getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value="<?=  date("d/m/Y"); ?>";					
					}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado("");
					//	getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
						//getObj('tesoreria_firma_voucher_db_btn_actualizar').style.display='none';
						//getObj('tesoreria_firma_voucher_db_btn_guardar').style.display='';
						//getObj('tesoreria_firma_voucher_db_btn_consultar').style.display='';
						//clearForm('form_tesoreria_db_firmas_voucher');
						//getObj('tesoreria_frima_voucher_db_ayo').value=fecha_actual.getFullYear();
						
					//	getObj('tesoreria_frima_voucher_db_mes').value='01';	
					//	getObj('tesoreria_firmas_voucher_db_estatus').value='1';			
				}
				else if (html=="firma_activa")
							{
									setBarraEstado("");
  								  	setBarraEstado(mensaje[firma_existe],true,true);
									clearForm('form_tesoreria_db_firmas_voucher');
									getObj('tesoreria_firmas_voucher_db_estatus').value='1';
									getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value="<?=  date("d/m/Y"); ?>";		
							}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#tesoreria_firma_voucher_db_btn_guardar").click(function() {
	var fecha_actual=new Date();
	if($('#form_tesoreria_db_firmas_voucher').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/firmas_voucher/db/sql.registrar.php",
			data:dataForm('form_tesoreria_db_firmas_voucher'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_firmas_voucher');
					getObj('tesoreria_firmas_voucher_db_estatus').value='1';
					getObj('tesoreria_firmas_voucher_db_estatus_opt_act').checked='checked';
					getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value="<?=  date("d/m/Y"); ?>";					
				
					
				}
				else if ((html=="NoRegistro")||(html=="fechas_iguales"))
				{
						setBarraEstado(mensaje[registro_existe],true,true);
					//	clearForm('form_tesoreria_db_firmas_voucher');
					//	getObj('tesoreria_frima_voucher_db_ayo').value=fecha_actual.getFullYear();
					//	getObj('tesoreria_frima_voucher_db_mes').value='01';
					//	getObj('tesoreria_firmas_voucher_db_estatus').value='1';						
							}
					
					else if (html=="firma_activa")
							{
									setBarraEstado("");
  								  	setBarraEstado(mensaje[firma_existe],true,true);
									clearForm('form_tesoreria_db_firmas_voucher');
									getObj('tesoreria_firmas_voucher_db_estatus').value='1';
									getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value="<?=  date("d/m/Y"); ?>";		
							}
					else		
					{
					alert(html);
					setBarraEstado(html);
					
					//getObj('tesoreria_banco_db_direccion').value=html;
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			
			}
		});
	}
});

$("#tesoreria_db_btn_consultar_director").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/firmas_voucher/db/grid_firma_voucher.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val(); 
					var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-consultas-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
				$("#tesoreria-consultas-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
					function tesoreria_usuario_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_gridReload,500)
										}
						function tesoreria_usuario_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid");
							
						}
			}
		});
		
/*
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/firmas_voucher/db/grid_firma_voucher.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de usuarios', modal: true,center:false,x:0,y:0,show:false });								
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
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false,hidden:true},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_firmas_voucher_db_id_director').value = ret.id;
									getObj('tesoreria_firmas_voucher_db_director').value = ret.nombre;
							
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
$("#tesoreria_db_btn_consultar_jefe_finanzas").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/firmas_voucher/db/grid_firma_voucher.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val();
					var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-consultas-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
				$("#tesoreria-consultas-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
					function tesoreria_usuario_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_gridReload,500)
										}
						function tesoreria_usuario_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); //alert(busq_usuario);
							
						}
			}
		});
		
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
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false,hidden:true},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_firmas_voucher_db_id_jefe_finanzas').value = ret.id;
									getObj("tesoreria_firmas_voucher_db_jefe_finanzas").value = ret.nombre;

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


									
$("#tesoreria_db_btn_consultar_director_administracion").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/firmas_voucher/db/grid_firma_voucher.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val(); 
					var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-consultas-busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
				$("#tesoreria-consultas-busq_nombre_usuario2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_dosearch();
												
					});
					function tesoreria_usuario_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_gridReload,500)
										}
						function tesoreria_usuario_gridReload()
						{
							var busq_nombre= jQuery("#tesoreria-consultas-busq_nombre_usuario").val(); 
							var busq_usuario= jQuery("#tesoreria-consultas-busq_nombre_usuario2").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/firmas_voucher/db/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
							
						}
			}
		});
		
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
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false,hidden:true},
								
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_firmas_voucher_db_id_director_administracion').value = ret.id;
									getObj('tesoreria_firmas_voucher_db_director_administracion').value = ret.nombre;

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




						
// -----------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_firma_voucher_db_btn_cancelar").click(function() {

	setBarraEstado("");
//	getObj('tesoreria_banco_db_btn_eliminar').style.display='none';

	getObj('tesoreria_firma_voucher_db_btn_actualizar').style.display='none';
	getObj('tesoreria_firma_voucher_db_btn_guardar').style.display='';
	getObj('tesoreria_firma_voucher_db_btn_consultar').style.display='';
	clearForm('form_tesoreria_db_firmas_voucher');
	getObj('tesoreria_firmas_voucher_db_estatus').value='1';
	getObj('form_tesoreria_db_firmas_voucher_rp_fecha').value="<?=  date("d/m/Y"); ?>";							
									
});


	
$("#tesoreria_firmas_voucher_db_estatus_opt_act").click(function(){
		getObj('tesoreria_firmas_voucher_db_estatus').value="1";
		
	});
$("#tesoreria_firmas_voucher_db_estatus_opt_inact").click(function(){
		getObj('tesoreria_firmas_voucher_db_estatus').value="2";
	});
	
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


$('#tesoreria_banco_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
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
	<img id="tesoreria_firma_voucher_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="tesoreria_firma_voucher_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_firma_voucher_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="tesoreria_firma_voucher_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_firma_voucher_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_tesoreria_db_firmas_voucher" name="form_tesoreria_db_firmas_voucher">
<input type="hidden"  id="tesoreria_vista_banco_firmas_voucher" name="tesoreria_vista_banco_firmas_voucher"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Firmas Voucher </th>
	</tr>
	<tr>
		<th>Fecha :</th>
	      <td><label>
	      <input readonly="true" type="text" name="form_tesoreria_db_firmas_voucher_rp_fecha" id="form_tesoreria_db_firmas_voucher_rp_fecha" size="7" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha : '+$(this).val()]}"/>
	      <input type="hidden"  name="form_tesoreria_db_firmas_voucher_rp_fecha_oculto" id="form_tesoreria_db_firmas_voucher_rp_fecha_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="form_tesoreria_db_firmas_voucher_rp_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "form_tesoreria_db_firmas_voucher_rp_fecha",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "form_tesoreria_db_firmas_voucher_rp_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("form_tesoreria_db_firmas_voucher_rp_fecha").value.MMDDAAAA() );
								
							}
					});
			</script>
	      </label></td>
	</tr>
	<tr>
		<th>Director OCHINA:</th>
	    <td>
		<ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_firmas_voucher_db_director" type="text" id="tesoreria_firmas_voucher_db_director"    size="40" maxlength="80" message="Seleccione el Nombre del  Director de  su organismo." 
						jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		        <input type="hidden"  id="tesoreria_firmas_voucher_db_id_director" name="tesoreria_firmas_voucher_db_id_director"/>
		</li>
		<li id="tesoreria_db_btn_consultar_director" class="btn_consulta_emergente"></li>
		</td>
	</tr>
 
  <tr>
		<th>Director Administracion:</th>
	    <td><ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_firmas_voucher_db_director_administracion" type="text" id="tesoreria_firmas_voucher_db_director_administracion"    size="40" maxlength="80" message="Seleccione el Nombre del  Director de de administraciÛn." 
						jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		        <input type="hidden"  id="tesoreria_firmas_voucher_db_id_director_administracion" name="tesoreria_firmas_voucher_db_id_director_administracion"/>
		</li>
		<li id="tesoreria_db_btn_consultar_director_administracion" class="btn_consulta_emergente"></li>	</td>
  </tr>
   <tr>
		<th>Jefe de Finanzas:</th>
	    <td><ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_firmas_voucher_db_jefe_finanzas" type="text" id="tesoreria_firmas_voucher_db_jefe_finanzas"    size="40" maxlength="80" message="Seleccione el Nombre del jefe de finanzas." 
						jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		        <input type="hidden"  id="tesoreria_firmas_voucher_db_id_jefe_finanzas" name="tesoreria_firmas_voucher_db_id_jefe_finanzas"/>
		</li>
		<li id="tesoreria_db_btn_consultar_jefe_finanzas" class="btn_consulta_emergente"></li>	</td>
  </tr>
  
		 <tr>
		<th>Comentarios:</th>
		<td><textarea  name="tesoreria_banco_db_comentarios" cols="60" id="tesoreria_banco_db_comentarios" message="Introduzca un comentario."></textarea>		</td>
	</tr>
	<tr>
	<th>Estatus:</th>
		<td>
		   	<input id="tesoreria_firmas_voucher_db_estatus_opt_act" name="tesoreria_firmas_voucher_db_estatus_opt"  type="radio" value="1" checked="checked" />Activo
	      	<input id="tesoreria_firmas_voucher_db_estatus_opt_inact" name="tesoreria_firmas_voucher_db_estatus_opt"  type="radio" value="2" />Inactivo
   	      	
		  <input type="hidden" id="tesoreria_firmas_voucher_db_estatus" name="tesoreria_firmas_voucher_db_estatus"  value="1" /></td>

	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>  
<input  name="tesoreria_banco_cuenta_db_id" type="hidden" id="" />
</form>