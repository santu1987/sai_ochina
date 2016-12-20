<script type='text/javascript'>
var dialog;
$("#sareta_numero_control_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/sareta/numero_control/db/grid_numero_control.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Numero de Control', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#parametro_cxp_db_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/numero_control/db/sql_grid_numero_control.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();

				var timeoutHnd; 
				var flAuto = true;
				$("#parametro_cxp_db_nombre").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						programa_cxp_dosearch();
												
					});
					function programa_cxp_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(programa_cxp_gridReload,500)
										}
						function programa_cxp_gridReload()
						{
							var busq_nombre= jQuery("#parametro_cxp_db_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/sareta/numero_control/db/sql_grid_numero_control.php?nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							url="modulos/sareta/numero_control/db/sql_grid_numero_control.php?nombre="+busq_nombre;
							//alert(url);
						}
			}

		});
		

		
		function crear_grid()
						{
							
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:800,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/sareta/numero_control/db/sql_grid_numero_control.php?nd='+nd,
								datatype: "json",
								colNames:['Codigo','Descripción','','N&deg; Inicial','N&deg; Final','N&deg; Actual','Estatus','Comentario','con'],
								colModel:[
									{name:'id',index:'id', width:220,sortable:false,resizable:false,hidden:true},
									{name:'descripcion',index:'descripcion', width:220,sortable:false,resizable:false},
									{name:'des_paso',index:'des_paso', width:220,sortable:false,resizable:false,hidden:true},
									{name:'numero_inicial',index:'numero_inicial', width:220,sortable:false,resizable:false},
									{name:'numero_final',index:'numero_final', width:220,sortable:false,resizable:false},
									{name:'numero_actual',index:'numero_actual', width:220,sortable:false,resizable:false},
									{name:'estatus',index:'estatus', width:220,sortable:false,resizable:false},
									{name:'comentario',index:'comentario', width:220,sortable:false,resizable:false},
									{name:'com',index:'com', width:220,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_numero_control').value = ret.id;
									getObj('sareta_numero_control_db_vista_descripcion').value = ret.des_paso;
									getObj('sareta_numero_control_db_vista_Ninicial').value = ret.numero_inicial;
									getObj('sareta_numero_control_db_vista_Nfinal').value = ret.numero_final;
									getObj('sareta_numero_control_db_vista_Nactual').value = ret.numero_actual;
									if(ret.estatus=="Activo"){
									getObj('sareta_numero_control_db_vista_activo').selectedIndex =0;
									}else{getObj('sareta_numero_control_db_vista_activo').selectedIndex =1;
									}
									getObj('sareta_numero_control_db_vista_comentario').value = ret.com;
									getObj('sareta_numero_control_db_btn_cancelar').style.display='';
									getObj('sareta_numero_control_db_btn_actualizar').style.display='';
									getObj('sareta_numero_control_db_btn_eliminar').style.display='';
									getObj('sareta_numero_control_db_btn_guardar').style.display='none';
									dialog.hideAndUnload();
									$('#form_db_numero_control').jVal();
					 			},

								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#parametro_cxp_db_nombre").focus();
								$('#parametro_cxp_db_nombre').alpha({allow:'0123456789 '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'id_parametros_cxp',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});

$("#sareta_numero_control_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_db_numero_control').jVal())
	{
		$.ajax (
		{
			url: "modulos/sareta/numero_control/db/sql.actualizar.php",
			data:dataForm('form_db_numero_control'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true,function(){
						getObj('sareta_numero_control_db_btn_eliminar').style.display='none';
						getObj('sareta_numero_control_db_btn_actualizar').style.display='none';
						getObj('sareta_numero_control_db_btn_guardar').style.display='';
						clearForm('form_db_numero_control');
						getObj('sareta_numero_control_db_vista_activo').selectedIndex =0;
						
					});															
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);
				}
			}
		});
	}
});

