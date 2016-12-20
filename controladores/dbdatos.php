<?
interface DbBaseInterface {
	public function connect($dbhost='', $dbuser='', $dbpass='', $dbname='');
	public function query($sql);
	public function fetch_array($resultQuery='', $opt='');
	public function close();	
	public function num_rows($resultQuery='');
	public function field_name($number, $resultQuery='');
	public function data_seek($number, $resultQuery='');
	public function affected_rows($sql='');
	public function error($err='');
	public function no_error();
	public function table_exists($table);
}
class dbDatos
{
	public function __construct()
	{
		$this->conectar();
	}
	
	public static function load_driver(){
		
		$config = Config::read();	
		if(isset($config->database->type)){
			try {				
				if($config->database->type){					
					$config->database->type = escapeshellcmd($config->database->type);
					require /*"drive/".*/$config->database->type.".php";
					return true;					
				}
			} 
			catch(kexception $e){
				$e->show_message();
			}
		} else {
			return true;
		}
	}
	
		
	public function conectar()
	{
		$config = Config::read();
		if(!$Id_Connection = pg_connect("host=localhost port=5432 dbname=sai_ochina user=postgres password=batusay"))
		{
			echo "Error de Coneccion".pg_last_notice($Id_Connection);
		}
		return $config->Id_Connection = $Id_Connection;
	}
	
	public function consulta($sql){
      if(!$config->Id_Connection=pg_query( $sql)){
	  
	  	echo $Id_Connection;
		}
        return $config->Id_Connection;
      }
	  
}	
		
		
	
?>