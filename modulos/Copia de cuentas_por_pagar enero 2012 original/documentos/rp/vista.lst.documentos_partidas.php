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
//$desde=$_GET['desde'];
//$hasta=$_GET['hasta'];
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
		 ";
//				 AND documentos_cxp.fecha_vencimiento>='$desde' AND documentos_cxp.fecha_vencimiento<='$hasta'

} 	
$Sql="
			SELECT  DISTINCT 
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
				 documentos_cxp.tipo_documentocxp
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
			$this->Cell(0,10,'RESUMEN CXP DOCUMENTOS',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'DOCUMENTO',			0,0,'L',1);
			$this->Cell(40,6,		     'ID',			0,0,'L',1);
			$this->Cell(40,6,		     'PARTIDA',			0,0,'L',1);
			$this->Cell(30,6,		     'SAlDO',			0,0,'C',1);
			
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
		$valor_o=0;$valor_a=0;$valor_b=0;$valor_c=0;
		$tipo_doc=$row->fields("tipo_documentocxp"); 
		$sql_datos="SELECT
					 documentos_cxp.id_documentos,
					 documentos_cxp.porcentaje_iva,
					 documentos_cxp.porcentaje_retencion_iva,
					 documentos_cxp.porcentaje_retencion_islr,
					 documentos_cxp.monto_bruto,
					 documentos_cxp.monto_base_imponible,
					 documentos_cxp.numero_compromiso
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
				WHERE 
					documentos_cxp.tipo_documentocxp='$tipo_doc'
				ORDER BY
					 documentos_cxp.tipo_documentocxp";			
		$row_datos=& $conn->Execute($sql_datos);
		//ciclo para sacara las partidas por documento
		while (!$row_datos->EOF) 
		{
		
					$numero_compromiso=$row_datos->fields("numero_compromiso");
					if($numero_compromiso!=0)
					{
												/*$sql="SELECT 
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
															\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
														where
															\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";*/
															$sql="SELECT  distinct
																			proveedor.nombre,
																			proveedor.id_proveedor as id_proveedor,
																			proveedor.codigo_proveedor as codigo_proveedor ,
																			requisicion_encabezado.id_unidad_ejecutora, 
																			requisicion_encabezado.id_proyecto,
																			requisicion_encabezado.id_accion_centralizada, 
																			requisicion_encabezado.id_accion_especifica, 
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
																				organismo
																			ON
																				\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
																			INNER JOIN
																				\"orden_compra_servicioD\"
																			ON
																				\"orden_compra_servicioE\".numero_precompromiso = \"orden_compra_servicioD\".numero_pre_orden
																			INNER JOIN 
																				 requisicion_encabezado
																			 ON
																				\"orden_compra_servicioE\".numero_requisicion=requisicion_encabezado.numero_requisicion
																			INNER JOIN
																					\"solicitud_cotizacionE\"
																				ON
																					\"solicitud_cotizacionE\".numero_cotizacion=\"orden_compra_servicioE\".numero_cotizacion	
																			INNER JOIN	
																					proveedor
																				ON
																					\"solicitud_cotizacionE\".id_proveedor=proveedor.id_proveedor	 
																			where
																				\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
													$row_orden_compra=& $conn->Execute($sql);
													$partida=$row_orden_compra->fields("partida");
													if($partida==401) $valor_o=$valor_o+$row_datos->fields("monto_bruto");
													if($partida==402) $valor_a=$valor_a+$row_datos->fields("monto_bruto");
													if($partida==403) $valor_b=$valor_b+$row_datos->fields("monto_bruto");
													if($partida==404) $valor_c=$valor_c+$row_datos->fields("monto_bruto");
					}
			$row_datos->MoveNext();	
			}
						$tipo=$row->fields("doc"); 
						$tipo_id=$row->fields("tipo_documentocxp"); 
						$cont_partida=1;
						while($cont_partida<=4)
						{
									if($cont_partida==1)
											{
												$partidas=401;
												$total_partidas=$valor_o;
											}
									if($cont_partida==2)
											{
												$partidas=402;
												$total_partidas=$valor_a;
											}
									if($cont_partida==3)
											{
												$partidas=403;
												$total_partidas=$valor_b;
											}
									if($cont_partida==4)
											{
												$partidas=404;
												$total_partidas=$valor_c;
											}
								
							$pdf->SetFont('Arial','B',10);
							$pdf->Cell(60,6,strtoupper($tipo),0,0,'L',1);
							$pdf->Cell(40,6,$tipo_id,0,0,'L',1);
							$pdf->Cell(40,6,$partidas,0,0,'L',1);
							$pdf->Cell(30,6,number_format($total_partidas,2,',','.'),0,0,'R',1);
							$pdf->Ln();
							$total_general=$total_general+$total_partidas;
							$cont_partida=$cont_partida+1;
						 }	
						//$partidas=0;
						//$total_partidas=0;
			
			$pdf->Ln();
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