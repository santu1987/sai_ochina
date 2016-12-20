<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM organismos ";
$rs_grupo =& $conn->Execute($sql);
while (!$rs_grupo->EOF) {
	$opt_link.="<option ".(($rs_grupo->fields("id_organismo"))?"style='padding-left:5px;'":"")." value='".$rs_grupo->fields("id_organismo")."' >".$rs_grupo->fields("organismo")."</option>";		

   $rs_grupo->MoveNext();
}


require_once('../../../../controladores/popup.class.php');
$popup				=	new popup();
?>

<link rel="stylesheet" type="text/css" href="utilidades/boxy-0.1.4/src/stylesheets/boxy.css">
<script src="utilidades/boxy-0.1.4/src/javascripts/jquery.boxy.js" type="text/javascript"></script>

<span class="msg" id="msgAjax_registrarModulo"></span>

<div id="botonera">
	<img id="btn_consultar" src="imagenes/null.gif" onclick="Application.evalCode('win_popup_armador', true);" />
	<img id="btn_guardar" src="imagenes/null.gif" onclick="guardar()" />
</div>

<form method="post" name="cargo">
	<table align="center" width="450"  id="cuerpo_formulario">
		<tr>
			<th colspan="4" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Nuevo registro</th>
		</tr>
		<tr>
			<th>Codigo:</th>
			<td colspan="3">&nbsp;<?=$codigo?></td>
		</tr>
		<tr>
			<th>Organismo</th>
			<td >
				<select name="id_organismo" id="id_organismo">
					<option value="0"></option>
					<?=$opt_link?>
				</select>		
			</td>
		</tr>
		<tr>
			<th >Nombre</th>		<td ><input name="nombre" type="text"   value="<?=$nombre;?>" size="40"></td>
		</tr>
		<tr>
			<th >Comentario</th>	<td ><textarea name="comentario" cols="40" rows="3"><?=$comentario;?>
			</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="boton">
<input type="hidden" name="operacion" value="<?= $_POST['operacion'];?>">
<input type="hidden" name="id" value="<?=$codigo;?>" />
</form>
