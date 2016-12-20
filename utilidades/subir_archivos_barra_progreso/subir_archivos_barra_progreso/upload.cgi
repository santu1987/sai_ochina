#!/usr/bin/perl -W

#Definimos los módulos que vamos a utilizar
use CGI;
use CGI::Carp qw(fatalsToBrowser);
use Fcntl qw(:DEFAULT :flock);
use File::Temp qw/ tempfile tempdir /;
use LWP::UserAgent;
use HTTP::Request;

#definimos la url de regreso para cuando terminemos el proceso.
$url="http://codigo-fuente.com/fg/index.php";
#definimos el directorio del servidor donde queremos guardar el archivo
$tmp_dir="/var/www/vhosts/codigo-fuente.com/httpdocs/samples/subir_archivos_barra_progreso/upload/";
#definimos el tamaño máximo que permitimos para la subida del archivo en bytes
$max_upload = 500000; #Aproximadamente 0,5MB 

#Através de la url cogemos el valor del archivo
@qstring=split(/&/,$ENV{'QUERY_STRING'});
@p1 = split(/=/,$qstring[0]);
$archivo = $p1[1];

#Declaramos los nombres de los archivos
$|=1;
  $datos_archivo = "$tmp_dir/$archivo"; #Donde se guardará el archivo
  $tam_archivo = "$tmp_dir/$archivo"."tam"; #Donde se guardará el tamaño del archivo total
1;

$content_type = $ENV{'CONTENT_TYPE'};
$content_length = $ENV{'CONTENT_LENGTH'};
$bRead=0;
$|=1;

#Comprobamos si el tamaño del archivo es mayor que el permitido
if($content_length > $max_upload)
{
  
	close (STDIN);
	salir("S&oacute;lo se permite subir archivos no superior a $max_upload Bytes");
}
sub salir{
  $mes = shift;
	print "Content-type: text/html\n\n";
	print "<br>$mes<br>\n". $len;
	exit;
}
#Comprobamos si los archivos que vamos a crear existen, si es así los borramos
if (-e "$datos_archivo") {
	unlink("$datos_archivo");
}
if (-e "$tam_archivo") {
	unlink("$tam_archivo");
}

sysopen(FH, $tam_archivo, O_RDWR | O_CREAT)
	or die "No se puede abrir numfile: $!";

# autoflush FH
$ofh = select(FH); $| = 1; select ($ofh);
flock(FH, LOCK_EX)
	or die "No se puede escribir numfile: $!";
seek(FH, 0, 0)
	or die "No se puede volver a trás numfile : $!";
#Escribimos el tamaño total en el archivo 
print FH $content_length;	
close(FH);#Cerramos el flujo
	
sleep(1);

#Abrimos el archivo para escribir los datos y lo definimos como TMP
open(TMP,">","$datos_archivo") or &salir ("No se puede abrir el archivo de datos");

#Definimos el contador
my $i=0;
my $a=0;
$ofh = select(TMP); $| = 1; select ($ofh);
	
while (read (STDIN ,$LINE, 2048) && $bRead < $content_length )
{
 
      #Vamos calculando cuanto tenemos escrito
    	$bRead += length $LINE;
    	
    	select(undef, undef, undef,0.35);	# dormimos 0.35 segundos.
    	
    	$i++;
    	#Escribimos en el archivo
      print TMP $LINE;
    
    $a++;
  
}
#Cerramos el archivo
close (TMP);


#Eliminamos el archivo que informa el tamaño
if (-e "$tam_archivo") {
  unlink("$tam_archivo");
	
}

print "Content-type: text/html\n\n";
print "Archivo subido con &eacute;xito<br/>";
print "<a href='http://www.codigo-fuente.com/samples/subir_archivos_barra_progreso/descargar.php?id=".$archivo."'>Descargar archivo</a>"


