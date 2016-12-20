<?php
session_start();
$fecha=date("d/m/Y");
?>
<link rel="stylesheet" type="text/ccs" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.ccs" title="Aqua"/>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />

<script type="text/javascript">
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
/*function v_fecha()
{
	//alert("entro");
	var1=esFechaValida(getObj('contabilidad_comp_pr_fecha').value);
	if(var1!=false)
	{
		var2=comprobarSiBisisesto(var1);
	}
	alert(var1);
	alert(var2);
	if((var1==false)||(var2==true))
	{
		//alert("entro");
		getObj('contabilidad_comp_pr_fecha').value="";
	}

}*/
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
 function v_fecha(oTxt){
  fech=new Date(); 
  oTxt=getObj('revint_comp_pr_fecha');
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
</script>	<script type='text/javascript'>


$("#tesoreria_integracion_db_btn_cancelar").click(function() {
	setBarraEstado("");
	clearForm('form_tesoreria_integracion_contable');//
	getObj('revint_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";



});	

$("#tesoreria_db_btn_consultar_integracion_tipo").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuenta Presupuestaria', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/integracion_contable/pr/sql_grid_tipo_comprobante.php?nd='+nd,
								datatype: "json",
								colNames:['id','Codigo', 'Denominacion'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#tesoreria_integracion_contable_tipo').val(ret.codigo);
									$('#tesoreria_integracion_contable_tipo_id').val(ret.id);
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
$("#tesoreria_integracion_db_btn_guardar").click(function (){
if($('#form_tesoreria_integracion_contable').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax(
		{
			url:"modulos/tesoreria/integracion_contable/pr/sql.integrar.php",
			data:dataForm('form_tesoreria_integracion_contable'),
			type:'POST',
			cache: false,
			success:function(html)
			{
			setBarraEstado(html);
			//alert(html);
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_integracion_contable');
					
				}
				else if (html=="registros_integrados")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />NO SE PUDO REALIZAR LA OPERACI&Oacute;N REGISTROS YA INTEGRADOS</p></div>",true,true);
					clearForm('form_tesoreria_integracion_contable');	
	
				}
				else if (html=="NoRegistro")
				{
					clearForm('form_tesoreria_integracion_contable');		
				}
				else if(html=="valor_iva")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />REGISTRE LA CUENTA CONTABLE DEL IVA PARA REALIZAR LA INTEGRACI&Oacute;N</p></div>",true,true);	
				}
					else
				{
					alert(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
//
				}
			//
						    	getObj('revint_comp_pr_fecha').value="<?=  date("d/m/Y"); ?>";

			}
		});
	}


//////////////////////////////////
/////////////////////////////////////
/*$('#tesoreria_banco_db_telefono').numeric({allow:'/-'});
$('#tesoreria_banco_db_codigoarea').numeric({allow:'/-'});
$('#tesoreria_banco_db_fax').numeric({allow:'/-'});
$('#tesoreria_banco_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$("input, select, textarea").bind("focus", function(){
*/	
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
	<img id="tesoreria_integracion_db_btn_cancelar" class="btn_cancelar" src="imagenes/null.gif"/>
	<img id="tesoreria_integracion_db_btn_guardar" src="imagenes/iconos/integrar.png"  style="width:100px height:100px"/>
	<img id="tesoreria_integracion_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif" style="display:none"/>

</div>
<form method="post" id="form_tesoreria_integracion_contable" name="form_tesoreria_integracion_contable">
<input type="hidden" id="tesoreria_integracion_contable_id" name="tesoreria_integracion_contable_id"/>
	
	<table class="cuerpo_formulario">	
		<tr>
				<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmidel"/>Integraci&oacute;n Contable</th>		
		</tr>
		<tr style="display:none">
			<th>A&ntilde;o:</th>
			<td>
					<select  name="tesoreria_integracion_contable_ano" id="tesoreria_integracion_contable_ano">
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
			</td>
		</tr>
		<tr>		
		<th>Fecha:</th>
				  <td><label>
				  <input   alt="date" type="text" name="revint_comp_pr_fecha" id="revint_comp_pr_fecha" size="7"  onchange="v_fecha();" onblur="v_fecha();"  value="<? echo ($fecha);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" readonly="readonly"
					/>
				  
				  <button type="reset" id="revint_comp_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "revint_comp_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "revint_comp_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("revint_comp_pr_fecha").value.MMDDAAAA() );
										//consulta_automatica_impuesto_cxp();
								}
							});
					</script>
					<input type="hidden"  name="revint_comp_pr_fecha_oculto" id="revint_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
                    <input type="hidden" name="compi" id="compi">
				  </label>		        </td>
	</tr>	
		<tr>
			<th>Comentarios:</th>
			<td>
				<textarea id="tesoreria_integracion_contable_comentarios" name="tesoreria_integracion_contable_comentarios" cols="60"/>			</td>
		</tr>	
         <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
	</table>
   </form> 
