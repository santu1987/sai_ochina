<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
//
//
$("#contabilidad_ut_fondos_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/contabilidad/utilizacion_fondos/db/vista.grid_contabilidad_utf.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Utilizacion Fondos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#contabilidad_ut_fondos_nombre_consulta").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/utilizacion_fondos/db/sql_contabilidad_utf_cons.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#contabilidad_ut_fondos_nombre_consulta").keypress(function(key)
				{
						ut_fondos_dosearch();
												
					});
					function ut_fondos_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(ut_fondos_gridReload,500)
										}
						function ut_fondos_gridReload()
						{
							var busq_nombre= jQuery("#contabilidad_ut_fondos_nombre_consulta").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/contabilidad/utilizacion_fondos/db/sql_contabilidad_utf_cons.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
							url="modulos/contabilidad/utilizacion_fondos/db/sql_contabilidad_utf_cons.php?busq_nombre="+busq_nombre;
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/contabilidad/utilizacion_fondos/db/sql_contabilidad_utf_cons.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cuenta U.Fondos','Nombre','Tipos','Comentarios'],
								colModel:[
									{name:'id',index:'id_aux', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cuenta_contable',index:'cuenta_contable', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipos',index:'tipos', width:50,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:50,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('contabilidad_vista_ut_fondos').value = ret.id;
									getObj('contabilidad_ut_fondos_db_cuenta_contable').value=ret.cuenta_contable;
									getObj('contabilidad_ut_fondos_db_nombre').value = ret.nombre;
									getObj('contabilidad_ut_fondos_db_comentario').value = ret.comentario;
									tipo=ret.tipos;
									getObj('contabilidad_ut_fondos_db_tipo').value=tipo;
									getObj('contabilidad_ut_fondos_db_btn_cancelar').style.display='';
									getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='';
									getObj('contabilidad_ut_fondos_db_btn_eliminar').style.display='';
									getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='none';

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
								sortname: 'id_aux',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
$("#contabilidad_ut_fondos_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_contabilidad_db_ut_fondos').jVal())
	{
		$.ajax (
		{
			url: "modulos/contabilidad/utilizacion_fondos/db/sql.actualizar.php",
			data:dataForm('form_contabilidad_db_ut_fondos'),
			type:'POST',
			cache: false,
			success: function(html)
			{//alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
				    getObj('contabilidad_ut_fondos_db_btn_cancelar').style.display='';
					getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='none';
					getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_db_ut_fondos');
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('contabilidad_ut_fondos_db_btn_cancelar').style.display='';
					getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='none';
					getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='';
					clearForm('form_contabilidad_db_ut_fondos');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#contabilidad_ut_fondos_db_btn_guardar").click(function() {
	if($('#form_contabilidad_db_ut_fondos').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/utilizacion_fondos/db/sql.registrar.php",
			data:dataForm('form_contabilidad_db_ut_fondos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				//alert(html);
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_contabilidad_db_ut_fondos');
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_contabilidad_db_ut_fondos');
					}
					else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					
				}
			
			}
		});
	}
});

function consulta_automatica_utf()
{
	$.ajax({
			url:"modulos/contabilidad/utilizacion_fondos/db/sql_grid_utf_cod.php",
            data:dataForm('form_contabilidad_db_ut_fondos'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;	
				//alert(html);
				if((recordset)&&(recordset!='vacio'))
				{
					recordset = recordset.split("*");
					getObj('contabilidad_vista_ut_fondos').value=recordset[0];
					getObj('contabilidad_ut_fondos_db_nombre').value=recordset[2];
					getObj('contabilidad_ut_fondos_db_tipo').value=recordset[3];
					getObj('contabilidad_ut_fondos_db_comentario').value=recordset[4];
					getObj('contabilidad_ut_fondos_db_btn_eliminar').style.display='';
					getObj('contabilidad_ut_fondos_db_btn_cancelar').style.display='';
					getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='';
					getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='none';


				}
				else
				{
					getObj('contabilidad_vista_ut_fondos').value="";
					getObj('contabilidad_ut_fondos_db_nombre').value="";
					getObj('contabilidad_ut_fondos_db_tipo').value="";
					getObj('contabilidad_ut_fondos_db_comentario').value="";
				}
				
			 }
		});	 

}
$("#tesoreria_moneda_db_btn_consultar_moneda").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/moneda/db/grid_moneda.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Impuesto', modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/tesoreria/moneda/db/cmb.sql.organismo.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Organismo'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'organismo',index:'organismo', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#contabilidad_ut_fondos_db_btn_eliminar").click(function() {
	if($('#form_contabilidad_db_ut_fondos').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/contabilidad/utilizacion_fondos/db/sql.eliminar.php",
			data:dataForm('form_contabilidad_db_ut_fondos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					clearForm('form_contabilidad_db_ut_fondos');
					limpiar_utf();
				}
				else if (html=="ExisteRelacion")
				{

				setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);					
				}			
			}
		});
	}
});

