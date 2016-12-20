<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>

<script>
var dialog;
$("#sareta_cambio_moneda_db_btn_guardar").click(function() {
	
	if(($('#form_db_cambio_moneda').jVal()))
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax ({
			url: "modulos/sareta/cambio_moneda/db/sql.registrar.php",
			data:dataForm('form_db_cambio_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_cambio_moneda');
					getObj('cambio_moneda_db_fecha_cambio').value="<?= date ('d/m/Y') ?>";
					getObj('sareta_cambio_moneda_db_valor').value ="0,00";
				}
				else if(html=="falta_delegacion")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />Seleccione una Delegaci&oacute;n</p></div>",true,true);
				}
				else if(html=="Existe")
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
//************************************************************************
$("#sareta_cambio_moneda_db_btn_consultar_moneda").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/cambio_moneda/db/grid_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Monedas',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/cambio_moneda/db/sql_grid_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Operaci&oacute;n','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'operacion',index:'operacion', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id_moneda').value = ret.id;
									getObj('sareta_cambio_moneda_db_nombre').value = ret.nombre;
									dialog.hideAndUnload();
									$('#form_db_moneda').jVal();
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


//************************************************************************
$("#sareta_cambio_moneda_db_btn_consultar").click(function() {
		var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/cambio_moneda/db/grid_cambio_moneda.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Cambio de Moneda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/cambio_moneda/db/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
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
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/cambio_moneda/db/sql_grid_cambio_moneda.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/cambio_moneda/db/sql_grid_cambio_moneda.php?nombre="+busq_nombre;
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
								url:'modulos/sareta/cambio_moneda/db/sql_grid_cambio_moneda.php?nd='+nd,
								datatype: "json",
								colNames:['id','id_moneda','Moneda','moneda','Fecha de Cambio','Valor','Comentario','Com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
								{name:'id_moneda',index:'id_moneda', width:220,sortable:false,resizable:false,hidden:true},
								{name:'moneda1',index:'moneda1', width:220,sortable:false,resizable:false},
								{name:'moneda',index:'moneda', width:220,sortable:false,resizable:false,hidden:true},
								{name:'fecha',index:'fecha', width:220,sortable:false,resizable:false},
								{name:'valor',index:'valor', width:220,sortable:false,resizable:false},
								{name:'obs1',index:'obs1', width:220,sortable:false,resizable:false},
								{name:'obs',index:'obs', width:220,sortable:false,resizable:false,hidden:true}
								
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('id').value = ret.id;
									getObj('id_moneda').value = ret.id_moneda;
									getObj('sareta_cambio_moneda_db_nombre').value = ret.moneda;
									getObj('sareta_cambio_moneda_db_valor').value = ret.valor;
									getObj('cambio_moneda_db_fecha_cambio').value = ret.fecha;
									getObj('cambio_moneda_db_comentario').value = ret.obs;
									getObj('sareta_cambio_moneda_db_btn_cancelar').style.display='';
									getObj('sareta_cambio_moneda_db_btn_eliminar').style.display='';
									getObj('sareta_cambio_moneda_db_btn_actualizar').style.display='';
									getObj('sareta_cambio_moneda_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
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
	//-------------------------------------------------------------------------------------------------------------------------------
$("#sareta_cambio_moneda_db_btn_eliminar").click(function() {
  if (getObj('id').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/cambio_moneda/db/sql.eliminar.php",
			data:dataForm('form_db_cambio_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_cambio_moneda_db_btn_cancelar').style.display='';
					getObj('sareta_cambio_moneda_db_btn_eliminar').style.display='none';
					getObj('sareta_cambio_moneda_db_btn_actualizar').style.display='none';
					getObj('sareta_cambio_moneda_db_btn_guardar').style.display='';
					clearForm('form_db_cambio_moneda');
					getObj('cambio_moneda_db_fecha_cambio').value="<?= date ('d/m/Y') ?>";
					getObj('sareta_cambio_moneda_db_valor').value ="0,00";
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion Con el Cambio de esta Moneda</p></div>",true,true); 
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


$("#sareta_cambio_moneda_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_cambio_moneda').jVal())
	{
		
		$.ajax (
		{
			url: "modulos/sareta/cambio_moneda/db/sql.actualizar.php",
			data:dataForm('form_db_cambio_moneda'),
			type:'POST',
			cache: false,
			success: function(html)
			{	//alert(html);		
				if (html=="No Actualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}else if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					getObj('sareta_cambio_moneda_db_btn_cancelar').style.display='';
					getObj('sareta_cambio_moneda_db_btn_eliminar').style.display='none';
					getObj('sareta_cambio_moneda_db_btn_actualizar').style.display='none';
					getObj('sareta_cambio_moneda_db_btn_guardar').style.display='';
					clearForm('form_db_cambio_moneda');
					getObj('cambio_moneda_db_fecha_cambio').value="<?= date ('d/m/Y') ?>";
					getObj('sareta_cambio_moneda_db_valor').value ="0,00";
				}
				else if(html=="Existe")
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

$("#sareta_cambio_moneda_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_cambio_moneda_db_btn_cancelar').style.display='';
	getObj('sareta_cambio_moneda_db_btn_eliminar').style.display='none';
	getObj('sareta_cambio_moneda_db_btn_actualizar').style.display='none';
	getObj('sareta_cambio_moneda_db_btn_guardar').style.display='';
	clearForm('form_db_cambio_moneda');
	getObj('cambio_moneda_db_fecha_cambio').value="<?= date ('d/m/Y') ?>";
	getObj('sareta_cambio_moneda_db_valor').value ="0,00";
				
	
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
</script>


<div id="botonera">
	<img id="sareta_cambio_moneda_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
  <img id="sareta_cambio_moneda_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_cambio_moneda_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="sareta_cambio_moneda_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="sareta_cambio_moneda_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif" onclick="guardar()" /></div>


<form name="form_db_cambio_moneda" id="form_db_cambio_moneda">
<input type="hidden" name="id" id="id">
	<table class="cuerpo_formulario">
        <tr>
            <th colspan="2" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Cambio de Moneda</th>
        </tr>
        </tr>	
             <th>Moneda:		</th>	
	       <td><ul class="input_con_emergente">
              <li>
		<p>
        <input type="hidden" name="id_moneda" id="id_moneda" />
		  <input name="sareta_cambio_moneda_db_nombre" type="text" class="style4" id="sareta_cambio_moneda_db_nombre"  value="" size="60" maxlength="60"  readonly
						message="Introduzca una Moneda para su Cambio." 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Moneda Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Moneda: '+$(this).val()]}" />
         
                        
        <li id="sareta_cambio_moneda_db_btn_consultar_moneda" class="btn_consulta_emergente"></li>
		    </ul></td>
	</tr>
    <tr>
	<th>Cambio: </th>	
	<td><input  name="sareta_cambio_moneda_db_valor" type="text" id="sareta_cambio_moneda_db_valor"  size="8" onkeypress="reais(this,event)" onkeydown="backspace(this,event);" value="0,00" style="text-align:right" message="Introduzca un valor para la Moneda" 
        jval="{valid:/^[0-9,.]{1,12}$/, message:'Valor de Moneda Invalido', styleType:'cover'}"
		valkey="{valid:/[0-9,.]/, cFunc:'alert',cArgs:['Valor de Moneda: '+$(this).val()]}"/></td>
	</tr>	
        <tr>
			<th>Fecha Cambio:	</th>
			<td><input name="cambio_moneda_db_fecha_cambio" type="text" id="cambio_moneda_db_fecha_cambio" value="<?=date("d/m/Y")?>" size="9" maxlength="10" readonly="true" message="Introduzca el Fecha de Cambio">
		  <button type="reset" id="cambio_moneda_db_fecha_cambio_boton">...</button>
				
				<script type="text/javascript">
					Calendar.setup({
						inputField     :    "cambio_moneda_db_fecha_cambio",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cambio_moneda_db_fecha_cambio_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
			</td>
		</tr>
        <tr>
			<th>Comentario :			</th>
			<td>	<textarea name="cambio_moneda_db_comentario" cols="60" id="cambio_moneda_db_comentario" message="Introduzca un Comentario"></textarea></td>
		</tr>
        <tr>
            <td colspan="2" class="bottom_frame">&nbsp;
        <tr>			
       </table>
        <span class="bottom_frame"><span class="titulo_frame">
        </span></span>
</form>  