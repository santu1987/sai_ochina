<?
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
$examp = $_GET["q"];
$id = $_GET['id'];
$requi = $_GET['requi'];
$secuencia = $_GET['secuencia'];
$limit = 15;
if(!$sidx) $sidx =1;

switch ($examp) {
    case 1:
	
		$sql_countar="
			SELECT 
				count(id_solicitud_cotizacione)
		FROM 
			\"solicitud_cotizacionE\"
		INNER JOIN
			proveedor
		ON
			proveedor.id_proveedor = \"solicitud_cotizacionE\".id_proveedor
		WHERE
			\"solicitud_cotizacionE\".id_organismo = 1
		AND
			\"solicitud_cotizacionE\".ano = '2009'
		AND
			(\"solicitud_cotizacionE\".id_requisicion = $id )	
		";
		
		$row_countar=& $conn->Execute($sql_countar);	
		$nro_proveedores = $row_countar->fields("count");
		
		$count = $row_countar->fields("count");

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		 if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		 if ($start<0) $start = 0;
			
			
	///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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
	AND (\"solicitud_cotizacionD\".secuencia = $secuencia))AS monto
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
	(\"solicitud_cotizacionE\".id_requisicion = $id)	
	AND (\"solicitud_cotizacionD\".secuencia = $secuencia)
ORDER BY
	id_solicitud_cotizacione
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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
	AND (\"solicitud_cotizacionD\".secuencia = $secuencia))AS monto
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
	(\"solicitud_cotizacionE\".id_requisicion = $id)	
AND
	(\"solicitud_cotizacionD\".secuencia = $secuencia)
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
	/*$p = array(
		array($precio => $rowe->fields("monto")),
		array($numero_cotizacion => $rowe->fields("numero_cotizacion"))
	);*/
	//echo($precio[$j]." <br>" );
	$conpago[$j] = $rowe->fields("condiciones_pago") ;
	$garantia[$j] = $rowe->fields("garantia") ;
	$valoferta[$j] = $rowe->fields("validez_oferta") ;
	$tiem_entraga[$j] = $rowe->fields("tiempo_entrega") ;
	$lugar_entrega[$j] = $rowe->fields("lugar_entrega") ;
	$rowe->MoveNext();
}
$m= 0;
//////////////////////////////////////////////////////////////////
$sql_co="
	SELECT 
		id_parametro_analisis_cotizacion, 
		id_organismo, 
		aspecto, 
		peso
	FROM 
		parametro_analisis_cotizacion
	ORDER BY
		id_parametro_analisis_cotizacion";
$row_otro=& $conn->Execute($sql_co);

/////////////////////////////////////////////////////////////////////
$result_precio = count($precio);
$jj=1;
$gg ="";
for($t=1;$t<=$result_precio;$t++){
//echo('eso'.$t.'<br>');
$o=0;
$punto_precio[$t]=$row_otro->fields("peso");
	for($w=1;$w<=$result_precio;$w++){
		if($precio[$t]<=$precio[$w]){
		//$punto_precio[$t]=6;
			//$punto_total[$t]=$punto_total[$t]+$punto_precio[$t];
			/*echo($precio[$t].'&nbsp;'.$precio[$w].'<br>');
			echo('presio '.$t.'= '.$w.'<br>');*/
		}else{
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_precio[$t]=$row_otro->fields("peso")-$o;
			//echo($precio[$t].'&nbsp;'.$precio[$w].'<br>');
			//echo($punto_precio[$t].'<br>');
			
		}
		
		
	}
	$punto_total[$t]=$punto_total[$t]+$punto_precio[$t];
	//echo $punto_total[$t]."<br>";
}
$row_otro->MoveNext();
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>');
$o=0;
$punto_conpago[$cp]=$row_otro->fields("peso")+1;
	for($w=1;$w<=$result_precio;$w++){
		if($conpago[$cp]<=$conpago[$w]){
			//echo($conpago[$cp].'&nbsp;'.$conpago[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_conpago[$cp]=$row_otro->fields("peso")+1-$o;
			if($punto_conpago[$cp] <=0)
			{
				$punto_conpago[$cp] = 1;
			}
			//echo('puntos '.$punto_conpago[$cp].'<br>');
		}
	}
	$punto_total[$cp]=$punto_total[$cp]+$punto_conpago[$cp];

}

$row_otro->MoveNext();
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>'); 
$o=0;
$punto_garantia[$cp]=$row_otro->fields("peso");
	for($w=1;$w<=$result_precio;$w++){
		if($garantia[$cp]<=$garantia[$w]){
			//echo($valoferta[$cp].'&nbsp;'.$valoferta[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			if($garantia[$cp]!=$garantia[$w])
				$o++;
			//echo 'entro '.$o.'<br>';
			$punto_garantia[$cp]=$row_otro->fields("peso")-$o;
			if($punto_garantia[$cp] <=0)
			{
				$punto_garantia[$cp] = 1;
			}
			//echo('puntos '.$punto_valoferta[$cp].'<br>');
		}
	}
			$punto_total[$cp]=$punto_total[$cp]+$punto_garantia[$cp];
}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------
$row_otro->MoveNext();

for($cp=1;$cp<=$result_precio;$cp++){
//echo('eso'.$cp.'<br>'); 
$o=0;
$punto_valoferta[$cp]=$row_otro->fields("peso");
	for($w=1;$w<=$result_precio;$w++){
		if($valoferta[$cp]<=$valoferta[$w]){
			//echo($valoferta[$cp].'&nbsp;'.$valoferta[$w].'<br>');
			//echo('presio '.$cp.'= '.$w.'<br>');
			if($valoferta[$cp]!=$valoferta[$w])
				$o++;
			//echo 'entro '.$o.'<br>';
			$punto_valoferta[$cp]=$row_otro->fields("peso")-$o;
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
$row_otro->MoveNext();
for($t=1;$t<=$result_precio;$t++){
//echo('eso'.$t.'<br>');
$o=0;
$punto_tiem_entraga[$t]=$row_otro->fields("peso");
	for($w=1;$w<=$result_precio;$w++){
		if($tiem_entraga[$t]<=$tiem_entraga[$w]){
			//echo($tiem_entraga[$t].'&nbsp;'.$tiem_entraga[$w].'<br>');
			//echo('presio '.$t.'= '.$w.'<br>');
		}else{
			$o++;
			//echo 'entro '.$o.'<br>';
			$punto_tiem_entraga[$t]=$row_otro->fields("peso")-$o;
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

///////////////////////////////////////////////////////---------------------------------------------
for($inicio=1;$inicio<=$result_precio;$inicio++){

	$prove[$inicio]=$row->fields("id_solicitud_cotizacion")."*".$row->fields("proveedor")."*".$punto_precio[$inicio]."*".$punto_conpago[$inicio]."*".$punto_garantia[$inicio]."*".$punto_valoferta[$inicio]."*".$punto_tiem_entraga[$inicio]."*".$punto_lugar_entrega[$inicio]."".$punto_total[$inicio];
	//echo $prove[$inicio].'<br>';
	$row->MoveNext();
}
///-----------------------------------------------------------------------------------------------
///-----------------------------------------------------------------------------------------------


			
			
			
			
			
			
			
		$responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i=0;
		//while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$result_prove = count($prove);
		
		for($cp=1;$cp<=$result_precio;$cp++){
				for($w=1;$w<=$result_precio;$w++){
				list($id, $proveedor, $precio, $pago, $garantia, $oferta, $tiempo, $total) = explode ( "*", $prove[$cp]);
				list($id2, $proveedor2, $precio2, $pago2, $garantia2, $oferta2, $tiempo2, $total2) = explode ( "*", $prove[$w]);
					if($total>=$total2){
						$temp= $prove[$cp];
						$prove[$cp]= $prove[$w];
						$prove[$w]= $temp;
					}
				}
		}
	$x=1;
	//$x++;
	while ($x <= $result_prove){
			list($id, $proveedor, $precio, $pago, $garantia, $oferta, $tiempo, $total) = explode ( "*", $prove[$x]);

			$responce->rows[$i]['id']=$total;
            $responce->rows[$i]['cell']=array($id, $proveedor, $precio, $pago, $garantia, $oferta, $tiempo, $total);
            $i++;
			$x++;
		}        
        echo json_encode($responce);
           
        break;
}
?>