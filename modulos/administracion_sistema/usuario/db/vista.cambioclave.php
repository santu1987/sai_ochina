<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM unidad_ejecutora";
$rs_unidad =& $conn->Execute($sql);
while (!$rs_unidad->EOF){
	$opt_unidad.="<option value='".$rs_unidad->fields("id_unidad_ejecutora")."' >".$rs_unidad->fields("nombre")."</option>";
	$rs_unidad->MoveNext();
}  
?>
<link rel="stylesheet" type="text/css" media="all" href="utilidades/jscalendar-1.0/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/lang/calendar-es.js"></script>
<script type="text/javascript" src="utilidades/jscalendar-1.0/calendar-setup.js"></script>
<script type='text/javascript'>
var dialog;
/*$("#usuario_db_btn_consultar").click(function() {
	var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.post("modulos/administracion_sistema/usuario/db/grid_usuario.php", { },
                        function(data)
                        {								
								dialog=new Boxy('<table id="list_grid_'+nd+'" class="scroll" cellpadding="0" cellspacing="0"></table><div id="pager_grid_'+nd+'" class="scroll" style="text-align:center;"></div>', { title: 'Consulta Emergente del Usuario', modal: true,center:false,x:0,y:0,show:false });								
								setTimeout(crear_grid,100);
                        });*/
$("#usuario_db_btn_consultar").click(function() {
var nd=new Date().getTime();
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
		    url:"modulos/administracion_sistema/usuario/db/grid_filtro_usuario.php",
			data:"id_grid=list_grid_"+nd+"&id_pager=pager_grid_"+nd,
			type:'POST', 
			cache: false,
			success: function(data)
			{
				dialog=new Boxy(data,{ title: 'Consulta Emergente de Usuarios', modal: true,center:false,x:0,y:0,show:false});
				dialog_reload=function gridReload(){ 
					var busq_nombre_usu= jQuery("#administracion_busq_nombre_usuario").val();
					jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/usuario/db/sql_grid_usuario.php?busq_nombre_usu="+busq_nombre_usu,page:1}).trigger("reloadGrid"); 
			}	
				crear_grid();
				var timeoutHnd; 
				var flAuto = true;
				$("#administracion_busq_nombre_usuario").keypress(function(key)
				{
						if(key.keyCode==27){this.close();}
						administracion_filtro_usuario_dosearch();
												
					});
					function administracion_filtro_usuario_dosearch()
					{
						if(!flAuto) return; 
							// var elem = ev.target||ev.srcElement; 
						if(timeoutHnd) 
							clearTimeout(timeoutHnd) 
							timeoutHnd = setTimeout(administracion_filtrro_usuario_gridReload,500)
										}
						function administracion_filtrro_usuario_gridReload()
						{
							var busq_nombre_usu= jQuery("#administracion_busq_nombre_usuario").val(); 
							jQuery("#list_grid_"+nd).setGridParam({url:"modulos/administracion_sistema/usuario/db/sql_grid_usuario.php?busq_nombre_usu="+busq_nombre_usu,page:1}).trigger("reloadGrid"); 
			
						}
			}
		});
						function crear_grid()
						{
							jQuery("#list_grid_"+nd).jqGrid
							({
								width:900,
								height:300,
								recordtext:"Registro(s)",
								loadtext: "Recuperando Información del Servidor",		
								url:'modulos/administracion_sistema/usuario/db/sql_grid_usuario.php?nd='+nd,
								datatype: "json",
								colNames:['ID','Cedula','Nombre','Apellido','Usuario','Clave','fecha_desde','fecha_hasta','observacion','Foto','id_unidad_ejecutora'],
								colModel:[
									{name:'id',index:'id', width:50,sortable:false,resizable:false,hidden:true},
									{name:'cedula',index:'cedula', width:100,sortable:false,resizable:false},
									{name:'nombre',index:'nombre', width:150,sortable:false,resizable:false},
									{name:'apellido',index:'apellido', width:150,sortable:false,resizable:false},
									{name:'usuarios',index:'usuarios', width:100,sortable:false,resizable:false},
									{name:'clave',index:'clave', width:250,sortable:false,resizable:false,hidden:true},
									{name:'fecha_desde',index:'fecha_desde', width:250,sortable:false,resizable:false,hidden:true},
									{name:'fecha_hasta',index:'fecha_hasta', width:250,sortable:false,resizable:false,hidden:true},
									{name:'observacion',index:'observacion', width:250,sortable:false,resizable:false,hidden:true},
									{name:'foto',index:'foto', width:250,sortable:false,resizable:false,hidden:true},
									{name:'id_unidad_ejecutora',index:'id_unidad_ejecutora', width:250,sortable:false,resizable:false,hidden:true}
								],
								pager: $('#pager_grid_'+nd),
								rowNum:20,
								rowList:[20,50,100],
								imgpath: '../../../sai_ochina/utilidades/jqgrid_demo/themes/basic/images',
								onSelectRow: function(id){
								var ret = jQuery("#list_grid_"+nd).getRowData(id);
									getObj('vista_id_usuario').value = ret.id;
									nac = ret.cedula;
									nac = nac.substr(0,2);
									if (nac=="P-"){nac = 2;}
									if (nac=="V-"){nac = 0;}
									if (nac=="E-"){nac = 1;}
									getObj('usuario_db_vista_nacionalidad').selectedIndex= nac;
									getObj('usuario_db_vista_cedula').value = ret.cedula.substr(2,9);
									getObj('usuario_db_vista_nombre').value = ret.nombre;
									getObj('usuario_db_vista_apellido').value = ret.apellido;
									getObj('usuario_db_vista_usuario').value = ret.usuarios;
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
});
$("#usuario_db_btn_guardar").click(function() {
	if (document.form_usuario.usuario_db_vista_clave.value!=document.form_usuario.usuario_db_vista_clave2.value)
	{
		alert ("La clave de confirmacion es diferente a la clave...");
		document.form_usuario.usuario_db_vista_clave2.value="";
		document.form_usuario.usuario_db_vista_clave2.focus();
	}
	else
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
			url: "modulos/administracion_sistema/usuario/db/sql.actualizarclave.php",
			data:dataForm('form_usuario'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Actualizado")
				{					
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_usuario');
				}
				else
				{
					setBarraEstado(html);
				
				}
			}
		});
	}
});


