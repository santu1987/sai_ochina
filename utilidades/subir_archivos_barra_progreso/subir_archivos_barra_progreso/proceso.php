<?php
	//Definimos la ruta donde se suben los archivos
	define ("_DIR_SUBIDA_", "./upload/" );
	
	//Obtenemos el nombre del archivo a trav�s de un par�metro enviado por post
	$archivo = $_POST['id_archivo'];
	
	//Cogemos el tama�o que hay escrito en el archivo *.tam
	$total = file_get_contents (_DIR_SUBIDA_.$archivo."tam");
	
	//Calculamos el tama�o que hay escrito
	$cargado = filesize (_DIR_SUBIDA_.$archivo);
	//Sacamos el procentaje
	$porcentaje = round($cargado / $total * 100);
	//Pintamos el porcentaje
	echo $porcentaje; 
?>
