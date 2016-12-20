<?
class actualizar extends ClaseBase
{
	public function actualizar($id, $cargo, $statu)
		{ 
			$conexion = new dbdatos();
			
			$sql="UPDATE    BANCO  SET  NOMBRE = N'".$cargo."', estatus = ".$statu." WHERE     (id_banco = ".$id.");";
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