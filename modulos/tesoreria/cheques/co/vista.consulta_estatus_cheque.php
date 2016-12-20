<?php
session_start();
?>
<script type="text/javascript" src="utilidades/selectboxes/jquery.selectboxes.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<script type="text/javascript" language="JavaScript">   
/* Marcaras de edicion de campos de entrada --> */
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
</script>
<script type='text/javascript'>
//-------------- consulta automatica por cheque ---------------------------------//
function consul_auto_cheque()
{
 if((getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value!="")&&(getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="" &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!=""))||(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!="")||(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
 {	
				var nd=new Date().getTime();
				var proveedor;
			if(getObj('tesoreria_cheque_estatus_pr_op_oculto').value=='1')
			{	
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!=""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}else
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value==""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}else
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!=""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}
			}
			else
			if(getObj('tesoreria_cheque_estatus_pr_op_oculto').value=='2')
			{	
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&empleado="+getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}else
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value==""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}else
				if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
				{
					urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus_codigo.php?nd='+nd+"&empleado="+getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value+"&cheques="+getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
				}	
			}	
//////////////////////////////////	
	//getObj('tesoreria_cheque_estatus_che_pr_n_cheque').disabled="";
	$.ajax({
			url:urls,
            data:dataForm('form_tesoreria_pr_cheque_estatus'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
						if((html==""))
					
							{
								 //limpiar();
									getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value="";
									
									
							
								}	
					   		 if((html!="")||(html!=null)||(html!="undefined"))
								{	var recordset=html;
									if(recordset)
									{
									recordset = recordset.split("*");
									//getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value=recordset[0];
										getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value=recordset[0];
										getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value=recordset[6];
										getObj('tesoreria_cheque_estatus_che_pr_estatus').value=recordset[11];
									
									getObj('tesoreria_cheque_estatus_che_pr_btn_cancelar').style.display='';
									estad=recordset[12];
									estad2=estad.replace("{","");
									getObj('tesoreria_cheque_estatus_che_pr_estatus').value=estad2.replace("}","");
									contador=0;
									estado=(getObj('tesoreria_cheque_estatus_che_pr_estatus').value).split(",");
									getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=recordset[1];
									getObj('tesoreria_cheque_estatus_che_pr_nombre_banco').value=recordset[2];
									getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=recordset[3];
									if(recordset[5]=='1')
									{
										getObj('tesoreria_cheque_estatus_pr_radio1').checked="checked"
										getObj('tesoreria_cheque_estatus_pr_radio2').checked=""

										getObj('tr_empleado').style.display='none';
										getObj('tesoreria_cheque_estatus_pr_op_oculto').value='1';
										getObj('tr_proveedor').style.display='';
										getObj('tesoreria_cheque_estatus_pr_proveedor_id').value=recordset[14];
										getObj('tesoreria_cheque_estatus_pr_proveedor_codigo').value=recordset[13];
										getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value=recordset[7];

									}else
									if(recordset[5]=='2')
									{
									getObj('tesoreria_cheque_estatus_pr_radio1').checked=""
									getObj('tesoreria_cheque_estatus_pr_radio2').checked="checked"

									getObj('tr_empleado').style.display='';
									getObj('tesoreria_cheque_estatus_pr_op_oculto').value='2';
									getObj('tr_proveedor').style.display='none'
									getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value=recordset[13];
									getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value=recordset[7];
									}
									//////////////////////limpiando imagenes
										getObj('cheques_estatus_impreso').style.display='none';
										//getObj('cheques_estatus_tesoreria').style.display='none';
										getObj('cheques_estatus_caja').style.display='none';
										getObj('cheques_estatus_pagado').style.display='none';
									////////////////////////////////////////////////////////////////////
				
								estado_fecha=recordset[15];
								vecta_fecha1=estado_fecha.replace("{","");
								vecta_fecha=vecta_fecha1.replace("}","");
									
								vect_fecha=vecta_fecha.split(",");
								if((estado[0]=='1')||(estado[0]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									impreso=fds;
									getObj('cheques_estatus_impreso').style.display='';
									getObj('cheque_estatus_impreso_fecha').value=impreso;
								}
								
								if((estado[1]=='1')||(estado[1]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									caja=fds;
									getObj('cheques_estatus_caja').style.display='';
									getObj('cheque_estatus_caja_fecha').value=caja;

								}
								if((estado[3]=='1')||(estado[3]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									archivo=fds;
									getObj('cheques_estatus_archivado').style.display='';
									getObj('cheque_estatus_archivado_fecha').value=archivo;

								}
								if((estado[2]=='1')||(estado[2]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									pagado=fds;
									getObj('cheques_estatus_pagado').style.display='';
									getObj('cheque_estatus_pagado_fecha').value=pagado;

								}
								if(ret.estatus=='4')
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									archivo=fds;
									getObj('cheques_estatus_archivado').style.display='';
									getObj('cheque_estatus_archivado_fecha').value=archivo;

								}
				
				
				
				
				
				/****/
									if((estado[0]=='1')||(estado[0]=='2'))
									{
										getObj('cheques_estatus_impreso').style.display='';
									}
									
									if((estado[1]=='1')||(estado[1]=='2'))
									{
										getObj('cheques_estatus_caja').style.display='';
									}
									if((estado[2]=='2')||(estado[2]=='2'))
									{
										getObj('cheques_estatus_pagado').style.display='';
									}
									}
						
							//	getObj('tesoreria_cheque_estatus_che_pr_btn_cancelar').style.display='';
							//	getObj('tesoreria_cheque_db_btn_imprimir_vp_estatus_che').style.display='';

								}
								
				}
						    

		});	 				
 }	 
}



function limpiar_estatus_archivo(){

setBarraEstado("");
 clearForm('form_tesoreria_pr_cheque_estatus');
	getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value="";
	getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value="";
	getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value="";
	getObj('tesoreria_cheque_estatus_che_pr_nombre_banco').value="";
	getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value="";
	getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value="000000";
	getObj('cheques_estatus_impreso').style.display='none';
	getObj('cheques_estatus_caja').style.display='none';
	getObj('cheques_estatus_pagado').style.display='none';
	getObj('cheques_estatus_archivado').style.display='none';
	getObj('tesoreria_cheque_estatus_che_pr_estatus').value="";
	getObj('tr_empleado').style.display='none';
	getObj('tr_proveedor').style.display='';
	getObj('tesoreria_cheque_estatus_pr_radio1').checked="checked";
	getObj('tesoreria_cheque_estatus_pr_radio2').checked="";
	getObj('tesoreria_cheque_estatus_pr_op_oculto').value="1";
	getObj('tesoreria_cheque_estatus_pr_proveedor_codigo').value="";
	getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value="";
	getObj('tesoreria_cheque_estatus_pr_proveedor_id').value="";
	getObj('tesoreria_cheque_estatus_pr_proveedor_rif').value="";
	getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value="";
	getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value="";
	getObj('benef').style.display='none';
$('#cheques_estatus_partidas').removeOption(/(.?)/);
$('#cheques_estatus_compromisos').removeOption(/(.?)/);
$('#cheques_estatus_facturas').removeOption(/(.?)/);
$('#cheques_estatus_ordenes').removeOption(/(.?)/);
	
}
$("#tesoreria_cheque_estatus_che_pr_btn_cancelar").click(function() {
//*list de partidas

limpiar_estatus_archivo();
});	

//------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_estatus_che_pr_btn_consultar_cuentas_chequeras").click(function() {
if(getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="")
{
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuenta_cheque.php?nd='+nd+'&banco='+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value;
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });*/
						///////////////////////////////////
	var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_chequeras_cuentas.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuentas Bancarias', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_ano= jQuery("#tesoreria_cheques_chequeras_pr_ano").val();
					var busq_cuenta= jQuery("#tesoreria_cheques_chequeras_pr_cuenta").val(); 
 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_cuenta_cheque.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+'&busq_cuenta='+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}	

				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//-busqueda por banco
				$("#tesoreria_cheques_chequeras_pr_ano").keypress(function(key)
				{	ano_dosearch();
				//alert("entro");
				});
				$("#tesoreria_cheques_chequeras_pr_cuenta").keypress(function(key)
				{	ano_dosearch();
				});
					function ano_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(ano_gridReload,500)
						}
					function ano_gridReload()
					{
							var busq_ano= jQuery("#tesoreria_cheques_chequeras_pr_ano").val(); 
							var busq_cuenta= jQuery("#tesoreria_cheques_chequeras_pr_cuenta").val(); 
jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_cuenta_cheque.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+'&busq_cuenta='+busq_cuenta,page:1}).trigger("reloadGrid");
						    url="modulos/tesoreria/cheques/pr/sql_grid_cuenta_cheque.php?busq_ano="+busq_ano+'&banco='+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+'&busq_cuenta='+busq_cuenta;
						   // alert(url);
						}
				}
		});			
