<? if (!$_SESSION) session_start();
?>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="utilidades/jquery.meiomask.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script>
var dialog;
//----------------------------------------------------------------------------------------------------
$("#entrevista_db_btn_consulta_emergente_curriculum").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/entrevista/db/vista.grid_curriculum.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Curriculum', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#curriculum").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/entrevista/db/sql_curriculum.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#entrevista_db_nivele").change(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#curriculum").keypress(function(key)
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
							var busq_nombre= jQuery("#curriculum").val();
							var busq_nivele= jQuery("#entrevista_db_nivele").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/entrevista/db/sql_curriculum.php?busq_nombre="+busq_nombre+"&busq_nivele="+busq_nivele,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/entrevista/db/sql_curriculum.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Rama','C.I', 'Nombre','imagen','',''],
								colModel:[
									{name:'id_curriculum',index:'id_curriculum', width:50,sortable:false,resizable:false,hidden:true},
									{name:'rama',index:'rama', width:100,sortable:false,resizable:false},
									{name:'cedula_persona',index:'cedula_persona', width:100,sortable:false,resizable:false},
									{name:'nombre_persona',index:'nombre_persona', width:100,sortable:false,resizable:false},
									{name:'imagen',index:'imagen', width:100,sortable:false,hidden:true},
									{name:'nivel_ac',index:'nivel_ac',hidden:true},
									{name:'rama_ni',index:'rama_ni',hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('entrevista_db_id_curriculum').value=ret.id_curriculum;
									var imagen=ret.imagen;
									var src="imagenes/curriculos/"+imagen;
									getObj('imagen_curriculum').src=src;
									var curricu=ret.cedula_persona+" - "+ret.nombre_persona;
									getObj('entrevista_db_cedula').value=ret.cedula_persona;
									getObj('entrevista_db_nombre_entrevista').value=ret.nombre_persona;
									getObj('entrevista_db_nombre_nivel_aca').value=ret.nivel_ac;
									getObj('entrevista_db_nombre_ramani').value=ret.rama_ni;
									getObj('entrevista_db_curriculum').value=curricu;
									getObj('linea_curri1').style.display='';
									getObj('datos_nom').style.display='';
									getObj('datos_nom2').style.display='';
									getObj('linea_curri2').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#curriculum").focus();
								$('#curriculum').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_curriculum',
								viewrecords: true,
								sortorder: "asc"
						
			});	
		}
	});

