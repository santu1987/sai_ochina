<?php
echo('nada');
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
$id = $_GET['numero_precompromiso'];

$id =  $id;

$sqllx="
	SELECT 
		count(id_orden_compra_serviciod) as id
		
	
	FROM 
		\"orden_compra_servicioD\" 
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_encabezado.numero_requisicion = \"orden_compra_servicioE\".numero_requisicion
	WHERE 
		(\"orden_compra_servicioD\".id_organismo= $_SESSION[id_organismo])
	AND
		(numero_precompromiso = '".$id."')	
	";
	die($sqllx);
	$rowix=& $conn->Execute($sqllx);
$cid = $rowix->fields("id");
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
		subespecifica,
		descripcion
	
	FROM 
		\"orden_compra_servicioD\" 
	INNER JOIN
		\"orden_compra_servicioE\"
	ON
		\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso
	INNER JOIN
		requisicion_encabezado
	ON
		requisicion_encabezado.numero_requisicion = \"orden_compra_servicioE\".numero_requisicion
	WHERE 
		(\"orden_compra_servicioD\".id_organismo= $_SESSION[id_organismo])
	AND
		(numero_precompromiso = '".$id."')	
	";
	die($sqll);
	$rowi=& $conn->Execute($sqll);
	if (!$rowi->EOF)
		$arrai = $rowi->fields("id_orden_compra_serviciod")."*".$rowi->fields("id_unidad_ejecutora")."*".$rowi->fields("id_proyecto")."*".$rowi->fields("id_accion_centralizada")."*".$rowi->fields("id_accion_especifica")."*".$rowi->fields("partida")."*".$rowi->fields("generica")."*".$rowi->fields("especifica")."*".$rowi->fields("subespecifica")."*".$rowi->fields("monto")."*".$rowi->fields("cantidad")."*".$rowi->fields("secuencia")."*".$rowi->fields("impuesto")."*".$rowi->fields("descripcion");
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
		$monto_comprometido = " monto_comprometido [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
		$monto_comprometido = $monto_comprometido." + monto_comprometido [".$desde."]";
	}
	$desde++;
	$i++;
}
while ($e < $carrais) 
{ 
//$x = $arrais[$e];
list($id_orden, $id_unidad,$id_proyecto, $id_accion_centralizada, $id_accion_especifica , $partida, $generica, $especifica, $subespecifica, $monto, $cantidad, $secuencia, $iva, $descripcion) = explode ( "*", $arrais[$e]);
   $sqlcom =  "
	SELECT 
		((($monoto)+
		($traspasado)+
		($modificado))-
		($monto_comprometido))AS monto_comprometido
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
	//die($sqlcom);
//echo $arrais[$e]."<br>";
	$e++;
	if($partida_anterior <> $partida or $generica_anterior <> $generica or $especifica_anterior <> $especifica or $subespecifica_anterior <> $subespecifica){
		$partida_anterior = $partida;
		$generica_anterior = $generica; 
		$especifica_anterior = $especifica; 
		$subespecifica_anterior = $subespecifica;
		$monto_actual = 0;
	}
	//$monto_presupuesto = $row->fields("monto_presupuesto") + $row->fields("monto_modificado") + $row->fields("monto_traspasado");
	$disponoble =/* $monto_presupuesto - */$row->fields("monto_comprometido");
	if ($disponoble <> 0){
		//echo 'aqui';
		$ivas = (($cantidad * $monto)/100 )* $iva;
		$montos = $cantidad * $monto;
		$montos = $montos + $ivas;
		$monto_sin_iva = $cantidad * $monto;
		$monto_imprime = $montos;
		
		//$montos = $cantidad * $monto;
		//echo $montos;
		if ($disponoble >=  $montos ){
			$monto_actual = $monto_actual + $montos;
			//echo 'aqui 11 ';
		}
		if ($disponoble <  $monto_actual ){
			//echo 'aqui 10 ';
			$falta = $monto_actual -$disponoble ;
			$monto_actual = $monto_actual - $montos;
			if ($articulo == 0){
				$articulo = $secuencia/*.", ".$falta*/;
				$falta_total = $falta;
				//echo 'aqui 1 ';
			}else{
				$articulo = $articulo.", ".$secuencia/*.", ".$falta*/;
				$falta_total = $falta_total + $falta;
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
	if ($disponible < 0){
	//echo "Monto -->".($row->fields("monto")* $row->fields("cantidad"))." aqui -->".$disponible."<br>";
	//$disponible = ($disponible-1) +  $total_con_iva;
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/close.png' />";
	//	$dis_mensaje = "No tiene disponibilidad para este Reglon";
	//$dis_mensaje = $disponible;

	
}else{
	//$dis_mensaje = "Tiene&nbsp; disponibilidad";
	//$disponible = ($disponible-1);
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/bien.png' />";
	//echo "aqua -->".$disponible."<br>"; <img id="compromiso_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif" />
}
	$parti = $partida.'.'.$generica.'.'.$especifica.'.'.$subespecifica
	$responce->rows[$i]['id']=$id_orden;

	$responce->rows[$i]['cell']=array(	
										$id_orden,
										$secuencia,
										$descripcion,
										$cantidad,
										//$row->fields("nombre"),
										//number_format($row->fields("monto"),2,',','.'),
										number_format($monto_sin_iva,2,',','.'),
										number_format($ivas,2,',','.'),
										number_format($monto_imprime,2,',','.'),
										$parti,
										$dis_mensaje,
										number_format($disponible,2,',','.')
									);

}/*
$carticulo = count(split( ",", $articulo ));
if($articulo == 0)
	$carticulo = 0;
	
 $mensaje = $carticulo.'*'.$articulo.'*'.number_format($falta_total,2,',','.');
/* if ($carticulo <= 1)
	$mensaje = 'El articulo del renglon '.$articulo.' no tiene disponibilidad presupuestaria';
else
	$mensaje = 'Los articulos de los renglones '.$articulo.' no tienen disponibilidad presupuestaria';*/
/*
echo $mensaje ."  ". $falta;*/
echo $json->encode($responce);


?>