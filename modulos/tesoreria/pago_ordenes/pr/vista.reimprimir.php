<?php
session_start();
?>
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
//////////////////////////////--impresiones automaticas-/////////////////////////////////////////////////
$("#tesoreria_cheque_db_btn_imprimir_vp_reimpresion").click(function() {
ncheque=getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value;
beneficiario=getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value;
monto=getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value;

	/*if(confirm("�Desea reimprimir el cheque n� "+ncheque+" del beneficiario "+beneficiario+" por el siguiente monto: "+monto+"?")) 
	{	*/
 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	�Desea reimprimir el cheque n� "+ncheque+" del beneficiario "+beneficiario+" por el siguiente monto: "+monto+"?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
 {
	if(val=="ACEPTAR")
	{
					
					if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value!='1')
					{
					reimprimir_cheque_auto();
					}
					else
					if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value=='1')
					{
					 reimprimir_mismo_numero_auto();	
					}
					//limpiar_reimprimir();		
	}
})
});
function reimprimir_cheque_auto(){
getObj('tesoreria_cheque_reimpresion_pr_n_cheque').disabled="";
	var url;
		$.ajax (
					{
						url: "modulos/tesoreria/cheques/pr/sql.anular.reimprimir.php",
						data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							if((html!='Error-REIMPRIMIR' )&&(html!='chequera_agotada')&&(html!='ERROR-EN ORDEN')&&(html!='ERROR-P:MODIFICAR-CHEQUERA')&&(html!='Error-act cheque')&&(html!='no_disponible_saldo')&&(html!='integrado'))
							{

								//alert(html);
								recordset=html;
								recordset = recordset.split("*");
								if(recordset[2]==1)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6]; 
								}else
								if(recordset[2]==2)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@opcion="+recordset[3]+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6]; 
								}
								openTab("cheques",url);
								modificar_inactivo_reimpreso();
							setTimeout("limpiar_reimprimir()",2000);

							}
							else
							{
								if(html=='chequera_agotada')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El banco y la cuenta elegida para reimprimir el cheque no cuenta con chequeras activas</p></div>",true,true);
								}
								if(html=='no_disponible_saldo')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACI�N</p></div>",true,true);
								}
								if(html=='integrado')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Imposible Reimprimir Cheque Integrado</p></div>",true,true);
								}
								/*else
								alert(html); */
								//setBarraEstado(html);
							}
						}
					});
}
//--- funcion q reimprime con el mismo numero al cheque

function reimprimir_mismo_numero_auto(){
getObj('tesoreria_cheque_reimpresion_pr_n_cheque').disabled="";
	var url;
		$.ajax (
					{
						url: "modulos/tesoreria/cheques/pr/sql.anular.reimprimir.num.php",
						data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							if((html!='Error-REIMPRIMIR' )&&(html!='chequera_agotada')&&(html!='ERROR-EN ORDEN')&&(html!='ERROR-P:MODIFICAR-CHEQUERA')&&(html!='Error-act cheque'))
							{
								recordset=html;
								recordset = recordset.split("*");
								if(recordset[2]==1)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6];
								}else
								if(recordset[2]==2)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@opcion="+recordset[3]+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6];
								}
								//document.getElementById('iframeOculto_reimprimir').src=url;  
								openTab("cheques",url);
								setTimeout("limpiar_reimprimir()",2000);

							}
							else
							{
								alert(html); 
								setBarraEstado(html);
							}
						}
					});
}	
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_reimpresion_pr_btn_reimpresion").click(function() {
ncheque=getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value;
beneficiario=getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value;
monto=getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value;
 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea reimprimir el cheque n "+ncheque+" del beneficiario "+beneficiario+" por el siguiente monto: "+monto+"?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
 {
	if(val=="ACEPTAR")
	{
	/*if(confirm("�Desea reimprimir el cheque n� "+ncheque+" del beneficiario "+beneficiario+" por el siguiente monto: "+monto+"?")) 
	{	
	*/				
					if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value!='1')
					{
					reimprimir_cheque();
					}
					else
					if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value=='1')
					{
					 reimprimir_mismo_numero();	
					}
					//limpiar_reimprimir();		
	}
	})
});

