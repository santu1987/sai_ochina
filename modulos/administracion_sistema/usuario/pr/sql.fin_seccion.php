<?php
// Inicializa de la sesi&oacute;n.
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');

$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$sql = " UPDATE  sesion SET  estatus = 2	WHERE id_usuario = ". $_SESSION["id_usuario"];

		if ($conn->Execute($sql) === false) {
			echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
		}
// Destruye todas las variables de la sesi&oacute;n
session_unset();
// Finalmente, destruye la sesi&oacute;n
session_destroy();
echo "<script>window.location='../../../../index.php';</script>";

?>