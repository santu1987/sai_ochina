<? session_start();?>
<script type="text/javascript" src="utilidades/selectboxes/jquery.selectboxes.js"></script>
<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM accion_centralizada WHERE id_organismo = ".$_SESSION["id_organismo"]." ORDER BY denominacion";
$rs_perfil =& $conn->Execute($sql);
if ($rs_perfil){
	while (!$rs_perfil->EOF) {
		$opt_accion_central.="<option value='".$rs_perfil->fields('id_accion_central')."'>".$rs_perfil->fields('denominacion')."</option>";
	$rs_perfil->MoveNext();
	}
}
?>
<script type='text/javascript'>
function update_accion_especifica_centra_pr_accion_especifica()
{ 
	$("#accion_especifica_pr_accion_especificaSelect").removeOption(/./);
	$("#accion_especifica_pr_accion_especificaSelect").ajaxAddOption("modulos/presupuesto/accion_especifica/pr/cmb.sql.accion_especifica_e.php",{id_modulo:$(this).val()});
	$("#accion_especifica_centra_pr_accion_especifica").removeOption(/./);
	$("#accion_especifica_centra_pr_accion_especifica").ajaxAddOption("modulos/presupuesto/accion_especifica/pr/cmb.sql.accion_especifica_f.php",{id_modulo:$(this).val()});

}

$("#accion_especifica_centra_pr_accion_central").change(update_accion_especifica_centra_pr_accion_especifica);

$("#accion_especifica_centra_pr_accion_especifica").dblclick(function() {
   copyItemList('accion_especifica_centra_pr_accion_especifica','accion_especifica_pr_accion_especificaSelect');
});

$("#accion_especifica_pr_accion_especificaSelect").dblclick(function() {
   copyItemList('accion_especifica_pr_accion_especificaSelect','accion_especifica_centra_pr_accion_especifica');
});

$("#perfil_accion_especifica_centra_pr_btn_guardar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: "modulos/presupuesto/accion_especifica/pr/sql.accion_especifica_central.php",
		data:dataForm('form_pr_accion_especifica_centra'),
		type:'POST',
		cache: true,
		success: function(html)
		{
			if (html=="Ok")
			{
				setBarraEstado(mensaje[registro_exitoso],true,true);
			}
			else
			{
					setBarraEstado(html,true,true);
			}
		}
	});
});
</script>
<div id="botonera">
	<img id="perfil_accion_especifica_centra_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>
<form method="post" name="form_pr_accion_especifica_centra" id="form_pr_accion_especifica_centra" >
	<table   class="cuerpo_formulario">
		<tr>
			<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/proceso28x28.png" style="padding-right:5px;" align="absmiddle" />Acci&oacute;n Central - Acci&oacute;n Especifica</th>
		</tr>
		<tr>
			<th>Acci&oacute;n Centralizada:			</th>
			<td>
				<select name="accion_especifica_centra_pr_accion_central" id="accion_especifica_centra_pr_accion_central">
				<option>-- Seleccione --</option>
				<?=$opt_accion_central;?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Especifica:	</th>	
			<td>
			<table>
			<tr>
			<td rowspan="2">
			<select style="width:200px;" name="accion_especifica_centra_pr_accion_especifica" id="accion_especifica_centra_pr_accion_especifica" size="15"  multiple="MULTIPLE" >
			</select>
			
			</td>
			<td>
			<input style="width:30px;" type="button" value=">" onclick="copyItemList('accion_especifica_centra_pr_accion_especifica','accion_especifica_pr_accion_especificaSelect')">
			<input style="width:30px;" type="button" value=">>" onclick="copyItemList('accion_especifica_centra_pr_accion_especifica','accion_especifica_pr_accion_especificaSelect',true)">
			</td>	

			<td rowspan="2">	 
			<select style="width:200px;" name="accion_especifica_pr_accion_especificaSelect" size="15"  multiple="MULTIPLE" id="accion_especifica_pr_accion_especificaSelect">
			</select>
			<!--<input type="button" onclick="verProps('modulo')" value="ver" />-->
			</td>
			</tr>	
			<tr>
				<td>
				<input style="width:30px;" type="button" value="<" onclick="copyItemList('accion_especifica_pr_accion_especificaSelect','accion_especifica_centra_pr_accion_especifica')">
				<input style="width:30px;" type="button" value="<<" onclick="copyItemList('accion_especifica_pr_accion_especificaSelect','accion_especifica_centra_pr_accion_especifica',true)">
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