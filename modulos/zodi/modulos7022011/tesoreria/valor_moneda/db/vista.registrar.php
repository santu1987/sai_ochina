<? if (!$_SESSION) session_start();
?>
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
var dialog;
//----------------------------------------------------------------------------------------------------
$("#valor_moneda_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/valor_moneda/db/grid_valor_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Valor Impuesto', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/tesoreria/valor_moneda/db/sql_grid_valor_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['id','fecha','porcentaje_moneda','comentario','codigo del moneda','nombre del moneda'],
								colModel:[
									{name:'id_val_moneda',index:'id_val_moneda', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false},
									{name:'porcentaje_moneda',index:'porcentaje_moneda', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true},
									{name:'codigo_moneda',index:'codigo_moneda', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('valor_moneda_db_id_val_moneda').value = ret.id_val_moneda;
									getObj('valor_moneda_db_codigo_moneda').value = ret.codigo_moneda;
									getObj('valor_moneda_db_nombre_moneda').value = ret.nombre;
									getObj('valor_moneda_db_fecha').value = ret.fecha;
									getObj('valor_moneda_db_porcentaje').value = ret.porcentaje_moneda.replace('.',',');
									getObj('valor_moneda_db_comentario').value = ret.comentarios;
									getObj('valor_moneda_db_btn_guardar').style.display='none';
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
								sortname: 'id_val_impu',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#valor_moneda_db_btn_guardar").click(function() {
	if ($('#form_db_valor_moneda').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/tesoreria/valor_moneda/db/sql.registrar.php",
			data:dataForm('form_db_valor_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_valor_moneda');
					getObj('valor_moneda_db_fecha').value='<?php echo date("d-m-Y"); ?>';
					getObj('valor_moneda_db_codigo_moneda').value='';
					getObj('valor_moneda_db_nombre_moneda').value='';
					getObj('valor_moneda_db_porcentaje').value = '0,00';
					getObj('valor_moneda_db_comentario').value = '';
					
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del moneda \n tiene que ser mayor que la fecha actual </p></div>",true,true);
					}
				else 
				{//getObj('cargar_cotizacion_db_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});


//----------------------------------------------------------------



$("#valor_moneda_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/valor_moneda/db/vista.grid_moneda.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#valor_moneda_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/valor_moneda/db/sql_moneda_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#dbograma-consultas-busq_nombre").keydbess(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#valor_moneda_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
					function programa_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_gridReload,500)
										}
						function programa_gridReload()
						{
							var busq_nombre= jQuery("#valor_moneda_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/valor_moneda/db/sql_moneda_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/tesoreria/valor_moneda/db/sql_moneda_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
									{name:'id_moneda',index:'id_moneda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_moneda',index:'codigo_moneda', width:300,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('valor_moneda_db_id_moneda').value=ret.id_moneda;
									getObj('valor_moneda_db_codigo_moneda').value=ret.codigo_moneda;
									getObj('valor_moneda_db_nombre_moneda').value=ret.nombre;
									getObj('valor_moneda_db_id').value=ret.id_moneda;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#valor_moneda_db_nombre").focus();
								$('#valor_moneda_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_moneda',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});


var fecha = getObj('valor_moneda_db_fechact').value;													
$("#valor_moneda_db_btn_cancelar").click(function() {
clearForm('form_db_valor_moneda');
getObj('valor_moneda_db_porcentaje').value = "0,00";
getObj('valor_moneda_db_fecha').value = fecha;
getObj('valor_moneda_db_btn_guardar').style.display='';
//getObj('valor_moneda_db_mes').selectedIndex= 0;
//getObj('valor_moneda_bd_observacion').value = '';
setBarraEstado("");
});
//------------------------- funciones emergentes automaticas
function consulta_automatica_moneda()
{
	$.ajax({
			url:'modulos/tesoreria/valor_moneda/db/sql_grid_moneda.php',
            data:dataForm('form_db_valor_moneda'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		   	var recordset=html;	
				//alert(html);		
				
				if(html=="vacio")
				{
					clearForm('form_db_valor_moneda');
					getObj('valor_moneda_db_porcentaje').value = "0,00";
					getObj('valor_moneda_db_fecha').value = fecha;
					//getObj('valor_moneda_db_mes').selectedIndex= 0;
					//getObj('valor_moneda_bd_observacion').value = '';
					setBarraEstado("");
				}
				else
				if(recordset)
				{
					recordset = recordset.split("*");
					getObj('valor_moneda_db_nombre_moneda').value=recordset[2];
					getObj('valor_moneda_db_id_moneda').value=recordset[0];
					
				}
				else
			 {  
			   	
				}
				
			 }
		});	 	 
}

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
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#valor_moneda_db_codigo_moneda').numeric({allow:''});
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
	$('#valor_moneda_db_codigo_moneda').blur(consulta_automatica_moneda);
//
</script>
<div id="botonera">
	<img id="valor_moneda_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="valor_moneda_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
	<img id="valor_moneda_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_valor_moneda" id="form_db_valor_moneda">
<input type="hidden" name="cargar_cotizacion_pr_id" id="cargar_cotizacion_pr_id" />
<input type="hidden" name="cargar_cotizacion_pr_id_detalle" id="cargar_cotizacion_pr_id_detalle" />
<input type="hidden" name="valor_moneda_db_fechact" id="valor_moneda_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="valor_moneda_db_id_moneda" id="valor_moneda_db_id_moneda"/>
<input type="hidden" name="valor_moneda_db_id_val_moneda" id="valor_moneda_db_id_val_moneda"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Valor Moneda			</th>
	</tr>
    	<tr>
			<th>Moneda:</th>
		    <td>
          <ul class="input_con_emergente">
				<li>
		   <input name="valor_moneda_db_codigo_moneda" type="text" id="valor_moneda_db_codigo_moneda" size="4" maxlength="4" onchange="consulta_automatica_moneda" message="Introduzca el Codigo de la moneda." jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
           <input name="valor_moneda_db_nombre_moneda" type="text"  id="valor_moneda_db_nombre_moneda" maxlength="60" size="30" readonly="true"/>
		 <input name="valor_moneda_db_id" id="valor_moneda_db_id" type="hidden" />
		   </li>
				<li id="valor_moneda_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
		<tr>
			<th>Fecha:</th>
		    <td><label>
		    <input name="valor_moneda_db_fecha" type="text" id="valor_moneda_db_fecha" size="8" maxlength="10"  readonly="true" value="<?php echo date("d-m-Y");?>" message="hola"/><button id="boton_fecha">...</button>
            <script type="text/javascript">
					Calendar.setup({
						inputField     :    "valor_moneda_db_fecha",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "boton_fecha",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
		  </label></td>
		</tr>
		<tr>
			<th>Cambio:</th>
		    <td><input  name="valor_moneda_db_porcentaje" type="text" id="valor_moneda_db_porcentaje"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca el Porcentaje del Impuesto" jval="{valid:/^[0-9,]{1,12}$/, message:'Porcentaje Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9,]/, cFunc:'alert', cArgs:['Porcentaje: '+$(this).val()]}"/></td>
		</tr>
        <tr>
			<th>Observaci&oacute;n:</th>
		    <td><label>
		    <textarea name="valor_moneda_db_comentario" id="valor_moneda_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>