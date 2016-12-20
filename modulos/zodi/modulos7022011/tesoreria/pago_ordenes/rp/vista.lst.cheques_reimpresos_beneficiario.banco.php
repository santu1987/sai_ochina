<?php
session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$fecha=date("Y");
$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//************************************************************************
//							CONSULTANDO TODOS LOS REGISTROS DE LA TABLA
/*$sql_where = "WHERE
					cheques.numero_cheque>0	
				AND
					cheques.reimpreso=1
				AND
					cheques.id_organismo = ".$_SESSION["id_organismo"]."
					"
					
					;
if(isset($_GET['usu_chereimp']))
{
	$bus_usuario=strtoupper($_GET['usu_chereimp']);
	$sql_where.= " AND  (upper(usuario.nombre) like '%$bus_usuario%')";
}
if(isset($_GET['banco_chereimp']))
{
	$bus_banco=strtoupper($_GET['banco_chereimp']);
	if($bus_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$bus_banco%')";
}
if(isset($_GET['cuenta_chereimp']))
{
	$bus_cuenta=strtoupper($_GET['cuenta_chereimp']);
	if($bus_cuenta!='')
	$sql_where.= " AND  (upper(cheques.cuenta_banco) like '%$bus_cuenta%')";
}
if(isset($_GET['prov_chereimp']))
{
	$bus_proveedor=strtoupper($_GET['prov_chereimp']);
	if($bus_proveedor!='')
	$sql_where.= " AND  (upper(proveedor.nombre) like '%$bus_proveedor%')";
}*/
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
 $fechas=$dia.'/'.$mes.'/'.$ayo;if(isset($_GET['desde']))
{
	$where=" WHERE
					cheques.numero_cheque>0	
				AND
					cheques.reimpreso=1
				AND
					cheques.id_organismo = ".$_SESSION["id_organismo"]."
			 
        	  AND cheques.fecha_reimpresion>='$desde' AND cheques.fecha_reimpresion<='$fechas'
		 ";
} //AND chequeras.estatus='1'  AND cheques.estatus!=5				  
if(isset($_GET['id_usuario']))
{
	$id_usuario=$_GET['id_usuario'];
	$where.=" AND cheques.usuario_cheque='$id_usuario'";
} //AND chequeras.estatus='1'				  

if(isset($_GET['rif']))
{
	$rif=$_GET['rif'];
		$where.=" AND cheques.cedula_rif_beneficiario='$rif'
			";
			$as="AND cheques.cedula_rif_beneficiario='$rif'
			";
}

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
	if($tipo!='3')
	{
		$where.=" AND cheques.tipo_cheque=$tipo";
		$as=" AND cheques.tipo_cheque=$tipo";
		}		
}

if(isset($_GET['eva_opcion']))
{
	$op=$_GET['eva_opcion'];
	if($op=='1')
		{
			$where.=" AND cheques.id_proveedor!='0'";
			
		}
	else
		if($op=='2')
			$where.=" AND cheques.cedula_rif_beneficiario!='NULL'";

}
	
$Sql=" SELECT  distinct
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.codigo_banco_reimpreso as id_banco_reimpreso,
				cheques.numero_cheque_reimpreso as numero_reimpreso,
				cheques.cuenta_banco_reimpreso as cuenta_reimpreso,
				cheques.monto_cheque,
				cheques.id_proveedor,
				cheques.nombre_beneficiario,
				cheques.cedula_rif_beneficiario,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes,
				cheques.tipo_cheque,
				cheques.fecha_reimpresion
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
			 
			 ";
$row=& $conn->Execute($Sql);
$id_prove=$row->fields("id_proveedor");
		if(($id_prove=="")||($id_prove==NULL)||($id_prove=='0'))
		{	
			$proveedor=$row->fields("nombre_beneficiario");
		}
		else
		{
			$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
			$row_proveedor=& $conn->Execute($sql_proveedor);
			$proveedor=$row_proveedor->fields("nombre");
		}
		$banco_nombre=strtoupper($row->fields("banco"));

//$nombre=$row->fields("nombre");

