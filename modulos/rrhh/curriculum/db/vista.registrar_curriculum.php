<? if (!$_SESSION) session_start();
?>
<script>
var dialog;
//----------------------------------------------------------------------------------------------------


$("#curriculum_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/curriculum/db/vista.grid_curriculum_nom.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Ramas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nivel= jQuery("#curriculum_db_nombre_niv").val(); 
					var busq_nombre= jQuery("#curriculum_db_nombre").val(); 
					var busq_cedula= jQuery("#curriculum_db_ci").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/curriculum/db/sql_curriculum_nombre.php?busq_nombre="+busq_nombre+"&busq_nivel="+busq_nivel+"&busq_cedula="+busq_cedula,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#curriculum_db_nombre_niv").change(function()
				{
						//if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#curriculum_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#curriculum_db_ci").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#curriculum_db_nombre_per").keypress(function(key)
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
							var busq_nivel= jQuery("#curriculum_db_nombre_niv").val();
							var busq_nombre= jQuery("#curriculum_db_nombre").val();
							var busq_cedula= jQuery("#curriculum_db_ci").val();
							var busq_persona= jQuery("#curriculum_db_nombre_per").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/curriculum/db/sql_curriculum_nombre.php?busq_nombre="+busq_nombre+"&busq_nivel="+busq_nivel+"&busq_cedula="+busq_cedula+"&busq_persona="+busq_persona,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:290,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/rrhh/curriculum/db/sql_curriculum_nombre.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Ramas','Imagen','id_unidad','Unidad','Fecha'],
								colModel:[
									{name:'id_curriculum',index:'id_curriculum', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:50,sortable:false,resizable:false},
									{name:'nombre_persona',index:'nombre_persona', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'imagen',index:'imagen', width:100,sortable:false,resizable:false},
									{name:'observaciones',index:'observaciones', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_ramas',index:'id_ramas', width:100,sortable:false,resizable:false,hidden:true},
									{name:'fecha_actualizacion',index:'fecha_actualizacion', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('curriculum_db_id_curriculum').value = ret.id_curriculum;
									getObj('curriculum_db_id_ramas').value = ret.id_ramas;
									var cedula = ret.cedula;
									var nac = cedula.substr(0,2);
									if(nac=='V-')
										getObj('curriculum_db_nac').selectedIndex=0;
									if(nac=='E-')
										getObj('curriculum_db_nac').selectedindex=1;
									cedula = cedula.substr(2,8);
									getObj('curriculum_db_cedula').value=cedula;
									getObj('curriculum_db_ramas').value = ret.nombre;
									getObj('curriculum_db_nombre_persona').value=ret.nombre_persona;
									//getObj('curriculum_db_imagen').value = ret.imagen;
									getObj('curriculum_db_comentario').value = ret.observaciones;
									getObj('foto_ant').value = ret.imagen;
									getObj('foto_vie').value = ret.imagen;
									if(ret.imagen!='')								
									getObj('foto_curriculum').src='imagenes/curriculos/'+ret.imagen;
									else
									getObj('foto_curriculum').src='imagenes/curriculos/knode2.png';
									getObj('curriculum_db_btn_guardar').style.display = 'none';
									getObj('curriculum_db_btn_actualizar').style.display = '';
									getObj('curriculum_db_btn_eliminar').style.display = '';

									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#curriculum_db_nombre").focus();
								$('#curriculum_db_nombre').alpha({allow:' '});
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



//
//

//
//-----------------------------------------------------------------------------


$("#curriculum_db_btn_guardar").click(function() {
	if ($('#form_db_curriculum').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/curriculum/db/sql.registrar_curriculum.php",
			data:dataForm('form_db_curriculum'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html.substr(0,10)=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					getObj('curriculum_db_nac').selectedIndex=0;
					getObj('curriculum_db_cedula').value='';
					getObj('curriculum_db_nombre_persona').value='';
					getObj('curriculum_db_id_ramas').value='';
					getObj('curriculum_db_ramas').value='';
					getObj('curriculum_db_comentario').value = '';
					if(getObj('curriculum_db_imagen').value!=''){
						getObj('opt').value='2';
						document.form_db_foto.submit();
					}
					getObj('curriculum_db_imagen').value = '';
					getObj('foto_curriculum').src='../../../../imagenes/curriculos/knode2.png';
					getObj('opt').value = '';
					
					//alert("hola");
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
$("#curriculum_db_btn_actualizar").click(function() {
	if ($('#form_db_curriculum').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/curriculum/db/sql.actualizar_curriculum.php",
			data:dataForm('form_db_curriculum'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('curriculum_db_id_curriculum').value = '';
					getObj('curriculum_db_id_ramas').value='';
					getObj('curriculum_db_nac').selectedIndex=0;
					getObj('curriculum_db_cedula').value='';
					getObj('curriculum_db_nombre_persona').value='';
					getObj('curriculum_db_ramas').value='';
					getObj('curriculum_db_comentario').value = '';
					getObj('curriculum_db_btn_actualizar').style.display = 'none';
					getObj('curriculum_db_btn_eliminar').style.display = 'none';
					getObj('curriculum_db_btn_guardar').style.display = '';
					if(getObj('curriculum_db_imagen').value!=''){
						getObj('opt').value='2';
						document.form_db_foto.submit();
					}
					getObj('curriculum_db_imagen').value = '';
					getObj('foto_curriculum').src='../../../../imagenes/curriculos/knode2.png';
					getObj('opt').value = '';
					getObj('foto_ant').value='';
					getObj('foto_vie').value='';
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
$("#curriculum_db_btn_eliminar").click(function() {
	if ($('#form_db_curriculum').jVal()){											   
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/rrhh/curriculum/db/sql.eliminar_curriculum.php",
			data:dataForm('form_db_curriculum'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					
					getObj('curriculum_db_id_curriculum').value = '';
					getObj('curriculum_db_id_ramas').value='';
					getObj('curriculum_db_nac').selectedIndex=0;
					getObj('curriculum_db_cedula').value='';
					getObj('curriculum_db_nombre_persona').value = '';
					getObj('curriculum_db_ramas').value = '';
					getObj('curriculum_db_comentario').value='';
					getObj('curriculum_db_imagen').value = '';
					getObj('foto_curriculum').src='../../../../imagenes/curriculos/knode2.png';
					getObj('opt').value = '';
					getObj('foto_ant').value='';
					getObj('foto_vie').value='';
					getObj('ramas_db_btn_actualizar').style.display = 'none';
					getObj('ramas_db_btn_eliminar').style.display = 'none';
					getObj('ramas_db_btn_guardar').style.display = '';
					alert("llego");
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
$("#curriculum_db_btn_consulta_emergente").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/rrhh/curriculum/db/vista.grid_ramas_nombre.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Ramas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#curriculum_db_nombre").val(); 
					var busq_nivel= jQuery("#curriculum_db_nombre_niv").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/curriculum/db/sql_ramas_nom.php?busq_nombre="+busq_nombre+"&busq_nievl="+busq_nivel,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#curriculum_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#curriculum_db_nombre_niv").change(function()
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
							var busq_nombre= jQuery("#curriculum_db_nombre").val();
							var busq_nivel= jQuery("#curriculum_db_nombre_niv").val();  
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/rrhh/curriculum/db/sql_ramas_nom.php?busq_nombre="+busq_nombre+"&busq_nivel="+busq_nivel,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/rrhh/curriculum/db/sql_ramas_nom.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Ramas','Comentario'],
								colModel:[
									{name:'id_ramas',index:'id_ramas', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'observaciones',index:'observaciones', width:100,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('curriculum_db_id_ramas').value=ret.id_ramas;
									getObj('curriculum_db_ramas').value=ret.nombre;
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#curriculum_db_nombre").focus();
								$('#curriculum_db_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_ramas',
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
$("#curriculum_db_btn_cancelar").click(function() {
//clearForm('form_db_valor_impuesto');
getObj('curriculum_db_id_curriculum').value = '';
getObj('curriculum_db_nac').selectedIndex=0;
getObj('curriculum_db_cedula').value='';
getObj('curriculum_db_nombre_persona').value='';
getObj('curriculum_db_id_ramas').value = '';
getObj('curriculum_db_ramas').value = '';
getObj('curriculum_db_imagen').value = '';
getObj('curriculum_db_comentario').value = '';
getObj('curriculum_db_nombre_img').value='';
getObj('foto_curriculum').src='../../../../imagenes/curriculos/knode2.png';
getObj('opt').value='';
getObj('foto_ant').value='';
getObj('foto_vie').value='';
getObj('curriculum_db_btn_actualizar').style.display = 'none';
getObj('curriculum_db_btn_eliminar').style.display = 'none';
getObj('curriculum_db_btn_guardar').style.display = '';

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
$('#curriculum_db_cedula').numeric({allow:' '});
$('#curriculum_db_nombre_persona').alpha({allow:' '});
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
	<img id="curriculum_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
    <img id="curriculum_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="curriculum_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"/>
    <img style="display:none" id="curriculum_db_btn_actualizar" class="btn_actualizar" src="imagenes/null.gif"/>
	<img id="curriculum_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  /></div>
    

<form name="form_db_curriculum" id="form_db_curriculum" method="post">
<input type="hidden" name="curriculum_db_id_curriculum" id="curriculum_db_id_curriculum" />
<input type="hidden" name="curriculum_db_fechact" id="curriculum_db_fechact" value="<?php echo date("d-m-Y");?>"/>
<input type="hidden" name="curriculum_db_id_ramas" id="curriculum_db_id_ramas"/>
<input type="hidden" id="curriculum_db_nombre_img" name="curriculum_db_nombre_img"/>
<input type="hidden" id="foto_vie" name="foto_vie" />

<table class="cuerpo_formulario">
<tr>
			<th class="titulo_frame" colspan="2">
				<img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" /> Curriculum</th>
	</tr>
    <tr>
			<th>Cedula</th>
		  <td><select name="curriculum_db_nac" id="curriculum_db_nac" style="width:50px; min-width:50px;">
				  <option>V-</option>
				  <option>E-</option>
          </select>
		    <label>
		      <input type="text" name="curriculum_db_cedula" id="curriculum_db_cedula" size="8" maxlength="9" width="150px" message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" jval="{valid:/^[0-9]{1,09}$/, message:'C&eacute;dula Invalida', styleType:'cover'}" jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
          </label></td>
	</tr>
        <tr>
			<th>Nombre</th>
		  <td><label>
		    <input type="text" name="curriculum_db_nombre_persona" id="curriculum_db_nombre_persona" maxlength="60" size="30"  message="Introduzca el Nombre de la Persona " jval="{valid:/^[a-zA-Z ,. áéíóúÁÉÉÍÓÚ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}" jvalkey="{valid:/[a-zA-Z ,. áéíóúÁÉÍÓÚ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
	      </label></td>
		</tr>
    <tr>
			<th>Rama</th>
		  <td>          <ul class="input_con_emergente">
				<li>
           <input name="curriculum_db_ramas" type="text"  id="curriculum_db_ramas" maxlength="60" size="30" readonly="true"
           jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Unidad Ejecutora Invalida', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Unidad Ejecutora: '+$(this).val()]}"/>
           </li>
				<li id="curriculum_db_btn_consulta_emergente" class="btn_consulta_emergente"></li>
			</ul>
		  </td>
		</tr>
        <tr>
			<th>Observaci&oacute;n</th>
		  <td><label>
		    <textarea name="curriculum_db_comentario" id="curriculum_db_comentario" cols="60" style="width:422px" message="Introduzca una Observaciòn. Ejem: 'Este porcentaje es...'"></textarea>
		  </label></td>
		</tr>			
  </table>
</form>
<form enctype="multipart/form-data" name="form_db_foto" id="form_db_foto" method="post" action="modulos/rrhh/curriculum/db/vista.previa.php" target="vista_previa">
  <table class="cuerpo_formulario">
    	<tr>
			<th width="83"> Curriculum:&nbsp; </th>
		  <td width="317"><img src="../../../../imagenes/curriculos/knode2.png" name="foto_curriculum" width="84" height="84" id="foto_curriculum" />
		    <label>
		    <input type="file" name="curriculum_db_imagen" id="curriculum_db_imagen" onchange="getObj('opt').value='1'; tiempo();"/>
          </label></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>			
  </table>
  <input type="hidden" id="err_tamano" name="err_tamano" onclick="error_tamano()"/>
  <input type="hidden" id="err_formato" name="err_formato" onclick="error_formato()"/>
  <input type="hidden" id="opt" name="opt"/>
  <input type="hidden" id="foto_ant" name="foto_ant" />
</form>
<iframe id="vista_previa" name="vista_previa" src="" style="display:none"></iframe>
<iframe id="limpiar_cache" name="limpiar_cache" src="" style="display:none"></iframe>
<script language="javascript">
function error_tamano(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='../../../../imagenes/curriculos/knode2.png />El tama&ntilde;o de la imagen tiene que ser menor a 1 MB</p></div>",true,true);
}
function error_formato(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='../../../../imagenes/curriculos/knode2.png />El tipo de Imagen tiene que ser: jpeg</p></div>",true,true);
}
//
//

	var c=0;
	var timer;
	var sust = getObj('curriculum_db_imagen').value;
	var sust2;
	//
	//
	function change_picture(){
		var text = sust;
		//var text = document.foto_logo.src;
		var tam = text.length;
		var pos = text.lastIndexOf('_');
		//var pos = text.lastIndexOf('/');
		text = text.substr(pos+1, tam-1);
		text = eval("document.form_db_foto.foto_curriculum"+text);
		text.src = 'imagenes/iconos/ajax-loader2.gif';
		//if (text=='logo1.jpg')
		//	document.foto_logo.src='imagenes/iconos/logo2.jpg';
		//if (text=='logo2.jpg')
		//	document.foto_logo.src='imagenes/iconos/logo1.jpg';
		
			
		c++;
		if (c==4){
		clearInterval(timer);
		//document.getElementById('prueba').style.display='none';
		//document.getElementById('index').style.display='none';
		c=0;
		document.form_db_foto.submit();
		}
		
	}
	function tiempo(){
		//document.getElementById('prueba').style.display='';
		//document.getElementById('index').style.display='';
		timer =	setInterval("change_picture();",150);
	}

//
//
</script>