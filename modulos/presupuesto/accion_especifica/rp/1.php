<?
# Creamos el PDF con las nuevas funcionesn>
$g = pdf_new();
pdf_open_file($g);

pdf_begin_page($g, 595, 842);
$imagen1 = pdf_open_jpeg($g, "imagenes/foto/wfarinas.bmp");
$h=0.5;
$v=0.8;
pdf_save($g);
$x1 = pdf_get_value($g, "imagewidth", $imagen1);
$y1 = pdf_get_value($g, "imageheight", $imagen1);
pdf_scale($g,$h,$v);
pdf_place_image($g, $imagen1, ((595/$h-$x1)/2), (842/$v-$y1), 1.0);
pdf_close_image ($g,$imagen1);
pdf_restore($g);
pdf_save($g);
$imagen2 = pdf_open_gif($g, "imagenes/foto/sombra.png");
$ancho=150;
$alto=325;
$x1 = pdf_get_value($g, "imagewidth", $imagen2);
$y1 = pdf_get_value($g, "imageheight", $imagen2);
$h=$ancho/$x1;
$v=$alto/$y1;
pdf_scale($g,$h,$v);
pdf_place_image($g, $imagen2, ((595/$h-$x1)/2), 200, 1.0);

pdf_close_image ($g,$imagen2);
pdf_restore($g);
PDF_end_page($g);
pdf_close($g);
# Despu�s del pdf_close empezamos la lectura del buffer
$buffer = PDF_get_buffer($g);
/* Esta porci�n de c�digo es id�ntica a la del ejemplo anterior
con la �nica diferencia que ahora medimos la longitud de la cadena
buffer en vez de la longitud de un fichero como hac�amos all� */
$len = strlen($buffer);
Header("Content-type: application/pdf");
Header("Content-Length: $len");
Header("Content-Disposition: inline; filename=loquesea.pdf");
# Escribimos en el documento que se enviar� al cliente
# el contenido de la cadena buffer
echo $buffer;
/* liberamos la memoria que conten�a el fichero
con lo cual el documento solo aparecer� en el navegador del cliente
y en la cach� de este (con el nombre loquesea.pdf).
Si no queremos que se almacene en la cach� ser�a solo cuesti�n de
incluir las cabeceras no cach� del ejemplo anterior */
pdf_delete($g);



?>

