<?php
session_start();
//ini_set("memory_limit","20M");

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
	$usuario3 =$_GET['id_usuario'];
	if($usuario3!='')
	$where.="AND usuario.id_usuario='$usuario3'";
}
if(($usuario3!="")and($cuenta==""))
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
				cuenta_contable_contabilidad.id as idcuenta,
				cuenta_contable_contabilidad.nombre as descripcion,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.id_auxiliares,
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
				cuenta_contable_contabilidad.cuenta_contable,(auxiliares.cuenta_auxiliar::integer) asc	
				";
$row=& $conn->Execute($Sql);
$mes=$mes-1;
//************************************************************************
if (!$row->EOF)
{ 
	$usuario2=strtoupper($row->fields("usuario"));
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $tipo_reporte;
			global $usuario2;
			global $fecha;
			global $mes;
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
			$this->SetFont('Times','B',8);
			$this->Cell(0,10,'BALANCE DE AUXILIARES AL '." ".$fecha,0,0,'C');
		/*	$this->SetFillColor(255);
			$this->SetTextColor(0);*/
			$this->SetFillColor(175) ;
			$this->SetTextColor(0);
			$this->Ln(8);
			$this->SetFont('Times','B',7);
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
					$this->SetFont('Times','B',12);
					$this->Cell(0,10,'USUARIO:'." ".$usuario2,0,0,'C');

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
			//Times italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION['usuario']),0,0,'C');
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
	$pdf->SetFont('Times','',7);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(auto,15);	
	$a="omega";
	$b="1";
	$cta_ant=1;
	$cta_sig=2;
	//die($a);
	while (!$row->EOF) 
	{
//verificando si se muestra o no la info
$idcuenta=$row->fields("idcuenta");
$idaux=$row->fields("id_auxiliares");

$sql_v="
			SELECT 
				   id_saldo_auxiliar, id_organismo, ano, cuenta_contable, cuenta_auxiliar, 
				   saldo_inicio, debe, haber, comentarios, ultima_modificacion, 
				   ultimo_usuario
 		 FROM 
		 			saldo_auxiliares

				where
					cuenta_contable='$idcuenta'
				and
				ano='$ayo'	
					
";
$rowv=& $conn->Execute($sql_v);

while (!$rowv->EOF)
{
	$medida=strlen($rowv->fields("saldo_inicio"));
	$medida=$medida-2;
	$saldo_inicio_v=substr($rowv->fields("saldo_inicio"),1,$medida);
	$saldo_vector_v=split(",",$saldo_inicio_v);
		//-
	$conter2=0;
	//$mes=date("m");
	//$mes=10;
	$mes2=$mes;
	while($conter2<=$mes2)
	{
		$saldo_vector_v2=$saldo_vector_v2+$saldo_vector_v[$conter2];
		$conter2++;
	}$conter2=0;
		if($saldo_vector_v2!='0')
		{
			$mostrar="si";
		}
		else
			$mostrar="no";
$rowv->MoveNext();
}
/////////////////////////////////////////	
	
			
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
	
	
	$med3=strlen($row->fields("saldo_inicio_aux"));
	$med3=$med3-2;
	$saldo_inicio=substr($row->fields("saldo_inicio_aux"),1,$med3);
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
	$mes23=$mes-1;
	//claculando el saldo inicial
	while($conter<=$mes)
	{
		$saldo_vector2=$saldo_vector[$conter];
		$conter++;
	}
	
	
			
	
		$conter=0;
		$debe_total2=0;
		$haber_total2=0;
		while($conter!=$mes)
		{
			$debe_total2=$debe_total2+$debe_vector[$conter];
			$haber_total2=$haber_total2+$haber_vector[$conter];
			$conter++;
		}
		$debe_total3=$debe_vector[$mes-1];
		$haber_total3=$haber_vector[$mes-1];
		//$saldo_mes=$debe_total3+$haber_total3;
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$saldo_mes=$debe_total-$haber_total;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$saldo_mes=$haber_total-$debe_total;
			$saldo_mes=$saldo_mes*-1;
			//* aca multiplico por -1 ya q  los pasivos aumentan por el haber y en e balance deb aparecer-
			$saldo_vector2=$saldo_vector2*(-1);

		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$saldo_mes=$haber_total-$debe_total;
			$saldo_mes=$saldo_mes*-1;
		}
			if($row->fields("codigo")=='CO  ')
		{
			if(($debe_total!=0)&&($haber_total==0))
			{
				$saldo_mes=$debe_total;
				$saldo_mes=$saldo_mes*-1;
			}
			else	
			if(($haber_total!=0)&&($debe_total==0))
			{
				$saldo_mes=$haber_total;
				$saldo_mes=$saldo_mes*-1;
			}
			if(($debe_total2=0)&&($haber_total!=0))
			{
				$saldo_mes=$debe_total;
				$saldo_mes=$saldo_mes*-1;
			}
		}
	// valor de verificacion para ver si el mes tiene saldo
		
	//	$saldo_mes=$debe_total2-$haber_total2;
		//FALTA CC,CO 
		$saldo_inicio2=number_format($saldo_vector2,2,',','.');
	//-	SALDO ACTUAL
	$saldo_actual2=$saldo_mes2-$total_cuenta_debe_haber;
	//-
	if((($debe_total2!="0")&&($haber_total2!="0"))||(($debe_total2!="0")||($haber_total2!="0")))
	{
		$valor_comparacion=1;
			
	}	else
		$valor_comparacion=0;
	if(($saldo_inicio2!="0")&&($mostrar=="si"))
	{
		$valor_comparacion=1;
	}	
		$alguno=$alguno+$valor_comparacion;	
	//VERIFICANDO SI LA CUENTA ES DE TOTAL////////////
	
	$valores=4;
	$debe_total=$debe_vector[$mes];
	$haber_total=$haber_vector[$mes];
		
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G   '))
		{
			$total_cuenta_debe_haber=$debe_total-$haber_total;
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
			// aca le multiplico *-1 ya que  los pasivos aumentan por el haber y si e spasivo
			
			
		}
		else
		if($row->fields("codigo")=='R   ')
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
		}		
		$total_cuenta_debe_haber2=number_format($total_cuenta_debe_haber,2,',','.');
			$saldo_actual=$saldo_vector2-$total_cuenta_debe_haber;
	///vuelv a preguntar si es pasivo ya q realizo el calculo arriba... hay q depurar el codigo q gracias a las modificaciones del año pasado
	if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT ')or($row->fields("codigo")=='I   '))
		{
			
			// aca le multiplico *-1 ya que  los pasivos aumentan por el haber y si e spasivo
			$total_cuenta_debe_haber=$total_cuenta_debe_haber*(-1);
			$saldo_actual=$saldo_actual*(-1);
			
		}
	///
		$debito_mes=number_format($debe_total,2,',','.');
		$credito_mes=number_format($haber_total,2,',','.');
		$saldo_mes_total2=number_format($saldo_mes2,2,',','.');
		$saldo_mes_total=number_format($saldo_mes,2,',','.');
		$saldo_actual_total=number_format($saldo_actual,2,',','.');
		$saldo_ant_mes=number_format($saldo_inicio2,2,',','.');
	//$valor_comparacion=$debe_total2+$haber_total2;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($a=="omega")
		{
			
			
			$pdf->SetFont('Times','b',8);
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
					if(($valor_comparacion!=0)&&($mostrar=="si"))
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
					$pdf->SetFont('Times','',6);
					$pdf->SetFillColor(255);
					$pdf->SetTextColor(0);
					if($valor_comparacion!=0)
					{
							if((($debito_mes=="0,00")||($credito_mes=="0,00"))&&($saldo_inicio2!='0,00'))
							{
								$pdf->Cell(20,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
								$pdf->Cell(40,6,				substr($row->fields("nombre"),0,40),0,0,'L',1);
								$pdf->Cell(20,6,				$saldo_inicio2,0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				"0,00",	0,0,'R',1);
								$pdf->Cell(26,6,				$saldo_inicio2,	0,0,'R',1);
								$c1=$c1+$saldo_vector2;
								$c5=$c5+$saldo_vector2;
								$entra="ajuro";
								$b=2;
								$pdf->Ln(5);
							}
							else
							if(($debito_mes!="0,00")||($credito_mes!="0,00"))
							{
								$pdf->Cell(20,6,				substr($row->fields("cuenta_auxiliar"),0,60),					0,0,'L',1);
								$pdf->Cell(40,6,				substr($row->fields("nombre"),0,40),0,0,'L',1);
								$pdf->Cell(20,6,				$saldo_inicio2,0,0,'R',1);
								$pdf->Cell(26,6,				$debito_mes,	0,0,'R',1);
								$pdf->Cell(26,6,				$credito_mes,	0,0,'R',1);
								$pdf->Cell(26,6,				$total_cuenta_debe_haber2,	0,0,'R',1);
  								$pdf->Cell(26,6,				$saldo_actual_total,	0,0,'R',1);
								$c1=$c1+$saldo_vector2;
								$c2=$c2+$debito_mes;
								$c3=$c3+$credito_mes;
								$c4=$c4+$saldo_mes;
								$c5=$c5+$saldo_actual;
								$entra="ajuro";
								$b=2;
								$pdf->Ln(5);

							}
							$saldo_vector_v2=0;
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
		if(($cta_ant!=$cta_sig)&&($mostrar=="si"))
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
				$saldo_vector2=0;
				//$debe_total2=0;$haber_total2=0;
				$valor_comparacion=0;
				$a="omega";
				$mostrar="";

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
						
			$this->SetFont('Times','B',11);
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
	$pdf->SetFont('Times','',10);
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