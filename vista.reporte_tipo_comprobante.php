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
	SELECT DISTINCT
	    movimientos_contables.numero_comprobante,
		id_movimientos_contables,
		ano_comprobante,
		mes_comprobante,
		id_tipo_comprobante, 
		substr(movimientos_contables.numero_comprobante::varchar,8) as num_comp,
		movimientos_contables.secuencia,
		movimientos_contables.comentario,
		movimientos_contables.cuenta_contable,
		movimientos_contables.descripcion, 
		referencia,
		fecha_comprobante, 
		id_auxiliar,
		id_unidad_ejecutora,
		id_proyecto,
		id_utilizacion_fondos, 
		movimientos_contables.ultimo_usuario,
		movimientos_contables.id_organismo,
		ultima_modificacion,
		estatus,
		codigo_tipo_comprobante
  FROM movimientos_contables
  INNER JOIN
	tipo_comprobante	
  ON
	tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
 ORDER BY
 	tipo_comprobante.codigo_tipo_comprobante
	;
			
				";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de p�gina
		function Header()
		{	$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'TIPOS DE COMPROBANTES',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175);
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'TIPO',			0,0,'L',1);
			$this->Cell(40,6,		     'N COMPROBANTE',			0,0,'L',1);
			$this->Cell(40,6,		     'FECHA',			0,0,'L',1);
			$this->Cell(40,6,			 'DEBITO',		0,0,'L',1);
			$this->Cell(40,6,			 'CREDITO',		0,0,'L',1);
			$this->Cell(40,6,			 'DIF',		0,0,'L',1);
			$this->Ln(6);
		}
		//Pie de p�gina
		function Footer()
		{
			//Posici�n: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//N�mero de p�gina
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
			$numero_comprobante=$row->fields("numero_comprobante");
			$fecha_comp=$row->fields("fecha_comprobante");
			$tipo_comprobante=$row->fields("id_tipo_comprobante");
			$sql_sumas=" SELECT
							SUM(monto_debito) as debe,
							SUM(monto_credito) as haber
							
						from
							movimientos_contables
						where movimientos_contables.numero_comprobante='$numero_comprobante'
						and movimientos_contables.estatus!='3'
						"
						;
											
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
					
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
					$resta=$row_sumas->fields("debe")-$row_sumas->fields("haber");
					$resta=number_format($resta,2,',','.');
			}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		$pdf->Cell(40,6,				$row->fields("codigo_tipo_comprobante"),0,40),					0,0,'L',1);	
		$pdf->Cell(40,6,				$row->fields("num_comp"),					0,0,'L',1);
		$pdf->Cell(40,6,				$fecha_comp,					0,0,'L',1);
		$pdf->Cell(40,6,				$debe,	0,0,'L',1);
		$pdf->Cell(40,6,				$haber,	0,0,'L',1);
		$pdf->Cell(40,6,				$resta,	0,0,'L',1);
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
		//Cabecera de p�gina
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
			$this->Ln();
				/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de p�gina
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