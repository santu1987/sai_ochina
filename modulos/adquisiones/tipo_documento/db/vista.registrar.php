<? if (!$_SESSION) session_start();?>
<script>
var dialog;
/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#tipo_documento_db_btn_guardar").click(function() {
	if($('#form_db_tipo_documento').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
		url: "modulos/adquisiones/tipo_documento/db/sql.tipo_documento.php",
			data:dataForm('form_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_tipo_documento');
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});
/*--------------------------------------   BUSCAR ----------------------------------------------------*/
//
//
//
$("#tipo_documento_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/tipo_documento/db/vista.grid_tipo_doc.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Tipo de Documento', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#tipo_documento_db_nombre_d").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/tipo_documento/db/sql_tipo_doc.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#tipo_documento_db_nombre_d").keypress(function(key)
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
							var busq_nombre= jQuery("#tipo_documento_db_nombre_d").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/tipo_documento/db/sql_tipo_doc.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/adquisiones/tipo_documento/db/sql_tipo_doc.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Documento','Comentario'],
								colModel:[
									{name:'id_tipo_documento',index:'id_tipo_documento', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:15,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('tipo_documento_db_id').value = ret.id_tipo_documento;
									getObj('tipo_documento_db_nombre').value = ret.nombre;
									getObj('tipo_documento_db_comentario').value = ret.comentario;
									getObj('tipo_documento_db_btn_actualizar').style.display='';
									getObj('tipo_documento_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#tipo_documento_db_nombre_d").focus();
								$('#tipo_documento_db_nombre_d').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_tipo_documensto',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});


//
//
//
/*$("#tipo_documento_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/tipo_documento/db/grid_tipo_documento.php", { },
                        function(data)
                        {								
							dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Tipo de Documento',modal: true,center:false,x:0,y:0,show:false});								
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
								url:'modulos/adquisiones/tipo_documento/db/sql_grid_tipo_documento.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Tipo Documento','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'tipo_documento',index:'tipo_documento', width:221,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tipo_documento_db_id').value = ret.id;
									getObj('tipo_documento_db_nombre').value = ret.tipo_documento;
									getObj('tipo_documento_db_comentario').value = ret.comentario;
									getObj('tipo_documento_db_btn_actualizar').style.display='';
									getObj('tipo_documento_db_btn_guardar').style.display='none';									
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
								sortname: 'id_tipo_documento',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

/*--------------------------------------   ACTULIZAR ----------------------------------------------------*/

$("#tipo_documento_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_tipo_documento').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/tipo_documento/db/sql.actualizar.php",
			data:dataForm('form_db_tipo_documento'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('tipo_documento_db_btn_actualizar').style.display='none';
					getObj('tipo_documento_db_btn_guardar').style.display='';
					clearForm('form_db_tipo_documento');
				}
				else
				{
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
					//setBarraEstado(html);
				}
			}
		});
	}
});

$("#tipo_documento_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tipo_documento_db_btn_cancelar').style.display='';
	getObj('tipo_documento_db_btn_actualizar').style.display='none';
	getObj('tipo_documento_db_btn_guardar').style.display='';
	clearForm('form_db_tipo_documento');
});


/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/
$('#tipo_documento_db_nombre').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
	<img id="tipo_documento_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="tipo_documento_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="tipo_documento_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="tipo_documento_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" id="form_db_tipo_documento" name="form_db_tipo_documento">
	<table class="cuerpo_formulario">
		<tr>
		<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Tipo Documento</th>
		</tr>
		<tr>
			<th>Nombre:</th>
			<td><input name="tipo_documento_db_nombre" type="text" id="tipo_documento_db_nombre" size="60"  
				message="Introduzca un Nombre para la Unidad de Medida." 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<th>Comentario:</th>
			<td>
				<textarea name="tipo_documento_db_comentario" cols="60" rows="3" id="tipo_documento_db_comentario" ></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="tipo_documento_db_id" id="tipo_documento_db_id">
</form>