/*$where
	ORDER BY 
				cheques.fecha_reimpresion,banco.nombre,cheques.cuenta_banco,cheques.secuencia
*/		
//************************************************************************
if (!$row->EOF)
{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de página
		function Header()
		{	global $banco_nombre;
			global $fecha;	
			global $desde;	global $hasta;global $proveedor;
			
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');	
			$this->Ln(5);	
			$this->SetFont('Arial','B',10);
			$this->Cell(0,10,'Reimpresión de Cheques Beneficiario',0,0,'C');
			$this->ln(5);
			$this->Cell(0,10,strtoupper($proveedor),0,0,'C');
            $this->ln(5);
			$this->Cell(0,10,$banco_nombre,0,0,'C');
            $this->ln(5);
			$this->Cell(0,10,'Desde:'.$desde.'  '.'Hasta:'.$hasta ,0,0,'C');
			$this->ln(10);
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Cell(40,6,		     'USUARIO',			0,0,'L',1);
			$this->Cell(30,6,		     'CHEQUE REIMPRESO ',	0,0,'L',1);
			$this->Cell(40,6,		     'BANCO ',	0,0,'L',1);
			$this->Cell(40,6,		     'CUENTA ',	0,0,'L',1);
			$this->Cell(40,6,		     'CHEQUE EMITIDO',0,0,'L',1);
			$this->Cell(30,6,		     'CUENTA ',	0,0,'L',1);
			$this->Cell(30,6,		     'MONTO ',0,0,'L',1);


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
			$this->Cell(150,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[usuario]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
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
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		
		
		//---------------------------------------------
		$id_banco_reimpreso=$row->fields("id_banco_reimpreso");
		$sql_banco_reimpreso="select nombre  from banco where id_banco='$id_banco_reimpreso'";
		$row_banco_reimpreso=& $conn->Execute($sql_banco_reimpreso);
		$banco_reimpreso=$row_banco_reimpreso->fields("nombre");
		
				$nom=$row->fields("nombre");
		$ape=$row->fields("apellido");
		$nombre=$nom."  ". $ape;
			   //
			$primer=strlen($row->fields("numero_cheque"));
			$n_cheque=$row->fields("numero_cheque");
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
		$primer=strlen($row->fields("numero_reimpreso"));
			$n_cheque_reimpreso=$row->fields("numero_reimpreso");
							switch($primer)
										{
											case 1:
											$n_cheque_reimpreso='00000'.$n_cheque_reimpreso;
											break;
											case 2:
											$n_cheque_reimpreso='0000'.$n_cheque_reimpreso;
											break;
											case 3:
											$n_cheque_reimpreso='000'.$n_cheque_reimpreso;
											break;
											case 4:
											$n_cheque_reimpreso='00'.$n_cheque_reimpreso;
											break;
											case 5:
											$n_cheque_reimpreso='0'.$n_cheque_reimpreso;
											break;
											case 6:
											$n_cheque_reimpreso=$n_cheque_reimpreso;
											break;
											
										}									
		if ($row->fields("estatus")=="1")
			$estatus="Activo";
		else
		if ($row->fields("estatus")=="2")
				$estatus="Inactivo";	
		$monto=$row->fields("monto_cheque");
		$monto= str_replace(",",".",$monto);
	
		//--------------------------------
		if ($row->fields("estatus")=="1")
			$estatus="Activo";
		else
		if ($row->fields("estatus")=="2")
				$estatus="Inactivo";
		//---------------------------------
		$nom=$row->fields("nombre");
		$ape=$row->fields("apellido");
		$nombre_usuario=$nom."  ".$ape;
		$monto_cheques2=$row->fields("monto_cheque");
		$total_general=$total_general+$row->fields("monto_cheque");	
		

			$pdf->Cell(40,6,		   	 $nombre_usuario ,			0,0,'L',0);
			$pdf->Cell(30,6,		     $n_cheque_reimpreso,	0,0,'L',0);
			$pdf->Cell(40,6,		     substr($banco_reimpreso,0,18),	0,0,'L',0);
			$pdf->Cell(30,6,		     $row->fields("cuenta_banco"),	0,0,'L',0);
			$pdf->Cell(40,6,		     $n_cheque,	0,0,'L',0);
			$pdf->Cell(40,6,		     $row->fields("cuenta_reimpreso"),	0,0,'L',0);
			$pdf->Cell(30,6,		     number_format($monto,2,',','.'),	0,0,'R',0);
		
		$pdf->Ln(6);
			$banco_ant=$row->fields("banco");
			$n_cuenta_ant=$row->fields("cuenta_banco");
		
		//	$proveedor_ant=strtoupper($row->fields("proveedor"));
			//----------------	
			$row->MoveNext();
			$banco_sig=$row->fields("banco");
			$n_cuenta_sig=$row->fields("cuenta_banco");
			//$proveedor_sig=strtoupper($row->fields("proveedor"));
			
			if($n_cuenta_ant==$n_cuenta_sig)
			{	
				$n_cuenta="";
				$total_cuenta=$total_cuenta+$monto;
				
			}
			else
			{
				$n_cuenta=$n_cuenta_sig;
				$total_cuenta=$total_cuenta+$monto;
				$pdf->Cell(250,6,"Total Cuenta:".number_format($total_cuenta,2,',','.'),0,1,'R',1);
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
			$total_banco=$total_banco+$monto_cheques2;
			$pdf->Cell(250,6,"Total Banco:".number_format($total_banco,2,',','.'),0,1,'R',1);
			$pdf->Ln();
			$coordenada++;
			$total_cuenta=0;
			$total_banco=0;
		}	
		
								if($coordenada>=16)
								{
								$coordenada=0;
								$n_cuenta=$n_cuenta_sig;
								$pdf->AddPage('L');
								//$pdf->AddPage('');	
								}
		

	}
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(250,6,"TOTAL GENERAL:".number_format($total_general,2,',','.'),1,1,'R',1);
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
			$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'RIF G-20000451-7',0,0,'C');
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
		$pdf->Cell(190,		6,'No se encontraron los datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);
//No se encontraron datos
	$pdf->Output();
}

?>