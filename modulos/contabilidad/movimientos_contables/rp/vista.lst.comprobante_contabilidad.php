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
$where="WHERE  movimientos_contables.id_organismo=".$_SESSION["id_organismo"]."	";
$where.="and estatus!='3'";
$ano_comprobante=$_POST["contabilidad_mov_pr_ayo"];
if($ano_comprobante!="")
{
	$where.="AND ano_comprobante=$ano_comprobante";
}
//ano_comprobante
	if(isset($_GET["desde_numero"]))
	{
		if($_GET["desde_numero"]!="")
		{
			$desde_num=$_GET["desde_numero"];
			$where.="  and movimientos_contables.numero_comprobante >='$desde_num'";
		}else
		if($_GET["desde_numero"]=="")
		{
			$desde_num="";
		}	
	}
	if(isset($_GET["hasta_numero"]))
	{
		if($_GET["hasta_numero"]!="")
		{
		
			$hasta_num=$_GET["hasta_numero"];
			$where.="and   movimientos_contables.numero_comprobante <='$hasta_num'";
		}
		
		/*//otro caso
		if(($_GET["hasta_numero"]=="")&&($_GET["desde_numero"]!=""))
		{
			$hasta_num==$_GET["desde_numero"];
			$where.="and   movimientos_contables.numero_comprobante <='$hasta_num'";
		}*/
		if($_GET["hasta_numero"]=="")
		{
			$hasta_num="";
		}
	}
/*if(isset($_GET["desde_fecha"]))
{
	$desde_fecha=$_GET["desde_fecha"];
}
if(isset($_GET["hasta_fecha"]))
{
	if($hasta_fecha!="")
	{	
		$hasta_fecha=$_GET["hasta_fecha"];
		$where.=" and movimientos_contables.fecha_comprobante >= '$desde_fecha' ";
	}else
	if($hasta_fecha=="")
	{	
		$hasta_fecha=$_GET["hasta_fecha"];
		$where.="AND movimientos_contables.fecha_comprobante <='$hasta_fecha'";
	}
}
*/
/////////////////////////////////////////////////////////////////
list($dia,$mes,$ayo)=split("/",$hasta_fecha,3);
if(($dia=="30")&&($mes=="3"||$mes=='5'||$mes=='7'||$mes=='9'||$mes=='11'))
{
	$dia=1;
	$mes=$mes+1;
 }
 else
if($dia=="31")
{
	$dia=1;
	$mes=$mes+1;
	 if($mes=="12")
	 {
		$mes="1";
		$ayo=$ayo+1;
	  }	
 }
 else
 $dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;
/*{
	$where.=" and movimientos_contables.fecha_comprobante >= '$desde_fecha' ";
}*/
///////////////////////////////////////////////////////////////////
/*$where.="  and movimientos_contables.numero_comprobante >='$desde_num' and   movimientos_contables.numero_comprobante <='$hasta_num'";
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
*/
$sql_integracion_contable="
							SELECT  distinct
									movimientos_contables.cuenta_contable,
									movimientos_contables.numero_comprobante,
									movimientos_contables.secuencia,
									movimientos_contables.descripcion,
									movimientos_contables.referencia,
									movimientos_contables.debito_credito,
									movimientos_contables.monto_debito,
									movimientos_contables.monto_credito,
									movimientos_contables.fecha_comprobante ,
									movimientos_contables.id_tipo_comprobante,
									tipo_comprobante.nombre as tipo,
									tipo_comprobante.codigo_tipo_comprobante,
									cuenta_contable_contabilidad.nombre as descripcion_cuenta,
									movimientos_contables.id_auxiliar,
									movimientos_contables.fecha_comprobante,
									movimientos_contables.id_utilizacion_fondos,
									movimientos_contables.id_movimientos_contables
 
							from
									 movimientos_contables 
							INNER JOIN
										tipo_comprobante
								on
									movimientos_contables.id_tipo_comprobante=tipo_comprobante.id		 
							INNER JOIN
										cuenta_contable_contabilidad
								on
									cuenta_contable_contabilidad.cuenta_contable=movimientos_contables.cuenta_contable		
							$where
							order by
									
									movimientos_contables.numero_comprobante,
									movimientos_contables.secuencia,
									movimientos_contables.id_movimientos_contables,
									movimientos_contables.debito_credito
									";
