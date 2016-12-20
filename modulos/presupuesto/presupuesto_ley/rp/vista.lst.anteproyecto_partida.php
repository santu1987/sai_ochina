<?php
session_start();

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
//require('../../../../utilidades/fpdf153/fpdf.php');
	require('../../../../utilidades/fpdf153/code128.php');
				
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$nombre_elabora = $_SESSION[nombre].' '.$_SESSION[apellido];

//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$anio = $_GET['anio'];
$Sql="
		SELECT 
			anteproyecto_presupuesto.id_unidad_ejecutora,unidad_ejecutora.codigo_unidad_ejecutora AS codigo_unidad,unidad_ejecutora.nombre AS unidad_ejecutora,unidad_ejecutora.jefe_unidad,
			anteproyecto_presupuesto.id_accion_central, accion_centralizada.codigo_accion_central, accion_centralizada.denominacion AS accion_centralizada, 
			anteproyecto_presupuesto.id_proyecto, proyecto.codigo_proyecto, proyecto.nombre AS proyecto, 
			anteproyecto_presupuesto.id_accion_especifica, accion_especifica.codigo_accion_especifica, accion_especifica.denominacion AS accion_especifica,
			anteproyecto_presupuesto.anio, 
			anteproyecto_presupuesto.partida, generica, especifica, 	sub_especifica, 
			(enero + febrero + marzo +	 
			abril + mayo + junio +
			julio + agosto + septiembre + 
			octubre + noviembre + diciembre) AS monto_presupuesto
		FROM 
			anteproyecto_presupuesto
		INNER JOIN
			unidad_ejecutora
		ON
			anteproyecto_presupuesto.id_unidad_ejecutora = unidad_ejecutora.id_unidad_ejecutora
		LEFT JOIN
			accion_centralizada
		ON
			anteproyecto_presupuesto.id_accion_central = accion_centralizada.id_accion_central
		LEFT JOIN
			proyecto
		ON
			anteproyecto_presupuesto.id_proyecto = proyecto.id_proyecto
		LEFT JOIN
			accion_especifica
		ON
			anteproyecto_presupuesto.id_accion_especifica = accion_especifica.id_accion_especifica
			
		WHERE 
			(anteproyecto_presupuesto.id_organismo=$_SESSION[id_organismo])  
		AND 
			(anteproyecto_presupuesto.anio='".$anio."') 
		ORDER BY 
			anteproyecto_presupuesto.partida, 
			anteproyecto_presupuesto.generica, 
			anteproyecto_presupuesto.especifica, 
			anteproyecto_presupuesto.sub_especifica