$("#sareta_numero_control_db_btn_guardar").click(function() {
	if($('#form_db_numero_control').jVal())
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax (
		{
			url: "modulos/sareta/numero_control/db/sql.registrar.php",
			data:dataForm('form_db_numero_control'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true,function(){
						clearForm('form_db_numero_control');
						getObj('sareta_numero_control_db_vista_activo').selectedIndex =0;
					});					
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				else
				{
					setBarraEstado(html,true,true);
				}
			}
		});
	}
});
$("#sareta_numero_control_db_btn_eliminar").click(function() {
  if (getObj('vista_id_numero_control').value !=""){
	if(confirm("¿Desea elminar el registro seleccionado?")) {
		$.ajax ({
			url: "modulos/sareta/numero_control/db/sql.eliminar.php",
			data:dataForm('form_db_numero_control'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('sareta_numero_control_db_btn_eliminar').style.display='none';
					getObj('sareta_numero_control_db_btn_actualizar').style.display='none';
					getObj('sareta_numero_control_db_btn_guardar').style.display='';
					clearForm('form_db_numero_control');
					getObj('sareta_numero_control_db_vista_activo').selectedIndex =0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
				else if (html=="NoPuedeSerEliminado")
				{
				setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png />El registro no puede ser eliminado por ser el numero de control activo.</p></div>",true,true);
				}
				else if (html=="Foranio")
				{
					setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/iconos/folder_important.png /> Exite una Relacion con este Numero de Control</p></div>",true,true); 
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

$("#sareta_numero_control_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('sareta_numero_control_db_btn_cancelar').style.display='';
	getObj('sareta_numero_control_db_btn_eliminar').style.display='none';
	getObj('sareta_numero_control_db_btn_actualizar').style.display='none';
	getObj('sareta_numero_control_db_btn_guardar').style.display='';
	clearForm('form_db_numero_control');
	getObj('sareta_numero_control_db_vista_activo').selectedIndex =0;
});


	
$('#sareta_numero_control_db_vista_Ninicial').numeric({allow:' 0123456789'});
$('#sareta_numero_control_db_vista_Nfinal').numeric({allow:' 0123456789'});
$('#sareta_numero_control_db_vista_Nactual').numeric({allow:' 0123456789'});


</script>


<div id="botonera">
	<img id="sareta_numero_control_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />
    <img id="sareta_numero_control_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
	<img id="sareta_numero_control_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="sareta_numero_control_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="sareta_numero_control_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
    
</div>

<form method="post" id="form_db_numero_control" name="form_db_numero_control">
<input type="hidden" name="vista_id_numero_control" id="vista_id_numero_control" />
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4">
        <img src="imagenes/iconos/desktop24x24.png" 
        style="padding-right:5px;" align="absmiddle" />Registrar Numero Control
        </th>
	</tr>
	<tr>
	<th>Descripci&oacute;n:		</th>	
	<td><input name="sareta_numero_control_db_vista_descripcion" type="text" 
    id="sareta_numero_control_db_vista_descripcion"   value="" size="40" maxlength="30"  
		message="Introduzca una Descripci&oacute;n." 
		jval="{valid:/^[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]{1,30}$/, 
        message:'Nombre Invalido', styleType:'cover'}"
		jvalkey="{valid:/[a-zA-Z 0-9 áéíóúÁÉÍÓÚñ]/, cFunc:'alert',
        cArgs:['Descripci&oacute;n: '+$(this).val()]}" /></td>
	</tr>
    <tr>
		<th>Nº Inicial:		</th>	
		<td>
		<input name="sareta_numero_control_db_vista_Ninicial" type="text" 
        id="sareta_numero_control_db_vista_Ninicial"   value="" size="10" maxlength="9"
		message="Introduzca un Numero Inicial. Menor al N&deg; Final y Menor al N&deg; Actual" 
		jVal="{valid:/^[0-9]{1,9}$/, message:'Numero Final', styleType:'cover'}"
		jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Inicial: '+$(this).val()]}" />
		</td>
	</tr>	
	<tr>
	<tr>
		<th>Nº Final:</th>			
        <td ><input name="sareta_numero_control_db_vista_Nfinal" type="text" 
        id="sareta_numero_control_db_vista_Nfinal"   value="" size="10" maxlength="9"
		message="Introduzca un Numero Final. Mayor al N&deg; Inicial y Mayor al N&deg; Actual" 
		jval="{valid:/^[0-9]{1,9}$/, message:'Numero Final Invalido', styleType:'cover'}"
		jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Final: '+$(this).val()]}" />
        </td>
	</tr>
    <tr>
	<th>Nº Actual: </th>	
    <td><input name="sareta_numero_control_db_vista_Nactual" type="text" 
    id="sareta_numero_control_db_vista_Nactual"   value="" size="10" maxlength="9"
	message="Introduzca un Numero Actual.  Mayor al N&deg; Inicial y Menor al N&deg;</h6> Final" 
	jval="{valid:/^[0-9]{1,9}$/, message:'Numero Actual Invalido', styleType:'cover'}"
	jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Actual: '+$(this).val()]}" />
    </td>
	</tr>
    <tr>
	<th><strong>Estatus:</strong></th>	
	<td><select name="sareta_numero_control_db_vista_activo" id="sareta_numero_control_db_vista_activo">
  <option value="true" >Activo</option>
  <option value="false">Inactivo</option>
</select>
	</td>
	</tr>	
    <tr>
			<th>Comentario:</th>
			<td>	<textarea name="sareta_numero_control_db_vista_comentario" cols="60" 
            id="sareta_numero_control_db_vista_comentario" 
            message="Introduzca un Comentario"></textarea>
            </td>
	</tr>	
 
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>