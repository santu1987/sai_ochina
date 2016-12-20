<? if (!$_SESSION) session_start();

require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	?>
<link rel="stylesheet" type="text/css" media="all" href="../../orden_pago/db/utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="../../orden_pago/db/utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="../../orden_pago/db/utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="../../orden_pago/db/utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script type="text/javascript">
var dialog;
$("#proveedor_db_btn_imprimir").click(function() {
	if(getObj('proveedor_db_codigo').value != ""){
		url="pdf.php?p=modulos/adquisiones/proveedor/rp/vista.lst.proveedor.php¿codigo="+getObj('proveedor_db_codigo').value 
		openTab("Ficha Proveedor",url);
	}
});	
			
/*--------------------------------------   GUARDAR ----------------------------------------------------*/
/*$("#proveedor_db_ret_btn_guardar").click(function() {
	if($('#form_db_proveedor_ret').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/cuentas_por_pagar/documentos/db/sql.proveedor_ret.php",
			data:dataForm('form_db_proveedor_ret'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//dialog=new Boxy(html, { title: 'Consulta Emergente de Proveedor',modal: true,center:false,x:0,y:0});
				
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_proveedor_ret');
					
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);

				}else
					{
					//alert(html);
					setBarraEstado(html);
				}
			}
		});
	}
});*/



///////////////////////////////////////
/*--------------------------------------   BUSCAR ----------------------------------------------------*/
/*--------------------------------------   ACTULIZAR ----------------------------------------------------*/