";
//echo $Sql;
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	$unidad_ejecutora = $row->fields("unidad_ejecutora");
	$codigo_unidad = $row->fields('codigo_unidad');
	$jefe_unidad = $row->fields('jefe_unidad');
	
		$ano = $row->fields('anio');
		$AccionE = $row->fields('accion_especifica');

		//************************************************************************
	class PDF extends PDF_Code128 
		{
			//Cabecera de página
			function Header()
			{		
				global $unidad_ejecutora,  $ano, $codigo_unidad;
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
				$this->Cell(0,10,'ANTEPROYECTO DE PRESUPUESTO POR PARTIDAS',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(200,10,'Año: '.$ano,0,0,'L');
				$this->SetFont('Arial','B',10);
				$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
				$this->Ln(8);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(22,		6,		'Cuenta',					0,0,'L',1);
				$this->Cell(55,		6,		'Unidad Solicitante: ',		0,0,'L',1);
				$this->Cell(80,		6,		'Acción Central/Proyecto',	0,0,'L',1);
				$this->Cell(95,		6,		'Denominacion',				0,0,'L',1);
				$this->Cell(15,		6,		'Monto',					0,0,'R',1);
				$this->Ln(6);
			}
			//Pie de página
			function Footer()
			{
				global $nombre_elabora, $jefe_unidad, $unidad_ejecutora;
				//Posición: a 2,5 cm del final
				$this->SetY(-25);
				//Arial italic 8
				$this->SetFont('Arial','I',9);
				//Número de página
				$this->Cell(90,3,'Elaborado Por' ,0,0,'C');
				//$this->Cell(90,3,strtoupper($jefe_unidad)	 	,0,0,'C');
				//$this->Cell(90,3,'CN. EDGAR BERNARDO PARRA DUQUE' 	,0,0,'C');
				$this->Ln();
				$this->Cell(90,3,strtoupper($nombre_elabora) 			,0,0,'C');
				//$this->Cell(90,3,'Jefe '.$unidad_ejecutora ,0,0,'C');
				//$this->Cell(90,3,'Director General De OCHINA' ,0,0,'C');
				$this->Ln(6);
				$this->Cell(90,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
				$this->Cell(90,3,' '.str_replace('<br />',' ',''),0,0,'C');
				//$this->Cell(90,3,date("d/m/Y h:m:s"),0,0,'C');					
				$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(130,190,strtoupper($_SESSION[usuario].$_SESSION[id_unidad_ejecutora]),40,6);
			}
		}
		//************************************************************************
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		//$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(100);
		$pdf->SetAutoPageBreak(auto,30);
		$e=0;
		$i=0;
		$xe=0;
		while (!$row->EOF) 
		{
		
				$e++;
				$i++;
					if($row->fields("id_proyecto") != 0)
						$SqlProyectoAccion= $row->fields("proyecto");
					elseif($row->fields("id_proyecto") == 0)
						$SqlProyectoAccion = $row->fields("accion_centralizada");
					
					$rowAccionE = $row->fields("accion_especifica");
			
					$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
					
					$partida = 	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
					
					$sqlbuscapartida = "SELECT sum(
						anteproyecto_presupuesto.enero+
							anteproyecto_presupuesto.febrero+
							anteproyecto_presupuesto.marzo+
							anteproyecto_presupuesto.abril+
							anteproyecto_presupuesto.mayo+
							anteproyecto_presupuesto.junio+
							anteproyecto_presupuesto.julio+
							anteproyecto_presupuesto.agosto+
							anteproyecto_presupuesto.septiembre+
							anteproyecto_presupuesto.octubre+
							anteproyecto_presupuesto.noviembre+
							anteproyecto_presupuesto.diciembre
							) AS sum, 
							count(id_unidad_ejecutora) AS sum_unidad
				   	FROM 
						anteproyecto_presupuesto
					WHERE
						(partida= '".$row->fields("partida")."') AND (generica= '".$row->fields("generica")."') AND 
						(especifica= '".$row->fields("especifica")."') AND (sub_especifica= '".$row->fields("sub_especifica")."')
						";
						$rowpar=& $conn->Execute($sqlbuscapartida);
					
					$sqlbuscapartidasola = "SELECT sum(
						anteproyecto_presupuesto.enero+
							anteproyecto_presupuesto.febrero+
							anteproyecto_presupuesto.marzo+
							anteproyecto_presupuesto.abril+
							anteproyecto_presupuesto.mayo+
							anteproyecto_presupuesto.junio+
							anteproyecto_presupuesto.julio+
							anteproyecto_presupuesto.agosto+
							anteproyecto_presupuesto.septiembre+
							anteproyecto_presupuesto.octubre+
							anteproyecto_presupuesto.noviembre+
							anteproyecto_presupuesto.diciembre
							) AS sum, 
							count(id_unidad_ejecutora) AS sum_unidad
				   	FROM 
						anteproyecto_presupuesto
					WHERE
						(partida= '".$row->fields("partida")."') 
						";
						$rowparti=& $conn->Execute($sqlbuscapartidasola);	
				//echo($sqlbuscapartidasola);
					
					$contar_letra = strlen($SqlProyectoAccion);
					$contar_rowAccionE = strlen($rowAccionE);
					$salto1 = ceil($contar_letra / 55);
					
					$salto2 = ceil($contar_comentario / 65);
					
					if ($salto1 >= $salto2){
						$coordena = ($salto1 * 6);
						$coordena1 = 6;
					}else{
						$coordena = ($salto2 * 6);
						$coordena1 = $coordena;
					}
					$pdf->Cell(22,	$coordena,	$partida,																0,0,'L',1);
					$pdf->Cell(55,	$coordena,	$row->fields('codigo_unidad') ." ". $row->fields("unidad_ejecutora"),	0,0,'L',1);
					$y=$pdf->GetY();
					$pdf->SetFont('arial','',9);
					$pdf->MultiCell(80,	5,	$SqlProyectoAccion,													0,'L',1);
					$pdf->SetXY(170,$y);
					$y=$pdf->GetY();
					$pdf->MultiCell(95,	5,	$rowAccionE,														0,'L',1);
					$pdf->SetXY(263,$y);
					$pdf->SetFont('arial','',10);
					$pdf->Cell(15,	$coordena,	number_format($row->fields("monto_presupuesto"),2,',','.'),							0,0,'R',1);
					$pdf->Ln($coordena);
					$monto_partida = $monto_partida + $monto_presupuesto;
		
						if 	($e == $rowpar->fields('sum_unidad')){
							$monto_partida = number_format($rowpar->fields('sum'),2,',','.');
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(240,		$coordena,	'Total de la Partidad Especifica'.$partida,	0,0,'R',1);
							$pdf->Cell(30,		$coordena,	$monto_partida,						0,0,'R',1);
							$pdf->Ln($coordena);
							$pdf->SetFont('arial','',10);
							$e=0;
							$monto_partida =0;
						}
						
						if 	($i == $rowparti->fields('sum_unidad')){
							$monto_partidas = number_format($rowparti->fields('sum'),2,',','.');
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(240,		$coordena,	'Total de la Partidad '.$row->fields("partida"),	'T',0,'R',1);
							$pdf->Cell(30,		$coordena,	$monto_partidas,						'T',0,'R',1);
							$pdf->Ln($coordena);
							$pdf->SetFont('arial','',10);
							$i=0;
							$monto_partidas =0;
						}
				
			
			$row->MoveNext();
		}
		
		
			$sqlpartida = "SELECT sum(anteproyecto_presupuesto.enero + anteproyecto_presupuesto.febrero + anteproyecto_presupuesto.marzo + anteproyecto_presupuesto.abril + anteproyecto_presupuesto.mayo + anteproyecto_presupuesto.junio + anteproyecto_presupuesto.julio + anteproyecto_presupuesto.agosto + anteproyecto_presupuesto.septiembre + anteproyecto_presupuesto.octubre + anteproyecto_presupuesto.noviembre + anteproyecto_presupuesto.diciembre) AS sum
	   FROM anteproyecto_presupuesto ";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(245,		6,'Total Ante Proyecto de Presupuesto',							'LTB',0,'R',1);
			$pdf->Cell(30,		6,number_format($rowtotal->fields("sum"),2,',','.'),			'TRB',0,'R',1);
			$pdf->Ln(6);
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