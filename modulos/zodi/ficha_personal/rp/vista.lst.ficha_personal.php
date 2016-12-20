<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
require('../../../../utilidades/draw.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
/////////////////////////////////////////////////////////////////
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
/*$sql_personas="
							SELECT  distinct
									integracion_contable.cuenta_contable,
									integracion_contable.numero_comprobante,
									integracion_contable.secuencia,
									integracion_contable.descripcion,
									integracion_contable.referencia,
									integracion_contable.debito_credito,
									integracion_contable.monto_debito,
									integracion_contable.monto_credito,
									integracion_contable.fecha_comprobante ,
									integracion_contable.id_tipo_comprobante,
									tipo_comprobante.nombre as tipo,
									tipo_comprobante.codigo_tipo_comprobante,
									cuenta_contable_contabilidad.nombre as descripcion_cuenta,
									integracion_contable.id_auxiliar,
									integracion_contable.id_utilizacion_fondos,
									integracion_contable.fecha_comprobante 
							from
									 integracion_contable 
									 
							INNER JOIN
											tipo_comprobante
									on
										integracion_contable.id_tipo_comprobante=tipo_comprobante.id											 
							INNER JOIN
										cuenta_contable_contabilidad
								on
									cuenta_contable_contabilidad.cuenta_contable=integracion_contable.cuenta_contable		
							$where
							order by
									
									integracion_contable.numero_comprobante,integracion_contable.secuencia";
$row=& $conn->Execute($sql_personas);
if(!$row->EOF)
{*/
//************************************************************************
			class PDF extends PDF_Code128
			{
				//Cabecera de página
				function Header()
				{global $numero_comprobante;
					global $fecha_comprobante;global $tipo_comprobante;	global $codigo_tipo_comprobante;	
					
					$this->Image("../../../../imagenes/logos/logo_zodi.jpg",10,10,25);
					$this->SetFont('times','B',8);
					$this->Cell(0,10,'FICHA PERSONAL',0,0,'C');
					//$this->Rect(100, 10, 40, 20, 'DF', $style4, array(220, 220, 200));
					//$this->Rect(100,20, 20, 20, 'D', array('all' => $style3));

					$this->Ln(24);
								
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
				/*$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');*/
				$this->Cell(120,3,date("d/m/Y h:m:s"),0,0,'R');					
				$this->Ln();
				$this->SetFillColor(0);
				//$this->Code128(88,285,strtoupper($_SESSION['usuario']),40,6);
			}
	}
			//************************************************************************
		
						$pdf=new PDF();
						$pdf->AliasNbPages();
						$pdf->AddFont('barcode');
						$pdf->AddPage();
						$pdf->SetFont('arial','',6);
						$pdf->SetFillColor(255);
						//
						$acu_deb=0;
						$acu_cred=0;
						$contar=0;
				
						
									
					$pdf->SetFont('Arial','B',6);
					$pdf->SetLineWidth(0.3);
					/*$pdf->SetFillColor(175) ;
					$pdf->SetTextColor(0);*/
					$pdf->Ln(6);
					$pdf->SetFont('times','B',8);
					//$pdf->Cell(0,10,'FICHA PERSONAL',0,0,'C');
					$pdf->ln(12);
					$conter=0;
					while($conter<20)	
						{	$conter++;
									switch($conter)
									{
										case 	1 :
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'NOMBRES',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case 	2:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'APELLIDOS',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;	
										case 	3:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'CÉDULA DE IDENTIDAD',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
									    break;
										case 	4:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'FECHA DE NACIMIENTO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
									    break;
										case 	5:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'GRUPO SANGUINEO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
									    break;	
										case 	6:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'ALERGICO A:',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
									    break;
										case 	7:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'TRATAMIENTO MÉDICO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
									    break;
										case    8:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'DIR. DOMICILIARIA',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    9:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'N° TLF CELULAR PERSONAL',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    10:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'N° TLF  EMERGENCIA',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    11:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'DIR. FAMILIAR',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    12:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'DIR. FAMILIAR',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    13:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'TELÉFONO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    14:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'VEHÍCULO PERSONAL',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    15:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'MODELO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    16:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'MARCA',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    17:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'COLOR',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    18:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'PROFESIÓN U OFICO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    19:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'EMPRESA DONDE TRABAJA',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										case    20:
												$pdf->Cell(10,6,		     $conter,			1,0,'L',1);
												$pdf->Cell(60,6,		     'DIRECCIÓN DE TRABAJO',			1,0,'L',1);
												$pdf->Cell(100,6,			 'xxxxxxxxxxxxxxxxxx',		1,0,'L',1);
												$pdf->Ln(6);
										break;
										
										
									
									}
								
								
					}
					$pdf->Ln(6);
    $pdf->SetFont('Arial','B',16);					
	$pdf->Cell(10,6,		    "FAMILIA  N°(?):",			0,0,'L',1);
	$pdf->Ln(6);
    $pdf->SetFont('Arial','B',8);					
	$pdf->Cell(40,6,		    "Aclaratoria de la Firma",			0,0,'L',1);
	$pdf->Cell(40,6,		    "Firma",			0,0,'L',1);
	$pdf->Cell(40,6,		    "Huella índice Derecho",			0,0,'L',1);		
	$pdf->Cell(40,6,		    "Huella Pulgar Derecho",			0,0,'L',1);	
	$pdf->Ln(10);
	
	$pdf->SetFont('Arial','B',9);					
	$pdf->Cell(40,6, "NOTA: SE DEBE ELABORAR EXPEDIENTE POR GRUPO FAMILLIAR",			0,0,'L',1);
		
	$pdf->Output();	
/*}
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
*/
?>