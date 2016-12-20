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
//$nombre_banco =$_GET['nombre'];
$where="WHERE 1=1  AND banco.estatus='1' AND banco_cuentas.estatus='1'";
if(isset($_GET['id_banco']) and ($_GET['id_banco']!=""))
{
	$id_banco =$_GET['id_banco'];
	$where.=" AND banco.id_banco=$id_banco
	";
	
}
if(isset($_GET['n_cuenta']) and ($_GET['n_cuenta']!=""))
{
	$ncuenta=$_GET['n_cuenta'];
	if($ncuenta!="")
	$where.="AND chequeras.cuenta='$ncuenta'";
}
if(isset($_GET['fecha']) and ($_GET['fecha']!=""))
{
	$fecha=$_GET['fecha'];
	if($fecha!="")
	$where.="AND banco_cuentas.ayo='$fecha'";
}
if(($id_banco!="")and($ncuenta!=""))
{
	$tipo_reporte="banco_cuenta";
}else
if(($id_banco=="")and($n_cuenta==""))
{
	$tipo_reporte="vacio";
}else
if(($id_banco!="")and($n_cuenta==""))
{
	$tipo_reporte="banco";
}
$Sql="
		SELECT 
				chequeras.id_chequeras,
				chequeras.id_organismo,
				banco.id_banco,
				banco.nombre,
				chequeras.cuenta,
				chequeras.secuencia AS nchequera,
				chequeras.primer_cheque,
				chequeras.ultimo_emitido,
				chequeras.cantidad_cheques,
				chequeras.cantidad_emitidos,
				chequeras.estatus,
				chequeras.comentarios		
			FROM 
				chequeras
			INNER JOIN 
				banco
			ON 
				chequeras.id_banco = banco.id_banco
			INNER JOIN 
				banco_cuentas
			ON 
				chequeras.cuenta = banco_cuentas.cuenta_banco	
			INNER JOIN 
				organismo 
			ON 
				chequeras.id_organismo = organismo.id_organismo
			$where
			ORDER BY
				banco.nombre,chequeras.estatus,chequeras.secuencia 			
				";
$row=& $conn->Execute($Sql);

