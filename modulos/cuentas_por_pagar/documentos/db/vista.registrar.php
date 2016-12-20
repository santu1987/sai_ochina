<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY tipo_documento_cxp.nombre";
	$rs_tipos_doc =& $conn->Execute($sql);
//	die($sql);
	while (!$rs_tipos_doc->EOF)
	{
		$opt_tipos_doc.="<option value='".$rs_tipos_doc->fields("id_tipo_documento")."' >".$rs_tipos_doc->fields("nombre")."</option>";
		$rs_tipos_doc->MoveNext();
	}
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
if(!$rs_tipos_ant->EOF)
$tipos=$rs_tipos_ant->fields("id_tipo_documento");
else
$tipos=0;
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);

if(!$rs_tipos_fact->EOF)
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
else
$tipos_fact=0;

//	
$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor >= '".date("d-m-Y")."' AND nombre = 'IVA' ORDER BY fecha_valor asc";
$bus =& $conn->Execute($sql_porcen);
if ($bus->fields=="")
   {
		$sql_porcen = "SELECT id_val_impu, porcentaje_impuesto FROM valor_impuesto INNER JOIN impuesto ON valor_impuesto.id_impuesto = impuesto.id_impuesto WHERE fecha_valor <= '".date("d-m-Y")."' AND nombre = 'IVA' ORDER BY fecha_valor desc";
		$bus =& $conn->Execute($sql_porcen);
	}
//$porcentajexx = $bus->fields("porcentaje_impuesto");
//$porcentajexx=number_format( $bus->fields("porcentaje_impuesto"),2,',','.');		
$porcentajexx="0,00";
////------------------------------------------------------------------------
	$dia=date("d");
	$mes=date("m")+1;
	$ayo=date("Y");
if($mes>=12)
	{
	$mes="01";
	$ayo=date("Y")+1;
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
</head>
<!--/
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
--><script type="text/javascript" language="JavaScript">   
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
//----------------- funcion para crear las pestañas---------------------////
$(function() {
    $('#pestana_doc').tabs();
 });
////------------------------ fin de funcion crear pestañas bienes ----------------/////
function redondear(cantidad, decimales) {
var cantidad = parseFloat(cantidad);
var decimales = parseFloat(decimales);
decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);0
} 
//
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
//----------------------------------------------------------------------------------------------------
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
function restar()
{
//alert("entro2");
//verificar_mont();
//validando q no qeden campos en cero
if(getObj('cuentas_por_pagar_db_ret_iva').value=="")getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
if(getObj('cuentas_por_pagar_db_islr').value=="")getObj('cuentas_por_pagar_db_islr').value="0,00";
if(getObj('cuentas_por_pagar_db_ret_extra').value=="")getObj('cuentas_por_pagar_db_ret_extra').value="0,00";
if(getObj('cuentas_por_pagar_db_ret_extra2').value=="")getObj('cuentas_por_pagar_db_ret_extra2').value="0,00";
//verificando si es anticipo y si no excede el monto del compromiso //
	if((getObj('valor_tipo_doc').value=='anticipo')&&(getObj('valor_anticipo').value!='1'))
		{
			///alert("entro");
			monto_ant=getObj('cuentas_por_pagar_db_monto_ant').value.float();
			porcent=getObj('valor_porcentaje_compromiso').value;
			//alert(porcent);
			amorta=(monto_ant*porcent)/100;
			amorta2=amorta.currency(2,',','.');
		//	alert(amorta2);
			getObj('cuentas_por_pagar_amortizacion').value=amorta2;
			monto3=monto_ant-amorta;
			monto=redondear(monto3,2);
			mont=monto.currency(2,',','.');
			getObj('cuentas_por_pagar_db_monto_bruto').value=mont;
			getObj('cuentas_por_pagar_db_base_imponible').value=mont;
			base=monto_ant;
		}
		else
		{
		monto=getObj('cuentas_por_pagar_db_monto_bruto').value.float();
		base=monto;
		getObj('valor_anticipo').value=0;
		/*if(getObj('valor_tipo_doc').value=='anticipo')
		base=getObj('cuentas_por_pagar_db_monto_ant').value.float();
		else
		{*/
			if(getObj('valor_bi1').value=='0')
			{
				base=monto;
				getObj('cuentas_por_pagar_db_base_imponible').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
				base2=getObj('cuentas_por_pagar_db_base_imponible2').value;
				
			}
			else
			if(getObj('valor_bi1').value!='0')
			{
				base=getObj('cuentas_por_pagar_db_base_imponible').value.float();
				base2=getObj('cuentas_por_pagar_db_base_imponible2').value.float();
			}	
		}	
		iva=getObj('cuentas_por_pagar_db_iva').value.float();
		iva2=getObj('cuentas_por_pagar_db_iva2').value.float();
		ret_iva=getObj('cuentas_por_pagar_db_ret_iva').value.float();
		ret_iva2=getObj('cuentas_por_pagar_db_ret_iva2').value.float();
		ret_islr=getObj('cuentas_por_pagar_db_islr').value.float();
		sustraendo=getObj('cuentas_por_pagar_db_monto_sust').value.float();
		if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)
		//--- calculo si es un tipo de documento factura---//
		{
		if((iva2!='0,00')&&(getObj('valor_bi2').value==1))
				{
					p_iva2=base2*(iva2/100);
					por_iva2=p_iva2.currency(2,',','.');
					getObj('cuentas_por_pagar_db_monto_iva2').value=por_iva2;				

				}/*else
				p_iva2=0;*/
		//si es factura con anticpo
			if(getObj('valor_tipo_doc').value=='anticipo')
			{
					if(getObj('opcion_iva').value!='0')
					{
					base=monto;
					}else
					base=monto_ant;
					/*if((getObj('cuentas_por_pagar_db_restante').value)<(getObj('cuentas_por_pagar_db_monto_ant').value))
					{
						getObj('cuentas_por_pagar_db_monto_ant').value="0,00"
						as=getObj('cuentas_por_pagar_db_monto_ant').value;
						alert(as);
					}*/
			}//si es factura normal
				p_iva=base*(iva/100);
				por_iva=p_iva.currency(2,',','.');
				//alert(iva2);
		if((iva2!='0,00')&&(getObj('valor_bi2').value==1))
				{
				//getObj('cuentas_por_pagar_db_monto_iva').value=por_iva;
				p_ret_iva2=(p_iva2*ret_iva2)/100;
				p_ret_iva_mostrar2=p_ret_iva2.currency(2,',','.');
				//retencion iva
				//alert(p_ret_iva_mostrar2);
				

				getObj('cuentas_por_pagar_db_monto_ret_iva2').value=p_ret_iva_mostrar2;
				}				//porcentaje del iva
				//alert(p_iva);
				getObj('cuentas_por_pagar_db_monto_iva').value=por_iva;
				p_ret_iva=p_iva*(ret_iva/100);
				p_ret_iva_mostrar=p_ret_iva.currency(2,',','.');
				//retencion iva
				getObj('cuentas_por_pagar_db_monto_ret_iva').value=p_ret_iva_mostrar;
		
		iva_total=p_iva-p_ret_iva;
		if((iva2!='0,00')&&(getObj('valor_bi2').value==1))
		{
			iva_total=p_iva-(p_ret_iva+p_ret_iva2);
									

			}
		if(getObj('valor_tipo_doc').value=='anticipo')p_islr=base*(ret_islr/100);
				else
				if(getObj('valor_tipo_doc').value!='anticipo')p_islr=monto*(ret_islr/100);
				p_islr=p_islr-sustraendo;
				p_islr_mostrar=p_islr.currency(2,',','.');
				//retencion islr
						getObj('cuentas_por_pagar_db_monto_ret_islr').value=p_islr_mostrar;
				retenciones=p_islr+p_ret_iva;
						//+p_ret_iva2;
				//if(getObj('valor_anticipo').value==0)
				subtotal=(monto+p_iva);
				if((iva2!='0,00')&&(getObj('valor_bi2').value==1))
				{
					//suma de retenciones y subtotal
				retenciones=p_islr+p_ret_iva+p_ret_iva2;
				//if(getObj('valor_anticipo').value==0)
				subtotal=(monto+p_iva+p_iva2);
		//alert(iva2);
				}
				/*else
				if(getObj('valor_anticipo').value==1)
				{
					amortizar=getObj('cuentas_por_pagar_amortizacion').value.float();
					subtotal=(monto-amortizar)+(p_iva);
				}*/
				total=subtotal;
				//alert(total);
				subtotal=redondear(subtotal,2);
				sub_total=subtotal.currency(2,',','.');
				// subtotal del documento
						getObj('cuentas_por_pagar_db_sub_total').value=sub_total;
				//asiganado valor al monto a integrar contablemente		
						/*if(getObj('valor_tipo_doc').value=='anticipo')
						getObj('cuentas_por_pagar_integracion_monto').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
						else*/
						getObj('cuentas_por_pagar_integracion_monto').value=sub_total;
				if((getObj('valor_tipo_doc').value=='anticipo')&&(getObj('valor_anticipo').value!=''))
					{
		               	monto_inte=getObj('cuentas_por_pagar_db_monto_ant').value.float();
					    ret_iva_inte=getObj('cuentas_por_pagar_db_monto_ret_iva').value.float();
						suma_inte=monto_inte+ret_iva_inte;
						suma_inte_d=redondear(suma_inte,2);
						suma_inte_de=suma_inte_d.currency(2,',','.');
						getObj('cuentas_por_pagar_integracion_monto').value=suma_inte_de;		
					}
					total=total-retenciones;
					//alert(total);
					//alert(retenciones);
						total22=redondear(total,2);
						total22=total.currency(2,',','.');
				getObj('cuentas_por_pagar_db_monto_ret').value=total22;
				//en caso de q sea la retencion 1
					if(getObj('valor_ret_ex').value==1)
					{
						//if(getObj('cuentas_por_pagar_db_ret_e1').value!="0,00")
						//{
							ret_valor1=getObj('cuentas_por_pagar_db_ret_e1').value.float();
							if(getObj('valor_biex1').value==1)
							monto_p_porc=base;
							else
							monto_p_porc=monto;
							//alert(monto_p_porc);
							//alert(getObj('valor_biex1').value);
								//sin porcentaje
								 if(getObj('cuentas_por_pagar_db_ret_e1').value=='0,00')
										ret1=getObj('cuentas_por_pagar_db_ret_extra').value.float();
								else
								//con porcentaje
								if(getObj('cuentas_por_pagar_db_ret_e1').value!='0,00')
								{
										ret1=monto_p_porc*(ret_valor1/100);
										ret1_m=ret1.currency(2,',','.');
										getObj('cuentas_por_pagar_db_ret_extra').value=ret1_m;
								}		
								total=total-ret1;
						//}	
					}//retencion 2
					if(getObj('valor_ret_ex2').value==1)
					{
						//if(getObj('cuentas_por_pagar_db_ret_e2').value!="0,00")
					//	{
							ret_valor2=getObj('cuentas_por_pagar_db_ret_e2').value.float();
							if(getObj('valor_biex2').value==1)
							monto_p_porc=base;
							else
							monto_p_porc=monto;
							//sin porcentaje
							 if(getObj('cuentas_por_pagar_db_ret_e2').value=='0,00')	
								ret2=getObj('cuentas_por_pagar_db_ret_extra2').value.float();
							else	
							//con porcentaje
							if(getObj('cuentas_por_pagar_db_ret_e2').value!='0,00')
							{	
								ret2=monto_p_porc*(ret_valor2/100);
								ret2_m=ret2.currency(2,',','.');
								getObj('cuentas_por_pagar_db_ret_extra2').value=ret2_m;						
							}		
							total=total-ret2;
						//}	
					}
				total_a=total.currency(2,',','.');
				getObj('cuentas_por_pagar_db_monto_neto').value=total_a;
		}else
		{restar_anticipo();
			//	amortizar=getObj('cuentas_por_pagar_amortizacion').value.float();
				//gmonto2=getObj('cuentas_por_pagar_db_monto_bruto').value.float();
				//subtotal=monto2-amortizar;
				//subtotal=subtotal.currency(2,',','.');
					//	getObj('cuentas_por_pagar_db_sub_total').value=subtotal;
					//g	getObj('cuentas_por_pagar_db_monto_ret').value=monto2;
					//g	getObj('cuentas_por_pagar_integracion_monto').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
		
			}
		//
	/*if(getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)
	{
		if(getObj('valor_tipo_doc').value!="anticipo")
		{
			restar_anticipo();
		}
	}	*/

}
$("#cuentas_por_pagar_db_documentos_btn_abrir").click(function() {
getObj('cuentas_por_pagar_db_numero_documento').disabled='';
getObj('cuentas_por_pagar_db_tipo_documento').disabled='';
if(getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value!="")
{
///////////////////////////////////////////////////
setBarraEstado(mensaje[esperando_respuesta]);
						$.ajax (
						{
							//url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_documento2.php',
							//modificado:19/09/2012  por cambios peddos segun orden 
							url:'modulos/cuentas_por_pagar/documentos/db/sql_abrir_factura.php',
							data:dataForm('form_cuentas_por_pagar_db_documentos'),
							type:'POST',
							cache: false,
							success: function(html)
							{
							recordset=html;
							recordset = recordset.split("*");
							//alert(html);
								if (recordset[0]=="Actualizado")
								{
									abrir_comprobante_contable();
									//alert(url)
									//getObj('cuentas_por_pagar_db_compromiso_n').value="";
								}
								else
								{
								//	alert("hola");
									setBarraEstado(recordset[0]);
								}
							}
						});
///////////////////////////////////////////////////						
/*if(((getObj('cuentas_por_pagar_db_monto_neto').value!='0,00')||(getObj('cuentas_por_pagar_db_monto_bruto').value!='0,00'))&&(getObj('cuentas_por_pagar_integracion_cuenta').value!="")&&(getObj('cuentas_por_pagar_integracion_tipo').value!="")&&(getObj('cuentas_por_pagar_integracion_monto').value!='0,00'))
{*/
	/*setBarraEstado(mensaje[esperando_respuesta]);
	
		$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql_abrir_comprobante.php',
			data:dataForm('form_cuentas_por_pagar_db_documentos'),
			type:'POST',
			cache: false,
			success: function(html)
			{alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO ABIERTO</p></div>",true,true);
					getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';
					getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='none';
					getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
					getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';
					getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
					getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value='1';
					getObj('cuentas_por_pagar_numero_comprobante_integracion').value="";
				//	jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php'}).trigger("reloadGrid");
					url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php';
					jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
					//	alert(url);


				}
				else if (html=="NoActualizo")
				{//GIANNI
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION</p></div>",true,true);
				}else
				if(html=="no_documento")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION CON ESTE DOCUMENTO</p></div>",true,true);
				}else
				if(html=="tiene_cheque")
				{
							setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION, EL DOCUMENTO CUENTA CON CHEQUES CREADOS </p></div>",true,true);

				}else
				if(html=="tiene_orden")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION, EL DOCUMENTO CUENTA CON ORDEN DE PAGO </p></div>",true,true);
				}else
				
				if(html=="documento_integrado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION, EL DOCUMENTO YA FUE PASADO A CONTABILIDAD </p></div>",true,true);
				}
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
	//}*/
/////////////////////////////////////////////////	
}//fin de if(getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value!="")
else
{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />No se puede abrir documento sin n&ordm; comprobante</p></div>",true,true);
}
});
/////////////////////////////////////////////
function abrir_comprobante_presupuestario()
{
	$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql_abrir_presupuesto.php',
			data:dataForm('form_cuentas_por_pagar_db_documentos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Abierto")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO ABIERTO</p></div>",true,true);
					getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';
					getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='none';
					getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
					getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';
					getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
					getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value='1';
					//getObj('cuentas_por_pagar_numero_comprobante_integracion').value="";
				//	jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php'}).trigger("reloadGrid");
				consultar_seleccion();
					url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php';
					jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
				}
			
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});

}
function consultar_seleccion()
{
	$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql_consultar_se.php',
			data:dataForm('form_cuentas_por_pagar_db_documentos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//alert(html);
				getObj('cuentas_por_pagar_db_facturas_lista').value=html;
				//
			}
		});	
}
/////////////////////////////////////////////
function abrir_comprobante_contable()
{
$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql_abrir_comprobante.php',
			data:dataForm('form_cuentas_por_pagar_db_documentos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Abierto")
				{
					abrir_comprobante_presupuestario();
				}
			
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
}
/////////////////////////////////////////////
function cerrar_presupuesto()
{
	//funcion que genera, el cierre presupuesto
	$.ajax ({
							//url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_documento2.php',
							//modificado:19/09/2012  por cambios pedidos segun orden-
							//se procesa el cierre con getObj(cuentas_por_pagar_db_facturas_lista2).value..
							url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_presupuesto.php',
							data:dataForm('form_cuentas_por_pagar_db_documentos'),
							type:'POST',
							cache: false,
							success: function(html)
							{
							recordset=html;
							recordset = recordset.split("*");
							//alert(html);
								if (recordset[0]=="cerrado")
								{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png'/>DOCUMENTO CERRADO</p></div>",true,true);
									getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';
									getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value='2';
									getObj('cuentas_por_pagar_db_monto_bruto').disabled="true";
								//	jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value;
								//	alert(url);
									jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
					//	alert(url)
						//getObj('cuentas_por_pagar_db_compromiso_n').value="";
								}
								else
								{
								//	alert("hola");
									setBarraEstado(recordset[0]);
								}
							}
						});
	//
	
}
///////////////////////////////////////////
function cerrar_comprobante_contable()
{
//funcion que ciera el comprobante manual creado
$.ajax (
						{
							//url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_documento2.php',
							//modificado:19/09/2012  por cambios peddos segun orden 
							url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_comprobante.php',
							data:dataForm('form_cuentas_por_pagar_db_documentos'),
							type:'POST',
							cache: false,
							success: function(html)
							{
							recordset=html;
							recordset = recordset.split("*");
						//	alert(html);
								if (recordset[0]=="cerrado")
								{
									cerrar_presupuesto();
									//alert(url)
									//getObj('cuentas_por_pagar_db_compromiso_n').value="";
								}
								else
								{
								//	alert("hola");
									setBarraEstado(recordset[0]);
								}
							}
						});
//
}
$("#cuentas_por_pagar_db_documentos_btn_cerrar").click(function() {
getObj('cuentas_por_pagar_db_monto_bruto').disabled="";
getObj('cuentas_por_pagar_db_numero_documento').disabled='';
getObj('cuentas_por_pagar_db_tipo_documento').disabled='';
/*if(((getObj('cuentas_por_pagar_db_monto_neto').value!='0,00')||(getObj('cuentas_por_pagar_db_monto_bruto').value!='0,00'))&&(getObj('cuentas_por_pagar_integracion_cuenta').value!="")&&(getObj('cuentas_por_pagar_integracion_tipo').value!="")&&(getObj('cuentas_por_pagar_integracion_monto').value!='0,00'))
{
	*/
if((getObj('cxp_comp_pr_dif').value=="0,00")&&(getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value!="")&&(getObj('cuentas_por_pagar_numero_comprobante_integracion').value!=""))
{			
						
						setBarraEstado(mensaje[esperando_respuesta]);
						$.ajax (
						{
							
							//url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_documento2.php',
							//modificado:19/09/2012  por cambios peddos segun orden 
							url:'modulos/cuentas_por_pagar/documentos/db/sql_cerrar_factura.php',
							data:dataForm('form_cuentas_por_pagar_db_documentos'),
							type:'POST',
							cache: false,
							success: function(html)
							{
							recordset=html;
							recordset = recordset.split("*");
							///alert(html);
								if (recordset[0]=="cerrado")
								{
									cerrar_comprobante_contable();
									//alert(url)
									//getObj('cuentas_por_pagar_db_compromiso_n').value="";
								}
								else
								{
								//	alert("hola");
									setBarraEstado(recordset[0]);
								}
							}
						
						});
}
else
{
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION </p></div>",true,true);
}
/*
}
else
{
setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUDO REALIZAR LA OPERACION, DEBE ELABORAR EL COMPROBANTE PARA PODER CERRARLO </p></div>",true,true);
}*/					
				});

function actualizar_cxp()
{
		//alert('entro');
valor_compromiso =valor(getObj('cuentas_por_pagar_db_total').value);
valor_anticipo =valor(getObj('cuentas_por_pagar_db_monto_bruto').value);
//alert(valor_compromiso);
//alert(valor_anticipo);
/*if(valor_anticipo>valor_compromiso)
{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />SE EXCEDE EL MONTO COMPROMETIDO POR PARTIDA</p></div>",true,true);
}
else
{
*/		getObj('cuentas_por_pagar_db_tipo_documento').disabled="";
		getObj('cuentas_por_pagar_db_monto_bruto').disabled="";		
		
			
		if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!="2")
		{	
				setBarraEstado(mensaje[esperando_respuesta]);
						if($('#').jVal())
						{
							$.ajax (
							{
								url: "modulos/cuentas_por_pagar/documentos/db/sql.actualizar_documentos.php",
								data:dataForm('form_cuentas_por_pagar_db_documentos'),
								type:'POST',
								cache: false,
								success: function(html)
								{
									if (html=="Actualizado")
									{		//alert('entro');
											getObj('cuentas_por_pagar_db_monto_bruto').disabled="true";		
		
										/*if(getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)	
										{*/
											setBarraEstado(mensaje[actualizacion_exitosa],true,true);
									//	}
										setBarraEstado("");
																			/*getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
										getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
										getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='none';	
										getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='none';
										getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='';
										clearForm('form_cuentas_por_pagar_db_documentos');*/
										////*prueba*///
										//getObj('tr_comprometido').style.display=''
										//getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
										//getObj('cuentas_por_pagar_db_op_comprometido_no').checked="";
										//getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
										//*pruebas///*//
										/*getObj('cuentas_por_pagar_db_monto_bruto').value="0,00";
										getObj('cuentas_por_pagar_db_iva').value="<?php echo($porcentajexx); ?>";
										getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
										getObj('cuentas_por_pagar_db_islr').value="0,00";
										getObj('cuentas_por_pagar_db_base_imponible').value="0,00";
										getObj('cuentas_por_pagar_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
										getObj('cuentas_por_pagar_db_ayo').value="<?= date("Y"); ?>";
										getObj('cuentas_por_pagar_db_tipo_documento').value='0';
										getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value="1";
										getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTO";
										getObj('cuentas_por_pagar_db_op_oculto').value='1';
										getObj('cuentas_por_pagar_db_radio1').checked="checked";
										getObj('tr_empleado_cxp').style.display='none'; getObj('tr_proveedor_cxp').style.display='';
										limpiar_facturas();*/
										//pruebas
																	//getObj('cuentas_por_pagar_db_compromiso_n').disabled='';
									}
									else if (html=="NoActualizo")
									{//GIANNI
										setBarraEstado(mensaje[registro_existe],true,true);
										}
									else
									if(html=="No_existe_compromiso")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NUMERO DE COMPROMISO NO EXISTE</p></div>",true,true);
					
									}else
									if(html=="monto_superior")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL MONTO ES MAYOR AL MONTO DEL COMPROMISO</p></div>",true,true);
									}
									else
									if(html=="cerrado")
									{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);
									}	
									else
									if(html=="documento_orden")
									{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO CON ORDEN DE PAGO NO PUEDE SER MODIFICADO</p></div>",true,true);
									}
									else
									if(html=="documento_comp")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL DOCUMENTO NO SE LE PUEDE CAMBIAR EL NUMERO DE COMPROMISO</p></div>",true,true);
									}
									else
										if (html=="cerrados")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />MÓDULO CERRADO</p></div>",true,true);
										}	
									else
									{
										alert(html);
										setBarraEstado(html);
									}
								}
							});
					//	}
		}
		}
	else
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUEDE MODIFICAR EL DOCUMENTO</p></div>",true,true);


}
function actualizar_cxp2()
{
	//alert("entro_actual");
		getObj('cuentas_por_pagar_db_tipo_documento').disabled="";
		getObj('cuentas_por_pagar_db_monto_bruto').disabled="";	
		//alert(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value);
		if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!="2")
		{	
		setBarraEstado(mensaje[esperando_respuesta]);
		
						if($('#').jVal())
						{
							$.ajax (
							{
								url: "modulos/cuentas_por_pagar/documentos/db/sql.actualizar_documentos.php",
								data:dataForm('form_cuentas_por_pagar_db_documentos'),
								type:'POST',
								cache: false,
								success: function(html)
								{
									//alert(html);
									if (html=="Actualizado")
									{
										//setBarraEstado(mensaje[actualizacion_exitosa],true,true);
										setBarraEstado("");
										getObj('partida_comp').value="";
										getObj('monto_causar_comp').value="0,00";
										getObj('partida_celdas').style.display='';
										getObj('celdas_2').style.display='';
										getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';
										getObj('monto_ant_oculto').value=getObj('cuentas_por_pagar_db_monto_ant').value;	
										getObj('monto_bruto_oculto').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
										jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('fecha_oculta').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('fecha_oculta').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
//alert(url);
	jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
						//alert(url);
						
									}
									else if (html=="NoActualizo")
									{//GIANNI
										setBarraEstado(mensaje[registro_existe],true,true);
										getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;	
										getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										setTimeout(restar,10); restar();
									}
									else
									if(html=="No_existe_compromiso")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NUMERO DE COMPROMISO NO EXISTE</p></div>",true,true);
										getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
										getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										setTimeout(restar,10); restar();
									}else
									if(html=="monto_superior")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL MONTO ES MAYOR AL MONTO DEL COMPROMISO</p></div>",true,true);
										/*getObj('monto_oculto').value=recordset;
										rest3=getObj('cuentas_por_pagar_db_monto_bruto').value.float()-getObj('monto_causar_comp').value.float();
										rest3=rest3.currency(2,',','.');
									//	alert(rest3);	
										getObj('cuentas_por_pagar_db_monto_bruto').value=rest3;
										getObj('monto_oculto').value=0;*/
											getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
											getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										    setTimeout(restar,10); restar();
										setTimeout(restar,10); restar();
									}
									else
									if(html=="cerrado")
									{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);
											getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
											getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										    setTimeout(restar,10); restar();
									}	
									else
									if(html=="documento_orden")
									{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO CON ORDEN DE PAGO NO PUEDE SER MODIFICADO</p></div>",true,true);
											getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
											getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										    setTimeout(restar,10); restar();
									}
									else
									if(html=="documento_comp")
									{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL DOCUMENTO NO SE LE PUEDE CAMBIAR EL NUMERO DE COMPROMISO</p></div>",true,true);
										getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
										getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										setTimeout(restar,10); restar();
									}
									else
										if (html=="cerrados")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />MÓDULO CERRADO</p></div>",true,true);
											getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
											getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										    setTimeout(restar,10); restar();
										}	
									else
										if(html=="cargado_otra_fat")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />LA SUMATORIA DE LOS MONTOS EXCEDE AL COMPROMISO</p></div>",true,true);
											getObj('cuentas_por_pagar_db_monto_ant').value=getObj('monto_ant_oculto').value;
											getObj('cuentas_por_pagar_db_monto_bruto').value=getObj('monto_bruto_oculto').value;
										    setTimeout(restar,10); restar();
										}	
									else
									{
										alert(html);
										setBarraEstado(html);
									}
								}
							});
						}
		
		}
		else
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE PUEDE MODIFICAR EL DOCUMENTO</p></div>",true,true);


}
///actualizar en el caso de que no sea anticipo
function actualizar_opcion1()
{
		//alert("entro");
		/*var rest=getObj('cuentas_por_pagar_db_monto_bruto').value;
						valores=getObj('monto_causar_comp').value;
						rest=rest.float()+valores.float();
						rest2=rest.currency(2,',','.');
						getObj('cuentas_por_pagar_db_monto_bruto').value=rest2;
						//alert(rest2);
						setTimeout(restar,10); restar();*/
						/*guardar*/
						jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('cuentas_por_pagar_db_fecha_f').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
						url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('cuentas_por_pagar_db_fecha_f').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
						//alert(url);
						//////////paso2:consultar si existe
								$.ajax({
								url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_fact_doc_det.php",
								data:dataForm('form_cuentas_por_pagar_db_documentos'), 
								type:'POST',
								cache: false,
								 success:function(html)
								 {
								   
									var recordset=html;	
									alert(html);
									if(recordset!="")
									{	
									//alert("entro2");
										valores=getObj('monto_causar_comp').value;
										bruto=getObj('cuentas_por_pagar_db_monto_bruto').value;
									//	alert(bruto);
									// monto oculto se llena al momento de actualizar para dismunuir y aumentar el monto de la factura al actualizarla
										getObj('monto_oculto').value=recordset;
										rest3=getObj('cuentas_por_pagar_db_monto_bruto').value.float()-getObj('monto_oculto').value.float();
 									    rest3=rest3.currency(2,',','.');
									// proceso para doble iva 03 marzo 2012 en caso q sea doble iva no se pueden hacer facturas prorateadas es por ello q rest3=0;
										if((getObj('valor_bi1').value=="1")&&(getObj('valor_bi2').value=="1"))
													{
														valores=getObj("monto_causar_comp_v").value;
														rest3=0,00;
													}
													
										//	
										//alert(rest3);	
										getObj('cuentas_por_pagar_db_monto_bruto').value=rest3;
										setTimeout(restar,10); restar();
									/////////////////////////luego le sumo
									var rest=getObj('cuentas_por_pagar_db_monto_bruto').value;
									
									rest=rest.float()+valores.float();
									/////////////////////verificando que rest no exceda al monto total del renglon//////////////////////////
								//	valor_causar_comp=getObj('monto_causar_comp').value.float();
									//valor maximo
										/*valor_maximo = getObj("valor_maximo").value;
										valor_maximo= valor_maximo.replace('.','');
										valor_maximo= eval(valor_maximo.replace(',','.'));*/
										valor_maximo =redondear(valor(getObj("valor_maximo").value),2);
									// valor facturado
										/*valor_facturado = getObj("valor_fact").value;
										valor_facturado= valor_facturado.replace('.','');
										valor_facturado= eval(valor_facturado.replace(',','.'));*/
										valor_facturado =redondear(valor(getObj("valor_fact").value),2);
										suma=eval(rest+valor_facturado);
										/*//alert(suma);
										alert(valor_facturado);
										alert(suma);
										alert(valor_maximo);*/
										/*if(suma>valor_maximo)
										{
										setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />SE EXCEDE EL MONTO COMPROMETIDO POR PARTIDA</p></div>",true,true);
										getObj('cuentas_por_pagar_db_monto_bruto').value=bruto;
										//alert(rest2);
										setTimeout(restar,10); restar();
											
										}else
										{*/
										//////////////////////////////////////////////
										rest2=rest.currency(2,',','.');
										getObj('cuentas_por_pagar_db_monto_bruto').value=rest2;
									//	alert(rest2);
										setTimeout(restar,10); restar();
										/////////////////////////////////////////////////////////	
											actualizar_cxp2();
										//}	
														
									}else
									{						
											//alert("entro2");
											actualizar_cxp2();
									}	
								}							
							});	

}
////actualizar en el caso q sea anticipo
function actualizar_opcion_anticipo()
{
		var rest=getObj('cuentas_por_pagar_db_monto_ant').value;
						valores=getObj('monto_causar_comp').value;
						rest=rest.float()+valores.float();
						rest2=rest.currency(2,',','.');
						getObj('cuentas_por_pagar_db_monto_ant').value=rest2;
						setTimeout(restar,10); restar();
						/*guardar*/
						jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('cuentas_por_pagar_db_fecha_f').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
						url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('cuentas_por_pagar_db_fecha_f').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
						//alert(url);
						//////////paso2:consultar si existe
								$.ajax({
								url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_fact_doc_det.php",
								data:dataForm('form_cuentas_por_pagar_db_documentos'), 
								type:'POST',
								cache: false,
								 success:function(html)
								 {
								   
									var recordset=html;	
									//alert(html);
									if(recordset!="")
									{	
										getObj('monto_oculto').value=recordset;
										rest3=getObj('cuentas_por_pagar_db_monto_ant').value.float()-getObj('monto_oculto').value.float();
										rest3=rest3.currency(2,',','.');
										//alert(rest3);	
										getObj('cuentas_por_pagar_db_monto_ant').value=rest3;
										/*setTimeout(restar,10);*/
										restar();
										actualizar_cxp2();
									}else
									{						
										actualizar_cxp2();
									}	
								}							
							});	
									getObj('cuentas_por_pagar_db_monto_ant').disabled="";


}
$("#cuentas_por_pagar_documentos_db_btn_actualizar").click(function() {

				if((getObj('cuentas_por_pagar_db_numero_documento').value!="")&&(getObj('cuentas_por_pagar_db_numero_control').value!=""))
				{
				getObj('cuentas_por_pagar_db_numero_documento').disabled='';
				getObj('cuentas_por_pagar_db_monto_bruto').disabled='';
						if(((getObj('monto_causar_comp').value=="")||(getObj('monto_causar_comp').value=="0,00"))||(getObj('cuentas_por_pagar_activo_varios').value!=1))
								{
									getObj('monto_causar_comp').value="";
									getObj('partida_comp').value="";
									actualizar_cxp();
								}else
								{//alert("entro1");	
								  if(getObj('partida_comp').value!="")
									{
										
													
										causadoss=parseFloat(monto_causar_comp);
										comprometido=getObj('monto_causar_comp2').value;
										
										/*if(causadoss<=comprometido.float())
										{*/
												//if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)
												if((getObj('valor_tipo_doc').value=='anticipo')&&(getObj('valor_anticipo').value!='1'))
												{
													//alert("entro");
													actualizar_opcion_anticipo();
												}
												else
												{
													actualizar_opcion1();
												}
										/*}else
										alert("Monto superior");*/
									}
								}	
				}
});				
////boton actualizar partidas m,onto de causado
/*$("#cxp_pr_btn_anadir").click(function() {
//cambio_vectores();
//alert("entro");
var causes=0;
if(getObj('vector_causado').value!="")
{
	vector_partida=getObj('vector_partida').value;
	vector_partidas=vector_partida.split(';');
	vector_causado=getObj('vector_causado').value;
	vector_causados=vector_causado.split(';');


						for(its=0;its<vector_partidas.length;its++)
						{
							alert(vector_partidas[its]==getObj('partida_comp').value)
							alert(vector_partidas[its]);
							alert(getObj('partida_comp').value);
							if(vector_partidas[its]==getObj('partida_comp').value)
							{
								alert("entra");
								causes=causes+1;
								getObj('valor_n').value=vector_causados[its];
								vector_causados[its]="";
								vector_partidas[its]=""; 
												
							
							}
						
						}
		getObj('vector_causado').value=vector_causados;
 		getObj('vector_partida').value=vector_partidas;
	
		getObj('vector_causado').value=getObj('vector_causado').value+";"+getObj('monto_causar_comp').value;
		getObj('vector_partida').value=getObj('vector_partida').value+";"+getObj('partida_comp').value;
		/*vector_causado=getObj('vector_causado').value;
		vector_partida=getObj('vector_partida').value;
		vector_causado=vector_causado.replace(",","");
		vector_partida=vector_partida.replace(",","");
		getObj('vector_causado').value=vector_causado;
		getObj('vector_partida').value=vector_partida;*/
