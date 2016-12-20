<script type="text/javascript" src="utilidades/selectboxes/jquery.selectboxes.js"></script>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM perfil ORDER BY nombre";
$rs_perfil =& $conn->Execute($sql);
while (!$rs_perfil->EOF) {
	$opt_perfil.="<option value='".$rs_perfil->fields('id_perfil')."'>".$rs_perfil->fields('nombre')."</option>";
$rs_perfil->MoveNext();
}
?>
<script type='text/javascript'>
function update_modulo()
{
	$("#modulo").removeOption(/./);
	$("#modulo").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.programa_modulo.php",{id_perfil:$(this).val()},false,update_programa);
}

function update_programa()
{ 
	$("#programa").removeOption(/./);
	$("#programa").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.programa.php",{id_perfil:$("#id_perfil").val(),id_modulo:$(this).val()},false);
	$("#programaSelect").removeOption(/./);
	$("#programaSelect").ajaxAddOption("modulos/administracion_sistema/perfil/pr/cmb.sql.programaSelect.php",{id_perfil:$("#id_perfil").val(),id_modulo:$(this).val()},false);
}
	
$("#id_perfil").change(update_modulo);

$("#modulo").change(update_programa);


$("#programa").dblclick(function() {
   copyItemList('programa','programaSelect');
});

$("#programaSelect").dblclick(function() {
   copyItemList('programaSelect','programa');
});

$("#perfil_programa_pr_btn_guardar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: "modulos/administracion_sistema/perfil/pr/sql.perfil_programa.php",
		data:dataForm('form_pr_perfil_programa'),
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
</script>
<div id="botonera">
	<img id="perfil_programa_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_perfil_programa" id="form_pr_perfil_programa" >
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Perfil Programa</th>
		</tr>
		<tr>
			<th>Perfil:			</th>
			<td>
				<select name="id_perfil" id="id_perfil">
					<option value="0">--SELECCIONE--</option>
					<?=$opt_perfil?>
				</select>
				<!--<input type="button" onclick="verProps('id_perfil')" value="ver" />-->
			</td>
		</tr>
		<tr>
			<th>M&oacute;dulo:			</th>
			<td>
				<select name="modulo" id="modulo">
				</select>
			</td>
		</tr>
		<tr>
			<th>Programa:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px;" name="programa" size="15"  multiple="MULTIPLE" id="programa">
			</select>
			
			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onclick="copyItemList('programa','programaSelect')">
			<input style="width:30px;" type="button" value=">>" onclick="copyItemList('programa','programaSelect',true)">
			</td>	

			<td rowspan="2">	 
			<select style="width:200px;" name="programaSelect" size="15"  multiple="MULTIPLE" id="programaSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->
			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onclick="copyItemList('programaSelect','programa')">
				<input style="width:30px;" type="button" value="<<" onclick="copyItemList('programaSelect','programa',true)">
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr> 
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr> 
	</table>
	
</form>