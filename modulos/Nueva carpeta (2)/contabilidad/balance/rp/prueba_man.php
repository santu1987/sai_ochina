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
$where="WHERE 1=1 and movimientos_contables.estatus!='3'";
//		$where.="AND cuenta_contable_contabilidad.id='543'";
	$desde='01/01/2011';
	list($dia_d,$mes_d,$ayo_d)=split("/",$desde,3);
	$where2="AND	saldo_contable.ano>=$ayo_d";	

	$hasta='28 /02/2011';
	list($dia_h,$mes_h,$ayo_h)=split("/",$hasta,3);
	$where2="AND	saldo_contable.ano<=$ayo_h";	

	//$where.=" AND movimientos_contables.fecha_comprobante >='$desde' AND movimientos_contables.fecha_comprobante<='$hasta'";


	
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
	
									
			order by
				movimientos_contables.fecha_comprobante,movimientos_contables.numero_comprobante,cuenta_contable_contabilidad.cuenta_contable

		";/*	*/
//die($sql_cuenta);
$row=& $conn->Execute($sql_cuenta);
if(!$row->EOF)//	$where	
{

	$opcion="titulo";
	$teen=0;
	while (!$row->EOF) 
	{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
				echo($row->fields("cuenta_contable")."-");
		
	$debe_total=$row->fields("monto_debito");
	$haber_total=$row->fields("monto_credito");
	
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	$tipos=$row->fields("tipo");
	$valores=4;
	
	//$saldo_mes=number_format($total_cuenta_debe_haber,2,',','.');
	$cuenta_ant=$row->fields("cuenta_contable");
	
	if($opcion=="titulo")
	{	
		
		$id_cuenta_contable=$row->fields("id");
		$sql_saldos="SELECT debe,haber,saldo_inicio
  						FROM saldo_contable
						where
								cuenta_contable='$id_cuenta_contable'
						$where2			
						";
/*die($sql_saldos);
*/		$row_saldos=& $conn->Execute($sql_saldos);
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
				
				$med3=strlen($row->fields("saldo_inicio"));
				$med3=$med3-2;
				$saldo_inicio=substr($row_saldos->fields("saldo_inicio"),1,$med3);
				$saldo_vector=split(",",$saldo_inicio);
				$conter=0;
				
				$debe_total=0;
				$haber_total=0;
				$total_cuenta_debe_haber="";
				$cuenta_sumas="";
				//$mes_d2=$mes_d-1;
				$fecha2=$row->fields("fecha_comprobante");
				$mesx=substr($fecha2,5,2);


				$uno=substr($mesx,0,1);
	if($uno==0)
	$mes2=substr($mesx,1,1);
	$mes2=$mes2-1;
				/*while($conter!=$mes2)
				{*/
					//$debe_total=$debe_total+$debe_vector[$conter];
					//$haber_total=$haber_total+$haber_vector[$conter];
				$conter=0;
				echo($conter."!=".$mes2);
				$saldo_total=0;
				while($conter<=$mes2)
				{
					//$conter=$mes2;
					$saldo_total=$saldo_total+$saldo_vector[$conter];
					$conter++;	
				}
					//$conter++;
					
				//}
		}//end if eof		
	}//end opcion==titulo
//	 $row->fields("cuenta_auxiliar").$row->fields("referencia") $row->fields("descripcion"),
	$debe_total=$row->fields("monto_debito");
	$haber_total=$row->fields("monto_credito");
	
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
								$saldo_inicial=$debe_total;
							}
							else	
							if(($haber_total!=0)&&($debe_total==0))
							{
								$saldo_inicial=$haber_total;
							}
							if(($debe_total!=0)&&($haber_total!=0))
							{
								$saldo_inicial=$debe_total;
							}
					}		
				//	$saldo_total_acumulado=$saldo_total_acumulado+$saldo_iniciales;

					
				//$pdf->Ln();
				$saldo_in=$saldo_total+$saldo_inicial;
	
	 $haber_total=0;
	 $debe_total=0;
	$fecha_ant=$row->fields("fecha_comprobante");
	list($dia_ant,$mes_ant,$ayo_ant)=split("/",$fecha_ant,3);
	$debe_acum=$debe_acum+$row->fields("monto_debito");
	$haber_acum=$haber_acum+$row->fields("monto_credito");
	$saldo_acum=$saldo_in;
	$debe_mensual=$debe_mensual+$row->fields("monto_debito");
	$haber_mensual=$haber_mensual+$row->fields("monto_credito");
	$saldo_menso=$saldo_in;
	
	$row->MoveNext();
	$teen++;
	$saldo_total=$saldo_in;
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
		
			$debe_mensual=0;
			$haber_mensual=0;
			$saldo_menso=0;
			$fecha_valor="entro";
			$debe_total=0;
			$haber_total=0;
			$saldo_iniciales=0; 
			$saldo_inicial=0;
			$saldo_total=0;

		}
		///////////////////////////CASO3:CTA
		if($cuenta_ant!=$cuenta_sig)
		{
			$opcion="titulo";
			if($fecha_valor!="entro")
			{
				
				$debe_mensual=0;
				$haber_mensual=0;
				$saldo_menso=0;
				$fecha_valor="";
				$pdf->SetFont('Times','',6);
				$saldo_iniciales=0;	
			}
			$debe_acum=0;
			$haber_acum=0;
			$saldo_acum=0;
			//$pdf->Ln();
			//$pdf->Ln(4);
		}
			else
				$opcion="no_titulo";

//		$opcion="no_titulo";
	//$pdf->Ln();
	$cuenta_sumas="";
	$total_cuenta_debe_haber="";
	$valores=4;
	}


?>
