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
	 if($mes=="12")
	 {
		$mes="1";
		$ayo=$ayo+1;
	  }	
 }
 else
 	$dia=$dia+1;
 $fechas=$dia.'/'.$mes.'/'.$ayo;if(isset($_GET['rif']))
{
	$rif=$_GET['rif'];
	$where.=" AND cheques.cedula_rif_beneficiario='$rif'
			  AND cheques.numero_cheque>0
			  AND cheques.fecha_cheque >= '$desde' AND cheques.fecha_cheque <='$fechas'
			  AND cheques.estatus<5 AND cheques.estatus>1
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
				cheques.tipo_cheque,
				cheques.fecha_cheque		
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
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	global $beneficiario;	
			global $row;
			global $desde;
			global $hasta;
			
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
			$this->Ln(5);	
		//	$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'RELACIÓN BENEFICIARIOS CHEQUES REGISTRADOS',0,0,'C');
            $this->ln(5);
			$this->Cell(0,10,strtoupper($beneficiario),0,0,'C');
			$this->Ln(5);
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->Ln(10);
			$this->SetFont('Times','B',8);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'BANCO',			0,0,'L',1);
			$this->Cell(45,6,		     'Nº DE CUENTA',			0,0,'L',1);
			$this->Cell(30,6,		     'Nº DE CHEQUERA',			0,0,'L',1);
			$this->Cell(25,6,		     'Nº DE CHEQUE',			0,0,'L',1);
			$this->Cell(30,6,		     'FECHA',			0,0,'L',1);
			$this->Cell(20,6,		     'MONTO',			0,0,'L',1);
			$this->Cell(35,6,		     'ORDENES DE PAGO',			0,0,'L',1);
			$this->Cell(35,6,		     'USUARIO EMISOR',			0,0,'L',1);			
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
			$this->Cell(80,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(95,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(80,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(175) ;
			$this->Code128(120,200,strtoupper($_SESSION['usuario']),40,6);				}
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
	$cont=0;
	$coordenadas=0;
	while (!$row->EOF) 
	{		
	if($cont==0)
			{
						$nom=$row->fields("nombre");
						$ape=$row->fields("apellido");
						$nombre_usuario=$nom."  ".$ape;
				$cont=1;
				}
	//
		$monto_cheques=number_format($row->fields("monto_cheque"),2,',','.');		
		$monto_cheques2=$row->fields("monto_cheque");
		$total_general=$total_general+$row->fields("monto_cheque");	
		$primer=strlen($n_cheque);
		//$n_cheque=$row->fields("numero_cheque");
		$fecha_emitido=substr($row->fields("fecha_cheque"),0,10);
		$fecha_emitido = substr($fecha_emitido,8,2)."".substr($fecha_emitido,4,4)."".substr($fecha_emitido,0,4);

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
			$pdf->Cell(30,6,$fecha_emitido,0,0,'L',1);
			$pdf->Cell(20,6,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'L',1);
			$cheque=$row->fields("numero_cheque");
			if(($row->fields("ordenes")!='{0}')&&($row->fields("ordenes")!='{}'))
					{
						$pdf->Cell(35,6,$row->fields("ordenes"),0,0,'C',1);
						$coordenada++;
					}
				else
				{
				$pdf->Cell(35,6,"Sin orden",0,0,'C',1);
				}	
				$pdf->Cell(35,6,strtoupper($nombre_usuario),	0,0,'C',1);
				$pdf->Ln();		
				$coordenadas++;				
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
					$pdf->Cell(265,6,"Total Cuenta:".number_format($total_cuenta,2,',','.'),0,1,'R',1);
					$total_cuenta=0;

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
						$pdf->Cell(265,6,"Total Banco:".number_format($total_banco,2,',','.'),0,1,'R',1);
						$coordenadas++;
						$total_cuenta=0;
						$total_banco=0;
					}
					
		
	
							if($coordenada>=16)
								{
								$coordenada=0;
								$nombre_usuario=$usuario_sig;
								$n_cuenta=$n_cuenta_sig;
								$n_chequera=$n_chequera_sig;	
								$n_cheque=$n_cheque_sig;
								$pdf->AddPage('L');
								}
	}
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(265,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
	$pdf->Ln();
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