$row=& $conn->Execute($sql_integracion_contable);
if(!$row->EOF)
{
	$id_utf=$row->fields("id_utilizacion_fondos");
	$sql_utf="select cuenta_utilizacion_fondos from utilizacion_fondos where id_utilizacion_fondos=$id_utf";
	$row_utf=& $conn->Execute($sql_utf);
	if(!$row_utf->EOF)
	{
		$cuenta_utf=$row_utf->fields("cuenta_utilizacion_fondos");
	}else
	$cuenta_utf="";

//************************************************************************
			class PDF extends PDF_Code128
			{
				//Cabecera de página
				function Header()
				{	global $numero_comprobante;
					global $fecha_comprobante;global $tipo_comprobante;	global $codigo_tipo_comprobante;	
					$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
					$this->SetFont('times','B',7);
					$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
					$this->Ln(4);
					$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
					$this->Ln(4);
					$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
					$this->Ln(4);			
					$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
					$this->Ln(4);			
					$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
					$this->Ln(6);
				/*	$this->SetFont('times','B',8);
					$this->Cell(0,10,'COMPROBANTE '." ".$numero_comprobante." "."del"." ".$fecha_comprobante,0,0,'C');
					$this->ln(4);
					$this->Cell(0,10,'TIPO COMPROBANTE.'." ".$codigo_tipo_comprobante." ".$tipo_comprobante,0,0,'C');
					$this->ln(4);
						$this->Cell(0,10,"AÑO:" .date("Y"),0,0,'C');
					$this->Ln(6);
					$this->SetFont('Arial','B',6);
					$this->SetLineWidth(0.3);
					$this->SetFillColor(175) 
	;
					$this->SetTextColor(0);
					$this->Cell(20,6,		     'CUENTA',			1,0,'L',0);
					$this->Cell(40,6,			 'DESCRIPCION DE LA CUENTA',		1,0,'L',0);
					$this->Cell(50,6,			 'DESCRIPCION DEL ASIENTO',		1,0,'L',0);
					$this->Cell(10,6,			 'REF',		1,0,'L',0);
					$this->Cell(20,6,		     'DEBE',	1,0,'C',0);
					$this->Cell(20,6,		     'HABER',	1,0,'C',0);
					$this->Cell(20,6,		     'SECUENCIA',	1,0,'C',0);
					$this->Ln(6);*/
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
						$pdf->SetFont('arial','',6);
						$pdf->SetFillColor(255);
						//
						$acu_deb=0;
						$acu_cred=0;
						$contar=0;
						$conta_saltos=0;
				while(!$row->EOF)	
				{	
						$numero_comprobante=$row->fields("numero_comprobante");
						$fecha_comprobante=substr($row->fields("fecha_comprobante"),0,10);
						$fecha_comprobante = substr($fecha_comprobante,8,2)."".substr($fecha_comprobante,4,4)."".substr($fecha_comprobante,0,4);
						$tipo_comprobante=$row->fields("tipo");
						$codigo_tipo_comprobante=$row->fields("codigo_tipo_comprobante");
								if($contar==0)
								{
									$pdf->SetFont('times','B',8);
																		$pdf->ln(4);
									$pdf->Cell(0,10,'TIPO COMPROBANTE.'." ".$codigo_tipo_comprobante." ".$tipo_comprobante,0,0,'C');
									$pdf->ln(4);
									$pdf->Cell(0,10,'COMPROBANTE '." ".substr($numero_comprobante,10)." "."del"." ".$fecha_comprobante,0,0,'C');
									$pdf->ln(4);
										//$this->Cell(0,10,"AÑO:" .date("Y"),0,0,'C');
									$pdf->Ln(6);
									$pdf->SetFont('Arial','B',6);
									$pdf->SetLineWidth(0.3);
									/*$pdf->SetFillColor(0) ;
									$pdf->SetTextColor(0);
*/									
									$pdf->SetFillColor(175) ;
									$pdf->SetTextColor(0);
									$pdf->Cell(15,6,		     'CUENTA',			0,0,'L',1);
									$pdf->Cell(40,6,			 'DESCRIPCION DE LA CUENTA',		0,0,'L',1);
									$pdf->Cell(60,6,			 'DESCRIPCION DEL ASIENTO',		0,0,'L',1);
									$pdf->Cell(20,6,			 'REF',		0,0,'L',1);
									$pdf->Cell(20,6,		     'DEBE',	0,0,'C',1);
									$pdf->Cell(20,6,		     'HABER',	0,0,'C',1);
									$pdf->Cell(10,6,		     'SECUEN',	0,0,'C',1);
									$pdf->Ln(6);
								}
								$pdf->SetFont('arial','',6);
								$pdf->SetFillColor(255);
								$pdf->SetTextColor(0);
								if($row->fields("debito_credito")==1)
								{
									$monto_debito=number_format($row->fields("monto_debito"),2,',','.');
									$monto_credito="";
									$acu_deb=$acu_deb+$row->fields("monto_debito");
								}
								if($row->fields("debito_credito")==2)
								{
									$monto_debito="";
									$monto_credito=number_format($row->fields("monto_credito"),2,',','.');
									$acu_cred=$acu_cred+$row->fields("monto_credito");
								}
								/*if($comprobante_ant!=$comprobante_sig)
								{
									$contar=0;	$pdf->AddPage();
								}
								*/
								//
								/*if($contar==1)
									{*/
							/////////se amoldara segun la long de la///////////////////////		
								$valor_longitud=strlen($row->fields("descripcion"));	
								$caracteres=40;	
								$cantidad_lineas_total=0;
								$resta=$valor_longitud;
								do
								{
									$resta=$resta-($caracteres+1);
									$cantidad_lineas_total++;
								}
								while($resta>=0);
								$total_lineas=$cantidad_lineas_total*6;
							///////////////////////////////////////////////////////////////
								if($row->fields("id_auxiliar")!=0)
								{$switch=0;	
												$codaux=$row->fields("id_auxiliar");
												$sql_auxiliar="select 
																nombre,cuenta_auxiliar 
															from 
																	auxiliares 
															where id_auxiliares='$codaux'";
											if (!$conn->Execute($sql_auxiliar)) die ('Error al Registrar: '.$conn->ErrorMsg());
											$row_aux=& $conn->Execute($sql_auxiliar);
											if(!$row_aux->EOF)
											{
												$auxiliar=strtoupper($row_aux->fields("nombre"));
												$cuenta_auxiliar=strtoupper($row_aux->fields("cuenta_auxiliar"));
												$switch=0;	
											}
											else
											{
												$auxiliar="";
												$cuenta_auxiliar="";
												$switch=1;	
											}
//$pdf->Cell(40,6,"aux"." ".$row->fields("codigo_auxiliar")."  ".$auxiliar,1,0,'L',1);
											//$pdf->Ln();	
										//}
										
									}else
									{
									$auxiliar="";
									$cuenta_auxiliar="";
									$switch=1; 
									}
									$valory=20;
									$valory2=10;
									
									$valory4=$valory2/2;


							if($switch!=0)
							{
								$pdf->Cell(15,6,strtoupper($row->fields("cuenta_contable")),1,0,'L',1);
								
								$pdf->Cell(40,6,substr(strtoupper($row->fields("descripcion_cuenta")),0,26),1,0,'L',1);
							}
							else
							{
							$valor_concat="";
							$valor_concat2="   ";
							$desc=substr(strtoupper($row->fields("descripcion_cuenta")),0,26);
							/*$desc2=strlen($desc);
							if($desc2<150)
							{
								while($desc2<100)
								{
									$desc.="";
									$desc2=$desc2+1;
								}
							}*/
								$desc2=strlen($row->fields("descripcion_cuenta"));
								if($desc2<30)
								{
									$res=30-$desc2;
								}
								else
								$res=1;
								$counter=1;
								$res=$res*2;
								while($counter<$res)
								{
									$valor_concat.=" ";
									$counter=$counter+1;
								}
								/////////////////////////
								$cuenta_len=strlen($row->fields("cuenta_contable"));
								if($cuenta_len<=3)
								{
									$res2=20-$cuenta_len;
								}
								else
								$res2=3;
								$counter2=1;
								while($counter2<$res2)
								{
									$valor_concat2.=" ";
									$counter2=$counter2+1;
								}	
								/////////////////////////	
											//1era
											$x = $pdf->GetX();
											$y = $pdf->GetY(); 
											$pdf->Cell(15,3,$row->fields("cuenta_contable"),'LTR','L',1);
											$pdf->Cell(40,3,substr(strtoupper($desc),0,30),'LTR','L',1);
											$pdf->ln();
											$pdf->Cell(15,3,"auxiliar",'LBR','L',1);
											$pdf->Cell(40,3,$cuenta_auxiliar." ".substr(strtoupper($auxiliar),0,20),'LBR','L',1);


											//$pdf->MultiCell(15,3,$row->fields("cuenta_contable").$counter.$valor_concat2.auxiliar,1,1,'L',1);
											
											$pdf->SetXY($x+15,$y);
											
											//2da$desc2.$counter.
											$x = $pdf->GetX();
											$y = $pdf->GetY();//
/*$pdf->MultiCell(40,3,$desc.$valor_concat.$cuenta_auxiliar." ".substr(strtoupper($auxiliar),0,20),'T','LBR','L');
*/$desc2=0;
$res=0;
										/*	$pdf->Cell(40,3,substr(strtoupper($desc),0,24),1,'L',1);
											$pdf->ln();
											$pdf->Cell(15,3,"",0,'LTB','L');
											$pdf->Cell(40,3,$cuenta_auxiliar." ".substr(strtoupper($auxiliar),0,20),0,'TBR','L',1);*/
											$pdf->SetXY($x+40,$y);
											//
							}
							/*else
							{
								$valor_long=strlen($row->fields("descripcion_cuenta"));
								if($valor_long<23)
								{
									$res=50-$valor_long;
								}
								else
								$res=3;
								$counter=1;
								while($counter<=$res)
								{
									$valor_concat.=" ";
									$counter=$counter+1;
								}	
											$desc=$row->fields("descripcion_cuenta");
											//1era
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(20,3,$row->fields("cuenta_contable")."            ".auxiliar,1,'LBR','L');
											$pdf->SetXY($x+20,$y);
											//2da
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(40,3,substr(strtoupper($desc),0,25).$valor_concat."  ".$cuenta_auxiliar." ".$auxiliar,1,'LBR','L');
											$pdf->SetXY($x+40,$y);
											$valor_concat="";
							}*/	
							$x = $pdf->GetX();
							$y = $pdf->GetY();
							//$pdf->MultiCell(40,$total_lineas,$row->fields("descripcion"));
							//$pdf->SetXY(120,$y);substr(strtoupper($row->fields("descripcion")),0,55)
							$pdf->Cell(60,6,substr(strtoupper($row->fields("descripcion")),0,55),1,0,'L',1);
								if(($row->fields("id_utilizacion_fondos")!="")&&($row->fields("id_utilizacion_fondos")!=0))	
								{
										//1era//
											$x = $pdf->GetX();
											//$y = $pdf->GetY();
											$pdf->MultiCell(20,3,$row->fields("referencia")."    "."UF".$cuenta_utf,0,'LBR','L');
											$pdf->SetXY($x+20,$y);
								}else
								{
										$pdf->Cell(20,6,$row->fields("referencia"),1,0,'L',1);
//$total_lineas."-".strlen($row->fields("descripcion"))
								}
								$pdf->Cell(20,6,$monto_debito,1,0,'R',1);
								$pdf->Cell(20,6,$monto_credito,1,0,'R',1);
								$pdf->Cell(10,6,$row->fields("secuencia"),1,0,'C',1);
								$contar=$contar+1;//$row->fields("secuencia")
								$y = $pdf->GetY();
								$pdf->Line($x,$y,$x+70,$y);
								$pdf->Ln();
								$conta_saltos=$conta_saltos+1;
								$y = $pdf->GetY();
								$pdf->Line($x,$y,$x+70,$y);
							////////////////////////////////////////////////////////////////		
										/*if($row->fields("id_auxiliar")!=0)
										{
												$codaux=$row->fields("id_auxiliar");
												$sql_auxiliar="select 
																nombre,cuenta_auxiliar 
															from 
																	auxiliares 
															where id_auxiliares='$codaux'";
											//if (!$conn->Execute($sql_auxiliar)) die ('Error al Registrar: '.$conn->ErrorMsg());
											$row_aux=& $conn->Execute($sql_auxiliar);
											if(!$row_aux->EOF)
											{
												$auxiliar=$row_aux->fields("nombre");
												$cuenta_auxiliar=$row_aux->fields("cuenta_auxiliar");
											}
											else
											{
												$auxiliar="dsfdf";
												$cuenta_auxiliar="dfgdf";
											}
											//$pdf->Cell(40,6,"aux"." ".$row->fields("codigo_auxiliar")."  ".$auxiliar,1,0,'L',1);
											//$pdf->Ln();	
										//}
									}else
									{
									$auxiliar="";
									$cuenta_auxiliar=""; 
									}
							if($contar!=0)
							{
								$pdf->Cell(20,6,$row->fields("cuenta_contable"),1,0,'L',1);
								$pdf->Cell(40,6,$row->fields("descripcion_cuenta"),1,0,'L',1);
							}
							else
							{
											//1era
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(20,3,$row->fields("cuenta_contable")."            ".auxiliar,1,'LBR','L');
											$pdf->SetXY($x+20,$y);
											
											//2da
											$x = $pdf->GetX();
											$y = $pdf->GetY();
											$pdf->MultiCell(40,3,substr(strtoupper($row->fields("descripcion_cuenta")),0,25)."      ".$cuenta_auxiliar." ".$auxiliar,1,'LBR','L');
											$pdf->SetXY($x+40,$y);
											
							}	
								$pdf->Cell(50,6,$row->fields("descripcion"),1,0,'L',1);
								$pdf->Cell(10,6,$row->fields("referencia"),1,0,'L',1);
								$pdf->Cell(20,6,$monto_debito,1,0,'R',1);
								$pdf->Cell(20,6,$monto_credito,1,0,'R',1);
								$pdf->Cell(20,6,$row->fields("secuencia"),1,0,'C',1);
								$contar=$contar+1;
								
								$pdf->Ln();*/

								/*$pdf->Cell(57,6,$nombre_jefe,0,0,'L',1);
								$pdf->Cell(57,6,$nombre_preparado,0,0,'L',1);
								$pdf->Cell(20,6,$estatus,	0,0,'L',1);
								$pdf->Cell(20,6,$fecha,	0,0,'L',1);*/
				$comprobante_ant=$row->fields("numero_comprobante");
				$y = $pdf->GetY();
				$row->MoveNext();
				$sec_cont=$sec_cont+1;
				if($conta_saltos>=30)
				{
					$contar=0;	
					$conta_saltos=0;
											$pdf->AddPage();

				
				}
				$comprobante_sig=$row->fields("numero_comprobante");
					if($comprobante_ant!=$comprobante_sig)
					{
						//
						$pdf->SetFont('arial','B',7);		
						$pdf->Cell(135,6,"",0,'LBR','R',0);
						$pdf->Cell(20,6,number_format($acu_deb,2,',','.'),1,'LBR','R',1);
						$pdf->Cell(20,6,number_format($acu_cred,2,',','.'),1,'LBR','R',1);
						$acu_deb2=$acu_deb2+$acu_deb;
						$acu_cred2=$acu_cred2+$acu_cred;
						$acu_deb=0;
						$acu_cred=0;
						$pdf->Ln(30);
						$pdf->Cell(60,6,"_______________________",0,0,'R',1);
						$pdf->Cell(50,6,"_______________________",0,0,'R',1);
						$pdf->Cell(50,6,"_______________________",0,0,'R',1);
						$pdf->Ln();
						$pdf->Cell(50,6,"PREPARADO",0,0,'R',1);
						$pdf->Cell(50,6,"VERIFICADO",0,0,'R',1);
						$pdf->Cell(50,6,"APROBADO",0,0,'R',1);	
						$contar=0;	
						if(!$row->EOF)
						$pdf->AddPage();

						//
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
		$pdf->Cell(190,		6,'No se encontraron datos'.$desde_num.$hasta_num,			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>