<?php
session_start();

?>
<script type='text/javascript'>
$("#tesoreria_pago_db_btn_actualizar").click(function() {
if(getObj('tesoreria_pago_db_ordenes_pago').value!="")
{
	getObj('tesoreria_pago_db_n_precheque').disabled="";
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_cheque'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					limpiar();
					getObj('tesoreria_pago_db_btn_eliminar').style.display='none';
					getObj('tesoreria_pago_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_pago_db_btn_actualizar').style.display='none';
					getObj('tesoreria_pago_db_btn_guardar').style.display='';
					getObj('tesoreria_pago_db_btn_imprimir').style.display='none';
					getObj('tesoreria_pago_db_btn_imprimir_automatico').style.display='none';
					getObj('tesoreria_pago_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACION</p></div>",true,true);
					/*getObj('tesoreria_pago_db_btn_eliminar').style.display='none';
					limpiar();
					getObj('tesoreria_pago_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_pago_db_btn_actualizar').style.display='none';
					getObj('tesoreria_pago_db_btn_guardar').style.display='';
					getObj('tesoreria_pago_db_btn_cancelar').style.display='';
					getObj('tesoreria_pago_db_endosable_oculto').value=1;
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
		*/	}
				else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M”DULO CERRADO</p></div>",true,true);
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
function restar_cuenta() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.restar_cuenta.php",
				data:dataForm('form_tesoreria_db_cheque'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					//setBarraEstado(html);
					recordset=html;
				
					if (recordset=="no_disponible_saldo")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />IMPOSIBLE REALIZAR OPERACION POR FALTA DE FONDOS/CUENTA</p></div>",true,true);
		 			
						
					}
					else if (recordset=="disponible_saldo_cero")
					{					

							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CUENTA ACTUAL PARA ESTE BANCO YA NO CUENTA CON FONDOS SEGUN EL SISTEMA DIRIJASE AL MODULO DE MOVIMIENTOS BANCARIOS Y REGISTRE LOS MOVIMIENTOS PERTINENTES</p></div>",true,true);

					}
					return(html);
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
function modificar_inactivo() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.modificar_inactivo.php",
				data:dataForm('form_tesoreria_db_cheque'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					//alert(html);
					recordset=html;
					recordset = recordset.split("*");
					if (recordset[0]=="inactiva")
					{
					 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	LA CHEQUERA ACTUAL SE AGOT&Oacute; DESEA ACTIVAR LA SIGUIENTE N&Uacute;MERO : "+recordset[1]+"</p></div>", ["ACEPTAR","CANCELAR"], 
					function(val)
					 {
                		if(val=='ACEPTAR')
						{
							activo_chequera();
						}   
					 }, {title:"SAI-OCHINA"});
						
/*---confirmar=confirm("La chequera actual se agotÛ desea activar la siguiente chequera numero : "+recordset[1]);
		if (confirmar)
		{
		 activo_chequera();
		}
*/						//setTimeout("limpiar()",2000);
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					}
					else if (recordset[0]=="inactiva2")
					{					

							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CHEQUERA ACTUAL SE AGOT&Oacute; NO HAY MAS CHEQUERAS CARGADA PARA ESTE BANCO</p></div>",true,true);

					}
					/*else
					{
						//alert("no limpio");
    					//setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />PRUEBA</p></div>",true,true);
						//setTimeout("limpiar()",200);
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");

					}	*/
					
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////////
function activo_chequera() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.activo_chequera.php",
				data:dataForm('form_tesoreria_db_cheque'),
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
$("#tesoreria_pago_db_btn_guardar").click(function() {
if(getObj('tesoreria_pago_db_ordenes_pago').value!="")
{
	if($('#form_tesoreria_db_cheque').jVal())
	{
		getObj('tesoreria_pago_db_n_precheque').disabled="";
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.cheques.php",
			data:dataForm('form_tesoreria_db_cheque'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_cheque');
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_pago_db_monto_pagar').value="0,00";
					getObj('tesoreria_pago_db_endosable_oculto').value='1';
					getObj('tesoreria_pago_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_pago_pr_ret_islr').value="0,00";


				}
				else if (html=="NoRegistro")
				{
					//alert("La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras");
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CUENTA DEL USUARIO NO POSEE CHEQUERA REGISTRADA</p></div>",true,true);

					//setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_tesoreria_db_cheque');
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_pago_db_monto_pagar').value="0,00";
					getObj('tesoreria_pago_db_endosable_oculto').value='1';
					getObj('tesoreria_pago_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_pago_pr_ret_islr').value="0,00";
					}
				else if (html=="Error-orden")
				{
					//alert("");
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LAS ORDENES DE PAGO SELECCIONADAS YA FUERON CANCELADAS POR OTRO CHEQUE</p></div>",true,true);

					setBarraEstado("");
					clearForm('form_tesoreria_db_cheque');
					jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
					getObj('tesoreria_pago_db_monto_pagar').value="0,00";
					getObj('tesoreria_pago_db_endosable_oculto').value='1';

					}	
				else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M”DULO CERRADO</p></div>",true,true);
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
$("#tesoreria_pago_pr_calcular_impuesto").click(function() {

cambio();

});
//////////////////////////// impresion sin vista previa
$("#tesoreria_pago_db_btn_imprimir_automatico").click(function() {
a='';

if($('#form_tesoreria_db_cheque').jVal())
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
			url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques.php",
			data:dataForm('form_tesoreria_db_cheque'),
			type:'POST',
			cache: false,
			success: function(html)
			{
								///alert(html);

				if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='firma_inactiva')&&(html!='no_disponible_saldo')&&(html!="cerrado"))
				{
					setBarraEstado(html);
		
					recordset=html;
					recordset = recordset.split("*");
					url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.phpøid_banco="+getObj('tesoreria_pago_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_pago_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_pago_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_pago_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_pago_pr_caducidad').value+"@endosable="+getObj('tesoreria_pago_db_endosable_oculto').value+"@secuencia="+recordset[1]+"@proyecto="+recordset[3]+"@ejecutora="+recordset[2]+"@partida="+recordset[4]; 
					//setBarraEstado(url);
					modificar_inactivo();
					
					Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+url+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif />REALIZANDO IMPRESI&Oacute;N:porfavor presione el boton cerrar de esta ventana luego que haya culminado la impresi&oacute;n</p></div>", ["CERRAR"], 
					function(val)//
					 {
                		if(val=="CERRAR")
						{
							setTimeout("limpiar()",200);
						}   
					 }, {title:"SAI-OCHINA"});
			}
				
				if(html=='chequera_agotada' )
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA, PARA EMITIR UN CHEQUE POR LA MISMA DEBE CREAR UNA CHEQUERA NUEVA</p></div>",true,true);
				}else
				if(html=='no_disponible_saldo')
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACI”N</p></div>",true,true);
				}
				else
				if(html=='Error_impresion' )
					{
						alert(html);
						setBarraEstado(html);
					}	
				else
				if(html=='firma_inactiva')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />FIRMA INACTIVA,Dirigase al modulo de firmas voucher y active alguna cuenta</p></div>",true,true);
				}
					else
				if (html=="cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}	
				
			}
		});
//-
	}	
	});//cerrando funcion val
}
});
////////////////////////////impresion con vista previa

