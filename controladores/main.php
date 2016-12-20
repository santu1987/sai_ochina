<?php


/**
 * Clase para la carga de Archivos .INI y de configuración
 *
 * Aplica el patrón Singleton que utiliza un array 
 * indexado por el nombre del archivo para evitar que 
 * un .ini de configuración sea leido más de una
 * vez en runtime con lo que aumentamos la velocidad.
 * 
 */
class Config {

	static private $instance = array();

	/**
	 * El constructor privado impide q la clase sea 
	 * instanciada y obliga a usar el metodo read
	 * para obtener la instancia del objeto
	 *
	 */
	private function __construct(){

	}

	/**
	 * Constructor de la Clase Config
	 *
	 * @return Config
	 */
	static public function read($file="config.ini"){

		if(isset(self::$instance[$file])){
			return self::$instance[$file];
		}

		$config = new Config();
		$file = escapeshellcmd($file);
		foreach(parse_ini_file(/*'configuracion/'.*/$file, true) as $conf => $value){
			$config->$conf = new stdClass();
			foreach($value as $cf => $val){				
				$config->$conf->$cf = $val;
			}
		}

		if($file=="config.ini"){
			if(!isset($config->project->mode)){
				if(!isset($config->project)){
					$config->project = new stdClass();
				}
				$config->project->mode = "production";
			}

			//Carga las variables db del modo indicado
			if(isset($config->{$config->project->mode})){
				foreach($config->{$config->project->mode} as $conf => $value){
					if(ereg("([a-z0-9A-Z]+)\.([a-z0-9A-Z]+)", $conf, $registers)){
						if(!isset($config->{$registers[1]})){
							$config->{$registers[1]} = new stdClass();
						}
						$config->{$registers[1]}->{$registers[2]} = $value;
					} else {
						$config->$conf = $value;
					}
				}
			}

			//Carga las variables de [project]
			if(isset($config->project)){
				foreach($config->project as $conf => $value){
					if(ereg("([a-z0-9A-Z]+)\.([a-z0-9A-Z]+)", $conf, $registers)){
						if(!isset($config->{$registers[1]})){
							$config->{$registers[1]} = new stdClass();
						}
						$config->{$registers[1]}->{$registers[2]} = $value;
					} else {
						$config->$conf = $value;
					}
				}
			}
		}

		self::$instance[$file] = $config;
		return $config;
	}

}

?>
