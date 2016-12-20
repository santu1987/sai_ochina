<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#adquisiciones_impuesto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/impuesto/db/vista.grid_adquisiciones_nombre2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#adquisiciones_impuesto_db_nombre2").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#adquisiciones_impuesto_db_nombre2").keypress(function(key)
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
							var busq_nombre= jQuery("#adquisiciones_impuesto_db_nombre2").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre2.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre2.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Denominacion','aa','aa','aa','aa','aa','aa','aa','aa','cuenta_contable'],
								colModel:[
									{name:'id_impuesto',index:'id_impuesto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_impuesto',index:'codigo_impuesto', width:15,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'id_organismo',index:'id_organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:100,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'sub_especifica',index:'sub_especifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('adquisiciones_vista_impuesto').value = ret.id_impuesto;
									getObj('adquisiciones_impuesto_db_codigo').value=ret.codigo_impuesto;
									getObj('adquisiciones_impuesto_db_nombre').value = ret.nombre;
									getObj('adquisiciones_impuesto_pr_partida_numero').value = ret.partida+"."+ret.generica+"."+ret.especifica+"."+ret.sub_especifica;
									getObj('adquisiciones_impuesto_pr_partida').value = ret.denominacion;
									getObj('adquisiciones_impuesto_db_cuenta_contable').value = ret.cuenta_contable;
									getObj('adquisiciones_impuesto_db_observacion').value = ret.comentario;
									getObj('adquisiciones_impuesto_db_btn_cancelar').style.display='';
									getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='';
									//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='';
									getObj('adquisiciones_impuesto_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#adquisiciones_impuesto_db_nombre2").focus();
								$('#adquisiciones_impuesto_db_nombre2').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_impuesto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
/*$("#adquisiciones_impuesto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/impuesto/db/grid_impuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de impuesto',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:300,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/impuesto/db/sql_grid_impuesto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Impuesto','idorganismo','Organismo','Comentario','partida','generica','especifica','sub_especifica','denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'impuesto',index:'impuesto', width:70,sortable:false,resizable:false},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false,hidden:true },
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:110,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'sub_especifica',index:'sub_especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:110,sortable:false,resizable:false,hidden:true},
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('adquisiciones_vista_impuesto').value = ret.id;
									getObj('adquisiciones_impuesto_db_codigo').value=ret.codigo;
									getObj('adquisiciones_impuesto_db_nombre').value = ret.impuesto;
									//fd=ret.fecha.substr(0, 10);
									//fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4); 
									//getObj('adquisiciones_impuesto_db_fecha').value = fds;
									getObj('adquisiciones_impuesto_pr_partida_numero').value = ret.partida+"."+ret.generica+"."+ret.especifica+"."+ret.sub_especifica;
									getObj('adquisiciones_impuesto_pr_partida').value = ret.denominacion;
									getObj('adquisiciones_impuesto_db_observacion').value = ret.comentario;
									getObj('adquisiciones_impuesto_db_btn_cancelar').style.display='';
									getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='';
									//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='';
									getObj('adquisiciones_impuesto_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_adquisiciones_db_impuesto').jVal();
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
})*/


//boton consultar partida
//
//
$("#adquisiciones_impuesto_btn_consultar_partida").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/impuesto/db/vista.grid_adquisiciones_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#adquisiciones_impuesto_db_nombre").val(); 
					var busq_partida=jQuery().val('#adquisiciones_impuesto_db_partida').val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#adquisiciones_impuesto_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#adquisiciones_impuesto_db_partida").keypress(function(key)
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
							var busq_nombre= jQuery("#adquisiciones_impuesto_db_nombre").val();
							var busq_partida=jQuery("#adquisiciones_impuesto_db_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/impuesto/db/sql_adquisiciones_impuesto_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Denominacion'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:15,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('adquisiciones_impuesto_pr_partida_numero').value = ret.partida;
									getObj('adquisiciones_impuesto_pr_partida').value= ret.denominacion;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#adquisiciones_impuesto_db_nombre").focus();
								$('#adquisiciones_impuesto_db_nombre').alpha({allow:' '});
								$('#adquisiciones_impuesto_db_partida').numeric({allow:''});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_val_impu',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
/*$("#adquisiciones_impuesto_btn_consultar_partida").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/impuesto/db/grid_impuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/impuesto/db/sql_grid_impuesto_n.php?nd='+nd,
								datatype: "json",
								colNames:['Partida','Descripcion'],
								colModel:[
									
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:70,sortable:false,resizable:false},
									
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('adquisiciones_impuesto_pr_partida_numero').value = ret.partida;
									getObj('adquisiciones_impuesto_pr_partida').value= ret.descripcion;
									dialog.hideAndUnload();
									//$('#form_adquisiciones_db_impuesto').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
})*/

//

$("#adquisiciones_impuesto_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_adquisiciones_db_impuesto').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/impuesto/db/sql.actualizar.php",
			data:dataForm('form_adquisiciones_db_impuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					
				    getObj('adquisiciones_impuesto_db_btn_cancelar').style.display='';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
					//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
					clearForm('form_adquisiciones_db_impuesto');
				  	//getObj("adquisiciones_impuesto_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("adquisiciones_impuesto_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";	
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('adquisiciones_impuesto_db_btn_cancelar').style.display='';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
					//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
					clearForm('form_adquisiciones_db_impuesto');
					//getObj('adquisiciones_impuesto_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('adquisiciones_impuesto_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
					
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#adquisiciones_impuesto_db_btn_guardar").click(function() {
	if($('#form_adquisiciones_db_impuesto').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/adquisiones/impuesto/db/sql.registrar.php",
			data:dataForm('form_adquisiciones_db_impuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_adquisiciones_db_impuesto');
					getObj('adquisiciones_impuesto_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('adquisiciones_impuesto_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_adquisiciones_db_impuesto');
					getObj('adquisiciones_impuesto_db_fecha').value = "<?= date("d/m/Y"); ?>";
					getObj('adquisiciones_impuesto_db_fecha_oculto').value = "<?= date("d/m/Y"); ?>";
					}
					else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					
				}
			
			}
		});
	}
});

