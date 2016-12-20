<?
class guardar extends ClaseBase
{
	public function guarda($identificacion,$nombre,$pagina,$variables,$target,$menu,$publico,$obs)
	{ 
	 	$conexion = new dbdatos();
		$ver = "SELECT * FROM modulo order by id;";
		$resulta = $conexion->consulta($ver);
		$e = pg_num_rows($resulta);
		if($e == "")
		{
			$e++;		
		}else
		{
			$e++;
			$row = pg_fetch_array($resulta);
		}
$sql="INSERT INTO modulo (id, id_grupo, identificacion, nombre, pagina , variables, target, menu, publico, obs, fecha_actualizacion) VALUES (".$e.", '".$row[0]."', '".$identificacion."', '".$nombre."', '".$pagina."', '".$variables. "', '".$target."', '".$menu."' , '".$publico."', '".$obs."', '".date("Y-m-d H:i:s")."');";	
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