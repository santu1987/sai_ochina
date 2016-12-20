<script type='text/javascript'>
var dialog;
//
//
//

$("#clasificador_presupuestario_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/clasificador_presupuestario/db/grid_clasificador_presupuestario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#clasificador_presupuestario_db-consultas-busqueda_nombre").val(); 
					var busq_partida= jQuery("#clasificador_presupuestario_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#clasificador_presupuestario_db-consultas-busqueda_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#clasificador_presupuestario_db-consultas-busqueda_partida").keypress(function(key)
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
							var busq_nombre= jQuery("#clasificador_presupuestario_db-consultas-busqueda_nombre").val();
							var busq_partida= jQuery("#clasificador_presupuestario_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?busq_nombre="+busq_nombre+"&busq_partida="+busq_partida,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Grupo','Clasificador Presupuestario','Id_Grupo','Nº Partida','Gen&eacute;rica','Espec&iacute;fica', 'Sub-Espec&iacute;fica', 'Tipo', 'Cuenta Contable', 'Comentario','desc_cuenta','id_contab'],
								colModel:[
									{name:'id_clasi_presu',index:'id_clasi_presu', width:50,sortable:false,resizable:false,hidden:true},
									{name:'partidas',index:'partidas', width:50,sortable:false,resizable:false},
									{name:'nombreGrupo',index:'nombreGrupo', width:80,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false},
									{name:'grupo',index:'grupo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:100,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'subespecifica',index:'subespecifica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'desc_cuenta',index:'desc_cuenta', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_contab',index:'id_contab', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('clasificador_presupuestario_db_id').value = ret.id_clasi_presu;
									getObj('clasificador_presupuestario_db_denominacion').value = ret.denominacion;	
									getObj('clasificador_presupuestario_db_partida').value = ret.partida;
									getObj('clasificador_presupuestario_db_generica').value = ret.generica;
									getObj('clasificador_presupuestario_db_especifica').value = ret.especifica;
									getObj('clasificador_presupuestario_db_subespecifica').value = ret.subespecifica;
									getObj('clasificador_presupuestario_db_grupo').value = ret.grupo;	
									getObj('clasificador_presupuestario_db_tipo').value = ret.tipo;
									getObj('clasificador_presupuestario_db_cuenta_contable').value = ret.cuenta_contable;
									getObj('clasificador_presupuestario_db_comentario').value = ret.comentario;
									getObj('clasificador_presupuestario_db_cuenta_contable_desc').value = ret.desc_cuenta;
									getObj('clasificador_presupuestario_db_cuenta_contable_id').value = ret.id_contab;
									getObj('clasificador_presupuestario_db_btn_actualizar').style.display='';
									getObj('clasificador_presupuestario_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#clasificador_presupuestario_db-consultas-busqueda_nombre").focus();
								$('#clasificador_presupuestario_db-consultas-busqueda_nombre').alpha({allow:' '});
								$('#clasificador_presupuestario_db-consultas-busqueda_partida').numeric({allow:''});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
//
/*$("#clasificador_presupuestario_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/clasificador_presupuestario/db/grid_clasificador_presupuestario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Clasificador Presupuestario', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_partida= jQuery("#clasificador_presupuestario_db-consultas-busqueda_partida").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 
				}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#clasificador_presupuestario_db-consultas-busqueda_partida").keypress(function(key)
				{
						if (key.keycode==13)$("#clasificador_presupuestario_db-consultas-busqueda_boton_filtro")
						getObj('clasificador_presupuestario_db-consultas-busqueda_nombre').value=""
						if(key.keyCode==27){this.close();}
				});
					function clasificador_presupuestario_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(clasificador_presupuestario_gridReload,500)
						}
					function clasificador_presupuestario_gridReload()
					{
							var busq_partida= jQuery("#clasificador_presupuestario_db-consultas-busqueda_partida").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid"); 			
						}

					$("#clasificador_presupuestario_db-consultas-busqueda_nombre").keypress(function(key)
						{
							if (key.keycode==13)$("#clasificador_presupuestario_db-consultas-busqueda_boton_filtro")
							getObj('clasificador_presupuestario_db-consultas-busqueda_partida').value=""
							if(key.keyCode==27){this.close();}
							
						});
					function clasificador_presupuestario_nombre_dosearch()
					{
							if(!flAuto) return; 
								if(timeoutHnd) 
								clearTimeout(timeoutHnd) 
								timeoutHnd = setTimeout(clasificador_presupuestario_nombre_gridReload,500)
					}
					function clasificador_presupuestario_nombre_gridReload()
					{
								var busq_nombre= jQuery("#clasificador_presupuestario_db-consultas-busqueda_nombre").val();
								jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 			
					}
				
					$("#clasificador_presupuestario_db-consultas-busqueda_boton_filtro").click(function(){
					        clasificador_presupuestario_dosearch();
							if(getObj('clasificador_presupuestario_db-consultas-busqueda_partida').value!="")clasificador_presupuestario_dosearch();
    					    if(getObj('clasificador_presupuestario_db-consultas-busqueda_nombre').value!="")clasificador_presupuestario_nombre_dosearch();
						    
							
						});
				}
		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/clasificador_presupuestario/db/sql_grid_clasificador_presupuestario.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Partida','Grupo','Clasificador Presupuestario','Id_Grupo','Nº Partida','Gen&eacute;rica','Espec&iacute;fica', 'Sub-Espec&iacute;fica', 'Tipo', 'Cuenta Contable', 'Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ipartida',index:'ipartida', width:70,sortable:false,resizable:false},
									{name:'gruponombre',index:'gruponombre', width:100,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:300,sortable:false,resizable:false},
									{name:'grupo',index:'grupo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:221,sortable:false,resizable:false,hidden:true},
									{name:'generica',index:'generica', width:100,sortable:false,resizable:false,hidden:true},
									{name:'especifica',index:'especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'sub_especifica',index:'sub_especifica', width:110,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:110,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('clasificador_presupuestario_db_id').value = ret.id;
									getObj('clasificador_presupuestario_db_denominacion').value = ret.denominacion;	
									getObj('clasificador_presupuestario_db_partida').value = ret.partida;
									getObj('clasificador_presupuestario_db_generica').value = ret.generica;
									getObj('clasificador_presupuestario_db_especifica').value = ret.especifica;
									getObj('clasificador_presupuestario_db_subespecifica').value = ret.sub_especifica;
									getObj('clasificador_presupuestario_db_grupo').value = ret.grupo;	
									getObj('clasificador_presupuestario_db_tipo').value = ret.tipo;
									getObj('clasificador_presupuestario_db_cuenta_contable').value = ret.cuenta_contable;
									getObj('clasificador_presupuestario_db_comentario').value = ret.comentario;
									getObj('clasificador_presupuestario_db_btn_actualizar').style.display='';
									getObj('clasificador_presupuestario_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
									$('#clasificador_presupuestario_db-consultas-busqueda_partida').numeric();
									$('#clasificador_presupuestario_db-consultas-busqueda_nombre').alpha();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_clasi_presu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

/*$("#clasificador_presupuestario_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/clasificador_presupuestario/db/grid_clasificador_presupuestario.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Clasificador Presupuestario',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

		});
*/
$("#clasificador_presupuestario_db_btn_guardar").click(function() {
	if($('#form_db_clasificador').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
		url: "modulos/presupuesto/clasificador_presupuestario/db/sql.clasificador_presupuestario.php",
			data:dataForm('form_db_clasificador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					//clearForm('form_db_clasificador');
					getObj('clasificador_presupuestario_db_denominacion').value = "";	
					getObj('clasificador_presupuestario_db_especifica').value = "";
					getObj('clasificador_presupuestario_db_subespecifica').value = "";
					getObj('clasificador_presupuestario_db_cuenta_contable').value = "";
					getObj('clasificador_presupuestario_db_comentario').value = "";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);
					
				}
			}
		});
	}
});

$("#clasificador_presupuestario_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/clasificador_presupuestario/db/sql.actualizar.php",
			data:dataForm('form_db_clasificador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('clasificador_presupuestario_db_btn_actualizar').style.display='none';
					getObj('clasificador_presupuestario_db_btn_guardar').style.display='';
					partida=getObj('clasificador_presupuestario_db_partida').value;
					generica=getObj('clasificador_presupuestario_db_generica').value;
					clearForm('form_db_clasificador');
					getObj('clasificador_presupuestario_db_partida').value=partida;
					getObj('clasificador_presupuestario_db_generica').value=generica;
				}
				else
				{
					setBarraEstado(html);
				}
				
			}
		});
	});
