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

/*	$ayo=date("Y");
	$mes=date("m");
	$where="where	saldo_contable.ano=$ayo";	*/
if(isset($_GET[fecha]))
{
	$fecha=$_GET[fecha];
	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where="where	saldo_contable.ano=$ayo";	
	$where2="where	\"presupuesto_ejecutadoR\".ano=$ayo";	
	
}

$sql_cuenta="
				select
						distinct(cuenta_contable_contabilidad.id) as id_cuenta, 
						cuenta_contable_contabilidad.cuenta_contable,
						cuenta_contable_contabilidad.nombre,
						cuenta_contable_contabilidad.tipo,
						saldo_contable.debe,
						saldo_contable.haber,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						naturaleza_cuenta.codigo,
						clasificador_presupuestario.partida,
						clasificador_presupuestario.generica,
						clasificador_presupuestario.especifica,
						clasificador_presupuestario.subespecifica
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
						INNER JOIN
							clasificador_presupuestario
						on
							cuenta_contable_contabilidad.cuenta_contable=clasificador_presupuestario.cuenta_contable				
						$where	
						order by
						cuenta_contable_contabilidad.cuenta_contable
";

			
$row=& $conn->Execute($sql_cuenta);
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		
		{	
		global $fecha;
		$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
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
			$this->SetFont('Arial','B',8);
			$this->Cell(0,10,'SALDOS CUENTAS CONTABLES/PRESUPUESTARIAS al '." ".$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',7);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(45,6,		     'CUENTA CONTABLE',			0,0,'L',1);
			$this->Cell(30,6,			 'SALDO CUENTA',		0,0,'C',1);
			$this->Cell(50,6,		     'CUENTA PRESUPUESTARIA',			0,0,'C',1);
			$this->Cell(40,6,			 'SALDO CUENTA',		0,0,'C',1);
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
$partidas=$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//buscando saldos presupuestarios////
$partida=$row->fields("partida");
$generica=$row->fields("generica");
$especifica=$row->fields("especifica");
$sub_especifica=$row->fields("subespecifica");
$sql_presupuesto="select
						 monto_comprometido
				 from 
						   \"presupuesto_ejecutadoR\"
				 where
				 		 \"presupuesto_ejecutadoR\".partida='$partida'		 
				 and
				 		 \"presupuesto_ejecutadoR\".generica='$generica'	
				 and
				 		  \"presupuesto_ejecutadoR\".especifica='$especifica'
				 and
				 		   \"presupuesto_ejecutadoR\".sub_especifica='$sub_especifica'
				 and		   
					     (  \"presupuesto_ejecutadoR\".id_organismo=$_SESSION[id_organismo] )
						 			 	
					";		
$row_presupuesto=& $conn->Execute($sql_presupuesto);
if(!$row_presupuesto->EOF)
{
	$med=strlen($row_presupuesto->fields("monto_comprometido"));
	$med=$med-2;
	$comp=substr($row_presupuesto->fields("monto_comprometido"),1,$med);
	$comp_vector=split(",",$comp);
	$cuentas=0;
	while($cuentas!=$mes)
		{
			$comp_total=$comp_total+$comp_vector[$cuentas];
			$cuentas++;
		}
}else
$comp_total="0";
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
	$conter=0;
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
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$total_cuenta_debe_haber=$debe_total-$haber_total;
			
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
		}
		//FALTA CC,CO
//-
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	$valores=4;
	$saldo_mes=number_format($total_cuenta_debe_haber,2,',','.');
	$saldo_pres=number_format($comp_total,2,',','.');
	$acu1=$acu1+$total_cuenta_debe_haber;
	$acu2=$acu2+$comp_total;
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
	/*if($total_cuenta_debe_haber!="0,00")
	{*/	
		$pdf->Cell(45,6,				$row->fields("cuenta_contable"),					0,0,'L',1);
		$pdf->Cell(30,6,				$saldo_mes,					0,0,'R',1);
		$pdf->Cell(50,6,				$partida.".".$generica.".".$especifica.".".$sub_especifica,	0,0,'C',1);
	    $pdf->Cell(40,6,				$saldo_pres,	0,0,'R',1);

	//}	
		//$pdf->Cell(70,6,				strtoupper($row->fields("nombre").$mide),					0,0,'L',1);
		//$pdf->Cell(20,6,				$total_cuenta_debe_haber,	0,0,'L',1);
		//$pdf->Cell(20,6,				$cuenta_sumas,	0,0,'L',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	

	$row->MoveNext();
	$pdf->Ln($valores);

	$cuenta_sumas="";
	$total_cuenta_debe_haber="";
	$valores=4;
	}
	/*$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Ln(6);
	$pdf->Cell(90,6,"TOTAL",				0,0,'L',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);*/
		$pdf->SetFont('Times','B',7);
		$pdf->Cell(45,6,				"TOTALES:",					0,0,'L',1);
		$pdf->Cell(30,6,				number_format($acu1,2,',','.'),					0,0,'R',1);
		$pdf->Cell(50,6,				"",	0,0,'C',1);
	    $pdf->Cell(40,6,				number_format($acu2,2,',','.'),	0,0,'R',1);

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