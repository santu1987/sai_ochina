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
$id = $_GET['id'];
$anio = $_GET['anio'];
$id_unidad = $_GET['unidad_ejecutora'];

if ($id_unidad !="" ){
	$wheri = " AND (id_unidad_ejecutora =$id_unidad)";
}else{
	$wheri = "";
	}
$Sql="
	SELECT 
		id_accion_central, id_proyecto, id_unidad_ejecutora, 
		id_accion_especifica, clasificador_presupuestario.denominacion AS clasificador_presupuestario, anteproyecto_presupuesto.anio, anteproyecto_presupuesto.partida, anteproyecto_presupuesto.generica, anteproyecto_presupuesto.especifica, 
		anteproyecto_presupuesto.sub_especifica, (enero + febrero + marzo + abril + mayo + junio + julio + agosto + septiembre + octubre + noviembre + diciembre) AS total, anteproyecto_presupuesto.comentario 
		
	FROM anteproyecto_presupuesto
	INNER JOIN
	clasificador_presupuestario
	ON
	((clasificador_presupuestario.partida = anteproyecto_presupuesto.partida )
	AND
	(clasificador_presupuestario.generica = anteproyecto_presupuesto.generica )
	AND
	(clasificador_presupuestario.especifica = anteproyecto_presupuesto.especifica )
	AND
	(clasificador_presupuestario.subespecifica = anteproyecto_presupuesto.sub_especifica )
	)
	WHERE (id_accion_especifica = $id) AND (anio ='$anio') $wheri 
	ORDER BY partida, generica, especifica, sub_especifica
	";	
