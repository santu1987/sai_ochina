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

$nro_requisicion = $_GET['nro_requisicion'];
$id_requisicion = $_GET['id_requisicion'];
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sql_requi = "
SELECT 
	count(secuencia)
FROM 
	requisicion_detalle
WHERE
	id_organismo = ".$_SESSION['id_organismo']."
AND
	numero_requision = '$nro_requisicion'
";
$row_requi=& $conn->Execute($sql_requi);
$se = 1;
//$row_requi->fields("count");
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
while($se <= $row_requi->fields("count")){
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
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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
	AND (\"solicitud_cotizacionD\".secuencia = $se))AS monto
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
	(\"solicitud_cotizacionE\".id_requisicion = $id_requisicion)	
	AND (\"solicitud_cotizacionD\".secuencia = $se)
ORDER BY
	id_solicitud_cotizacione
			";
//echo $Sql.'<br>';
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
	AND (\"solicitud_cotizacionD\".secuencia = $se))AS monto
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
	(\"solicitud_cotizacionE\".id_requisicion = $id_requisicion)	
AND
	(\"solicitud_cotizacionD\".secuencia = $se)
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

	$prove[$inicio]=$row->fields("proveedor")."*".$punto_precio[$inicio]."*".$punto_conpago[$inicio]."*".$punto_garantia[$inicio]."*".$punto_valoferta[$inicio]."*".$punto_tiem_entraga[$inicio]."*".$punto_lugar_entrega[$inicio]."".$punto_total[$inicio];
	//echo $prove[$inicio].'<br>';
	$row->MoveNext();
}


/*
while($jj<=$result_precio){
	//echo($precio[$jj]." <br>" );
	while($yy<=$result_precio){
		if($precio[$jj] >= ($rowe->fields("monto") *  $rowe->fields("cantidad"))){
		echo $g_arreglo[$jj]." <br>";
			$g_arreglo[$jj] = 6;
		}elseif($precio[$jj] <= ($rowe->fields("monto") *  $rowe->fields("cantidad"))){
			$g_arreglo[$jj] = 5;
			echo $g_arreglo[$jj]." <br>";
		}
		$yy++;
	}
	
	
	$jj++;
}*/
/*for ($j = 1; $j <= $result_precio; $j++) {
	for ($i = $j; $i <= $result_precio; $i++) {
		echo $i;
	}
    echo $j." <br>";
}*/
/*function burbuja($A){
      $N = count($A)-1;
      for($i=1;$i < = $N;$i++)
         for($j=$N;$j>=$i;$j--)
            if($A[$j-1]>$A[$j]){
                  $aux = $A[$j-1];
                  $A[$j-1] = $A[$j];
                  $A[$j] = $aux;
               }
      return $A;
   }*/
//function bubble_sort($array){
 /*   $count = count($precio);
   // if ($count <= 0) return false;
    for($i=0; $i<=$count; $i++){
        for($jt=$count; $jt>=$i; $jt--){
		//echo $precio[$jt].' * '.$precio[$jt-1].' <br>';
            if ($precio[$jt] < $precio[$jt-1]){
                $tmp = $precio[$jt];
                $precio[$jt] = $precio[$jt-1];
                $precio[$jt-1] = $tmp;
             }
			 				echo $precio[$jt].' * '.$precio[$jt-1].' * '.$tmp.' <br>';

         }
     }*/
//    return $array;
//}
//bubble_sort($precio);
/*for($r=0;$r<=$result_precio;$r++){
	echo ($precio[$r]).'<br>';
}*/
/*
for ($j = 1 to $result_precio)
    FOR i = j TO 1 STEP -1
        IF a(i - 1) > a(i) THEN
            aux = a(i)
            a(i) = a(i - 1)
            a(i - 1) = aux
        END IF
    NEXT
NEXT*/


/*	if($gg == ""){
		$gg = $precio[$jj];
		echo $gg."Mayor <br>";
		$g_arreglo[$result_precio]=$precio[$jj];
	}elseif($gg < $precio[$jj]){
		$gg = $precio[$jj];
		echo $gg."Nuevo Mayor<br>";
		$g_arreglo[$result_precio]=$precio[$jj];
	}else{
		echo $precio[$jj]." menor<br>";
	}*/
/*	$jj++;
}*/
$xx=0;
//while($xx<=$g_arreglo){
	////echo($precio[$jj]." <br>" );
		//echo count($g_arreglo)." <br>" ;
	//$xx++;
//}
//echo($result_precio."<br>");
//echo($j);
/*$sql_contar="
	SELECT 
		count(id_parametro_analisis_cotizacion)
	FROM 
		parametro_analisis_cotizacion
	";

$row_contar=& $conn->Execute($sql_contar);	
$nro_registro = $row_contar->fields("count");*/
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
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
	(\"solicitud_cotizacionE\".id_requisicion = $id_requisicion)	
";

$row_countar=& $conn->Execute($sql_countar);	
$nro_proveedores = $row_countar->fields("count");
"
SELECT 
	\"solicitud_cotizacionD\".id_solicitud_cotizacion,
	\"solicitud_cotizacionD\".numero_requisicion,
	\"solicitud_cotizacionD\".secuencia,
	\"solicitud_cotizacionD\".descripcion,
	tiempo_entrega,
	lugar_entrega,
	condiciones_pago,
	validez_oferta,
	\"solicitud_cotizacionE\".garantia,
	(monto*cantidad) AS precio
FROM 
	\"solicitud_cotizacionE\"
INNER JOIN
	\"solicitud_cotizacionD\"
ON
	\"solicitud_cotizacionD\".numero_cotizacion = \"solicitud_cotizacionE\".numero_cotizacion
ORDER BY
	\"solicitud_cotizacionD\".numero_requisicion ASC,
	secuencia, precio ASC,
	condiciones_pago DESC, 
	garantia DESC,
	validez_oferta DESC,
	tiempo_entrega ASC,
	lugar_entrega DESC
";
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////////////////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
?>
<table border="1">
	<tr>
		<th  bgcolor="#0066CC" style="color: #FFFFFF" colspan="<?=$nro_proveedores+1; ?>">Listado de Proveedores</th>
	</tr>
	<tr>
		<th>Aspceto</th>
	<? $row->MoveFirst();
	 while(!$row->EOF) 
		{ 
	?>
		<th><?=$row->fields("proveedor")?></th>
	<? $row->MoveNext();
	 } ?>
	</tr>
	<? while(!$row_otro->EOF) 
		{ 
	?>
	<tr>
		<td><?=$row_otro->fields("aspecto")?></td>
		<?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 1)
		 while(!$row->EOF) 
		{ 
		?>
			<td align="right"><?=number_format($row->fields("monto"),2,',','.')?></td>
		<? $row->MoveNext();
		 } ?>
		 <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 2)
		 while(!$row->EOF) 
		{ 
		?>
			<td align="center"><?=$row->fields("condiciones_pago")?></td>
		<? $row->MoveNext();
		 } ?>
		  <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 3)
		 while(!$row->EOF) 
		{ 
		?>
			<td align="center"><?=$row->fields("garantia")?></td>
		<? $row->MoveNext();
		 } ?>
		 <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 4)
		 while(!$row->EOF) 
		{ 
		?>
			<td align="center"><?=$row->fields("validez_oferta")?></td>
		<? $row->MoveNext();
		 } ?>
		<?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 5)
		 while(!$row->EOF) 
		{ 
		?>
			<td align="center"><?=$row->fields("tiempo_entrega")?></td>
		<? $row->MoveNext();
		 } ?>
	</tr>
	<? $row_otro->MoveNext();
	 } ?>
</table>
<!--*********************************************************************************************** -->
<!--*********************************************************************************************** -->
<table border="1">
	<tr>
		<th  bgcolor="#0066CC" style="color: #FFFFFF" colspan="<?=$nro_proveedores+1; ?>">Puntuaciones de los Proveedores</th>
	</tr>
	<tr>
		<th>Aspceto</th>
	<? $row->MoveFirst();
	while(!$row->EOF) 
		{ 
	?>
		<th><?=$row->fields("proveedor")?></th>
	<? $row->MoveNext();
	 } ?>
	</tr>
	<? $row_otro->MoveFirst();
	while(!$row_otro->EOF) 
		{ 
	?>
	<tr>
		<td><?=$row_otro->fields("aspecto")?></td>
		<?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 1)
		$p = 1;
		 while($p <=$result_precio ) 
		{ 
		?>
			<td align="center"><?=$punto_precio[$p]?></td>
		<? $p++;
		 } ?>
		 <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 2){
		 $cop = 1;
		 while($cop <=$result_precio ) 
		{ 
		?>
			<td align="center"><?=$punto_conpago[$cop]?></td>
		<? $cop++;
		 } }?>
		  <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 3){
		$garan = 1;
		 while($garan <=$result_precio ) 
			{ 
			?>
				<td align="center"><?=$punto_garantia[$garan]?></td>
			<? $garan++;
			 } 
		 }?>
		 <?
		$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 4){
		$valo = 1;
		 while($valo <=$result_precio ) 
			{ 
			?>
				<td align="center"><?=$punto_valoferta[$valo]?></td>
			<? $valo++;
			 } 
		 } ?>
		<?
		//$row->MoveFirst();
		if($row_otro->fields("id_parametro_analisis_cotizacion") == 5){
		$ti = 1;
		 while($ti <=$result_precio ) 
			{ 
			?>
				<td align="center"><?=$punto_tiem_entraga[$ti]?></td>
			<? $ti++;
			 } 
		 }?>
	</tr>
	<? $row_otro->MoveNext(); //$punto_total  $prove[$inicio]
	 } ?>
	 <tr>
	 	<td>TOTAL</td>
	<?	
		$to = 1;
		 while($to <=$result_precio ) 
			{ 
			?>
				<td align="center"><?=$punto_total[$to]?></td>
			<? 
			$to++;
			 } 
		 ?>
	 </tr>