$("#clasificador_presupuestario_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('clasificador_presupuestario_db_btn_cancelar').style.display='';
	getObj('clasificador_presupuestario_db_btn_actualizar').style.display='none';
	getObj('clasificador_presupuestario_db_btn_guardar').style.display='';
	getObj('clasificador_presupuestario_db_grupo').value=3;
	getObj('clasificador_presupuestario_db_tipo').value=1;
	clearForm('form_db_clasificador');
});
function consulta_automatica_partida()
{
	if ((getObj('clasificador_presupuestario_db_partida')!=" ") && (getObj('clasificador_presupuestario_db_generica')!=" ")&&(getObj('clasificador_presupuestario_db_especifica')!=" ")&&(getObj('clasificador_presupuestario_db_subespecifica')!=" "))
	{
	$.ajax({
			url:"modulos/presupuesto/clasificador_presupuestario/db/sql.clasificador_presupuestario_subespecifica.php",
            data:dataForm('form_db_clasificador'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				//alert(html);
				if(recordset)
				{
				recordset = recordset.split(".");
				getObj('clasificador_presupuestario_db_id').value = recordset[0].substring(1);
				getObj('clasificador_presupuestario_db_grupo').value=recordset[5];
				getObj('clasificador_presupuestario_db_tipo').value=recordset[14];
				getObj('clasificador_presupuestario_db_comentario').value =recordset[13];
			 	getObj('clasificador_presupuestario_db_denominacion').value=recordset[6];
				getObj('clasificador_presupuestario_db_cuenta_contable').value=recordset[12];
				getObj('clasificador_presupuestario_db_btn_actualizar').style.display='';
				getObj('clasificador_presupuestario_db_btn_guardar').style.display='none';	
				getObj('clasificador_presupuestario_db_cuenta_contable_desc').value=recordset[15];
				getObj('clasificador_presupuestario_db_cuenta_contable_id').value =recordset[16];
				}
				else
			 {  
			   getObj('clasificador_presupuestario_db_id').value ="";
			    getObj('clasificador_presupuestario_db_grupo').value="";
				getObj('clasificador_presupuestario_db_tipo').value="";
				getObj('clasificador_presupuestario_db_comentario').value ="" ;
			 	getObj('clasificador_presupuestario_db_denominacion').value="" ;
				getObj('clasificador_presupuestario_db_cuenta_contable').value="";
				}
			 }
		});	 	 
	}	//alert(html);
}
function cuenta_contable_cod_presupuesto()
{///alert("entro");
$.ajax({
			url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_cod_pres.php",
            data:dataForm('form_db_clasificador'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('clasificador_presupuestario_db_cuenta_contable_id').value=recordset[0];
					getObj('clasificador_presupuestario_db_cuenta_contable_desc').value=recordset[2];
				}
				else
				{
					getObj('clasificador_presupuestario_db_cuenta_contable_id').value="";
					getObj('clasificador_presupuestario_db_cuenta_contable_desc').value="";				}
			 }
		});		
}

