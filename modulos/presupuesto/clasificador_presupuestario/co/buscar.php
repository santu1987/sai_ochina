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
	 public function __construct($id_accion_especifica, $id_accion_central, $id_organismo, $denominacion, $comentarios, $ultimo_usuario, $fecha_modificacion)
	{
	  $this->campos[0]=$id_accion_especifica;
	  $this->campos[1]=$id_accion_central;
	  $this->campos[2]=$id_organismo;
	  $this->campos[3]=$denominacion;
	  $this->campos[4]=$comentarios;
	  $this->campos[5]=$ultimo_usuario;
	  $this->campos[6]=$fecha_modificacion;
    }
	public function get($campo){
      return $this->campos[$campo];
    }

    public function set($campo, $tipo){
      $this->campos[$campo]=$tipo;
    }
	public function buscarSelect(){  
	  $conexion = new dbdatos();
	  $sql="SELECT * FROM acciones_especificas;";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_accion_especifica,$id_accion_central, $id_organismo, $denominacion, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
						 $id_accion_especifica,
				         $id_accion_central, 
						 $id_organismo, 
						 $denominacion, 
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
	  $sql="SELECT * FROM acciones_especificas WHERE (".$busca.");";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_accion_especifica, $id_accion_central, $id_organismo, $denominacion, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				         $id_accion_especifica,
						 $id_accion_central, 
						 $id_organismo, 
						 $denominacion, 
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
	  $sql="SELECT * FROM acciones_especificas ORDER BY $sortname $sortorder OFFSET  $start LIMIT $rp;";
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_accion_especifica, $id_accion_central, $id_organismo, $denominacion, $comentarios, $ultimo_usuario, $fecha_modificacion)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				        $id_accion_especifica,
						$id_accion_central, 
						$id_organismo, 
						$denominacion, 
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
		$sql = "SELECT count(id_accion_especifica) FROM acciones_especificas;";
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