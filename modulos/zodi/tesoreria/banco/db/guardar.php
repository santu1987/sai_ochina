<?
class guardar extends ClaseBase
{
	public function guarda($banco,$sucursal,$email,$pag_web,$persona_contacto,$cargo_contacto,$estatu,$direccion ,$cod_area,$telefono,$fax,$cod_cel,$celular)
	{ 
	 	$conexion = new dbdatos();
		$ver = "SELECT * FROM BANCO order by id_banco;";
		//$ver = "SELECT     MAX(id_banco) AS ID  FROM BANCO;";
		$resulta = $conexion->consulta($ver);
		$e = pg_num_rows($resulta);
		echo $e;
		if($e == "")
		{
			$e++;		
			
		}else
		{
			$e++;
		}
		
		$sql="INSERT INTO BANCO (id_banco, nombre, sucursal, direccion, codigo_area, telefono, fax, persona_contacto, cargo_contacto, email_contacto, pagina_banco, estatus,ultimo_usuario) VALUES (".$e.",'".$banco."', '".$sucursal."','".$direccion."',".$cod_area.",'".$telefono."','".$fax."','". $persona_contacto."','". $cargo_contacto."','".$email ."','". $pag_web ."',".$estatu.",1);";	
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