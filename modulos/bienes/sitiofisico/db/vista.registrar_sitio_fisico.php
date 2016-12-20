<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------


$("#sitio_fisico_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/sitiofisico/db/vista.grid_sitio_fisico_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Sitio Físico', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_unidad= jQuery("#sitio_fisico_db_nombre_uni").val(); 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?busq_unidad="+busq_unidad+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_db_nombre_uni").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#sitio_fisico_db_nombre").keypress(function(key)
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
							var busq_unidad= jQuery("#sitio_fisico_db_nombre_uni").val();
							var busq_nombre= jQuery("#sitio_fisico_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?busq_unidad="+busq_unidad+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/sitiofisico/db/sql_sitio_fisico_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Sitio Fisico','Comentario','id_unidad','Unidad'],
								colModel:[
									{name:'id_sitio_fisico',index:'id_sitio_fisico', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad',index:'unidad', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sitio_fisico_db_id_sitio_fisico').value = ret.id_sitio_fisico;
									getObj('sitio_fisico_db_id_unidad_ejecutora').value = ret.id_unidad_ejecutora;
									getObj('sitio_fisico_db_nombre_unidad').value = ret.unidad;					
									getObj('sitio_fisico_db_nombre_sitio').value = ret.nombre;
									getObj('sitio_fisico_db_comentario').value = ret.comentarios;
									getObj('sitio_fisico_db_btn_guardar').style.display = 'none';
									getObj('sitio_fisico_db_btn_actualizar').style.display = '';
									getObj('sitio_fisico_db_btn_eliminar').style.display = '';
		
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#valor_impuesto_db_nombre_uni").focus();
								$('#sitio_fisico_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_sitio_fisico',
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


$("#sitio_fisico_db_btn_guardar").click(function() {
	if ($('#form_db_sitio_fisico').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/sitiofisico/db/sql.registrar_sitio_fisico.php",
			data:dataForm('form_db_sitio_fisico'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('sitio_fisico_db_id_unidad_ejecutora').value='';
					getObj('sitio_fisico_db_nombre_unidad').value='';
					getObj('sitio_fisico_db_nombre_sitio').value = '';
					getObj('sitio_fisico_db_comentario').value = '';
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

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#sitio_fisico_db_btn_actualizar").click(function() {
	if ($('#form_db_sitio_fisico').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/sitiofisico/db/sql.actualizar_sitio_fisico.php",
			data:dataForm('form_db_sitio_fisico'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('sitio_fisico_db_id_sitio_fisico').value = '';
					getObj('sitio_fisico_db_id_unidad_ejecutora').value='';
					getObj('sitio_fisico_db_nombre_unidad').value='';
					getObj('sitio_fisico_db_nombre_sitio').value = '';
					getObj('sitio_fisico_db_comentario').value = '';
					getObj('sitio_fisico_db_btn_actualizar').style.display = 'none';
					getObj('sitio_fisico_db_btn_eliminar').style.display = 'none';
					getObj('sitio_fisico_db_btn_guardar').style.display = '';
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
//----------------------------------------------------------------
$("#sitio_fisico_db_btn_eliminar").click(function() {
	if ($('#form_db_sitio_fisico').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/sitiofisico/db/sql.eliminar_sitio_fisico.php",
			data:dataForm('form_db_sitio_fisico'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sitio_fisico_db_id_sitio_fisico').value = '';
					getObj('sitio_fisico_db_id_unidad_ejecutora').value='';
					getObj('sitio_fisico_db_nombre_unidad').value='';
					getObj('sitio_fisico_db_nombre_sitio').value = '';
					getObj('sitio_fisico_db_comentario').value = '';
					getObj('sitio_fisico_db_btn_actualizar').style.display = 'none';
					getObj('sitio_fisico_db_btn_eliminar').style.display = 'none';
					getObj('sitio_fisico_db_btn_guardar').style.display = '';
//					clearForm('form_db_valor_impuesto');
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
$("#sitio_fisico_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/sitiofisico/db/vista.grid_uni_eje_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidad Ejecutora', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#sitio_fisico_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#sitio_fisico_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#sitio_fisico_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/sitiofisico/db/sql_uni_eje_nom.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/sitiofisico/db/sql_uni_eje_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Unidad','Comentario'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('sitio_fisico_db_id_unidad_ejecutora').value=ret.id_unidad_ejecutora;
									getObj('sitio_fisico_db_nombre_unidad').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#sitio_fisico_db_nombre").focus();
								$('#sitio_fisico_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});





//
//

//
//
// ******************************************************************************
$("#sitio_fisico_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('sitio_fisico_db_id_sitio_fisico').value = '';
getObj('sitio_fisico_db_id_unidad_ejecutora').value = '';
getObj('sitio_fisico_db_nombre_unidad').value = '';
getObj('sitio_fisico_db_nombre_sitio').value = '';
getObj('sitio_fisico_db_comentario').value = '';
getObj('sitio_fisico_db_btn_actualizar').style.display = 'none';
getObj('sitio_fisico_db_btn_eliminar').style.display = 'none';
getObj('sitio_fisico_db_btn_guardar').style.display = '';
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');
//------------------------- funciones emergentes automaticas
/// ------------------------renglon----------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------------------------------------


/*------------------- Fin de la Su-Mascara jquery_moneda ---------------------*/
//$('#cargar_cotizacion_pr_numero_cotizacion').change(consulta_automatica_ncotizacion)
//$('#cargar_cotizacion_pr_renglon_codigo').change(consulta_automatica_renglon)
//Validacion de los campos
$('#sitio_fisico_db_nombre_unidad').alpha({allow:' '});
$('#sitio_fisico_db_nombre_sitio').alpha({allow:' '});
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
	<img id="sitio_fisico_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="sitio_fisico_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sitio_fisico_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="sitio_fisico_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="sitio_fisico_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_sitio_fisico" id="form_db_sitio_fisico">
<input type="hidden" name="sitio_fisico_db_id_sitio_fisico" id="sitio_fisico_db_id_sitio_fisico" />
<input type="hidden" name="sitio_fisico_db_fechact" id="sitio_fisico_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="sitio_fisico_db_id_unidad_ejecutora" id="sitio_fisico_db_id_unidad_ejecutora"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Sitio Fisico			</th>
	</tr>
    <tr>
			<th>Unidad</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="sitio_fisico_db_nombre_unidad" type="text"  id="sitio_fisico_db_nombre_unidad" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="sitio_fisico_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
    	<tr>
			<th>Nombre</th>
		  <td><input name="sitio_fisico_db_nombre_sitio" type="text"  id="sitio_fisico_db_nombre_sitio" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="sitio_fisico_db_comentario" id="sitio_fisico_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>