<?
	require_once '../CONFIGURACION/main.php';
	require_once '../CONTROLADORES/dbdatos.php';
	require_once '../MODELOS/sucursal.php';
?>
<link href="../../datos_base/RECURSOS/SEOC.css" rel="stylesheet" type="text/css">
<form method="post" name="clienten_vista">
<table width="100%">
	<tr>
		<td class="titulo" colspan="5" align="center" bgcolor="#44439A"> CLIENTE</td>
	</tr>
	<tr align="center">
		<td class="menu"> CODIGO</td>
		<td class="menu"> SUCURSAL</td>
		<td class="menu"> EMAIL</td>
		<td class="menu"> PERSONA CONTACTO</td>
		<td class="menu"> VER</td>
	</tr>
	<? foreach(sucursal::buscart() as $obj){ ?>
	<tr align="center">
		<td class="consulta"> <?= $obj-> get("ID_SUCURSAL")?></td>
		<td class="consulta"> <?= $obj-> get("SUCURSAL")?></td>
		<td class="consulta"> <?= $obj-> get("EMAIL_SU")?></td>
		<td class="consulta"> <?= $obj-> get("PERSO_CONTA")?></td>
		<td class="consulta"><input type="radio" name="id" value="<?= $obj-> get("ID_SUCURSAL")?>"> VER</td>
	</tr>
	<? } ?>
	<tr>
		<td class="menu"colspan="1">&nbsp;</td>
		
		<td class="menu" align="right" colspan="2"> 
				 <input name="AGREGAR" type="button" class="boton" value="AGREGAR" onClick="agregar();">
				 <input name="ACTUALIZAR" type="button" class="boton" value="ACTUALIZAR" onClick="actualizar();">
		</td>
		
		<td class="menu" colspan="2"> 
				<input name="ELIMINAR" type="button" class="boton" value="ELIMINAR" onClick="eliminar();">
				<input name="REGRESAR" type="button" class="boton" value="REGRESAR" onclick="">
		</td>
		
	</tr>
</table>
<input type="hidden" name="formula" />
<input type="hidden" name="boton" />
<input type="hidden" name="operacion" />
</form>
<script>
	function agregar()
	{
		var operacion = 1;
		document.all.operacion.value = operacion;
		clienten_vista.action="sucursal.php";
		clienten_vista.submit();
	}
	function actualizar()
	{
		var operacion = 2;
		var id = document.all.id.checked;
		if (id != "")
		{
			document.all.operacion.value = operacion;
			clienten_vista.action="sucursal.php";
			clienten_vista.submit();
		}else
		{
			alert('Debe seleccionar un registro');
		}
	}
	function eliminar()
	{
		var operacion = 3;
		var formula = 1;
		var id = document.all.id.checked;
		if (id != "")
		{
			document.all.operacion.value = operacion;
			document.all.formula.value = formula;
			clienten_vista.action="../AJAX/seocel.php";
			clienten_vista.submit();
		}else
		{
			alert('Debe seleccionar un registro');
		}
	}
</script>