</table>
<table border="1">
	<tr>
		<th>Proveedor</th>
		<th>Precio</th>
		<th>Condiciones de Pago</th>
		<th>Garantia</th>
		<th>Validez de la Oferta</th>
		<th>Tiempo de Entrega</th>
		<th>Total</th>
	</tr>
	<? 
	$result_prove = count($prove);
	$x=1;
	//$x++;
	while ($x <= $result_prove){
	//	$prove_line = split("*",$prove[$x]);
		//list($proveedor, $precio, $pago, $garantia, $oferta, $tiempo) = split('*', $prove[$x]);
		list($proveedor, $precio, $pago, $garantia, $oferta, $tiempo,$total) = explode ( "*", $prove[$x]);

	?>
	<tr>
		<td><?=$proveedor?></td>
		<td><?=$precio?></td>
		<td><?=$pago?></td>
		<td><?=$garantia?></td>
		<td><?=$oferta?></td>
		<td><?=$tiempo?></td>
		<td><?=$total?></td>
	</tr>
	
	<?
		$x++;
	}
	?>
		
</table>
<? 
	$se++;
	for($inicio=1;$inicio<=$result_precio;$inicio++){

	//$prove[$inicio]="0";
	$conpago[$inicio] = 0 ;
	$garantia[$inicio] = 0 ;
	$valoferta[$inicio] = 0 ;
	$tiem_entraga[$inicio] = 0 ;
	$lugar_entrega[$inicio] = 0 ;
	$punto_valoferta[$inicio]= 0;
	$punto_precio[$inicio] = 0 ;
	$punto_tiem_entraga[$inicio]= 0;
	$punto_conpago[$inicio]=0;
	$punto_total[$inicio]= 0;
	$total = 0;
	//echo $prove[$inicio].'<br>';
	$row->MoveNext();
}
	// $row_requi->MoveNext();
}?>