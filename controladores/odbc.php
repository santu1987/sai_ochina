<?
$dsn = "prueba";
//debe ser de sistema no de usuario
$usuario = "";
$clave="";

//realizamos la conexion mediante odbc
$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
}	

// consulta SQL a nuestra tabla "usuarios" que se encuentra en la base de datos "db.mdb"
$sql="Select * from usuarios";

// generamos la tabla mediante odbc_result_all(); utilizando borde 1
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
print odbc_result_all($result,"border=1");
?>