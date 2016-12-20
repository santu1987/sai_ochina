<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$user=$_SESSION['id_usuario'];


$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>

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

function esFechaValida(fecha){
    if (fecha != undefined && fecha != "" ){
        
        var dia  =  parseInt(fecha.substring(0,2),10);
        var mes  =  parseInt(fecha.substring(3,5),10);
        var anio =  parseInt(fecha.substring(6),10);
		if((anio>2100)||(anio<1900))
		{
			return false;
		}
    switch(mes){
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            numDias=31;
            break;
        case 4: case 6: case 9: case 11:
            numDias=30;
            break;
        case 2:
            if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
            break;
        default:
           // alert("Fecha introducida errÛnea");
            return false;
    }
        if (dia>numDias || dia==0){
         //   alert("Fecha introducida errÛnea");
            return false;
        }
        return anio;
    }
}
 
function comprobarSiBisisesto(anio){
if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
    return true;
    }
else {
    return false;
    }
}
function v_fecha()
{
	//alert("entro");
	var1=esFechaValida(getObj('contabilidad_comp_pr_fecha').value);
	if(var1!=false)
	{
		var2=comprobarSiBisisesto(var1);
	}
	//alert(var1);
	//alert(var2);
	if((var1==false)||(var2==true))
	{
		getObj('contabilidad_comp_pr_fecha').value="";
	}

}
</script>	<script type='text/javascript'>


var dialog;