$("#adquisiciones_impuesto_db_btn_consultar_impuesto").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/impuesto/db/grid_impuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/impuesto/db/cmb.sql.organismo.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Organismo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'organismo',index:'organismo', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#adquisiciones_impuesto_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
	//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
	getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
	getObj('adquisiciones_impuesto_db_btn_consultar').style.display='';
	clearForm('form_adquisiciones_db_impuesto');
	//getObj("adquisiciones_impuesto_db_fecha").value = "<?=  date("d/m/Y"); ?>";
	getObj("adquisiciones_impuesto_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
});

/*
$("#adquisiciones_impuesto_db_btn_eliminar").click(function() {
  if (getObj('adquisiciones_vista_impuesto').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/adquisiones/impuesto/db/sql.eliminar.php",
			data:dataForm('form_adquisiciones_db_impuesto'),
			type:'POST',
			cache: false,
			success: function(html)
			{ 
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
					clearForm('form_adquisiciones_db_impuesto');
					//getObj("adquisiciones_impuesto_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("adquisiciones_impuesto_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
  }
});
*/
//consultas automaticas
function consulta_automatica_impuesto()
{
	if (getObj('adquisiciones_impuesto_db_codigo')!=" ")
	{
	$.ajax({
			url:"modulos/adquisiones/impuesto/db/sql_grid_codigo.php",
            data:dataForm('form_adquisiciones_db_impuesto'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				//alert(html);
				getObj('adquisiciones_impuesto_db_observacion').value=html;
					if(recordset)
				{
					recordset = recordset.split("*");
					getObj('adquisiciones_vista_impuesto').value = recordset[0];
					getObj('adquisiciones_impuesto_db_nombre').value = recordset[1];
					//fd=recordset[4].substr(0, 10);
					//fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4); 
					//getObj('adquisiciones_impuesto_db_fecha').value = fds;
					getObj('adquisiciones_impuesto_db_observacion').value = recordset[4];
					getObj('adquisiciones_impuesto_pr_partida_numero').value = recordset[5]+"."+recordset[6]+"."+recordset[7]+"."+recordset[8];
					getObj('adquisiciones_impuesto_pr_partida').value = recordset[9];
					getObj('adquisiciones_impuesto_db_btn_cancelar').style.display='';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='';
					//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='';
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='none'
				 }
				 else
				 {
				 	setBarraEstado("");
					getObj('adquisiciones_impuesto_db_btn_guardar').style.display='';
					//getObj('adquisiciones_impuesto_db_btn_eliminar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_actualizar').style.display='none';
					getObj('adquisiciones_impuesto_db_btn_consultar').style.display='';
					a=getObj('adquisiciones_impuesto_db_codigo').value;
					clearForm('form_adquisiciones_db_impuesto');
					getObj('adquisiciones_impuesto_db_codigo').value=a;
					getObj("adquisiciones_impuesto_db_fecha").value = "<?=  date("d/m/Y"); ?>";
					getObj("adquisiciones_impuesto_db_fecha_oculto").value = "<?=  date("d/m/Y"); ?>";
				 }
			 }
		});	 	 
	}	
}
//
$("#adquisiciones_impuesto_db_btn_consultar_cuenta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/impuesto/db/grid_impuesto.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/adquisiones/impuesto/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#adquisiciones_impuesto_db_cuenta_contable').val(ret.cuenta_contable);
									$('#valor_impuesto_db_id_cuenta').val(ret.id);
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
								sortname: 'cuenta_contable',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

//
adquisiciones_impuesto_db_btn_consultar_auxiliar
$('#adquisiciones_impuesto_db_codigo').change(consulta_automatica_impuesto)
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#adquisiciones_impuesto_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#adquisiciones_impuesto_db_organismo').alpha({allow:' áéíóúÄÉÍÓÚ'});
$('#adquisiciones_impuesto_db_fecha').numeric({allow:'/-'});
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
	<img id="adquisiciones_impuesto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <!--<img id="adquisiciones_impuesto_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/> --><img id="adquisiciones_impuesto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" /><img id="adquisiciones_impuesto_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="adquisiciones_impuesto_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_adquisiciones_db_impuesto" name="form_adquisiciones_db_impuesto">
<input type="hidden"  id="adquisiciones_vista_impuesto" name="adquisiciones_vista_impuesto"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar  Impuesto </th>
	</tr>
	<tr>
		<th>C&oacute;digo:</th>
		 <td>
		    	<input type="text" name="adquisiciones_impuesto_db_codigo" id="adquisiciones_impuesto_db_codigo"  style="width:6ex;" 
				 onchange="consulta_automatica_impuesto" onclick="consulta_automatica_impuesto"message="Introduzca el Codigo del Impuesto." 
				jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ1234567890]{1,4}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ1234567890]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		 </td>
		</tr>
	<tr>
		<th>Nombre:		</th>	
	    <td>	
		<input name="adquisiciones_impuesto_db_nombre" type="text" id="adquisiciones_impuesto_db_nombre"   value="" size="40" maxlength="60" message="Introduzca un Nombre del impuesto. Ejem: ''IVA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>
    
    
    <tr>
			<th>Partida :	 </th>
			<td>
			<ul class="input_con_emergente">
				<li>
					<input name="adquisiciones_impuesto_pr_partida_numero" type="text" id="adquisiciones_impuesto_pr_partida_numero" size="9" maxlength="12"/>
					<input name="adquisiciones_impuesto_pr_partida" type="text" id="adquisiciones_impuesto_pr_partida" style="width:57ex" maxlength="100" readonly 
					message="Introduzca la Partidad Presupuestaria" jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0,.-_123456789]{1,100}$/, message:'Partidad no Invalida', styleType:'cover'}"
					/>
				</li>
				<li id="adquisiciones_impuesto_btn_consultar_partida" class="btn_consulta_emergente"></li>
			</ul>
			
			</td>
		</tr>
    <tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="adquisiciones_impuesto_db_cuenta_contable" id="adquisiciones_impuesto_db_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="valor_impuesto_db_id_cuenta" name="valor_impuesto_db_id_cuenta" />
		 </li>
		<li id="adquisiciones_impuesto_db_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
	    </ul>	  </td>
		</tr>
    
    
	<tr>
		<th>Observaci&oacute;n:</th>
		<td><textarea  name="adquisiciones_impuesto_db_observacion" cols="60" id="adquisiciones_impuesto_db_observacion" message="Introduzca una Observación. Ejem:'Este impuesto es ...' " style="width:422px"></textarea>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
</table>
<input  name="adquisiciones_impuesto_db_id" type="hidden" id="impuesto_db_id" />
</form>