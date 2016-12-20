<script type='text/javascript'>
var dialog;
$("#sareta_tipo_tasa_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/tipo_tasa/db/grid_tipo_tasa.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Tasa',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/tipo_tasa/db/sql_grid_tipo_tasa.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Abreviatura','Comentario','com'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'abreviatura',index:'abreviatura', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_tipo_tasa').value = ret.id;
									getObj('sareta_tipo_tasa_db_vista_nombre').value = ret.nombre;
									getObj('sareta_tipo_tasa_db_vista_abreviatura').value = ret.abreviatura;
									getObj('sareta_tipo_tasa_db_vista_observacion').value = ret.com;
									getObj('sareta_tipo_tasa_db_btn_cancelar').style.display='';
									getObj('sareta_tipo_tasa_db_btn_actualizar').style.display='';
									getObj('sareta_tipo_tasa_db_btn_eliminar').style.display='';
									getObj('sareta_tipo_tasa_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_tipo_tasa').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},															
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#sareta_tipo_tasa_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_tipo_tasa').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/tipo_tasa/db/sql.actualizar.php",
			data:dataForm('form_db_tipo_tasa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_tipo_tasa_db_btn_eliminar').style.display='none';
						getObj('sareta_tipo_tasa_db_btn_actualizar').style.display='none';
						getObj('sareta_tipo_tasa_db_btn_guardar').style.display='';
						clearForm('form_db_tipo_tasa');
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else if(html=="Abreviatura_Existe"){
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ABREVIATURA ESTA ASIGNADA A UN TIPO DE TASA</p></div>",true,true);

				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#sareta_tipo_tasa_db_btn_guardar").click(function() {
	if($('#form_db_tipo_tasa').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/tipo_tasa/db/sql.registrar.php",
			data:dataForm('form_db_tipo_tasa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_tipo_tasa');
					});					
				}
				else if (html=="Abreviatura_Existe")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />LA ABREVIATURA ESTA ASIGNADA A UN TIPO DE TASA</p></div>",true,true);
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
$("#sareta_tipo_tasa_db_btn_eliminar").click(function() {
  if (getObj('vista_id_tipo_tasa').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/tipo_tasa/db/sql.eliminar.php",
			data:dataForm('form_db_tipo_tasa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_tipo_tasa_db_btn_eliminar').style.display='none';
					getObj('sareta_tipo_tasa_db_btn_actualizar').style.display='none';
					getObj('sareta_tipo_tasa_db_btn_guardar').style.display='';
					clearForm('form_db_tipo_tasa');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con este tipo de Tasa</p></div>",true,true); 
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
$("#sareta_tipo_tasa_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_tipo_tasa_db_btn_cancelar').style.display='';
	getObj('sareta_tipo_tasa_db_btn_eliminar').style.display='none';
	getObj('sareta_tipo_tasa_db_btn_actualizar').style.display='none';
	getObj('sareta_tipo_tasa_db_btn_guardar').style.display='';
	clearForm('form_db_tipo_tasa');
});
	
$('#sareta_tipo_tasa_db_vista_nombre').alpha({allow:' áéíóúÁÉÍÓÚñ'});
$('#sareta_tipo_tasa_db_vista_abreviatura').alpha({allow:' áéíóúÁÉÍÓÚñ'});
</script>


<div id="botonera">
	<img id="sareta_tipo_tasa_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_tipo_tasa_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_tipo_tasa_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_tipo_tasa_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_tipo_tasa_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_tipo_tasa" name="form_db_tipo_tasa">
<input type="hidden" name="vista_id_tipo_tasa" id="vista_id_tipo_tasa" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Tipo de Tasa</th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
	<td>
		<input name="sareta_tipo_tasa_db_vista_nombre" type="text" id="sareta_tipo_tasa_db_vista_nombre"   value="" size="40" maxlength="30"  
						message="Introduzca un Nombre para el Tipo de Tasa. Ejem: ''Recalada'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
    <tr>
	<th>Abreviatura:		</th>	
	<td>
		<input name="sareta_tipo_tasa_db_vista_abreviatura" type="text" id="sareta_tipo_tasa_db_vista_abreviatura"   value="" size="3" maxlength="3"  
						message="Introduzca un Abreviatura para el Tipo de Tasa. Ejem: ''REC'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ]{1,3}$/, message:'Abreviatura  Invalida', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
	<tr>
		<th>Observaci&oacute;n:</th>			<td ><textarea name="sareta_tipo_tasa_db_vista_observacion" cols="60" id="sareta_tipo_tasa_db_vista_observacion"  message="Introduzca una Observación. Ejem: ''Este Tipo de Tasa es...'' "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>