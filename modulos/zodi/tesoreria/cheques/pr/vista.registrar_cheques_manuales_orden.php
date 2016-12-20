<?php
session_start();

?>
<script type='text/javascript'>
function nombre_beneficiario2()
{
	if(getObj('tesoreria_cheque_manual_ord_db_otro_beneficiario_oc').value=="0")
	{
		getObj('tr_empleado32').style.display='';
		getObj('tesoreria_cheque_manual_ord_db_otro_beneficiario_oc').value="1";
	}else
	if(getObj('tesoreria_cheque_manual_ord_db_otro_beneficiario_oc').value=="1")
	{
		getObj('tr_empleado32').style.display='none';
		getObj('tesoreria_cheque_manual_ord_db_otro_beneficiario_oc').value="0";
	}
	
}
$("#tesoreria_cheques_manual_orden_db_btn_actualizar").click(function() {
if(getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value!="")
{
	getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.actualizar.manual_orden.php",
			data:dataForm('form_tesoreria_db_cheques_manual_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					limpiar_manual_orden();
					getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='none';
					getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='';
					getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='none';
					//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='none';
					getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php"}).trigger("reloadGrid");
				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='none';
					limpiar_manual_orden();
					getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='';
					getObj('tesoreria_cheques_manual_orden_db_btn_cancelar').style.display='';
					getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php"}).trigger("reloadGrid");
			}else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
					}	
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
}else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE ACTUALIZAR SIN HABER ELEGIDO ALGUNA ORDEN DE PAGO</p></div>",true,true);

});


////////////////////////////////////////////////////////////////////////////
function modificar_inactivo_orden() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.modificar_inactivo.manual_orden.php",
				data:dataForm('form_tesoreria_db_cheques_manual_orden'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					//alert(html);
					recordset=html;
					recordset = recordset.split("*");
					if (recordset[0]=="inactiva")
					{
					 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	LA CHEQUERA ACTUAL SE AGOTÓ DESEA ACTIVAR LA SIGUIENTE NÚMERO : "+recordset[1]+"</p></div>", ["ACEPTAR","CANCELAR"], 
					function(val)
					 {
                		if(val=='ACEPTAR')
						{
							activo_chequera_manual22();
						}   
					 }, {title:"SAI-OCHINA"});
						
/*---confirmar=confirm("La chequera actual se agotó desea activar la siguiente chequera numero : "+recordset[1]);
		if (confirmar)
		{
		 activo_chequera();
		}
*/						//setTimeout("limpiar_manual_orden()",2000);
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					}
					else if (recordset[0]=="inactiva2")
					{					

							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CHEQUERA ACTUAL SE AGOTÓ NO HAY MAS CHEQUERAS CARGADA PARA ESTE BANCO</p></div>",true,true);

					}
					/*else
					{
						//alert("no limpio");
    					//setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />PRUEBA</p></div>",true,true);
						//setTimeout("limpiar_manual_orden()",200);
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");

					}	*/
					
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////////
function activo_chequera_manual22() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.activo_chequera.php",
				data:dataForm('form_tesoreria_db_cheques_manual_orden'),
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
						
					
				}
	});
	
}

///////////////////////////////////////////////////////////////////////////////
$("#tesoreria_cheques_manual_orden_db_btn_guardar").click(function() {
if(getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value!="")
{
	if($('#').jVal())
	{
		getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.cheques.manual_orden.php",
			data:dataForm('form_tesoreria_db_cheques_manual_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{alert(html);
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_cheques_manual_orden');
					jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value='1';
					getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value="0,00";
					getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="1";
					getObj('tr_benef_manual').style.display='none';
					getObj('tr_porve_manual').style.display='';
					getObj('tesoreria_cheque_manual_orden_pr_radio1').checked="checked";
	

				}
				else if (html=="NoRegistro")
				{
					//alert("La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras");
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CUENTA DEL USUARIO NO POSEE CHEQUERA REGISTRADA</p></div>",true,true);

					//setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_tesoreria_db_cheques_manual_orden');
					jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_cheque_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value='1';
					getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value="0,00";
					getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="1";
					getObj('tr_benef_manual').style.display='none';
					getObj('tr_porve_manual').style.display='';
					getObj('tesoreria_cheque_manual_orden_pr_radio1').checked="checked";
					
					}
				else if (html=="Error-orden")
				{
					//alert("");
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LAS ORDENES DE PAGO SELECCIONADAS YA FUERON CANCELADAS POR OTRO CHEQUE</p></div>",true,true);

					setBarraEstado("");
					clearForm('form_tesoreria_db_cheques_manual_orden');
					jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value='1';

					}	
				else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
					}		
					else
				{
					alert(html);
					setBarraEstado(html);
				}
			
			}
		});
	}
}else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE GUARDAR EL REGISTRO SIN ELEGIR ALGUNA ORDEN DE PAGO</p></div>",true,true);
	
});
$("#tesoreria_cheques_manual_orden_pr_calcular_impuesto").click(function() {
cambio_orden();

});
//////////////////////////// impresion sin vista previa
$("#tesoreria_cheques_manual_orden_db_btn_imprimir_automatico").click(function() {
a='';
if($('#').jVal())
	{
	 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos, Nota:de ser incorrectos debera anular el cheque impreso</p></div>", ["ACEPTAR","CANCELAR"], 

function(val)
 {
	if(val=="ACEPTAR")
	{
//confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
//if (confirmar)

$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques.manuales_orden.php",
			data:dataForm('form_tesoreria_db_cheques_manual_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{
					alert(html);

				if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='firma_inactiva')&&(html!='no_disponible_saldo')&&(html!='cerrado'))
				{
					recordset=html;
					recordset = recordset.split("*");
					url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_manuales.php¿id_banco="+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+"@ordenes="+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_manual_orden_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value+"@secuencia="+recordset[1]+"@opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value+"@proyecto="+recordset[3]+"@ejecutora="+recordset[2]+"@partida="+recordset[4]; 
					modificar_inactivo_orden();
					Boxy.ask("<iframe style='width:0px; height:0px; border:0px'  src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif />REALIZANDO IMPRESI&Oacute;N:porfavor presione el boton cerrar de esta ventana luego que haya culminado la impresi&oacute;n</p></div>", ["CERRAR"], 
					//
					function(val)
					 {
                		if(val=="CERRAR")
						{
							setTimeout("limpiar_manual_orden()",200);
						}   
					 }, {title:"SAI-OCHINA"});
//-------------	//document.getElementById('iframeOculto2').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value+"@secuencia="+recordset[1];  
//-------------	modificar_inactivo();
				//	setTimeout("limpiar_manual_orden()",2000);
					/*
					
						getObj('iframeOculto').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value+"@secuencia="+recordset[1];  
					modificar_inactivo();
					setTimeout("limpiar_manual_orden_impresion()",2000);
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
*/
					
				}
				
				if(html=='chequera_agotada' )
				{
				//setBarraEstado(mensaje[no_impresion],true,true);
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA, PARA EMITIR UN CHEQUE POR LA MISMA DEBE CREAR UNA CHEQUERA NUEVA</p></div>",true,true);

			//	alert('No se encuentran chqueras activas para esta cuenta, para emitir un cheque por la misma debe crear una chequera nueva');
					
				}else
				if(html=='no_disponible_saldo')
				{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACIÓN</p></div>",true,true);
				}
				else
				if(html=='Error_impresion' )
					{
						alert(html);
						setBarraEstado(html);
	 					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					}	
				else
				if(html=='firma_inactiva')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />FIRMA INACTIVA,Dirigase al modulo de firmas voucher y active alguna cuenta</p></div>",true,true);
				}else
				if (html=="cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}	
				/*else
				{
					alert(html);
					setbarraEstado(html);
				}*/
				
			}
		});
//-
	}	
	});//cerrando funcion val
}
});
////////////////////////////impresion con vista previa

