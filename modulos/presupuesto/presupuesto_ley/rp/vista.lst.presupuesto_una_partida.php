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
$partida_toda = $_GET['partida'];
$partida =explode(".",$partida_toda);
$anio = $_GET['anio'];
//if ($unidad_ejecutora !="" ){
$Sql="
	SELECT 
		id_accion_central, id_proyecto, id_unidad_ejecutora, 
		id_accion_especifica, anio, partida, generica, especifica, 
		sub_especifica, (enero + febrero + marzo + abril + mayo + junio + julio + agosto + septiembre + octubre + noviembre + diciembre) AS total, comentario 
		
	FROM presupuesto_ley
	WHERE (partida = '$partida[0]' AND generica = '$partida[1]' AND especifica = '$partida[2]' AND sub_especifica = '$partida[3]') AND (anio ='$anio')
	ORDER BY partida, generica, especifica, sub_especifica
	";	
//}
//echo $Sql;
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
		
		
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
				global   $ano,$partida_toda;
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
				$this->Cell(0,10,'ANTEPROYECTO DE PRESUPUESTO POR PARTIDA',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(120,10,'Año: '.$ano,0,0,'L');
				$this->Cell(90,10,'Partida: '.$partida_toda,0,0,'L');
				$this->SetFont('Arial','B',10);
				$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
				$this->Ln(8);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(40,		6,		'Unidad Ejecutora',					0,0,'L',1);
				$this->Cell(100,	6,		'Acción Central/Proyecto',	0,0,'L',1);
				$this->Cell(105,	6,		'Denominacion',				0,0,'L',1);
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
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(-110);
		$e=0;
		while (!$row->EOF) 
		{
		$SqlUnida="SELECT nombre, jefe_unidad FROM  unidad_ejecutora WHERE (id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").") ORDER BY id_unidad_ejecutora ";
		$rowUnida=& $conn->Execute($SqlUnida);
		if(!$rowUnida->EOF){
			$unidad_ejecutora = $rowUnida->fields('nombre');
			//$jefe_unidad = $rowUnida->fields('jefe_unidad');
		}
		$e++;
			if($row->fields("id_proyecto") != 0)
				$SqlProyectoAccion="SELECT nombre AS proyectopccion FROM  proyecto WHERE (id_proyecto = ".$row->fields("id_proyecto").")";
			elseif($row->fields("id_proyecto") == 0)
				$SqlProyectoAccion="SELECT denominacion AS proyectopccion  FROM  accion_centralizada WHERE (id_accion_central = ".$row->fields("id_accion_central").") ";
			$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
			
			$SqlAccionE="SELECT denominacion FROM  accion_especifica WHERE (id_accion_especifica = ".$row->fields("id_accion_especifica").") ";
			$rowAccionE=& $conn->Execute($SqlAccionE);
	
			$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
			
			
			$sqlbuscapartida = "SELECT sum(
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
		   FROM presupuesto_ley
		WHERE
				(partida= '".$row->fields("partida")."') AND (generica= '".$row->fields("generica")."') AND 
				(especifica= '".$row->fields("especifica")."') AND (sub_especifica= '".$row->fields("sub_especifica")."')
				
				";
				$rowpar=& $conn->Execute($sqlbuscapartida);

			
			$pdf->Cell(40,		6,	$unidad_ejecutora,												0,0,'L',1);
			$pdf->Cell(100,		6,	$rowProyectoAccion->fields('proyectopccion'),			0,0,'L',1);
			$pdf->Cell(105,		6,	$rowAccionE->fields('denominacion'),					0,0,'L',1);
			$pdf->Cell(30,		6,	number_format($row->fields("total"),2,',','.'),							0,0,'R',1);
			$pdf->Ln(6);
			$monto_partida = $monto_partida + $monto_presupuesto;

				if 	($e == $rowpar->fields('sum_unidad')){
					$monto_partida = number_format($rowpar->fields('sum'),2,',','.');
					$pdf->SetFont('arial','B',10);
					$pdf->Cell(240,		6,	'Total de la Partidad '.$partida,	0,0,'R',1);
					$pdf->Cell(30,		6,	$monto_partida,						0,0,'R',1);
					$pdf->Ln(6);
					$pdf->SetFont('arial','',10);
					$e=0;
					$monto_partida =0;
				}
			
			
			$row->MoveNext();
		}
		
		
			$sqlpartida = "SELECT sum(presupuesto_ley.enero + presupuesto_ley.febrero + presupuesto_ley.marzo + presupuesto_ley.abril + presupuesto_ley.mayo + presupuesto_ley.junio + presupuesto_ley.julio + presupuesto_ley.agosto + presupuesto_ley.septiembre + presupuesto_ley.octubre + presupuesto_ley.noviembre + presupuesto_ley.diciembre) AS sum
	   FROM presupuesto_ley where  (partida = '$partida[0]' AND generica = '$partida[1]' AND especifica = '$partida[2]' AND sub_especifica = '$partida[3]')";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(245,		6,'Total',							0,0,'R',1);
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