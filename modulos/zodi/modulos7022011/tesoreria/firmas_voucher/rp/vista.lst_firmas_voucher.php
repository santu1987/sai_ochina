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
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA

$Sql="
		SELECT 
				* 					
			FROM 
				firmas_voucher
			
			INNER JOIN 
				organismo 
			ON 
					firmas_voucher.id_organismo = organismo.id_organismo
			";
$row_firmas=& $conn->Execute($Sql);
//$nombre=$row->fields("nombre");
//************************************************************************
if (!$row_firmas->EOF)
{ 		
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $nombre_banco;
			global $fecha;	
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
			$this->Cell(0,10,'LISTADO ',0,0,'C');
            $this->ln();
			$this->Cell(0,10,'HISTORIAL FIRMAS VOUCHER',0,0,'C');
			$this->ln();
				//$this->Cell(0,10,"AÑO:" .date("Y"),0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(255);
			$this->Cell(57,6,		     'DIRECTOR GENERAL DE OCHINA',			0,0,'L',1);
			$this->Cell(57,6,			 'DIRECTOR DE ADMINISTRACION',		0,0,'L',1);
			$this->Cell(57,6,			 'JEFE DE TESORERIA',		0,0,'L',1);
			$this->Cell(57,6,			 'PREPARADO POR',		0,0,'L',1);
			$this->Cell(20,6,		     'ESTATUS',	0,0,'L',1);
			$this->Cell(20,6,		     'FECHA',	0,0,'L',1);
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
			$this->Cell(140,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(175) ;
			$this->Code128(125,200,strtoupper($_SESSION['usuario']),40,6);
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row_firmas->EOF) 
	{
	
		///////////////////////////////////////////////////////////////////////////////////////
		$codigo_director=$row_firmas->fields("codigo_director_ochina");
		$codigo_administracion=$row_firmas->fields("codigo_director_administracion");
		$codigo_jefe_finanzas=$row_firmas->fields("codigo_jefe_finanzas");
		$preparado=$row_firmas->fields("codigo_preparado_por");
		
		$sql_director=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_director";
		$row_director=& $conn->Execute($sql_director);
		$nom_director=$row_director->fields("nombre");
		$ape_director=$row_director->fields("apellido");
		$nombre_director=strtoupper($nom_director."  ". $ape_director);	
		 
		$sql2=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_administracion";
		$row_administrador=& $conn->Execute($sql2);
		$nom_administrador=$row_administrador->fields("nombre");
		$ape_administrador=$row_administrador->fields("apellido");
		$nombre_administrador=strtoupper($nom_administrador."  ". $ape_administrador);
		
		$sql3=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_jefe_finanzas";
		$row_jefe=& $conn->Execute($sql3);
		$nom_jefe=$row_jefe->fields("nombre");
		$ape_jefe=$row_jefe->fields("apellido");
		$nombre_jefe=strtoupper($nom_jefe."  ". $ape_jefe);
		
		$sql4=" SELECT	 nombre,apellido from usuario where id_usuario=".$_SESSION['id_usuario']."";			
		$row_preparado=& $conn->Execute($sql4);
		$nom_preparado=$row_preparado->fields("nombre");
		$ape_preparado=$row_preparado->fields("apellido");
		$nombre_preparado=strtoupper($nom_preparado."  ". $ape_preparado);

		/////////////////////////////////////////////////////////
		//--------------------------------
		if ($row_firmas->fields("estatus")=="1")
			$estatus="Activo";
		else
		if ($row_firmas->fields("estatus")=="2")
				$estatus="Inactivo";
				$fecha = $row_firmas->fields("fecha_firma");
				$fecha = substr($fecha, 0,10);
				$fecha = substr($fecha,8,2).substr($fecha,4,4).substr($fecha,0,4);
								
		//---------------------------------
			$pdf->Cell(57,6,$nombre_director,			0,0,'L',1);
			$pdf->Cell(57,6,$nombre_administrador,		0,0,'L',1);
			$pdf->Cell(57,6,$nombre_jefe,0,0,'L',1);
			$pdf->Cell(57,6,$nombre_preparado,0,0,'L',1);
			$pdf->Cell(20,6,$estatus,	0,0,'L',1);
			$pdf->Cell(20,6,$fecha,	0,0,'L',1);
		$pdf->Ln(6);
	$row_firmas->MoveNext();
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
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
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