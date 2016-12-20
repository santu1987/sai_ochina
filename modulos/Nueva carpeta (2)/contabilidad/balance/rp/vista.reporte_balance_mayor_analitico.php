<?php
if (!$_SESSION) session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

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

function limpiar_reporte_detalle_doc_cxp(){
	setBarraEstado("");
	clearForm('form_contabilidad_rp_mayor');
	getObj('contabilidad_mayor_resumen_rp_fecha_desde').value="<?=  $fecha; ?>";
	getObj('contabilidad_mayor_resumen_rp_fecha_hasta').value="<?=  date("d/m/Y"); ?>";
	getObj('contabilidad_rp_tipo_documento').value='0';
	getObj('contabilidad_rp_op_oculto').value='1';
	getObj('contabilidad_db_radio1').checked="checked";
	getObj('tr_empleado_cxp_rp').style.display='none';
	getObj('tr_proveedor_cxp_rp').style.display='';
									
}
$("#contabilidad_vista_btn_consultar_mayor_rp").click(function() {
	
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
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta,page:1}).trigger("reloadGrid"); 
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
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_nom="+busq_nom;	
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
					url="modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?busq_cuenta="+busq_cuenta;
                 // ¿ alert(url);				
				}
			}
		}
	);
///						
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
	 						({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/auxiliares/db/sql_grid_cuenta_suma.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Cuenta', 'Denominacion','Tipo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:200,sortable:false,resizable:false},
									{name:'tipo',index:'tipo', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									$('#contabilidad_mayor_rp_cuenta_contable').val(ret.cuenta_contable);
									getObj('contabilidad_mayor_rp_id_cuenta').value=ret.id;
									getObj('contabilidad_mayor_rp_desc').value=ret.nombre;
					
//									$('#contabilidad_auxiliares_db_id_cuenta').val(ret.id);
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

$("#contabilidad_mayor_resumen_db_btn_cancelar").click(function() {
	setBarraEstado("");
	
	//getObj('contabilidad_db_compromiso_n').disabled='';
limpiar_reporte_detalle_doc_cxp();
});	
function cuenta_contable_cod_mayor_rp()
{
	$.ajax({
			url:"modulos/contabilidad/balance/rp/sql_contabilidad_mayor_rp_cuenta_contable.php",
            data:dataForm('form_contabilidad_rp_mayor'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('contabilidad_mayor_rp_id_cuenta').value=recordset[0];
					getObj('contabilidad_mayor_rp_cuenta_contable').value=recordset[1];
					getObj('contabilidad_mayor_rp_desc').value=recordset[2];
				}
				else
				{
					getObj('contabilidad_mayor_rp_cuenta_contable').value="";
				}
			 }
		});		
}
//----------------------------------------------------------------------------------------------------
$("#contabilidad_vista_btn_consultar_auxiliar_rp_ma").click(function() {
if(getObj('contabilidad_mayor_rp_id_cuenta').value!=="")
{	/*
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:'modulos/contabilidad/auxiliares/db/vista.grid_contabilidad_auxiliares.php',							
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de auxiliares', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#contabilidad_auxiliares_nombre_ma").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nombre="+busq_nombre+"&cuenta_contable="+getObj('contabilidad_mayor_rp_id_cuenta').value,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_auxiliares_nombre_ma").keypress(function(key)
				{
						auxiliares_dosearch();
												
					});
					function auxiliares_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(auxiliares_gridReload,500)
										}
						function auxiliares_gridReload()
						{
							var busq_nombre= jQuery("#contabilidad_auxiliares_nombre_ma").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nombre="+busq_nombre+"&cuenta_contable="+getObj('contabilidad_mayor_rp_id_cuenta').value,page:1}).trigger("reloadGrid"); 
							url="modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?busq_nombre="+busq_nombre+"&cuenta_contable="+getObj('contabilidad_mayor_rp_id_cuenta').value;
							//alert(url);
						}

			}
		});*/
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
											loadtext: "Recuperando Información del Servidor",		
											url:'modulos/contabilidad/movimientos_contables/pr/cmb.sql_auxiliares.php?cuenta='+getObj('contabilidad_mayor_rp_id_cuenta').value,							
											datatype: "json",
											colNames:['id','c&oacute;digo','Denominaci&oacute;n'],
											colModel:[
												{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
												{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
												{name:'denominacion',index:'denominacion', width:50,sortable:false,resizable:false},

													],
											pager: $('#pager_grid_'+nd),
											rowNum:20,
											rowList:[20,50,100],
											imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
											onSelectRow: function(id){
											var ret = jQuery("#list_grid_"+nd).getRowData(id);
												$('#contabilidad_mayor_rp_aux').val(ret.cuenta_contable);
												$('#contabilidad_mayor_id_aux').val(ret.id);
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
}
});

////////////////////////////////////

$("#contabilidad_resumen_db_orden_btn_imprimir").click(function() {
			url="pdf.php?p=modulos/contabilidad/balance/rp/vista.lst.balance_mayores_an.php¿desde="+getObj('contabilidad_mayor_resumen_rp_fecha_desde').value+"@hasta="+getObj('contabilidad_mayor_resumen_rp_fecha_hasta').value+"@id_cuenta="+getObj('contabilidad_mayor_rp_id_cuenta').value+"@aux="+getObj('contabilidad_mayor_id_aux').value; 
			openTab("Mayor analitico",url);
//			alert(url);
alert(url);
});
</script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_rp_empleado_codigo').numeric({});
$('#contabilidad_rp_proveedor_codigo').numeric({});

$("#contabilidad_rp_radio1").click(function(){
		getObj('contabilidad_rp_op_oculto').value="1"
	});
$("#contabilidad_rp_radio2").click(function(){
		getObj('contabilidad_rp_op_oculto').value="2"
	});

//$('#contabilidad_cheque_manuals_db_concepto').alpha({allow:' áéíóúÄÉÍÓÚ'});
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
function auxiliares_consulta_mov_aux_ma()
{
		url="modulos/contabilidad/balance/rp/sql_grid_auxi.php";
		//alert(url);

		//alert('entro');
			$.ajax({
						url:"modulos/contabilidad/balance/rp/sql_grid_auxi.php",
						data:dataForm('form_contabilidad_rp_mayor'), 
						type:'POST',
						cache: false,
						 success:function(html)
						 {
							var recordset=html;
						//	alert(html);
							if((recordset)&&(recordset!='vacio'))
							{
								recordset = recordset.split("*");
								//getObj('').value = recordset[0];
								getObj('contabilidad_mayor_id_aux').value=recordset[0];
								//getObj('contabilidad_auxiliar_db_cuenta_contable').value=recordset[2];
								/*getObj('contabilidad_auxiliares_db_nombre').value=recordset[4];
								getObj('contabilidad_auxiliares_db_comentario').value=recordset[5];
								getObj('contabilidad_auxiliares_db_btn_eliminar').style.display='';
								getObj('contabilidad_auxiliares_db_desc').value=recordset[6];*/
							}
							else
							if(recordset=='vacio')
							{	
								getObj('contabilidad_mayor_rp_aux').value='';
								getObj('contabilidad_mayor_id_aux').value='';

							}
							
						 }
					});	 	
	
}
//////////////////////////////////
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
  oTxt=getObj('contabilidad_db_fecha_v');
  var bOk = true;
  if (oTxt.value != ""){
   bOk = bOk && (valAno(oTxt));
   bOk = bOk && (valMes(oTxt));
   bOk = bOk && (valDia(oTxt));
   bOk = bOk && (valSep(oTxt));
   if (!bOk){
   alert("Fecha inválida");
   oTxt.value ="<?= date("d/m/Y")?>";
  // getObj('contabilidad_db_fecha_v').value = date();
  // oTxt.focus();
   } //else alert("Fecha correcta");
  }
  }
 
/////////////////////////////////////////////
</script>
   <div id="botonera"><img id="contabilidad_mayor_resumen_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="contabilidad_resumen_db_orden_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />	</div>
	
	</div>
<form method="post" id="form_contabilidad_rp_mayor" name="form_contabilidad_rp_mayor">
<input type="hidden"  id="contabilidad_vista_mayor" name="contabilidad_vista_mayor"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Mayor Anal&iacute;tico Selectivo </th>
	</tr>
	<th>Desde :</th>
	      <td><label>
	      <input readonly="true" type="text" name="contabilidad_mayor_resumen_rp_fecha_desde" id="contabilidad_mayor_resumen_rp_fecha_desde" size="7" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="contabilidad_mayor_resumen_rp_fecha_desde_oculto" id="contabilidad_mayor_resumen_rp_fecha_desde_oculto" value="<? echo $fecha ?>"/>
	      <button type="reset" id="contabilidad_mayor_resumen_rp_fecha_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "contabilidad_mayor_resumen_rp_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "contabilidad_mayor_resumen_rp_fecha_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("contabilidad_mayor_resumen_rp_fecha_desde").value.MMDDAAAA());
								f2=new Date( getObj("contabilidad_mayor_resumen_rp_fecha_hasta").value.MMDDAAAA());
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("contabilidad_cheques_anulados_usuarios_rp_fecha_desde").value =getObj("contabilidad_mayor_resumen_rp_fecha_desde_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
		  </tr>
	<tr>
	  <th>Hasta :</th>
	      <td><label>
	      <input readonly="true" type="text" name="contabilidad_mayor_resumen_rp_fecha_hasta" id="contabilidad_mayor_resumen_rp_fecha_hasta" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="contabilidad_mayor_resumen_rp_fecha_hasta_oculto" id="contabilidad_mayor_resumen_rp_fecha_hasta_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="contabilidad_mayor_resumen_rp_fecha_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "contabilidad_mayor_resumen_rp_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "contabilidad_mayor_resumen_rp_fecha_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("contabilidad_mayor_resumen_rp_fecha_desde").value.MMDDAAAA() );
								f2=new Date( getObj("contabilidad_mayor_resumen_rp_fecha_hasta").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("contabilidad_mayor_resumen_rp_fecha_hasta").value =getObj("contabilidad_mayor_resumen_rp_fecha_hasta_oculto").value;
									}
							}
					});
			</script>
	      </label></td>
    <tr>
		<th>Cuenta Contable:</th>
		 <td>
		 <ul class="input_con_emergente">
		 <li>
		    	<input type="text" name="contabilidad_mayor_rp_cuenta_contable" id="contabilidad_mayor_rp_cuenta_contable"  size='12' maxlength="12"
				message="Introduzca la cuenta contable"  onblur="cuenta_contable_cod_mayor_rp()"
				/>
		       <input type="text" id="contabilidad_mayor_rp_desc"  name="contabilidad_mayor_rp_desc" readonly="readonly">
                <input type="hidden" id="contabilidad_mayor_rp_id_cuenta" name="contabilidad_mayor_rp_id_cuenta" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_mayor_rp" class="btn_consulta_emergente"></li>
	    </ul>	  </td>	
    </tr>     
	<tr>
		<th>Auxiliares:</th>
		<td>
		<ul class="input_con_emergente">
		 <li>
				<input type="text" id="contabilidad_mayor_rp_aux" name="contabilidad_mayor_rp_aux" onblur="auxiliares_consulta_mov_aux_ma()" />
				<input type="hidden" id="contabilidad_mayor_id_aux" name="contabilidad_auxiliares_id" />
		 </li>
		<li id="contabilidad_vista_btn_consultar_auxiliar_rp_ma" class="btn_consulta_emergente"></li>
	    </ul>	
		</td>
	</tr>   
	
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="contabilidad_rp_id" type="hidden" id="contabilidad_rp_id"  />
</form>

   