$("#proveedor_db_ret_btn_guardar").click(function() {
	if($('#form_db_proveedor_ret').jVal())
	{
	setBarraEstado(mensaje[esperando_respuesta]);

		//check_modificado();	
		$.ajax (
		{
			url: "modulos/cuentas_por_pagar/documentos/db/sql.retenciones.php",
			data:dataForm('form_db_proveedor_ret'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar_proveedor_ret();
					clearForm('form_db_proveedor');
				}
				else if (html=="Existe")
				{
					limpiar_proveedor_ret();
				}
				else
				{
					alert(html);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#proveedor_db_ret_btn_cancelar").click(function() {
limpiar_proveedor_ret();
	
	

});
function limpiar_proveedor_ret(){
setBarraEstado("");
clearForm('form_db_proveedor_ret');
getObj('cuentas_por_pagar_db_proveedor_ret_iva').value="0,00";
getObj('cuentas_por_pagar_db_proveedor_ret_islr').value="0,00";


}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function consulta_automatica_proveedor()
{
	if (getObj('proveedor_db_codigo')!="")
	{ 
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/db/sql_grid_proveedor_codigo_cxp2.php",
            data:dataForm('form_db_proveedor_ret'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				//alert(html);
					if(recordset!=" ")
				{
					recordset = recordset.split("*");
					getObj('cuentas_por_pagar_db_proveedor_ret_id').value = recordset[0];
					getObj('cuentas_por_pagar_db_proveedor_ret_nombre').value = recordset[1];
					getObj('cuentas_por_pagar_db_proveedor_ret_iva').value=recordset[3];
					getObj('cuentas_por_pagar_db_proveedor_ret_islr').value=recordset[4];
					rif=recordset[2];
					rif2 = rif.split("-");
					getObj('cuentas_por_pagar_db_proveedor_ret_rif').value=rif2[0];
				 }
				 else
				 {
				 	getObj('cuentas_por_pagar_db_proveedor_ret_id').value ="";
					getObj('cuentas_por_pagar_db_proveedor_ret_nombre').value ="";
					getObj('cuentas_por_pagar_db_proveedor_ret_rif').value ="";
				 }
			 }
		});	 	 
	}	
}$("#cuentas_por_pagar_db_btn_consultar_proveedor_ret").click(function() {
		/*var nd=new Date().getTime();
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
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
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
							var busq_proveedor= jQuery("#cuentas_por_pagar_db_proveedor_consulta").val(); 
							var busq_cod= jQuery("#cuentas_por_pagar_db_codigo_proveedor_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor+"&busq_cod="+busq_cod,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor;
						}

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
									getObj('cuentas_por_pagar_db_proveedor_ret_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_db_proveedor_ret_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_db_proveedor_ret_nombre').value = ret.nombre;
									getObj('cuentas_por_pagar_db_proveedor_ret_iva').value=ret.ret_iva;
									getObj('cuentas_por_pagar_db_proveedor_ret_islr').value=ret.ret_islr;
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_db_proveedor_ret_rif').value=rif2[0];
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
//--------------------------------------------------------------------------------------------------

$("#cuentas_por_pagar_db_op_comprometido_si").click(function(){
		getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="1";
	});
$("#cuentas_por_pagar_db_op_comprometido_no").click(function(){
		getObj('cuentas_por_pagar_db_op_comprometido_oculto').value="2";
	});
	
//$('#tesoreria_cheque_manual_pr_proveedor_codigo').change(consulta_automatica_proveedor_manual)

</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
$('#cuentas_por_pagar_db_base_imponible').numeric({allow:',.'});
$('#cuentas_por_pagar_db_iva').numeric({allow:',.'});
$('#cuentas_por_pagar_db_ret_iva').numeric({allow:',.'});
$('#cuentas_por_pagar_db_islr').numeric({allow:',.'});
$('#cuentas_por_pagar_db_monto_bruto').numeric({allow:',.'});
$('#cuentas_por_pagar_db_compromiso_n').numeric({});

$('#cuentas_por_pagar_db_numero_documento').numeric({});
$('#cuentas_por_pagar_db_numero_control').numeric({});
$('#cuentas_por_pagar_db_proveedor_codigo').numeric({});

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
//////////////////////////////////////

/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
<img id="proveedor_db_ret_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
<img id="proveedor_db_ret_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>


<form name="form_db_proveedor_ret" id="form_db_proveedor_ret">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame"  colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Retenciones Proveedor</th>
		</tr>
		<tr>
		<th>Proveedor:</th>
		  <td>
		  <ul class="input_con_emergente">
				<li>
				  <input name="cuentas_por_pagar_db_proveedor_ret_codigo" type="text" id="cuentas_por_pagar_db_proveedor_ret_codigo"  maxlength="4"  onchange="consulta_automatica_proveedor()"
				message="Introduzca un Codigo para el proveedor."  size="5"
				jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
				
				  <input name="cuentas_por_pagar_db_proveedor_ret_nombre" type="text" id="cuentas_por_pagar_db_proveedor_ret_nombre" size="45" maxlength="60" readonly
				message="Introduzca el nombre del Proveedor." />
				</li> 
				<li id="cuentas_por_pagar_db_btn_consultar_proveedor_ret" class="btn_consulta_emergente"></li>
	  </ul>	  
	  <input type="hidden" name="cuentas_por_pagar_db_proveedor_ret_id" id="cuentas_por_pagar_db_proveedor_ret_id" readonly />
		<input type="hidden" name="cuentas_por_pagar_db_proveedor_ret_rif" id="cuentas_por_pagar_db_proveedor_ret_rif" readonly />

	  </td>
		</tr>
		<tr>
			<th>Retencion % IVA</th>
			<td><input  type="text" id="cuentas_por_pagar_db_proveedor_ret_iva" name="cuentas_por_pagar_db_proveedor_ret_iva"  maxlength="6" size="6"  value="0,00" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)"
					jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/>			 </td>
		</tr>
		<tr>
			<th>Retencion ISLR</th>
			<td><input  type="text" id="cuentas_por_pagar_db_proveedor_ret_islr" name="cuentas_por_pagar_db_proveedor_ret_islr" maxlength="6" size="6"  value="0,00" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)"  
				jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/></td>
		</tr> 		 		
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="proveedor_db_id" id="proveedor_db_id" />
</form>