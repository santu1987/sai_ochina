<? if (!$_SESSION) session_start();
?>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM frecuencia ORDER BY id_frecuencia";
$rs_concepto =& $conn->Execute($sql);
while (!$rs_concepto->EOF){
	$opt_concepto.="<option value='".$rs_concepto->fields("id_frecuencia")."' >".$rs_concepto->fields("descripcion")."</option>";
	$rs_concepto->MoveNext();
} 
?>
<script language="javascript">
(function($){
	// call setMask function on the document.ready event. Formatos de Input
	$(function(){ 
		$('input:text').setMask(); 
		}
);    
})(jQuery);
//----------------- fin mascara edicion de campo -------------------------///
</script>
<script>
$("#prestamo_pr_trabajador").change(function()
{
		$.ajax({
			url:"modulos/rrhh/prestamo/pr/sql_consu_trabajador.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_prestamo'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			  if(recordset)
				{				
					recordset = recordset.split("*");
					var nombre = recordset[1];
					var apellido= recordset[2];
					var trabaja= nombre+" "+apellido;
					getObj('prestamo_pr_id_trabajador').value =	recordset[0];
					getObj('prestamo_pr_trabajador').value = trabaja;					
				}
			if(!recordset){
					getObj('prestamo_pr_id_trabajador').value =	'';
					getObj('prestamo_pr_trabajador').value = '';	
				}
			 }
		});	 	 
});
$("#prestamo_pr_id_concepto").change(function()
{
		$.ajax({
			url:"modulos/rrhh/prestamo/pr/sql_consu_concepto.php",
			//"modulos/adquisiones/requisiciones/db/sql_grid_accion_especifica_central.php",
            data:dataForm('form_pr_prestamo'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		  var recordset=html
			  if(recordset)
				{				
					recordset = recordset.split("*");
					getObj('prestamo_pr_id_concepto').value =	recordset[0];
					getObj('prestamo_pr_concep').value = recordset[1];					
				}
			if(!recordset){
					getObj('prestamo_pr_id_concepto').value =	'';
					getObj('prestamo_pr_concep').value = '';	
				}
			 }
		});	 	 
});
//------------------ Marcaras de edicion de campos de entrada -----------------////

