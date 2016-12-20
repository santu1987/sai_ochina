<? if (!$_SESSION) session_start();
?>
<script>
/* function vehiculo(){
	if (document.getElementById('vehiculo_1').checked=true){
		document.getElementById('val_vehiculo').value=1;
	}
	if (document.getElementById('vehiculo_2').checked=true){
		document.getElementById('val_vehiculo').value=2;
	}
} */
$("#vehiculo_1").click(function(){
				document.getElementById('val_vehiculo').value=1;
				   });
$("#vehiculo_2").click(function(){
				document.getElementById('val_vehiculo').value=2;
				   });
var dialog;
//----------------------------------------------------------------------------------------------------
$("#tipo_bienes_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/tipobien/db/vista.grid_tipo_bienes.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Bienes', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_bienes_db_nombre").val(); 
					var busq_mayor= jQuery("#tipo_bienes_db_may").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/db/sql_tipo_bienes_nombre.php?busq_nombre="+busq_nombre+"&busq_mayor="+busq_mayor,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_bienes_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#tipo_bienes_db_may").change(function()
				{
						//if(key.keyCode==27){this.close();}
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
							var busq_nombre= jQuery("#tipo_bienes_db_nombre").val();
							var busq_mayor= jQuery("#tipo_bienes_db_may").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/db/sql_tipo_bienes_nombre.php?busq_nombre="+busq_nombre+"&busq_mayor="+busq_mayor,page:1}).trigger("reloadGrid");	
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
								url:'modulos/bienes/tipobien/db/sql_tipo_bienes_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo de Bienes','Comentario','id_mayor','Mayor','Vida Util (Meses)',''],
								colModel:[
									{name:'id_tipo_bienes',index:'id_tipo_bienes', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,hidden:true},
									{name:'id_mayor',index:'id_mayor', width:100,sortable:false,resizable:false,hidden:true},
									{name:'mayor',index:'mayor', width:100,sortable:false,resizable:false},
									{name:'vida_util_tb',index:'vida_util_tb', width:100,sortable:false,resizable:false},
									{name:'vehiculo',index:'vehiculo', width:100,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tipo_bienes_db_id_tipo_bienes').value = ret.id_tipo_bienes;
									getObj('tipo_bienes_db_id_mayor').value = ret.id_mayor;
									getObj('tipo_bienes_db_nombre_mayor').value = ret.mayor; 
									getObj('tipo_bienes_db_nombre_tipo_bienes').value = ret.nombre;
									getObj('vida_util_tb').value = ret.vida_util_tb;
									var val_vehi=ret.vehiculo;
									if(val_vehi==1){
										getObj('val_vehiculo').value=val_vehi;
										getObj('vehiculo_1').checked=true;
									}
									else{
										getObj('val_vehiculo').value=val_vehi;
										getObj('vehiculo_2').checked=true;
									}
									getObj('tipo_bienes_db_comentario').value = ret.comentarios;
									getObj('tipo_bienes_db_btn_guardar').style.display = 'none';
									getObj('tipo_bienes_db_btn_actualizar').style.display = '';
									getObj('tipo_bienes_db_btn_eliminar').style.display = '';
									getObj('vehiculo_1').checked=false;
									getObj('vehiculo_2').checked=false;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#tipo_bienes_db_nombre").focus();
								$('#tipo_bienes_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_bienes',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
//

$("#tipo_bienes_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/bienes/tipobien/db/vista.grid_may.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Mayor', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_bienes_db_nombre_may").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/db/sql_may_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_bienes_db_nombre_may").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_bienes_db_nombre_may").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/bienes/tipobien/db/sql_may_nombre.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");	
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
								url:'modulos/bienes/tipobien/db/sql_may_nombre.php?nd='+nd,
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
									getObj('tipo_bienes_db_id_mayor').value = ret.id_mayor;
									getObj('tipo_bienes_db_nombre_mayor').value = ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_bienes_db_nombre_may").focus();
								$('#tipo_bienes_db_nombre_may').alpha({allow:' '});
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
//
//----------------------------------------------------------------
$("#tipo_bienes_db_btn_guardar").click(function() {
	if ($('#form_db_tipo_bienes').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/tipobien/db/sql.registrar_tipo_bienes.php",
			data:dataForm('form_db_tipo_bienes'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('tipo_bienes_db_id_mayor').value='';
					getObj('tipo_bienes_db_id_tipo_bienes').value='';
					getObj('tipo_bienes_db_nombre_mayor').value='';
					getObj('tipo_bienes_db_nombre_tipo_bienes').value='';
					getObj('tipo_bienes_db_comentario').value='';
					getObj('val_vehiculo').value='';
					getObj('vida_util_tb').value='';
					getObj('vehiculo_1').checked=false;
					getObj('vehiculo_2').checked=false;
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
$("#tipo_bienes_db_btn_actualizar").click(function() {
	if ($('#form_db_tipo_bienes').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/tipobien/db/sql.actualizar_tipo_bienes.php",
			data:dataForm('form_db_tipo_bienes'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('tipo_bienes_db_id_mayor').value='';
					getObj('tipo_bienes_db_nombre_mayor').value='';
					getObj('tipo_bienes_db_id_tipo_bienes').value='';
					getObj('tipo_bienes_db_nombre_tipo_bienes').value='';
					getObj('vida_util_tb').value='';
					getObj('tipo_bienes_db_comentario').value='';
					getObj('tipo_bienes_db_btn_actualizar').style.display='none';
					getObj('tipo_bienes_db_btn_eliminar').style.display = 'none';
					getObj('tipo_bienes_db_btn_guardar').style.display='';
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

$("#tipo_bienes_db_btn_eliminar").click(function() {
	if ($('#form_db_tipo_bienes').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/bienes/tipobien/db/sql.eliminar_tipo_bienes.php",
			data:dataForm('form_db_tipo_bienes'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('tipo_bienes_db_id_mayor').value='';
					getObj('tipo_bienes_db_nombre_mayor').value='';
					getObj('tipo_bienes_db_id_tipo_bienes').value='';
					getObj('tipo_bienes_db_nombre_tipo_bienes').value='';
					getObj('vida_util_tb').value='';
					getObj('tipo_bienes_db_comentario').value='';
					getObj('tipo_bienes_db_btn_actualizar').style.display='none';
					getObj('tipo_bienes_db_btn_eliminar').style.display = 'none';
					getObj('tipo_bienes_db_btn_guardar').style.display='';
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
$("#tipo_bienes_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('tipo_bienes_db_id_mayor').value='';
getObj('tipo_bienes_db_id_tipo_bienes').value='';
getObj('tipo_bienes_db_nombre_mayor').value='';
getObj('tipo_bienes_db_nombre_tipo_bienes').value = '';
getObj('vida_util_tb').value='';
getObj('tipo_bienes_db_comentario').value = '';
getObj('val_vehiculo').value = '';
getObj('tipo_bienes_db_btn_actualizar').style.display = 'none';
getObj('tipo_bienes_db_btn_eliminar').style.display = 'none';
getObj('tipo_bienes_db_btn_guardar').style.display = '';
getObj('vehiculo_1').checked=false;
getObj('vehiculo_2').checked=false;
setBarraEstado("");
});
//					clearForm('form_pr_cargar_cotizacion');

$('#tipo_bienes_db_nombre_tipo_bienes').alpha({allow:' '});
$('#tipo_bienes_db_nombre_mayor').alpha({allow:' '});
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
	<img id="tipo_bienes_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="tipo_bienes_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="tipo_bienes_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="tipo_bienes_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="tipo_bienes_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_tipo_bienes" id="form_db_tipo_bienes">
<input type="hidden" name="tipo_bienes_db_id_tipo_bienes" id="tipo_bienes_db_id_tipo_bienes" />
<input type="hidden" name="tipo_bienes_db_id_mayor" id="tipo_bienes_db_id_mayor" />
<input type="hidden" name="tipo_bienes_db_fechact" id="tipo_bienes_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<table class="cuerpo_formulario">
  <tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Tipo de Bienes			</th>
	</tr>
    	<tr>
    	  <th>Mayor:</th>
    	  <td> <ul class="input_con_emergente">
				<li>
           <input name="tipo_bienes_db_nombre_mayor" type="text"  id="tipo_bienes_db_nombre_mayor" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Mayor Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Mayor: '+$(this).val()]}"/>
           </li>
				<li id="tipo_bienes_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul></td>
  	  </tr>
    	<tr>
			<th>Nombre:</th>
		  <td><input name="tipo_bienes_db_nombre_tipo_bienes" type="text"  id="tipo_bienes_db_nombre_tipo_bienes" maxlength="60" size="30" message="Introduzca un Nombre." jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		  </td>
		</tr>
    	<tr>
    	  <th>Vida Util:</th>
    	  <td><input name="vida_util_tb" type="text" id="vida_util_tb" size="10" maxlength="4" message="Introduzca la Vida Util para el tipo de Activo."/></td>
  	  </tr>
  <tr>
<th>¿Es Vehículo?</th>
    	  <td><p>
    	    <label>
    	      <input type="radio" name="vehiculo" value="si" id="vehiculo_1"/>
    	      Sí</label>
    	    &nbsp;
    	    <label>
    	      <input name="vehiculo" type="radio" id="vehiculo_2" value="no" checked="checked" />
   	        No</label>
    	    <input type="hidden" name="val_vehiculo" id="val_vehiculo" />
    	    <br />
  	    </p></td>
    </tr>
        <tr>
			<th>Observaci&oacute;n:</th>
		  <td><label>
		    <textarea name="tipo_bienes_db_comentario" id="tipo_bienes_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn."></textarea>
		  </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
</form>