/*}
else
if(getObj('vector_causado').value=="")
{
	getObj('vector_causado').value=getObj('monto_causar_comp').value;
	getObj('vector_partida').value=getObj('partida_comp').value;
}

var rest=getObj('cuentas_por_pagar_db_monto_bruto').value;
/*valores=getObj('vector_causado').value;
vector_sumas = valores.split(";");
		for(i=0;i<vector_sumas.length;i++)
		{
			valor2=vector_sumas[i];
			rest=rest.float()+valor2.float();
			
		}*/
/*valores=getObj('monto_causar_comp').value;
		rest=rest.float()+valores.float();
	if(causes!=0)
		{rest=rest-getObj('valor_n').value.float();	
		
		}
		rest2=rest.currency(2,',','.');
		//alert(rest2);	
getObj('cuentas_por_pagar_db_monto_bruto').value=rest2;
							setTimeout(restar,10); restar();
							
							cambio_vectores();


});*/
//
function valor(cadena)
{	
	vueltas=cadena.length;
	co=1;
	while(co<=vueltas)
	{
		cadena= cadena.replace('.','');
		co=co+1;
	}
//	alert(cadena);
		cadena= cadena.replace(',','.');
//	alert(cadena);
	return(cadena);
		
}

function guardar_factura()
{
														/////////////////////////valor compromiso
														/*valor_compromiso =getObj('cuentas_por_pagar_db_restante').value;
														valor_compromiso= valor_compromiso.replace('.','');
														valor_compromiso= eval(valor_compromiso.replace(',','.'));
																
														///////////////////////// valor anticipo
														valor_anticipo = getObj("cuentas_por_pagar_db_monto_bruto").value;
														valor_anticipo= valor_anticipo.replace('.','');
														valor_anticipo= valor_anticipo.replace(',','.');*/
														
													
/*alert(valor_anticipo);
alert(valor_compromiso);

*/
valor_compromiso =valor(getObj('cuentas_por_pagar_db_total').value);
valor_anticipo =valor(getObj('cuentas_por_pagar_db_monto_bruto').value);
//alert(valor_compromiso);
//alert(valor_anticipo);
/*if(valor_anticipo>valor_compromiso)
{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />SE EXCEDE EL MONTO COMPROMETIDO POR PARTIDA</p></div>",true,true);
}
else
{*/
		if(getObj('cuentas_por_pagar_db_tipo_documento').value=='0')
						{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO PUEDE REGISTRAR DOCUMENTOS SIN SELECCIONAR UN TIPO</p></div>",true,true);
						}
						if((getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)&&(getObj('cuentas_por_pagar_db_compromiso_n').value==""))
						{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />LOS ANTICIPOS DEBEN ASOCIARSE A UN NUMERO DE COMPROMISO</p></div>",true,true);
						}
						else
						{
						
									if((getObj('cuentas_por_pagar_db_monto_bruto').value!="0,00")&&(getObj('cuentas_por_pagar_db_tipo_documento').value!='0')&&((getObj('cuentas_por_pagar_db_proveedor_id').value!="")||(getObj('cuentas_por_pagar_db_empleado_codigo').value!="")))
										{		
												
										if ($('#form_cuentas_por_pagar_db_documentos').jVal()){	
													getObj('cuentas_por_pagar_db_monto_bruto').disabled='';					   
														setBarraEstado(mensaje[esperando_respuesta]);
															$.ajax (
															{
																url: "modulos/cuentas_por_pagar/documentos/db/sql.documentos.php",
																data:dataForm('form_cuentas_por_pagar_db_documentos'),
																type:'POST',
																cache: false,
																success: function(html)
																{
																	recordset=html;
																	//alert(recordset[1]);
																	recordset = recordset.split("*");
																	if (recordset[0]=="Registrado")
																	{
																		setBarraEstado("");
																		if(getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)	
																		{
																				setBarraEstado(mensaje[registro_exitoso],true,true);
																				getObj('cuentas_por_pagar_db_monto_bruto').disabled="true";		
				
																		}
																		//
																		//
																		getObj('cuentas_por_pagar_db_id').value=recordset[1];
																		getObj('cuentas_por_pagar_documentos_db_btn_cancelar').style.display='';
																		getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='';
																		getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='';
																		getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';	
																		getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='none';
																		
																		//
																		/*getObj('cuentas_por_pagar_db_iva').value="<?php //echo($porcentajexx); ?>";
																		getObj('cuentas_por_pagar_db_islr').value="0,00";
																		getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
																	//	getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
																		getObj('tr_comprometido').style.display='';
																		getObj('cuentas_por_pagar_db_compromiso_n').value="";
																		getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
																		getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
																		getObj('tr_empleado_cxp').style.display='none'; getObj('tr_proveedor_cxp').style.display='';
																		limpiar_facturas();	*/
																	}
																	else if (recordset[0]=="NoRegistro")
																	{
																		setBarraEstado(mensaje[registro_existe],true,true);
																		borrar_calculadora();
																		/*limpiar_facturas_manual();
																		getObj('cuentas_por_pagar_db_monto_pagar').value="0,00";
																		getObj('cuentas_por_pagar_db_iva').value="0,00";
																		getObj('cuentas_por_pagar_db_islr').value="0,00";
																		getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
													
																		getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
																		getObj('tr_comprometido').style.display='';*/
																	}else
																	if(recordset[0]=="No_existe_compromiso")
																	{
																		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NUNMERO DE COMPROMISO NO EXISTE</p></div>",true,true);
																		borrar_calculadora();
																	}else
																	if(recordset[0]=="monto_superior")
																	{
																		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL MONTO EXCEDE AL MONTO DISPONIBLE</p></div>",true,true);
																		borrar_calculadora();
																	}
																	else
																	if(recordset[0]=="compromiso")
																	{
																		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />MAS DE UNA ANTICIPO NO PUEDE ESTAR ASOCIADO A UN MISMO COMPROMISO</p></div>",true,true);
																		borrar_calculadora();
																	}
																	else
																	if (recordset[0]=="cerrado")
																	{
																		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />MÓDULO CERRADO</p></div>",true,true);
																		borrar_calculadora();
																	}	
																else									
																	{
																		alert(html);
																		setBarraEstado(html);
																		getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';
																		borrar_calculadora();
																	}
																
																}
															});
														}
														//}//else
														//alert("me diste canalla");
														
										}
										///*else
															//	alert("efecto");*/
							}	
	//}// fin de  else
}//fin de funcion
function guardar_factura2()
{
if((getObj('cuentas_por_pagar_db_iva2').value!='0,00')&&(getObj('cuentas_por_pagar_db_iva2').value=='0,00'))
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />error:IVA2 SIN BASE IMP</p></div>",true,true);
	
if(getObj('cuentas_por_pagar_db_tipo_documento').value=='0')
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO PUEDE REGISTRAR DOCUMENTOS SIN SELECCIONAR UN TIPO</p></div>",true,true);
				}
				if((getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)&&(getObj('cuentas_por_pagar_db_compromiso_n').value==""))
				{
						setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />LOS ANTICIPOS DEBEN ASOCIARSE A UN NUMERO DE COMPROMISO</p></div>",true,true);
				}
				else
				{
				
							if((getObj('cuentas_por_pagar_db_monto_bruto').value!="0,00")&&(getObj('cuentas_por_pagar_db_tipo_documento').value!='0')&&((getObj('cuentas_por_pagar_db_proveedor_id').value!="")||(getObj('cuentas_por_pagar_db_empleado_codigo').value!="")))
								{		
										
								if ($('#form_cuentas_por_pagar_db_documentos').jVal()){	
											getObj('cuentas_por_pagar_db_monto_bruto').disabled='';					   
												setBarraEstado(mensaje[esperando_respuesta]);
													$.ajax (
													{
														url: "modulos/cuentas_por_pagar/documentos/db/sql.documentos.php",
														data:dataForm('form_cuentas_por_pagar_db_documentos'),
														type:'POST',
														cache: false,
														success: function(html)
														{
															recordset=html;
															//alert(html);
															recordset = recordset.split("*");
															if (recordset[0]=="Registrado")
															{
     															//setBarraEstado("");
																setBarraEstado(mensaje[registro_exitoso],true,true);
																//
																getObj('cuentas_por_pagar_db_monto_bruto').disabled="true";
																getObj('partida_comp').value="";
																getObj('monto_causar_comp').value="0,00";		
		
																getObj('cuentas_por_pagar_db_id').value=recordset[1];
																getObj('cuentas_por_pagar_documentos_db_btn_cancelar').style.display='';
																getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='';
																getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='';
																getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';	
																getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='none';
																jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('fecha_oculta').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
																url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+getObj('cuentas_por_pagar_db_fecha_f').value+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
																//alert(url);
																getObj('monto_ant_oculto').value=getObj('cuentas_por_pagar_db_monto_ant').value;	
																getObj('monto_bruto_oculto').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
																//alert(url);
																//
																/*getObj('cuentas_por_pagar_db_iva').value="<?php //echo($porcentajexx); ?>";
																getObj('cuentas_por_pagar_db_islr').value="0,00";
																getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
															//	getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
																getObj('tr_comprometido').style.display='';
																getObj('cuentas_por_pagar_db_compromiso_n').value="";
																getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
																getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
																getObj('tr_empleado_cxp').style.display='none'; getObj('tr_proveedor_cxp').style.display='';
																limpiar_facturas();	*/
																jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
						//alert(url);
						
															}
															else if (recordset[0]=="NoRegistro")
															{
																setBarraEstado(mensaje[registro_existe],true,true);
																borrar_calculadora();
																/*limpiar_facturas_manual();
																getObj('cuentas_por_pagar_db_monto_pagar').value="0,00";
																getObj('cuentas_por_pagar_db_iva').value="0,00";
																getObj('cuentas_por_pagar_db_islr').value="0,00";
																getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
											
																getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
																getObj('tr_comprometido').style.display='';*/
													
																}else
															if(recordset[0]=="No_existe_compromiso")
															{
																setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NUNMERO DE COMPROMISO NO EXISTE</p></div>",true,true);
																borrar_calculadora();
							
															}else
															if(recordset[0]=="monto_superior")
															{
																	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL MONTO EXCEDE AL MONTO DISPONIBLE</p></div>",true,true);
																	restr=getObj('cuentas_por_pagar_db_monto_bruto').value.float()-getObj('monto_causar_comp').value.float();		
																	este_valor=restr.currency(2,',','.');
																	getObj('cuentas_por_pagar_db_monto_bruto').value=este_valor;
																	borrar_calculadora();
															}
															else
															if(recordset[0]=="compromiso")
															{
																setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />MAS DE UNA ANTICIPO NO PUEDE ESTAR ASOCIADO A UN MISMO COMPROMISO</p></div>",true,true);
																borrar_calculadora();	
															}
															else
															if (recordset[0]=="cerrado")
															{
																setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />MÓDULO CERRADO</p></div>",true,true);
																borrar_calculadora();
															}	
														else									
															{
																alert(html);
																setBarraEstado(html);
																getObj('tesoreria_cheque_manual_db_endosable_oculto').value='1';
																borrar_calculadora();
															}
														
														}
													});
												}
												//}//else
												//alert("me diste canalla");
												
								}
								///*else
													//	alert("efecto");*/
					}	

}

///////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_documentos_db_btn_iva_borrar").click(function() {
	getObj('iva_multi').value="0,00"
});
///////////////////////////////////////////////////////////////////////////////

$("#cuentas_por_pagar_documentos_db_btn_guardar").click(function() {
		 if((getObj('cuentas_por_pagar_db_numero_documento').value!="")&&(getObj('cuentas_por_pagar_db_numero_control').value!=""))
		{
		
				if ($('#form_cuentas_por_pagar_db_documentos').jVal())
				{			//si no es 
							if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)	
							{	
								if((getObj('monto_causar_comp').value=="0,00")&&(getObj('cuentas_por_pagar_activo_varios').value=='0'))
								{
									guardar_factura();
								}else
								{
									if(getObj('partida_comp').value!="")
									{
										causadoss=parseFloat(getObj('monto_causar_comp').value);
										
										
										comprometido=getObj('comprometido').value;
										
												if((getObj('valor_tipo_doc').value=='anticipo')&&(getObj('valor_anticipo').value!='1'))
												{
													var rest=getObj('cuentas_por_pagar_db_monto_ant').value;
													valores=getObj('monto_causar_comp').value;
													rest=rest.float()+valores.float();
													rest2=rest.currency(2,',','.');
													getObj('cuentas_por_pagar_db_monto_ant').value=rest2;
													//getObj('cuentas_por_pagar_db_base_imponible').value=rest2;
												}
												else
												{
													
														
													//- si es doble iva el monto q va para la suma es uno y el q va para el registro es otro
													if((getObj('valor_bi1').value=="1")&&(getObj('valor_bi2').value=="1"))
													{
														valor_causa=getObj("monto_causar_comp_v").value;
													}
													else
														valor_causa=getObj("monto_causar_comp").value;
													//05/03/2012 se hizo un cambio de variable de $valor_causa=getObj("monto_causar_comp").value;
													
													//valor maximo
														/*valor_maximo = getObj("valor_maximo").value;
														valor_maximo= valor_maximo.replace('.','');
														valor_maximo= eval(valor_maximo.replace(',','.'));*/
														valor_maximo =redondear(valor(getObj("valor_maximo").value),2);
													//monto_causar
														/*monto_causar = getObj("monto_causar_comp").value;
														monto_causar= monto_causar.replace('.','');
														monto_causar= eval(monto_causar.replace(',','.'));*/
														//1- monto_causar =redondear(valor( getObj("monto_causar_comp").value),2);
														monto_causar =redondear(valor(valor_causa),2);

													// valor facturado
														/*valor_facturado = getObj("valor_fact").value;
														valor_facturado= valor_facturado.replace('.','');
														valor_facturado= eval(valor_facturado.replace(',','.'));*/
														valor_facturado =valor(getObj("valor_fact").value);

													suma=eval(parseFloat(monto_causar)+parseFloat(valor_facturado));
														/*alert(monto_causar);
														alert(valor_facturado);
														alert(suma);
														alert(valor_maximo);*/
													/*if(suma>valor_maximo)
													{
														setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />EXCEDE EL MONTO COMPROMETIDO POR PARTIDA</p></div>",true,true);

													}else
													{*/
														var rest=getObj('cuentas_por_pagar_db_monto_bruto').value;
														//valores=getObj('monto_causar_comp').value;
														valores=valor_causa;
														rest=rest.float()+valores.float();
														rest2=rest.currency(2,',','.');
														getObj('cuentas_por_pagar_db_monto_bruto').value=rest2;
													//}
												//	getObj('cuentas_por_pagar_db_base_imponible').value=rest2;
												
												
												}
													
													
													//alert(rest2);
													setTimeout(restar,10); restar();
												
												/*guardar*/
												guardar_factura2();
										/*}else
										alert("Monto superior");*/
									}
									else
									alert("falta la partida");	
										
								
								}		
							}	else//si es anticipo
								{
												guardar_factura();
							
										}					
				}//primer if
		}
});

function cambio_vectores()
{
	var causes=0;var vector_partidas_nuevo;var vector_causados_nuevo;
	vector_partida=getObj('vector_partida').value;
	//vector_partidas=vector_partida.split(';');
	vector_causado=getObj('vector_causado').value;
	//vector_causados=vector_causado.split(';');
						
									vector_partidas_nuevo=vector_partida.replace(",","");
									vector_causados_nuevo=vector_causado.replace(",","");
								getObj('vector_partida').value=vector_partidas_nuevo;
								getObj('vector_causado').value=vector_causados_nuevo;	
						
					

}
function limpiar_facturas(){
setBarraEstado("");
	//bloqueo la fecha
	getObj('partida_comp').style.display='';
	getObj('partida_comp2').style.display='none';
	getObj('cuentas_por_pagar_db_boton_fd').style.display='';
	getObj('btn_cxp_cerrar_comp').style.display='none';
	//
	getObj('cuentas_por_pagar_numero_comprobante_integracion').value="";
	getObj('cuentas_por_pagar_db_numero_documento').disabled='';	
	getObj('monto_causar_comp_v').style.display="none";
	getObj('monto_causar_comp').style.display="";
	//getObj('pestana2_doc').style.display='none';
	//getObj('cargar_debe').style.display='none';
	getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='none';	
	getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='';
	getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='none';
	getObj('monto_bruto2').style.display='none';
	getObj('monto_bruto').style.display='';
	getObj('cuentas_por_pagar_db_tipo_documento').disabled='';
	clearForm('form_cuentas_por_pagar_db_documentos');
	//getObj('check_invisible').value=0;
	getObj('check_co').checked="";
	getObj('cuentas_por_pagar_db_cuentas_orden').value="";

//	getObj('tr_comprometido').style.display=''
//getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
//	getObj('cuentas_por_pagar_db_op_comprometido_no').checked="";
//	getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
	getObj('cuentas_por_pagar_db_monto_bruto').value="0,00";
//	getObj('cuentas_por_pagar_db_iva').value="<?php echo($porcentajexx); ?>"; se qito xq me fecta en la consulta del grid de partidas
	getObj('cuentas_por_pagar_db_iva').value="0,00";
	getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
	getObj('cuentas_por_pagar_db_islr').value="0,00";
	getObj('cuentas_por_pagar_db_base_imponible').value="0,00";
	getObj('cuentas_por_pagar_db_fecha_v').value="<?=$fecha; ?>";
	getObj('cuentas_por_pagar_db_fecha_f').value="<?=  date("d/m/Y"); ?>";	
	getObj('cuentas_por_pagar_db_ayo').value="<?= date("Y"); ?>";
	getObj('cuentas_por_pagar_db_tipo_documento').value='0';
	getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value="1";
	getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTO";
	getObj('cuentas_por_pagar_db_op_oculto').value='1';
	getObj('cuentas_por_pagar_db_radio1').checked="checked";
	getObj('tr_empleado_cxp').style.display='none';
	getObj('tr_proveedor_cxp').style.display='';
	getObj('valor_bi1').value='0';
	getObj('valor_ret_ex').value='0';
	getObj('valor_ret_ex2').value='0';
	getObj('cuentas_por_pagar_check_bi').checked='';
	getObj('cuentas_por_pagar_check_extra1').checked='';
	getObj('cuentas_por_pagar_check_extra2').checked='';
	getObj('tr_base_i').style.display='none';
	getObj('tr_ret_ex1').style.display='none';
	getObj('tr_ret_ex2').style.display='none';
	//getObj('tr_ret_e').style.display='none';
	getObj('cuentas_por_pagar_db_ret_e1').value="0,00";
	getObj('cuentas_por_pagar_db_ret_e2').value="0,00";
	getObj('cuentas_por_pagar_amortizacion').value="0,00";
	getObj('cuentas_por_pagar_db_sub_total').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra2').value="0,00";
	getObj('cuentas_por_pagar_db_monto_ret_iva').value="0,00";
	getObj('cuentas_por_pagar_db_monto_ret_islr').value="0,00";
	getObj('cuentas_por_pagar_db_sub_total').value="0,00";
	getObj('cuentas_por_pagar_db_monto_iva').value="0,00";
	getObj('cuentas_por_pagar_check_bie1').disabled="disabled";
	//getObj('cuentas_por_pagar_check_bie2').disabled="disabled";
	getObj('cuentas_por_pagar_check_bie3').disabled="disabled";

	getObj('cuentas_por_pagar_db_monto_neto').value="0,00"
	getObj('cuentas_por_pagar_db_anticipos').value=<?= $tipos?>;
	getObj('cuentas_por_pagar_db_fac').value=<?= $tipos_fact?>;
	getObj('valor_anticipo').value='0';
	getObj('cuentas_por_pagar_check_anticipos').checked='';
	getObj('cuentas_por_pagar_check_anticipos').disabled='disabled';
	getObj('tr_amort').style.display='none';
	getObj('cuentas_por_pagar_check_bi').disabled='';
	getObj('cuentas_por_pagar_check_extra1').disabled='';
	getObj('cuentas_por_pagar_check_extra2').disabled='';
	getObj('cuentas_por_pagar_db_monto_ret').value='0,00';
	getObj('cuentas_por_pagar_db_monto_sust').value='0,00';
	//
	getObj('cuentas_por_pagar_integracion_total_debe').value='0,00';
	getObj('cuentas_por_pagar_integracion_total_haber').value='0,00';
	getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value='0,00';
	//
	getObj('ret_total').style.display='none';
	getObj('valor_biex1').value='0';
	getObj('valor_biex2').value='0';
	getObj('cuentas_por_pagar_check_bie1').checked='';
	getObj('cuentas_por_pagar_check_bie3').checked='';
	getObj('tr_cxp_iva').style.display='';
	getObj('tr_cxp_sub_t').style.display='';
	getObj('tr_cpx_retiva').style.display='';
	getObj('tr_cxp_retislr').style.display='';
	getObj('tr_ret_e').style.display='';
	getObj('tr_cxp_total_sin_ret').style.display='';
	getObj('tr_amort').style.display='none';
	getObj('tr_cxp_monto_anticipo').style.display='none';
	getObj('cuentas_por_pagar_db_monto_ant').value='0,00';
	getObj('cuentas_por_pagar_check_bi').disabled='';
	getObj('sub_total1').style.display='';
	//campos adicionados 01/03/2012 para modificar las tabalas de ordenes e cas e ordenes especiales
	getObj('iva_multi').value="0,00";
	getObj('cuadro_iva').style.display='none';
	getObj('btn_cxp_actualizar_comprobante_contable').style.display='none';
	getObj('btn_cxp_agregar_comprobante_contable').style.display='';

	getObj('btn_cxp_eliminar_registro').style.display='none';
	getObj('btn_cxp_limpiar').style.display='none';

///////////////////////////////////

	getObj('valor_bi2').value='0';
	getObj('tr_base_i2').style.display='none';
	getObj('tr_cxp_iva2').style.display='none';
	getObj('tr_cpx_retiva2').style.display='none';
	//getObj('cuentas_por_pagar_check_bi2').disabled="disabled";
	getObj('cuentas_por_pagar_check_bi2').checked='';
	getObj('cxp_comp_pr_dif').value='0,00';
///////////////////////////////////
//	jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php'}).trigger("reloadGrid");
	url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php';
	getObj('check_invisible_mp').value='0';
	/**/
	jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value;
							//getObj('partida_celdas').style.display='none';
							getObj('celdas_2').style.display='none';
							//getObj('cuentas_por_pagar_db_monto_bruto').disabled='false';
							
	/**/
	//alert(url);
		
	
	getObj('monto_causar_comp').value="0,00";
	getObj('cuentas_por_pagar_activo_varios').value='0';
jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime(),page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
	var numero_c=0;
jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_c,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_c;
													//alert(url);


//	getObj('cuentas_por_pagar_db_compromiso_n').value="0";
}
$("#cuentas_por_pagar_documentos_db_btn_cancelar").click(function() {
	getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
	getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
	setBarraEstado("");
	getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='none';	
	getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='none';
	getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='';
	clearForm('form_cuentas_por_pagar_db_documentos');//
	//getObj('tr_comprometido').style.display=''
	//getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
	//getObj('cuentas_por_pagar_db_op_comprometido_no').checked="";
	//getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
	getObj('cuentas_por_pagar_db_monto_bruto').value="0,00";
//	getObj('cuentas_por_pagar_db_iva').value="<?php echo($porcentajexx); ?>";
	getObj('cuentas_por_pagar_db_iva').value="0,00";
	getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
	getObj('cuentas_por_pagar_db_islr').value="0,00";
	getObj('cuentas_por_pagar_db_base_imponible').value="0,00";
	getObj('cuentas_por_pagar_db_fecha_v').value="<?=  date("d/m/Y"); ?>";
	getObj('cuentas_por_pagar_db_fecha_f').value="<?=  date("d/m/Y"); ?>";		
	getObj('cuentas_por_pagar_db_ayo').value="<?= date("Y"); ?>";
	getObj('cuentas_por_pagar_db_tipo_documento').value='0';
	getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value="1";
	getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTO";
	getObj('cuentas_por_pagar_db_op_oculto').value='1';
	getObj('cuentas_por_pagar_db_radio1').checked="checked";
	getObj('tr_empleado_cxp').style.display='none';
	getObj('tr_proveedor_cxp').style.display='';
	getObj('tr_cxp_total_sin_ret').style.display=''
	getObj('cuentas_por_pagar_db_monto_sust').value='0,00';
	limpiar_facturas();

});	
//----------------------------------------------------------------------------------------------------

$("#cuentas_por_pagar_documentos_db_btn_consultar").click(function() {
var nd=new Date().getTime();
var anos=getObj('cuentas_por_pagar_db_ayo').value;
limpiar_facturas();
//alert("entro");
getObj('valor_anticipo').value=1;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/documentos/db/vista.grid_fecha_vencimiento.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					//var busq_fecha_v= jQuery("#cuentas_por_pagar_db_fecha_vencimiento_consulta").val(); 
					var benef=jQuery("#cuentas_por_pagra_db_benef").val();
					var prove=jQuery("#cuentas_por_pagra_db_prove").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?busq_fecha_v="+busq_fecha_v+"&benef="+benef+"&prove="+prove+"&anos="+anos,page:1}).trigger("reloadGrid"); 
		    }	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagra_db_benef").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_benef_dosearch();
					});
					$("#cuentas_por_pagra_db_prove").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_prove_dosearch();
					});
				$("#cuentas_por_pagar_db_fecha_vencimiento_consulta").focus(function()
				{
					//if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
				});
					//
					function consulta_benef_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_benef_gridReload,500)
										}
					//
					function consulta_prove_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_prove_gridReload,500)
					}					
					//	
						/*function consulta_doc_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_doc_gridReload,500)
					}*/
						/*function consulta_doc_gridReload()
						{
							var busq_fecha_v= jQuery("#cuentas_por_pagar_db_fecha_vencimiento_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?busq_fecha_v="+busq_fecha_v,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?busq_fecha_v="+busq_fecha_v;
						}*/
						function consulta_benef_gridReload()
						{
							var benef=jQuery("#cuentas_por_pagra_db_benef").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?benef="+benef,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?benef="+benef+"&anos="+anos;
							//alert(url);
							setBarraEstado(url);
						}
						function consulta_prove_gridReload()
						{
							var prove=jQuery("#cuentas_por_pagra_db_prove").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?prove="+prove,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?prove="+prove+"&anos="+anos;
							//alert(url);
							//setBarraEstado(url);
						}
			}
		});
