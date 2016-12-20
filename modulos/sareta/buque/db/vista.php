<script type='text/javascript'>
var dialog;
$("#sareta_buque_db_btn_consultar_bandera").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/db/grid_bandera.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Banderas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_bandera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_bandera.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/db/sql_grid_bandera.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/db/sql_grid_bandera.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_bandera').value = ret.id;
									getObj('sareta_buque_db_bandera').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_db_buque').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//------------------------------------------------------------------------------------------------

$("#sareta_buque_db_btn_consultar_actividad").click(function() {
var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/buque/db/grid_tipo_actividad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Actividades',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/db/sql_grid_tipo_actividad.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_actividad').value = ret.id;
									getObj('sareta_buque_db_actividad').value = ret.nombre;
		
									dialog.hideAndUnload();
									$('#form_db_buque').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
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
//------------------------------------------------------------------------------------------------

$("#sareta_buque_db_btn_consultar_clase").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/db/grid_clases_buques.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Clases de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_clases_buques.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/db/sql_grid_clases_buques.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/db/sql_grid_clases_buques.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_clase').value = ret.id;
									getObj('sareta_buque_db_clase').value = ret.nombre;	
									dialog.hideAndUnload();
									$('#form_db_buque').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//------------------------------------------------------------------------------------------------

$("#sareta_buque_db_btn_consultar_ley").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/db/grid_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Leyes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/db/sql_grid_ley.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/db/sql_grid_ley.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Articulo','Parágrafo','Descripción','des','Tasa','codigo_tasa','Tarifa','Activo','Tonelaje Inicial','Tonelaje Final','Comentario','con'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'articulo',index:'articulo', width:220,sortable:false,resizable:false},
									{name:'paragrafo',index:'paragrafo', width:220,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:220,sortable:false,resizable:false},
									{name:'des',index:'des', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tasa',index:'tasa', width:220,sortable:false,resizable:false},
									{name:'codigo_tasa',index:'codigo_tasa', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa',index:'tarifa', width:220,sortable:false,resizable:false},
									{name:'activo',index:'activo', width:220,sortable:false,resizable:false},
									{name:'tonelaje_inicial',index:'tonelaje_inicial', width:220,sortable:false,resizable:false},
									{name:'tonelaje_final',index:'tonelaje_final', width:220,sortable:false,resizable:false},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_ley').value = ret.id;
									getObj('sareta_buque_db_ley').value = ret.des;
								
									
									dialog.hideAndUnload();
									$('#form_db_buque').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	
//------------------------------------------------------------------------------------------------

$("#sareta_buque_db_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/buque/db/grid_buque.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Buques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/buque/db/sql_grid_buque.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/buque/db/sql_grid_buque.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/buque/db/sql_grid_buque.php?nd='+nd,
								datatype: "json",
								colNames:['id','Matricula','Call Sign','Nombre','nombre','id_bandera','Bandera','bandera',
								'R. Bruto','id_actividad','Actividad','actividad','id_clase','Clase','clase','Nac/Ext','Pago Anual',
								'id_ley','Ley','ley','Exonerado','com'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'matricula',index:'matricula', width:220,sortable:false,resizable:false},
									{name:'call_sign',index:'call_sign', width:220,sortable:false,resizable:false},
									
									{name:'nombre1',index:'nombre1', width:220,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_bandera',index:'id_bandera', width:220,sortable:false,resizable:false,hidden:true},
									{name:'bandera1',index:'bandera1', width:220,sortable:false,resizable:false},
									{name:'bandera',index:'bandera', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'r_bruto',index:'r_bruto', width:220,sortable:false,resizable:false},
									
									{name:'id_actividad',index:'id_actividad', width:220,sortable:false,resizable:false,hidden:true},
									{name:'actividad1',index:'actividad1', width:220,sortable:false,resizable:false},
									{name:'actividad',index:'actividad', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'id_clase',index:'id_clase', width:220,sortable:false,resizable:false,hidden:true},
									{name:'clase1',index:'clase1', width:220,sortable:false,resizable:false},
									{name:'clase',index:'clase', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'nac',index:'nac', width:220,sortable:false,resizable:false},
									{name:'pago_anual',index:'pago_anual', width:220,sortable:false,resizable:false},
									
									{name:'id_ley',index:'id_ley', width:220,sortable:false,resizable:false,hidden:true},
									{name:'ley1',index:'ley1', width:220,sortable:false,resizable:false},
									{name:'ley',index:'ley', width:220,sortable:false,resizable:false,hidden:true},
									
									{name:'exonerado',index:'exonerado', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_buque').value = ret.id;
									getObj('sareta_buque_db_matricula').value = ret.matricula;
									getObj('sareta_buque_db_call_sign').value = ret.call_sign;
									getObj('sareta_buque_db_nombre').value = ret.nombre;
									getObj('sareta_buque_db_rb').value = ret.r_bruto;
									getObj('id_ley').value = ret.id_ley;
									getObj('sareta_buque_db_ley').value = ret.ley;
									getObj('id_clase').value = ret.id_clase;
									getObj('sareta_buque_db_clase').value = ret.clase;	
									getObj('id_actividad').value = ret.id_actividad;
									getObj('sareta_buque_db_actividad').value = ret.actividad;
									getObj('id_bandera').value = ret.id_bandera;
									getObj('sareta_buque_db_bandera').value = ret.bandera;
									   
									if(ret.nac=="NACIONAL"){
									getObj('buque_db_Nac').selectedIndex =0;
									}else{getObj('buque_db_Nac').selectedIndex =1;
									}
									
									if(ret.pago_anual=="SI"){
									getObj('buque_db_Pago_anual').selectedIndex =0;
									}else{getObj('buque_db_Pago_anual').selectedIndex =1;
									}
									
									if(ret.exonerado=="SI"){
									getObj('buque_db_exonerado').selectedIndex =0;
									}else{getObj('buque_db_exonerado').selectedIndex =1;
									}
									
									getObj('sareta_buque_db_vista_observacion').value = ret.com;
									dialog.hideAndUnload();
									getObj('sareta_buque_db_btn_cancelar').style.display='';
									getObj('sareta_buque_db_btn_eliminar').style.display='';
									getObj('sareta_buque_db_btn_guardar').style.display='none';
									getObj('sareta_buque_db_btn_actualizar').style.display='';
									dialog.hideAndUnload();
									$('#form_db_buque').jVal();
									
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
	
//------------------------------------------------------------------------------------------------
	
	
$("#sareta_buque_db_btn_guardar").click(function() {
	if($('#form_db_buque').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/buque/db/sql.registrar.php",
			data:dataForm('form_db_buque'),
			
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_buque');
						getObj('buque_db_Nac').selectedIndex =0;
						getObj('buque_db_Pago_anual').selectedIndex =0;
						getObj('buque_db_exonerado').selectedIndex =0;
						getObj('sareta_buque_db_rb').value ='0,00';
					});					
				}
				else if (html=="NoRegistroMatricula")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>La Matricula se Encuentra Registrada…</p></div>",true,true);
				}
				else if (html=="NoRegistroCall_sign")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>La Call Sign se Encuentra Registrada…</p></div>",true,true);
				}
				else if (html=="arqueo_bruto_no_coresponde")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El R. Bruto no esta sujet&oacute; al pago de esta ley verifique…</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_buque_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_buque').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/buque/db/sql.actualizar.php",
			data:dataForm('form_db_buque'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_buque_db_btn_eliminar').style.display='none';
						getObj('sareta_buque_db_btn_actualizar').style.display='none';
						getObj('sareta_buque_db_btn_guardar').style.display='';
						clearForm('form_db_buque');
						getObj('buque_db_Nac').selectedIndex =0;
						getObj('buque_db_Pago_anual').selectedIndex =0;
						getObj('buque_db_exonerado').selectedIndex =0;
						getObj('sareta_buque_db_rb').value ='0,00';
					});															
				}
				else if (html=="arqueo_bruto_no_coresponde")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br>El R. Bruto no esta sujet&oacute; al pago de esta ley verifique…</p></div>",true,true);
				}
				else if (html=="NoActualizoMatricula")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La Matricula se Encuentra Registrada…</p></div>",true,true);
				}
				else if (html=="NoActualizoCall_sign")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La Call Sign se Encuentra Registrada…</p></div>",true,true);
				}
				
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_buque_db_btn_eliminar").click(function() {
  if (getObj('vista_id_buque').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/buque/db/sql.eliminar.php",
			data:dataForm('form_db_buque'),
			
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_buque_db_btn_eliminar').style.display='none';
					getObj('sareta_buque_db_btn_actualizar').style.display='none';
					getObj('sareta_buque_db_btn_guardar').style.display='';
					clearForm('form_db_buque');
					getObj('buque_db_Nac').selectedIndex =0;
					getObj('buque_db_Pago_anual').selectedIndex =0;
					getObj('buque_db_exonerado').selectedIndex =0;
					getObj('sareta_buque_db_rb').value ='0,00';
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta Buque</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_buque_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_buque_db_btn_cancelar').style.display='';
	getObj('sareta_buque_db_btn_eliminar').style.display='none';
	getObj('sareta_buque_db_btn_actualizar').style.display='none';
	getObj('sareta_buque_db_btn_guardar').style.display='';
	clearForm('form_db_buque');
	getObj('buque_db_Nac').selectedIndex =0;
	getObj('buque_db_Pago_anual').selectedIndex =0;
	getObj('buque_db_exonerado').selectedIndex =0;
	getObj('sareta_buque_db_rb').value ='0,00';
});
/* ------------------ Sub-Mascara jquery_moneda   ---------------------------*/
documentall = document.all;


