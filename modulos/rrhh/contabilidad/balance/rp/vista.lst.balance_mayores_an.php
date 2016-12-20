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
$where="WHERE 1=1";
if(isset($_GET[id_cuenta]))
{
	$id_c=$_GET[id_cuenta];
	if($id_c!="")
	{
		$where.="AND cuenta_contable_contabilidad.id='$id_c'";
	}
}
if(isset($_GET[desde]))
{
	$desde=$_GET[desde];
	list($dia_d,$mes_d,$ayo_d)=split("/",$desde,3);
	$where2="AND	saldo_contable.ano>=$ayo_d";	
}
if(isset($_GET[hasta]))
{
	$hasta=$_GET[hasta];
	list($dia_h,$mes_h,$ayo_h)=split("/",$hasta,3);
	$where2="AND	saldo_contable.ano<=$ayo_h";	
}
if((isset($_GET[desde]))&&(isset($_GET[hasta])))
{
	$where.=" AND movimientos_contables.fecha_comprobante >='$desde' AND movimientos_contables.fecha_comprobante<='$hasta'";
}
if(isset($_GET['aux']))
{
	$aux=$_GET['aux'];
	if($aux!="")
	{
		$where.="
					AND
						id_auxiliares='$aux'
		";
	}

}	
$sql_cuenta="
			SELECT 
				cuenta_contable_contabilidad.cuenta_contable,
			    cuenta_contable_contabilidad.id,
				cuenta_contable_contabilidad.nombre AS nombre,
				movimientos_contables.numero_comprobante,
				movimientos_contables.descripcion,
				movimientos_contables.referencia,
				movimientos_contables.debito_credito,
				movimientos_contables.monto_debito,
				movimientos_contables.monto_credito,
				movimientos_contables.fecha_comprobante, 
				movimientos_contables.id_auxiliar,
				movimientos_contables.id_tipo_comprobante,
				movimientos_contables.ultimo_usuario,
				tipo_comprobante.codigo_tipo_comprobante,
				auxiliares.cuenta_auxiliar,
				usuario.usuario,
				naturaleza_cuenta.codigo
			FROM cuenta_contable_contabilidad
				inner join
				movimientos_contables
				on
				movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable	
				inner join
				tipo_comprobante
				on
				movimientos_contables.id_tipo_comprobante=tipo_comprobante.id		
				left join
				auxiliares
				on
				movimientos_contables.id_auxiliar=auxiliares.id_auxiliares
				inner join
				usuario
				on
				usuario.id_usuario=movimientos_contables.ultimo_usuario	
				inner join
				naturaleza_cuenta
				on
				cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id			
			$where
			order by
									movimientos_contables.numero_comprobante,movimientos_contables.fecha_comprobante,cuenta_contable_contabilidad.cuenta_contable

		";/*	*/
