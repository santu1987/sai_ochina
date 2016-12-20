<?php
header("Content-Type: text/html; charset=iso-8859-1");
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql="SELECT * FROM modulo";
$rs_modulo =& $conn->Execute($sql);
while (!$rs_modulo->EOF) {
	$opt_modulo.="<option value='".$rs_modulo->fields("id")."' >".$rs_modulo->fields("nombre")."</option>";
	$rs_modulo->MoveNext();
}
$sql="SELECT * FROM proceso";
$rs_proceso =& $conn->Execute($sql);
while (!$rs_proceso->EOF) {
	$opt_proceso.="<option value='".$rs_proceso->fields("id")."' >".$rs_proceso->fields("nombre")."</option>";
	$rs_proceso->MoveNext();
}
?>
<script type='text/javascript'>
var dialog;
$("#programa_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/administracion_sistema/programa/db/vista_grid_programa.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Programas', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
		//	$("#programa-consultas-busq_nombre").keypress(function(key){
		//		if(key.keyCode==13) $("#programa-consultas-busq_nombre").click();
		//	});
				var timeoutHnd; 
				var flAuto = true;
				$("#programa-consultas-busq_nombre").keypress(function(key)
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
							var busq_nombre= jQuery("#programa-consultas-busq_nombre").val();
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid");
							
						}
/////////////////////////////////////-2DA FORMA DE REALIZAR-////////////////////////////////////////////
				//	$("#programa-consultas-busq_nombre").keypress(function(key){
				//	var busq_nombre= jQuery("#programa-consultas-busq_nombre").val(); 
				//	jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?busq_nombre="+busq_nombre,page:1}).trigger("reloadGrid"); 
			//	});
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
								url:'modulos/administracion_sistema/programa/db/sql_grid_nombre_programa.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Icono','ID Modulo','Modulo','Id Proceso','Proceso','Pagina','obs'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:300,sortable:false,resizable:false},
									{name:'icono',index:'icono', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_modulo',index:'id_modulo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'modulo',index:'modulo', width:110,sortable:false,resizable:false},
									{name:'id_proceso',index:'id_proceso', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proceso',index:'proceso', width:100,sortable:false,resizable:false},
									{name:'pagina',index:'pagina', width:250,sortable:false,resizable:false,hidden:true},
									{name:'obs',index:'obs', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('programa_db_id').value = ret.id;
									getObj('programa_db_nombre').value = ret.nombre;
									getObj('programa_db_pagina').value = ret.pagina;
									getObj('programa_db_icono').value = ret.icono;
									getObj('programa_db_id_modulo').value = ret.id_modulo;
									getObj('programa_db_id_proceso').value = ret.id_proceso;
									getObj('programa_db_obs').value = ret.obs;
									getObj('programa_db_btn_cancelar').style.display='';
									getObj('programa_db_btn_eliminar').style.display='';
									getObj('programa_db_btn_actualizar').style.display='';
									getObj('programa_db_btn_guardar').style.display='none';									
									dialog.hideAndUnload();
					 			},
								loadComplete:function (id){
									setBarraEstado("");
									dialog.center();
									dialog.show();
								$("#programa-consultas-busq_nombre").focus();
								$('#programa-consultas-busq_nombre').alpha({allow:' '});
								}, 
								loadError:function(xhr,st,err){ 
									setBarraEstado("Type: "+st+"; Response: "+ xhr.status + " "+xhr.statusText);
								},										
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
						
			});							
		}
	});/****fin de consulta emergente*/
