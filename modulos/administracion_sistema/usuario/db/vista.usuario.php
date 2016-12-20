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
								loadtext: "Recuperando InformaciÛn del Servidor",		
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
								//	document.form_usuario.usuario_db_vista_cedula.disabled=true;
									getObj('usuario_db_vista_nombre').value = ret.nombre;
									getObj('usuario_db_vista_apellido').value = ret.apellido;
									getObj('usuario_db_vista_usuario').value = ret.usuarios;
									getObj('usuario2').value = ret.foto;
									document.form_foto.foto_usuario.src="imagenes/foto/"+ret.foto;
									getObj('vie_nomfoto').value = ret.foto;
									document.form_foto.foto.value="";
									fd=ret.fecha_desde.substr(0, 10);
									fds=fd.substr(8,2)+"/"; fds=fds+fd.substr(5,2)+"/"; fds=fds+fd.substr(0,4); 
									getObj('db_vista_usuario_fecha_desde').value = fds;
									fh=ret.fecha_hasta.substr(0, 10);
									fhs=fh.substr(8,2)+"/"; fhs=fhs+fh.substr(5,2)+"/"; fhs=fhs+fh.substr(0,4); 
									getObj('db_vista_usuario_fecha_hasta').value = fhs;
									getObj('usuario_db_vista_unidad_ejecutora').value=ret.id_unidad_ejecutora;
									getObj('usuario_db_vista_obs').value = ret.observacion;
									getObj('usuario_db_vista_clave').value = ret.clave;
									getObj('usuario_db_vista_clave2').value = ret.clave;
									getObj('usuario_db_vista_clave').style.display='none';
									getObj('usuario_db_vista_clave2').style.display='none';
									getObj('usuario_db_btn_cancelar').style.display='';
									getObj('usuario_db_btn_eliminar').style.display='';
									getObj('usuario_db_btn_actualizar').style.display='';
									getObj('usuario_db_btn_guardar').style.display='none';
									getObj('tr1').style.display='none';
									getObj('tr2').style.display='none';
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
	if (document.form_usuario.db_vista_usuario_fecha_hasta.value<=document.form_usuario.db_vista_usuario_fecha_desde.value)
	{
		alert ("La Fecha Hasta tiene que ser mayor que la Fecha Desde...");
	}
	if((($('#form_usuario').jVal())&&(document.form_usuario.usuario_db_vista_clave.value==document.form_usuario.usuario_db_vista_clave2.value))&& (document.form_usuario.db_vista_usuario_fecha_hasta.value>document.form_usuario.db_vista_usuario_fecha_desde.value))
	{
		setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
		$.ajax ({
			url: "modulos/administracion_sistema/usuario/db/sql.registrar.php",
			data:dataForm('form_usuario'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="NO Registro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_usuario.usuario_db_vista_cedula.value="";
					document.form_usuario.usuario_db_vista_cedula.focus();
				}
				if (html=="No_Registro")
				{
					setBarraEstado(mensaje[registro_existe],true,true);
					document.form_usuario.usuario_db_vista_usuario.value="";
					document.form_usuario.usuario_db_vista_usuario.focus();
				}
				if (html=="Registrado")
				{
					
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('form_usuario');
					getObj('db_vista_usuario_fecha_desde').value = "<?= date("d/m/Y"); ?>";
					getObj('db_vista_usuario_fecha_hasta').value = "<?= date("d/m")."/".(date("Y")+1); ?>";
					if (document.form_foto.foto.value!=""){
						document.form_foto.form_foto_opt.value = 1;
						document.form_foto.submit();
					}
					document.form_foto.foto.value="";
					document.form_usuario.usuario_db_vista_nacionalidad.selectedIndex=0;
					document.form_usuario.usuario_db_vista_unidad_ejecutora.selectedIndex=0;
					document.form_foto.foto_usuario.src="imagenes/foto/sombra.png";
				}
				else
				{
					setBarraEstado(html);
				
				}
			}
		});
	}
});
$("#usuario_db_btn_eliminar").click(function() {
	if(confirm("øDesea elminar el registro seleccionado?")) 
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/usuario/db/sql.eliminar.php",
			data:dataForm('form_usuario'),
			type:'POST',
			cache: false,
			success: function(html)
			{
				if (html=="Eliminado")
				{
					setBarraEstado(mensaje[eliminacion_exitosa],true,true);
					getObj('usuario_db_btn_cancelar').style.display='';
					getObj('usuario_db_btn_eliminar').style.display='none';
					getObj('usuario_db_btn_actualizar').style.display='none';
					getObj('usuario_db_btn_guardar').style.display='';
					getObj('tr1').style.display='';
					getObj('tr2').style.display='';
					getObj('usuario_db_vista_clave').style.display='';
					getObj('usuario_db_vista_clave2').style.display='';
					clearForm('form_usuario');
					getObj('db_vista_usuario_fecha_desde').value = "<?= date("d/m/Y");?>";
					getObj('db_vista_usuario_fecha_hasta').value = "<?= date("d/m")."/".(date("Y")+1); ?>";
					document.form_usuario.usuario_db_vista_nacionalidad.selectedIndex=0;
					document.form_usuario.usuario_db_vista_cedula.disabled=false;
					document.form_usuario.usuario_db_vista_unidad_ejecutora.selectedIndex=0;
					document.form_foto.foto_usuario.src="imagenes/foto/sombra.png";
					fecha_foto();
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
	document.form_foto.foto.value="";
	document.form_usuario.usuario_db_vista_cedula.disabled=false;
	document.form_usuario.usuario_db_vista_nacionalidad.selectedIndex=0;
	document.form_usuario.usuario_db_vista_unidad_ejecutora.selectedIndex=0;
	document.form_foto.foto_usuario.src="imagenes/foto/sombra.png";
	fecha_foto();
});
$("#usuario_db_btn_actualizar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	if($('#form_usuario').jVal())
	{
		$.ajax (
		{
			url: "modulos/administracion_sistema/usuario/db/sql.actualizar.php",
			data:dataForm('form_usuario'),
			type:'POST',
			cache: false,
			success: function(html)
			{			
				if (html=="No Actualizo")
				{
					document.form_usuario.usuario_db_vista_cedula.value="";
					document.form_usuario.usuario_db_vista_cedula.focus();
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				if (html=="No_Actualizo")
				{
					document.form_usuario.usuario_db_vista_usuario.value="";
					document.form_usuario.usuario_db_vista_usuario.focus();
					setBarraEstado(mensaje[registro_existe],true,true);
				}
				if (html=="Actualizado")
				{
					setBarraEstado(mensaje[actualizacion_exitosa],true,true);
					getObj('usuario_db_btn_actualizar').style.display='none';
					getObj('usuario_db_btn_guardar').style.display='';
					getObj('tr1').style.display='';
					getObj('usuario_db_vista_clave').style.display='';
					getObj('tr2').style.display='';
					getObj('usuario_db_vista_clave2').style.display='';
					getObj('usuario_db_btn_eliminar').style.display='none';
					getObj('usuario_db_btn_actualizar').style.display='none';
					getObj('usuario_db_btn_guardar').style.display='';
					clearForm('form_usuario');
					getObj('db_vista_usuario_fecha_desde').value = "<?= date("d/m/Y");?>";
					getObj('db_vista_usuario_fecha_hasta').value = "<?= date("d/m")."/".(date("Y")+1);?>";
					document.form_usuario.usuario_db_vista_cedula.disabled=false;
					document.form_usuario.usuario_db_vista_nacionalidad.selectedIndex=0;
					document.form_usuario.usuario_db_vista_unidad_ejecutora.selectedIndex=0;
					document.form_foto.foto_usuario.src="imagenes/foto/sombra.png";
					fecha_foto();
				}
				/*else
				{
					setBarraEstado(html);
				}*/
					if (document.form_foto.foto.value!=""){
						document.form_foto.form_foto_opt.value = 1;
						document.form_foto.submit();
					}
					document.form_foto.foto.value="";
			}
				
		});
	}
});

$('#usuario_db_vista_nombre').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#usuario_db_vista_apellido').alpha({allow:' ·ÈÌÛ˙ƒ…Õ”⁄'});
$('#usuario_db_vista_usuario').alphanumeric({allow:'._0-9'});
$('#usuario_db_vista_clave').alphanumeric({allow:'_'});
$('#usuario_db_vista_clave2').alphanumeric({allow:'_'});
$('#usuario_db_vista_cedula').numeric({allow:'_'});
$('#db_vista_usuario_fecha_desde').numeric({allow:'/-'});
$('#db_vista_usuario_fecha_hasta').numeric({allow:'/-'});

$("input, select, textarea").bind("focus", function(){
		if (($(this).attr('message')&&!$(this).val()) || ($(this).attr('message')&&$(this).attr('tagName')=='SELECT'))
			inlineMsg($(this).attr('id'),$(this).attr('message'),2);
	});
	$("input, select, textarea").bind("blur", function(){
		if (getObj('msg')) getObj('msg').style.display = "none";		
	});
//scrip para enviar el nombre de la foto al sql.registrar.php
	function enviar_foto(){
		foto = document.form_foto.foto.value;
		tam = foto.length;
		cad = "\\s";
		cad = cad.substr(0,1);
		pos = foto.lastIndexOf(cad);
		foto = foto.substr(pos+1,tam);
		document.form_foto.form_foto_opt.value = 0;
		document.form_usuario.nomfoto.value=document.form_foto.form_foto_fecha.value;
		document.form_foto.nomfoto.value=document.form_usuario.usuario_db_vista_nacionalidad.value+document.form_usuario.usuario_db_vista_cedula.value+"_"+document.form_foto.form_foto_fecha.value;
		document.form_foto.submit();
	}
//
//
function error_formato(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/foto/sombra.png />El tipo de Imagen tiene que ser: jpeg, png o bmp</p></div>",true,true);
}
function error_tamano(){
	setBarraEstado("<div id='mensaje'><p><img align='absmiddle' src='imagenes/foto/sombra.png />El tama&ntilde;o de la imagen tiene que ser menor a 1 MB</p></div>",true,true);
}
//
//
var timer;
var c=0;
function change_picture(){
		
		document.form_foto.foto_usuario.src='imagenes/iconos/ajax-loader2.gif';	
		c++;
		if (c==4){
		clearInterval(timer);
		c=0;
		enviar_foto();
		}
		
	}
	function tiempo(){
		//document.getElementById('prueba').style.display='';
		//document.getElementById('index').style.display='';
		timer =	setInterval("change_picture();",150);
	}
//
//
function fecha_foto(){
ahora=new Date();
hora=ahora.getHours();
minutos=ahora.getMinutes();
segundos=ahora.getSeconds();
document.form_foto.form_foto_fecha.value= hora+"_"+minutos+"_"+segundos;
}
fecha_foto();
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
	    <select name="usuario_db_vista_nacionalidad" id="usuario_db_vista_nacionalidad" style="width:50px; min-width:50px;">
				  <option>V-</option>
				  <option>E-</option>
				  <option>P-</option>
          </select>	    
		  <input name="usuario_db_vista_cedula" type="text" id="usuario_db_vista_cedula"  size="8" maxlength="9" width="150px" 
					message="Introduzca el N&uacute;mero de C&eacute;dula. Ejem: ''V-0000000 &oacute; E-0000000''" 
					jval="{valid:/^[0-9]{1,12}$/, message:'C&eacute;dula Invalida', styleType:'cover'}"
					jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Nombre: </th><td width="1%"><input name="usuario_db_vista_nombre" type="text" id="usuario_db_vista_nombre" value=""  size="35" maxlength="40" message="Escriba un Nombre" 
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Nombre Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Nombre: '+$(this).val()]}" /></td>
		</tr>
		<tr>
			<th>Apellido: </th><td><input name="usuario_db_vista_apellido" type="text" id="usuario_db_vista_apellido" value=""  size="35" maxlength="40" message="Escriba un Apellido"
			jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]{1,60}$/, message:'Apellido Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;]/, cFunc:'alert', cArgs:['Apellido: '+$(this).val()]}"></td>
		</tr>	
		<tr>
			<th>Usuario: </th><td><input name="usuario_db_vista_usuario" type="text" id="usuario_db_vista_usuario" value=""  size="35" maxlength="40" message="Escriba un Nombre de Usuario" 
			jval="{valid:/^[a-zA-Z._0-9]{1,60}$/, message:'Usuario Invalido', styleType:'cover'}"
			jvalkey="{valid:/[a-zA-Z._0-9]/, cFunc:'alert', cArgs:['Usuario: '+$(this).val()]}" /></td>
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
		  <th>Fecha Desde:</th>
	      <td><label>
		<input readonly="true" type="text" name="db_vista_usuario_fecha_desde" id="db_vista_usuario_fecha_desde" size="7" value="<?php echo date("d/m/Y")?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Desde: '+$(this).val()]}"/>
		<button type="reset" id="fecha_desde_boton"> ...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "db_vista_usuario_fecha_desde",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_desde_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
</label></td>
		</tr>
		<tr>
		  <th>Fecha Hasta:</th>
	      <td><label>
	      <input readonly="true" type="text" name="db_vista_usuario_fecha_hasta" id="db_vista_usuario_fecha_hasta" size="7" value="<? $year=date("Y")+1; echo date("d/m")."/".$year;?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}"
			jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Hasta: '+$(this).val()]}"/>
	      <button type="reset" id="fecha_hasta_boton">...</button>
          <script type="text/javascript">
					Calendar.setup({
						inputField     :    "db_vista_usuario_fecha_hasta",      // id of the input field
						ifFormat       :    "%d/%m/%Y",       // format of the input field
						showsTime      :    false,            // will display a time selector
						button         :    "fecha_hasta_boton",   // trigger for the calendar (button ID)
						singleClick    :    true          // double-click mode
					});
				</script>
	      </label></td>
	  </tr>
		<tr>
		  <th>Unidad :</span></th>
          <td></span>
            <select name="usuario_db_vista_unidad_ejecutora" id="usuario_db_vista_unidad_ejecutora">
              <?= $opt_unidad?>
            </select></td>
	</tr>
		<tr>
		  <th>Observaci&oacute;n: </th>
          <td><label>
            <textarea name="usuario_db_vista_obs" cols="60" id="usuario_db_vista_obs" message="Instrduzca una Observacion"></textarea>
          </label></td>
	</tr>
		<tr>
		  <td colspan="2" class="bottom_frame">&nbsp;</td>
    </tr>
  </table>
</form>
<iframe name="resultado" style="display:none"></iframe>
<iframe id="limpiar_cache" name="limpiar_cache" style="display:none"></iframe>
<form action="modulos/administracion_sistema/usuario/db/foto.php" method="post" enctype="multipart/form-data" name="form_foto" target="resultado" id="form_foto">
    <table width="310" class="cuerpo_formulario">
      <tr>
        <th class="titulo_frame" colspan="5"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Foto del  Usuario </th>
      </tr>
      <tr>
        <th><img src="imagenes/foto/sombra.png" name="foto_usuario" width="77" height="92" id="foto_usuario" /></th>
        <td width="99%"><div align="center">
          <input name="foto" type="file" id="foto" onchange="tiempo()" size="50"/>
        </div></td>
      </tr>

      <tr>
        <td height="22" colspan="2" class="bottom_frame">&nbsp;</td>
      </tr>
  </table>
    <input name="nomfoto" type="hidden" id="nomfoto" value=""/>
    <input id="form_foto_opt" name="form_foto_opt" type="hidden"/>
    <input id="form_foto_err_f" name="form_foto_err_f" type="hidden" onclick="error_formato()"/>
    <input id="form_foto_err_t" name="form_foto_err_t" type="hidden" onclick=" error_tamano()" />
    <input id="form_foto_fecha" name="form_foto_fecha" type="hidden"/>
</form>

