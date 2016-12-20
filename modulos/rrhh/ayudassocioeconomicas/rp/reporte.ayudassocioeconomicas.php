<?
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

$sql_cono="
			SELECT 
				beneficiario_ayudas.id_beneficiario_ayudas,
				to_char(beneficiario_ayudas.fecha, 'DD/MM/YYYY') as fecha, 
				beneficiario_ayudas.concepto, 
				beneficiario_ayudas.monto, 
				beneficiario_ayudas.nombre,
				beneficiario_ayudas.apellido 				
			FROM
				beneficiario_ayudas  
			ORDER BY
				fecha
";
$row=& $conn->Execute($sql_cono);
if (!$row->EOF){
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RELACIÓN DE AYUDAS SOCIO ECONOMICAS',0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',9);
			$this->Cell(0,10, $texto,0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','B',13);
			//$this->SetFont('Arial','B',10);
			//$this->Cell(90,10,date("d/m/Y h:m:s"),0,0,'C');	
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			$this->Cell(130,				10,		'CONCEPTO',					'B',0,'L',1);
			$this->Cell(30,				10,		'FECHA',				'B',0,'L',1);
			$this->Cell(40,				10,		'MONTO',				'B',0,'L',1);
			$this->Cell(80,				10,		'NOMBRES Y APELLIDOS',				'B',0,'L',1);
			
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
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	while (!$row->EOF)
	{
		$pdf->Cell(130,	5,	$row->fields("concepto"),												'TRLB',0,'L',1);
		$pdf->Cell(30,	5,	$row->fields("fecha"),												'TRLB',0,'C',1);
		$pdf->Cell(40,	5,	number_format($row->fields("monto"),2,',','.'),															'TRLB',0,'R',1);
		$pdf->Cell(80,	5,	$row->fields("nombre").' '.$row->fields("apellido"),															'TRLB',0,'L',1);
		$pdf->Ln(5);
		$row->MoveNext();
	}
	$pdf->Output();
}
?>