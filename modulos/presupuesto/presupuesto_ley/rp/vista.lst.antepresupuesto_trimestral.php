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
$anio = $_GET['ano'];
$proyectos = $_GET['proyectos'];
$acciones = $_GET['acciones'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];
//************************************************************************
if (($desde == 01) || ($desde ==1))
	$desde_mes ='Enero';
if (($desde == 02) || ($desde ==2))
	$desde_mes ='Febrero';
if (($desde == 03) || ($desde ==3))
	$desde_mes ='Marzo';
if (($desde == 04) || ($desde ==4))
	$desde_mes ='Abril';
if (($desde == 05) || ($desde ==5))
	$desde_mes ='Mayo';
if (($desde == 06) || ($desde ==6))
	$desde_mes ='Junio';
if (($desde == 07) || ($desde ==7))
	$desde_mes ='Julio';
if (($desde == 08) || ($desde ==8))
	$desde_mes ='Agosto';
if (($desde == 09) || ($desde ==9))
	$desde_mes =' Septiembre';
if (($desde == 10) || ($desde ==10))
	$desde_mes ='Octubre';
if (($desde == 11) || ($desde ==11))
	$desde_mes ='Noviembre';
if (($desde == 12) || ($desde ==12))
	$desde_mes ='Diciembre';
if (($desde == 01) || ($desde ==1))
//************************************************************************
if (($hasta == 01) || ($hasta ==1))
	$hasta_mes ='Enero';
if (($hasta == 02) || ($hasta ==2))
	$hasta_mes ='Febrero';
if (($hasta == 03) || ($hasta ==3))
	$hasta_mes ='Marzo';
if (($hasta == 04) || ($hasta ==4))
	$hasta ='Abril';
if (($hasta == 05) || ($hasta ==5))
	$hasta_mes ='Mayo';
if (($hasta == 06) || ($hasta ==6))
	$hasta_mes ='Junio';
if (($hasta == 07) || ($hasta ==7))
	$hasta_mes ='Julio';
if (($hasta == 08) || ($hasta ==8))
	$hasta_mes ='Agosto';
if (($hasta == 09) || ($hasta ==9))
	$hasta_mes =' Septiembre';
if (($hasta == 10) || ($hasta ==10))
	$hasta_mes ='Octubre';
if (($hasta == 11) || ($hasta ==11))
	$hasta_mes ='Noviembre';
if (($hasta == 12) || ($hasta ==12))
	$hasta_mes ='Diciembre';	
//die $ano ; 

//************************************************************************
if($proyectos == 1){
	$where = " AND anteproyecto_presupuesto.id_proyecto <> 0";
}else{
	$where = " AND anteproyecto_presupuesto.id_accion_central <> 0";
}

//************************************************************************

$sql = "
 SELECT 
	unidad_ejecutora.codigo_unidad_ejecutora,
	accion_centralizada.codigo_accion_central,
	proyecto.id_proyecto,
	proyecto.codigo_proyecto,
	accion_especifica.codigo_accion_especifica,
	clasificador_presupuestario.partida,
	clasificador_presupuestario.generica,
	clasificador_presupuestario.especifica,
	clasificador_presupuestario.subespecifica,
	clasificador_presupuestario.denominacion,
	(enero + febrero + marzo) AS primer_semestre,
	(abril + mayo + junio) AS segundo_semestre,
	(julio + agosto + septiembre) AS tercero_semestre,
	(octubre + noviembre) AS cuarto_semestre,
	diciembre AS reserva,
	(enero + febrero + marzo + abril + mayo + junio +
	julio + agosto + septiembre + octubre + noviembre + diciembre) AS total
 FROM 
	anteproyecto_presupuesto
INNER JOIN
	clasificador_presupuestario
ON
	(clasificador_presupuestario.partida = anteproyecto_presupuesto.partida) AND
	(clasificador_presupuestario.generica = anteproyecto_presupuesto.generica) AND
	(clasificador_presupuestario.especifica = anteproyecto_presupuesto.especifica) AND
	(clasificador_presupuestario.subespecifica = anteproyecto_presupuesto.sub_especifica) 
INNER JOIN
	unidad_ejecutora
ON
	(unidad_ejecutora.id_unidad_ejecutora = anteproyecto_presupuesto.id_unidad_ejecutora)
	INNER JOIN
	accion_especifica
