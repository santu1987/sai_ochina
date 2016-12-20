<script type='text/javascript'>
var dialog;
$("#organismo_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado(mensaje[esperando_respuesta]);
	$.post("modulos/administracion_sistema/organismo/db/grid_organismo.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente de Organismo',modal: true,center:false,x:0,y:0,show:false });								
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
								url:'modulos/administracion_sistema/organismo/db/sql_grid_organismo.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','RIF','Direccion Principal','Direccion Secundaria','Codigo Area','Telefono','fax','Nombre','Direccion 1','Direccion 2','Codigo Area','Telefono'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:10,sortable:false,resizable:false},
									{name:'rif',index:'rif', width:30,sortable:false,resizable:false},
									{name:'direccion1',index:'direccion1', width:100,sortable:false,resizable:false,hidden:true},
									{name:'direccion2',index:'direccion2', width:100,sortable:false,resizable:false,hidden:true},
									{name:'codigo_area',index:'codigo_area', width:60,sortable:false,resizable:false,hidden:true},
									{name:'telefono',index:'telefono', width:70,sortable:false,resizable:false,hidden:true},
									{name:'fax',index:'fax', width:70,sortable:false,resizable:false,hidden:true},
									{name:'pagina_web',index:'pagina_web', width:100,sortable:false,resizable:false,hidden:true},
									{name:'email',index:'email', width:50,sortable:false,resizable:false,hidden:true},
									{name:'representante',index:'representante', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cedula_repre',index:'cedula_repre', width:100,sortable:false,resizable:false,hidden:true},
									{name:'cargo_repre',index:'cargo_repre', width:100,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
								nac1 = ret.rif.substr(0,2);
								if (nac1=="J-"){nac1=0;} if (nac1=="G-"){nac1=1;} if (nac1=="V-"){nac1=2} if(nac1=="E-"){nac1=3}
								nac2 = ret.cedula_repre.substr(0,2);
								if (nac2=="V-"){nac2=0;} if (nac2=="E-"){nac2=1;} if (nac2=="P-"){nac2=2;}
								//
								var rif2 = ret.rif;
								var tam  = rif2.length;
								rif2 = rif2.substr(2,tam);
								tam = rif2.length;
								rif2 = rif2.substr(0,rif2.indexOf("-"));
								//
								getObj('organismo_db_vista_id_organismo').value = ret.id;
								getObj('organismo_db_vista_nombre').value = ret.nombre;	
								getObj('organismo_db_vista_hrif').selectedIndex=nac1;							
								getObj('organismo_db_vista_rif').value = rif2;								
								getObj('organismo_db_vista_rif2').value = ret.rif.substr(-1,2);
								getObj('organismo_db_vista_direccion_principal').value = ret.direccion1;
								getObj('organismo_db_vista_direccion_secundaria').value = ret.direccion2;
								getObj('organismo_db_vista_cod_area').value = ret.codigo_area;
								getObj('organismo_db_vista_telefono').value = ret.telefono;
								getObj('organismo_db_vista_fax').value = ret.fax;
								getObj('organismo_db_vista_pag_web').value = ret.pagina_web;
								getObj('organismo_db_vista_email').value = ret.email;
								getObj('organismo_db_vista_persona_contacto').value = ret.representante;
								getObj('organismo_db_vista_nacionalidad').selectedIndex = nac2;
								getObj('organismo_db_vista_cedula_contacto').value = ret.cedula_repre.substr(2,9);
								getObj('organismo_db_vista_cargo_contacto').value = ret.cargo_repre;
								getObj('organismo_db_btn_eliminar').style.display='';
								getObj('organismo_db_btn_actualizar').style.display='';
								getObj('organismo_db_btn_guardar').style.display='none';
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
								sortname: 'id_organismo',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});
$("#organismo_db_btn_actualizar").click(function() {
	setBarraEstado(mensaje[esperando_respuesta]);
	if($('#form_organismo').jVal())
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/organismo/db/sql.actualizar.php",
			data:dataForm('form_organismo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizo")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('organismo_db_btn_eliminar').style.display='none';
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('organismo_db_btn_eliminar').style.display='none';
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#organismo_db_btn_guardar").click(function() {
	if($('#form_organismo').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/administracion_sistema/organismo/db/sql.registrar.php",
			data:dataForm('form_organismo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});
$("#organismo_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/organismo/db/sql.eliminar.php",
			data:dataForm('form_organismo'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('organismo_db_btn_cancelar').style.display='';
					getObj('organismo_db_btn_eliminar').style.display='none';
					getObj('organismo_db_btn_actualizar').style.display='none';
					getObj('organismo_db_btn_guardar').style.display='';
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
					getObj('organismo_db_btn_cancelar').style.display='';
					getObj('organismo_db_btn_eliminar').style.display='none';
					getObj('organismo_db_btn_actualizar').style.display='none';
					getObj('organismo_db_btn_guardar').style.display='';
					clearForm('form_organismo');
					getObj('organismo_db_vista_hrif').selectedIndex=0;
					getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});


$("#organismo_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('organismo_db_btn_cancelar').style.display='';
	getObj('organismo_db_btn_eliminar').style.display='none';
	getObj('organismo_db_btn_actualizar').style.display='none';
	getObj('organismo_db_btn_guardar').style.display='';
	clearForm('form_organismo');
	getObj('organismo_db_vista_hrif').selectedIndex=0;
	getObj('organismo_db_vista_nacionalidad').selectedIndex=0;
	
});

$('#organismo_db_vista_nombre').alpha({allow:'._1234567890 '});
$('#organismo_db_vista_rif').numeric();
$('#organismo_db_vista_rif2').numeric();
$('#organismo_db_vista_email').alpha({allow:'._@1234567890'});
$('#organismo_db_vista_pag_web').alpha({allow:'._/'});
$('#organismo_db_vista_persona_contacto').alpha({allow:' '});
$('#organismo_db_vista_cedula_contacto').numeric();
$('#organismo_db_vista_cargo_contacto').alpha({allow:' '});
$('#organismo_db_vista_cod_area').numeric();
$('#organismo_db_vista_telefono').numeric();
$('#organismo_db_vista_fax').numeric();
$('#organismo_db_vista_direccion_principal').alpha({allow: '.()@1234567890ñ '});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		getObj('msg').style.display = "none";		
	});
</script>

<div id="botonera">
	<img id="organismo_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif" />
	<img id="organismo_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />
    <img src="imagenes/null.gif" name="organismo_db_btn_consultar" class="btn_consultar" id="organismo_db_btn_consultar" />
	<img id="organismo_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img src="imagenes/null.gif" name="organismo_db_btn_guardar" class="btn_guardar" id="organismo_db_btn_guardar" />
</div>

<form method="post" name="form_organismo" id="form_organismo">
  <table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="6"><img src="imagenes/iconos/korganizer28x28.png" style="padding-right:5px;" align="absmiddle" />Registrar Organismo </th>
		</tr>		
		<tr>
			<th>Nombre:</th> 
			<td colspan="3">
				<input type="hidden" name="organismo_db_vista_id_organismo" id="organismo_db_vista_id_organismo" />		
				<input 
					name="organismo_db_vista_nombre" id="organismo_db_vista_nombre" type="text"  size="40" maxlength="40"  
					message="Introduzca un Nombre para el Organismo. Ejem: ''Ochina''" 
					jVal="{valid:/^[a-zA-Z0-9 áéíóúÁÉÍÓÚñÑ._]{1,40}$/, message:'Nombre Invalido', styleType:'cover'}"
					jValKey="{valid:/[a-zA-Z0-9 áéíóúÁÉÍÓÚñÑ._]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				>			</td>
		</tr>
		<tr>
			<th>R.I.F.</th>
			<td colspan="1">
				<select name="organismo_db_vista_hrif" id="organismo_db_vista_hrif" style="width:50px; min-width:1%;">
					<option>J-</option>
					<option>G-</option>
					<option>V-</option>
                    <option>E-</option>
				</select>			</td>
		<td width="1%">
				<input name="organismo_db_vista_rif" type="text" id="organismo_db_vista_rif"  value="" size="5" maxlength="8" width="150px" 
					message="Introduzca el Rif del Organismo. Ejem: ''J-0000000-0 ó G-0000000-0''" 
					jVal="{valid:/^[0-9]{1,12}$/, message:'Rif Invalido', styleType:'cover'}"
					jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				/>				</td>
		<td colspan=""><label>
		  <input name="organismo_db_vista_rif2" type="text" id="organismo_db_vista_rif2" size="1" maxlength="1" style="max-width:20px" 
					jVal="{valid:/^[0-9]{1,12}$/, message:'Rif Invalido', styleType:'cover'}"
					jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"/>
		</label></td>
		</tr>
		<tr>
			<th>Correo Electr&oacute;nico</th>
			<td colspan="3">
            	<input name="organismo_db_vista_email" id="organismo_db_vista_email" type="text"  value="" size="40" maxlength="60"
					message="Introduzca un E-mail para el Organismo. Ejem: ''nombre@dominio.com''" 
					jval="{valid:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,40}$/, message:'Invalid Email Address', styleType:'cover'}"
					jvalkey="{valid:/[a-zA-Z0-9._%+-@]/, cFunc:'alert', cArgs:['Email Address: '+$(this).val()]}" 
                />             </td>
		</tr>
		<tr>
			<th>P&aacute;gina Web</th>
			<td colspan="3"><input name="organismo_db_vista_pag_web" id="organismo_db_vista_pag_web" type="text" value="" size="40" maxlength="60"
					message="Introduzca la direccion de la Pag Web del Organismo. Ejem: ''www.ochina.gob.ve''" 
					jval="{valid:/^[a-zA-Z._/ ]{1,60}$/, message:'Pagina Web Invalido', styleType:'cover'}"
					jvalkey="{valid:/[a-zA-Z ]/, cFunc:'alert', cArgs:['Pagina Web: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>C&eacute;dula Contacto</th>
		  <td width="1%">
				<select name="organismo_db_vista_nacionalidad" id="organismo_db_vista_nacionalidad" style="width:50px; min-width:50px;">
				  <option>V-</option>
				  <option>E-</option>
				  <option>P-</option>
                </select>		  </td>
		<td width="99%" colspan="2"><input name="organismo_db_vista_cedula_contacto" type="text" id="organismo_db_vista_cedula_contacto"  value="" size="7" maxlength="9" width="150px" 
					message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" />         </td>
		</tr>
		<tr>
			<th>Nombre  Contacto</th>
			<td colspan="3"><input name="organismo_db_vista_persona_contacto" id="organismo_db_vista_persona_contacto" type="text" value="" size="40" maxlength="60" 
					message="Introduzca el Nombre de la persona de contacto." 
					jval="{valid:/^[a-zA-Z ]{1,60}$/, message:'Contacto Invalido', styleType:'cover'}"
					jvalkey="{valid:/[a-zA-Z ]/, cFunc:'alert', cArgs:['Pagina Web: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Cargo</th>
			<td colspan="3">		
				<input name="organismo_db_vista_cargo_contacto" id="organismo_db_vista_cargo_contacto" type="text" value="" size="40" maxlength="60"
					message='Introduzca el Cargo que desempeña en el Organismo. Ejem: "Gerente"' 
					jVal="{valid:/^[a-zA-Z ]{1,60}$/, message:'Cargo Invalido', styleType:'cover'}"
					jValKey="{valid:/[a-zA-Z ]/, cFunc:'alert', cArgs:['Pagina Web: '+$(this).val()]}"
				>			</td>
		</tr>
		<tr>
			<th>Direcci&oacute;n Principal</th>
			<td colspan="3">
				<textarea name="organismo_db_vista_direccion_principal" id="organismo_db_vista_direccion_principal"	cols="60">
                </textarea>			</td>
		</tr>

		<tr>
			<th>Direcci&oacute;n Secundaria</th>
			<td colspan="3">
				<textarea name="organismo_db_vista_direccion_secundaria" id="organismo_db_vista_direccion_secundaria"	cols="60"></textarea>			</td>
		</tr>
		<tr>
			<th>Teléfono Principal</th>
			<td colspan="1%">	
				<input name="organismo_db_vista_cod_area" type="text" id="organismo_db_vista_cod_area" style="width:25px; max-width:25px" size="0" maxlength="3"  
					message="Introduzca el Codigo de Area. Ejem: ''0212''" 
					jVal="{valid:/^[0-9]{1,3}$/, message:'Cod Area Invalido', styleType:'cover'}"
					jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				/>			</td>
		<td colspan="2">
				<input name="organismo_db_vista_telefono" type="text" class="campo" id="organismo_db_vista_telefono" value="" size="5" maxlength="7" width="150px"  
					message="Introduzca el Telefono del Organismo." 
					jVal="{valid:/^[0-9]{1,8}$/, message:'Telefono Invalido', styleType:'cover'}"
					jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				/>		  </td>
				<td></td>
		</tr>	
		<tr>
			<th>Fax</th>
			<td colspan="3">
				<input name="organismo_db_vista_fax" id="organismo_db_vista_fax" type="text"  value="" size="16" maxlength="8" 
					 message="Introduzca el Fax del Organismo. Ejem: ''0212-1112233''" 
					 jVal="{valid:/^[0-9]{1,40}$/, message:'Fax Invalido', styleType:'cover'}"
					 jValKey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
				/>			</td>
		</tr>
		<tr>
			<td colspan="4" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
</form>
