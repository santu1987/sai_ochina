<?
	require_once '../../../controladores/main.php';
	require_once '../../../controladores/dbdatos.php';
	require_once '../../../controladores/ClaseBase.php';
	require_once 'guardar.php';
	
	if ($_POST["boton"] ==1){
		guardar::guarda($_POST["nombre"],$_POST["linnk"],$_POST["estatus"]);
		echo '<script>self.location.href = "../db/menu.php";</script>';
	}
	
?>