/////////////////////////////////////////////////////////////////////////
				function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:700,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
				url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos2.php?nd='+nd+"&anos="+anos,
								datatype: "json",
								colNames:['Id documentos','Organismo','A&ntilde;o','id_proveedor','C&oacute;digo','Proveedor','Rif','Tipo Doc','N documento','N control','Fecha.V.','%IVA','%RET.IVA','%RET.ISLR','Base imponible','Monto Bruto','base2','iva2','ret2','N Compr','desc documento','Tipo doc','estatus','opcion','total_orden','ret1','ret2','desc1','desc2','pret1','pret2','amortizacion','restar','aplica1','aplica2','tipo_docu','porc','anticipo_monto','Fecha Doc','cuenta_contable','id_tipo_comprobante','descripcion','monto_debito','id_cuenta_cont','tipo_nombre','descripcion_cuenta','descripcion_tipo','numero_comprobante','iva_opcion','comp_co','contador','fecha_comp','fecha_ord','nocomp','sustraendo','por_amort','cta_orden','vector','partidas_ant'],
								colModel:[
									{name:'id_documentos',index:'id_organismo', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'id_organismo',index:'id_organismo', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'id_proveedor',index:'id_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'codigo_proveedor',index:'nombre_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'nombre_proveedor',index:'nombre_proveedor', width:60,sortable:false,resizable:false},
									{name:'rif_proveedor',index:'rif_proveedor', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp1',index:'tipo_documento_cxp1', width:40,sortable:false,resizable:false,hidden:true},
									{name:'numero_documento',index:'numero_documento', width:30,sortable:false,resizable:false},
									{name:'numero_control',index:'numero_control', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha_vencimiento',index:'fecha_vencimiento', width:40,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje_iva',index:'porcentaje_iva', width:30,sortable:false,resizable:false},
									{name:'porcentaje_ret_iva',index:'porcentaje_ret_iva', width:40,sortable:false,resizable:false},
									{name:'porcentaje_ret_islr',index:'porcentaje_ret_islr', width:65,sortable:false,resizable:false},
									{name:'base_imponible',index:'base_imponible', width:80,sortable:false,resizable:false,hidden:true},
									{name:'monto_bruto',index:'monto_bruto', width:60,sortable:false,resizable:false},
									{name:'base2',index:'base2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'iva2',index:'iva2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'retiva2',index:'retiva2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'numero_compromiso',index:'numero_compromiso', width:40,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:60,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:60,sortable:false,resizable:false,hidden:true},
									{name:'opcion',index:'opcion', width:60,sortable:false,resizable:false,hidden:true},
									{name:'total_orden',index:'total_orden', width:60,sortable:false,resizable:false,hidden:true},
									{name:'ret1',index:'ret1', width:60,sortable:false,resizable:false,hidden:true},
									{name:'ret2',index:'ret2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'desc1',index:'desc1', width:60,sortable:false,resizable:false,hidden:true},
									{name:'desc2',index:'desc2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'pret1',index:'pret1', width:60,sortable:false,resizable:false,hidden:true},
									{name:'pret2',index:'pret2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'amort',index:'amort', width:60,sortable:false,resizable:false,hidden:true},
									{name:'restar',index:'resta', width:60,sortable:false,resizable:false,hidden:true},
									{name:'aplica1',index:'aplica1', width:60,sortable:false,resizable:false,hidden:true},
									{name:'aplica2',index:'aplica2', width:60,sortable:false,resizable:false,hidden:true},
									{name:'tipo_docu_ant',index:'tipo_docu_ant', width:60,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje_comp',index:'porcentaje_comp', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto_anticipo',index:'monto_anticipo', width:60,sortable:false,resizable:false,hidden:true},
									{name:'fecha_doc',index:'fecha_doc', width:60,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:60,sortable:false,resizable:false,hidden:true,hidden:true},
									{name:'id_tipo_comprobante',index:'id_tipo_comprobante', width:60,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto_debito',index:'monto_debito', width:60,sortable:false,resizable:false,hidden:true},
									{name:'id_cuenta_cont',index:'id_cuenta_cont', width:60,sortable:false,resizable:false,hidden:true},
									{name:'tipo_codigo',index:'tipo_codigo', width:60,sortable:false,resizable:false,hidden:true},
									{name:'desc_cuentas',index:'desc_cuentas', width:60,sortable:false,resizable:false,hidden:true},
									{name:'tipo_nombre',index:'tipo_nombre', width:60,sortable:false,resizable:false,hidden:true},
									{name:'numero_comprobante',index:'numero_comprobante', width:60,sortable:false,resizable:false,hidden:true},
									{name:'iva_anticipo',index:'iva_anticipo', width:60,sortable:false,resizable:false,hidden:true},
									{name:'comp_co',index:'comp_co', width:60,sortable:false,resizable:false,hidden:true},
									{name:'contador',index:'contador', width:60,sortable:false,resizable:false,hidden:true},
									{name:'fecha_comp',index:'fecha_comp', width:60,sortable:false,resizable:false,hidden:true},
									{name:'fecha_ord',index:'fecha_ord', width:60,sortable:false,resizable:false,hidden:true},
								{name:'ncomp',index:'ncomp', width:60,sortable:false,resizable:false,hidden:true},
							{name:'sustraendo',index:'sustraendo', width:60,sortable:false,resizable:false,hidden:true},
							{name:'por_amort',index:'por_amort', width:60,sortable:false,resizable:false,hidden:true},
							{name:'cuenta_orden',index:'cuenta_orden', width:60,sortable:false,resizable:false,hidden:true},
								{name:'vector',index:'vector', width:60,sortable:false,resizable:false,hidden:true},
								{name:'partidas_ant',index:'partidas_ant', width:60,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								//limpiar_facturas();
								tipo=ret.tipo_documento_cxp1;
								getObj("cuentas_por_pagar_db_facturas_lista").value=ret.vector;	
								getObj("cuentas_por_pagar_db_facturas_lista2").value=ret.vector;	
								getObj('cuentas_por_pagar_db_id').value=ret.id_documentos;
								getObj('cuentas_por_pagar_db_ayo').value=ret.ano;
								getObj('cuentas_por_pagar_db_numero_documento').value=ret.numero_documento;
								getObj('cuentas_por_pagar_db_numero_documento').disabled='disabled';	

								getObj('cuentas_por_pagar_db_numero_control').value=ret.numero_control;	
								getObj('cuentas_por_pagar_db_tipo_documento').value=tipo;
								getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';	
								getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=ret.estatus;
								getObj('opcion_iva').value=ret.iva_anticipo;
								fechas=ret.fecha_vencimiento,	
								fd=fechas.substr(0, 10);//alert(fd);
								fds=fd.substr(0,2)+"/"; fds=fds+fd.substr(3,2)+"/"; fds=fds+fd.substr(6,4);
								getObj('fecha_oculta').value=ret.fecha_comp;
								getObj('cuentas_por_pagar_db_fecha_v').value=fds;
	//--			
								fechas_doc=ret.fecha_doc,	
								fd2=fechas_doc.substr(0, 10);//alert(fd);
								fds2=fd2.substr(0,2)+"/"; fds2=fds2+fd2.substr(3,2)+"/"; fds2=fds2+fd2.substr(6,4);
								getObj('cuentas_por_pagar_db_fecha_f').value=fds2;
	//--
								if(ret.opcion=='1')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='1';
									getObj('cuentas_por_pagar_db_radio1').checked="checked"
									getObj('cuentas_por_pagar_db_proveedor_codigo').value=ret.codigo_proveedor;	
									getObj('cuentas_por_pagar_db_proveedor_id').value=ret.id_proveedor;	
									getObj('cuentas_por_pagar_db_proveedor_nombre').value=ret.nombre_proveedor;
									getObj('cuentas_por_pagar_db_proveedor_rif').value=ret.rif_proveedor;
									getObj('tr_empleado_cxp').style.display='none';
									getObj('tr_proveedor_cxp').style.display='';	
								}else
								if(ret.opcion=='2')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='2';
									getObj('cuentas_por_pagar_db_radio2').checked="checked"
									getObj('cuentas_por_pagar_db_empleado_codigo').value=ret.codigo_proveedor;	
									getObj('cuentas_por_pagar_db_empleado_nombre').value=ret.nombre_proveedor;
									getObj('tr_empleado_cxp').style.display='';
									getObj('tr_proveedor_cxp').style.display='none';		
								}
									getObj('valor_tipo_doc').value=ret.tipo_docu_ant;
									getObj('valor_porcentaje_compromiso').value=ret.porcentaje_comp;
/////////////////////////////////////////////////
if(ret.tipo_documento_cxp1==getObj('cuentas_por_pagar_db_anticipos').value)
	{
		if(ret.numero_compromiso!=0)
		{
				getObj('cuentas_por_pagar_db_compromiso_n').value=ret.numero_compromiso;
				getObj('cuentas_por_pagar_db_total').value=ret.total_orden;
				
				/////
										getObj('partida_comp').style.display='none';
										getObj('partida_comp2').style.display='';
										getObj('partida_comp2').value=ret.partidas_ant;
				
		}
		tota=getObj('cuentas_por_pagar_db_total').value.float();
		tota=tota.currency(2,',','.');
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled'
		getObj('cuentas_por_pagar_db_iva').value='0,00';
		getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_islr').value='0,00';
		//g- getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
		getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
		getObj('cuentas_por_pagar_check_bi').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled';
		getObj('tr_base_i').style.display='none'; 
		//g-getObj('tr_cxp_iva').style.display='none';
		//g-getObj('tr_cxp_sub_t').style.display='none';
		getObj('tr_cpx_retiva').style.display='none';
		getObj('tr_cxp_retislr').style.display='none';
		getObj('tr_ret_e').style.display='none';
		getObj('tr_cxp_total_sin_ret').style.display='none';
		getObj('cuentas_por_pagar_db_monto_bruto').value=ret.monto_bruto;
		getObj('cuentas_por_pagar_db_iva').value=ret.porcentaje_iva;	
		restar_anticipo();
	//	getObj('cuentas_por_pagar_db_monto_ret').value=ret.monto_bruto;
	}
	else
	{	
								if(ret.numero_compromiso!=0)
								{
										getObj('cuentas_por_pagar_db_compromiso_n').value=ret.numero_compromiso;
										getObj('cuentas_por_pagar_db_total').value=ret.total_orden;

									if(ret.tipo_docu_ant=="anticipo")
									{
										getObj('tr_amort').style.display='';
										getObj('tr_cxp_monto_anticipo').style.display='';
										getObj('cuentas_por_pagar_amortizacion').value=ret.amort;
										getObj('cuentas_por_pagar_db_monto_ant').value=ret.monto_anticipo;
										getObj('monto_ant_oculto').value=ret.monto_anticipo;
										getObj('cuentas_por_pagar_db_monto_bruto').value=ret.monto_bruto;
										getObj('monto_bruto_oculto').value=ret.monto_bruto;
										getObj('cuentas_por_pagar_check_bi').disabled="disabled";
										getObj('valor_porcentaje_compromiso').value=ret.por_amort;
										
									//	restar();
									}				
								}
								
								getObj('cuentas_por_pagar_db_iva').value=ret.porcentaje_iva;	
								getObj('cuentas_por_pagar_db_islr').value=ret.porcentaje_ret_islr;	
								getObj('cuentas_por_pagar_db_monto_sust').value=ret.sustraendo;	
								getObj('cuentas_por_pagar_db_ret_iva').value=ret.porcentaje_ret_iva;	
								getObj('cuentas_por_pagar_db_monto_bruto').value=ret.monto_bruto;
								getObj('monto_bruto_oculto').value=ret.monto_bruto;
								getObj('cuentas_por_pagar_db_base_imponible').value=ret.base_imponible;
								if((ret.monto_bruto!=ret.base_imponible)&&(ret.tipo_docu_ant!="anticipo"))
								{	
									getObj('cuentas_por_pagar_check_bi').checked='checked';
									getObj('valor_bi1').value=1;
									getObj('tr_base_i').style.display='';
									getObj('cuentas_por_pagar_check_bie1').disabled='';
									//getObj('cuentas_por_pagar_check_bie2').disabled='';
									}else
								if(ret.monto_bruto==ret.base_imponible)
								{	
									//getObj('cuentas_por_pagar_check_bi').checked='';
									getObj('valor_bi1').value=0;
									getObj('tr_base_i').style.dysplay='none';
								}
								if(ret.ret1!='0,00')
								{
									getObj('tr_ret_ex1').style.display='';
									getObj('text_ret1').style.display='';
									getObj('cuentas_por_pagar_check_extra1').checked="checked";
									getObj('valor_ret_ex').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(ret.ret1=='0,00')
								{
									getObj('tr_ret_ex1').style.display='none';
									getObj('text_ret1').style.display='none';
								}	
								getObj('cuentas_por_pagar_db_ret_extra').value=ret.ret1;
								getObj('cuentas_por_pagar_db_ret_extra_dsc1').value=ret.desc1;
								if(ret.ret2!='0,00')
								{
									getObj('tr_ret_ex2').style.display='';
									getObj('text_ret2').style.display='';
									getObj('cuentas_por_pagar_check_extra2').checked="checked";
									getObj('valor_ret_ex2').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(ret.ret2=='0,00')
								{
									getObj('tr_ret_ex2').style.display='none';
									getObj('text_ret2').style.display='none';
								}
								getObj('cuentas_por_pagar_db_ret_e1').value=ret.pret1;
								getObj('cuentas_por_pagar_db_ret_e2').value=ret.pret2;
								getObj('cuentas_por_pagar_db_ret_extra_dsc2').value=ret.desc2;		
								getObj('cuentas_por_pagar_db_sub_total').value=ret.desc2;
							if (ret.aplica1=='1')
							{
								getObj('cuentas_por_pagar_check_bie1').checked='checked';
								getObj('valor_biex1').value="1";

							}else
							getObj('cuentas_por_pagar_check_bie1').checked='';
							if (ret.aplica2=='1')
							{
								//getObj('cuentas_por_pagar_check_bie2').checked='checked';
								getObj('valor_biex2').value="1";
							}else
								//getObj('cuentas_por_pagar_check_bie2').checked='';
								
						//	alert(ret.iva2);	
							if(ret.iva2!='0,00')
							{//alert("entro");
							//////
								getObj('valor_bi2').value='1';
								getObj('tr_base_i2').style.display='';
								getObj('tr_cxp_iva2').style.display='';
								getObj('tr_cpx_retiva2').style.display='';
								//getObj('cuentas_por_pagar_check_bie2').checked='checked';
							///////////////
							getObj('cuentas_por_pagar_db_base_imponible2').value=ret.base2;
							getObj('cuentas_por_pagar_db_iva2').value=ret.iva2;
							getObj('cuentas_por_pagar_db_ret_iva2').value=ret.retiva2;
							getObj('cuentas_por_pagar_check_bi2').checked='checked';
								getObj('valores').value="prueba";

							//cuentas_por_pagar_check_bi2
							restar();

							}
								
								getObj('cuentas_por_pagar_db_comentarios').value=ret.comentarios,
								restar();
								getObj('cuentas_por_pagar_db_ret_extra').value=ret.ret1;
								getObj('cuentas_por_pagar_db_ret_extra2').value=ret.ret2;
	}
							
							if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='2')
								{
									/*getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
									getObj('cuentas_por_pagar_numero_comprobante_integracion').value=recordset[44];
									getObj('cuentas_por_pagar_integracion_cuenta').value=recordset[36];
									getObj('cuentas_por_pagar_integracion_cuenta_id').value=recordset[39];
									getObj('cuentas_por_pagar_integracion_tipo').value=recordset[41];
									getObj('cuentas_por_pagar_integracion_tipo_id').value=recordset[37];
									//getObj('cuentas_por_pagar_db_comentarios2').value=recordset[];
									getObj('cuentas_por_pagar_integracion_tipo_nombre').value=recordset[43];
									getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=recordset[38];
									getObj('cuentas_por_pagar_integracion_monto').value=getObj('cuentas_por_pagar_db_sub_total').value;
									jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value;
*/
								
								
								
									//getObj('cargar_debe').style.display='';
									getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
								//	getObj('cuentas_por_pagar_db_ret_extra').value=ret.ret1;
									getObj('cuentas_por_pagar_numero_comprobante_integracion').value=ret.numero_comprobante;
									getObj('compi').value=ret.ncomp;
									getObj('cuentas_por_pagar_integracion_cuenta').value=ret.cuenta_contable;
									getObj('cuentas_por_pagar_integracion_cuenta_id').value=ret.id_cuenta_cont;
									getObj('cuentas_por_pagar_integracion_tipo').value=ret.tipo_codigo;
									getObj('cuentas_por_pagar_integracion_tipo_id').value=ret.id_tipo_comprobante;
								//	getObj('cuentas_por_pagar_db_comentarios2').value=ret.descripcion;
									getObj('cuentas_por_pagar_integracion_tipo_nombre').value=ret.tipo_nombre;
									getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=ret.desc_cuentas;
									getObj('cuentas_por_pagar_integracion_monto').value=ret.monto_debito;
										getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='';

									getObj('cuentas_por_pagar_numero_comprobante_cuenta_orden').value=ret.comp_co;

									//jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value;
									
									//alert(url);
								}
								if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='1')
								{
									getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';
									getObj('btn_cxp_agregar_comprobante_contable').style.display='';

								}	
														
								getObj('cuentas_por_pagar_documentos_db_btn_cancelar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='none';	
							if((ret.contador!=0) &&((ret.tipo_documento_cxp1!=getObj('cuentas_por_pagar_db_anticipos').value))
)							{
								
								
								getObj('fecha_oculta').value=ret.fecha_comp;
								fecha=getObj('cuentas_por_pagar_db_fecha_f').value;
//colocando el cuadro para las ordenes especiales
	$orden_especial=ret.cuenta_orden;
									getObj('cuentas_por_pagar_db_cuentas_orden').value=$orden_especial;
									
//									
//									if($orden_especial=="1")
//cambie esto 12/09/2012: si tiene iva no manda este mensaje									
$iva_prueba=ret.porcentaje_iva;
if(($iva_prueba=='0,00')	&&	(getObj('cuentas_por_pagar_db_documentos_estatus').value!="CERRADA"))
{
										Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ESTA CONSULTANDO UN COMPROMISO REALCIONADO CON UNA ORDEN ESPECIAL SIN IVA. DESEA AGREGAR EL VALOR DEL IVA</p></div>", ["ACEPTAR","CANCELAR"], 
										function(val)
										 {
											if(val=="ACEPTAR")
											{
													getObj('iva_multi').style.display='';
													getObj('cuadro_iva').style.display='';
													
													
											}
											else if(val=="CANCELAR")
											{
													getObj('iva_multi').style.display='none';
													getObj('cuadro_iva').style.display='none';
													
													
											}
										});

									}
//
								jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+ret.fecha_ord+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
								url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+ret.fecha_ord+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
								//alert(url)
								getObj('partida_celdas').style.display='';
								getObj('celdas_2').style.display='';
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';	
								getObj('monto_causar_comp').disabled='';
								getObj('cuentas_por_pagar_activo_varios').value='1';
							
							}else
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='';
							///colocando los nuevos iva
						
						valores_concat=getObj('cuentas_por_pagar_db_compromiso_n').value+"-"+getObj('cuentas_por_pagar_db_orden_compra').value;
						getObj('cuentas_por_pagar_integracion_desc_asiento').value=valores_concat;
						
						jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
						//alert(url);
						//llamo la funcion que cargara el grid y los datos del cmprbante si la tiene
						numer_comprobantess=ret.ncomp;
						///
						if(numer_comprobantess!="")
								cargar_consulta_comprobante_contable(numer_comprobantess); 
						///
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
//----------------------------------------------------------------------------------------------------
function cargar_consulta_comprobante_contable(numero_c)
{
		//alert('entro');
			$.ajax({
						url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_comprobantes.php?numero_comprobante="+numero_c,
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								//bloqueo la fecha
								getObj('cuentas_por_pagar_db_boton_fd').style.display='none';
								//
								getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value = recordset[0];
								getObj('cuentas_por_pagar_numero_comprobante_integracion').value = recordset[1];
								getObj('cuentas_por_pagar_integracion_total_debe').value = recordset[2];
								getObj('cuentas_por_pagar_integracion_total_haber').value = recordset[3];
								getObj('cxp_comp_pr_dif').value = recordset[4];
								getObj('cuentas_por_pagar_integracion_tipo_id').value = recordset[5];
								getObj('cuentas_por_pagar_integracion_tipo_nombre').value = recordset[6];
								getObj('cuentas_por_pagar_integracion_tipo').value = recordset[7];
								
								//activo la opcion de completa comprobante
								getObj('btn_cxp_cerrar_comp').style.display='';
								
								//al consultar genera el jqgrid cargando el grid de la informacion
								
								//
								//getObj('cuentas_por_pagar_integracion_id_aux').value=recordset[0];
								//getObj('cuentas_por_pagar_integracion_auxiliar_desc').value=recordset[4];
								//getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
								/*getObj('contabilidad_auxiliares_db_nombre').value=recordset[4];
								getObj('contabilidad_auxiliares_db_comentario').value=recordset[5];
								getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
								getObj('contabilidad_auxiliares_db_desc').value=recordset[6];*/
								jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_c,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_c;
													//alert(url);
							}//fin de if
							
							
						 }
					});	 	
	

}
//----------------------------------------------------------------------------------------------------
$("#cuentas_por_pagar_db_btn_consultar_proveedor").click(function() {
/*getObj('cuentas_por_pagar_db_compromiso_n').value="";
		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Proveedor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
				var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
				jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagar_db_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#cuentas_por_pagar_db_codigo_proveedor_consulta").keypress(function(key)
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
							var busq_nom= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
							var busq_cod= jQuery("#cuentas_por_pagar_db_codigo_proveedor_consulta").val(); 

							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_nom="+busq_nom+"&busq_cod="+busq_cod,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_nom="+busq_nom+"&busq_cod="+busq_cod;
						//	alert(url);
						}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
		});
//						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?nd='+nd,
								//url:'modulos/tesoreria/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
							
							
								datatype: "json",
								colNames:['Id','C&oacute;digo','Proveedor','rif','ret_iva','ret_islr'],
								colModel:[
									{name:'id_proveedor',index:'id_proveedor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:100,sortable:false,resizable:false},
									{name:'ret_iva',index:'ret_iva', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ret_islr',index:'ret_islr', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_db_proveedor_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_db_proveedor_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_db_proveedor_nombre').value = ret.nombre;
									if(getObj('cuentas_por_pagar_db_proveedor_codigo').value='0438')
									{
										getObj('cuentas_por_pagar_db_iva').value='0,00';
									}
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_db_proveedor_rif').value=rif2[0];
									getObj('cuentas_por_pagar_db_ret_iva').value=ret.ret_iva;
									getObj('cuentas_por_pagar_db_islr').value=ret.ret_islr;
								//////////////////////////////////////////////////////////////
								getObj('valor_porcentaje_compromiso').value="";
								getObj('valor_tipo_doc').value="";
								getObj('cuentas_por_pagar_db_orden_compra').value="";
								getObj('cuentas_por_pagar_db_compromiso_n').value="";
								getObj('cuentas_por_pagar_db_total').value="";								
								/////////////////////////////////////////////////////////////
								/////////////////////////////////////////////////////////////
									/*if(getObj('valor_anticipo').value=='0')
									{
										getObj('cuentas_por_pagar_db_iva').value='0,00';
										getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_islr').value='0,00';
										getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
									//	getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
							
									}*/
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
//
//--------------------------------------------------------------------------------------------------
var lastsel,idd,monto;
$("#list_facturas").jqGrid({
	height: 115,
	width: 650,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/orden_pago/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value,
	datatype: "json",
		colNames:['&ordm;Id documentos','Año','Tipo Doc','N doc','N control','Fecha.V.','Monto.B.','Base imp','%IVA','%RET.IVA','%RET.ISLR','NComp','desc documento','Tipo doc','Total Fact'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false,hidden:true},	
									{name:'ano',index:'ano', width:25,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:50,sortable:false,resizable:false},
									{name:'numero_documento',index:'numero_documento', width:40,sortable:false,resizable:false},
									{name:'numero_control',index:'numero_control', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha_vencimiento',index:'fecha_vencimiento', width:60,sortable:false,resizable:false,hidden:true},
									{name:'monto_bruto',index:'monto_bruto', width:50,sortable:false,resizable:false},
									{name:'base_imponible',index:'base_imponible', width:50,sortable:false,resizable:false},
									{name:'porcentaje_iva',index:'porcentaje_iva', width:30,sortable:false,resizable:false},
									{name:'porcentaje_ret_iva',index:'porcentaje_ret_iva', width:60,sortable:false,resizable:false},
									{name:'porcentaje_ret_islr',index:'porcentaje_ret_islr', width:60,sortable:false,resizable:false},
									{name:'numero_compromiso',index:'numero_compromiso', width:40,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:20,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento_cxp',index:'tipo_documento_cxp', width:60,sortable:false,resizable:false	},
									{name:'total',index:'total', width:60,sortable:false,resizable:false}
								],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_facturas'),
   	sortname: 'Id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: true,
	gridComplete:function(){
	   vector=getObj('cuentas_por_pagar_db_facturas_lista').value;
							if(vector!="")
							{
								vector2=vector.split(",");
							
									
									i=0;//&&(getObj('tesoreria_cheques_db_n_precheque').value!="")
									if((vector2!=""))
									{										
											
											while((i<vector2.length))
											{
												//	alert(vector2[i]);
  													jQuery("#list_facturas").setSelection(vector2[i]);
													i=i+1;		
											}
									}			
							}	
							
},//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
onSelectRow: function(id){

////////////////////////////
var ret = jQuery("#list_facturas").getRowData(id);
		html1="";
	   	s = jQuery("#list_facturas").getGridParam('selarrrow');
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('cuentas_por_pagar_db_facturas_lista').value=s;
		//	getObj('cuentas_por_pagar_db_facturas_lista2').value=s;
			
		}	
//////////////////////////
},
onSelectAll: function(id){
 				var ret = jQuery("#list_facturas").getRowData(id);
				s = jQuery("#list_facturas").getGridParam('selarrrow');
				idd="";
				idd = ret.id;
				if(id && id!==lastsel)
				{
					getObj('cuentas_por_pagar_db_facturas_lista').value=s;
					//getObj('cuentas_por_pagar_db_facturas_lista2').value=s;
				}
},
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*onSelectRow: function(id){Anteriormente al arle click cargaba a la nueva factura.....MODIFICACION SEGUN ORDEN DIA:19/09/2012
	//alert(id);
		var ret = jQuery("#list_facturas").getRowData(id);
		var	compromiso=getObj('cuentas_por_pagar_db_compromiso_n').value;
		idd="";lastsel="";
		idd = ret.id;
		//partida=ret.partida;
		if(idd && idd!==lastsel){
		$.ajax({
			url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos3.php?id='+idd,
			
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		   url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documentos3.php?id='+idd;
			    var recordset=html.split("*");
		//	alert(url);
		//	setBarraEstado(html);
	//////////////////////////////////////////////////////////////////////////////////////
	ret_id_documentos=recordset[0];
	ret_id_organismo=recordset[1];
	ret_ano=recordset[2];
	ret_id_proveedor=recordset[3];
	ret_codigo_proveedor=recordset[4];
	ret_nombre_proveedor=recordset[5];
	ret_rif_proveedor=recordset[6];
	ret_tipo_documento_cxp1=recordset[7];
	ret_numero_documento=recordset[8];//alert(recordset[8]);
	ret_numero_control=recordset[9];
   ret_fecha_vencimiento=recordset[10];
   ret_porcentaje_iva=recordset[11];
   ret_porcentaje_ret_iva=recordset[12];
   ret_porcentaje_ret_islr=recordset[13];
   ret_base_imponible=recordset[14];
   ret_monto_bruto=recordset[15];
   ret_base2=recordset[16];
   ret_iva2=recordset[17];
   ret_retiva2=recordset[18];
   ret_numero_compromiso=recordset[19];
   ret_comentarios=recordset[20];
   ret_tipo_documento_cxp=recordset[21];
   ret_estatus=recordset[22];
   ret_opcion=recordset[23];
   ret_total_orden=recordset[24];
   ret_ret1=recordset[25];
   ret_ret2=recordset[26];
   ret_desc1=recordset[27];
   ret_desc2=recordset[28];
   ret_pret1=recordset[29];
   ret_pret2=recordset[30];
   ret_amort=recordset[31];
   ret_restar=recordset[32];
   ret_aplica1=recordset[33];
   ret_aplica2=recordset[34];
   ret_tipo_docu_ant=recordset[35];
   ret_porcentaje_comp=recordset[36];
   ret_monto_anticipo=recordset[37];
   ret_fecha_doc=recordset[38];
   ret_cuenta_contable=recordset[39];
   ret_id_tipo_comprobante=recordset[40];
   ret_descripcion=recordset[41];
   ret_monto_debito=recordset[42];
   ret_id_cuenta_cont=recordset[43];
   ret_tipo_codigo=recordset[44];
   ret_desc_cuentas=recordset[45];
   ret_tipo_nombre=recordset[46];
   ret_numero_comprobante=recordset[47];
   ret_iva_anticipo=recordset[48];
   ret_comp_co=recordset[49];
   ret_contador=recordset[50]
   ret_fecha_comp=recordset[51];
   ret_fecha_ord=recordset[52];
   ret_ncomp=recordset[53];
   ret_sustraendo=recordset[54];
   ret_por_amort=recordset[55];
			///////////////////////////////////////////////////////////////////////////////////////
								//limpiar_facturas();
								tipo=ret_tipo_documento_cxp1;
								getObj('cuentas_por_pagar_db_id').value=ret_id_documentos;
								getObj('cuentas_por_pagar_db_ayo').value=ret_ano;
								getObj('cuentas_por_pagar_db_numero_documento').value=ret_numero_documento;
								getObj('cuentas_por_pagar_db_numero_documento').disabled='disabled';	

								getObj('cuentas_por_pagar_db_numero_control').value=ret_numero_control;	
								getObj('cuentas_por_pagar_db_tipo_documento').value=tipo;
								getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';	
								getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=ret_estatus;
								getObj('opcion_iva').value=ret_iva_anticipo;
								fechas=ret_fecha_vencimiento,	
								fd=fechas.substr(0, 10);//alert(fd);
								fds=fd.substr(0,2)+"/"; fds=fds+fd.substr(3,2)+"/"; fds=fds+fd.substr(6,4);
								getObj('fecha_oculta').value=ret_fecha_comp;
								getObj('cuentas_por_pagar_db_fecha_v').value=fds;
	//--			
								fechas_doc=ret_fecha_doc,	
								fd2=fechas_doc.substr(0, 10);//alert(fd);
								fds2=fd2.substr(0,2)+"/"; fds2=fds2+fd2.substr(3,2)+"/"; fds2=fds2+fd2.substr(6,4);
								getObj('cuentas_por_pagar_db_fecha_f').value=fds2;
	//--
								if(ret_opcion=='1')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='1';
									getObj('cuentas_por_pagar_db_radio1').checked="checked"
									getObj('cuentas_por_pagar_db_proveedor_codigo').value=ret_codigo_proveedor;	
									getObj('cuentas_por_pagar_db_proveedor_id').value=ret_id_proveedor;	
									getObj('cuentas_por_pagar_db_proveedor_nombre').value=ret_nombre_proveedor;
									getObj('cuentas_por_pagar_db_proveedor_rif').value=ret_rif_proveedor;
									getObj('tr_empleado_cxp').style.display='none';
									getObj('tr_proveedor_cxp').style.display='';	
								}else
								if(ret_opcion=='2')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='2';
									getObj('cuentas_por_pagar_db_radio2').checked="checked"
									getObj('cuentas_por_pagar_db_empleado_codigo').value=ret_codigo_proveedor;	
									getObj('cuentas_por_pagar_db_empleado_nombre').value=ret_nombre_proveedor;
									getObj('tr_empleado_cxp').style.display='';
									getObj('tr_proveedor_cxp').style.display='none';		
								}
									getObj('valor_tipo_doc').value=ret_tipo_docu_ant;
									getObj('valor_porcentaje_compromiso').value=ret_porcentaje_comp;
/////////////////////////////////////////////////
if(ret_tipo_documento_cxp1==getObj('cuentas_por_pagar_db_anticipos').value)
	{
		if(ret_numero_compromiso!=0)
		{
				getObj('cuentas_por_pagar_db_compromiso_n').value=ret_numero_compromiso;
				getObj('cuentas_por_pagar_db_total').value=ret_total_orden;
		}
		tota=getObj('cuentas_por_pagar_db_total').value.float();
		tota=tota.currency(2,',','.');
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled'
		getObj('cuentas_por_pagar_db_iva').value='0,00';
		getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_islr').value='0,00';
		//g- getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
		getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
		getObj('cuentas_por_pagar_check_bi').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled';
		getObj('tr_base_i').style.display='none'; 
		//g-getObj('tr_cxp_iva').style.display='none';
		//g-getObj('tr_cxp_sub_t').style.display='none';
		getObj('tr_cpx_retiva').style.display='none';
		getObj('tr_cxp_retislr').style.display='none';
		getObj('tr_ret_e').style.display='none';
		getObj('tr_cxp_total_sin_ret').style.display='none';
		getObj('cuentas_por_pagar_db_monto_bruto').value=ret_monto_bruto;
		getObj('cuentas_por_pagar_db_iva').value=ret_porcentaje_iva;	
		restar_anticipo();
	//	getObj('cuentas_por_pagar_db_monto_ret').value=ret_monto_bruto;
	}
	else
	{	
								if(ret_numero_compromiso!=0)
								{
										getObj('cuentas_por_pagar_db_compromiso_n').value=ret_numero_compromiso;
										getObj('cuentas_por_pagar_db_total').value=ret_total_orden;

									if(ret_tipo_docu_ant=="anticipo")
									{
										getObj('tr_amort').style.display='';
										getObj('tr_cxp_monto_anticipo').style.display='';
										getObj('cuentas_por_pagar_amortizacion').value=ret_amort;
										getObj('cuentas_por_pagar_db_monto_ant').value=ret_monto_anticipo;
										getObj('monto_ant_oculto').value=ret_monto_anticipo;
										getObj('cuentas_por_pagar_db_monto_bruto').value=ret_monto_bruto;
										getObj('monto_bruto_oculto').value=ret_monto_bruto;
										getObj('cuentas_por_pagar_check_bi').disabled="disabled";
										getObj('valor_porcentaje_compromiso').value=ret_por_amort;
									//	restar();
									}				
								}
								
								getObj('cuentas_por_pagar_db_iva').value=ret_porcentaje_iva;	
								getObj('cuentas_por_pagar_db_islr').value=ret_porcentaje_ret_islr;	
								getObj('cuentas_por_pagar_db_monto_sust').value=ret_sustraendo;	
								getObj('cuentas_por_pagar_db_ret_iva').value=ret_porcentaje_ret_iva;	
								getObj('cuentas_por_pagar_db_monto_bruto').value=ret_monto_bruto;
								getObj('monto_bruto_oculto').value=ret_monto_bruto;
								getObj('cuentas_por_pagar_db_base_imponible').value=ret_base_imponible;
								if((ret_monto_bruto!=ret_base_imponible)&&(ret_tipo_docu_ant!="anticipo"))
								{	
									getObj('cuentas_por_pagar_check_bi').checked='checked';
									getObj('valor_bi1').value=1;
									getObj('tr_base_i').style.display='';
									getObj('cuentas_por_pagar_check_bie1').disabled='';
									//getObj('cuentas_por_pagar_check_bie2').disabled='';
									}else
								if(ret_monto_bruto==ret_base_imponible)
								{	
									//getObj('cuentas_por_pagar_check_bi').checked='';
									getObj('valor_bi1').value=0;
									getObj('tr_base_i').style.dysplay='none';
								}
								if(ret_ret1!='0,00')
								{
									getObj('tr_ret_ex1').style.display='';
									getObj('text_ret1').style.display='';
									getObj('cuentas_por_pagar_check_extra1').checked="checked";
									getObj('valor_ret_ex').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(ret_ret1=='0,00')
								{
									getObj('tr_ret_ex1').style.display='none';
									getObj('text_ret1').style.display='none';
								}	
								getObj('cuentas_por_pagar_db_ret_extra').value=ret_ret1;
								getObj('cuentas_por_pagar_db_ret_extra_dsc1').value=ret_desc1;
								if(ret_ret2!='0,00')
								{
									getObj('tr_ret_ex2').style.display='';
									getObj('text_ret2').style.display='';
									getObj('cuentas_por_pagar_check_extra2').checked="checked";
									getObj('valor_ret_ex2').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(ret_ret2=='0,00')
								{
									getObj('tr_ret_ex2').style.display='none';
									getObj('text_ret2').style.display='none';
								}
								getObj('cuentas_por_pagar_db_ret_e1').value=ret_pret1;
								getObj('cuentas_por_pagar_db_ret_e2').value=ret_pret2;
								getObj('cuentas_por_pagar_db_ret_extra_dsc2').value=ret_desc2;		
								getObj('cuentas_por_pagar_db_sub_total').value=ret_desc2;
								if (ret_aplica1=='1')
							{
								getObj('cuentas_por_pagar_check_bie1').checked='checked';
								getObj('valor_biex1').value="1";

							}else
							getObj('cuentas_por_pagar_check_bie1').checked='';
							if (ret_aplica2=='1')
							{
								//getObj('cuentas_por_pagar_check_bie2').checked='checked';
								getObj('valor_biex2').value="1";
							}else
								//getObj('cuentas_por_pagar_check_bie2').checked='';
								
						//	alert(ret_iva2);	
							if(ret_iva2!='0,00')
							{//alert("entro");
							//////
								getObj('valor_bi2').value='1';
								getObj('tr_base_i2').style.display='';
								getObj('tr_cxp_iva2').style.display='';
								getObj('tr_cpx_retiva2').style.display='';
								//getObj('cuentas_por_pagar_check_bie2').checked='checked';
							///////////////
							getObj('cuentas_por_pagar_db_base_imponible2').value=ret_base2;
							getObj('cuentas_por_pagar_db_iva2').value=ret_iva2;
							getObj('cuentas_por_pagar_db_ret_iva2').value=ret_retiva2;
							getObj('cuentas_por_pagar_check_bi2').checked='checked';
								getObj('valores').value="prueba";

							//cuentas_por_pagar_check_bi2
							restar();

							}
								
								getObj('cuentas_por_pagar_db_comentarios').value=ret_comentarios,
								restar();
								getObj('cuentas_por_pagar_db_ret_extra').value=ret_ret1;
								getObj('cuentas_por_pagar_db_ret_extra2').value=ret_ret2;
	}
							
							if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='2')
								{
									//getObj('cargar_debe').style.display='';
									getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
								//	getObj('cuentas_por_pagar_db_ret_extra').value=ret_ret1;
									getObj('cuentas_por_pagar_numero_comprobante_integracion').value=ret_numero_comprobante;
									getObj('compi').value=ret_ncomp;
									getObj('cuentas_por_pagar_integracion_cuenta').value=ret_cuenta_contable;
									getObj('cuentas_por_pagar_integracion_cuenta_id').value=ret_id_cuenta_cont;
									getObj('cuentas_por_pagar_integracion_tipo').value=ret_tipo_codigo;
									getObj('cuentas_por_pagar_integracion_tipo_id').value=ret_id_tipo_comprobante;
								//	getObj('cuentas_por_pagar_db_comentarios2').value=ret_descripcion;
									getObj('cuentas_por_pagar_integracion_tipo_nombre').value=ret_tipo_nombre;
									getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=ret_desc_cuentas;
									getObj('cuentas_por_pagar_integracion_monto').value=ret_monto_debito;
										getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='';

									getObj('cuentas_por_pagar_numero_comprobante_cuenta_orden').value=ret_comp_co;

									url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value;
									
									//alert(url);
								}
								if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='1')
								{
									getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';

								}	
														
								getObj('cuentas_por_pagar_documentos_db_btn_cancelar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='';
								getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='none';	
							if((ret_contador!=0) &&((ret_tipo_documento_cxp1!=getObj('cuentas_por_pagar_db_anticipos').value))
)							{
								getObj('fecha_oculta').value=ret_fecha_comp;
								fecha=getObj('cuentas_por_pagar_db_fecha_f').value;

								jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+ret_fecha_ord+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
								url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+ret_fecha_ord+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
								//alert(url)
								getObj('partida_celdas').style.display='';
								getObj('celdas_2').style.display='';
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';	
								getObj('monto_causar_comp').disabled='';
								getObj('cuentas_por_pagar_activo_varios').value='1';
							}else
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='';
							///colocando los nuevos iva
						jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
						//alert(url);
							///
								dialog.hideAndUnload();
					 					
//alert(html);
				
			}	
		
		});	
		}
        	},*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
/*onSelectAll: function(id){//COMKENTADO POR ORDEN DIA:19/09/2012
    var ret = jQuery("#list_factura").getRowData(id);
	   	s = jQuery("#list_factura").getGridParam('selarrrow');
	
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='1')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&proveedor='+getObj('cuentas_por_pagar_db_orden_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	}else
	if(getObj('cuentas_por_pagar_orden_db_op_oculto').value=='2')
	{
		urls="modulos/cuentas_por_pagar/orden_pago/db/sql.consulta_sel.php?id="+idd+"&vector="+s+'&beneficiario='+getObj('cuentas_por_pagar_orden_db_empleado_codigo').value+'&ano='+getObj('cuentas_por_pagar_db_ayo_orden_pago').value;
	
	}//alert(urls);
		idd = ret.id;
		if(id && id!==lastsel){
			getObj('cuentas_por_pagar_db_facturas_oculto').value=s;
				$.ajax({
					url:urls,
					data:dataForm('form_cuentas_por_pagar_db_orden_pago'), 
					type:'GET',
					cache: false,
					 success:function(html)
					 {
						
						var recordset=html.split("*");	
					//setBarraEstado(html);			
						valor=parseFloat(recordset[0]);
						valor = valor.currency(2,',','.');	
						getObj('cuentas_por_pagar_db_facturas_total').value=valor;
						if(recordset[1]!=null)
							getObj('cuentas_por_pagar_db_numero_compromiso').value=recordset[1];
						else
							getObj('cuentas_por_pagar_db_numero_compromiso').value="";
					}
					});	 
				
		}
	 		
	},*/
}).navGrid("#pager_factura",{refresh:false,search :false,edit:false,add:false,del:false});

//}).navGrid("#pager_factura",{search :false,edit:false,add:false,del:false});/*//////////////////////////////////////////////////////////////////////////////////////////////*/
/*url3='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('cuentas_por_pagar_numero_comprobante_integracion').value;
*///alert(url3);	

//----------------------------------------------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////
//funcion que completa e comprobante contable
$("#btn_cxp_cerrar_comp").click(function(){
//
//habilito este campo
getObj('cuentas_por_pagar_db_tipo_documento').disabled='';
$.ajax (
		{
			url:'modulos/cuentas_por_pagar/documentos/db/sql.completar_comprobante_contable.php',
			data:dataForm('form_cuentas_por_pagar_db_documentos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//alert(html);
				recordset=html;
				recordset = recordset.split("*");
			//	alert(html);
				if (recordset[0]=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					//
													getObj('cuentas_por_pagar_integracion_total_debe').value=recordset[1];
													getObj('cuentas_por_pagar_integracion_total_haber').value=recordset[2];
													getObj('cxp_comp_pr_dif').value=recordset[4];
													//if(getObj('cxp_comp_pr_dif').value=='0,00')
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													
													
					//
					jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
					url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante;					
					limpiar_algunos_cxp();
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					
				}				
				else
				{
					alert(html);
					setBarraEstado(html);
				}
			}
		});
//
getObj('cuentas_por_pagar_db_tipo_documento').disabled='none';
});

//******************************************************************************************************
function limpiar_algunos_cxp()
{
	//alert("entro");
	//getObj('activo').value='';
	getObj('btn_cxp_eliminar_registro').style.display='none';
	getObj('cuentas_por_pagar_debe_haber_integracion_estatus').disabled='';
	getObj('cuentas_por_pagar_integracion_cuenta').disabled='';
	getObj('cuentas_por_pagar_integracion_descripcion_cuentas').disabled='';

	getObj('cuentas_por_pagar_integracion_auxiliar_p').disabled='';
	//
	getObj('cuentas_por_pagar_integracion_ref').value="";
	getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
	//getObj('contabilidad_comp_pr_desc').value="";
	getObj('cuentas_por_pagar_debe_haber_integracion_estatus').value="";
	getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value="0,00";
	/*getObj('contabilidad_comp_pr_total_debe').value="0,00";
	getObj('contabilidad_comp_pr_total_haber').value="0,00";*/
	getObj('cuentas_por_pagar_integracion_desc_asiento').value="";
	getObj('cuentas_por_pagar_integracion_auxiliar_p').value="";
	getObj('cuentas_por_pagar_integracion_cuenta').value="";
	getObj('cuentas_por_pagar_integracion_id_aux').value="";
	getObj('cxp_pr_ubicacion').value="";
	getObj('cxp_pr_ejec_id').value="";
	getObj('cxp_pr_centro_costo').value="";
	getObj('cxp_comp_pr_utf').value="";
	getObj('cxp_comp_pr_utf_id').value="";
	getObj('cuentas_por_pagar_integracion_auxiliar_desc').value="";
	getObj('cxp_centro_costo_desc').value="";
	getObj('cxp_pr_acc_desc').value="";
	getObj('cxp_pr_ubicacion_desc').value="";
	getObj('cxp_comp_pr_utf_desc').value="";

	
	
	
	getObj('cxp_centro_costo_id').value="";
	getObj('cxp_pr_acc').value="";
	getObj('cxp_pr_acc_id').value="";
	//getObj('contabilidad_comprobante_btn_consultar_ubicacion_cmp').value="";
	//getObj('contabilidad_vista_btn_cmp_consultar_acc').value="";
	//getObj('movimientos_contables_db_btn_eliminar').style.display='none';
	//getObj('movimientos_contables_db_btn_eliminar2').style.display='none';
	getObj('cxp_comp_pr_activo').value=0;
	getObj('cxp_comp_pr_activo2').value=0;
	getObj('cxp_comp_pr_activo3').value=0;
	getObj('cxp_comp_pr_activo4').value=0;
	
	//getObj('contabilidad_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";	
}
/////////////////////////////////////////////////////////////////////////////
//$("#btn_cxp_agregar_comprobante_contable").click(function(){
$("#btn_cxp_agregar_comprobante_contable").click(function(){
alert("entro");
if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
{
////////////////////////////////////////////////////////////////////////////////////////////////////////////
//verificar si las facturas pertenecen al mismo mes..
//verificar_mes(getObj('cuentas_por_pagar_db_facturas_lista').value);

////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////	
	if((getObj('cxp_comp_pr_activo').value==1)&&(getObj('cuentas_por_pagar_integracion_cuenta_id').value==""))
	{	
			alert("seleccione un auxiliar");
	}		
	else
	{
			if(getObj('cuentas_por_pagar_db_facturas_lista').value!="")
			{
			///si no tiene lista de facturas relacionadas no debe emitir comprobante	
				if((getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value!='0,00')&&(getObj('cuentas_por_pagar_integracion_tipo_id').value!='')&&(getObj('cuentas_por_pagar_integracion_cuenta_id').value!=''))
				
				{
								 
									//alert("entro");
									setBarraEstado(mensaje[esperando_respuesta]);
									//desbloquear();
									$.ajax (
										{
											url: "modulos/cuentas_por_pagar/documentos/db/guardar_comprobante.php",
											data:dataForm('form_cuentas_por_pagar_db_documentos'),
											type:'POST',
											cache: false,
											success: function(html)
											{
												recordset=html;
												recordset = recordset.split("*");
												//alert(html);
												if (recordset[0]=="Registrado")
												{
													setBarraEstado(mensaje[registro_exitoso],true,true);
													getObj('btn_cxp_cerrar_comp').style.display='';
													getObj('cuentas_por_pagar_numero_comprobante_integracion').value=recordset[3];
													getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value=recordset[5];
													numero_comprobante=getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value;	
													//bloqueo la fecha
													getObj('cuentas_por_pagar_db_boton_fd').style.display='none';
													//											
													jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,
													getObj('cuentas_por_pagar_db_facturas_lista2').value=getObj('cuentas_por_pagar_db_facturas_lista').value;
													//alert(url);
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';													
													getObj('cuentas_por_pagar_integracion_total_debe').value=recordset[1];
													getObj('cuentas_por_pagar_integracion_total_haber').value=recordset[2];
													getObj('cxp_comp_pr_dif').value=recordset[4];
													//if(getObj('cxp_comp_pr_dif').value=='0,00')
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													
													limpiar_algunos_cxp();
												}
												else if (recordset[0]=="NoActualizo")
												{
													alert(html);
												}
												else if (recordset[0]=="numero_existe")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />N&Uacute;MERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
													valor=getObj('contabilidad_comp_pr_numero_comprobante').value;
													
												}else
												 if (recordset[0]=="numero_comprobante")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png'/>N&Uacute;MERO DE COMPROBANTE NO EXISTE, CREE UNO EN EL M&Uacute;DULO N&Uacute;MERO COMRPOBANTE</p></div>",true,true);
												}
												else
												 if (recordset[0]=="no_ayo")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />FECHA ERRADA </p></div>",true,true);
												}							
												else
												if(recordset[0]=="error_num_comprobante")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ERROR EN NUMERO DE COMPROBANTE</p></div>",true,true);
												}
												else
												{
													setBarraEstado(recordset[0]);
												}
											}
										});
									
				}//if((getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value!='')&&(getObj('cuentas_por_pagar_integracion_tipo_id').value!='')&&(getObj('cuentas_por_pagar_integracion_cuenta_id').value!=''))
			///
			else
			{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />Debe llenar los campos del comprobante contable</p></div>",true,true);
			}	
			//
			}
			else
			{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />Debe Seleccionar al menos una factura de la lista</p></div>",true,true);
			}		
	}//FIN DEL ELSE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}//fn deif(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);

});
//-----------------------------------------------------------------------------------------------------
$("#cuentas_por_pagar_db_radio1").click(function(){
		getObj('cuentas_por_pagar_db_op_oculto').value="1"
	});
$("#cuentas_por_pagar_db_radio2").click(function(){
		getObj('cuentas_por_pagar_db_op_oculto').value="2"
	});
	


/*$("#cuentas_por_pagar_db_op_comprometido_si").click(function(){
		getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
	});
$("#cuentas_por_pagar_db_op_comprometido_no").click(function(){
		getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="2";
	});
	*/
//$('#tesoreria_cheque_manual_pr_proveedor_codigo').change(consulta_automatica_proveedor_manual)
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
/*$('#cuentas_por_pagar_db_base_imponible').numeric({allow:',.'});
$('#cuentas_por_pagar_db_iva').numeric({allow:',.'});
$('#cuentas_por_pagar_db_ret_iva').numeric({allow:',.'});
$('#cuentas_por_pagar_db_islr').numeric({allow:',.'});
$('#cuentas_por_pagar_db_monto_bruto').numeric({allow:',.'});
$('#cuentas_por_pagar_db_compromiso_n').numeric({});

$('#cuentas_por_pagar_db_numero_documento').numeric({});
$('#cuentas_por_pagar_db_numero_control').numeric({});
$('#cuentas_por_pagar_db_proveedor_codigo').numeric({});
*/
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

//$('#').change();
/////////////////// validando/////////////////

  function esDigito(sChr){
  var sCod = sChr.charCodeAt(0);
  return ((sCod > 47) && (sCod < 58));
  }
 
  function valSep(oTxt){
  var bOk = false;
  var sep1 = oTxt.value.charAt(2);
  var sep2 = oTxt.value.charAt(5);
  bOk = bOk || ((sep1 == "-") && (sep2 == "-"));
  bOk = bOk || ((sep1 == "/") && (sep2 == "/"));
  return bOk;
  }
 
  function finMes(oTxt){
  var nMes = parseInt(oTxt.value.substr(3, 2), 10);
  var nAno = parseInt(oTxt.value.substr(6), 10);
  var nRes = 0;
  switch (nMes){
   case 1: nRes = 31; break;
   case 2: nRes = 28; break;
   case 3: nRes = 31; break;
   case 4: nRes = 30; break;
   case 5: nRes = 31; break;
   case 6: nRes = 30; break;
   case 7: nRes = 31; break;
   case 8: nRes = 31; break;
   case 9: nRes = 30; break;
   case 10: nRes = 31; break;
   case 11: nRes = 30; break;
   case 12: nRes = 31; break;
  }
  return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
  }
 
  function valDia(oTxt){
  var bOk = false;
  var nDia = parseInt(oTxt.value.substr(0, 2), 10);
  bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
  return bOk;
  }
 
  function valMes(oTxt){
  var bOk = false;
  var nMes = parseInt(oTxt.value.substr(3, 2), 10);
  bOk = bOk || ((nMes >= 1) && (nMes <= 12));
  return bOk;
  }
 
  function valAno(oTxt){
  var bOk = true;
  var nAno = oTxt.value.substr(6);
  bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
  if (bOk){
   for (var i = 0; i < nAno.length; i++){
   bOk = bOk && esDigito(nAno.charAt(i));
   }
  }
  return bOk;
  }
 
  function valFecha(oTxt){
  fech=new Date(); 
  oTxt=getObj('cuentas_por_pagar_db_fecha_v');
  var bOk = true;
  if (oTxt.value != ""){
   bOk = bOk && (valAno(oTxt));
   bOk = bOk && (valMes(oTxt));
   bOk = bOk && (valDia(oTxt));
   bOk = bOk && (valSep(oTxt));
   if (!bOk){
   alert("Fecha inválida");
   oTxt.value ="<?= date("d/m/Y")?>";
  // getObj('cuentas_por_pagar_db_fecha_v').value = date();
  // oTxt.focus();
   } //else alert("Fecha correcta");
  }
  }
 
//////////////////////////////////////////////////////////////////////////////////////////////
/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//consultas automaticas
// consulta por código del documento
function consulta_automatica_documento_cxp()
{

	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_documento_codigo_cxp.php",
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				//setBarraEstado(html);
			if((recordset)&&(html!="blanco"))
				{
				//setBarraEstado(recordset);
				recordset = recordset.split("*");
				//
				getObj('cuentas_por_pagar_documentos_db_btn_cancelar').style.display='';
				getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='';
				getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='';
				getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='none';	
				//
								getObj('cuentas_por_pagar_db_id').value=recordset[0];
								getObj('cuentas_por_pagar_db_numero_documento').value=recordset[8];
								getObj('cuentas_por_pagar_db_numero_control').value=recordset[9];	
								getObj('cuentas_por_pagar_db_tipo_documento').value=recordset[7];	
								getObj('cuentas_por_pagar_db_tipo_documento').disabled='disabled';
								getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=recordset[19];
								getObj('opcion_iva').value=recordset[45];
								//
								if(recordset[19]=='2')
								{
								//alert(recordset[36]);
									getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
									getObj('cuentas_por_pagar_numero_comprobante_integracion').value=recordset[44];
									getObj('cuentas_por_pagar_integracion_cuenta').value=recordset[36];
									getObj('cuentas_por_pagar_integracion_cuenta_id').value=recordset[39];
									getObj('cuentas_por_pagar_integracion_tipo').value=recordset[41];
									getObj('cuentas_por_pagar_integracion_tipo_id').value=recordset[37];
										getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='';
										getObj('cuentas_por_pagar_numero_comprobante_cuenta_orden').value=recordset[46];
							
									//getObj('cuentas_por_pagar_db_comentarios2').value=recordset[];
									getObj('cuentas_por_pagar_integracion_tipo_nombre').value=recordset[43];
									getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=recordset[38];
									getObj('cuentas_por_pagar_integracion_monto').value=getObj('cuentas_por_pagar_db_sub_total').value;
									//jQuery("#list_integracion").setGridParam({url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value,page:1}).trigger("reloadGrid");
									url='modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?numero_doc='+getObj('cuentas_por_pagar_db_numero_documento').value;
								}else
								if(recordset[19]=='1')
								{
									getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';

								}
								//
								fechas=recordset[10],	
								fd=fechas.substr(0, 10);
								fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4);
								getObj('cuentas_por_pagar_db_fecha_v').value=fds;
							fechas2=recordset[35];
					//		alert(fechas2);
								fd3=fechas2.substr(0, 10);
								fds3=fd3.substr(8,2)+"/"; fds3=fds3+fd3.substr(5,2)+"/"; fds3=fds3+fd3.substr(0,4);
								getObj('cuentas_por_pagar_db_fecha_f').value=fds3;

								getObj('cuentas_por_pagar_db_iva').value=recordset[11];	
								getObj('cuentas_por_pagar_db_islr').value=recordset[13];	
								getObj('cuentas_por_pagar_db_ret_iva').value=recordset[12];	
								getObj('cuentas_por_pagar_db_monto_bruto').value=recordset[15];	
								getObj('cuentas_por_pagar_db_base_imponible').value=recordset[14];
								getObj('cuentas_por_pagar_db_comentarios').value=recordset[17];
							
							if(recordset[20]=='1')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='1';
									getObj('cuentas_por_pagar_db_radio1').checked="checked"
									getObj('cuentas_por_pagar_db_proveedor_codigo').value=recordset[4];	
									getObj('cuentas_por_pagar_db_proveedor_id').value=recordset[3];	
									getObj('cuentas_por_pagar_db_proveedor_nombre').value=recordset[5];
									//getObj('cuentas_por_pagar_db_proveedor_rif').value=ret.rif_proveedor;
									getObj('tr_empleado_cxp').style.display='none';
									getObj('tr_proveedor_cxp').style.display='';	
								}else
								if(recordset[20]=='2')
								{
									getObj('cuentas_por_pagar_db_op_oculto').value='2';
									getObj('cuentas_por_pagar_db_radio2').checked="checked"
									getObj('cuentas_por_pagar_db_empleado_codigo').value=recordset[4];	
									getObj('cuentas_por_pagar_db_empleado_nombre').value=recordset[5];
									getObj('tr_empleado_cxp').style.display='';
									getObj('tr_proveedor_cxp').style.display='none';		
								}
								getObj('valor_tipo_doc').value=recordset[32];
								getObj('valor_porcentaje_compromiso').value=recordset[33];
if(recordset[7]==getObj('cuentas_por_pagar_db_anticipos').value)
	{
		if(recordset[16]!=0)
		{
				getObj('cuentas_por_pagar_db_compromiso_n').value=recordset[16];
				getObj('cuentas_por_pagar_db_total').value=recordset[21];
		}
		tota=getObj('cuentas_por_pagar_db_total').value.float();
		tota=tota.currency(2,',','.');
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled'
	//g- 	getObj('cuentas_por_pagar_db_iva').value='0,00';
		getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_islr').value='0,00';
	//g-	getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
		getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
		getObj('cuentas_por_pagar_check_bi').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled';
		getObj('tr_base_i').style.display='none'; 
	//g-	getObj('tr_cxp_iva').style.display='none';
	//g-	getObj('tr_cxp_sub_t').style.display='none';
		getObj('tr_cpx_retiva').style.display='none';
		getObj('tr_cxp_retislr').style.display='none';
		getObj('tr_ret_e').style.display='none';
		getObj('tr_cxp_total_sin_ret').style.display='none';
		getObj('cuentas_por_pagar_db_monto_bruto').value=recordset[15];	
		//g- getObj('cuentas_por_pagar_db_monto_ret').value=recordset[15];
		getObj('cuentas_por_pagar_integracion_monto').value=recordset[15];	
		getObj('cuentas_por_pagar_db_iva').value=recordset[11];	
		restar_anticipo();
		getObj('sub_total1').style.display='none';
	}
	else
	{	
								if(recordset[16]!=0)
								{
										getObj('cuentas_por_pagar_db_compromiso_n').value=recordset[16];
										//cargando tabla de partidas
											if(recordset[47]!=0)
											{
											fecha=fds3;
											jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&cuentas_por_pagar_db_id="+getObj('cuentas_por_pagar_db_id').value+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
											url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
										//	alert(url);
											
											getObj('partida_celdas').style.display='';
											getObj('celdas_2').style.display='';
											//getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';		
											
											}
										getObj('cuentas_por_pagar_db_total').value=recordset[21];
									if(recordset[32]=="anticipo")
									{
										getObj('tr_amort').style.display='';
										getObj('tr_cxp_monto_anticipo').style.display='';
										getObj('cuentas_por_pagar_amortizacion').value=recordset[28];
										getObj('cuentas_por_pagar_db_monto_ant').value=recordset[34];
										getObj('cuentas_por_pagar_db_monto_bruto').value=recordset[15];
										getObj('cuentas_por_pagar_check_bi').disabled="disabled";
									}				
								}
								if((recordset[14]!=recordset[17])&&(recordset[32]!="anticipo"))
								{	
									getObj('cuentas_por_pagar_check_bi').checked='checked';
									getObj('valor_bi1').value=1;
									getObj('tr_base_i').style.display='';
									getObj('cuentas_por_pagar_check_bie1').disabled='';
									//getObj('cuentas_por_pagar_check_bie2').disabled='';
									}else
								if(recordset[14]==recordset[17])
								{	
									getObj('valor_bi1').value=0;
									getObj('tr_base_i').style.dysplay='none';
								}
								if(recordset[22]!='0,00')
								{
									getObj('tr_ret_ex1').style.display='';
									getObj('text_ret1').style.display='';
									getObj('cuentas_por_pagar_check_extra1').checked="checked";
									getObj('valor_ret_ex').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(recordset[22]=='0,00')
								{
									getObj('tr_ret_ex1').style.display='none';
									getObj('text_ret1').style.display='none';
								}	
								getObj('cuentas_por_pagar_db_ret_extra').value=recordset[22];
								getObj('cuentas_por_pagar_db_ret_extra_dsc1').value=recordset[24];
								if(recordset[23]!='0,00')
								{
									getObj('tr_ret_ex2').style.display='';
									getObj('text_ret2').style.display='';
									getObj('cuentas_por_pagar_check_extra2').checked="checked";
									getObj('valor_ret_ex2').value='1';
									getObj('ret_total').style.display='';		
								}else
								if(recordset[23]=='0,00')
								{
									getObj('tr_ret_ex2').style.display='none';
									getObj('text_ret2').style.display='none';
								}
								getObj('cuentas_por_pagar_db_ret_e1').value=recordset[26];
								getObj('cuentas_por_pagar_db_ret_e2').value=recordset[27];
								getObj('cuentas_por_pagar_db_ret_extra').value=recordset[22];
								getObj('cuentas_por_pagar_db_ret_extra2').value=recordset[23];
								getObj('cuentas_por_pagar_db_ret_extra_dsc2').value=recordset[25];
								 restar();		
							if (recordset[30]=='1')
							{
								getObj('cuentas_por_pagar_check_bie1').checked='checked';
								getObj('valor_biex1').value="1";
								restar();

							}else
							getObj('cuentas_por_pagar_check_bie1').checked='';
							if (recordset[31]=='1')
							{
								//getObj('cuentas_por_pagar_check_bie2').checked='checked';
								getObj('valor_biex2').value="1";
								restar();
							}else
								getObj('cuentas_por_pagar_check_bie2').checked='';
								getObj('cuentas_por_pagar_db_comentarios').value=ret.comentarios,
								getObj('cuentas_por_pagar_db_ret_extra').value=recordset[22];
								getObj('cuentas_por_pagar_db_ret_extra2').value=recordset[23];
													
							
	    	}
								/*if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='2')
								{
									getObj('cuentas_por_pagar_db_documentos_estatus').value="CERRADA"
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='';
								}else
								if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='1')
								{
									getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTA"
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='';

								}*/
    	}			
	}
		});	 	 
}
///////////////////////////-consulta automatica eliva-////////////////////////////////////////////////////
function consulta_automatica_impuesto_cxp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_documento_impuesto.php",
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
			if((recordset)&&(html!="blanco"))
				{
					getObj('cuentas_por_pagar_db_iva').value=recordset;
							
						}
				
				
			 }
		});	 	 
}
/*/--------------------------------------consulta por codigo de proveedor ------------------------------------------------------------------------/*/
//consultas automaticas
function consulta_automatica_proveedor_cxp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_proveedor_codigo_cxp.php",
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);		
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('cuentas_por_pagar_db_proveedor_nombre').value = recordset[1];
				getObj('cuentas_por_pagar_db_proveedor_id').value=recordset[0];
				rif=recordset[2];
				rif2 = rif.split("-");
				//
				//modificacion hecha el 26/09/2012
				if(getObj('cuentas_por_pagar_db_proveedor_codigo').value='0438')
				{
					getObj('cuentas_por_pagar_db_iva').value='0,00';
				}
				//
								getObj('cuentas_por_pagar_db_proveedor_rif').value=rif[0];
								getObj('cuentas_por_pagar_db_ret_iva').value=recordset[3];
								getObj('cuentas_por_pagar_db_islr').value=recordset[4];
								getObj('cuentas_por_pagar_db_compromiso_n').value="";
								getObj('cuentas_por_pagar_db_total').value="";
								getObj('cuentas_por_pagar_db_orden_compra').value="";
								getObj('cuentas_por_pagar_db_restante').value="";
								/*if(getObj('valor_anticipo').value=='0')
									{
										getObj('cuentas_por_pagar_db_iva').value='0,00';
										getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_islr').value='0,00';
										getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
										getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
							
									}*/

	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
								//////////////////////////////////////////////////////////////
								getObj('valor_porcentaje_compromiso').value="";
								getObj('valor_tipo_doc').value="";
								getObj('cuentas_por_pagar_db_orden_compra').value="";
								getObj('cuentas_por_pagar_db_compromiso_n').value="";
								getObj('cuentas_por_pagar_db_total').value="";								
								/////////////////////////////////////////////////////////////
			}
				else
			 {  
			   	getObj('cuentas_por_pagar_db_proveedor_nombre').value ="";
				getObj('cuentas_por_pagar_db_proveedor_id').value="";
				getObj('cuentas_por_pagar_db_proveedor_rif').value="";
				//getObj('').disabled="disdabled";
				}
				
			 }
		});	 	 
}
///imporimir comprobante contable 
$("#cuentas_por_pagar_db_documentos_btn_imprimir_comprobante").click(function(){
/*comprobante=getObj('compi').value;
	url="pdf.php?p=modulos/cuentas_por_pagar/documentos/rp/vista.lst.comprobante_cxp_doc.php¿comprobante="+comprobante+"@ano="+getObj('cuentas_por_pagar_numero_comprobante_integracion').value+"@fecha="+getObj('fecha_oculta').value; 
		openTab("Comprobante-cxp",url);	*/
		desde_numero=getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value;
		hasta_numero=getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value;
				url="pdf.php?p=modulos/contabilidad/movimientos_contables/rp/vista.lst.comprobante_contabilidad.php¿desde_numero="+desde_numero+"@hasta_numero="+hasta_numero;
		openTab("Movimientos Contables",url);
});
//---------------------------------------eliminacion----------------------------------------------------------------------------------------------------------------
$("#cuentas_por_pagar_documentos_db_btn_eliminar").click(function() {
/////////////////////////////////////////////////////////////////////////////////////////
//si el numero de comprobante ya existe, si puede cerrarce...
if((getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value=="")&&(getObj('cuentas_por_pagar_numero_comprobante_integracion').value=="")) 
{
/////////////////////////////////////////////////////////////////////
	if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value=='2')
	{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />EL DOCUMENTO SE ENCUENTRA CERRADO</p></div>",true,true);
	}
	else
	if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
	{Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />	Desea realmente anular el siguiente documento?</p></div>", ["ACEPTAR","CANCELAR"], 
		function(val)
		 {
			if(val=='ACEPTAR')
			{
			getObj('cuentas_por_pagar_db_numero_documento').disabled='';
					
							$.ajax (
							{
								url:'modulos/cuentas_por_pagar/documentos/db/sql_eliminar.documento.php',
								data:dataForm('form_cuentas_por_pagar_db_documentos'),
								type:'POST',
								cache: false,
								success: function(html)
								{
									if (html=="Eliminado")
									{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />REGISTRO ANULADO</p></div>",true,true);
									setBarraEstado("");
									limpiar_facturas();
									//getObj('cuentas_por_pagar_db_monto_pagar').value="0,00";
									getObj('cuentas_por_pagar_db_iva').value="<?php echo($porcentajexx); ?>";
									getObj('cuentas_por_pagar_db_islr').value="0,00";
									getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
						
									//getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
									getObj('tr_comprometido').style.display='';
									getObj('cuentas_por_pagar_db_compromiso_n').value="";
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
						
									getObj('cuentas_por_pagar_db_documentos_btn_cerrar').style.display='none';
									getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
									//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
									//getObj('tesoreria_banco_db_btn_actualizar').style.display='none';
									//('tesoreria_banco_db_btn_guardar').style.display='';
									//getObj('tesoreria_banco_db_btn_consultar').style.display='';
									//clearForm('form_tesoreria_db_banco');
									
									}
									else
									if(html=="documento_orden")
									{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL DOCUMENTO CUENTA CON ORDEN DE PAGO</p></div>",true,true);
					
									}else
									if(html=="documento_cerrado")
									{
									setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />EL DOCUMENTO SE ENCUENTRA CERRADO</p></div>",true,true);
					
									}
									else
												if (html=="cerrado")
												{
													setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />MÓDULO CERRADO</p></div>",true,true);
												}	
									else
									{
										alert(html);
										//setBarraEstado(mensaje[relacion_existe],true,true);
										
									}
								}
							});
						}
					});	
	}
	else
			setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);
