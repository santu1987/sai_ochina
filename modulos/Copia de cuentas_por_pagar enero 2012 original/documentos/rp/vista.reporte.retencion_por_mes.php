<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
	$sql="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (id_organismo = ".$_SESSION["id_organismo"].") ORDER BY tipo_documento_cxp.nombre";
	$rs_tipos_doc =& $conn->Execute($sql);
	while (!$rs_tipos_doc->EOF)
	{
		$opt_tipos_doc.="<option value='".$rs_tipos_doc->fields("id_tipo_documento")."' >".$rs_tipos_doc->fields("nombre")."</option>";
		$rs_tipos_doc->MoveNext();
	}

if(date("d")=="31")
{
	$dia=date("d")-1;
	$mes=date("m")-1;
	$ayo=date("Y");
}
	else
	{
		$dia=date("d");	
	}
if(date("m")=="1")
{
	$mes="12";
	$ayo=date("Y")-1;
}
else
	{
	$mes=date("m")-1;
	$ayo=date("Y");
	}
$fecha=date("d/m/Y",mktime(0,0,0,$mes,$dia,$ayo));
?>



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
<script type='text/javascript'>

function limpiar_reporte_doc_cxp(){
	setBarraEstado("");
	clearForm('form_cuentas_por_pagar_retenciones_documentos');
	getObj('cuentas_por_pagar_retenciones_rp_fecha_desde').value="<?=  $fecha; ?>";
	getObj('cuentas_por_pagar_retenciones_rp_fecha_hasta').value="<?=  date("d/m/Y"); ?>";
	getObj('cuentas_por_pagar_retenciones_tipo_documento').value='0';
	getObj('cuentas_por_pagar_retenciones_op_oculto').value='1';
	getObj('cuentas_por_pagar_db_radio1').checked="checked";
	getObj('tr_empleado_cxp_rp').style.display='none';
	getObj('tr_proveedor_cxp_rp').style.display='';
									
}
$("#cuentas_por_pagar_retenciones_db_btn_cancelar").click(function() {
	setBarraEstado("");
	
	//getObj('cuentas_por_pagar_db_compromiso_n').disabled='';
limpiar_reporte_doc_cxp();
});	
//----------------------------------------------------------------------------------------------------

$("#cuentas_por_pagar_db_orden_btn_imprimir").click(function() {
if(($('#').jVal()))
	{
			url="pdf.php?p=modulos/cuentas_por_pagar/documentos/rp/vista.lst.retencion_por_mes.php¿desde="+getObj('cuentas_por_pagar_retenciones_rp_fecha_desde').value+"@hasta="+getObj('cuentas_por_pagar_retenciones_rp_fecha_hasta').value+"@proveedor="+getObj('cuentas_por_pagar_retenciones_proveedor_id').value+"@ret_tipo="+getObj('cuentas_por_pagar_retenciones_btn_ret').value; 
			//setBarraEstado(url);
			openTab("Retenciones",url);
	}
});
	

