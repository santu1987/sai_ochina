<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
$("#tesoreria_banco_cuenta_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/banco_cuentas/db/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cuentas Bancarias', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/db/sql_grid_banco_cuentas.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
				}	
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				//-busqueda por banco
				$("#tesoreria_banco_cuenta_banco-busqueda_bancos").keypress(function(key)
				{	banco_dosearch();
				});
					function banco_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(banco_gridReload,500)
						}
					function banco_gridReload()
					{
							var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/db/sql_grid_banco_cuentas.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid");			
						}
							$("#tesoreria_banco_cuenta-busqueda_boton_filtro").click(function(){
							
					       // banco_dosearch();
							if(getObj('tesoreria_banco_cuenta_banco-busqueda_bancos').value!="")banco_dosearch();
    					 	})
	
				}
		});
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/banco_cuentas/db/sql_grid_banco_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Id_banco','Banco','banco_largo','Cuenta','Cuenta Contable','Comentarios','Estatus','A&ntilde;o','saldo','saldo_ctual','fecha'],
							
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idbanco',index:'idbanco', width:50,sortable:false,resizable:false,hidden:true},
								    {name:'banco_corto',index:'banco_corto', width:60,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:60,sortable:false,resizable:false,hidden:true},
									{name:'cuenta',index:'cuenta', width:70,sortable:false,resizable:false},
									{name:'cuentacontable' ,index:'cuentacontable', width:70,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'estatus',index:'estatus', width:40,sortable:false,resizable:false},
									{name:'ayo',index:'ayo', width:40,sortable:false,resizable:false},
									{name:'saldo',index:'saldo', width:40,sortable:false,resizable:false,hidden:true },
									{name:'saldo_actual',index:'saldo_actual', width:40,sortable:false,resizable:false,hidden:true },
									{name:'fecha_apertura',index:'fecha_apertura', width:40,sortable:false,resizable:false,hidden:true }
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_vista_banco_cuenta').value=ret.id;
									getObj('tesoreria_banco_cuenta_id_banco').value = ret.idbanco;
									getObj('tesoreria_banco_cuenta_db_nombre').value = ret.nombre;
									getObj('tesoreria_vista_banco_cuenta').value=ret.id;
									getObj('tesoreria_banco_cuenta_db_n_cuenta').value=ret.cuenta;
									getObj('tesoreria_banco_cuenta_db_cuenta_contable').value=ret.cuentacontable;
									getObj('tesoreria_banco_cuenta_db_btn_eliminar').style.display='';
									getObj('tesoreria_banco_cuenta_db_btn_cancelar').style.display='';
									getObj('tesoreria_banco_cuenta_db_btn_actualizar').style.display='';
									getObj('tesoreria_banco_cuenta_db_btn_guardar').style.display='none';
									getObj('tesoreria_banco_cuenta_db_ayo').value=ret.ayo;	
									getObj('tesoreria_banco_db_comentarios').value=ret.comentarios;	
									getObj('tesoreria_banco_cuenta_saldo_inicial').value=ret.saldo;
									getObj('tesoreria_banco_cuenta_saldo').value=ret.saldo_actual;
									getObj('tesoreria_banco_cuenta_db_fecha_v').value=ret.fecha_apertura;
									getObj('tesoreria_banco_cuenta_db_n_cuenta').disabled='disabled';
									getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='disabled';
									getObj('tesoreria_banco_cuenta_saldo').disabled='disabled';
									if(ret.estatus=="Activo")
						     	    {  
										getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
										getObj('tesoreria_banco_cuenta_db_estatus').value="1";
									}else
									{
									getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="checked";
									getObj('tesoreria_banco_cuenta_db_estatus').value="2";
									}							
									dialog.hideAndUnload();
								//$('#form_tesoreria_db_banco_cuenta').jVal();
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

