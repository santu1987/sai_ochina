<? session_start();?>
<script type="text/javascript" src="utilidades/selectboxes/jquery.selectboxes.js"></script>

<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM proyecto WHERE id_organismo = ".$_SESSION["id_organismo"]." ORDER BY nombre";
$rs_organismo =& $conn->Execute($sql);
while (!$rs_organismo->EOF) {
	$opt_organismo.="<option value='".$rs_organismo->fields('id_proyecto')."'>".$rs_organismo->fields('nombre')."</option>";
$rs_organismo->MoveNext();
}
?>
<script type='text/javascript'>

function update_accion_especifica()
{ 
	$("#accion_espe_pro_pr_accion_especifica").removeOption(/./);
	$("#accion_espe_pro_pr_accion_especifica").ajaxAddOption("modulos/presupuesto/accion_especifica/pr/cmb.sql.accion_especifica_proyecto_f.php",{id_organismo:$("#accion_espe_pro_pr_organismo").val(),id_accion:$(this).val()});
	$("#accion_espe_pro_pr_accion_especificaSelect").removeOption(/./);
	$("#accion_espe_pro_pr_accion_especificaSelect").ajaxAddOption("modulos/presupuesto/accion_especifica/pr/cmb.sql.accion_especifica_proyecto_e.php",{id_organismo:$("#accion_espe_pro_pr_organismo").val(),id_acciones:$(this).val()});

}

$("#accion_espe_pro_pr_proyecto").change(update_accion_especifica);
//---------------------------------------------------------------------------------------

$("#accion_espe_pro_pr_accion_especifica").dblclick(function() {
   copyItemList('accion_espe_pro_pr_accion_especifica','accion_espe_pro_pr_accion_especificaSelect');
});
$("#accion_espe_pro_pr_accion_especificaSelect").dblclick(function() {
   copyItemList('accion_espe_pro_pr_accion_especificaSelect','accion_espe_pro_pr_accion_especifica');
});
//---------------------------------------------------------------------------------------

$("#accion_espe_pro_pr_btn_guardar").click(function() {
	setBarraEstado("<img align='absmiddle' src='imagenes/loading.gif' /> Esperando Respuesta del Servidor...");
	$.ajax ({
		url: "modulos/presupuesto/accion_especifica/pr/sql.accion_especifica_proyecto.php",
		data:dataForm('form_pr_accion_especifica_proyecto'),
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
	<img id="accion_espe_pro_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
</div>

<form method="post" name="form_pr_accion_especifica_proyecto" id="form_pr_accion_especifica_proyecto" >
	<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4">
			<img src="imagenes/iconos/proceso28x28.png" style="padding-right:5px;" align="absmiddle" />Proyecto - Acci&oacute;n Especif&iacute;ca
		</th>
	</tr>
		<tr>
			<th>Proyecto:			</th>
			<td>
				<select name="accion_espe_pro_pr_proyecto" id="accion_espe_pro_pr_proyecto">
				<option value="0">--SELECIONE--</option>
					<?=$opt_organismo;?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Acci&oacute;n Espec&iacute;fica:</th>
			<td>
				<table>
					<tr>
						<td rowspan="2">
							<select style="width:200px;" name="accion_espe_pro_pr_accion_especifica" id="accion_espe_pro_pr_accion_especifica" size="15"  multiple="MULTIPLE">
							</select>
						</td>
						<td>
							<input type="button" value=">" style="width:30px;" onclick="copyItemList('accion_espe_pro_pr_accion_especifica','accion_espe_pro_pr_accion_especificaSelect')">
							<input type="button" value=">>" style="width:30px;" onclick="copyItemList('accion_espe_pro_pr_accion_especifica','accion_espe_pro_pr_accion_especificaSelect',true)">
						</td>
						<td rowspan="2">
							<select style="width:200px;" name="accion_espe_pro_pr_accion_especificaSelect" id="accion_espe_pro_pr_accion_especificaSelect" size="15"  multiple="MULTIPLE">
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<input style="width:30px;" type="button" value="<" onclick="copyItemList('accion_espe_pro_pr_accion_especificaSelect','accion_espe_pro_pr_accion_especifica')">
							<input style="width:30px;" type="button" value="<<" onclick="copyItemList('accion_espe_pro_pr_accion_especificaSelect','accion_espe_pro_pr_accion_especifica',true)">
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