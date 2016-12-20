<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------
$("#mayor_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/mayor/db/vista.grid_mayor.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Mayor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#mayor_db_nombre_mayor").val(); 

					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mayor/db/sql_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#mayor_db_nombre_mayor").keypress(function(key)
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
							var busq_nombre= jQuery("#mayor_db_nombre_mayor").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/mayor/db/sql_mayor_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");	
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
								url:'modulos/bienes/mayor/db/sql_mayor_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Mayor','Comentario'],
								colModel:[
									{name:'id_mayor',index:'id_mayor', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('mayor_db_id_mayor').value = ret.id_mayor;
									getObj('mayor_db_nombre').value = ret.nombre; 
									getObj('mayor_db_comentario').value = ret.comentarios;
									getObj('mayor_db_btn_guardar').style.display = 'none';
									getObj('mayor_db_btn_actualizar').style.display = '';
									getObj('mayor_db_btn_eliminar').style.display = '';
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#mayor_db_nombre_mayor").focus();
								$('#mayor_db_nombre_mayor').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_mayor',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//

//
//----------------------------------------------------------------
$("#mayor_db_btn_guardar").click(function() {
	if ($('#form_db_mayor').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/mayor/db/sql.registrar_mayor.php",
			data:dataForm('form_db_mayor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('mayor_db_nombre').value='';
					getObj('mayor_db_comentario').value='';
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
$("#mayor_db_btn_actualizar").click(function() {
	if ($('#form_db_mayor').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/mayor/db/sql.actualizar_mayor.php",
			data:dataForm('form_db_mayor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('mayor_db_id_mayor').value='';
					getObj('mayor_db_nombre').value='';
					getObj('mayor_db_comentario').value='';
					getObj('mayor_db_btn_actualizar').style.display='none';
					getObj('mayor_db_btn_eliminar').style.display = 'none';
					getObj('mayor_db_btn_guardar').style.display='';
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

$("#mayor_db_btn_eliminar").click(function() {
	if ($('#form_db_mayor').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/mayor/db/sql.eliminar_mayor.php",
			data:dataForm('form_db_mayor'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('mayor_db_id_mayor').value='';
					getObj('mayor_db_nombre').value='';
					getObj('mayor_db_comentario').value='';
					getObj('mayor_db_btn_actualizar').style.display='none';
					getObj('mayor_db_btn_eliminar').style.display = 'none';
					getObj('mayor_db_btn_guardar').style.display='';
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
// ******************************************************************************
$("#mayor_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('mayor_db_id_mayor').value='';
getObj('mayor_db_nombre').value='';
getObj('mayor_db_comentario').value = '';
getObj('mayor_db_btn_actualizar').style.display = 'none';
getObj('mayor_db_btn_eliminar').style.display = 'none';
getObj('mayor_db_btn_guardar').style.display = '';
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');


$('#mayor_db_nombre').alpha({allow:' '});
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
	<img id="mayor_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="mayor_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="mayor_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="mayor_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="mayor_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_mayor" id="form_db_mayor">
<input type="hidden" name="mayor_db_id_mayor" id="mayor_db_id_mayor" />
<input type="hidden" name="mayor_db_fechact" id="mayor_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Mayor			</th>
	</tr>
    	<tr>
			<th>Nombre:</th>
		  <td><input name="mayor_db_nombre" type="text"  id="mayor_db_nombre" maxlength="60" size="30" message="Introduzca un Nombre." jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n:</th>
		  <td><label>
		    <textarea name="mayor_db_comentario" id="mayor_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn."></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>