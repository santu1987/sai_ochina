<?php
session_start();
?>
<script type='text/javascript'>
// -------------------------script para sacar el monto total --------------------------- //
	function restar_manual()
	{
	
base=getObj('tesoreria_cheque_manual_db_baseimp').value.float();
imp=getObj('tesoreria_cheque_manual_db_islr').value.float();
islr=imp*(base/100);
//////////////////////////////////////////////////////////////////////////////////	
	 if((base>=4138)&&(getObj('tesoreria_cheque_manual_pr_proveedor_rif').value=='V')&&(getObj('tesoreria_cheque_manual_db_islr').value)!='0,00') 
	 {
	 //	alert("entro");
	 	islr=islr-138;
	}	
//////////////////////////////////////////////////////////////////////////////////	 

getObj('oculto_islr').value=islr;
total=base-islr; total = total.currency(2,',','.');
getObj('tesoreria_cheque_manual_db_monto_pagar').value=total
	 /*var valor1 = document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_baseimp.value;
	 var valor2 = document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_islr.value;
	 valor1 = valor1.replace('.','');
	 valor1 = valor1.replace(',','.');
	 document.form_tesoreria_db_cheque_manual.oculto_baseimp.value=valor1;
     valor2 = valor2.replace(',','.');
	 document.form_tesoreria_db_cheque_manual.oculto_porislr.value=valor2;
	 valor1=parseFloat(valor1.replace(',',''));
	 valor2=parseFloat(valor2.replace(',',''));
	 //alert(valor1+"-----"+valor2);
 	 var porcenta_arestar_manual= valor1*(valor2/100);
//
	// porcenta_arestar = porcenta_arestar.replace('.','');
	// porcenta_arestar = porcenta_arestar.replace(',','.');
//
//////////////////////////////////////////////////////////////////////////////////	
	 if((document.form_tesoreria_db_cheque_manual.oculto_baseimp.value>=4138)&&(tesoreria_cheque_manual_pr_proveedor_rif=='V'))porcenta_arestar =porcenta_arestar +138;
//////////////////////////////////////////////////////////////////////////////////	 
	 document.form_tesoreria_db_cheque_manual.oculto_islr.value = porcenta_arestar;
	 var total= valor1 - porcenta_arestar;
	 
	 total = total.currency(2,',','.');
	 document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_monto_pagar.value =total;	
	var val = document.form_tesoreria_db_cheque_manual.oculto_islr.value;
	val = parseFloat(val.replace('.',','));
	val= val.currency(2,'.',',');
	document.form_tesoreria_db_cheque_manual.oculto_islr.value=val;
	return total;*/
	}
