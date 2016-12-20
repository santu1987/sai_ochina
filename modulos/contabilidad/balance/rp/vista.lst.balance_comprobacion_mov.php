<?php
session_start();
ini_set("memory_limit","20M");
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
	//$where="where	movimiento_contable.ano_comprobante=$ayo";	
}
else
{
		$where="";	
}
$sql_cuenta="select
						cuenta_contable_contabilidad.id as id_cuenta, 
						cuenta_contable_contabilidad.cuenta_contable,
						cuenta_contable_contabilidad.nombre,
						cuenta_contable_contabilidad.tipo,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						naturaleza_cuenta.codigo  AS codigo
						from
						cuenta_contable_contabilidad
						inner join
							naturaleza_cuenta
						on
							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
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
						$this->SetFont('Arial','B',9);
						$this->Cell(0,10,'BALANCE DE COMPROBACIÓN'."  "."AL"." ".$fecha ,0,0,'C');
						$this->Ln(10);
						$this->SetFont('Times','B',6);
						$this->SetLineWidth(0.3);
						$this->SetFillColor(175) ;
						$this->SetTextColor(0);
						$this->Cell(25,6,		     'CUENTA CONTABLE',			0,0,'L',1);
						$this->Cell(50,6,		     'NOMBRE',			0,0,'L',1);
						$this->Cell(23,6,			 'SALDO ANTERIOR',		0,0,'C',1);
						$this->Cell(23,6,		     'DEBITO MES',	0,0,'C',1);
						$this->Cell(23,6,		     'CREDITO MES',	0,0,'C',1);
						$this->Cell(23,6,		     'SALDO MES',	0,0,'C',1);
						$this->Cell(23,6,		     'SALDO ACTUAL',	0,0,'C',1);
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
	$pdf->SetFont('arial','',6);
	$pdf->SetFillColor(255);
	
	
	while (!$row->EOF) 
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	$cuenta_cont=$row->fields("cuenta_contable");
	///////////////////////////////
	$sql_mov="
				SELECT
						sum(monto_debito) as debe,
						sum(monto_credito) as haber
				FROM
						movimientos_contables
				where
						cuenta_contable='$cuenta_cont'
				and		
						movimientos_contables.ano_comprobante='$ayo'
				and		
						movimientos_contables.mes_comprobante='$mes'
												
	
	";
	$row_total=& $conn->Execute($sql_mov);
	
	/////////////////////////////////
	
	$debe=$row_total->fields("debe");
	$haber=$row_total->fields("haber");
	$saldo_inicio=0;
	/*$saldo_inicio=$row->fields("saldo_inicio");
	$saldo_vector=split(",",$saldo_inicio);
	*///-
	$conter=0;
	$mes_ant=$mes;
	$mes_ant=$mes_ant-1;
	//$mes=date("m");
	$debe_total=0;
	$haber_total=0;
	$total_cuenta_debe_haber="";
	$cuenta_sumas="";
	$mes23=$mes2-1;

		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$saldo_mes=$debe-$haber;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$saldo_mes=$haber-$debe;
		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$saldo_mes=$haber-$debe;
		}
	// valor de verificacion para ver si el mes tiene saldo
	$valor_comparacion=$debe+$haber;	
	//	$saldo_mes=$debe_total2-$haber_total2;
		//FALTA CC,CO 
	//-	SALDO ACTUAL
		
	//-
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	if($tipos=='t')
	{
  		$valores=5;
		$valor_inicial=2;
	}
	else
	{
		$valores=4;
		$valor_inicial=0;
	}
	$debito_mes=number_format($debe,2,',','.');
	$credito_mes=number_format($haber,2,',','.');
	$saldo_mes_total=number_format($saldo_mes,2,',','.');
	$saldo_actual_total=number_format($saldo_actual,2,',','.');
	$saldo_ant_mes==number_format($saldo_inicio2,2,',','.');
//////////////////////////////////////////////////
/*	$debe = split(",",$row->fields("debe"));
	$haber= split(",",$row->fields("haber"));
*/	//	
/*if(($valor_comparacion=='0')&&($tipos=='e'))
	{
		$valor_comparacion='1';
		$saldo_inicio2="";
		$debito_mes="";
		$credito_mes="";
		$saldo_mes_total="";
		$saldo_actual_total="";
	}*/