$("#cuentas_por_pagar_retenciones_btn_consultar_proveedor").click(function() {
		/*var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/cuentas_por_pagar/documentos/db/grid_pagar.php", { },
						function(data)
                        {					
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/cuentas_por_pagar/documentos/rp/grid_pagar.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_proveedor= jQuery("#cuentas_por_pagar_rp_proveedor_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#cuentas_por_pagar_rp_proveedor_consulta").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_doc_dosearch();
					});
				$("#cuentas_por_pagar_rp_codigo_proveedor_consulta").keypress(function(key)
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
							var busq_proveedor= jQuery("#cuentas_por_pagar_rp_proveedor_consulta").val();
							 var busq_cod= jQuery("#cuentas_por_pagar_rp_codigo_proveedor_consulta").val(); 
							
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/cuentas_por_pagar/documentos/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor+"&busq_cod="+busq_cod,page:1}).trigger("reloadGrid"); 
							url="modulos/cuentas_por_pagar/documentos/rp/cmb.sql.proveedor.php?busq_proveedor="+busq_proveedor+"&busq_cod="+busq_cod;
						//	alert(url);
						}

			}
		});
///
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.proveedor.php?nd='+nd,
								//url:'modulos/cuentas_por_pagar/cheques/pr/cmb.sql.proveedor.php?nd='+nd,
							
							
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
									getObj('cuentas_por_pagar_retenciones_proveedor_id').value = ret.id_proveedor;
									getObj('cuentas_por_pagar_retenciones_proveedor_codigo').value = ret.codigo;
									getObj('cuentas_por_pagar_retenciones_proveedor_nombre').value = ret.nombre;
									rif=ret.rif;
									rif2 = rif.split("-");
									getObj('cuentas_por_pagar_retenciones_proveedor_rif').value=rif2[0];
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
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#cuentas_por_pagar_retenciones_empleado_codigo').numeric({});
$('#cuentas_por_pagar_retenciones_proveedor_codigo').numeric({});

$("#cuentas_por_pagar_retenciones_radio1").click(function(){
		getObj('cuentas_por_pagar_retenciones_op_oculto').value="1"
	});
$("#cuentas_por_pagar_retenciones_radio2").click(function(){
		getObj('cuentas_por_pagar_retenciones_op_oculto').value="2"
	});

//$('#cuentas_por_pagar_cheque_manuals_db_concepto').alpha({allow:' áéíóúÄÉÍÓÚ'});
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
 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//consultas automaticas
function consulta_automatica_proveedor_rp_cxp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_proveedor_codigo_ret.php",
            data:dataForm('form_cuentas_por_pagar_retenciones_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);	
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('cuentas_por_pagar_retenciones_proveedor_nombre').value = recordset[1];
				getObj('cuentas_por_pagar_retenciones_proveedor_id').value=recordset[0];
				rif=recordset[2];
				rif2 = rif.split("-");
								getObj('cuentas_por_pagar_retenciones_proveedor_rif').value=rif[0];
								
			}
				else
			 {  
			   	getObj('cuentas_por_pagar_retenciones_proveedor_nombre').value ="";
				getObj('cuentas_por_pagar_retenciones_proveedor_id').value="";
				getObj('cuentas_por_pagar_retenciones_proveedor_rif').value="";
				//getObj('').disabled="disdabled";
				}
				
			 }
		});	 	 
}

//-------------------------------------------------------------------------------------------------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("#cuentas_por_pagar_retenciones_btn_consultar_beneficiario").click(function() {

		var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/cheques/pr/grid_cheques.php", { },
						function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Proveedores', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/cuentas_por_pagar/documentos/db/cmb.sql.beneficiario.php?nd='+nd,
								datatype: "json",
								colNames:['Código','Beneficiario'],
								colModel:[
									{name:'rif',index:'rif', width:50,sortable:false,resizable:false},
									{name:'beneficiario',index:'beneficiario', width:100,sortable:false,resizable:false}
									],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('cuentas_por_pagar_retenciones_empleado_codigo').value = ret.rif;
									getObj('cuentas_por_pagar_retenciones_empleado_nombre').value = ret.beneficiario;
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
//---------------------------------------------------------------------------------------------------------------------------------------------------------------

//consultas automaticas
function consulta_automatica_benef_rp()
{
	$.ajax({
			url:"modulos/cuentas_por_pagar/documentos/rp/sql_grid_beneficiario_codigo_cxp.php",
			data:dataForm('form_cuentas_por_pagar_retenciones_documentos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				
				if(recordset)
				{
				recordset = recordset.split("*");
				//getObj('cuentas_por_pagar_db_empleado_codigo').value = recordset[1];
				getObj('cuentas_por_pagar_retenciones_empleado_nombre').value=recordset[1];
					}
				else

			 {  
			   	//getObj('cuentas_por_pagar_db_empleado_codigo').value ="";
				getObj('cuentas_por_pagar_retenciones_empleado_nombre').value="";
				}
				
			 }
		});	 	 
}
$('#cuentas_por_pagar_retenciones_empleado_codigo').change(consulta_automatica_benef_rp)
/////////////////////////////////////////////
</script>
   <div id="botonera"><img id="cuentas_por_pagar_retenciones_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="cuentas_por_pagar_db_orden_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />	</div>
	
	</div>
<form method="post" id="form_cuentas_por_pagar_retenciones_documentos" name="form_cuentas_por_pagar_db_docuemntos">
<input type="hidden"  id="cuentas_por_pagar_vista_documentos" name="cuentas_por_pagar_vista_documentos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Retenciones </th>
	</tr>
	<th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_retenciones_rp_fecha_desde" id="cuentas_por_pagar_retenciones_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cuentas_por_pagar_retenciones_rp_fecha_desde_oculto" id="cuentas_por_pagar_retenciones_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="cuentas_por_pagar_retenciones_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_retenciones_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_retenciones_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cuentas_por_pagar_retenciones_rp_fecha_desde").value.MMDDAAAA());
								f2=new Date( getObj("cuentas_por_pagar_retenciones_rp_fecha_hasta").value.MMDDAAAA());
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("cuentas_por_pagar_cheques_anulados_usuarios_rp_fecha_desde").value =getObj("cuentas_por_pagar_retenciones_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
		  </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="cuentas_por_pagar_retenciones_rp_fecha_hasta" id="cuentas_por_pagar_retenciones_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="cuentas_por_pagar_retenciones_rp_fecha_hasta_oculto" id="cuentas_por_pagar_retenciones_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="cuentas_por_pagar_retenciones_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "cuentas_por_pagar_retenciones_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "cuentas_por_pagar_retenciones_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("cuentas_por_pagar_retenciones_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("cuentas_por_pagar_retenciones_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("cuentas_por_pagar_retenciones_rp_fecha_hasta").value =getObj("cuentas_por_pagar_retenciones_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
      
   <tr id="tr_proveedor_cxp_rp">
		<th>Proveedor:</th>
		  <td>
		  <ul class="input_con_emergente">
				<li>
				  <input name="cuentas_por_pagar_retenciones_proveedor_codigo" type="text" id="cuentas_por_pagar_retenciones_proveedor_codigo"  maxlength="4"
				onchange="consulta_automatica_proveedor_rp_cxp()" 
				message="Introduzca un Codigo para el proveedor."  size="5"
						jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Código Invalido', styleType:'cover'}"
						jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}"/>
				<input name="cuentas_por_pagar_retenciones_proveedor_nombre" type="text" id="cuentas_por_pagar_retenciones_proveedor_nombre" size="45" maxlength="60" readonly
				message="Introduzca el nombre del Proveedor."/>
				<input type="hidden" name="cuentas_por_pagar_retenciones_proveedor_id" id="cuentas_por_pagar_retenciones_proveedor_id" readonly />
				<input type="hidden" name="cuentas_por_pagar_retenciones_proveedor_rif" id="cuentas_por_pagar_retenciones_proveedor_rif" readonly />
				</li> 
					<li id="cuentas_por_pagar_retenciones_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
	  </ul>	  </td>		
	</tr>
	<tr>
	<th>Retenci&oacute;n</th>
	<td><select name="cuentas_por_pagar_retenciones_btn_ret" id="cuentas_por_pagar_retenciones_btn_ret">
		<option id="0">IVA</option>
		<option id="1">ISLR</option>
		<option id="2">OTRAS</option>
		</select>
	</td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="cuentas_por_pagar_retenciones_id" type="hidden" id="cuentas_por_pagar_retenciones_id"  />
</form>

   