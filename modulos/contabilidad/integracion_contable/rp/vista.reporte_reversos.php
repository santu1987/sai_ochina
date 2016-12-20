<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT * FROM modulo";
$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.="<option value='".$rs_modulo->fields("id")."' >".$rs_modulo->fields("nombre")."</option>";
	$rs_modulo->MoveNext();
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

function limpiar_reporte_reversos(){
	setBarraEstado("");
	clearForm('form_reverso_int_contabilidad_usuarios_rp_documentos');
	getObj('reverso_int_contabilidad_rp_fecha_desde').value="<?=  $fecha; ?>";
	getObj('reverso_int_contabilidad_usuarios_rp_fecha_hasta').value="<?=  date("d/m/Y"); ?>";
	getObj('reverso_int_contabilidad_mod').value='0';
	
									
}
$("#reverso_int_contabilidad_db_btn_cancelar").click(function() {
	setBarraEstado("");
	
	//getObj('reverso_int_contabilidad_db_compromiso_n').disabled='';
limpiar_reporte_reversos();
});	
//----------------------------------------------------------------------------------------------------

$("#reverso_int_contabilidad_db_btn_imprimir").click(function() {
url="pdf.php?p=modulos/contabilidad/integracion_contable/rp/vista.lst_reversos.php¿desde="+getObj('reverso_int_contabilidad_rp_fecha_desde').value+"@hasta="+getObj('reverso_int_contabilidad_usuarios_rp_fecha_hasta').value+"@modulo="+getObj('reverso_int_contabilidad_mod').value+"@usua="+getObj('reverso_int_contabilidad_usuarios_rp_id_usuario').value; 
		//	url="pdf.php?p=modulos/cuentas_por_pagar/documentos/rp/vista.lst.documentos_partidas.php";
		//	setBarraEstado(url);
			openTab("Reversos Usuarios",url);
			
		
});
	


//----------------------------------------------------------------------------------------------------
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>



$("#reverso_int_contabilidad_usuarios_rp_radio1").click(function(){
		getObj('reverso_int_contabilidad_usuarios_rp_op_oculto').value="1"
	});
$("#reverso_int_contabilidad_usuarios_rp_radio2").click(function(){
		getObj('reverso_int_contabilidad_usuarios_rp_op_oculto').value="2"
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
  oTxt=getObj('reverso_int_contabilidad_db_fecha_v');
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$("#reverso_int_contabilidad_usuarios_rp_btn_consultar_usuario").click(function() {

//// ESTA CONSULTA DE USUARIO ES SMILAR ALA QUE ESTA EN TESORERIA

var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/contabilidad/integracion_contable/rp/grid_usuario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco").val(); 
					var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco2").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/integracion/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria-reportes-busq_nombre_usuario_banco").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_banco_reportes_dosearch();
												
					});
				$("#tesoreria-reportes-busq_nombre_usuario_banco2").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						tesoreria_usuario_banco_reportes_dosearch();
												
					});
					function tesoreria_usuario_banco_reportes_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(tesoreria_usuario_banco_reportes_gridReload,500)
										}
						function tesoreria_usuario_banco_reportes_gridReload()
						{
						var busq_nombre= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco").val(); 
						var busq_usuario= jQuery("#tesoreria-reportes-busq_nombre_usuario_banco2").val(); 
						jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/integracion_contable/rp/sql_grid_usuario.php?busq_nombre="+busq_nombre+"&busq_usuario="+busq_usuario,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/integracion_contable/rp/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
     							colNames:['Id','Usuario','Unidad'],
     							colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'unidad',index:'unidad', width:200,sortable:false,resizable:false},
												],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('reverso_int_contabilidad_usuarios_rp_id_usuario').value = ret.id;
									getObj('reverso_int_contabilidad_usuarios_rp_usuario').value = ret.nombre;
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
/////////////////////////////////////////////
</script>
   <div id="botonera"><img id="reverso_int_contabilidad_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="reverso_int_contabilidad_db_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />	</div>
	
	</div>
<form method="post" id="form_reverso_int_contabilidad_usuarios_rp_documentos" name="form_reverso_int_contabilidad_db_docuemntos">
<input type="hidden"  id="reverso_int_contabilidad_db_vista_documentos" name="reverso_int_contabilidad_db_vista_documentos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Reversos de Comprobantes  </th>
	</tr>
	<th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="reverso_int_contabilidad_rp_fecha_desde" id="reverso_int_contabilidad_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="reverso_int_contabilidad_rp_fecha_desde_oculto" id="reverso_int_contabilidad_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="reverso_int_contabilidad_usuarios_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "reverso_int_contabilidad_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "reverso_int_contabilidad_usuarios_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("reverso_int_contabilidad_rp_fecha_desde").value.MMDDAAAA());
								f2=new Date( getObj("reverso_int_contabilidad_usuarios_rp_fecha_hasta").value.MMDDAAAA());
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("reverso_int_contabilidad_cheques_anulados_usuarios_rp_fecha_desde").value =getObj("reverso_int_contabilidad_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
		  </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="reverso_int_contabilidad_usuarios_rp_fecha_hasta" id="reverso_int_contabilidad_usuarios_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="reverso_int_contabilidad_usuarios_rp_fecha_hasta_oculto" id="reverso_int_contabilidad_usuarios_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="reverso_int_contabilidad_usuarios_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "reverso_int_contabilidad_usuarios_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "reverso_int_contabilidad_usuarios_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("reverso_int_contabilidad_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("reverso_int_contabilidad_usuarios_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("reverso_int_contabilidad_usuarios_rp_fecha_hasta").value =getObj("reverso_int_contabilidad_usuarios_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
   <tr>
		<th>M&oacute;dulo origen de la Integraci&oacute;n:</th>
		<td><select  id="reverso_int_contabilidad_mod" name="reverso_int_contabilidad_mod" message="Seleccione un Modulo">
		<option value='0'>--SELECCIONE--</option>
			<?= $opt_modulo ?>
		</select></td>
	</tr>
<tr>
      <th>Usuario</th>
      <td><ul class="input_con_emergente">
          <li>
            <input name="reverso_int_contabilidad_usuarios_rp_usuario" type="text" id="reverso_int_contabilidad_usuarios_rp_usuario"    size="50" maxlength="80" message="Seleccione el Nombre de un usuario" readonly 
			 />
            <input type="hidden" id="reverso_int_contabilidad_usuarios_rp_id_usuario" name="reverso_int_contabilidad_usuarios_rp_id_usuario"/>
          </li>
        <li id="reverso_int_contabilidad_usuarios_rp_btn_consultar_usuario" class="btn_consulta_emergente"></li>
      </ul></td>
    </tr>

	
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="reverso_int_contabilidad_usuarios_rp_id" type="hidden" id="reverso_int_contabilidad_usuarios_rp_id"  />
</form>

   