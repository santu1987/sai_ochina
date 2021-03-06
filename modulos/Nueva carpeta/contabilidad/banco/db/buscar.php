<?
class Buscar extends ClaseBase
{
	protected $campos;
	 public function __construct($id_banco, $nombre, $sucursal, $direccion, $codigo_area, $telefono, $fax, $persona_contacto, $cargo_contacto, $email_contacto, $pagina_banco, $estatus, $ultimo_usuario)
	{
	  $this->campos[0]=$id_banco;
	  $this->campos[1]=$nombre;
	  $this->campos[2]=$sucursal;
	  $this->campos[3]=$direccion;
	  $this->campos[4]=$codigo_area;
	  $this->campos[5]=$telefono;
	  $this->campos[6]=$fax;
	  $this->campos[7]=$persona_contacto;
	  $this->campos[8]=$cargo_contacto;
	  $this->campos[9]=$email_contacto;
	  $this->campos[10]=$pagina_banco;
	  $this->campos[11]=$articulo;
	  $this->campos[12]=$estatus;
	  $this->campos[13]=$ultimo_usuario;
    }
	
	public function get($campo){
      return $this->campos[$campo];
    }
	    public function set($campo, $tipo){
      $this->campos[$campo]=$tipo;
    }
	
	public function select($tsql,$busca){  
	  $conexion = new dbdatos();
	  if ($tsql=="simple")
	  {
	  	$sql="SELECT * FROM banco;";
	  }elseif ($tsql=="parametro")
	  {
	  	$sql="SELECT * FROM banco WHERE (".$busca.");";
	  }
	   }elseif ($tsql=="contar")
	  {
	  	$sql = "SELECT count(id_banco) FROM banco;";
	  }
	  
	  
	  
	  
	  
	  if ($resultSet = $conexion->consulta($sql))
	  {
	  	if(pg_num_rows($resultSet))
		{
			while(list($id_banco, $nombre, $sucursal, $direccion, $codigo_area, $telefono, $fax, $persona_contacto, $cargo_contacto, $email_contacto, $pagina_banco, $estatus, $ultimo_usuario)=pg_fetch_row($resultSet))  
			{
            $tmp[]=new buscar(
				         $id_banco, 
						 $nombre, 
						 $sucursal, 
						 $direccion, 
						 $codigo_area, 
						 $telefono, 
						 $fax, 
						 $persona_contacto, 
						 $cargo_contacto, 
						 $email_contacto, 
						 $pagina_banco, 
						 $estatus, 
						 $ultimo_usuario
				       );
          }
          return $tmp;
		}else
	      return 0;
	  
	  }
	  echo $resultSet;
      return $conexion->consulta($sql);
    }
	
}
?>