function reimprimir_cheque(){
getObj('tesoreria_cheque_reimpresion_pr_n_cheque').disabled="";
	var url;
		$.ajax (
					{
						url: "modulos/tesoreria/cheques/pr/sql.anular.reimprimir.php",
						data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
						type:'POST',
						cache: false,
						success: function(html)
						{//alert(html);
							if((html!='Error-REIMPRIMIR' )&&(html!='chequera_agotada')&&(html!='ERROR-EN ORDEN')&&(html!='ERROR-P:MODIFICAR-CHEQUERA')&&(html!='Error-act cheque')&&(html!='no_disponible_saldo')&&(html!='integrado'))
							{
								recordset=html;
								recordset = recordset.split("*");
								if(recordset[2]==1)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6]; 
								}else
								if(recordset[2]==2)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_manuales.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@opcion="+recordset[3]+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6]; 
								}
								modificar_inactivo_reimpreso();
								Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif />REALIZANDO IMPRESI&Oacute;N:porfavor presione el boton cerrar de esta ventana luego que haya culminado la impresi�n</p></div>", ["CERRAR"], 
								function(val)
								 {
								 if(val=="CERRAR")
									{
										setTimeout("limpiar_reimprimir()",1000);
									}   
								 }, {title:"SAI-OCHINA"});

							}
							else
							{
							
								if(html=='no_disponible_saldo')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACI&Oacute;N</p></div>",true,true);
								}
								if(html=='chequera_agotada')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EL BANCO Y LA CUENTA ELEGIDA PARA REIMPRIMIR EL CHEQUE NO CUENTA CON CHEQUERAS ACTIVAS</p></div>",true,true);
								}
								if(html=='integrado')
								{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />IMPOSIBLE REIMPRIMIR CHEQUE INTEGRADO</p></div>",true,true);
								}
							
							}
						}
					});
}	
//--- funcion q reimprime con el mismo numero al cheque