$("#tesoreria_cheques_manual_orden_db_btn_imprimir").click(function() {
b=0;
if($('#').jVal())
	{
		Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos, Nota:de ser incorrectos debera anular el cheque impreso</p></div>", ["ACEPTAR","CANCELAR"], 
					function(val)
					 {
                		if(val=='ACEPTAR')
						{
			
//confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
//if (confirmar)
						$.ajax (
									{
										url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques.manuales_orden.php",
										data:dataForm('form_tesoreria_db_cheques_manual_orden'),
										type:'POST',
										cache: false,
										success: function(html)
										{
											//alert(html);
											//setBarraEstado(html);
											if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='no_disponible_saldo')&&(html!='firma_inactiva')&&(html!='cerrado'))
											{	
												
												recordset=html;
												recordset = recordset.split("*");
												//alert('Preparando vista de impresión');
							
												url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.php¿id_banco="+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+"@caducidad="+getObj('tesoreria_cheques_manual_orden_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value+"@secuencia="+recordset[1]+"@opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value+"@proyecto="+recordset[3]+"@ejecutora="+recordset[2]+"@partida="+recordset[4]; 
												
												modificar_inactivo_orden();
												openTab("cheques",url);
												//setBarraEstado(mensaje[impresion_cheque],true,true);
												limpiar_manual_orden();
											}
											
											if(html=='chequera_agotada' )
											{
											//setBarraEstado(mensaje[no_impresion],true,true);
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA, PARA EMITIR UN CHEQUE POR LA MISMA DEBE CREAR UNA CHEQUERA NUEVA</p></div>",true,true);
							
											//alert('No se encuentran chqueras activas para esta cuenta, para emitir un cheque por la misma debe crear una chequera nueva');
												
											}
											else
										if(html=='no_disponible_saldo')
										{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACIÓN</p></div>",true,true);
										}
											else
											if (html=="cerrado")
											{
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
											}	
										else
											if(html=='Error_impresion' )
												{
													alert(html);
													setBarraEstado(html);
													//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
												}	
							
											
										}
									});
							//-
							}	
						});
								}	
						
});
//---
function r()
{
	document.getElementById('iframeOculto').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_manual_orden_db_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value+"@secuencia="+recordset[1];  
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_manual_orden_db_btn_eliminar").click(function() {
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Desea elminar el registro seleccionado?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
	//if(confirm("¿Desea elminar el registro seleccionado?")) 
	//{	
	getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";

		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.eliminar.manual_orden.php",
			data:dataForm('form_tesoreria_db_cheques_manual_orden'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar_manual_orden();
				}
				else
				{
					//setBarraEstado(mensaje[relacion_existe],true,true);
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />No se puedo eliminar el precheque</p></div>",true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
})
});
//---
function limpiar_iframe()
{


	//document.getElementById('iframeOculto').value="";
	document.getElementById('iframeOculto').src="";
}
function limpiar_manual_orden(){
setBarraEstado("");

	getObj('tesoreria_cheques_manual_orden_pr_ret_islr').disabled='';
	getObj('tesoreria_cheques_manual_orden_db_nombre_banco').disabled="";
	getObj('tesoreria_cheques_manual_orden_pr_proveedor_codigo').disabled="";
	getObj('tesoreria_cheques_manual_orden_db_n_cuenta').disabled="";
	getObj('tesoreria_cheques_manual_orden_db_monto_pagar').disabled="";
	getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').disabled="disabled";
	getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='none';
	//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='';
	clearForm('form_tesoreria_db_cheques_manual_orden');
	getObj('tesoreria_cheques_manual_orden_pr_caducidad').value=3;
	getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value="1";
	document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="";
	document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_pr_endosable.checked="checked";
	jQuery("#list_orden_pago_manual").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
    getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value="0,00";
	getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disabled";
	getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value="0,00";
	getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="1";
	getObj('tr_benef_manual').style.display='none';
	getObj('tr_porve_manual').style.display='';
	getObj('tesoreria_cheque_manual_orden_pr_radio1').checked="checked";
	getObj('tesoreria_cheques_manual_pr_sustraendo').checked='';
	getObj('tesoreria_cheques_manual_pr_sustraendo').disabled='disabled';
	getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value='0';
	//setBarraEstado(mensaje[impresion_cheque],true,true);
	//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
	//setTimeout("limpiar_manual_orden_iframe()",5000);

}
function limpiar_impresion(){
setBarraEstado("");

	getObj('tesoreria_cheques_manual_orden_pr_ret_islr').disabled='';
	getObj('tesoreria_cheques_manual_orden_db_nombre_banco').disabled="";
	getObj('tesoreria_cheques_manual_orden_pr_proveedor_codigo').disabled="";
	getObj('tesoreria_cheques_manual_orden_db_n_cuenta').disabled="";
	getObj('tesoreria_cheques_manual_orden_db_monto_pagar').disabled="";
	getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').disabled="disabled";
	getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='none';
	//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='none';
	getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='';
	clearForm('form_tesoreria_db_cheques_manual_orden');
	getObj('tesoreria_cheques_manual_orden_pr_caducidad').value=3;
	getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value="1";
	document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="";
	document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_pr_endosable.checked="checked";
    getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value="0,00";
	getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disabled";
	getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value="0,00";
	//setBarraEstado(mensaje[impresion_cheque],true,true);
	getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="1";
	getObj('tr_benef_manual').style.display='none';
	getObj('tr_porve_manual').style.display='';
	getObj('tesoreria_cheque_manual_orden_pr_radio1').checked="checked";
	setTimeout("limpiar_iframe()",100);

}
$("#tesoreria_cheques_manual_orden_db_btn_cancelar").click(function() {
limpiar_manual_orden();
});	
//------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_manual_orden_db_btn_consultar_cuentas_chequeras").click(function() {
if((getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value!="")&&(getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display=='none'))
{
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value;

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
								width:500,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N Cuenta','Estatus'],
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
									getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
						//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value,page:1}).trigger("reloadGrid");
									jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");
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
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
});

