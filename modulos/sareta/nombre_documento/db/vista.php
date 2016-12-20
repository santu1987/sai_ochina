<script type='text/javascript'>
var dialog;
$("#sareta_nombre_documento_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/nombre_documento/db/grid_nombre_documento.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Nombre de Documentos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre_nombre_documento").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/nombre_documento/db/sql_grid_nombre_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre_nombre_documento").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre_nombre_documento").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/nombre_documento/db/sql_grid_nombre_documento.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/nombre_documento/db/sql_grid_nombre_documento.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/nombre_documento/db/sql_grid_nombre_documento.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Nombre'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
								{name:'codigo',index:'codigo', width:220,sortable:false,resizable:false},
								{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_nombre_documento').value = ret.id;
									getObj('sareta_nombre_documento_db_vista_codigo').value = ret.codigo;
									getObj('sareta_nombre_documento_db_vista_nombre').value = ret.nombre;
								
									getObj('sareta_nombre_documento_db_btn_cancelar').style.display='';
									getObj('sareta_nombre_documento_db_btn_actualizar').style.display='';
									getObj('sareta_nombre_documento_db_btn_eliminar').style.display='';
									getObj('sareta_nombre_documento_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_nombre_documento').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre_nombre_documento").focus();
								$('#parametro_cxp_db_nombre_nombre_documento').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});


$("#sareta_nombre_documento_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_nombre_documento').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/nombre_documento/db/sql.actualizar.php",
			data:dataForm('form_db_nombre_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_nombre_documento_db_btn_eliminar').style.display='none';
						getObj('sareta_nombre_documento_db_btn_actualizar').style.display='none';
						getObj('sareta_nombre_documento_db_btn_guardar').style.display='';
						clearForm('form_db_nombre_documento');
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_nombre_documento_db_btn_guardar").click(function() {
	if($('#form_db_nombre_documento').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/nombre_documento/db/sql.registrar.php",
			data:dataForm('form_db_nombre_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_nombre_documento');
					});					
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});


$("#sareta_nombre_documento_db_btn_eliminar").click(function() {
  if (getObj('vista_id_nombre_documento').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/nombre_documento/db/sql.eliminar.php",
			data:dataForm('form_db_nombre_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_nombre_documento_db_btn_eliminar').style.display='none';
					getObj('sareta_nombre_documento_db_btn_actualizar').style.display='none';
					getObj('sareta_nombre_documento_db_btn_guardar').style.display='';
					clearForm('form_db_nombre_documento');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con este Nombre de Documento</p></div>",true,true); 
				}
				else 
				{
					
					setBarraEstado(html,true,true);
				}
			}
		});
	}
  }
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#sareta_nombre_documento_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_nombre_documento_db_btn_cancelar').style.display='';
	getObj('sareta_nombre_documento_db_btn_eliminar').style.display='none';
	getObj('sareta_nombre_documento_db_btn_actualizar').style.display='none';
	getObj('sareta_nombre_documento_db_btn_guardar').style.display='';
	clearForm('form_db_nombre_documento');
});
	
$('#sareta_nombre_documento_db_vista_nombre').alpha({allow:' áéíóúÁÉÍÓÚñ'});
$('#sareta_nombre_documento_db_vista_codigo').numeric({allow:'0123456789'});
</script>


<div id="botonera">
	<img id="sareta_nombre_documento_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_nombre_documento_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_nombre_documento_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_nombre_documento_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_nombre_documento_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_nombre_documento" name="form_db_nombre_documento">
<input type="hidden" name="vista_id_nombre_documento" id="vista_id_nombre_documento" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Nombre de Documento</th>
	</tr>
    <tr>
	<th>Codigo:		</th>	
		<td>
		<input name="sareta_nombre_documento_db_vista_codigo" type="text" id="sareta_nombre_documento_db_vista_codigo"   value="" size="40" maxlength="30"  
		message="Introduzca un Codigo para el Nombre de Documento." 
		jVal="{valid:/^[0-9]{1,30}$/, message:'Codigo Invalido', styleType:'cover'}"
		jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" />
		</td>
	<tr>
	<th>Nombre:		</th>	
		<td>
		<input name="sareta_nombre_documento_db_vista_nombre" type="text" id="sareta_nombre_documento_db_vista_nombre"   value="" size="40" maxlength="30"  
		message="Introduzca un Nombre para la nombre_documento." 
		jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ ]{1,30}$/, message:'Nombre Invalido', styleType:'cover'}"
		jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
   		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>