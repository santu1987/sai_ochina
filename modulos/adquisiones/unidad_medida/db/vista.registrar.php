<? if (!$_SESSION) session_start();?>
<script>
var dialog;
/*--------------------------------------   GUARDAR ----------------------------------------------------*/
$("#unidad_medida_db_btn_guardar").click(function() {
	if($('#form_db_unidad_medida').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
		url: "modulos/adquisiones/unidad_medida/db/sql.unidad_medida.php",
			data:dataForm('form_db_unidad_medida'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado("<img align='absmiddle' src='imagenes/arrow_down.gif /> Se Registro Con Exito",true);
					clearForm('form_db_unidad_medida');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
/*--------------------------------------   BUSCAR ----------------------------------------------------*/
//
//
//
$("#unidad_medida_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/adquisiones/unidad_medida/db/vista.grid_unidad_medida.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidades de Medida', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#unidad_medida_db_nombre_uni").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/unidad_medida/db/sql_unidad_medida.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#unidad_medida_db_nombre_uni").keypress(function(key)
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
							var busq_nombre= jQuery("#unidad_medida_db_nombre_uni").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/adquisiones/unidad_medida/db/sql_unidad_medida.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/adquisiones/unidad_medida/db/sql_unidad_medida.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Unidad Medida','Comentario'],
								colModel:[
									{name:'id_unidad_medida',index:'id_unidad_medida', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:15,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true}				],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('unidad_medida_db_id').value = ret.id_unidad_medida;
									getObj('unidad_medida_db_unidad').value = ret.nombre;
									getObj('unidad_medida_db_comentario').value = ret.comentario;
									getObj('unidad_medida_db_btn_actualizar').style.display='';
									getObj('unidad_medida_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#unidad_medida_db_nombre_uni").focus();
								$('#unidad_medida_db_nombre_uni').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_medida',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#unidad_medida_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/adquisiones/unidad_medida/db/grid_unidad_medida.php", { },
                        function(data)
                        {								
							dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidades de Medida',modal: true,center:false,x:0,y:0,show:false});								
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
								url:'modulos/adquisiones/unidad_medida/db/sql_grid_unidad_medida.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Unidad Medida','Comentario'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'unidad_medida',index:'unidad_medida', width:221,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('unidad_medida_db_id').value = ret.id;
									getObj('unidad_medida_db_unidad').value = ret.unidad_medida;
									getObj('unidad_medida_db_comentario').value = ret.comentario;
									getObj('unidad_medida_db_btn_actualizar').style.display='';
									getObj('unidad_medida_db_btn_guardar').style.display='none';									
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
								sortname: 'id_unidad_medida',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

/*--------------------------------------   ACTULIZAR ----------------------------------------------------*/

$("#unidad_medida_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_db_unidad_medida').jVal())
	{
		$.ajax (
		{
			url: "modulos/adquisiones/unidad_medida/db/sql.actualizar.php",
			data:dataForm('form_db_unidad_medida'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado("<img align='absmiddle' src='imagenes/arrow_down.gif /> Se Actualizo Con Exito",true);
					getObj('unidad_medida_db_btn_actualizar').style.display='none';
					getObj('unidad_medida_db_btn_guardar').style.display='';
					clearForm('form_db_unidad_medida');
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#unidad_medida_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('unidad_medida_db_btn_cancelar').style.display='';
	getObj('unidad_medida_db_btn_actualizar').style.display='none';
	getObj('unidad_medida_db_btn_guardar').style.display='';
	clearForm('form_db_unidad_medida');
});

/*---------------------------------------------  validaciones ----------------------------------------------------------------------------*/
$('#unidad_medida_db_nombre').alpha({allow:'·ÈÌÛ˙¡…Õ”⁄ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>
<div id="botonera">
	<img id="unidad_medida_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="unidad_medida_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="unidad_medida_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="unidad_medida_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" id="form_db_unidad_medida" name="form_db_unidad_medida">
	<table class="cuerpo_formulario">
		<tr>
		<th class="titulo_frame" colspan="2"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Unidad de Medida</th>
		</tr>
		<tr>
			<th>Nombre:</th>
			<td><input name="unidad_medida_db_unidad" type="text" id="unidad_medida_db_unidad" size="60"  
				message="Introduzca un Nombre para la Unidad de Medida." 
				jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄.,-()]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄.,-()]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/></td>
		</tr>
		<tr>
			<th>Comentario:</th>
			<td>
				<textarea name="unidad_medida_db_comentario" cols="60" rows="3" id="unidad_medida_db_comentario" ></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="unidad_medida_db_id" id="unidad_medida_db_id">
</form>