//-----------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_manual_orden_db_btn_consultar_banco_chequeras").click(function() {
if(getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display=='none')
	{		
		
/*		var nd=new Date().getTime();
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
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo &Aacute;rea','Tel&eacute;fono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas'],
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
									getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value=ret.id;
									getObj('tesoreria_cheques_manual_orden_db_nombre_banco').value=ret.nombre;
									getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value=ret.cuentas;
								dialog.hideAndUnload();
								jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
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
		}				}
});
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_manual_orden_db_btn_consultar_proveedor").click(function() {
/*		var nd=new Date().getTime();
		getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_pr_proveedor_consulta_cheques_m").keypress(function(key)
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor;
							setBarraEstado(url);
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
								url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','C&oacute;digo','Proveedor','rif'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value = ret.id_proveedor;
									getObj('tesoreria_cheques_manual_orden_pr_proveedor_codigo').value = ret.codigo;
									getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value = ret.nombre;
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').value ="";
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value=rif2[0];
									if(getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value=='V')
									{
										getObj('tesoreria_cheques_manual_pr_sustraendo').disabled='';
									}
									dialog.hideAndUnload();
								//	url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value;
									//setBarraEstado(url);
									jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
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
//-------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheques_manual_orden_db_btn_consultar_precheque").click(function() {
/*if((getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value!="")||(getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value!=""))
{*/	
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		if(getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value=='1')
		{
			urls='modulos/tesoreria/cheques/pr/cmb.sql.precheque_manual_orden.php?nd='+nd+"&proveedor="+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value;
		}else
		if(getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value=='2')
		{
			urls='modulos/tesoreria/cheques/pr/cmb.sql.precheque_manual_orden.php?nd='+nd+"&beneficiario="+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value;
		}
							
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Pre-Cheque', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:750,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:urls,
								datatype: "json",
								colNames:['Id','N precheque','Id Banco','Banco','N Cuenta','id_proveedor','Base Imp','Concepto','porcentaje','ordenes','islr','sustraendo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'n_precheque',index:'n_precheque', width:100,sortable:false,resizable:false},
									{name:'id_banco',index:'id_banco', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_banco',index:'nombre_banco', width:160,sortable:false,resizable:false},
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false},
									{name:'id_proveedor',index:'id_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje',index:'porcentaje', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ordenes',index:'ordenes', width:100,sortable:false,resizable:false,hidden:true},
									{name:'islr',index:'islr', width:100,sortable:false,resizable:false,hidden:true},
									{name:'sustraendo',index:'sustraendo', width:100,sortable:false,resizable:false,hidden:true}

									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								sortname: 'n_precheque',
								sortorder: "asc",
								onSelectRow: 
								
								function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);

									getObj('tesoreria_cheques_manual_orden_db_n_precheque').value=ret.n_precheque;
									getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value=ret.id_banco;
									getObj('tesoreria_cheques_manual_orden_db_nombre_banco').value=ret.nombre_banco;
									getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value=ret.cuentas;
									getObj('tesoreria_cheques_manual_orden_db_concepto').value=ret.concepto;
									getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value=ret.islr;

									orden=ret.ordenes;
									orden1=orden.replace("{","");
									getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value=orden1.replace("}","");
								//	jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+ret.id_proveedor+'&ncuenta='+ret.cuentas+'&banco='+ret.id_banco+'&precheque='+ret.n_precheque+'&islr='+ret.islr,page:1}).trigger("reloadGrid");
									/*vector=getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value;
									vector2=vector.split(",");
									//alert(vector2);
									i=0;
									while(i<vector2.length)
									{
											jQuery("#list_orden_pago_manual").setSelection(1);

											//alert(vector2[i]);
											i=i+1;		
									}	
								*/
								//url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value;
								
								setTimeout(pasar_valores_orden,100);
							//	jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");
								if(ret.porcentaje!=0)
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="";
									}
									//valor=parseFloat(ret.monto);
								   	//valor = valor.currency(2,',','.');	

								   	getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=ret.monto;
									//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+ret.id_proveedor+'&ncuenta='+ret.cuentas+'&banco='+ret.id_banco+'&precheque='+ret.n_precheque+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");
									getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value=ret.sustraendo;
								if(getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value=='1')
								{
									getObj('tesoreria_cheques_manual_pr_sustraendo').checked='checked';
								}else
								if(getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value=='0')
								{
									getObj('tesoreria_cheques_manual_pr_sustraendo').checked='';
								}

							
									dialog.hideAndUnload();

					 			
							/*	getObj('tesoreria_cheques_db_nombre_banco').disabled="disabled";
								getObj('tesoreria_cheques_pr_proveedor_codigo').disabled="disabled";
								getObj('tesoreria_cheques_db_n_cuenta').disabled="disabled";
								getObj('tesoreria_cheques_db_monto_pagar').disabled="disabled";*/
								getObj('tesoreria_cheques_manual_orden_db_btn_cancelar').style.display='';
								getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='';
								getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='';
								//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='';
								getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='none';									
								getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='';
								//getObj('tesoreria_cheques_pr_ret_islr').disabled='disabled';

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
//}	
});
function pasar_valores_orden()
{
	//url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value;

	//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value,page:1}).trigger("reloadGrid");	

//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");
cambio_orden();
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_precheque_codigo()
{
	if (getObj('tesoreria_cheques_pr_proveedor_codigo').value!="")
    {  
			$.ajax({
					url:'modulos/tesoreria/cheques/pr/sql_grid_precheque.php',
					data:dataForm('form_tesoreria_db_cheque'),
					type:'POST',
					cache: false,
					 success:function(html)
					 {//alert(html);
					    if((html!="")||(html!=null)||(html!="undefined"))
						{		var recordset=html;
						 if(recordset)
								{
									recordset = recordset.split("*");
									getObj('tesoreria_cheques_db_n_precheque').value=recordset[1];
									getObj('tesoreria_cheques_db_banco_id_banco').value=recordset[2];
									getObj('tesoreria_cheques_db_nombre_banco').value=recordset[3];
									getObj('tesoreria_cheques_db_n_cuenta').value=recordset[4];
									getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value = recordset[5];
									getObj('tesoreria_cheques_pr_proveedor_codigo').value = recordset[6];
									getObj('tesoreria_cheques_pr_proveedor_nombre').value = recordset[7];
									getObj('tesoreria_cheques_db_concepto').value=recordset[9];
									getObj('tesoreria_cheques_pr_ret_islr').value=recordset[12];
									orden=recordset[11];
									orden1=orden.replace("{","");
									getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value=orden1.replace("}","");
									/*vector=getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value;
									vector2=vector.split(",");
									//alert(vector2);
									i=0;
									while(i<vector2.length)
									{
											jQuery("#list_orden_pago").setSelection(1);

											//alert(vector2[i]);
											i=i+1;		
									}	
											
								*/	
								setTimeout(pasar_valores_orden,100);
								if(recordset[10]!=0)
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_ordens_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_ordens_db_itf.checked="";
									}
									valor=parseFloat(recordset[8]);
								   	valor = valor.currency(2,',','.');	
								   	getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=valor;
								//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");
								/*	getObj('tesoreria_cheques_db_nombre_banco').disabled="disabled";
									getObj('tesoreria_cheques_pr_proveedor_codigo').disabled="disabled";
									getObj('tesoreria_cheques_db_n_cuenta').disabled="disabled";
									getObj('tesoreria_cheques_db_monto_pagar').disabled="disabled";*/
									getObj('tesoreria_cheques_manual_orden_db_btn_cancelar').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='';
									//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='none';	
									getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='';
									jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");

										/*if(recordset[10]=='Activo')
										{ 
											getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_chequeras_db_estatus').value="1";
										}else
										{
										getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
										getObj('tesoreria_chequeras_db_estatus').value="2";
										}		*/
									
									
								}
								 else
								 {
									//limpiar_manual_orden();
									getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value="";
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').value="";
									getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='none';
									//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='none';
									getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='none';
									getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').value="";
									getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value="";
									getObj('tesoreria_cheques_manual_orden_db_nombre_banco').value="";
									getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value="";
									getObj('tesoreria_cheques_manual_orden_db_concepto').value="";
									jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value,page:1}).trigger("reloadGrid");
									document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="";
									getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value="";
									getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value='0,00';

								}
						}	
					 }
				});	 	 
		}	
}//-------------------------------------------------------------------------------------------------------------------------------------
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
//
function consulta_automatica_proveedor_manual_orden()
{	
		
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_proveedor_codigo_orden.php",
			
            data:dataForm('form_tesoreria_db_cheques_manual_orden'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value=recordset[0];
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').value ="";
				rif=recordset[2];
				rif2 = rif.split("-");
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value=rif2[0];
				if(getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value=='V')
				{
					getObj('tesoreria_cheques_manual_pr_sustraendo').disabled='';
				}
				jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
					getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value = recordset[1];

	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value="";
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}

		 
//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
$("#list_orden_pago_manual").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",

	url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+new Date().getTime(),
	//+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value
	datatype: "json",
		colNames:['&ordm;Id','Orden.Pago','Fecha','SubTotal','Base Imp.','%IVA','%Ret.IVA','Total.IVA','%ISLR','Total.ISLR','Ret Extra','Total.Pagar'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'n_orden',index:'n_orden', width:35},
			{name:'fecha',index:'fecha', width:45},
			{name:'bruto',index:'bruto', width:50},
			{name:'base_imponible',index:'base_imponible', width:50},
			{name:'iva1',index:'iva1', width:50},
			{name:'ret_iva',index:'ret_iva', width:50},
			{name:'total_iva',index:'total_iva', width:50,hidden:true},
			{name:'islr1',index:'islr1', width:60},
			{name:'islr',index:'islr', width:60,hidden:true},
			{name:'ret',index:'ret1', width:50},
			{name:'monto',index:'monto', width:55,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_orden_manual'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	gridComplete:function(){
	vector=getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value;
							if(vector!="")
							{
								vector2=vector.split(",");
							
									
									i=0;//&&(getObj('tesoreria_cheques_db_n_precheque').value!="")
									if((vector2!=""))
									{										
											
											while((i<vector2.length))
											{
													
													jQuery("#list_orden_pago_manual").setSelection(vector2[i]);
													i=i+1;		
											}
									}			
									
							}	

},
	onSelectRow: function(id){
        var ret = jQuery("#list_orden_pago_manual").getRowData(id);
	   	s = jQuery("#list_orden_pago_manual").getGridParam('selarrrow');
		idd = ret.id;
		
		
		if(id && id!==lastsel){
			getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value=s;
			url="modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value+'&rif='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value+'&sustraendo='+getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value;	
		$.ajax({
			url:url,
			data:dataForm('form_tesoreria_db_cheques_manual_orden'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {/*url="modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value;
       			*///alert(html);

       		    var recordset=html;				
				valor=parseFloat(recordset);
				valor = valor.currency(2,',','.');	
			    getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=valor;
				if(getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=='0,00')
				{
						getObj('tesoreria_cheques_manual_pr_sustraendo').checked='';
	        			getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value='0';	
				}
			}
			});	 
				
		}
	 		
	},
   
onSelectAll:function(id){
          var ret = jQuery("#list_orden_pago_manual").getRowData(id);
	   	s = jQuery("#list_orden_pago_manual").getGridParam('selarrrow');
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value=s;
			url="modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value;
		$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,
		    data:dataForm('form_tesoreria_db_cheques_manual_orden'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {/*url="modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value;
       			alert(url);*/

       		    var recordset=html;				
				valor=parseFloat(recordset);
				valor = valor.currency(2,',','.');	
			    getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=valor;
			}
			});	 
	}
}

