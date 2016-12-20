<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
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

<script>
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
var dialog;
$("#parametro_tesoreria_db_btn_guardar").click(function() {
	
	if(($('#form_db_parametro_tesoreria').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/tesoreria/parametros_tesoreria/db/sql.parametro_tesoreria.php",
			data:dataForm('form_db_parametro_tesoreria'),
			type:'POST',
			cache: false,
			success: function(html)
			{
			
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('parametro_tesoreria_db_ultimo_mes').value='1';
					getObj('parametro_tesoreria_db_btn_cancelar').style.display='';
					getObj('parametro_tesoreria_db_btn_eliminar').style.display='none';
					getObj('parametro_tesoreria_db_btn_actualizar').style.display='none';
					getObj('parametro_tesoreria_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_tesoreria');
					getObj('parametro_tesoreria_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_tesoreria_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
					getObj('parametro_tesoreria_db_porcentaje_itf').value='0,00';
					getObj('parametro_tesoreria_db_factor_islr').value='00,000000';
				}
				else
				{
	//setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO COMPLETAR LA OPERTACION</p></div>",true,true);

				}
			}
		});
	}
});
$("#parametro_tesoreria_db_btn_eliminar").click(function() {
  
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/tesoreria/parametros_tesoreria/db/sql.eliminar.php",
			data:dataForm('form_db_parametro_tesoreria'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('parametro_tesoreria_db_btn_consultar').style.display='';
					getObj('parametro_tesoreria_db_ultimo_mes').value='1';
					getObj('parametro_tesoreria_db_btn_cancelar').style.display='';
					getObj('parametro_tesoreria_db_btn_eliminar').style.display='none';
					getObj('parametro_tesoreria_db_btn_actualizar').style.display='none';
					getObj('parametro_tesoreria_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_tesoreria');
					getObj('parametro_tesoreria_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_tesoreria_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
					getObj('parametro_tesoreria_db_porcentaje_itf').value='0,00';
					getObj('parametro_tesoreria_db_factor_islr').value='00,000000';
				}
				else
				if(html="no_eliminar_xayos")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUEDE ELIMINAR PARAMETROS PERTENECIENTE AL AÑO EN CURSO </p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
  }
);
$("#parametro_tesoreria_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/parametros_tesoreria/db/vista.grid_parametro_tesoreria.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
					dialog=new Boxy(data,{ title: 'Consulta Emergente de Parametros', modal: true,center:false,x:0,y:0,show:false});
					dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_tesoreria_db_nombre_organismo").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/parametros_tesoreria/db/sql_parametro_tesoreria.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_tesoreria_db_nombre_organismo").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_tesoreria_dosearch();
												
					});
					function programa_tesoreria_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_tesoreria_gridReload,500)
										}
						function programa_tesoreria_gridReload()
						{
							var busq_nombre= jQuery("#parametro_tesoreria_db_nombre_organismo").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/parametros_tesoreria/db/sql_parametro_tesoreria.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/parametros_tesoreria/db/sql_parametro_tesoreria.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','A&ntilde;o','Fecha Ciere mes','Fecha Ciere Anual','Porcentaje ITF ','Factor ISLR','Comentario','Ultimo Mes'],
								colModel:[
									{name:'id_parametros_tesoreria',index:'id_parametros_tesoreria', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false},
									{name:'ano',index:'ano', width:20,sortable:false,resizable:false},
									{name:'fecha_cierre_mes',index:'fecha_cierre_mes', width:50,sortable:false,resizable:false},
									{name:'fecha_cierre_anual',index:'fecha_cierre_anual', width:50,sortable:false,resizable:false},
									{name:'porcentaje_itf',index:'porcentaje_itf', width:50,sortable:false,resizable:false,hidden:true},
									{name:'factor_islr',index:'factor_islr', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ultimo',index:'ultimo', width:50,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('parametro_tesoreria_db_id_parametros_tesoreria').value = ret.id_parametros_tesoreria;
									getObj('parametro_tesoreria_db_anio').value = ret.ano;
									getObj('parametro_tesoreria_db_porcentaje_itf').value=ret.porcentaje_itf;
									getObj('parametro_tesoreria_db_factor_islr').value=ret.factor_islr;
									getObj('parametro_tesoreria_db_fecha_cierre_mes').value = ret.fecha_cierre_mes.substr(0, 10);
									getObj('parametro_tesoreria_db_fecha_cierre_anual').value = ret.fecha_cierre_anual.substr(0, 10);
									getObj('parametro_tesoreria_db_comentario').value = ret.comentario;
									getObj('parametro_tesoreria_db_btn_cancelar').style.display='';
									getObj('parametro_tesoreria_db_btn_eliminar').style.display='';
									getObj('parametro_tesoreria_db_btn_actualizar').style.display='';
									getObj('parametro_tesoreria_db_btn_guardar').style.display='none';
									getObj('parametro_tesoreria_db_ultimo_mes').value=ret.ultimo;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_tesoreria_db_nombre_organismo").focus();
								$('#parametro_tesoreria_db_nombre_organismo').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_tesoreria',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
/*$("#parametro_tesoreria_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/tesoreria/parametro_tesoreria/db/grid_parametro_tesoreria.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Parametro tesoreria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/parametro_tesoreria/db/sql_grid_parametro_tesoreria.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Organismo','A&ntilde;o','Precompromiso','Compromiso','Ultimo mes','Fecha Ciere mes','Fecha Ciere Anual','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false},
									{name:'anio',index:'anio', width:150,sortable:false,resizable:false},
									{name:'precompromiso',index:'precompromiso', width:150,sortable:false,resizable:false,hidden:true},
									{name:'compromiso',index:'compromiso', width:100,sortable:false,resizable:false,hidden:true},
									{name:'ultimo_mes',index:'ultimo_mes', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha_mes',index:'fecha_mes', width:250,sortable:false,resizable:false},
									{name:'fecha_anual',index:'fecha_anual', width:250,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('parametro_tesoreria_db_id_parametros_tesoreria').value = ret.id_parametros_tesoreria;
									getObj('parametro_tesoreria_db_anio').value = ret.anio;
									getObj('parametro_tesoreria_db_num_precompromiso').value = ret.precompromiso;
									getObj('parametro_tesoreria_db_num_compromiso').value = ret.compromiso;
									getObj('parametro_tesoreria_db_ultimo_mes').value = ret.ultimo_mes;
									getObj('parametro_tesoreria_db_fecha_cierre_mes').value = ret.fecha_mes.substr(0, 10);
									getObj('parametro_tesoreria_db_fecha_cierre_anual').value = ret.fecha_anual.substr(0, 10);
									getObj('parametro_tesoreria_db_comentario').value = ret.comentario;
									getObj('parametro_tesoreria_db_btn_cancelar').style.display='';
									getObj('parametro_tesoreria_db_btn_eliminar').style.display='';
									getObj('parametro_tesoreria_db_btn_actualizar').style.display='';
									getObj('parametro_tesoreria_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									if(xhr.status == 200){
									setBarraEstado("No se han registrado datos en este organismo para buscar");
									}else{
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
									}
								},															
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/
$("#parametro_tesoreria_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_parametro_tesoreria').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/parametros_tesoreria/db/sql.actualizar.php",
			data:dataForm('form_db_parametro_tesoreria'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('parametro_tesoreria_db_btn_eliminar').style.display='none';
					getObj('parametro_tesoreria_db_btn_actualizar').style.display='none';
					getObj('parametro_tesoreria_db_btn_guardar').style.display='';
					clearForm('form_db_parametro_tesoreria');
					getObj('parametro_tesoreria_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
					getObj('parametro_tesoreria_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#parametro_tesoreria_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('parametro_tesoreria_db_ultimo_mes').value='1';
	getObj('parametro_tesoreria_db_btn_cancelar').style.display='';
	getObj('parametro_tesoreria_db_btn_eliminar').style.display='none';
	getObj('parametro_tesoreria_db_btn_actualizar').style.display='none';
	getObj('parametro_tesoreria_db_btn_guardar').style.display='';
	clearForm('form_db_parametro_tesoreria');
	getObj('parametro_tesoreria_db_fecha_cierre_mes').value="<?= date ('d/m/Y')?>";
	getObj('parametro_tesoreria_db_fecha_cierre_anual').value="<?= date ('d/m/Y') ?>";
	getObj('parametro_tesoreria_db_porcentaje_itf').value='0,00';
	getObj('parametro_tesoreria_db_factor_islr').value='00,000000';
});
$('#parametro_tesoreria_db_anio').numeric({allow:''});
$('#parametro_tesoreria_db_num_precompromiso').numeric({allow:'_'});
$('#parametro_tesoreria_db_num_compromiso').numeric({allow:'_'});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="parametro_tesoreria_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
  <img id="parametro_tesoreria_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="parametro_tesoreria_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" onClick="Application.evalCode('win_popup_armador', true);" />
	<img id="parametro_tesoreria_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="parametro_tesoreria_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onClick="guardar()" /></div>
<form name="form_db_parametro_tesoreria" id="form_db_parametro_tesoreria">
  <table class="cuerpo_formulario">
  <tr>
			<th colspan="2" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />
			Par&aacute;metros Tesorer&iacute;a </th>
	</tr>
		
		<tr>
			<th>A&ntilde;o :						</th>
			<td>	<input name="parametro_tesoreria_db_anio" type="text" id="parametro_tesoreria_db_anio" size="6" maxlength="4" message="Introduzca el a&ntilde;o " jVal="{valid:/^[0-9]{1,60}$/, message:'A&ntilde;o Invalida', styleType:'cover'}"	jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['A&ntilde;o: '+$(this).val()]}"></td>
		</tr>
		<tr><th>Porcentaje ITF:</th>
		<td><input name="parametro_tesoreria_db_porcentaje_itf" type="text" id="parametro_tesoreria_db_porcentaje_itf" size="12" maxlength="12" value="0,00" onkeypress="reais(this,event)" onkeydown="backspace(this,event)"
		message="Introduzca el % del impuesto a transferencia bancaria" 
		jVal="{valid:/^[0123456789 .,]{1,10}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789 .,]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		</td>
		</tr>
		<tr>
		  <th>Factor ISLR:</th>
		  <td><input name="parametro_tesoreria_db_factor_islr" type="text" id="parametro_tesoreria_db_factor_islr" size="12" maxlength="12" value="0,0000" 
		message="Introduzca el factor para el calculo del sustraendo del impuesto sobre la renta" 
		jVal="{valid:/^[0123456789 .,]{1,10}$/, message:'N&uacute;mero  Invalido', styleType:'cover'}"
			jValKey="{valid:/[0123456789 .,]/, cFunc:'alert', cArgs:['N&uacute;mero: '+$(this).val()]}"/>
		<!--
		alt="factor-c"
		-->
		</td>
		</tr>
		<tr><th>Ultimo mes Cerrado:</th>
			<td><select name="parametro_tesoreria_db_ultimo_mes" id="parametro_tesoreria_db_ultimo_mes" style="width:90px; min-width:90px;">
					<option value="1">Enero</option>
					<option value="2">Febrero</option>
					<option value="3">Marzo</option>
					<option value="4">Abril</option>
					<option value="5">Mayo</option>
					<option value="6">Junio</option>
					<option value="7">Julio</option>
					<option value="8">Agosto</option>
					<option value="9">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select></td>
		</tr>
		<tr>
			<th>Fecha Cierre Mes :	</th>
			<td><input name="parametro_tesoreria_db_fecha_cierre_mes" type="text" id="parametro_tesoreria_db_fecha_cierre_mes" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_tesoreria_db_fecha_mes_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_tesoreria_db_fecha_cierre_mes",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_tesoreria_db_fecha_mes_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>			</td>
		</tr>
		<tr>
			<th>Fecha Cierre Anual :	</th>
			<td><input name="parametro_tesoreria_db_fecha_cierre_anual" type="text" id="parametro_tesoreria_db_fecha_cierre_anual" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha Cierre Mes">
		  <button type="reset" id="parametro_tesoreria_db_fecha_anual_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "parametro_tesoreria_db_fecha_cierre_anual",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "parametro_tesoreria_db_fecha_anual_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>			</td>
		</tr>
		<tr>
			<th>Comentario :			</th>
			<td>	<textarea name="parametro_tesoreria_db_comentario" cols="60" id="parametro_tesoreria_db_comentario" message="Introduzca un Comentario"></textarea></td>
		</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;
      <input type="hidden" name="parametro_tesoreria_db_id_parametros_tesoreria" id="parametro_tesoreria_db_id_parametros_tesoreria" /></td>
  </table>
	<span class="bottom_frame"><span class="titulo_frame">
	</span></span>
</form>