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
//DATOS PARA CARGAR DOCUMENTOS..
$sql_ant="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='ANTICIPOS')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_ant =& $conn->Execute($sql_ant);
$tipos_ant=$rs_tipos_ant->fields("id_tipo_documento");
//
$sql_fact="SELECT tipo_documento_cxp.nombre AS nombre,id_tipo_documento FROM tipo_documento_cxp WHERE (upper(nombre) ='FACTURA')AND (id_organismo = ".$_SESSION["id_organismo"].")";
$rs_tipos_fact =& $conn->Execute($sql_fact);
$tipos_fact=$rs_tipos_fact->fields("id_tipo_documento");
//
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
} 	
if(isset($_GET['empleado']))
{
	$beneficiario=$_GET['empleado'];
	if($beneficiario!="")
	{
		$where.=" AND documentos_cxp.cedula_rif_beneficiario='$beneficiario'";
		$a=" AND documentos_cxp.cedula_rif_beneficiario='$beneficiario'";
		
	}
} 	     
 if(isset($_GET['proveedor']))
{
	$proveedor=$_GET['proveedor'];
	if($proveedor!="")
	{
		$where.=" AND documentos_cxp.id_proveedor='$proveedor'";
	}
}
	     	     
if(isset($_GET[tipo]))	
{
	$tipo=$_GET[tipo];
	if($tipo!=0)
	$where.="AND documentos_cxp.tipo_documentocxp='$tipo'";
	
}	
if(isset($_GET['opcion_prove'])&&($proveedor=="")&&($beneficiario==""))
{
	$op=$_GET['opcion_prove'];
	if($op=='1')
		{
			$where.=" AND documentos_cxp.id_proveedor!='0'";
			
		}
	else
		if($op=='2')
			$where.=" AND documentos_cxp.id_proveedor=NULL
					 OR	documentos_cxp.id_proveedor='0'
			";

}
//**************************** validando que tipo de reporte es *******************//////////
if(($proveedor=="")and($beneficiario=="") and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="VACIO";
}else
if(($proveedor=="")and($beneficiario=="")and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
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

}			  
else
if((($proveedor!="")or($beneficiario!=""))and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="proveedor";
}
else
if((($proveedor!="")or($beneficiario!=""))and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="TODOS";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
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
				 documentos_cxp.numero_compromiso,
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
				 documentos_cxp.tipo_documentocxp,
		     	 documentos_cxp.amortizacion,
				 documentos_cxp.retencion_ex1,
				 documentos_cxp.retencion_ex2,
				 documentos_cxp.orden_pago 
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
			$where
			ORDER BY
				 documentos_cxp.fecha_documento
";
			
$row=& $conn->Execute($Sql);
if($tipo_de_reporte!="VACIO")
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
			$this->SetFont('Arial','B',16);
			if($tipo_de_reporte=="VACIO")
			{
					$this->Cell(0,10,'DOCUMENTOS CUENTAS POR PAGAR',0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
					$this->Ln(10);
					$this->SetFont('Times','B',8);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) ;
					$this->SetTextColor(0);
					$this->Cell(25,6,		     'DOCUMENTO',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº DOC',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº CONTR',			0,0,'L',1);
					$this->Cell(30,6,		     'PROVEEDOR',			0,0,'L',1);
					$this->Cell(20,6,		     'FECHA V.',			0,0,'L',1);
					$this->Cell(23,6,		     'COMPROMISO',			0,0,'L',1);
					$this->Cell(20,6,		     'ORD PAGO',			0,0,'L',1);
					$this->Cell(20,6,		     'M.BRUTO.',			0,0,'L',1);
					$this->Cell(20,6,		     'BASE IMP.',			0,0,'L',1);
					$this->Cell(15,6,		     '% IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET ISLR',			0,0,'L',1);
					$this->Cell(15,6,		     'RET EXT',			0,0,'L',1);
					$this->Cell(20,6,		     'TOTAL',			0,0,'L',1);
					$this->Ln(6);
			}else
///////////////////////////////////////////////////////////////////////////////////////////////			
			if($tipo_de_reporte=="TODOS")
			{
					$this->Cell(0,10,'DOCUMENTOS CUENTAS POR PAGAR',0,0,'C');
					$this->ln();
					$this->Cell(0,10,'BENEFICIARIO:'.$proveedor.$A,0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'DOCUMENTO:'.strtoupper($nombre_documento),0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
					$this->Ln(10);
					$this->SetFont('Times','B',8);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) ;
					$this->SetTextColor(0);
					$this->Cell(20,6,		     'Nº DOC',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº CONTR',			0,0,'L',1);
					$this->Cell(20,6,		     'FECHA V.',			0,0,'L',1);
					$this->Cell(23,6,		     'COMPROMISO',			0,0,'L',1);
					$this->Cell(20,6,		     'ORD PAGO',			0,0,'L',1);
					$this->Cell(20,6,		     'M.BRUTO.',			0,0,'L',1);
					$this->Cell(20,6,		     'BASE IMP.',			0,0,'L',1);
					$this->Cell(25,6,		     '% IVA',			0,0,'L',1);
					$this->Cell(25,6,		     'RET IVA',			0,0,'L',1);
					$this->Cell(25,6,		     'RET ISLR',			0,0,'L',1);
					$this->Cell(25,6,		     'RET EXT',			0,0,'L',1);
					$this->Cell(35,6,		     'TOTAL',			0,0,'L',1);
					$this->Ln(6);
			}else
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			if($tipo_de_reporte=="proveedor")
			{
					$this->Cell(0,10,'DOCUMENTOS CUENTAS POR PAGAR ',0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'PROVEEDOR'.":".$proveedor,0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
					$this->Ln(10);
					$this->SetFont('Times','B',8);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) ;
					$this->SetTextColor(0);
					$this->Cell(25,6,		     'DOCUMENTO',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº DOC',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº CONTR',			0,0,'L',1);
					$this->Cell(20,6,		     'FECHA V.',			0,0,'L',1);
					$this->Cell(23,6,		     'COMPROMISO',			0,0,'L',1);
					$this->Cell(20,6,		     'ORD PAGO',			0,0,'L',1);
					$this->Cell(20,6,		     'M.BRUTO.',			0,0,'L',1);
					$this->Cell(20,6,		     'BASE IMP.',			0,0,'L',1);
					$this->Cell(15,6,		     '% IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET ISLR',			0,0,'L',1);
					$this->Cell(15,6,		     'RET EXT',			0,0,'L',1);
					$this->Cell(25,6,		     'TOTAL',			0,0,'L',1);
					$this->Ln(6);
			}else	
			if($tipo_de_reporte=="tipo")
			{
					$this->Cell(0,10,'DOCUMENTOS CUENTAS POR PAGAR ',0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'DOCUMENTO'.":".strtoupper($nombre_documento),0,0,'C');
					$this->Ln();
					$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
					$this->Ln(10);
					$this->SetFont('Times','B',8);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) ;
					$this->SetTextColor(0);
					$this->Cell(20,6,		     'Nº DOC',			0,0,'L',1);
					$this->Cell(20,6,		     'Nº CONTR',			0,0,'L',1);
					$this->Cell(30,6,		     'PROVEEDOR',			0,0,'L',1);
					$this->Cell(20,6,		     'FECHA V.',			0,0,'L',1);
					$this->Cell(23,6,		     'COMPROMISO',			0,0,'L',1);
					$this->Cell(20,6,		     'ORD PAGO',			0,0,'L',1);
					$this->Cell(20,6,		     'M.BRUTO.',			0,0,'L',1);
					$this->Cell(20,6,		     'BASE IMP.',			0,0,'L',1);
					$this->Cell(15,6,		     '% IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET IVA',			0,0,'L',1);
					$this->Cell(15,6,		     'RET ISLR',			0,0,'L',1);
					$this->Cell(15,6,		     'RET EXT',			0,0,'L',1);
					$this->Cell(25,6,		     'TOTAL',			0,0,'L',1);
					$this->Ln(6);
			}	
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
			$this->SetFillColor(0);
			$this->Code128(125,200,strtoupper($_SESSION['usuario']),40,6);}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	//$pdf->SetAutoPageBreak(auto,50);	
	
	while (!$row->EOF) 
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$numero_compromiso=$row->fields("numero_compromiso");
			$orden_pago=$row->fields("orden_pago");

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
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
		$ret1=$row->fields("retencion_ex1");
		$ret2=$row->fields("retencion_ex2");
		$retenciones=$ret1+$ret2;
		//$retislr=$islr;
