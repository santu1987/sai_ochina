<?php
session_start();

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
				
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$nombre_elabora = $_SESSION[nombre]/*.' '.$_SESSION[apellido]*/;

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$unidad_ejecutora = $_GET['unidad_ejecutora'];
$anio = $_GET['anio'];
$proyectos = $_GET['proyectos'];
$acciones = $_GET['acciones'];

if($proyectos == 1){
	$where = " AND presupuesto_ley.id_proyecto != 0";
}elseif($acciones == 1){
	$where = " AND presupuesto_ley.id_accion_central != 0";
}
$Sql="
		SELECT 
			presupuesto_ley.id_unidad_ejecutora, unidad_ejecutora.codigo_unidad_ejecutora AS codigo_unidad,unidad_ejecutora.nombre AS unidad_ejecutora,
			presupuesto_ley.id_accion_central, accion_centralizada.codigo_accion_central, accion_centralizada.denominacion AS accion_centralizada, 
			presupuesto_ley.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre AS proyecto
		FROM presupuesto_ley INNER JOIN
			unidad_ejecutora
		ON
			presupuesto_ley.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
		LEFT JOIN
			accion_centralizada
		ON
			presupuesto_ley.id_accion_central = accion_centralizada.id_accion_central
		LEFT JOIN
			proyecto
		ON
			presupuesto_ley.id_proyecto = proyecto.id_proyecto
			
		WHERE 
			(presupuesto_ley.id_organismo=$_SESSION[id_organismo])  
		AND 
			(presupuesto_ley.anio='".$anio."')
		AND 
			(presupuesto_ley.partida >'400') 
		AND 
			(presupuesto_ley.partida <'405') 
		$where
		GROUP BY
			presupuesto_ley.id_unidad_ejecutora, codigo_unidad,unidad_ejecutora,
			presupuesto_ley.id_accion_central, codigo_accion_central,accion_centralizada,
			presupuesto_ley.id_proyecto, codigo_proyecto, proyecto
		ORDER BY 
			presupuesto_ley.id_unidad_ejecutora

";