//}
//echo $Sql;
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
		
			$SqlUnida="SELECT nombre, jefe_unidad, codigo_unidad_ejecutora FROM  unidad_ejecutora WHERE (id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").") ORDER BY id_unidad_ejecutora ";
		$rowUnida=& $conn->Execute($SqlUnida);
		if(!$rowUnida->EOF){
			$unidad_ejecutora = $rowUnida->fields('nombre');
			$codigo_unidad = $rowUnida->fields('codigo_unidad_ejecutora');
			//$jefe_unidad = $rowUnida->fields('jefe_unidad');
			
		}
		if($row->fields("id_proyecto") != 0){
				$SqlProyectoAccion="SELECT codigo_proyecto AS proyectopccion, nombre AS nomb FROM  proyecto WHERE (id_proyecto = ".$row->fields("id_proyecto").")";
				$sql_procen = "(id_proyecto= ".$row->fields("id_proyecto").") ";
		}elseif($row->fields("id_proyecto") == 0){
				$SqlProyectoAccion="SELECT codigo_accion_central AS proyectopccion, denominacion AS nomb FROM  accion_centralizada WHERE (id_accion_central = ".$row->fields("id_accion_central").") ";
				$sql_procen = "(id_accion_central= ".$row->fields("id_accion_central").") ";
		}
		$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
		if(!$rowUnida->EOF){
			$proyectopccion = $rowProyectoAccion->fields('proyectopccion')." ".$rowProyectoAccion->fields('nomb');
		}
		$SqlAccionE="SELECT denominacion FROM  accion_especifica WHERE (id_accion_especifica = ".$row->fields("id_accion_especifica").") ORDER BY id_accion_especifica ";
		$rowAccionE=& $conn->Execute($SqlAccionE);
		if(!$rowAccionE->EOF){
			$AccionE = $rowAccionE->fields('denominacion');
		}
		$ano = $row->fields('anio');
		
		//************************************************************************
		class PDF extends FPDF
		{
			
			//Cabecera de página
			function Header()
			{		
				global   $ano,$AccionE, $codigo_unidad, $unidad_ejecutora, $proyectopccion, $id_unidad;
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
				$this->Cell(0,10,'ANTEPROYECTO DE PRESUPUESTO POR ACCIÓN ESPECÍFICA',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(30,5,'Año: '.$ano,0,0,'L');
				if ($id_unidad !="" ){
				$this->MultiCell(150,5,'Unidad: '.$codigo_unidad." ".utf8_decode($unidad_ejecutora) ,0,'L',0);
				}
				$this->Ln(7);
				$this->MultiCell(150,5,'Acción Central/Proyecto: '.utf8_decode($proyectopccion) ,0,'L',0);
				$this->Ln(7);
				$this->Cell(45,5,'Accion Especifica: ',0,0,'L');
				$this->MultiCell(150,5,utf8_decode($AccionE),0,'L',0);
				$this->SetFont('Arial','B',10);
				//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
				$this->Ln(7);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(150,		6,		'Partida',					0,0,'C',1);
				//$this->Cell(40,		6,		'Unidad Ejecutora',			0,0,'L',1);
				//$this->Cell(170,	6,		'Acción Central/Proyecto',	0,0,'L',1);
				$this->Cell(30,		6,		'Monto',					0,0,'R',1);
				$this->Ln(6);
			}
			//Pie de página
		
		}
		//************************************************************************
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage();
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(-110);
		$e=0;
		while (!$row->EOF) 
		{
		$partida = 	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica")." ".utf8_decode($row->fields("clasificador_presupuestario")); 

		$e++;
			
			$SqlAccionE="SELECT denominacion, codigo_accion_especifica FROM  accion_especifica WHERE (id_accion_especifica = ".$row->fields("id_accion_especifica").") ";
			$rowAccionE=& $conn->Execute($SqlAccionE);
	
			$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
			
			
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
		   FROM anteproyecto_presupuesto
		WHERE
				(id_accion_especifica = $id)
				$wheri
				";
				$rowpar=& $conn->Execute($sqlbuscapartida);
				/////////////////////////////////////////////////////////////////////
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
						$sql_procen
					
					 AND (id_accion_especifica = $id) 
					 $wheri
					 ";
						
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
					AND
						(generica= '".$row->fields("generica")."')
					AND
						$sql_procen
					$wheri
					 AND (id_accion_especifica = $id) 
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
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
					if ($partt != $row->fields("partida")){
						$pdf->SetFont('arial','B',10);
						$pdf->SetTextColor(235,50,50);
						$partt = $row->fields("partida");
						$pdf->Cell(150,	5,	$row->fields("partida").".00.00.00"." ".$rowparti1->fields("clasificador_presupuestario")	,0,0,'L',1);
						$pdf->Cell(30,	5,	number_format($rowparti->fields("sum"),2,',','.')	,0,0,'R',1);
						$pdf->Ln(5);
						$pdf->SetFont('arial','',10);
						$pdf->SetTextColor(0,0,0);

					}
					if ($generi != $row->fields("generica")){
						$pdf->SetFont('arial','B',10);
						$pdf->SetTextColor(50,200,50);
						$partt = $row->fields("partida");
						$generi = $row->fields("generica");
						$pdf->Cell(150,	5,	$row->fields("partida").".".$row->fields("generica").".00.00"." ".$rowparti22->fields("clasificador_presupuestario"),				0,0,'L',1);
						$pdf->Cell(30,	5,	number_format($rowparti2->fields("sum"),2,',','.'),				0,0,'R',1);
						$pdf->Ln(5);
						$pdf->SetFont('arial','',10);
						$pdf->SetTextColor(0,0,0);
					}
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

			$pdf->Cell(150,		6,	$partida,					0,0,'L',1);
		//	$pdf->Cell(40,		6,	$codigo_unidad,												0,0,'L',1);
		//	$pdf->Cell(170,		6,	$rowProyectoAccion->fields('proyectopccion'),			0,0,'L',1);
			$pdf->Cell(30,		6,	number_format($row->fields("total"),2,',','.'),							0,0,'R',1);
			$pdf->Ln(6);
			$monto_partida = $monto_partida + $monto_presupuesto;

				if 	($e == $rowpar->fields('sum_unidad')){
					$monto_partida = number_format($rowpar->fields('sum'),2,',','.');
					$pdf->SetFont('arial','B',10);
					$pdf->Cell(150,		6,	'Total  ',	0,0,'R',1);
					$pdf->Cell(30,		6,	$monto_partida,						0,0,'R',1);
					$pdf->Ln(6);
					$pdf->SetFont('arial','',10);
					$e=0;
					$monto_partida =0;
				}
			
			
			$row->MoveNext();

if($partidasss <> $partidas){
////////////////////////////////////////////////////////////////////////////////////////////////
$sqlbuscapartidasolass = "SELECT sum(
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
							count(id_unidad_ejecutora) AS com_unidad
				   	FROM 
						anteproyecto_presupuesto
					WHERE
						(partida= '".$row->fields("partida")."')
					AND
						(generica= '".$row->fields("generica")."')
					AND
						(especifica= '".$row->fields("especifica")."')
					AND
						(sub_especifica= '".$row->fields("sub_especifica")."')
					AND
						(id_accion_especifica = $id)
						$wheri
					";	
					$rowpartiss=& $conn->Execute($sqlbuscapartidasolass);
					
					if ($rowpartiss->fields("com_unidad") >=2){
						$sqlbuscapartidasolasss ="SELECT 
							denominacion AS clasificador_presupuestario
						FROM 
							clasificador_presupuestario
						WHERE
							(partida= '".$row->fields("partida")."')
						AND
							generica = '".$row->fields("generica")."'
						
						AND
							(especifica= '".$row->fields("especifica")."')
						AND
							(subespecifica= '".$row->fields("sub_especifica")."')
							";
							//echo $sqlbuscapartidasolasss;
							$rowpartisss=& $conn->Execute($sqlbuscapartidasolasss);
							
						$pdf->SetFont('arial','B',10);
						$pdf->SetTextColor(50,200,50);
						$pdf->Cell(25,	5,	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica"),	0,0,'L',1);
						$pdf->Cell(140,	5,	$rowpartisss->fields("clasificador_presupuestario"),				0,0,'L',1);
						$pdf->Cell(25,	5,	number_format($rowpartiss->fields("sum"),2,',','.'),				0,0,'R',1);
						$pdf->Ln(5);
						$pdf->SetFont('arial','',10);
						$pdf->SetTextColor(0,0,0);
					
					}
	}
////////////////////////////////////////////////////////////////////////////////////////////////				
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

		}
		
		
			$sqlpartida = "SELECT sum(anteproyecto_presupuesto.enero + anteproyecto_presupuesto.febrero + anteproyecto_presupuesto.marzo + anteproyecto_presupuesto.abril + anteproyecto_presupuesto.mayo + anteproyecto_presupuesto.junio + anteproyecto_presupuesto.julio + anteproyecto_presupuesto.agosto + anteproyecto_presupuesto.septiembre + anteproyecto_presupuesto.octubre + anteproyecto_presupuesto.noviembre + anteproyecto_presupuesto.diciembre) AS sum
	   FROM anteproyecto_presupuesto where  (id_accion_especifica = $id) $wheri";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(150,		6,'Total',							0,0,'R',1);
			$pdf->Cell(30,		6,number_format($rowtotal->fields("sum"),2,',','.'),			'T',0,'R',1);
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