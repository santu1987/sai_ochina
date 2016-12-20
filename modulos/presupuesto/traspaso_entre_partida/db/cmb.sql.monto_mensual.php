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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$fecha = date('Y');
/*
//$unidad =	$_POST['traspaso_entre_partida_db_unidad_ejecutora'];
$unidad_es = $_POST['traspaso_entre_partida_db_accion_especifica_id'];
$mes =		$_POST['traspaso_entre_partida_db_mes_cendente'];
$partida_toda = $_POST['traspaso_entre_partida_db_partida_numero'];

*/
$unidad =	$_GET['unidad'];
$unidad_es = $_GET['unidad_es'];
$mes =		$_GET['mes'];
$fecha =		$_GET['ano'];
$partida_toda = $_GET['partida_toda'];

$partida =explode(".",$partida_toda);

if ($_GET['proyecto'] != "")
	$proyecto = $_GET['proyecto'];
else
	$proyecto = 0;
if ($_GET['accion_central'] != "")
$accion_central = $_GET['accion_central'];
else
	$accion_central = 0;
	/*
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'enero')
	$posicion = 1;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'febrero')
	$posicion = 2;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'marzo')
	$posicion = 3;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'abril')
	$posicion = 4;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'mayo')
	$posicion = 5;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'junio')
	$posicion = 6;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'julio')
	$posicion = 7;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'agosto')
	$posicion = 8;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'septiembre')
	$posicion = 9;	
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'octubre')
	$posicion = 10;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'noviembre')
	$posicion = 11;
if ($_POST[traspaso_entre_partida_db_mes_cendente] == 'diciembre')
	$posicion = 12;		*/

	$count = 12;


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
$page =1;
// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

