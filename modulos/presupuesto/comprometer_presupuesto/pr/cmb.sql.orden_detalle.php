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
$cotizacion = $_GET['cotizacion'];
//$cotizacion = '090001';
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(\"orden_compra_servicioD\".id_orden_compra_serviciod)
			FROM 
					organismo 
				INNER JOIN 
					\"orden_compra_servicioD\" 
				ON
					\"orden_compra_servicioD\".id_organismo=organismo.id_organismo 
				INNER JOIN 
					unidad_medida 
				ON
					\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
				INNER JOIN 
					\"orden_compra_servicioE\" 
				ON
					\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
				WHERE 
					(\"orden_compra_servicioD\".id_organismo=$_SESSION[id_organismo] )
				AND
					(\"orden_compra_servicioD\".numero_pre_orden = '$cotizacion')
";
$row=& $conn->Execute($Sql);
//echo $Sql.'<br>';
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query
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
$Sql = "	
				SELECT 
					id_orden_compra_serviciod,
					secuencia,
					cantidad,
					unidad_medida.nombre,
					descripcion,
					monto,
					impuesto,
					partida,
					generica,
					especifica,
					subespecifica,
					(
					SELECT  
						(($monoto) +
						($traspasado) +
						($modificado) -
						($monto_comprometido)) AS disponible
					FROM 
						\"presupuesto_ejecutadoR\"
					WHERE
						(id_organismo = $_SESSION[id_organismo])
					AND
						(ano = '".date("Y")."')
					AND
						(id_unidad_ejecutora = \"orden_compra_servicioE\".id_unidad_ejecutora)
					AND
						(id_accion_especifica = \"orden_compra_servicioE\".id_accion_especifica)
					AND
						(partida = \"orden_compra_servicioD\".partida)
					AND
						(generica = \"orden_compra_servicioD\".generica)
					AND
						(especifica = \"orden_compra_servicioD\".especifica)
					AND
						(sub_especifica = \"orden_compra_servicioD\".subespecifica)
					)AS disponible
				FROM 
					organismo 
				INNER JOIN 
					\"orden_compra_servicioD\" 
				ON
					\"orden_compra_servicioD\".id_organismo=organismo.id_organismo 
				INNER JOIN 
					unidad_medida 
				ON
					\"orden_compra_servicioD\".id_unidad_medida = unidad_medida.id_unidad_medida
				INNER JOIN 
					\"orden_compra_servicioE\" 
				ON
					\"orden_compra_servicioD\".numero_pre_orden = \"orden_compra_servicioE\".numero_precompromiso 
				WHERE 
					(\"orden_compra_servicioD\".id_organismo=$_SESSION[id_organismo])
				AND
					(\"orden_compra_servicioD\".numero_pre_orden = '$cotizacion')	
				ORDER BY 
					partida,
					generica,
					especifica,
					subespecifica 
			";
//echo $Sql;
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

//$dispon = 0;

while (!$row->EOF) 
{
$total1 = $row->fields("monto")* $row->fields("cantidad");
if($row->fields("impuesto") ==0)
	$ivass1= 0;
else
	$ivass=(($total1*$row->fields("impuesto"))/100);
	
$total_con_iva1 = $total1 + $ivass1;

$disponible = $row->fields("disponible");
$dis= $row->fields("disponible"); 
$disponible = $disponible - ($total_con_iva1);
if ($disponible < 0){
	//echo "Monto -->".($row->fields("monto")* $row->fields("cantidad"))." aqui -->".$disponible."<br>";
	$disponible = $disponible +  ($total_con_iva1);
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/close.png' />";
	//	$dis_mensaje = "No tiene disponibilidad para este Reglon";

	
}else{
	//$dis_mensaje = "Tiene&nbsp; disponibilidad";
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/bien.png' />";
	//echo "aqua -->".$disponible."<br>"; <img id="compromiso_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif" />
}	
//$dispon =   $row->fields("disponible")-$dispon;


$total = $row->fields("monto")* $row->fields("cantidad");
if($row->fields("impuesto") ==0)
	$ivass= 0;
else
	$ivass= (($total*$row->fields("impuesto"))/100);
	
$total_con_iva = $total + $ivass;
$disponible = $row->fields("disponible");
$disponible = ($disponible+1) - ($total_con_iva);
if ($disponible < 0){
	//echo "Monto -->".($row->fields("monto")* $row->fields("cantidad"))." aqui -->".$disponible."<br>";
	$disponible = ($disponible-1) +  $total_con_iva;
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/close.png' />";
	//	$dis_mensaje = "No tiene disponibilidad para este Reglon";
	//$dis_mensaje = $disponible;

	
}else{
	//$dis_mensaje = "Tiene&nbsp; disponibilidad";
	$disponible = ($disponible-1);
	$dis_mensaje = "<img id='compromiso_pr_btn_guardar' src='imagenes/bien.png' />";
	//echo "aqua -->".$disponible."<br>"; <img id="compromiso_pr_btn_guardar" class="btn_guardar" src="imagenes/null.gif" />
}	


	$responce->rows[$i]['id']=$row->fields("id_orden_compra_serviciod");

	$responce->rows[$i]['cell']=array(	
										$row->fields("id_orden_compra_serviciod"),
										$row->fields("secuencia"),
										$row->fields("descripcion"),
										$row->fields("cantidad"),
										$row->fields("nombre"),
										//number_format($row->fields("monto"),2,',','.'),
										number_format($total,2,',','.'),
										number_format($row->fields("impuesto"),2,',','.'),
										number_format($total_con_iva,2,',','.'),
										$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica"),
										$dis_mensaje,
										number_format($disponible,2,',','.')
									);
	$i++;
	
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
//echo $responce;
?>