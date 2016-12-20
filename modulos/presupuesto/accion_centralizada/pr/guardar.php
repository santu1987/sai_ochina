<?
class guardar extends ClaseBase
{
	public function guarda($nombre,$linnk,$estatus)
	{ 
	 	$conexion = new dbdatos();
		$ver = "SELECT * FROM menu order by id_menu;";
		$resulta = $conexion->consulta($ver);
		$e = pg_num_rows($resulta);
		//echo $e;
		if($e == "")
		{
			$e++;		
			
		}else
		{
			$e++;
		}
		$sql="INSERT INTO menu (id_menu , nombre, linnk, estatus) VALUES (".$e.",'".$nombre."', '".$linnk."',".$estatus.");";	
		//echo $sql;
		if ($resultSet = $conexion->consulta($sql))
		{
			echo '<script>alert("Sus datos fueron registrados y actualizados");</script>';
			
		}
		else
		{
			echo '<script>alert("01) Operación no realizada");</script>';
		}
	return 0;
	} 	
		
}

?>