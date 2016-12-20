<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------


$("#tipo_concepto_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/tipo_concepto/db/vista.grid_tipo_concepto_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente Tipo de Concepto', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_concepto_db_descripcion").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/tipo_concepto/db/sql_tipo_concepto_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_concepto_db_descripcion").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_concepto_db_descripcion").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/tipo_concepto/db/sql_tipo_concepto_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/tipo_concepto/db/sql_tipo_concepto_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Concepto','Comentario'],
								colModel:[
									{name:'id_tipo_concepto',index:'id_tipo_concepto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:100,sortable:false,resizable:false},
									{name:'observacion',index:'observacion', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tipo_concepto_db_id_tipo_concepto').value = ret.id_tipo_concepto;
									getObj('tipo_concepto_db_descripcion_tipo_concepto').value = ret.descripcion;					
									getObj('tipo_concepto_db_comentario').value = ret.observacion;
									getObj('tipo_concepto_db_btn_guardar').style.display = 'none';
									getObj('tipo_concepto_db_btn_actualizar').style.display = '';
									getObj('tipo_concepto_db_btn_eliminar').style.display = '';
		
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_concepto_db_descripcion").focus();
								$('#tipo_concepto_db_descripcion').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_concepto',
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


$("#tipo_concepto_db_btn_guardar").click(function() {
	if ($('#form_db_tipo_concepto').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_concepto/db/sql.registrar_tipo_concepto.php",
			data:dataForm('form_db_tipo_concepto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('tipo_concepto_db_descripcion_tipo_concepto').value='';
					getObj('tipo_concepto_db_comentario').value = '';
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

//----------------------------------------------------------------
//-----------------------Actualizar-------------------------------
$("#tipo_concepto_db_btn_actualizar").click(function() {
	if ($('#form_db_tipo_concepto').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_concepto/db/sql.actualizar_tipo_concepto.php",
			data:dataForm('form_db_tipo_concepto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('tipo_concepto_db_id_tipo_concepto').value = '';
					getObj('tipo_concepto_db_descripcion_tipo_concepto').value='';
					getObj('tipo_concepto_db_comentario').value = '';
					getObj('tipo_concepto_db_btn_actualizar').style.display = 'none';
					getObj('tipo_concepto_db_btn_eliminar').style.display = 'none';
					getObj('tipo_concepto_db_btn_guardar').style.display = '';
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
//----------------------------------------------------------------
$("#tipo_concepto_db_btn_eliminar").click(function() {
	if ($('#form_db_tipo_concepto').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/tipo_concepto/db/sql.eliminar_tipo_concepto.php",
			data:dataForm('form_db_tipo_concepto'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('tipo_concepto_db_id_tipo_concepto').value = '';
					getObj('tipo_concepto_db_descripcion_tipo_concepto').value = '';
					getObj('tipo_concepto_db_comentario').value = '';
					getObj('tipo_concepto_db_btn_actualizar').style.display = 'none';
					getObj('tipo_concepto_db_btn_eliminar').style.display = 'none';
					getObj('tipo_concepto_db_btn_guardar').style.display = '';
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
$("#tipo_concepto_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('tipo_concepto_db_id_tipo_concepto').value = '';
getObj('tipo_concepto_db_descripcion_tipo_concepto').value = '';
getObj('tipo_concepto_db_comentario').value = '';
getObj('tipo_concepto_db_btn_actualizar').style.display = 'none';
getObj('tipo_concepto_db_btn_eliminar').style.display = 'none';
getObj('tipo_concepto_db_btn_guardar').style.display = '';
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
$('#tipo_concepto_db_descripcion_tipo_concepto').alpha({allow:' '});
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
	<img id="tipo_concepto_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="tipo_concepto_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="tipo_concepto_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="tipo_concepto_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="tipo_concepto_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_tipo_concepto" id="form_db_tipo_concepto">
<input type="hidden" name="tipo_concepto_db_id_tipo_concepto" id="tipo_concepto_db_id_tipo_concepto" />
<input type="hidden" name="tipo_concepto_db_fechact" id="tipo_concepto_db_fechact" value="<?php echo date("d-m-Y");?>"/>

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Tipo de Concepto</th>
	</tr>
    	<tr>
			<th>Descripción</th>
		  <td><input name="tipo_concepto_db_descripcion_tipo_concepto" type="text"  id="tipo_concepto_db_descripcion_tipo_concepto" maxlength="60" size="30" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="tipo_concepto_db_comentario" id="tipo_concepto_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este tipo de concepto es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>