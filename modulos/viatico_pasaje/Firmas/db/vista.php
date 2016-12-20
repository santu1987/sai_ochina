<script type='text/javascript'>
var dialog;
$("#sareta_armador_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/sareta/armador/db/grid_armador.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Armadores',modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/sareta/armador/db/sql_grid_armador.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario'],
								colModel:[
								{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_armador').value = ret.id;
									getObj('sareta_armador_db_vista_nombre').value = ret.nombre;
									getObj('sareta_armador_db_vista_observacion').value = ret.comentario;
									getObj('sareta_armador_db_btn_cancelar').style.display='';
									getObj('sareta_armador_db_btn_actualizar').style.display='';
									getObj('sareta_armador_db_btn_eliminar').style.display='';
									getObj('sareta_armador_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_armador').jVal();
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

$("#sareta_armador_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_armador').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/armador/db/sql.actualizar.php",
			data:dataForm('form_db_armador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_armador_db_btn_eliminar').style.display='none';
						getObj('sareta_armador_db_btn_actualizar').style.display='none';
						getObj('sareta_armador_db_btn_guardar').style.display='';
						clearForm('form_db_armador');
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

$("#sareta_armador_db_btn_guardar").click(function() {
	if($('#form_db_armador').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/armador/db/sql.registrar.php",
			data:dataForm('form_db_armador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_armador');
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
$("#sareta_armador_db_btn_eliminar").click(function() {
  if (getObj('vista_id_armador').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/armador/db/sql.eliminar.php",
			data:dataForm('form_db_armador'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_armador_db_btn_eliminar').style.display='none';
					getObj('sareta_armador_db_btn_actualizar').style.display='none';
					getObj('sareta_armador_db_btn_guardar').style.display='';
					clearForm('form_db_armador');
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
$("#sareta_armador_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_armador_db_btn_cancelar').style.display='';
	getObj('sareta_armador_db_btn_eliminar').style.display='none';
	getObj('sareta_armador_db_btn_actualizar').style.display='none';
	getObj('sareta_armador_db_btn_guardar').style.display='';
	clearForm('form_db_armador');
});
	
$('#sareta_armador_db_vista_nombre').alpha({allow:' áéíóúÁÉÍÓÚñ 0123456789'});
</script>


<div id="botonera">
	<img id="sareta_armador_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_armador_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_armador_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_armador_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_armador_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_armador" name="form_db_armador">
<input type="hidden" name="vista_id_armador" id="vista_id_armador" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Armador </th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
	<td>
		<input name="sareta_armador_db_vista_nombre" type="text" id="sareta_armador_db_vista_nombre"   value="" size="40" maxlength="60"  
						message="Introduzca un Nombre para el Armador. Ejem: ''ABC MARITIMA'' " 
						jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ 0-9]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
    	
	<tr>
	<tr>
		<th>Observaci&oacute;n:</th>			<td ><textarea name="sareta_armador_db_vista_observacion" cols="60" id="sareta_armador_db_vista_observacion"  message="Introduzca una Observación. Ejem: ''Esta Armador es...'' "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>