$Sql = "	
				SELECT  
				\"presupuesto_ejecutadoR\".monto_presupuesto,
				\"presupuesto_ejecutadoR\".monto_modificado,
				\"presupuesto_ejecutadoR\".monto_traspasado,
				\"presupuesto_ejecutadoR\".monto_precomprometido,
				\"presupuesto_ejecutadoR\".monto_comprometido
			FROM 
				\"presupuesto_ejecutadoR\"
			
			WHERE
				(\"presupuesto_ejecutadoR\".id_unidad_ejecutora = ".$unidad .")
				AND
				(\"presupuesto_ejecutadoR\".id_accion_especifica =".$unidad_es .")
				AND
				(\"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo])
				AND
				(\"presupuesto_ejecutadoR\".ano='".$fecha."') 
				AND
				(\"presupuesto_ejecutadoR\".id_accion_centralizada=".$accion_central.")
				AND
				(\"presupuesto_ejecutadoR\".id_proyecto='".$proyecto."') 
				AND
				(\"presupuesto_ejecutadoR\".partida='".$partida[0]."')
				AND
				(\"presupuesto_ejecutadoR\".generica='".$partida[1]."') 
				AND
				(\"presupuesto_ejecutadoR\".especifica='".$partida[2]."')
				AND
				(\"presupuesto_ejecutadoR\".sub_especifica='".$partida[3]."') 
			";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
$me = 0;
$pre = str_replace("{","",$row->fields("monto_presupuesto"));
$pre = str_replace("}","",$pre);

$modificado = str_replace("{","",$row->fields("monto_modificado"));
$modificado = str_replace("}","",$modificado);

$traspasado = str_replace("{","",$row->fields("monto_traspasado"));
$traspasado = str_replace("}","",$traspasado);

$comprome = str_replace("{","",$row->fields("monto_precomprometido"));
$comprome = str_replace("}","",$comprome);
$monto_comprometido = str_replace("{","",$row->fields("monto_comprometido"));
$monto_comprometido = str_replace("}","",$monto_comprometido);


list($enero, $febrero, $marzo, $abril, $mayo, $junio, $julio, $agosto, $septiembre,  $octubre, $noviembre, $diciembre) = split(',', $pre);
list($enero_modificado, $febrero_modificado, $marzo_modificado, $abril_modificado, $mayo_modificado, $junio_modificado, $julio_modificado, $agosto_modificado, $septiembre_modificado,  $octubre_modificado, $noviembre_modificado, $diciembre_modificado) = split(',', $modificado);
list($enero_traspasado, $febrero_traspasado, $marzo_traspasado, $abril_traspasado, $mayo_traspasado, $junio_traspasado, $julio_traspasado, $agosto_traspasado, $septiembre_traspasado,  $octubre_traspasado, $noviembre_traspasado, $diciembre_traspasado) = split(',', $traspasado);
list($enero_comprome, $febrero_comprome, $marzo_comprome, $abril_comprome, $mayo_comprome, $junio_comprome, $julio_comprome, $agosto_comprome, $septiembre_comprome,  $octubre_comprome, $noviembre_comprome, $diciembre_comprome) = split(',', $comprome);
list($enero_comprome2, $febrero_comprome2, $marzo_comprome2, $abril_comprome2, $mayo_comprome2, $junio_comprome2, $julio_comprome2, $agosto_comprome2, $septiembre_comprome2,  $octubre_comprome2, $noviembre_comprome2, $diciembre_comprome2) = split(',', $monto_comprometido);

$signos = -1;

while ($me < 12) 
{
					if($me == 0){
							$xx = 1;
							$meses = 'Enero';
							
							$sqlenero="
							SELECT 
								SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS precomprometido
							FROM
								\"orden_compra_servicioD\"
							INNER JOIN
								\"orden_compra_servicioE\"
							ON
								\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
							WHERE
								id_unidad_ejecutora = ".$unidad."
							AND
								id_accion_especifica = ".$unidad_es."
							AND
								partida = '".$partida[0]."'
							AND
								generica = '".$partida[1]."'
							AND
								especifica = '".$partida[2]."'
							AND
								subespecifica = '".$partida[3]."'
							AND 
								disponible = 1
							AND
								fecha_elabora BETWEEN '2011-01-01' AND '2011-01-31'
							";
//die ($sqlenero);
							$rowenero=& $conn->Execute($sqlenero);
							if (!$rowenero->EOF)
							{
								if(date('n') == '01')
									$enero_comprome = $rowenero->fields("precomprometido");
								else
									$enero_comprome = $enero_comprome2;
							}else{
								$enero_comprome = 0;
							}

							$dispo = $enero + $enero_modificado + $enero_traspasado - $enero_comprome;
							if($dispo < 0)
								$dispo = 0;
					}
					if($me == 1){
						$xx = 2;
							$meses = 'Febrero';
							
							$sqlfebrero="
							SELECT 
								SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS precomprometido
							FROM
								\"orden_compra_servicioD\"
							INNER JOIN
								\"orden_compra_servicioE\"
							ON
								\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
							WHERE
								id_unidad_ejecutora = ".$unidad."
							AND
								id_accion_especifica = ".$unidad_es."
							AND
								partida = '".$partida[0]."'
							AND
								generica = '".$partida[1]."'
							AND
								especifica = '".$partida[2]."'
							AND
								subespecifica = '".$partida[3]."'
							AND 
								disponible = 1
							AND
								fecha_elabora BETWEEN '2011-02-01' AND '2011-02-28'
							";
//die ($sqlenero);
							$rowfebrero=& $conn->Execute($sqlfebrero);
							if (!$rowfebrero->EOF)
							{
								if(date('n') == '02')
									$febrero_comprome = $rowfebrero->fields("precomprometido");
								else
									$febrero_comprome = $febrero_comprome2;
							}else{
								$febrero_comprome = 0;
							}
							
							$dispo = $febrero + $febrero_modificado + $febrero_traspasado - $febrero_comprome;
							/*if($dispo < 0)
								$dispo = 0;*/
					}
					if($me == 2){
							$xx = 3;
							$meses = 'Marzo';
							
							$sqlmarzo="
							SELECT 
								SUM((cantidad * monto) + (((cantidad * monto)/100)*impuesto)) AS precomprometido
							FROM
								\"orden_compra_servicioD\"
							INNER JOIN
								\"orden_compra_servicioE\"
							ON
								\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
							WHERE
								id_unidad_ejecutora = ".$unidad."
							AND
								id_accion_especifica = ".$unidad_es."
							AND
								partida = '".$partida[0]."'
							AND
								generica = '".$partida[1]."'
							AND
								especifica = '".$partida[2]."'
							AND
								subespecifica = '".$partida[3]."'
							AND 
								disponible = 1
							AND
								fecha_elabora BETWEEN '2011-03-01' AND '2011-03-31'
							";
//die ($sqlenero);
							$rowmarzo=& $conn->Execute($sqlmarzo);
							if (!$rowmarzo->EOF)
							{
								if(date('n') == '03')
									$marzo_comprome = $rowmarzo->fields("precomprometido");
								else
									$marzo_comprome = $marzo_comprome2;
							}else{
								$marzo_comprome = 0;
							}
							
							$dispo = $marzo + $marzo_modificado + $marzo_traspasado - $marzo_comprome;
							/*if($dispo < 0)
								$dispo = 0;*/
					}
					if($me == 3){
							$xx = 4;
							$meses = 'Abril';
							if($abril_comprome <0)
								$abril_comprome= $abril_comprome * $signos ;
							
							$dispo = $abril + $abril_modificado + $abril_traspasado - $abril_comprome;
					}
					if($me == 4){
						$xx = 5;
							$meses = 'Mayo';
							if($mayo_comprome <0)
								$mayo_comprome= $mayo_comprome * $signos ;
							
							$dispo = $mayo + $mayo_modificado + $mayo_traspasado - $mayo_comprome;
					}
					if($me == 5){
						$xx = 6;
							$meses = 'Junio';
							if($junio_comprome <0)
								$junio_comprome= $junio_comprome * $signos ;
							
							$dispo = $junio + $junio_modificado + $junio_traspasado - $junio_comprome;
					}
					if($me == 6){
						$xx = 7;
							$meses = 'Julio';
							if($julio_comprome <0)
								$julio_comprome= $julio_comprome * $signos ;
							
							$dispo = $julio + $julio_modificado + $julio_traspasado - $julio_comprome;
					}
					if($me == 7){
						$xx = 8;
							$meses = 'Agosto';
							if($agosto_comprome <0)
								$agosto_comprome= $agosto_comprome * $signos ;
						
							$dispo = $agosto + $agosto_modificado + $agosto_traspasado - $agosto_comprome;
					}
					if($me == 8){
						$xx = 9;
							$meses = 'Septiembre';
							if($septiembre_comprome <0)
								$septiembre_comprome= $septiembre_comprome * $signos ;
							
							$dispo = $septiembre + $septiembre_modificado + $septiembre_traspasado - $septiembre_comprome;
					}
					if($me == 9){
						$xx = 10;
							$meses = 'Octubre';
							if($octubre_comprome <0)
								$octubre_comprome= $octubre_comprome * $signos ;
							
							$dispo = $octubre + $octubre_modificado + $octubre_traspasado - $octubre_comprome;
					}
					if($me == 10){
						$xx = 11;
							$meses = 'Noviembre';
							if($noviembre_comprome <0)
								$noviembre_comprome= $noviembre_comprome * $signos ;
							
							$dispo = $noviembre + $noviembre_modificado + $noviembre_traspasado - $noviembre_comprome;
					}
					if($me == 11){
						$xx = 12;
							$meses = 'Diciembre' ;
							if($diciembre_comprome <0)
								$diciembre_comprome= $diciembre_comprome * $signos ;
							
							$dispo = $diciembre + $diciembre_modificado + $diciembre_traspasado - $diciembre_comprome;
					}
						
	$responce->rows[$i]['id']=$xx;

	$responce->rows[$i]['cell']=array(	
					
							$xx,
							$meses,
							number_format($dispo,2,',','.')
					);
	$i++;
	$me++;
	//$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
//echo $responce;
?>