//echo $Sql;
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
		//************************************************************************
		class PDF extends FPDF
		{
			//Cabecera de página
			function Header()
			{		
				global $unidad_ejecutora,  $anio, $codigo_unidad;
				$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
							
				$this->SetFont('Arial','B',9);
				$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
				$this->Ln();			
				$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
				$this->Ln();			
				$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
				$this->Ln();
				/*$this->Ln();			
				$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
				$this->Ln();	
				$this->SetFont('Arial','B',14);
				$this->Cell(0,10,'PRESUPUESTO LEY',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(200,10,'Año: '.$anio,0,0,'L');
				$this->SetFont('Arial','B',10);
			//	$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
				$this->Ln(8);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(55,		6,		'Unidad Solicitante: ',			0,0,'L',1);
				$this->Cell(103,	6,		'Acción Central/Proyecto',		0,0,'L',1);
				$this->Cell(23,		6,		'401',							'RL',0,'C',1);
				$this->Cell(23,		6,		'402',							'L',0,'C',1);
				$this->Cell(23,		6,		'403',							'L',0,'C',1);
				$this->Cell(23,		6,		'404',							'L',0,'C',1);
				$this->Cell(25,		6,		'Monto',						'L',0,'R',1);
				$this->Ln(6);
			}
			//Pie de página
			function Footer()
			{
				global $nombre_elabora;
				//Posición: a 2,5 cm del final
				$this->SetY(-25);
				//Arial italic 8
				$this->SetFont('Arial','I',9);
				//Número de página
				$this->Cell(90,3,strtoupper($nombre_elabora) 			,0,0,'C');
				//$this->Cell(90,3,strtoupper($jefe_unidad)	 	,0,0,'C');
				//$this->Cell(90,3,'CN. EDGAR BERNARDO PARRA DUQUE' 	,0,0,'C');
				$this->Ln();
				$this->Cell(90,3,'Elaborado Por' ,0,0,'C');
				//$this->Cell(90,3,'Jefe '.$unidad_ejecutora ,0,0,'C');
				//$this->Cell(90,3,'Director General De OCHINA' ,0,0,'C');
				$this->Ln(6);
				$this->Cell(90,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
				$this->Cell(90,3,' '.str_replace('<br />',' ',''),0,0,'C');
				//$this->Cell(90,3,date("d/m/Y h:m:s"),0,0,'C');					
				$this->Ln();
				$this->SetFont('barcode','',6);
				$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			}
		}
		//************************************************************************
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		//$pdf->SetAutoPageBreak(100);
		$pdf->SetAutoPageBreak(auto,30);
		$e=0;
		$xe=0;
		while (!$row->EOF) 
		{
		
				$e++;
				$xe++;
					if($row->fields("id_proyecto") != 0)
						$SqlProyectoAccion= $row->fields("proyecto");
					elseif($row->fields("id_proyecto") == 0)
						$SqlProyectoAccion = $row->fields("accion_centralizada");
			$xx = 401;
				while ($xx<=404) 
							{					
					$sql_par = "
						SELECT sum(
								presupuesto_ley.enero+
								presupuesto_ley.febrero+
								presupuesto_ley.marzo+
								presupuesto_ley.abril+
								presupuesto_ley.mayo+
								presupuesto_ley.junio+
								presupuesto_ley.julio+
								presupuesto_ley.agosto+
								presupuesto_ley.septiembre+
								presupuesto_ley.octubre+
								presupuesto_ley.noviembre+
								presupuesto_ley.diciembre
							) AS sum, 
							count(id_unidad_ejecutora) AS sum_unidad
						FROM presupuesto_ley WHERE
							(partida= '".$xx."') 
						AND 
							(id_unidad_ejecutora= ".$row->fields('id_unidad_ejecutora').")
						AND 
							(id_proyecto= ".$row->fields("id_proyecto").")
						AND 
							(id_accion_central= ".$row->fields("id_accion_central").")
							";			
						$rowpar=& $conn->Execute($sql_par);	
						if (!$rowpar->EOF) 
							{	
						if ($xx == 401)
							$uno= $rowpar->fields("sum");
						if ($xx == 402)
							$dos= $rowpar->fields("sum");
						if ($xx == 403)
							$tres= $rowpar->fields("sum");
						if ($xx == 404)
							$cuatro= $rowpar->fields("sum");
						}
						$xx++;
					}						
					
			
					$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
					
					if 	(($f != $row->fields('id_unidad_ejecutora')) && ($f != "")){
							//$f = $row->fields('id_unidad_ejecutora');
							
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(235,		6,	$unidad,	"B",0,'R',1);
							$pdf->Cell(35,		6,	$monto_unida,						"B",0,'R',1);
							//$pdf->Cell(270,		6,	'',						'B',0,'R',1);
							
							$pdf->Ln(10);
							$pdf->SetFont('arial','',10);
							$xe=0;
							$monto_unida =0;
						}

					$total_par= $uno+$dos+$tres+$cuatro;
					if 	(($f != $row->fields('id_unidad_ejecutora')) )
						$pdf->Cell(55,	6,	$row->fields('codigo_unidad') ." ". utf8_decode($row->fields("unidad_ejecutora")),	0,0,'L',1);
					else
						$pdf->Cell(55,	6,	' ',	0,0,'L',1);
					$pdf->Cell(103,	6,	utf8_decode($SqlProyectoAccion),										0,0,'L',1);
					$pdf->Cell(23,	6,	number_format($uno,2,',','.'),																	'RL',0,'R',1);
					$pdf->Cell(23,	6,	number_format($dos,2,',','.'),																	'L',0,'R',1);
					$pdf->Cell(23,	6,	number_format($tres,2,',','.'),																	'L',0,'R',1);
					$pdf->Cell(23,	6,	number_format($cuatro,2,',','.'),																'L',0,'R',1);
					$pdf->Cell(25,	6,	number_format($total_par,2,',','.'),									'L',0,'R',1);
					$pdf->Ln(6);

					$monto_partida = $monto_partida + $monto_presupuesto;
		/*
						if 	($e == $rowpar->fields('sum_unidad')){
							$monto_partida = number_format($rowpar->fields('sum'),2,',','.');
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(240,		6,	'Total de la Partidad '.$partida,	0,0,'R',1);
							$pdf->Cell(30,		6,	$monto_partida,						0,0,'R',1);
							$pdf->Ln(6);
							$pdf->SetFont('arial','',10);
							$e=0;
							$monto_partida =0;
						}*/
						$sql_parte = "
						SELECT 
							count(codigo_unidad_ejecutora) as sum_unidad,
							sum(
								presupuesto_ley.enero+
								presupuesto_ley.febrero+
								presupuesto_ley.marzo+
								presupuesto_ley.abril+
								presupuesto_ley.mayo+
								presupuesto_ley.junio+
								presupuesto_ley.julio+
								presupuesto_ley.agosto+
								presupuesto_ley.septiembre+
								presupuesto_ley.octubre+
								presupuesto_ley.noviembre+
								presupuesto_ley.diciembre
							) AS suma
						FROM presupuesto_ley INNER JOIN
							unidad_ejecutora
						ON
							presupuesto_ley.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
						LEFT JOIN
							accion_centralizada
						ON
							presupuesto_ley.id_accion_central = accion_centralizada.id_accion_central
						LEFT JOIN
							proyecto
						ON
							presupuesto_ley.id_proyecto = proyecto.id_proyecto
							
						WHERE 
							(presupuesto_ley.id_organismo=$_SESSION[id_organismo])  
						AND 
							(presupuesto_ley.partida >'400') 
						AND 
							(presupuesto_ley.partida <'405') 
						AND
							(presupuesto_ley.id_unidad_ejecutora= ".$row->fields('id_unidad_ejecutora').")
						AND
							(presupuesto_ley.anio= '".$anio."')
						GROUP BY
							presupuesto_ley.id_unidad_ejecutora
						ORDER BY 
							presupuesto_ley.id_unidad_ejecutora							
							
						";
						$rowcount=& $conn->Execute($sql_parte);	
					if($f ==""){
						$f = $row->fields('id_unidad_ejecutora');
						$unidad = 'Total de la Division '.utf8_decode($row->fields("unidad_ejecutora"));
						$monto_unida =number_format($rowcount->fields('suma'),2,',','.');
						//$anterior = $row->fields('id_unidad_ejecutora');
					}
					if 	($f != $row->fields('id_unidad_ejecutora')){
						$f = $row->fields('id_unidad_ejecutora');
						$unidad = 'Total de la Division '.utf8_decode($row->fields("unidad_ejecutora"));
						$monto_unida =number_format($rowcount->fields('suma'),2,',','.');
						}
					/*$pdf->Cell(20,	6,'HOLA '.$f,									'L',0,'R',1);
					$pdf->Cell(20,	6,'UNIDAD '.$rowcount->fields('sum_unidad'),								'L',0,'R',1);
					$pdf->Ln(6);*/
					//elseif($f !=$row->fields('id_unidad_ejecutora')){
					//	$f = $row->fields('id_unidad_ejecutora');
					//}
			$row->MoveNext();
		}
		
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(235,		6,	$unidad,	"B",0,'R',1);
			$pdf->Cell(35,		6,	$monto_unida,						"B",0,'R',1);
			
			$pdf->Ln(10);
			$pdf->SetFont('arial','',10);
			$xe=0;
			$monto_unida =0;

			$sqlpartida = "SELECT sum(presupuesto_ley.enero + presupuesto_ley.febrero + presupuesto_ley.marzo + presupuesto_ley.abril + presupuesto_ley.mayo + presupuesto_ley.junio + presupuesto_ley.julio + presupuesto_ley.agosto + presupuesto_ley.septiembre + presupuesto_ley.octubre + presupuesto_ley.noviembre + presupuesto_ley.diciembre) AS sum
	   FROM presupuesto_ley WHERE (partida= '401' OR partida= '402' OR partida= '403' OR partida= '404') and (presupuesto_ley.anio='".$anio."')";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(245,		6,'Total Ante Proyecto de Presupuesto',							'LTB',0,'R',1);
			$pdf->Cell(30,		6,number_format($rowtotal->fields("sum"),2,',','.'),			'TRB',0,'R',1);  //$sql_par
		
			$pdf->Ln(6);
			//$pdf->MultiCell(275,10,$sql_parte,'LBR','L');
		$pdf->Output();

	
}else{
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de página
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetDrawColor(200);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>