$("#tesoreria_banco_cuenta_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/banco_cuentas/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_banco_cuenta'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					getObj('tesoreria_banco_cuenta_db_btn_eliminar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_actualizar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_guardar').style.display='';
					//getObj('tesoreria_banco_db_cuenta_btn_cancelar').style.display='';
					clearForm('form_tesoreria_db_banco_cuenta');
					getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='';
					getObj('tesoreria_banco_cuenta_saldo').disabled='';
					getObj('tesoreria_banco_cuenta_db_n_cuenta').disabled='';
					getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_cuenta_db_estatus').value="1";	
					getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
					getObj('tesoreria_banco_cuenta_saldo').value="0,00";
					getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";										
						}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('tesoreria_banco_cuenta_db_btn_eliminar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_actualizar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_guardar').style.display='';
					//getObj('tesoreria_banco_cuenta_db_btn_cancelar').style.display='';
					clearForm('form_tesoreria_db_banco_cuenta');
					getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='';
					getObj('tesoreria_banco_cuenta_saldo').disabled='';
					getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_cuenta_db_estatus').value="1";
					getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
					getObj('tesoreria_banco_cuenta_saldo').value="0,00";
					getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
				}
				else
				{
					setBarraEstado(html);
					getObj('tesoreria_banco_db_direccion').value=html;
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			}
		});
	}
});
$("#tesoreria_banco_cuenta_db_btn_guardar").click(function() {
	if($('#form_tesoreria_db_banco_cuenta').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/banco_cuentas/db/sql.registrar.php",
			data:dataForm('form_tesoreria_db_banco_cuenta'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_banco_cuenta');
					getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='';
					getObj('tesoreria_banco_cuenta_saldo').disabled='';
					getObj('tesoreria_banco_cuenta_db_n_cuenta').disabled='';
					getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_cuenta_db_estatus').value="1";	
					getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
					getObj('tesoreria_banco_cuenta_saldo').value="0,00";
					getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
					
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_tesoreria_db_banco_cuenta');
					getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='';
					getObj('tesoreria_banco_cuenta_saldo').disabled='';
					getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_cuenta_db_estatus').value="1";
					getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
					getObj('tesoreria_banco_cuenta_saldo').value="0,00";
					getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
							}
					else
				{
					alert(html);
					//setBarraEstado(html);
				//getObj('tesoreria_banco_db_direccion').value=html;
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			
			}
		});
	}
});

