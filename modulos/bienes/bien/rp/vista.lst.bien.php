<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();
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
//selecionando la tabla monedas
$where = " WHERE 1 = 1 ";
$id_mayor = "";
if(isset($_GET['id_mayor']))
$id_mayor = $_GET['id_mayor'];
$id_tipo_bien = "";
if(isset($_GET['id_tipo_bien']))
$id_tipo_bien = $_GET['id_tipo_bien'];
$id_custodio = "";
if(isset($_GET['id_custodio']))
$id_custodio = $_GET['id_custodio'];
$id_bienes = "";
if(isset($_GET['id_bienes']))
$id_bienes = $_GET['id_bienes'];
if($id_mayor!='')
	$where.= " AND bienes.id_mayor = '$id_mayor' "; 
if($id_tipo_bien!='')
	$where.= " AND bienes.id_tipo_bienes = $id_tipo_bien "; 	
if($id_custodio!='')
	$where.= " AND bienes.id_custodio = $id_custodio ";
if($id_bienes!='')
	$where.= " AND bienes.id_bienes = $id_bienes ";	

$Sql="SELECT 
				bienes.id_bienes,
				bienes.nombre as bien, 
				valor_compra,
				valor_rescate,
				tipo_bienes.nombre as tipo,
				sitio_fisico.nombre as sitio,
				unidad_ejecutora.nombre as unidad,
				mayor.nombre as mayor,
				vida_util,
				custodio.nombre as custodio,
				descripcion_general as descri,
				marca,
				modelo,
				anobien,
				serial_motor,
				serial_carroceria,
				color,
				placa,
				estatus_bienes as estatus,
				bienes.comentarios as comen,
				codigo_bienes,
				serial_bien,
				bienes.anobien,
				bienes.calcular_depreciacion as depreciacion,
				bienes.fecha_compra as fecompra,
				descripcion_general as descrip
			FROM 
				bienes 
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes=bienes.id_tipo_bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				mayor
			ON 
				mayor.id_mayor=bienes.id_mayor
			".$where."
			AND
				bienes.id_bienes=$_REQUEST[id_bienes] 
			AND 
				estatus_bienes!=3 
			AND 
				estatus_bienes!=5 
			AND 
				bienes.id_organismo = $_SESSION[id_organismo] 
			ORDER BY id_bienes";
$row=& $conn->Execute($Sql);
$sql_foto=  "SELECT
				
				fotos_bienes.nombre as foto
			 FROM
			 	fotos_bienes
			INNER JOIN
				bienes
			ON
				fotos_bienes.id_bienes=bienes.id_bienes
			WHERE
				fotos_bienes.id_bienes=$_REQUEST[id_bienes]
			";
