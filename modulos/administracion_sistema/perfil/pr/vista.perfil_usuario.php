<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM usuario";
$rs_usuario =& $conn->Execute($sql);
$xx = 'javascript:var optionnueva = new Option(perfil_usuario.usuario.options[perfil_usuario.usuario.selectedIndex].text,  perfil_usuario.usuario.options[perfil_usuario.usuario.selectedIndex].value); perfil_usuario.usuarioSelect.options[perfil_usuario.usuarioSelect.length]=optionnueva;
elem=document.getElementById("usuario"); 
				if (elem.selectedIndex!=-1)  
					elem.options[elem.selectedIndex]=null;
';
while (!$rs_usuario->EOF) {
	$opt_usuario.="<option value='".$rs_usuario->fields("id_usuario")."' ondblclick='".$xx."'>".$rs_usuario->fields("apellido")."&nbsp;".$rs_usuario->fields('nombre')."</option>";
$rs_usuario->MoveNext();
}

$sql="SELECT * FROM perfil";
$rs_perfil =& $conn->Execute($sql);
while (!$rs_perfil->EOF) {
	$opt_perfil.="<option value='".$rs_perfil->fields('id_perfil')."'>".$rs_perfil->fields('nombre')."</option>";
$rs_perfil->MoveNext();
}
?>
<script type='text/javascript'>

$("#modulo_btn_guardar").click(function() {
	$.ajax ({
		url: "modulos/administracion_sistema/perfil/pr/sql.registrar_perfil_usuario.php",
		data:dataForm('perfil_usuario'),
		type:'POST',
		cache: true,
		success: function(html)
			{
				if (html=="Registrado")
				{
					setBarraEstado(mensaje[registro_exitoso],true,true);
					clearForm('perfil_usuario');
				}
				else
				{
					setBarraEstado(html);
				}
			}
	});
});

function update_usuario()
{ 
	$("#usuario").removeOption(/./);
	$("#usuario").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.usuario.php",{id_perfil:$(this).val()},false);
	$("#usuarioSelect").removeOption(/./);
	$("#usuarioSelect").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.usuarioSelect.php",{id_perfil:$(this).val()},false);
}
	
$("#id_perfil").change(update_usuario);


$("#usuario").dblclick(function() {
   copyItemList('usuario','usuarioSelect');
});

$("#usuarioSelect").dblclick(function() {
   copyItemList('usuarioSelect','usuario');
});
</script>
<div id="botonera">
	<img id="modulo_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="perfil_usuario" id="perfil_usuario" >
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Perfil Usuario </th>
		</tr>
		<tr>
			<th>Perfil:			</th>
			<td>
				<select name="id_perfil" id="id_perfil">
				<option value="0">--SELECCIONE--</option>
					<?=$opt_perfil?>
			  </select>
				<!--<input type="button" onclick="verProps('id_perfil')" value="ver" />-->			</td>
		</tr>
		<tr>
			<th>Usuarios:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px;" name="usuario" size="15"  multiple="MULTIPLE" id="usuario">
				
			</select>			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onClick="copyItemList('usuario','usuarioSelect')">
			<input style="width:30px;" type="button" value=">>" onClick="copyItemList('usuario','usuarioSelect',true)">			</td>	

			<td rowspan="2">	 
			<select style="width:200px;" name="usuarioSelect" size="15"  multiple="MULTIPLE" id="usuarioSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onClick="copyItemList('usuarioSelect','usuario')">
				<input style="width:30px;" type="button" value="<<" onClick="copyItemList('usuarioSelect','usuario',true)">				</td>
			</tr>
			</table>			</td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>
