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
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$nombre_banco =$_GET['nombre'];
$fecha=$_GET['fecha'];

$where="WHERE 1=1 ";
$where.=" AND banco_cuentas.ayo='$fecha' AND banco_cuentas.id_organismo=$_SESSION[id_organismo]";

if(isset($_GET['id_banco']))
{
	$id_banco=$_GET['id_banco'];
	if($id_banco!="")
	{
		$where.=" AND banco.id_banco=$id_banco";
	}
if(isset($_GET['cuenta']))
{
	$cuenta =$_GET['cuenta'];
	if($cuenta !="")
	{
	$where.="  AND banco_cuentas.cuenta_banco='$cuenta'
			  ";
	}		  
}
}
if(($id_banco!="")and($cuenta!=""))
{
	$tipo_reporte="banco_cuenta";
}else
if(($id_banco=="")and($cuenta==""))
{
	$tipo_reporte="vacio";
}else
if(($id_banco!="")and($cuenta==""))
{
	$tipo_reporte="banco";
}

$Sql="
		SELECT 
				banco_cuentas.id_cuenta_banco,
				organismo.id_organismo,
				banco.id_banco,
				banco.nombre as nombre,
				banco_cuentas.cuenta_banco as cuenta,
				banco_cuentas.cuenta_contable_banco,
				banco_cuentas.comentarios,
				banco_cuentas.estatus AS estatus,
				banco_cuentas.saldo_actual,
				banco_cuentas.fecha_apertura					
			FROM 
				banco_cuentas
			INNER JOIN 
				 banco
			ON 
				banco_cuentas.id_banco =banco.id_banco
			INNER JOIN 
				organismo 
			ON 
					banco_cuentas.id_organismo = organismo.id_organismo
			$where
			order by 
				banco.nombre,banco_cuentas.estatus
				";
$row=& $conn->Execute($Sql);
//$nombre=$row->fields("nombre");


//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
$cuenta_banco=$row->fields("cuenta");
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $nombre_banco;
			global $fecha;	
			global $tipo_reporte;
			global $cuenta;
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
			$this->Ln(4);	
			$this->SetFont('Arial','B',9);
			if($tipo_reporte=="banco")
			{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$this->Cell(0,10,'Listado de Bancos Cuentas',0,0,'C');
            $this->ln();
			$this->Cell(0,10,strtoupper($nombre_banco)." "."AÑO:" .$fecha,0,0,'C');
			$this->Ln(4);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(35,6,		     'FECHA DE APERTURA',			0,0,'L',1);
			$this->Cell(40,6,			 'CUENTA CONTABLE',		0,0,'L',1);
			$this->Cell(40,6,		     'ESTATUS',	0,0,'L',1);
			$this->Cell(35,6,		     'SALDO ACTUAL',	0,0,'L',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			}
		else
		if($tipo_reporte=="banco_cuenta")
		{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			 $this->ln();
			$this->Cell(0,10,strtoupper($nombre_banco)." ".$cuenta." "."AÑO:" .$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(45,6,		     'FECHA DE APERTURA',			0,0,'L',1);
			$this->Cell(45,6,			 'CUENTA CONTABLE',		0,0,'L',1);
      		$this->Cell(45,6,		     'ESTATUS',	0,0,'L',1);
			$this->Cell(45,6,		     'SALDO ACTUAL',	0,0,'L',1);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			}
			else
		if($tipo_reporte=="vacio")
		{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$this->Cell(0,10,'Listado de Bancos Cuentas',0,0,'C');
            $this->ln();
			$this->Cell(0,10,"AÑO:" .$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(30,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(30,6,		     'FECHA DE APERTURA',			0,0,'L',1);
			$this->Cell(30,6,			 'CUENTA CONTABLE',		0,0,'L',1);
      		$this->Cell(20,6,		     'ESTATUS',	0,0,'L',1);
			$this->Cell(35,6,		     'SALDO ACTUAL',	0,0,'L',1);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			}
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
		$fechas=substr($row->fields("fecha_apertura"),0,10);
		$dia=substr($row->fields("fecha_apertura"),8,2);
		$mes=substr($row->fields("fecha_apertura"),5,2);
		$ayo=substr($row->fields("fecha_apertura"),0,4);
		
		$fecha=$dia."/".$mes."/".$ayo;
		//--------------------------------
		if ($row->fields("estatus")=="1")
			$estatus="Activo";
		else
		if ($row->fields("estatus")=="2")
				$estatus="Inactivo";
		//---------------------------------
		if($tipo_reporte=="banco")
		{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
		$pdf->Cell(40,6,				$row->fields("cuenta"),					0,0,'L',1);
		$pdf->Cell(35,6,				$fecha,					0,0,'L',1);
		$pdf->Cell(40,6,				$row->fields("cuenta_contable_banco"),	0,0,'L',1);
		$pdf->Cell(40,6,				$estatus,				0,0,'L',1);
		$pdf->Cell(35,6,				number_format($row->fields("saldo_actual"),2,',','.'),0,0,'L',1);
		$pdf->Ln(6);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		if($tipo_reporte=="banco_cuenta")
		{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$pdf->Cell(45,6,				$fecha,					0,0,'L',1);
		$pdf->Cell(45,6,				$row->fields("cuenta_contable_banco"),	0,0,'L',1);
		$pdf->Cell(45,6,				$estatus,				0,0,'L',1);
		$pdf->Cell(45,6,				number_format($row->fields("saldo_actual"),2,',','.'),0,0,'L',1);
		$pdf->Ln(6);			
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else
		if($tipo_reporte=="vacio")
		{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		$pdf->Cell(40,6,				$row->fields("nombre"),					0,0,'L',1);
		$pdf->Cell(30,6,				$row->fields("cuenta"),					0,0,'L',1);
		$pdf->Cell(30,6,				$fecha,					0,0,'L',1);
		$pdf->Cell(30,6,				$row->fields("cuenta_contable_banco"),	0,0,'L',1);
		$pdf->Cell(20,6,				$estatus,				0,0,'L',1);
		$pdf->Cell(25,6,				number_format($row->fields("saldo_actual"),2,',','.'),0,0,'L',1);
		$pdf->Ln(6);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
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
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>