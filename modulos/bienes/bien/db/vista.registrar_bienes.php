<?php session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />

</head>
<script>
//------------------ Marcaras de edicion de campos de entrada -----------------////
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
//---------------------- funciones radio boton -------------------------///
$("#depreciacion0").click(function(){
	getObj('val_depreciacion').value='0';
});
$("#depreciacion1").click(function() {
	getObj('val_depreciacion').value='1';
});
$("#registrar_bienes_db_estatus").click(function() {
	getObj('estatus').value='2';
});
$("#registrar_bienes_db_estatus2").click(function() {
	getObj('estatus').value='3';
});
$("#registrar_bienes_db_estatus3").click(function() {
	getObj('estatus').value='4';
});
$("#registrar_bienes_db_estatus4").click(function() {
	getObj('estatus').value='5';
});
//---------------------- Consulta emergente de orden de compra -----------------////												
$("#bienes_db_btn_consulta_emergente_orden").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_orden_compra.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Orden de Compra', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_orden= jQuery("#bien_grid_db_orden").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.orden_compra.php?busq_orden="+busq_orden,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#bien_grid_db_orden").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_orden= jQuery("#bien_grid_db_orden").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.orden_compra.php?busq_orden="+busq_orden,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql.orden_compra.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Orden de Compra','N&deg; Factura','Concepto','','Cantidad','Restantes'],
								colModel:[
									{name:'id_orden',index:'id_orden', width:50,sortable:false,resizable:false,hidden:true},
									{name:'orden',index:'orden', width:100,sortable:false,resizable:false},
									{name:'numero_documento',index:'numero_documento', width:50,sortable:false,resizable:false},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cantidad',index:'cantidad', width:50,sortable:false,resizable:false},
									{name:'total',index:'total', width:50,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_orden').value=ret.id_orden;
									getObj('registrar_bienes_db_orden').value=ret.orden;
									getObj('registrar_bienes_db_orden_ano').value=ret.ano;
									/* getObj('registrar_bienes_db_factura').value=ret.numero_documento; */
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#bien_grid_db_orden").focus();
								$('#bien_grid_db_orden').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_orden',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//----------------------- fin consulta emergente orden de compra -------------------------///
//---------------------- Consulta emergente de unidad -----------------////												
$("#bienes_db_btn_consulta_emergente_unidad").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_uni_eje_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#bien_grid_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql_uni_eje_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#bien_grid_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#bien_grid_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql_uni_eje_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql_uni_eje_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Unidad','Comentario'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_unidad').value=ret.id_unidad_ejecutora;
									getObj('registrar_bienes_db_unidad').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#bien_grid_db_nombre").focus();
								$('#bien_grid_db_nombre').alpha({allow:' '});
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
//----------------------- fin consulta emergente unidad -------------------------///
//----------------------- Consulta emergente de mayor ----------------------------//
$("#bienes_db_btn_consulta_emergente_mayor").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_mayor_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Mayor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#mayor_grid_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#mayor_grid_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#mayor_grid_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql.bienes_mayor_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Mayor','Comentario'],
								colModel:[
									{name:'id_mayor',index:'id_mayor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_mayor').value=ret.id_mayor;
									getObj('registrar_bienes_db_mayor').value=ret.nombre;
									/* if(ret.nombre=="Transporte"){
										getObj('pestan').style.display='';
									}
									else
										getObj('pestan').style.display='none';
										getObj('registrar_bienes_db_serial_motor').value='';
										getObj('registrar_bienes_db_serial_car').value='';
										getObj('registrar_bienes_db_color').value='';
										getObj('registrar_bienes_db_placa').value=''; */
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#mayor_grid_db_nombre").focus();
								$('#mayor_grid_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//------------------------- fin consulta emergente mayor ---------------------------//////
//------------------------- consulta emergente tipo bienes ------------------------/////
$("#bienes_db_btn_consulta_emergente_tipo_bien").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_tipo_bien_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Bienes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_bien_grid_db_nombre").val(); 
					var id_mayor=getObj('bien_db_id_mayor').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_tipo_bien_nombre.php?busq_nombre="+busq_nombre+"&id_mayor="+id_mayor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_bien_grid_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#tipo_bien_grid_db_nombre").val();
							var id_mayor=getObj('bien_db_id_mayor').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_tipo_bien_nombre.php?busq_nombre="+busq_nombre+"&id_mayor="+id_mayor,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql.bienes_tipo_bien_nombre.php?id_mayor='+getObj('bien_db_id_mayor').value,
								datatype: "json",
								colNames:['ID','Tipo de Bien','Comentario','',''],
								colModel:[
									{name:'id_tipo_bien',index:'id_tipo_bien', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false},
									{name:'vida_util_tb',index:'vida_util_tb', width:100,hidden:true},
									{name:'vehiculo',index:'vehiculo', width:100,hidden:true}


								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_tipo').value=ret.id_tipo_bien;
									getObj('registrar_bienes_db_tipo').value=ret.nombre;
									getObj('registrar_bienes_db_vida_util').value=ret.vida_util_tb;
									if(ret.vehiculo==1){
										getObj('pestan').style.display='';
									}
									else
										getObj('pestan').style.display='none';
										getObj('registrar_bienes_db_serial_motor').value='';
										getObj('registrar_bienes_db_serial_car').value='';
										getObj('registrar_bienes_db_color').value='';
										getObj('registrar_bienes_db_placa').value=''; 
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_bien_grid_db_nombre").focus();
								$('#tipo_bien_grid_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//----------------------- fin consulta emeregnte tipo de bienes ----------------------///
///---------------------- consulta emergente sitio fisico ----------------------------//
$("#bienes_db_btn_consulta_emergente_sitio_fisico").click(function() {
	var busq_id_unidad= getObj('bien_db_id_unidad').value;
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_sitio_fisico_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Sitio fisico', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#sitio_fisico_grid_db_nombre").val();
					var busq_id_unidad= getObj('bien_db_id_unidad').value;
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_sitio_fisico_nombre.php?busq_nombre="+busq_nombre+"&busq_id_unidad="+busq_id_unidad,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_grid_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#sitio_fisico_grid_db_nombre").val();
							var busq_id_unidad= getObj('bien_db_id_unidad').value;
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_sitio_fisico_nombre.php?busq_nombre="+busq_nombre+"&busq_id_unidad="+busq_id_unidad,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql.bienes_sitio_fisico_nombre.php?busq_id_unidad='+busq_id_unidad,
								datatype: "json",
								colNames:['ID','Sitio Fisico','Comentario'],
								colModel:[
									{name:'id_sitio_fisico',index:'id_sitio_fisico', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_sitio_fisico').value=ret.id_sitio_fisico;
									getObj('registrar_bienes_db_sitio_fisico').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#sitio_fisico_grid_db_nombre").focus();
								$('#sitio_fisico_grid_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//--------------------- fin consulta emergente sitio fisico ----------------------------////
//--------------------- consulta emergente de custodio ---------------------------------////
$("#bienes_db_btn_consulta_emergente_custodio").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_custodio_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Custodio', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#custodio_grid_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#custodio_grid_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#custodio_grid_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql.bienes_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql.bienes_custodio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Custodio'],
								colModel:[
									{name:'id_custodio',index:'id_custodio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('bien_db_id_custodio').value=ret.id_custodio;
									getObj('registrar_bienes_db_custodio').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#custodio_grid_db_nombre").focus();
								$('#custodio_grid_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//------------------------- fin consulta emergente custodio ----------------------------////
///------------------------- boton de guardar --------------------------------------///
//var dialog;
$("#bien_db_btn_guardar").click(function() {
	//if ($('#form_db_bien').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/bien/db/sql.registrar_bien.php",
			data:dataForm('form_db_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{ 
			  tam = html.length;
				if (html.substr(0,10)=="Registrado")
				{
					//setBarraEstado(mensaje[registro_exitoso],true,true);
					//
					url='modulos/bienes/bien/pr/vista.registrar_foto_bien.php?id_bienes='+html.substr(11,tam-1);
					 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/filetypes.png />Se Registro con éxito ¿Desea agregarle una Foto a éste Bien?</p></div>", ["SI", "NO"], function(val) {
      if(val=="SI") openTab('Fotos del Activo',url);
    }, {title: "SAI-OCHINA"});
    
					//
					setBarraEstado("");
					limpiar_campos();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /></p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	//}
});
//----------------------Actualizar--------------------------------
$("#bien_db_btn_actualizar").click(function() {
	//if ($('#form_db_bien').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/bien/db/sql.actualizar_bien.php",
			data:dataForm('form_db_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_campos();
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /></p></div>",true,true);
					}
				else 
				{
					setBarraEstado(html);
				}
			}
		});
	//}
});
//------------------------ fin boton actualizar
// ----------------------- boton cunsultar bienes --------------------------------------//
$("#bien_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/bien/db/vista.grid_bienes.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{   
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Bienes', modal: true,center:true,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#bienes_db_nombre").val();
					var busq_custodio= jQuery("#bienes_db_custodine").val();
					var busq_fecha= jQuery("#bienes_db_fecha_comp").val();
					//var busq_fecha_compp= jQuery("fecha_compp").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql_consulta_bienes.php?busq_nombre="+busq_nombre+"&busq_custodio="+busq_custodio+"&busq_fecha="+busq_fecha,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#bienes_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();/*this.close();*/}
						programa_dosearch();
												
					});
				
				$("#bienes_db_custodine").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();/*this.close();*/}
						programa_dosearch();
												
					});
				$("#bienes_db_fecha_comp").focus(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#bienes_db_nombre").val();
							var busq_custodio= jQuery("#bienes_db_custodine").val();
							var busq_fecha= jQuery("#bienes_db_fecha_comp").val();
					//var busq_fecha_compp= jQuery("fecha_compp").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/bien/db/sql_consulta_bienes.php?busq_nombre="+busq_nombre+"&busq_custodio="+busq_custodio+"&busq_fecha="+busq_fecha,page:1}).trigger("reloadGrid");
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/bien/db/sql_consulta_bienes.php?nd='+nd,
								datatype: "json",
								colNames:['','Codigo','','Nombre','','','','','','',
										  '','','Unidad','','Custodio','','','','','','','','','','','','','','Fecha Compra','','','','Orden Compra',''],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:90,sortable:false,resizable:false},
									{name:'serial_bien',index:'serial_bien', width:100,sortable:false,resizable:false,hidden:true},
									{name:'bien',index:'bien', width:130,sortable:false,resizable:false},
									{name:'mayor',index:'mayor', width:50,hidden:true},
									{name:'tipo',index:'tipo', width:50,hidden:true},
									{name:'marca',index:'marca', width:50,hidden:true},
									{name:'modelo',index:'modelo', width:50,hidden:true},
									{name:'descri',index:'descri', width:50,hidden:true},
									{name:'comen',index:'comen', width:50,hidden:true},
									{name:'idmayor',index:'idmayor', width:50,hidden:true},
									{name:'idtipo',index:'idtipo', width:50,hidden:true},
									{name:'unidad',index:'unidad', width:130},
									{name:'sitio',index:'sitio', width:50,hidden:true},
									{name:'custodio',index:'custodio', width:100},
									{name:'idunidad',index:'idunidad', width:50,hidden:true},
									{name:'idsitio',index:'idsitio', width:50,hidden:true},
									{name:'idcustodio',index:'idcustodio', width:50,hidden:true},
									{name:'vida_util',index:'vida_util', width:50,hidden:true},
									{name:'valor_compra',index:'valor_compra', width:50,hidden:true},
									{name:'valor_rescate',index:'valor_rescate', width:50,hidden:true},
									{name:'serial_motor',index:'serial_motor', width:50,hidden:true},
									{name:'serial_carroceria',index:'serial_carroceria', width:50,hidden:true},
									{name:'color',index:'color', width:50,hidden:true},
									{name:'placa',index:'placa', width:50,hidden:true},
									{name:'anobien',index:'anobien', width:50,hidden:true},
									{name:'estatus',index:'estatus', width:50,hidden:true},
								{name:'depreciacion',index:'depreciacion', width:50,hidden:true},
								{name:'fecompra',index:'fecompra', width:100},
								{name:'ordencompra',index:'ordencompra', width:50,hidden:true},
								{name:'anocompra',index:'anocompra', width:50,hidden:true},
								{name:'factura',index:'factura', width:50,hidden:true},
								{name:'num_compra',index:'num_compra', width:100},
								{name:'num_seguro',index:'num_seguro', width:50,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('registrar_bien_db_id_bienes').value=ret.id_bienes;
									getObj('registrar_bienes_db_codigo').value=ret.codigo_bienes;
									getObj('registrar_bienes_db_serial').value=ret.serial_bien;
									getObj('registrar_bienes_db_nombre').value=ret.bien;
									getObj('registrar_bienes_db_mayor').value=ret.mayor;
									getObj('registrar_bienes_db_tipo').value=ret.tipo;
									if(ret.mayor=="Transporte"){
										getObj('pestan').style.display='';
									}
									else{
										getObj('pestan').style.display='none';
									}
									getObj('registrar_bienes_db_marca').value=ret.marca;
									getObj('registrar_bienes_db_modelo').value=ret.modelo;
									getObj('registrar_bienes_db_comentarios2').value=ret.descri;
									getObj('registrar_bienes_db_comentarios').value=ret.comen;
									getObj('bien_db_id_mayor').value=ret.idmayor;
									getObj('bien_db_id_tipo').value=ret.idtipo;
									getObj('registrar_bienes_db_unidad').value=ret.unidad;
									getObj('registrar_bienes_db_sitio_fisico').value=ret.sitio;
									getObj('registrar_bienes_db_custodio').value=ret.custodio;
									getObj('bien_db_id_unidad').value=ret.idunidad;
									getObj('bien_db_id_sitio_fisico').value=ret.idsitio;
									getObj('bien_db_id_custodio').value=ret.idcustodio;
									getObj('registrar_bienes_db_vida_util').value=ret.vida_util;
									getObj('registrar_bienes_db_valor_compra').value=ret.valor_compra;
									getObj('registrar_bienes_db_valor_rescate').value=ret.valor_rescate;
									getObj('registrar_bienes_db_serial_motor').value=ret.serial_motor;
									getObj('registrar_bienes_db_serial_car').value=ret.serial_carroceria;
									getObj('registrar_bienes_db_color').value=ret.color;
									getObj('registrar_bienes_db_placa').value=ret.placa;
									getObj('registrar_bienes_db_ano').value=ret.anobien;
									if(ret.num_seguro!=""){
									  var poliza=ret.num_seguro;
									  document.getElementById('th_seguro').style.display="";
									  document.getElementById('td_seguro').style.display="";
									  document.getElementById('bienes_bien_num_seguro').value=poliza;
									  getObj('bienes_bien_seguro_0').checked=true;
									}
									if(ret.num_seguro==""){
									  seguro_no();
									}
									if(ret.estatus==2)
									{ getObj('registrar_bienes_db_estatus').checked=true;
									  getObj('estatus').value=2;
									}
									if(ret.estatus==3)
									{ getObj('registrar_bienes_db_estatus2').checked=true;
									  getObj('estatus').value=3;
									}
									if(ret.estatus==4)
									{ getObj('registrar_bienes_db_estatus3').checked=true;
									  getObj('estatus').value=4;
									}
									if(ret.estatus==5)
									{ getObj('registrar_bienes_db_estatus4').checked=true;
									  getObj('estatus').value=5;
									}
									if(ret.depreciacion==0)
									{ getObj('depreciacion0').checked=true;
									  getObj('val_depreciacion').value=0;
									}
									if(ret.depreciacion==1)
									{ getObj('depreciacion1').checked=true;
									  getObj('val_depreciacion').value=1;
									}
									getObj('registrar_bienes_db_fecha_compra').value=ret.fecompra;
									getObj('bien_db_id_orden').value=ret.ordencompra;
									getObj('registrar_bienes_db_orden_ano').value=ret.anocompra;
									getObj('registrar_bienes_db_orden').value=ret.num_compra;
									getObj('registrar_bienes_db_factura').value=ret.factura;
									getObj('bien_db_btn_guardar').style.display = 'none';
									getObj('bien_db_btn_actualizar').style.display = '';
									getObj('th_codigo').style.display = 'none';
									getObj('barcode').style.display = '';
									getObj('registrar_bienes_db_serial').focus();
									var bien=ret.bien;
									getObj('nomb_bien_cod').value= bien;
									var fecom=ret.fecompra;
									var dia_c=fecom.substr(8,10);
									var mes_c=fecom.substr(5,2);
									var ano_c=fecom.substr(0,4);
									if(mes_c==01){mes_c="ENE";}
									if(mes_c==02){mes_c="FEB";}
									if(mes_c==03){mes_c="MAR";}
									if(mes_c==04){mes_c="ABR";}
									if(mes_c==05){mes_c="MAY";}
									if(mes_c==06){mes_c="JUN";}
									if(mes_c==07){mes_c="JUL";}
									if(mes_c==08){mes_c="AGO";}
									if(mes_c==09){mes_c="SEP";}
									if(mes_c==10){mes_c="OCT";}
									if(mes_c==11){mes_c="NOV";}
									if(mes_c==12){mes_c="DIC";}
									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#bienes_db_nombre").focus();
								$('#bienes_db_nombre').alpha({allow:' '});
								$('#bienes_db_custodine').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//--------------------------------- fin boton consulta bienes------------------------///
$("#bien_db_btn_cancelar").click(function() {
//clearForm('form_db_bien');
limpiar_campos();
setBarraEstado("");
});

//------------------ Funcion para limpiar campos -----------------/////
function limpiar_campos(){
	getObj('bien_db_btn_actualizar').style.display='none';
	getObj('th_codigo').style.display='';
	getObj('barcode').style.display='none';
	getObj('bien_db_btn_guardar').style.display='';
	getObj('bien_db_id_mayor').value='';
	getObj('registrar_bien_db_id_bienes').value='';
	getObj('bien_db_id_tipo').value='';
	getObj('bien_db_id_unidad').value='';
	getObj('bien_db_id_sitio_fisico').value='';
	getObj('bien_db_id_custodio').value='';
	getObj('registrar_bienes_db_estatus').checked=true;
	getObj('bienes_bien_num_seguro').value='';
	getObj('depreciacion0').checked=true;
	getObj('bienes_bien_seguro_1').checked=true;
	seguro_no();
	getObj('registrar_bienes_db_ano').value='';
	getObj('registrar_bienes_db_codigo').value='';
	getObj('registrar_bienes_db_nombre').value='';
	getObj('registrar_bienes_db_mayor').value='';
	getObj('registrar_bienes_db_tipo').value='';
	getObj('registrar_bienes_db_marca').value='';
	getObj('registrar_bienes_db_modelo').value='';
	getObj('registrar_bienes_db_serial').value='';
	getObj('registrar_bienes_db_comentarios2').value='';
	getObj('registrar_bienes_db_estatus').value='';
	getObj('registrar_bienes_db_comentarios').value='';
	getObj('registrar_bienes_db_unidad').value='';
	getObj('pestan').style.display='none';
	getObj('registrar_bienes_db_fecha_compra').value='';
	getObj('fecha_dep').value='';
	getObj('registrar_bienes_db_sitio_fisico').value='';
	getObj('registrar_bienes_db_vida_util').value='';
	getObj('registrar_bienes_db_valor_compra').value='0,00';
	getObj('registrar_bienes_db_valor_rescate').value='0,00';
	getObj('registrar_bienes_db_serial_motor').value='';
	getObj('registrar_bienes_db_custodio').value='';
	getObj('registrar_bienes_db_serial_car').value='';
	getObj('registrar_bienes_db_color').value='';
	getObj('registrar_bienes_db_placa').value='';
	getObj('registrar_bienes_db_orden').value='';
	getObj('bien_db_id_orden').value='';
	getObj('registrar_bienes_db_orden_ano').value='';
	getObj('registrar_bienes_db_factura').value='';
	getObj('registrar_bienes_db_depreciacion').value='';
}
//------------------ fin funcion limpiar campos ---------------------------------/////
//----------------- validaciones de campos ----------------------------------------////
$('#registrar_bienes_db_codigo').alpha({allow:'0123456789 '});
$('#registrar_bienes_db_nombre').alpha({allow:' '});
$('#registrar_bienes_db_marca').alpha({allow:'0123456789- '});
$('#registrar_bienes_db_modelo').alpha({allow:'0123456789- '});
$('#registrar_bienes_db_serial').alpha({allow:'0123456789- '});
$('#registrar_bienes_db_vida_util').numeric({allow:''});
$('#bienes_bien_num_seguro').numeric({allow:''});

//----------------- fin validaciones de campos ------------------------------------////
//----------------- Codigo para mostrar los mensajes de ayuda ---------------------////
$("input, select, textarea").bind("focus", function(){
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
//----------------- Fin de codigo para mostrar los mensajes de ayuda --------------////
//----------------- funcion para crear las pestañas de bienes ---------------------////
$(function() {
    $('#pestana').tabs();
 });
////------------------------ fin de funcion crear pestañas bienes ----------------/////
////------------------------ funcion para obtener la fecha a depreciar -----------/////
function fecha_depre(){
	var fech_dep=getObj('registrar_bienes_db_fecha_compra').value;
	var dia_dpc= fech_dep.substr(0,2); dia_dpc=parseInt(dia_dpc,10);
	var mes_dpc= fech_dep.substr(3,2); mes_dpc=parseInt(mes_dpc,10);
	var ano_dpc= fech_dep.substr(6,4); ano_dpc=parseInt(ano_dpc,10);
	var mes_prox=mes_dpc+1;
	if(mes_prox>12){
		mes_dpc=1;
		ano_dpc=ano_dpc+1;
		if(mes_dpc==1 && dia_dpc<10)
		{
			fech_dep='0'+dia_dpc+'-'+'0'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
		if(mes_dpc==1 && dia_dpc>=10)
		{
			fech_dep=dia_dpc+'-'+'0'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
	}
	else
	{
		if(mes_dpc<9 && dia_dpc<10){
			mes_dpc=mes_dpc+1;
			fech_dep='0'+dia_dpc+'-'+'0'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
		if(mes_dpc<9 && dia_dpc>=10)
		{
			if(mes_dpc==1 && dia_dpc>27)
			{
				dia_dpc=28;
			}
			if((mes_dpc==3 || mes_dpc==5)|| mes_dpc==8)
			{
				dia_dpc=30;
			}
			mes_dpc=mes_dpc+1;
			fech_dep=dia_dpc+'-'+'0'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
		if(mes_dpc>=9 && dia_dpc<10)
		{ 
			mes_dpc=mes_dpc+1;
			fech_dep='0'+dia_dpc+'-'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
		if(mes_dpc>=9 && dia_dpc>=10)
		{ 
			if(mes_dpc==10)
			{
				dia_dpc=30;
			}
			mes_dpc=mes_dpc+1;
			fech_dep=dia_dpc+'-'+mes_dpc+'-'+ano_dpc;
			getObj('fecha_dep').value=fech_dep;
		}
	}
}
////------------------------ fin de la funcion------------------------------------////
////------------------------ funcion calcular valor rescate ----------------/////
function cal_rescate(){
	var val_compra= getObj('registrar_bienes_db_valor_compra').value;
	var val_rescate;
	val_compra= val_compra.replace('.','');
	val_compra= val_compra.replace('.','');
	val_compra= val_compra.replace(',','.');
	val_compra= parseFloat(val_compra);
	val_rescate= val_compra*(10/100);
	val_rescate=val_rescate.currency(2,',','.');
	getObj('registrar_bienes_db_valor_rescate').value=val_rescate;
}
////------------------------ fin funcion calcular valor rescate ----------------/////
////------------------------ Funcion para el seguro ----------------------------////
function seguro_si(){
	document.getElementById('th_seguro').style.display="";
	document.getElementById('td_seguro').style.display="";
}
function seguro_no(){
	document.getElementById('th_seguro').style.display="none";
	document.getElementById('td_seguro').style.display="none";
	document.getElementById('bienes_bien_num_seguro').value="";
}
</script>
<body>
<div id="botonera">
	<img id="bien_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="bien_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="bien_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="bien_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="bien_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
<form action="" method="post" name="form_db_bien" id="form_db_bien">
<div id="pestana">
<div>
  <ul class="tabs-nav">
    <li><a href="#pestana1"><span>Datos del Bien</span></a></li>
    <li><a href="#pestana2"><span>Custodio</span></a></li>
    <li><a href="#pestana3"><span>Depreciaci&oacute;n</span></a></li>
    <li><a href="#pestana4" style="display:none" name="pestan" id="pestan"><span>Veh&iacute;culo</span></a></li>
  </ul>
</div>
<div>
  <div id="pestana1" class="tabs-container">
    <table class="cuerpo_formulario">
      <tr>
        <th width="90" style="border-top: 1px #BADBFC solid;"> C&oacute;digo:</th>
        <td colspan="3" style="border-top: 1px #BADBFC solid">
        <div align="center" id="th_codigo"><img src="imagenes/info.png" width="16" height="16">
          <h3>El C&oacute;digo de Barra se Genera Autom&aacute;ticamente al Guardar...</h3></div>
        <script language="javascript" type="text/javascript">
        	 function barreando(){
				var src=getObj('registrar_bienes_db_codigo').value;
				src="modulos/bienes/bien/db/barcode.php?bdata="+src;
				document.images.codigo_barra.src=src;
			}  
		</script>
        
        <div id="barcode" style="border:#000 1px ridge; width:270px; display:none">
        <img src="imagenes/logos/logo_ochina.png" width="40" height="30" style="padding-left:20px"><input name="nomb_bien_cod" type="text" disabled id="nomb_bien_cod" style="background-color:#FFF; border: 1px #FFF solid; font-weight:bold" size="30" readonly="readonly">
        <br>
			<img name="codigo_barra" > 
        </div>
          <input type="hidden" name="registrar_bien_db_id_bienes" id="registrar_bien_db_id_bienes">
          <input name="registrar_bienes_db_codigo" type="hidden" id="registrar_bienes_db_codigo" size="13" maxlength="13"/>
          </td>
      </tr>
      <tr>
        <th>Serial:</th>
        <td colspan="3"><input name="registrar_bienes_db_serial" type="text" id="registrar_bienes_db_serial" size="10" maxlength="10" onFocus="barreando();" message="Introduzca el Serial Correspondiente al Bien." jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Inserte El Serial del Bien', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
      </tr>
      <tr>
        <th>Nombre:</th>
        <td colspan="3"><input name="registrar_bienes_db_nombre" type="text" id="registrar_bienes_db_nombre" size="30" maxlength="30" message="Introduzca el Nombre del Bien. Ejem: Computador HP, Escritorio" jval="{valid:/^[a-zA-Z&aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,30}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z&aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
      </tr>
      <tr>
        <th>Mayor:</th>
        <td colspan="3"><ul class="input_con_emergente">
          <li>
            <input name="registrar_bienes_db_mayor" type="text"  id="registrar_bienes_db_mayor" maxlength="30" size="30" readonly="true" message="Seleccione el Mayor" jval="{valid:/^[0-9a-zA-Z_]{1,60}$/, message:'Mayor Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9a-zA-Z_]/, cFunc:'alert', cArgs:['Mayor: '+$(this).val()]}" />
            <span class="btn_consulta_emergente">
              <input type="hidden" name="bien_db_id_mayor" id="bien_db_id_mayor">
              </span></li>
          <li id="bienes_db_btn_consulta_emergente_mayor" class="btn_consulta_emergente"></li>
          </ul></td>
      </tr>
      <tr>
        <th>Tipo de Bien:</th>
        <td colspan="3"><ul class="input_con_emergente">
				<li>
        <input name="registrar_bienes_db_tipo" type="text" id="registrar_bienes_db_tipo" size="30" maxlength="30" readonly="readonly" message="Seleccione el Tipo de Bien" jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Inserte El Tipo de Bien', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
        <span class="btn_consulta_emergente">
        <input type="hidden" name="bien_db_id_tipo" id="bien_db_id_tipo">
        </span></li>
          <li id="bienes_db_btn_consulta_emergente_tipo_bien" class="btn_consulta_emergente"></li>
        </ul></td>
      </tr>
      <tr>
        <th>Marca:</th>
        <td colspan="3"><input name="registrar_bienes_db_marca" type="text" id="registrar_bienes_db_marca" size="30" maxlength="30" message="Introduzca el Nombre de la Marca. Ejem: HP, Toyota, Samsung"/></td>
      </tr>
      <tr>
        <th>Modelo:</th>
        <td colspan="3"><input name="registrar_bienes_db_modelo" type="text" id="registrar_bienes_db_modelo" size="30" maxlength="30" message="Introduzca el Modelo del Bien."/></td>
      </tr>
      <tr>
        <th>A&ntilde;o:</th>
        <td colspan="3">
          <input name="registrar_bienes_db_ano" id="registrar_bienes_db_ano" type="text" size="6" maxlength="4" message="Introduzca el Año del Bien. Ejem: 2009" jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Año Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
      </tr>
      <tr>
        <th>Descripci&oacute;n:</th>
        <td colspan="3"><p>
          <textarea  name="registrar_bienes_db_comentarios2" cols="60" id="registrar_bienes_db_comentarios2" message="De una Breve Descripción del Bien"></textarea>
          </p></td>
      </tr>
      <tr>
        <th>Estatus:</th>
        <td colspan="3"><input id="registrar_bienes_db_estatus" name="registrar_bienes_db_estatus"  type="radio" value="2" checked="checked" />
Activo &nbsp;
<input id="registrar_bienes_db_estatus4" name="registrar_bienes_db_estatus"  type="radio" value="5" />
Inactivo
<input id="registrar_bienes_db_estatus3" name="registrar_bienes_db_estatus"  type="radio" value="4" disabled/>
Con Mejoras
<input id="registrar_bienes_db_estatus2" name="registrar_bienes_db_estatus"  type="radio" value="3" disabled/>
  Desincorporado&nbsp;
  <input name="estatus" type="hidden" id="estatus" value="2"></td>
      </tr>
      <tr>
        <th>Seguro:</th>
        <td width="95"><p>
          <label>
            <input type="radio" name="bienes_bien_seguro" value="1" id="bienes_bien_seguro_0" onClick="seguro_si();">
            Sí</label>
          &nbsp;
          <label>
            <input name="bienes_bien_seguro" onClick="seguro_no();" type="radio" id="bienes_bien_seguro_1" value="2" checked>
            No</label>
          <br>
        </p></td>
    
        <th width="61" id="th_seguro" style="display:none">Nº Poliza:</th>
        <td width="407" id="td_seguro" style="display:none"><label>
          <input name="bienes_bien_num_seguro" type="text" id="bienes_bien_num_seguro" size="10" maxlength="10" message="Escriba el Nº de la Poliza de Seguro del Activo.">
        </label></td>
      </tr>
      <tr>
        <th>Comentarios:</th>
        <td colspan="3"><textarea  name="registrar_bienes_db_comentarios" cols="60" id="registrar_bienes_db_comentarios" message="Introduzca un comentario."></textarea></td>
      </tr>
      <tr>
        <td colspan="4" class="bottom_frame">&nbsp;</td>
        </tr>
    
     
    </table>
  </div>
  <div id="pestana2" class="tabs-container">
    <table   class="cuerpo_formulario">
      <tr>
        <th style="border-top: 1px #BADBFC solid">Unidad:</th>
        <td style="border-top: 1px #BADBFC solid"><ul class="input_con_emergente">
				<li>
           <input name="registrar_bienes_db_unidad" type="text"  id="registrar_bienes_db_unidad" maxlength="60" size="40" readonly="true" message="Seleccione la Unidad para la ubicación del Bien." jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Inserte La Unidad', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
           <span class="btn_consulta_emergente">
           <input type="hidden" name="bien_db_id_unidad" id="bien_db_id_unidad">
           </span>                </li>
				<li id="bienes_db_btn_consulta_emergente_unidad" class="btn_consulta_emergente"></li>
			</ul></td>
      </tr>
      <tr>
        <th>Sitio F&iacute;sico:</th>
        <td><ul class="input_con_emergente">
				<li><input name="registrar_bienes_db_sitio_fisico" type="text" id="registrar_bienes_db_sitio_fisico" size="40" readonly="readonly" message="Seleccione el sitio Físico donde estará el Bien." jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Inserte El Sitio Físico', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}">
				  <span class="btn_consulta_emergente">
				  <input type="hidden" name="bien_db_id_sitio_fisico" id="bien_db_id_sitio_fisico">
			    </span></li>
        <li id="bienes_db_btn_consulta_emergente_sitio_fisico" class="btn_consulta_emergente"></li>
			</ul>
        </td>
      </tr>
      <tr>
        <th>Custodio:</th>
        <td><ul class="input_con_emergente">
				<li><input name="registrar_bienes_db_custodio" type="text" id="registrar_bienes_db_custodio" size="40" maxlength="60" readonly="readonly" message="Seleccione el Custodio del Bien" jval="{valid:/^[a-zA-Z0-9.]{1,10}$/, message:'Inserte El Custodio', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z0-9.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
				  <span class="btn_consulta_emergente">
				  <input type="hidden" name="bien_db_id_custodio" id="bien_db_id_custodio">
			    </span></li>
        <li id="bienes_db_btn_consulta_emergente_custodio" class="btn_consulta_emergente"></li>
			</ul></td>
      </tr>
      <tr>
        <td colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
    </table>
  </div>
  <div id="pestana3" class="tabs-container">
    <table   class="cuerpo_formulario">
      <tr>
        <th style="border-top: 1px #BADBFC solid">Vida Util:</th>
        <td style="border-top: 1px #BADBFC solid"><input name="registrar_bienes_db_vida_util" type="text" id="registrar_bienes_db_vida_util" style="text-align:right" size="4" maxlength="3" readonly="readonly">
          &nbsp;Mes(es)</td>
      </tr>
      <tr>
        <th>Valor Compra:</th>
        <td><input name="registrar_bienes_db_valor_compra" type="text" id="registrar_bienes_db_valor_compra" size="12" maxlength="12" message="Introduzca el Valor de Compra del Bien." alt="signed-decimal" onBlur="cal_rescate();"></td>
      </tr>
      <tr>
        <th>Valor Rescate:</th>
        <td><input name="registrar_bienes_db_valor_rescate" type="text" id="registrar_bienes_db_valor_rescate" size="12" maxlength="12" readonly="readonly" alt="signed-decimal" message="Introduzca el Valor de Rescate del Bien."></td>
      </tr>
      <tr>
        <th>Orden de Compra:</th>
        <td><ul class="input_con_emergente">
          <li>
            <input name="registrar_bienes_db_orden" type="text"  id="registrar_bienes_db_orden" maxlength="10" size="12" readonly="true" message="Seleccione la Orden de Compra" jval="{valid:/^[0-9a-zA-Z_]{1,60}$/, message:'Mayor Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9a-zA-Z_]/, cFunc:'alert', cArgs:['Mayor: '+$(this).val()]}" />
            <span class="btn_consulta_emergente">
              <input type="hidden" name="bien_db_id_orden" id="bien_db_id_orden">
              <input name="registrar_bienes_db_orden_ano" type="hidden"  id="registrar_bienes_db_orden_ano" maxlength="10" size="12" readonly="true" message="Seleccione el Mayor" jval="{valid:/^[0-9a-zA-Z_]{1,60}$/, message:'Mayor Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9a-zA-Z_]/, cFunc:'alert', cArgs:['Mayor: '+$(this).val()]}" />
            </span></li>
          <li id="bienes_db_btn_consulta_emergente_orden" class="btn_consulta_emergente"></li>
        </ul></td>
      </tr>
      <tr>
        <th>Factura:</th>
        <td><input name="registrar_bienes_db_factura" type="text" id="registrar_bienes_db_factura" size="12" maxlength="8"/></td>
      </tr>
      <tr>
        <th>Fecha Compra:</th>
        <td><input readonly="true" type="text" name="registrar_bienes_db_fecha_compra" id="registrar_bienes_db_fecha_compra" size="7" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}" onChange="fecha_depre();"/>
          <button type="reset" id="fecha_boton2"> ...</button>
          <input name="fecha_dep" type="hidden" id="fecha_dep">
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "registrar_bienes_db_fecha_compra",      
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_boton2",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script></td>
      </tr>
      <tr>
        <th>Cal. Depreciaci&oacute;n:</th>
        <td><p>
          <label>
            <input name="depreciacion" type="radio" id="depreciacion0"  onClick="" value="0" checked >
            Si</label>
          <label>
            &nbsp;&nbsp;
            <input type="radio" name="depreciacion" value="1" id="depreciacion1" onclick="valor_depre();" >
            No</label>
          <input type="hidden" name="val_depreciacion" id="val_depreciacion" value="0">
          <br>
        </p></td>
      </tr>
      <tr>
        <td colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
    </table>
<script language="javascript" type="text/javascript">
	function valor_depre(){
			
			if(getObj('val_depreciacion').value=="0")
			{
				getObj('val_depreciacion').value="0";
			}else
			if(getObj('val_depreciacion').value=="1")
			{
				getObj('val_depreciacion').value="0";
			}
						
		}
	
		/*if(getObj('depreciacion_1').checked=true){
			getObj('val_depreciacion').value=valor2;
		}*/
</script>
  </div>
  <div id="pestana4" class="tabs-container">
    <table   class="cuerpo_formulario">
      <tr>
        <th style="border-top: 1px #BADBFC solid">Serial Motor:</th>
        <td style="border-top: 1px #BADBFC solid;"><input name="registrar_bienes_db_serial_motor" type="text" id="registrar_bienes_db_serial_motor" size="20" maxlength="20" message="Introduzca el Serial del Motor. Ejem: 457HLKJ78-5"></td>
      </tr>
      <tr>
        <th>Serial Carrocería:</th>
        <td><input name="registrar_bienes_db_serial_car" type="text" id="registrar_bienes_db_serial_car" size="20" maxlength="20" message="Introduzca el Serial de la Carrocería. Ejem: 457HL-KJ785"></td>
      </tr>
      <tr>
        <th>Color:</th>
        <td><input name="registrar_bienes_db_color" type="text" id="registrar_bienes_db_color" size="20" maxlength="20" message="Introduzca el Color del Vehículo"></td>
      </tr>
      <tr>
        <th>Placa:</th>
        <td><input name="registrar_bienes_db_placa" type="text" id="registrar_bienes_db_placa" size="8" maxlength="8" message="Introduzca la Placa del Vehículo"></td>
      </tr>
      <tr>
        <td colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
    </table>
  </div>
</div>
</div>
<p><input type="hidden" name="bienes_db_fechact" id="bienes_db_fechact" value="<?php echo date("d-m-Y");?>"/></p>
</form>
</body>
</html>