// ------------------------- fin del script monto total  ------------------------------- //
</script>
<script type='text/javascript'>
$("#tesoreria_cheque_manual_db_btn_actualizar").click(function() {
	getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.actualizar_manual.php",
			data:dataForm('form_tesoreria_db_cheque_manual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					clearForm('form_tesoreria_db_cheque_manual');
					getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='none';
					getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='';
					getObj('tesoreria_cheque_manual_db_btn_imprimir').style.display='none';
					getObj('tesoreria_cheque_manual_db_btn_imprimir_automatico').style.display='none';
					getObj('tesoreria_cheque_manual_pr_radio1').checked="checked";
					getObj('tesoreria_cheque_manual_pr_op_oculto').value=1;
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value=1;
					getObj('tr_proveedor').style.display='';
					getObj('tr_empleado').style.display='none';
				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='none';
					limpiar_manual();
					getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";
					getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='none';
					getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='';
					getObj('tesoreria_cheque_manual_db_btn_cancelar').style.display='';
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value=1;
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
});


////////////////////////////////////////////////////////////////////////////
function modificar_inactivo_manual() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.modificar_inactivo_manuales.php",
				data:dataForm('form_tesoreria_db_cheque_manual'),
				type:'POST',
				cache: false,
				success: function(html)
				{
					recordset=html;
					recordset = recordset.split("*");
					//alert(recordset);
					if (recordset[0]=="inactiva")
					{
					 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	LA CHEQUERA ACTUAL SE AGOTÓ DESEA ACTIVAR LA SIGUIENTE NÚMERO : "+recordset[1]+"</p></div>", ["ACEPTAR","CANCELAR"], 
						function(val)
						 {
							if(val=='ACEPTAR')
							{
								activo_chequera_manual();
							}   
						 }, {title:"SAI-OCHINA"});
					
					}
					else if (recordset[0]=="inactiva2")
					{	
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA CHEQUERA ACTUAL SE AGOTÓ NO HAY MAS CHEQUERAS CARGADA PARA ESTE BANCO</p></div>",true,true);

						}
				/*	else if (html!="")
					{	
						alert(html);
						setBarraEstado(html);
						}*/
					/*else
					{	
						setTimeout("limpiar_manual()",2000);

					}	*/
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////////
function activo_chequera_manual() {
$.ajax (
			{
				url: "modulos/tesoreria/cheques/pr/sql.activo_chequera_manual.php",
				data:dataForm('form_tesoreria_db_cheque_manual'),
				type:'POST',
				cache: false,
				success: function(html)
				{	
					
					recordset=html;
					recordset = recordset.split("*");
				//	alert(recordset);
					/*if (recordset[0]=="activa")
					{
						
						alert("Se activo la siguiente chequera numero : "+recordset[1]);
						//alert("Se activo la chequera  numero : "+recordset[1]);						
					}
					
					else */
					if (recordset[0]!="activa")
					{	
						alert(html);
						setBarraEstado(html);
						}
						//jQuery("#list_orden_pago").setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php"}).trigger("reloadGrid");
						
					
				}
	});
	
}
///////////////////////////////////////////////////////////////////////////////
$("#tesoreria_cheque_manual_db_btn_guardar").click(function() {
if(getObj('tesoreria_cheque_manual_db_monto_pagar').value=="0,00")
{
	alert("No se puede almacenar el cheque con un monto menor o igual a 0,00 bsf")
}
	if($('#form_tesoreria_db_cheque_manual').jVal()&& getObj('tesoreria_cheque_manual_db_monto_pagar').value!="0,00")
	{
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.cheques_manuales.php",
			data:dataForm('form_tesoreria_db_cheque_manual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar_manual();
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';
					getObj('tesoreria_cheque_manual_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheque_manual_db_baseimp').value="0,00";
					getObj('tesoreria_cheque_manual_db_islr').value="0,00";
					getObj('tesoreria_cheque_manual_pr_radio1').checked="checked";
					getObj('tr_empleado').style.display='none';
					getObj('tr_proveedor').style.display='';
					
					getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";

				}
				else if (html=="NoRegistro")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras</p></div>",true,true);
					/*alert("La cuenta del usuario no posee chequera registrada,por favor consulte las mismas en el modulo chequeras");*/
					limpiar_manual();
					getObj('tesoreria_cheque_manual_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';
					getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";
					}
				else if (html=="Error-orden")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Las orden de pago seleccionadas ya fueron canceladas en otro cheque registrado</p></div>",true,true);
					
					setBarraEstado("");
					clearForm('form_tesoreria_db_cheque');
					getObj('tesoreria_cheque_manual_db_monto_pagar').value="0,00";
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';
					}	
				else
					if (html=="cerrado")
					{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />MÓDULO CERRADO</p></div>",true,true);
					}
				else
					if (html=="No hay firmas activas")
					{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />No existen firmas activas</p></div>",true,true);
					}			
				else
				{
					alert(html);
					setBarraEstado(html);
					getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';

				}
			
			}
		});
	}
});
/*
///IMPRIMIR_PRUEBA

$("#tesoreria_cheque_manual_db_btn_imprimir").click(function() {
if($('#form_tesoreria_db_cheque_manual').jVal())
	{

confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
if (confirmar)
{
	
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
		//alert('Preparando vista de impresión');
		url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque.php¿id_banco="+getObj('tesoreria_cheque_manual_db_banco_id_banco').value+"@ncheque="+getObj('tesoreria_cheque_manual_db_n_precheque').value+"@ncuenta="+getObj('tesoreria_cheque_manual_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"@caducidad="+getObj('tesoreria_cheque_manual_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheque_manual_db_endosable_oculto').value; 
//		url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+getObj('tesoreria_cheques_db_n_precheque').value+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value; 
//document.getElementById('iframeOculto').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+getObj('tesoreria_cheques_db_n_precheque').value+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value;  
//		a=getObj('iframeOculto').location="modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheques_db_banco_id_banco').value+"@ncheque="+getObj('tesoreria_cheques_db_n_precheque').value+"@ncuenta="+getObj('tesoreria_cheques_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheques_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheques_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheques_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheques_db_endosable_oculto').value; 
//				alert(url);
						openTab("cheques",url);
						limpiar();
}	
else
alert('Por favor verifique si los datos de cheque son lso correctos');	
}

});
//sin vista previa
$("#tesoreria_cheque_manual_db_btn_imprimir_automatico").click(function() {
if($('#form_tesoreria_db_cheque_manual').jVal())
	{

confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
if (confirmar)
{
	
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
alert('Preparando vista de impresión');
document.getElementById('iframeOculto_manual').src="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques.php¿id_banco="+getObj('tesoreria_cheque_manual_db_banco_id_banco').value+"@ncheque="+getObj('tesoreria_cheque_manual_db_n_precheque').value+"@ncuenta="+getObj('tesoreria_cheque_manual_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"@ordenes="+getObj('tesoreria_cheque_manual_db_ordenes_pago').value+"@caducidad="+getObj('tesoreria_cheque_manual_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheque_manual_db_endosable_oculto').value;  
		limpiar();
}	
else
alert('Por favor verifique si los datos de cheque son lso correctos');	
}

});
/*//////////////////////////// impresion sin vista previa
$("#tesoreria_cheque_manual_db_btn_imprimir_automatico").click(function() {
if($('#form_tesoreria_db_cheque_manual').jVal())
{
/*confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
if (confirmar)
{
	*/	
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos, Nota:de ser incorrectos debera anular el cheque impreso</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{					
$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques_manuales.php",
			data:dataForm('form_tesoreria_db_cheque_manual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='firma_inactiva')&&(html!='no_disponible_saldo')&&(html!='cerrado'))
				{	
					if(getObj('tesoreria_cheque_manual_pr_op_oculto').value=='2')
					{
								id_proveedor="0";
					}else
					id_proveedor=getObj('tesoreria_cheque_manual_pr_proveedor_id').value;
					recordset=html;
					recordset = recordset.split("*");
					urls="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheques_manuales.php¿id_banco="+getObj('tesoreria_cheque_manual_db_banco_id_banco').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_manual_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"@caducidad="+getObj('tesoreria_cheque_manual_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheque_manual_db_endosable_oculto').value+"@secuencia="+recordset[1]+"@opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto').value; 
					
					//getObj('iframeOculto_manual').src=urls;
					modificar_inactivo_manual();
					Boxy.ask("<iframe style='width:0px; height:0px; border:0px' src="+urls+" ></iframe><div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/ajax-loader2.gif />REALIZANDO IMPRESI&Oacute;N:porfavor presione el boton cerrar de esta ventana luego que haya culminado la impresión</p></div>", ["CERRAR"], 
					function(val)
					 {
                		if(val=="CERRAR")
						{
							setTimeout("limpiar_manual()",200);
						}   
					 }, {title:"SAI-OCHINA"});
					//setTimeout("limpiar_iframe_manual()",600);
					
					/* ant
					
					modificar_inactivo_manual();
					getObj('iframeOculto_manual').src=urls;
					setTimeout("limpiar_iframe_manual()",600);
					
					 :modificar_inactivo_manual();
					setTimeout("limpiar_manual()",600);
					alert("Realizando impresion");
					setTimeout("limpiar_iframe_manual()",1000);*/
				}
				
				if(html=='chequera_agotada' )
				{
					//setBarraEstado(mensaje[no_impresion],true,true);
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA, PARA EMITIR UN CHEQUE POR LA MISMA DEBE CREAR UNA CHEQUERA NUEVA</p></div>",true,true);
				}
				else
				if(html=='no_disponible_saldo')
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO HAY SALDO DISPONIBLE PARA EL DESARROLLO DE ESTA OPERACI&Oacute;N</p></div>",true,true);
				}
				else
				if(html=='Error_impresion' )
					{
						alert(html);
						setBarraEstado(html);
	 					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					}
				else
				if (html=="cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}	
				else
				if(html=='firma_inactiva')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />FIRMA INACTIVA,Dirigase al modulo de firmas voucher y active alguna cuenta</p></div>",true,true);
				}
				
			}
		});
//-
}	
})
}
});
////////////////////////////impresion con vista previa

