<script type='text/javascript'>
var dialog;
$("#perfil_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/administracion_sistema/perfil/db/grid_perfil.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Perfil', modal: true,center:false,x:0,y:0,show:false});
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:650,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/administracion_sistema/perfil/db/sql_grid_perfil.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario'],
								colModel:[
									{name:'id',index:'id', width:10,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('perfil_db_id_perfil').value = ret.id;
									getObj('perfil_db_vista_nombre').value = ret.nombre;
									getObj('perfil_db_vista_comentarios').value = ret.comentario;
									getObj('perfil_db_btn_cancelar').style.display='';
									getObj('perfil_db_btn_eliminar').style.display='';
									getObj('perfil_db_btn_actualizar').style.display='';
									getObj('perfil_db_btn_guardar').style.display='none';
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});							
						}
});

$("#perfil_db_btn_guardar").click(function() {
	if($('#form_db_perfil').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/administracion_sistema/perfil/db/sql.registrar.php",
			data:dataForm('form_db_perfil'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_perfil');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#perfil_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_perfil').jVal())
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/perfil/db/sql.actualizar.php",
			data:dataForm('form_db_perfil'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('perfil_db_btn_cancelar').style.display='none';
					getObj('perfil_db_btn_eliminar').style.display='none';
					getObj('perfil_db_btn_actualizar').style.display='none';
					getObj('perfil_db_btn_guardar').style.display='';
					clearForm('form_db_perfil');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#perfil_db_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/perfil/db/sql.eliminar.php",
			data:dataForm('form_db_perfil'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('perfil_db_btn_cancelar').style.display='';
					getObj('perfil_db_btn_eliminar').style.display='none';
					getObj('perfil_db_btn_actualizar').style.display='none';
					getObj('perfil_db_btn_guardar').style.display='';
					clearForm('form_db_perfil');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#perfil_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('perfil_db_btn_cancelar').style.display='';
	getObj('perfil_db_btn_eliminar').style.display='none';
	getObj('perfil_db_btn_actualizar').style.display='none';
	getObj('perfil_db_btn_guardar').style.display='';
	clearForm('form_db_perfil');
});

$('#perfil_db_vista_nombre').alpha({allow:'@ ·ÈÌÛ˙¡…Õ”⁄'});
$('#perfil_db_vista_comentarios').alphanumeric({allow:'._/@ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>

<div id="botonera">
	<img id="perfil_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" " />	
	<img id="perfil_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />	
	<img id="perfil_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="perfil_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="perfil_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>

<form method="post" name="form_db_perfil" id="form_db_perfil">
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Perfil </th>
		</tr>
		<tr>
			<th>Nombre:			</th><td>		
			<input type="text"  size="40" name="perfil_db_vista_nombre" id="perfil_db_vista_nombre" value="" 
				message="Introduzca un Nombre para el Perfil. Ejem. ''Administrador''" 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,40}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
			/>
			</td>
		</tr>
		<tr>
			<th>Comentarios:	</th><td >		
			<textarea name="perfil_db_vista_comentarios" id="perfil_db_vista_comentarios" cols="40" message="Introduzca un Comentario para el Perfil"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>

<input type="hidden" name="perfil_db_id_perfil" id="perfil_db_id_perfil" />	
</form>