$("#clasificador_vista_consultar_cuenta").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
///
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/presupuesto/clasificador_presupuestario/db/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda2").keypress(
					function(key)
					{
						
						dosearch2();													
					}
				);		
				function dosearch2()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload2,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
					//alert(url);
				}
				//
				$("#consulta-cuenta-contable-busqueda-nombre").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);				
				function dosearch()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload()
				{
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
                 // ¿ alert(url);				
				}
			}
		}
	);
///						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/clasificador_presupuestario/db/sql_grid_cuenta_suma.php?nd='+nd,
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
									getObj('clasificador_presupuestario_db_cuenta_contable').value=ret.cuenta_contable;
									getObj('clasificador_presupuestario_db_cuenta_contable_id').value=ret.id;
									getObj('clasificador_presupuestario_db_cuenta_contable_desc').value=ret.nombre;
					
//									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
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
$('#clasificador_presupuestario_db_subespecifica').blur(consulta_automatica_partida)
$('#clasificador_presupuestario_db_subespecifica').change(consulta_automatica_partida)
$('#clasificador_presupuestario_db_especifica').change(consulta_automatica_partida)
$('#clasificador_presupuestario_db_generica').change(consulta_automatica_partida)
$('#clasificador_presupuestario_db_partida').change(consulta_automatica_partida)
$('#clasificador_presupuestario_db_denominacion').alpha({allow:'._1234567890- '});
$('#clasificador_presupuestario_db_cuenta_contable').alphanumeric({allow:'_'});
$('#clasificador_presupuestario_db_partida').numeric({allow:''});
$('#clasificador_presupuestario_db_generica').numeric({allow:''});
$('#clasificador_presupuestario_db_especifica').numeric({allow:''});
$('#clasificador_presupuestario_db_subespecifica').numeric({allow:''});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	
</script>
<div id="botonera">
	<img id="clasificador_presupuestario_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" /><img id="clasificador_presupuestario_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onclick="Application.evalCode('win_popup_armador', true);" /><img id="clasificador_presupuestario_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="clasificador_presupuestario_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" /></div>
