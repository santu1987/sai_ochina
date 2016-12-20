<?php
session_start();
?>
<script type='text/javascript'>
var dialog;
$("#tesoreria_banco_db_btn_consultar").click(function() {
	/*var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/tesoreria/banco/db/grid_banco.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Banco',modal: true,center:false,x:0,y:0,show:false});								
								setTimeout(crear_grid,100);								
                        });*/
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/tesoreria/banco/db/grid_banco.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente De Bancos', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_banco= jQuery("#tesoreria_busqueda_bancos").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#tesoreria_busqueda_bancos").keypress(function(key)
				{
						if(key.keyCode==27){dialog.hideAndUnload();}
						consulta_rel_banco_us_dosearch();
					});
				
						function consulta_rel_banco_us_dosearch()
					{
						if(!flAuto) return; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(consulta_rel_banco_us_gridReload,500)
										}
						function consulta_rel_banco_us_gridReload()
						{
							var busq_banco= jQuery("#tesoreria_busqueda_bancos").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/tesoreria/banco/db/sql_grid_banco.php?busq_banco="+busq_banco,page:1}).trigger("reloadGrid"); 
							//url="modulos/tesoreria/movimientos/db/sql_grid_banco.php";
						}

			}
		});	
									

						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:600,
								height:250,
								recordtext:"Registro(s)",
								loadtext: "Recuperando InformaciÛn del Servidor",		
								url:'modulos/tesoreria/banco/db/sql_grid_banco.php?nd='+nd,
								datatype: "json",
								colNames:['Id','Id_organismo','Nombre','Sucursal','Direccion','Codigo ¡rea','Telefono','Fax','Persona Contacto','Cargo Contacto','Email','Pagina','Estatus','Comentarios'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'idorganismo',index:'idorganismo', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:70,sortable:false,resizable:false},
									{name:'sucursal' ,index:'sucursal', width:70,sortable:false,resizable:false,hidden:true},
									{name:'direccion',index:'direccion', width:50,sortable:false,resizable:false,hidden:true},
									{name:'codigoarea',index:'codigoarea', width:50,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:50,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax',width:50,sortable:false,resizable:false,hidden:true},
									{name:'persona_contacto',index:'persona_contacto', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cargo_contacto',index:'cargo_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'email_contacto',index:'email_contacto', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'pagina_banco',index:'pagina_banco', width:50,sortable:false,resizable:false,hidden:true},
                                    {name:'estatus',index:'estatus', width:50,sortable:false,resizable:false},
									{name:'comentarios',index:'comentarios', width:100,sortable:false,resizable:false,hidden:true }
									    ],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: 'utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('tesoreria_vista_banco').value=ret.id;
									nombre=ret.nombre
									nombre=nombre.split("-");
									getObj('tesoreria_banco_db_nombre').value=nombre[0];
									getObj('tesoreria_banco_db_sucursal').value=ret.sucursal;
									getObj('tesoreria_banco_db_direccion').value=ret.direccion;
									getObj('tesoreria_banco_db_codigoarea').value=ret.codigoarea;
									getObj('tesoreria_banco_db_telefono').value=ret.telefono;
									getObj('tesoreria_banco_db_fax').value=ret.fax;
									getObj('tesoreria_banco_db_persona_contacto').value=ret.persona_contacto;
									getObj('tesoreria_banco_db_cargo_contacto').value=ret.cargo_contacto;
									getObj('tesoreria_banco_db_email_contacto').value=ret.email_contacto;
									getObj('tesoreria_banco_db_comentarios').value=ret.comentarios;
									
									getObj('tesoreria_banco_db_pagina_web').value=ret.pagina_banco;
										if(ret.estatus=='Activo')
						     	    { 
										getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
										getObj('tesoreria_banco_db_estatus').value="1";
									}else
									{
									getObj('tesoreria_banco_db_estatus_opt_inact').checked="checked";
									getObj('tesoreria_banco_db_estatus').value="2";
									}
									getObj('tesoreria_banco_db_btn_cancelar').style.display='';
									getObj('tesoreria_banco_db_btn_actualizar').style.display='';
									getObj('tesoreria_banco_db_btn_eliminar').style.display='';
									getObj('tesoreria_banco_db_btn_guardar').style.display='none';									
									
									dialog.hideAndUnload();
									$('#form_tesoreria_db_banco').jVal();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								},
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'estatus',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});

