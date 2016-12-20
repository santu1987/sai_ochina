<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM modulo";
$rs_modulo =& $conn->Execute($sql);
$xx = 'javascript:var optionnueva = new Option(perfil_modulo.modulo.options[perfil_modulo.modulo.selectedIndex].text,  perfil_modulo.modulo.options[perfil_modulo.modulo.selectedIndex].value); perfil_modulo.moduloSelect.options[perfil_modulo.moduloSelect.length]=optionnueva;
elem=document.getElementById("modulo"); 
				if (elem.selectedIndex!=-1)  
					elem.options[elem.selectedIndex]=null;
';
while (!$rs_modulo->EOF) {
	$opt_modulo.="<option value='".$rs_modulo->fields("id")."' ondblclick='".$xx."'>".$rs_modulo->fields("nombre")."</option>";
$rs_modulo->MoveNext();
}

$sql="SELECT * FROM perfil";
$rs_perfil =& $conn->Execute($sql);
while (!$rs_perfil->EOF) {
	$opt_perfil.="<option value='".$rs_perfil->fields('id_perfil')."'>".$rs_perfil->fields('nombre')."</option>";
$rs_perfil->MoveNext();
}
?>
<script type='text/javascript'>

$("#perfil_modulo_pr_btn_guardar").click(function() {
	$.ajax ({
		url: "modulos/administracion_sistema/perfil/pr/sql.registrar_perfil_modulo.php",
		data:dataForm('form_pr_perfil_modulo'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			if (html=="Registrado")
			{
				setBarraEstado(mensaje[registro_exitoso],true,true);
			}
			else
			{
				setBarraEstado(html);
			}
		}
	});
});
function update_modulo()
{ 
	$("#modulo").removeOption(/./);
	$("#modulo").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.modulo.php",{id_perfil:$(this).val()},false);
	$("#moduloSelect").removeOption(/./);
	$("#moduloSelect").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.moduloSelect.php",{id_perfil:$(this).val()},false);
}
	
$("#id_perfil").change(update_modulo);
$("#modulo").dblclick(function() {
   copyItemList('modulo','moduloSelect');
});

$("#moduloSelect").dblclick(function() {
   copyItemList('moduloSelect','modulo');
});
</script>
<div id="botonera">
	<img id="perfil_modulo_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_perfil_modulo" id="form_pr_perfil_modulo" >
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Perfil M&oacute;dulo </th>
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
			<th>M&oacute;dulo:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px;" name="modulo" size="15"  multiple="MULTIPLE" id="modulo" >
				
			</select>			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onClick="copyItemList('modulo','moduloSelect')">
			<input style="width:30px;" type="button" value=">>" onClick="copyItemList('modulo','moduloSelect',true)">			</td>	

			<td rowspan="2">	 
			<select style="width:200px;" name="moduloSelect" size="15"  multiple="MULTIPLE" id="moduloSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onClick="copyItemList('moduloSelect','modulo')">
				<input style="width:30px;" type="button" value="<<" onClick="copyItemList('moduloSelect','modulo',true)">				</td>
			</tr>
			</table>			</td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>