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
		id_orden_compra_serviciod,
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
		\"orden_compra_servicioD\" 
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_pre_orden
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_encabezado.numero_requisicion = \"orden_compra_servicioE\".numero_requisicion
	WHERE 
		(\"orden_compra_servicioD\".id_organismo= $_SESSION[id_organismo])
	AND
		(id_orden_compra_serviciod = ".$id[$i].")	
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
while ($e < $carrais) 
{ 
//$x = $arrais[$e];
list($id_unidad,$id_proyecto, $id_accion_centralizada, $id_accion_especifica , $partida, $generica, $especifica, $subespecifica, $monto, $cantidad, $secuencia) = explode ( "*", $arrais[$e]);
   $sqlcom =  "
	SELECT 
		monto_presupuesto[$mes], 
		monto_comprometido[$mes],
		monto_modificado[$mes],
		monto_traspasado[$mes]
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
		ano = '".date('Y')."'
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
	$disponoble = $monto_presupuesto - $row->fields("monto_comprometido");
	if ($disponoble <> 0){
		$monto = $cantidad*$monto;
		if ($disponoble >  $monto )
			$monto_actual = $monto_actual + $monto;
		if ($disponoble <  $monto_actual ){
			$falta = $monto_actual -$disponoble ;
			$monto_actual = $monto_actual - $monto;
			if ($articulo == 0)
				$articulo = $secuencia;
			else
				$articulo = $articulo.", ".$secuencia/*."* ".$falta*/;
		}
	}else{
		if ($articulo == 0)
				$articulo = $secuencia;
			else
				$articulo = $articulo.", ".$secuencia/*."* ".$falta*/;
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

echo $mensaje;


?>