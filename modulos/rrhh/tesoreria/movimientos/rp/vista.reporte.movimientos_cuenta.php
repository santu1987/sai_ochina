<?php
session_start();
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
var dialog;
$("#tesoreria_movimientos_rp_btn_imprimir").click(function() {
	if((getObj('tesoreria_movimientos_rp_nombre').value=="")&&(getObj('tesoreria_movimientos_db_n_cuenta_rep').value==""))
	{
		url="pdf.php?p=modulos/tesoreria/movimientos/rp/vista.lst.movimientos_cuenta.php?desde="+getObj('tesoreria_movimientos_rp_fecha_d').value+"@hasta="+getObj('tesoreria_movimientos_rp_fecha_h').value;
		openTab("Movimientos Bancarios",url);
		
	}
	//øid_banco="+getObj('tesoreria_banco_cuentas_rp_id_banco').value+"@cuenta="+getObj('tesoreria_banco_cuentas_rp_n_cuenta').value+"@nombre="+getObj('tesoreria_banco_cuentas_rp_nombre').value+"@fecha="+getObj('tesoreria_banco_cuentas_rp_ayo').value; 

else
	if((getObj('tesoreria_movimientos_rp_nombre').value!="")&&(getObj('tesoreria_movimientos_db_n_cuenta_rep').value==""))
	{
		url="pdf.php?p=modulos/tesoreria/movimientos/rp/vista.reporte.movimientos_cuenta_banco.php?banco="+getObj('tesoreria_movimientos_id_banco').value+"@desde="+getObj('tesoreria_movimientos_rp_fecha_d').value+"@hasta="+getObj('tesoreria_movimientos_rp_fecha_h').value;
		openTab("Movimientos Bancarios",url);
	}
else
	if((getObj('tesoreria_movimientos_rp_nombre').value!="")&&(getObj('tesoreria_movimientos_db_n_cuenta_rep').value!=""))
	{
		url="pdf.php?p=modulos/tesoreria/movimientos/rp/vista.reporte.movimientos_cuenta_banco_c.php?banco="+getObj('tesoreria_movimientos_id_banco').value+"@cuenta="+getObj('tesoreria_movimientos_db_n_cuenta_rep').value+"@desde="+getObj('tesoreria_movimientos_rp_fecha_d').value+"@hasta="+getObj('tesoreria_movimientos_rp_fecha_h').value;
		openTab("Movimientos Bancarios",url);
		setBarraEstado(ulr);
	}		
	
	
});
//-----------------------------------------------------------------------------------------------------
$("#tesoreria_db_btn_consultar_banco").click(function() {
	/*	var nd=new Date().getTime();
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.post("modulos/tesoreria/movimientos/db/grid_movimientos.php", { },
							function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Bancos activos',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,50);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/movimientos/db/grid_movimientos.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Documentos Cuentas por pagar', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_movimientos_banco-busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/movimientos/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_movimientos_banco-busqueda_bancos").keypress(function(key)
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
							var busq_banco= jQuery("#tesoreria_movimientos_banco-busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/movimientos/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							url="modulos/tesoreria/movimientos/db/sql_grid_banco.php?busq_banco="+busq_banco;
							
						}

			}
		});
						function crear_grid()
						{		
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:350,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/movimientos/db/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo ¡rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios','Cuentas','saldo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:160,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:130,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true },
									{name:'cuentas',index:'cuentas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'saldo_actual',index:'saldo_actual', width:100,sortable:false,resizable:false,hidden:true}
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									
									getObj('tesoreria_movimientos_id_banco').value=ret.id;
									getObj('tesoreria_movimientos_rp_nombre').value=ret.nombre;
									getObj('tesoreria_movimientos_db_n_cuenta_rep').value=ret.cuentas;
								
									
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
$("#tesoreria_movimientos_rp_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tesoreria_movimientos_rp_btn_imprimir').style.display='';
	clearForm('form_tesoreria_db_movimientos');
	getObj('tesoreria_movimientos_db_n_cuenta_rep').disabled='';
	getObj('tesoreria_movimientos_rp_fecha_h').value="<?=  date("d/m/Y"); ?>";	
	getObj('tesoreria_movimientos_rp_fecha_d').value="<?= $fecha; ?>";	
	getObj('tesoreria_movimientos_rp_nombre').disabled='';
	getObj('tesoreria_movimientos_db_n_cuenta_rep').disabled='';
	

});
$("#tesoreria_movimientos_db_n_cuenta_rep_btn_consultar_cuentas_chequeras").click(function() {
if(getObj('tesoreria_movimientos_id_banco').value!="")
{
	var nd=new Date().getTime();
	urls='modulos/tesoreria/movimientos/db/sql_grid_cuenta_cheque.php?nd='+nd+'&banco='+getObj('tesoreria_movimientos_id_banco').value;
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/movimientos/db/grid_movimientos.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Cuentas Activas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:urls,
								//'modulos/tesoreria/usuario_banco_cuentas/db/sql_grid_cuentas.php?nd='+nd,
								datatype: "json",
								colNames:['Id','N∫ Cuenta','Estatus','CuentaNuevo','saldo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ncuenta',index:'ncuenta', width:50,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuentan',index:'cuentan', width:50,sortable:false,resizable:false,hidden:true},
									{name:'saldo',index:'saldo', width:50,sortable:false,resizable:false,hidden:true}
									 ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									dialog.hideAndUnload();
									getObj('tesoreria_movimientos_db_n_cuenta_rep').value=ret.ncuenta;
									getObj('tesoreria_movimientos_saldo_inicial').value=ret.saldo;
				 			
							},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'ncuenta',
								viewrecords: true,
								sortorder: "asc"
							});
						}
}
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
  oTxt=getObj('tesoreria_movimientos_rp_fecha_d');
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
 

</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#tesoreria_movimientos_db_n_cuenta_rep').numeric({allow:'-'});
$('#tesoreria_banco_db_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
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
	
</script>

<div id="botonera">
	<img id="tesoreria_movimientos_rp_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
 	<img id="tesoreria_movimientos_rp_btn_imprimir" class="btn_imprimir"src="imagenes/null.gif" />
</div>
	</div>
<form method="post" id="form_tesoreria_db_movimientos" name="form_tesoreria_db_movimientos">
<input type="hidden"  id="tesoreria_vista_movimientos" name="tesoreria_vista_movimientos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Movimientos Bancarios </th>
	</tr>
	<th>Banco:</th>
	    <td>
	  <ul class="input_con_emergente">
		<li>
		    	<input name="tesoreria_movimientos_rp_nombre" type="text" id="tesoreria_movimientos_rp_nombre"   value="" size="50" maxlength="30" 
				message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' "  readonly
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò- ,.-.]{1,30}$/,message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò-.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	
		<input type="hidden"  id="tesoreria_movimientos_id_banco" name="tesoreria_movimientos_id_banco"/>
		</li>
		<li id="tesoreria_db_btn_consultar_banco" class="btn_consulta_emergente"></li>
	</ul>	</td>
	</tr>
   	<tr>
	<th>N&ordm; Cuenta: </th>	
	    <td>	
		<ul class="input_con_emergente">
		<li>
				<input name="tesoreria_movimientos_rp_n_cuenta_rep" type="text" id="tesoreria_movimientos_db_n_cuenta_rep"   value="" size="50" maxlength="20" message="Introduzca el N˙mero de cuenta. " readonly=""/>
		</li>
		<li id="tesoreria_movimientos_db_n_cuenta_rep_btn_consultar_cuentas_chequeras" class="btn_consulta_emergente"></li>
		</ul>		</td>
	</tr>
		<tr>
			<th>Desde : </th>
			<td><label><input   alt="date" type="text" name="tesoreria_movimientos_rp_fecha_d" id="tesoreria_movimientos_rp_fecha_d" size="7"  onchange="valFecha();" onBlur="valFecha();" value="<? echo $fecha ?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha : '+$(this).val()]}"/>
	      
	      <button type="reset" id="tesoreria_movimientos_rp_boton_d">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_movimientos_rp_fecha_d",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_movimientos_rp_boton_d",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_movimientos_rp_fecha_d").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_movimientos_rp_fecha_h").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del parametro desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_movimientos_rp_fecha_d").value =getObj("tesoreria_movimientos_rp_fecha_d_oculto").value;
									}
							}
					});
			</script>
			
	      </label></td>
		</tr>
		<tr>
		<th width="167">Hasta:</th>
		<td>
			<input readonly="true" type="text" name="tesoreria_movimientos_rp_fecha_h" id="tesoreria_movimientos_rp_fecha_h" size="7" value="<?   $year=date("Y"); echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
	      <input type="hidden"  name="tesoreria_movimientos_rp_fecha_h_oculto" id="tesoreria_movimientos_rp_fecha_h_oculto" value="<?  $year=date("Y"); echo date("d/m")."/".$year;?>"/>
	      <button type="reset" id="tesoreria_movimientos_rp_boton_h">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "tesoreria_movimientos_rp_fecha_h",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "tesoreria_movimientos_rp_boton_h",   // trigger for the calendar (button ID)
						singleClick    :    true,          // double-click mode
						onUpdate :function(date){
								f1=new Date( getObj("tesoreria_movimientos_rp_fecha_d").value.MMDDAAAA() );
								f2=new Date( getObj("tesoreria_movimientos_rp_fecha_h").value.MMDDAAAA() );
								if (f1 > f2) {
									//setBarraEstado(mensaje[fecha_impuesto],true,true);
									alert("La fecha del paramtreo desde no puede ser mayor a la del parametro hasta");
									getObj("tesoreria_movimientos_rp_fecha_h").value =getObj("tesoreria_movimientos_rp_fecha_h_oculto").value;
									}
							}
					});
			</script>
	      	
		  </td>
	  </tr>	
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>  
<input  name="tesoreria_movimientos_rp_id" type="hidden" id="" />
</form>