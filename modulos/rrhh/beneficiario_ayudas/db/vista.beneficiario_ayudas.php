<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);


?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
var dialog;
/*$("#beneficiario_ayudas_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/rrhh/beneficiario_ayudas/db/grid_beneficiario_ayudas.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente del Usuario', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
function consulta(cedula)
{
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		
		$.ajax ({
		    url:"modulos/rrhh/beneficiario_ayudas/db/grid_filtro_usuario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd+"&busq_cedula="+cedula,
			type:'POST', 
			cache: false,
			success: function(data)
				{
					
					dialog=new Boxy(data,{ title: 'Consulta Emergente de Ayudas Socio Econ&oacute;micas', modal: true,center:false,x:0,y:0,show:false});
					dialog_reload=function gridReload(){ 
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/beneficiario_ayudas/db/sql_grid_beneficiario_ayudas.php?busq_cedula="+cedula,page:1}).trigger("reloadGrid"); 
					}	
				
					crear_grid();
					var timeoutHnd; 
					var flAuto = true;
		
					$("#beneficiario_busq_nombre_usuario").keypress(function(key)
					{
							if(key.keyCode==27){this.close();}
							administracion_filtro_usuario_dosearch();													
					});
					
					function administracion_filtro_usuario_dosearch()
					{
						if(!flAuto) return; 
						// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
						clearTimeout(timeoutHnd) 
						timeoutHnd = setTimeout(administracion_filtro_usuario_gridReload,500)
					}
					
					function administracion_filtro_usuario_gridReload()
					{
						var busq_cedula=  jQuery("#beneficiario_busq_nombre_usuario").val(); 
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/beneficiario_ayudas/db/sql_grid_beneficiario_ayudas.php?busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid"); 		
					}
					
					if (cedula) {timeoutHnd = setTimeout(administracion_filtro_usuario_gridReload,100)}
			
				}
			});
	
			function crear_grid()
			{
				jQuery("#list_grid_"+nd).jqGrid
				({
					width:900,
					height:300,
					recordtext:"Registro(s)",
					loadtext: "Recuperando Información del Servidor",		
					url:'modulos/rrhh/beneficiario_ayudas/db/sql_grid_beneficiario_ayudas.php?nd='+nd,
					datatype: "json",
					colNames:['ID','Fecha','Cedula','Nombre','Apellido','Unidad','Monto','Concepto','Concepto'],
					colModel:[
						{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
						{name:'fecha',index:'fecha', width:100,sortable:false,resizable:false},
						{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
						{name:'nombre',index:'nombre', width:150,sortable:false,resizable:false},
						{name:'apellido',index:'apellido', width:150,sortable:false,resizable:false},
						{name:'unidad',index:'unidad', width:150,sortable:false,resizable:false},
						{name:'monto',index:'monto', width:250,sortable:false,resizable:false},
						{name:'concepto',index:'concepto', width:10,sortable:false,resizable:false,hidden:true},
						{name:'resumen',index:'resumen', width:250,sortable:false,resizable:false}
					],
					pager: $('#pager_grid_'+nd),
					rowNum:20,
					rowList:[20,50,100],
					imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
					onSelectRow: function(id){
					var ret = jQuery("#list_grid_"+nd).getRowData(id);
						getObj('vista_id_beneficiario_ayudas').value = ret.id;
						nac = ret.cedula;
						nac = nac.substr(0,2);
						if (nac=="P-"){nac = 2;}
						if (nac=="V-"){nac = 0;}
						if (nac=="E-"){nac = 1;}

						getObj('beneficiario_ayudas_db_vista_cedula').value = ret.cedula.substr(2,9);
						getObj('beneficiario_ayudas_db_vista_nombre').value = ret.nombre;
						getObj('beneficiario_ayudas_db_vista_apellido').value = ret.apellido;
						getObj('beneficiario_ayudas_db_vista_unidad').value = ret.unidad;

						getObj('rrhh_ayudasocioeconomica_db_monto').value=ret.monto;
						getObj('rrhh_ayudasocioeconomica_db_fecha').value=ret.fecha;
						getObj('rrhh_ayudasocioeconomica_db_concepto').value=ret.concepto;
						
						getObj('beneficiario_ayudas_db_btn_cancelar').style.display='';
						getObj('beneficiario_ayudas_db_btn_eliminar').style.display='';
						getObj('beneficiario_ayudas_db_btn_actualizar').style.display='';
						getObj('beneficiario_ayudas_db_btn_guardar').style.display='none';
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
					sortname: 'fecha',
					viewrecords: true,
					sortorder: "asc"
				});
			}
}

$("#beneficiario_ayudas_db_btn_consultar").click(function(){consulta('')});

$("#beneficiario_ayudas_db_btn_guardar").click(function() {
	
	if( $('#form_beneficiario_ayudas').jVal() )
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
			url: "modulos/rrhh/beneficiario_ayudas/db/sql.registrar.php",
			data:dataForm('form_beneficiario_ayudas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_beneficiario_ayudas.beneficiario_ayudas_db_vista_cedula.value="";
					document.form_beneficiario_ayudas.beneficiario_ayudas_db_vista_cedula.focus();
				}
				if (html=="Registrado")
				{					
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_beneficiario_ayudas');
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
					getObj('rrhh_ayudasocioeconomica_db_fecha').value='<?=date('d/m/Y')?>';					
				}
				else
				{
					setBarraEstado(html);
				
				}
			}
		});
	}
});

$("#beneficiario_ayudas_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/rrhh/beneficiario_ayudas/db/sql.eliminar.php",
			data:dataForm('form_beneficiario_ayudas'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('beneficiario_ayudas_db_btn_cancelar').style.display='';
					getObj('beneficiario_ayudas_db_btn_eliminar').style.display='none';
					getObj('beneficiario_ayudas_db_btn_actualizar').style.display='none';
					getObj('beneficiario_ayudas_db_btn_guardar').style.display='';
					clearForm('form_beneficiario_ayudas');
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
					getObj('rrhh_ayudasocioeconomica_db_fecha').value='<?=date('d/m/Y')?>';					
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#beneficiario_ayudas_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('beneficiario_ayudas_db_btn_eliminar').style.display='none';
	getObj('beneficiario_ayudas_db_btn_actualizar').style.display='none';
	getObj('beneficiario_ayudas_db_btn_guardar').style.display='';
	clearForm('form_beneficiario_ayudas');
	getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
	getObj('rrhh_ayudasocioeconomica_db_fecha').value='<?=date('d/m/Y')?>';
});

$("#beneficiario_ayudas_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_beneficiario_ayudas').jVal())
	{
		$.ajax (
		{
			url: "modulos/rrhh/beneficiario_ayudas/db/sql.actualizar.php",
			data:dataForm('form_beneficiario_ayudas'),
			type:'POST',
			cache: false,
			success: function(html)
			{			
				if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_beneficiario_ayudas.beneficiario_ayudas_db_vista_cedula.value="";
					document.form_beneficiario_ayudas.beneficiario_ayudas_db_vista_cedula.focus();
				}
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('beneficiario_ayudas_db_btn_actualizar').style.display='none';
					getObj('beneficiario_ayudas_db_btn_guardar').style.display='';
					getObj('beneficiario_ayudas_db_btn_eliminar').style.display='none';
					getObj('beneficiario_ayudas_db_btn_actualizar').style.display='none';
					getObj('beneficiario_ayudas_db_btn_guardar').style.display='';
					clearForm('form_beneficiario_ayudas');
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
					getObj('rrhh_ayudasocioeconomica_db_fecha').value='<?=date('d/m/Y')?>';	
				}
				else
				{
					setBarraEstado(html);
				}
			}
				
		});
	}
});

$('#beneficiario_ayudas_db_vista_nombre').alpha({allow:' áéíóúÄÉÍÓÚ'});
$('#beneficiario_ayudas_db_vista_apellido').alpha({allow:' áéíóúÄÉÍÓÚ'});
$('#beneficiario_ayudas_db_vista_cedula').numeric({allow:'_'});

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
$('#rrhh_ayudasocioeconomica_db_monto').numeric({allow:',.'});



var timeoutHnd; 
var flAuto = true;

$("#beneficiario_ayudas_db_vista_cedula").keypress(function(key)
{
		administracion_filtro_cedula_dosearch();													
});

function administracion_filtro_cedula_dosearch()
{
	if(!flAuto) return; 
	// var elem = ev.target||ev.srcElement; 
	if(timeoutHnd) 
	clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(buscar_cedula,600)
}

function buscar_cedula()
{
	$.ajax (
	{
		url: "modulos/rrhh/beneficiario_ayudas/db/sql.buscar.php",
		data:dataForm('form_beneficiario_ayudas'),
		type:'POST',
		cache: false,
		success: function(html)
		{
			if (html=="Existe")
			{
				consulta(jQuery("#beneficiario_ayudas_db_vista_cedula").val());
			}
			else
			{
				setBarraEstado(html);
			}
		}
	});	
}

</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:631px;
	height:19px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:27px;
	z-index:2;
}
#Layer3 {
	position:absolute;
	width:280px;
	height:20px;
	z-index:1;
	left: 130px;
	top: 430px;
}
#Layer4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 339px;
	top: 484px;
}
-->
</style>
<div id="botonera">
	<img id="beneficiario_ayudas_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />	
	<img id="beneficiario_ayudas_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />		
	<img id="beneficiario_ayudas_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="beneficiario_ayudas_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />
    <img id="beneficiario_ayudas_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" enctype="multipart/form-data" name="form_beneficiario_ayudas" id="form_beneficiario_ayudas">
<input type="hidden" name="vista_id_beneficiario_ayudas" id="vista_id_beneficiario_ayudas" />
<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Beneficiario Ayudas </th>
		</tr>
		<tr>
			<th>C&eacute;dula:</th>
			<td colspan="2">
	    <select name="beneficiario_ayudas_db_vista_nacionalidad" id="beneficiario_ayudas_db_vista_nacionalidad" style="width:50px; min-width:50px;">
				  <option>V-</option>
          </select>	    
		  <input name="beneficiario_ayudas_db_vista_cedula" type="text" id="beneficiario_ayudas_db_vista_cedula"  size="8" maxlength="9" width="150px" 
					message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Nombre: </th><td width="1%"><input name="beneficiario_ayudas_db_vista_nombre" type="text" id="beneficiario_ayudas_db_vista_nombre" value=""  size="35" maxlength="40" message="Escriba un Nombre" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Apellido: </th><td><input name="beneficiario_ayudas_db_vista_apellido" type="text" id="beneficiario_ayudas_db_vista_apellido" value=""  size="35" maxlength="40" message="Escriba un Apellido"
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Unidad: </th><td><input name="beneficiario_ayudas_db_vista_unidad" type="text" id="beneficiario_ayudas_db_vista_unidad" value=""  size="35" maxlength="40" message="Escriba una Unidad"
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'unidad Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['unidad: '+$(this).val()]}"></td>
		</tr>        
		<tr>
		  <th>Fecha:</th>
	      <td><label>
		<input readonly="true" type="text" name="rrhh_ayudasocioeconomica_db_fecha" id="rrhh_ayudasocioeconomica_db_fecha" size="10" value="<?php echo date("d/m/Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_desde_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "rrhh_ayudasocioeconomica_db_fecha",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
			</label>
	  </td>
	</tr>	
		<tr>	
		<th>Monto de la Ayuda:		</th>
		<td>
		<input align="right"  name="rrhh_ayudasocioeconomica_db_monto" type="text" id="rrhh_ayudasocioeconomica_db_monto"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00" size="16" maxlength="16" style="text-align:right" message="Ingrese el Valor del Monto de la Ayuda" jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['Monto: '+$(this).val()]}" />
	</tr>            	 
         <tr>
		<th>Concepto de la Ayuda:</th>
		<td><textarea  name="rrhh_ayudasocioeconomica_db_concepto" cols="60" id="rrhh_ayudasocioeconomica_db_concepto" message="Introduzca el Concepto o Motivo de la Ayuda Econ&oacute;mica."></textarea>		</td>
		</tr>
		<tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
</form>