/////////////////////////////////////////////////////////////////////
}//end if((getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value!="")&&(getObj('cuentas_por_pagar_numero_comprobante_integracion').value!="")) 

else
{
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DEBE ANULAR EL COMPROBANTE ANTES DE ANULAR EL DOCUMENTO</p></div>",true,true);
}//end if((getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value!="")&&(getObj('cuentas_por_pagar_numero_comprobante_integracion').value!="")) 
});
//limpiar documentos
function limpieza_combinada_facturas()
{

											getObj('cuentas_por_pagar_db_documentos_btn_abrir').style.display='none';
											setBarraEstado("");
											getObj('cuentas_por_pagar_documentos_db_btn_eliminar').style.display='none';	
											getObj('cuentas_por_pagar_documentos_db_btn_actualizar').style.display='none';
											getObj('cuentas_por_pagar_documentos_db_btn_guardar').style.display='';
											clearForm('form_cuentas_por_pagar_db_documentos');
											//getObj('tr_comprometido').style.display=''
											//getObj('cuentas_por_pagar_db_op_comprometido_si').checked="checked";
											//getObj('cuentas_por_pagar_db_op_comprometido_no').checked="";
											//getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
											getObj('cuentas_por_pagar_db_monto_bruto').value="0,00";
											//getObj('cuentas_por_pagar_db_iva').value="<?php echo($porcentajexx); ?>";
											getObj('cuentas_por_pagar_db_iva').value="0,00";
											getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
											getObj('cuentas_por_pagar_db_islr').value="0,00";
											getObj('cuentas_por_pagar_db_base_imponible').value="0,00";
											getObj('cuentas_por_pagar_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
											getObj('cuentas_por_pagar_db_ayo').value="<?= date("Y"); ?>";
											getObj('cuentas_por_pagar_db_tipo_documento').value='0';
											getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value="1";
											getObj('cuentas_por_pagar_db_documentos_estatus').value="ABIERTO";
											getObj('cuentas_por_pagar_db_op_oculto').value='1';
											getObj('cuentas_por_pagar_db_radio1').checked="checked";
											getObj('tr_empleado_cxp').style.display='none';
											getObj('tr_proveedor_cxp').style.display='';
											getObj('tr_cxp_total_sin_ret').style.display=''
											limpiar_facturas();
}
//---------------------------------------------------------consultar compromiso--------------------------------------------------------------------------------
$("#cuentas_por_pagar_db_btn_consultar_compromiso").click(function() {
//if((getObj('cuentas_por_pagar_db_proveedor_id').value!='')&&(getObj('cuentas_por_pagar_db_tipo_documento').value!='0'))
/*if(getObj('cuentas_por_pagar_db_id').value=='')
{*/
//alert(getObj('cuentas_por_pagar_db_id').value);
		url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor_compromiso.php?nd='+nd+"&proveedor="+getObj('cuentas_por_pagar_db_proveedor_id').value;
				//alert(url);	
		/*var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
		//	$.post("/modulos/cuentas_por_pagar/documentos/db/grid_cuentasxpagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores/Compromisos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/documentos/db/grid_pagar_comp.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Compromisos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
				var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
				jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor_compromiso.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagar_db_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#cuentas_por_pagar_db_compromiso_proveedor_consulta").keypress(function(key)
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
							//alert("entro");
							var busq_nom= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
							var busq_com= jQuery("#cuentas_por_pagar_db_compromiso_proveedor_consulta").val(); 

							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor_compromiso.php?busq_nom="+busq_nom+"&busq_com="+busq_com,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor_compromiso.php?busq_nom="+busq_nom+"&busq_com="+busq_com;
						//alert(url);
						}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////						
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor_compromiso.php?nd='+nd+"&proveedor="+getObj('cuentas_por_pagar_db_proveedor_id').value,
								datatype: "json",
								colNames:['Nombre','Nombre','Compromiso','N Orden','Fecha Orden','Monto_Orden','Monto_fact','tipo','Disponible','Monto Ant','Porcentaje_Ant','iva','id_prove','codigo_prove','partida','concepto','cuenta_orden','partida_ant'],
								colModel:[
									{name:'nombre',index:'nombre', width:60,sortable:false,resizable:false,hidden:true},
									{name:'nombre2',index:'nombre2', width:110,sortable:false,resizable:false},
									{name:'compromiso',index:'compromiso', width:100,sortable:false,resizable:false},
									{name:'orden',index:'orden', width:60,sortable:false,resizable:false,hidden:true},
									{name:'fecha_orden',index:'fecha_orden', width:100,sortable:false,resizable:false,hidden:true},
									{name:'total',index:'total', width:100,sortable:false,resizable:false},
									{name:'disponible',index:'disponible', width:100,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'monto_factura',index:'monto_factura', width:100,sortable:false,resizable:false},
									{name:'monto_ant',index:'monto_ant', width:100,sortable:false,resizable:false,hidden:true},
									{name:'porcentaje',index:'porcentaje', width:100,sortable:false,resizable:false},
									{name:'iva',index:'iva', width:100,sortable:false,resizable:false,hidden:true},								
									{name:'id_prove',index:'id_prove', width:100,sortable:false,resizable:false,hidden:true},								
									{name:'codigo_prove',index:'codigo_prove', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partida',index:'partida', width:100,sortable:false,resizable:false,hidden:true},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_orden',index:'cuenta_orden', width:100,sortable:false,resizable:false,hidden:true},
									{name:'partida_ant',index:'partida_ant', width:100,sortable:false,resizable:false,hidden:true}
									
									
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(compromiso){
								var ret = jQuery("#list_grid_"+nd).getRowData(compromiso);
									getObj('cuentas_por_pagar_db_compromiso_n').value=ret.compromiso;
									getObj('cuentas_por_pagar_db_total').value=ret.total;
									getObj('cuentas_por_pagar_db_orden_compra').value=ret.orden;
									getObj('valor_porcentaje_compromiso').value=ret.porcentaje;
									getObj('valor_tipo_doc').value=ret.tipo;
									getObj('opcion_iva').value=ret.iva;
									getObj('cuentas_por_pagar_db_restante').value=ret.monto_factura;
									getObj('cuentas_por_pagar_db_proveedor_nombre').value=ret.nombre;
									getObj('cuentas_por_pagar_db_proveedor_id').value=ret.id_prove;
									getObj('cuentas_por_pagar_db_proveedor_codigo').value=ret.codigo_prove;
									getObj('cuentas_por_pagar_db_comentarios').value=ret.concepto;
									fecha=ret.fecha_orden;
									getObj('fecha_oculta').value=fecha;
										//alert(ret.partida);
									///////////////////////////////////
		if((getObj('cuentas_por_pagar_db_anticipos').value)==(getObj('cuentas_por_pagar_db_tipo_documento').value))									
									{
										getObj('partida_comp').style.display='none';
										getObj('partida_comp2').style.display='';
										getObj('partida_comp2').value=ret.partida_ant;
									}
									//////////////////////////////////	
//////////////////mod:29/02/2012:proceso que en el caso de que sea orden especial mandar alerta para que el usuario ingrese en un programa el valor del iva...
									$orden_especial=ret.cuenta_orden;
									getObj('cuentas_por_pagar_db_cuentas_orden').value=$orden_especial;
									if($orden_especial=="1")
									{
										Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ESTA CONSULTANDO UN COMPROMISO REALCIONADO CON UNA ORDEN ESPECIAL SIN IVA. DESEA AGREGAR EL VALOR DEL IVA</p></div>", ["ACEPTAR","CANCELAR"], 
										function(val)
										 {
											if(val=="ACEPTAR")
											{
													getObj('iva_multi').style.display='';
													getObj('cuadro_iva').style.display='';
													
													
											}
											else if(val=="CANCELAR")
											{
													getObj('iva_multi').style.display='none';
													getObj('cuadro_iva').style.display='none';
													
													
											}
										});

									}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								if(ret.partida!=0)
								{
								
								
								if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)
									{
		
										jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value+"&comp="+getObj('cuentas_por_pagar_db_compromiso_n').value,page:1}).trigger("reloadGrid"); 
										url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value+"&comp="+getObj('cuentas_por_pagar_db_compromiso_n').value;
									 	//alert(url);
										getObj('partida_celdas').style.display='';
										getObj('celdas_2').style.display='';
										getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';								
										getObj('cuentas_por_pagar_activo_varios').value='1';
										//alert(getObj('cuentas_por_pagar_activo_varios').value);
									}
									else
									{
										getObj('cuentas_por_pagar_db_monto_ant').disabled='';
									}
								}
							getObj('monto_causar_comp').disabled="";	
								//	alert(ret.monto_factura);
									if(ret.monto_factura=="0,00")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />No se cuenta con mas dinero comprometido para la orden seleccionada</p></div>",true,true);
												getObj('cuentas_por_pagar_db_compromiso_n').value="";
												getObj('cuentas_por_pagar_db_total').value="";
												getObj('cuentas_por_pagar_db_orden_compra').value="";	
										}
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_anticipos').value)) 
									{
										tota1=ret.total;
										if(ret.tipo=="anticipo")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />El compromiso seleccionado ya cuenta con un anticipo creado, debe elegir otro tipo de documento</p></div>",true,true);
											limpieza_combinada_facturas();		
										}
									}else
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_fac').value)&&(ret.tipo=="anticipo"))
									{
											//alert("entro");
											getObj('tr_amort').style.display='';
											getObj('tr_cxp_monto_anticipo').style.display='';
											getObj('cuentas_por_pagar_check_bi').disabled='disabled';
									}else
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_fac').value)&&(ret.tipo!="anticipo"))
									{
											getObj('tr_amort').style.display='none';
											getObj('tr_cxp_monto_anticipo').style.display='none';
											getObj('cuentas_por_pagar_check_bi').disabled='';
									}
									valores_concat=getObj('cuentas_por_pagar_db_compromiso_n').value+"-"+getObj('cuentas_por_pagar_db_orden_compra').value;
									getObj('cuentas_por_pagar_integracion_desc_asiento').value=valores_concat;
									dialog.hideAndUnload();
									
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();

								},
								loadError:function(xhr,st,err){ 
								setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								//setBarraEstado(url);
								},															
								sortname: 'id_proveedor',
								viewrecords: true,
								sortorder: "asc"
							});
						}
//}
});
//proceso q aparece o desparece el cuadro de la base imponible=bi
function proceso_bi()
{

 if(getObj('valor_bi1').value=='1')
 {	
	//alert("entro");
	getObj('valor_bi1').value='0';
	getObj('tr_base_i').style.display='none';
	getObj('cuentas_por_pagar_check_bie1').disabled="disabled";
	getObj('cuentas_por_pagar_check_bie1').checked="";
	getObj('cuentas_por_pagar_db_base_imponible').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
	}else
 if(getObj('valor_bi1').value=='0')
	{
		getObj('valor_bi1').value='1';
		getObj('tr_base_i').style.display='';
		getObj('cuentas_por_pagar_db_base_imponible').value='0,00';
		getObj('cuentas_por_pagar_check_bie1').disabled="";
		//getObj('cuentas_por_pagar_check_bie2').disabled="";

	}		
}//
//proceso q aparece o desparece el cuadro de la base imponible=bi
function proceso_bi2()
{
getObj('cuentas_por_pagar_db_base_imponible2').value="0,00";
getObj('cuentas_por_pagar_db_iva2').value='0,00';
getObj('cuentas_por_pagar_db_monto_iva2').value='0,00';
getObj('cuentas_por_pagar_db_ret_iva2').value='0,00';
getObj('cuentas_por_pagar_db_monto_ret_iva2').value='0,00';
 if(getObj('valor_bi2').value=='1')
 {	
	//alert("entro");
	getObj('valor_bi2').value='0';
	getObj('tr_base_i2').style.display='none';
	getObj('tr_cxp_iva2').style.display='none';
	getObj('tr_cpx_retiva2').style.display='none';
	getObj('cuentas_por_pagar_check_bie3').disabled="disabled";
	getObj('cuentas_por_pagar_check_bie3').checked="";
	getObj('cuentas_por_pagar_db_base_imponible').value=getObj('cuentas_por_pagar_db_monto_bruto').value;
	}
	else
 if(getObj('valor_bi2').value=='0')
	{
		getObj('valor_bi2').value='1';
		getObj('tr_base_i2').style.display='';
		getObj('tr_cxp_iva2').style.display='';
		getObj('tr_cpx_retiva2').style.display='';
		getObj('cuentas_por_pagar_db_base_imponible2').value='0,00';
		//getObj('cuentas_por_pagar_check_bie2').disabled="";
		
		getObj('cuentas_por_pagar_db_iva2').value='0,00';
		getObj('cuentas_por_pagar_db_monto_iva2').value='0,00';
		getObj('cuentas_por_pagar_db_ret_iva2').value='0,00';
		getObj('cuentas_por_pagar_db_monto_ret_iva2').value='0,00';
	}		
}//
//al seleccionar la retencion extra 1
function proceso_ex()
{
 if(getObj('valor_ret_ex').value=='1')
 {	
	getObj('valor_ret_ex').value='0';
	getObj('tr_ret_ex1').style.display='none';
	getObj('text_ret1').style.display='none';
	getObj('ret_total').style.display='none';
	//if(getObj('valor_ret_ex2').value==0)
	//getObj('tr_ret_e').style.display='none';
	
	}else
 if(getObj('valor_ret_ex').value=='0')
	{
		getObj('valor_ret_ex').value='1';
		getObj('tr_ret_ex1').style.display='';
		getObj('text_ret1').style.display='';
		getObj('ret_total').style.display='';
		//getObj('tr_ret_e').style.display='';
	}		
}//
//al seleccionar la retencion extra2
function proceso_ex2()
{
 if(getObj('valor_ret_ex2').value=='1')
 {	
	getObj('valor_ret_ex2').value='0';
	getObj('tr_ret_ex2').style.display='none';
	getObj('text_ret2').style.display='none';
	getObj('ret_total').style.display='none';
	//if(getObj('valor_ret_ex').value==0)
	//getObj('tr_ret_e').style.display='none';

	}else
 if(getObj('valor_ret_ex2').value=='0')
	{
		getObj('valor_ret_ex2').value='1';
		getObj('tr_ret_ex2').style.display='';
		getObj('text_ret2').style.display='';
		getObj('ret_total').style.display='';
		//getObj('tr_ret_e').style.display='';

	}		
}
//para saber si hay retencion extra y si va contra la base imponible o el monto bruto
function proceso_biex1()
{

 if(getObj('valor_biex1').value=='1')
{	
	getObj('valor_biex1').value='0';
}else
 if(getObj('valor_biex1').value=='0')
	{
		getObj('valor_biex1').value='1';
	}		
}
//para saber si hay retencion extra2 y si va contra la base imponible o el monto bruto
function proceso_biex2()
{


 if(getObj('valor_biex2').value=='1')
{	
	getObj('valor_biex2').value='0';
}else
 if(getObj('valor_biex2').value=='0')
	{
		getObj('valor_biex2').value='1';
	}		
}
//
/*function proceso_ant()
{
 if(getObj('valor_anticipo').value=='1')
 {	
	getObj('valor_anticipo').value='0';
	getObj('cuentas_por_pagar_check_bi').disabled="";
	}else
 if(getObj('valor_anticipo').value=='0')
	{
		getObj('valor_anticipo').value='1';
		getObj('cuentas_por_pagar_check_bi').disabled="disabled";
	}		
}//*/
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_db_btn_consultar_beneficiario").click(function() {
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
		    url:"modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Empleados Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagar_db_proveedor_consulta").keypress(function(key)
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
							var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?busq_proveedor="+busq_proveedor;
							setBaraEstado(url);
						}

			}
		});
	//////					
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['Código','Beneficiario'],
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
									getObj('cuentas_por_pagar_db_empleado_codigo').value = ret.rif;
									getObj('cuentas_por_pagar_db_empleado_nombre').value = ret.beneficiario;
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