$("#contabilidad_movimientos_pr_btn_guardar").click(function() {
	
	
							//alert("entro");
								setBarraEstado(mensaje[esperando_respuesta]);
									$.ajax (
									{
										url: "modulos/contabilidad/movimientos_contables/pr/sql.guardar_balance.php",
										data:dataForm('form_contabilidad_comprobantes_pr_movimientos'),
										type:'POST',
										cache: false,
										success: function(html)
										{
											recordset=html;
											recordset = recordset.split("*");
											//alert(html);
											if (recordset[0]=="Registrado")
											{
												setBarraEstado(mensaje[actualizacion_exitosa],true,true);
											}
											else if (recordset[0]=="NoActualizo")
											{
												//setBarraEstado(mensaje[registro_existe],true,true);
												setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE REALIZ” LA OPERACI”N</p></div>",true,true);
												
											}
																
											else
											{
												setBarraEstado(recordset[0]);
												//limpiar_comp();
											}
										}
									});
								
		
});
/////////////// consultas
$("#contabilidad_comprobante_btn_consultar_cuenta").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax 
	(
		{
			url:"modulos/contabilidad/movimientos_contables/pr/grid_cuenta_contable.php",
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_nom="+busq_nom;	
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?busq_cuenta="+busq_cuenta;
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
								height:350,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/contabilidad/movimientos_contables/pr/sql_grid_cuenta.php?nd='+nd,
								datatype: "json",
								colNames:['C&oacute;digo','Cuenta', 'Denominacion','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora','requiere_utf'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
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
								//alert(ret.requiere_auxiliar);
									if(ret.requiere_auxiliar=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(ret.requiere_utilizacion_fondos=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(ret.requiere_proyecto=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}
									if(ret.requiere_unidad_ejecutora=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
									$('#contabilidad_comp_pr_cuenta_contable').val(ret.cuenta_contable);
									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
									getObj('cuenta_nombre').value=ret.nombre;
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
function consulta_automatica_cuentas_contables()
{
	$.ajax({
			url:"modulos/contabilidad/movimientos_contables/pr/sql_grid_cuentas_cont.php",
            data:dataForm('form_contabilidad_comprobantes_pr_movimientos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
				recordset = recordset.split("*");
									if(recordset[3]=='t')
									{
										getObj('contabilidad_comp_pr_activo').value=1;
									}
									if(recordset[6]=='t')
									{
										getObj('contabilidad_comp_pr_activo2').value=1;
									}
									if(recordset[4]=='t')
									{
										getObj('contabilidad_comp_pr_activo4').value=1;
									}
									if(recordset[5]=='t')
									{
										getObj('contabilidad_comp_pr_activo3').value=1;
									}
				getObj('contabilidad_comp_pr_cuenta_contable').value = recordset[1];
				getObj('contabilidad_auxiliares_db_id_cuenta').value=recordset[0];
				getObj('cuenta_nombre').value=recordset[2];
				}
				else
				{
				getObj('contabilidad_comp_pr_cuenta_contable').value = "";
				getObj('contabilidad_auxiliares_db_id_cuenta').value="";
				getObj('cuenta_nombre').value="";
				//getObj('cuentas_por_pagar_integracion_descripcion_cuentas').value="";
				}
				
			 }
		});	 	 
}
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

// $('#contabilidad_comp_pr_comentarios').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
/*$('#contabilidad_comp_pr_desc').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
*/
$('#contabilidad_comp_pr_tipo').numeric({});
$('#contabilidad_comp_pr_cuenta_contable').numeric({});
//$('#contabilidad_comp_pr_ref').alphanumeric({});
$('#contabilidad_comp_pr_cuenta_contable').numeric({});
$('#contabilidad_comp_pr_centro_costo').numeric({});
$('#contabilidad_comp_pr_acc').numeric({});
$('#contabilidad_comp_pr_ubicacion').numeric({});
$('#contabilidad_comp_pr_utf').numeric({});
$('#contabilidad_comp_pr_auxiliar').numeric({});
$('#contabilidad_comp_pr_numero_comprobante').numeric({});




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
	

	
</script>
<div id="botonera">
	<img src="imagenes/null.gif" width="31" height="26" class="btn_cancelar" id="contabilidad_auxiliares_db_btn_cancelar"/>
   <!-- <img id="movimientos_contables_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>-->
	

	<img id="contabilidad_movimientos_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	</div>	
<form method="post" id="form_contabilidad_comprobantes_pr_movimientos" name="form_contabilidad_comprobantes_pr_movimientos">
<input type="hidden"  id="contabilidad_comp_id_comprobante" name="contabilidad_comp_id_comprobante" value="0"/>
<input type="hidden" id="contabilidad_comp_pr_activo" name="contabilidad_comp_pr_activo"  value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo2" name="contabilidad_comp_pr_activo2" value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo3" name="contabilidad_comp_pr_activo3" value="0"/>
  <input type="hidden" id="contabilidad_comp_pr_activo4" name="contabilidad_comp_pr_activo4" value="0"/>

  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Mantenimiento Comprobante</th>
	<input   type="hidden" name="contabilidad_comp_pr_id"  id="contabilidad_comp_pr_id" />
	</tr>
	
	<tr>
		 	<th>posicion :</th>
			<td width="124">
			
				<input type="text" name="posicion" id="posicion"  size='12' maxlength="12" o
				message="Introduzca el tipo de cuenta" />
		      <!--				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
				message="Introduzca el tipo de cuenta" />
-->			 			</td>
    </tr>	
	<tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_comp_pr_cuenta_contable" id="contabilidad_comp_pr_cuenta_contable"  size='12' maxlength="12"  onchange="consulta_automatica_cuentas_contables()" onblur="consulta_automatica_cuentas_contables()" 
				message="Introduzca la cuenta contable" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Cuenta invalida ', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		       <input type="text" name="cuenta_nombre" id="cuenta_nombre" />
			    <input type="text" id="contabilidad_auxiliares_db_id_cuenta" name="contabilidad_auxiliares_db_id_cuenta" />
		 </li>
		<li id="contabilidad_comprobante_btn_consultar_cuenta" class="btn_consulta_emergente"></li>
	    </ul>			  </td>	
	</tr>
		<tr>
			<th>
				debe:			</th>
			<td>
				<input type="text" name="debe" id="debe" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event);" value="0,00" message="Introduzca el monto dle asiento"  size="12" maxlength="12">			</td>
		</tr>
		<tr>
			<th>haber:</th>
			<td>
				<input type="text" name="haber" id="haber" onKeyPress="reais(this,event)" onKeyDown="backspace(this,event);" value="0,00" message="Introduzca el monto dle asiento"  size="12" maxlength="12">			</td>
		</tr>
			
		<tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
	
  
<input   type="hidden" name="contabilidad_auxiliares_db_id_aux"  id="contabilidad_auxiliares_db_id_aux" />
</form>