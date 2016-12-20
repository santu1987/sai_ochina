<?php
	//Definimos la ruta donde se suben los archivos
	define ("_DIR_SUBIDA_", "./upload/" );
	
	//Obtenemos el nombre del archivo a través de un parámetro enviado por post
	$archivo = $_POST['id_archivo'];
	
	//Cogemos el tamaño que hay escrito en el archivo *.tam
	$total = file_get_contents (_DIR_SUBIDA_.$archivo."tam");
	
	//Calculamos el tamaño que hay escrito
	$cargado = filesize (_DIR_SUBIDA_.$archivo);
	//Sacamos el procentaje
	$porcentaje = round($cargado / $total * 100);
	//Pintamos el porcentaje
	echo $porcentaje; 
?>
