<script>
var dialog;
//
//
//

$("#accion_centralizada_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/accion_centralizada/db/vista.grid_accion_centralizada.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Acci&oacute;n Centarlizada', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#accion_centralizada_db_codigo_acc_cen").val(); 
					var busq_nombre= jQuery("#accion_centralizada_db_nombre_acc_cen").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#accion_centralizada_db_codigo_acc_cen").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#accion_centralizada_db_nombre_acc_cen").keypress(function(key)
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
							var busq_codigo= jQuery("#accion_centralizada_db_codigo_acc_cen").val();
							var busq_nombre= jQuery("#accion_centralizada_db_nombre_acc_cen").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}

			}
		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Accion Centralizada','Accion Centralizada2','Comentario','Jefe de Proyecto','id_jefe_proyecto'],
								colModel:[
									{name:'id_accion_central',index:'id_accion_central', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo_accion_central',index:'codigo_accion_central', width:20,sortable:false,resizable:false},
									{name:'denominacion',index:'denominacion', width:100,sortable:false,resizable:false},
									{name:'denominacion2',index:'denominacion2', width:100,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:100,sortable:false,resizable:false,hidden:true},
									{name:'nombre_jefe_proyecto',index:'nombre_jefe_proyecto', width:100,sortable:false,resizable:false},
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:100,sortable:false,resizable:false,hidden:true}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('accion_centralizada_db_id').value = ret.id_accion_central;
									getObj('accion_centralizada_db_codigo').value = ret.codigo_accion_central;
									getObj('accion_centralizada_db_nombre').value = ret.denominacion2;
									getObj('accion_centralizada_db_comentario').value = ret.comentario;
									getObj('accion_centralizada_db_jefe_accion').value = ret.nombre_jefe_proyecto;
									getObj('accion_centralizada_db_jefe_accion_id').value = ret.id_jefe_proyecto;
									getObj('accion_centralizada_db_btn_actualizar').style.display='';
									getObj('accion_centralizada_db_btn_eliminar').style.display='';
									getObj('accion_centralizada_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#accion_centralizada_db_codigo_acc_cen").focus();
								$('#accion_centralizada_db_codigo_acc_cen').numeric({allow:''});
								$('#accion_centralizada_db_nombre_acc_cen').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_accion_central',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});
//
//
//
/*$("#accion_centralizada_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_centralizada/db/grid_accion_centralizada.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Acci&oacute;n Centarlizada',modal: true,center:false,x:0,y:0,show:false});								
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
								url:'modulos/presupuesto/accion_centralizada/db/sql_grid_accion_centralizada.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Codigo','Accion Centralizada','Accion Centralizada','Comentario','Jefe de Proyecto','id_jefe_proyecto'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigo',index:'codigo', width:50,sortable:false,resizable:false},
									{name:'accionli',index:'accionli', width:221,sortable:false,resizable:false},
									{name:'accion',index:'accion', width:221,sortable:false,resizable:false,hidden:true},
									{name:'comentario',index:'comentario', width:110,sortable:false,resizable:false,hidden:true},
									{name:'jefe',index:'jefe', width:110,sortable:false,resizable:false},
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:110,sortable:false,resizable:false,hidden:true}							
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('accion_centralizada_db_id').value = ret.id;
									getObj('accion_centralizada_db_codigo').value = ret.codigo;
									getObj('accion_centralizada_db_nombre').value = ret.accion;
									getObj('accion_centralizada_db_comentario').value = ret.comentario;
									getObj('accion_centralizada_db_jefe_accion').value = ret.jefe;
									getObj('accion_centralizada_db_jefe_accion_id').value = ret.id_jefe_proyecto;
									getObj('accion_centralizada_db_btn_actualizar').style.display='';
									getObj('accion_centralizada_db_btn_eliminar').style.display='';
									getObj('accion_centralizada_db_btn_guardar').style.display='none';									
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
								sortname: 'id_accion_central',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
*/
$("#accion_centralizada_db_btn_guardar").click(function() {
	if($('#form_db_accion_centralizada').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
		url: "modulos/presupuesto/accion_centralizada/db/sql.accion_centralizada.php",
			data:dataForm('form_db_accion_centralizada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_accion_centralizada');
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj(accion_centralizada_db_nombre).value="";
					getObj(accion_centralizada_db_nombre).focus();
				}
				else
				{
					//setBarraEstado(html);
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			}
		});
	}
});