function formatamoney(c) {
    var t = this; if(c == undefined) c = 2;		
    var p, d = (t=t.split("."))[1].substr(0, c);
    for(p = (t=t[0]).length; (p-=3) >= 1;) {
	        t = t.substr(0,p) + "." + t.substr(p);
    }
    return t+","+d+Array(c+1-d.length).join(0);
}

String.prototype.formatCurrency=formatamoney

function demaskvalue(valor, currency){

var val2 = '';
var strCheck = '0123456789';
var len = valor.length;
	if (len== 0){
		return 0.00;
	}

	if (currency ==true){	

		
		for(var i = 0; i < len; i++)
			if ((valor.charAt(i) != '0') && (valor.charAt(i) != ',')) break;
		
		for(; i < len; i++){
			if (strCheck.indexOf(valor.charAt(i))!=-1) val2+= valor.charAt(i);
		}

		if(val2.length==0) return "0.00";
		if (val2.length==1)return "0.0" + val2;
		if (val2.length==2)return "0." + val2;
		
		var parte1 = val2.substring(0,val2.length-2);
		var parte2 = val2.substring(val2.length-2);
		var returnvalue = parte1 + "." + parte2;
		return returnvalue;
		
	}
	else{
			val3 ="";
			for(var k=0; k < len; k++){
				if (strCheck.indexOf(valor.charAt(k))!=-1) val3+= valor.charAt(k);
			}			
	return val3;
	}
}