//consultas automaticas
//-consulta beneficiario
function consulta_automatica_benef()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_beneficiario_codigo_cxp.php",
			data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('cuentas_por_pagar_db_empleado_codigo').value = recordset[1];
				getObj('cuentas_por_pagar_db_empleado_nombre').value=recordset[1];
					}
				else
			 {  
			   	//getObj('cuentas_por_pagar_db_empleado_codigo').value ="";
				getObj('cuentas_por_pagar_db_empleado_nombre').value="";
				}
				
			 }
		});	 	 
}
//consultas automaticas de compromiso
function compromiso_cons()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_compromiso_codigo_cxp.php",
			data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		   
			    var recordset=html;	
				//alert(html);
			if((recordset!="")&&(recordset!="undefined")&&(recordset!="null"))
			{	
				recordset = recordset.split("*");
			//	setBarraEstado(recordset[5]);
				////
									
				////
					//getObj('cuentas_por_pagar_db_compromiso_n').value=recordset[1];
									getObj('cuentas_por_pagar_db_total').value=recordset[4];
									getObj('cuentas_por_pagar_db_orden_compra').value=recordset[2];
									getObj('valor_porcentaje_compromiso').value=recordset[8];
									getObj('opcion_iva').value=recordset[9];
									getObj('valor_tipo_doc').value=recordset[6];
									getObj('cuentas_por_pagar_db_restante').value=recordset[5];
									getObj('cuentas_por_pagar_db_proveedor_id').value=recordset[11];
									getObj('cuentas_por_pagar_db_proveedor_codigo').value=recordset[12];
									getObj('cuentas_por_pagar_db_proveedor_nombre').value=recordset[0];
								//	alert(recordset[13]);
								if(recordset[13]!=0)	
								{
										jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
										url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
										//alert(url);
										getObj('partida_celdas').style.display='';
										getObj('celdas_2').style.display='';
										getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';		
										getObj('monto_causar_comp').disabled="";	
										getObj('fecha_oculta').value=recordset[3];

								}
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_anticipos').value)) 
									{
										//tota1=ret.total;
										getObj('cuentas_por_pagar_db_monto_bruto').value=recordset[4];
										if(recordset[6]=="anticipo")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />El compromiso seleccionado ya cuenta con un anticipo creado, debe elegir otro tipo de documento</p></div>",true,true);
											limpieza_combinada_facturas();		
										}
									}else
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_fac').value)&&(recordset[6]=="anticipo"))
									{
											getObj('tr_amort').style.display='';
											getObj('tr_cxp_monto_anticipo').style.display='';
											getObj('cuentas_por_pagar_check_bi').disabled='disabled';
										
									}
										if(recordset[5]=="0,00")
										{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />No se cuenta con mas dinero comprometido para la orden seleccionada</p></div>",true,true);
												getObj('cuentas_por_pagar_db_compromiso_n').value="";
												getObj('cuentas_por_pagar_db_total').value="";
												getObj('cuentas_por_pagar_db_orden_compra').value="";	
										}
						valores_concat=getObj('cuentas_por_pagar_db_compromiso_n').value+"-"+getObj('cuentas_por_pagar_db_orden_compra').value;
						getObj('cuentas_por_pagar_integracion_desc_asiento').value=valores_concat;	
						
								
			}else
			{						getObj('cuentas_por_pagar_integracion_desc_asiento').value="";
									getObj('cuentas_por_pagar_db_compromiso_n').value="";
									getObj('cuentas_por_pagar_db_total').value="";
									getObj('cuentas_por_pagar_db_orden_compra').value="";
									getObj('valor_porcentaje_compromiso').value="";
									getObj('valor_tipo_doc').value="";
									getObj('cuentas_por_pagar_db_restante').value="";
									jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
									url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value;
									//getObj('partida_celdas').style.display='none';
									getObj('celdas_2').style.display='none';
									//getObj('cuentas_por_pagar_db_monto_bruto').disabled='false';
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_anticipos').value)) 
									{
										tota1=ret.total;
										getObj('cuentas_por_pagar_db_monto_bruto').value="";
									}else
									if(((getObj('cuentas_por_pagar_db_tipo_documento').value)==getObj('cuentas_por_pagar_db_fac').value)&&(recordset[6]=="anticipo"))
									{
											getObj('tr_amort').style.display='';
											getObj('tr_cxp_monto_anticipo').style.display='';
											getObj('cuentas_por_pagar_check_bi').disabled='disabled';
											
									}
			
			}						
		 }							
		});	 	
									
 }		 
//}

