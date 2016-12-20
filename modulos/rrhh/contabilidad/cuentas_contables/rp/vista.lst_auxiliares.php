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
$Sql="
		SELECT 
				*
			FROM 
				cuenta_contable_contabilidad 
			INNER JOIN 
				organismo 
			ON 
				cuenta_contable_contabilidad.id_organismo = organismo.id_organismo
			order by
				cuenta_contable	
				";
$row=& $conn->Execute($Sql);

			
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'AUXILIARES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(30,6,		     'CUENTA ',			0,0,'L',1);
			$this->Cell(40,6,		     'NOMBRE',			0,0,'L',1);
			$this->Cell(10,6,			 'TIPO',		0,0,'L',1);
			$this->Cell(30,6,		     'SUMA EN',	0,0,'L',1);
			$this->Cell(10,6,		     'AX',	0,0,'L',1);
			$this->Cell(10,6,		     'CC',	0,0,'L',1);
			$this->Cell(10,6,		     'UB',	0,0,'L',1);
			$this->Cell(10,6,		     'UF',	0,0,'L',1);
			$this->Cell(30,6,		     'CTA.PRESUPUESTO',	0,0,'L',1);
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
			$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(88,285,strtoupper($_SESSION['usuario']),40,6);
			}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		$cuenta_presupuesto="";
		$cuenta_suma="";
		$suma=$row->fields("id_cuenta_suma");
		if(($suma!='0')&&($suma!=''))
		{
			$sql_suma="
						select 
								cuenta_contable
								from
										cuenta_contable_contabilidad
								INNER JOIN 
									organismo 
								ON 
									cuenta_contable_contabilidad.id_organismo = organismo.id_organismo		
								where
										cuenta_contable_contabilidad.id='$suma'		
			";
			$row_suma=& $conn->Execute($sql_suma);
			$cuenta_suma=$row_suma->fields("cuenta_contable");
		}
		$presupuesto=$row->fields("id_cuenta_presupuesto");
		if(($presupuesto!='0')&&($presupuesto!=''))
		{
			$sql_presupuesto="
						select 
								cuenta_contable
								from
										cuenta_contable_contabilidad
								INNER JOIN 
									organismo 
								ON 
									cuenta_contable_contabilidad.id_organismo = organismo.id_organismo		
								where
										cuenta_contable_contabilidad.id='$presupuesto'		
			";
			$row_presupuesto=& $conn->Execute($sql_presupuesto);
			$cuenta_presupuesto=$row_presupuesto->fields("cuenta_contable");
		}
		 	 if($row->fields("requiere_auxiliar")=='t')
			 	$auxiliar_r="SI";
			else
				$auxiliar_r="NO";	
			if($row->fields("requiere_proyecto"))
			 	$proyecto_r="SI";
			else
				 	$proyecto_r="NO";
			if($row->fields("requiere_unidad_ejecutora"))
				$ejecutora_r="SI";
			else
				$ejecutora_r="NO";
			if($row->fields("requiere_utilizacion_fondos"))
				$utf_r="SI";
			else
			$utf_r="NO";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$pdf->Cell(30,6,		      $row->fields("cuenta_contable"),			0,0,'L',1);
			$pdf->Cell(40,6,		      strtoupper(substr($row->fields("nombre"),0,40)),			0,0,'L',1);
			$pdf->Cell(10,6,			  $row->fields("tipo"),		0,0,'L',1);
			$pdf->Cell(30,6,		      $cuenta_suma,0,0,'L',1);
			$pdf->Cell(10,6,		      $auxiliar_r,	0,0,'L',1);
			$pdf->Cell(10,6,		      $proyecto_r,	0,0,'L',1);
			$pdf->Cell(10,6,		      $ejecutora_r,	0,0,'L',1);
			$pdf->Cell(10,6,		      $utf_r,	0,0,'L',1);
			$pdf->Cell(30,6,		      $cuenta_presupuesto,	0,0,'L',1);
			$pdf->Ln(6);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$row->MoveNext();
	}
	$pdf->Output();
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
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
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
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>