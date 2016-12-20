<?php
//Indicamos el directorio donde están los archivos que se van a descargar
define("_DIR_","./upload");
$id=_DIR_."/".$_GET['id'];

//Comprobamos que exista el archivo
if(file_exists($id))
{
  //Obtenemos su handle 	
  $file = file($id);
  $file2 = implode("", $file);
  
  //Preparamos la cabecera, indicando que va ser una descarga
  header("Content-Type: application/octet-stream");
  header("Content-Disposition: attachment; filename=".$_GET['id']."\r\n\r\n");
  header("Content-Length: ".strlen($file2)."\n\n");
  //Abrimos el archivo
  $file_handle = fopen($id, "r");
  $total=0;
  $cont=0;
  
  //Contamos cuantas líneas tiene el archivo
  //Contamos las lineas
  while (!feof($file_handle)) {
   $line = fgets($file_handle);
   $total++;
  }
  //Posicionamos el puntero al principio del archivo
  rewind($file_handle);
  //Volvemos a recorrer el archivo
  while (!feof($file_handle)) {
     $line = fgets($file_handle);
     
     if($l_m<4)
     {
        $l_m++;
        continue;
     }
     //Empezamos a escribir a la 4 linea, ya que estas primeras líneas no nos interesa.
     if($cont<$total)//La última línea no la escribimos
     {
     	//Escribimos
        echo $line;
     }
     $cont++;
  }
  //Cerramos el archivo
  fclose($file_handle);

}else{
  die("El archivo no existe");
}
?>