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
if(isset($_GET[fecha]))
{
	$fecha=$_GET[fecha];
	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where="AND	saldo_contable.ano=$ayo";	
}

$sql_cuenta="
				select
						cuenta_contable_contabilidad.id as id_cuenta, 
						cuenta_contable_contabilidad.cuenta_contable,
						cuenta_contable_contabilidad.nombre,
						cuenta_contable_contabilidad.tipo,
						saldo_contable.debe,
						saldo_contable.haber,
						saldo_contable.saldo_inicio,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						cuenta_contable_contabilidad.columna,
						naturaleza_cuenta.codigo 
						from
						cuenta_contable_contabilidad
						inner join
							saldo_contable
						on
						cuenta_contable_contabilidad.id=saldo_contable.cuenta_contable
						inner join
							naturaleza_cuenta
						on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
						where
						(		
							naturaleza_cuenta.codigo='A'
							OR
							naturaleza_cuenta.codigo='P'
							OR
							naturaleza_cuenta.codigo='PAT'
							OR
							naturaleza_cuenta.codigo='CO'
						)	
						$where	
						order by
						cuenta_contable_contabilidad.cuenta_contable
";
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
						global $mes;
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
						$this->Cell(0,10,'BALANCE GENERAL'."  "."AL"." ".$fecha ,0,0,'C');
						$this->Ln(6);
						$this->SetFont('Times','B',6);
						$this->SetLineWidth(0.3);
						$this->SetFillColor(175) ;
						$this->SetTextColor(0);
						/*$this->Cell(30,6,		     'CUENTA CONTABLE',			0,0,'L',1);
						$this->Cell(60,6,		     'NOMBRE',			0,0,'L',1);
						$this->Cell(40,6,			 'DEBE',		0,0,'L',1);
						$this->Cell(50,6,		     'HABER',	0,0,'L',1);*/
						$this->Ln(6);
					
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
	
	$mes=$mes-1;
	while (!$row->EOF) 
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		
	$med=strlen($row->fields("debe"));
	$med=$med-2;
	$debe=substr($row->fields("debe"),1,$med);
	$debe_vector=split(",",$debe);
	
	$med2=strlen($row->fields("haber"));
	$med2=$med2-2;
	$haber=substr($row->fields("haber"),1,$med2);
	$haber_vector=split(",",$haber);
	//sald inicio
	//*-CORREGIR:que las cuentas sumen al saldo total...
	$med2=strlen($row->fields("saldo_inicio"));
	$med2=$med2-2;
	$saldo_inicial=substr($row->fields("saldo_inicio"),1,$med2);
	$saldo_vector=split(",",$saldo_inicial);
	//-
	$conter=0;
	//$mes=date("m");
	//$mes=$mes-1;
	$debe_total=0;
	$haber_total=0;
	$total_cuenta_debe_haber="";
	$cuenta_sumas="";
		while($conter<=$mes)
		{
			$debe_total=$debe_total+$debe_vector[$conter];
			$haber_total=$haber_total+$haber_vector[$conter];
			$conter++;
		}
		
		//SE SUMAN E TOA DE LOS MOVMIENTOS + SALDO INICIAL
		$total_cuenta_debe_haber=$total_cuenta_debe_haber+$saldo_vector[$mes];

		if(($row->fields("codigo")=='A   ')or($row->fields("codigo")=='CO  '))
		{
			$total_cuenta_debe_haber=$total_cuenta_debe_haber+($debe_total-$haber_total);
	    }
		else
		if(($row->fields("codigo")=='P   ')or($row->fields("codigo")=='PAT '))
		{
			$total_cuenta_debe_haber=$total_cuenta_debe_haber+($haber_total-$debe_total);
			$total_cuenta_debe_haber=$total_cuenta_debe_haber*(-1);
		}
			
		
		
		
		
	//-
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	$valores=4;
	//
	$cuenta_contable_numero=$row->fields("cuenta_contable");
	//
	$columna=$row->fields("columna");//porque= segun lo verificado solo las cuentas que tienen 9 caracateres son totales definitivos las cuentas totales cn mas de 9 caacteres son subtotales y deben ir en la primera columna mod:17/02/2012
	if(($tipos=='t')&&($columna=='2'))
	{
		//hay cuentas especificas que no deben  aparecer en la columna de totales a pesar de ser totales estas son sub totales y siempre seran las mismas q se muestran a continuación: mod:17/02/2012
		$cuenta_sumas=$total_cuenta_debe_haber;
		$total_cuenta_debe_haber="";
		$pdf->SetFont('Times','B',6);

		
		//
		//las cuentas contables de los totales no deben ser visbles en el reporte  mod-17/02/2012
		//$cuenta_contable_numero="";
		//
		$valores=5.5;
		$cuenta_sumas=number_format($cuenta_sumas,2,',','.');
		$total_total=$cuenta_sumas;
		$cuenta_contable_numero="";
	}else
	{
		$pdf->SetFont('Times','',6);
		$cuenta_sumas=" ";
		$total_cuenta_debe_haber=number_format($total_cuenta_debe_haber,2,',','.');
		$total_total=$total_cuenta_debe_haber;
	}
	//
		$acu_debe=$acu_debe+$total_cuenta_debe_haber;
		$acu_haber=$acu_haber+$cuenta_sumas;
		$cuenta_contable_numero=$cuenta_contable_numero;

	//////////////////////////////////////////////////opcion x si los usuarios desean q aparezca toda la info
		/*$contar_letra = strlen($row->fields("nombre"));
		$salto1 = ceil($contar_letra / 62);
			$coordena = ($salto1 * 6);
			$coordena1 = 6;
			$pdf->Cell(10,		$coordena,		$row->fields("cuenta_contable"),		0,0,'C',1);
			$y=$pdf->GetY();
			$pdf->MultiCell(77,		$coordena1,strtoupper($row->fields("nombre")),	0,			'TRL','L',1);
			$pdf->SetXY(97,$y);
			$pdf->MultiCell(20,$coordena,				$total_cuenta_debe_haber,	0,0,'L',1);
			$pdf->MultiCell(20,$coordena,				$cuenta_sumas,	0,0,'L',1);
			$x=$pdf->GetX();*/
