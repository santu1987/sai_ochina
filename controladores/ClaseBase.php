<?php
require_once 'dbdatos.php';
  class ClaseBase{
    protected $tabla;
	protected $campos;
    
	public function __construct(){
	  
    }

    public function get($campo){
      return $this->campos[$campo];
    }

    public function set($campo, $tipo){
      $this->campos[$campo]=$tipo;
    }

	public function ejecutar($sql){/**/
	  $conexion = new dbdatos();
	  return $conexion->consulta($sql);
	}
    

	public function __destruct(){
      unset($this->campos,$this->tabla);
    }
  }
?>