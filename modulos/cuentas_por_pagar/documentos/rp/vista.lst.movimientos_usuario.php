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
 if(isset($_GET['usuario']))
{
	$usuario=$_GET['usuario'];
	if($usuario!="")
	{
		$where.=" AND documentos_cxp.ultimo_usuario='$usuario'";
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
/*//**************************** validando que tipo de reporte es *******************//////////
if(($proveedor=="")and($beneficiario=="")and($usuario=="") and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="VACIO";
}else
if(($usuario!="")and($proveedor=="")and($beneficiario=="") and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="usuario";
}else
if(($proveedor=="")and($beneficiario=="")and($usuario=="") and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
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
if((($proveedor!="")or($beneficiario!=""))and ($usuario=="")and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="proveedor";
}
else
if((($proveedor!="")or($beneficiario!=""))and($usuario=="")and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="PROVEEDOR-TIPO";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
}else
if((($proveedor=="")and($beneficiario==""))and($usuario!="")and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="USUARIO-TIPO";
	$sql_tipo="
				SELECT
						nombre 
				FROM
					tipo_documento_cxp
				WHERE
					 id_tipo_documento='$tipo'";
	$row_tipo=& $conn->Execute($sql_tipo);
	$nombre_documento=$row_tipo->fields("nombre");
}else
if((($proveedor!="")or($beneficiario!=""))and($usuario!="")and ($tipo=="0") and  ($desde!="") and ($hasta!=""))
{
	$tipo_de_reporte="PROVE-US";
	
}

else
if((($proveedor!="")or($beneficiario!=""))and($usuario!="")and ($tipo!="0") and  ($desde!="") and ($hasta!=""))
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
				 documentos_cxp.numero_control,
				 documentos_cxp.fecha_vencimiento,
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.monto_bruto,
				 documentos_cxp.monto_base_imponible,
				 documentos_cxp.numero_compromiso,
				 documentos_cxp.comentarios,
				 documentos_cxp.ultimo_usuario,
				 usuario.nombre,
				 usuario.apellido,
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
				 documentos_cxp.id_documentos
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
		
										
///************************************************************************

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
			global $nombre_usuario;
			global $a;
			global $tipo_de_reporte;
						$nom=$row->fields("nombre");
						$ape=$row->fields("apellido");
						$nombre_usuario=$nom."  ".$ape;		
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
			$this->SetFont('Arial','B',10);
			if($tipo_de_reporte=="USUARIO-TIPO")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"USUARIO:".strtoupper($nombre_usuario),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"DOCUMENTO:".strtoupper($nombre_documento) ,0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'PROVEEDOR',			0,0,'C',1);
			$this->Cell(60,6,		     'Nº DOCUMENTO',			0,0,'C',1);
			$this->Cell(60,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="PROVEEDOR-TIPO")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,strtoupper($proveedor),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"DOCUMENTO:".strtoupper($nombre_documento) ,0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'USUARIO',			0,0,'C',1);
			$this->Cell(60,6,		     'Nº DOCUMENTO',			0,0,'C',1);
			$this->Cell(60,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="VACIO")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(35,6,		     'USUARIO',			0,0,'L',1);
			$this->Cell(35,6,		     'DOCUMENTO',			0,0,'L',1);
			$this->Cell(35,6,		     'Nº DOCUMENTO',			0,0,'L',1);
			$this->Cell(35,6,		     'PROVEEDOR',			0,0,'L',1);
			$this->Cell(35,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="usuario")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,strtoupper($nombre_usuario),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(45,6,		     'DOCUMENTO',			0,0,'L',1);
			$this->Cell(45,6,		     'Nº DOCUMENTO',			0,0,'L',1);
			$this->Cell(45,6,		     'PROVEEDOR',			0,0,'L',1);
			$this->Cell(45,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}	else
			if($tipo_de_reporte=="proveedor")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,strtoupper($proveedor),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(45,6,		     'USUARIO',			0,0,'L',1);
			$this->Cell(45,6,		     'DOCUMENTO',			0,0,'L',1);
			$this->Cell(45,6,		     'Nº DOCUMENTO',			0,0,'L',1);
			$this->Cell(45,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="tipo")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"DOCUMENTO:".strtoupper($nombre_documento) ,0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(45,6,		     'USUARIO',			0,0,'L',1);
			$this->Cell(45,6,		     'Nº DOCUMENTO',			0,0,'L',1);
			$this->Cell(45,6,		     'PROVEEDOR',			0,0,'L',1);
			$this->Cell(45,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="PROVE-US")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"USUARIO:".strtoupper($nombre_usuario),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"PROVEEDOR:".strtoupper($proveedor),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(60,6,		     'DOCUMENTO',			0,0,'C',1);
			$this->Cell(60,6,		     'Nº DOCUMENTO',			0,0,'C',1);
			$this->Cell(60,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
			}else
			if($tipo_de_reporte=="TODOS")
			{
///////////////////////////////////////////////////////////////////////////////////////
			$this->Cell(0,10,'MOVIMIENTOS POR USUARIO',0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"USUARIO:"." ".strtoupper($nombre_usuario),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"BENEFICIARIO:"." ".strtoupper($proveedor),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,"DOCUMENTO:"." ".strtoupper($nombre_documento) ,0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(80,6,		     'Nº DOCUMENTO',			0,0,'C',1);
			$this->Cell(80,6,		     'FECHA V.',			0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////
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
	
	while (!$row->EOF) 
	{	
		$nom=$row->fields("nombre");
		$ape=$row->fields("apellido");
		$nombre_usuario=$nom."  ".$ape;
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
		//$retislr=$islr;
		$base_iva=($base)*($iva/100);
		$monto_restar=($base_iva)*(($porcentaje_iva_ret)/100);
		$total_iva=$base_iva-$monto_restar;//
		//-
		$islr=($bruto)*($porcentaje_islr_ret/100);
		$monto_total=($bruto)+($total_iva)-$islr;
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
				if($tipo_de_reporte=="USUARIO-TIPO")
			{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(60,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(60,6,$row->fields("numero_documento"),0,0,'C',1);
						$pdf->Cell(60,6,$fecha,0,0,'C',1);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}			
			
				if($tipo_de_reporte=="PROVEEDOR-TIPO")
			{
///////////////////////////////////////////////////////////////////////////////////////
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(60,6,strtoupper($nombre_usuario),0,0,'C',1);
						$pdf->Cell(60,6,$row->fields("numero_documento"),0,0,'C',1);
						$pdf->Cell(60,6,$fecha,0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////
			}
			
			
			if($tipo_de_reporte=="VACIO")
			{
//////////////////////////////////////////////////////////////////////////////////////////////////////		
						
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(35,6,strtoupper($nombre_usuario),0,0,'L',1);
						$pdf->Cell(35,6,$tipo,0,0,'L',1);
						$pdf->Cell(35,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(35,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(35,6,$fecha,0,0,'C',1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}else
		if($tipo_de_reporte=="usuario")
			{
//////////////////////////////////////////////////////////////////////////////////////////////////////		
						
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(45,6,$tipo,0,0,'L',1);
						$pdf->Cell(45,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(45,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(45,6,$fecha,0,0,'C',1);
						
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}else
		if($tipo_de_reporte=="proveedor")
			{
//////////////////////////////////////////////////////////////////////////////////////////////////////		
						
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(45,6,strtoupper($nombre_usuario),0,0,'L',1);
						$pdf->Cell(45,6,$tipo,0,0,'L',1);
						$pdf->Cell(45,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(45,6,$fecha,0,0,'C',1);
						
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		}else
		if($tipo_de_reporte=="tipo")
			{
///////////////////////////////////////////////////////////////////////////////////////
						$pdf->Cell(45,6,strtoupper($nombre_usuario),0,0,'L',1);
						$pdf->Cell(45,6,$row->fields("numero_documento"),0,0,'L',1);
						$pdf->Cell(45,6,strtoupper($proveedor),0,0,'L',1);
						$pdf->Cell(45,6,$fecha,0,0,'C',1);
						
///////////////////////////////////////////////////////////////////////////////////////		
			}else
			if($tipo_de_reporte=="PROVE-US")
			{
///////////////////////////////////////////////////////////////////////////////////////
						$pdf->Cell(60,6,$tipo,0,0,'C',1);
						$pdf->Cell(60,6,$row->fields("numero_documento"),0,0,'C',1);
						$pdf->Cell(60,6,$fecha,0,0,'C',1);
///////////////////////////////////////////////////////////////////////////////////////
			}else
		if($tipo_de_reporte=="TODOS")
			{
///////////////////////////////////////////////////////////////////////////////////////
						$pdf->Cell(80,6,$row->fields("numero_documento"),0,0,'C',1);
						$pdf->Cell(80,6,$fecha,0,0,'C',1);
///////////////////////////////////////////////////////////////////////////////////////					
			}							
							$pdf->Ln();
							$row->MoveNext();
						
		
	}
	//	$pdf->SetFont('Arial','B',12);
	//	$pdf->Cell(265,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
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