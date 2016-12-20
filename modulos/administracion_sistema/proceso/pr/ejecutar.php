<?
require_once '../../../../controladores/main.php';
require_once '../../../../controladores/dbdatos.php';
require_once '../../../../controladores/ClaseBase.php';
require_once 'guardar.php';
	
if ($_POST["boton"] ==1){
	guardar::guarda($_POST["identificacion"],$_POST["nombre"],$_POST["pagina"], $_POST["variables"],$_POST["target"],$_POST["menu"],$_POST["publico"],$_POST["obs"]);
	echo '<script>self.location.href = "../db/modulo.php";</script>';
}
	
?>