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
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla monedas
$unidad_ejecutora = $_GET['unidad_ejecutora'];
$ano = $_GET['ano'];
$where = "WHERE (1=1)";
if($unidad_ejecutora !="")
	$where =$where . "	AND (id_unidad_ejecutora =$unidad_ejecutora)";
if($ano !="")
	$where =$where . "	AND (anio ='$ano')";

$Sql="
	SELECT 
		id_accion_central, id_proyecto, id_unidad_ejecutora, 
		id_accion_especifica, anio, partida, generica, especifica, 
		sub_especifica, (enero + febrero + marzo + abril + mayo + junio + julio + agosto + septiembre + octubre + noviembre + diciembre) AS total, comentario 
		
	FROM presupuesto_ley
	".$where."
	ORDER BY id_unidad_ejecutora, anio, partida, generica, 
		especifica, sub_especifica
";
//echo $Sql;


$row=& $conn->Execute($Sql);

if (!$row->EOF)
{ 
	if($anio !="" and $unidad_ejecutora =="")
	{
	//************************************************************************
		while (!$row->EOF) 
		{
		
		$Sqld="
			SELECT 
				presupuesto_ley.id_accion_central, 
				presupuesto_ley.id_proyecto, 
				presupuesto_ley.id_unidad_ejecutora, 
				presupuesto_ley.id_accion_especifica, 
				presupuesto_ley.anio, 
				presupuesto_ley.partida, 
				presupuesto_ley.generica, 
				presupuesto_ley.especifica, 
				presupuesto_ley.sub_especifica, 
				(
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
				) AS monto_presupuesto,  
				unidad_ejecutora.nombre, 
				accion_especifica.denominacion,
				presupuesto_ley.comentario 
			FROM 
				presupuesto_ley 
			INNER JOIN 
				unidad_ejecutora 
			ON 
				unidad_ejecutora.id_unidad_ejecutora=presupuesto_ley.id_unidad_ejecutora 
			INNER JOIN 
				accion_especifica 
			ON 
				accion_especifica.id_accion_especifica=presupuesto_ley.id_accion_especifica 
			WHERE 
				(presupuesto_ley.id_organismo=$_SESSION[id_organismo]) AND
				(presupuesto_ley.id_unidad_ejecutora =".$row->fields("id_unidad_ejecutora").")
			ORDER BY 
				presupuesto_ley.id_unidad_ejecutora, 
				presupuesto_ley.anio
		";
		$rowro=& $conn->Execute($Sqld);
		
			
					if($rowro->fields("id_proyecto") != 0)
					$SqlProyectoAccion="SELECT nombre AS proyectopccion FROM  proyecto WHERE (id_proyecto = ".$rowro->fields("id_proyecto").")";
				elseif($rowro->fields("id_proyecto") == 0)
					$SqlProyectoAccion="SELECT denominacion AS proyectopccion  FROM  accion_centralizada WHERE (id_accion_central = ".$rowro->fields("id_accion_central").") ";
				$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);		
				$proyectopccion = $rowProyectoAccion->fields('proyectopccion');
			//************************************************************************
			$unidad_ejecutora=$rowro->fields("nombre");
			class PDF extends FPDF
			{
				//Cabecera de página
				function Header()
				{		
					global $unidad_ejecutora, $proyectopccion;
					
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
					$this->SetFont('Arial','B',15);
					$this->Cell(0,10,'LISTADO PRESUPUESTO DE LEY POR UNIDAD EJECUTORA',0,0,'C');
					$this->Ln();	
					$this->SetFont('Arial','B',14);
					$this->Cell(0,10,'Año 2009',0,0,'L');
					$this->Ln();	
					$this->SetFont('Arial','B',10);
					$this->Cell(0,10,'Unidad Ejecutora '.$unidad_ejecutora,0,0,'L');
					$this->Ln();	
					$this->SetFont('Arial','B',10);
					$this->Cell(0,10,'Proyecto/A.C. '.$proyectopccion,0,0,'L');
					$this->Ln(10);
					$this->SetFont('Arial','B',10);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(120) ;
					$this->SetTextColor(0);
					$this->Cell(40,		6,		'Partida',					0,0,'C',1);
					$this->Cell(130,	6,		'Descripcion',		0,0,'C',1);
					$this->Cell(20,		6,		'Monto',					0,0,'C',1);
					$this->Ln(6);
				}
			}
			//************************************************************************
		
		
			$total=0;
			$pdf=new PDF();
			$pdf->AliasNbPages();
			$pdf->AddFont('barcode');
			$pdf->AddPage();
			$pdf->SetFont('arial','',10);
			$pdf->SetFillColor(255);
			while (!$rowro->EOF) 
			{
				
				$partida = 	$rowro->fields("partida").".".$rowro->fields("generica").".".$rowro->fields("especifica").".".$rowro->fields("sub_especifica");
				
				$pdf->Cell(40,		6,	$partida,										0,0,'C',1);
				$pdf->Cell(130,		6,	$rowro->fields('denominacion'),					0,0,'L',1);
				$pdf->Cell(20,		6,	$rowro->fields("monto_presupuesto"),					0,0,'C',1);
				$pdf->Ln(6);
				$rowro->MoveNext();
			}
			$sqlpartida = "SELECT sum(presupuesto_ley.total_monto) AS sum
		   FROM presupuesto_ley
		   WHERE (id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").")";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->Cell(170,		6,'Total',							0,0,'R',1);
			$pdf->Cell(20,		6,$rowtotal->fields("sum"),			0,0,'C',1);
			$pdf->Ln(6);
			$pdf->Output();
		$row->MoveNext();
		
		}
	}elseif($unidad_ejecutora !="")
	{
		$SqlUnida="SELECT nombre, jefe_unidad FROM  unidad_ejecutora WHERE (id_unidad_ejecutora = ".$row->fields("id_unidad_ejecutora").") ORDER BY id_unidad_ejecutora ";
		$rowUnida=& $conn->Execute($SqlUnida);
		if(!$rowUnida->EOF){
			$unidad_ejecutora = $rowUnida->fields('nombre');
			$jefe_unidad = $rowUnida->fields('jefe_unidad');
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
				global $unidad_ejecutora,  $ano;
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
				$this->Cell(0,10,'PRESUPUESTO DE LEY POR UNIDAD EJECUTORA',0,0,'C');
				$this->Ln(11);	
				$this->SetFont('Arial','B',13);
				$this->Cell(160,10,'Año: '.$ano,0,0,'L');
				$this->Cell(80,10,'Unidad Ejecutora: '.$unidad_ejecutora,0,0,'L');
				$this->Ln(8);
				$this->SetFont('Arial','B',10);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);
				$this->Cell(40,		6,		'Cuenta',					0,0,'L',1);
				$this->Cell(100,	6,		'Acción Central/Proyecto',	0,0,'L',1);
				$this->Cell(105,	6,		'Denominacion',				0,0,'L',1);
				$this->Cell(30,		6,		'Monto',					0,0,'R',1);
				$this->Ln(6);
			}
			//Pie de página
			function Footer()
			{
				global $nombre, $jefe_unidad, $unidad_ejecutora;
				//Posición: a 2,5 cm del final
				$this->SetY(-20);
				//Arial italic 8
				$this->SetFont('Arial','I',9);
				//Número de página
				/*$this->Cell(90,3,'Preparado Por: ' ,0,0,'C');
				$this->Cell(90,3,'Solicitado Por: ' ,0,0,'C');
				$this->Cell(90,3,'Vo Bo: ' ,0,0,'C');
				$this->Ln();*/
				$this->Cell(90,3,strtoupper($nombre) 			,0,0,'C');
				$this->Cell(90,3,strtoupper($jefe_unidad)	 	,0,0,'C');
				$this->Cell(90,3,'CF. EDGAR BERNARDO PARRA DUQUE' 	,0,0,'C');
				$this->Ln();
				$this->Cell(90,3,'Elaborado Por' ,0,0,'C');
				$this->Cell(90,3,'Jefe '.$unidad_ejecutora ,0,0,'C');
				$this->Cell(90,3,'Director General De OCHINA' ,0,0,'C');
				$this->Ln(6);
				$this->Cell(90,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
				$this->Cell(90,3,' '.str_replace('<br />',' ',''),0,0,'C');
				$this->Cell(90,3,date("d/m/Y h:m:s"),0,0,'C');					
				$this->Ln();
				$this->SetFont('barcode','',6);
				$this->Cell(0,3,strtoupper("$_SESSION[usuario]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
			}
		}
		//************************************************************************
		//echo $SqlRequiDetalle; 	while (!$rowRequiDetalle->EOF) 
	
	$total=0;
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(-110);
		
		while (!$row->EOF) 
		{
			if($row->fields("id_proyecto") != 0)
				$SqlProyectoAccion="SELECT nombre AS proyectopccion FROM  proyecto WHERE (id_proyecto = ".$row->fields("id_proyecto").")";
			elseif($row->fields("id_proyecto") == 0)
				$SqlProyectoAccion="SELECT denominacion AS proyectopccion  FROM  accion_centralizada WHERE (id_accion_central = ".$row->fields("id_accion_central").") ";
			$rowProyectoAccion=& $conn->Execute($SqlProyectoAccion);
			
			$SqlAccionE="SELECT denominacion FROM  accion_especifica WHERE (id_accion_especifica = ".$row->fields("id_accion_especifica").") ";
			$rowAccionE=& $conn->Execute($SqlAccionE);
	
			$id_unidad_ejecutora = $row->fields("id_unidad_ejecutora");
			$partida = 	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica");
			
			$pdf->Cell(40,		6,	$partida,												0,0,'L',1);
			$pdf->Cell(100,		6,	$rowProyectoAccion->fields('proyectopccion'),			0,0,'L',1);
			$pdf->Cell(105,		6,	$rowAccionE->fields('denominacion'),					0,0,'L',1);
			$pdf->Cell(30,		6,	$row->fields("total"),							0,0,'R',1);
			$pdf->Ln(6);
			$row->MoveNext();
		}
			$sqlpartida = "SELECT sum(presupuesto_ley.enero + presupuesto_ley.febrero + presupuesto_ley.marzo + presupuesto_ley.abril + presupuesto_ley.mayo + presupuesto_ley.junio + presupuesto_ley.julio + presupuesto_ley.agosto + presupuesto_ley.septiembre + presupuesto_ley.octubre + presupuesto_ley.noviembre + presupuesto_ley.diciembre) AS sum
	   FROM presupuesto_ley where (presupuesto_ley.id_unidad_ejecutora = ".$id_unidad_ejecutora.")";
			$rowtotal=& $conn->Execute($sqlpartida);
			$pdf->SetFont('arial','B',10);
			$pdf->Cell(245,		6,'Total',							0,0,'R',1);
			$pdf->Cell(30,		6,$rowtotal->fields("sum"),			'T',0,'R',1);
			$pdf->Ln(6);
		$pdf->Output();
	}
}
?>