/*function amort()
{
	if(getObj('cuentas_por_pagar_db_tipo_documento').value==<?=$tipos_fact?>)
	{
		getObj('cuentas_por_pagar_check_anticipos').disabled="";
	}
	if((getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)||(getObj('valor_anticipo').value=='1'))
	{
		tota=getObj('cuentas_por_pagar_db_total').value.float();
		tota=tota.currency(2,',','.');
		getObj('tr_amort').style.display="";
		getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
		getObj('cuentas_por_pagar_check_extra2').disabled='disabled'
		if(getObj('valor_anticipo').value=='0')
		{
			getObj('cuentas_por_pagar_db_iva').value='0,00';
			getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
			getObj('cuentas_por_pagar_db_islr').value='0,00';
			getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
			getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
			getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
			getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
			getObj('cuentas_por_pagar_check_bi').disabled='disabled';
			getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
			getObj('cuentas_por_pagar_check_extra2').disabled='disabled';
			;
		}
	}else
	if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)
	{
		getObj('tr_amort').style.display="none";
		getObj('cuentas_por_pagar_db_monto_bruto').value='0,00';
		getObj('cuentas_por_pagar_check_extra1').disabled='';
		getObj('cuentas_por_pagar_check_extra2').disabled=''
	}
}
*/
////funcion de anticipos que activa al tipo de documento segun lo0 seleccionado en el combo
function anticipos()
{
	//alert("entro");
	getObj('cuentas_por_pagar_db_total').value="";
	getObj('cuentas_por_pagar_db_orden_compra').value="";
	getObj('cuentas_por_pagar_db_restante').value="";
	getObj('cuentas_por_pagar_db_compromiso_n').value="";
		getObj('celdas_2').style.display='none';
		getObj('cuentas_por_pagar_db_monto_bruto').disabled='';
	if(getObj('cuentas_por_pagar_db_tipo_documento').value==getObj('cuentas_por_pagar_db_anticipos').value)
	{	
		//getObj('cuentas_por_pagar_db_monto_bruto').disabled='false';
		if(getObj('valor_tipo_doc').value!="anticipo")
		{
			
			tota=getObj('cuentas_por_pagar_db_total').value.float();
			tota=tota.currency(2,',','.');
			//
			//alert("entro");
			//
			getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
			getObj('cuentas_por_pagar_check_extra2').disabled='disabled'
			//g - getObj('cuentas_por_pagar_db_iva').value='0,00';
			getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
			getObj('cuentas_por_pagar_db_islr').value='0,00';
			getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
			getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
			getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
			//getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
			getObj('cuentas_por_pagar_check_bi').disabled='disabled';
			getObj('cuentas_por_pagar_check_extra1').disabled='disabled';
			getObj('cuentas_por_pagar_check_extra2').disabled='disabled';
			getObj('tr_base_i').style.display='none'; 
			//g- getObj('tr_cxp_iva').style.display='none';
			//g- getObj('tr_cxp_sub_t').style.display='none';
			getObj('tr_cpx_retiva').style.display='none';
			getObj('tr_cxp_retislr').style.display='none';
			getObj('tr_ret_e').style.display='none';
			getObj('tr_cxp_total_sin_ret').style.display='none';
			getObj('monto_bruto').style.display='none';
			getObj('monto_bruto2').style.display='';
			getObj('tr_amort').style.display='none';
			getObj('tr_cxp_monto_anticipo').style.display='none';
		}else
		{
		
		setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />El compromiso seleccionado ya cuenta con un anticipo creado, debe elegir otro tipo de documento</p></div>",true,true);
		limpieza_combinada_facturas();
		
		}
	}else
	if(getObj('cuentas_por_pagar_db_tipo_documento').value!=getObj('cuentas_por_pagar_db_anticipos').value)
	{
		getObj('cuentas_por_pagar_db_monto_bruto').value='0,00';
		getObj('cuentas_por_pagar_check_bi').disabled='';
		getObj('cuentas_por_pagar_check_extra1').disabled='';
		getObj('cuentas_por_pagar_check_extra2').disabled='';
		getObj('monto_bruto2').style.display='none';
		getObj('monto_bruto').style.display='';
		//getObj('tr_base_i').style.display=''; 
		getObj('tr_cxp_iva').style.display='';
		getObj('tr_cxp_sub_t').style.display='';
		getObj('tr_cpx_retiva').style.display='';
		getObj('tr_cxp_retislr').style.display='';
		getObj('tr_ret_e').style.display='';
		getObj('tr_cxp_total_sin_ret').style.display='';
		/*getObj('celdas_2').style.display='';
		getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';
		jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha,page:1}).trigger("reloadGrid"); 
			url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha;
			alert(url);*/
	}
}
function reducir_subtotal()
{
//alert("entro");
//funcion creada para reducir el subtotal en ordenes extraordinarias
	monto=getObj('monto_causar_comp').value.float();
	iva_reducir=getObj('iva_multi').value.float();
	iva_reducir=(iva_reducir/100)+1;
	base_imposible=monto/(iva_reducir);
	base_imposible2=base_imposible.currency(2,',','.');
	getObj('monto_causar_comp').value=base_imposible2;
	//alert(base_imposible2);
//	alert(monto);alert(iva_reducir);
	/*alert(base_imposible);*/
	//getObj('monto_causar_comp').value=base_imposible.currency(2,',','.');
//
}
function restar_anticipo()
{
		base=getObj('cuentas_por_pagar_db_monto_bruto').value.float();
		iva=getObj('cuentas_por_pagar_db_iva').value.float();
		p_iva=base*(iva/100);
		total_anticipos=base+p_iva;
	
		total2=total_anticipos.currency(2,',','.');
		//
		getObj('cuentas_por_pagar_db_sub_total').value=total2;
		p_iva3=p_iva.currency(2,',','.');
		getObj('cuentas_por_pagar_db_monto_iva').value=p_iva3;
		getObj('cuentas_por_pagar_integracion_monto').value=getObj('cuentas_por_pagar_db_sub_total').value;
		//alert(getObj('cuentas_por_pagar_db_sub_total').value);

}
//////////////////////////////programacion de segunda pestaña 
function consulta_automatica_cuentas_contables_cxp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuentas_contables_codigo_cxp.php",
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
				getObj('cuentas_por_pagar_integracion_cuenta').value = recordset[1];
				getObj('cuentas_por_pagar_integracion_cuenta_id').value=recordset[0];
				getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=recordset[2];
								if(recordset[3]=='t')
									{
										getObj('cxp_comp_pr_activo').value=1;
									}
									if(recordset[4]=='t')
									{
										getObj('cxp_comp_pr_activo2').value=1;
									}
									if(recordset[5]=='t')
									{
										getObj('cxp_comp_pr_activo4').value=1;
									}
									if(recordset[6]=='t')
									{
										getObj('cxp_comp_pr_activo3').value=1;
									}
			///////////////////////////////////////////////////////////////////////////////////////////
			//proceso para llenar los campos de accion e, unidad ejec, proy
			llenar_campos_comprobante();
			///////////////////////////////////////////////////////////////////////////////////////////
			}
				else
				{
				getObj('cuentas_por_pagar_integracion_cuenta').value = "";
				getObj('cuentas_por_pagar_integracion_cuenta_id').value="";
				getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
				}
				
			 }
			
		});	 	 
}
///

function consulta_automatica_tipo_comprobante_cxp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo_comprobante_cxp.php",
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
			//	alert(recordset);
				if((recordset)&&(recordset!='vacio')&&(recordset!='undefined'))
				{
				recordset = recordset.split("*");
				getObj('cuentas_por_pagar_integracion_tipo').value = recordset[1];
				getObj('cuentas_por_pagar_integracion_tipo_id').value=recordset[0];
				getObj('cuentas_por_pagar_integracion_tipo_nombre').value=recordset[2];
							/*if(getObj('valor_anticipo').value=='0')
									{
										getObj('cuentas_por_pagar_db_iva').value='0,00';
										getObj('cuentas_por_pagar_db_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_islr').value='0,00';
										getObj('cuentas_por_pagar_db_monto_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_iva').value='0,00';
										getObj('cuentas_por_pagar_db_monto_ret_islr').value='0,00';
										getObj('cuentas_por_pagar_db_monto_bruto').value=tota;
							
									}*/

	//			setBarraEstado('modulos/tesoreria/cheques/pr/cmb.sql.orden_pago.php?nd='+nd+'&proveedor='+getObj('tesoreria_cheque_manuals_pr_proveedor_id').value+'&ncuenta='+getObj('tesoreria_cheque_manuals_db_n_cuenta').value+'&banco='+getObj('tesoreria_cheque_manuals_db_banco_id_banco').value);
			}else
			{
				getObj('cuentas_por_pagar_integracion_tipo').value = "";
				getObj('cuentas_por_pagar_integracion_tipo_id').value="";
				getObj('cuentas_por_pagar_integracion_tipo_nombre').value="";
			
			}
				
				
			 }
		});	 	 
}
$("#cuentas_por_pagar_integracion_btn_consultar_cuenta").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/cuentas_por_pagar/documentos/db/grid_cuenta_contable.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuenta Contables', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					//alert("entro01");
					var busq_cuenta= $("#consulta-cuenta-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-cuenta-contable-busqueda-partida").keypress(
					function(key)
					{
						
						dosearch3();													
					}
				);		
				function dosearch3()
				{
					if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
					if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(gridReload3,500)
				}
				function gridReload3()
				{
					//alert("entro");
					var busq_partida= $("#consulta-cuenta-contable-busqueda-partida").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_partida="+busq_partida,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_partida="+busq_partida;	
					//alert(url);
				}	
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
					//alert("entro");
					var busq_nom= $("#consulta-cuenta-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_nom="+busq_nom;	
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta;
                 //  alert(url);				
				}
			}
		}
	);						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_cuenta.php?nd='+nd,
								datatype: "json",
		colNames:['C&oacute;digo','Cuenta', 'Denominacion','Tipo','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utilizacion_fondos'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false},
									{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'requiere_utilizacion_fondos',index:'requiere_utilizacion_fondos', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#cuentas_por_pagar_integracion_cuenta').val(ret.cuenta_contable);
									$('#cuentas_por_pagar_integracion_cuenta_id').val(ret.id);
									$('#cuentas_por_pagar_integracion_descripcion_cuentas').val(ret.nombre);
								//alert(ret.requiere_auxiliar);
									if(ret.requiere_auxiliar=='t')
									{
										getObj('cxp_comp_pr_activo').value=1;
									}
									if(ret.requiere_utilizacion_fondos=='t')
									{
										getObj('cxp_comp_pr_activo2').value=1;
									}
									if(ret.requiere_proyecto=='t')
									{
										getObj('cxp_comp_pr_activo4').value=1;
									}
									if(ret.requiere_unidad_ejecutora=='t')
									{
										getObj('cxp_comp_pr_activo3').value=1;
									}
			///////////////////////////////////////////////////////////////////////////////////////////
			//proceso para llenar los campos de accion e, unidad ejec, proy
			llenar_campos_comprobante();
			///////////////////////////////////////////////////////////////////////////////////////////
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
$("#cuentas_por_pagar_integracion_btn_consultar_tipo").click(function() {
	var nd=new Date().getTime();
	/*setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar_prove.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de comprobante Contable', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
						///
setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_tipo.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val();  
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-auxiliar-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom;	
				//	alert(url);
				}
				//
				$("#consulta_tipo_cod").keypress(
					function(key)
					{
						
						dosearch();													
					}
				);
				$("#consulta_tipo_dem").keypress(
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
	    			var busq_cod= $("#consulta_tipo_cod").val();
					var busq_denom= $("#consulta_tipo_dem").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom,page:1}).trigger("reloadGrid");					
					url="modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?busq_cod="+busq_cod+"&busq_denom="+busq_denom;
					//alert(url);				
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
								url:'modulos/cuentas_por_pagar/documentos/db/sql_grid_tipo.php?nd='+nd,
								datatype: "json",
								colNames:['id','C&oacute;digo','Denominacion','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'comen',index:'comen', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#cuentas_por_pagar_integracion_tipo').val(ret.codigo);
									$('#cuentas_por_pagar_integracion_tipo_id').val(ret.id);
									$('#cuentas_por_pagar_integracion_tipo_nombre').val(ret.nombre);
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
//proceso para verificar si necesita cuenta de orden automatik en contabilidad
function proceso_aceptar_co()
{
		if(getObj('check_invisible').value=='1')
		{	
			getObj('check_invisible').value='0';
		}else
		 if(getObj('check_invisible').value=='0')
			{
				getObj('check_invisible').value='1';
			}		

}
function proceso_aceptar_co_mp()
{
	var nd=new Date().getTime();
	
	fecha=getObj('cuentas_por_pagar_db_fecha_f').value;
		if(getObj('cuentas_por_pagar_db_compromiso_n').value!="")
		{	
			if(getObj('check_invisible_mp').value=='1')
			{	
				getObj('check_invisible_mp').value='0';
				jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
								url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?iva="+getObj('cuentas_por_pagar_db_iva').value;
								getObj('partida_celdas').style.display='none';
								getObj('celdas_2').style.display='none';
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='false';
			}else
			 if(getObj('check_invisible_mp').value=='0')
				{
					getObj('check_invisible_mp').value='1';
					jQuery("#list_compro").setGridParam({url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value,page:1}).trigger("reloadGrid"); 
								url="modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?compromiso="+getObj('cuentas_por_pagar_db_compromiso_n').value+"&fecha="+fecha+"&iva="+getObj('cuentas_por_pagar_db_iva').value;
								getObj('partida_celdas').style.display='';
								getObj('celdas_2').style.display='';
								getObj('cuentas_por_pagar_db_monto_bruto').disabled='true';
				}		
		}
}
function restar_causado()
{
	if((getObj('valor_tipo_doc').value=='anticipo')&&(getObj('valor_anticipo').value!='1'))
								{
									var rest=getObj('cuentas_por_pagar_db_monto_ant').value;
									valores=getObj('monto_causar_comp').value;
									rest=rest.float()+valores.float();
									rest2=rest.currency(2,',','.');
									getObj('cuentas_por_pagar_db_monto_ant').value=rest2;
									//getObj('cuentas_por_pagar_db_base_imponible').value=rest2;

								}
								else
								{
									var rest=getObj('cuentas_por_pagar_db_monto_bruto').value;
									
									valores=getObj('monto_causar_comp').value;
									rest=rest.float()+valores.float();
									rest2=rest.currency(2,',','.');
									getObj('cuentas_por_pagar_db_monto_bruto').value=rest2;
								//	getObj('cuentas_por_pagar_db_base_imponible').value=rest2;
								}
									
									
									//alert(rest2);
									setTimeout(restar,10); restar();
}
function borrar_calculadora()
{
	getObj('cuentas_por_pagar_db_monto_ant').value="0,00";
	getObj('cuentas_por_pagar_amortizacion').value="0,00";
	getObj('cuentas_por_pagar_db_monto_bruto').value="0,00";
	getObj('cuentas_por_pagar_db_base_imponible').value="0,00";
	getObj('cuentas_por_pagar_db_iva').value="0,00";
	getObj('cuentas_por_pagar_db_base_imponible2').value="0,00";
	getObj('cuentas_por_pagar_db_iva2').value="0,00";
	getObj('cuentas_por_pagar_db_sub_total').value="0,00";
	getObj('cuentas_por_pagar_db_ret_iva').value="0,00";
	getObj('cuentas_por_pagar_db_islr').value="0,00";
	getObj('cuentas_por_pagar_db_monto_ret').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra2').value="0,00";
	getObj('cuentas_por_pagar_db_monto_neto').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra_dsc1').value="";
	getObj('cuentas_por_pagar_db_ret_extra_dsc2').value="0,00";
	getObj('cuentas_por_pagar_db_ret_extra2').value="0,00";
	getObj('cuentas_por_pagar_db_monto_neto').value="0,00";
getObj('valor_biex1').value="0";
getObj('valor_biex2').value="0";
getObj('valor_ret_ex2').value="0";
getObj('valor_ret_ex').value="0";
	
	}
$("#cuentas_por_pagar_integracion_btn_consultar_auxiliar_cmp").click(function() {
/*if((getObj('contabilidad_comp_pr_activo').value==1))
{*/
//esta enlazado con loas busquedas de contabilidad: 17/09/2012
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_auxiliar_mov.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-auxiliar-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-auxiliar-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-auxiliar-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom;	
					alert(url);
				}
				//
				$("#consulta-auxiliar-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-auxiliar-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-auxiliar-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom+"&cuenta="+getObj('cuentas_por_pagar_integracion_cuenta_id').value,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom+"&cuenta="+getObj('contabilidad_auxiliares_db_id_cuenta').value;
                 alert(url);				
				}
			}
		}
	);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////									
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('cuentas_por_pagar_integracion_cuenta_id').value,							
											datatype: "json",
											colNames:['id','c&oacute;digo','Denominaci&oacute;n'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
												{name:'denominacion',index:'denominacion', width:50,sortable:false,resizable:false},

													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#cuentas_por_pagar_integracion_auxiliar_p').val(ret.cuenta_contable);
												$('#cuentas_por_pagar_integracion_id_aux').val(ret.id);
												$('#cuentas_por_pagar_integracion_auxiliar_desc').val(ret.denominacion);
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
////////////////////////////////////
//}									
});	
//busqueda de accion centralizada
$("#cxp_btn_cmp_consultar_acc").click(function() {

if(getObj('cxp_comp_pr_activo4').value==1)
{	
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_acc.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-acc-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-acc-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-acc-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-acc-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-acc-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-acc-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
///////////////////////////////////////////////////////////////////////////////////						
						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Accion Central'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cxp_pr_acc_id').value = ret.id;
									getObj('cxp_pr_acc').value = ret.codigo;
									getObj('cxp_pr_acc_desc').value=ret.denominacion;
									//getObj('cxp_centro_costo_id').value = "0";
									getObj('cxp_pr_centro_costo').value="0";
									getObj('cxp_centro_costo_desc').value="NO APLICA";
									getObj('cxp_centro_costo_id').value="";
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
//busqueda de ubicacion
$("#contabilidad_comprobante_btn_consultar_ubicacion_cmp").click(function() {
if(getObj('contabilidad_comp_pr_activo3').value==1)
{	
				/*var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});*/
									var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_ubicacion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-ubic-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-ubic-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec.php',							
											datatype: "json",
											colNames:['id','c&oacute;digo','Unidad'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
												{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_comp_pr_ubicacion').val(ret.codigo);
												$('#contabilidad_comp_pr_ejec_id').val(ret.id);
												$('#contabilidad_comp_pr_ubicacion_desc').val(ret.unidad);
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
}
});
//busqueda automatica de unidad ejecutora
function consulta_ubicacion_mov_cxp()
{
valores3=getObj('cxp_comp_pr_activo3').value;	
//alert(valores);	
if(valores3!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_ubic.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('cxp_pr_ejec_id').value=recordset[0];
								getObj('cxp_pr_ubicacion_desc').value=recordset[2];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('cxp_pr_ubicacion').value='';
								getObj('cxp_pr_ejec_id').value='';
								getObj('cxp_pr_ubicacion_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('cxp_pr_ubicacion_desc').value='';
		  getObj('cxp_pr_ejec_id').value='';

	}

}
//busqueda de ubicacion(unidad_ejecutora)
$("#cxp_btn_consultar_ubicacion_cmp").click(function() {
if(getObj('cxp_comp_pr_activo3').value==1)
{	
				/*var nd=new Date().getTime();
				setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
				$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
									function(data)
									{								
											dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
											setTimeout(crear_grid,100);
									});*/
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_ubicacion.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Ubicaci&oacute;n', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec2.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-ubic-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.accion_central.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec2.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-ubic-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-ubic-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-ubic-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec2.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec2.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
									function crear_grid()
									{
										jQuery("#list_grid_"+nd).jqGrid
										({
											width:800,
											height:300,
											recordtext:"Registro(s)",
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_unidad_ejec2.php',							
											datatype: "json",
											colNames:['id','c&oacute;digo','Unidad'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
												{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#cxp_pr_ubicacion').val(ret.codigo);
												$('#cxp_pr_ejec_id').val(ret.id);
												$('#cxp_pr_ubicacion_desc').val(ret.unidad);
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
}
});
//busqueda de utf
function consulta_utf_mod_cxp()
{
valores2=getObj('cxp_comp_pr_activo2').value;	
//alert(valores);	
if(valores2!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_utf3.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
						//	alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('cxp_comp_pr_utf_id').value=recordset[0];
								getObj('cxp_comp_pr_utf_desc').value=recordset[2];
							}
							else
							if(recordset=='vacio')
							{	
								getObj('cxp_comp_pr_utf_id').value='';
								getObj('cxp_comp_pr_utf').value='';
								getObj('cxp_comp_pr_utf_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('cxp_comp_pr_utf_id').value='';
		  getObj('cxp_comp_pr_utf').value='';
	}
}
//busqueda utilizacion de fondos
$("#cxp_btn_consultar_utf").click(function() {
if(getObj('cxp_comp_pr_activo2').value==1)
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_utf.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de UTF', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-utf-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-utf-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-utf-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-utf-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-utf-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-utf-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
             ///  alert(url);				
				}
			}
		}
	);
		
										function crear_grid()
										{
											jQuery("#list_grid_"+nd).jqGrid
											({
												width:800,
												height:300,
												recordtext:"Registro(s)",
												loadtext: "Recuperando Información del Servidor",		
												url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_utf.php',							
												datatype: "json",
												colNames:['id','C&oacute;digo','Unidad'],
												colModel:[
													{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
													{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
													{name:'unidad',index:'unidad', width:50,sortable:false,resizable:false},
														],
												pager: $('#pager_grid_'+nd),
												rowNum:20,
												rowList:[20,50,100],
												imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
												onSelectRow: function(id){
												var ret = jQuery("#list_grid_"+nd).getRowData(id);
													$('#cxp_comp_pr_utf').val(ret.codigo);
													$('#cxp_comp_pr_utf_id').val(ret.id);
													$('#cxp_comp_pr_utf_desc').val(ret.unidad);
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
}
});
//busqueda de proyecto
$("#cxp_btn_consultar_proyecto_cmp").click(function() {
if(getObj('cxp_comp_pr_activo4').value==1)
{
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/modificacion_presupuesto/pr/grid_presupuesto_ley.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Accion Central', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_presupuesto_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: 
			function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Proyectos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload()
				{ 
					var busq_cuenta= $("#consulta-proyecto-contable-busqueda-nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
				}					
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//
				$("#consulta-proyecto-contable-busqueda2").keypress(
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
						timeoutHnd = setTimeout(gridReload,500)
				}				
				function gridReload2()
				{
					var busq_nom= $("#consulta-proyecto-contable-busqueda2").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nom="+busq_nom;	
				//	alert(url);
				}
				//
				$("#consulta-proyecto-contable-busqueda-nombre").keypress(
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
	    			var busq_nom= $("#consulta-proyecto-contable-busqueda2").val();
					var busq_cuenta= $("#consulta-proyecto-contable-busqueda-nombre").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php?busq_cuenta="+busq_cuenta+"&busq_nombre="+busq_nom;
                //alert(url);				
				}
			}
		}
	);
///////////////////////////////////////////////////////////////////////////////////						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql.proyecto.php',
								datatype: "json",
								colNames:['Id','C&oacute;digo', 'Proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cxp_pr_acc_id').value ="0";
									getObj('cxp_pr_acc').value ="0" ;
									getObj('cxp_pr_centro_costo').value = ret.codigo;
									getObj('cxp_centro_costo_id').value = ret.id;
									getObj('cxp_centro_costo_desc').value = ret.denominacion;
									getObj('cxp_pr_acc').value="0";
									getObj('cxp_pr_acc_desc').value="NO APLICA";
									getObj('cxp_pr_acc_id').value="";									

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
//busqueda de acc
function consulta_acc_mov_cxp()
{
	valores4=getObj('cxp_comp_pr_activo4').value;	
//alert(valores);	
if(valores4!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_acc3.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('cxp_pr_acc_id').value=recordset[0];
								getObj('cxp_pr_acc_desc').value=recordset[2];
								getObj('cxp_pr_centro_costo').value="0";
								getObj('cxp_centro_costo_desc').value="NO APLICA";
								getObj('cxp_centro_costo_id').value="";
							}
							else
							if(recordset=='vacio')
							{	
								getObj('cxp_pr_acc_id').value='';
								getObj('cxp_pr_acc').value='';
								getObj('cxp_pr_acc_desc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('cxp_pr_acc_id').value='';
		  getObj('cxp_pr_acc').value='';

	}
	
}
//busqueda de proyecto
function consulta_proyecto_mov_cxp()
{
valores4=getObj('cxp_comp_pr_activo4').value;	
//alert(valores);	
if(valores4!=0)
{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_proy3.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								getObj('cxp_centro_costo_id').value=recordset[0];
								getObj('cxp_centro_costo_desc').value=recordset[2];
								getObj('cxp_pr_acc').value="0";
								getObj('cxp_pr_acc_desc').value="NO APLICA";
								getObj('cxp_pr_acc_id').value="";
							}
							else
							if(recordset=='vacio')
							{	
								getObj('cxp_centro_costo_desc').value='';
								getObj('cxp_centro_costo_id').value='';
								getObj('cxp_pr_acc').value='';
							}
						 }
					});	 	
	}
	else
	{	
		  getObj('cxp_centro_costo_desc').value='';
		  getObj('cxp_centro_costo_id').value='';

	}
	
}
//busqueda de auxiliares
function auxiliares_consulta_mov_cxp()
{
valores=getObj('cxp_comp_pr_activo').value;	
//alert(valores);	
if((valores!=0)&&(getObj('cuentas_por_pagar_integracion_cuenta_id').value!=''))
	{	
		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_auxi3.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
							//alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								//getObj('').value = recordset[0];
								getObj('cuentas_por_pagar_integracion_id_aux').value=recordset[0];
								getObj('cuentas_por_pagar_integracion_auxiliar_desc').value=recordset[4];
								//getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
								/*getObj('contabilidad_auxiliares_db_nombre').value=recordset[4];
								getObj('contabilidad_auxiliares_db_comentario').value=recordset[5];
								getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
								getObj('contabilidad_auxiliares_db_desc').value=recordset[6];*/
							}
							else
							if(recordset=='vacio')
							{	
								getObj('cuentas_por_pagar_integracion_id_aux').value='';
								getObj('cuentas_por_pagar_integracion_auxiliar_p').value='';
								getObj('contabilidad_comp_pr_auxiliar_desc').value='';

							}
							
						 }
					});	 	
	}
	else
	{	
		  getObj('contabilidad_comp_pr_auxiliar').value='';
		  getObj('contabilidad_comp_contabilidad_id').value='';

	}
}
/////////////////////////////////////////////////////////////////////////////
//actualizar o modifcar asientos
$("#btn_cxp_actualizar_comprobante_contable").click(function(){
if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
{
///////////////////////////////////////////////////////////////	

								setBarraEstado(mensaje[esperando_respuesta]);
									$.ajax (

									{
										url: "modulos/cuentas_por_pagar/documentos/db/sql.actualizar_comprobante.php",
										data:dataForm('form_cuentas_por_pagar_db_documentos'),
										type:'POST',
										cache: false,
										success: function(html)
										{
											recordset=html;
											recordset = recordset.split("*");
											//alert(html);
											if (recordset[0]=="Registrado")
											{
											////////////////////////////////////////
											setBarraEstado(mensaje[actualizacion_exitosa],true,true);
											getObj('btn_cxp_agregar_comprobante_contable').style.display='';
											getObj('btn_cxp_actualizar_comprobante_contable').style.display='none';
											
													getObj('cuentas_por_pagar_numero_comprobante_integracion').value=recordset[5];
													getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value=recordset[4];
													numero_comprobante=getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value;												
													jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante;
													//alert(url);
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';													
													getObj('cuentas_por_pagar_integracion_total_debe').value=recordset[1];
													getObj('cuentas_por_pagar_integracion_total_haber').value=recordset[2];
													getObj('cxp_comp_pr_dif').value=recordset[3];
													//if(getObj('cxp_comp_pr_dif').value=='0,00')
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													limpiar_algunos_cxp();
											///////////////////////////////////////
											}
											else if (recordset[0]=="NoActualizo")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NO SE REALIZÓ LA OPERACIÓN</p></div>",true,true);
												
											}
											else if (recordset[0]=="numero_existe")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />NÚMERO DE COMPROBANTE YA UTILIZADO</p></div>",true,true);
												
											}
											else if (recordset[0]=="documento_cerrado")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />DOCUMENTO CERRADO</p></div>",true,true);
											}
											else if (recordset[0]=="modulo cerrado")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />M&Oacute;DULO CERRADO</p></div>",true,true);
												
											}else
											 if (recordset[0]=="no_ayo")
											{
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />FECHA ERRADA </p></div>",true,true);
																		}		
											else
											{
												setBarraEstado(recordset[0]);
												getObj('contabilidad_comp_pr_numero_comprobante').value=valor;
											}
										}
									});

///////////////////////////////////////////////////////////////
}//	if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);
								
});
/////////////////////////////////////////////////////////////////////////////////////
//proceso que llena los campos del comprobante
function llenar_campos_comprobante()
{
	
		$.ajax (
					{
						url: "modulos/cuentas_por_pagar/documentos/db/sql_llenar_comprobante.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'),
						type:'POST',
						cache: false,
						success: function(html)
						{
						//alert(html);
						recordset=html;
						recordset = recordset.split("*");
						//alert(html);
						if (recordset[0]=="bien")
						{
							getObj('cxp_centro_costo_id').value=recordset[1];
							getObj('cxp_pr_centro_costo').value=recordset[2];
							getObj('cxp_centro_costo_desc').value=recordset[3];
							getObj('cxp_pr_ejec_id').value=recordset[5];
							getObj('cxp_pr_ubicacion').value=recordset[6];
							getObj('cxp_pr_ubicacion_desc').value=recordset[7];
						
						}
							
							else
							{
								setBarraEstado(html,true,true);					
							}			
						}
					});
}
/////////////////////////////////////////////////////////////////////////////////////
//LIMPIAR
$("#btn_cxp_limpiar").click(function(){
	//alert("entro");
	limpiar_algunos_cxp();
});
$("#btn_cxp_eliminar_registro").click(function(){
if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
{
///////////////////////////////////////////////////////////////	


setBarraEstado(mensaje[esperando_respuesta]);
Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ESTA ABSOLUTAMENTE SEGURO QUE DESEA ELIMINAR EL REGISTRO SELECCIONADO?</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
{
if(val=='ACEPTAR')
{	
					setBarraEstado(mensaje[esperando_respuesta]);
					$.ajax (
					{
						url: "modulos/cuentas_por_pagar/documentos/db/sql_eliminar_comprobante.php",
						data:dataForm('form_cuentas_por_pagar_db_documentos'),
						type:'POST',
						cache: false,
						success: function(html)
						{
						//alert(html);
						recordset=html;
						recordset = recordset.split("*");
				//	alert(html);
						if (recordset[0]=="Eliminado")
						{
											setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />ELIMINACI&Oacute;N EXITOSA</p></div>",true,true);
											getObj('btn_cxp_agregar_comprobante_contable').style.display='';
											getObj('btn_cxp_actualizar_comprobante_contable').style.display='none';
											
													numero_comprobante=recordset[4];												
													getObj('cuentas_por_pagar_numero_pr_numero_comprobante2').value=recordset[4];
													getObj('cuentas_por_pagar_numero_comprobante_integracion').value=recordset[5];
													if(numero_comprobante=="")
													{
														numero_comprobante=0;
														//bloqueo la fecha
														getObj('cuentas_por_pagar_db_boton_fd').style.display='';
														getObj('cuentas_por_pagar_db_facturas_lista2').style.display='';
														getObj('cuentas_por_pagar_db_facturas_lista').value='';
														getObj('cuentas_por_pagar_db_facturas_lista2').value='';
														jQuery("#list_facturas").setGridParam({url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value,page:1}).trigger("reloadGrid");
						url='modulos/cuentas_por_pagar/documentos/db/cmb.sql.documento.php?nd='+new Date().getTime()+'&compromiso='+getObj('cuentas_por_pagar_db_compromiso_n').value+'&id_proveedor='+getObj('cuentas_por_pagar_db_proveedor_id').value+'&ano='+getObj('cuentas_por_pagar_db_ayo').value+'&tipo='+getObj('cuentas_por_pagar_db_tipo_documento').value;
														//
													}
													jQuery("#list_comprobante_cxp").setGridParam({url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante,page:1}).trigger("reloadGrid");
													url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+numero_comprobante;
													//alert(url);
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';													
													getObj('cuentas_por_pagar_integracion_total_debe').value=recordset[1];
													getObj('cuentas_por_pagar_integracion_total_haber').value=recordset[2];
													getObj('cxp_comp_pr_dif').value=recordset[3];
													//if(getObj('cxp_comp_pr_dif').value=='0,00')
													//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
													limpiar_algunos_cxp();
											///////////////////////////////////////

						
						}
							else if (html=="ExisteRelacion")
							{
								setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png' />IMPOSIBLE ELIMINAR</p></div>",true,true);
							}
							else
							{
								setBarraEstado(html,true,true);					
							}			
						}
					});
}
else
setBarraEstado("");
});
//////////////////////									
///////////////////////////////////////////////////////////////
}//fin de if(getObj('cuentas_por_pagar_db_documentos_abrir_cerrar').value!='2')
else
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png' />DOCUMENTO CERRADO</p></div>",true,true);

});
//////////////////////////////////////////////////////////////////////////////////////
//verficando si el monto ante excede  al del compromiso
/*function verificar_mont()
{*/
	
	
	/*alert(parseFloat(getObj('cuentas_por_pagar_db_monto_ant').value));
	alert(parseFloat(getObj('cuentas_por_pagar_db_restante').value));
	
	if(parseFloat(getObj('cuentas_por_pagar_db_monto_ant').value)>parseFloat(getObj('cuentas_por_pagar_db_restante').value))
	{
		getObj('cuentas_por_pagar_db_monto_ant').value="0,00";
	}else
	alert("no entero");*/
//}//
//grid debe/haber
//-------------------------------------------------------------------------------------------------------------------------------------
var lastsel; 
url:"modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php";
$("#list_compro").jqGrid({ 
	height:115,
	width:550,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/documentos/db/sql.partidas_comp.php?nd='+new Date().getTime(),
	datatype: "json",
   	colNames:['Id','Partida','Monto Comp','Monto Causado','Monto a Causar','Monto BI','Facturado por partida','partidas','Estatus'],
								colModel:[
									{name:'id',index:'id', width:20,sortable:false,resizable:false},	
									{name:'partida',index:'partida', width:20,sortable:false,resizable:false},	
									{name:'monto',index:'monto', width:25,sortable:false,resizable:false},
									{name:'monto2',index:'monto2', width:20,sortable:false,resizable:false,hidden:true},
									{name:'monto3',index:'monto3', width:20,sortable:false,resizable:false},
									{name:'monto4',index:'monto4', width:20,sortable:false,resizable:false},
									{name:'monto5',index:'monto5', width:29,sortable:false,resizable:false},
									{name:'partidas',index:'partidas', width:20,sortable:false,resizable:false,hidden:true},
									{name:'estatus',index:'estatus', width:11,align:"center",sortable:false,resizable:false}

								],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
	pager: jQuery('#pager_compro'),
   	sortname: 'id',
	viewrecords: true,
	sortorder: "desc",
	onSelectRow: function(id){ //alert(id);
		var ret = jQuery("#list_compro").getRowData(id);
		var	compromiso=getObj('cuentas_por_pagar_db_compromiso_n').value;
		idd="";lastsel="";
		idd = ret.id;
		partida=ret.partida;
		if(idd && idd!==lastsel){
		$.ajax({
			url:'modulos/cuentas_por_pagar/documentos/db/sql.partidas_compromiso.php?id='+idd+'&fecha='+fecha+'&compromiso='+compromiso+'&iva='+getObj('cuentas_por_pagar_db_iva').value+'&id_factura='+getObj('cuentas_por_pagar_db_id').value+'&partida='+partida,
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		   url='modulos/cuentas_por_pagar/documentos/db/sql.partidas_compromiso.php?id='+idd+'&fecha='+fecha+'&compromiso='+compromiso+'&id_factura='+getObj('cuentas_por_pagar_db_id').value+'&partida='+partida;
			    var recordset=html;
				//alert(html);
					
//alert(html);
				//alert(url);		
				recordset = recordset.split("*");
				//**** partida/generica
				getObj('partida_comp').value=recordset[0];
				//getObj('monto_causar_comp').value=recordset[1];
				//***** monto del compromiso
				getObj('comprometido').value=recordset[2];
			//	getObj('comprometido').value=;
				//**** monto total q se ha facturado para esa partida en diferentes documentos con el mismo compromiso
				getObj('monto_causar_comp2').value=recordset[6];
				//**** monto a guardarse de la factura
				getObj('monto_causar_comp').value=recordset[7];//5
				//**** el monto o valor de la orden de compra o servicio usado para las validaciones de si excede el monto 
				getObj('valor_maximo').value=recordset[8];
				//**** valor facturados en otras facturas para esa partida
				getObj('valor_fact').value=recordset[10];
				//***** vector de las partidas usadas para almacenar informacion
				getObj('cuentas_por_pagar_imp3').value=recordset[11];
				//getObj('fact_partida').value=recordset[6];	
				///verificio si la base imponible es difrente al total+iva...para q no aparezca el cuadro q reduce el total a la base imponible
				if(recordset[8]!=recordset[5])
				{
				
					/*alert(recordset[8]);
					alert(recordset[2]);*/
					//campos adicionados 01/03/2012 para modificar las tablas de ordenes en caso de ordenes especiales
					getObj('iva_multi').value="0,00";
					getObj('cuadro_iva').style.display='none';
				}
				//
				///verificio si la base imponible es difrente al total+iva...para q no aparezca el cuadro q reduce el total a la base imponible
				if((recordset[8]==recordset[2])&&(getObj('cuentas_por_pagar_db_cuentas_orden').value=="1"))
				{
				
					//campos adicionados 01/03/2012 para modificar las tablas de ordenes e cas e ordenes especiales
					getObj('iva_multi').value="0,00";
					getObj('cuadro_iva').style.display='';
					getObj('iva_multi').style.display='';
	
				}
				//
				//verifico si hay dos bases imposible...el valor a causado es la suma de estas
				if((getObj('valor_bi1').value=="1")&&(getObj('valor_bi2').value=="1"))
				{
					bi1=getObj('cuentas_por_pagar_db_base_imponible').value.float();
					bi2=getObj('cuentas_por_pagar_db_base_imponible2').value.float();
					///////////////////////validacion
				/////si la suma de las bases imponibles+iva es difrente de monto comprometido no se puede procesar
					
					ivas1=getObj('cuentas_por_pagar_db_monto_iva').value.float();
					ivas2=getObj('cuentas_por_pagar_db_monto_iva2').value.float();
					total=bi1+ivas1+bi2+ivas2;
					/*if(total!=recordset[2])
					{	
						alert(total);
						alert(recordset[2]);
						alert("No se puede procesar");
					}
					else
					{*/
				///////////////////////////////
					causa=bi1+bi2;
					//no aparecen
					getObj('iva_multi').value="0,00";
					getObj('cuadro_iva').style.display='none';
					//
					getObj('monto_causar_comp_v').style.display="";
					getObj('monto_causar_comp').value=recordset[2];//solo si es factura cn doble iva el campo sera iual al valor del cmpromiso...
					getObj('monto_causar_comp').style.display="none";
				//	getObj('monto_causar_comp_v').disabled='false';
					getObj('monto_causar_comp_v').value=causa.currency(2,',','.');
					//}//elseif(total!=recordset[2])
			
				}
				//
			}	
		
		});	
		}
        	},
}); 
jQuery("#list_compro").jqGrid('navGrid',"#pager_compro",{edit:false,add:false,del:false});
/*var lastsel,idd,monto;
$("#list_integracion").jqGrid({
	height:115,
	width:550,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/cuentas_por_pagar/documentos/pr/cmb.sql_integracion_contable.php?nd='+new Date().getTime(),
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Comentarios','Cuenta Contable','Desc','REF','Debito','Credito','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true},
			{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true},
			{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true},
			{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:20,hidden:true},
			{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
			{name:'secuencia',index:'secuencia', width:20,hidden:true},
			{name:'comentarios',index:'comentarios', width:20,hidden:true},
			{name:'cuenta_contable',index:'cuenta_contable',width:45},
			{name:'descripcion',index:'descripcion',width:60},
			{name:'ref',index:'ref',width:20},
			{name:'monto_debito',index:'monto_debito',width:40},
			{name:'monto_credito',index:'monto_credito',width:40},
			{name:'fecha_comprobante',index:'fecha_comprobante',width:30,hidden:true,hidden:true},
			{name:'codigo_auxiliar',index:'codigo_auxiliar',width:30,hidden:true},
			{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:30,hidden:true},
			{name:'codigo_proyecto',index:'codigo_proyecto',width:30,hidden:true,hidden:true},
			{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:30,hidden:true}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_integracion'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,

	
   



}).navGrid("#pager_integracion",{search :false,edit:false,add:false,del:false});*/
var lastsel,idd,monto;
$("#list_comprobante_cxp").jqGrid({
	height: 100,
	width: 570,
	recordtext:"Consulta(s)",
	loadtext: "Recuperando Información del Servidor",
	url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_movimientos_contables_manuales.php?nd='+new Date().getTime()+"&numero_comprobante="+getObj('cuentas_por_pagar_numero_comprobante_integracion').value,
//	+'&islr='+getObj('tesoreria_cheques_pr_ret_islr').value
	datatype: "json",
	colNames:['Id','Organismo','Ano','Mes','tipo','Comprobante','Secuencia','Cuenta Contable','Cuenta Contable','Desc','REF','Debe','Haber','Fecha Comprobante','CodAux','CodUbic','Codcost','CodUFondos','id_cc','codigo_tipo_comp','estatus','req_aux','req_ueject','req_proyecto','req_utf','desc2'],
   	colModel:[
	   		{name:'id',index:'id', width:20,hidden:true},
	   		{name:'codigo_organismo',index:'codigo_organismo', width:20,hidden:true},
			{name:'ano_comprobante',index:'ano_comprobante', width:20,hidden:true},
			{name:'mes_comprobante',index:'mes_comprobante', width:20,hidden:true},
			{name:'codigo_tipo_comprobante',index:'codigo_tipo_comprobante', width:20,hidden:true},
			{name:'numero_comprobante',index:'numero_comprobante', width:20,hidden:true},
			{name:'secuencia',index:'secuencia', width:20,hidden:true},
			{name:'cuenta_contable2',index:'cuenta_contable',width:70},
			{name:'cuenta_contable',index:'cuenta_contable',width:70,hidden:true},
			{name:'descripcion',index:'descripcion',width:70},
			{name:'ref',index:'ref',width:20},
			{name:'monto_debito',index:'monto_debito',width:50},
			{name:'monto_credito',index:'monto_credito',width:50},
			{name:'fecha_comprobante',index:'fecha_comprobante',width:50,hidden:true,hidden:true},
			{name:'codigo_auxiliar',index:'codigo_auxiliar',width:50,hidden:true},
			{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora',width:50,hidden:true},
			{name:'codigo_proyecto',index:'codigo_proyecto',width:50,hidden:true,hidden:true},
			{name:'codigo_utilizacion_fondos',index:'codigo_utilizacion_fondos',width:50,hidden:true},
			{name:'id_cc',index:'id_cc',width:50,hidden:true},
			{name:'codigo_tipo_comp',index:'codigo_tipo_comp',width:50,hidden:true},
			{name:'estatus',index:'estatus',width:50,hidden:true},
			{name:'req_aux',index:'req_aux',width:50,hidden:true},
			{name:'req_ueject',index:'req_ueject',width:50,hidden:true},
			{name:'req_proyecto',index:'req_proyecto',width:50,hidden:true},
			{name:'req_utf',index:'req_utf',width:50,hidden:true},
			{name:'descripcion2',index:'descripcion2',width:60,hidden:true}
   	],
	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
   	pager: jQuery('#pager_comprobante_cxp'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	
	
   
	//multiselect: true,
	
		onSelectRow: function(id){
		var ret = jQuery("#list_comprobante_cxp").getRowData(id);
	//alert("modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value);
		//setBarraEstado("modulos/adquisiones/cotizacion/pr/sql.actualizar.php?id="+idd+"&monto="+monto);
		/*if(getObj('contabilidad_comp_pr_numero_comprobante').value!="")
		{*/
		/*getObj('contabilidad_comp_pr_tipo_id').value=ret.codigo_tipo_comprobante
		getObj('contabilidad_comp_pr_tipo').value=ret.codigo_tipo_comp
		getObj('contabilidad_comp_id_comprobante').value=ret.id*/
//}
		idd="";lastsel="";
		idd = ret.id;
		if(idd && idd!==lastsel){//	alert(idd);
		$.ajax({
			url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes_auto.php?nd='+new Date().getTime()+"&id="+idd,
			//url:"modulos/adquisiones/cotizacion/co/sql.consulta_selec.php?id="+idd+"&nro="+getObj('cotizacones_pr_numero_cotizacion').value,
            data:dataForm('form_cuentas_por_pagar_db_documentos'), 
			type:'GET',
			cache: false,
			 success:function(html)
			 {
       		   url='modulos/contabilidad/movimientos_contables/pr/cmb.sql_comprobantes_auto.php?nd='+new Date().getTime()+"&id="+idd;


			    var recordset=html;			

			//	 alert(url);
		
	  			//alert(recordset);				
				recordset = recordset.split("*");
				//getObj('contabilidad_comp_pr_fecha_boton_d').disabled="true";
				//getObj('contabilidad_comp_pr_fecha_boton_d').value=ret.fecha_comprobante;
			/*	getObj('activo').value='ACTIVO';
				getObj('contabilidad_comp_pr_debe_haber').disabled='disabled';
				getObj('contabilidad_comp_pr_cuenta_contable').disabled='disabled';
				getObj('cuenta_nombre').disabled='disabled';
				getObj('contabilidad_comp_pr_auxiliar').disabled='disabled';*/
				getObj('btn_cxp_actualizar_comprobante_contable').style.display='';
				getObj('btn_cxp_eliminar_registro').style.display='';
				getObj('btn_cxp_limpiar').style.display='';
				getObj('btn_cxp_agregar_comprobante_contable').style.display='none';
				getObj('cxp_id_comprobante').value = recordset[0];
				//getObj('contabilidad_comp_pr_numero_comprobante').value = recordset[5];
				getObj('cuentas_por_pagar_integracion_cuenta').value=recordset[8];
				getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value=recordset[32];
				getObj('cuentas_por_pagar_integracion_ref').value=recordset[10];
				getObj('cuentas_por_pagar_integracion_desc_asiento').value=recordset[7];
				debito_credito=1;
				if(recordset[11]!="0,00")
				{
					debito_credito=1;
					getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value=recordset[11];
					
				}else
				if(recordset[12]!="0,00")
				{
					debito_credito=2;
					getObj('cuentas_por_pagar_debe_haber_integracion_monto_p').value=recordset[12];
				}	
				getObj('cuentas_por_pagar_debe_haber_integracion_estatus').value=debito_credito;

				
				getObj('cuentas_por_pagar_integracion_auxiliar_p').value=recordset[24];
				getObj('cuentas_por_pagar_integracion_auxiliar_desc').value=recordset[35];
				getObj('cxp_pr_ubicacion').value=recordset[22];
				getObj('cxp_pr_ubicacion_desc').value=recordset[33];
				getObj('cxp_comp_pr_utf_desc').value=recordset[34];
				getObj('cxp_pr_centro_costo').value=recordset[26];
				getObj('cxp_centro_costo_desc').value=recordset[36];
				getObj('cxp_pr_acc_desc').value=recordset[37];
				getObj('cxp_comp_pr_utf').value=recordset[23];
				getObj('cuentas_por_pagar_integracion_cuenta_id').value=recordset[17];
				
				//////////////////////////
				getObj('cxp_pr_ejec_id').value=recordset[14];
				getObj('cxp_comp_pr_utf_id').value=recordset[16];
				getObj('cuentas_por_pagar_integracion_id_aux').value=recordset[13];
				//getObj('').value=recordset[13];
				getObj('cxp_centro_costo_id').value=recordset[15];
				getObj('cxp_pr_acc').value=recordset[25];
				getObj('cxp_pr_acc_id').value=recordset[27];
				
				// getObj('movimientos_contables_db_btn_eliminar').style.display='';
				// getObj('movimientos_contables_db_btn_eliminar2').style.display='';
//
				///////////////////////////
				/////////////////////////////
				if(recordset[21]==0)
				{
					//getObj('cuentas_por_pagar_integracion_estatus').value="Abierto";
					//getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='none';
					if ((getObj('cuentas_por_pagar_integracion_total_debe').value)==(getObj('cuentas_por_pagar_integracion_total_haber').value))
					{
					if(((getObj('cuentas_por_pagar_integracion_total_debe').value)!="0,00")&&((getObj('cuentas_por_pagar_integracion_total_haber').value)!="0,00"))
					//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='';
					getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='none';
                  

					}
				}
				else
				{
					//getObj('cuentas_por_pagar_integracion_estatus').value="Cerrado";
					//getObj('contabilidad_db_comprobante_manual_btn_abrir').style.display='';
					//getObj('contabilidad_db_comprobante_manual_btn_cerrar').style.display='none';
					getObj('cuentas_por_pagar_db_documentos_btn_imprimir_comprobante').style.display='';


				}
				///// activando condiciones de campos ocultos
				getObj('cuentas_por_pagar_integracion_desc_asiento').value=recordset[32];
				

			if(recordset[28]=='t')
			{
				getObj('cxp_comp_pr_activo').value=1;
			}
			if(recordset[31]=='t')
			{
				getObj('cxp_comp_pr_activo2').value=1;
			}
			
			if(recordset[29]=='t')
			{
				getObj('cxp_comp_pr_activo3').value=1;
			}
			
			if(recordset[30]=='t')
			{
				getObj('cxp_comp_pr_activo4').value=1;
			}
			if(recordset[28]=='f')
			{
				getObj('cxp_comp_pr_activo').value=0;
			}
			if(recordset[31]=='f')
			{
				getObj('cxp_comp_pr_activo2').value=0;
			}
			
			if(recordset[29]=='f')
			{
				getObj('cxp_comp_pr_activo3').value=0;
			}
			
			if(recordset[30]=='f')
			{
				getObj('cxp_comp_pr_activo4').value=0;
			}
				getObj('cuentas_por_pagar_integracion_tipo').value=recordset[20];
				getObj('cuentas_por_pagar_integracion_tipo_id').value=recordset[4];
			//estos sean los botones para agregar actualizar y eliminar comprobantes contables
			/*	getObj('cxp_movimientos_pr_btn_guardar_comp').style.display='none';	
				getObj('cxp_movimientos_pr_btn_cancelar_comp').style.display='';
				getObj('cxp_movimientos_pr_btn_actualizar_comp').style.display='';
				*/
		
			 }
		});	 
			
		
			jQuery('#list_comprobante_cxp').restoreRow(lastsel);
		//	jQuery('#list_comprobante_cxp').editRow(idd,true);
			lastsel=idd;
			
		}
			
	},
	
}).navGrid("#pager_comprobante_cxp",{search :false,edit:false,add:false,del:false});


//validaciones
$('#cuentas_por_pagar_db_empleado_codigo').change(consulta_automatica_benef)


$('#cuentas_por_pagar_db_fecha_v').numeric({allow:'/-'});
//2da pestaña
$('#cuentas_por_pagar_integracion_cuenta').numeric({allow:',.-'});
$('#cuentas_por_pagar_integracion_tipo').numeric({allow:',.-'});
$('#cuentas_por_pagar_dbnumero_documento').numeric({allow:',.-'});
$('#cuentas_por_pagar_db_numero_documento').alpha({allow:'áéíóúÁÉÍÓÚ1234567890.,-'});
$('#cuentas_por_pagar_db_numero_control').alpha({allow:'áéíóúÁÉÍÓÚ1234567890.,-'});
$("input, select, textarea").bind("focus", function(){
/////////////////////////////funcion de permite mostrar menages en pantalla
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
//////////////////////////////////	
</script>
<div id="botonera"><img id="cuentas_por_pagar_documentos_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
<img id="cuentas_por_pagar_documentos_db_btn_eliminar" class="btn_anular"src="imagenes/null.gif"  style="display:none"/>
  
<!-- <img id="cuentas_por_pagar_documentos_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/> <img id="cuentas_por_pagar_documentos_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
-->
  <img src="imagenes/null.gif"  class="btn_consultar" id="cuentas_por_pagar_documentos_db_btn_consultar"  />
  <img id="cuentas_por_pagar_db_documentos_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png" style="display:none" />
  <img id="cuentas_por_pagar_db_documentos_btn_imprimir_comprobante" class="btn_imprimir" src="imagenes/null.gif" style="display:none"/>
  <img id="cuentas_por_pagar_db_documentos_btn_cerrar" src="imagenes/iconos/cerrar_orden_cxp.png" style="display:none"/></div>
</div>
<form method="post" id="form_cuentas_por_pagar_db_documentos" name="form_cuentas_por_pagar_db_docuemntos">
<div id="pestana_doc">
			<div>
			  <ul class="tabs-nav">
				<li><a href="#pestana1_doc" name="cargar_haber"><span>Datos del Documento</span></a></li>
				<li><a href="#pestana3_doc" name="cargar_haber2" id="cargar_debe"><span>Listados de Facturas</span></a></li>          <li><a href="#pestana2_doc" name="cargar_debe" id="cargar_debe3"><span>Elaborar Comprobante</span></a></li>
			    <li></li>
			  </ul> 
			</div>
<div>			
  <div id="pestana1_doc" class="tabs-container">
	
		  <table   class="cuerpo_formulario">
			<tr>
			<input type="hidden"  id="cuentas_por_pagar_vista_documentos" name="cuentas_por_pagar_vista_documentos"/>

			<!--<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Documentos </th>
			</tr>
			<tr>
			<tr>-->
			 <th style="border-top: 1px #BADBFC solid">A&ntilde;o</th>
			
				<td  style="border-top: 1px #BADBFC solid">
					<select  name="cuentas_por_pagar_db_ayo" id="cuentas_por_pagar_db_ayo" >
							<?
							$anio_inicio=date("Y");
							$anio_fin=date("Y")+1;
							while($anio_inicio <= $anio_fin)
							{
							?>
							<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
							<?
								$anio_inicio++;
							}
							?>
				  </select>	
				  <input type="hidden" name="fact_partida" id="fact_partida">
				  <input type="hidden" name="valor_maximo" id="valor_maximo"><!--valor maximo va a ser el monto del renglon de la orden de compra uo servicio-->
				  <input type="hidden" name="valor_fact" id="valor_fact">							 </td>
			</tr>
			<tr>
			<th>N&uacute;mero:  </th>
			  <td><input name="cuentas_por_pagar_db_numero_documento" type="text" id="cuentas_por_pagar_db_numero_documento"   value="" size="10" maxlength="10" message="Ingrese el n&uacute;mero del documento "
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]{2,200}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]/, cFunc:'alert', cArgs:['Asunto: '+$(this).val()]}"
  						 />
			  <!--onChange="consulta_automatica_documento_cxp()" onClick="consulta_automatica_documento_cxp()"							"
-->		      <input type="hidden"  id="cuentas_por_pagar_db_numero_documento_oculto" name="cuentas_por_pagar_db_numero_documento_oculto"/>			</td>
			</tr>
			  <th>N&uacute;mero de Control : </th>
					<td>
				
						<input name="cuentas_por_pagar_db_numero_control" type="text" id="cuentas_por_pagar_db_numero_control"   value="" size="10" maxlength="10" message="Ingrese el Numero de control" 
						jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]{2,200}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
						jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]/, cFunc:'alert', cArgs:['Asunto: '+$(this).val()]}"
							 />
							