$("#contabilidad_ut_fondos_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='';
	getObj('contabilidad_ut_fondos_db_btn_eliminar').style.display='none';
	getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='none';
	getObj('contabilidad_ut_fondos_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_db_ut_fondos');
});
function limpiar_utf()
{
	setBarraEstado("");
	getObj('contabilidad_ut_fondos_db_btn_guardar').style.display='';
	getObj('contabilidad_ut_fondos_db_btn_eliminar').style.display='none';
	getObj('contabilidad_ut_fondos_db_btn_actualizar').style.display='none';
	getObj('contabilidad_ut_fondos_db_btn_consultar').style.display='';
	clearForm('form_contabilidad_db_ut_fondos');
}

</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#contabilidad_ut_fondos_db_nombre').alpha({allow:' áéíóúÄÉÍÓÚ. '});
$('#contabilidad_ut_fondos_db_cuenta_auxiliar').numeric({});
$('#contabilidad_ut_fondos_db_cuenta_contable').numeric({});
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
	<img id="contabilidad_ut_fondos_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="contabilidad_ut_fondos_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_ut_fondos_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="contabilidad_ut_fondos_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>		
	<img id="contabilidad_ut_fondos_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
<form method="post" id="form_contabilidad_db_ut_fondos" name="form_contabilidad_db_ut_fondos">
<input type="hidden"  id="contabilidad_vista_ut_fondos" name="contabilidad_vista_ut_fondos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Utilizaci&oacute;n De Fondos </th>
	</tr>
	<tr>
		<th>Cuenta Utilizaci&oacute;n Fondos:</th>
		 <td>
		    	<input type="text" name="contabilidad_ut_fondos_db_cuenta_contable" id="contabilidad_ut_fondos_db_cuenta_contable"  size='12' maxlength="12" onblur="consulta_automatica_utf()" onchange="consulta_automatica_utf()"
				message="Introduzca la cuenta contable" 
					jval="{valid:/^[0-9]{1,12}$/, message:'Codigo Invalido', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
	</tr>
	<tr>
		<th>Nombre:		</th>	
	    <td>	
		<input name="contabilidad_ut_fondos_db_nombre" type="text" id="contabilidad_ut_fondos_db_nombre"   value="" size="40" maxlength="60" message="Introduzca un Nombre de la cuenta . Ejem: 'Banco Bolívar' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />	   </td>
   </tr>
    <tr>
		<th>Tipo:</th>
		<td><select name="contabilidad_ut_fondos_db_tipo" id="contabilidad_ut_fondos_db_tipo">
				<option value="0" >DETALLE</option>
				<option value="1">TOTAL</option>
				<option  value="2">AUTOMÁTICA</option>
				<option value="3">ENCABEZADO</option>
			</select>												
		</td>
	</tr>
    
    
	<tr>
		<th>Comentarios:</th>
		<td><textarea  name="contabilidad_ut_fondos_db_comentario" cols="60" id="contabilidad_ut_fondos_db_comentario" message="Introduzca una Observación. Ejem:'Esta cuenta es ...' " style="width:422px"></textarea>		</td>
	</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
</table>
<input   type="hidden" name="contabilidad_ut_fondos_db_id_aux"  id="contabilidad_ut_fondos_db_id_aux" />
</form>