$row2=& $conn->Execute($sql_foto);
$titulo=$row->fields("bien");
$valor_compra=$row->fields('valor_compra');
$valor_rescate=$row->fields('valor_rescate');
$foto=$row2->fields('foto'); 
//$codigo="../db/barcode.php?bdata=".$row->fields('codigo_bienes');
//************************************************************************
//if (!$row->EOF)
//{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $titulo;	
			global $valor_compra;
			global $valor_rescate;
			global $foto;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
			
			//$this->Image("../db/barcode.php?bdata=1234564879",15,50,40);
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
			$this->Cell(0,10,strtoupper('tarjeta de custodia'),0,0,'C');
			$this->Ln(12);
			
			
			
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,0,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(62,0,'Impreso por: '.str_replace('<br />',' ',$_SESSION[name]),0,0,'C');
			$this->Cell(65,0,date("d/m/Y h:m:s"),0,0,'R');					
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
	//
	$exi=0;
	if($row->fields("codigo_bienes")!='')
		$exi=1;
		
	//
	if($exi!=0){
		$pdf->SetFont('Times','B',8);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(0);
		$pdf->SetTextColor(255);	
		
		$pdf->Cell(60,7,strtoupper('Ficha del Activo'),1,1,'L',1);		
		$pdf->Cell(190,	7,			'DATOS DEL BIEN',		1,1,'C',1);
		$pdf->SetTextColor(000);
		$pdf->SetFillColor(230);
		$pdf->Cell(33,	7,			strtoupper('Bien:'),		1,0,'L',1);
		$pdf->Cell(33,	7,			$titulo,		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Codigo: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('codigo_bienes'),		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Serial: '),		1,0,'L',1);
		$pdf->Cell(25,	7,			$row->fields('serial_bien'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Marca: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('marca'),		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Modelo: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('modelo'),		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Ano: '),		1,0,'L',1);
		$pdf->Cell(25,	7,			$row->fields('anobien'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Unidad: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('unidad'),		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Sitio Fisico: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('sitio'),		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Custodio: '),		1,0,'L',1);
		$pdf->Cell(25,	7,			$row->fields('custodio'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Valor de Compra: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			substr($valor_compra,1,20)." BsF",		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('valor de Rescate: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			substr($valor_rescate,1,20)." BsF",		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Fecha de Compra: '),		1,0,'L',1);
		$pdf->Cell(25,	7,			$row->fields('fecompra'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Vida Util: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			$row->fields('vida_util')." "."Meses",		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Valor Depreciado: '),		1,0,'L',1);
		$pdf->Cell(33,	7,			'------',		1,0,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Tipo de Bien: '),		1,0,'L',1);
		$pdf->Cell(25,	7,			$row->fields('tipo'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Descripcion: '),		1,0,'L',1);
		$pdf->Cell(157,	7,			$row->fields('descrip'),		1,1,'L',0);
		$pdf->Cell(33,	7,			strtoupper('Comentario: '),		1,0,'L',1);
		$pdf->Cell(157,	7,			$row->fields('comen'),		1,1,'L',0);
		$pdf->SetTextColor(255);
		$pdf->SetFillColor(0);
		if($foto!=""){
		$pdf->Cell(190,	7,			'FOTOS DEL BIEN',		1,1,'C',1);
			$cont=0;
			$x=15;
			while(!$row2->EOF)
			{
				$pdf->Image("../../../../imagenes/bienes/".$row2->fields('foto'),$x,130,40);
				$cont++;
				$x=$x+45;
				if($cont==4){
					break;
				}
				$row2->MoveNext();
			} 
		$pdf->Cell(190,	50,			' ',		1,0,'C',0);
		}
		else{
		$pdf->Cell(190,	7,			'FOTOS DEL BIEN',		1,1,'C',1);
				$pdf->Image("../../../../imagenes/iconos/anulados.jpg",85,130,40);
				$pdf->SetTextColor(0);
		$pdf->SetFillColor(255);
		$pdf->Cell(190,	50,			'Este Activo no Posee Fotos',		1,0,'C',0);
		}
		$pdf->SetTextColor(0);
		$pdf->SetFillColor(255);
		$pdf->Ln(82);
		$pdf->Line(40, 200, 90, 200, $style);
		$pdf->Line(120, 200, 170, 200, $style);
		$pdf->Cell(110,	7,			'CUSTODIO',		0,0,'C',1);
		$pdf->Cell(50,	7,			'COORD. DE CONTROL DE MATERIALES',		0,0,'C',1);
		/*$pdf->Cell(37,	6,			'BIEN',			1,0,'L',1);
		$pdf->Cell(37,	6,			'CUSTODIO',			1,0,'L',1);
		$pdf->Cell(37,	6,			'VALOR',			1,0,'L',1);
		$pdf->Ln(6);*/
	}
	else{
		$pdf->SetFont('Arial','B',12);
		//$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);	
		$pdf->Ln();
		$pdf->Line(10,50,200,50);
		$pdf->Cell(195,	15,			'No se encontraron Datos',		0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",140,53,30);
		$pdf->Line(10,85,200,85);
	}
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetTextColor(0);	
	
	while (!$row->EOF) 
	{
		
		/*$pdf->Cell(37,	6,		$row->fields("codigo_bienes"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("bien"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("custodio"),	1,0,'L',1);
		$pdf->Cell(37,	6,		$row->fields("valor_compra"),	1,0,'L',1);*/
		$pdf->Ln(6);
		$row->MoveNext();
		
	}
	$pdf->Output();
//}
?>