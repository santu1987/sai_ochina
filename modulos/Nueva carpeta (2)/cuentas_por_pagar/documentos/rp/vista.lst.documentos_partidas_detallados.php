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
if((isset($_GET['desde']))&&(isset($_GET['hasta'])))
{
	$desde=$_GET['desde'];
	$hasta=$_GET['hasta'];
}
list($dia,$mes,$ayo)=split("/",$hasta,3);
if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=01;
	$mes=$mes+1;
 
 }
 else
if($dia=="31")
{
	$dia=01;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="10";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;
 if(isset($_GET['desde']))
{
	$where=" WHERE
				 documentos_cxp.id_organismo = ".$_SESSION["id_organismo"]." 
				 AND documentos_cxp.fecha_vencimiento>='$desde' AND documentos_cxp.fecha_vencimiento<='$hasta'
		 ";
//				

} 
if(isset($_GET[tipo]))	
{
	$tipo=$_GET[tipo];
	if($tipo!=0)
	$where.="AND documentos_cxp.tipo_documentocxp='$tipo'";
	
}
//**************************** validando que tipo de reporte es *******************//////////
if(($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="VACIO";
}else
if(($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="tipo";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
	$where.=" AND documentos_cxp.tipo_documentocxp='$tipo'";
}			  
	
$Sql="
			SELECT
					 documentos_cxp.id_documentos,
					 documentos_cxp.numero_control,
					 documentos_cxp.porcentaje_iva,
					 documentos_cxp.porcentaje_retencion_iva,
					 documentos_cxp.porcentaje_retencion_islr,
					 documentos_cxp.monto_bruto,
					 documentos_cxp.monto_base_imponible,
					 documentos_cxp.numero_compromiso,
					 documentos_cxp.tipo_documentocxp,
					 tipo_documento_cxp.nombre as doc
					 
				  FROM
				  	documentos_cxp
				INNER JOIN
				organismo
				ON
					documentos_cxp.id_organismo=organismo.id_organismo
				INNER JOIN
					tipo_documento_cxp
				ON
					documentos_cxp.tipo_documentocxp=tipo_documento_cxp.id_tipo_documento
				INNER JOIN
					usuario
				ON	
					documentos_cxp.ultimo_usuario=usuario.id_usuario			
			$where
			ORDER BY
				 documentos_cxp.numero_control,tipo_documento_cxp.nombre
";
			
$row=& $conn->Execute($Sql);
/*if($tipo_de_reporte!="VACIO")
{
$id_prove=$row->fields("id_proveedor");
if(($id_prove=="")||($id_prove==NULL)||($id_prove=='0'))
		{	
			$proveedor=strtoupper($row->fields("beneficiario"));
		}
		else
		{
			$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
			$row_proveedor=& $conn->Execute($sql_proveedor);
			$proveedor=strtoupper($row_proveedor->fields("nombre"));
		}
}
*///************************************************************************

if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $nombre_usuario;	
			global $row;
			global $desde;
			global $hasta;
			global $tipo_de_reporte;
			global $proveedor;
			global $nombre_documento;
			global $a;
			
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
			$this->SetFont('Arial','B',11);
			
/////////////////////////////////////tipo vacio/////////////////////////////////			
		if($tipo_de_reporte=="VACIO")
		{	
			$this->Cell(0,10,'DETALLE DOCUMENTOS EMITIDOS',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'Nº CONTROL',			1,0,'L',1);
			$this->Cell(40,6,		     'TIPO',			1,0,'L',1);
			$this->Cell(40,6,		     'PARTIDA',			1,0,'L',1);
			$this->Cell(30,6,		     'SALDO PARTIDAS',			1,0,'C',1);
		}	
//////////////////////////////////////////////////////////////////////////////////			
		else
		/////////////////////////////////////tipo vacio/////////////////////////////////			
		if($tipo_de_reporte=="tipo")
		{	
			$this->Cell(0,10,'DETALLE DOCUMENTOS EMITIDOS',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'TIPO:'.strtoupper($nombre_documento),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(70,6,		     'Nº CONTROL',			1,0,'L',1);
			$this->Cell(50,6,		     'PARTIDA',			1,0,'L',1);
			$this->Cell(50,6,		     'SALDO PARTIDAS',			1,0,'C',1);
		}	
//////////////////////////////////////////////////////////////////////////////////			

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
			$this->Cell(60,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(40,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(88,285,strtoupper($_SESSION['usuario']),40,6);}
		}	
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	//$pdf->SetAutoPageBreak(auto,50);	
	//$cont_partida=1;
	//ciclo de los tipos de doc
	while (!$row->EOF) 
	{	
		//ciclo para sacara las partidas por documento
		$numero_compromiso=$row->fields("numero_compromiso");
		$tipo=$row->fields("doc"); 
		$tipo_id=$row->fields("tipo_documentocxp"); 
					if($numero_compromiso!=0)
					{
												$sql="SELECT 
															\"orden_compra_servicioE\".id_proveedor, 
															\"orden_compra_servicioE\".id_unidad_ejecutora,
															\"orden_compra_servicioE\".id_proyecto_accion_centralizada, 
															\"orden_compra_servicioE\".id_accion_especifica, 
															\"orden_compra_servicioE\".numero_compromiso, 
															\"orden_compra_servicioE\".numero_pre_orden,
															\"orden_compra_servicioE\".tipo,
															partida, 
															   generica, 
															   especifica, 
															   subespecifica
														FROM 
															\"orden_compra_servicioE\"
														INNER JOIN
															\"orden_compra_servicioD\"
														ON
															\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
													$row_orden_compra=& $conn->Execute($sql);
													$partida=$row_orden_compra->fields("partida");
													$generica=$row_orden_compra->fields("generica");
													$especifica=$row_orden_compra->fields("especifica");
													$subespecifica=$row_orden_compra->fields("subespecifica");
													$partidas=$partida.".".$generica.".".$especifica.".".$subespecifica;
													$total_partidas=$row->fields("monto_bruto");													
					
			$numero_control=$row->fields("numero_control");
			/*
			$partidas_sig=$partidas;
			if($partidas_sig==$partidas_ant)
			{
				$partidas="";
			}
			else
			$partidas=$partidas_sig;
			$tipo_sig=$row->fields("doc");
			if($tipo_sig==$tipo_ant)
			{
				$tipo="";
			}
			else
			$tipo=$tipo_sig;*/
			$pdf->SetFont('Arial','B',10);
///////////////////////////////////////// caso reporte vacio///////////////////////		
		if($tipo_de_reporte=="VACIO")
		{
							$pdf->Cell(60,6,$numero_control,0,0,'L',1);
							$pdf->Cell(40,6,strtoupper($tipo),0,0,'L',1);
							$pdf->Cell(40,6,$partidas,0,0,'L',1);
							$pdf->Cell(30,6,number_format($total_partidas,2,',','.'),0,0,'R',1);
							$pdf->Ln();
							$total_general=$total_general+$total_partidas;
							$cont_partida=$cont_partida+1;
							$partidas="";
		}					
///////////////////////////////////////////////////////////////////////////////////////////////////							
///////////////////////////////////////// caso reporte tipo///////////////////////		
		if($tipo_de_reporte=="tipo")
		{
							$pdf->Cell(70,6,$numero_control,0,0,'L',1);
							$pdf->Cell(50,6,$partidas,0,0,'L',1);
							$pdf->Cell(50,6,number_format($total_partidas,2,',','.'),0,0,'R',1);
							$pdf->Ln();
							$total_general=$total_general+$total_partidas;
							$cont_partida=$cont_partida+1;
							$partidas="";
		}					
///////////////////////////////////////////////////////////////////////////////////////////////////							

							//$pdf->Ln();

						}
	
		/*	$partida=$row_orden_compra->fields("partida");
			$generica=$row_orden_compra->fields("generica");
			$especifica=$row_orden_compra->fields("especifica");
			$subespecifica=$row_orden_compra->fields("subespecifica");
			$partidas_ant=$partida.".".$generica.".".$especifica.".".$subespecifica;
											
			$tipo_ant=$row->fields("doc");
	*/		
			$row->MoveNext();
			
		}									
//////////////////////////////////////////////////////////////////////////////////////////////////////		

	//	$pdf->SetFont('Arial','B',12);
		$pdf->Cell(170,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
	//	$pdf->Ln();
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
		$pdf->Cell(190,		6,'No se encontraron Datos' ,			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>