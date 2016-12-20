<?php
session_start();
?>

<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>


var dialog;

$("#form_rrhh_ayudasocioeconomica_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_rp_rrhh_ayudasocioeconomica');	
	getObj('rrhh_ayudasocioeconomica_db_fecha').value ='<?php echo date("d/m/Y")?>';
	getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
	getObj('form_rrhh_ayudasocioeconomica_rp_btn_cancelar').style.display='';
	getObj('form_rrhh_ayudasocioeconomica_rp_btn_eliminar').style.display='none';
	getObj('form_rrhh_ayudasocioeconomica_rp_btn_actualizar').style.display='none';
	getObj('form_rrhh_ayudasocioeconomica_rp_btn_guardar').style.display='';	
});

$("#form_rrhh_ayudasocioeconomica_rp_btn_guardar").click(function() {
	if($('#form_rp_rrhh_ayudasocioeconomica').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/rrhh/ayudassocioeconomicas/db/sql.registrar.php",
			data:dataForm('form_rp_rrhh_ayudasocioeconomica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_rp_rrhh_ayudasocioeconomica');
					getObj('rrhh_ayudasocioeconomica_db_fecha').value ='<?php echo date("d/m/Y")?>';
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});

$("#form_rrhh_ayudasocioeconomica_rp_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/rrhh/ayudassocioeconomicas/db/sql.eliminar.php",
			data:dataForm('form_rp_rrhh_ayudasocioeconomica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_cancelar').style.display='';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_eliminar').style.display='none';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_actualizar').style.display='none';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_guardar').style.display='';
					clearForm('form_rp_rrhh_ayudasocioeconomica');
					getObj('rrhh_ayudasocioeconomica_db_fecha').value ='<?php echo date("d/m/Y")?>';
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});


$("#form_rrhh_ayudasocioeconomica_rp_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
			url:"modulos/rrhh/ayudassocioeconomicas/db/vista_grid.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Programas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/ayudassocioeconomicas/db/sql_grid.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#programa-consultas-busq_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#programa-consultas-busq_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/ayudassocioeconomicas/db/sql_grid.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
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
								url:'modulos/rrhh/ayudassocioeconomicas/db/sql_grid.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Fecha','Concepto','Concepto','Monto','Id Usuario','Nombres y Apellidos'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fecha',index:'fecha', width:40,sortable:false,resizable:false},
									{name:'resumen_concepto',index:'resumen_concepto', width:230,sortable:false,resizable:false},
									{name:'concepto',index:'concepto', width:230,sortable:false,resizable:false,hidden:true},
									{name:'monto',index:'monto', width:60,sortable:false,resizable:false},
									{name:'id_usuario',index:'id_usuario', width:300,sortable:false,resizable:false,hidden:true},
									{name:'usuario',index:'usuario', width:200,sortable:false,resizable:false}

									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('rrhh_ayudasocioeconomica_db_id').value = ret.id;
									getObj('rrhh_ayudasocioeconomica_usuarios_rp_id_usuario').value = ret.id_usuario;
									getObj('rrhh_ayudasocioeconomica_usuarios_rp_usuario').value = ret.usuario;
									getObj('rrhh_ayudasocioeconomica_db_fecha').value = ret.fecha;
									getObj('rrhh_ayudasocioeconomica_db_concepto').value = ret.concepto;
									getObj('rrhh_ayudasocioeconomica_db_monto').value = ret.monto;
									getObj('form_rrhh_ayudasocioeconomica_rp_btn_cancelar').style.display='';
									getObj('form_rrhh_ayudasocioeconomica_rp_btn_eliminar').style.display='';
									getObj('form_rrhh_ayudasocioeconomica_rp_btn_actualizar').style.display='';
									getObj('form_rrhh_ayudasocioeconomica_rp_btn_guardar').style.display='none';												
									dialog.hideAndUnload();
								},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#programa-consultas-busq_nombre").focus();
								$('#programa-consultas-busq_nombre').alpha({allow:' '});
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