$("#tesoreria_banco_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#').jVal())
	{
		$.ajax (
		{
			url: "modulos/tesoreria/banco/db/sql.actualizar.php",
			data:dataForm('form_tesoreria_db_banco'),
			type:'POST',
			cache: false,
			success: function(html)
			{alert(html);
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					setBarraEstado("");
					getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
					clearForm('form_tesoreria_db_banco');
					getObj('tesoreria_banco_db_btn_actualizar').style.display='none';
					getObj('tesoreria_banco_db_btn_guardar').style.display='';
					getObj('tesoreria_banco_db_btn_cancelar').style.display='';
					getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_db_estatus').value="1";	
					
														
					}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('tesoreria_banco_db_nombre').value="";
					getObj('tesoreria_banco_db_sucursal').value="";
					//getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
					//clearForm('form_tesoreria_db_banco');	
					//getObj('tesoreria_banco_db_btn_actualizar').style.display='none';
					//getObj('tesoreria_banco_db_btn_guardar').style.display='';
					//getObj('tesoreria_banco_db_btn_cancelar').style.display='';
					//getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
					//getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
					//getObj('tesoreria_banco_db_estatus').value="1";	
					
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#tesoreria_banco_db_btn_guardar").click(function() {
	if($('#form_tesoreria_db_banco').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/tesoreria/banco/db/sql.registrar.php",
			data:dataForm('form_tesoreria_db_banco'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_tesoreria_db_banco');
					getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_db_estatus').value="1";			
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					//clearForm('form_tesoreria_db_banco');
					getObj('tesoreria_banco_db_nombre').value="";
					getObj('tesoreria_banco_db_sucursal').value="";
					getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
					getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
					getObj('tesoreria_banco_db_estatus').value="1";			
								}
					else
				{
					alert(html);
					getObj('tesoreria_banco_db_direccion').value=html;
					setBarraEstado(mensaje[la_transacion_no_se_efectuo],true,true);
				}
			
			}
		});
	}
});
//-------------------------------------------------------------------------------------------------------------------------------------------------------
$("#tesoreria_banco_db_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/tesoreria/banco/db/sql.eliminar.php",
			data:dataForm('form_tesoreria_db_banco'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
				setBarraEstado("");
				getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
				getObj('tesoreria_banco_db_btn_actualizar').style.display='none';
				getObj('tesoreria_banco_db_btn_guardar').style.display='';
				getObj('tesoreria_banco_db_btn_consultar').style.display='';
				clearForm('form_tesoreria_db_banco');
				getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
				getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
				getObj('tesoreria_banco_db_estatus').value="1";					
				}
				else
				{
					setBarraEstado(mensaje[relacion_existe],true,true);
				}
			}
		});
	}
});
//-----------------------------------------------------------------------------------------------------------------------------------------

$("#tesoreria_banco_db_btn_cancelar").click(function() {
	setBarraEstado("");
    getObj('tesoreria_banco_db_btn_eliminar').style.display='none';
	getObj('tesoreria_banco_db_btn_actualizar').style.display='none';
	getObj('tesoreria_banco_db_btn_guardar').style.display='';
	getObj('tesoreria_banco_db_btn_consultar').style.display='';
	getObj('tesoreria_banco_db_estatus_opt_act').checked="checked";
	clearForm('form_tesoreria_db_banco');
	getObj('tesoreria_banco_db_estatus_opt_inact').checked="";
	getObj('tesoreria_banco_db_estatus').value="1";	
	
	
	
});
function medicion_caracteres()
{
	variable=getObj('tesoreria_banco_db_codigoarea').value;
	valor=variable.length;
	if(valor=='4'){	document.getElementById('tesoreria_banco_db_telefono').focus(); }
	
}
$("#tesoreria_banco_db_estatus_opt_act").click(function(){
		getObj('tesoreria_banco_db_estatus').value="1"
	});
$("#tesoreria_banco_db_estatus_opt_inact").click(function(){
		getObj('tesoreria_banco_db_estatus').value="2"
	});
</script>


<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>

$('#tesoreria_banco_db_telefono').numeric({allow:'/-'});
$('#tesoreria_banco_db_codigoarea').numeric({allow:'/-'});
$('#tesoreria_banco_db_fax').numeric({allow:'/-'});
$('#tesoreria_banco_db_nombre').alphanumeric({allow:' ·ÈÌÛ˙ƒ…Õ”⁄. '});
$('#tesoreria_banco_db_sucursal').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#tesoreria_banco_db_persona_contacto').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$("input, select, textarea").bind("focus", function(){
	/////////////////////////////
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	
</script>

 <div id="botonera">
	<img src="imagenes/null.gif" width="22" class="btn_cancelar" id="tesoreria_banco_db_btn_cancelar"  />
    <img id="tesoreria_banco_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>
   	<img id="tesoreria_banco_db_btn_consultar" class="btn_consultar"src="imagenes/null.gif" />
	<img id="tesoreria_banco_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