$row=& $conn->Execute($sql_cuenta);
if(!$row->EOF)//	$where	
{
	//************************************************************************
				class PDF extends PDF_Code128
				{
					//Cabecera de p�gina
					function Header()
					{	
						global $desde;
						global $hasta;
						$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
						$this->SetFont('times','B',7);
						$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
						$this->Ln(4);
						$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
						$this->Ln(4);			
						$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
						$this->Ln(4);			
						$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
						$this->Ln(6);
						$this->SetFont('Arial','B',9);
						$this->Cell(0,10,'MAYOR ANALITICO'."  "."DEL"." ".$desde."AL"." ".$hasta ,0,0,'C');
						$this->Ln(10);
						$this->SetFont('Times','B',6);
						$this->SetLineWidth(0.3);
						$this->SetFillColor(175) ;
						$this->SetTextColor(0);
						$this->Cell(15,6,		     'C.U',			0,0,'L',1);
						$this->Cell(10,6,		     'FECHA',			0,0,'L',1);
						$this->Cell(15,6,		     'TIPO.CMP ',			0,0,'L',1);
						$this->Cell(20,6,		     'NUMERO.CMP',			0,0,'L',1);
						$this->Cell(20,6,		     'REFRENCIA',			0,0,'L',1);
						$this->Cell(20,6,		     'AUXIL',			0,0,'L',1);
						$this->Cell(30,6,		     'DESCRIPCION',			0,0,'L',1);
						$this->Cell(20,6,			 'DEBE',		0,0,'R',1);
						$this->Cell(20,6,			 'HABER',		0,0,'R',1);
						$this->Cell(20,6,			 'SALDO',		0,0,'R',1);						
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
	$opcion="titulo";
	
	while (!$row->EOF) 
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		
	
	$debe_total2=$row->fields("monto_debito");
	$haber_total2=$row->fields("monto_credito");
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$saldo_mov=$debe_total2-$haber_total2;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$saldo_mov=$haber_total2-$debe_total2;
		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$saldo_mov=$haber_total2-$debe_total2;
		}
		if($row->fields("codigo")=='CO  ')
		{
			if(($debe_total2!=0)&&($haber_total2==0))
			{
				$saldo_mov=$debe_total2;
			}
			else	
			if(($haber_total2!=0)&&($debe_total2==0))
			{
				$saldo_mov=$haber_total2;
			}
			if(($debe_total2!=0)&&($haber_total2!=0))
			{
				$saldo_mov=$debe_total2;
			}
		}	
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	$valores=4;
	
	//$saldo_mes=number_format($total_cuenta_debe_haber,2,',','.');
	$cuenta_ant=$row->fields("cuenta_contable");
	
	if($opcion=="titulo")
	{	
		
		$id_cuenta_contable=$row->fields("id");
		$sql_saldos="SELECT debe,haber
  						FROM saldo_contable
						where
								cuenta_contable='$id_cuenta_contable'
						$where2			
						";

		$row_saldos=& $conn->Execute($sql_saldos);
		if(!$row_saldos->EOF)//	$where	
		{
				$med=strlen($row_saldos->fields("debe"));
				$med=$med-2;
				$debe=substr($row_saldos->fields("debe"),1,$med);
				$debe_vector=split(",",$debe);
				
				$med2=strlen($row_saldos->fields("haber"));
				$med2=$med2-2;
				$haber=substr($row_saldos->fields("haber"),1,$med2);
				$haber_vector=split(",",$haber);
				
				$conter=0;
				
				$debe_total=0;
				$haber_total=0;
				$total_cuenta_debe_haber="";
				$cuenta_sumas="";
				$mes_d2=$mes_d-1;
				while($conter!=$mes_d2)
				{
					$debe_total=$debe_total+$debe_vector[$conter];
					$haber_total=$haber_total+$haber_vector[$conter];
					$conter++;
				}
				
					if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
					{
						$saldo_inicial=$debe_total-$haber_total;
					}
					else
					if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
					{
						$saldo_inicial=$haber_total-$debe_total;
					}
					else
					if($row->fields("codigo")=='R   ')
					{
						$saldo_inicial=$haber_total-$debe_total;
					}
					if($row->fields("codigo")=='CO  ')
					{
							if(($debe_total!=0)&&($haber_total==0))
							{
								$saldo_inicial=$debe_total2;
							}
							else	
							if(($haber_total!=0)&&($debe_total==0))
							{
								$saldo_inicial=$haber_total2;
							}
							if(($debe_total!=0)&&($haber_total!=0))
							{
								$saldo_inicial=$debe_total2;
							}
					}	
				//$pdf->Ln();
				$pdf->SetFont('Times','B',6);

				$pdf->Cell(15,6,		    $row->fields("cuenta_contable"),			0,0,'L',1);
				//$pdf->Cell(15,6,		    $mes_d."-".$mes_d2,			0,0,'L',1);
				$pdf->Cell(120,6,			substr(strtoupper($row->fields("nombre")),0,80),					0,0,'L',1);
				$pdf->Cell(80,6,			"SALDO INICIAL"."   ".number_format($saldo_inicial,2,',','.'),0,0,'L',1);
				$pdf->Ln();
		}//end if eof		
	}//end opcion==titulo
		$pdf->SetFont('Times','',6);
		$pdf->Cell(15,6,		    $row->fields("usuario"),			0,0,'L',1);
		$pdf->Cell(10,6,		    $row->fields("fecha_comprobante"),			0,0,'L',1);
		$pdf->Cell(15,6,		    $row->fields("codigo_tipo_comprobante"),			0,0,'L',1);
		$pdf->Cell(20,6,		    $row->fields("numero_comprobante"),			0,0,'L',1);
		$pdf->Cell(20,6,		    $row->fields("referencia"),			0,0,'L',1);
		$pdf->Cell(20,6,		    $row->fields("cuenta_auxiliar"),			0,0,'L',1);
		$pdf->Cell(30,6,		    $row->fields("descripcion"),			0,0,'L',1);
		$pdf->Cell(20,6,			number_format($row->fields("monto_debito"),2,',','.'),		0,0,'R',1);
		$pdf->Cell(20,6,			number_format($row->fields("monto_credito"),2,',','.'),		0,0,'R',1);
		$pdf->Cell(20,6,			number_format($saldo_mov,2,',','.') ,		0,0,'R',1);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$fecha_ant=$row->fields("fecha_comprobante");
	list($dia_ant,$mes_ant,$ayo_ant)=split("/",$fecha_ant,3);
	$debe_acum=$debe_acum+$row->fields("monto_debito");
	$haber_acum=$haber_acum+$row->fields("monto_credito");
	$saldo_acum=$saldo_acum+$saldo_mov;
	$debe_mensual=$debe_mensual+$row->fields("monto_debito");
	$haber_mensual=$haber_mensual+$row->fields("monto_credito");
	$saldo_menso=$saldo_menso+$saldo_mov;
	
	$row->MoveNext();
	$cuenta_sig=$row->fields("cuenta_contable");

	$fecha_sig=$row->fields("fecha_comprobante");
	list($dia_sig,$mes_sig,$ayo_sig)=split("/",$fecha_sig,3);

///////////////////////////CASO1 FECHA Y CAT
/*if(($fecha_ant<$fecha_siguiente)&&($cuenta_ant!=$cuenta_sig))
{
			$pdf->Ln($valores);
			$pdf->Cell(120,6,			"TOTAL DEL MES:",		0,0,'R',1);
			$pdf->Cell(30,6,			number_format($debe_mensual  ,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($haber_mensual,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($saldo_menso,2,',','.') ,		0,0,'R',1);
			$pdf->Ln($valores);
		
			$debe_mensual=0;
			$haber_mensual=0;
			$saldo_menso=0;
			
			$pdf->Ln($valores);
			$pdf->Cell(120,6,			"TOTAL CUENTA:"."  ". $row->fields("cuenta_contable"),		0,0,'R',1);
			$pdf->Cell(30,6,			number_format($debe_acum  ,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($haber_acum,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($saldo_acum,2,',','.') ,		0,0,'R',1);
			$pdf->Ln($valores);
}
*/
		///////////////////////////CASO2:fecha ant< fecha sig
		if($fecha_ant<$fecha_siguiente)
		{
			$pdf->Ln();
			$pdf->SetFont('Times','B',6);
			$pdf->Cell(120,6,			"TOTAL DEL MES:",		0,0,'R',1);
			$pdf->SetFont('Times','',6);
			$pdf->Cell(30,6,			number_format($debe_mensual  ,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($haber_mensual,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($saldo_menso,2,',','.') ,		0,0,'R',1);
			//$pdf->Ln();
		
			$debe_mensual=0;
			$haber_mensual=0;
			$saldo_menso=0;
			$fecha_valor="entro";
		}
		///////////////////////////CASO3:CTA
		if($cuenta_ant!=$cuenta_sig)
		{
			$opcion="titulo";
			if($fecha_valor!="entro")
			{
				$pdf->Ln();
				$pdf->SetFont('Times','B',6);
				$pdf->Cell(120,6,			"TOTAL DEL MES:",		0,0,'R',1);
				$pdf->Cell(30,6,			number_format($debe_mensual  ,2,',','.'),		0,0,'R',1);
				$pdf->Cell(20,6,			number_format($haber_mensual,2,',','.'),		0,0,'R',1);
				$pdf->Cell(20,6,			number_format($saldo_menso,2,',','.') ,		0,0,'R',1);
				$debe_mensual=0;
				$haber_mensual=0;
				$saldo_menso=0;
				$fecha_valor="";
				$pdf->SetFont('Times','',6);

			}
			$pdf->Ln();
			$pdf->SetFont('Times','B',6);
			$pdf->Cell(120,6,			"TOTAL CUENTA:"."  ". $row->fields("cuenta_contable"),		0,0,'R',1);
			$pdf->Cell(30,6,			number_format($debe_acum  ,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($haber_acum,2,',','.'),		0,0,'R',1);
			$pdf->Cell(20,6,			number_format($saldo_acum,2,',','.') ,		0,0,'R',1);
			$debe_acum=0;
			$haber_acum=0;
			$saldo_acum=0;
			$pdf->SetFont('Times','',6);
			//$pdf->Ln();
			//$pdf->Ln(4);
		}
			else
				$opcion="no_titulo";

//		$opcion="no_titulo";
	$pdf->Ln();
	$cuenta_sumas="";
	$total_cuenta_debe_haber="";
	$valores=4;
	}
	/*$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Ln(6);
	$pdf->Cell(90,6,"TOTAL",				0,0,'L',1);
	$pdf->SetFillColor(255) ;
	$pdf->SetTextColor(0);
	$pdf->Cell(40,6,				$acu_debe,	0,0,'L',1);
	$pdf->Cell(50,6,				$acu_haber,				0,0,'L',1);*/

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
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
			$this->Ln();
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
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>