function reimprimir_mismo_numero(){
getObj('tesoreria_cheque_reimpresion_pr_n_cheque').disabled="";
	var url;
		$.ajax (
					{
						url: "modulos/tesoreria/cheques/pr/sql.anular.reimprimir.num.php",
						data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
						type:'POST',
						cache: false,
						success: function(html)
						{
							if((html!='Error-REIMPRIMIR' )&&(html!='chequera_agotada')&&(html!='ERROR-EN ORDEN')&&(html!='ERROR-P:MODIFICAR-CHEQUERA')&&(html!='Error-act cheque'))
							{
								recordset=html;
								
								
								recordset = recordset.split("*");
								if(recordset[2]==1)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6]; 
							
								}else
								if(recordset[2]==2)
								{
									url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_manuales.php�id_banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value+"@ordenes="+getObj('tesoreria_cheque_reimpresion_pr_ordenes').value+"@secuencia="+recordset[1]+"@caducidad="+getObj('tesoreria_cheques_reimpresion_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value+"@opcion="+recordset[3]+"@proyecto="+recordset[5]+"@ejecutora="+recordset[4]+"@partida="+recordset[6];
								}
								//setBarraEstado(url);
								Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif />REALIZANDO IMPRESI�N:porfavor presione el boton cerrar de esta ventana luego que haya culminado la impresi�n</p></div>", ["CERRAR"], 
								
								function(val)
								 {
									 if(val=="CERRAR")
									{
										setTimeout("limpiar_reimprimir()",1000)
									}   
								 }, {title:"SAI-OCHINA"});
//-document.getElementById('iframeOculto_reimprimir').src=url;  
								//openTab("cheques",url);
								//modificar_inactivo_reimpreso();
//-setTimeout("limpiar_reimprimir()",2000);

							}
							else
							{
								alert(html); 
								setBarraEstado(html);
							}
						}
					});
}	
////////////////////////////////////////////////////////				
function modificar_inactivo_reimpreso() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.modificar_inactivo_reimpreso.php",
				data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					recordset=html;
					recordset = recordset.split("*");
					if (recordset[0]=="inactiva")
					{
						/*confirmar=confirm("La chequera actual se agoto desea activar la siguiente chequera numero : "+recordset[1]);
						if (confirmar)
						{
							activo_chequera_reimpresion();
				
						}*/
						 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	LA CHEQUERA ACTUAL SE AGOT&Oacute; DESEA ACTIVAR LA SIGUIENTE N&Uacute;MERO : "+recordset[1]+"</p></div>", ["ACEPTAR","CANCELAR"], 
						function(val)
						 {
							if(val=='ACEPTAR')
							{
								activo_chequera_reimpresion();
							}   
						 }, {title:"SAI-OCHINA"});
												}
					else if (recordset[0]=="inactiva2")
					{					
					//	alert("La chequera actual se agoto, no hay mas chequeras cargadas para este banco");
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CHEQUERA ACTUAL SE AGOT&Oacute; NO HAY MAS CHEQUERAS CARGADA PARA ESTE BANCO</p></div>",true,true);

						}
					/*else
					{
						setTimeout("limpiar_reimprimir()",1000);
					}	*/
					
				}
	});
	
}
////////////////////////////////////////////////////////////////
function activo_chequera_reimpresion() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.activo_chequera_reimpresion.php",
				data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					
					recordset=html;
					recordset = recordset.split("*");
					 
					if (recordset[0]!="activa")
					{	
						alert(html);
						setBarraEstado(html);
						}
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
						
					
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////
//--
function limpiar_iframe_reimprimir()
{
	document.getElementById('iframeOculto_reimprimir').src="";
}
//---
function limpiar_reimprimir(){
setBarraEstado("");
	getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value="";
	getObj('tesoreria_cheque_reimpresion_pr_id_cheque').value="";
	getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value="";
	getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value="";
	getObj('tesoreria_cheque_reimpresion_pr_nombre_banco').value="";
	getObj('tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo').value="";
	getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value="";
	getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value="";
	getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value ="";
	getObj('tesoreria_cheque_reimpresion_pr_ordenes').value="";
	getObj('tesoreria_cheque_reimpresion_pr_secuencia').value="";
	getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value="0,00";
	//getObj('tr_reimprimir').style.display='none';
	document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="";
	getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='0';

	getObj('tesoreria_cheque_reimpresion_pr_btn_reimpresion').style.display='none';
	getObj('tesoreria_cheque_db_btn_imprimir_vp_reimpresion').style.display='none';

	getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value="000000";
	setTimeout("limpiar_iframe_reimprimir()",5000);


	
}
$("#tesoreria_cheque_reimpresion_pr_btn_cancelar").click(function() {
limpiar_reimprimir();
});	

//------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_reimpresion_pr_btn_consultar_cuentas_chequeras").click(function() {
if(getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value!="")
{
	var nd=new Date().getTime();
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas_anular.php?nd='+nd+'&banco='+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value;

	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N� Cuenta','Estatus','CuentaNuevo'],
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
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value=ret.ncuenta;
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value=ret.cuentan;

				 			if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value==getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)&&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value==getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										getObj('tr_reimprimir').style.display='';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="checked";

									}
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value!=getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)||(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										//getObj('tr_reimprimir').style.display='none';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='0';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="";
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
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});
//----
$("#tesoreria_cheque_reimpresion_pr_btn_consultar_cuentas_chequeras_nuevo").click(function() {

if(getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value!="")
{
	//urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value;
		  urls_cuenta='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value;
		
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:urls_cuenta,
								datatype: "json",
								colNames:['Id','N� Cuenta','Estatus'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value=ret.ncuenta;
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value==getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)&&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value==getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										getObj('tr_reimprimir').style.display='';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="checked";

									}
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value!=getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)||(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										//getObj('tr_reimprimir').style.display='none';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='0';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="";
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
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});