$("#tesoreria_cheque_manual_db_btn_imprimir").click(function() {
if($('#form_tesoreria_db_cheque_manual').jVal())
	{
/*confirmar=confirm("Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso");
if (confirmar)
{*/
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea realmente imprimir el cheque, ya ha verificado si los datos colocados son correctos?, Nota:de ser incorrectos debera anular el cheque impreso</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
 {
	if(val=='ACEPTAR')
	{
$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.impresion_cheques_manuales.php",
			data:dataForm('form_tesoreria_db_cheque_manual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if((html!='Error_impresion' )&&(html!='chequera_agotada')&&(html!='firma_inactiva')&&(html!='no_disponible_saldo')&&(html!="cerrado"))
				{
					recordset=html;
					recordset = recordset.split("*");
					if(getObj('tesoreria_cheque_manual_pr_op_oculto').value=='2')
					{
								id_proveedor="0";
					}
					url="pdf.php?p=modulos/tesoreria/cheques/rp/vista.lst.cheque_manual.php¿id_banco="+getObj('tesoreria_cheque_manual_db_banco_id_banco').value+"@opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto').value+"@ncheque="+recordset[0]+"@ncuenta="+getObj('tesoreria_cheque_manual_db_n_cuenta').value+"@proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"@caducidad="+getObj('tesoreria_cheque_manual_pr_caducidad').value+"@endosable="+getObj('tesoreria_cheque_manual_db_endosable_oculto').value+"@secuencia="+recordset[1]; 
				//	alert(url);
					openTab("cheques",url);
			 		modificar_inactivo_manual();
					limpiar_manual();
					
				}
				
				if(html=='chequera_agotada' )
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE ENCUENTRAN CHEQUERAS ACTIVAS PARA ESTA CUENTA, PARA EMITIR UN CHEQUE POR LA MISMA DEBE CREAR UNA CHEQUERA NUEVA</p></div>",true,true);
				}
				else
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
				if (html=="cerrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />M&Oacute;DULO CERRADO</p></div>",true,true);
				}	
				else
				if(html=='firma_inactiva')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />FIRMA INACTIVA,Dirigase al modulo de firmas voucher y active alguna cuenta</p></div>",true,true);
				}		
				/*else
				{
				alert(html);
						setBarraEstado(html);
				}	*/

				
			}
		});
//-
}	
})
}
});
//---
//-------------------------------------------------------------------------------------------------------------------------------------------------------