$("#form_rrhh_ayudasocioeconomica_rp_btn_actualizar").click(function() {
	if($('#form_rp_rrhh_ayudasocioeconomica').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/rrhh/ayudassocioeconomicas/db/sql.actualizar.php",
			data:dataForm('form_rp_rrhh_ayudasocioeconomica'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_cancelar').style.display='';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_eliminar').style.display='none';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_actualizar').style.display='none';
					getObj('form_rrhh_ayudasocioeconomica_rp_btn_guardar').style.display='';	
					clearForm('form_rp_rrhh_ayudasocioeconomica');
					getObj('rrhh_ayudasocioeconomica_db_fecha').value ='<?=date("d/m/Y")?>';
					getObj('rrhh_ayudasocioeconomica_db_monto').value='0,00';
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#rrhh_ayudasocioeconomica_usuarios_rp_btn_consultar_usuario").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		
		$.ajax ({
		    url:"modulos/rrhh/beneficiario_ayudas/db/grid_filtro_usuario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
				{
					
					dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
					dialog_reload=function gridReload(){ 
						var busq_nombre_usu= jQuery("#administracion_busq_nombre_usuario").val();
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/beneficiario_ayudas/db/sql_grid_beneficiario_ayudas.php?busq_nombre_usu="+busq_nombre_usu,page:1}).trigger("reloadGrid"); 
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
						var busq_nombre_usu= jQuery("#beneficiario_busq_nombre_usuario").val(); 
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/beneficiario_ayudas/db/sql_grid_beneficiario_ayudas.php?busq_nombre_usu="+busq_nombre_usu,page:1}).trigger("reloadGrid"); 		
					}
			
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
					colNames:['ID','Cedula','Nombre','Apellido','Unidad','observacion'],
					colModel:[
						{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
						{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
						{name:'nombre',index:'nombre', width:150,sortable:false,resizable:false},
						{name:'apellido',index:'apellido', width:150,sortable:false,resizable:false},
						{name:'unidad',index:'unidad', width:150,sortable:false,resizable:false},
						{name:'observacion',index:'observacion', width:250,sortable:false,resizable:false,hidden:true}
					],
					pager: $('#pager_grid_'+nd),
					rowNum:20,
					rowList:[20,50,100],
					imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
					onSelectRow: function(id){
					var ret = jQuery("#list_grid_"+nd).getRowData(id);
						getObj('rrhh_ayudasocioeconomica_usuarios_rp_id_usuario').value = ret.id;

						getObj('rrhh_ayudasocioeconomica_usuarios_rp_usuario').value = ret.nombre+' '+ret.apellido;
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
					sortname: 'nombre',
					viewrecords: true,
					sortorder: "asc"
				});
			}
	
});


//-------------------------------------------------------------------

/*-------------------   Inicio Validaciones  ---------------------------*/

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

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	
	$('#rrhh_ayudasocioeconomica_db_monto').numeric({allow:',.'});
	
	
/*-------------------   Fin Validaciones  ---------------------------*/
</script>

<div id="botonera">
	<img id="form_rrhh_ayudasocioeconomica_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="form_rrhh_ayudasocioeconomica_rp_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
     <img id="form_rrhh_ayudasocioeconomica_rp_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>		    
    <img id="form_rrhh_ayudasocioeconomica_rp_btn_actualizar" class="btn_guardar"src="imagenes/null.gif" style="display:none"/>		    
	<img id="form_rrhh_ayudasocioeconomica_rp_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_rp_rrhh_ayudasocioeconomica" id="form_rp_rrhh_ayudasocioeconomica">
  <table class="cuerpo_formulario">
    <tr>
      <th class="titulo_frame" colspan="2"> <img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Asignaci&oacute;n de Ayuda Econ&oacute;mica</th>
    </tr>  
    <tr>
      <th>Usuario:</th>
      <td><ul class="input_con_emergente">
          <li>
            <input name="rrhh_ayudasocioeconomica_usuarios_rp_usuario" type="text" id="rrhh_ayudasocioeconomica_usuarios_rp_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" readonly 
			 />
            <input type="hidden" id="rrhh_ayudasocioeconomica_usuarios_rp_id_usuario" name="rrhh_ayudasocioeconomica_usuarios_rp_id_usuario"/>
            <input type="hidden" id="rrhh_ayudasocioeconomica_db_id" name="rrhh_ayudasocioeconomica_db_id"/>           
          </li>
        <li id="rrhh_ayudasocioeconomica_usuarios_rp_btn_consultar_usuario" class="btn_consulta_emergente"></li>
      </ul></td>
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
		<th>Concepto de la Ayuda:</th>
		<td><textarea  name="rrhh_ayudasocioeconomica_db_concepto" cols="60" id="rrhh_ayudasocioeconomica_db_concepto" message="Introduzca el Concepto o Motivo de la Ayuda Econ&oacute;mica."></textarea>		</td>
		</tr>
		<tr>	
		<th>Monto de la Ayuda:		</th>
		<td>
		<input align="right"  name="rrhh_ayudasocioeconomica_db_monto" type="text" id="rrhh_ayudasocioeconomica_db_monto"  onKeyPress="reais(this,event)" onKeyDown="backspace(this,event)" value="0,00" size="16" maxlength="16" style="text-align:right" message="Ingrese el Valor del monto de la Ayuda" jValKey="{valid:/[0123456789,]/, cFunc:'alert', cArgs:['Año: '+$(this).val()]}" />
	</tr>    	  
    <tr>
      <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
</form>