//-----------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_reimpresion_pr_btn_consultar_banco").click(function() {
		/*var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });
*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
					dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
					dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco_anular.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_bancos").keypress(function(key)
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
							var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco_anular.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/sql_grid_banco_anular.php?busq_banco="+busq_banco;
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
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco_anular.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo �rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','id_banco_cheques','banco_cheques','cuenta_banco_cheques'],
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
									
									getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value=ret.id_banco_cheques;
									getObj('tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo').value=ret.banco_cheques;
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value=ret.cuenta_banco_cheques;
									getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value=ret.id;
									getObj('tesoreria_cheque_reimpresion_pr_nombre_banco').value=ret.nombre;
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value=ret.cuentas;
									
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value==getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)&&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value==getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										getObj('tr_reimprimir').style.display='';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="checked";

									}
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value!=getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)||(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										//getObj('tr_reimprimir').style.display='none';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='0';
										//document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="";


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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_reimpresion_pr_btn_consultar_banco_nuevo").click(function() {
		urls_banco='modulos/tesoreria/cheques/pr/sql_grid_banco.php';

		/*var nd=new Date().getTime();
		
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
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
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
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
							var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco;
						//	setBarraEstado(url);
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
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:urls_banco,
								//'modulos/tesoreria/cheques/pr/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo �rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas'],
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
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value=ret.id;
									getObj('tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo').value=ret.nombre;
									getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value=ret.cuentas;
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value==getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)&&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value==getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										getObj('tr_reimprimir').style.display='';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='1';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="checked";

									}
									if((getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value!=getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value)||(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value))
									{
										//getObj('tr_reimprimir').style.display='none';
										getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value='0';
										document.form_tesoreria_pr_cheque_reimprimir.tesoreria_cheques_reimpresion_pr_mismo_numero.checked="";
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
//-------------------------------------------------------------------------------------------------------------------------------------

$("#tesoreria_cheque_reimpresion_pr_btn_consultar_n_cheque").click(function() {
if(getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value!="" &&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=""))
{	
		/*var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cheque', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/cheques/pr/grid_cheques_banco.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					 var busq_cheques= jQuery("#tesoreria_cheques_busqueda_cheques").val(); 
					 var busq_benef=jQuery("#tesoreria_busqueda_beneficiario_an").val();
					 var busq_prove=jQuery("#tesoreria_busqueda_proveedor_an").val();
					 jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value+"&busq_cheques="+busq_cheques+"&busq_benef="+busq_benef+"&busq_prove="+busq_prove,page:1}).trigger("reloadGrid"); 
	 		}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_cheques").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#tesoreria_busqueda_beneficiario_an").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#tesoreria_busqueda_proveedor_an").keypress(function(key)
				{		if(key.keyCode==27){dialog.hideAndUnload();}
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
								var busq_benef=jQuery("#tesoreria_busqueda_beneficiario_an").val();
					 			var busq_prove=jQuery("#tesoreria_busqueda_proveedor_an").val();
								 jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value+"&busq_cheques="+busq_cheques+"&busq_benef="+busq_benef+"&busq_prove="+busq_prove,page:1}).trigger("reloadGrid"); 
								 url="modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value+"&busq_cheques="+busq_cheques+"&busq_benef="+busq_benef+"&busq_prove="+busq_prove;
								// setBarraEstado(url);
						}

			}
		});							
				function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Informaci�n del Servidor",		
								url:'modulos/tesoreria/cheques/pr/cmb.sql.cheque_consulta.php?nd='+nd+"&banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value,
								datatype: "json",
								colNames:['Id','Id Banco','Banco','N Cuenta','N chequera','N cheque','Beneficiario','Monto','Ordenes','tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'id_banco',index:'id_banco', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre_banco',index:'nombre_banco', width:160,sortable:false,resizable:false},
									{name:'cuentas',index:'cuentas', width:200,sortable:false,resizable:false},
									{name:'secuencia',index:'secuencia', width:100,sortable:false,resizable:false},
									{name:'n_cheque',index:'n_cheque', width:150,sortable:false,resizable:false},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:100,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'ordenes',index:'ordenes', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true}

										  ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: 
								
								function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheque_reimpresion_pr_id_cheque').value=ret.id;
									getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value=ret.n_cheque;
									getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value = ret.nombre_proveedor;
									orden=ret.ordenes;
									orden1=orden.replace("{","");
									getObj('tesoreria_cheque_reimpresion_pr_ordenes').value=orden1.replace("}","");
									getObj('tesoreria_cheque_reimpresion_pr_secuencia').value=ret.secuencia;
									/*valor=parseFloat(ret.monto);
								   	valor = valor.currency(2,',','.');*/	
								   	getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value=ret.monto;
									getObj('tesoreria_cheques_reimpresion_pr_tipo').value=ret.tipo;
									dialog.hideAndUnload();
							
								getObj('tesoreria_cheque_reimpresion_pr_btn_cancelar').style.display='';
								getObj('tesoreria_cheque_reimpresion_pr_btn_reimpresion').style.display='';
								getObj('tesoreria_cheque_db_btn_imprimir_vp_reimpresion').style.display='';
								
								
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
function consulta_automatica_cheque_codigo()
{
if(getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value!="" &&(getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value!=""))
    {  		
			 var nd=new Date().getTime();
			$.ajax({
					url:'modulos/tesoreria/cheques/pr/sql.cheque_consulta_codigo.php?numero_cheque='+getObj('tesoreria_cheque_reimpresion_pr_n_cheque').value+"&banco="+getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value+"&cuenta="+getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value,
					data:dataForm('form_tesoreria_pr_cheque_reimprimir'),
					type:'POST',
					cache: false,
					 success:function(html)
					 {
				//	 alert(html);
					// setBarraEstado(html);//
						if((html==""))
					
							{
								 //limpiar();
									getObj('tesoreria_cheque_reimpresion_pr_id_cheque').value="";
									getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value ="";
									getObj('tesoreria_cheque_reimpresion_pr_secuencia').value="";
									getObj('tesoreria_cheque_reimpresion_pr_ordenes').value="";
									
//alert(orden).value;
									/*if(orden!="")
									{	orden1=orden.replace("{","");
										getObj('tesoreria_cheque_reimpresion_pr_ordenes').value=orden1.replace("}","");
									}*/
									getObj('tesoreria_cheque_reimpresion_pr_btn_reimpresion').style.display='none';
									getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value="0,00";
								}	
					    if((html!="")||(html!=null)||(html!="undefined"))
						{		var recordset=html;
								if(recordset)
								{
									//alert(recordset);
									recordset = recordset.split("*");
									getObj('tesoreria_cheque_reimpresion_pr_id_cheque').value=recordset[0];
									getObj('tesoreria_cheque_reimpresion_pr_beneficiario_nombre').value = recordset[1];
									getObj('tesoreria_cheque_reimpresion_pr_secuencia').value=recordset[4];
									orden=recordset[3];
									if(orden!="")
									{	orden1=orden.replace("{","");
										getObj('tesoreria_cheque_reimpresion_pr_ordenes').value=orden1.replace("}","");
									}
									valor=parseFloat(recordset[2]);
								   	valor = valor.currency(2,',','.');	
									getObj('tesoreria_cheques_reimpresion_pr_tipo').value=recordset[5];
								   	getObj('tesoreria_cheque_reimpresion_pr_monto_pagar').value=valor;
			
								getObj('tesoreria_cheque_reimpresion_pr_btn_cancelar').style.display='';
								getObj('tesoreria_cheque_reimpresion_pr_btn_reimpresion').style.display='';
								getObj('tesoreria_cheque_db_btn_imprimir_vp_reimpresion').style.display='';

								}
								
						}
						    
					 }
				});	 	 
		}
}			

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

