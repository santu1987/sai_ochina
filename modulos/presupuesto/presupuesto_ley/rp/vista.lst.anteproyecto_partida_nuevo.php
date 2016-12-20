<?php
session_start();
/************************
CAMBIO SOLICITADO POR MARIA ALEJANDRA EL DIA 17 DE ENERO DE 2010
SE PIDE QUE SOLO SE COLOQUE LOS CODIGOS TANTO DE LAS UNIDADES COMO DE LAS ACCIONES CENTRALES-ESPECIFICAS Y LOS PROYECTOS


*/
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
			anteproyecto_presupuesto.partida, anteproyecto_presupuesto.generica, anteproyecto_presupuesto.especifica, anteproyecto_presupuesto.sub_especifica, clasificador_presupuestario.denominacion AS clasificador_presupuestario,			
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
		INNER JOIN
			clasificador_presupuestario
		ON
			(
			anteproyecto_presupuesto.partida = clasificador_presupuestario.partida
			AND
			anteproyecto_presupuesto.generica = clasificador_presupuestario.generica
			AND
			anteproyecto_presupuesto.especifica = clasificador_presupuestario.especifica
			AND
			anteproyecto_presupuesto.sub_especifica = clasificador_presupuestario.subespecifica
			)
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
				global $unidad_ejecutora,  $ano, $codigo_unidad,$yy;
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
				$this->Cell(65,				10,		'Cuenta',					0,0,'L',1);
				$yy=$this->GetY();
				$this->MultiCell(25,		5,		'Unidad Solicitante',		0,'C',1);
				$this->SetXY(100,$yy);
				$yy=$this->GetY();
				$this->MultiCell(35,		5,		'Acción Central/ Proyecto',	0,'C',1);
				$this->SetXY(135,$yy);
				$yy=$this->GetY();
				$this->MultiCell(25,		5,		'Acción Especifica',		0,'C',1);
				$this->SetXY(160,$yy);
				$yy=$this->GetY();
				$this->Cell(25,				10,		'Monto',					0,0,'C',1);
				$this->Ln(10);
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
				$this->Cell(60,3,'Elaborado Por' ,0,0,'C');
				$this->Ln();
				$this->Cell(60,3,strtoupper($nombre_elabora) 			,0,0,'C');
				$this->Cell(100,3,' ',0,0,'C');
				$this->Cell(50,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
				$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(90,270,strtoupper($_SESSION[usuario].$_SESSION[id_unidad_ejecutora]),40,6);
			}
		}
		//************************************************************************
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		//$pdf->AddFont('barcode');
		$pdf->AddPage();
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(100);
		$pdf->SetAutoPageBreak(auto,25);
		$e=0;
		$i=0;
		$xe=0;
		$partt = 0;
		$generi = 0;
		$sqlpartida = "SELECT sum(anteproyecto_presupuesto.enero + anteproyecto_presupuesto.febrero + anteproyecto_presupuesto.marzo + anteproyecto_presupuesto.abril + anteproyecto_presupuesto.mayo + anteproyecto_presupuesto.junio + anteproyecto_presupuesto.julio + anteproyecto_presupuesto.agosto + anteproyecto_presupuesto.septiembre + anteproyecto_presupuesto.octubre + anteproyecto_presupuesto.noviembre + anteproyecto_presupuesto.diciembre) AS sum
	   FROM anteproyecto_presupuesto where  
			(anteproyecto_presupuesto.anio='".$anio."') ";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->SetTextColor(215,60,60);
			$pdf->Cell(150,		6,'Ante Proyecto de Presupuesto',							0,0,'L',1);
			$pdf->Cell(25,		6,number_format($rowtotal->fields("sum"),2,',','.'),			0,0,'R',1);
			$pdf->SetTextColor(0,0,0);
			$pdf->Ln(6);
			$pdf->SetFont('arial','',10);
		while (!$row->EOF) 
		{
		
				$e++;
				$i++;
					if($row->fields("id_proyecto") != 0)
						$SqlProyectoAccion= $row->fields("codigo_proyecto");
					elseif($row->fields("id_proyecto") == 0)
						$SqlProyectoAccion = $row->fields("codigo_accion_central");
					
					$rowAccionE = $row->fields("codigo_accion_especifica");
			
					$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
					
					$partida = 	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica")." ".$row->fields("clasificador_presupuestario");
					
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
						AND 
			(anteproyecto_presupuesto.anio='".$anio."') 
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
						AND 
			(anteproyecto_presupuesto.anio='".$anio."') ";
						
					$sqlbuscapartidasola1 ="SELECT 
						denominacion AS clasificador_presupuestario
					FROM 
						clasificador_presupuestario
					WHERE
						(partida= '".$row->fields("partida")."')
						AND
						generica = '00'
						";
						$rowparti=& $conn->Execute($sqlbuscapartidasola);
						$rowparti1=& $conn->Execute($sqlbuscapartidasola1);
						
					$sqlbuscapartidasola2 = "SELECT sum(
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
						and
						(generica= '".$row->fields("generica")."')
						AND 
			(anteproyecto_presupuesto.anio='".$anio."') 
					";	
					$sqlbuscapartidasola22 ="SELECT 
						denominacion AS clasificador_presupuestario
					FROM 
						clasificador_presupuestario
					WHERE
						(partida= '".$row->fields("partida")."')
						AND
						generica = '".$row->fields("generica")."'
						AND
						especifica = '00'
						";
						$rowparti2=& $conn->Execute($sqlbuscapartidasola2);	
						$rowparti22=& $conn->Execute($sqlbuscapartidasola22);	
				//echo($sqlbuscapartidasola);
					
					$contar_letra = strlen($partida);
					
					$salto1 = ceil($contar_letra / 31);
					$coordena = ($salto1 * 5);
					$coordena1 = 5;
					if ($partt != $row->fields("partida")){
						$pdf->SetFont('arial','B',10);
						$pdf->SetTextColor(235,50,50);
						$partt = $row->fields("partida");
						$pdf->Cell(150,	5,	$row->fields("partida").".00.00.00"." ".$rowparti1->fields("clasificador_presupuestario"),0,0,'L',1);
						$pdf->Cell(25,	5,	number_format($rowparti->fields("sum"),2,',','.'),				0,0,'R',1);
						$pdf->Ln(5);
						$pdf->SetFont('arial','',10);
						$pdf->SetTextColor(0,0,0);

					}
					if ($generi != $row->fields("generica")){
						$pdf->SetFont('arial','B',10);
						$pdf->SetTextColor(50,200,50);
						$partt = $row->fields("partida");
						$generi = $row->fields("generica");
						$pdf->Cell(150,	5,	$row->fields("partida").".".$row->fields("generica").".00.00"." ".$rowparti22->fields("clasificador_presupuestario"),			0,0,'L',1);
						$pdf->Cell(25,	5,	number_format($rowparti2->fields("sum"),2,',','.'),				0,0,'R',1);
						$pdf->Ln(5);
						$pdf->SetFont('arial','',10);
						$pdf->SetTextColor(0,0,0);
					}
					if($pdf->GetY() < 264){
						$y=$pdf->GetY();
					}else{
						$y=69.00125;
					}
					$pdf->MultiCell(65,	5,	$partida,														0,'L',1);
					$pdf->SetXY(75,$y);
					$pdf->Cell(25,	$coordena1,	$row->fields('codigo_unidad'),								0,0,'C',1);
					$pdf->SetFont('arial','',9);
					$pdf->Cell(35,	$coordena1,	$SqlProyectoAccion,											0,0,'C',1);
					$pdf->Cell(25,	$coordena1,	$rowAccionE,												0,0,'C',1);
					$pdf->SetFont('arial','',10);
					$pdf->Cell(25,	$coordena1,	number_format($row->fields("monto_presupuesto"),2,',','.'),	0,0,'R',1);
					$pdf->Ln($coordena);
					
				
					$monto_partida = $monto_partida + $monto_presupuesto;
		
						/*if 	($e == $rowpar->fields('sum_unidad')){
							$monto_partida = number_format($rowpar->fields('sum'),2,',','.');
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(135,		$coordena,	'Total de la Partidad Especifica'.$partida,	0,0,'R',1);
							$pdf->Cell(50,		$coordena,	$monto_partida,						0,0,'R',1);
							$pdf->Ln($coordena);
							$pdf->SetFont('arial','',10);
							$e=0;
							$monto_partida =0;
						}*/
						
						/*if 	($i == $rowparti->fields('sum_unidad')){
							$monto_partidas = number_format($rowparti->fields('sum'),2,',','.');
							$pdf->SetFont('arial','B',10);
							$pdf->Cell(135,		$coordena,	'Total de la Partidad '.$row->fields("partida"),	'T',0,'R',1);
							$pdf->Cell(50,		$coordena,	$monto_partidas,						'T',0,'R',1);
							$pdf->Ln($coordena);
							$pdf->SetFont('arial','',10);
							$i=0;
							$monto_partidas =0;
						}*/
				
			
			$row->MoveNext();
		}
		
			/*$sqlpartida = "SELECT sum(anteproyecto_presupuesto.enero + anteproyecto_presupuesto.febrero + anteproyecto_presupuesto.marzo + anteproyecto_presupuesto.abril + anteproyecto_presupuesto.mayo + anteproyecto_presupuesto.junio + anteproyecto_presupuesto.julio + anteproyecto_presupuesto.agosto + anteproyecto_presupuesto.septiembre + anteproyecto_presupuesto.octubre + anteproyecto_presupuesto.noviembre + anteproyecto_presupuesto.diciembre) AS sum
	   FROM anteproyecto_presupuesto ";
			$rowtotal=& $conn->Execute($sqlpartida);*/
			$pdf->SetFont('arial','B',10);
			$pdf->SetTextColor(215,60,60);
			$pdf->Cell(100,		6,'Total Ante Proyecto de Presupuesto',							'LTB',0,'L',1);
			$pdf->Cell(80,		6,number_format($rowtotal->fields("sum"),2,',','.'),			'TRB',0,'R',1);
			$pdf->SetTextColor(0,0,0);
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