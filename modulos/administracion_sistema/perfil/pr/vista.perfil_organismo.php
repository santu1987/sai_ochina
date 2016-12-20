<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM organismo";
$rs_organismo =& $conn->Execute($sql);
$xx = 'javascript:var optionnueva = new Option(perfil_organismo.organismo.options[perfil_organismo.organismo.selectedIndex].text,  perfil_organismo.organismo.options[perfil_organismo.organismo.selectedIndex].value); perfil_organismo.organismoSelect.options[perfil_organismo.organismoSelect.length]=optionnueva;
elem=document.getElementById("organismo"); 
				if (elem.selectedIndex!=-1)  
					elem.options[elem.selectedIndex]=null;
';
while (!$rs_organismo->EOF) {	
	$opt_organismo.="<option value='".$rs_organismo->fields('id_organismo')."' ondblclick='".$xx."'>".$rs_organismo->fields("nombre")."</option>";
$rs_organismo->MoveNext();
 }

$sql="SELECT * FROM perfil";
$rs_perfil =& $conn->Execute($sql);
while (!$rs_perfil->EOF) {
	$opt_perfil.="<option value='".$rs_perfil->fields("id_perfil")."' >".$rs_perfil->fields("nombre")."</option>";
$rs_perfil->MoveNext();
}

?>
<script type='text/javascript'>

$("#perfil_organismo_pr_btn_guardar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: "modulos/administracion_sistema/perfil/pr/sql.registrar_perfil_organismo.php",
		data:dataForm('form_pr_perfil_organismo'),
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
function update_organismo()
{ 
	$("#organismo").removeOption(/./);
	$("#organismo").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.organismo.php",{id_perfil:$(this).val()},false);
	$("#organismoSelect").removeOption(/./);
	$("#organismoSelect").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.organismoSelect.php",{id_perfil:$(this).val()},false);
}
	
$("#id_perfil").change(update_organismo);

$("#organismo").dblclick(function() {
   copyItemList('organismo','organismoSelect');
});

$("#organismoSelect").dblclick(function() {
   copyItemList('organismoSelect','organismo');
});
</script>
<div id="botonera">
	<img id="perfil_organismo_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_perfil_organismo" id="form_pr_perfil_organismo" >
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Perfil Organismo</th>
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
			<th>Organismo:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px;" name="organismo" size="15"  multiple="MULTIPLE" id="organismo">
				
			</select>			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onClick="copyItemList('organismo','organismoSelect')">
			<input style="width:30px;" type="button" value=">>" onClick="copyItemList('organismo','organismoSelect',true)">			</td>	

			<td rowspan="2">	 
			<select style="width:200px;" name="organismoSelect" size="15"  multiple="MULTIPLE" id="organismoSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onClick="copyItemList('organismoSelect','organismo')">
				<input style="width:30px;" type="button" value="<<" onClick="copyItemList('organismoSelect','organismo',true)">				</td>
			</tr>
			</table>			</td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>