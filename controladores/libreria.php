<?php
//if(!eregi("Firefox", $_SERVER['HTTP_USER_AGENT'])) die ("Solo Para Ser Visualizada en Mozilla Firefox.");
/*********************************************************************************/
//																	CONEXION A BD					
$link_mysql = conectar_mysql("bd_intranet");
/*********************************************************************************/
/*********************************************************************************/
//												MODULOS DE ACCESO A LA INTRANET
require_once("acceso.php");
/*********************************************************************************/
/*********************************************************************************/
function crear_miniatura($archivo_original,$width,$height)
{
	$original = imagecreatefromjpeg($archivo_original);
	$thumb = imagecreatetruecolor($width,$height); 
		
	$ancho = imagesx($original);
	$alto = imagesy($original);
		
	imagecopyresampled($thumb,$original,0,0,0,0,$width,$height,$ancho,$alto);
		
	imagejpeg($thumb,$archivo_original."min.jpg",90);
};
/*********************************************************************************/
/*********************************************************************************/
function cambiar_formato($cadena,$formato)
{
	if ($formato=='bs_to_float')
	{
		$cadena=str_replace(',','.',str_replace('.','',$cadena));
	}
	return $cadena;
};
/*********************************************************************************/
/*********************************************************************************/
function resumen_error_sql($Sql,$descripcion)
{
	$resumen="
						<br />
							<b>Fecha:</b>							".date("Y-m-d H:i:s")."
							<br />
							<b>Pagina:</b> 							$_SERVER[PHP_SELF]
							<br />
							<b>Ultima Sentencia SQL:</b> 	$Sql
							<br />
							<b>Descripción del Error:</b> 	$descripcion
						";
	return $resumen;
}
/*********************************************************************************/
//************************************************************************
function mostrar_variables($tipo='')
{	
	if ($tipo)
	{
		$arr_tipo=explode(',',$tipo);
		foreach ($arr_tipo as $item_tipo) 
		{
			eval("echo '<b>$item_tipo</b><br />';");
			eval("foreach ($item_tipo as \$clave => \$valor) echo \"\$clave = \$valor<br />\";");
		}
	}
	if (!$tipo)
	{
		echo "<b>POST</b><br />";
		foreach ($_POST as $clave => $valor) echo "$clave = $valor<br />";
		echo "<b>GET</b><br />";
		foreach ($_GET as $clave => $valor) echo "$clave = $valor<br />";
		echo "<b>SESSION</b><br />";
		foreach ($_SESSION as $clave => $valor) echo "$clave = $valor<br />";
		echo "<b>FILES</b><br />";
		foreach ($_FILES as $clave => $valor) echo "$clave = $valor<br />";		
		echo "<b>SERVER</b><br />";
		foreach ($_SERVER as $clave => $valor) echo "$clave = $valor<br />";				
	}
}
//************************************************************************
/*********************************************************************************/
//					FUNCION PARA ACTUALIZAR EL REGISTRO DE ACTIVIDAD DE LA SESSION
function usuarios_activos()
{
	$link_mysql = conectar_mysql("bd_intranet");
	//asignamos un nombre memotecnico a la variable
	$ip 			=	$_SERVER['REMOTE_ADDR'];
	//definimos el momento actual
	$ahora 	= 	time();
	//actualizamos la tabla
	//borrando los registros de las ip inactivas (10 minutos)
	$limite 	=	$ahora-15*60;
	$Sql		=	"DELETE FROM usuario_autentificado WHERE tiempo_actividad < $limite";
	mysql_query($Sql,$link_mysql);

	//miramos si el ip del visitante existe en nuestra tabla
	$Sql 									=	"SELECT ip_autentificacion, fecha_actividad FROM usuario_autentificado WHERE ip_autentificacion = '$ip'";
	$result_usuario_activo 	=	mysql_query($Sql,$link_mysql);
	
	//si existe actualizamos el campo fecha
	if (mysql_num_rows($result_usuario_activo) != 0) 
	{
		$Sql = "UPDATE usuario_autentificado SET fecha_actividad='".date("Y-m-d H:i:s")."' , tiempo_actividad = '$ahora' , fecha_autentificacion=fecha_autentificacion  WHERE ip_autentificacion = '$ip'";
	}
	//si no existe insertamos el registro correspondiente a la nueva sesion	
	else 
	{
		/*********************************************************************************/
		//													CERRANDO LA SESSION
		if ($_SESSION AND !$_SESSION[acceso_root])
		{
			//eliminando la cookie del cliente relacionada a la session
			$parametros_cookies = session_get_cookie_params();
			setcookie(session_name(),0,1,$parametros_cookies["path"]);
			//cambiamos la direcion de los archivos de sessiones
			//session_save_path($save_path);
			//iniciamos session
			//session_start();
			//destruimos la session
			session_destroy();			
			header("location: msg_session_expiro.php");
			die();
		}
		/*********************************************************************************/
		return;
	}
	//ejecutamos la sentencia sql
	mysql_query($Sql,$link_mysql);
}
/*********************************************************************************/
/*********************************************************************************/
function string_php_to_url($cadena_original)
{
	$cadena_final	=	urlencode($cadena_original);
	$cadena_final	=	str_replace(array('%0D','%0A','%09'),'',$cadena_final);
	$cadena_final	=	urldecode ($cadena_final);
	return $cadena_final;
}
/*********************************************************************************/
/*********************************************************************************/
function redimencionar_imagen($anchura,$hmax,$archivo)
{
	$nombre		=	$archivo;
	$datos 		=	getimagesize($nombre);

	if($datos[2]==1)	$img = @imagecreatefromgif($nombre);
	if($datos[2]==2)	$img = @imagecreatefromjpeg($nombre);
	if($datos[2]==3)	$img = @imagecreatefrompng($nombre);

	$ratio 			=	($datos[0] / $anchura);
	$altura 		=	($datos[1] / $ratio);
	
	if($altura>$hmax)
	{
		$anchura2	=	$hmax*$anchura/$altura;
		$altura			=	$hmax;
		$anchura		=	$anchura2;
	}
	
	$thumb 		=	imagecreatetruecolor($anchura,$altura);
	imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);
	
	if($datos[2]==1)	{imagegif($thumb,$archivo);}
	if($datos[2]==2)	{imagejpeg($thumb,$archivo);}
	if($datos[2]==3)	{imagepng($thumb,$archivo);}
	
	imagedestroy($thumb);
}
/*********************************************************************************/
/*********************************************************************************/
//											FUNCION RESUMEN NOTICIA
//utilizo esta funcion para cortar el contenido de las noticias que muestro en el index
function resumen($cadena,$top,$enlace='',$forzar=false)
{
	/*
	si viene la orden de forzar y el largo de la cadena es mayor al TOP
	cortamos la cadena, y le concatenamos su enlace si lo trae
	*/
	if ($forzar && strlen($cadena)>$top)
	{
		return substr($cadena,0,$top).(($enlace)?" <a href='$enlace'>[...]</a>":" [...]");
	}
	
	for ($i=0; $i<strlen($cadena);$i++)
	{
		$cad=$cad.$cadena[$i];
		$final_cadena=(($enlace)?" <a href='$enlace'>[...]</a>":" [...]");
		/*
		si el contenido de la cadena temporal es mayor al tope selecionado y 
		el caracter actual es un espacio en blanco devuelvo el contenido de la variable
		*/
		if ($i>$top and $cadena[$i]==' ') return $cad.$final_cadena; 
	}
	return $cad; 
}
/*********************************************************************************/
/*********************************************************************************/
//							FUNCION PARA CONECTARSE A UNA BASE DE DATOS MYSQL
function conectar_mysql($nombre_bd)
{
	//conectandose como osalazar a la base de datos
	if(!($link_mysql=mysql_connect("localhost","public_intranet","123456789xyzABC")))
	{
		//si al tratar de conectarno a la base de datos da error, muestro un mensaje
		die("Error conectando a la base de datos <b>$nombre_bd</b><br />".mysql_error($link_mysql));
	}
	if (!mysql_select_db($nombre_bd,$link_mysql))
	{
		//si al tratar de conectarno a la base de datos da error, muestro un mensaje
		die("Error conectando a la base de datos <b>$nombre_bd</b><br />".mysql_error($link_mysql));
	}
	//devuelvo el puntero de conecion de la base de datos
	return $link_mysql;
}
/*********************************************************************************/
/*********************************************************************************/
//				FUNCION PARA CAMBIAR LA FECHA NORMAL A TimeStamp
function cambiaf_a_timestamp($fecha_inicial,$devolver_hora='false')
{
	list($fecha, $hora) 			= 		explode(" ", $fecha_inicial);
	list($ano, $mes, $dia) 		=		explode("-", $fecha);
	list($h, $m, $s) 				= 		explode(":", $hora);
	if ($devolver_hora=='true')
	{
		return "$h:$m:00";
	}
	return mktime($h, $m, $s, $mes, $dia, $ano);
}
/*********************************************************************************/
/*********************************************************************************/
//				FUNCION PARA CAMBIAR LA FECHA NORMAL A TimeStamp
function timestamp_to_date($fecha_inicial,$devolver_hora='false')
{
	ereg( "([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})", $fecha_inicial, $array_fecha); 
	$fecha_final	=	"$array_fecha[3]/$array_fecha[2]/$array_fecha[1]";
	if ($devolver_hora=='true')
	{
		$hora =	"&nbsp;[$array_fecha[4]:$array_fecha[5]:$array_fecha[6]]";
	}
	$fecha_final	=	"$array_fecha[3]/$array_fecha[2]/$array_fecha[1]$hora";
	return $fecha_final;	
}
/*********************************************************************************/
/*********************************************************************************/
//				FUNCION PARA CAMBIAR LA FECHA NORMAL A FORMATO POSTGRES
function cambiaf_a_postgres($fecha,$hora='00:00:00')
{
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha="$mifecha[3]-$mifecha[2]-$mifecha[1]"; 
	$lafecha="$lafecha $hora";
    return $lafecha; 	
}
/*********************************************************************************/
/*********************************************************************************/
//												FUNCION PARA CALCULAR EDAD
function Edad($fecha_nacimiento)
{
	list($d,$m,$y)	=		explode("/",$fecha_nacimiento);
	
	$hoy					=		mktime(0,0,0,date("d"),date("m"),date("Y"));
	$cumple			=		mktime(0,0,0,"$d","$m","$y");
	$age				=		intval(($hoy-$cumple)/(60*60*24*365));
	return $age+1;
}
/*********************************************************************************/
/*********************************************************************************/
//				FUNCION PARA CAMBIAR LA FECHA MYSQL A FORMATO NORMAL
function cambiaf_a_normal($fecha,$devolver_formato='')
{ 
    ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	if ($devolver_formato)
	{
		$devolver_formato	=	str_replace('%d',$mifecha[3],$devolver_formato);
		$devolver_formato	=	str_replace('%m',$mifecha[2],$devolver_formato);
		$devolver_formato	=	str_replace('%a',$mifecha[1],$devolver_formato);
		return $devolver_formato;
	}
    return $lafecha; 
} 
/*********************************************************************************/
/*********************************************************************************/
//				FUNCION PARA CAMBIAR LA FECHA NORMAL A FORMATO MYSQL
function cambiaf_a_mysql($fecha)
{ 
    ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha); 
    $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
    return $lafecha; 
}
/*********************************************************************************/
/*********************************************************************************/
//											FUNCION PARA CONVERTIR A MB O KB
function to_MB($tamano)
{
	
	$size = $tamano/1024; 
	$ext="Kb";
	if ($size>1024)
	{
		$size = $size/1024; 
		$ext="Mb";
	}
	$pos = strpos($size,".");
	if ($pos) $size = intval($size).substr($size,$pos,3);
	return $size." ".$ext;
}
/*********************************************************************************/
/*********************************************************************************/
//					FUNCION PARA VALIDAR REGISTROS RELACIONADOS
function verificar_relacion($tablas_campos_relacion,$relacion)
{
	$link_mysql = conectar_mysql("bd_intranet");
	$tabla_campo_relacion	=	explode('|',$tablas_campos_relacion);
	foreach($tabla_campo_relacion as $value)
	{
		list($tabla,$campo)=explode(':',$value);
		$Sql	=	"SELECT * FROM $tabla WHERE $campo=$relacion LIMIT 1";
		//envio el query de busqueda del registro
		$result=mysql_query($Sql,$link_mysql);
		//si hay un error en la sentencia SQL mata el proceso
		if (mysql_error($link_mysql)) die( resumen_error_sql($Sql, mysql_error( $link_mysql) ) );
		if (mysql_num_rows($result)) return true;
	}		
}
/*********************************************************************************/

