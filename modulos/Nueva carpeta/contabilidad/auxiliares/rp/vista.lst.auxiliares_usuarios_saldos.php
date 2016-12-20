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
$c1=0;$c2=0;$c3=0;$c4=0;$c5=0;

$where="WHERE 1=1 ";
if(isset($_GET[fecha]))
{
	$fecha=$_GET[fecha];
	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where="where	saldo_auxiliares.ano=$ayo";	
}
else
{
		$where="";	
}

if(isset($_GET[opcion]))
{
	$opcion=$_GET['opcion'];
}
if(isset($_GET['cuenta']))
{
	$cuenta =$_GET['cuenta'];
	if($cuenta!='')
	$where.="AND cuenta_contable_contabilidad.id='$cuenta'";
}
if(isset($_GET['id_usuario']))
{
	$usuario =$_GET['id_usuario'];
	if($usuario!='')
	$where.="AND usuario.id_usuario='$usuario'";
}
if(($usuario!="")and($cuenta==""))
{
	$tipo_reporte="usuario";
}
else
	$tipo_reporte="todos";
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
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
/*

		SELECT 
				auxiliares.id_auxiliares,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre as descripcion,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				usuario.nombre as name,
				usuario.apellido as apellido,
				usuario.usuario
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				rel_aux_cont.id_contab=cuenta_contable_contabilidad.id	
			INNER JOIN 
				usuario 
			ON 
				auxiliares.ultimo_usuario = usuario.id_usuario	
			$where	
			order by
				cuenta_contable_contabilidad.cuenta_contable,auxiliares.cuenta_auxiliar	
*/
$Sql="	SELECT 
				auxiliares.id_auxiliares,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre as descripcion,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				usuario.nombre as name,
				usuario.apellido as apellido,
				usuario.usuario,
				saldo_auxiliares.saldo_inicio as saldo_inicio_aux,
				saldo_auxiliares.debe as debe_aux,
				saldo_auxiliares.haber as haber_aux,
				naturaleza_cuenta.codigo  AS codigo
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN
				saldo_auxiliares
			ON
				auxiliares.id_auxiliares=saldo_auxiliares.cuenta_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				saldo_auxiliares.cuenta_contable=cuenta_contable_contabilidad.id	
			INNER JOIN 
				usuario 
			ON 
				auxiliares.ultimo_usuario = usuario.id_usuario	
			
			inner join
					naturaleza_cuenta
				on
					cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id	

			$where	
			order by
				cuenta_contable_contabilidad.cuenta_contable,auxiliares.cuenta_auxiliar	
				";