////////////////////////////////////////////////////
		
		/*$pdf->Cell(30,4,				$row->fields("cuenta_contable"),					0,0,'L',1);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetXY($x+70,$y);
		$pdf->Cell(80,6,				strtoupper($row->fields("nombre")),					0,0,'L',1);
		$pdf->Cell(20,6,				$total_cuenta_debe_haber,	0,0,'L',1);
		$pdf->Cell(20,6,				$cuenta_sumas,	0,0,'L',1);*/
		//."---".$conter."/".$mes
    	if($total_total!="0,00")
		{
		$pdf->Cell(30,6,				$cuenta_contable_numero,					0,0,'L',1);
		$pdf->Cell(75,6,				substr(strtoupper($row->fields("nombre")),0,50),					0,0,'L',1);
		$pdf->Cell(30,6,				$total_cuenta_debe_haber,	0,0,'R',1);
		$pdf->Cell(30,6,				$cuenta_sumas,	0,0,'R',1);
	  	$pdf->Ln($valores);

	    }
		
		//$pdf->Cell(70,6,				strtoupper($row->fields("nombre").$mide),					0,0,'L',1);
		//$pdf->Cell(20,6,				$total_cuenta_debe_haber,	0,0,'L',1);
		//$pdf->Cell(20,6,				$cuenta_sumas,	0,0,'L',1);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	$row->MoveNext();

	$cuenta_sumas="";
	$total_cuenta_debe_haber="";
	$valores=4;
	}
	/*if($total_total=="0,00")
		{
				$pdf->SetFont('Times','',10);

			$pdf->Cell(190,		6,'',			'B',0,'C',1);
			$pdf->Ln(16);
			$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
			$pdf->Ln(16);
			$pdf->Cell(190,		6,'',			'B',0,'C',1);
		}*/
	/*$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Ln(6);
	$pdf->Cell(90,6,"TOTAL",				0,0,'L',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(40,6,				$acu_debe,	0,0,'L',1);
	$pdf->Cell(50,6,				$acu_haber,				0,0,'L',1);*/

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
