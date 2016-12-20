<?php
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
		require('../../../../utilidades/fpdf153/fpdf.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************ 

$desde = $_GET['ejecucion_presupuestaria_rp_desde'];
$hasta = $_GET['ejecucion_presupuestaria_rp_hasta'];
//$desde = '01/01/2011';
//$hasta = '01/03/2011';

list($anoxx,$mesxx , $diaxx) = $desde;
list($anoyy,$mesyy , $diayy) = $hasta;

//$dia = substr ($dia, 0, 2);
	/*$where = $where."
	AND
		id_accion_especifica = 225 
	";*/
$sql ="
SELECT 
	presupuesto_ley.partida, 
	presupuesto_ley.generica, 
	presupuesto_ley.especifica, 
	presupuesto_ley.sub_especifica,
	denominacion AS clasificador_presupuestario,
	SUM(anteproyecto_presupuesto.enero + 
	anteproyecto_presupuesto.febrero + 
	anteproyecto_presupuesto.marzo + 
	anteproyecto_presupuesto.abril + 
	anteproyecto_presupuesto.mayo + 
	anteproyecto_presupuesto.junio + 
	anteproyecto_presupuesto.julio + 
	anteproyecto_presupuesto.agosto + 
	anteproyecto_presupuesto.septiembre + 
	anteproyecto_presupuesto.octubre + 
	anteproyecto_presupuesto.noviembre + 
	anteproyecto_presupuesto.diciembre) AS ante_proyecto, 
	SUM(presupuesto_ley.enero + 
	presupuesto_ley.febrero + 
	presupuesto_ley.marzo + 
	presupuesto_ley.abril + 
	presupuesto_ley.mayo + 
	presupuesto_ley.junio + 
	presupuesto_ley.julio + 
	presupuesto_ley.agosto + 
	presupuesto_ley.septiembre + 
	presupuesto_ley.octubre + 
	presupuesto_ley.noviembre + 
	presupuesto_ley.diciembre) AS presupuesto_ley
FROM 
	presupuesto_ley
LEFT JOIN
	anteproyecto_presupuesto
ON
	anteproyecto_presupuesto.partida = presupuesto_ley.partida
AND
	anteproyecto_presupuesto.generica = presupuesto_ley.generica
AND
	anteproyecto_presupuesto.especifica = presupuesto_ley.especifica
AND
	anteproyecto_presupuesto.sub_especifica = presupuesto_ley.sub_especifica
INNER JOIN
	clasificador_presupuestario
ON
	clasificador_presupuestario.partida = presupuesto_ley.partida
	AND
	clasificador_presupuestario.generica = presupuesto_ley.generica
	AND
	clasificador_presupuestario.especifica = presupuesto_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica

GROUP BY
	presupuesto_ley.partida, 
	presupuesto_ley.generica, 
	presupuesto_ley.especifica, 
	presupuesto_ley.sub_especifica,
	denominacion
ORDER BY
	presupuesto_ley.partida, 
	presupuesto_ley.generica, 
	presupuesto_ley.especifica, 
	presupuesto_ley.sub_especifica
	";
//die ($sql);

/*
AND
	\"presupuesto_ejecutadoD\".fecha_anula <> ''
AND
	fecha_compromiso BETWEEN  '".$desde."' AND '".$hasta."'
*/
$row=& $conn->Execute($sql);

if (!$row->EOF){
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			global $unidad_ejecutora,  $ano, $codigo_unidad,  $texto;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			//$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'ANTEPROYECTO & PRESUPUESTARIA',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(30,				6,		'CUENTA',						0,0,'C',1);
			$this->Cell(90,				6,		'DESCRIPCION',				0,0,'C',1);
			$this->Cell(40,				6,		'ANTEPROYECTO',					0,0,'C',1);
			$this->Cell(40,				6,		'PRESUPUESTO LEY',					0,0,'C',1);
			$this->Cell(40,				6,		'ABSOLUTAS',					0,0,'C',1);
			$this->Cell(25,				6,		'%',				0,0,'C',1);
			//$this->SetXY(193,$y);
			//$this->SetFont('Arial','B',7);
			//$this->MultiCell(19,		12,		'PORCENTAJE',					0,'C',1);
			$this->Ln(6);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(100,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(85,3,'Impreso por:  '.$_SESSION[apellido].' '.$_SESSION[nombre],0,0,'C');
			$this->Cell(75,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
	}
	
//*************************************************************************************************************
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
	$tamano = 0;
	$suma_total = 0;
	$totaliza = 0;
	$compriso_antes = 0 ;
	$partidas_antes = 0;
	$genericas_antes = 0;
		//****************************************************************************************************************************

	
//**************************************************************************
$sqlcomprometido_todo	="
SELECT 
	SUM(anteproyecto_presupuesto.enero + 
	anteproyecto_presupuesto.febrero + 
	anteproyecto_presupuesto.marzo + 
	anteproyecto_presupuesto.abril + 
	anteproyecto_presupuesto.mayo + 
	anteproyecto_presupuesto.junio + 
	anteproyecto_presupuesto.julio + 
	anteproyecto_presupuesto.agosto + 
	anteproyecto_presupuesto.septiembre + 
	anteproyecto_presupuesto.octubre + 
	anteproyecto_presupuesto.noviembre + 
	anteproyecto_presupuesto.diciembre) AS ante_proyecto, 
	SUM(presupuesto_ley.enero + 
	presupuesto_ley.febrero + 
	presupuesto_ley.marzo + 
	presupuesto_ley.abril + 
	presupuesto_ley.mayo + 
	presupuesto_ley.junio + 
	presupuesto_ley.julio + 
	presupuesto_ley.agosto + 
	presupuesto_ley.septiembre + 
	presupuesto_ley.octubre + 
	presupuesto_ley.noviembre + 
	presupuesto_ley.diciembre) AS presupuesto_ley
FROM 
	presupuesto_ley
LEFT JOIN
	anteproyecto_presupuesto
ON
	anteproyecto_presupuesto.partida = presupuesto_ley.partida
AND
	anteproyecto_presupuesto.generica = presupuesto_ley.generica
AND
	anteproyecto_presupuesto.especifica = presupuesto_ley.especifica
AND
	anteproyecto_presupuesto.sub_especifica = presupuesto_ley.sub_especifica
INNER JOIN
	clasificador_presupuestario
ON
	clasificador_presupuestario.partida = presupuesto_ley.partida
	AND
	clasificador_presupuestario.generica = presupuesto_ley.generica
	AND
	clasificador_presupuestario.especifica = presupuesto_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011' 

	*/
$row_todo=& $conn->Execute($sqlcomprometido_todo);	

$absolutas =  $row_todo->fields("presupuesto_ley") - $row_todo->fields("ante_proyecto") ;
if (($row_todo->fields("ante_proyecto") != "") )
	$porcentaje = ($absolutas/$row_todo->fields("ante_proyecto"))*100;
else
	$porcentaje = 0;
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(30,	5,			'400.00.00.00',														'TRL',0,'C',1);
				$pdf->Cell(90,	5,			'TOTAL EGRESOS',													'TRL',0,'L',1);
				$pdf->Cell(40,	5,			number_format($row_todo->fields("ante_proyecto"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($row_todo->fields("presupuesto_ley"),2,',','.'),		'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($absolutas,2,',','.'),								'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($porcentaje ,2,',','.'),								'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
	
	
	
$partidas_antes = 0;
$genericas_antes = 0;	
	//******************************************************************************************************************************

while (!$row->EOF)
{
//************************************************************************************
if($partidas_antes !=$row->fields("partida")){
$sqlcomprometido_par	="
SELECT 
	presupuesto_ley.partida, 
	denominacion AS clasificador_presupuestario,

	SUM(anteproyecto_presupuesto.enero + 
	anteproyecto_presupuesto.febrero + 
	anteproyecto_presupuesto.marzo + 
	anteproyecto_presupuesto.abril + 
	anteproyecto_presupuesto.mayo + 
	anteproyecto_presupuesto.junio + 
	anteproyecto_presupuesto.julio + 
	anteproyecto_presupuesto.agosto + 
	anteproyecto_presupuesto.septiembre + 
	anteproyecto_presupuesto.octubre + 
	anteproyecto_presupuesto.noviembre + 
	anteproyecto_presupuesto.diciembre) AS ante_proyecto, 
	SUM(presupuesto_ley.enero + 
	presupuesto_ley.febrero + 
	presupuesto_ley.marzo + 
	presupuesto_ley.abril + 
	presupuesto_ley.mayo + 
	presupuesto_ley.junio + 
	presupuesto_ley.julio + 
	presupuesto_ley.agosto + 
	presupuesto_ley.septiembre + 
	presupuesto_ley.octubre + 
	presupuesto_ley.noviembre + 
	presupuesto_ley.diciembre) AS presupuesto_ley
FROM 
	presupuesto_ley
LEFT JOIN
	anteproyecto_presupuesto
ON
	anteproyecto_presupuesto.partida = presupuesto_ley.partida
AND
	anteproyecto_presupuesto.generica = presupuesto_ley.generica
AND
	anteproyecto_presupuesto.especifica = presupuesto_ley.especifica
AND
	anteproyecto_presupuesto.sub_especifica = presupuesto_ley.sub_especifica
INNER JOIN
	clasificador_presupuestario
ON
	clasificador_presupuestario.partida = presupuesto_ley.partida
	AND
	clasificador_presupuestario.generica = presupuesto_ley.generica
	AND
	clasificador_presupuestario.especifica = presupuesto_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
WHERE
	presupuesto_ley.partida = '".$row->fields('partida')."'
GROUP BY
	presupuesto_ley.partida, 
	denominacion

	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011' 

	*/
$row_par=& $conn->Execute($sqlcomprometido_par);	

$absolutasx =  $row_par->fields("presupuesto_ley") - $row_par->fields("ante_proyecto") ;
if (($row_par->fields("ante_proyecto") != "") )
	$porcentajex = ($absolutasx/$row_par->fields("ante_proyecto"))*100;
else
	$porcentajex = 0;
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(30,	5,			$row_par->fields('partida').'.00.00.00',										'TRL',0,'C',1);
				$pdf->Cell(90,	5,			utf8_decode(substr($row_par->fields("clasificador_presupuestario"),0,90)),	'TRL',0,'L',1);
				$pdf->Cell(40,	5,			number_format($row_par->fields("ante_proyecto"),2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($row_par->fields("presupuesto_ley"),2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($absolutasx,2,',','.'),									'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($porcentajex ,2,',','.'),									'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
				$partidas_antes = $row_par->fields("partida");
	}
//************************************************************************************
//************************************************************************************
if($genericas_antes !=$row->fields("generica")){
$sqlcomprometido_par	="
SELECT 
	presupuesto_ley.partida, 
	presupuesto_ley.generica,
	denominacion AS clasificador_presupuestario,

	SUM(anteproyecto_presupuesto.enero + 
	anteproyecto_presupuesto.febrero + 
	anteproyecto_presupuesto.marzo + 
	anteproyecto_presupuesto.abril + 
	anteproyecto_presupuesto.mayo + 
	anteproyecto_presupuesto.junio + 
	anteproyecto_presupuesto.julio + 
	anteproyecto_presupuesto.agosto + 
	anteproyecto_presupuesto.septiembre + 
	anteproyecto_presupuesto.octubre + 
	anteproyecto_presupuesto.noviembre + 
	anteproyecto_presupuesto.diciembre) AS ante_proyecto, 
	SUM(presupuesto_ley.enero + 
	presupuesto_ley.febrero + 
	presupuesto_ley.marzo + 
	presupuesto_ley.abril + 
	presupuesto_ley.mayo + 
	presupuesto_ley.junio + 
	presupuesto_ley.julio + 
	presupuesto_ley.agosto + 
	presupuesto_ley.septiembre + 
	presupuesto_ley.octubre + 
	presupuesto_ley.noviembre + 
	presupuesto_ley.diciembre) AS presupuesto_ley
FROM 
	presupuesto_ley
LEFT JOIN
	anteproyecto_presupuesto
ON
	anteproyecto_presupuesto.partida = presupuesto_ley.partida
AND
	anteproyecto_presupuesto.generica = presupuesto_ley.generica
AND
	anteproyecto_presupuesto.especifica = presupuesto_ley.especifica
AND
	anteproyecto_presupuesto.sub_especifica = presupuesto_ley.sub_especifica
INNER JOIN
	clasificador_presupuestario
ON
	clasificador_presupuestario.partida = presupuesto_ley.partida
	AND
	clasificador_presupuestario.generica = presupuesto_ley.generica
	AND
	clasificador_presupuestario.especifica = presupuesto_ley.especifica
	AND
	clasificador_presupuestario.subespecifica = presupuesto_ley.sub_especifica
WHERE
	presupuesto_ley.partida = '".$row->fields('partida')."'
AND
	presupuesto_ley.generica = '".$row->fields('generica')."'
GROUP BY
	presupuesto_ley.partida,
	presupuesto_ley.generica, 
	denominacion

	";
	/*
		AND
		\"orden_compra_servicioE\".fecha_elabora BETWEEN  '01/01/2011' AND '31/01/2011' 

	*/
$row_par=& $conn->Execute($sqlcomprometido_par);	

$absolutasx =  $row_par->fields("presupuesto_ley") - $row_par->fields("ante_proyecto") ;
if (($row_par->fields("ante_proyecto") != "") )
	$porcentajex = ($absolutasx/$row_par->fields("ante_proyecto"))*100;
else
	$porcentajex = 0;
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(30,	5,			$row_par->fields('partida').'.'.$row_par->fields('generica').'.00.00',										'TRL',0,'C',1);
				$pdf->Cell(90,	5,			utf8_decode(substr($row_par->fields("clasificador_presupuestario"),0,90)),	'TRL',0,'L',1);
				$pdf->Cell(40,	5,			number_format($row_par->fields("ante_proyecto"),2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($row_par->fields("presupuesto_ley"),2,',','.'),			'TRL',0,'R',1);
				$pdf->Cell(40,	5,			number_format($absolutasx,2,',','.'),									'TRL',0,'R',1);
				$pdf->Cell(25,	5,			number_format($porcentajex ,2,',','.'),									'TRL',0,'R',1);
				$pdf->Ln(5);
				$pdf->SetFont('arial','',10);
				$genericas_antes = $row_par->fields("generica");
	}
//************************************************************************************
//************************************************************************************
//************************************************************************************

$absolutas =  $row->fields("presupuesto_ley") - $row->fields("ante_proyecto") ;
if (($row->fields("ante_proyecto") != "") or ($row->fields("ante_proyecto") != 0))
	$porcentaje = ($absolutas/$row->fields("ante_proyecto"))*100;
else
	$porcentaje = 0;

		$suma_total = 0;
				$pdf->Cell(30,	5,			$row->fields('partida').".".$row->fields('generica').".".$row->fields('especifica').".".$row->fields('sub_especifica'),	1,0,'C',1);
				$pdf->Cell(90,	5,			utf8_decode(substr($row->fields("clasificador_presupuestario"),0,90)),				1,0,'L',1);
				$pdf->Cell(40,	5,			number_format($row->fields("ante_proyecto"),2,',','.'),		1,0,'R',1);
				$pdf->Cell(40,	5,			number_format($row->fields("presupuesto_ley"),2,',','.'),	1,0,'R',1);
				$pdf->Cell(40,	5,			number_format($absolutas,2,',','.'),						1,0,'R',1);
				$pdf->Cell(25,	5,			number_format($porcentaje ,2,',','.'),						1,0,'R',1);
		$pdf->Ln(5);
	
		$row->MoveNext();
		//$rowprecomprometido_todo->MoveNext();
		
		
	}

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
			$this->Cell(0,5,'Dirección General de  Empresas y Servicios',0,0,'C');			
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