$row=& $conn->Execute($Sql);
//************************************************************************
if (!$row->EOF)
{ 
	$usuario=strtoupper($row->fields("usuario"));
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $tipo_reporte;
			global $usuario;
			global $fecha;
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,25);
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'Viceministerio de Servicios',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,'Dirección General de Empresas y Servicios',0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación',0,0,'C');
			$this->Ln(10);	
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'Balance de Auxiliares  al '." ".$fecha,0,0,'C');
			$this->SetFillColor(255);
			$this->SetTextColor(0);
			$this->Ln(8);
			$this->SetFont('Arial','B',7);
			$this->Cell(20,6,		"CÓDIGO ",0,0,'L',1);	
			$this->Cell(40,6,		"NOMBRE ",0,0,'L',1);
			$this->Cell(26,6,			 'SALDO ANTERIOR',		0,0,'L',1);
			$this->Cell(26,6,		     'DEBITO MES',	0,0,'L',1);
			$this->Cell(26,6,		     'CREDITO MES',	0,0,'L',1);
			$this->Cell(26,6,		     'SALDO MES',	0,0,'L',1);
			$this->Cell(26,6,		     'SALDO ACTUAL',	0,0,'L',1);
			$this->Ln(8);
				if($tipo_reporte=="usuario")
				{
					$this->Ln(5);
					$this->SetFont('Arial','B',12);
					$this->Cell(0,10,'USUARIO:'." ".$usuario,0,0,'C');

				}
			$this->SetFont('Times','B',6);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(175);
			$this->SetTextColor(0);
			//$this->Ln(10);	

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
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(auto,15);	
	$a="omega";
	$b="1";
	$cta_ant=1;
	$cta_sig=2;
	//die($a);
	while (!$row->EOF) 
	{		
///////////////////////////////////////calculando los saldos///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
	$med=strlen($row->fields("debe_aux"));
	$med=$med-2;
	$debe=substr($row->fields("debe_aux"),1,$med);
	$debe_vector=split(",",$debe);
	
	$med2=strlen($row->fields("haber_aux"));
	$med2=$med2-2;
	$haber=substr($row->fields("haber_aux"),1,$med2);
	$haber_vector=split(",",$haber);
	$saldo_inicio=$row->fields("saldo_inicio_aux");
	$saldo_vector=split(",",$saldo_inicio);
	//-
	$conter=0;
	//$mes=date("m");
	//$mes=10;
	$mes_ant=$mes-1;
	$debe_total=0;
	$haber_total=0;
	$total_cuenta_debe_haber="";
	$cuenta_sumas="";
	//claculando el saldo inicial
	$saldo_vector2=$saldo_vector[$mes-1];
	$saldo_inicio2=number_format($saldo_vector2,2,',','.');
			
	//calculando el monto del saldo anterior	
		while($conter!=$mes_ant)
		{
			$debe_total=$debe_total+$debe_vector[$conter];
			$haber_total=$haber_total+$haber_vector[$conter];
			$conter++;
		}
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$total_cuenta_debe_haber=$debe_total-$haber_total;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
		}
	//-	
	//calculando el monto del debito y credito del mes	
		$conter=0;
		$debe_total2=0;
		$haber_total2=0;
		while($conter!=$mes)
		{
			$debe_total2=$debe_total2+$debe_vector[$conter];
			$haber_total2=$haber_total2+$haber_vector[$conter];
			$conter++;
		}
		$debe_total3=$debe_vector[$mes];
		$haber_total3=$haber_vector[$mes];
		$saldo_mes=$debe_total3+$haber_total3;
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$saldo_mes=$debe_total2-$haber_total2;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$saldo_mes=$haber_total2-$debe_total2;
		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$saldo_mes=$haber_total2-$debe_total2;
		}
	// valor de verificacion para ver si el mes tiene saldo
		
	//	$saldo_mes=$debe_total2-$haber_total2;
		//FALTA CC,CO 
	//-	SALDO ACTUAL
	$saldo_actual=$saldo_vector2-$saldo_mes;
	$saldo_actual2=$saldo_mes2-$total_cuenta_debe_haber;
	//-
	if((($debe_total2!="0")&&($haber_total2!="0"))||(($debe_total2!="0")||($haber_total2!="0")))
	{
		$valor_comparacion=1;
			
	}	else
		$valor_comparacion=0;
		$alguno=$alguno+$valor_comparacion;	
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	
	$valores=4;
		$total_cuenta_debe_haber2=number_format($total_cuenta_debe_haber,2,',','.');
		
		$debito_mes=number_format($debe_total3,2,',','.');
		$credito_mes=number_format($haber_total3,2,',','.');
		$saldo_mes_total2=number_format($saldo_mes2,2,',','.');
		$saldo_mes_total=number_format($saldo_mes,2,',','.');
		$saldo_actual_total=number_format($saldo_actual2,2,',','.');
		$saldo_ant_mes=number_format($saldo_inicio2,2,',','.');
	//$valor_comparacion=$debe_total2+$haber_total2;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($a=="omega")
		{
			
			
			$pdf->SetFont('Times','B',8);
			$pdf->SetFillColor(175);
			$pdf->SetTextColor(0);
			if($tipo_reporte=="todos")
			{
/*nn*******************************************cabecera**********************************************************************/			
			
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);
			
			/*$pdf->Cell(26,6,				number_format($c1,1,',','.'),0,0,'L',1);
			$pdf->Cell(26,6,				number_format($c1,2,',','.'),	0,0,'L',1);
			$pdf->Cell(26,6,				number_format($c1,3,',','.'),	0,0,'L',1);
			$pdf->Cell(26,6,				number_format($c1,4,',','.'),	0,0,'L',1);
			$pdf->Cell(26,6,				number_format($c1,5,',','.')	,	0,0,'L',1);
			$pdf->Ln(5);*/
					if($valor_comparacion!=0)
					{
					$pdf->Ln(4);
					$alguno=0;	
					$cuenta=$row->fields("cuenta_contable");
					$pdf->Cell(190,6,		"Cuenta Contable"." ".$row->fields("cuenta_contable")."      ".strtoupper(substr($row->fields("descripcion"),0,50)),0,0,'C',1);

					$pdf->Ln(8); 
					$b=1;
					}
/*nn***************************************************************************************************************************/		
			}
			
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
		if(($a=="alpha")||($b==1))
		{
		if($tipo_reporte=="todos")
			{
		/*mn*********************************************************************************************************************/			
					$pdf->SetFont('Times','B',6);
					$pdf->SetFillColor(255);
					$pdf->SetTextColor(0);
					if($valor_comparacion!=0)
					{
							if($opcion==1)
							{
								$pdf->Cell(20,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
								$pdf->Cell(40,6,				substr($row->fields("nombre"),0,40),0,0,'L',1);
								$pdf->Cell(20,6,				$saldo_mes_total,0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				$saldo_mes_total,	0,0,'R',1);
								$c1=$c1+$saldo_mes;
								$c5=$c5+$saldo_mes;
							$entra="ajuro";
							}
							else
							if($opcion==2)
							{
								$pdf->Cell(20,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
								$pdf->Cell(40,6,				substr($row->fields("nombre"),0,40),0,0,'L',1);
								$pdf->Cell(20,6,				$total_cuenta_debe_haber2,0,0,'R',1);
								$pdf->Cell(26,6,				$debito_mes,	0,0,'R',1);
								$pdf->Cell(26,6,				$credito_mes,	0,0,'R',1);
								$pdf->Cell(26,6,				$saldo_mes_total2,	0,0,'R',1);
								$pdf->Cell(26,6,				$saldo_actual_total,	0,0,'R',1);
								$c1=$c1+$total_cuenta_debe_haber2;
								$c2=$c2+$debito_mes;
								$c3=$c3+$credito_mes;
								$c4=$c4+$saldo_mes_total;
								$c5=$c5+$saldo_actual_total;
								$entra="ajuro";
							}
							/*$pdf->Cell(40,6,				substr($row->fields("nombre"),0,40),0,0,'C',1);
							$pdf->Cell(20,6,				$saldo_inicio2,0,0,'R',1);
							$pdf->Cell(26,6,				$debito_mes,	0,0,'R',1);
							$pdf->Cell(26,6,				$credito_mes,	0,0,'R',1);
							$pdf->Cell(26,6,				$saldo_mes_total,	0,0,'R',1);
							$pdf->Cell(26,6,				$saldo_actual_total	,	0,0,'R',1);*/
							
							/*$c1=$c1+saldo_vector2;
							$c2=$c2+debe_total2;
							$c3=$c3+haber_total2;
							$c4=$c4+$saldo_mes;
							$c5=$c5+$saldo_actual;*/
							
							
					
					$b=2;
					$pdf->Ln(5);
					}
					//$pdf->Cell(50,6,				$where,	0,0,'L',1);
		/*mn***********************************************************************************************************************/
				}
		
		}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$cta_ant=$row->fields("cuenta_contable");
			$row->MoveNext();
			
		$cta_sig=$row->fields("cuenta_contable");
		
		
		if(($cta_ant==$cta_sig)&&($valor_comparacion!=0))
		//($b==1))
		{
			$a="alpha";	
			$conta_cuenta=$conta_cuenta+1;
		}
		/*if($cta_ant==$cta_sig)
		{
			$a="alpha";	
			//$conta_cuenta=$conta_cuenta+1;
		}*/
		if($cta_ant!=$cta_sig)
		{
			
			if(($valor_comparacion!=0)||($entra=="ajuro"))
			{
				$pdf->SetFont('Times','B',6);
				$pdf->Cell(60,6,				"TOTAL CUENTA"." ".$cuenta,0,0,'R',1);
				$pdf->Cell(20,6,				number_format($c1,2,',','.'),0,0,'R',1);
				$pdf->Cell(26,6,				number_format($c2,2,',','.'),	0,0,'R',1);
				$pdf->Cell(26,6,				number_format($c3,2,',','.'),	0,0,'R',1);
				$pdf->Cell(26,6,				number_format($c4,2,',','.'),	0,0,'R',1);
				$pdf->Cell(26,6,				number_format($c5,2,',','.'),	0,0,'R',1);
				$pdf->Ln(5);
				$c1=0;$c2=0;$c3=0;$c4=0;$c5=0;
				$entra="";
				//$debe_total2=0;$haber_total2=0;
				$valor_comparacion=0;
				$a="omega";

			}
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
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>