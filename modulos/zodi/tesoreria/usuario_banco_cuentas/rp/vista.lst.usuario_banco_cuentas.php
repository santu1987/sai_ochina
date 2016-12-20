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
/*if($id_usuario=="")
	$tipo_reporte="vacio";
else
*/	

if(isset($_GET['id_usuario']))
{
	$id_usuario =$_GET['id_usuario'];
	if($id_usuario!="")
	{
	$where="WHERE 1=1 ";
	$where.=" AND 
				usuario_banco_cuentas.id_usuario=$id_usuario
			  AND
                  usuario_banco_cuentas.id_organismo=$_SESSION[id_organismo]
	";
	$tipo_reporte="usuario";
	}else
	$tipo_reporte="vacio";

}
$Sql="
			SELECT 
				usuario_banco_cuentas.id_usuario_banco_cuentas,
				usuario_banco_cuentas.id_organismo,
				usuario_banco_cuentas.id_banco,
				banco.nombre as banco,
				usuario_banco_cuentas.cuenta_banco,
				usuario_banco_cuentas.estatus,
				usuario_banco_cuentas.comentarios,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.id_usuario,
				banco.estatus AS estatusb,
				banco_cuentas.estatus AS estatusc, 	
				banco_cuentas.ayo AS ayo	
			FROM 
				usuario_banco_cuentas
			INNER JOIN 
				banco
			ON 
				usuario_banco_cuentas.id_banco = banco.id_banco
			INNER JOIN 
				organismo 
			ON 
				usuario_banco_cuentas.id_organismo = organismo.id_organismo
			INNER JOIN 
				usuario 
			ON 
				usuario_banco_cuentas.id_usuario = usuario.id_usuario
			INNER JOIN 
				banco_cuentas
			ON 
				usuario_banco_cuentas.cuenta_banco=banco_cuentas.cuenta_banco
			$where
			ORDER BY
				banco_cuentas.ayo,banco.nombre,usuario_banco_cuentas.cuenta_banco,usuario_banco_cuentas.estatus
";
$row=& $conn->Execute($Sql);
////////////////////////////////////
$nom=$row->fields("nombre");
$ape=$row->fields("apellido");
$nombre_usuario=$nom."  ". $ape;	


//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $nombre_usuario;	
			global $tipo_reporte;	
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
	if($tipo_reporte=="usuario")
	{
///////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'RELACIÓN USUARIOS CUENTAS BANCARIAS',0,0,'C');
            $this->ln();
			$this->Cell(0,10,strtoupper($nombre_usuario),0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(50,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(40,6,		     'ESTATUS/BANCO',	0,0,'L',1);
			$this->Cell(30,6,		     'AÑO',	0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
			}
		if($tipo_reporte=="vacio")
			{
///////////////////////////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'RELACIÓN USUARIOS CUENTAS BANCARIAS',0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(50,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(50,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(30,6,			 'USUARIO',		0,0,'L',1);
			$this->Cell(30,6,		     'ESTATUS/BANCO',	0,0,'L',1);
			$this->Cell(20,6,		     'AÑO',	0,0,'L',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
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
		//--
		if ($row->fields("estatusb")=="1")
			$estatusb="Activo";
		else
			if ($row->fields("estatusb")=="2")
				$estatusb="Inactivo";
		//--
		//--
		if ($row->fields("estatusc")=="1")
			$estatusc="Activo";
		else
			if ($row->fields("estatusc")=="2")
				$estatusc="Inactivo";
$nom=$row->fields("nombre");
$ape=$row->fields("apellido");
$nombre_usuario=$nom."  ". $ape;	

		//--
	if($tipo_reporte=="usuario")
	{
///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$pdf->Cell(60,6,				$row->fields("banco"),					0,0,'L',1);
		$pdf->Cell(50,6,				$row->fields("cuenta_banco"),	0,0,'L',1);
		$pdf->Cell(40,6,				$estatusb,				0,0,'L',1);
		$pdf->Cell(30,6,				$row->fields("ayo"),				0,0,'L',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////
	}else
		if($tipo_reporte=="vacio")
		{
///////////////////////////////////////////////////////////////////////////////////////////////////////////
		$pdf->Cell(50,6,				$row->fields("banco"),					0,0,'L',1);
		$pdf->Cell(50,6,				$row->fields("cuenta_banco"),	0,0,'L',1);
		$pdf->Cell(30,6,				substr(strtoupper($nombre_usuario),0,25),				0,0,'L',1);
		$pdf->Cell(30,6,				$estatusb,				0,0,'L',1);
		$pdf->Cell(20,6,				$row->fields("ayo"),				0,0,'L',1);
///////////////////////////////////////////////////////////////////////////////////////////////////////////
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