/*function() {	
	   	var s
		s = jQuery("#list_orden_pago_manual").getGridParam('selarrrow');
		alert(s);
		}*/

}).navGrid("#pager_cotizaciones",{search :false,edit:false,add:false,del:false});
		
//--------------------------------------------------------------------------------------------------------------------------------------
//consultas automaticas
function consulta_automatica_proveedor()
{
	getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_proveedor_codigo.php",
            data:dataForm('form_tesoreria_db_cheques_manual_orden'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				//alert(html);
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value = recordset[1];
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value=recordset[0];
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').value ="";
				rif=recordset[2];
				rif2 = rif.split("-");
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value=rif2[0];
	            jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+recordset[0]+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&retislr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value="";
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}
function cambio_orden()
{

if(getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value=='1')
{
jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+'&rif='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value+'&sustraendo='+getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value,page:1}).trigger("reloadGrid");	
url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+'&rif='+getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value+'&sustraendo='+getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value;

}
if(getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value=='2')
{
jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+'&rif='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&sustraendo='+getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value,page:1}).trigger("reloadGrid");	
url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_manual_orden_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value+'&rif='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&sustraendo='+getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value;
}
//setBarraEstado(url);
}	
function cargar_iframe()
{
	document.getElementById('iframeOculto').src="";
}	
$('#tesoreria_cheques_manual_orden_pr_proveedor_codigo').change(consulta_automatica_proveedor);
//$('#tesoreria_cheques_pr_ret_islr').blur(cambio_orden);
$('#tesoreria_cheques_manual_orden_pr_ret_islr').change(cambio_orden);
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#tesoreria_cheques_manual_orden_db_n_precheque').numeric({allow:'-'});
$('#tesoreria_cheques_manual_orden_db_monto').numeric({allow:',.'});
$('#tesoreria_cheques_manual_orden_db_rif').numeric({allow:'-'});
$('#tesoreria_cheques_manual_orden_pr_proveedor_codigo').numeric({});
$('#tesoreria_cheques_manual_orden_pr_ret_islr').numeric({});