////////////--- si es factura con anticipo
		if((($row->fields("tipo_documentocxp"))==$tipos_fact)&&($row->fields("amortizacion")!='0'))
		{
			$bruto=$row->fields("monto_bruto");
			$amortizacion=$row->fields("amortizacion");
			$base=($bruto+$amortizacion);
			$islr=((($base)*($porcentaje_islr_ret))/100);

		}else
		$islr=($bruto)*($porcentaje_islr_ret/100);
/////////////
		$base_iva=($base)*($iva/100);
		$monto_restar=($base_iva)*(($porcentaje_iva_ret)/100);
		$total_iva=$base_iva-$monto_restar;//
		//-
//		$islr=($bruto)*($porcentaje_islr_ret/100);
		$monto_total=($bruto)+($total_iva)-$islr;
		$monto_total=$monto_total-$retenciones;
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

//////////////////////////////////////////////////////////////////////////////////////////////////////		
			if($tipo_de_reporte=="VACIO")
			{													
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(25,6,$tipo,0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_control"),0,0,'L',1);
						$pdf->Cell(30,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(20,6,$fecha,0,0,'L',1);
						$pdf->Cell(23,6,$numero_compromiso,0,0,'L',1);
						$pdf->Cell(20,6,$orden_pago,0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_bruto"),2,',','.'),0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($row->fields("porcentaje_iva"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($monto_restar,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($islr,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($retenciones,2,',','.'),0,0,'L',1);
						$pdf->Cell(20,6,number_format($monto_total,2,',','.'),0,0,'L',1);
						$total_general=$total_general+$monto_total;
						
			}else	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		if($tipo_de_reporte=="TODOS")
			{													
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(20,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_control"),0,0,'L',1);
						$pdf->Cell(20,6,$fecha,0,0,'L',1);
						$pdf->Cell(23,6,$numero_compromiso,0,0,'L',1);
						$pdf->Cell(20,6,$orden_pago,0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_bruto"),2,',','.'),0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($row->fields("porcentaje_iva"),2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($monto_restar,2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($islr,2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($retenciones,2,',','.'),0,0,'L',1);
						$pdf->Cell(35,6,number_format($monto_total,2,',','.'),0,0,'L',1);
						$total_general=$total_general+$monto_total;
			}else
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			if($tipo_de_reporte=="proveedor")
			{			$pdf->SetFont('Arial','B',10);
						$pdf->Cell(25,6,$tipo,0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_control"),0,0,'L',1);
						$pdf->Cell(20,6,$fecha,0,0,'L',1);
						$pdf->Cell(23,6,$numero_compromiso,0,0,'L',1);
						$pdf->Cell(20,6,$orden_pago,0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_bruto"),2,',','.'),0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($row->fields("porcentaje_iva"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($monto_restar,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($islr,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($retenciones,2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($monto_total,2,',','.'),0,0,'L',1);
						$total_general=$total_general+$monto_total;
						
				
					
			}
			else
			if($tipo_de_reporte=="tipo")
			{			$pdf->SetFont('Arial','B',10);
						$pdf->Cell(20,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(20,6,$row->fields("numero_control"),0,0,'L',1);
						$pdf->Cell(30,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(20,6,$fecha,0,0,'L',1);
						$pdf->Cell(23,6,$numero_compromiso,0,0,'L',1);
						$pdf->Cell(20,6,$orden_pago,0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_bruto"),2,',','.'),0,0,'L',1);
						$pdf->Cell(20,6,number_format($row->fields("monto_base_imponible"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($row->fields("porcentaje_iva"),2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($monto_restar,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($islr,2,',','.'),0,0,'L',1);
						$pdf->Cell(15,6,number_format($retenciones,2,',','.'),0,0,'L',1);
						$pdf->Cell(25,6,number_format($monto_total,2,',','.'),0,0,'L',1);
						$total_general=$total_general+$monto_total;
			}	
							$pdf->Ln();
							$row->MoveNext();
						
		
	}
	//	$pdf->SetFont('Arial','B',12);
		$pdf->Cell(280,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
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