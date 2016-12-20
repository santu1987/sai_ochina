<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM sareta.tipo_tasa";
$rs_tasa =& $conn->Execute($sql);

while (!$rs_tasa->EOF) {
	
	$opt_tasa.="<option value='".$rs_tasa->fields("id_tipo_tasa")."' >".$rs_tasa->fields("nombre")."</option>";
$rs_tasa->MoveNext();
}

?>
<script type='text/javascript'>
var dialog;
$("#sareta_ley_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/ley/db/grid_ley.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Leyes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/ley/db/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/ley/db/sql_grid_ley.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/ley/db/sql_grid_ley.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/ley/db/sql_grid_ley.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Articulo','Parágrafo','Descripción','des','Tasa','codigo_tasa','Tarifa','Activo','Tonelaje Inicial','Tonelaje Final','Comentario','con'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'articulo',index:'articulo', width:220,sortable:false,resizable:false},
									{name:'paragrafo',index:'paragrafo', width:220,sortable:false,resizable:false},
									{name:'descripcion',index:'descripcion', width:220,sortable:false,resizable:false},
									{name:'des',index:'des', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tasa',index:'tasa', width:220,sortable:false,resizable:false},
									{name:'codigo_tasa',index:'codigo_tasa', width:220,sortable:false,resizable:false,hidden:true},
									{name:'tarifa',index:'tarifa', width:220,sortable:false,resizable:false},
									{name:'activo',index:'activo', width:220,sortable:false,resizable:false},
									{name:'tonelaje_inicial',index:'tonelaje_inicial', width:220,sortable:false,resizable:false},
									{name:'tonelaje_final',index:'tonelaje_final', width:220,sortable:false,resizable:false},
									{name:'obs',index:'obs', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_ley').value = ret.id;
									getObj('sareta_ley_db_vista_articulo').value = ret.articulo;
									getObj('sareta_ley_db_vista_paragrafo').value = ret.paragrafo;
									getObj('sareta_ley_db_vista_descripcion').value = ret.des;
									//getObj('sareta_ley_db_vista_codigo_tasa').selectedIndex =ret.tasa;
									getObj('sareta_ley_db_vista_codigo_tasa').value =ret.codigo_tasa;
									getObj('sareta_ley_db_vista_tarifa').value = ret.tarifa.replace('.',',');
									if(ret.activo=="Si"){
									getObj('sareta_ley_db_vista_activo').selectedIndex =0;
									}else{getObj('sareta_ley_db_vista_activo').selectedIndex =1;
									}
									getObj('sareta_ley_db_vista_tonelaje_inicial').value = ret.tonelaje_inicial.replace('.',',');
									getObj('sareta_ley_db_vista_tonelaje_final').value = ret.tonelaje_final.replace('.',',');
									getObj('sareta_ley_db_vista_obs').value = ret.com;
									getObj('sareta_ley_db_btn_cancelar').style.display='';
									getObj('sareta_ley_db_btn_actualizar').style.display='';
									getObj('sareta_ley_db_btn_eliminar').style.display='';
									getObj('sareta_ley_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_ley').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

$("#sareta_ley_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_ley').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/ley/db/sql.actualizar.php",
			data:dataForm('form_db_ley'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_ley_db_btn_eliminar').style.display='none';
						getObj('sareta_ley_db_btn_actualizar').style.display='none';
						getObj('sareta_ley_db_btn_guardar').style.display='';
						clearForm('form_db_ley');
						getObj('sareta_ley_db_vista_tarifa').value ='0,00';
						getObj('sareta_ley_db_vista_activo').selectedIndex =0;
						getObj('sareta_ley_db_vista_tonelaje_inicial').value ='0,00';
						getObj('sareta_ley_db_vista_tonelaje_final').value ='0,00';
						getObj('sareta_ley_db_vista_codigo_tasa').selectedIndex =0;
					});															
				}
				else if (html=="vorlor_inicial_erroneo")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br> El Tonelaje Inicial no puede ser mayor al  Tonelaje Final </p></div>",true,true); 
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_ley_db_btn_guardar").click(function() {
	if($('#form_db_ley').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/ley/db/sql.registrar.php",
			data:dataForm('form_db_ley'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_ley');
						getObj('sareta_ley_db_vista_tarifa').value ='0,00';
						getObj('sareta_ley_db_vista_activo').selectedIndex =0;
						getObj('sareta_ley_db_vista_tonelaje_inicial').value ='0,00';
						getObj('sareta_ley_db_vista_tonelaje_final').value ='0,00';
						getObj('sareta_ley_db_vista_codigo_tasa').selectedIndex =0;
					});					
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if (html=="vorlor_inicial_erroneo")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /><br> El Tonelaje Inicial no puede ser mayor al  Tonelaje Final </p></div>",true,true); 
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#sareta_ley_db_btn_eliminar").click(function() {
  if (getObj('vista_id_ley').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/ley/db/sql.eliminar.php",
			data:dataForm('form_db_ley'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_ley_db_btn_eliminar').style.display='none';
					getObj('sareta_ley_db_btn_actualizar').style.display='none';
					getObj('sareta_ley_db_btn_guardar').style.display='';
					clearForm('form_db_ley');
					getObj('sareta_ley_db_vista_tarifa').value ='0,00';
					getObj('sareta_ley_db_vista_activo').selectedIndex =0;
					getObj('sareta_ley_db_vista_tonelaje_inicial').value ='0,00';
					getObj('sareta_ley_db_vista_tonelaje_final').value ='0,00';
					getObj('sareta_ley_db_vista_codigo_tasa').selectedIndex =0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con esta Ley</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_ley_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_ley_db_btn_cancelar').style.display='';
	getObj('sareta_ley_db_btn_eliminar').style.display='none';
	getObj('sareta_ley_db_btn_actualizar').style.display='none';
	getObj('sareta_ley_db_btn_guardar').style.display='';
	clearForm('form_db_ley');
	getObj('sareta_ley_db_vista_tarifa').value ='0,00';
	getObj('sareta_ley_db_vista_activo').selectedIndex =0;
	getObj('sareta_ley_db_vista_tonelaje_inicial').value ='0,00';
	getObj('sareta_ley_db_vista_tonelaje_final').value ='0,00';
	getObj('sareta_ley_db_vista_codigo_tasa').selectedIndex =0;
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
	
$('#sareta_ley_db_vista_articulo').numeric({allow:' 0123456789'});
$('#sareta_ley_db_vista_paragrafo').numeric({allow:' 0123456789'});

</script>


<div id="botonera">
	<img id="sareta_ley_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_ley_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_ley_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_ley_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_ley_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_ley" name="form_db_ley">
<input type="hidden" name="vista_id_ley" id="vista_id_ley" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Ley </th>
	</tr>
	<tr>
	<th>Articulo:		</th>	
	<td>
		<input name="sareta_ley_db_vista_articulo" type="text" id="sareta_ley_db_vista_articulo"   value="" size="10" maxlength="4"  
						message="Introduzca un Articulo para la Ley. Ejem: ''175'' " 
						jVal="{valid:/^[0-9]{1,4}$/, message:'Articulo Invalido', styleType:'cover'}"
						jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Articulo: '+$(this).val()]}" />
		</td>
	</tr>
    <tr>
	<th>Parágrafo:		</th>	
	<td>
		<input name="sareta_ley_db_vista_paragrafo" type="text" id="sareta_ley_db_vista_paragrafo"   value="" size="10" maxlength="4"
						message="Introduzca un Parágrafo para la Ley. Ejem: ''175'' " 
						jVal="{valid:/^[0-9]{1,4}$/, message:'Articulo Invalido', styleType:'cover'}"
						jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Articulo: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
	<tr>
		<th>Descripci&oacute;n:</th>			<td ><textarea name="sareta_ley_db_vista_descripcion" cols="60" id="sareta_ley_db_vista_descripcion"  message="Introduzca una Descripcion. Ejem: ''Esta Ley es...'' "></textarea></td>
	</tr>
    <tr>
	<th>Código Tasa: </th>	
    <td>
   		<select name="sareta_ley_db_vista_codigo_tasa" id="sareta_ley_db_vista_codigo_tasa" >
   		 
        <?=$opt_tasa ?>
        </select>
	  </td>
	</tr>
    <tr>
	<th>Tarifa: </th>	
	<td>
        <input  name="sareta_ley_db_vista_tarifa" type="text" id="sareta_ley_db_vista_tarifa"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca un valor para la Tarifa" 
        jval="{valid:/^[0-9,.]{1,12}$/, message:'Tarifa Invalido', styleType:'cover'}"
		valkey="{valid:/[0-9,.]/, cFunc:'alert',cArgs:['Tarifa: '+$(this).val()]}"/>
		
        <strong>Activo:</strong><select name="sareta_ley_db_vista_activo" id="sareta_ley_db_vista_activo">
  <option value="true" >Si</option>
  <option value="false">No</option>
</select>
		</td>
	</tr>	
    <tr>
	<th>Tonelaje Inicial: </th>	
	<td> <input  name="sareta_ley_db_vista_tonelaje_inicial" type="text" id="sareta_ley_db_vista_tonelaje_inicial"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca un Valor 	para el Tonelaje Inicial" 
    		jval="{valid:/^[0-9,.]{1,12}$/, message:'Tonelaje Inicial Invalido', styleType:'cover'}"
			valkey="{valid:/[0-9,.]/, cFunc:'alert',cArgs:['Tonelaje Inicial: '+$(this).val()]}"/>
		
		</td>
	</tr>
    <tr>
	<th>Tonelaje Final: </th>	
	<td> <input  name="sareta_ley_db_vista_tonelaje_final" type="text" id="sareta_ley_db_vista_tonelaje_final"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca un Valor para el Tonelaje Final" 
    		jval="{valid:/^[0-9,.]{1,12}$/, message:'Tonelaje Final Invalido', styleType:'cover'}"
			valkey="{valid:/[0-9,.]/, cFunc:'alert',cArgs:['Tonelaje Final: '+$(this).val()]}"/>
	</td>
	</tr>
    <tr>
			<th>Observaci&oacute;n :			</th>
			<td>	<textarea name="sareta_ley_db_vista_obs" cols="60" id="sareta_ley_db_vista_obs" message="Introduzca un Observación"></textarea></td>
	</tr>	
 
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>