$('#tesoreria_cheques_manual_orden_db_ncheque_codigo').numeric({});
$('#tesoreria_cheques_manual_orden_db_n_cuenta').numeric({});
$('#tesoreria_cheques_manual_orden_pr_proveedor_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#tesoreria_cheques_manual_orden_db_nombre_banco').alpha({allow:' áéíóúÄÉÍÓÚ'});
//////////////////////////////////////////////////////////////////////////////////
$("#tesoreria_cheque_manual_orden_db_btn_consultar_beneficiario").click(function() {

	/*	var nd=new Date().getTime();
	
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
/////////////////////
$("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc2_dosearch2();
					});

/////////////////////////////////////////
function consulta_doc2_dosearch2()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc2_gridReload2,500)
										}
////////////////////////////////////////										
						function consulta_doc2_gridReload()
						{
							var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor;
						}
///////////////////////////////////////////
////////////////////////////////////////										
						function consulta_doc2_gridReload2()
						{
							var busq_codigo= jQuery("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?busq_codigo="+busq_codigo;
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
								url:'modulos/tesoreria/cheques/pr/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Beneficiario'],
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
									getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
									getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value = ret.rif;
									getObj('tesoreria_cheque_manual_orden_pr_empleado_nombre').value = ret.beneficiario;
										getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
									jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php?nd='+nd+'&beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
									url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php?nd='+nd+'&beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value;
									//setBarraEstado(url);
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
function consulta_automatica_benef_manual_orden()
{

//	getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_beneficiario_codigo_manual.php",
            data:dataForm('form_tesoreria_db_cheques_manual_orden'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				//	alert(html);
				if(recordset)
				{
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="";
				recordset = recordset.split("*");
				getObj('tesoreria_cheque_manual_orden_pr_empleado_nombre').value=recordset[1];
					jQuery("#list_orden_pago_manual").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php?beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value,page:1}).trigger("reloadGrid");
					url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago_manual.php?beneficiario='+getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value+'&ncuenta='+getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value+'&islr='+getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value;
					//setBarraEstado(url);
											
			     }
				else
			 {  
			   	getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value="";
				getObj('tesoreria_cheque_manual_orden_pr_empleado_nombre').value="";
				getObj('tesoreria_cheques_manual_orden_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}
//
function consulta_automatica_precheque_codigo_orden()
{
	if((getObj('tesoreria_cheques_manual_orden_pr_proveedor_codigo').value!="")||(getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value!=""))
    {  
			$.ajax({
					url:'modulos/tesoreria/cheques/pr/sql_grid_precheque_manual_orden.php',
					data:dataForm('form_tesoreria_db_cheques_manual_orden'),
					type:'POST',
					cache: false,
					 success:function(html)
					 {
					    if((html!="")||(html!=null)||(html!="undefined"))
						{	//setBarraEstado(html);	
						 var recordset=html;
						 if(recordset)
								{
									recordset = recordset.split("*");
									//getObj('tesoreria_cheques_manual_orden_db_n_precheque').value=recordset[1];
									getObj('tesoreria_cheques_manual_orden_db_banco_id_banco').value=recordset[2];
									getObj('tesoreria_cheques_manual_orden_db_nombre_banco').value=recordset[3];
									getObj('tesoreria_cheques_manual_orden_db_n_cuenta').value=recordset[4];
									getObj('tesoreria_cheques_manual_orden_db_concepto').value=recordset[8];
									getObj('tesoreria_cheques_manual_orden_pr_ret_islr').value=recordset[11];
									orden=recordset[10];
									orden1=orden.replace("{","");
									getObj('tesoreria_cheques_manual_orden_db_ordenes_pago').value=orden1.replace("}","");
									/*vector=getObj('tesoreria_cheques_db_ordenes_pago').value;
									vector2=vector.split(",");
									//alert(vector2);
									i=0;
									while(i<vector2.length)
									{
											jQuery("#list_orden_pago").setSelection(1);

											//alert(vector2[i]);
											i=i+1;		
									}	
											
								*/	
								setTimeout(pasar_valores_orden,100);
								if(recordset[10]!=0)
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheques_manual_orden.tesoreria_cheques_manual_orden_db_itf.checked="";
									}
									valor=parseFloat(recordset[9]);
								   	valor = valor.currency(2,',','.');	
								   	getObj('tesoreria_cheques_manual_orden_db_monto_pagar').value=valor;
									getObj('tesoreria_cheques_manual_orden_db_btn_cancelar').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_actualizar').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_imprimir').style.display='';
									//getObj('tesoreria_cheques_manual_orden_db_btn_imprimir_automatico').style.display='';
									getObj('tesoreria_cheques_manual_orden_db_btn_guardar').style.display='none';	
									getObj('tesoreria_cheques_manual_orden_db_btn_eliminar').style.display='';
								//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_cheques_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheques_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheques_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_cheques_db_n_precheque').value+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value,page:1}).trigger("reloadGrid");

										/*if(recordset[10]=='Activo')
										{ 
											getObj('tesoreria_chequeras_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_chequeras_db_estatus').value="1";
										}else
										{
										getObj('tesoreria_chequeras_db_estatus_opt_inact').checked="checked";
										getObj('tesoreria_chequeras_db_estatus').value="2";
										}		*/
									
									
								}
								 else
								 {
									limpiar_manual_orden();
									

								}
						}	
					 }
				});	 	 
		}	
}
//----------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_manual_orden_pr_radio1").click(function(){
		getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="1";
		getObj('tesoreria_cheque_manual_orden_pr_empleado_codigo').value="";
		getObj('tesoreria_cheque_manual_orden_pr_empleado_nombre').value="";
		
	});
