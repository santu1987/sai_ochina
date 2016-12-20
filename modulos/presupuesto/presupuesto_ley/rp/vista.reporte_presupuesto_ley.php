<?php session_start(); ?>
<!-- vista.lista.presupuesto_movimiento.php -->
<!-- pdf.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto.php  -->
<script>
var dialog;
$("#ante_pro_presupuesto_rp_btn_imprimir").click(function() {
//alert('aqui');
	//mes_hasta = eval(getObj("ante_pro_presupuesto_rp_cmb_mes_hasta").value);
	//mes_desde = eval(getObj("ante_pro_presupuesto_rp_cmb_mes_desde").value);
	//if (mes_hasta >= mes_desde){
	//////////////////////////////////////////////// TODAS LAS UNIDADES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_todas_unidad').checked  == true) && ((getObj('ante_pro_presupuesto_rp_todas_accion_cen').checked  == true) || (getObj('ante_pro_presupuesto_rp_todas_proyecto').checked  == true) ))
		{
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_todo1.PHP!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
	//////////////////////////////////////////////// UNA UNIDAD \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_una_unidad').checked  == true) && ((getObj('ante_pro_presupuesto_rp_todas_accion_cen').checked  == true) || (getObj('ante_pro_presupuesto_rp_todas_proyecto').checked  == true)) )
		{
			//alert('unidad');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
	//////////////////////////////////////////////// UNA UNIDAD UNA CENTRAL \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_una_unidad').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_accion_cen').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == false))
		{
		//	alert('central');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_acc_cen.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@id="+getObj('ante_pro_presupuesto_rp_id_accion').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
	//////////////////////////////////////////////// UNA CENTRAL \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_una_unidad').checked  == false) && (getObj('ante_pro_presupuesto_rp_una_accion_cen').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == false))
		{
		//	alert('central');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_accion_cen_nuevo.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@id="+getObj('ante_pro_presupuesto_rp_id_accion').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}

	//////////////////////////////////////////////// UNA UNIDAD UN PROYECTO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_una_unidad').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_proyecto').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == false))
		{
			//alert('proyecto');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_proyecto.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@id="+getObj('ante_pro_presupuesto_rp_id_proyecto').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
	//////////////////////////////////////////////// UN PROYECTO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if((getObj('ante_pro_presupuesto_rp_una_unidad').checked  == false) && (getObj('ante_pro_presupuesto_rp_una_proyecto').checked  == true) && (getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == false))
		{
			//alert('proyecto');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_proyecto_nuevo.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@id="+getObj('ante_pro_presupuesto_rp_id_proyecto').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
		
	//////////////////////////////////////////////// UNA ESPECIFICA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		if( (getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value  != "") && (getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == true))
		{
			//alert('especifica');
			url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.presupuesto_especifica.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@id="+getObj('ante_pro_presupuesto_rp_id_accion_es').value; 
			//url="pdfb.php?p=modulos/presupuesto/presupuesto_ley/rp/vista.lst.movimiento_presupuesto_unidad.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"@proyectos="+getObj('proyectos').value+"@acciones="+getObj('acciones').value; 
		}
	
		//alert(url);
		openTab("Resumen de presupuesto",url);
	/*}else{
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La Fecha de Inicio tiene que ser Mayor o Igual que la Fecha Final </p></div>",true,true);
	}*/
});
////////////////////////////////////////////////// FUNCIONES OPTION \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºººº
$("#ante_pro_presupuesto_rp_todas_unidad").click(function() {
	getObj('ante_pro_presupuesto_rp_direccion').value="vista.lst.movimiento_presupuesto.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/;
	//alert(getObj('ante_pro_presupuesto_rp_direccion').value);
	//clearForm('form_rp_compromiso');
});
$("#ante_pro_presupuesto_rp_una_unidad").click(function() {
	getObj('ante_pro_presupuesto_rp_id_unidad').disabled='';
	getObj('ante_pro_presupuesto_rp_nombre_unidad').disabled='';
	clearForm('form_rp_ante_pro_presupuesto');
	getObj('acciones').value='0';
	getObj('proyectos').value='1';
	
	getObj('ante_pro_presupuesto_rp_todas_proyecto').checked=true;
});
$("#ante_pro_presupuesto_rp_todas_accion_cen").click(function() {
	getObj('ante_pro_presupuesto_rp_id_accion').value='0';
	getObj('ante_pro_presupuesto_rp_id_proyecto').value='0';
	getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value='';
	getObj('ante_pro_presupuesto_rp_btn_consultar_accion_central').style.display='none';
	getObj('ante_pro_presupuesto_rp_btn_consultar_proyecto').style.display='none'; 
	getObj('acciones').value='1';
	getObj('proyectos').value='0';
});
$("#ante_pro_presupuesto_rp_todas_proyecto").click(function() {
	getObj('ante_pro_presupuesto_rp_id_accion').value='0';
	getObj('ante_pro_presupuesto_rp_id_proyecto').value='0';
	getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value='';
	getObj('ante_pro_presupuesto_rp_btn_consultar_accion_central').style.display='none';
	getObj('ante_pro_presupuesto_rp_btn_consultar_proyecto').style.display='none'; 
	getObj('proyectos').value='1';
	getObj('acciones').value='0';
});
$("#ante_pro_presupuesto_rp_una_proyecto").click(function() {
	getObj('ante_pro_presupuesto_rp_id_accion').value='0';
	getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value='';
	getObj('ante_pro_presupuesto_rp_btn_consultar_accion_central').style.display='none';
	getObj('ante_pro_presupuesto_rp_btn_consultar_proyecto').style.display='';
	getObj('proyectos').value='0';
});
$("#ante_pro_presupuesto_rp_una_accion_cen").click(function() {
	getObj('ante_pro_presupuesto_rp_id_proyecto').value='0';
	getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value='';
	getObj('ante_pro_presupuesto_rp_btn_consultar_accion_central').style.display='';
	getObj('ante_pro_presupuesto_rp_btn_consultar_proyecto').style.display='none';
	getObj('acciones').value='0';
});
$("#ante_pro_presupuesto_rp_todas_accion_es").click(function() {
	getObj('ante_pro_presupuesto_rp_id_accion_es').value='0';
	getObj('ante_pro_presupuesto_rp_nombre_accion_es').value='';
});
$("#ante_pro_presupuesto_rp_todas_partida").click(function() {
	getObj('ante_pro_presupuesto_rp_partida').value='';
});
$("#ante_pro_presupuesto_rp_todas_generica").click(function() {
	getObj('ante_pro_presupuesto_rp_generica').value='';
});
$("#ante_pro_presupuesto_rp_todas_especifica").click(function() {
	getObj('ante_pro_presupuesto_rp_especifica').value='';
});

////////////////////////////////////////////////// FIN FUNCIONES OPTION \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ºººº

//********************************************************** CONSULTAS EMERGENTES***********************************************************************************
//*********************************************************** UNIDAD EJECUTORA ***********************************************************
$("#ante_pro_presupuesto_rp_btn_consultar_unidad").click(function() {
	if(getObj('ante_pro_presupuesto_rp_una_unidad').checked==true){														
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_unidad.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_unidad").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora_ante.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#ante_pro_presupuesto_pr_nombre_unidad").keypress(function(key)
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
							var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_unidad").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora_ante.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/presupuesto_ley/rp/cmb.sql.unidad_ejecutora_ante.php?nd='+nd+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:25,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false}								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj("ante_pro_presupuesto_rp_id_unidad").value=ret.id_unidad_ejecutora;							
								getObj("ante_pro_presupuesto_rp_nombre_unidad").value=ret.nombre;
								getObj('ante_pro_presupuesto_rp_id_unidad').value= ret.id_unidad_ejecutora;
								getObj('ante_pro_presupuesto_rp_direccion').value="vista.lst.presupuesto_comprometido_unidad_ejecutora!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@unidad_ejecutora_ejecutora="+ret.id;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#ante_pro_presupuesto_pr_nombre_unidad").focus();
								$('#ante_pro_presupuesto_pr_nombre_unidad').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	}
	});
	//******************************************************** ACCION CENTRALIADA ***********************************************************
//
//

$("#ante_pro_presupuesto_rp_btn_consultar_accion_central").click(function() {
if((getObj('ante_pro_presupuesto_rp_una_accion_cen').checked  == true)  /*&& (getObj('ante_pro_presupuesto_rp_id_unidad').value  != "")*/ )
{																 
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_accion_central.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_accion_central").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.accion_central_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#ante_pro_presupuesto_pr_nombre_accion_central").keypress(function(key)
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
							var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_accion_central").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.accion_central_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/presupuesto_ley/rp/cmd.sq.accion_central_ante.php?ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value,
								datatype: "json",
								colNames:['ID','Codigo','Denominacion'],
								colModel:[
									{name:'id_accion_central',index:'id_accion_central', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_central',index:'codigo_accion_cental', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:300,sortable:false,resizable:false}								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('ante_pro_presupuesto_rp_id_proyecto').value = '';
									getObj('ante_pro_presupuesto_rp_id_accion').value = ret.id_accion_central;
									//getObj('ante_pro_presupuesto_rp_codigo_central').value = ret.codigo;
									getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value = ret.denominacion;
									getObj('ante_pro_presupuesto_rp_direccion').value="vista.lst.presupuesto_comprometido_accion_central.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@accion_cen="+ret.id;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#ante_pro_presupuesto_pr_nombre_accion_central").focus();
								$('#ante_pro_presupuesto_pr_nombre_accion_central').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_accion_central',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}
	});

//******************************************************** PROYECTO ***********************************************************
//
//
$("#ante_pro_presupuesto_rp_btn_consultar_proyecto").click(function() {
if((getObj('ante_pro_presupuesto_rp_una_proyecto').checked  == true) /*&& (getObj('ante_pro_presupuesto_rp_id_unidad').value  != "") */)
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_proyecto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proyecto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_proyecto").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sq.proyecto_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#ante_pro_presupuesto_pr_nombre_proyecto").keypress(function(key)
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
							var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_proyecto").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sq.proyecto_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/presupuesto_ley/rp/cmd.sq.proyecto_ante.php?ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value,
								datatype: "json",
								colNames:['ID','Codigo','Proyecto'],
								colModel:[
									{name:'id_proyecto',index:'id_proyecto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proyecto',index:'codigo_proyecto', width:25,sortable:false,resizable:false},
									{name:'proyecto',index:'proyecto', width:300,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								    getObj('ante_pro_presupuesto_rp_id_accion').value = '';
									getObj('ante_pro_presupuesto_rp_id_proyecto').value = ret.id_proyecto;
									//getObj('ante_pro_presupuesto_rp_codigo_proyecto').value = ret.codigo;
									getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value = ret.proyecto;
									getObj('ante_pro_presupuesto_rp_direccion').value="vista.lst.presupuesto_comprometido_proyecto.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@proyecto="+ret.id;
									//alert(getObj('ante_pro_presupuesto_rp_direccion').value);
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#ante_pro_presupuesto_pr_nombre_proyecto").focus();
								$('#ante_pro_presupuesto_pr_nombre_proyecto').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_proyecto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}
	});

//******************************************************** ACCION ESPECIFICA ***********************************************************
//
//
$("#ante_pro_presupuesto_rp_btn_consultar_accion_es").click(function() {
if((getObj('ante_pro_presupuesto_rp_una_accion_es').checked  == true) && (getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value  != ""))
{															
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/presupuesto_ley/rp/grid_accion_especifica.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Accion Especifica', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_accion_especifica").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.accion_especifica_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"&proyecto="+getObj('ante_pro_presupuesto_rp_id_proyecto').value+"&accion_central="+getObj('ante_pro_presupuesto_rp_id_accion').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#ante_pro_presupuesto_pr_nombre_accion_especifica").keypress(function(key)
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
							var busq_nombre= jQuery("#ante_pro_presupuesto_pr_nombre_accion_especifica").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/presupuesto_ley/rp/cmd.sql.accion_especifica_ante.php?busq_nombre="+busq_nombre+"&ano="+getObj('ante_pro_presupuesto_rp_cmb_ano').value+"&unidad="+getObj('ante_pro_presupuesto_rp_id_unidad').value+"&proyecto="+getObj('ante_pro_presupuesto_rp_id_proyecto').value+"&accion_central="+getObj('ante_pro_presupuesto_rp_id_accion').value,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/presupuesto_ley/rp/cmd.sql.accion_especifica_ante.php?ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&proyecto='+getObj('ante_pro_presupuesto_rp_id_proyecto').value+'&accion_central='+getObj('ante_pro_presupuesto_rp_id_accion').value,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
									{name:'id_accion_especifica',index:'id_accion_especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_especifica',index:'codigo_accion_especifica', width:25,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:300,sortable:false,resizable:false}								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
								getObj('ante_pro_presupuesto_rp_id_accion_es').disabled = "";
									getObj('ante_pro_presupuesto_rp_id_accion_es').value = ret.id_accion_especifica;
									//getObj('ante_pro_presupuesto_rp_codigo_proyecto').value = ret.codigo;
									getObj('ante_pro_presupuesto_rp_nombre_accion_es').value = ret.denominacion;
									//getObj('ante_pro_presupuesto_rp_direccion').value="vista.lst.presupuesto_comprometido_proyecto.php!anio="+getObj('ante_pro_presupuesto_rp_cmb_ano').value/*+"@desde="+getObj('ante_pro_presupuesto_rp_cmb_mes_desde').value+"@hasta="+getObj('ante_pro_presupuesto_rp_cmb_mes_hasta').value*/+"@proyecto="+ret.id;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#ante_pro_presupuesto_pr_nombre_accion_especifica").focus();
								$('#ante_pro_presupuesto_pr_nombre_accion_especifica').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_accion_especifica',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
}
	});
//

//******************************************************** PARTIDA ***********************************************************
$("#ante_pro_presupuesto_rp_btn_consultar_partida").click(function() {
if  ((getObj("ante_pro_presupuesto_rp_una_partida").checked  == true) && (getObj('ante_pro_presupuesto_rp_nombre_accion_es').value  != "") && (getObj('ante_pro_presupuesto_rp_id_unidad').value  != "")&& (getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value  != "")){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/rp/grid_unidad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						//alert('modulos/presupuesto/presupuesto_ley/rp/cmb.sql.partida_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/rp/cmb.sql.partida_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value,
								datatype: "json",
								colNames:['Partida', 'Descripci&oacute;n'],
								colModel:[
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:400,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('ante_pro_presupuesto_rp_partida').value = ret.partida;

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
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
$("#ante_pro_presupuesto_rp_btn_consultar_generica").click(function() {
if  ((getObj("ante_pro_presupuesto_rp_una_generica").checked  == true) && (getObj("ante_pro_presupuesto_rp_partida").value != "") && (getObj('ante_pro_presupuesto_rp_nombre_accion_es').value  != "") && (getObj('ante_pro_presupuesto_rp_id_unidad').value  != "")&& (getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value  != "")){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/rp/grid_unidad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						//alert('modulos/presupuesto/presupuesto_ley/rp/cmb.sql.partida_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/rp/cmb.sql.generica_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value+'&partida='+getObj('ante_pro_presupuesto_rp_partida').value,
								datatype: "json",
								colNames:['Partida','Generica', 'Descripci&oacute;n'],
								colModel:[
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'generica',index:'generica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:400,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('ante_pro_presupuesto_rp_generica').value = ret.generica;
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
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
$("#ante_pro_presupuesto_rp_btn_consultar_especifica").click(function() {
if  ((getObj("ante_pro_presupuesto_rp_una_especifica").checked  == true) && (getObj('ante_pro_presupuesto_rp_generica').value != "") && (getObj("ante_pro_presupuesto_rp_partida").value != "") && (getObj('ante_pro_presupuesto_rp_nombre_accion_es').value  != "") && (getObj('ante_pro_presupuesto_rp_id_unidad').value  != "")&& (getObj('ante_pro_presupuesto_rp_nombre_accion_proyecto').value  != "")){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/presupuesto_ley/rp/grid_unidad.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						//alert('modulos/presupuesto/presupuesto_ley/rp/cmb.sql.partida_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value);
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/presupuesto_ley/rp/cmb.sql.especifica_ante.php?acion_esp='+getObj('ante_pro_presupuesto_rp_id_accion_es').value+'&unidad='+getObj('ante_pro_presupuesto_rp_id_unidad').value+'&ano='+getObj('ante_pro_presupuesto_rp_cmb_ano').value+'&partida='+getObj('ante_pro_presupuesto_rp_partida').value+'&generica='+getObj('ante_pro_presupuesto_rp_generica').value,
								datatype: "json",
								colNames:['Partida','Especifica', 'Descripci&oacute;n'],
								colModel:[
									{name:'partida',index:'partida', width:50,sortable:false,resizable:false},
									{name:'especifica',index:'especifica', width:50,sortable:false,resizable:false,hidden:true},
									{name:'denominacion',index:'denominacion', width:400,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('ante_pro_presupuesto_rp_especifica').value = ret.especifica;
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
								sortname: 'partida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});

//***********************************************************************************************************************************************
//***********************************************************************************************************************************************
</script>
<div id="botonera">
	<img id="ante_pro_presupuesto_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="ante_pro_presupuesto_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>
<form method="post" name="form_rp_ante_pro_presupuesto" id="form_rp_ante_pro_presupuesto">
<input type="hidden" name="ante_pro_presupuesto_rp_direccion" id="ante_pro_presupuesto_rp_direccion" value="vista.lst.movimiento_presupuesto.php!anio=2009" />
<input type="hidden" name="proyectos" id="proyectos" value="1" />
<input type="hidden" name="acciones"  id="acciones"  value="0" />

<table  class="cuerpo_formulario"  style="width:700">
	<tr>
		<th  class="titulo_frame" colspan="3">
			<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />	 RESUMEN DE ANTEPROYECTO DE PRESUPUESTO</th>
	</tr>
	<tr>
		<th colspan="3">A&Ntilde;O
			<select  name="ante_pro_presupuesto_rp_cmb_ano" id="ante_pro_presupuesto_rp_cmb_ano" style="width:60px; min-width:60px;" >
					<option   value="2010">2010</option>
                    <option   value="2011">2011</option>
					
				</select>
		</th>
		<!--<th>DESDE
				<select  name="ante_pro_presupuesto_rp_cmb_mes_desde" id="ante_pro_presupuesto_rp_cmb_mes_desde" style="width:100px; min-width:100px;">
				  <?/*
					$mes_inicio=1;
					
					while($mes_inicio <= date('n'))
					{
					if($mes_inicio==date('n'))
						$selected = "selected";
					else
						$selected = "";
					if($mes_inicio == 1)
						$mes_desde = 'Enero';
					if($mes_inicio == 2)
						$mes_desde = 'Febrero';
					if($mes_inicio == 3)
						$mes_desde = 'Marzo';	
					if($mes_inicio == 4)
						$mes_desde = 'Abril';
					if($mes_inicio == 5)
						$mes_desde = 'Mayo';
					if($mes_inicio == 6)
						$mes_desde = 'Junio';
					if($mes_inicio == 7)
						$mes_desde = 'Julio';
					if($mes_inicio == 8)
						$mes_desde = 'Agosto';
					if($mes_inicio == 9)
						$mes_desde = 'Septiembre';	
					if($mes_inicio == 10)
						$mes_desde = 'Octubre';
					if($mes_inicio == 11)
						$mes_desde = 'Noviembre';
					if($mes_inicio == 12)
						$mes_desde = 'Diciembre';	
					*/?>
				  <option <?//=$selected?>  value="<?//=$mes_inicio;?>">
				    <?//=$mes_desde;?>
			      </option>
				  <?/*
						$mes_inicio++;
					}
					*/?>
    </select></th>
		<th>HASTA
				<select  name="ante_pro_presupuesto_rp_cmb_mes_hasta" id="ante_pro_presupuesto_rp_cmb_mes_hasta" style="width:100px; min-width:100px;">
					<?/*
					$mes_inicio=1;
					
					while($mes_inicio <= date('n'))
					{
					if($mes_inicio==date('n'))
						$selected = "selected";
					else
						$selected = "";
					if($mes_inicio == 1)
						$mes_hasta = 'Enero';
					if($mes_inicio == 2)
						$mes_hasta = 'Febrero';
					if($mes_inicio == 3)
						$mes_hasta = 'Marzo';	
					if($mes_inicio == 4)
						$mes_hasta = 'Abril';
					if($mes_inicio == 5)
						$mes_hasta = 'Mayo';
					if($mes_inicio == 6)
						$mes_hasta = 'Junio';
					if($mes_inicio == 7)
						$mes_hasta = 'Julio';
					if($mes_inicio == 8)
						$mes_hasta = 'Agosto';
					if($mes_inicio == 9)
						$mes_hasta = 'Septiembre';	
					if($mes_inicio == 10)
						$mes_hasta = 'Octubre';
					if($mes_inicio == 11)
						$mes_hasta = 'Noviembre';
					if($mes_inicio == 12)
						$mes_hasta = 'Diciembre';	
					*/?>
					<option <?//=$selected?>  value="<?//=$mes_inicio;?>"><?//=$mes_hasta;?></option>
					<?/*
						$mes_inicio++;
					}
					*/?>
				</select>	
		
		</th>-->
	</tr>
	<tr>
		<th>
			<table class="clear" width="100%" border="0">
				
				<tr>
					<th width="10%" style="width:5%"><input name="ante_pro_presupuesto_rp_unidad" id="ante_pro_presupuesto_rp_una_unidad" type="radio" value="0"></th>
					<th style="width:95%">UNA UNIDAD</th>
				</tr>
				<tr>
					<th><input name="ante_pro_presupuesto_rp_unidad" type="radio" id="ante_pro_presupuesto_rp_todas_unidad" value="1" checked="checked"></th>
					<th>TODAS</th>
				</tr>
				<tr>
					<th colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td>
									<input name="ante_pro_presupuesto_rp_nombre_unidad"id="ante_pro_presupuesto_rp_nombre_unidad" type="text"  size="35">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_unidad" src="imagenes/null.gif" />
									<input name="ante_pro_presupuesto_rp_id_unidad" id="ante_pro_presupuesto_rp_id_unidad" type="hidden" disabled="disabled">
								</td>
							</tr>
						</table>
					</th>
				</tr>
				
				
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_proyecto" id="ante_pro_presupuesto_rp_una_proyecto" type="radio" value="2"></th>
					<th>UN PROYECTO</th>
				</tr>
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_proyecto" id="ante_pro_presupuesto_rp_todas_proyecto" type="radio" value="3" checked="checked"></th>
					<th>TODOS </th>
				</tr>
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_proyecto" id="ante_pro_presupuesto_rp_una_accion_cen" type="radio" value="0"></th>
					<th>UNA ACCI&Oacute;N CENTRALIZADA</th>
				</tr>
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_proyecto" id="ante_pro_presupuesto_rp_todas_accion_cen" type="radio" value="1"></th>
					<th>TODAS </th>
				</tr>
				
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td>
									<input name="ante_pro_presupuesto_rp_nombre_accion_proyecto"id="ante_pro_presupuesto_rp_nombre_accion_proyecto" type="text"  size="35">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_accion_central" src="imagenes/null.gif" style="display:none"/>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_proyecto" src="imagenes/null.gif" style="display:none"/>
									<input name="ante_pro_presupuesto_rp_id_accion" id="ante_pro_presupuesto_rp_id_accion" type="hidden" disabled="disabled">
									<input name="ante_pro_presupuesto_rp_id_proyecto" id="ante_pro_presupuesto_rp_id_proyecto" type="hidden" disabled="disabled">
								</td>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_es" id="ante_pro_presupuesto_rp_una_accion_es" type="radio" value="0"></th>
					<th>UNA ACCI&Oacute;N ESPECIFICA</th>
				</tr>
				<tr>
					<th><input name="ante_pro_presupuesto_rp_accion_es" type="radio" id="ante_pro_presupuesto_rp_todas_accion_es" value="1" checked="checked"></th>
					<th>TODAS </th>
				</tr>
				<tr>
					<th colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="2">&nbsp;</th>
				</tr>
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td>
									<input name="ante_pro_presupuesto_rp_nombre_accion_es" id="ante_pro_presupuesto_rp_nombre_accion_es" type="text"  size="35">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_accion_es" src="imagenes/null.gif" />
									<input name="ante_pro_presupuesto_rp_id_accion_es" id="ante_pro_presupuesto_rp_id_accion_es" type="hidden" disabled="disabled">
								</td>
							</tr>
						</table>
					</th>
				</tr>
				
			</table>
		</th>
	</tr>
	<!--<tr>
		<th colspan="3" bgcolor="#4c7595">&nbsp;</th>
	</tr>
	<tr>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_partida" id="ante_pro_presupuesto_rp_una_partida" type="radio" value="0"></th>
					<th style="text-align:left">UNA PARTIDA</th>
				</tr>
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_partida" type="radio" id="ante_pro_presupuesto_rp_todas_partida" value="1" checked="checked"></th>
					<th style="text-align:left" >TODAS</th>
				</tr>
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td style="text-align:right" width="50%"><input name="ante_pro_presupuesto_rp_partida"id="ante_pro_presupuesto_rp_partida" type="text"  size="5"><td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_partida" src="imagenes/null.gif" />
								</td>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_generica" id="ante_pro_presupuesto_rp_una_generica" type="radio" value="0"></th>
					<th>UNA GENERICA</th>
				</tr>
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_generica" type="radio" id="ante_pro_presupuesto_rp_todas_generica" value="1" checked="checked"></th>
					<th>TODAS </th>
				</tr>
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td style="text-align:right" width="50%">
									<input name="ante_pro_presupuesto_rp_generica"id="ante_pro_presupuesto_rp_generica" type="text"  size="5">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_generica" src="imagenes/null.gif" />
								</td>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</th>
		<th>
			<table class="clear" width="100%" border="0">
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_especifica" id="ante_pro_presupuesto_rp_una_especifica" type="radio" value="0"></th>
					<th>UNA ESPECIFICA</th>
				</tr>
				<tr>
					<th style="text-align:right"><input name="ante_pro_presupuesto_rp_especifica" type="radio" id="ante_pro_presupuesto_rp_todas_especifica" value="1" checked="checked"></th>
					<th>TODAS</th>
				</tr>
				<tr>
					<th colspan="2">
						<table class="clear" width="100%" border="0">
							<tr>
								<td style="text-align:right" width="50%">
									<input name="ante_pro_presupuesto_rp_especifica"id="ante_pro_presupuesto_rp_especifica" type="text"  size="5">
								</td>
								<td>
									<img class="btn_consulta_emergente" id="ante_pro_presupuesto_rp_btn_consultar_especifica" src="imagenes/null.gif" />
								</td>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</th>
	</tr>-->
	<tr>
		<td colspan="3" class="bottom_frame">&nbsp;</td>
	</tr>
</table>
</form>