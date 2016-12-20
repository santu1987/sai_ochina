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
$unidad = $_GET['unidad'];
$id = $_GET['id'];
$requisicion = $_GET['requisicion'];
//************************************************************************

$limit = 15;
if(!$sidx) $sidx =1;


$Sql="
SELECT 
	count(\"solicitud_cotizacionE\".id_solicitud_cotizacione)
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
WHERE
	\"solicitud_cotizacionE\".id_organismo = 1
AND
	\"solicitud_cotizacionE\".ano = '2009'
AND
	(\"solicitud_cotizacionE\".id_requisicion = 1)	
	AND (\"solicitud_cotizacionD\".secuencia = 1)
";
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query echo($Sql);
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} 
else {
	$total_pages = 0;
}

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

$Sql = "	
SELECT 
	\"solicitud_cotizacionE\".id_solicitud_cotizacione, 
	\"solicitud_cotizacionE\".numero_cotizacion, 
	\"solicitud_cotizacionE\".id_proveedor, 
	proveedor.nombre AS proveedor,
	\"solicitud_cotizacionE\".id_requisicion, 
	\"solicitud_cotizacionE\".titulo, 
	\"solicitud_cotizacionE\".tiempo_entrega, 
	\"solicitud_cotizacionE\".lugar_entrega, 
	\"solicitud_cotizacionE\".condiciones_pago, 
	\"solicitud_cotizacionE\".validez_oferta,
	\"solicitud_cotizacionE\".garantia,
	(SELECT SUM(monto*cantidad) 
	FROM \"solicitud_cotizacionD\"
	WHERE \"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
	AND (\"solicitud_cotizacionD\".secuencia = 1))AS monto
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
WHERE
	\"solicitud_cotizacionE\".id_organismo = 1
AND
	\"solicitud_cotizacionE\".ano = '2009'
AND
	(\"solicitud_cotizacionE\".id_requisicion = 1)	
	AND (\"solicitud_cotizacionD\".secuencia = 1)
ORDER BY
	id_solicitud_cotizacione
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º

$Sqll = "	
SELECT 
	\"solicitud_cotizacionE\".id_solicitud_cotizacione, 
	\"solicitud_cotizacionE\".numero_cotizacion, 
	\"solicitud_cotizacionE\".id_proveedor, 
	proveedor.nombre AS proveedor,
	\"solicitud_cotizacionE\".id_requisicion, 
	\"solicitud_cotizacionE\".titulo, 
	\"solicitud_cotizacionE\".tiempo_entrega, 
	\"solicitud_cotizacionE\".lugar_entrega, 
	\"solicitud_cotizacionE\".condiciones_pago, 
	\"solicitud_cotizacionE\".validez_oferta,
	\"solicitud_cotizacionE\".garantia,
	(SELECT (monto*cantidad) 
	FROM \"solicitud_cotizacionD\"
	WHERE \"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
	AND (\"solicitud_cotizacionD\".secuencia = 1))AS monto
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	proveedor
ON
	proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
WHERE
	\"solicitud_cotizacionE\".id_organismo = 1
AND
	\"solicitud_cotizacionE\".ano = '2009'
AND
	(\"solicitud_cotizacionE\".id_requisicion = 1)	
AND
	(\"solicitud_cotizacionD\".secuencia = 1)
ORDER BY
	id_solicitud_cotizacione
			";
//echo $Sql;
$rowe=& $conn->Execute($Sqll);
$j=0;
while(!$rowe->EOF) { 
$j++;
	$precio[$j] = $rowe->fields("monto");
	$numero_cotizacion[$j] = $rowe->fields("numero_cotizacion");
	$conpago[$j] = $rowe->fields("condiciones_pago") ;
	$garantia[$j] = $rowe->fields("garantia") ;
	$valoferta[$j] = $rowe->fields("validez_oferta") ;
	$tiem_entraga[$j] = $rowe->fields("tiempo_entrega") ;
	$lugar_entrega[$j] = $rowe->fields("lugar_entrega") ;
	$rowe->MoveNext();
}
$m= 0;

$result_precio = count($precio);
$jj=1;
$gg ="";
for($t=1;$t<=$result_precio;$t++){
//echo('eso'.$t.'<br>');
$o=0;
$punto_precio[$t]=6;
	for($w=1;$w<=$result_precio;$w++){
		if($precio[$t]<=$precio[$w]){
		//$punto_precio[$t]=6;
			//$punto_total[$t]=$punto_total[$t]+$punto_precio[$t];
			/*echo($precio[$t].'&nbsp;'.$precio[$w].'<br>');
			echo('presio '.$t.'= '.$w.'<br>');*/
		}else{
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_precio[$t]=6-$o;
			//echo($precio[$t].'&nbsp;'.$precio[$w].'<br>');
			//echo($punto_precio[$t].'<br>');
			
		}
		
		
	}
	$punto_total[$t]=$punto_total[$t]+$punto_precio[$t];
	//echo $punto_total[$t]."<br>";
}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($t=1;$t<=$result_precio;$t++){
//echo('eso'.$t.'<br>');
$o=0;
$punto_tiem_entraga[$t]=2;
	for($w=1;$w<=$result_precio;$w++){
		if($tiem_entraga[$t]<=$tiem_entraga[$w]){
			//echo($tiem_entraga[$t].'&nbsp;'.$tiem_entraga[$w].'<br>');
			//echo('presio '.$t.'= '.$w.'<br>');
		}else{
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_tiem_entraga[$t]=2-$o;
			if($punto_tiem_entraga[$t] <=0)
			{
				$punto_tiem_entraga[$t] = 1;
			}
			//echo($tiem_entraga[$t].'&nbsp;'.$tiem_entraga[$w].'<br>');
			//echo($punto_tiem_entraga[$t].'<br>');
			
		}
	}
		$punto_total[$t]=$punto_total[$t]+$punto_tiem_entraga[$t];
}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>');
$o=0;
$punto_conpago[$cp]=6;
	for($w=1;$w<=$result_precio;$w++){
		if($conpago[$cp]<=$conpago[$w]){
			//echo($conpago[$cp].'&nbsp;'.$conpago[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_conpago[$cp]=6-$o;
			if($punto_conpago[$cp] <=0)
			{
				$punto_conpago[$cp] = 1;
			}
			//echo('puntos '.$punto_conpago[$cp].'<br>');
		}
	}
	$punto_total[$cp]=$punto_total[$cp]+$punto_conpago[$cp];

}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>'); 
$o=0;
$punto_valoferta[$cp]=3;
	for($w=1;$w<=$result_precio;$w++){
		if($valoferta[$cp]<=$valoferta[$w]){
			//echo($valoferta[$cp].'&nbsp;'.$valoferta[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			if($valoferta[$cp]!=$valoferta[$w])
				$o++;
			//echo 'entro '.$o.'<br>';
			$punto_valoferta[$cp]=3-$o;
			if($punto_valoferta[$cp] <=0)
			{
				$punto_valoferta[$cp] = 1;
			}
			//echo('puntos '.$punto_valoferta[$cp].'<br>');
		}
	}
			$punto_total[$cp]=$punto_total[$cp]+$punto_valoferta[$cp];
}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>'); 
$o=0;
$punto_garantia[$cp]=4;
	for($w=1;$w<=$result_precio;$w++){
		if($garantia[$cp]<=$garantia[$w]){
			//echo($valoferta[$cp].'&nbsp;'.$valoferta[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			if($garantia[$cp]!=$garantia[$w])
				$o++;
			//echo 'entro '.$o.'<br>';
			$punto_garantia[$cp]=4-$o;
			if($punto_garantia[$cp] <=0)
			{
				$punto_garantia[$cp] = 1;
			}
			//echo('puntos '.$punto_valoferta[$cp].'<br>');
		}
	}
			$punto_total[$cp]=$punto_total[$cp]+$punto_garantia[$cp];
}
for($inicio=1;$inicio<=$result_precio;$inicio++){

	$prove[$inicio]=$row->fields("id_solicitud_cotizacione")."*".$row->fields("proveedor")."*".$punto_precio[$inicio]."*".$punto_conpago[$inicio]."*".$punto_garantia[$inicio]."*".$punto_valoferta[$inicio]."*".$punto_tiem_entraga[$inicio]."*".$punto_lugar_entrega[$inicio]."".$punto_total[$inicio];
	//echo $prove[$inicio].'<br>';
	$row->MoveNext();
}
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
///////////////////////////////////////////////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\º
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

//$i=0;
	$result_prove = count($prove);
	$x=1;
while ($x <= $count){
//echo $count ." ".$x."<br>";	
	
	//	$prove_line = split("*",$prove[$x]);
		//list($proveedor, $precio, $pago, $garantia, $oferta, $tiempo) = split('*', $prove[$x]);
		list($id, $proveedor, $precio, $pago, $garantia, $oferta, $tiempo,$total) = explode ( "*", $prove[$x]);


	$responce->rows[$i]['id']=$id;

	$responce->rows[$i]['cell']=array(	
															
															$id,
															$proveedor,
															number_format($precio,2,',','.'),
															$pago,
															$garantia,
															$oferta,
															$tiempo,
															$total
														);
	$x++;
}
// return the formated data
echo $json->encode($responce);

?>