//$('#tesoreria_cheque_reimpresion_pr_n_cheque').change(consulta_automatica_cheque_codigo)

</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

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
$("#tesoreria_cheques_reimpresion_pr_mismo_numero").click(function(){
		if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value=="0")
		{
			getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value="1"
			getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value=getObj('tesoreria_cheque_reimpresion_pr_banco_id_banco').value;
			getObj('tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo').value=getObj('tesoreria_cheque_reimpresion_pr_nombre_banco').value;
			getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value=getObj('tesoreria_cheque_reimpresion_pr_n_cuenta').value;
		}	
		else
		if(getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value=="1")
		{
			getObj('tesoreria_cheques_reimpresion_pr_mismo_numero_oculto').value="0"
/*			getObj('tesoreria_cheque_reimpresion_pr_banco_id_nuevo').value="";
			getObj('tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo').value="";
			getObj('tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo').value="";*/
	
		}
	});
$("#tesoreria_cheques_reimpresion_pr_endosable").click(function(){
		if(getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value=="0")
			getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value="1"
		else
		if(getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value=="1")
			getObj('tesoreria_cheques_reimpresion_pr_endosable_oculto').value="0"
		
	});

$('#tesoreria_cheque_reimpresion_pr_n_cheque').blur(consulta_automatica_cheque_codigo);
	
