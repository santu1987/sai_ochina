<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
$where="WHERE 1=1 ";
list($dia,$mes,$ayo)=split("/",$hasta,3);
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
 
 }
 else
 	$dia=$dia+1;
 if($mes=="12")
 {
 	$mes="1";
	$ayo=$ayo+1;
  }	$fechas=$dia.'/'.$mes.'/'.$ayo;
if(isset($_GET['rif']))
{
	$rif=$_GET['rif'];	
	$where.=" AND cheques.cedula_rif_beneficiario='$rif'
			  AND cheques.numero_cheque < 0
			  AND cheques.fecha_cheque >= '$desde' AND cheques.fecha_cheque <='$fechas'
			  AND cheques.estatus=1
		  ";
}//AND chequeras.estatus='1'
if(isset($_GET['id_banco']))
{
	$id_banco =$_GET['id_banco'];
	$where.=" AND cheques.id_banco=$id_banco
			";
}
if(isset($_GET['cuenta']))
{
	$cuenta=$_GET['cuenta'];
	$where.=" AND cheques.cuenta_banco='$cuenta'";
}
if(isset($_GET['tipo']))
{
	$tipo=$_GET['tipo'];
	if(($tipo!=3)&&($tipo!=""))$where.=" AND cheques.tipo_cheque=$tipo";
}
if(isset($_GET['eva_opcion']))
{
	$op=$_GET['eva_opcion'];
	if($op=='1')
		$where.=" AND cheques.id_proveedor!='0'";
	else
		if($op=='2')
			$where.=" AND cheques.cedula_rif_beneficiario!='NULL'";

}

$Sql="
		SELECT  distinct
				cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				cheques.nombre_beneficiario AS beneficiario,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.usuario as usuario,
				cheques.ordenes,
				cheques.concepto,
				cheques.tipo_cheque		
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			INNER JOIN 
				chequeras
			ON 
				cheques.cuenta_banco=chequeras.cuenta
			$where
			ORDER BY
				banco.nombre,cheques.cuenta_banco,cheques.secuencia
";
			
$row=& $conn->Execute($Sql);
/*$cheque=$row->fields("numero_cheque");
$sql_orden="		SELECT 
						*
					FROM
						 \"orden_pagoE\"
					WHERE
							(\"orden_pagoE\".numero_cheque='$cheque')
						";	
$row_orden=& $conn->Execute($sql_orden);		*/	

//consultando las ordenes de pago
				
					

$beneficiario=$row->fields("beneficiario");