$nombre_banco=$row->fields("nombre");
$cuenta_banco=$row->fields("cuenta");
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $nombre_banco;	
			global $fecha;
			global $tipo_reporte;
			global $cuenta_banco;
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
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
				if($tipo_reporte=="vacio")
				{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$this->Cell(0,10,'Listado de Chequeras',0,0,'C');
           	$this->ln();
			$this->Cell(0,10,'AÑO:'.$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$pdf->SetFillColor(175) ;
			$pdf->SetTextColor(0);
			$this->Cell(30,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(20,6,		     'Nº',			0,0,'C',1);
			$this->Cell(30,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(30,6,			 'PRIMER CHEQUE',		0,0,'L',1);
			$this->Cell(30,6,		     'ULTIMO EMITIDO',	0,0,'L',1);
			$this->Cell(30,6,		     'CANTIDAD/CHEQUES',	0,0,'L',1);
			$this->Cell(20,6,		     'ESTATUS',	0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
				}
				if($tipo_reporte=="banco")
				{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$this->Cell(0,10,'Listado de Chequeras',0,0,'C');
            $this->ln();
			$this->Cell(0,10,strtoupper($nombre_banco),0,0,'C');
			$this->ln();
			$this->Cell(0,10,'AÑO:'.$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(20,6,		     'Nº ',			0,0,'C',1);
			$this->Cell(40,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(40,6,			 'PRIMER CHEQUE',		0,0,'L',1);
			$this->Cell(30,6,		     'ULTIMO EMITIDO',	0,0,'L',1);
			$this->Cell(30,6,		     'CANTIDAD/CHEQUES',	0,0,'L',1);
			$this->Cell(20,6,		     'ESTATUS',	0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
				}
			if($tipo_reporte=="banco_cuenta")
				{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$this->Cell(0,10,'Listado de Chequeras',0,0,'C');
            $this->ln();
			$this->Cell(0,10,strtoupper($nombre_banco),0,0,'C');
			$this->ln();
			$this->Cell(0,10,strtoupper($cuenta_banco),0,0,'C');
			$this->ln();
			$this->Cell(0,10,'AÑO:'.$fecha,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(20,6,		     'Nº',			0,0,'C',1);
			$this->Cell(40,6,			 'PRIMER CHEQUE',		0,0,'L',1);
			$this->Cell(40,6,		     'ULTIMO EMITIDO',	0,0,'L',1);
			$this->Cell(40,6,		     'CANTIDAD/CHEQUES',	0,0,'L',1);
			$this->Cell(40,6,		     'ESTATUS',	0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
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
			$this->SetFillColor(255);
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
		$primer=strlen($row->fields("primer_cheque"));
		$n_cheque=$row->fields("primer_cheque");
						switch($primer)
									{
										case 1:
										$n_cheque='00000'.$n_cheque;
										break;
										case 2:
										$n_cheque='0000'.$n_cheque;
										break;
										case 3:
										$n_cheque='000'.$n_cheque;
										break;
										case 4:
										$n_cheque='00'.$n_cheque;
										break;
										case 5:
										$n_cheque='0'.$n_cheque;
										break;
										case 6:
										$n_cheque=$n_cheque;
										break;
										
									}
		$ultimo=strlen($row->fields("ultimo_emitido"));
		$cheque_ultimo=$row->fields("ultimo_emitido");
						switch($ultimo)
									{
										case 1:
										$cheque_ultimo='00000'.$cheque_ultimo;
										break;
										case 2:
										$cheque_ultimo='0000'.$cheque_ultimo;
										break;
										case 3:
										$cheque_ultimo='000'.$cheque_ultimo;
										break;
										case 4:
										$cheque_ultimo='00'.$cheque_ultimo;
										break;
										case 5:
										$cheque_ultimo='0'.$cheque_ultimo;
										break;
										case 6:
										$cheque_ultimo=$cheque_ultimo;
										break;
										
									}
		//--
		if ($row->fields("estatus")=="1")
			$estatus="Activo";
		else
			if ($row->fields("estatus")=="2")
				$estatus="Inactivo";
		else
			if ($row->fields("estatus")=="3")
				$estatus="Agotado";		
		//--
		if($tipo_reporte=="vacio")
		{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		$pdf->Cell(30,6,				substr(strtoupper($row->fields("nombre")),0,20),					0,0,'L',1);
		$pdf->Cell(20,6,				$row->fields("nchequera"),					0,0,'C',1);
		$pdf->Cell(30,6,				$row->fields("cuenta"),	0,0,'L',1);
		$pdf->Cell(30,6,				$n_cheque,	0,0,'L',1);
		$pdf->Cell(30,6,				$cheque_ultimo,				0,0,'L',1);
		$pdf->Cell(30,6,				$row->fields("cantidad_cheques"),				0,0,'L',1);
		$pdf->Cell(20,6,				$estatus,				0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else
		if($tipo_reporte=="banco")
		{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		$pdf->Cell(20,6,				$row->fields("nchequera"),					0,0,'C',1);
		$pdf->Cell(40,6,				$row->fields("cuenta"),0,0,'L',1);
		$pdf->Cell(40,6,				$n_cheque,	0,0,'L',1);
		$pdf->Cell(30,6,				$cheque_ultimo,				0,0,'L',1);
		$pdf->Cell(30,6,				$row->fields("cantidad_cheques"),				0,0,'L',1);
		$pdf->Cell(20,6,				$estatus,				0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else
		if($tipo_reporte=="banco_cuenta")
		{
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		$pdf->Cell(20,6,				$row->fields("nchequera"),					0,0,'C',1);
		$pdf->Cell(40,6,				$n_cheque,	0,0,'L',1);
		$pdf->Cell(40,6,				$cheque_ultimo,				0,0,'L',1);
		$pdf->Cell(40,6,				$row->fields("cantidad_cheques"),				0,0,'L',1);
		$pdf->Cell(40,6,				$estatus,				0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	$pdf->Ln(6);
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