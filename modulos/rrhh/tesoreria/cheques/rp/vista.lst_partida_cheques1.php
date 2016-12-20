<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$tera=1;
//////////////////////////////////////////// realizando el pagado en las tablas de presupuesto/////////////
/*				$vector = split(",",$_GET['ordenes']);
				$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
				$i=0;
				while($i < $contador)
				{///////////consultando las orden de pago
					$sql_orden="SELECT  orden_pago,documentos
														FROM
															orden_pago
														WHERE(orden_pago.id_orden_pago='$vector[$i]')";		
					$row_orden=$conn->Execute($sql_orden); 
					$documentos=$row_orden->fields("documentos");
					$doc1=str_replace("{","",$documentos);
					$doc2=str_replace("}","",$doc1);
					$facturas= split(",",$doc2);
					$contador_fact=count($facturas);
					$i_fact=0;
					while($i_fact < $contador_fact)
					{//////////consultando las facturas			
											$sql_facturas="
																SELECT 
																		numero_compromiso
																FROM
																		documentos_cxp
																where
																		id_documentos='$facturas[$i_fact]'";
											$row_documentos=& $conn->Execute($sql_facturas);
											$numero_compromiso=$row_documentos->fields("numero_compromiso");
											if(($numero_compromiso!="")&&($numero_compromiso!="0"))
											$tera=$tera+1;
					$i_fact ++;
					}				
			$i++;
			}*/
/////////////////////////////////////////////////////////////////////////////////////////////////////
if ($tera>0)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln();	
			$this->SetFont('Arial','B',10);
			//$this->Cell(0,10,$rif,0,0,'C');

			$this->Cell(0,10,'CLASIFICADOR POR PARTIDAS DE CHEQUE EMITIDO',0,0,'C');
            $this->ln(5);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'PARTIDA',			0,0,'L',1);
			$this->Cell(60,6,		     'CODIGO PROYECTO',			0,0,'L',1);
			$this->Cell(60,6,		     'PROYECTO',			0,0,'L',1);
			$this->Cell(60,		    	 'TOTAL',			0,0,'L',1);
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
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(135,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(175) ;
			$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);
		}
	}
	//************************************************************************
/*	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(auto,50);
	
		$pdf->SetFont('Times','B',8);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(0) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(60,6,		     'PARTIDA',			1,0,'L',1);
		$pdf->Cell(60,6,		     'CODIGO PROYECTO',			1,0,'L',1);
		$pdf->Cell(60,6,		     'PROYECTO',			1,0,'L',1);
		$pdf->Cell(60,		    	 'TOTAL',			1,0,'L',1);	*/
	
}
else
{
require('../../../../utilidades/fpdf153/fpdf.php');
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
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
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
		$pdf->Cell(190,		6,"No se Encontraron los datos" ,			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}
?>



