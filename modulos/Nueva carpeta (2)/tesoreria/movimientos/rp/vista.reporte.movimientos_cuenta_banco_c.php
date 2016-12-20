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
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
list($dia,$mes,$ayo)=split("/",$hasta,3);

if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=1;
	$mes=$mes+1;
 
 }
 else
if($dia=="31")
{
	$dia=1;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="1";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;
 if(isset($_GET['desde']))
{
	$where=" WHERE
					
					movimientos_cuentas.id_organismo = ".$_SESSION["id_organismo"]."
			     AND movimientos_cuentas.fecha_proceso>='$desde' AND movimientos_cuentas.fecha_proceso<='$fechas'

		 ";
} 
if(isset($_GET['id_banco']))
{
	$id_banco =$_GET['id_banco'];
	$where.=" AND movimientos_cuentas.id_banco='$id_banco'";
}
if(isset($_GET['cuenta']))
{
	$cuenta=$_GET['cuenta'];
	$where.=" AND movimientos_cuentas.cuenta_banco='$cuenta'";
}
/*/
//AND chequeras.estatus='1'  AND cheques.estatus!=5				  
if(isset($_GET['id_usuario']))
{
	$id_usuario=$_GET['id_usuario'];
	$where.=" AND cheques.usuario_cheque='$id_usuario'";
} //AND chequeras.estatus='1'				  

if(isset($_GET['rif']))
{
	$rif=$_GET['rif'];
	$where.=" AND cheques.cedula_rif_beneficiario='$rif'
			";
}

if(isset($_GET['cuenta']))
{
	$cuenta=$_GET['cuenta'];
	$where.=" AND cheques.cuenta_banco='$cuenta'";
}
if(isset($_GET['id_proveedor']))
{
	$id_proveedor=$_GET['id_proveedor'];
	$where.=" AND cheques.id_proveedor='$id_proveedor'
			";
	$as=" AND cheques.id_proveedor='$id_proveedor'
			";		
			
}

if(isset($_GET['tipo']))
{
	$tipo=$_GET['tipo'];
	if($tipo!='3')
	{
		$where.=" AND cheques.tipo_cheque=$tipo";
		$as=" AND cheques.tipo_cheque=$tipo";
		}		
}

if(isset($_GET['eva_opcion']))
{
	$op=$_GET['eva_opcion'];
	if($op=='1')
		{
			$where.=" AND cheques.id_proveedor!='0'";
			
		}
	else
		if($op=='2')
			$where.=" AND cheques.cedula_rif_beneficiario!='NULL'";

}*/

$Sql="
			SELECT
					movimientos_cuentas.id_movimientos_cuentas,
					banco.nombre AS banco,
					movimientos_cuentas.cuenta_banco,
					movimientos_cuentas.fecha_proceso,
					movimientos_cuentas.referencia,
					movimientos_cuentas.monto
			FROM
					movimientos_cuentas
			INNER JOIN
					banco
			ON
					movimientos_cuentas.id_banco=banco.id_banco
			INNER JOIN
					organismo
			ON
					movimientos_cuentas.id_organismo=organismo.id_organismo
			$where											
					
";
			
$row=& $conn->Execute($Sql);

//************************************************************************
if (!$row->EOF)
{ 

		$banco=$row->fields("banco");
		$cuenta=$row->fields("cuenta_banco");
		
		require('../../../../utilidades/fpdf153/fpdf.php');
		//************************************************************************
class PDF extends PDF_Code128
		{
			
			//Cabecera de página
			function Header()
			{	
				global $desde;
				global $hasta;
				global $banco;
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
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');$this->Ln(10);	
			$this->Ln(10);	
				$this->SetFont('Arial','B',16);
				$this->Cell(0,10,'MOVIMIENTOS BANCARIOS',0,0,'C');
				$this->ln(10);
				$this->Cell(0,10,strtoupper($banco).":".$cuenta,0,0,'C');
				$this->ln(10);
				//$this->Cell(0,10,strtoupper($nombre_usuario),0,0,'C');
				$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
				$this->Ln(10);
				$this->SetFont('Times','B',10);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(175) ;
				$this->SetTextColor(0);
				$this->Cell(60,6,		     'MOVIMIENTOS',			0,0,'C',1);
				$this->Cell(50,6,		     'FECHA',			0,0,'C',1);
				$this->Cell(60,6,		     'MONTO',			0,0,'C',1);
			
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
				$this->Cell(65,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
				$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
				$this->Ln();
				$this->SetFillColor(175) ;
			$this->Code128(88,285,strtoupper($_SESSION['usuario']),40,6);}
		}
		//************************************************************************
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('');
		$pdf->SetFont('arial','',10);
		$pdf->SetFillColor(255);
		while (!$row->EOF) 
		{	
		$fecha_proceso = substr($row->fields("fecha_proceso"),0,10);
		$fecha_proceso = substr($fecha_proceso,8,2)."".substr($fecha_proceso,4,4)."".substr($fecha_proceso,0,4);
		
					$pdf->SetFont('Arial','B',10);
							$pdf->Cell(60,6,strtoupper($row->fields("referencia")),0,0,'C',1);
							$pdf->Cell(50,6,$fecha_proceso,	0,0,'C',1);
							$pdf->Cell(60,6,number_format($row->fields("monto"),2,',','.'),	0,0,'C',1);
		$pdf->Ln(6);							
		$row->MoveNext();
		
		}$pdf->Output();
		
	
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
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');$this->Ln(10);	
			
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
		$pdf->Cell(190,		6,'No se encontraron Datos' ,			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>