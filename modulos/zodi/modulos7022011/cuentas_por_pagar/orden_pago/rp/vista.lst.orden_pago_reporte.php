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
		$sql4=" SELECT	 nombre,apellido,comentario from usuario where id_usuario=".$_SESSION['id_usuario']."";			
		$row_preparado=& $conn->Execute($sql4);
		$nom_preparado=$row_preparado->fields("nombre");
		$ape_preparado=$row_preparado->fields("apellido");
		$nombre_preparado=strtoupper($nom_preparado."  ". $ape_preparado);
		$comentario=strtoupper($row_preparado->fields("comentario"));
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
//************************************************************************
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//--
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
//--

///formatos de fechas llevada sal español
// Obtenemos y traducimos el nombre del día
$dia=date("l");
if ($dia=="Monday") $dia="Lunes";
if ($dia=="Tuesday") $dia="Martes";
if ($dia=="Wednesday") $dia="Miércoles";
if ($dia=="Thursday") $dia="Jueves";
if ($dia=="Friday") $dia="Viernes";
if ($dia=="Saturday") $dia="Sabado";
if ($dia=="Sunday") $dia="Domingo";

// Obtenemos el número del día
$dia2=date("d");

// Obtenemos y traducimos el nombre del mes

$mes=date("F");
if ($mes=="January") $mes="Enero";
if ($mes=="February") $mes="Febrero";
if ($mes=="March") $mes="Marzo";
if ($mes=="April") $mes="Abril";
if ($mes=="May") $mes="Mayo";
if ($mes=="June") $mes="Junio";
if ($mes=="July") $mes="Julio";
if ($mes=="August") $mes="Agosto";
if ($mes=="September") $mes="Setiembre";
if ($mes=="October") $mes="Octubre";
if ($mes=="November") $mes="Noviembre";
if ($mes=="December") $mes="Diciembre";