/////////////////////////SCRIPT PARA Q NO APAREZCAN CUENTAS DE TITULOS INNECESARIAS////////////////////
//
if($tipos=='e')
{
	$cuenta_titulo=$row->fields("cuenta_contable");
	//$valor_long=strlen($cuenta_titulo);
	$sql_titulos="select
						cuenta_contable_contabilidad.id as id_cuenta, 
						cuenta_contable_contabilidad.cuenta_contable,
						cuenta_contable_contabilidad.nombre,
						cuenta_contable_contabilidad.tipo,
						cuenta_contable_contabilidad.id_naturaleza_cuenta,
						naturaleza_cuenta.codigo  AS codigo
						from
						cuenta_contable_contabilidad
						
						inner join
							naturaleza_cuenta
						on

							cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id
						WHERE
							cuenta_contable_contabilidad.cuenta_contable like'%$cuenta_titulo%' 
						and
							cuenta_contable_contabilidad.tipo='d'	
							
						order by
						cuenta_contable_contabilidad.cuenta_contable";
					
	$row_titulo=& $conn->Execute($sql_titulos);
	while(!$row_titulo->EOF)
	{					
			$cuenta_cont=$row_titulo->fields("cuenta_contable");

		$sql_mov="
				SELECT
						sum(monto_debito) as debe,
						sum(monto_credito) as haber
				FROM
						movimientos_contables
				where
						cuenta_contable='$cuenta_cont'	
				and		
						movimientos_contables.ano_comprobante='$ayo'
				and		
						movimientos_contables.mes_comprobante='$mes'			
	
	";
	$row_total=& $conn->Execute($sql_mov);
	
	/////////////////////////////////
	
	$debe6=$row_total->fields("debe");
	$haber6=$row_total->fields("haber");
	$saldo_inicio=0;
		if((($debe6!=0)||($haber6!=0))||(($debe6!=0)&&($haber6!=0)))
		{
				breake;
				$valor_comparacion=$debe6+$haber6;
				$saldo_inicio2="";
				$debito_mes="";
				$credito_mes="";
				$saldo_mes_total="";
				$saldo_actual_total="";
				$cuenta_cont="";
		}
		//
		$row_titulo->MoveNext();
	}
}else
$suma=0;
//
////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if($valor_comparacion!=0)
{
		$pdf->Ln($valor_inicial);
		$pdf->Cell(25,6,				$cuenta_cont,					0,0,'L',1);
		if(($tipos=='e')||($tipos=='t'))
		{
			$pdf->SetFont('arial','B',6);
		}
		else
		{
			$pdf->SetFont('arial','',6);
		}
		$pdf->Cell(50,6,substr(strtoupper($row->fields("nombre")),0,30),					0,0,'L',1);
		$pdf->SetFont('arial','',6);
		$pdf->Cell(23,6,				"0,00",0,0,'L',1);
		$pdf->Cell(23,6,				$debito_mes,	0,0,'L',1);
		$pdf->Cell(23,6,				$credito_mes,	0,0,'L',1);
		$pdf->Cell(23,6,				$saldo_mes_total,	0,0,'L',1);
		$pdf->Cell(23,6,				"0,00"	,	0,0,'L',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$haber_total2=0;
	$debe_total2=0;
	$saldo_actual=0;
	$saldo_mes=0;
	$total_cuenta_debe_haber=0;
	
			$pdf->Ln($valores);
}
	$suma=0;
	$valor_comparacion=0;
	
	// en caso de q sea saldo inicial
	if($cuenta_cont=='3999999999')
	{
				$pdf->Cell(23,6,				$saldo_inicio2."hola",0,0,'R',1);
		

	}
	if(($debito_mes=="0,00")&& ($credito_mes=="0,00")&&($saldo_inicio2!="0,00")) 
	{
		$pdf->Ln($valor_inicial);
		$pdf->Cell(25,6,				$cuenta_cont,					0,0,'L',1);
		if(($tipos=='e')||($tipos=='t'))
		{
			$pdf->SetFont('arial','B',6);
		}
		else
		{
			$pdf->SetFont('arial','',6);
		}
		$pdf->Cell(50,6,				substr(strtoupper($row->fields("nombre")),0,30),					0,0,'L',1);
		$pdf->SetFont('arial','',6);
		$pdf->Cell(23,6,				"0,00",0,0,'R',1);
		$pdf->Cell(23,6,				$debito_mes."",	0,0,'R',1);
		$pdf->Cell(23,6,				$credito_mes,	0,0,'R',1);
		$pdf->Cell(23,6,				$saldo_mes_total,	0,0,'R',1);
		$pdf->Cell(23,6,				"0,00"	,	0,0,'R',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$haber_total2=0;
	$debe_total2=0;
	$saldo_actual=0;
	$saldo_mes=0;
	$total_cuenta_debe_haber=0;
	$pdf->Ln($valores);
	}
	/////////////////////////////////
	
	
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