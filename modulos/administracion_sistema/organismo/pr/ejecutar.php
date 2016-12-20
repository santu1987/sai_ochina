<?
	require_once '../../../controladores/main.php';
	require_once '../../../controladores/dbdatos.php';
	require_once '../../../controladores/ClaseBase.php';
	require_once 'guardar.php';
	
	if ($_POST["boton"] ==1){
	$direccion1 = $_POST["urb"] .", ".$_POST["tip_inmu"] ."  ".$_POST["piso"] .", ".$_POST["ptoref"] .", ".$_POST["descripcion"] .", ".$_POST["ciudad"] .", ".$_POST["estado"];
	$direccion2 = $_POST["urb2"] .", ".$_POST["tip_inmu2"] ."  ".$_POST["piso2"] .", ".$_POST["ptoref2"] .", ".$_POST["descripcion2"] .", ".$_POST["ciudad2"] .", ".$_POST["estado2"];
	
	
		guardar::guarda($_POST["organismo"], $direccion1, $direccion2 ,$_POST["cod_area"] ,$_POST["telefono"] ,$_POST["fax"] ,$_POST["rif"] ,$_POST["nit"] ,$_POST["pag_web"] ,$_POST["email"] ,$_POST["persona_contacto"] ,$_POST["cedula_persona"] ,$_POST["cargo_contacto"]);
		echo '<scriptself.location.href = "../db/organismo.php";</script>';
	}
	
?>