<?
	require_once '../../../../controladores/main.php';
	require_once '../../../../controladores/dbdatos.php';
	require_once '../../../../controladores/ClaseBase.php';

 if (dbdatos::load_driver())
  {
  	//echo 'fino ';
	if (db::conectar())
	{
  		//echo 'fino2';
		//db::consulta("select * from bancos;");
	}
	else
	{
		echo 'No se ha conectado con el servidor';
	}
  }

class Buscar extends ClaseBase
{
	protected $campos;
	 public function __construct($id_organismo, $organismo, $direccion1, $direccion2, $codigo_area, $telefono, $fax, $rif, $nit, $pagina_web, $email, $representante, $cedula_repre, $cargo_repre, $comentarios, $ultimo_usuario, $fecha_modificacion)
	{
	  $this->campos[0]=$id_organismo;
	  $this->campos[1]=$organismo;
	  $this->campos[2]=$direccion1;
	  $this->campos[3]=$direccion2;
	  $this->campos[4]=$codigo_area;
	  $this->campos[5]=$telefono;
	  $this->campos[6]=$fax;
	  $this->campos[7]=$rif;
	  $this->campos[8]=$nit;
	  $this->campos[9]=$pagina_web;
	  $this->campos[10]=$email;
	  $this->campos[11]=$representante;
	  $this->campos[12]=$cedula_repre;
	  $this->campos[13]=$cargo_repre;
	  $this->campos[14]=$comentarios;
	  $this->campos[15]=$ultimo_usuario;
	  $this->campos[16]=$fecha_modificacion;
    }
	public function get($campo){
      return $this->campos[$campo];
    }

    public function set($campo, $tipo){
      $this->campos[$campo]=$tipo;
    }
	public function buscarSelect(){  
	  $conexion = new dbdatos();
	  $sql="SELECT * FROM organismos;";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_organismo, $organismo, $direccion1, $direccion2, $codigo_area, $telefono, $fax, $rif, $nit, $pagina_web, $email, $representante, $cedula_repre, $cargo_repre, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				         $id_organismo, 
						 $organismo, 
						 $direccion1, 
						 $direccion2, 
						 $codigo_area, 
						 $telefono, 
						 $fax, 
						 $rif, 
						 $nit, 
						 $pagina_web, 
						 $email, 
						 $representante, 
						 $cedula_repre,
						 $cargo_repre, 
						 $comentarios, 
						 $ultimo_usuario, 
						 $fecha_modificacion
				       );
          }
          return $tmp;
		}else
	      return 0;
	  
	  }
	  echo $resultSet;
      return $conexion->consulta($sql);
    }
	
	public function buscarDonde($busca){  
	  $conexion = new dbdatos();
	  $sql="SELECT * FROM organismos WHERE (".$busca.");";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_organismo, $organismo, $direccion1, $direccion2, $codigo_area, $telefono, $fax, $rif, $nit, $pagina_web, $email, $representante, $cedula_repre, $cargo_repre, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				         $id_organismo, 
						 $organismo, 
						 $direccion1, 
						 $direccion2, 
						 $codigo_area, 
						 $telefono, 
						 $fax, 
						 $rif, 
						 $nit, 
						 $pagina_web, 
						 $email, 
						 $representante, 
						 $cedula_repre,
						 $cargo_repre, 
						 $comentarios, 
						 $ultimo_usuario, 
						 $fecha_modificacion
				       );
          }
          return $tmp;
		}else
	      return 0;
	  
	  }
	  echo $resultSet;
      return $conexion->consulta($sql);
    }
	
	public function paginas(){  
	  $conexion = new dbdatos();
	  $sql="SELECT * FROM organismos ORDER BY $sortname $sortorder OFFSET  $start LIMIT $rp;";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_organismo, $organismo, $direccion1, $direccion2, $codigo_area, $telefono, $fax, $rif, $nit, $pagina_web, $email, $representante, $cedula_repre, $cargo_repre, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				        $id_organismo, 
						 $organismo, 
						 $direccion1, 
						 $direccion2, 
						 $codigo_area, 
						 $telefono, 
						 $fax, 
						 $rif, 
						 $nit, 
						 $pagina_web, 
						 $email, 
						 $representante, 
						 $cedula_repre,
						 $cargo_repre, 
						 $comentarios, 
						 $ultimo_usuario, 
						 $fecha_modificacion
				       );
          }
          return $tmp;
		}else
	      return 0;
	  
	  }
	  echo $resultSet;
      return $conexion->consulta($sql);
    }
	
	public function buscacombo($tabla)
	{
		$conexion = new dbdatos();
	  $sql="SELECT * FROM $tabla ;";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id, $campo1, $campo2, $campo3, $campo4)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				        $id, 
						$campo1, 
						$campo2, 
						$campo3, 
						$campo4
				       );
          }
          return $tmp;
		}else
	      return 0;
	  
	  }
	  echo $resultSet;
      return $conexion->consulta($sql);
	}
	
	public function countRec() {
		$conexion = new dbdatos();
		$sql = "SELECT count(id_organismo) FROM organismos;";
		if ($resultSet = $conexion->consulta($sql))
		{
			while ($row = pg_fetch_array($resultSet)) 
			{
				return $row[0];
			}	
		}
	}
}
?>