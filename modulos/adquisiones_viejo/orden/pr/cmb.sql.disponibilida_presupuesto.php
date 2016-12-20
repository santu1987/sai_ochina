<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
$id = $_GET['id'];

$id = split( ",", $id );
$cid = count($id);
$i=0;
while ($i < $cid) 
{
	$sqll="
	SELECT 
		id_solicitud_cotizacion,
		requisicion_encabezado.id_unidad_ejecutora,
		requisicion_encabezado.id_proyecto,
		requisicion_encabezado.id_accion_centralizada,
		requisicion_encabezado.id_accion_especifica,
		secuencia,
		cantidad,
		monto,
		impuesto,
		partida,
		generica,
		especifica,
		subespecifica
	
	FROM 
		\"solicitud_cotizacionD\" 
	INNER JOIN
		\"solicitud_cotizacionE\"
	ON
		\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_encabezado.id_requisicion_encabezado = \"solicitud_cotizacionE\".id_requisicion
	WHERE 
		(\"solicitud_cotizacionD\".id_organismo= $_SESSION[id_organismo])
	AND
		(id_solicitud_cotizacion = ".$id[$i].")	
	";
	$rowi=& $conn->Execute($sqll);
	if (!$rowi->EOF)
		$arrai = $rowi->fields("id_unidad_ejecutora")."*".$rowi->fields("id_proyecto")."*".$rowi->fields("id_accion_centralizada")."*".$rowi->fields("id_accion_especifica")."*".$rowi->fields("partida")."*".$rowi->fields("generica")."*".$rowi->fields("especifica")."*".$rowi->fields("subespecifica")."*".$rowi->fields("monto")."*".$rowi->fields("cantidad")."*".$rowi->fields("secuencia");
	if ($arrais == "")
		$arrais = $arrai;
	else
		$arrais = $arrais."&".$arrai;
	$i++;
}
$arrais = split( "&", $arrais );

sort($arrais);
reset($arrais); 
$carrais = count($arrais);
$e=0;$articulo = 0;
$mes= date('n');
$monto_actual = 0;
$mes= date('n');
$i = 0;
$desde =1;
while($desde<=$mes){
	if ($i == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
		$monto_precomprometido = " monto_precomprometido [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$monto_precomprometido = $monto_precomprometido." + monto_precomprometido [".$desde."]";
	}
	$desde++;
	$i++;
}
while ($e < $carrais) 
{ 
//$x = $arrais[$e];
list($id_unidad,$id_proyecto, $id_accion_centralizada, $id_accion_especifica , $partida, $generica, $especifica, $subespecifica, $monto, $cantidad, $secuencia) = explode ( "*", $arrais[$e]);
   $sqlcom =  "
	SELECT 
		($monoto)AS monto_presupuesto, 
		($traspasado)AS monto_traspasado,
		($modificado)AS monto_modificado,
		($monto_precomprometido)AS monto_precomprometido
	FROM 
		\"presupuesto_ejecutadoR\"
	WHERE
		id_unidad_ejecutora=$id_unidad 
	AND
		id_proyecto=$id_proyecto 
	AND 
		id_accion_centralizada = $id_accion_centralizada
	AND 
		id_accion_especifica = $id_accion_especifica
	AND 
		ano = '".date("Y")."'
	AND 
		partida = '$partida'
	AND 
		generica = '$generica'
	AND 
		especifica = '$especifica'
	AND
		sub_especifica = '$subespecifica'
	";
	$row=& $conn->Execute($sqlcom);
//echo $arrais[$e]."<br>";
	$e++;
	if($partida_anterior <> $partida and $generica_anterior <> $generica and $especifica_anterior <> $especifica and $subespecifica_anterior <> $subespecifica){
		$partida_anterior = $partida;
		$generica_anterior = $generica; 
		$especifica_anterior = $especifica; 
		$subespecifica_anterior = $subespecifica;
		$monto_actual = 0;
	}
	$monto_presupuesto = $row->fields("monto_presupuesto") + $row->fields("monto_modificado") + $row->fields("monto_traspasado");
	$disponoble = $monto_presupuesto - $row->fields("monto_precomprometido");
	if ($disponoble <> 0){
		//echo 'aqui';
		$montos = $cantidad * $monto;
		//echo $montos;
		if ($disponoble >=  $monto ){
			$monto_actual = $monto_actual + $montos;
			//echo 'aqui 11 ';
		}
		if ($disponoble <  $monto_actual ){
			//echo 'aqui 10 ';
			$falta = $monto_actual -$disponoble ;
			$monto_actual = $monto_actual - $montos;
			if ($articulo == 0){
				$articulo = $secuencia/*.", ".$falta*/;
				//echo 'aqui 1 ';
			}else{
				$articulo = $articulo.", ".$secuencia/*.", ".$falta*/;
				//echo 'aqui 2 ';
			}
		}
	}else{
		if ($articulo == 0)
				$articulo = $secuencia;
			else
				$articulo = $articulo.", ".$secuencia;
	}		
//echo $secuencia."<br>";
//echo 'monto_presupuesto '.$monto_presupuesto."<br>";
//echo 'monto '.$monto."<br>";		
//echo 'disponoble '.$disponoble."<br>";		
//echo 'monto actual '.$monto_actual."<br>";
//echo 'articulo '.$articulo."<br>";
	
		
	/*if ($row->fields("monto_presupuesto") > $row->fields("monto_comprometido")){
		if ($row->fields("monto_comprometido") >=  $monto)
			$monto_actual = $monto_actual + $monto;
	}
	$monto_actual = $monto_actual + $monto;*/
	//$f++;
	//echo $sqlcom."<br><br>";
}
$carticulo = count(split( ",", $articulo ));
if($articulo == 0)
	$carticulo = 0;
	
 $mensaje = $carticulo.'*'.$articulo;
/* if ($carticulo <= 1)
	$mensaje = 'El articulo del renglon '.$articulo.' no tiene disponibilidad presupuestaria';
else
	$mensaje = 'Los articulos de los renglones '.$articulo.' no tienen disponibilidad presupuestaria';*/

echo $mensaje/* ."  ". $disponoble*/;


?>