</script>
	
   <div id="botonera">
   		<img id="tesoreria_cheque_reimpresion_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
		<img id="tesoreria_cheque_reimpresion_pr_btn_reimpresion" class="btn_reimprimir"src="imagenes/null.gif" style="display:none"/>
	  	<img id="tesoreria_cheque_db_btn_imprimir_vp_reimpresion"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" />
   </div>
<form method="post" id="form_tesoreria_pr_cheque_reimprimir" name="form_tesoreria_pr_cheque_reimprimir">
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Reimprimir Cheques  </th>
	</tr>
	  <th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Actual	</td>	  </tr>
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_cheque_reimpresion_pr_nombre_banco" type="text" id="tesoreria_cheque_reimpresion_pr_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z �����������-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z �����������-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_cheque_reimpresion_pr_banco_id_banco" name="tesoreria_cheque_reimpresion_pr_banco_id_banco"/>
		</li>
		<li id="tesoreria_cheque_reimpresion_pr_btn_consultar_banco" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_cheque_reimpresion_pr_n_cuenta" type="text" id="tesoreria_cheque_reimpresion_pr_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el N�mero de cuenta. " readonly=""/>
		</li>
		<li id="tesoreria_cheque_reimpresion_pr_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	<tr>
		 <th>N&ordm; Cheque:</th> 
		 
		  <td>
		  		<ul class="input_con_emergente">
				<li>
				  <input name="tesoreria_cheque_reimpresion_pr_n_cheque" type="text" id="tesoreria_cheque_reimpresion_pr_n_cheque"  value="000000"   size="6"    maxlength="6"  message="Introduzca el N�mero de cheque " alt="signed-dec"  />
				  <input type="hidden"  id="tesoreria_cheque_reimpresion_pr_id_cheque" name="tesoreria_cheque_reimpresion_pr_id_cheque"/>
				  <input type="hidden"  id="tesoreria_cheque_reimpresion_pr_secuencia" name="tesoreria_cheque_reimpresion_pr_secuencia"/>
				</li> 
					<li id="tesoreria_cheque_reimpresion_pr_btn_consultar_n_cheque" class="btn_consulta_emergente"></li>
				</ul>	  </td>				 
   </tr>
     <tr id="tr_reimprimir" >
   		<th colspan="3">&nbsp;&nbsp;Reimprimir con mismo n&uacute;mero  
		  <input name="tesoreria_cheques_reimpresion_pr_mismo_numero" id="tesoreria_cheques_reimpresion_pr_mismo_numero" type="checkbox" value=""  />
		
		<input type="hidden" name="tesoreria_cheques_reimpresion_pr_mismo_numero_oculto" id="tesoreria_cheques_reimpresion_pr_mismo_numero_oculto"  value="0"/>
	
   </tr>
      	  <th colspan="2" align="center" bgcolor="#83B4D8" style="text-align:center;color:#FFFFFF; font-size:14px">Nuevo	</td>	  </tr>
 <th>Banco:</th>
	  <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo" type="text" id="tesoreria_cheque_reimpresion_pr_nombre_banco_nuevo"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z �����������-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z �����������-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_cheque_reimpresion_pr_banco_id_nuevo" name="tesoreria_cheque_reimpresion_pr_banco_id_nuevo"/>
		</li>
		<li id="tesoreria_cheque_reimpresion_pr_btn_consultar_banco_nuevo" class="btn_consulta_emergente"></li>
		</ul>	</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo" type="text" id="tesoreria_cheque_reimpresion_pr_n_cuenta_nuevo"   value="" size="50" maxlength="20" message="Introduzca el N�mero de cuenta. " readonly=""/>
		</li>
		<li id="tesoreria_cheque_reimpresion_pr_btn_consultar_cuentas_chequeras_nuevo" class="btn_consulta_emergente"></li>
		</ul>		</td>
   <tr>
		<th>Beneficiario:</th>
	    <td><label><input name="tesoreria_cheque_reimpresion_pr_beneficiario_nombre" type="text" id="tesoreria_cheque_reimpresion_pr_beneficiario_nombre"   value="" size="50" maxlength="20"  readonly=""/></label></td>
	</tr>
	<tr>
		<th>
			Monto a Pagar:		</th>
	    <td>
		<input align="right"  name="tesoreria_cheque_reimpresion_pr_monto_pagar" type="text" id="tesoreria_cheque_reimpresion_pr_monto_pagar"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00" size="16" maxlength="16"  style="text-align:right" readonly="" />
		<span class="bottom_frame">
		<input align="right"  name="tesoreria_cheque_reimpresion_pr_ordenes" type="hidden" id="tesoreria_cheque_reimpresion_pr_ordenes" size="16" maxlength="16"  style="text-align:right" readonly="" />
		</span></td>
	</tr>
	<tr>
	<th>&nbsp;</th>
	  <td>&nbsp;</td>
	</tr>
	<tr>	<th colspan="3">&nbsp;&nbsp;Cheque No endosable 
		<input name="tesoreria_cheques_reimpresion_pr_endosable" id="tesoreria_cheques_reimpresion_pr_endosable" type="checkbox" value="" checked="checked" />
		
		<input type="hidden" name="tesoreria_cheques_reimpresion_pr_endosable_oculto" id="tesoreria_cheques_reimpresion_pr_endosable_oculto"  value="1"/>
		<label>&nbsp;&nbsp;&nbsp;&nbsp;caduca a:
		<select name="tesoreria_cheques_reimpresion_pr_caducidad" id="tesoreria_cheques_reimpresion_pr_caducidad">
		  <option value="0">No incluir</option>
		  <option value="1">15 DIAS</option>
		  <option value="2">60 DIAS</option>
		  <option value="3" selected="selected" >90 DIAS</option>
		  <option value="4">120 DIAS</option>
        </select>
		</label></th>
    </tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
		</td>
	</tr>
</table> 
  <iframe id="iframeOculto_reimprimir" name="iframeOculto_reimprimir" style="width:0px; height:0px; border:0px"></iframe>
<input  name="tesoreria_cheques_reimpresion_pr_tipo" type="hidden" id="tesoreria_cheques_reimpresion_pr_tipo" />
</form>