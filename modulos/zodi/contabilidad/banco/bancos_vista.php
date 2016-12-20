<!--<?/*
	require_once '../../CONFIGURACION/main.php';
	require_once '../../CONTROLADORES/dbdatos.php';*/
	require_once 'buscar.php';
?>-->
<link href="../../datos_base/bancos/RECURSOS/SEOC.css" rel="stylesheet" type="text/css">-->
<link href="co/vista_consultar_banco.php" >
<form name="vista_cargo" method="post">
	<table width="100%">
		<tr>
			<td class="titulo" colspan="4" align="center" bgcolor="#44439A"> BANCOS</td>
		</tr>
		<tr align="center">
			<td class="menu" width="25%"> C&Oacute;DIGO</td>
			<td class="menu" width="35%"> NOMBRE</td>
			<td class="menu" width="20%"> ESTATUS</td>
			<td class="menu" width="20%"> VER</td>
		</tr>
		<? 
		if (Buscar::buscarSelect("banco"))
		{
			foreach(Buscar::buscarSelect("banco") as $obj){ ?>
			<tr align="center">
				<td class="consulta"> <?= $obj-> get(0)?></td>
				<td class="consulta"> <?= $obj-> get(1)?></td>
				<td class="consulta"> <?
				if  ($obj-> get(2) == 1)
				{
					echo 'Activo';
				}
				else
				{
					echo 'Inactivo';
				}
				?></td>
				<td class="consulta"><input type="radio" name="id" value="<?= $obj-> get(0)?>"> VER</td>
			</tr>
			<? 
			}
		}
		else
		{?>
			<tr>
				<td colspan="4" class="consulta" align="center">No se encontraron Datos</td>
			</tr>
		<?
		}
		 ?>
	
			<tr>
				<td class="menu"colspan="2">&nbsp;</td>
				<td class="menu" align="right"> 
				 <input name="AGREGAR" type="button" class="boton" value="GUARDAR" onClick="agregar();">
				 <input name="ACTUALIZAR" type="button" class="boton" value="ACTUALIZAR" onClick="actualizar();">
				</td>
				<td class="menu" > 
				<input name="ELIMINAR" type="button" class="boton" value="ELIMINAR" onClick="eliminar();">
				<input name="REGRESAR" type="button"  value="REGRESAR" onclick="javascript:load('datos_base/bancos/post.php', '', '');">
				</td>
			</tr>
			<input type="hidden" name="operacion">
			<input type="hidden" name="formu">
	</table>
</form>

<script>
	function agregar()
	{
		var operacion = 1;
		document.all.operacion.value = operacion;
		vista_cargo.action="bancos.php";
		vista_cargo.submit();
	}
	function actualizar()
	{
		var operacion = 2;
		var id = document.all.id.checked;
		if (id != "")
		{
			document.all.operacion.value = operacion;
			vista_cargo.action="bancos.php";
			vista_cargo.submit();
		}else
		{
			alert('Debe seleccionar un registro');
		}
	}
	function eliminar()
	{
		var operacion = 3;
		var formu = 1;
		var id = document.all.id.checked;
		if (id != "")
		{
			document.all.operacion.value = operacion;
			document.all.formu.value = formu;
			vista_cargo.action="../AJAX/seocel.php";
			vista_cargo.submit();
		}else
		{
			alert('Debe seleccionar un registro');
		}
	}
</script>