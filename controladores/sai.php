<?
	require_once '../CONFIGURACION/main.php';
	require_once '../CONTROLADORES/dbdatos.php';
	require_once '../MODELOS/modelosai.php';
// **************   BANCOS	
if ($_POST['cargo'] != "" && $_POST['estatus'] != 0)
{
//echo $_POST['operacion'];
	if ($_POST['operacion'] == 1)
	{
		bancos::guardarGuarda($_POST['cargo'],$_POST['estatus']); 
		if ($_POST['boton'] == 1)
		{
			echo '<a href="../MATENIMIENTO/bancos_vista.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/bancos_vista.php";</script>';
		}
		if ($_POST['boton'] == 2)
		{
			echo '<a href="../MATENIMIENTO/bancos.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/bancos.php";</script>';
		}
	}elseif ($_POST['operacion'] == 2)
	{
		bancos::actualizar($_POST['id'],$_POST['cargo'],$_POST['estatus']); 
		if ($_POST['boton'] == 1)
		{
			echo '<a href="../MATENIMIENTO/bancos_vista.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/bancos_vista.php";</script>';
		}
		if ($_POST['boton'] == 2)
		{
			echo '<a href="../MATENIMIENTO/bancos.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/bancos.php";</script>';
		}
	}
}
// **************   UNIDAD DE MEDIDA	
if ($_POST['unidad_medida'] != "" && $_POST['COMENTARIO'] != "")
{
//echo $_POST['operacion'];
	if ($_POST['operacion'] == 1)
	{
		unidad_medida::guardarGuarda($_POST['unidad_medida'],$_POST['COMENTARIO']); 
		if ($_POST['boton'] == 1)
		{
			echo '<a href="../MATENIMIENTO/unidad_medida_vista.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/unidad_medida_vista.php";</script>';
		}
		if ($_POST['boton'] == 2)
		{
			echo '<a href="../MATENIMIENTO/unidad_medida.php">Continuar</a>';
			echo '<scriptself.location.href = "../MATENIMIENTO/unidad_medida.php";</script>';
		}
	}elseif ($_POST['operacion'] == 2)
	{
		unidad_medida::actualizar($_POST['id'],$_POST['unidad_medida'],$_POST['COMENTARIO']); 
		if ($_POST['boton'] == 1)
		{
			echo '<a href="../MATENIMIENTO/bancos_vista.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/unidad_medida_vista.php";</script>';
		}
		if ($_POST['boton'] == 2)
		{
			echo '<a href="../MATENIMIENTO/bancos.php">Continuar</a>';
			echo '<script>self.location.href = "../MATENIMIENTO/unidad_medida.php";</script>';
		}
	}
}
?>
