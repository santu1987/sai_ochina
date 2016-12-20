<script type='text/javascript'>
var dialog;
//
//
//
$("#unidad_ejecutora_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/unidad_ejecutora/db/vista.grid_unidad_ejecutora.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Unidades', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#unidad_ejecutora_db_cod").val(); 
					var busq_unidad= jQuery("#unidad_ejecutora_db_unidad_solicitante").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/unidad_ejecutora/db/sql_unidad_ejecutora.php?busq_codigo="+busq_codigo+"&busq_unidad="+busq_unidad,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#unidad_ejecutora_db_cod").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#unidad_ejecutora_db_unidad_solicitante").keypress(function(key)
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
							var busq_codigo= jQuery("#unidad_ejecutora_db_cod").val();
							var busq_unidad= jQuery("#unidad_ejecutora_db_unidad_solicitante").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/unidad_ejecutora/db/sql_unidad_ejecutora.php?busq_codigo="+busq_codigo+"&busq_unidad="+busq_unidad,page:1}).trigger("reloadGrid");
							
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
								url:'modulos/presupuesto/unidad_ejecutora/db/sql_unidad_ejecutora.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Unidad Solicitante','Comentario','Organismo','Jefe Unidad','Nombre','Tipo unidad','Unidad Regional'],
								colModel:[
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_unidad_ejecutora',index:'codigo_unidad_ejecutora', width:20,sortable:false,resizable:false},
									{name:'nombreuni',index:'nombreuni', width:100,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false},
									{name:'id_organismo',index:'id_organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'jefe_unidad',index:'jefe_unidad', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo_unidad',index:'tipo_unidad', width:100,sortable:false,resizable:false,hidden:true},
									{name:'unidad_regional',index:'unidad_regional', width:100,sortable:false,resizable:false,hidden:true}
									
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('unidad_ejecutora_db_id').value = ret.id_unidad_ejecutora;
									getObj('unidad_ejecutora_db_codigo').value = ret.codigo_unidad_ejecutora;
									getObj('unidad_ejecutora_db_nombre').value = ret.nombreuni;	
									getObj('unidad_ejecutora_db_comentario').value = ret.comentario;
									getObj('unidad_ejecutora_db_jefe').value = ret.jefe_unidad;
									getObj('unidad_ejecutora_db_estatus').value = ret.tipo_unidad;
									getObj('unidad_ejecutora_db_estatus2').value = ret.unidad_regional;
									if(ret.tipo_unidad == 0){
										getObj('unidad_ejecutora_db_interna').checked="checked";
									}
									if(ret.tipo_unidad == 1){
										getObj('unidad_ejecutora_db_externa').checked="checked";
									}
									if(ret.unidad_regional == 1){
										getObj('unidad_ejecutora_db_si').checked="checked";
									}
									if(ret.unidad_regional == 0){
										getObj('unidad_ejecutora_db_no').checked="checked";
									}
									getObj('unidad_ejecutora_db_btn_actualizar').style.display='';
									getObj('unidad_ejecutora_db_btn_eliminar').style.display='';
									getObj('unidad_ejecutora_db_btn_guardar').style.display='none';		
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#unidad_ejecutora_db_codigo").focus();
								$('#unidad_ejecutora_db_cod').numeric({allow:''});
								$('#unidad_ejecutora_db_unidad_solicitante').alpha({allow:''});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#unidad_ejecutora_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/unidad_ejecutora/db/grid_unidad_ejecutora.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Unidades',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:730,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/unidad_ejecutora/db/sql_grid_unidad_ejecutora.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Unidad Solicitante','Organismo','Comentario','jefe_unidad','tipo_unidad'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false,},
									{name:'unieje',index:'unieje', width:221,sortable:false,resizable:false},
									{name:'organismo',index:'organismo', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:120,sortable:false,resizable:false},
									{name:'jefe_unidad',index:'jefe_unidad', width:100,sortable:false,resizable:false,hidden:true},
									{name:'tipo_unidad',index:'tipo_unidad', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('unidad_ejecutora_db_id').value = ret.id;
									getObj('unidad_ejecutora_db_codigo').value = ret.codigo;
									getObj('unidad_ejecutora_db_nombre').value = ret.unieje;	
									getObj('unidad_ejecutora_db_comentario').value = ret.comentario;
									getObj('unidad_ejecutora_db_jefe').value = ret.jefe_unidad;
									getObj('unidad_ejecutora_db_estatus').value = ret.tipo_unidad;
									if(ret.tipo_unidad == 0){
										getObj('unidad_ejecutora_db_interna').checked="checked";
									}
									if(ret.tipo_unidad == 1){
										getObj('unidad_ejecutora_db_externa').checked="checked";
									}
									getObj('unidad_ejecutora_db_btn_actualizar').style.display='';
									getObj('unidad_ejecutora_db_btn_eliminar').style.display='';
									getObj('unidad_ejecutora_db_btn_guardar').style.display='none';									
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
								sortname: 'id_unidad_ejecutora',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

$("#unidad_ejecutora_db_btn_guardar").click(function() {
	if($('#form_db_programa').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/unidad_ejecutora/db/sql.unidad_ejecutora.php",
			data:dataForm('form_db_unidad_ejecutora'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_unidad_ejecutora');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('unidad_ejecutora_db_nombre').value="";
					document.form_db_unidad_ejecutora.unidad_ejecutora_db_nombre.focus();
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#unidad_ejecutora_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	/*if($('#form_db_programa').jVal())
	{*/
		$.ajax (
		{
			url: "modulos/presupuesto/unidad_ejecutora/db/sql.actualizar.php",
			data:dataForm('form_db_unidad_ejecutora'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('unidad_ejecutora_db_btn_eliminar').style.display='none';
					getObj('unidad_ejecutora_db_btn_actualizar').style.display='none';
					getObj('unidad_ejecutora_db_btn_guardar').style.display='';
					clearForm('form_db_unidad_ejecutora');
				}
				else if (html=="Existe"){
					setBarraEstado(mensaje[registro_existe]);
					getObj('unidad_ejecutora_db_nombre').value="";
					getObj('unidad_ejecutora_db_nombre').focus();
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	//}
});

$("#unidad_ejecutora_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/presupuesto/unidad_ejecutora/db/sql.eliminar.php",
			data:dataForm('form_db_unidad_ejecutora'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('unidad_ejecutora_db_btn_cancelar').style.display='';
					getObj('unidad_ejecutora_db_btn_eliminar').style.display='none';
					getObj('unidad_ejecutora_db_btn_actualizar').style.display='none';
					getObj('unidad_ejecutora_db_btn_guardar').style.display='';
					clearForm('form_db_unidad_ejecutora');
				}
				else if(html=='Registro Relacionado')
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />Este Registro ya tiene Relacionado un Presupuesto </p></div>",true,true);
				}
			}
		});
	}
});

$("#unidad_ejecutora_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('unidad_ejecutora_db_btn_cancelar').style.display='';
	getObj('unidad_ejecutora_db_btn_actualizar').style.display='none';
	getObj('unidad_ejecutora_db_btn_eliminar').style.display='none';
	getObj('unidad_ejecutora_db_btn_guardar').style.display='';
	clearForm('form_db_unidad_ejecutora');
});
///**************************************************************
function consulta_automatica_unidad_ejecutora()
{
	$.ajax({
			url:"modulos/presupuesto/unidad_ejecutora/db/sql_grid_unidad_codigo.php",
            data:dataForm('form_db_unidad_ejecutora'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
			 	 
       		    var recordset=html.replace('"','');
				if(recordset)
				{
				recordset = recordset.split("*");
				getObj('unidad_ejecutora_db_id').value = recordset[0];
				getObj('unidad_ejecutora_db_nombre').value=recordset[1];
				getObj('unidad_ejecutora_db_comentario').value=recordset[2];
				getObj('unidad_ejecutora_db_jefe').value =recordset[3];
				getObj('unidad_ejecutora_db_estatus').value =recordset[4];
				getObj('unidad_ejecutora_db_estatus2').value =recordset[5];
				getObj('unidad_ejecutora_db_btn_actualizar').style.display='';
				getObj('unidad_ejecutora_db_btn_guardar').style.display='none';
				if(recordset[4] == 0){
					getObj('unidad_ejecutora_db_interna').checked="checked";
									}
				if(recordset[4] == 1){
					getObj('unidad_ejecutora_db_externa').checked="checked";
									}
				if(recordset[5] == 0){
					getObj('unidad_ejecutora_db_no').checked="checked";
									}
				if(recordset[5] == 1){
					getObj('unidad_ejecutora_db_si').checked="checked";
									}
				
				}
				else
			 {  
			   	getObj('unidad_ejecutora_db_id').value ="";
			    getObj('unidad_ejecutora_db_nombre').value="";
				getObj('unidad_ejecutora_db_comentario').value="";
				getObj('unidad_ejecutora_db_jefe').value ="" ;

				}
			 }
		});	 	 
}
//************************************************************

$('#unidad_ejecutora_db_nombre').alpha({allow:'._1234567890- '});
$('#unidad_ejecutora_db_codigo').numeric({allow:''});
//$('#unidad_ejecutora_db_codigo').change(consulta_automatica_unidad_ejecutora);

$("#unidad_ejecutora_db_interna").click(function(){
		getObj('unidad_ejecutora_db_estatus').value="0"
	});
$("#unidad_ejecutora_db_externa").click(function(){
		getObj('unidad_ejecutora_db_estatus').value="1"
	});
$("#unidad_ejecutora_db_si").click(function(){
		getObj('unidad_ejecutora_db_estatus2').value="1"
	});
$("#unidad_ejecutora_db_no").click(function(){
		getObj('unidad_ejecutora_db_estatus2').value="0"
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
</script>
<div id="botonera">
	<img id="unidad_ejecutora_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="unidad_ejecutora_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="unidad_ejecutora_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />		
	<img id="unidad_ejecutora_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="unidad_ejecutora_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>
<div ></div>
<form method="post" name="form_db_unidad_ejecutora" id="form_db_unidad_ejecutora">
	<table class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Unidad Solicitante </th>
		</tr>
	<tr>
		<th>C&oacute;digo:				</th>
		<td ><input id="unidad_ejecutora_db_codigo" name="unidad_ejecutora_db_codigo" type="text" maxlength="4"
										    onchange="consulta_automatica_unidad_ejecutora" onclick="consulta_automatica_unidad_ejecutora"
										   	message="Introduzca un Codigo para la Unidad Ejecutora."  size="5"
										   	jVal="{valid:/^[0-9]]{1,60}$/, message:'Codigo Invalido', styleType:'cover'}"
			jValKey="{valid:/^[0-9]]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" 
										   	/></td>
	</tr>
	<tr>
		<th>Nombre:</th>
		<td ><input name="unidad_ejecutora_db_nombre" id="unidad_ejecutora_db_nombre" type="text"   style="width:62ex;"
			message="Introduzca un Nombre para la Unidad Ejecutora." 
			jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ 1234567890-_]]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jValKey="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ 1234567890-_]]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"></td>
	</tr>
	<tr>
		<th>Director:</th>
	  	<td ><input name="unidad_ejecutora_db_jefe" type="text" id="unidad_ejecutora_db_jefe" size="60" style="width:62ex;" /></td>
	</tr>
	<tr>
			<th>Comentario:</th>
			<td ><textarea name="unidad_ejecutora_db_comentario" id="unidad_ejecutora_db_comentario" cols="65" rows="3" message="Introduzca un Comentario para la Unidad Ejecutora."></textarea></td>
	</tr>
	<tr>
			<th colspan="2">Unidad Interna<input type="radio" name="unidad_ejecutora_db_tipo" id="unidad_ejecutora_db_interna" checked="checked" value="0" />&nbsp;&nbsp;Unidad Externa<input type="radio" name="unidad_ejecutora_db_tipo" id="unidad_ejecutora_db_externa"  value="1" />
			<input type="hidden" name="unidad_ejecutora_db_estatus" id="unidad_ejecutora_db_estatus" value="0" />
			</th>
	</tr>
    <tr>
			<th colspan="2">Unidad Regonal?: Si<input type="radio" name="unidad_ejecutora_db_unidad_regional" id="unidad_ejecutora_db_si"  value="1" />&nbsp;&nbsp;No<input type="radio" name="unidad_ejecutora_db_unidad_regional" id="unidad_ejecutora_db_no"  checked="checked" value="0" />
			<input type="hidden" name="unidad_ejecutora_db_estatus2" id="unidad_ejecutora_db_estatus2" value="0" />
			</th>
	</tr>
		
			
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="unidad_ejecutora_db_id" id="unidad_ejecutora_db_id" />
</form>