/*********************************************************************************/
//													OBTENER EL IP REAL DEL CLIENTE
function getRealIP()
{
   
   if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
   {
      $client_ip =
         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
            $_SERVER['REMOTE_ADDR']
            :
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
               $_ENV['REMOTE_ADDR']
               :
               "unknown" );
   
      // los proxys van añadiendo al final de esta cabecera
      // las direcciones ip que van "ocultando". Para localizar la ip real
      // del usuario se comienza a mirar por el principio hasta encontrar
      // una dirección ip que no sea del rango privado. En caso de no
      // encontrarse ninguna se toma como valor el REMOTE_ADDR
   
      $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
   
      reset($entries);
      while (list(, $entry) = each($entries))
      {
         $entry = trim($entry);
         if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
         {
            // http://www.faqs.org/rfcs/rfc1918.html
            $private_ip = array(
                  '/^0\./',
                  '/^127\.0\.0\.1/',
                  '/^192\.168\..*/',
                  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                  '/^10\..*/');
   
            $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
   
            if ($client_ip != $found_ip)
            {
               $client_ip = $found_ip;
               break;
            }
         }
      }
   }
   else
   {
      $client_ip =
         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
            $_SERVER['REMOTE_ADDR']
            :
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
               $_ENV['REMOTE_ADDR']
               :
               "unknown" );
   }
   
   return $client_ip;
   
}
/*********************************************************************************/
?>