<form method="post"  name="form_db_clasificador" id="form_db_clasificador">
	<table class="cuerpo_formulario">
		<tr>
			<th colspan="2" class="titulo_frame"><img src="imagenes/iconos/clasifica24x24.png" style="padding-right:5px;" align="absmiddle" /> Clasificaci&oacute;n Presupuestaria</th>
		</tr>
		<tr>
			<th>Partida : 				</th>
			<td>	<input name="clasificador_presupuestario_db_partida" type="text" id="clasificador_presupuestario_db_partida" size="3"  maxlength="3" message="Introduzca una Partida en el Siguiente Formato (301 o 401). " 
				jVal="{valid:/^[0-9]{3}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida" onblur="consulta_automatica_partida" ></td>
		</tr>
		<tr>
			<th> Gen&eacute;rica :		</th>
			<td>	<input name="clasificador_presupuestario_db_generica" type="text" id="clasificador_presupuestario_db_generica" size="3" maxlength="2"message="Introduzca la Partida Gen&eacute;rica." 
				jVal="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida"  onblur="consulta_automatica_partida" ></td>
		</tr>
		<tr>
			<th> Espec&iacute;fica:		</th>
			<td><input name="clasificador_presupuestario_db_especifica" type="text" id="clasificador_presupuestario_db_especifica" size="3" maxlength="2" message="Introduzca la Partida Especifica." 
				jval="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onchange="consulta_automatica_partida" onclick="consulta_automatica_partida" onblur="consulta_automatica_partida" /></td>
		</tr>
		<tr>
			<th> Sub-Espec&iacute;fica:	</th>
			<td><input name="clasificador_presupuestario_db_subespecifica" type="text" id="clasificador_presupuestario_db_subespecifica" size="3" maxlength="2" message="Introduzca la Partida Sub-Especifica."  
				jval="{valid:/^[0-9]{1,2}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}" onclick="consulta_automatica_partida" onchange="consulta_automatica_partida"  onblur="consulta_automatica_partida"  /></td>
		</tr>
		<tr>
			<th>Grupo:</th>
			<td >
				<select name="clasificador_presupuestario_db_grupo" id="clasificador_presupuestario_db_grupo" style="width:90px; min-width:90px;"  onfocus="consulta_automatica_partida">
					<option value="3">Recursos</option>
					<option value="4">Egresos</option>
				</select>			</td>
		</tr>
		<tr>
			<th>Tipo:</th>
			<td >
				<select name="clasificador_presupuestario_db_tipo" id="clasificador_presupuestario_db_tipo" style="width:90px; min-width:90px;">
					<option value="1">Titulo</option>
					<option value="2">Detalle</option>
				</select>			</td>
		</tr>
		<tr>
			<th>Cuenta Contable:			</th>
			<!--<td>	<input name="clasificador_presupuestario_db_cuenta_contable" type="text" id="clasificador_presupuestario_db_cuenta_contable" size="13" maxlength="12" message="Introduzca el Nombre de la Cuenta Contable. "
			jVal="{valid:/^[0-9]{10,20}$/, message:'Col&oacute;quele al menos 10 d&iacute;gitos ', styleType:'cover'}"
				jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"></td>-->
                 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="clasificador_presupuestario_db_cuenta_contable" id="clasificador_presupuestario_db_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable"  onblur="cuenta_contable_cod_presupuesto()"
				jval="{valid:/^[0-9]{1,12}$/, message:'C&oacute;digo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		       <input type="text" id="clasificador_presupuestario_db_cuenta_contable_desc"  name="clasificador_presupuestario_db_cuenta_contable_desc" readonly="readonly">
                <input type="hidden" id="clasificador_presupuestario_db_cuenta_contable_id" name="clasificador_presupuestario_db_cuenta_contable_id" />
		 </li>
		<li id="clasificador_vista_consultar_cuenta" class="btn_consulta_emergente"></li>
	    </ul>	  </td>	
		</tr>
		<tr>
			<th>Denominaci&oacute;n:		</th>
			<td>	<input name="clasificador_presupuestario_db_denominacion" type="text" id="clasificador_presupuestario_db_denominacion" style="width:62ex" maxlength="60" message="Introduzca el Nombre de la Clasificacion." 
				jVal="{valid:/^[a-zA-Z // ,áéíóúÁÉÍÓÚ _1234567890-]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z //,áéíóúÁÉÍÓÚ _1234567890-]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>	

		<tr>
			<th>Comentario:				</th>
			<td>	<textarea name="clasificador_presupuestario_db_comentario" id="clasificador_presupuestario_db_comentario" cols="65" rows="3"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="clasificador_presupuestario_db_id" id="clasificador_presupuestario_db_id" />
</form>