var dialog;
//----------------------------------------------------------------------------------------------------
$("#prestamo_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/prestamo/pr/vista.grid_prestamo_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Prestamos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#prestamo_pr_trabajador_grid").val(); 
					var busq_ci= jQuery("#prestamo_pr_ci_grid").val(); 
					var busq_fecha= jQuery("#prestamo_pr_fecha_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_prestamo_nombre.php?busq_nombre="+busq_nombre+"&busq_ci="+busq_ci+"&busq_fecha="+busq_fecha,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#prestamo_pr_trabajador_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#prestamo_pr_ci_grid").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#prestamo_pr_fecha_grid").keypress(function(key)
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
							var busq_nombre= jQuery("#prestamo_pr_trabajador_grid").val();
							var busq_ci= jQuery("#prestamo_pr_ci_grid").val();
							var busq_fecha= jQuery("#prestamo_pr_fecha_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_prestamo_nombre.php?busq_nombre="+busq_nombre+"&busq_ci="+busq_ci+"&busq_fecha="+busq_fecha,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/prestamo/pr/sql_prestamo_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','id_concepto','concepto','Monto','Cuota','Saldo','Fecha Prestamo','observacion','frecuencia',''],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador',hidden:true},
									{name:'cedula',index:'cedula',width:100},
									{name:'nombre',index:'nombre',width:100},
									{name:'apellido',index:'apellido',width:100},
									{name:'id_concepto',index:'id_concepto',hidden:true},
									{name:'concepto',index:'concepto',hidden:true},
									{name:'monto',index:'monto',hidden:true},
									{name:'cuota',index:'cuota',hidden:true},
									{name:'saldo',index:'saldo',hidden:true},
									{name:'fecha',index:'fecha',width:60},
									{name:'obs',index:'obs',hidden:true},
									{name:'frecuencia',index:'frecuencia',hidden:true},
									{name:'id_prestamo',index:'id_prestamo',hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('prestamo_pr_id_trabajador').value=ret.id_trabajador;
									getObj('prestamo_pr_id_concepto').value=ret.id_concepto;
									var trabajador=ret.nombre+" "+ret.apellido;
									getObj('prestamo_pr_trabajador').value=trabajador;
									getObj('prestamo_pr_concep').value=ret.concepto;
									getObj('prestamo_pr_monto').value=ret.monto;
									getObj('prestamo_pr_cuota').value=ret.cuota;
									getObj('prestamo_pr_saldo').value=ret.saldo;
									getObj('prestamo_pr_fecha').value=ret.fecha;
									getObj('prestamo_pr_comentario').value=ret.obs;
									var frecu=ret.frecuencia;
									if(frecu==2){
										getObj('prestamo_pr_frecuencia').selectedIndex=1;
									}
									if(frecu==3){
										getObj('prestamo_pr_frecuencia').selectedIndex=2;
									}
									if(frecu==4){
										getObj('prestamo_pr_frecuencia').selectedIndex=3;
									}
									if(frecu==5){
										getObj('prestamo_pr_frecuencia').selectedIndex=4;
									}
									getObj('prestamo_pr_id_prestamo').value=ret.id_prestamo;
									getObj('prestamo_pr_btn_guardar').style.display = 'none';
									getObj('prestamo_pr_btn_actualizar').style.display = '';
									getObj('prestamo_pr_btn_eliminar').style.display = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#prestamo_pr_trabajador_grid').alpha({allow:' '});
								$('#prestamo_pr_ci_grid').numeric({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_prestamo',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//

//
//
//----------------------------------------------------------------


$("#prestamo_pr_btn_guardar").click(function() {
	if(getObj('prestamo_pr_trabajador').value!='' && getObj('prestamo_pr_concep').value!=''){
	//if ($('#form_pr_prestamo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/prestamo/pr/sql.registrar_prestamo.php",
			data:dataForm('form_pr_prestamo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					limpiar();
//					clearForm('form_pr_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
	else{
		setBarraEstado(mensaje[19],true,true);
	}
});

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#prestamo_pr_btn_actualizar").click(function() {
	if(getObj('prestamo_pr_trabajador').value!='' && getObj('prestamo_pr_concep').value!=''){
	//if ($('#form_pr_prestamo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/prestamo/pr/sql.actualizar_prestamo.php",
			data:dataForm('form_pr_prestamo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar();
//					clearForm('form_pr_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
	else{
		setBarraEstado(mensaje[19],true,true);
	}
});

//
//----------------------------------------------------------------
$("#prestamo_pr_btn_eliminar").click(function() {
	if ($('#form_pr_prestamo').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/prestamo/pr/sql.eliminar_prestamo.php",
			data:dataForm('form_pr_prestamo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					limpiar();
//					clearForm('form_pr_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else 
				{//getObj('cargar_cotizacion_pr_titulo').value=html;
					//alert(html);
					//setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					setBarraEstado(html);
				}
			}
		});
	}
});

//
//
$("#concepto_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/prestamo/pr/vista.grid_concepto.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Concepto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#prestamo_pr_concepto_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_concepto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#prestamo_pr_concepto_grid").keypress(function(key)
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
							var busq_nombre= jQuery("#prestamo_pr_concepto_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_concepto.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/prestamo/pr/sql_concepto.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Concepto'],
								colModel:[
									{name:'id_concepto',index:'id_concepto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'concepto',index:'concepto', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('prestamo_pr_id_concepto').value=ret.id_concepto;
									//alert(ret.concepto);
									getObj('prestamo_pr_concep').value=ret.concepto;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#prestamo_pr_concepto_grid').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_concepto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
$("#trabajador_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/prestamo/pr/vista.grid_trabajador.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Trabajadores', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#prestamo_pr_trabajador_grid").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_trabajador.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#prestamo_pr_trabajador_grid").keypress(function(key)
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
							var busq_nombre= jQuery("#prestamo_pr_trabajador_grid").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/prestamo/pr/sql_trabajador.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/prestamo/pr/sql_trabajador.php?nd='+nd,
								datatype: "json",
								colNames:['ID','CI','Nombres','Apellidos'],
								colModel:[
									{name:'id_trabajador',index:'id_trabajador',hidden:true},
									{name:'ci',index:'ci', width:100},
									{name:'nombre',index:'nombre', width:100},
									{name:'apellido',index:'apellido', width:100}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('prestamo_pr_id_trabajador').value=ret.id_trabajador;
									var trabajador=ret.nombre+" "+ret.apellido;
									getObj('prestamo_pr_trabajador').value=trabajador;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$('#prestamo_pr_trabajador_grid').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_trabajador',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
// ******************************************************************************
function limpiar(){
	getObj('prestamo_pr_id_concepto').value='';
	getObj('prestamo_pr_concep').value='';
	getObj('prestamo_pr_id_trabajador').value = '';
	getObj('prestamo_pr_trabajador').value = '';
	getObj('prestamo_pr_frecuencia').selectedIndex = 0;
	getObj('prestamo_pr_monto').value = '0,00';
	getObj('prestamo_pr_cuota').value = '0,00';
	getObj('prestamo_pr_saldo').value = '0,00';
	getObj('prestamo_pr_fecha').value = '<?= date("d/m/Y"); ?>';
	getObj('prestamo_pr_comentario').value = '';
	getObj('prestamo_pr_btn_actualizar').style.display = 'none';
	getObj('prestamo_pr_btn_eliminar').style.display = 'none';
	getObj('prestamo_pr_btn_guardar').style.display = '';
}
$("#prestamo_pr_btn_cancelar").click(function() {
	limpiar();
setBarraEstado("");
});
$("#prestamo_pr_cuota").blur(function() {
	var monto= getObj('prestamo_pr_monto').value;
	var val_cuota = getObj('prestamo_pr_cuota').value;
	monto=monto.replace('.','');
	monto=monto.replace(",",".");
	monto= parseFloat(monto);
	val_cuota=val_cuota.replace('.','');
	val_cuota=val_cuota.replace(",",".");
	val_cuota= parseFloat(val_cuota);
	num_cuota=monto%val_cuota;
	alert(num_cuota);
	num_cuota=Math.round(num_cuota);
	getObj('prestamo_pr_numcuota').value=num_cuota;
});
//
//
function saldo(){
	getObj('prestamo_pr_saldo').value = getObj('prestamo_pr_monto').value;
}
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#prestamo_pr_porcentaje').numeric({allow:','});
$('#prestamo_pr_descripcion_concepto').alpha({allow:' '});
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
//
</script>
<div id="botonera">
	<img id="prestamo_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="prestamo_pr_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="prestamo_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="prestamo_pr_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="prestamo_pr_btn_guardar" src="imagenes/iconos/prestar.png"  /></div>
    

<form name="form_pr_prestamo" id="form_pr_prestamo">
  <input type="hidden" name="prestamo_pr_id_prestamo" id="prestamo_pr_id_prestamo" />
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Prestamo</th>
	</tr>
        <tr>
			<th width="159">Trabajador</th>
		  <td>
             <ul class="input_con_emergente">
				<li>
           <input name="prestamo_pr_trabajador" type="text"  id="prestamo_pr_trabajador" size="30"
           jval="{valid:/^[VEP- 0-9]{1,60s}$/, message:'Falta el Nombre del Trabajador', styleType:'cover'}" 
			jvalkey="{valid:/[VEP - 0-9]/, cFunc:'alert', cArgs:['Cedula: '+$(this).val()]}" message="Escriba o Seleccione el Número de Cédula del Trabajador al que se le hará el Prestamo'"/>
           <input type="hidden" name="prestamo_pr_id_trabajador" id="prestamo_pr_id_trabajador" />
			   </li>
				<li id="trabajador_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
          <th>Concepto</th>
          <td><ul class="input_con_emergente">
            <li>
              <input name="prestamo_pr_id_concepto" type="text" id="prestamo_pr_id_concepto" size="4" maxlength="3" message="Escriba el Código del Concepto o Consulte el Concepto en la Lupa"/>
              <label>
                <input name="prestamo_pr_concep" type="text" id="prestamo_pr_concep" size="30" 
                message="Seleccione un Concepto'"/>
              </label>
            </li>
            <li id="concepto_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
          </ul></td>
        </tr>
        <tr>
          <th>Frecuencia</th>
          <td><label>
            <select name="prestamo_pr_frecuencia" id="prestamo_pr_frecuencia" message="Seleccione la Frecuencia de Pago'">
            <option value="0">-- SELECCION --</option>
              <?= $opt_concepto?>
            </select>
          </label></td>
        </tr>
        <tr>
			<th>Monto</th>
		  <td><input name="prestamo_pr_monto" type="text"  id="prestamo_pr_monto" maxlength="5" size="8" onblur="saldo()" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Limite Inferior Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" message="Escriba el Monto del Prestamo'"/></td>
		</tr>
        <tr>
			<th>Cuota</th>
		  <td><input name="prestamo_pr_cuota" type="text"  id="prestamo_pr_cuota" maxlength="5" size="8" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Limite Inferior Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" message="Asigne el Número de Cuotas a pagar'"/></td>
	    </tr>
        <tr>
          <th>Saldo</th>
          <td><input readonly="true" name="prestamo_pr_saldo" type="text"  id="prestamo_pr_saldo" maxlength="5" size="8" alt="signed-decimal" jval="{valid:/^[0-9.,]{1,60}$/, message:'Limite Inferior Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
        </tr>
        <tr>
          <th>Nº Cuotas</th>
          <td><input readonly="true" name="prestamo_pr_numcuota" type="text"  id="prestamo_pr_numcuota" maxlength="5" size="4"/></td>
        </tr>
        <tr>
			<th>Fecha</th>
		  <td>
		    <input name="prestamo_pr_fecha" type="text" id="prestamo_pr_fecha" value="<?php echo date("d/m/Y"); ?>" size="8" maxlength="10" readonly="readonly" />
		    <button type="reset" id="fecha_boton"> ...</button>
	      <script type="text/javascript">
                        Calendar.setup({
                            inputField     :    "prestamo_pr_fecha",      // id of the input field
                            ifFormat       :    "%d/%m/%Y",       // format of the input field
                            showsTime      :    false,            // will display a time selector
                            button         :    "fecha_boton",   // trigger for the calendar (button ID)
                            singleClick    :    true          // double-click mode
                        });
                    </script></td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="prestamo_pr_comentario" id="prestamo_pr_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>