<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT * FROM estado ";
$rs_grupo =& $conn->Execute($sql);
while (!$rs_grupo->EOF) {
	$opt_link.="<option value='".$rs_grupo->fields("id_estado")."' >".$rs_grupo->fields("estado")."</option>";		

   $rs_grupo->MoveNext();
}
$sql2="SELECT * FROM inmueble ";
$rs_grupo2 =& $conn->Execute($sql2);
while (!$rs_grupo2->EOF) {
	$opt_linki.="<option  value='".$rs_grupo2->fields("id_inmueble")."' >".$rs_grupo2->fields("nombre")."</option>";		

   $rs_grupo2->MoveNext();
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
	<table  width="450"   id="cuerpo_formulario">
		<tr>
			<th colspan="4" class="titulo_frame"><img src="imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Nuevo registro</th>
		</tr>
		<tr>
			<th >Codigo:</th><td colspan="3">&nbsp;<?=$codigo?></td>
		</tr>
		<tr>
			<th  >Banco:</th><td><input type="text"  size="25" name="banco" value="<?=$banco?>"></td>
		</tr>
		<tr>
			<th  >Sucursal</th><td><input name="sucursal" type="text"  onKeyPress="return acceptLetra(event);" value="<?=$sucursal;?>" size="40"></td>
		</tr>
		<tr>
			<th >Correo electr&oacute;nico</th><td><input name="email" type="text"  onblur="validarEmailNecesario(this);" value="<?=$email;?>" size="40" ></td>
		</tr>
		<tr>
			<th >Pagina Web</th><td><input name="pag_web" type="text" value="<?=$pag_web;?>" size="40"></td>
		</tr>
		<tr>
			<th >Persona de Contacto</th><td><input name="persona_contacto" type="text" value="<?=$persona_contacto;?>" size="40"></td>
		</tr>
		<tr>
			<th >Cargo del Contacto</th><td><input name="cargo_contacto" type="text" value="<?=$cargo_contacto;?>" size="40"></td>
		</tr>
		<tr>
			<th  >Estatus</th><td>
				<select name="estatu" id="estatu">
					<option value="0" selected="selected"></option>
					<option value="1" <?=$act;?>>Activo</option>
					<option value="2" <?=$act2;?>>Inactivo</option>
			  </select>		
			</td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="4C7595" style="color:#FFFFFF"><strong>Direcci&oacute;n</strong></td>	  
	  	</tr>

		<tr>
			<th  >Estado</th><td>
				<select name="estado"   >
					<option value="0"></option>
					<?=$opt_link?>
				</select>		
			</td>
		</tr>
		<tr>
			<th >Ciudad</th><td><input name="ciudad" class="campo" type="text" /></td>
		</tr>
		<tr>
			<th >Urbanizaci&oacute;n</th><td><input type="text" name="urb" class="campo" value=" <?=$urb;?>"></td>
		</tr>
		<tr>
			<th >Tipo de Inmueble</th><td>
				<select name="tip_inmu" id="tip_inmu">
					<option value="0"></option>
					<?=$opt_linki?>
			  	</select>		
			  </td>
		</tr>
		<tr>
			<th >Piso</th>				<td>	<input type="text" name="piso"  value=" <?=$piso;?>"></td>
		</tr>
		<tr>
			<th >Punto Refenrencia</th>	<td>	<input type="text"  name="ptoref" value=" <?=$ptoref;?>"  size="40"></td>
		</tr>
		<tr>
			<th >Descripci&oacute;n</td><td><input type="text" name="descripcion" value=" <?=$descripcion;?>"  size="40"></td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="4C7595" style="color:#FFFFFF"><strong>Tel&eacute;fonos</strong></td>	  
	  	</tr>
		<tr>
			<th >Principal</th>	<td>	<input name="telefono" type="text" class="campo" onKeyPress="return acceptNumInt(event);" value="<?=$telefono;?>" maxlength="12" />		
			</td>
		</tr>
		<tr>	
			<th >Celular</th>	<td>	<input name="celular" type="text" class="campo"  onKeyPress="return acceptNumInt(event);" value="<?=$celular;?>" maxlength="12"/>		</td>
		</tr>
		<tr>
			<th >Fax</th>	<td>	<input name="fax" type="text" class="campo"  onKeyPress="return acceptNumInt(event);" value="<?=$fax;?>" maxlength="12"/>		</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom_frame">&nbsp;</td>
		</tr>
	</table>
<input type="hidden" name="boton">
<input type="hidden" name="operacion" value="<?= $_POST['operacion'];?>">
<input type="hidden" name="id" value="<?=$codigo;?>" />
</form>
