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
						cuenta_contable_contabilidad.id_cuenta_suma,
						saldo_contable.debe,
						saldo_contable.haber,
						saldo_contable.saldo_inicio,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						naturaleza_cuenta.codigo,
						cuenta_contable_contabilidad.columna
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
							naturaleza_cuenta.codigo='I'
							OR
							naturaleza_cuenta.codigo='G'
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
					//Cabecera de p�gina
					function Header()
					{	
						global $fecha;
						$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
						$this->SetFont('times','B',7);
						$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
						$this->Ln(4);			
						$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
						$this->Ln(4);			
						$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
						$this->Ln(6);
						$this->SetFont('Times','B',9);
						$this->Cell(0,10,'ESTADO DE GANANCIAS Y PERDIDAS'."  "."AL"." ".$fecha ,0,0,'C');
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
					//Pie de p�gina
					function Footer()
					{
						//Posici�n: a 2,5 cm del final
						$this->SetY(-15);
						//Times italic 8
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
	$pdf->SetFont('Times','',6);
	$pdf->SetFillColor(255);
	
	
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
	//-
	$med2=strlen($row->fields("sald_inicio"));
	$med2=$med2-2;
	$saldo_inicio=substr($row->fields("saldo_inicio"),1,$med2);
	$saldo_vector=split(",",$saldo_inicio);
	//
	$conter=0;
	//$mes=date("m");
	//$mes=$mes-1;
	$debe_total=0;
	$haber_total=0;
	$total_cuenta_debe_haber="";
	$cuenta_sumas="";
		while($conter!=$mes)
		{
			$debe_total=$debe_total+$debe_vector[$conter];
			$haber_total=$haber_total+$haber_vector[$conter];
			
			$conter++;
		}
		$total_cuenta_debe_haber=$total_cuenta_debe_haber+$saldo_vector[0];

		if($row->fields("codigo")=='G   ')
		{
			$total_cuenta_debe_haber=$total_cuenta_debe_haber+($debe_total-$haber_total);
			
		}
		else
		if($row->fields("codigo")=='I   ')
		{
			$total_cuenta_debe_haber=$total_cuenta_debe_haber+($haber_total-$debe_total);
			$total_cuenta_debe_haber=$total_cuenta_debe_haber*(-1);
		}
//-
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	$columna=$row->fields("columna");

	$cta_principal=substr($row->fields("cuenta_contable"),3);
	$cta_suma=$row->fields("id_cuenta_suma");
	$valores=4;
	$cuenta_contables=$row->fields("cuenta_contable");
	//si es detalle
	if($tipos=='d')
	{
		$pdf->SetFont('Times','',6);

		$total_uno=$total_cuenta_debe_haber;
		$total_cuenta_debe_haber=number_format($total_cuenta_debe_haber,2,',','.');
		$total_uno=number_format($total_uno,2,',','.');
		$sub_total_cuenta="";
		$total_cuenta="";
	}else//si es total
	if($tipos=='t')
	{
		$cuenta_contables="";
		$pdf->SetFont('Times','B',6);
	}
	//va por la segunda columna
	if($columna=='2')
	{
		$sub_total_cuenta=$total_cuenta_debe_haber;
		$total_uno="";
		$total_cuenta="";
		$sub_total_cuenta=number_format($sub_total_cuenta,2,',','.');

	}else
	//va por la tercera columna
	if($columna=='3')
	{
		$total_cuenta=$total_cuenta_debe_haber;
		$sub_total_cuenta="";
		$total_uno="";
		$total_cuenta=number_format($total_cuenta,2,',','.');
	}
	
	
	
//$pdf->Cell(30,6,$total_cuenta_debe_haber."+".$saldo_vector[0],					0,0,'L',1);
		
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
	if($total_cuenta_debe_haber!="0,00")
	{	
		$pdf->Cell(30,6,				$cuenta_contables,					0,0,'L',1);
		$pdf->Cell(80,6,				substr(strtoupper($row->fields("nombre")),0,50),					0,0,'L',1);
		$pdf->Cell(25,6,				$total_uno,	0,0,'R',1);
		$pdf->Cell(25,6,				$sub_total_cuenta,	0,0,'R',1);
		$pdf->Cell(25,6,				$total_cuenta,	0,0,'R',1);
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
		//Cabecera de p�gina
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',11);
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
			}
		//Pie de p�gina
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