////////////////////////////////////						


						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N Cuenta','Estatus','CuentaNuevo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuentan',index:'cuentan', width:50,sortable:false,resizable:false,hidden:true}

									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									dialog.hideAndUnload();
									getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=ret.ncuenta;

				 			
							},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//----

//-----------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_estatus_che_pr_btn_consultar_banco").click(function() {
/*		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,50);								
                        });*/

var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_cheque_banc_dosearch();
					});
				
						function consulta_cheque_banc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_cheque_banc_gridReload,500)
										}
						function consulta_cheque_banc_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco;
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco_cheques.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo Área','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','id_banco_cheques','banco_cheques','cuenta_banco_cheques'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:160,sortable:false,resizable:false},
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
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_banco_cheques',index:'id_banco_cheques', width:100,sortable:false,resizable:false,hidden:true},
									{name:'banco_cheques',index:'banco_cheques', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_banco_cheques',index:'cuenta_banco_cheques', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=ret.id;
									getObj('tesoreria_cheque_estatus_che_pr_nombre_banco').value=ret.nombre;
									getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=ret.cuentas;
									
								
									
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
});
//-------------------------------------------------------------------------------------------------------------------------------------


$("#tesoreria_cheque_estatus_che_pr_btn_consultar_n_cheque").click(function() {
if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="" &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!=""))||(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!="")||(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
{	
		var nd=new Date().getTime();
		var proveedor;
	if(getObj('tesoreria_cheque_estatus_pr_op_oculto').value=='1')
	{	
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!=""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;
		}else
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value==""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;;
		}else
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=="")&&(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!=""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;;
		}
	}
	else
	if(getObj('tesoreria_cheque_estatus_pr_op_oculto').value=='2')
	{	
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&empleado="+getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;;
		}else
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value!="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value!="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value==""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;;
		}else
		if((getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=="") &&(getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=="")&&(getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value!=""))
		{
			urls='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&empleado="+getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value+"&op="+getObj('tesoreria_cheque_estatus_pr_op_oculto').value;;
		}	
	}	
		//alert(urls);
	/*	if(getObj('tesoreria_cheque_estatus_pr_proveedor_id').value!="")
		{
			proveedor=getObj('tesoreria_cheque_estatus_pr_proveedor_id').value;
		}
		else
			proveedor=0;
	*/	//url='modulos/tesoreria/cheques/co/cmb.sql.cheque_consulta_estatus.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value;
	//	alert(url);
		/*setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cheque', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
///////////////////////////////////
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_cheques_banco3.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{title: 'Consulta Emergente De Cheques', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					 var busq_cheques= jQuery("#tesoreria_cheques_busqueda_cheques").val(); 
					 jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&busq_cheques="+busq_cheques,page:1}).trigger("reloadGrid"); 
	 		}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_cheques").keypress(function(key)
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
								var busq_cheques= jQuery("#tesoreria_cheques_busqueda_cheques").val(); 
								var proveedor= jQuery("#tesoreria_cheques_pr_proveedor_id").val(); 
								 jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&busq_cheques="+busq_cheques,page:1}).trigger("reloadGrid"); 
								 url="modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&busq_cheques="+busq_cheques;
								//alert(url);
								// setBarraEstado(url);
						}

			}
		});	
//////////////////////////////////						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:750,
								height:450,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								datatype: "json",
								colNames:['Id','Id Banco','Banco','N Cuenta','N chequera','N cheque','Beneficiario','Monto','Ordenes','tipo','estatus','estado','codigo','id_proveedor','fecha_estado','bene','','',''],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_banco',index:'id_banco', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre_banco',index:'nombre_banco', width:100,sortable:false,resizable:false},
									{name:'cuentas',index:'cuentas', width:200,sortable:false,resizable:false},
									{name:'secuencia',index:'secuencia', width:100,sortable:false,resizable:false},
									{name:'n_cheque',index:'n_cheque', width:150,sortable:false,resizable:false},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'ordenes',index:'ordenes', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:100,sortable:false,resizable:false,hidden:true},
									{name:'estado',index:'estado', width:100,sortable:false,resizable:false,hidden:true},	
									{name:'codigo_p',index:'codigo_p', width:100,sortable:false,resizable:false,hidden:true},	
									{name:'id_proveedor',index:'id_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'estado_fecha',index:'estado_fecha', width:100,sortable:false,resizable:false,hidden:true},
									{name:'bene',index:'bene', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partidas',index:'partidas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'compromisos',index:'compromisos', width:100,sortable:false,resizable:false,hidden:true},
									{name:'facturas',index:'facturas', width:100,sortable:false,resizable:false,hidden:true}
										
										  ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: 
								
								function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//limpiando los select
$('#cheques_estatus_partidas').removeOption(/(.?)/);
$('#cheques_estatus_compromisos').removeOption(/(.?)/);
$('#cheques_estatus_facturas').removeOption(/(.?)/);
$('#cheques_estatus_ordenes').removeOption(/(.?)/);
								//*list de partidas
								$("#cheques_estatus_partidas").ajaxAddOption("modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+1,null,false);
									url="modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+1;
									//alert(url);
									//list de compromisos
									$("#cheques_estatus_compromisos").ajaxAddOption("modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+2,null,false);
									url2="modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+2;
									//list de facturas
									$("#cheques_estatus_facturas").ajaxAddOption("modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+3,null,false);
									url3="modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+3;
									//list de ordenes
									$("#cheques_estatus_ordenes").ajaxAddOption("modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+4,null,false);
									url4="modulos/tesoreria/cheques/co/sql_compromisos.php?nd="+nd+"&banco="+getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value+"&proveedor="+getObj('tesoreria_cheque_estatus_pr_proveedor_id').value+"&opcion="+4;
									//alert(url4);
									getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value=ret.id;
									getObj('tesoreria_cheque_estatus_che_pr_n_cheque').value=ret.n_cheque;
									getObj('tesoreria_cheque_estatus_che_pr_id_cheque').value=ret.estatus;
									getObj('tesoreria_cheque_estatus_che_pr_banco_id_banco').value=ret.id_banco;
									getObj('tesoreria_cheque_estatus_che_pr_nombre_banco').value=ret.nombre_banco;
									getObj('tesoreria_cheque_estatus_che_pr_n_cuenta').value=ret.cuentas;
									if(ret.bene!='')
									{
										getObj('benef').style.display='';
										getObj('benef_nom').value=ret.bene;
										}
							
								if(ret.tipo=='1')
									{
										getObj('tesoreria_cheque_estatus_pr_radio1').checked="checked"
										getObj('tesoreria_cheque_estatus_pr_radio2').checked=""

										getObj('tr_empleado').style.display='none';
										getObj('tesoreria_cheque_estatus_pr_op_oculto').value='1';
										getObj('tr_proveedor').style.display='';
										getObj('tesoreria_cheque_estatus_pr_proveedor_id').value=ret.id_proveedor;
										getObj('tesoreria_cheque_estatus_pr_proveedor_codigo').value=ret.codigo_p;
										getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value=ret.nombre_proveedor;

									}else
									if(ret.tipo=='2')
									{
									getObj('tesoreria_cheque_estatus_pr_radio1').checked=""
									getObj('tesoreria_cheque_estatus_pr_radio2').checked="checked"

									getObj('tr_empleado').style.display='';
									getObj('tesoreria_cheque_estatus_pr_op_oculto').value='2';
									getObj('tr_proveedor').style.display='none'
									getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value=ret.codigo_p;
									getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value=ret.nombre_proveedor;
									}



									//getObj('cheques_estatus_ordenes').value=ret.partidas;
									dialog.hideAndUnload();
								
								getObj('tesoreria_cheque_estatus_che_pr_btn_cancelar').style.display='';
								estad=ret.estado;
								estad2=estad.replace("{","");
								getObj('tesoreria_cheque_estatus_che_pr_estatus').value=estad2.replace("}","");
								contador=0;
								estado=(getObj('tesoreria_cheque_estatus_che_pr_estatus').value).split(",");
								//////////////////////limpiando imagenes
									getObj('cheques_estatus_impreso').style.display='none';
									/*getObj('cheques_estatus_finanzas').style.display='none';
									getObj('cheques_estatus_administracion').style.display='none';
									getObj('cheques_estatus_direccion').style.display='none';
								//	getObj('cheques_estatus_tesoreria').style.display='none';
									*/getObj('cheques_estatus_caja').style.display='none';
									getObj('cheques_estatus_pagado').style.display='none';
									getObj('cheques_estatus_archivado').style.display='none';
								////////////////////////////////////////////////////////////////////
								
								vecta_fecha1=ret.estado_fecha.replace("{","");
									vecta_fecha=vecta_fecha1.replace("}","");
									
								vect_fecha=vecta_fecha.split(",");
								if((estado[0]=='1')||(estado[0]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									impreso=fds;
									getObj('cheques_estatus_impreso').style.display='';
									getObj('cheque_estatus_impreso_fecha').value=impreso;
								}
								/*if((estado[8]=='1')||(estado[8]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									finanzas=fds;
									getObj('cheques_estatus_cont').style.display='';
									getObj('cheque_estatus_cont_fecha').value=finanzas;
								}
								if((estado[1]=='1')||(estado[1]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									finanzas=fds;
									getObj('cheques_estatus_finanzas').style.display='';
									getObj('cheque_estatus_finanzas_fecha').value=finanzas;
								}
								if((estado[2]=='1')||(estado[2]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									administracion=fds;
									getObj('cheques_estatus_administracion').style.display='';
									getObj('cheque_estatus_administracion_fecha').value=administracion;

								}
								if((estado[3]=='1')||(estado[3]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									direccion=fds;
									getObj('cheques_estatus_direccion').style.display='';
									getObj('cheque_estatus_direccion_fecha').value=direccion;

								}
								if((estado[4]=='1')||(estado[4]=='2'))
								{
									
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									tesoreria=fds;
									getObj('cheques_estatus_tesoreria').style.display='';
									getObj('cheque_estatus_tesoreria_fecha').value=tesoreria;

								}*/
								if((estado[1]=='1')||(estado[1]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									caja=fds;
									getObj('cheques_estatus_caja').style.display='';
									getObj('cheque_estatus_caja_fecha').value=caja;

								}
								if((estado[3]=='1')||(estado[3]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									archivo=fds;
									getObj('cheques_estatus_archivado').style.display='';
									getObj('cheque_estatus_archivado_fecha').value=archivo;

								}
								if((estado[2]=='1')||(estado[2]=='2'))
								{
									valor=vect_fecha[0];
									fd=valor.substr(1, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
									pagado=fds;
									getObj('cheques_estatus_pagado').style.display='';
									getObj('cheque_estatus_pagado_fecha').value=pagado;

								}
							
														
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
}	
});
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------
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

//$('#tesoreria_cheque_estatus_che_pr_n_cheque').change(consulta_automatica_cheque_codigo)
$("#tesoreria_cheque_estatus_db_btn_consultar_proveedor").click(function() {

		/*var nd=new Date().getTime();
		//getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_beneficiario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Proveedor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/co/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_pr_proveedor_consulta_cheques_m").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").keypress(function(key)
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
							var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
							var busq_codigo=jQuery("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor+"&busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor+"&busq_codigo="+busq_codigo;
							//alert(url);
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/co/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor','rif'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
										{name:'rif',index:'rif', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheque_estatus_pr_proveedor_id').value = ret.id_proveedor;
									getObj('tesoreria_cheque_estatus_pr_proveedor_codigo').value = ret.codigo;
									getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value = ret.nombre;
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('tesoreria_cheque_estatus_pr_proveedor_rif').value=rif2[0];
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
function consulta_automatica_proveedor_estatus()
{
	//getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/co/sql.grid_proveedor_codigo_estatus.php",
            data:dataForm('form_tesoreria_pr_cheque_estatus'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				///setBarraEstado(html);		
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value = recordset[1];
				getObj('tesoreria_cheque_estatus_pr_proveedor_id').value=recordset[0];
				rif=recordset[2];
				rif2 = rif.split("-");
								getObj('tesoreria_cheque_estatus_pr_proveedor_rif').value=rif[0];

	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheque_estatus_pr_proveedor_id').value="";
				}
				
			 }
		});	 	 
}
function consulta_automatica_empleado_estatus()
{
	//getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/co/sql.grid_proveedor_codigo_estatus.php",
            data:dataForm('form_tesoreria_pr_cheque_estatus'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    	
				var recordset=html;			
				
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('tesoreria_cheque_manual_pr_empleado_codigo').value = recordset[0];
				getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value=recordset[1];
				getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value = "";
				getObj('tesoreria_cheque_estatus_pr_proveedor_id').value="";
				
	//setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheque_estatus_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheque_estatus_pr_proveedor_id').value="";
				getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value="";
			//	getObj('tesoreria_cheque_estatus_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}
//////////////////////////////////////////////////////////////////////////////////
$("#tesoreria_cheque_manual_consulta_db_btn_consultar_beneficiario").click(function() {
/*
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_beneficiario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Empleados Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_pr_proveedor_consulta_cheques_m").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc2_dosearch();
					});
				
						function consulta_doc2_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc2_gridReload,500)
										}
						function consulta_doc2_gridReload()
						{
							var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor;
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['RIF','Beneficiario'],
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
									getObj('tesoreria_cheque_estatus_pr_empleado_codigo').value = ret.rif;
									getObj('tesoreria_cheque_estatus_pr_empleado_nombre').value = ret.beneficiario;
									
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
//---------------------------------------------------------------------------------------------------------------------------------------------------------------

</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#tesoreria_cheque_estatus_pr_proveedor_codigo').numeric({});
$('#tesoreria_cheque_estatus_pr_empleado_codigo').numeric({});
$('#tesoreria_cheque_estatus_che_pr_n_cheque').numeric({});

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

$('#tesoreria_cheque_estatus_che_pr_n_cheque').blur(consul_auto_cheque);
$('#tesoreria_cheque_estatus_pr_proveedor_codigo').blur(consulta_automatica_proveedor_estatus);
$('#tesoreria_cheque_estatus_pr_empleado_codigo').blur(consulta_automatica_empleado_estatus);


	consulta_automatica_proveedor_estatus
</script>
	
   <div id="botonera">
   		<img id="tesoreria_cheque_estatus_che_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
	
	  	<img id="tesoreria_cheque_db_btn_imprimir_vp_estatus_che"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" />
   </div>
<form method="post" id="form_tesoreria_pr_cheque_estatus" name="form_tesoreria_pr_cheque_estatus">
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Estatus Cheque </th>
	</tr>
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_cheque_estatus_che_pr_nombre_banco" type="text" id="tesoreria_cheque_estatus_che_pr_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_cheque_estatus_che_pr_banco_id_banco" name="tesoreria_cheque_estatus_che_pr_banco_id_banco"/>
		</li>
		<li id="tesoreria_cheque_estatus_che_pr_btn_consultar_banco" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_cheque_estatus_che_pr_n_cuenta" type="text" id="tesoreria_cheque_estatus_che_pr_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. " readonly=""
					jval="{valid:/^[,.-_123456789]{1,20}$/,message:'Numero Invalido', styleType:'cover'}"
					jvalkey="{valid:/^[,.-_123456789]{1,20}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    
		</li>
		<li id="tesoreria_cheque_estatus_che_pr_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	<tr style="display:none">
	  <th>Beneficiario</th>
	  <td><label>
	     <input name="tesoreria_cheque_estatus_pr_radio" type="radio" id="tesoreria_cheque_estatus_pr_radio1" onclick="getObj('tr_empleado').style.display='none';getObj('tesoreria_cheque_estatus_pr_op_oculto').value='1';getObj('tr_proveedor').style.display='';" value="1" checked="CHECKED"/>
	    Prooveedor</label>
	    &nbsp;&nbsp;
	    <label>
          <input name="tesoreria_cheque_estatus_pr_radio" type="radio" id="tesoreria_cheque_estatus_pr_radio2"  onclick="getObj('tr_empleado').style.display='';getObj('tesoreria_cheque_estatus_pr_op_oculto').value='2'; getObj('tr_proveedor').style.display='none';" value="0" />
      Empleado</label></br>
      <input type="hidden" name="tesoreria_cheque_estatus_pr_op_oculto" id="tesoreria_cheque_estatus_pr_op_oculto" value="1" /></td>
    <tr id="tr_proveedor">
		<th>Proveedor</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				  <input name="tesoreria_cheque_estatus_pr_proveedor_codigo" type="text" id="tesoreria_cheque_estatus_pr_proveedor_codigo"  maxlength="4"
					onchange="consulta_automatica_proveedor_estatus" onclick="consulta_automatica_proveedor_estatus"
					message="Introduzca un Codigo para el proveedor."  size="5"
					jval="{valid:/^[,.-_123456789]{1,4}$/,message:'Numero Invalido', styleType:'cover'}"
					jvalkey="{valid:/^[,.-_123456789]{1,4}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
				
				  <input name="tesoreria_cheque_estatus_pr_proveedor_nombre" type="text" id="tesoreria_cheque_estatus_pr_proveedor_nombre" size="45" maxlength="60" readonly
					message="Introduzca el nombre del Proveedor." 
					jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
					jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	
				<input type="hidden" name="tesoreria_cheque_estatus_pr_proveedor_id" id="tesoreria_cheque_estatus_pr_proveedor_id" readonly />
				<input type="hidden" name="tesoreria_cheque_estatus_pr_proveedor_rif" id="tesoreria_cheque_estatus_pr_proveedor_rif" readonly />
				</li> 
					<li id="tesoreria_cheque_estatus_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
	  </ul>				</td>		
	</tr>
    <tr id="benef" style="display:none">
    	<th>Beneficiario:</th>
        <td><input type="text" id="benef_nom" name="benef_nomf"  maxlength="60" /></td>
    </tr>
    
    <tr id="tr_empleado" style="display:none">
      <th height="25">Empleado</th>
      <td >		<ul class="input_con_emergente">
	  <li><input name="tesoreria_cheque_estatus_pr_empleado_codigo" type="text" id="tesoreria_cheque_estatus_pr_empleado_codigo"
				onchange="consulta_automatica_empleado_estatus" onclick="consulta_automatica_empleado_estatus"  size="6"  maxlength="6" 
				message="Introduzca un Codigo para el Empleado."
				/>
	    <input name="tesoreria_cheque_estatus_pr_empleado_nombre" type="text" id="tesoreria_cheque_estatus_pr_empleado_nombre" size="40" maxlength="60"
				message="Introduzca el nombre del Empleado."
				jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	
		  <label>
		    <input type="hidden" name="textprue" id="textprue" />
		    </label>
	      <input type="hidden" name="textprue2" id="textprue2" />
	      <input type="hidden" name="textprue3" id="textprue3" />
		  </li> 
	  		<li id="tesoreria_cheque_manual_consulta_db_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>
    </tr>
	<tr>
		 <th>N&ordm; Cheque:</th> 
		 
		  <td>
		  		<ul class="input_con_emergente">
				<li>
				  <input name="tesoreria_cheque_estatus_che_pr_n_cheque" type="text" id="tesoreria_cheque_estatus_che_pr_n_cheque"  value="000000" onChange="consul_auto_cheque()" size="6"    maxlength="6"  message="Introduzca el Número de cheque " alt="signed-dec"
					    jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Numero Invalido', styleType:'cover'}"
						jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    
				  
				  <input type="hidden"  id="tesoreria_cheque_estatus_che_pr_id_cheque" name="tesoreria_cheque_estatus_che_pr_id_cheque"/>
				 <input type="hidden" id="tesoreria_cheque_estatus_che_pr_estatus" name="tesoreria_cheque_estatus_che_pr_estatus" />	
				</li> 
					<li id="tesoreria_cheque_estatus_che_pr_btn_consultar_n_cheque" class="btn_consulta_emergente"></li>
				</ul>	  </td>				 
   </tr>
   <tr>
   			<th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Datos de origen del Cheque:</th>
			
   </tr>
   <tr>
 
    <th colspan="2" > <table width="517" height="144" class= width="70%" >
	
	 
	  <tr>
	     <th width="11%"style="text-align:center;">
				1</th>
	
        <th width="11%"style="text-align:center;">
				2</th>
        <th width="18%"style="text-align:center;">
				3</th>
		<th width="17%"style="text-align:center;">
				4</th>
	</tr>
	<tr>
        <th width="11%"style="text-align:center;">	  	
			<p><img   src="imagenes/iconos/grafico1.png"  /></br>
		    </p>
			<p>Partidas		</p>
			<p><select style="width:100px;" name="cheques_estatus_partidas" size="6"  multiple="MULTIPLE" id="cheques_estatus_partidas">
			</select></p>
			</th>
		
		
        <th width="11%"style="text-align:center;">
			<p><img   src="imagenes/iconos/grafico2.png"  /></br>
			  Compromisos</p>
			<p><select style="width:100px;" name="cheques_estatus_compromisos" size="6"  multiple="MULTIPLE" id="cheques_estatus_compromisos">
			</select></p>
			</th>
        <th width="20%"style="text-align:center;">
			<p><img   src="imagenes/iconos/grafico3.png"   /></br>
	</p>
			<p>		  Facturas</p>
			<p><select style="width:100px;" name="cheques_estatus_facturas" size="6"  multiple="MULTIPLE" id="cheques_estatus_facturas">
			</select></p>
			</th>
		<th width="20%"style="text-align:center;">
			<p><img   src="imagenes/iconos/grafico4.png"   /></br>
	</p>
			<p> Ordenes  </p>
			<p><select style="width:100px;" name="cheques_estatus_ordenes" size="6"  multiple="MULTIPLE" id="cheques_estatus_ordenes">
			</select></p></th>
	</tr>
	</table>
	</th>
	</tr> 
   <tr>
   			
   </tr>
         	    <th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px"> Ubicaci&oacute;n de Cheques Seg&uacute;n Estatus	</td>	  
         	  </tr>

   <tr>
 
    <th colspan="2" > <table width="517" height="144" class= width="70%" >
	
	 
	  <tr>
	     <th width="11%"style="text-align:center;">
				<img  id="cheques_estatus_impreso"  src="imagenes/iconos/check_mark.png" style="display:none" />1</th>
	
        <th width="11%"style="text-align:center;">
				<img  id="cheques_estatus_caja" src="imagenes/iconos/check_mark.png" style="display:none" />2</th>
        <th width="18%"style="text-align:center;">
				<img id="cheques_estatus_pagado" src="imagenes/iconos/check_mark.png" style="display:none" />3</th>
		<th width="17%"style="text-align:center;">
				<img  id="cheques_estatus_archivado" src="imagenes/iconos/check_mark.png" style="display:none" />4</th>
	</tr>
	
	  <tr>
        <th width="11%"style="text-align:center;">	  	
			<p><img   src="imagenes/iconos/frameprint.png"  /></br>
		    </p>
			<p>Impreso		</p>
			<input type="text" name="cheque_estatus_impreso_fecha" id="cheque_estatus_impreso_fecha" readonly="readonly" maxlength="10" size="10"/>	
		</th>
		
		
        <th width="11%"style="text-align:center;">
			<p><img   src="imagenes/iconos/caja.png"  /></br>
			  Caja</p>
			<p>		
			  <input type="text" name="cheque_estatus_caja_fecha" id="cheque_estatus_caja_fecha" readonly="readonly" maxlength="10" size="10"/>	
			  
	          </p></th>
        <th width="20%"style="text-align:center;">
			<p><img   src="imagenes/iconos/pagado.png"   /></br>
	</p>
			<p>		  Pagado</p>
			<p>
			  <input type="text" name="cheque_estatus_pagado_fecha" id="cheque_estatus_pagado_fecha" readonly="readonly" maxlength="10" size="10"/>	
			  
	          </p></th>
		<th width="20%"style="text-align:center;">
			<p><img   src="imagenes/iconos/archivo.png"   /></br>
	</p>
			<p>		  Archivado</p>
			<p>
			
			  <input type="text" name="cheque_estatus_archivado_fecha" id="cheque_estatus_archivado_fecha" readonly="readonly" maxlength="10" size="10"/>	
			  
	          </p></th>
	</tr>
	<tr>				
		
	     </tr>
	<tr>	
		
				  
      </tr>
	  <tr>
          </tr>
	  <tr>
        
		
      </tr>
	 </table> 
  <tr>
  </tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
		</td>
	</tr>
</table> 
  <input  name="tesoreria_cheques_estatus_che_pr_tipo" type="hidden" id="tesoreria_cheques_estatus_che_pr_tipo" />
</form>