$("#tesoreria_pago_db_btn_imprimir").click(function() {
b=0;
if($('#form_tesoreria_db_cheque').jVal())
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
										url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques.php",
										data:dataForm('form_tesoreria_db_cheque'),
										type:'POST',
										cache: false,
										success: function(html)
										
										{
											if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='firma_inactiva')&&(html!='no_disponible_saldo')&&(html!="cerrado"))
											{	recordset=html;
												recordset = recordset.split("*");
												//alert('Preparando vista de impresiÛn');
							
												url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque.phpøid_banco="+getObj('tesoreria_pago_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_pago_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_pago_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_pago_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_pago_pr_caducidad').value+"@endosable="+getObj('tesoreria_pago_db_endosable_oculto').value+"@secuencia="+recordset[1]+"@proyecto="+recordset[3]+"@ejecutora="+recordset[2]+"@partida="+recordset[4]; 
												modificar_inactivo();
												openTab("cheques",url);
										/*	
											url2="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst_partida_cheques.phpøordenes="+getObj('tesoreria_pago_db_ordenes_pago').value+"@ncheque="+recordset[0]; 
											openTab("resumen",url2);
									*/		
											limpiar();
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
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACI”N</p></div>",true,true);
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
	document.getElementById('iframeOculto').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.phpøid_banco="+getObj('tesoreria_pago_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_pago_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_pago_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_pago_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_pago_pr_caducidad').value+"@endosable="+getObj('tesoreria_pago_db_endosable_oculto').value+"@secuencia="+recordset[1];  
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_pago_db_btn_eliminar").click(function() {
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />øDesea elminar el registro seleccionado?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
	//if(confirm("øDesea elminar el registro seleccionado?")) 
	//{	
	getObj('tesoreria_pago_db_n_precheque').disabled="";

		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.eliminar.php",
			data:dataForm('form_tesoreria_db_cheque'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar();
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
function limpiar(){
setBarraEstado("");

	getObj('tesoreria_pago_pr_ret_islr').disabled='';
	getObj('tesoreria_pago_db_nombre_banco').disabled="";
	getObj('tesoreria_pago_pr_proveedor_codigo').disabled="";
	getObj('tesoreria_pago_db_n_cuenta').disabled="";
	getObj('tesoreria_pago_db_monto_pagar').disabled="";
	getObj('tesoreria_pago_pr_proveedor_nombre').disabled="disabled";
	getObj('tesoreria_pago_db_btn_eliminar').style.display='none';
	getObj('tesoreria_pago_db_btn_imprimir').style.display='none';
	getObj('tesoreria_pago_db_btn_imprimir_automatico').style.display='none';
	getObj('tesoreria_pago_db_btn_actualizar').style.display='none';
	getObj('tesoreria_pago_db_btn_guardar').style.display='';
	clearForm('form_tesoreria_db_cheque');
	getObj('tesoreria_pago_pr_caducidad').value=3;
	getObj('tesoreria_pago_db_endosable_oculto').value="1";
	document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="";
	document.form_tesoreria_db_cheque.tesoreria_pago_pr_endosable.checked="checked";
	jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
    getObj('tesoreria_pago_db_monto_pagar').value="0,00";
	getObj('tesoreria_pago_db_n_precheque').disabled="disabled";
	getObj('tesoreria_pago_pr_ret_islr').value="0,00";
	getObj('tesoreria_pago_pr_sustraendo').checked='';
	getObj('tesoreria_pago_pr_sustraendo').disabled='disabled';
	getObj('tesoreria_pago_pr_sustraendo_oculto').value='0';
	//setBarraEstado(mensaje[impresion_cheque],true,true);
	//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
	//setTimeout("limpiar_iframe()",5000);

}
function limpiar_impresion(){
setBarraEstado("");

	getObj('tesoreria_pago_pr_ret_islr').disabled='';
	getObj('tesoreria_pago_db_nombre_banco').disabled="";
	getObj('tesoreria_pago_pr_proveedor_codigo').disabled="";
	getObj('tesoreria_pago_db_n_cuenta').disabled="";
	getObj('tesoreria_pago_db_monto_pagar').disabled="";
	getObj('tesoreria_pago_pr_proveedor_nombre').disabled="disabled";
	getObj('tesoreria_pago_db_btn_eliminar').style.display='none';
	getObj('tesoreria_pago_db_btn_imprimir').style.display='none';
	getObj('tesoreria_pago_db_btn_imprimir_automatico').style.display='none';
	getObj('tesoreria_pago_db_btn_actualizar').style.display='none';
	getObj('tesoreria_pago_db_btn_guardar').style.display='';
	clearForm('form_tesoreria_db_cheque');
	getObj('tesoreria_pago_pr_caducidad').value=3;
	getObj('tesoreria_pago_db_endosable_oculto').value="1";
	document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="";
	document.form_tesoreria_db_cheque.tesoreria_pago_pr_endosable.checked="checked";
    getObj('tesoreria_pago_db_monto_pagar').value="0,00";
	getObj('tesoreria_pago_db_n_precheque').disabled="disabled";
	getObj('tesoreria_pago_pr_ret_islr').value="0,00";
	//setBarraEstado(mensaje[impresion_cheque],true,true);
	setTimeout("limpiar_iframe()",100);

}
$("#tesoreria_pago_db_btn_cancelar").click(function() {
limpiar();
});	
//------------------------------------------------------------------------------------------------------------------
$("#tesoreria_pago_db_btn_consultar_cuentas_chequeras").click(function() {
if((getObj('tesoreria_pago_db_banco_id_banco').value!="")&&(getObj('tesoreria_pago_db_btn_actualizar').style.display=='none'))
{
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value;
//alert(urls);
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
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
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
									getObj('tesoreria_pago_db_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
						//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value,page:1}).trigger("reloadGrid");
									jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value,page:1}).trigger("reloadGrid");
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
$("#tesoreria_pago_db_btn_consultar_banco_chequeras").click(function() {
if(getObj('tesoreria_pago_db_btn_actualizar').style.display=='none')
	{		
		
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
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_pago_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_pago_busqueda_bancos").keypress(function(key)
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
							var busq_banco= jQuery("#tesoreria_pago_busqueda_bancos").val(); 
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
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/cheques/pr/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo ¡rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas'],
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
									getObj('tesoreria_pago_db_banco_id_banco').value=ret.id;
									getObj('tesoreria_pago_db_nombre_banco').value=ret.nombre;
									getObj('tesoreria_pago_db_n_cuenta').value=ret.cuentas;
								dialog.hideAndUnload();
								jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&islr='+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value,page:1}).trigger("reloadGrid");
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
$("#tesoreria_pago_db_btn_consultar_proveedor").click(function() {

/*		var nd=new Date().getTime();
		getObj('tesoreria_pago_db_n_precheque').disabled="";
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
				$("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch2();
				});	
				function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
					}
				function consulta_doc_dosearch2()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload2,500)
					}	
						function consulta_doc_gridReload()
						{
							var busq_proveedor= jQuery("#tesoreria_pr_proveedor_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor;
							//alert(url);
						}
					function consulta_doc_gridReload2()
						{
							var busq_codigo= jQuery("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_codigo="+busq_codigo;
							//alert(url);
						}		
			}
		});						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Codigo','Proveedor','rif'],
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
									getObj('tesoreria_pago_pr_proveedor_id').value = ret.id_proveedor;
									getObj('tesoreria_pago_pr_proveedor_codigo').value = ret.codigo;
									getObj('tesoreria_pago_pr_proveedor_nombre').value = ret.nombre;
									getObj('tesoreria_pago_db_n_precheque').value ="";
									getObj('tesoreria_pago_db_n_precheque').disabled="";
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('tesoreria_pago_pr_proveedor_rif').value=rif2[0];
									if(getObj('tesoreria_pago_pr_proveedor_rif').value=='V')
									{
										getObj('tesoreria_pago_pr_sustraendo').disabled='';
									}
									dialog.hideAndUnload();
								//	url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value;
									//setBarraEstado(url);
									jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value,page:1}).trigger("reloadGrid");
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
$("#tesoreria_pago_db_btn_consultar_precheque").click(function() {
if(getObj('tesoreria_pago_pr_proveedor_id').value!="")
{	
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
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
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/cheques/pr/cmb.sql.precheque.php?nd='+nd+"&proveedor="+getObj('tesoreria_pago_pr_proveedor_id').value,
								datatype: "json",
								colNames:['Id','N precheque','Id Banco','Banco','N Cuenta','id_proveedor','codigo_proveedor','nombre_proveedor2','nombre_proveedor','Base Imp','Concepto','porcentaje','ordenes','islr','sustraendo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'n_precheque',index:'n_precheque', width:100,sortable:false,resizable:false},
									{name:'id_banco',index:'id_banco', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_banco',index:'nombre_banco', width:160,sortable:false,resizable:false},
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false},
									{name:'id_proveedor',index:'id_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_proveedor2',index:'nombre_proveedor2', width:100,sortable:false,resizable:false},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:100,sortable:false,resizable:false,hidden:true},
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
									getObj('tesoreria_pago_db_n_precheque').value=ret.n_precheque;
									getObj('tesoreria_pago_db_banco_id_banco').value=ret.id_banco;
									getObj('tesoreria_pago_db_nombre_banco').value=ret.nombre_banco;
									getObj('tesoreria_pago_db_n_cuenta').value=ret.cuentas;
									getObj('tesoreria_pago_pr_proveedor_id').value = ret.id_proveedor;
									getObj('tesoreria_pago_pr_proveedor_codigo').value = ret.codigo_proveedor;
									getObj('tesoreria_pago_pr_proveedor_nombre').value = ret.nombre_proveedor;
									getObj('tesoreria_pago_db_concepto').value=ret.concepto;
									getObj('tesoreria_pago_pr_ret_islr').value=ret.islr;
									orden=ret.ordenes;
									orden1=orden.replace("{","");
									getObj('tesoreria_pago_db_ordenes_pago').value=orden1.replace("}","");
								//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+ret.id_proveedor+'&ncuenta='+ret.cuentas+'&banco='+ret.id_banco+'&precheque='+ret.n_precheque+'&islr='+ret.islr,page:1}).trigger("reloadGrid");
									/*vector=getObj('tesoreria_pago_db_ordenes_pago').value;
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
								url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value;
								
								setTimeout(pasar_valores,100);
							//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value,page:1}).trigger("reloadGrid");
								if(ret.porcentaje!=0)
									{
										document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="";
									}
									//valor=parseFloat(ret.monto);
								   	//valor = valor.currency(2,',','.');	

								   	getObj('tesoreria_pago_db_monto_pagar').value=ret.monto;
									//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+ret.id_proveedor+'&ncuenta='+ret.cuentas+'&banco='+ret.id_banco+'&precheque='+ret.n_precheque+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value,page:1}).trigger("reloadGrid");

									dialog.hideAndUnload();

					 			
							/*	getObj('tesoreria_pago_db_nombre_banco').disabled="disabled";
								getObj('tesoreria_pago_pr_proveedor_codigo').disabled="disabled";
								getObj('tesoreria_pago_db_n_cuenta').disabled="disabled";
								getObj('tesoreria_pago_db_monto_pagar').disabled="disabled";*/
								getObj('tesoreria_pago_db_btn_cancelar').style.display='';
								getObj('tesoreria_pago_db_btn_actualizar').style.display='';
								getObj('tesoreria_pago_db_btn_imprimir').style.display='';
								getObj('tesoreria_pago_db_btn_imprimir_automatico').style.display='';
								getObj('tesoreria_pago_db_btn_guardar').style.display='none';									
								getObj('tesoreria_pago_db_btn_eliminar').style.display='';
								getObj('tesoreria_pago_pr_sustraendo_oculto').value=ret.sustraendo;
								if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=='1')
								{
									getObj('tesoreria_pago_pr_sustraendo').checked='checked';
								}else
								if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=='0')
								{
									getObj('tesoreria_pago_pr_sustraendo').checked='';
								}
								//getObj('tesoreria_pago_pr_ret_islr').disabled='disabled';

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
function pasar_valores()
{
	//url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_pago_db_ordenes_pago').value;

	//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_pago_db_ordenes_pago').value,page:1}).trigger("reloadGrid");	

//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value,page:1}).trigger("reloadGrid");
cambio();
	
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_precheque_codigo()
{
	if (getObj('tesoreria_pago_pr_proveedor_codigo').value!="")
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
									
									getObj('tesoreria_pago_db_n_precheque').value=recordset[1];
									getObj('tesoreria_pago_db_banco_id_banco').value=recordset[2];
									getObj('tesoreria_pago_db_nombre_banco').value=recordset[3];
									getObj('tesoreria_pago_db_n_cuenta').value=recordset[4];
									getObj('tesoreria_pago_pr_proveedor_id').value = recordset[5];
									getObj('tesoreria_pago_pr_proveedor_codigo').value = recordset[6];
									getObj('tesoreria_pago_pr_proveedor_nombre').value = recordset[7];
									getObj('tesoreria_pago_db_concepto').value=recordset[9];
									getObj('tesoreria_pago_pr_ret_islr').value=recordset[12];
									orden=recordset[11];
									orden1=orden.replace("{","");
									getObj('tesoreria_pago_db_ordenes_pago').value=orden1.replace("}","");
									/*vector=getObj('tesoreria_pago_db_ordenes_pago').value;
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
								setTimeout(pasar_valores,100);
								if(recordset[10]!=0)
									{
										document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="";
									}
									valor=parseFloat(recordset[8]);
								   	valor = valor.currency(2,',','.');	
								   	getObj('tesoreria_pago_db_monto_pagar').value=valor;
								//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value,page:1}).trigger("reloadGrid");
								/*	getObj('tesoreria_pago_db_nombre_banco').disabled="disabled";
									getObj('tesoreria_pago_pr_proveedor_codigo').disabled="disabled";
									getObj('tesoreria_pago_db_n_cuenta').disabled="disabled";
									getObj('tesoreria_pago_db_monto_pagar').disabled="disabled";*/
									getObj('tesoreria_pago_db_btn_cancelar').style.display='';
									getObj('tesoreria_pago_db_btn_actualizar').style.display='';
									getObj('tesoreria_pago_db_btn_imprimir').style.display='';
									getObj('tesoreria_pago_db_btn_imprimir_automatico').style.display='';
									getObj('tesoreria_pago_db_btn_guardar').style.display='none';	
									getObj('tesoreria_pago_db_btn_eliminar').style.display='';
									
									//getObj('tesoreria_pago_pr_ret_islr').disabled='disabled';
	
									getObj('tesoreria_pago_pr_sustraendo_oculto').value=recordset[13];
									if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=='1')
									{
										getObj('tesoreria_pago_pr_sustraendo').checked='checked';
									}else
									if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=='0')
									{
										getObj('tesoreria_pago_pr_sustraendo').checked='';
									}
										jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&sustraendo='+getObj('tesoreria_pago_pr_sustraendo_oculto').value,page:1}).trigger("reloadGrid");

										/*if(recordset[10]=='Activo')
										{ 
											getObj('tesoreria_pagoras_db_estatus_opt_act').checked="checked";
											getObj('tesoreria_pagoras_db_estatus').value="1";
										}else
										{
										getObj('tesoreria_pagoras_db_estatus_opt_inact').checked="checked";
										getObj('tesoreria_pagoras_db_estatus').value="2";
										}		*/
									
									
								}
								 else
								 {
									//limpiar();
									getObj('tesoreria_pago_db_ordenes_pago').value="";
									getObj('tesoreria_pago_db_n_precheque').value="";
									getObj('tesoreria_cheque_db_btn_imprimir').style.display='none';
									getObj('tesoreria_cheque_db_btn_imprimir_automatico').style.display='none';
									getObj('tesoreria_cheque_db_btn_actualizar').style.display='none';
									getObj('tesoreria_pago_db_btn_guardar').style.display='';
									getObj('tesoreria_pago_db_n_precheque').value="";
									getObj('tesoreria_pago_db_banco_id_banco').value="";
									getObj('tesoreria_pago_db_nombre_banco').value="";
									getObj('tesoreria_pago_db_n_cuenta').value="";
									getObj('tesoreria_pago_db_concepto').value="";
									jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value,page:1}).trigger("reloadGrid");
									document.form_tesoreria_db_cheque.tesoreria_pago_db_itf.checked="";
									getObj('tesoreria_pago_db_monto_pagar').value="";
									getObj('tesoreria_pago_pr_ret_islr').value='0,00';

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
		 
//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
$("#list_orden_pago").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando InformaciÛn del Servidor",

	url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+new Date().getTime()+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value,
//	+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value
	datatype: "json",
	colNames:['&ordm;Id','Orden','factura','fecha','Total.Pagar'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'n_orden',index:'n_orden', width:35},
			{name:'facturas',index:'facturas', width:45},
			{name:'fecha',index:'fecha', width:45},
			{name:'monto',index:'monto',width:55,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_orden'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	gridComplete:function(){
	vector=getObj('tesoreria_pago_db_ordenes_pago').value;
							if(vector!="")
							{
								vector2=vector.split(",");
							
									
									i=0;//&&(getObj('tesoreria_pago_db_n_precheque').value!="")
									if((vector2!=""))
									{										
											
											while((i<vector2.length))
											{
													
													jQuery("#list_orden_pago").setSelection(vector2[i]);
													i=i+1;		
											}
									}			
									
							}	

},
	onSelectRow: function(id){
        var ret = jQuery("#list_orden_pago").getRowData(id);
		html1="";
	   	s = jQuery("#list_orden_pago").getGridParam('selarrrow');
		/*if(getObj('tesoreria_pago_db_n_precheque').value=="")
		{
			getObj('tesoreria_pago_pr_ret_islr').value='0,00';
		}*/
		//alert(s);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('tesoreria_pago_db_ordenes_pago').value=s;
		$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&sustraendo='+getObj('tesoreria_pago_pr_sustraendo_oculto').value,
		    data:dataForm('form_tesoreria_db_cheque'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {/*url="modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value;
       			alert(url);*/

       		    var recordset=html;	
				//setBarraEstado(recordset);			
			//	valor=recordset;
				valor=parseFloat(recordset);
				valor = valor.currency(2,',','.');	
			    getObj('tesoreria_pago_db_monto_pagar').value=valor;
				if(getObj('tesoreria_pago_db_monto_pagar').value=='0,00')
				{
						getObj('tesoreria_pago_pr_sustraendo').checked='';
	        			getObj('tesoreria_pago_pr_sustraendo_oculto').value='0';	
				}
			}
			});	 
				
		}
	 		
	},
   
onSelectAll:function(id){
        var ret = jQuery("#list_orden_pago").getRowData(id);
	   	s = jQuery("#list_orden_pago").getGridParam('selarrrow');
		/*if(getObj('tesoreria_pago_db_n_precheque').value=="")
		{
			getObj('tesoreria_pago_pr_ret_islr').value='0,00';
		}*/
		//alert(s);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('tesoreria_pago_db_ordenes_pago').value=s;
		$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql.consulta_selec.php?id="+idd+"&vector="+s+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value+'&sustraendo='+getObj('tesoreria_pago_pr_sustraendo_oculto').value,
		    data:dataForm('form_tesoreria_db_cheque'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
			 	var recordset=html;				
				valor=parseFloat(recordset);
				valor = valor.currency(2,',','.');	
			    getObj('tesoreria_pago_db_monto_pagar').value=valor;
				if(getObj('tesoreria_pago_db_monto_pagar').value=='0,00')
				{
						getObj('tesoreria_pago_pr_sustraendo').checked='';
	        			getObj('tesoreria_pago_pr_sustraendo_oculto').value='0';	
				}
			}
			});	 
	}
}

/*function() {	
	   	var s
		s = jQuery("#list_orden_pago").getGridParam('selarrrow');
		alert(s);
		}*/

}).navGrid("#pager_cotizaciones",{search :false,edit:false,add:false,del:false});
		
