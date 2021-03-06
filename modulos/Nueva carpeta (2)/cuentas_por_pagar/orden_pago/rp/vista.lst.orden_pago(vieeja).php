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

///formatos de fechas llevada sal espa�ol
// Obtenemos y traducimos el nombre del d�a
/*$dia=date("l");
if ($dia=="Monday") $dia="Lunes";
if ($dia=="Tuesday") $dia="Martes";
if ($dia=="Wednesday") $dia="Mi�rcoles";
if ($dia=="Thursday") $dia="Jueves";
if ($dia=="Friday") $dia="Viernes";
if ($dia=="Saturday") $dia="Sabado";
if ($dia=="Sunday") $dia="Domingo";

// Obtenemos el n�mero del d�a
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
*/
// Obtenemos el a�o
$ano=date("Y");
if(isset($_GET['ano']))
{
	$anos=$_GET['ano'];
	$where.="AND ano='$anos'";
}

if($_GET['caducidad']==0)
{
	$caducidad="";
}else
	{
		switch($_GET['caducidad'])
		{
		case 1:
			$caducidad="CADUCA A LOS 15 D�AS";
			break;
		case 2:		
			$caducidad="CADUCA A LOS 60 D�AS";
			break;
		case 3:
			$caducidad="CADUCA A LOS 90 D�AS";
			break;
		case 4:
			$caducidad="CADUCA A LOS 120 D�AS";
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
//$hasta=$_GET['hasta'];
/////////////////////PARA TRAER LA RET EXTRA EN CASO DE QUE SEA NECESARIO
$Sql="
			SELECT 
				 documentos_cxp.porcentaje_iva,
				 documentos_cxp.porcentaje_retencion_iva,
				 documentos_cxp.porcentaje_retencion_islr,
				 documentos_cxp.retencion_ex1,
				 documentos_cxp.retencion_ex2,
				 documentos_cxp.monto_base_imponible2,
  				 documentos_cxp.porcentaje_iva2,
				 documentos_cxp.retencion_iva2 ,
				 documentos_cxp.monto_base_imponible,
 				 documentos_cxp.monto_bruto		 
		 
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
			
$row_retext=& $conn->Execute($Sql);
if (!$row_retext->EOF)
{
 	if($row_retext->fields("retencion_ex1")!=0)
	{
		$alpha="ret";
		$iva_rep1=$row_retext->fields("porcentaje_iva");
		$iva_rep2=$row_retext->fields("porcentaje_retencion_iva");
		$islr_rep=$row_retext->fields("porcentaje_retencion_islr");
	
	}
	if($row_retext->fields("monto_base_imponible2")!=0)
	{
		$alpha2="dos_ivas";
		$iva2=$row_retext->fields("porcentaje_iva2");
		$base2=$row_retext->fields("monto_base_imponible2");
		$ret_iva2=$row_retext->fields("retencion_iva2");
	
	}
	if($row_retext->fields("porcentaje_retencion_islr")!=0)
	{
		$islr_opcion="islr";
	}
	if($row_retext->fields("monto_bruto")!=$row_retext->fields("monto_base_imponible"))
	{
		$exnto="exnto";
	}
}
////////////////////////////////////////////////
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
				 documentos_cxp.amortizacion,
				 documentos_cxp.fecha_documento,
				 documentos_cxp.monto_base_imponible2,
  				 documentos_cxp.porcentaje_iva2,
				 documentos_cxp.retencion_iva2,
				 documentos_cxp.monto_base_imponible		 
				 
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
if (!$row->EOF)
{ 
//////////////////////////////////////////////////////////////////////////////////////////////////
$sql_orden="SELECT 
											fecha_orden_pago
										FROM 
											orden_pago
										WHERE
												orden_pago.orden_pago='$orden'"	;
										
									$row_orden_fecha=& $conn->Execute($sql_orden);
									if(!$row_orden_fecha->EOF)
									{
										$fecha_ord=$row_orden_fecha->fields('fecha_orden_pago');
										$dia=substr($fecha_ord,8,2);
										$mes=substr($fecha_ord,5,2);
										$ano=substr($fecha_ord,0,4);
										
										
										
										if ($mes=="01") $mes2="Enero";
										if ($mes=="02") $mes2="Febrero";
										if ($mes=="03") $mes2="Marzo";
										if ($mes=="04") $mes2="Abril";
										if ($mes=="05") $mes2="Mayo";
										if ($mes=="06") $mes2="Junio";
										if ($mes=="07") $mes2="Julio";
										if ($mes=="08") $mes2="Agosto";
										if ($mes=="09") $mes2="Setiembre";
										if ($mes=="10") $mes2="Octubre";
										if ($mes=="11") $mes2="Noviembre";
										if ($mes=="12") $mes2="Diciembre";

									}
//************************************************************************
				
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

	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de p�gina
		function Header()
		{	global $nombre_usuario;	
			global $row;
			global $desde;
			global $hasta;
			global $orden;
			global $dia;
			global $mes2;
			global $ano;
			global $partida;
			global $nombre;global $rif;
			global $alpha;
			global $iva_rep1;
			global $iva_rep2;
			global $islr_rep;
			global $islr_opcion;
			global $exnto;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
				$this->SetFont('Times','B',10);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');	$this->Ln();	
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
			$this->Cell(50,6,'ORDEN DE PAGO N�:       ',			0,0,'L',0);
			$this->Cell(10,6,$orden,			0,0,'R',0);
			$this->Ln();
			$this->SetFont('Times','B',11);
			$this->Cell(25,6,'NOMBRE:',			0,0,'L',0);
	        $this->SetFont('Times','B',11);
			$this->Cell(25,6,strtoupper($nombre),0,0,'L',0);
			$this->SetFont('Times','B',11);
			$this->Cell(150,6,'FECHA:',			0,0,'R',0);
			$this->Cell(150,4," $dia de $mes2 de $ano",0,0,'L');

	       
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
			$this->Cell(30,6,		     'N� FACTURA',			1,0,'L',0);
			$this->Cell(15,6,		     'FECHA ',			1,0,'L',0);
		//	$this->Cell(20,6,		     'MONTO.BR',			1,0,'L',0);
			$this->Cell(25,6,		     'BASE.Imp',			1,0,'L',0);
			$this->Cell(20,6,		     'ALICUOTA',			1,0,'L',0);
			$this->Cell(20,6,		     'IVA'.$iva_rep1,			1,0,'L',0);
			if($exnto=="exnto")
			{
			
						$this->Cell(20,6,		     'Comp.Exenta'.$iva_rep1,			1,0,'L',0);
	
			}

			$this->Cell(30,6,		     'TOTAL FACTURA',			1,0,'L',0);
			$this->Cell(25,6,		     'RET IVA'.$iva_rep2,			1,0,'L',0);
			$this->Cell(35,6,		     'SUB TOTAL',			1,0,'L',0);
			if($islr_opcion=="islr")
			{
				$this->Cell(20,6,		     'RET ISLR'.$islr_rep,			1,0,'L',0);
			}
			if($alpha=="ret")
			{
				$this->Cell(28,6,		     'RET EXTRAS',			1,0,'L',0);
			}
			$this->Cell(25,6,		     'TOTAL A PAGAR',			1,0,'L',0);
		
			
				$this->Ln(6);
			
		
		}
		//Pie de p�gina
		function Footer()
		{
			//Posici�n: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);

			//N�mero de p�gina
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
	//$pdf->SetAutoPageBreak(auto,50);	
	while (!$row->EOF) 
	{	
		$fechas=substr($row->fields("fecha_vencimiento"),0,10);
		$dia=substr($row->fields("fecha_vencimiento"),8,2);
		$mes=substr($row->fields("fecha_vencimiento"),5,2);
		$ayo=substr($row->fields("fecha_vencimiento"),0,4);
		$fechas2=substr($row->fields("fecha_documento"),0,10);
		$dia2=substr($row->fields("fecha_documento"),8,2);
		$mes2=substr($row->fields("fecha_documento"),5,2);
		$ayo2=substr($row->fields("fecha_documento"),0,4);
		
		$fecha=$dia."/".$mes."/".$ayo;
		$fecha2=$dia2."/".$mes2."/".$ayo2;

		$tipo=$row->fields("doc"); 
		$tipo=substr($tipo,0,15);
					
		$base=$row->fields("monto_base_imponible");
		$bruto=$row->fields("monto_bruto");
		$iva=$row->fields("porcentaje_iva");
		$porcentaje_iva_ret=$row->fields("porcentaje_retencion_iva");
		$porcentaje_islr_ret=$row->fields("porcentaje_retencion_islr");
		$islr=($bruto)*($porcentaje_islr_ret/100);
		if($alpha2=="dos_ivas")
		{
			$base=$row->fields("monto_base_imponible");
			$base2=$row->fields("monto_base_imponible2");
			$iva=$row->fields("porcentaje_iva");
			$iva2=$row->fields("porcentaje_iva2");
			$base_iva=($base)*($iva/100);
			$base_iva2=($base2)*($iva2/100);
			$compra_excenta=$bruto-($base+$base2);
			$total_facturado=$bruto+$base_iva+$base_iva2;
			$retencion_iva1=$row->fields("porcentaje_retencion_iva");
			$retencion_iva2=$row->fields("porcentaje_iva2");
			$ret_base1=($base_iva*$retencion_iva1)/100;
			$ret_base2=($base_iva2*$retencion_iva2)/100;
			$ret_total=$ret_base1+$ret_base2;
			/*
			/////////////////////////////////////////////////
			*/
			$ret1=$row->fields("retencion_ex1");
			$ret2=$row->fields("retencion_ex2");
			$retenciones=$ret1+$ret2+$ret_total;
			$sub_total_ret_iva1=$total_facturado-$ret_base1;
			$sub_total_ret_iva2=$total_facturado-$ret_base2;
			$monto_total=$sub_total_ret_iva1+$sub_total_ret_iva2;
		//	$monto_total=$total_facturado-($islr+$retenciones);
			$total_ret=$ret1+$ret2;									
			/*
			///////////////////////////////////////////////
			*/
		}else
		{
		
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
		
		}	
	
		//operaciones
		//si el documento es factura con anticipo
		if(($tipos_fact==$row->fields("tipo_documentocxp"))&&($row->fields("amortizacion")!='0'))
		{
			$bruto=$row->fields("monto_bruto");
			$amort=$row->fields("amortizacion");
			$m_ant=$bruto+$amort;
			/*$base=$m_ant;
			$islr=($m_ant)*($porcentaje_islr_ret/100);
			*/
				//prueba en el caso de estar mal el calculo iva e islr para anticip�s cambiar esto
		//	$base=$m_ant;
		//	$islr=($m_ant)*($porcentaje_islr_ret/100);
			//
			$base=$bruto;
			$islr=($bruto)*($porcentaje_islr_ret/100);
		}
			//$retislr=$islr;
						if($alpha2=="dos_ivas")
						{
							$pdf->Cell(30,12,"",1,0,'L',1);
							$pdf->Cell(15,6,"",1,0,'L',1);
							$pdf->Cell(25,6,number_format($base,2,',','.'),1,0,'C ',1);
							//$pdf->Cell(20,6,number_format($base,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($row->fields("porcentaje_iva"),2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($base_iva,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($compra_excenta,2,',','.'),1,0,'L',1);
							$pdf->Cell(30,6,number_format($total_facturado,2,',','.'),1,0,'L',1);
							$pdf->Cell(25,6,number_format($retencion_iva1,2,',','.'),1,0,'L',1);
							$pdf->Cell(35,6,number_format($sub_total_ret_iva1,2,',','.'),1,0,'L',1);
							if($islr!=0)
							{
								$pdf->Cell(20,6,number_format($islr,2,',','.'),1,0,'L',1);
							}
							if($alpha=="ret")
							{
								$pdf->Cell(28,6,number_format($total_ret,2,',','.'),1,0,'L',1);
							}
								$pdf->Cell(25,6,number_format($monto_total,2,',','.'),1,0,'L',1);
							$pdf->Ln();
							$pdf->Cell(30,6,$row->fields("numero_documento"),'LBR','C',1);
							$pdf->Cell(18,6,$fecha2,'LBR','C',1);
							$pdf->Cell(25,6,number_format($base,2,',','.'),1,0,'L',1);
							//$pdf->Cell(20,6,number_format($base,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($row->fields("porcentaje_iva"),2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($base_iva,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($compra_excenta,2,',','.'),1,0,'L',1);
							$pdf->Cell(30,6,number_format($total_facturado,2,',','.'),1,0,'L',1);
							$pdf->Cell(25,6,number_format($retencion_iva1,2,',','.'),1,0,'L',1);
							$pdf->Cell(35,6,number_format($sub_total_ret_iva1,2,',','.'),1,0,'L',1);
							if($islr!=0)
							{
								$pdf->Cell(20,6,number_format($islr,2,',','.'),1,0,'L',1);
							}
							if($alpha=="ret")
							{
								$pdf->Cell(28,6,number_format($total_ret,2,',','.'),1,0,'L',1);
							}
								$pdf->Cell(25,6,number_format($monto_total,2,',','.'),1,0,'L',1);

						}
						else
						{
							$pdf->SetFont('Arial','B',7);
							$pdf->Cell(30,6,$row->fields("numero_documento"),1,0,'L',1);
							$pdf->Cell(15,6,$fecha2,1,0,'L',1);
							$pdf->Cell(25,6,number_format($bruto,2,',','.'),1,0,'L',1);
							//$pdf->Cell(20,6,number_format($base,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($row->fields("porcentaje_iva"),2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($base_iva,2,',','.'),1,0,'L',1);
							$pdf->Cell(30,6,number_format($total_facturado,2,',','.'),1,0,'L',1);
							$pdf->Cell(25,6,number_format($monto_restar,2,',','.'),1,0,'L',1);
							$pdf->Cell(35,6,number_format($sub_total_ret_iva,2,',','.'),1,0,'L',1);
							$pdf->Cell(20,6,number_format($islr,2,',','.'),1,0,'L',1);
							if($alpha=="ret")
							{
								$pdf->Cell(28,6,number_format($total_ret,2,',','.'),1,0,'L',1);
							}
							$pdf->Cell(25,6,number_format($monto_total,2,',','.'),1,0,'L',1);
							$pdf->Ln();
						}
							$row->MoveNext();
							$monto_total_next=$monto_total_next+$monto_total;
							$counter_striker++;
						
		
	}
	if($counter_striker>1)
	{
						$pdf->Cell(30,6,"",0,0,'L',1);
						$pdf->Cell(15,6,"",0,0,'L',1);
						$pdf->Cell(25,6,"",0,0,'L',1);
						//$pdf->Cell(20,6,number_format($base,2,',','.'),1,0,'L',1);
						$pdf->Cell(20,6,"",0,0,'L',1);
						$pdf->Cell(20,6,"",0,0,'L',1);
  						$pdf->Cell(30,6,"",0,0,'L',1);
						$pdf->Cell(25,6,"",0,0,'L',1);
						$pdf->Cell(35,6,"",0,0,'L',1);
						$pdf->Cell(20,6,"",0,0,'L',1);
						if($alpha=="ret")
						{
							$pdf->Cell(28,6,"",1,0,'L',1);
						}

						$pdf->Cell(25,6,number_format($monto_total_next,2,',','.'),0,0,'L',1);
		
		}
	//$pdf->Cell(17,6,number_format($monto_total_next,2,',','.'),1,0,'L',1);
	
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
		//Cabecera de p�gina
		function Header()
		{		
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
			$this->SetFont('Arial','B',11);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministro de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');	$this->Ln();	
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
			$this->Ln();
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');	*/
		}
		//Pie de p�gina
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