//
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de p�gina
		function Header()
		{	global $beneficiario;	
			global $row;
			global $desde;
			global $hasta;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'Rep�blica Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Control de Gesti�n de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');	
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'RELACI�N BENEFICIARIOS CHEQUES REGISTRADOS',0,0,'C');
            $this->ln();
			$this->Cell(0,10,strtoupper($beneficiario),0,0,'C');
			$this->Ln();
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(45,6,		     'N� DE CUENTA',			0,0,'L',1);
			$this->Cell(30,6,		     'N� DE CHEQUERA',			0,0,'L',1);
			$this->Cell(25,6,		     'N� DE CHEQUE',			0,0,'L',1);
			$this->Cell(50,6,		     'CONCEPTO',			0,0,'L',1);
			$this->Cell(25,6,		     'MONTO',			0,0,'L',1);
			$this->Cell(25,6,		     'ORDENES DE PAGO',			0,0,'R',1);
			$this->Cell(40,6,		     'USUARIO EMISOR',			0,0,'L',1);			
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
			$this->Cell(80,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(95,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(80,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('L');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(auto,50);	
	$ordenes=$row->fields("ordenes");
	$vector = split( ",",$ordenes);
	$contador=count($vector);  ///$_POST['covertir_req_cot_titulo']
	$ia=0;
	$banco_nombre=$row->fields("banco");
	$n_cuenta=$row->fields("cuenta_banco");
	//$n_cheque_monto=$row->fields("numero_cheque");
	$n_chequera=$row->fields("secuencia");
	$primer=strlen($n_cheque);
	$n_cheque=$row->fields("numero_cheque");

	$nombre_usuario=$row->fields("usuario");
	while (!$row->EOF) 
	//$row->fields("numero_cheque")
	{		
	
	//
		$monto_cheques=number_format($row->fields("monto_cheque"),2,',','.');		
		$monto_cheques2=$row->fields("monto_cheque");
		$total_general=$total_general+$row->fields("monto_cheque");	
		$primer=strlen($n_cheque);
		//$n_cheque=$row->fields("numero_cheque");
		$secuencia=$row->fields("secuencia");
		$banco=$row->fields("id_banco");
		$cuenta=$row->fields("cuenta_banco");
	
						switch($primer)
									{
										case 1:
										$n_cheque='00000'.$n_cheque;
										break;
										case 2:
										$n_cheque='0000'.$n_cheque;
										break;
										case 3:
										$n_cheque='000'.$n_cheque;
										break;
										case 4:
										$n_cheque='00'.$n_cheque;
										break;
										case 5:
										$n_cheque='0'.$n_cheque;
										break;
										case 6:
										$n_cheque=$n_cheque;
										break;
										
									}
	//-------------------------------------------------------------------------------------------------------------------------
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(40,6,		    strtoupper($banco_nombre),			0,0,'L',1);
			$pdf->Cell(45,6,		     $n_cuenta,	0,0,'L',1);
			$pdf->Cell(30,6,		     $n_chequera,			0,0,'L',1);
			$pdf->Cell(25,6,		     $n_cheque,			        0,0,'L',1);
			$pdf->Cell(50,6,substr($row->fields("concepto"),0,15),	0,0,'L',1);
			$pdf->Cell(25,6,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'L',1);
			$cheque=$row->fields("numero_cheque");
	/*	if($row->fields("tipo_cheque")=='1')
			{
				
							$sql_orden="SELECT DISTINCT
										*
									FROM
										 \"orden_pagoE\"
									WHERE
											(\"orden_pagoE\".numero_cheque='$cheque')
									AND
											\"orden_pagoE\".secuencia='$secuencia'
									AND
											\"orden_pagoE\".id_banco='$banco'
									AND
											\"orden_pagoE\".cuenta_banco='$cuenta'				
										";	
									$row_orden=& $conn->Execute($sql_orden);
									$tamano=25;		
									while(!$row_orden->EOF)
										{
											$pdf->SetFont('Arial','',10);
											$pdf->Cell($tamano,6,$row_orden->fields("numero_orden_pago"),0,0,'R',1);
											$tamano=280;
											$row_orden->MoveNext();
											}
										$row_orden="";
				}
				else
				{*/
				$pdf->Cell(25,6,"Sin orden",0,0,'R',1);
				//}	
				$pdf->Cell(40,6,strtoupper($nombre_usuario),	0,0,'C',1);
				$pdf->Ln();						
				$banco_ant=$row->fields("banco");
				$n_cuenta_ant=$row->fields("cuenta_banco");
				$n_chequera_ant=$row->fields("secuencia");
				$n_cheque_ant= $row->fields("numero_cheque");
				$nom=$row->fields("nombre");
				$ape=$row->fields("apellido");
				$usuario_ant=$nom."  ".$ape;
				$usuario_ant=strtoupper($usuario_ant);
				//----------------	
				$row->MoveNext();
				$banco_sig=$row->fields("banco");
				$n_cuenta_sig=$row->fields("cuenta_banco");
				$n_chequera_sig=$row->fields("secuencia");
				$n_cheque_sig= $row->fields("numero_cheque");
				$nom=$row->fields("nombre");
				$ape=$row->fields("apellido");
				$usuario_sig=$nom."  ".$ape;
				$usuario_sig=strtoupper($usuario_sig);
			
				if($usuario_ant==$usuario_sig)
					$nombre_usuario="";
				else
					$nombre_usuario=$usuario_sig;	
				if($n_cuenta_ant==$n_cuenta_sig)
				{	
					$n_cuenta="";
					$total_cuenta=$total_cuenta+$monto_cheques2;

					if($n_chequera_ant==$n_chequera_sig)
					{	
						$n_chequera="";
						if($n_cheque_ant==$n_cheque_sig)
						$n_cheque="";
						else
						$n_cheque=$n_cheque_sig;
					}
						else
							{
								$n_chequera=$n_chequera_sig;	
								$n_cheque=$n_cheque_sig;
							}	
				}
				else
				{
					$n_cuenta=$n_cuenta_sig;
					$n_chequera=$n_chequera_sig;	
					$n_cheque=$n_cheque_sig;	
					$total_cuenta=$total_cuenta+$monto_cheques2;
					//$pdf->Cell(278,6,"Total Banco:".number_format($total_banco,2,',','.')."     "."Total Cuenta:".number_format($total_cuenta,2,',','.'),1,1,'R',1);
					$pdf->Cell(275,6,"Total Cuenta:".number_format($total_cuenta,2,',','.'),0,1,'R',1);
					//$pdf->Ln();	
				}
				if($banco_ant==$banco_sig)
				{	
					$banco_nombre="";
					$total_banco=$total_banco+$monto_cheques2;
				}
				else
					{	
						$banco_nombre=$banco_sig;
						$nombre_usuario=$usuario_sig;	
						$total_banco=$total_banco+$monto_cheques2;
						$pdf->Cell(275,6,"Total Banco:".number_format($total_banco,2,',','.'),0,1,'R',1);
						$pdf->Ln();
						$total_cuenta=0;
						$total_banco=0;
					}
					
		
	//--------------------------------------------------------------------------------------------------------------------------								
	
	/*		$pdf->Ln(10);
			$pdf->SetFont('Arial','B',11 );
			$pdf->Cell(30,6,'N� CHEQUE:',0,0,'L',1);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(15,6,$n_cheque,0,0,'L',1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(20,6, 'BANCO:',0,0,'L',1);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,6,$row->fields("banco"),0,0,'L',1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(50,6,'N� DE CUENTA:',0,0,'R',1);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,6,$row->fields("cuenta_banco"),0,0,'L',1);
			$pdf->Ln();
			$pdf->SetFont('Arial','B',11);
    		$pdf->Cell(20,6, 'Usuario:',0,0,'L',1);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,6,$nombre_usuario,0,0,'L',1);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(100,6,'MONTO CHEQUE:',	0,0,'R',1);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(77,6,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'L',1);
			$pdf->Ln();
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(30,6,'Concepto:',0,0,'L',1);
			$pdf->SetFont('Arial','',10);
			//$pdf->Cell(60,6,$row->fields("concepto"),0,0,'L',1);
			$pdf->Cell(60,6,substr($row->fields("concepto"),0,80),0,0,'L');
			$pdf->Ln();
			$pdf->Cell(60,6,substr($row->fields("concepto"),80),0,0,'L');
			$pdf->Ln(10);
			$pdf->SetLineWidth(0.3);
			$pdf->SetFillColor(0) ;
			$pdf->SetTextColor(0);
		//----
		$cheque=$row->fields("numero_cheque");
				$sql_orden="SELECT DISTINCT
					*
				FROM
					 \"orden_pagoE\"
				WHERE
						(\"orden_pagoE\".numero_cheque='$cheque')
				AND
						\"orden_pagoE\".secuencia='$secuencia'
				AND
						\"orden_pagoE\".id_banco='$banco'
				AND
						\"orden_pagoE\".cuenta_banco='$cuenta'				
					";
		$row_orden=& $conn->Execute($sql_orden);		
		//----	
		$pdf->Cell(30,6,				"ORDEN DE PAGO",				1,0,'C',1);
		$pdf->Cell(30,6,				"MONTO",				1,0,'C',1);
		$pdf->Cell(30,6,				"FECHA",				1,0,'C',1);
		$pdf->Ln(6);
		$pdf->SetFont('arial','B',10);
		$pdf->SetFillColor(255);
		$pdf->SetTextColor(0);

		//----------------
		while(!$row_orden->EOF)
			{
    			$pdf->SetFont('Arial','',10);
				$pdf->Cell(30,6,$row_orden->fields("numero_orden_pago"),1,0,'C',1);
				$pdf->Cell(30,6,number_format($row_orden->fields("monto_pagar"),2,',','.'),1,0,'C',1);
				$pdf->Cell(30,6,$row_orden->fields("fecha_orden_pago"),1,0,'C',1);
				$pdf->Ln();
				$ia=$ia+1;
				$row_orden->MoveNext();

				}
			$row_orden="";	
		//----------------	
		$pdf->Ln(10);	
	$row->MoveNext();
	*/
	}
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(280,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
	$pdf->Ln();
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Direcci�n General de Control de Gesti�n de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrograf�a y Navegaci�n',0,0,'C');
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