//--------------------------------------------------------------------------------------------------------------------------------------
//consultas automaticas
function consulta_automatica_proveedor()
{
	getObj('tesoreria_pago_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_proveedor_codigo.php",
            data:dataForm('form_tesoreria_db_cheque'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
				
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_pago_pr_proveedor_nombre').value = recordset[1];
				getObj('tesoreria_pago_pr_proveedor_id').value=recordset[0];
				getObj('tesoreria_pago_db_n_precheque').value ="";
				rif=recordset[2];
				rif2 = rif.split("-");
				getObj('tesoreria_pago_pr_proveedor_rif').value=rif2[0];
				getObj('tesoreria_pago_pr_proveedor_rif').value=rif2[0];
									if(getObj('tesoreria_pago_pr_proveedor_rif').value=='V')
									{
										getObj('tesoreria_pago_pr_sustraendo').disabled='';
									}
	            jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+recordset[0]+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value,page:1}).trigger("reloadGrid");
	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_pago_pr_proveedor_nombre').value ="";
				getObj('tesoreria_pago_pr_proveedor_id').value="";
				getObj('tesoreria_pago_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}
function cambio()
{
//alert("el pasante");
//url='modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_pago_db_ordenes_pago').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value
	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?proveedor='+getObj('tesoreria_pago_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_pago_db_n_cuenta').value+'&banco='+getObj('tesoreria_pago_db_banco_id_banco').value+'&precheque='+getObj('tesoreria_pago_db_n_precheque').value+'&islr='+getObj('tesoreria_pago_pr_ret_islr').value+'&ordenes='+getObj('tesoreria_pago_db_ordenes_pago').value+'&rif='+getObj('tesoreria_pago_pr_proveedor_rif').value+'&sustraendo='+getObj('tesoreria_pago_pr_sustraendo_oculto').value,page:1}).trigger("reloadGrid");	
	
	
}	
function cargar_iframe()
{
	document.getElementById('iframeOculto').src="";
}	
$('#tesoreria_pago_pr_proveedor_codigo').change(consulta_automatica_proveedor);
//$('#tesoreria_pago_pr_ret_islr').blur(cambio);
$('#tesoreria_pago_pr_ret_islr').change(cambio);
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#tesoreria_pago_db_n_precheque').numeric({allow:'-'});
$('#tesoreria_pago_db_monto').numeric({allow:',.'});
$('#tesoreria_pago_db_rif').numeric({allow:'-'});
$('#tesoreria_pago_pr_proveedor_codigo').numeric({});
$('#tesoreria_pago_pr_ret_islr').numeric({});

$('#tesoreria_pago_db_ncheque_codigo').numeric({});
$('#tesoreria_pago_db_n_cuenta').numeric({});
$('#tesoreria_pago_pr_proveedor_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_pago_db_nombre_banco').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
//$('#tesoreria_pago_db_concepto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄·ÈÌÛ˙¡…Õ”⁄0123456789,.-'});
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
$("#tesoreria_pago_pr_endosable").click(function(){
		if(getObj('tesoreria_pago_db_endosable_oculto').value=="0")
			getObj('tesoreria_pago_db_endosable_oculto').value="1"
		else
		if(getObj('tesoreria_pago_db_endosable_oculto').value=="1")
			getObj('tesoreria_pago_db_endosable_oculto').value="0"
		
	});
$("#tesoreria_pago_pr_sustraendo").click(function(){
		if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=="0")
			getObj('tesoreria_pago_pr_sustraendo_oculto').value="1"
		else
		if(getObj('tesoreria_pago_pr_sustraendo_oculto').value=="1")
			getObj('tesoreria_pago_pr_sustraendo_oculto').value="0"
		
	});	
$('#tesoreria_pago_db_n_precheque').change(consulta_automatica_precheque_codigo);
$('#tesoreria_pago_pr_proveedor_codigo').change(consulta_automatica_proveedor);
	
</script>

   <div id="botonera"><img id="tesoreria_pago_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="tesoreria_pago_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="tesoreria_pago_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_pago_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="tesoreria_pago_db_btn_imprimir_automatico"  class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />
	<img id="tesoreria_pago_db_btn_imprimir"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" /></div>
<form method="post" id="form_tesoreria_db_cheque" name="form_tesoreria_db_cheque" onload="cargar_iframe()">
<input type="hidden"  id="tesoreria_vista_cheque" name="tesoreria_vista_cheque"/>
<input type="hidden" name="orden_pago_pr_cot_select" id="orden_pago_pr_cot_select"  />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Carga Ordenes Extraordinarias </th>
	</tr>
	
   	<tr>
		<th>Proveedor:</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				<input name="tesoreria_pago_pr_proveedor_codigo" type="text" id="tesoreria_pago_pr_proveedor_codigo"  maxlength="4"
				onchange="consulta_automatica_proveedor" onclick="consulta_automatica_proveedor"
				message="Introduzca un Codigo para el proveedor."  size="5"
				jVal="{valid:/^[0123456789]{1,5}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
				
	
				<input type="text" name="tesoreria_pago_pr_proveedor_nombre" id="tesoreria_pago_pr_proveedor_nombre" size="60"
				message="Introduzca el nombre del Proveedor." readonly />
				<input type="hidden" name="tesoreria_pago_pr_proveedor_id" id="tesoreria_pago_pr_proveedor_id" readonly />
				<input type="hidden" name="tesoreria_pago_pr_proveedor_rif" id="tesoreria_pago_pr_proveedor_rif" readonly />
				</li> 
					<li id="tesoreria_pago_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
				</ul>				</td>		
	</tr>
	<tr>
	 <th>Orden de Pago: </th>
	 	    <td>
				<ul class="input_con_emergente">
				<li>
		    	<input name="cuentas_por_pagar_db_orden_numero_control" type="text" id="cuentas_por_pagar_db_orden_numero_control"   value="" size="5" maxlength="5" message="Ingrese el Numero de control"  onblur="consulta_automatica_orden()" onchange="consulta_automatica_orden()"  
				jVal="{valid:/^[0123456789]{1,6}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			    jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		    	<input type="hidden"  id="cuentas_por_pagar_db_orden_numero_control_oculto" name="cuentas_por_pagar_db_orden_numero_control_oculto"/>	
				</li> 
					<li id="cuentas_por_pagar_db_orden_btn_consultar" class="btn_consulta_emergente"></li>
				</ul>				</td>	
    </tr>
	<tr>
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_pago_db_nombre_banco" type="text" id="tesoreria_pago_db_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_pago_db_banco_id_banco" name="tesoreria_pago_db_banco_id_banco"/>
		</li>
		<li id="tesoreria_pago_db_btn_consultar_banco_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
	<tr>
		<th>
			Monto a Pagar		</th>
		<td>
		<input align="right"  name="tesoreria_pago_db_monto_pagar" type="text" id="tesoreria_pago_db_monto_pagar"  readonly value="0,00" size="16" maxlength="16"  style="text-align:right" />
		<input  name="tesoreria_pago_db_ordenes_pago" type="hidden" id="tesoreria_pago_db_ordenes_pago" size="16" maxlength="16" />		</td>
	</tr>
	<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_orden_pago" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_orden" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
	</tr><tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table>
  <input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>
<iframe id="iframeOculto" name="iframeOculto"  src=" " style="width:0px; height:0px; border:0px"></iframe>
 <!-- <p>style="width:0px; height:0px; border:0px"</p>--> 
   