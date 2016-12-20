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
$where="WHERE 1=1 ";

if(isset($_GET['cuenta']))
{
	$cuenta =$_GET['cuenta'];
	if($cuenta!='')
	$where.="AND cuenta_contable_contabilidad.id='$cuenta'";
}
if(isset($_GET['id_usuario']))
{
	$usuario =$_GET['id_usuario'];
	if($usuario!='')
	$where.="AND usuario.id_usuario='$usuario'";
}
if(($usuario!="")and($cuenta==""))
{
	$tipo_reporte="usuario";
}
else
	$tipo_reporte="todos";

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
$Sql="
		SELECT 
				auxiliares.id_auxiliares,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre as descripcion,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				usuario.nombre as name,
				usuario.apellido as apellido,
				usuario.usuario
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN
				saldo_auxiliares
			ON
				auxiliares.id_auxiliares=saldo_auxiliares.cuenta_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				saldo_auxiliares.cuenta_contable=cuenta_contable_contabilidad.id	
			INNER JOIN 
				usuario 
			ON 
				auxiliares.ultimo_usuario = usuario.id_usuario	
			$where	
			group by
				auxiliares.id_auxiliares,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre ,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				usuario.nombre ,
				usuario.apellido ,
				usuario.usuario
			
			order by
				cuenta_contable_contabilidad.cuenta_contable,auxiliares.cuenta_auxiliar	
				";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	$usuario=strtoupper($row->fields("usuario"));
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $tipo_reporte;
			global $usuario;
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
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'AUXILIARES',0,0,'C');
				if($tipo_reporte=="usuario")
				{
					$this->Ln(5);
					$this->SetFont('Arial','B',12);
					$this->Cell(0,10,'USUARIO:'." ".$usuario,0,0,'C');
				}
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175);
			$this->SetTextColor(0);
			$this->Ln(10);	

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
	$pdf->SetAutoPageBreak(auto,15);	
	$a="omega";
	$b="1";
	//die($a);
	while (!$row->EOF) 
	{		
		if($a=="omega")
		{
			$pdf->Ln(4);
			
			$pdf->SetFont('Times','B',10);
			$pdf->SetFillColor(175);
			$pdf->SetTextColor(0);
			if($tipo_reporte=="todos")
			{
/*nn*******************************************cabecera**********************************************************************/			
			$pdf->Cell(190,6,		"Cuenta Contable"." ".$row->fields("cuenta_contable")."      ".strtoupper(substr($row->fields("descripcion"),0,50)),0,0,'C',1);
			$pdf->Ln(8); 
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);
			$pdf->Cell(50,6,		"Código Auxiliar",0,0,'L',1);	
			$pdf->Cell(60,6,		"Nombre Auxiliar",0,0,'L',1);
			$pdf->Cell(50,6,		"Usuario",0,0,'L',1);		
			$pdf->Ln(8);
			$b=1;
/*nn***************************************************************************************************************************/		
			}
			if($tipo_reporte=="usuario")		
			{
/*nn*******************************************cabecera**********************************************************************/			
			$pdf->Cell(190,6,		"Cuenta Contable"." ".$row->fields("cuenta_contable")."      ".strtoupper(substr($row->fields("descripcion"),0,50)),0,0,'C',1);		 
			$pdf->Ln(8); 
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);			$pdf->Cell(50,6,		"Código Auxiliar",0,0,'L',1);	
			$pdf->Cell(60,6,		"Nombre Auxiliar",0,0,'L',1);
			$pdf->Ln(8);
			$b=1;
/*nn***************************************************************************************************************************/		
			}
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		if(($a=="alpha")||($b==1))
		{
		if($tipo_reporte=="todos")
			{
/*mn*********************************************************************************************************************/			
			$pdf->SetFont('Times','B',6);
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);
			$pdf->Cell(50,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
			$pdf->Cell(60,6,				$row->fields("nombre"),	0,0,'L',1);
			$pdf->Cell(50,6,				strtoupper($row->fields("usuario")),	0,0,'L',1);

			$b=2;
			$pdf->Ln(5);
			//$pdf->Cell(50,6,				$where,	0,0,'L',1);
/*mn***********************************************************************************************************************/
		}
		if($tipo_reporte=="usuario")
			{
/*mn*********************************************************************************************************************/			
			$pdf->SetFont('Times','B',6);
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);
			$pdf->Cell(50,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
			$pdf->Cell(60,6,				$row->fields("nombre"),	0,0,'L',1);
			$b=2;
			$pdf->Ln(5);
			//$pdf->Cell(50,6,				$where,	0,0,'L',1);
/*mn***********************************************************************************************************************/
		}
		}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$cta_ant=$row->fields("cuenta_contable");
			$row->MoveNext();
		$cta_sig=$row->fields("cuenta_contable");
		if($cta_ant==$cta_sig)
		{
			$a="alpha";	
		}
		else
		{
			$a="omega";
			}
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