// Obtenemos el año
$ano=date("Y");
if($_GET['caducidad']==0)
{
	$caducidad="";
}else
	{
		switch($_GET['caducidad'])
		{
		case 1:
			$caducidad="CADUCA A LOS 15 DÍAS";
			break;
		case 2:		
			$caducidad="CADUCA A LOS 60 DÍAS";
			break;
		case 3:
			$caducidad="CADUCA A LOS 90 DÍAS";
			break;
		case 4:
			$caducidad="CADUCA A LOS 120 DÍAS";
			break;
		}
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
$opcion=$_GET['opcion'];
$id_proveedor=$_GET['prove'];
if($opcion=='1')
{
	$where.="AND documentos_cxp.id_proveedor='$id_proveedor'";

}else
if($opcion=='2')
{
	$where.="AND documentos_cxp.cedula_rif_beneficiario='$id_proveedor'";

}
$orden=$_GET['orden'];
if($_GET['busq_ano']!="")
{
	$busq_fecha_orden=$_GET['bus_ano'];
	$where.="AND (orden_pago.ano='$busq_ano')";
}
$Sql="
			SELECT 
				 documentos_cxp.id_documentos,	
				 documentos_cxp.id_organismo,
				 documentos_cxp.id_proveedor,
				 documentos_cxp.beneficiario,
				 documentos_cxp.cedula_rif_beneficiario,
				 documentos_cxp.ano,
				 documentos_cxp.tipo_documentocxp,
				 documentos_cxp.numero_documento,
				 documentos_cxp.numero_control,
				 documentos_cxp.fecha_vencimiento,
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.monto_bruto,
				 documentos_cxp.monto_base_imponible,
				 documentos_cxp.numero_compromiso,
				 documentos_cxp.comentarios,
				 tipo_documento_cxp.nombre as doc,
				 documentos_cxp.orden_pago,
				 documentos_cxp.retencion_ex1,
				 documentos_cxp.retencion_ex2,
				 documentos_cxp.amortizacion
				 
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
			
			WHERE
				documentos_cxp.orden_pago='$orden'
			$where
			order by documentos_cxp.numero_documento ASC
";
$row=& $conn->Execute($Sql);
$numero_compromiso=$row->fields("numero_compromiso");
if($opcion=='2')
{
	$nombre=$row->fields("beneficiario");
	$rif=$row->fields("cedula_rif_beneficiario");
}else
{
					$id_proveedor=$row->fields("id_proveedor");
					$sql_prove="select nombre,rif from proveedor where id_proveedor='$id_proveedor'";
					$row_prove=& $conn->Execute($sql_prove);
					$nombre=$row_prove->fields("nombre");
					$rif=$row_prove->fields("rif");
					$opcion='1';
}
//////////////////////////////////////VERIFICANDO EL NUMERO DE LA PARTIDA/////////////////////////
$sql_comp="SELECT 
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
											organismo
										ON
											\"orden_compra_servicioE\".id_organismo=organismo.id_organismo			
										INNER JOIN
											\"orden_compra_servicioD\"
										ON
											\"orden_compra_servicioE\".numero_pre_orden = \"orden_compra_servicioD\".numero_pre_orden
										where
											\"orden_compra_servicioE\".numero_compromiso='$numero_compromiso'";
									$row_orden_compra=& $conn->Execute($sql_comp);
									$partida=$row_orden_compra->fields("partida");
//////////////////////////////////////////////////////////////////////////////////////////////////
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
			global $row;
			global $desde;
			global $hasta;
			global $orden;
			global $dia2;
			global $mes;
			global $ano;
			global $partida;
			global $nombre;global $rif;
			
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
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	$this->Ln();	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
		//	$this->Cell(0,10,'REPORTE DOCUMENTOS CUENTAS POR PAGAR',0,0,'C');
            $this->ln();
			//$this->Cell(0,10,strtoupper($nombre_usuario),0,0,'C');
			$this->Ln();
			//$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			//$this->Ln(10);
			$this->SetFont('Times','B',14);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(0) ;
			$this->SetTextColor(0);
			$this->Cell(50,6,'ORDEN DE PAGO Nº:       ',			0,0,'L',0);
			$this->Cell(10,6,$orden,			0,0,'R',0);
			$this->Ln();
			$this->SetFont('Times','B',11);
			$this->Cell(25,6,'NOMBRE:',			0,0,'L',0);
	        $this->SetFont('Times','B',11);
			$this->Cell(25,6,strtoupper($nombre),0,0,'L',0);
			$this->SetFont('Times','B',11);
			$this->Cell(150,6,'FECHA:',			0,0,'R',0);
			$this->Cell(150,4," $dia2 de $mes de $ano",0,0,'L');

	       
			$this->Ln(6);
			$this->Ln();	$this->SetFont('Times','B',14);
			$this->Cell(25,6,'RIF:'.strtoupper($rif),			0,0,'L',0);
	        $this->SetFont('Times','B',11);
			//$this->Cell(25,6,strtoupper($rif),0,0,'L',0);
			$this->Ln();
			$this->SetFont('Times','B',11);
			$this->Cell(25,6,'PARTIDA:'.$partida,			0,0,'L',0);
		//	$this->Cell(30,6,$partida,			0,0,'R',0);
			$this->Ln(6);
			$this->SetFont('Times','B',8);
			$this->Cell(20,6,		     'Nº FACTURA',			1,0,'L',0);
			$this->Cell(15,6,		     'FECHA V.',			1,0,'L',0);
			$this->Cell(20,6,		     'MONTO.BR',			1,0,'L',0);
			$this->Cell(20,6,		     'BASE.Imp',			1,0,'L',0);
			$this->Cell(20,6,		     'ALICUOTA',			1,0,'L',0);
			$this->Cell(20,6,		     '%IVA',			1,0,'L',0);
			$this->Cell(30,6,		     'SUBTOTAL',			1,0,'L',0);
			$this->Cell(25,6,		     'RETIVA',			1,0,'L',0);
			$this->Cell(35,6,		     'SUBTOTAL RET IVA',			1,0,'L',0);
			$this->Cell(20,6,		     'RET ISLR',			1,0,'L',0);
			$this->Cell(28,6,		     'RET EXTRAS',			1,0,'L',0);
			$this->Cell(17,6,		     'TOTAL',			1,0,'L',0);
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
			$this->Cell(125,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Ln();
			$this->SetFillColor(0);
			$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$counter_striker=0;
	//$pdf->SetAutoPageBreak(auto,50);	
	while(!$row->EOF) 
	{	
		$fechas=substr($row->fields("fecha_vencimiento"),0,10);
		$dia=substr($row->fields("fecha_vencimiento"),8,2);
		$mes=substr($row->fields("fecha_vencimiento"),5,2);
		$ayo=substr($row->fields("fecha_vencimiento"),0,4);
		
		$fecha=$dia."/".$mes."/".$ayo;
		$tipo=$row->fields("doc"); 
		$tipo=substr($tipo,0,15);
					
		$base=$row->fields("monto_base_imponible");
		$bruto=$row->fields("monto_bruto");
		$iva=$row->fields("porcentaje_iva");
		$porcentaje_iva_ret=$row->fields("porcentaje_retencion_iva");
		$porcentaje_islr_ret=$row->fields("porcentaje_retencion_islr");
		$islr=($bruto)*($porcentaje_islr_ret/100);
	
		//operaciones
		//si el documento es factura con anticipo
		if(($tipos_fact==$row->fields("tipo_documentocxp"))&&($row->fields("amortizacion")!='0'))
		{
			$bruto=$row->fields("monto_bruto");
			$amort=$row->fields("amortizacion");
		//	$m_ant=$bruto+$amort;
			//prueba en el caso de estar mal el calculo iva e islr para anticipós cambiar esto
		//	$base=$m_ant;
		//	$islr=($m_ant)*($porcentaje_islr_ret/100);
			//
			$base=$bruto;
			$islr=($bruto)*($porcentaje_islr_ret/100);
		}
			//$retislr=$islr;
		$base_iva=($base)*($iva/100);
		$monto_restar=($base_iva)*(($porcentaje_iva_ret)/100);
		$total_iva=$base_iva-$monto_restar;//
		$ret1=$row->fields("retencion_ex1");
		$ret2=$row->fields("retencion_ex2");
		$retenciones=$ret1+$ret2;
		//-
		$monto_total=($bruto)+($total_iva)-$islr;	
		$total_facturado=($bruto)+($base_iva);	
		$sub_total_ret_iva=$total_facturado-$monto_restar;	
		$monto_total=($bruto)+($total_iva)-($islr+$retenciones);
		$total_ret=$ret1+$ret2;									
						$pdf->SetFont('Arial','B',7);
						$pdf->Cell(20,6,$row->fields("numero_documento"),1,0,'L',1);
						$pdf->Cell(15,6,$fecha,1,0,'L',1);
						$pdf->Cell(20,6,number_format($bruto,2,',','.'),1,0,'L',1);
						$pdf->Cell(20,6,number_format($base,2,',','.'),1,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("porcentaje_iva"),2,',','.'),1,0,'L',1);
						$pdf->Cell(20,6,number_format($base_iva,2,',','.'),1,0,'L',1);
  						$pdf->Cell(30,6,number_format($total_facturado,2,',','.'),1,0,'L',1);
						$pdf->Cell(25,6,number_format($monto_restar,2,',','.'),1,0,'L',1);
						$pdf->Cell(35,6,number_format($sub_total_ret_iva,2,',','.'),1,0,'L',1);
						$pdf->Cell(20,6,number_format($islr,2,',','.'),1,0,'L',1);
						$pdf->Cell(28,6,number_format($total_ret,2,',','.'),1,0,'L',1);
						$pdf->Cell(17,6,number_format($monto_total,2,',','.'),1,0,'L',1);}
						
						$pdf->Ln();
							$monto_total_next=$monto_total_next+$monto_total;
							$counter_striker++;
							
							$row->MoveNext();
	}
	if($counter_striker>1)
	{
		$pdf->Cell(17,6,number_format($monto_total_next,2,',','.'),1,0,'L',1);}
		}
	$pdf->Cell(17,6,number_format($monto_total_next,2,',','.'),1,0,'L',1);}
	
$pdf->Ln(20);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,6,"Elaborado por:",0,0,'L',1);
	$pdf->Ln(15);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(30,6,$nombre_preparado,0,0,'L',1);
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30,6,$comentario,0,0,'L',1);
//	$pdf->SetFont('Arial','B',12);
	//	$pdf->Cell(250,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
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