$("#usuario_db_btn_cancelar").click(function() {
	setBarraEstado("");
	getObj('tr1').style.display='';
	getObj('usuario_db_vista_clave').style.display='';
	getObj('tr2').style.display='';
	getObj('usuario_db_vista_clave2').style.display=''; 
	getObj('usuario_db_btn_eliminar').style.display='none';
	getObj('usuario_db_btn_actualizar').style.display='none';
	getObj('usuario_db_btn_guardar').style.display='';
	clearForm('form_usuario');
	getObj("db_vista_usuario_fecha_desde").value = "<?=  date("d/m/Y"); ?>";
	getObj("db_vista_usuario_fecha_hasta").value = "<?= date("d/m")."/".(date("Y")+1); ?>";
});





</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:631px;
	height:19px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	width:200px;
	height:27px;
	z-index:2;
}
#Layer3 {
	position:absolute;
	width:280px;
	height:20px;
	z-index:1;
	left: 130px;
	top: 430px;
}
#Layer4 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:1;
	left: 339px;
	top: 484px;
}
-->
</style>
<div id="botonera">
	<img id="usuario_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  />	
	<img id="usuario_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none" />		
	<img id="usuario_db_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
	<img id="usuario_db_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none" />
    <img id="usuario_db_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" enctype="multipart/form-data" name="form_usuario" id="form_usuario">
<input type="hidden" name="vista_id_usuario" id="vista_id_usuario" />
<input name="nomfoto" type="hidden" id="nomfoto" value="" />
<input name="usuario2" type="hidden" id="usuario2" />
<input id="vie_nomfoto" name="vie_nomfoto" type="hidden" />
<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Usuario </th>
		</tr>
		<tr>
			<th>C&eacute;dula:</th>
			<td colspan="2">
	    <select name="usuario_db_vista_nacionalidad" id="usuario_db_vista_nacionalidad" style="width:50px; min-width:50px;" disabled="disabled">
				  <option>V-</option>
				  <option>E-</option>
				  <option>P-</option>
          </select>	    
		  <input name="usuario_db_vista_cedula" type="text" id="usuario_db_vista_cedula"  size="8" maxlength="9" width="150px" 
					message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" readonly="readonly" /></td>
		</tr>
		<tr>
			<th>Nombre: </th><td width="1%"><input name="usuario_db_vista_nombre" type="text" id="usuario_db_vista_nombre" value=""  size="35" maxlength="40" message="Escriba un Nombre" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" readonly="readonly" /></td>
		</tr>
		<tr>
			<th>Apellido: </th><td><input name="usuario_db_vista_apellido" type="text" id="usuario_db_vista_apellido" value=""  size="35" maxlength="40" message="Escriba un Apellido"
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}" readonly="readonly" /></td>
		</tr>	
		<tr>
			<th>Usuario: </th><td><input name="usuario_db_vista_usuario" type="text" id="usuario_db_vista_usuario" value=""  size="35" maxlength="40" message="Escriba un Nombre de Usuario" 
			jval="{valid:/^[a-zA-Z._0-9]{1,60}$/, message:'Usuario Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z._0-9]/, cFunc:'alert', cArgs:['Usuario: '+$(this).val()]}" readonly="readonly" /></td>
		</tr>
		<tr id="tr1">
			<th>Clave: </th>
			<td><input name="usuario_db_vista_clave" type="password" id="usuario_db_vista_clave" value=""  size="35" maxlength="40" message="Escriba una Clave para el Usuario"
			jval="{valid:/^[0-9a-zA-Z_]{1,60}$/, message:'Clave Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9a-zA-Z_]/, cFunc:'alert', cArgs:['Clave: '+$(this).val()]}" /></td>
		</tr>		
		<tr id="tr2">
			<th>Confirmar Clave:</th>
		    <td><input name="usuario_db_vista_clave2" type="password" id="usuario_db_vista_clave2" value=""  size="35" maxlength="40" message="Confirme de Nuevo su Clave Anterior"
			jval="{valid:/^[0-9a-zA-Z_]{1,60}$/, message:'Confirmacion de Clave Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9a-zA-Z_]/, cFunc:'alert', cArgs:['Clave: '+$(this).val()]}" /></td>
		</tr>
		<tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>        
  </table>
</form>