<img id="tesoreria_banco_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" /></div>
	</div>
<form method="post" id="form_tesoreria_db_banco" name="form_tesoreria_db_banco">
<input type="hidden"  id="tesoreria_vista_banco" name="tesoreria_vista_banco"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Registrar Banco  </th>
	</tr>
	
	<tr>
		<th>Nombre:		</th>	
	    <td>	
		<input name="tesoreria_banco_db_nombre" type="text" id="tesoreria_banco_db_nombre" size="40" maxlength="60" message="Introduzca el Nombre del Banco. Ejem: ''Banco Venezuela.'' " jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò1234567890.]{1,30}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò1234567890.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]} />
							   </td>
<!-- "-->                               
   </tr>
   <tr>
		<th>Sucursal:		</th>	
	    <td>	
		<input name="tesoreria_banco_db_sucursal" type="text" id="tesoreria_banco_db_sucursal" size="40" maxlength="60" message="Introduzca el nombre de la sucursal bancaria. "  />
 <!--  /*    jVal="{valid:/^[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]{1,30}$/,message:'Nombre Invalido', styleType:'cover'}"
						jValKey="{valid:/[a-zA-Z ·ÈÌÛ˙¡…Õ”⁄Ò.]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /> */-->
      </td>
   </tr>
   <tr>
		<th>Direcci&oacute;n:</th>
		<td><textarea  name="tesoreria_banco_db_direccion" cols="60" id="tesoreria_banco_db_direccion" message="Introduzca una direcciÛn."></textarea>		</td>
	</tr>
	<tr>
		<th>Tel&eacute;fono:</th>
	    <td>
		    	<input name="tesoreria_banco_db_codigoarea"  onkeypress="medicion_caracteres()"  onkeydown="medicion_caracteres()" type="text" id="tesoreria_banco_db_codigoarea"  size="6"  maxlength="4"
				/>
		    	<input name="tesoreria_banco_db_telefono" type="text" id="tesoreria_banco_db_telefono" size="27" maxlength="7" message="Introduzca el N˙mero de TelÈfono ." 
				/></td>
	</tr>	
	<tr>
		<th>Fax:</th>
	    <td><input name="tesoreria_banco_db_fax" type="text" id="tesoreria_banco_db_fax" size="40" maxlength="11" message="Introduzca el N˙mero de fax ." 
							/></td>
	</tr><tr>
			<th>Persona Contacto:</th>
			<td><input name="tesoreria_banco_db_persona_contacto" type="text" id="tesoreria_banco_db_persona_contacto" size="40" maxlength="40"
				message="Introduzca el Nombre de la Persona de Contacto con el banco." 
				/></td>
		</tr>
		<tr>
			<th>Cargo Contacto:</th>
			<td><input name="tesoreria_banco_db_cargo_contacto" type="text" id="tesoreria_banco_db_cargo_contacto" size="40" maxlength="25" 
				message="Introduzca el Cargo de la Persona de Contacto con el banco."  
				/>
			</td>
		</tr>
		<tr>
			<th>Email Contacto:</th>
			<td><input name="tesoreria_banco_db_email_contacto" type="text" id="tesoreria_banco_db_email_contacto" size="40" maxlength="50" 
				message="Introduzca el Email de la Persona de Contacto con el Banco."
				j/></td><!--Val="{valid:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/, message:'Direcci&oacute;n de Email Invalidad', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z0-9._%+-@]/, cFunc:'alert', cArgs:['Email Direcci&oacute;n: '+$(this).val()]}" -->
		</tr>
		<tr>
			<th>Pagina Web:</th>
			<td><input name="tesoreria_banco_db_pagina_web" type="text" id="tesoreria_banco_db_pagina_web" size="40" maxlength="50" 
				message="Introduzca el Pagina Web si posee."/></td>
		</tr>
		 <tr>
		<th>Comentarios:</th>
		<td><textarea  name="tesoreria_banco_db_comentarios" cols="60" id="tesoreria_banco_db_comentarios" message="Introduzca un comentario."></textarea>		</td>
	</tr>
		<tr> 
		<th>Estatus:</th>
		<td>
		   	<input id="tesoreria_banco_db_estatus_opt_act" name="tesoreria_banco_db_estatus_opt"  type="radio" value="1" checked="checked" />Activo
	      	<input id="tesoreria_banco_db_estatus_opt_inact" name="tesoreria_banco_db_estatus_opt"  type="radio" value="2" />Inactivo
          <input  type="hidden" id="tesoreria_banco_db_estatus" name="tesoreria_banco_db_estatus"  value="1" /></td>
		</tr>
	 <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
</table>
<input  name="tesoreria_banco_db_id" type="hidden" id="" />
</form>