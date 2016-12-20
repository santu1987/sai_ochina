<?php
//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
$CONF[identificacion]="impfindia";
require_once('../../../../../libreria.php');
require_once('../../../../../controles/conversion.class.php');
require_once('../../../../../controles/conexion_bd.class.php');
$conexion=new conexion_bd();
$conversion			=	new conversion();
$lnkbd['PGSQL']=$conexion->nuevo_link('PGSQL');
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//selecionando la tabla moneda
$Sql="SELECT * FROM \"CgMovaut\" WHERE \"Fecha-Com\"='".$conversion->date_to_datepgsql($_GET[fecha])."' ORDER BY \"TipCom\",\"Secuen-Com\"";
$resultado=$conexion->query('PGSQL',$Sql,$lnkbd['PGSQL']);
//************************************************************************
if ($conexion->recordCount('PGSQL',$resultado))
{ 
	require('../../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{		
			$this->Image("../../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../../imagenes/logos/logo_ochina_295x260.jpg",172,10,29);
						
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
			$this->Ln();				
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'REPORTE DE FIN DE DÍA '.$_GET[fecha],0,0,'C');
			$this->Ln(20);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(255) ;
			$this->SetFont('arial','B',9);
			
//			$this->Cell(15,	6,			'Sec',					1,0,'C',1);
			$this->Cell(15,	4,			'Tipo',					'B',0,'L',1);
			$this->Cell(25,	4,			'Numero',				'B',0,'L',1);
			$this->Cell(30,	4,			'Cuenta',				'B',0,'L',1);
			$this->Cell(60,	4,			'Descripción',		'B',0,'L',1);
			$this->Cell(30,	4,			'Debe',					'B',0,'R',1);
			$this->Cell(30,	4,			'Haber',				'B',0,'R',1);
						
			$this->Ln(5);
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(30,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(130,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[name]),0,0,'C');
			$this->Cell(30,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->AddFont('barcode');		
			/*$this->SetFont('barcode','',6);*/
			/*$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');*/}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('arial','',9);
	$pdf->SetFillColor(255) ;
	while ($row=$conexion->fetch_array('PGSQL',$resultado))
	{ 

//		$pdf->Cell(15,	6,			$row['Secuen-Com'],			1,0,'R',1);
		$pdf->Cell(15,	3,			$row['TipCom'],					0,0,'L',1);
		$pdf->Cell(25,	3,			$row['Numero-Com'],			0,0,'L',1);
		$pdf->Cell(30,	3,			$row['Cuenta'],					0,0,'L',1);
		$pdf->Cell(60,	3,			$row['Descri-Com'],				0,0,'L',1);
		
		$pdf->Cell(30,	3,			((($row['DebCre-Com']=='t'))?number_format(round($row['Monto-Com'],2), 2, '.', ','):''),				0,0,'R',1);
		$pdf->Cell(30,	3,			((($row['DebCre-Com']=='f'))?number_format(round($row['Monto-Com'],2), 2, '.', ','):''),				0,0,'R',1);
		
		$pdf->Ln(6);
	}
	$pdf->Output();
}
?>