$("#entrevista_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/entrevista/db/vista.grid_entrevista_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Entrevistados', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#entrevista_db_nivel").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/entrevista/db/sql_entrevista_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#entrevista_db_nivel").change(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#entrevista_db_ramas").keypress(function(key)
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
							var busq_nombre= jQuery("#entrevista_db_nivel").val();
							var busq_rama= jQuery("#entrevista_db_ramas").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/entrevista/db/sql_entrevista_nombre.php?busq_nombre="+busq_nombre+"&busq_rama="+busq_rama,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/entrevista/db/sql_entrevista_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Entrevistado','','','','Fecha Entrevista','',''],
								colModel:[
									{name:'id_entrevista',index:'id_entrevista', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula_entre',index:'cedula_entre', width:50,sortable:false,resizable:false},
									{name:'entrevistado',index:'entrevistado', width:50,sortable:false,resizable:false},
									{name:'observaciones',index:'observaciones', width:50,hidden:true},
									{name:'imagenw',index:'imagenw', width:50,hidden:true},
									{name:'id_curriculos',index:'id_curriculos', width:50,hidden:true},
									{name:'fech',index:'fech', width:50},
									{name:'nivele',index:'nivele', width:50,hidden:true},
									{name:'ramani',index:'ramani', width:50,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('entrevista_db_id_entrevista').value=ret.id_entrevista;
									var imagenw=ret.imagenw;
									var src="imagenes/curriculos/"+imagenw;
									getObj('imagen_curriculum').src=src;
									var curricul=ret.cedula_entre+"---"+ret.entrevistado;
									getObj('entrevista_db_cedula').value=ret.cedula_entre;
									getObj('entrevista_db_nombre_entrevista').value=ret.entrevistado;
									getObj('entrevista_db_nombre_nivel_aca').value=ret.nivele;
									getObj('entrevista_db_nombre_ramani').value=ret.ramani;
									getObj('entrevista_db_id_curriculum').value=ret.id_curriculos;
									getObj('entrevista_db_comentario').value=ret.observaciones;
									
									getObj('entrevista_db_curriculum').value=curricul;
									getObj('entrevista_db_fecha').value=ret.fech;
									getObj('linea_curri1').style.display='';
									getObj('datos_nom').style.display='';
									getObj('datos_nom2').style.display='';
									getObj('linea_curri2').style.display='';
									getObj('entrevista_db_btn_guardar').style.display = 'none';
									getObj('entrevista_db_btn_actualizar').style.display = '';
									getObj('entrevista_db_btn_eliminar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#entrevista_db_nivel").focus();
								//$('#entrevista_db_nivel').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_entrevista',
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


$("#entrevista_db_btn_guardar").click(function() {
	if ($('#form_db_entrevista').jVal()){	
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/entrevista/db/sql.registrar_entrevista.php",
			data:dataForm('form_db_entrevista'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('entrevista_db_id_entrevista').value='';
					getObj('entrevista_db_id_curriculum').value='';
					getObj('entrevista_db_cedula').value='';
					getObj('entrevista_db_nombre_entrevista').value='';
					getObj('entrevista_db_curriculum').value='';
					getObj('entrevista_db_comentario').value = '';
					getObj('entrevista_db_fecha').value = '<? echo date("d-m-Y") ?>';
					getObj('linea_curri1').style.display='none';
					getObj('linea_curri2').style.display='none';
					getObj('datos_nom').style.display='none';
					getObj('datos_nom2').style.display='none';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error")
				{
					setBarraEstado(mensaje[19],true,true);
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


//----------------------------------------------------------------
//----------------------Actualizar--------------------------------
$("#entrevista_db_btn_actualizar").click(function() {
	if ($('#form_db_entrevista').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/entrevista/db/sql.actualizar_entrevista.php",
			data:dataForm('form_db_entrevista'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('entrevista_db_id_entrevista').value='';
					getObj('entrevista_db_id_curriculum').value='';
					getObj('entrevista_db_cedula').value='';
					getObj('entrevista_db_nombre_entrevista').value='';
					getObj('entrevista_db_curriculum').value='';
					getObj('entrevista_db_comentario').value = '';
					getObj('entrevista_db_fecha').value = '<? echo date("d-m-Y") ?>';
					getObj('entrevista_db_btn_actualizar').style.display='none';
					getObj('entrevista_db_btn_eliminar').style.display='none';
					getObj('linea_curri1').style.display='none';
					getObj('linea_curri2').style.display='none';
					getObj('datos_nom').style.display='none';
					getObj('datos_nom2').style.display='none';
					getObj('entrevista_db_btn_guardar').style.display='';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Error fecha"){
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />La fecha del valor del impuesto \n tiene que ser mayor que la fecha actual </p></div>",true,true);
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
$("#entrevista_db_btn_eliminar").click(function() {
	if ($('#form_db_entrevista').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/entrevista/db/sql.eliminar_entrevista.php",
			data:dataForm('form_db_entrevista'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('entrevista_db_id_entrevista').value='';
					getObj('entrevista_db_id_curriculum').value='';
					getObj('entrevista_db_cedula').value='';
					getObj('entrevista_db_nombre_entrevista').value='';
					getObj('entrevista_db_curriculum').value='';
					getObj('entrevista_db_comentario').value = '';
					getObj('entrevista_db_fecha').value = '<? echo date("d-m-Y") ?>';
					getObj('entrevista_db_btn_actualizar').style.display='none';
					getObj('entrevista_db_btn_eliminar').style.display='none';
					getObj('linea_curri1').style.display='none';
					getObj('linea_curri2').style.display='none';
					getObj('datos_nom').style.display='none';
					getObj('datos_nom2').style.display='none';
					getObj('entrevista_db_btn_guardar').style.display='';
//					clearForm('form_db_valor_impuesto');
				}
				else if (html=="Relacion_Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
//					clearForm('form_db_valor_impuesto');
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
/* $("#entrevista_db_btn_consulta_emergente_curriculum").click(function(){
	alert('Me Trae el Curriculum');
	getObj('linea_curri1').style.display='';
	getObj('linea_curri2').style.display='';
}); */
//
//
// ******************************************************************************

$("#entrevista_db_btn_cancelar").click(function() {
//clearForm('form_db_entrevista');
getObj('entrevista_db_nombre_entrevista').focus();
getObj('entrevista_db_id_entrevista').value='';
getObj('entrevista_db_id_curriculum').value='';
getObj('entrevista_db_cedula').value='';
getObj('entrevista_db_nombre_entrevista').value='';
getObj('entrevista_db_curriculum').value='';
getObj('entrevista_db_comentario').value = '';
getObj('entrevista_db_fecha').value = '<? echo date("d-m-Y") ?>';
getObj('entrevista_db_btn_actualizar').style.display='none';
getObj('entrevista_db_btn_eliminar').style.display='none';
getObj('linea_curri1').style.display='none';
getObj('linea_curri2').style.display='none';
getObj('datos_nom').style.display='none';
getObj('datos_nom2').style.display='none';
getObj('entrevista_db_btn_guardar').style.display='';
setBarraEstado("");
});



//Validacion de los campos
$('#entrevista_db_cedula_entrevista').numeric({allow:' '});
$('#entrevista_db_nombre_entrevista').alpha({allow:' '});
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
	<img id="entrevista_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="entrevista_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="entrevista_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="entrevista_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="entrevista_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_entrevista" id="form_db_entrevista">
<input type="hidden" name="entrevista_db_id_entrevista" id="entrevista_db_id_entrevista"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="4">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Entrevistas		</th>
	</tr>
        <tr>
          <th width="145">Curriculum:</th>
          <td colspan="3">
          <ul class="input_con_emergente">
          <li>
            <input name="entrevista_db_curriculum" type="text"  id="entrevista_db_curriculum" maxlength="30" size="30" readonly="true" message="Seleccione el Curriculum" />
            <span class="btn_consulta_emergente">
              <input type="hidden" name="entrevista_db_id_curriculum" id="entrevista_db_id_curriculum">
            </span></li>
          <li id="entrevista_db_btn_consulta_emergente_curriculum" class="btn_consulta_emergente"></li>
          </ul>
          </td>
        </tr>
        <tr id="datos_nom2" style="display:none">
          <th>Nivel Academico:</th>
          <td><input name="entrevista_db_nombre_nivel_aca" type="text" id="entrevista_db_nombre_nivel_aca" size="30" maxlength="60" readonly="readonly" style="background-color:#FFF; border:none;" /></td>
          <th>Rama:</th>
          <td><input name="entrevista_db_nombre_ramani" type="text" id="entrevista_db_nombre_ramani" size="30" maxlength="60" readonly="readonly" style="background-color:#FFF; border:none;" /></td>
        </tr>
        <tr id="datos_nom" style="display:none">
          <th>C.I:</th>
          <td width="70"><input name="entrevista_db_cedula" type="text" id="entrevista_db_cedula"  size="10" maxlength="9" readonly="readonly" width="150px" 
					 style="background-color:#FFF; border:none; width:70px" /></td>
          <th width="54">Nombre</th>
          <td width="452"><input name="entrevista_db_nombre_entrevista" type="text" id="entrevista_db_nombre_entrevista" size="30" maxlength="60" readonly="readonly" style="background-color:#FFF; border:none;" /></td>
        </tr>
        <tr >
          <th>Fecha:</th>
          <td colspan="3">
          <input name="entrevista_db_fecha" type="text" size="10" readonly="readonly" id="entrevista_db_fecha" value="<?php echo date("d-m-Y")?>"/>
          <label>
            <input type="button" name="fechass" id="fechass" value="..." />
          </label>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "entrevista_db_fecha",      
						ifFormat       :    "%d-%m-%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fechass",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script></td>
        </tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td colspan="3"><label>
		    <textarea name="entrevista_db_comentario" id="entrevista_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'El Entrevistado Posee...'"></textarea>
		  </label></td>
		</tr>
        <tr>
          <td align="center" class="titulo_td" id="linea_curri1" style="display:none" colspan="4">Curriculum Vitae          </td>
        </tr>
        <tr>
          <th id="linea_curri2" style="display:none" colspan="4" align="center">
          <div id="curri" style="width:500px; height:500px; border:#3CF 1px ridge; margin-left:50px;"><img id="imagen_curriculum" width="500px;" height="500px" /></div>
          </th>
        </tr>
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>