$("#tesoreria_cheque_manual_orden_pr_radio2").click(function(){
		getObj('tesoreria_cheque_manual_pr_op_oculto_orden').value="2";
		getObj('tesoreria_cheques_manual_orden_pr_proveedor_codigo').value="";
		getObj('tesoreria_cheques_manual_orden_pr_proveedor_nombre').value="";
		getObj('tesoreria_cheques_manual_orden_pr_proveedor_id').value="";
		getObj('tesoreria_cheques_manual_orden_pr_proveedor_rif').value="";

	});
	

//$('#tesoreria_cheques_db_concepto').alpha({allow:' áéíóúÄÉÍÓÚáéíóúÁÉÍÓÚ0123456789,.-'});
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
$("#tesoreria_cheques_pr_endosable").click(function(){
		if(getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value=="0")
			getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value="1"
		else
		if(getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value=="1")
			getObj('tesoreria_cheques_manual_orden_db_endosable_oculto').value="0"
		
	});
	$("#tesoreria_cheques_manual_pr_sustraendo").click(function(){
		if(getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value=="0")
			getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value="1"
		else
		if(getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value=="1")
			getObj('tesoreria_cheques_manual_pr_sustraendo_oculto').value="0"
		
	});	
$('#tesoreria_cheques_manual_orden_db_n_precheque').change(consulta_automatica_precheque_codigo_orden);
$('#tesoreria_cheques_manual_orden_pr_proveedor_codigo').change(consulta_automatica_proveedor_manual_orden);
$('#tesoreria_cheque_manual_orden_pr_empleado_codigo').change(consulta_automatica_benef_manual_orden);
	
	
</script>

   <div id="botonera"><img id="tesoreria_cheques_manual_orden_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="tesoreria_cheques_manual_orden_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="tesoreria_cheques_manual_orden_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_cheques_manual_orden_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="tesoreria_cheques_manual_orden_db_btn_imprimir_automatico"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />
	<img id="tesoreria_cheques_manual_orden_db_btn_imprimir"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" /></div>