$("#tesoreria_db_btn_consultar_banco").click(function() {

/*	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/banco_cuentas/db/grid_banco_cuenta.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos Activos', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/banco_cuentas/db/grid_banco_cuenta.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_banco_cuenta_banco-busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_rel_banco_us_dosearch();
					});
				
						function consulta_rel_banco_us_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_rel_banco_us_gridReload,500)
										}
						function consulta_rel_banco_us_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_banco_cuenta_banco-busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco_cuentas/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							//url="modulos/tesoreria/movimientos/db/sql_grid_banco.php";
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
								url:'modulos/tesoreria/banco_cuentas/db/sql_grid_banco.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Nombre','Sucursal','Direccion','Estatus'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'sucursal',index:'sucursal', width:200,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:200,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:200,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_banco_cuenta_id_banco').value = ret.id;
									getObj('tesoreria_banco_cuenta_db_nombre').value = ret.nombre;
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
	
});
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_banco_cuenta_db_btn_eliminar").click(function() {
 Boxy.ask("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />	Desea realmente eliminar el registro ?, Nota:de ser incorrectos debera anular el cheque impreso</p></div>", ["ACEPTAR","CANCELAR"], 
function(val)
 {
	if(val=="ACEPTAR")
	{
		$.ajax (
		{
			url: "modulos/tesoreria/banco_cuentas/db/sql.eliminar.php",
			data:dataForm('form_tesoreria_db_banco_cuenta'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					clearForm('form_tesoreria_db_banco_cuenta');
					getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_cuenta_db_estatus').value="1";
					getObj('tesoreria_banco_cuenta_db_btn_cancelar').style.display='';
					getObj('tesoreria_banco_cuenta_db_btn_actualizar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_eliminar').style.display='none';
					getObj('tesoreria_banco_cuenta_db_btn_guardar').style.display='';
					getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
					getObj('tesoreria_banco_cuenta_saldo').value="0,00";
					getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";		
					
				}
				else
				{
					//setBarraEstado(mensaje[relacion_existe],true,true);
				//setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Imposible Realizar eliminaciÛn debido a que el registro guarda relacion con otras tablas</p></div>",true,true);
				setBarraEstado(html);	
				}
			}
		});
		}
	});
});
//----
// -----------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_banco_cuenta_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tesoreria_banco_cuenta_saldo_inicial').disabled='';
	getObj('tesoreria_banco_cuenta_saldo').disabled='';
    getObj('tesoreria_banco_cuenta_db_btn_eliminar').style.display='none';
	getObj('tesoreria_banco_cuenta_db_btn_actualizar').style.display='none';
	getObj('tesoreria_banco_cuenta_db_btn_guardar').style.display='';
	getObj('tesoreria_banco_cuenta_db_btn_consultar').style.display='';
	clearForm('form_tesoreria_db_banco_cuenta');
	getObj('tesoreria_banco_cuenta_db_estatus_opt_act').checked="checked";
	getObj('tesoreria_banco_cuenta_db_estatus_opt_inact').checked="";
	getObj('tesoreria_banco_cuenta_db_estatus').value="1";
	getObj('tesoreria_banco_cuenta_db_ayo').value="2009";
	getObj('tesoreria_banco_cuenta_db_n_cuenta').disabled='';
	getObj('tesoreria_banco_cuenta_saldo_inicial').value="0,00";
	getObj('tesoreria_banco_cuenta_saldo').value="0,00";
	getObj('tesoreria_banco_cuenta_db_fecha_v').value="<?=  date("d/m/Y"); ?>";	
	
});
////////////////////////////-----------------
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

//////////////////////////////////////////////
//consultas automaticas
function consulta_automatica_banco_cuenta()
{
	
	$.ajax({
			url:"modulos/tesoreria/banco_cuentas/db/sql_grid_codigo.php",
            data:dataForm('form_tesoreria_db_banco_cuenta'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
					recordset = recordset.split("*");
					getObj('tesoreria_banco_cuenta_id_banco').value=recordset[0];
					getObj('tesoreria_banco_cuenta_db_codigo').value=recordset[1];				
					getObj('tesoreria_banco_cuenta_db_nombre').value=recordset[2];
										
				 }
				 else
				 {
				 	setBarraEstado("");
					getObj('tesoreria_banco_cuenta_id_banco').value="";
					getObj('tesoreria_banco_cuenta_db_codigo').value="";				
					getObj('tesoreria_banco_cuenta_db_nombre').value="";
					
				 }
			 }
		});	 	 
		
}
//
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
  oTxt=getObj('tesoreria_banco_cuenta_db_fecha_v');
  var bOk = true;
  if (oTxt.value != ""){
   bOk = bOk && (valAno(oTxt));
   bOk = bOk && (valMes(oTxt));
   bOk = bOk && (valDia(oTxt));
   bOk = bOk && (valSep(oTxt));
   if (!bOk){
   alert("Fecha inv·lida");
   oTxt.value ="<?= date("d/m/Y")?>";
  // getObj('cuentas_por_pagar_db_fecha_v').value = date();
  // oTxt.focus();
   } //else alert("Fecha correcta");
  }
  }
 //
 $("#tesoreria_db_btn_consultar_cuenta_contable").click(function() {
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom=act?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom,page:1}).trigger("reloadGrid");					
					url="'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid");					
					url="'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
              //   alert(url);				
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
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
				colNames:['','Cuenta','Nombre','Tipo','tipo','id_cuenta_suma','cuenta_suma','id_cuenta_presupuesto','cuenta_presupuesto','naturaleza_cuenta','requiere_auxiliar','requiere_proyecto','requiere_unidad_ejecutora'],
			colModel:[
				{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
				{name:'cuenta',index:'cuenta', width:50,sortable:false,resizable:false},
				{name:'nombre',index:'nombre', width:250,sortable:false,resizable:false},
				{name:'tipo_cuenta',index:'tipo_cuenta', width:60,sortable:false,resizable:false},
				{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false,hidden:true},
				{name:'id_cuenta_suma',index:'id_cuenta_suma', width:100,sortable:false,resizable:false,hidden:true},
				{name:'cuenta_suma',index:'cuenta_suma', width:100,sortable:false,resizable:false,hidden:true},
				{name:'id_cuenta_presupuesto',index:'id_cuenta_presupuesto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'cuenta_presupuesto',index:'cuenta_presupuesto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'naturaleza_cuenta',index:'naturaleza_cuenta', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_auxiliar',index:'requiere_auxiliar', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_proyecto',index:'requiere_proyecto', width:100,sortable:false,resizable:false,hidden:true},
				{name:'requiere_unidad_ejecutora',index:'requiere_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true}
			],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#tesoreria_banco_cuenta_db_cuenta_contable').val(ret.cuenta);
									$('#tesoreria_banco_cuenta_id_cuenta_contable').val(ret.id);
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
//
function cambio_monto()
{
getObj('tesoreria_banco_cuenta_saldo').value=getObj('tesoreria_banco_cuenta_saldo_inicial').value;

}

$("#tesoreria_banco_cuenta_db_estatus_opt_act").click(function(){
		getObj('tesoreria_banco_cuenta_db_estatus').value="1"
	});
$("#tesoreria_banco_cuenta_db_estatus_opt_inact").click(function(){
		getObj('tesoreria_banco_cuenta_db_estatus').value="2"
	});
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#tesoreria_banco_cuenta_db_n_cuenta').numeric({allow:'-'});
$('#tesoreria_banco_cuenta_db_cuenta_contable').numeric({allow:'-'});
$('#tesoreria_banco_cuenta_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄- '});
$('#tesoreria_banco_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
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
	<img id="tesoreria_banco_cuenta_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="tesoreria_banco_cuenta_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_banco_cuenta_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="tesoreria_banco_cuenta_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tesoreria_banco_cuenta_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_tesoreria_db_banco_cuenta" name="form_tesoreria_db_banco_cuenta">
<input type="hidden"  id="tesoreria_vista_banco_cuenta" name="tesoreria_vista_banco_cuenta"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Cuentas  </th>
	</tr>
	<tr>
		<th>
			A&ntilde;o
		:</th>
		<td>
			<select  name="tesoreria_banco_cuenta_db_ayo" id="tesoreria_banco_cuenta_db_ayo">
					<?
					$anio_inicio=2011;
					$anio_fin=2012;
					while($anio_inicio <= $anio_fin)
					{
					?>
					<option value="<?=$anio_inicio;?>"><?=$anio_inicio;?></option>
					<?
						$anio_inicio++;
					}
					?>
		  </select>
		</td>
	</tr>
	<tr>
		<th>Banco:</th>
	    <td>
	  <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_banco_cuenta_db_nombre" type="text" id="tesoreria_banco_cuenta_db_nombre"   value="" size="50" maxlength="30" 
				message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄ -_]{2,60}$/, message:'Banco Invalido', styleType:'cover'}"
				jValKey="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄-_]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
			/>
	  		<input type="hidden"  id="tesoreria_banco_cuenta_id_banco" name="tesoreria_banco_cuenta_id_banco"/>
		</li>
		<li id="tesoreria_db_btn_consultar_banco" class="btn_consulta_emergente"></li>
	</ul>
	</td>
	</tr>
   <tr>
		<th>N&ordm; Cuenta: </th>	
	    <td>	
		<input name="tesoreria_banco_cuenta_db_n_cuenta" type="text" id="tesoreria_banco_cuenta_db_n_cuenta"   value="" size="50" maxlength="20" message="Introduzca el N˙mero de cuenta. " 
			jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/></td>

   </tr>
   <tr>
		<th>Cuenta Cotable: </th>
		<td>
		<ul class="input_con_emergente">
		<li>
		    <input name="tesoreria_banco_cuenta_db_cuenta_contable" type="text" id="tesoreria_banco_cuenta_db_cuenta_contable"   value="" size="50" maxlength="20" message="Introduzca el N˙mero de cuenta contable. " 
			/>
	  		<!--jVal="{valid:/^[0123456789]{1,20}$/, message:'N&uacute;mero de cuenta contable Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"-->
            <input type="hidden"  id="tesoreria_banco_cuenta_id_cuenta_contable" name="tesoreria_banco_cuenta_id_cuenta_contable"/>
		</li>
		<li id="tesoreria_db_btn_consultar_cuenta_contable" class="btn_consulta_emergente"></li>
	</ul>
				</td>
	</tr>
	
		<th>Comentarios:</th>
		<td><textarea  name="tesoreria_banco_db_comentarios" cols="60" id="tesoreria_banco_db_comentarios" message="Introduzca un comentario."></textarea>		</td>
	</tr>
		<tr style="display:none">
			<th>Saldo Inicial: </th>
			<td><input type="text" name="tesoreria_banco_cuenta_saldo_inicial" id="tesoreria_banco_cuenta_saldo_inicial" onkeypress="reais(this,event)" onkeydown="backspace(this,event);"  onblur="cambio_monto()"value="0,00" size="16" maxlength="16"  style="text-align:right"  message="Ingrese el valor del saldo Actual." jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['AÒo: '+$(this).val()]}" />
			</td>
		</tr>
		<tr style="display:none">
			<th>Saldo Actual: </th>
			<td><input  type="text" name="tesoreria_banco_cuenta_saldo" id="tesoreria_banco_cuenta_saldo"  readonly="readonly"  value="0,00" size="16" maxlength="16"  style="text-align:right"  message="Ingrese el valor del saldo Actual." />
			</td>
		</tr>
		<tr>
			<th>Fecha Apertura </th>
			<td><label><input   alt="date" type="text" name="tesoreria_banco_cuenta_db_fecha_v" id="tesoreria_banco_cuenta_db_fecha_v" size="7"  onchange="valFecha();" onblur="valFecha();" value="<? $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha : '+$(this).val()]}"/>
	      
	      <button type="reset" id="tesoreria_banco_cuenta_db_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_banco_cuenta_db_fecha_v",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_banco_cuenta_db_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_banco_cuenta_db_fecha_v").value.MMDDAAAA() );
								
						}
					});
			</script>
			
	      </label></td>
		</tr>
		<tr> 
		<th>Estatus:</th>
		<td>
		   	<input id="tesoreria_banco_cuenta_db_estatus_opt_act" name="tesoreria_banco_cuenta_db_estatus_opt"  type="radio" value="1" checked="checked" />Activo
	      	<input id="tesoreria_banco_cuenta_db_estatus_opt_inact" name="tesoreria_banco_cuenta_db_estatus_opt"  type="radio" value="2" />Inactivo
          <input type="hidden" id="tesoreria_banco_cuenta_db_estatus" name="tesoreria_banco_cuenta_db_estatus"  value="1" /></td>
		</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>  
<input  name="tesoreria_banco_cuenta_db_id" type="hidden" id="" />
</form>