ON
	(accion_especifica.id_accion_especifica = anteproyecto_presupuesto.id_accion_especifica)
LEFT JOIN
	proyecto
ON
	(proyecto.id_proyecto = anteproyecto_presupuesto.id_proyecto)
LEFT JOIN
	accion_centralizada
ON
	(accion_centralizada.id_accion_central = anteproyecto_presupuesto.id_accion_central)
WHERE
	anteproyecto_presupuesto.anio = '$anio'
	$where
ORDER BY 
	unidad_ejecutora.codigo_unidad_ejecutora,
	accion_centralizada.codigo_accion_central,
	proyecto.codigo_proyecto,
	accion_especifica.codigo_accion_especifica,
	clasificador_presupuestario.partida,
	clasificador_presupuestario.generica,
	clasificador_presupuestario.especifica,
	clasificador_presupuestario.subespecifica
";
//die($sql);
$row=& $conn->Execute($sql);


//************************************************************************
if (!$row->EOF)
{
//die($sql);

//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
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
			$this->Cell(0,10,'RESUMEN DE ANTEPROYECTO DE PRESUPUESTO POR TRIMESTRE',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			$this->Cell(200,10,'Año: '.date('Y'),0,0,'L');
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(15,				10,		'UNIDAD ',					'B',0,'C',1);
			$this->Cell(25,				10,		'PROYECTO O AC ',			'B',0,'C',1);
			$this->Cell(15,				10,		'AE ',						'B',0,'C',1);
			$this->Cell(95,				10,		'PARTIDA',	'B',0,'C',1);
			$this->Cell(21,				10,		'1 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'2 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'3 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'4 TRIMESTRE',	'B',0,'C',1);
			$this->Cell(21,				10,		'RESERVA',	'B',0,'C',1);
			$this->Cell(21,				10,		'TOTAL',	'B',0,'C',1);
			
			$this->Ln(10);
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
		$pdf->SetFont('arial','',8);
		$pdf->SetFillColor(255);
		$total=0;
		$primer_trimestre = 0;
		$segundo_trimestre = 0;
		$tercer_trimestre = 0;
		$cuarto_trimestre = 0;
		$total_t = 0;
		$id_proyecto="";
		$id_accion_centralizada="";
		$id_accion_especifica = "";
	while (!$row->EOF)// id_unidad_ejecutora $row->fields("id_unidad_ejecutora")
	{
	
	$total = $row->fields("total");
	
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(15,	5,	$row->fields("codigo_unidad_ejecutora"),								'LRB',0,'C',1);
			
		
		if(($row->fields("id_proyecto") !=0) || ($row->fields("id_proyecto") !="")){

				$proyecto = $row->fields("codigo_proyecto");
			$pdf->Cell(25,	5,	$proyecto,								'LRB',0,'C',1);
		}else{
				$acion = $row->fields("codigo_accion_central");
			
			$pdf->Cell(25,	5,	$acion,					'LRB',0,'C',1);
		}		
			$pdf->Cell(15,	5,	$row->fields("codigo_accion_especifica"),					'LRB',0,'C',1);	
		
		$pdf->Cell(95,	5,	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica")." ".$row->fields("denominacion"),		'RLB',0,'L',1);
		$pdf->Cell(21,	5,	number_format($row->fields("primer_semestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("segundo_semestre"),2,',','.'),																			'LRB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("tercero_semestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("cuarto_semestre"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($row->fields("reserva"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(21,	5,	number_format($total,2,',','.'),		'LRB',0,'R',1);
		$pdf->Ln(5);
		
	$primer_semestre = $primer_semestre + $row->fields("primer_semestre");
	$segundo_semestre = $segundo_semestre + $row->fields("segundo_semestre");
	$tercero_semestre = $tercero_semestre + $row->fields("tercero_semestre");
	$cuarto_semestre = $cuarto_semestre + $row->fields("cuarto_semestre");
	$reserva = $reserva + $row->fields("reserva");
	$total_t = $total_t + $total;
	
	$row->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(150,	5,	Total,			1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($primer_semestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($segundo_semestre,2,',','.'),	1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($tercero_semestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($cuarto_semestre,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($reserva,2,',','.'),		1,0,'R',1);
		$pdf->Cell(21,	5,	number_format($total_t,2,',','.'),				1,0,'R',1);

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

}
$pdf->Output();
?>