$("#accion_centralizada_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_accion_centralizada').jVal())
	{
		$.ajax (
		{
			url: "modulos/presupuesto/accion_centralizada/db/sql.actualizar.php",
			data:dataForm('form_db_accion_centralizada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('accion_centralizada_db_btn_actualizar').style.display='none';
					getObj('accion_centralizada_db_btn_eliminar').style.display='none';
					getObj('accion_centralizada_db_btn_guardar').style.display='';
					clearForm('form_db_accion_centralizada');
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('accion_centralizada_db_nombre').value="";
					getObj('accion_centralizada_db_nombre').focus();
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#accion_centralizada_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/presupuesto/accion_centralizada/db/sql.eliminar.php",
			data:dataForm('form_db_accion_centralizada'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Ok")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('accion_centralizada_db_btn_cancelar').style.display='';
					getObj('accion_centralizada_db_btn_eliminar').style.display='none';
					getObj('accion_centralizada_db_btn_actualizar').style.display='none';
					getObj('accion_centralizada_db_btn_guardar').style.display='';
					clearForm('form_db_accion_centralizada');
				}
				else if (html=="bloqueado")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_red.png />LA ACCION CENTRALIZADA YA TIENE ACCIONES ESPECIFICAS ASIGNADAS</p></div>",true,true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
//-----------------------------------------------------------------------------------------------------
//
//
//

$("#accion_centralizada_db_btn_consultar_accion").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/presupuesto/accion_centralizada/db/vista.grid_accion_centralizada2.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Jefe de Acción Centralizada', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_codigo= jQuery("#accion_centralizada_db_codigo2_acc_cen").val(); 
					var busq_nombre= jQuery("#accion_centralizada_db_nombre2_acc_cen").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada2.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#accion_centralizada_db_codigo2_acc_cen").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_dosearch();
												
					});
				$("#accion_centralizada_db_nombre2_acc_cen").keypress(function(key)
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
							var busq_codigo= jQuery("#accion_centralizada_db_codigo2_acc_cen").val();
							var busq_nombre= jQuery("#accion_centralizada_db_nombre2_acc_cen").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada2.php?busq_codigo="+busq_codigo+"&busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_centralizada/db/sql_accion_centralizada2.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Jefe de Acción Centralizada'],
								colModel:[
									{name:'id_jefe_proyecto',index:'id_jefe_proyecto', width:20,sortable:false,resizable:false},
									{name:'jefe',index:'jefe', width:100,sortable:false,resizable:false}

								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
								getObj('accion_centralizada_db_jefe_accion_id').value = ret.id_jefe_proyecto;
									getObj('accion_centralizada_db_jefe_accion').value = ret.jefe;
									dialog.hideAndUnload();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								//$("#accion_centralizada_db_codigo2_acc_cen").focus();
								$('#accion_centralizada_db_codigo2_acc_cen').numeric({allow:''});
								$('#accion_centralizada_db_nombre2_acc_cen').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_accion_central',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

//
//
//
/*$("#accion_centralizada_db_btn_consultar_accion").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/presupuesto/accion_centralizada/db/grid_accion_centralizada.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Jefe de Acción Centralizada', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:400,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/presupuesto/accion_centralizada/db/cmb.sql.jefe_proyecto.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo', 'Jefe de Acción Centralizada'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false},
									{name:'jefe',index:'jefe', width:200,sortable:false,resizable:false}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('accion_centralizada_db_jefe_accion_id').value = ret.id;
									getObj('accion_centralizada_db_jefe_accion').value = ret.jefe;
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
								sortname: 'id_jefe_proyecto',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/

///**************************************************************
function consulta_automatica_accion_central()
{
	$.ajax({
			url:"modulos/presupuesto/accion_centralizada/db/sql_grid_accion_codigo.php",
            data:dataForm('form_db_accion_centralizada'), 
			type:'POST',
			cache: false,
			 success:function(html)
			 {
       		    var recordset=html;
				if(recordset)
				{
				recordset = recordset.split(".");
				getObj('accion_centralizada_db_id').value = recordset[0];
				getObj('accion_centralizada_db_jefe_accion_id').value=recordset[1];
				getObj('accion_centralizada_db_jefe_accion').value=recordset[2];
				getObj('accion_centralizada_db_nombre').value =recordset[3];
				getObj('accion_centralizada_db_comentario').value=recordset[4];
				getObj('accion_centralizada_db_btn_actualizar').style.display='';
				getObj('accion_centralizada_db_btn_guardar').style.display='none';
				}
				else
			 {  
			   	getObj('accion_centralizada_db_id').value ="";
			    getObj('accion_centralizada_db_jefe_accion_id').value="";
				getObj('accion_centralizada_db_jefe_accion').value="";
				getObj('accion_centralizada_db_nombre').value ="" ;
			 	getObj('accion_centralizada_db_comentario').value="" ;
				}
			 }
		});	 	 
}
//************************************************************


$("#accion_centralizada_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('accion_centralizada_db_btn_cancelar').style.display='';
	getObj('accion_centralizada_db_btn_actualizar').style.display='none';
	getObj('accion_centralizada_db_btn_eliminar').style.display='none';
	getObj('accion_centralizada_db_btn_guardar').style.display='';
	clearForm('form_db_accion_centralizada');
});


$('#accion_centralizada_db_nombre').alpha({allow:'._1234567890- áéíóúÁÉÍÓÚ(),'});
$('#accion_centralizada_db_codigo').numeric({allow:''});
$('#accion_centralizada_db_codigo').change(consulta_automatica_accion_central);


$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});

</script>

<div id="botonera">
	<img id="accion_centralizada_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="accion_centralizada_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
	<img id="accion_centralizada_db_btn_consultar" name="accion_centralizada_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif"onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="accion_centralizada_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="accion_centralizada_db_btn_guardar" class="btn_guardar" src="imagenes/null.gif"  />
</div>

<form method="post" id="form_db_accion_centralizada" name="form_db_accion_centralizada">
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Acci&oacute;n Centralizada </th>
	</tr>
	<tr>
		<th>A&ntilde;o:				</th>
		<td ><?=date('Y')+1?></td>
	</tr>
	<tr>
		<th>C&oacute;digo:   </th>
		<td><input name="accion_centralizada_db_codigo" type="text" id="accion_centralizada_db_codigo"  maxlength="6"
							onchange="consulta_automatica_accion_central" onclick="consulta_automatica_accion_central"
							message="Introduzca un Codigo de 5 digito para el Accion Centralizada."  size="6"
							jVal="{valid:/^[0-9]{4,6}$/, message:'Codigo Invalido', styleType:'cover'}"
							jValKey="{valid:/[0-9-]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" 
							></td>
	</tr>
	<tr>
		<th>Denominaci&oacute;n:</th>
		<td>
			<table width="100%" class="clear">
				<tr>
					<td>
						<input name="accion_centralizada_db_nombre" type="text" id="accion_centralizada_db_nombre"    size="90" maxlength="100"
						message="Introduzca un Nombre para el Accion Centralizada." 
						jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚ1234567890-().',_]{1,100}$/, message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{a-zA-Z0-9 áéíóúÁÉÍÓÚ1234567890-_.',]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
						>
					</td>
				</tr>
			</table>
		</td>
	</tr>
		<tr>
		  <th>Responsable:</th>
		   <td >
			<table width="100%" class="clear">
				<tr>
					<td><input name="accion_centralizada_db_jefe_accion" type="text" id="accion_centralizada_db_jefe_accion" size="90" readonly="readonly"  
		    message="Elija un jefe de acción centralizada" 
			 jVal="{valid:/^[a-zA-Z0-9 ()áéíóúÁÉÍÓÚ1234567890.,-_]{1,100}$/, message:'Nombre de Jefe Invalido', styleType:'cover'}"
		   		/></td>
					<td><img class="btn_consulta_emergente" id="accion_centralizada_db_btn_consultar_accion" src="imagenes/null.gif" />
						<input type="hidden" name="accion_centralizada_db_jefe_accion_id" id="accion_centralizada_db_jefe_accion_id">
					</td>
				</tr>
			</table>
	

       </tr>
	<tr>
		<th>Observaci&oacute;n:	</th><td ><textarea name="accion_centralizada_db_comentario" cols="87" id="accion_centralizada_db_comentario" message="Introduzca un Comentario para el Accion Centralizada." ></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	<tr>			
</table>
<input type="hidden" name="accion_centralizada_db_id" id="accion_centralizada_db_id" />
</form>