<!--							 onchange="consulta_automatica_documento_cxp()" onClick="consulta_automatica_documento_cxp()"
-->						<input type="hidden"  id="cuentas_por_pagar_db_numero_control_oculto" name="cuentas_por_pagar_db_numero_control_oculto"/>					</td>
		<tr>
			<th>Tipo de Documento</th>
			<td>
				<select  name="cuentas_por_pagar_db_tipo_documento" id="cuentas_por_pagar_db_tipo_documento"  onchange="anticipos();">
							<option value="0">---- SELECCIONE -----</option>
							<?=strtoupper($opt_tipos_doc);?>
				</select>
				<input id="cuentas_por_pagar_db_anticipos" name="cuentas_por_pagar_db_anticipos" type="hidden"  value= <?=$tipos?> />
				<input id="cuentas_por_pagar_db_fac" name="cuentas_por_pagar_db_fac" type="hidden"  value= <?=$tipos_fact?> />
				<label id="doc_ant_cxp" style="display:none">
				<input  type="checkbox"  name="cuentas_por_pagar_check_anticipos"  id="cuentas_por_pagar_check_anticipos"value="checkbox" disabled="disabled" onclick="proceso_ant();amort();" />
				 <label id="extra2" style="display:none">Documento con Anticipos  </label>
		 	    <input type="hidden" id="valor_anticipo" name="valor_anticipo"  value="0"/>			  </td>
		</tr>
		<tr>
			<th>Estatus:</th>
				<td>
						<input type="text"  id="cuentas_por_pagar_db_documentos_abrir_cerrar" name="cuentas_por_pagar_db_documentos_abrir_cerrar"  maxlength="20" value="1"/>
						<input type="text" id="cuentas_por_pagar_db_documentos_estatus" name="cuentas_por_pagar_db_documentos_estatus" readonly="readonly" value="ABIERTO" />				</td>
		</tr>
		<tr>		
		<th>Fecha Factura:</th>
				  <td><label>
				  <input   alt="date" type="text" name="cuentas_por_pagar_db_fecha_f" id="cuentas_por_pagar_db_fecha_f" size="7"  onchange="valFecha();" onblur="valFecha();"  value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" readonly="readonly"
					/>
				  
				  <button type="reset" id="cuentas_por_pagar_db_boton_fd">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "cuentas_por_pagar_db_fecha_f",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "cuentas_por_pagar_db_boton_fd",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("cuentas_por_pagar_db_fecha_f").value.MMDDAAAA() );
										//consulta_automatica_impuesto_cxp();
								}
							});
					</script>
					<input type="hidden" name="fecha_oculta" id="fecha_oculta">
                    <input type="hidden" name="compi" id="compi">
				  </label>		        </td>
			</tr>	
		<tr>		
		<th>Fecha Vencimiento:</th>
				  <td><label>
				  <input   alt="date" type="text" name="cuentas_por_pagar_db_fecha_v" id="cuentas_por_pagar_db_fecha_v" size="7"  onchange="valFecha();" onblur="valFecha();" value="<? echo $fecha; ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" readonly="readonly"
					/>
				  
				  <button type="reset" id="cuentas_por_pagar_db_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "cuentas_por_pagar_db_fecha_v",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "cuentas_por_pagar_db_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("cuentas_por_pagar_db_fecha_v").value.MMDDAAAA() );
										//consulta_automatica_impuesto_cxp();
								}
							});
					</script>
					
				  </label>				</td>
			</tr>	
			<tr style="display:none">
				<th >Beneficiario</th>
			  <td><label>
				 <input name="cuentas_por_pagar_db_radio" type="radio" id="cuentas_por_pagar_db_radio1" onclick="getObj('tr_empleado_cxp').style.display='none'; getObj('tr_proveedor_cxp').style.display='';" value="1" checked="CHECKED"/>
				Prooveedor</label>
				&nbsp;&nbsp;
				<label>
				  <input name="cuentas_por_pagar_db_radio" type="radio" id="cuentas_por_pagar_db_radio2"  onclick="getObj('tr_empleado_cxp').style.display=''; getObj('tr_proveedor_cxp').style.display='none';" value="0" />
				  Otro
				  Benefiiario				</label>
				</br>
			  <input type="hidden" name="cuentas_por_pagar_db_op_oculto" id="cuentas_por_pagar_db_op_oculto" value="1" />			    </td>
			</tr>  
		 	  <tr id="tr_proveedor_cxp">
				<th>Proveedor:</th>
				  <td>
				  <ul class="input_con_emergente">
						<li>
						  <input name="cuentas_por_pagar_db_proveedor_codigo" type="text" id="cuentas_por_pagar_db_proveedor_codigo"  maxlength="4"
						onchange="consulta_automatica_proveedor_cxp()" onClick="consulta_automatica_proveedor_cxp()"
						message="Introduzca un Codigo para el proveedor."  size="8"
						 />			
		
						  <input name="cuentas_por_pagar_db_proveedor_nombre" type="text" id="cuentas_por_pagar_db_proveedor_nombre" size="45" maxlength="60" readonly
						message="Introduzca el nombre del Proveedor." />
						<input type="hidden" name="cuentas_por_pagar_db_proveedor_id" id="cuentas_por_pagar_db_proveedor_id" readonly />
						<input type="hidden" name="cuentas_por_pagar_db_proveedor_rif" id="cuentas_por_pagar_db_proveedor_rif" readonly />
						</li> 
							<li id="cuentas_por_pagar_db_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
			  </ul>			  </td>		
			</tr>
			<tr  id="tr_empleado_cxp" style="display:none">
				<th>Empleado</th>
			  <td >		<ul class="input_con_emergente">
			  <li><input name="cuentas_por_pagar_db_empleado_codigo" type="text" id="cuentas_por_pagar_db_empleado_codigo"
						 onchange="consulta_automatica_benef" onblur="consulta_automatica_benef"  size="8"  maxlength="4" 
						message="Introduzca un Codigo para el Empleado." />
					<input name="cuentas_por_pagar_db_empleado_nombre" type="text" id="cuentas_por_pagar_db_empleado_nombre" size="45" maxlength="60"
						message="Introduzca el nombre del Empleado." />
				  <label>		    </label>
				 
				  <input type="hidden" name="textprue3" id="textprue3" />
			  </li> 
					<li id="cuentas_por_pagar_db_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
				</ul>			 </td>
			</tr>
			<tr id="tr_comprometido" >
			<th>N&uacute;mero Compromiso:</th>
			  <td>
			  <ul class="input_con_emergente">
			  <li>
					 <input type="text" name="cuentas_por_pagar_db_compromiso_n" id="cuentas_por_pagar_db_compromiso_n" size="8" maxlength="8"  message="Ingrese el n&uacute;mero de compromiso." readonly="" />
					<!--onblur="compromiso_cons()"-->
                    <input type="text" name="cuentas_por_pagar_db_total" id="cuentas_por_pagar_db_total" />
					<input type="hidden" name="cuentas_por_pagar_db_orden_compra" id="cuentas_por_pagar_db_orden_compra"  />
					<input type="hidden" name="cuentas_por_pagar_db_restante" id="cuentas_por_pagar_db_restante" />
					<input type="hidden" name="cuentas_por_pagar_db_cuentas_orden" id="cuentas_por_pagar_db_cuentas_orden" />
			</li>
			 <li id="cuentas_por_pagar_db_btn_consultar_compromiso" class="btn_consulta_emergente"></li>
		</ul>		</td>
		</tr>
			 <tr>
				<th>Descripci&oacute;n:</th>
				<td><textarea  name="cuentas_por_pagar_db_comentarios" cols="60" id="cuentas_por_pagar_db_comentarios" message="Introduzca un comentario."></textarea>		
						<input type="hidden" name="valores" id="valores"  />				</td>
			</tr>
		