//---
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_manual_db_btn_eliminar").click(function() {
	/*if(confirm("¿Desea elminar el registro seleccionado?")) 
	{*/
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />¿Desea elminar el registro seleccionado?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";

	$.ajax (
		{
			url: "modulos/tesoreria/cheques/pr/sql.eliminar_manual.php",
			data:dataForm('form_tesoreria_db_cheque_manual'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar_manual();
				}
				else
				{
					//setBarraEstado(mensaje[relacion_existe],true,true);
					alert("No se puedo eliminar el precheque"); 
					setBarraEstado(html);
				}
			}
		});
	}
})	
});
//--
function limpiar_iframe_manual()
{
	//alert("limpiando esta vaina");
	document.getElementById('iframeOculto_manual').value="";
	document.getElementById('iframeOculto_manual').src="";
}
//---
function limpiar_manual(){
setBarraEstado("");
	//getObj('tesoreria_cheque_manual_db_nombre_banco').disabled="";
	getObj('tesoreria_cheque_manual_pr_proveedor_codigo').disabled="";
	getObj('tesoreria_cheque_manual_db_n_cuenta').disabled="";
	getObj('tesoreria_cheque_manual_db_monto_pagar').disabled="";
	getObj('tesoreria_cheque_manual_pr_proveedor_nombre').disabled="disabled";
	getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='none';
	getObj('tesoreria_cheque_manual_db_btn_imprimir').style.display='none';	
	getObj('tesoreria_cheque_manual_db_btn_imprimir_automatico').style.display='none';	
	getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='none';
	getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='';
	clearForm('form_tesoreria_db_cheque_manual');
	getObj('tesoreria_cheque_manual_pr_caducidad').value=3;
	getObj('tesoreria_cheque_manual_db_endosable_oculto').value="1";
	document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="";
	document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_pr_endosable.checked="checked";
    getObj('tesoreria_cheque_manual_db_monto_pagar').value="0,00";
	getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disabled";
	getObj('tesoreria_cheque_manual_pr_op_oculto').value='1';
	getObj('tesoreria_cheque_manual_pr_radio1').checked="checked";
	getObj('tr_empleado').style.display='none';
	getObj('tr_proveedor').style.display=''
	getObj('tesoreria_cheque_manual_db_baseimp').value="0,00";
	getObj('tesoreria_cheque_manual_db_islr').value="0,00";
	//setTimeout("limpiar_iframe_manual()",5000);


}
$("#tesoreria_cheque_manual_db_btn_cancelar").click(function() {
limpiar_manual();
});	
//------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_manual_db_btn_consultar_cuentas_chequeras").click(function() {
if((getObj('tesoreria_cheque_manual_db_banco_id_banco').value!="")&&(getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display=='none'))
{
	urls='modulos/tesoreria/cheques/pr/sql_grid_cuentas.php?nd='+nd+'&banco='+getObj('tesoreria_cheque_manual_db_banco_id_banco').value;

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
									getObj('tesoreria_cheque_manual_db_n_cuenta').value=ret.ncuenta;
									dialog.hideAndUnload();
							//	jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value,page:1}).trigger("reloadGrid");
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
$("#tesoreria_cheque_manual_db_btn_consultar_banco_chequeras").click(function() {
if(getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display=='none')
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
					var busq_banco= jQuery("#tesoreria_cheques_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_cheques_busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_cheque_manual_dosearch();
					});
				
						function consulta_cheque_manual_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_cheque_manual_gridReload,500)
										}
						function consulta_cheque_manual_gridReload()
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
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo Área','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas'],
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
									getObj('tesoreria_cheque_manual_db_banco_id_banco').value=ret.id;
									getObj('tesoreria_cheque_manual_db_nombre_banco').value=ret.nombre;
									getObj('tesoreria_cheque_manual_db_n_cuenta').value=ret.cuentas;
								dialog.hideAndUnload();
								//jQuery("#list_orden_pago").setGridParam({url:'modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value,page:1}).trigger("reloadGrid");
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
//----------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_manual_db_btn_consultar_beneficiario").click(function() {

	/*	var nd=new Date().getTime();
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
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
								colNames:['C&oacute;digo','Beneficiario'],
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
									getObj('tesoreria_cheque_manual_pr_empleado_codigo').value = ret.rif;
									getObj('tesoreria_cheque_manual_pr_empleado_nombre').value = ret.beneficiario;
									getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
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
$("#tesoreria_cheque_manual_db_btn_consultar_proveedor").click(function() {

/*		var nd=new Date().getTime();
		getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
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
							//setBarraEstado(url);
						}
						function consulta_doc_gridReload2()
						{
							var busq_codigo= jQuery("#tesoreria_pr_proveedor_codigo_consulta_cheques_m").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?busq_codigo="+busq_codigo;
							//setBarraEstado(url);
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
									getObj('tesoreria_cheque_manual_pr_proveedor_id').value = ret.id_proveedor;
									getObj('tesoreria_cheque_manual_pr_proveedor_codigo').value = ret.codigo;
									getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value = ret.nombre;
									getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('tesoreria_cheque_manual_pr_proveedor_rif').value=rif2[0];
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
//-------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_cheque_manual_db_btn_consultar_precheque").click(function() {
if((getObj('tesoreria_cheque_manual_pr_proveedor_id').value!="")||(getObj('tesoreria_cheque_manual_pr_empleado_codigo').value!=""))
{	
		var nd=new Date().getTime();
url='modulos/tesoreria/cheques/pr/cmb.sql.precheque_manual.php?nd='+nd+"&opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto').value+"&proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"&beneficiario="+getObj('tesoreria_cheque_manual_pr_empleado_codigo').value;
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
								width:750,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/cheques/pr/cmb.sql.precheque_manual.php?nd='+nd+"&opcion="+getObj('tesoreria_cheque_manual_pr_op_oculto').value+"&proveedor="+getObj('tesoreria_cheque_manual_pr_proveedor_id').value+"&beneficiario="+getObj('tesoreria_cheque_manual_pr_empleado_codigo').value,
								datatype: "json",
								colNames:['Id','N Precheque','Id Banco','Banco','N Cuenta','id_proveedor','codigo_proveedor','Proveedor','Monto','Concepto','porcentaje','opcion','',''],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'n_precheque',index:'n_precheque', width:100,sortable:false,resizable:false},
									{name:'id_banco',index:'id_banco', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_banco',index:'nombre_banco', width:100,sortable:false,resizable:false},
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false},
									{name:'id_proveedor',index:'id_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'codigo_proveedor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:100,sortable:false,resizable:false},
									{name:'monto',index:'monto', width:100,sortable:false,resizable:false},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje',index:'porcentaje', width:100,sortable:false,resizable:false,hidden:true},
									{name:'opcion',index:'opcion', width:100,sortable:false,resizable:false,hidden:true},
									{name:'base_imponible',index:'base_imponible', width:100,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje_islr',index:'porcentaje_islr', width:100,sortable:false,resizable:false,hidden:true}

								  ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: 
								
								function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_cheque_manual_db_n_precheque').value=ret.n_precheque;
									getObj('tesoreria_cheque_manual_db_banco_id_banco').value=ret.id_banco;
									getObj('tesoreria_cheque_manual_db_nombre_banco').value=ret.nombre_banco;
									getObj('tesoreria_cheque_manual_db_n_cuenta').value=ret.cuentas;
									if(ret.opcion=='1')
									{
											getObj('tesoreria_cheque_manual_pr_op_oculto').value='1';
											getObj('tesoreria_cheque_manual_pr_radio1').checked="checked";
											getObj('tesoreria_cheque_manual_pr_proveedor_id').value = ret.id_proveedor;
											getObj('tesoreria_cheque_manual_pr_proveedor_codigo').value = ret.codigo_proveedor;
											getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value = ret.nombre_proveedor;
											getObj('tesoreria_cheque_manual_db_baseimp').value = ret.base_imponible;
											getObj('tesoreria_cheque_manual_db_islr').value = ret.porcentaje_islr;
									}
									if(ret.opcion=='2')
									{ //alert(ret.base_imponible);
										var codigo = getObj('tesoreria_cheque_manual_pr_empleado_codigo').value;
										var nombre=getObj('tesoreria_cheque_manual_pr_empleado_nombre').value;
										getObj('textprue2').value=codigo;
										getObj('textprue3').value=nombre;
											getObj('tesoreria_cheque_manual_pr_op_oculto').value='2';
											getObj('tesoreria_cheque_manual_pr_radio2').checked="checked";
											getObj('tesoreria_cheque_manual_pr_empleado_codigo').value = codigo;//ret.cedula_rif_beneficiario;
											getObj('tesoreria_cheque_manual_pr_empleado_nombre').value = nombre;
									getObj('tesoreria_cheque_manual_db_baseimp').value = ret.base_imponible;
									getObj('tesoreria_cheque_manual_db_islr').value = ret.porcentaje_islr;		
									}
									getObj('tesoreria_cheque_manual_db_concepto').value=ret.concepto;
								//	valor=parseFloat(ret.monto);
								//   	valor = valor.currency(2,',','.');	
								   	getObj('tesoreria_cheque_manual_db_monto_pagar').value=ret.monto;
								//	valor2=parseFloat(ret.base_imponible);
								//	valor2= valor2.currency(2,',','.');
									getObj('tesoreria_cheque_manual_db_baseimp').value=ret.base_imponible;
									//valor3=parseFloat(ret.porcentaje_islr);
									//valor3= valor3.currency(2,',','.');
									getObj('tesoreria_cheque_manual_db_islr').value=ret.porcentaje_islr;
									dialog.hideAndUnload();
									
									/*vector=getObj('tesoreria_cheque_manuals_db_ordenes_pago').value;
									vector2=vector.split(",");
									//alert(vector2);
									i=0;
									while(i<vector2.length)
									{
											jQuery("#list_orden_pago").setSelection(1);

											//alert(vector2[i]);
											i=i+1;		
									}	
								*/	if(ret.porcentaje!=0)
									{
										document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="";
									}
									
					 			
							/*	getObj('tesoreria_cheque_manuals_db_nombre_banco').disabled="disabled";
								getObj('tesoreria_cheque_manuals_pr_proveedor_codigo').disabled="disabled";
								getObj('tesoreria_cheque_manuals_db_n_cuenta').disabled="disabled";
								getObj('tesoreria_cheque_manuals_db_monto_pagar').disabled="disabled";*/
								getObj('tesoreria_cheque_manual_db_btn_cancelar').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_imprimir').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_imprimir_automatico').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='none';									
								getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='';
								
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
function consulta_automatica_precheque_codigo_manual()
{//alert('esta pasando');
	if ((getObj('tesoreria_cheque_manual_pr_proveedor_id').value!="")||(getObj('tesoreria_cheque_manual_pr_empleado_codigo').value!=""))
    {
			$.ajax({
					url:'modulos/tesoreria/cheques/pr/sql_grid_precheque_manual.php',
					data:dataForm('form_tesoreria_db_cheque_manual'),
					type:'POST',
					cache: false,
					 success:function(html)
					 {//alert(html);
					    if((html!="")||(html!=null)||(html!="undefined"))
						{		var recordset=html;
								if(recordset)
								{
									recordset = recordset.split("*");
										getObj('tesoreria_cheque_manual_db_n_precheque').value=recordset[1];
										getObj('tesoreria_cheque_manual_db_banco_id_banco').value=recordset[2];
										getObj('tesoreria_cheque_manual_db_nombre_banco').value=recordset[3];
										getObj('tesoreria_cheque_manual_db_n_cuenta').value=recordset[4];
										if(getObj('tesoreria_cheque_manual_pr_op_oculto').value==1){
											getObj('tesoreria_cheque_manual_pr_proveedor_id').value = recordset[5];
											getObj('tesoreria_cheque_manual_pr_proveedor_codigo').value = recordset[6];
											getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value = recordset[7];
										}
										if(getObj('tesoreria_cheque_manual_pr_op_oculto').value==2){
											getObj('tesoreria_cheque_manual_pr_empleado_codigo').value = recordset[6];
											getObj('textprue').value = recordset[6];
											getObj('tesoreria_cheque_manual_pr_empleado_nombre').value = recordset[7];
										}
										getObj('tesoreria_cheque_manual_db_concepto').value=recordset[9];
										val1=parseFloat(recordset[12]);
										val1=val1.currency(2,',','.');
										getObj('tesoreria_cheque_manual_db_baseimp').value=val1;
										val2=parseFloat(recordset[13]);
										val2=val2.currency(2,',','.');
										getObj('tesoreria_cheque_manual_db_islr').value=val2;
									
									/*vector=getObj('tesoreria_cheque_manuals_db_ordenes_pago').value;
									vector2=vector.split(",");
									//alert(vector2);
									i=0;
									while(i<vector2.length)
									{
											jQuery("#list_orden_pago").setSelection(1);

											//alert(vector2[i]);
											i=i+1;		
									}	
								*/	if(recordset[10]!=0)
									{
										document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="checked";
									}
									else
									{
										document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="";
									}
									valor=parseFloat(recordset[8]);
								   	valor = valor.currency(2,',','.');	
								   	getObj('tesoreria_cheque_manual_db_monto_pagar').value=valor;
							/*	getObj('tesoreria_cheque_manuals_db_nombre_banco').disabled="disabled";
								getObj('tesoreria_cheque_manuals_pr_proveedor_codigo').disabled="disabled";
								getObj('tesoreria_cheque_manuals_db_n_cuenta').disabled="disabled";
								getObj('tesoreria_cheque_manuals_db_monto_pagar').disabled="disabled";*/
								getObj('tesoreria_cheque_manual_db_btn_cancelar').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_imprimir').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_imprimir_automatico').style.display='';
								getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='none';	
								getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='';
	
										
								}
								 else
								 {
									//limpiar_manual();
									getObj('tesoreria_cheque_manual_db_btn_imprimir').style.display='none';	
									getObj('tesoreria_cheque_manual_db_btn_imprimir_automatico').style.display='none';	
									getObj('tesoreria_cheque_manual_db_btn_actualizar').style.display='none';
									getObj('tesoreria_cheque_manual_db_btn_eliminar').style.display='none';
									getObj('tesoreria_cheque_manual_db_btn_guardar').style.display='';
									getObj('tesoreria_cheque_manual_db_n_precheque').value="";
									getObj('tesoreria_cheque_manual_db_banco_id_banco').value="";
									getObj('tesoreria_cheque_manual_db_nombre_banco').value="";
									getObj('tesoreria_cheque_manual_db_n_cuenta').value="";
									getObj('tesoreria_cheque_manual_db_concepto').value="";
									getObj('tesoreria_cheque_manual_db_ordenes_pago').value="";
									document.form_tesoreria_db_cheque_manual.tesoreria_cheque_manual_db_itf.checked="";
									getObj('tesoreria_cheque_manual_db_monto_pagar').value="0,00";
									getObj('tesoreria_cheque_manual_db_baseimp').value="0,00";
									getObj('tesoreria_cheque_manual_db_islr').value="0,00";
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

//consultas automaticas
function consulta_automatica_proveedor_manual()
{
	getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_proveedor_codigo_manual.php",
            data:dataForm('form_tesoreria_db_cheque_manual'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);		
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value = recordset[1];
				getObj('tesoreria_cheque_manual_pr_proveedor_id').value=recordset[0];
				getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
				rif=recordset[2];
				rif2 = rif.split("-");
								getObj('tesoreria_cheque_manual_pr_proveedor_rif').value=rif[0];

	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheque_manual_pr_proveedor_id').value="";
				getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}
function consulta_automatica_empleado_manual()
{

	getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
	$.ajax({
			url:"modulos/tesoreria/cheques/pr/sql_grid_proveedor_codigo_manual.php",
            data:dataForm('form_tesoreria_db_cheque_manual'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;			
					//alert(html);
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('tesoreria_cheque_manual_pr_empleado_codigo').value = recordset[0];
				getObj('tesoreria_cheque_manual_pr_empleado_nombre').value=recordset[1];
				getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value = "";
				getObj('tesoreria_cheque_manual_pr_proveedor_id').value="";
				getObj('tesoreria_cheque_manual_db_n_precheque').disabled="";
				
	//setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
			}
				else
			 {  
			   	getObj('tesoreria_cheque_manual_pr_proveedor_nombre').value ="";
				getObj('tesoreria_cheque_manual_pr_proveedor_id').value="";
				getObj('tesoreria_cheque_manual_pr_empleado_nombre').value="";
				getObj('tesoreria_cheque_manual_db_n_precheque').disabled="disdabled";
				}
				
			 }
		});	 	 
}

$("#tesoreria_cheque_manual_pr_radio1").click(function(){
		getObj('tesoreria_cheque_manual_pr_op_oculto').value="1"
	});
$("#tesoreria_cheque_manual_pr_radio2").click(function(){
		getObj('tesoreria_cheque_manual_pr_op_oculto').value="2"
	});
	
$('#tesoreria_cheque_manual_pr_proveedor_codigo').change(consulta_automatica_proveedor_manual)
$('#tesoreria_cheque_manual_pr_empleado_codigo').change(consulta_automatica_empleado_manual)

</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#tesoreria_cheque_manual_db_n_precheque').numeric({allow:'-'});
$('#tesoreria_cheque_manual_db_monto').numeric({allow:',.'});
$('#tesoreria_cheque_manual_db_rif').numeric({allow:'-'});
$('#tesoreria_cheque_manual_pr_proveedor_codigo').numeric({});
$('#tesoreria_cheque_manual_db_ncheque_codigo').numeric({});
$('#tesoreria_cheque_manual_db_n_cuenta').numeric({});
$('#tesoreria_cheque_manual_pr_proveedor_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#tesoreria_cheque_manual_db_nombre_banco').alpha({allow:' áéíóúÄÉÍÓÚ'});
//$('#tesoreria_cheque_manuals_db_concepto').alpha({allow:' áéíóúÄÉÍÓÚ'});
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
$("#tesoreria_cheque_manual_pr_endosable").click(function(){
		if(getObj('tesoreria_cheque_manual_db_endosable_oculto').value=="0")
			getObj('tesoreria_cheque_manual_db_endosable_oculto').value="1"
		else
		if(getObj('tesoreria_cheque_manual_db_endosable_oculto').value=="1")
			getObj('tesoreria_cheque_manual_db_endosable_oculto').value="0"
		
	});
$('#tesoreria_cheque_manual_db_n_precheque').change(consulta_automatica_precheque_codigo_manual);
$('#tesoreria_cheque_manual_pr_proveedor_codigo').change(consulta_automatica_proveedor_manual);
	
</script>
   <div id="botonera"><img id="tesoreria_cheque_manual_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="tesoreria_cheque_manual_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="tesoreria_cheque_manual_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/><img id="tesoreria_cheque_manual_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="tesoreria_cheque_manual_db_btn_imprimir_automatico" class="btn_imprimir" src="imagenes/null.gif"  style="display:none" />
	<img id="tesoreria_cheque_manual_db_btn_imprimir"  class="btn_imprimir_vista_previa" src="imagenes/null.gif"  style="display:none" /></div>
	</div>
<form method="post" id="form_tesoreria_db_cheque_manual" name="form_tesoreria_db_cheque_manual">
<input type="hidden"  id="tesoreria_vista_cheque" name="tesoreria_vista_cheque"/>
<input type="hidden" name="orden_pago_pr_cot_select" id="orden_pago_pr_cot_select"  />
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Cargar Cheques Manuales </th>
	</tr>
	
	  <th>Banco:</th>
	 	    <td>
		 <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_cheque_manual_db_nombre_banco" type="text" id="tesoreria_cheque_manual_db_nombre_banco"   value="" size="50" maxlength="80" message="Seleccione el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ-.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		    	<input type="hidden"  id="tesoreria_cheque_manual_db_banco_id_banco" name="tesoreria_cheque_manual_db_banco_id_banco"/>
		</li>
		<li id="tesoreria_cheque_manual_db_btn_consultar_banco_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_cheque_manual_db_n_cuenta" type="text" id="tesoreria_cheque_manual_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el Número de cuenta. " readonly=""/>
		</li>
		<li id="tesoreria_cheque_manual_db_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	<tr>
	  <th>Beneficiario</th>
	  <td><label>
	     <input name="tesoreria_cheque_manual_pr_radio" type="radio" id="tesoreria_cheque_manual_pr_radio1" onclick="getObj('tr_empleado').style.display='none'; getObj('tr_proveedor').style.display='';" value="1" checked="CHECKED"/>
	    Prooveedor</label>
	    &nbsp;&nbsp;
	    <label>
          <input name="tesoreria_cheque_manual_pr_radio" type="radio" id="tesoreria_cheque_manual_pr_radio2"  onclick="getObj('tr_empleado').style.display=''; getObj('tr_proveedor').style.display='none';" value="0" />
      Empleado</label></br>
      <input type="hidden" name="tesoreria_cheque_manual_pr_op_oculto" id="tesoreria_cheque_manual_pr_op_oculto" value="1" /></td>
    <tr id="tr_proveedor">
		<th>Proveedor</th>
		  <td>
				<ul class="input_con_emergente">
				<li>
				  <input name="tesoreria_cheque_manual_pr_proveedor_codigo" type="text" id="tesoreria_cheque_manual_pr_proveedor_codigo"  maxlength="4"
				onchange="consulta_automatica_proveedor_manual" onclick="consulta_automatica_proveedor_manual"
				message="Introduzca un Codigo para el proveedor."  size="5"
				/>
				  <input name="tesoreria_cheque_manual_pr_proveedor_nombre" type="text" id="tesoreria_cheque_manual_pr_proveedor_nombre" size="45" maxlength="60" readonly
				message="Introduzca el nombre del Proveedor." />
				<input type="hidden" name="tesoreria_cheque_manual_pr_proveedor_id" id="tesoreria_cheque_manual_pr_proveedor_id" readonly />
				<input type="hidden" name="tesoreria_cheque_manual_pr_proveedor_rif" id="tesoreria_cheque_manual_pr_proveedor_rif" readonly />
				</li> 
					<li id="tesoreria_cheque_manual_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
	  </ul>				</td>		
	</tr>
    <tr id="tr_empleado" style="display:none">
      <th>Empleado</th>
      <td >		<ul class="input_con_emergente">
	  <li><input name="tesoreria_cheque_manual_pr_empleado_codigo" type="text" id="tesoreria_cheque_manual_pr_empleado_codigo"
				onchange="consulta_automatica_empleado_manual" onclick="consulta_automatica_empleado_manual"  size="5"  maxlength="4" 
				message="Introduzca un Codigo para el Empleado."
				/>
	    <input name="tesoreria_cheque_manual_pr_empleado_nombre" type="text" id="tesoreria_cheque_manual_pr_empleado_nombre" size="45" maxlength="60"
				message="Introduzca el nombre del Empleado." />
		  <label>
		    <input type="hidden" name="textprue" id="textprue" />
		    </label>
	      <input type="hidden" name="textprue2" id="textprue2" />
	      <input type="hidden" name="textprue3" id="textprue3" />
	  </li> 
	  		<li id="tesoreria_cheque_manual_db_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>
    </tr>
	<tr>
		 <th>N&ordm; Pre-cheque:</th> 
		 
		  <td>
		  		<ul class="input_con_emergente">
				<li>
				  <input type="text" name="tesoreria_cheque_manual_db_n_precheque" id="tesoreria_cheque_manual_db_n_precheque"   size="6"    maxlength="6"  disabled="disabled" onchange="consulta_automatica_precheque_codigo_manual" onclick="consulta_automatica_precheque_codigo_manual"/>
				</li> 
					<li id="tesoreria_cheque_manual_db_btn_consultar_precheque" class="btn_consulta_emergente"></li>
				</ul>	  </td>				 
   </tr>
   <tr>
		<th>ITF:</th>
	    <td><label>
	    <input type="checkbox" name="tesoreria_cheque_manual_db_itf" value="checkbox" />
		<input type="hidden" id="tesoreria_cheque_manual_db_itf_estatus" name="tesoreria_cheque_manual_db_itf_estatus"  value="0" />
	 <b> Incluir Impuesto a las transacciones financieras</b> </label></td>
	</tr>
	<tr>
		<th>Concepto:</th>
	    <td>
	<textarea name="tesoreria_cheque_manual_db_concepto" cols="65" rows="2"  id="tesoreria_cheque_manual_db_concepto" 
				message="Introduzca el concepto del cheque"
		></textarea>	</tr>
	<tr>	<th colspan="3">&nbsp;&nbsp;Cheque No endosable: 
		<input name="tesoreria_cheque_manual_pr_endosable" id="tesoreria_cheque_manual_pr_endosable" type="checkbox" value="" checked="checked" />
		
		<input type="hidden" name="tesoreria_cheque_manual_db_endosable_oculto" id="tesoreria_cheque_manual_db_endosable_oculto"  value="1"/>
		<label>&nbsp;&nbsp;&nbsp;&nbsp;Caduca a:
		<select name="tesoreria_cheque_manual_pr_caducidad" id="tesoreria_cheque_manual_pr_caducidad">
		  <option value="0">No incluir</option>
		  <option value="1">15 DIAS</option>
		  <option value="2">60 DIAS</option>
		  <option value="3" selected="selected" >90 DIAS</option>
		  <option value="4">120 DIAS</option>
        </select>
		</label></th>
    </tr>
	<tr>
	  <th>Base Imponible:</th>
	  <td><input align="right" name="tesoreria_cheque_manual_db_baseimp"  type="text" id="tesoreria_cheque_manual_db_baseimp" readonly onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar_manual,10); restar_manual();" value="0,00" size="16" maxlength="16" message="Introduzca la cantidad para la Base Imponible. " style="text-align:right"/>
      <label>
        <input type="hidden" name="oculto_baseimp" id="oculto_baseimp" />
      </label></td>
    </tr>
	<tr>
	  <th>% ISLR:</th>
	  <td><input align="right" name="tesoreria_cheque_manual_db_islr" type="text" onblur="restar_manual();" id="tesoreria_cheque_manual_db_islr" readonly onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" message="Introduzca el porcentaje de ISRL. " style="text-align:right"/>
      <input type="hidden" name="oculto_islr" id="oculto_islr"/>
      <label>
        <input type="hidden" name="oculto_porislr" id="oculto_porislr" />
		<input type="hidden" name="oculto_total" id="oculto_total" />
      </label></td>
    </tr>
	<tr>
		<th>
			Monto Total:		</th>
		<td>
		<input align="right"  name="tesoreria_cheque_manual_db_monto_pagar" type="text" id="tesoreria_cheque_manual_db_monto_pagar"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right" />
		<input   name="tesoreria_cheque_manual_db_ordenes_pago" type="hidden" id="tesoreria_cheque_manual_db_ordenes_pago" size="16" maxlength="16" />		</td>
	</tr>
	
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>
<iframe id="iframeOculto_manual" name="iframeOculto_manual" style="width:0px; height:0px; border:0px"></iframe>
   