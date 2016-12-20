<script type='text/javascript'>
var dialog;
//
//
//
$("#tipo_demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/plan_compra/db/vista.grid_tipo_demanda.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Demanda', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_demanda_db_nombre_demanda").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_tipo_demanda.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_demanda_db_nombre_demanda").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_demanda_db_nombre_demanda").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/plan_compra/db/sql_tipo_demanda.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:500,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/adquisiones/plan_compra/db/sql_tipo_demanda.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Comentario'],
								colModel:[
									{name:'id_tipo_demanda',index:'id_tipo_demanda', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_tipo_demanda').value = ret.id_tipo_demanda;
									getObj('tipo_demanda_db_nombre').value = ret.nombre;
									getObj('tipo_demanda_db_observacion').value = ret.comentario;
									setBarraEstado("");
									getObj('tipo_demanda_btn_cancelar').style.display='';
									getObj('tipo_demanda_btn_actualizar').style.display='none';
									getObj('tipo_demanda_btn_guardar').style.display='';
									setBarraEstado("");
	
									dialog.hideAndUnload();
									$('#tipo_demanda').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_demanda_db_nombre_demanda").focus();
								$('#tipo_demanda_db_nombre_demanda').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_demanda',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#tipo_demanda_btn_consultar").click(function() {
	var nd=new Date().getTime();
	
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/plan_compra/db/grid_plan_compra.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Demanda',modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/adquisiones/plan_compra/db/sql_grid_tipo_demanda.php?nd='+nd,
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
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_tipo_demanda').value = ret.id;
									getObj('tipo_demanda_db_nombre').value = ret.nombre;
									getObj('tipo_demanda_db_observacion').value = ret.comentario;
									setBarraEstado("");
									getObj('tipo_demanda_btn_cancelar').style.display='';
									getObj('tipo_demanda_btn_actualizar').style.display='none';
									getObj('tipo_demanda_btn_guardar').style.display='';
									setBarraEstado("");
	
									dialog.hideAndUnload();
									$('#tipo_demanda').jVal();
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
$("#tipo_demanda_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#tipo_demanda').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.actualizar.php",
			data:dataForm('tipo_demanda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('tipo_demanda_btn_eliminar').style.display='none';
						clearForm('tipo_demanda');
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
});*/

$("#tipo_demanda_btn_guardar").click(function() {
	if($('#tipo_demanda').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/adquisiones/plan_compra/db/sql.registrar.php",
			data:dataForm('tipo_demanda'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('tipo_demanda');
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
$("#tipo_demanda_btn_cancelar").click(function() {
					clearForm('tipo_demanda');
});


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("#tipo_demanda_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tipo_demanda_btn_cancelar').style.display='';
	getObj('tipo_demanda_btn_eliminar').style.display='none';
	getObj('tipo_demanda_btn_actualizar').style.display='none';
	getObj('tipo_demanda_btn_guardar').style.display='';
	clearForm('tipo_demanda');
});
	
$('#tipo_demanda_db_nombre').alpha({allow:' ·ÈÌÛ˙¡…Õ”⁄Ò—'});
</script>



<div id="botonera">
	<img id="tipo_demanda_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
   <!-- <img id="tipo_demanda_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>-->
	<img id="tipo_demanda_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="tipo_demanda_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tipo_demanda_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>
<form method="post" id="tipo_demanda" name="tipo_demanda">
<input type="hidden" name="vista_id_tipo_demanda" id="vista_id_tipo_demanda" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Tipo Demanda </th>
	</tr>
	<tr>
	<th>Nombre:		</th>	
	<td>
		<input name="tipo_demanda_db_nombre" type="text" id="tipo_demanda_db_nombre"   value="" size="60" maxlength="60"  
						message="Introduzca un Nombre. Ejem: ''AdministraciÛn'' " 
						jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò—]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
		<th>Comentario:</th>			
		<td ><textarea name="tipo_demanda_db_observacion"  cols="57" id="tipo_demanda_db_observacion"  message="Introduzca un Comentario. Ejem: ''Este Tipo de...'' "></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>