<!--		 <th colspan="2" bgcolor="#4c7595">&nbsp;</th>
-->	
<tr style="display:none">
				<th>
				Factura Multi Partida Pagada por Partes				</th>
					<td>	
						 <input type="checkbox" id="check_mp" name="check_mp"  value="checkbox" onclick="proceso_aceptar_co_mp();" >
						
						 <input type="hidden" id="check_invisible_mp" name="check_invisible_mp" value="0">					</td>	 
</tr>
<tr  id="partida_celdas" >
    <th colspan="2">
    	<table  class="clear" width="100%" border="0">
			<tr>
					<th>				  </th>
				  <td align="center"><b>						Partida:</b><br>
				  	<input type="text" name="partida_comp" id="partida_comp" readonly="">
					<input type="text" name="partida_comp2" id="partida_comp2" readonly="" style="display:none">
					
				</td>
						 <th>				  </th>
								  <td align="center"><b>						Monto BI:</b><br>
								   <input  align="right"   style="text-align:right"  type="text" name="monto_causar_comp" id="monto_causar_comp" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" disabled="disabled" >
				  <input type="text" name="monto_causar_comp_v" id="monto_causar_comp_v"   value="0,00" style="display:none;text-align:right" align="right" onkeypress="reais(this,event)" onkeydown="backspace(this,event);"  >				   
				  <input type="hidden" name="monto_oculto" id="monto_oculto"><input type="hidden" name="monto_causar_comp2" id="monto_causar_comp2">	
			      <input type="hidden" name="comprometido" id="comprometido">	<!--monto de la orden-->
                  <input type="hidden" id="cuentas_por_pagar_activo_varios" name="cuentas_por_pagar_activo_varios">
				  <input type="hidden" id="cuentas_por_pagar_imp3" name="cuentas_por_pagar_imp3"
				   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]{2,200}$/, message:'N&uacute;mero Invalido', styleType:'cover'}"
				   jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ0123456789.//,-()]/, cFunc:'alert', cArgs:['Asunto: '+$(this).val()]}"
  						 />
				<input type="hidden" id="cuentas_por_pagar_partidas_todas" name="cuentas_por_pagar_partidas_todas">	    </td>
							<td align="center" style="display:none" id="cuadro_iva">
							<b>
									Iva:
							</b><br>
							<input type="text" id="iva_multi" name="iva_multi" value="0,00"  style="text-align:right"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);"  onBlur="setTimeout(reducir_subtotal,10);"  >
							<img id="cuentas_por_pagar_documentos_db_btn_iva_borrar" src="imagenes/iconos/borrar.png" />							</td>

							</td>
					
							<td width="20%" rowspan="3" align="center">
								<img style="vertical-align:middle" id="cuentas_por_pagar_documentos_db_btn_guardar" src="imagenes/iconos/actu.png"   />
								<img style="display:none" id="cuentas_por_pagar_documentos_db_btn_actualizar" src="imagenes/iconos/actu.png" />							</td>
			</tr>	
</table></th>
</tr>
	<tr id="celdas_2" style="display:none">
				<td class="celda_consulta" colspan="2">
				<table id="list_compro" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_compro" class="scroll" style="text-align:center;"></div>				</td>
			</tr>		
<tr id="tr_cxp_monto_anticipo" style="display:none">
				<th>Monto :</th>
				<td>
				  <input align="right"  name="cuentas_por_pagar_db_monto_ant" type="text" id="cuentas_por_pagar_db_monto_ant"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16"  style="text-align:right" 
				  message="Ingrese el valor del monto bruto." readonly="readonly"
				  /><input type="hidden" id="valor_porcentaje_compromiso" name="valor_porcentaje_compromiso"  value="0"/>
				  <input type="hidden" id="valor_tipo_doc" name="valor_tipo_doc"  value="0"/>	
				 <input type="hidden"	 id="monto_ant_oculto" name="monto_ant_oculto">				 </td> 	  			
			</tr>
			<tr>			</tr>
				
			<tr id="tr_amort" style="display:none">
			<th>Amortizacion:</th>
				<td><input id="cuentas_por_pagar_amortizacion" name="cuentas_por_pagar_amortizacion" type="text" onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" size="29" value="0,00" maxlength="16"style="text-align:right"
				 message="Ingrese el valor del monto bruto."
				  />	</td>
			</tr>
			
			<tr id="tr_cxp_monto_bruto">
				<th><label id="monto_bruto">Monto Bruto:</label><label id="monto_bruto2" style="display:none">Monto :</label></th>
				<td>
				  <input align="right"  name="cuentas_por_pagar_db_monto_bruto" type="text" id="cuentas_por_pagar_db_monto_bruto"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16" readonly="" style="text-align:right" 
				  message="Ingrese el valor del monto bruto."
				  />	  <label>
				  <input  type="checkbox"  name="cuentas_por_pagar_check_bi" id="cuentas_por_pagar_check_bi" value="checkbox" onclick="proceso_bi();" />
				  </label>
	 			  </label>
	    		  <label id="mas">Base Imponible</label><input type="hidden" id="valor_bi1" name="valor_bi1"  value="0"/></label>
	    		  <input  type="checkbox"  name="cuentas_por_pagar_check_bi2" id="cuentas_por_pagar_check_bi2" value="checkbox" onClick="proceso_bi2();" />
	    		  <label id="mas">Base Imponible2</label><input type="hidden" id="valor_bi2" name="valor_bi2"  value="0"/></label>
				<input type="hidden" name="monto_bruto_oculto" id="monto_bruto_oculto">			  </td>
			</tr>	
			<tr id="tr_base_i" style="display:none">
				<th>Base Imponible:</th>
			  <td><input align="right"  name="cuentas_por_pagar_db_base_imponible" type="text" id="cuentas_por_pagar_db_base_imponible"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"
			  message="Ingrese el valor de la base imponible." 
			  /></td>
			</tr>
			<tr id="tr_cxp_iva">
			  <th> IVA:</th>
				<td><input type="text" name="cuentas_por_pagar_db_iva" id="cuentas_por_pagar_db_iva" style="text-align:right"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" onBlur="restar();" value="<?php echo($porcentajexx); ?>" size="6" maxlength="6"  readonly=""
			  message="Ingrese el valor del IVA. "  
			  />
				<input align="right"  name="cuentas_por_pagar_db_monto_iva" type="text" id="cuentas_por_pagar_db_monto_iva"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"
				message="Ingrese el valor de la base imponible." />
				<input type="hidden" name="opcion_iva" id="opcion_iva" value="0">				</td></tr>
				<tr id="tr_base_i2" style="display:none">
				<th>Base Imponible2:</th>
			  <td><input align="right"  name="cuentas_por_pagar_db_base_imponible2" type="text" id="cuentas_por_pagar_db_base_imponible2"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"
			  message="Ingrese el valor de la base imponible." 
			  /></td>
			</tr>
			<tr id="tr_cxp_iva2" style="display:none">
			  <th> IVA2:</th>
				<td><input type="text" name="cuentas_por_pagar_db_iva2" id="cuentas_por_pagar_db_iva2" style="text-align:right"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" onBlur="restar();" value="0,00" size="6" maxlength="6"  readonly=""
			  message="Ingrese el valor del IVA. "  
			  />
				<input align="right"  name="cuentas_por_pagar_db_monto_iva2" type="text" id="cuentas_por_pagar_db_monto_iva2"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"
				message="Ingrese el valor de la base imponible." />
				<input type="hidden" name="opcion_iva" id="opcion_iva" value="0">				</td></tr>
			<tr id="tr_cxp_sub_t">
				<th><label id="sub_total1">Sub-</label><label id="sub_total2">Total:</label></th>
				<td>
			<input id="cuentas_por_pagar_db_sub_total" type="text" name="cuentas_por_pagar_db_sub_total"  style="text-align:right"  onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onblur="restar();" readonly="" value="0,00" size="29" maxlength="16" message="Ingrese el valor de la retenci&oacute;n IVA. " />				</td>
			</tr>
			<tr id="tr_cpx_retiva">
				<th>Retenci&oacute;n IVA:</th>
				<td><input type="text" name="cuentas_por_pagar_db_ret_iva" id="cuentas_por_pagar_db_ret_iva" style="text-align:right"  readonly="" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" onBlur="restar();" value="0,00" size="6" maxlength="6" 
			  message="Ingrese el valor de la retenci&oacute;n IVA. " 
			  />
				<input align="right"  name="cuentas_por_pagar_db_monto_ret_iva" type="text" id="cuentas_por_pagar_db_monto_ret_iva"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor de la base imponible." />
				  <label></label>			   </td>
		    </tr>
			<tr id="tr_cpx_retiva2" style="display:none">
				<th>Retenci&oacute;n IVA2:</th>
				<td><input type="text" name="cuentas_por_pagar_db_ret_iva2" id="cuentas_por_pagar_db_ret_iva2" style="text-align:right"  readonly="" onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onblur="restar();" value="0,00" size="6" maxlength="6" 
			  message="Ingrese el valor de la retenci&oacute;n IVA. " 
			  />
				  <input align="right"  name="cuentas_por_pagar_db_monto_ret_iva2" type="text" id="cuentas_por_pagar_db_monto_ret_iva2"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor de la base imponible." />
				  <label></label>			   </td>
		    </tr>
			<tr id="tr_cxp_retislr">
				<th>Retenci&oacute;n ISLR: </th>
				<td><input type="text" name="cuentas_por_pagar_db_islr" readonly="" id="cuentas_por_pagar_db_islr" style="text-align:right"  onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onblur="restar();" value="0,00" size="6" maxlength="6" 
				message="Ingrese el valor de la retenci&oacute;n del ISLR. " 
				/>
				<input align="right"  name="cuentas_por_pagar_db_monto_ret_islr" type="text" id="cuentas_por_pagar_db_monto_ret_islr"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor de la base imponible."  />
            Sustraendo: <input align="right"  name="cuentas_por_pagar_db_monto_sust" type="text" id="cuentas_por_pagar_db_monto_sust"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor del sustraendo"  />            </td>
			</tr>
			<tr id="tr_cxp_total_sin_ret">	
			<th>Total a Pagar:		</th>
			<td>
			<input align="right"  name="cuentas_por_pagar_db_monto_ret" type="text" id="cuentas_por_pagar_db_monto_ret" readonly="readonly"  value="0,00" size="29" maxlength="16"  style="text-align:right" message="Ingrese el Valor del monto bruto"  />			</td>
			</tr>
		
			<tr id="tr_ret_e" >
			<th> Retenciones Extras</th>
			<td><label>
			  <input  type="checkbox"  name="cuentas_por_pagar_check_extra1" id="cuentas_por_pagar_check_extra1" value="checkbox" onclick="proceso_ex();" />
			</label>
			  <label id="extra1"> Retenci&oacute;n Extra 1</label>
			  <input type="hidden" id="valor_ret_ex" name="valor_ret_ex"  value="0"/>
			  <label>
			  <input  type="checkbox"  name="cuentas_por_pagar_check_extra2"  id="cuentas_por_pagar_check_extra2"value="checkbox" onclick="proceso_ex2();" />
			  </label>
			  <label id="extra2">Retenci&oacute;n Extra2 </label>
			  <input type="hidden" id="valor_ret_ex2" name="valor_ret_ex2"  value="0"/></td>
			</tr>
				<tr id="tr_ret_ex1" style="display:none">
				<th>RETENC&Oacute;IN EXTRA 1: </th>
				<td>
					<label id="text_ret1" style="display:none">
					<input type="text" name="cuentas_por_pagar_db_ret_e1" id="cuentas_por_pagar_db_ret_e1" style="text-align:right"  onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onblur="restar();" value="0,00" size="6" maxlength="6" message="Ingrese el valor de la retención del ISLR. " jvalkey="{valid:/[,.-_0123456789]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}"/>
					</label>
					<input align="right"  name="cuentas_por_pagar_db_ret_extra" type="text" id="cuentas_por_pagar_db_ret_extra"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);"  value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor de la base imponible." />
					  Descripción.
					<input id="cuentas_por_pagar_db_ret_extra_dsc1" name="cuentas_por_pagar_db_ret_extra_dsc1" type="text" />
					  <label>
					  <input  type="checkbox"  name="cuentas_por_pagar_check_bie1" id="cuentas_por_pagar_check_bie1" value="checkbox"  disabled="disabled"onclick="proceso_biex1();restar()" />
					  </label>
					 Applicable BI<input type="hidden" id="valor_biex1" name="valor_biex1"  onblur="restar()" value="0"/>		      </td>
			</tr>
				<tr id="tr_ret_ex2" style="display:none">
				<th >RETENCI&Oacute;N EXTRA 2: </th>
				<td>
				<label id="text_ret2"  style="display:none">
				<input type="text" name="cuentas_por_pagar_db_ret_e2" id="cuentas_por_pagar_db_ret_e2" style="text-align:right"  onkeypress="reais(this,event)" onkeydown="backspace(this,event)" onblur="restar();" value="0,00" size="6" maxlength="6" message="Ingrese el valor de la retención del ISLR. " jvalkey="{valid:/[,.-_0123456789]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}" />
				</label>
				<input align="right"  name="cuentas_por_pagar_db_ret_extra2" type="text" id="cuentas_por_pagar_db_ret_extra2"  onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" size="16" maxlength="16" readonly="" style="text-align:right"  onblur="restar()"message="Ingrese el valor de la base imponible." />
					Descripción. 
					<input id="cuentas_por_pagar_db_ret_extra_dsc2" name="cuentas_por_pagar_db_ret_extra_dsc2" type="text" />		
					  <label>
					  <input  type="checkbox"  name="cuentas_por_pagar_check_bie3"  id="cuentas_por_pagar_check_bie3" value="checkbox" onclick="proceso_biex2();restar();" />
					  </label>
					 Applicable BI<input type="hidden" id="valor_biex2" name="valor_biex2"   onblur="restar()" value="0"/>				</td>
			</tr>
			<tr id="ret_total" style="display:none">	
			<th>Total(-)Retenciones :		</th>
			<td>
			<input align="right"  name="cuentas_por_pagar_db_monto_neto" type="text" id="cuentas_por_pagar_db_monto_neto" readonly="readonly"  value="0,00" size="29" maxlength="16"  style="text-align:right" message="Ingrese el Valor del monto bruto"  />
			<input align="right" name="cuentas_por_pagar_switch_activacion" type="text" id="cuentas_por_pagar_switch_activacion"/></td>
			</tr>
			<!--<tr>
				<th>Comprometido:</th>
			  <td><input name="cuentas_por_pagar_db_op_comprometido" id="cuentas_por_pagar_db_op_comprometido_si" type="radio" value="1" checked="checked" onclick="getObj('tr_comprometido').style.display='none'; getObj('tr_comprometido').style.display='';" />Si</option>
				<input name="cuentas_por_pagar_db_op_comprometido" id="cuentas_por_pagar_db_op_comprometido_no" type="radio" value="1" onclick="getObj('tr_comprometido').style.display=''; getObj('tr_comprometido').style.display='none';" />
				No
				<input type="hidden" name="cuentas_por_pagar_db_op_comprometido_oculto" id="cuentas_por_pagar_db_op_comprometido_oculto" value="1"/></td>
			</tr>-->
  <input  name="cuentas_por_pagar_db_id" type="hidden" id="cuentas_por_pagar_db_id"  />
			<tr>
				<td colspan="2" class="bottom_frame">&nbsp;</td>
			</tr>
		</table> 
</div>
<div id="pestana3_doc"  class="tabs-container">
	<table   class="cuerpo_formulario">
	
		<th  style="border-top: 1px #BADBFC solid" colspan="4" align="center">
		<div align="center">FACTURAS REALACIONADAS POR COMPROMISO/PROVEEDOR:</div>
		</th>
		<tr>
        <th colspan="2">
		  <input type="hidden" name="cuentas_por_pagar_db_facturas_lista" id="cuentas_por_pagar_db_facturas_lista"></th>
        </tr>
         <tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_facturas" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_facturas" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
    </tr>
    
		 <tr>
				<td colspan="2" class="bottom_frame">&nbsp;</td>
	  </tr>
	</table>
</div>		 

	 <div id="pestana2_doc"  class="tabs-container" >
	 <table   class="cuerpo_formulario">
		<table class="cuerpo_formulario"><!-- table de carga manual de comprbante contable -->	
		
		<!--<th  style="border-top: 1px #BADBFC solid">
		Numero Comprobante:
		</th>
		 <td style="border-top: 1px #BADBFC solid">
			<input type="text" id="cuentas_por_pagar_numero_comprobante_integracion" name="cuentas_por_pagar_numero_comprobante_integracion"  readonly="readonly" >
			<input type="hidden" id="cuentas_por_pagar_numero_comprobante_cuenta_orden" name="cuentas_por_pagar_numero_comprobante_cuenta_orden"  readonly="readonly" >
            <input type="hidden" id="fecha_oc" name="fecha_oc">
		</td>
		<tr>
		<th>
		 	Cuenta Contable:</th>
			<td style="border-top: 1px #BADBFC solid">
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="cuentas_por_pagar_integracion_cuenta" id="cuentas_por_pagar_integracion_cuenta"  size='12' maxlength="12" onBlur="consulta_automatica_cuentas_contables_cxp()" onChange="consulta_automatica_cuentas_contables_cxp()"
				message="Introduzca la cuenta contable" 
				 />
				<input type="hidden" id="cuentas_por_pagar_integracion_cuenta_id" name="cuentas_por_pagar_integracion_cuenta_id" />
				<input type="text" name="cuentas_por_pagar_integracion_descripcion_cuentas" id="cuentas_por_pagar_integracion_descripcion_cuentas"  size='30' maxlength="30" readonly="readonly"
				message="Introduzca la cuenta contable" 
				/>
			
				
			 </li>
			<li id="cuentas_por_pagar_integracion_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
			</ul>			</td>	
		 </tr>
		 <tr>
		 	<th>Tipo Comprobante :</th>
			<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="cuentas_por_pagar_integracion_tipo" id="cuentas_por_pagar_integracion_tipo"  size='12' maxlength="12" onBlur="consulta_automatica_tipo_comprobante_cxp()" onChange="consulta_automatica_tipo_comprobante_cxp()"
				message="Introduzca el tipo de cuenta" 
				/>
		        <input type="hidden" id="cuentas_por_pagar_integracion_tipo_id" name="cuentas_por_pagar_integracion_tipo_id" />
				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
				message="Introduzca el tipo de cuenta" />
			 </li>
			<li id="cuentas_por_pagar_integracion_btn_consultar_tipo" class="btn_consulta_emergente"></li>
			</ul>			</td>
		 </tr>
		<tr>
			<th>Descripci&oacute;n</th>
			<td>
				<textarea  name="cuentas_por_pagar_db_comentarios2" cols="60" id="cuentas_por_pagar_db_comentarios2" message="Introduzca un comentario."></textarea>
			</td>
		</tr>
		<tr>
			<th>Monto:</th>
			<td>
				<input type="text" id="cuentas_por_pagar_integracion_monto" name="cuentas_por_pagar_integracion_monto" onkeypress="reais(this,event)" onkeydown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16" readonly="readonly">			</td>
		</tr>
		<tr>
		<td class="celda_consulta" colspan="2">
				<table id="list_integracion" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_integracion" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
		</tr>
		<tr>
			<th>Generar Cuenta de orden automatica</th>
			<td>
				 <input type="checkbox" id="check_co" name="check_co"  value="checkbox" onclick="proceso_aceptar_co();" >
				 <input type="hidden" id="check_invisible" name="check_invisible" value="0">
			
			</td>
		</tr>
		 <tr>
				<td colspan="2" class="bottom_frame">&nbsp;</td>
		  </tr>-->
		 <th colspan="4"  style="border-top: 1px #BADBFC solid" align="center">
			<P align="center">	CARGA DE COMPROBANTE-CXP</P>
<input type="hidden"  id="cxp_id_comprobante" name="cxp_id_comprobante" value="0"/>
<input type="hidden" id="cuentas_por_pagar_db_facturas_lista2" name="cuentas_por_pagar_db_facturas_lista2" >
<input type="hidden" id="cxp_comp_pr_activo" name="cxp_comp_pr_activo"  value="0"/>
<input type="hidden" id="cxp_comp_pr_activo2" name="cxp_comp_pr_activo2" value="0"/>
<input type="hidden" id="cxp_comp_pr_activo3" name="cxp_comp_pr_activo3" value="0"/>
<input type="hidden" id="cxp_comp_pr_activo4" name="cxp_comp_pr_activo4" value="0"/>		</th>
		<tr>
			<th>
					Tipo:			</th>
			<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="cuentas_por_pagar_integracion_tipo" id="cuentas_por_pagar_integracion_tipo"  size='12' maxlength="12" onBlur="consulta_automatica_tipo_comprobante_cxp()" onChange="consulta_automatica_tipo_comprobante_cxp()"
				message="Introduzca el tipo de cuenta" 
				/>
		        <input type="hidden" id="cuentas_por_pagar_integracion_tipo_id" name="cuentas_por_pagar_integracion_tipo_id" />
				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
				message="Introduzca el tipo de cuenta" />
			 </li>
			<li id="cuentas_por_pagar_integracion_btn_consultar_tipo" class="btn_consulta_emergente"></li>
			</ul>			</td>
		</tr>
		<tr>
			<th  style="border-top: 1px #BADBFC solid">
			Numero Comprobante:			</th>
			 <td style="border-top: 1px #BADBFC solid">
				<input type="text" id="cuentas_por_pagar_numero_comprobante_integracion" name="cuentas_por_pagar_numero_comprobante_integracion"  readonly="readonly" >
				<input type="hidden" id="cuentas_por_pagar_numero_comprobante_cuenta_orden" name="cuentas_por_pagar_numero_comprobante_cuenta_orden"  readonly="readonly" >
				<input type="hidden" id="cuentas_por_pagar_numero_pr_numero_comprobante2" name="cuentas_por_pagar_numero_pr_numero_comprobante2"  readonly="readonly" >
				<input type="hidden" id="fecha_oc" name="fecha_oc">		</td>
		</tr>
		<tr>
			<th>Cuenta Contable:</th>
			<td style="border-top: 1px #BADBFC solid">
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="cuentas_por_pagar_integracion_cuenta" id="cuentas_por_pagar_integracion_cuenta"  size='12' maxlength="12" onBlur="consulta_automatica_cuentas_contables_cxp()" onChange="consulta_automatica_cuentas_contables_cxp()"
				message="Introduzca la cuenta contable" 
				 />
				<input type="hidden" id="cuentas_por_pagar_integracion_cuenta_id" name="cuentas_por_pagar_integracion_cuenta_id" />
				<input type="text" name="cuentas_por_pagar_integracion_descripcion_cuentas" id="cuentas_por_pagar_integracion_descripcion_cuentas"  size='60' maxlength="60" readonly="readonly"
				message="Introduzca la cuenta contable" 
				/>
			 </li>
			<li id="cuentas_por_pagar_integracion_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
			</ul>			</td>
		</tr>
		<tr>
			<th>Referencia:</th>
			<td>
					<input type="text" name="cuentas_por_pagar_integracion_ref" id="cuentas_por_pagar_integracion_ref" maxlength="10" size="8" message="Introlduzca Un referencia">			</td>
		</tr>	
		<tr>
			<th>Descripci&oacute;n:</th>
			<td>
				<textarea name="cuentas_por_pagar_integracion_desc_asiento" id="cuentas_por_pagar_integracion_desc_asiento" message="Introduzca una Descripci&oacute;n del asiento. Ejem:'Esta cuenta es ...' " cols="35"></textarea>
				<img src="imagenes/iconos/agregar.png" id="btn_cxp_agregar_comprobante_contable" title="Agregar Registro" >
				<img src="imagenes/iconos/agregar.png" id="btn_cxp_actualizar_comprobante_contable" title="Actualizar Registro"  style="display:none">
				<img src="imagenes/iconos/trash.png" id="btn_cxp_eliminar_registro" title="Borrar asiento" style="display:none">
				<img src="imagenes/iconos/limpiar_comp.png" id="btn_cxp_limpiar" title="Limpiar asiento" style="display:none">
				<img src="imagenes/iconos/completar.png" id="btn_cxp_cerrar_comp" title="Completar asiento"  style="display:none">
				
								</td>
		</tr>
		
		<tr>
			<th>Debe-Haber</th>
			<td><select name="cuentas_por_pagar_debe_haber_integracion_estatus" id="cuentas_por_pagar_debe_haber_integracion_estatus">
              <option value="1">Debe</option>
              <option value="2">Haber</option>
            </select></td>
		</tr>
		<tr>
				<th>Monto:</th>
				<td>
						<input type="text" name="cuentas_por_pagar_debe_haber_integracion_monto_p" id="cuentas_por_pagar_debe_haber_integracion_monto_p" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event);" value="0,00" message="Introduzca el monto de asiento"  size="12" maxlength="12">				</td>
		</tr>
	
		<tr>
				<th>
						Total-Debe:				</th>
		  <td>
						<input type="text" name="cuentas_por_pagar_integracion_total_debe" id="cuentas_por_pagar_integracion_total_debe" value="0,00" readonly="readonly" size="12" maxlength="12" >
Total-Haber:			<input type="text" name="cuentas_por_pagar_integracion_total_haber" id="cuentas_por_pagar_integracion_total_haber" value="0,00" readonly="readonly" size="12" maxlength="12">		</td>
		</tr>
		<tr>
				<th>Auxiliar:</th>
				<td >
					<ul class="input_con_emergente">
					 <li>	
					<input name="cuentas_por_pagar_integracion_auxiliar_p" type="text" id="cuentas_por_pagar_integracion_auxiliar_p"   value="" size="12" maxlength="30" message="Introduzca el c&oacute;digo del auxiliar' "   onblur="auxiliares_consulta_mov_cxp()" 
					/> 
					<input type="text"  name="cuentas_por_pagar_integracion_auxiliar_desc" id="cuentas_por_pagar_integracion_auxiliar_desc" size="35"/>
     				 <input type="hidden" id="cuentas_por_pagar_integracion_id_aux" name="cuentas_por_pagar_integracion_id_aux">
					 </li>
					<li id="cuentas_por_pagar_integracion_btn_consultar_auxiliar_cmp" class="btn_consulta_emergente"></li>
					</ul>				</td>
		</tr>
		<tr>
					<th>Proyecto:</th>
					<td >
					<ul class="input_con_emergente">
					<li>
					<input name="cxp_pr_centro_costo"  id="cxp_pr_centro_costo" type="text"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo del centro de costo' "   onblur="consulta_proyecto_mov_cxp();"
										/>
					<input type="text"  name="cxp_centro_costo_desc" id="cxp_centro_costo_desc" size="35"/>						
						<input type="hidden" id="cxp_centro_costo_id" name="cxp_centro_costo_id" >
					</li>
					<li id="cxp_btn_consultar_proyecto_cmp" class="btn_consulta_emergente"></li>	
					</ul>					</td>
				</tr>
		<tr>
		<th>Acci&oacute;n Centralizada:</th>
		<td >
							<ul class="input_con_emergente">
							<li>
							  <input name="cxp_pr_acc" type="text" id="cxp_pr_acc"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de la acci&oacute;n centralizada'" onblur="consulta_acc_mov_cxp()" 
												/>
							  <input type="text"  name="cxp_pr_acc_desc" id="cxp_pr_acc_desc" size="35"/>
							  <input type="hidden" id="cxp_pr_acc_id" name="cxp_pr_acc_id" />
							 </li>
							<li id="cxp_btn_cmp_consultar_acc" class="btn_consulta_emergente"></li>
							</ul>		</td>	
		</tr>
		<tr>
				<th>Ubicaci&oacute;n</th>
				<td>
					<ul class="input_con_emergente">
					<li>
					  <input name="cxp_pr_ubicacion" type="text" id="cxp_pr_ubicacion"   value="" size="12" maxlength="12" message="Introduzca ubicaci&oacute;n d ela cuenta ejm:Div. Telemat' " onblur="consulta_ubicacion_mov_cxp()"
							/>
					<input type="text"  name="cxp_pr_ubicacion_desc" id="cxp_pr_ubicacion_desc" size="35" />		
					
					 <input type="hidden" name="cxp_pr_ejec_id" id="cxp_pr_ejec_id"/>
				      </li>
					<li id="cxp_btn_consultar_ubicacion_cmp" class="btn_consulta_emergente"></li>
					</ul>				</td>
		</tr>
		<tr>
			<th>Utilizaci&oacute;n Fondos</th>
			<td>
					<ul class="input_con_emergente">
					<li>
					  <input name="cxp_comp_pr_utf" type="text" id="cxp_comp_pr_utf"   value="" size="12" maxlength="12" message="Introduzca el c&oacute;digo de Utilizaci&oacute;n de fondos' " onblur='consulta_utf_mod_cxp()'/>
					  <input type="text"  name="cxp_comp_pr_utf_desc" id="cxp_comp_pr_utf_desc" size="35"/>
					  <input type="hidden" id="cxp_comp_pr_utf_id" name="cxp_comp_pr_utf_id"  />
				 </li>
				<li id="cxp_btn_consultar_utf" class="btn_consulta_emergente"></li>
				</ul>			</td>
		</tr>
		<tr>
			<th>Diferencia Debe-Haber </th>
			<td>
				<input  type="text" name="cxp_comp_pr_dif" id="cxp_comp_pr_dif"  readonly="readonly" size="12" maxlength="12"  value="0,00"/>
				<input type="hidden" id="cuentas_por_pagar_integracion_monto" name="cuentas_por_pagar_integracion_monto" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event);setTimeout(restar,10); restar();" value="0,00" size="29" maxlength="16" readonly="readonly">
			
			</td>
		</tr>
		<tr >
			<th style="display:none">
			 <input type="checkbox" id="check_co" name="check_co"  value="checkbox" onclick="proceso_aceptar_co();" >
			 <input type="hidden" id="check_invisible" name="check_invisible" value="0">			</th>
		</tr>
		<tr>
        <th colspan="4">&nbsp;</th>
        </tr>
         <tr>
		<td class="celda_consulta" colspan="4">
				<table id="list_comprobante_cxp" class="scroll" cellpadding="0" cellspacing="0"></table> 
				<div id="pager_comprobante_cxp" class="scroll" style="text-align:center;"></div> 
				<br />		</td>
		</tr>
    
		 <tr>
				<td colspan="4" class="bottom_frame">&nbsp;</td>
	  </tr>
	</table>
	<!--</div>
</div>
</div>-->
</form>

   
   