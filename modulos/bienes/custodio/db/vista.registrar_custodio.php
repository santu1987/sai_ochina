<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------

$("#custodio_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/custodio/db/vista.grid_custodio_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Custodio', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#custodio_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/db/sql_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#custodio_db_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#custodio_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/custodio/db/sql_custodio_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/bienes/custodio/db/sql_custodio_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Custodio','Observacion'],
								colModel:[
									{name:'id_custodio',index:'id_custodio', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:50,sortable:false,resizable:false},
									{name:'nombre',index:'codigo_impuesto', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('custodio_db_id_custodio').value=ret.id_custodio;
									var cedula = ret.cedula;
									
									if(cedula.substr(0,1)=='V')
										getObj('custodio_db_tipo_custodio').selectedIndex=0;
									if(cedula.substr(0,1)=='E')
										getObj('custodio_db_tipo_custodio').selectedIndex=1;
									if(cedula.substr(0,1)=='J')	
										getObj('custodio_db_tipo_custodio').selectedIndex=2;
getObj('custodio_db_cedula_custodio').value=cedula.substr(2,cedula.length);
									getObj('custodio_db_nombre_custodio').value=ret.nombre;
									getObj('custodio_db_comentario').value=ret.comentarios;
									getObj('custodio_db_btn_guardar').style.display = 'none';
									getObj('custodio_db_btn_actualizar').style.display = '';
									getObj('custodio_db_btn_eliminar').style.display='';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#custodio_db_nombre").focus();
								$('#custodio_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_custodio',
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


$("#custodio_db_btn_guardar").click(function() {
	if ($('#form_db_custodio').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/custodio/db/sql.registrar_custodio.php",
			data:dataForm('form_db_custodio'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('custodio_db_id_custodio').value='';
					getObj('custodio_db_tipo_custodio').selectedIndex=0;
					getObj('custodio_db_cedula_custodio').value='';
					getObj('custodio_db_nombre_custodio').value='';
					getObj('custodio_db_comentario').value = '';
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
//----------------------Actualizar--------------------------------
$("#custodio_db_btn_actualizar").click(function() {
	if ($('#form_db_custodio').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/custodio/db/sql.actualizar_custodio.php",
			data:dataForm('form_db_custodio'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('custodio_db_id_custodio').value='';
					getObj('custodio_db_tipo_custodio').selectedIndex=0;
					getObj('custodio_db_cedula_custodio').value='';
					getObj('custodio_db_nombre_custodio').value='';
					getObj('custodio_db_comentario').value = '';
					getObj('custodio_db_btn_actualizar').style.display='none';
					getObj('custodio_db_btn_eliminar').style.display='none';
					getObj('custodio_db_btn_guardar').style.display='';
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
$("#custodio_db_btn_eliminar").click(function() {
	if ($('#form_db_custodio').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/custodio/db/sql.eliminar_custodio.php",
			data:dataForm('form_db_custodio'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('custodio_db_id_custodio').value='';
					getObj('custodio_db_tipo_custodio').selectedIndex=0;
					getObj('custodio_db_cedula_custodio').value='';
					getObj('custodio_db_nombre_custodio').value='';
					getObj('custodio_db_comentario').value = '';
					getObj('custodio_db_btn_actualizar').style.display='none';
					getObj('custodio_db_btn_eliminar').style.display='none';
					getObj('custodio_db_btn_guardar').style.display='';
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

//
//
// ******************************************************************************

$("#custodio_db_btn_cancelar").click(function() {
//clearForm('form_db_custodio');
getObj('custodio_db_nombre_custodio').focus();
getObj('custodio_db_id_custodio').value='';
getObj('custodio_db_tipo_custodio').selectedIndex=0;
getObj('custodio_db_cedula_custodio').value='';
getObj('custodio_db_nombre_custodio').value='';
getObj('custodio_db_comentario').value='';
getObj('custodio_db_btn_actualizar').style.display='none';
getObj('custodio_db_btn_eliminar').style.display='none';
getObj('custodio_db_btn_guardar').style.display='';
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
$('#custodio_db_cedula_custodio').numeric({allow:' '});
$('#custodio_db_nombre_custodio').alpha({allow:' '});
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
	<img id="custodio_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img style="display:none" id="custodio_db_btn_eliminar" class="btn_eliminar" src="imagenes/null.gif"/>
	<img id="custodio_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="custodio_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="custodio_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_custodio" id="form_db_custodio">
<input type="hidden" name="custodio_db_fechact" id="custodio_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="custodio_db_id_custodio" id="custodio_db_id_custodio"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Custodio			</th>
	</tr>

    	<tr>
			<th>Cedula</th>
		  <td>
		    <label><select name="custodio_db_tipo_custodio" id="custodio_db_tipo_custodio" style="width:50px; min-width:35px;">
				  <option>V-</option>
                  <option>E-</option>
				  <option>J-</option>
          </select></label>
		    <input name="custodio_db_cedula_custodio" type="text" id="custodio_db_cedula_custodio" maxlength="9" size="10" message="Introduzca la Cedula del Custodio." jval="{valid:/^[0-9]{1,9}$/, message:'Cedula Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Cedula: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Nombre</th>
		  <td><input name="custodio_db_nombre_custodio" type="text" id="custodio_db_nombre_custodio" maxlength="60" size="30" message="Introduzca el Nombre del Custodio. Ejem: 'Juan'" jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="custodio_db_comentario" id="custodio_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este custodio es...'"></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>