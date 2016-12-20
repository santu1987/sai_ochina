<?
class guardar extends ClaseBase
{
	public function guarda($organismo, $direccion1, $direccion2, $codigo_area, $telefono, $fax, $rif, $nit, $pagina_web, $email, $representante, $cedula_repre, $cargo_repre)
	{ 
	 	$conexion = new dbdatos();
		$ver = "SELECT * FROM organismos order by id_organismo;";
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
		$sql="INSERT INTO organismos (id_organismo , organismo, direccion1, direccion2, codigo_area, telefono, fax, rif, nit, pagina_web, email, representante, cedula_repre, cargo_repre) VALUES (".$e.",'".$organismo."', '".$direccion1."','".$direccion2."',".$codigo_area.", '".$telefono."','".$fax."','".$rif."', '".$nit."','".$pagina_web."','".$email."', '".$representante."','".$cedula_repre."','".$cargo_repre."');";	
		echo $sql;
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