<?php
//foreach ($_POST as $k => $v) $_POST[$k]=utf8_decode($v);	
//foreach ($_GET as $k => $v) $_GET[$k]=utf8_decode($v);	

function dbconn($nombre_enlace)
{
	if (eregi('PGSQL',strtoupper($nombre_enlace))) 
	{
		return array(	
								"host"=>"localhost",
								"port"=>"5432",
								"dbname"=>"sai_ochina",
								"user"=>"postgres",
								"password"=>"batusay"
							);
	}
}
?>
