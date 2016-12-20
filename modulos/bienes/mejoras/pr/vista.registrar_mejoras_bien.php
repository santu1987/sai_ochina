<?php
session_start();
$nombre="";
$codigo="";
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
if($_REQUEST['id_bienes']!=""){
$sql="SELECT codigo_bienes, bienes.nombre FROM bienes inner join organismo on bienes.id_organismo= organismo.id_organismo WHERE bienes.id_organismo = $_SESSION[id_organismo] AND bienes.id_bienes= $_REQUEST[id_bienes]";
$row =& $conn->Execute($sql);
$codigo = $row->fields("codigo_bienes");
$nombre = $row->fields("nombre");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 

<html> 
<head>
<title>Crear input file</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- -->
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
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------
//
//
$("#form_mejoras_bien_pr_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/mejoras/pr/vista.grid_mejoras_bien2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Mejoras del Activo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#mejoras_bien_pr_nombre_bien").val(); 
					var busq_codigo= jQuery("#mejoras_bien_pr_codigo_bien").val(); 
					var busq_numero= jQuery("#mejoras_bien_pr_numero_comprobante").val(); 
					var busq_fecha_mejora= jQuery("#mejoras_bien_pr_fecha_mejora").val(); 
					var busq_fecha_comprobante= jQuery("#mejoras_bien_pr_fecha_comprobante").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre2.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&busq_numero="+busq_numero+"&busq_fecha_mejora="+busq_fecha_mejora+"&busq_fecha_comprobante="+busq_fecha_comprobante,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#mejoras_bien_pr_nombre_bien").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
				$("#mejoras_bien_pr_codigo_bien").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
				$("#mejoras_bien_pr_numero_comprobante").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						programa_dosearch();
												
					});
				$("#mejoras_bien_pr_fecha_mejora").focus(function()
				{
						programa_dosearch();
												
					});
				$("#mejoras_bien_pr_fecha_comprobante").focus(function()
				{
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
							var busq_nombre= jQuery("#mejoras_bien_pr_nombre_bien").val();
							var busq_codigo= jQuery("#mejoras_bien_pr_codigo_bien").val();
							var busq_numero= jQuery("#mejoras_bien_pr_numero_comprobante").val();
							var busq_fecha_mejora= jQuery("#mejoras_bien_pr_fecha_mejora").val();
							var busq_fecha_comprobante= jQuery("#mejoras_bien_pr_fecha_comprobante").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre2.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo+"&busq_numero="+busq_numero+"&busq_fecha_mejora="+busq_fecha_mejora+"&busq_fecha_comprobante="+busq_fecha_comprobante,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:840,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre2.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Mejora','Fecha Mejora','Valor Rescate','Usuario','N&deg; Comprobante','Fecha Comprobante','Descripcion','Observacion','id_bienes','Codigo Bien','Activo','Vida Util'],
								colModel:[
									{name:'id_mejoras',index:'id_mejoras', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre_mejora',index:'nombre_mejora', width:100,sortable:false,resizable:false},
									{name:'fecha_mejora',index:'fecha_mejora', width:45,sortable:false,resizable:false},
									{name:'valor_rescate',index:'valor_rescate', width:45,sortable:false,resizable:false},
									{name:'usuario_carga_mejora',index:'usuario_carga_mejora', width:45,sortable:false,resizable:false},
									{name:'numero_comprobante',index:'numero_comprobante', width:60,sortable:false,resizable:false},
									{name:'fecha_comprobante',index:'fecha_comprobante', width:55,sortable:false,resizable:false},
									{name:'descripcion_general',index:'descripcion_general', width:55,sortable:false,resizable:false,hidden:true},
									{name:'comentarios',index:'comentarios', width:55,sortable:false,resizable:false,hidden:true},
									{name:'id_bienes',index:'id_bienes', width:55,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:55,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:55,sortable:false,resizable:false},
									{name:'vida_util',index:'vida_util', width:55,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_mejoras_bien_pr_id_mejoras').value=ret.id_mejoras;
									getObj('form_mejoras_bien_pr_nombre_mejora').value=ret.nombre_mejora;
									getObj('form_mejoras_bien_pr_fecha_mejora').value=ret.fecha_mejora;
									getObj('form_mejoras_bien_pr_valor_mejora').value = ret.valor_rescate; 
									getObj('form_mejoras_bien_pr_descripcion').value = ret.descripcion_general;
									getObj('form_mejoras_bien_pr_comentario').value = ret.comentarios;
									getObj('form_mejoras_bien_pr_id_bienes').value = ret.id_bienes;
									//getObj('form_mejoras_bien_pr_codigo_bien').value = ret.codigo_bienes;
									getObj('form_mejoras_bien_pr_nombre_bien').value = ret.nombre;
									getObj('form_mejoras_bien_pr_vida_util').value = ret.vida_util;
									getObj('form_mejoras_bien_pr_btn_guardar').style.display = 'none';
									//getObj('form_mejoras_bien_pr_btn_actualizar').style.display = '';
									//getObj('form_mejoras_bien_pr_btn_eliminar').style.display = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#mejoras_bien_pr_nombre").focus();
								$('#mejoras_bien_pr_nombre_bien').alpha({allow:' '});
								//$('#mejoras_bien_pr_codigo_bien').alpha({allow:'0123456789 '});
								$('#mejoras_bien_pr_numero_comprobante').alpha({allow:'0123456789 '});
								$('#mejoras_bien_pr_fecha_mejora').alpha({allow:'0123456789 '});
								$('#mejoras_bien_pr_fecha_comprobante').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_mejoras',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
$("#form_mejoras_bien_pr_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/mejoras/pr/vista.grid_mejoras_bien.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente del Activo', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#mejoras_bien_pr_nombre").val(); 
					var busq_codigo= jQuery("#mejoras_bien_pr_codigo").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#mejoras_bien_pr_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#mejoras_bien_pr_codigo").keypress(function(key)
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
							var busq_nombre= jQuery("#mejoras_bien_pr_nombre").val();
							var busq_codigo= jQuery("#mejoras_bien_pr_codigo").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre.php?busq_nombre="+busq_nombre+"&busq_codigo="+busq_codigo,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/bienes/mejoras/pr/sql_mejoras_bien_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Bien'],
								colModel:[
									{name:'id_bienes',index:'id_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_bienes',index:'codigo_bienes', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('form_mejoras_bien_pr_id_bienes').value=ret.id_bienes;
									//getObj('form_mejoras_bien_pr_codigo_bien').value=ret.codigo_bienes;
									getObj('form_mejoras_bien_pr_nombre_bien').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#mejoras_bien_pr_nombre").focus();
								$('#mejoras_bien_pr_nombre').alpha({allow:' '});
								$('#mejoras_bien_pr_codigo').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});


//
//
function consulta_automatica_codigo_bien()
{
	if (getObj('form_mejoras_bien_pr_codigo_bien')!=" ")
	{
	$.ajax({
			url:"modulos/bienes/mejoras/pr/sql_auto_codigo_bienes.php",
            data:dataForm('form_pr_mejoras_bien'),
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
			
				//alert(html);
					if(recordset!=' ')
				{
					recordset = recordset.split("*");
					getObj('form_mejoras_bien_pr_id_bienes').value = recordset[0];
					//getObj('form_mejoras_bien_pr_codigo_bien').value = recordset[1];
					getObj('form_mejoras_bien_pr_nombre_bien').value = recordset[2];
				 }
				 else
				 {
					getObj('form_mejoras_bien_pr_nombre_bien').value=''; 
					setBarraEstado("");
				 }
			 }
		});	 	 
	}	
}
//
//
//----------------------------------------------------------------


$("#form_mejoras_bien_pr_btn_guardar").click(function() {
if ($('#form_pr_mejoras_bien').jVal()){
	$.ajax ({
			url: "modulos/bienes/mejoras/pr/sql.registrar_mejoras_bien.php",
			data:dataForm('form_pr_mejoras_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{
					if(html=='Registrado'){
						setBarraEstado(mensaje[registro_exitoso],true,true);
						limpiar();
					}
					else{
						setBarraEstado(html);
					}
				
			}
		});	
}
});


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#form_mejoras_bien_pr_btn_actualizar").click(function() {
	if ($('#form_pr_mejoras_bien').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/mejoras/pr/sql.actualizar_mejoras_bien.php",
			data:dataForm('form_pr_mejoras_bien'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					limpiar();
//					clearForm('form_db_valor_impuesto');
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
});

//
//
//
$("#fotos_bien_pr_btn_eliminar").click(function() {
//clearForm('form_db_custodio');

});


//
//
//
//
// ******************************************************************************

$("#form_mejoras_bien_pr_btn_cancelar").click(function() {
//clearForm('form_db_custodio');
limpiar();
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------
/* ******************************************************************************


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#form_mejoras_bien_pr_codigo_bien').alpha({allow:'0123456789-_ '});
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
//
function limpiar(){
	getObj('form_mejoras_bien_pr_id_bienes').value='';
	//getObj('form_mejoras_bien_pr_codigo_bien').value='';
	getObj('form_mejoras_bien_pr_nombre_bien').value='';
	getObj('form_mejoras_bien_pr_nombre_mejora').value='';
	var fecha = getObj('form_mejoras_bien_pr_fechact').value;
	getObj('form_mejoras_bien_pr_fecha_mejora').value=fecha;
	getObj('form_mejoras_bien_pr_valor_mejora').value='0,00';
	getObj('form_mejoras_bien_pr_descripcion').value='';
	getObj('form_mejoras_bien_pr_comentario').value='';
	getObj('form_mejoras_bien_pr_vida_util').value='';
	getObj('form_mejoras_bien_pr_btn_guardar').style.display = '';
	getObj('form_mejoras_bien_pr_btn_actualizar').style.display = 'none';
	getObj('form_mejoras_bien_pr_btn_eliminar').style.display = 'none';
}
</script>
<!-- -->
<div id="botonera">
	<img id="form_mejoras_bien_pr_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="form_mejoras_bien_pr_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif" style="display:none"/>
    <img style="display:none" id="custodio_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
    <img id="form_mejoras_bien_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
   <img id="form_mejoras_bien_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>
	<img id="form_mejoras_bien_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>

<div id="index" style="position:absolute; width:100%; height:100%; top:0%; left:0%; opacity:0.5; display:none; z-index:1">
<img src="imagenes/iconos/fondo.gif" style="height:100%; width:100%"/>
</div>
<div id="prueba" align="center" style="position:absolute; top:50%; left:40%; border:3px solid; border-color:#CCC; background:#FFF; display:none; z-index:2">
<img id="foto_logo" src="imagenes/iconos/logo1.jpg" onclick="change_picture();"/>
<div><img id="foto_loading" src="imagenes/iconos/ajax-loader.gif"/></div>
</div>
</head>
<body>
<form name="form_pr_mejoras_bien" id="form_pr_mejoras_bien" method="POST" action="">
<input id="form_mejoras_bien_pr_id_bienes" name="form_mejoras_bien_pr_id_bienes" type="hidden" />
<input id="form_mejoras_bien_pr_id_mejoras" name="form_mejoras_bien_pr_id_mejoras" type="hidden" />
<input id="form_mejoras_bien_pr_fechact" name="form_mejoras_bien_pr_fechact" type="hidden" value="<?php echo date("d-m-Y");?>" />
  <table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="3">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Mejoras del Activo			</th>
	</tr>
    	<tr>
			<th>Bien</th>
		  <td>
          <ul class="input_con_emergente">
				<li>
           <input name="form_mejoras_bien_pr_nombre_bien" type="text"  id="form_mejoras_bien_pr_nombre_bien" maxlength="60" size="30" readonly="true" value="<?=$nombre;?>"/>
       </li>
				<li id="form_mejoras_bien_pr_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		 
		<tr>
			<th>Nombre Mejora</th>
		  <td colspan="2">
		    <label>
		      <input type="text" name="form_mejoras_bien_pr_nombre_mejora" id="form_mejoras_bien_pr_nombre_mejora" message="Escriba un Nombre de la Mejora" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre de Mejora Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
	        </label>
		  </td>
		</tr>
		<tr>
			<th>Fecha Mejora</th>
		  <td colspan="2"><label>
		   <input readonly="true" type="text" name="form_mejoras_bien_pr_fecha_mejora" id="form_mejoras_bien_pr_fecha_mejora" size="7" value="<?php echo date("d-m-Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_mejora_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "form_mejoras_bien_pr_fecha_mejora",      // id of the input field
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_mejora_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>

		  </label></td>
		</tr>
        <tr>
			<th>Valor de Mejora</th>
		  <td colspan="2">
		    <label>
		      <input type="text" name="form_mejoras_bien_pr_valor_mejora" id="form_mejoras_bien_pr_valor_mejora" message="Escriba el Valor de Mejora del Bien" 
			jval="{valid:/^[0-9.,$ ]{1,60}$/, message:'Valor de Mejora Invalido', styleType:'cover'}"
			jvalkey="{valid:/[0-9.,$]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" alt="signed-decimal"/>
	      </label></td>
		</tr>
        <tr>
			<th>Vida Util</th>
		  <td colspan="2">
		    <label>
		      <input type="text" name="form_mejoras_bien_pr_vida_util" id="form_mejoras_bien_pr_vida_util" size="5" maxlength="2" message="Escriba la Vida Util de la Mejora del Bien"  jval="{valid:/^[0-9]{1,60}$/, message:'Vida Util Invalido', styleType:'cover'}" jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
	      </label></td>
		</tr>
    <tr>
<th>Descriocion General</th>
		  <td colspan="2">
		    <label>
		      <textarea name="form_mejoras_bien_pr_descripcion" id="form_mejoras_bien_pr_descripcion" cols="60" message="Escriba una Descripci&oacute;n"></textarea>
	        </label>
      </td>
    </tr>
    <tr>
<th>Observaci&oacute;n</th>
		  <td colspan="2">
		    <label>
		      <textarea name="form_mejoras_bien_pr_comentario" id="form_mejoras_bien_pr_comentario" cols="60" message="Escriba una Observaci&oacute;n" ></textarea>
	        </label>
      </td>
    </tr>
<tr>
			
		</tr>
		<tr>
			<td colspan="3" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
<label>
</label>
</form>
</body>
</html>