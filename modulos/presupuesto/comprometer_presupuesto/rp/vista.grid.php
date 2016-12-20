<?php
session_start();
?>

<script>
var dialog;
$("#compromiso_rp_btn_imprimir").click(function() {
	if(($('#form_rp_comprometido').jVal()))
	{
		url="pdf.php?p=modulos/presupuesto/comprometer_presupuesto/rp/"+getObj('compromiso_rp_direccion').value; 
		openTab("Anteproyecto de presupuesto",url);
	}
});
$("#compromiso_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_comprometido');
});
//------------------ partida ---------------------------
$("#compromiso_rp_btn_consultar_unidad_ejecutora").click(function() {

if (getObj("compromiso_rp_opt_unidad").checked  == true){

	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.unidad.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo','Unidad'],
								colModel:[
									{name:'id',index:'id', width:10,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:40,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj("compromiso_rp_id_unidad_ejecutora").value=ret.id;							
									getObj("compromiso_rp_nombre_unidad_ejecutora").value=ret.nombre;
									getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_comprometido_unidad_ejecutora¿ano="+getObj('compromiso_rp_cmb_ano').value+"@unidad_ejecutora="+getObj('compromiso_rp_id_unidad_ejecutora').value;
									//alert(getObj('compromiso_rp_direccion').value);
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});

// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\////////////////////////////////////////
//------------------ partida ---------------------------
$("#compromiso_rp_btn_consultar_partida").click(function() {
if  (getObj("compromiso_rp_opt_partida").checked  == true){
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Partida', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.partida.php?nd='+nd,
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
									getObj('compromiso_rp_partida_numero').value = ret.partida;
									getObj('compromiso_rp_partida').value = ret.denominacion;
									getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_comprometido_partida.php¿ano="+getObj('compromiso_rp_cmb_ano').value+"@partida="+ret.partida;
									//alert(getObj('compromiso_rp_direccion').value);
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

///**********************----************************************
function consulta_automatica_accion_central()
{ 
	alert("modulos/presupuesto/comprometer_presupuesto/rp/sql_grid_accion_cental_codigo.php");
	$.ajax({
			url:"modulos/presupuesto/comprometer_presupuesto/rp/sql_grid_accion_cental_codigo.php",
            data:dataForm('form_rp_comprometido'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('compromiso_rp_id_accion_cen').value = recordset[0];
				getObj('compromiso_rp_nombre_accion_cen').value=recordset[1];
				/*getObj('compromiso_rp_proyecto_id').value="";
				getObj('presupuesto_ley_db_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
				getObj('presupuesto_ley_db_codigo_proyecto').value ="0000" ;
				getObj('presupuesto_ley_db_codigo_proyecto').disabled="disabled" ;*/
				}
				else
			 {  
			   	getObj('compromiso_rp_id_accion_cen').value ="";
			    getObj('compromiso_rp_nombre_accion_cen').value="";
				/*getObj('compromiso_rp_proyecto_id').value="";
				getObj('presupuesto_ley_db_nombre_proyecto').value=""; compromiso_rp_codigo_central
				getObj('presupuesto_ley_db_codigo_proyecto').value ="" ;
				getObj('presupuesto_ley_db_codigo_proyecto').disabled="" ;*/
				}
			 }
		});	 	 
}
// --------------------
$("#compromiso_rp_btn_consultar_accion_cen").click(function() {
if((getObj('compromiso_rp_opt_accion_central').checked  == true) || (getObj('compromiso_rp_opt_cen_esp').checked  == true) )
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:650,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.accion_central.php?ano='+getObj('compromiso_rp_cmb_ano').value,
								datatype: "json",
								colNames:['id','Codigo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								
									getObj('compromiso_rp_id_accion_cen').value = ret.id;
									getObj('compromiso_rp_codigo_central').value = ret.codigo;
									getObj('compromiso_rp_nombre_accion_cen').value = ret.denominacion;
									getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_comprometido_accion_central.php¿ano="+getObj('compromiso_rp_cmb_ano').value+"@accion_cen="+ret.id;
									//alert(getObj('compromiso_rp_direccion').value)
									getObj('compromiso_rp_nombre_proyecto').value="  NO APLICA ESTA OPCION  ";
									getObj('compromiso_rp_codigo_proyecto').value ="0000" ;
									getObj('compromiso_rp_codigo_proyecto').disabled="disabled" ;
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
								sortname: 'denominacion',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});


///**********************----************************************
function consulta_automatica_proyecto()
{
	$.ajax({
			url:"modulos/presupuesto/comprometer_presupuesto/db/sql_grid_proyecto_codigo.php",
            data:dataForm('form_rp_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('compromiso_rp_proyecto_id').value = recordset[0];
				getObj('presupuesto_ley_db_nombre_proyecto').value=recordset[1];
				/*getObj('compromiso_rp_accion_central_id').value="";
				getObj('compromiso_rp_nombre_central').value="  NO APLICA ESTA OPCION  ";
				getObj('compromiso_rp_codigo_central').value ="0000" ;
				getObj('compromiso_rp_codigo_central').disabled="disabled" ;*/
				}
				else
			 {  
			   	getObj('compromiso_rp_proyecto_id').value ="";
			    getObj('presupuesto_ley_db_nombre_proyecto').value="";
				getObj('compromiso_rp_nombre_central').value="";
				getObj('compromiso_rp_codigo_central').value ="" ;
				getObj('compromiso_rp_accion_central_id').value ="";
				getObj('compromiso_rp_codigo_central').disabled="" ;
				}
			 }
		});	 	 
}
// -----

$("#compromiso_rp_btn_consultar_proyecto").click(function() {
if((getObj('compromiso_rp_opt_proyecto').checked  == true) || (getObj('compromiso_rp_opt_pro_esp').checked  == true))
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/comprometer_presupuesto/pr/grid_compromiso.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:650,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.proyecto?ano='+getObj('compromiso_rp_cmb_ano').value,
								datatype: "json",
								colNames:['id','Codigo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:40,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('compromiso_rp_id_proyecto').value = ret.id;
									getObj('compromiso_rp_codigo_proyecto').value = ret.codigo;
									getObj('compromiso_rp_nombre_proyecto').value = ret.denominacion;
									getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_comprometido_proyecto.php¿ano="+getObj('compromiso_rp_cmb_ano').value+"@proyecto="+ret.id;
									getObj('compromiso_rp_nombre_accion_cen').value="  NO APLICA ESTA OPCION  ";
									getObj('compromiso_rp_codigo_central').value ="0000" ;
									getObj('compromiso_rp_codigo_central').disabled="disabled" ;

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
	}
});
//***********************************************************************************************************************
function consulta_automatica_especifica()
{
	$.ajax({
			url:"modulos/presupuesto/comprometer_presupuesto/db/sql_grid_accion_especifica.php",
            data:dataForm('form_rp_presupuesto'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('compromiso_rp_accion_especifica').value = recordset[0];
				getObj('presupuesto_ley_db_nombre_especifica').value=recordset[1];
				//setBarraEstado(html);
				}
				else
			 	{  
			   	getObj('compromiso_rp_accion_especifica').value ="";
			    getObj('presupuesto_ley_db_nombre_especifica').value="";
				}
			 }
		});	 	 
}
// -----
$("#compromiso_rp_btn_consultar_accion_esp").click(function() {
//alert('aqui'); compromiso_rp_opt_pro_esp
if(getObj('compromiso_rp_opt_accion_especifica').checked  == true)
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if (getObj('compromiso_rp_opt_accion_especifica').checked  == true){
		urls = 'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.accion_especifica.php?nd='+nd;
	}
	/*if (getObj('compromiso_rp_opt_cen_esp').checked  == true){
		urls = 'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.accion_especifica.php?nd='+nd+'&accion_central='+getObj('compromiso_rp_id_accion_cen').value;
	}
	if (getObj('compromiso_rp_opt_pro_esp').checked  == true){
		urls = 'modulos/presupuesto/comprometer_presupuesto/rp/cmb.sql.accion_especifica.php?nd='+nd+'&proyecto='+getObj('compromiso_rp_id_proyecto').value;
	}*/
	$.post("modulos/presupuesto/comprometer_presupuesto/db/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
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
								colNames:['id','Codigo', 'Accion Especifica'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('compromiso_rp_id_accion_esp').value = ret.id;
									getObj('compromiso_rp_codigo_esp').value = ret.codigo;
									getObj('compromiso_rp_nombre_accion_esp').value = ret.denominacion;
			
			if (getObj('compromiso_rp_opt_accion_especifica').checked  == true){
			getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_especifica.php¿anio="+getObj('compromiso_rp_cmb_ano').value+"@id="+ret.id;
			}
			if (getObj('compromiso_rp_opt_cen_esp').checked  == true){
			getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_accion_cen_esp.php¿anio="+getObj('compromiso_rp_cmb_ano').value+"@idc="+getObj('compromiso_rp_id_accion_cen').value+"@ide="+ret.id;
			}
			if (getObj('compromiso_rp_opt_pro_esp').checked  == true){ 
				alert("modulos/presupuesto/comprometer_presupuesto/rp/vista.lst.presupuesto_proyecto_esp.php¿anio="+getObj('compromiso_rp_cmb_ano').value+"@idp="+getObj('compromiso_rp_id_proyecto').value+"@ide="+ret.id);

			getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_proyecto_esp.php¿anio="+getObj('compromiso_rp_cmb_ano').value+"@idp="+getObj('compromiso_rp_id_proyecto').value+"@ide="+ret.id;
			}
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
								sortname: 'id_accion_especifica',
								viewrecords: true,
								sortorder: "asc"
							});
						}
	}
});
//***********************************************************************************************************************
// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º////////

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ simples ++++++++++++++++++++++++++++++
$("#compromiso_rp_opt_todas").click(function() {
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';	
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';	
	/*getObj('compromiso_rp_id_accion_esp').disabled='true';
	getObj('compromiso_rp_codigo_esp').disabled='true';
	getObj('compromiso_rp_nombre_accion_esp').disabled='true';*/
	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';	
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	
	/*getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';*/
	getObj('compromiso_rp_direccion').value="vista.lst.presupuesto_comprometido.php¿ano="+getObj('compromiso_rp_cmb_ano').value+"@unidad_ejecutora="+getObj('compromiso_rp_id_unidad_ejecutora').value;
});

$("#compromiso_rp_opt_unidad").click(function() {
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='';
	
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';	
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';	
	/*getObj('compromiso_rp_id_accion_esp').disabled='true';
	getObj('compromiso_rp_codigo_esp').disabled='true';
	getObj('compromiso_rp_nombre_accion_esp').disabled='true';*/
	
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';	
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	
	/*getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';*/
	
});

$("#compromiso_rp_opt_partida").click(function() {
	getObj('compromiso_rp_partida_numero').disabled='';
	getObj('compromiso_rp_partida').disabled='';
	
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';	
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';	
	/*getObj('compromiso_rp_id_accion_esp').disabled='true';
	getObj('compromiso_rp_codigo_esp').disabled='true';
	getObj('compromiso_rp_nombre_accion_esp').disabled='true';*/
	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';	
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	
	/*getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';*/
});
$("#compromiso_rp_opt_accion_central").click(function() {
	getObj('compromiso_rp_id_accion_cen').disabled='';
	getObj('compromiso_rp_codigo_central').disabled='';
	getObj('compromiso_rp_nombre_accion_cen').disabled='';
	
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';	
	/*getObj('compromiso_rp_id_accion_esp').disabled='true';
	getObj('compromiso_rp_codigo_esp').disabled='true';
	getObj('compromiso_rp_nombre_accion_esp').disabled='true';*/
	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	
	/*getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';*/
});

$("#compromiso_rp_opt_proyecto").click(function() {
	getObj('compromiso_rp_id_accion_cen').disabled='';
	getObj('compromiso_rp_codigo_central').disabled='';
	getObj('compromiso_rp_nombre_accion_cen').disabled='';
	
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';	
	/*getObj('compromiso_rp_id_accion_esp').disabled='true';
	getObj('compromiso_rp_codigo_esp').disabled='true';
	getObj('compromiso_rp_nombre_accion_esp').disabled='true';*/
	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';	
	/*getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';*/
});
/*$("#compromiso_rp_opt_accion_especifica").click(function() {
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';	
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';
		
	getObj('compromiso_rp_id_accion_esp').disabled='';
	getObj('compromiso_rp_codigo_esp').disabled='';
	getObj('compromiso_rp_nombre_accion_esp').disabled='';
	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';	
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	
});*/
//++++++++++++++++++++++++++++++++++++++++++++++++++ combinados ++++++++++++++++++++++++++++++++
/*$("#compromiso_rp_opt_cen_esp").click(function() {
	
	getObj('compromiso_rp_id_accion_cen').disabled='';
	getObj('compromiso_rp_codigo_central').disabled='';
	getObj('compromiso_rp_nombre_accion_cen').disabled='';
	getObj('compromiso_rp_id_accion_esp').disabled='';
	getObj('compromiso_rp_codigo_esp').disabled='';
	getObj('compromiso_rp_nombre_accion_esp').disabled='';
	
	getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';
	getObj('compromiso_rp_id_accion_cen').value='';
	getObj('compromiso_rp_codigo_central').value='';
	getObj('compromiso_rp_nombre_accion_cen').value='';
	
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';
	getObj('compromiso_rp_id_proyecto').disabled='true';
	getObj('compromiso_rp_codigo_proyecto').disabled='true';
	getObj('compromiso_rp_nombre_proyecto').disabled='true';	

	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';	

});

$("#compromiso_rp_opt_pro_esp").click(function() {
	
	getObj('compromiso_rp_id_accion_esp').disabled='';
	getObj('compromiso_rp_codigo_esp').disabled='';
	getObj('compromiso_rp_nombre_accion_esp').disabled='';
	getObj('compromiso_rp_id_proyecto').disabled='';
	getObj('compromiso_rp_codigo_proyecto').disabled='';
	getObj('compromiso_rp_nombre_proyecto').disabled='';	
		
	getObj('compromiso_rp_id_accion_esp').value='';
	getObj('compromiso_rp_codigo_esp').value='';
	getObj('compromiso_rp_nombre_accion_esp').value='';
	getObj('compromiso_rp_id_proyecto').value='';
	getObj('compromiso_rp_codigo_proyecto').value='';
	getObj('compromiso_rp_nombre_proyecto').value='';

	getObj('compromiso_rp_id_accion_cen').disabled='true';
	getObj('compromiso_rp_codigo_central').disabled='true';
	getObj('compromiso_rp_nombre_accion_cen').disabled='true';
	getObj('compromiso_rp_id_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_nombre_unidad_ejecutora').disabled='true';
	getObj('compromiso_rp_partida_numero').disabled='true';
	getObj('compromiso_rp_partida').disabled='true';


	
	getObj('compromiso_rp_id_unidad_ejecutora').value='';
	getObj('compromiso_rp_nombre_unidad_ejecutora').value='';
	getObj('compromiso_rp_partida_numero').value='';
	getObj('compromiso_rp_partida').value='';
	

});*/
//++++++++++++++++++++++++++++++++++++++++++++++++++ fin combinados ++++++++++++++++++++++++++++++++

/*-------------------   Inicio Validaciones  ---------------------------*/
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
/*-------------------   Fin Validaciones  ---------------------------*/
</script>
<style type="text/css">
<!--
.Estilo3 {color: #FFFFFF; font-weight: bold; }
-->
</style>


<div id="botonera">
	<img id="compromiso_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="compromiso_rp_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_comprometido" id="form_rp_comprometido">
<input type="hidden" name="compromiso_rp_direccion" id="compromiso_rp_direccion" value="vista.lst.presupuesto_comprometido.php¿ano=2009" />
	<table class="cuerpo_formulario"  style="width:800">
	  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />  Presupuesto Comprometido			</th>
	  </tr>
		<tr>
		<!--	<th valign="middle" style="vertical-align:middle">Selecci&oacute;n</th>-->
		  	<th colspan="2">
				<table class="clear" width="100%" border="0">
					<tr>
						<td colspan="3" bgcolor="#4c7595" align="center"><span class="Estilo3">Reportes Individuales</span></td>
					</tr>
					<tr>
						<td><input id="compromiso_rp_opt_todas" name="compromiso_rp_opt" type="radio" value="0" checked="checked"> 
						Todas</td>
						<td><input id="compromiso_rp_opt_partida" name="compromiso_rp_opt" type="radio" value="2"> PARTIDA</td>
						<td><input id="compromiso_rp_opt_unidad" name="compromiso_rp_opt" type="radio" value="1"> UNIDAD EJECUTORA</td>
					</tr>
					<tr>
						<td><input id="compromiso_rp_opt_accion_central" name="compromiso_rp_opt" type="radio" value="3"> ACCION CENTRALIZADA</td>
						<td><input id="compromiso_rp_opt_proyecto" name="compromiso_rp_opt" type="radio" value="4"> Proyecto</td>
						<!--<td><input id="compromiso_rp_opt_accion_especifica" name="compromiso_rp_opt" type="radio" value="5"> ACCION ESPECIFICA</td>-->
					</tr>
					<!--<tr>
						<td colspan="3" bgcolor="#4c7595" align="center"><span class="Estilo3">Reportes Combinados</span></td>
					</tr>
					<tr>
						<td><input id="compromiso_rp_opt_cen_esp" name="compromiso_rp_opt" type="radio" value="6" > ACCION CENTRALIZADA - ACCION ESPECIFICA</td>
						<td><input id="compromiso_rp_opt_pro_esp" name="compromiso_rp_opt" type="radio" value="8" > PROYECTO - ACCION ESPECIFICA</td>
						<td><input id="compromiso_rp_opt_esp_unidad" name="compromiso_rp_opt" type="radio" value="10" > ACCION ESPECIFICA -  UNIDAD EJECUTORA</td>
					</tr>-->
				</table>			
			</th>
		</tr>
		<tr>
			<th>A&ntilde;o</th>
			<td>
				<select  name="compromiso_rp_cmb_ano" id="compromiso_rp_cmb_ano" style="width:60px; min-width:60px;">
					<?
					$anio_inicio=date('Y') - 2;
					$anio_fin=date('Y') + 1;
					while($anio_inicio <= $anio_fin)
					{
					if($anio_inicio==date('Y'))
						$selected = "selected";
					else
						$selected = "";
					?>
					<option <?=$selected?>  value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
				</select>			</td>
		</tr>
		<tr id="compromiso_rp_tr_partida" ><!--style="display:none"-->
			<th>Partida</th>
			<td>
				<ul class="input_con_emergente">
					<li>
					<input name="compromiso_rp_partida_numero" type="text" id="compromiso_rp_partida_numero" size="5" maxlength="4" disabled="disabled" />
					<input name="compromiso_rp_partida" type="text" id="compromiso_rp_partida" style="width:57ex" maxlength="60" readonly disabled="disabled" 
					message="Introduzca la Partidad Presupuestaria" jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789]{1,60}$/, message:'Partidad no Invalida', styleType:'cover'}"
					/>
					</li>
					<li id="compromiso_rp_btn_consultar_partida" class="btn_consulta_emergente"></li>
				</ul>		  </td>    
		</tr>
		<tr id="compromiso_rp_tr_unidad_ejecutora" ><!--style="display:none"-->
			<th>Unidad Ejecutora</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="compromiso_rp_id_unidad_ejecutora" id="compromiso_rp_id_unidad_ejecutora" disabled="disabled" />
						<input  name="compromiso_rp_nombre_unidad_ejecutora" type="text" id="compromiso_rp_nombre_unidad_ejecutora" size="60" 
							message="Introduzca un Nombre para la Unidad Ejecutora." 
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="compromiso_rp_btn_consultar_unidad_ejecutora" class="btn_consulta_emergente"></li>
				</ul>			</td>    
		</tr>
		<tr id="compromiso_rp_tr_accion_cen" ><!--style="display:none"-->
			<th>Acci&oacute;n Centralizada </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="compromiso_rp_id_accion_cen" id="compromiso_rp_id_accion_cen" disabled="disabled" />
						
						<input name="compromiso_rp_codigo_central" type="text" id="compromiso_rp_codigo_central"  maxlength="5"
						onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
						message="Introduzca un Codigo para el Accion Central."  size="5" disabled="disabled" 
						jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}" >
						
						<input  name="compromiso_rp_nombre_accion_cen" type="text" id="compromiso_rp_nombre_accion_cen" size="60" 
							message="Introduzca un Nombre para la Unidad Ejecutora." 
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="compromiso_rp_btn_consultar_accion_cen" class="btn_consulta_emergente"></li>
				</ul>			</td>    
		</tr>
		<tr id="compromiso_rp_tr_proyecto" ><!--style="display:none"-->
			<th>Proyecto</th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="compromiso_rp_id_proyecto" id="compromiso_rp_id_proyecto" disabled="disabled" />
						
						<input name="compromiso_rp_codigo_proyecto" type="text" id="compromiso_rp_codigo_proyecto"  maxlength="5"
						onchange="consulta_automatica_pro" onclick="consulta_automatica_pro"
						message="Introduzca un Codigo para el Accion Central."  size="5" disabled="disabled" 
						jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}" >
						
						<input  name="compromiso_rp_nombre_proyecto" type="text" id="compromiso_rp_nombre_proyecto" size="60" 
							message="Introduzca un Nombre para el proyecto." 
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="compromiso_rp_btn_consultar_proyecto" class="btn_consulta_emergente"></li>
				</ul>			</td>    
		</tr>
		<!--<tr id="compromiso_rp_tr_accion_esp" ><!--style="display:none"-->
			<!--<th>Acci&oacute;n Especifica </th>
			<td>
				<ul class="input_con_emergente">
					<li>
						<input type="hidden" name="compromiso_rp_id_accion_esp" id="compromiso_rp_id_accion_esp" disabled="disabled" />
						
						<input name="compromiso_rp_codigo_esp" type="text" id="compromiso_rp_codigo_esp"  maxlength="5"
						onchange="consulta_automatica_accion_esp" onclick="consulta_automatica_accion_esp"
						message="Introduzca un Codigo para el Accion Central."  size="5" disabled="disabled" 
						jVal="{valid:/^[0-9]{4,5}$/, message:'Codigo Invalido', styleType:'cover'}" >
						
						<input  name="compromiso_rp_nombre_accion_esp" type="text" id="compromiso_rp_nombre_accion_esp" size="60" 
							message="Introduzca un Nombre para la Unidad Ejecutora."  
							jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
							jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" 
							readonly="true" 
							disabled="disabled"  
						>
					</li>
					<li id="compromiso_rp_btn_consultar_accion_esp" class="btn_consulta_emergente"></li>
				</ul>			</td>    
		</tr>-->

		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
	</table>
</form>