/*$("#programa_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/administracion_sistema/programa/db/grid_programa.php", { },
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
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/administracion_sistema/programa/db/sql_grid_programa.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Nombre','Icono','ID Modulo','Modulo','Id Proceso','Proceso','Pagina','obs'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'nombre',index:'nombre', width:221,sortable:false,resizable:false},
									{name:'icono',index:'icono', width:100,sortable:false,resizable:false,hidden:true},
									{name:'id_modulo',index:'id_modulo', width:110,sortable:false,resizable:false,hidden:true},
									{name:'modulo',index:'modulo', width:110,sortable:false,resizable:false},
									{name:'id_proceso',index:'id_proceso', width:100,sortable:false,resizable:false,hidden:true},
									{name:'proceso',index:'proceso', width:100,sortable:false,resizable:false},
									{name:'pagina',index:'pagina', width:250,sortable:false,resizable:false,hidden:true},
									{name:'obs',index:'obs', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
									var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('programa_db_id').value = ret.id;
									getObj('programa_db_nombre').value = ret.nombre;
									getObj('programa_db_pagina').value = ret.pagina;
									getObj('programa_db_icono').value = ret.icono;
									getObj('programa_db_id_modulo').value = ret.id_modulo;
									getObj('programa_db_id_proceso').value = ret.id_proceso;
									getObj('programa_db_obs').value = ret.obs;
									getObj('programa_db_btn_cancelar').style.display='';
									getObj('programa_db_btn_eliminar').style.display='';
									getObj('programa_db_btn_actualizar').style.display='';
									getObj('programa_db_btn_guardar').style.display='none';									
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
								sortname: 'nombre',
								viewrecords: true,
								sortorder: "asc"
							});
						}
});*/
$("#programa_db_btn_guardar").click(function() {
	if($('#form_db_programa').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/administracion_sistema/programa/db/sql.registrar.php",
			data:dataForm('form_db_programa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_db_programa');
					getObj('programa_db_id_modulo').selectedIndex=0;
					getObj('programa_db_id_proceso').selectedIndex=0;
					getObj('programa_db_icono').selectedIndex=0;
				}
				else if (html=="NoRegistro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					clearForm('form_db_programa');
					getObj('programa_db_id_modulo').selectedIndex=0;
					getObj('programa_db_id_proceso').selectedIndex=0;
					getObj('programa_db_icono').selectedIndex=0;
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#programa_db_btn_actualizar").click(function() {
	if($('#form_db_programa').jVal())
	{
		setBarraEstado(mensaje[esperando_respuesta]);
		$.ajax (
		{
			url: "modulos/administracion_sistema/programa/db/sql.actualizar.php",
			data:dataForm('form_db_programa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('programa_db_btn_eliminar').style.display='none';
					getObj('programa_db_btn_actualizar').style.display='none';
					getObj('programa_db_btn_guardar').style.display='';
					clearForm('form_db_programa');
					getObj('programa_db_id_modulo').selectedIndex=0;
	 				getObj('programa_db_id_proceso').selectedIndex=0;
					getObj('programa_db_icono').selectedIndex=0;
				}
				else if (html=="NoActualizo")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					getObj('programa_db_btn_guardar').style.display='';
					clearForm('form_db_programa');
					getObj('programa_db_id_modulo').selectedIndex=0;
					getObj('programa_db_id_proceso').selectedIndex=0;
					getObj('programa_db_icono').selectedIndex=0;
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#programa_db_btn_eliminar").click(function() {
	if(confirm("¿Desea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/programa/db/sql.eliminar.php",
			data:dataForm('form_db_programa'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('programa_db_btn_cancelar').style.display='';
					getObj('programa_db_btn_eliminar').style.display='none';
					getObj('programa_db_btn_actualizar').style.display='none';
					getObj('programa_db_btn_guardar').style.display='';
					clearForm('form_db_programa');
					getObj('programa_db_id_modulo').selectedIndex=0;
					getObj('programa_db_id_proceso').selectedIndex=0;
					getObj('programa_db_icono').selectedIndex=0;
				}
				else if (html=="Existe")
				{
					setBarraEstado(mensaje[relacion_existe],true);
				}
				else
				{
					setBarraEstado(html);
				}
			}
		});
	}
});

$("#programa_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('programa_db_btn_cancelar').style.display='';
	getObj('programa_db_btn_eliminar').style.display='none';
	getObj('programa_db_btn_actualizar').style.display='none';
	getObj('programa_db_btn_guardar').style.display='';
	clearForm('form_db_programa');
	getObj('programa_db_id_modulo').selectedIndex=0;
	getObj('programa_db_id_proceso').selectedIndex=0;
	getObj('programa_db_icono').selectedIndex=0;
});
$('#programa_db_nombre').alpha({allow:'áéíóúÁÉÍÓÚ .-'});
$('#programa_db_pagina').alpha({nocaps:true,allow:'._/?&='});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
	
</script>

<div id="botonera">
	<img id="programa_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />	
	<img id="programa_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />	
	<img id="programa_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="programa_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />		
	<img id="programa_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>

<form method="post" id="form_db_programa" name="form_db_programa">
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Programa </th>
	</tr>
	<tr>
		<th>Nombre:</th>
		<td >
		<input type="text"  size="40" name="programa_db_nombre" id="programa_db_nombre" value="" 
				/><!--message="Introduzca un Nombre para el Programa. Ejem:''Consulta Usuario''" 
			jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ.-]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ.-]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
	-->
		</td>
	</tr>
	<tr>
		<th>P&aacute;gina:</th>
		<td >
			<input name="programa_db_pagina" type="text" id="programa_db_pagina"   value="" size="40"
					/>		<!--message="Introduzca una Dirección Valida para el Programa. Ejem: ''/modulos/administracion/programa.php''" 
				jVal="{valid:/^[a-zA-Z //._/]{1,150}$/, message:'Dirección Invalida', styleType:'cover'}"
				jValKey="{valid:/[a-zA-Z //._/]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}"
		-->
		</td>
	</tr>
	<tr>
		<th>M&oacute;dulo:</th>
		<td >
			<select name="programa_db_id_modulo" id="programa_db_id_modulo" message="Seleccione un Modulo">
			<?=$opt_modulo?>
			</select>		</td>
	</tr>
	<tr>
		<th>Proceso:</th>
		<td >
			<select name="programa_db_id_proceso" id="programa_db_id_proceso" message="Seleccione el Proceso al cual va a Pertenecer el Programa" >
			<?=$opt_proceso?>
			</select>		</td>
	</tr>
	<tr>
		<th>Icono:</th>
		<td ><select name="programa_db_icono" id="programa_db_icono" message="Seleccione el Icono al cual va a Pertenecer el Programa">
          <option value="korganizer16x16.png">Registrar</option>
          <option value="kappfinder16x16.png">Consulta</option>
          <option value="proceso16x16.png">Proceso</option>
        </select></td>
	</tr>			
	<tr>
		<th>Observaci&oacute;n:</th>
		<td>
			<textarea name="programa_db_obs" cols="60" id="programa_db_obs" message="Introduzca un Comentario para el Programa"></textarea>		</td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
  </table>
<input type="hidden" id="programa_db_id" name="programa_db_id" />
</form>