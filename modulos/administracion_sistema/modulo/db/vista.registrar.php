<script type='text/javascript'>
var dialog;
$("#modulo_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/administracion_sistema/modulo/db/grid_modulo.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de MÛdulos',modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:630,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/administracion_sistema/modulo/db/sql_grid_modulo.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false,hidden:true},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_modulo').value = ret.id;
									getObj('modulo_db_vista_nombre').value = ret.nombre;
									getObj('modulo_db_vista_observacion').value = ret.comentario;
									getObj('modulo_btn_cancelar').style.display='';
									getObj('modulo_btn_actualizar').style.display='';
									getObj('modulo_btn_eliminar').style.display='';
									getObj('modulo_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#modulo').jVal();
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
$("#modulo_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#modulo').jVal())
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/modulo/db/sql.actualizar.php",
			data:dataForm('modulo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('modulo_btn_eliminar').style.display='none';
						clearForm('modulo');
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

$("#modulo_btn_guardar").click(function() {
	if($('#modulo').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/administracion_sistema/modulo/db/sql.registrar.php",
			data:dataForm('modulo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('modulo');
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
$("#modulo_btn_eliminar").click(function() {
  if (getObj('vista_id_modulo').value !=""){
	if(confirm("øDesea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/administracion_sistema/modulo/db/sql.eliminar.php",
			data:dataForm('modulo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('modulo_btn_eliminar').style.display='none';
					getObj('modulo_btn_actualizar').style.display='none';
					getObj('modulo_btn_guardar').style.display='';
					clearForm('modulo');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else
				{
					setBarraEstado(html);
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
$("#modulo_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('modulo_btn_cancelar').style.display='';
	getObj('modulo_btn_eliminar').style.display='none';
	getObj('modulo_btn_actualizar').style.display='none';
	getObj('modulo_btn_guardar').style.display='';
	clearForm('modulo');
});
	
$('#modulo_db_vista_nombre').alpha({allow:' ·ÈÌÛ˙¡…Õ”⁄Ò'});
</script>



<div id="botonera">
	<img id="modulo_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="modulo_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="modulo_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="modulo_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="modulo_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>
<form method="post" id="modulo" name="modulo">
<input type="hidden" name="vista_id_modulo" id="vista_id_modulo" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar M&oacute;dulo </th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
	<td>
		<input name="modulo_db_vista_nombre" type="text" id="modulo_db_vista_nombre"   value="" size="40" maxlength="60"  
						message="Introduzca un Nombre para el Modulo. Ejem: ''AdministraciÛn'' " 
						jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
		<th>Observaci&oacute;n:</th>			<td ><textarea name="modulo_db_vista_observacion" cols="60" id="modulo_db_vista_observacion"  message="Introduzca una ObservaciÛn. Ejem: ''Este MÛdulo es...'' "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>