<form method="post" id="form_tesoreria_db_cheques_manual_orden" name="form_tesoreria_db_cheques_manual_orden" onload="cargar_iframe()">
<input type="hidden"  id="tesoreria_vista_cheques_manual_orden" name="tesoreria_vista_cheques_manual_orden"/>
<input type="hidden" name="orden_pago_pr_cot_select" id="orden_pago_pr_cot_select"  />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Cargar Cheque Manual (orden)</th>
	</tr>
	
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_cheques_manual_orden_db_nombre_banco" type="text" id="tesoreria_cheques_manual_orden_db_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_cheques_manual_orden_db_banco_id_banco" name="tesoreria_cheques_manual_orden_db_banco_id_banco"/>
		</li>
		<li id="tesoreria_cheques_manual_orden_db_btn_consultar_banco_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_cheques_manual_orden_db_n_cuenta" type="text" id="tesoreria_cheques_manual_orden_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. " readonly=""
				jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>

		</li>
		<li id="tesoreria_cheques_manual_orden_db_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	<tr>
		
	</tr>
	<tr>
	<th>Beneficiario:</th>
	<td><label>
	    <input type="checkbox" name="tesoreria_cheque_manual_ord_db_otro_beneficiario" id="tesoreria_cheque_manual_ord_db_otro_beneficiario" value="checkbox" onclick="nombre_beneficiario2();" />
		<input type="text" id="tesoreria_cheque_manual_ord_db_otro_beneficiario_oc" name="tesoreria_cheque_manual_ord_db_otro_beneficiario_oc"  value="0" />
	 <b> Incluir Beneficiario</b> </label></td>
	</tr>
	<tr style="display:none">
	 <th>Beneficiario</th>
	  <td><label>
	  <input name="tesoreria_cheque_manual_orden_pr_radio" type="radio" id="tesoreria_cheque_manual_orden_pr_radio1" onclick="getObj('tr_benef_manual').style.display='none'; getObj('tr_porve_manual').style.display='';" value="1" checked="CHECKED"/>
	  </label>
	 
	    Prooveedor</label>
	    &nbsp;&nbsp;
	    <label>
          <input name="tesoreria_cheque_manual_orden_pr_radio" type="radio" id="tesoreria_cheque_manual_orden_pr_radio2"  onclick="getObj('tr_benef_manual').style.display=''; getObj('tr_porve_manual').style.display='none';" value="0" />
	    </label>
	
	  Otro Beneficiario</label></br>
      <input type="hidden" name="tesoreria_cheque_manual_pr_op_oculto_orden" id="tesoreria_cheque_manual_pr_op_oculto_orden" value="1" /></td>
   </tr>
   <tr id="tr_porve_manual">
		<th>Proveedor:</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="tesoreria_cheques_manual_orden_pr_proveedor_codigo" type="text" id="tesoreria_cheques_manual_orden_pr_proveedor_codigo"  maxlength="4"
				onchange="consulta_automatica_proveedor_manual_orden" onclick="consulta_automatica_proveedor_manual_orden"
				message="Introduzca un Codigo para el proveedor."  size="5"
				jVal="{valid:/^[0123456789]{1,5}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
				
	
				<input type="text" name="tesoreria_cheques_manual_orden_pr_proveedor_nombre" id="tesoreria_cheques_manual_orden_pr_proveedor_nombre" size="45"
				message="Introduzca el nombre del Proveedor." readonly />
				<input type="hidden" name="tesoreria_cheques_manual_orden_pr_proveedor_id" id="tesoreria_cheques_manual_orden_pr_proveedor_id" readonly />
				<input type="hidden" name="tesoreria_cheques_manual_orden_pr_proveedor_rif" id="tesoreria_cheques_manual_orden_pr_proveedor_rif" readonly />
				</li> 
					<li id="tesoreria_cheques_manual_orden_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>				</td>		
	</tr>
	<tr  id="tr_benef_manual" style="display:none">
			<th>Empleado</th>
      <td >		<ul class="input_con_emergente">
	  <li><input name="tesoreria_cheque_manual_orden_pr_empleado_codigo" type="text" id="tesoreria_cheque_manual_orden_pr_empleado_codigo"
				onblur="consulta_automatica_benef_manual_orden"   size="5"  maxlength="4" 
				message="Introduzca un Codigo para el Empleado."
				/>
	    <input name="tesoreria_cheque_manual_orden_pr_empleado_nombre" type="text" id="tesoreria_cheque_manual_orden_pr_empleado_nombre" size="45" maxlength="45"
				message="Introduzca el nombre del Empleado." />
		  <label>
		    <input type="hidden" name="textprue" id="textprue" />
		    </label>
	      <input type="hidden" name="textprue2" id="textprue2" />
	      <input type="hidden" name="textprue3" id="textprue3" />
	  </li> 
	  		<li id="tesoreria_cheque_manual_orden_db_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>
	</tr>
	<tr id="tr_empleado32" style="display:none">
		<th >Beneficiario:</th>
		<td><input type="text" name="tesoreria_manual_nombre_ord" id="tesoreria_manual_nombre_ord" size="70" maxlength="70" /></td>
	</tr>
	<tr>
	<tr>
		 <th>N&ordm; Pre-cheque:</th> 
		 
		  <td>
		  		<ul class="input_con_emergente">
				<li>
				  <input type="text" name="tesoreria_cheques_manual_orden_db_n_precheque" id="tesoreria_cheques_manual_orden_db_n_precheque"   size="6"    maxlength="6"  disabled="disabled"  onblur="consulta_automatica_precheque_codigo_orden"
				  />
	
				</li> 
					<li id="tesoreria_cheques_manual_orden_db_btn_consultar_precheque" class="btn_consulta_emergente"></li>
	  </ul>	  </td>				 
   </tr>
   <tr>
		<th>ITF</th>
	  <td><label>
	    <input type="checkbox" name="tesoreria_cheques_manual_orden_db_itf" value="checkbox" />
		<input type="hidden" id="tesoreria_cheques_manual_orden_db_itf_estatus" name="tesoreria_cheques_manual_orden_db_itf_estatus"  value="0" />
	 <b> Incluir Impuesto a las transacciones financieras</b> </label></td>
	</tr>
	<tr>
		<th>Concepto:</th>
	    <td>
	<textarea name="tesoreria_cheques_manual_orden_db_concepto" cols="65" rows="2"  id="tesoreria_cheques_manual_orden_db_concepto" 
				message="Introduzca el concepto del cheque"
				></textarea>	</tr>
	<tr>	<th colspan="3">&nbsp;&nbsp;Cheque No endosable 
		<input name="tesoreria_cheques_manual_orden_pr_endosable" id="tesoreria_cheques_manual_orden_pr_endosable" type="checkbox" value="" checked="checked" />
		
		<input type="hidden" name="tesoreria_cheques_manual_orden_db_endosable_oculto" id="tesoreria_cheques_manual_orden_db_endosable_oculto"  value="1"/>
		<label>&nbsp;&nbsp;&nbsp;&nbsp;caduca a:
		<select name="tesoreria_cheques_manual_orden_pr_caducidad" id="tesoreria_cheques_manual_orden_pr_caducidad">
		  <option value="0">No incluir</option>
		  <option value="1">15 DIAS</option>
		  <option value="2">60 DIAS</option>
		  <option value="3" selected="selected" >90 DIAS</option>
		  <option value="4">120 DIAS</option>
        </select>
		</label></th>
    </tr>
	<tr>
		<th>
			Monto a Pagar		</th>
		<td>
		<input align="right"  name="tesoreria_cheques_manual_orden_db_monto_pagar" type="text" id="tesoreria_cheques_manual_orden_db_monto_pagar"  readonly value="0,00" size="16" maxlength="16"  style="text-align:right" />
		<input  name="tesoreria_cheques_manual_orden_db_ordenes_pago" type="hidden" id="tesoreria_cheques_manual_orden_db_ordenes_pago" size="16" maxlength="16" />		</td>
	</tr>
	<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_orden_pago_manual" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_orden_manual" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
    </tr>
	<th>% Ret Islr</th>
	  <td>
			<input name="tesoreria_cheques_manual_orden_pr_ret_islr" type="text"  id="tesoreria_cheques_manual_orden_pr_ret_islr"  onblur=""   size="16" maxlength="10" value="0,00"  style="text-align:right" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)"
			jVal="{valid:/^[0123456789 .,]{1,10}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789 .,]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
				
			%	
<img class="btn_impuesto" name="tesoreria_cheques_manual_orden_pr_calcular_impuesto" id="tesoreria_cheques_manual_orden_pr_calcular_impuesto"  src="imagenes/null.gif"/>


	 Aplicar Impuesto 
	<input name="tesoreria_cheques_manual_pr_sustraendo" id="tesoreria_cheques_manual_pr_sustraendo" type="checkbox" value=""  disabled="disabled"/>
	Aplicar Sustraendo 
	<input type="hidden" name="tesoreria_cheques_manual_pr_sustraendo_oculto" id="tesoreria_cheques_manual_pr_sustraendo_oculto"  value="0"/>
	<input type="hidden" name="tesoreria_cheques_manual_pr_sustraendo_oculto2" id="tesoreria_cheques_manual_pr_sustraendo_oculto2"  value="0"/>	
</td>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table>
  <input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>
<iframe id="iframeOculto" name="iframeOculto"  src=" " style="width:0px; height:0px; border:0px"></iframe>
 <!-- <p>style="width:0px; height:0px; border:0px"</p>--> 
   