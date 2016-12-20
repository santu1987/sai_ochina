
<script type='text/javascript'>


var dialog;
$("#ramos_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/ramos/db/grid_ramos.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Programas',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/presupuesto/ramos/db/sql_grid_ramos.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Ramo','Organismo','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'ramo',index:'ramo', width:221,sortable:false,resizable:false},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: jQuery('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('ramos_db_id').value = ret.id;
									getObj('ramos_db_nombre').value = ret.ramo;	
									getObj('ramos_db_comentario').value = ret.comentario;
									getObj('ramos_db_btn_eliminar').style.display='';
									getObj('ramos_db_btn_actualizar').style.display='';
									getObj('ramos_db_btn_guardar').style.display='none';									
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
								mtype: "POST",
								sortname: 'id_ramo',
								viewrecords: true,
								toolbar : [true,"top"],
								sortorder: "asc",
								caption:"Multiple Toolbar Search Example"

							});
							jQuery("#t_s1list").height(25).hide().filterGrid("list_grid_"+nd,{gridModel:true,gridToolbar:true});
							jQuery("#sg_invdate").datepicker({dateFormat:"yy-mm-dd"});
							
							jQuery("#list_grid_"+nd).navGrid('#pager_grid_'+nd,{edit:false,add:false,del:false,search:false,refresh:false})
							.navButtonAdd('#pager_grid_'+nd,{caption:"Search",title:"Toggle Search",buttonimg:'../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images/find.gif',
								onClickButton:function(){ 
									if(jQuery("#t_s1list").css("display")=="none") {
										jQuery("#t_s1list").css("display","");
									} else {
										jQuery("#t_s1list").css("display","none");
									}
									
								} 
							});
								
						}
});

$("#ramos_db_btn_guardar").click(function() {
	if($('#form_db_ramos').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/ramos/db/sql.ramos.php",
			data:dataForm('form_db_ramos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_ramos');
				}
				else if(html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_db_ramos.ramos_db_nombre.value="";
					document.form_db_ramos.ramos_db_nombre.focus();
				}
			}
		});
	}
});

$("#ramos_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/ramos/db/sql.actualizar.php",
			data:dataForm('form_db_ramos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('ramos_db_btn_actualizar').style.display='none';
					getObj('ramos_db_btn_eliminar').style.display='none';
					getObj('ramos_db_btn_guardar').style.display='';
					clearForm('form_db_ramos');
				}
				if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_db_ramos.ramos_db_nombre.value="";
					document.form_db_ramos.ramos_db_nombre.focus();
				}
				//else
				//{
					//setBarraEstado(html);
				//}
			}
		});
	//}
});

$("#ramos_db_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/presupuesto/ramos/db/sql.eliminar.php",
			data:dataForm('form_db_ramos'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('ramos_db_btn_cancelar').style.display='';
					getObj('ramos_db_btn_eliminar').style.display='none';
					getObj('ramos_db_btn_actualizar').style.display='none';
					getObj('ramos_db_btn_guardar').style.display='';
					clearForm('form_db_ramos');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});




$("#ramos_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('ramos_db_btn_cancelar').style.display='';
	getObj('ramos_db_btn_eliminar').style.display='none';
	getObj('ramos_db_btn_actualizar').style.display='none';
	getObj('ramos_db_btn_guardar').style.display='';
	clearForm('form_db_ramos');
});


$('#ramos_db_nombre').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>

<div id="botonera">
	<img id="ramos_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="ramos_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="ramos_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="ramos_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="ramos_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" name="form_db_ramos" id="form_db_ramos">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Ramos</th>
		</tr>
		<tr>
			<th>Nombre:</th>
			<td ><input name="ramos_db_nombre" id="ramos_db_nombre" type="text" style="width:62ex;"
				message= "Introduzca un Nombre para el Ramo." 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
		</tr>
		<tr>
			<th>Comentario:</th>
			<td ><textarea name="ramos_db_comentario" id="ramos_db_comentario" cols="65" rows="3" message= "Introduzca un Comentario para el Ramo."></textarea></td>
		</tr>
		
			
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="ramos_db_id" id="ramos_db_id" />
</form>
