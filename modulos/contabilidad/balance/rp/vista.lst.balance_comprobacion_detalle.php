<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
require('../../../../utilidades/fpdf153/code128.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//inicializando valores
/*$alpha1=0;
$alpha2=0;
$alpha3=0;
$alpha4=0;
$alpha5=0;*/
//
$where="where
							movimientos_contables.estatus='1'
						and
							ano_comprobante='2012'";
/*if(isset($_GET[desde]))
{
	$fecha=$_GET[desde];
	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where.="AND movimientos_contables.fecha_comprobante>='$fecha'";	
}
if(isset($_GET[hasta]))
{
	$fecha_hasta=$_GET[hasta];
	$where.="AND movimientos_contables.fecha_comprobante <= '$fecha_hasta'";	
}*/
$sql_cuenta="select
					cuenta_contable_contabilidad.id as id_cuenta, 
					cuenta_contable_contabilidad.cuenta_contable,
					cuenta_contable_contabilidad.nombre,
					cuenta_contable_contabilidad.tipo,
					cuenta_contable_contabilidad.id_naturaleza_cuenta,
					naturaleza_cuenta.codigo  AS codigo,
					cuenta_contable_contabilidad.id_cuenta_suma
					from
							cuenta_contable_contabilidad
					inner join
							naturaleza_cuenta
					on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
					order by
							cuenta_contable_contabilidad.cuenta_contable";
$row=& $conn->Execute($sql_cuenta);
if(!$row->EOF)
{
	//************************************************************************
				class PDF extends PDF_Code128
				{
					//Cabecera de página
					function Header()
					{	
						global $fecha;
						$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
						$this->SetFont('times','B',7);
						$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
						$this->Ln(4);			
						$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
						$this->Ln(4);			
						$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
						$this->Ln(6);
						$this->SetFont('Times','B',9);
						$this->Cell(0,10,'BALANCE DE COMPROBACIÓN'."  "."AL"." ".$fecha ,0,0,'C');
						$this->Ln(10);
						$this->SetFont('Times','B',6);
						$this->SetLineWidth(0.3);
						$this->SetFillColor(175) ;
						$this->SetTextColor(0);
						$this->Cell(25,6,		     'CUENTA CONTABLE',			0,0,'L',1);
						$this->Cell(50,6,		     'NOMBRE',			0,0,'L',1);
						$this->Cell(23,6,			 'SALDO ANTERIOR',		0,0,'L',1);
						$this->Cell(23,6,		     'DEBITO MES',	0,0,'R',1);
						$this->Cell(23,6,		     'CREDITO MES',	0,0,'R',1);
						$this->Cell(23,6,		     'SALDO MES',	0,0,'R',1);
						$this->Cell(23,6,		     'SALDO ACTUAL',	0,0,'R',1);
						$this->Ln();
					}
					//Pie de página
					function Footer()
					{
						//Posición: a 2,5 cm del final
						$this->SetY(-15);
						//Times italic 8
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
	$pdf->SetFont('Times','',6);
	$pdf->SetFillColor(255);
while (!$row->EOF) 
	{	
		//CONSULTO NUEVAMENTE PARA OBTENER LOS VALORES DE LOS SALDOS
		$id_cuenta=$row->fields("id_cuenta");
		$id_cuenta_suma=$row->fields("id_cuenta_suma");
		$sql_cuenta2="select
						sum(movimientos_contables.monto_debito) as debe,
						sum(movimientos_contables.monto_credito) as haber
						from
							cuenta_contable_contabilidad
						inner join
							naturaleza_cuenta
						on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
						inner join
							movimientos_contables
						on
							movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
						$where
						and
							cuenta_contable_contabilidad.id='$id_cuenta'
						group by
						cuenta_contable_contabilidad.id, 
						cuenta_contable_contabilidad.cuenta_contable,
						cuenta_contable_contabilidad.nombre,
						cuenta_contable_contabilidad.tipo,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						naturaleza_cuenta.codigo
						
						order by
							cuenta_contable_contabilidad.cuenta_contable
						";
						$row2=& $conn->Execute($sql_cuenta2);
						//
						
						if($row2->fields("debe")=="")
							$debe='0';
						else
							$debe=$row2->fields("debe");
						if($row2->fields("haber")=="")		
							$haber='0';
						else	
							$haber=$row2->fields("haber");
							
					   //si es cuenta total 
						if($row->fields("tipo")=='d')	
						{
									if(($debe!="0")or($haber!="0"))
									{
										$valor_comparacion="1";
									}
						}	
						//si es cuenta total 
						if($row->fields("tipo")=='t')
						{
							$sql_total="SELECT
											sum(movimientos_contables.monto_debito) as debe,
											sum(movimientos_contables.monto_credito) as haber
												from
													cuenta_contable_contabilidad
												inner join
													naturaleza_cuenta
												on
													cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
												inner join
													movimientos_contables
												on
													movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable	
											
											$where
											and
												cuenta_contable_contabilidad.id_cuenta_suma='$id_cuenta'
											
											";
								$row_suma=& $conn->Execute($sql_total);
								if(!$row_suma->EOF)
								{
									$debe=$row_suma->fields("debe");
									$haber=$row_suma->fields("haber");
									if(($debe!="0")or($haber!="0"))
									{
										$valor_comparacion="1";
									}
								}
				
											
						}
						
						//si es encabezado
						if($row->fields("tipo")=='e')
						{
							
							$cuenta_titulo=$row->fields("cuenta_contable");
							//rutina para q no aparezcan cuentas innecesarias
							$sql_total="SELECT
											sum(movimientos_contables.monto_debito) as debe,
											sum(movimientos_contables.monto_credito) as haber
												from
													cuenta_contable_contabilidad
												inner join
													naturaleza_cuenta
												on
													cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
												inner join
													movimientos_contables
												on
													movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable	
											
											$where
												and
													cuenta_contable_contabilidad.cuenta_contable like'%$cuenta_titulo%' 
												and
													cuenta_contable_contabilidad.tipo='d'	
											";
								$row_suma=& $conn->Execute($sql_total);
								if(!$row_suma->EOF)
								{
									$debe=$row_suma->fields("debe");
									$haber=$row_suma->fields("haber");
									if(($debe!="0")or($haber!="0"))
									{
										$valor_comparacion="1";
										
									}
										$debe="";
										$haber="";
								}	
							//
						}
						//
						
						if($valor_comparacion=="1")
						{	
							$pdf->Cell(25,6,		     $row->fields("cuenta_contable"),			0,0,'L',1);
							$pdf->Cell(50,6,		     strtoupper($row->fields("nombre")),			0,0,'L',1);
							$pdf->Cell(23,6,			 "0,00",		0,0,'L',1);
							$pdf->Cell(23,6,		     $debe,	0,0,'R',1);
							$pdf->Cell(23,6,		     $haber,	0,0,'R',1);
							$pdf->Cell(23,6,		     "0,00",	0,0,'R',1);
							$pdf->Cell(23,6,		      "0,00",	0,0,'R',1);	
							$pdf->Ln();
							$valor_comparacion=0;
						}	
		//					
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
						
			$this->SetFont('Times','B',11);
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
	$pdf->SetFont('Times','',10);
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