function reais(obj,event){

var whichCode = (window.Event) ? event.which : event.keyCode;

if (whichCode == 8 && !documentall) {	

	if (event.preventDefault){ //standart browsers
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	obj.value= demaskvalue(x,true).formatCurrency();
	return false;
}

FormataReais(obj,'.',',',event);
} // end reais


function backspace(obj,event){


var whichCode = (window.Event) ? event.which : event.keyCode;
if (whichCode == 8 && documentall) {	
	var valor = obj.value;
	var x = valor.substring(0,valor.length-1);
	var y = demaskvalue(x,true).formatCurrency();

	obj.value =""; 
	obj.value += y;
	
	if (event.preventDefault){ 
			event.preventDefault();
		}else{ // internet explorer
			event.returnValue = false;
	}
	return false;

	}		
}

function FormataReais(fld, milSep, decSep, e) {
var sep = 0;
var key = '';
var i = j = 0;
var len = len2 = 0;
var strCheck = '0123456789';
var aux = aux2 = '';
var whichCode = (window.Event) ? e.which : e.keyCode;

if (whichCode == 0 ) return true;
if (whichCode == 9 ) return true; //tecla tab
if (whichCode == 13) return true; //tecla enter
if (whichCode == 16) return true; //shift internet explorer
if (whichCode == 17) return true; //control no internet explorer
if (whichCode == 27 ) return true; //tecla esc
if (whichCode == 34 ) return true; //tecla end
if (whichCode == 35 ) return true;//tecla end
if (whichCode == 36 ) return true; //tecla home


if (e.preventDefault){ 
		e.preventDefault()
	}else{ 
		e.returnValue = false
}

var key = String.fromCharCode(whichCode);  
if (strCheck.indexOf(key) == -1) return false;  

fld.value += key;

var len = fld.value.length;
var bodeaux = demaskvalue(fld.value,true).formatCurrency();
fld.value=bodeaux;

  if (fld.createTextRange) {
    var range = fld.createTextRange();
    range.collapse(false);
    range.select();
  }
  else if (fld.setSelectionRange) {
    fld.focus();
    var length = fld.value.length;
    fld.setSelectionRange(length, length);
  }
  return false;

}
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos

	
	$('#sareta_buque_db_matricula').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñÑ'});
	$('#sareta_buque_db_call_sign').alpha({allow:'0123456789 áéíóúÁÉÍÓÚñÑ'});
	$('#sareta_buque_db_nombre').alpha({allow:'- 0123456789 áéíóúÁÉÍÓÚñÑ'});

</script>
<style type="text/css">
<!--
.style4 {color: #33CCFF}
-->
</style>



<div id="botonera">
	<img id="sareta_buque_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_buque_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_buque_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_buque_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_buque_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_buque" name="form_db_buque">
<input type="hidden" name="vista_id_buque" id="vista_id_buque" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Buque</th>
	</tr>
	<tr>
	    <th>Matricula:		</th>	
	  <td>
      <input name="sareta_buque_db_matricula" type="text" id="sareta_buque_db_matricula"   value="" size="15" maxlength="10"  
						message="Introduzca una Matricula para el Buque." 
						jval="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]{1,30}$/, message:'Matricula Invalida', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚÑñ]/, cFunc:'alert', cArgs:['Matricula: '+$(this).val()]}" /> 
      </td>
    </tr>
    <tr>                    
      <th>Call Sign:</th>
      <td>
      <input name="sareta_buque_db_call_sign" type="text" id="sareta_buque_db_call_sign" 
      size="15" maxlength="10"  
						message="Introduzca una Call Sign para el Buque." 
						jval="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]{1,30}$/, message:'Call Sign Invalida', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÑÚñ]/, cFunc:'alert', cArgs:['Call Sign: '+$(this).val()]}" />
       </td>
	</tr>
    <tr>
	<th>Nombre:</th>	
	<td>
		  <input name="sareta_buque_db_nombre" type="text" id="sareta_buque_db_nombre"   value="" size="60" maxlength="60"  
						message="Introduzca una Nombre para el Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚÑñ-]{1,60}$/, message:'Nombre  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÑÍÓÚñ-]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
              
	</tr>	
           <th>Bandera:		</th>	
	       <td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="id_bandera" id="id_bandera" />
		  <input name="sareta_buque_db_bandera" type="text" class="style4" id="sareta_buque_db_bandera"  value="" size="60" maxlength="60"  readonly
						message="Introduzca una Bandera para el Buque." 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ]{1,60}$/, message:'Bandera  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚÑñ]/, cFunc:'alert', cArgs:['Bandera: '+$(this).val()]}" />
         
                        
        <li id="sareta_buque_db_btn_consultar_bandera" class="btn_consulta_emergente"></li>
		    </ul></td>
	</tr>
    <tr>
	<th>R. Bruto: </th>	
	<td><input  name="sareta_buque_db_rb" type="text" id="sareta_buque_db_rb"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca un valor para R. Bruto" 
        jval="{valid:/^[0-9,.]{1,12}$/, message:'R. Bruto Invalido', styleType:'cover'}"
		valkey="{valid:/[0-9,.]/, cFunc:'alert',cArgs:['R. Bruto: '+$(this).val()]}"/></td>
	</tr>	
    <tr>
    	<th>Actividad:</th>
    	<td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="id_actividad" id="id_actividad" />
		  <input name="sareta_buque_db_actividad" type="text" class="style4" id="sareta_buque_db_actividad"  value="" size="60" maxlength="60"  readonly
						message="Introduzca una Actividad para el Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]{1,60}$/, message:'actividad  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚÑñ]/, cFunc:'alert', cArgs:['Actividad: '+$(this).val()]}" />
         
                        
        <li id="sareta_buque_db_btn_consultar_actividad" class="btn_consulta_emergente"></li>
		    </ul></td>
    </tr>
    <tr>
    	<th>Clase:</th>
    	<td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="id_clase" id="id_clase" />
		<input name="sareta_buque_db_clase" type="text" class="style4" id="sareta_buque_db_clase"  		value="" size="60" maxlength="60"  readonly
						message="Introduzca una Clase para el Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]{1,60}$/, message:'Clase  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñÑ]/, cFunc:'alert', cArgs:['Clase: '+$(this).val()]}" />
         
                        
        <li id="sareta_buque_db_btn_consultar_clase" class="btn_consulta_emergente"></li>
		    </ul></td>
    </tr>
<tr>
    	<th>Nac/Ext:</th>
    	<td>
        <select name="buque_db_Nac" id="buque_db_Nac"  style="width:100px; min-width:100px;" >
				<option value="true">NACIONAL</option>
				<option value="false">EXTRANJERO</option>
		</select> 
        <strong>Pago Anual:</strong>
        <select name="buque_db_Pago_anual" id="buque_db_Pago_anual"  style="width:60px; min-width:60px;" >
				<option value="true">SI</option>
				<option value="false">NO</option>
		</select> 
        </td>
    </tr>
    <tr>
    	<th>Articulo Ley:</th>
    	<td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="id_ley" id="id_ley" />
		<input name="sareta_buque_db_ley" type="text" class="style4" id="sareta_buque_db_ley"
        value="" size="60" maxlength="1000"  readonly
						message="Introduzca una Ley para el Buque." 
						jVal="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Ley  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Ley: '+$(this).val()]}" />
         
                        
        <li id="sareta_buque_db_btn_consultar_ley" class="btn_consulta_emergente"></li>
		    </ul></td>
    </tr>
    <tr>
    	<th>Exonerado:</th>
    	<td>
        <select name="buque_db_exonerado" id="buque_db_exonerado"  style="width:60px; min-width:60px;" >
				<option value="true">SI</option>
				<option value="false">NO</option>
		</select> 
        </td>
     </tr>
	<tr>
		<th>Comentario:</th>			
        <td ><textarea name="sareta_buque_db_vista_observacion" cols="60" 
        id="sareta_buque_db_vista_observacion"  
        message="Introduzca una Observación. "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>