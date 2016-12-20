<?php
session_start();
//*

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
//include_once('../../../../controladores/num_en_letras.php');
include_once('../../../../controladores/numero_to_letras.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************				
//----------------------------------------------------------------------------------------
if(isset($_GET['id_banco']))
{
	$id_banco =$_GET['id_banco'];
	$where="WHERE 
				1=1 
				 
			AND		
				cheques.id_organismo=$_SESSION[id_organismo]";
				
	$where.=" AND cheques.id_banco=$id_banco";
}
if(isset($_GET['n_cuenta']))
{
	$ncuenta=$_GET['n_cuenta'];
	$where.=" AND cheques.cuenta_banco=$ncuenta";
}
if(isset($_GET['ejecutora']))
{
	$ejecutora=$_GET['ejecutora'];
}
if(isset($_GET['proyecto']))
{
	$proyecto=$_GET['proyecto'];
}
if(isset($_GET['partida']))
{
	$partida=$_GET['partida'];
}

if(isset($_GET['ncheque']))
{
	$ncheque=$_GET['ncheque'];
	$where.=" AND cheques.numero_cheque=$ncheque";
}
if(isset($_GET['secuencia']))
{
	$secuencia=$_GET['secuencia'];
	$where.=" AND cheques.secuencia=$secuencia";
}
if(isset($_GET['proveedor']))
{
	$proveedor=$_GET['$proveedor'];
	//$where.=" AND cheques.numero_cheque=$ncheque";
}
if(isset($_GET['ordenes']))
{
	$ordenes=$_GET['ordenes'];
	//$where.=" AND cheques.numero_cheque=$ncheque";
}


/*//////////////////////////////////////////////////////////////////////////////////
//---------------busqueda del ultimo CHEQUE----------------
$sql_ultimo_emitido = "SELECT 
							ultimo_emitido
					   FROM 
					   		chequeras 
					   	WHERE
							cuenta='$ncuenta'
			   		    AND 
			   		 		id_banco='$id_banco'
						AND
							estatus='1'		
					  ";

	$row_emitido= $conn->Execute($sql_ultimo_emitido);
	if(!$row_emitido->EOF)	
	{
		$n_cheque=$row_emitido->fields("ultimo_emitido");
		$n_cheque_resultado=intval($n_cheque)+1;
		$n_ultimo=intval($n_cheque_resultado)+1;
		$sql_chequeras=" UPDATE chequeras
						SET
								ultimo_emitido='$n_cheque_resultado',
								fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
								ultimo_usuario=".$_SESSION['id_usuario']."
						WHERE
							id_banco='$id_banco'
						AND	
							cuenta='$ncuenta'
						AND
							ultimo_emitido='$n_cheque'
						";
			//die($sql_chequeras);	
		if (!$conn->Execute($sql_chequeras)) 
		die ('Error al registrar: '.$sql_chequeras);
		
	}
	if($n_cheque!="")
	{
		//--- modificando el n_cheque en la tabla cheques
		$sql_cheques=" UPDATE cheques
				SET
						numero_cheque='$n_cheque_resultado',
						estatus='2',
						fecha_ultima_modificacion='".date("Y-m-d H:i:s")."',
						ultimo_usuario=".$_SESSION['id_usuario']."
				WHERE
					id_banco='$id_banco'
				AND	
					cuenta_banco='$ncuenta'
				AND
					numero_cheque='$ncheque'
				";
		//die($sql_cheques);			
		if (!$conn->Execute($sql_cheques)) 
		die ('Error al registrar: '.$sql_cheques);
		
		$sql_orden=" UPDATE \"orden_pagoE\"
				SET
						numero_cheque='$n_cheque_resultado'
				WHERE
					id_banco='$id_banco'
				AND	
					cuenta_banco='$ncuenta'
				AND
					numero_cheque='$ncheque'
				";	
		//die($sql_orden);					
		$conn->Execute($sql_orden); 
		
	}
//////////////////////////////////////////////////////////////////////////////////
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///formatos de fechas llevada sal español
// Obtenemos y traducimos el nombre del día
$dia=date("l");
if ($dia=="Monday") $dia="Lunes";
if ($dia=="Tuesday") $dia="Martes";
if ($dia=="Wednesday") $dia="Miércoles";
if ($dia=="Thursday") $dia="Jueves";
if ($dia=="Friday") $dia="Viernes";
if ($dia=="Saturday") $dia="Sabado";
if ($dia=="Sunday") $dia="Domingo";

// Obtenemos el número del día
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

// Obtenemos el año
$ano=date("Y");
if($_GET['caducidad']==0)
{
	$caducidad="";
}else
	{
		switch($_GET['caducidad'])
		{
		case 1:
			$caducidad="CADUCA A LOS 15 DÍAS";
			break;
		case 2:		
			$caducidad="CADUCA A LOS 60 DÍAS";
			break;
		case 3:
			$caducidad="CADUCA A LOS 90 DÍAS";
			break;
		case 4:
			$caducidad="CADUCA A LOS 120 DÍAS";
			break;
		}
	}
$endoso=$_GET['endosable'];
if($endoso=='1')
	{
	$endosable="NO ENDOSABLE";
	}
	else
	{
	$endosable="";
	}
//////////////////////////
$Sql_firmas="
			SELECT 
				firmas_voucher.id_firmas_voucher,
				firmas_voucher.codigo_director_ochina,
				firmas_voucher.codigo_director_administracion,
				firmas_voucher.codigo_jefe_finanzas,
				firmas_voucher.codigo_preparado_por,
				firmas_voucher.comentarios
			FROM 
				firmas_voucher
			INNER JOIN 
				organismo 
			ON 
				firmas_voucher.id_organismo = organismo.id_organismo
			WHERE
					firmas_voucher.estatus='1';
";			
 					
$Sql="SELECT 
				cheques.id_cheques,
				banco.nombre,
				banco.id_banco,
				cheques.cuenta_banco,
				cheques.numero_cheque,
				cheques.tipo_cheque,
				cheques.id_proveedor,
				proveedor.nombre as proveedor,
				cheques.cedula_rif_beneficiario,
				cheques.monto_cheque,
				cheques.monto_escrito,				
				cheques.concepto,
				cheques.estatus,
				cheques.comentarios				
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo =cheques.id_organismo
			INNER JOIN 
				banco 
			ON 
				cheques.id_banco =banco.id_banco
			INNER JOIN 
				proveedor 
			ON 
				cheques.id_proveedor =proveedor.id_proveedor	
			$where
    		ORDER BY
				cheques.numero_cheque";
				$row=& $conn->Execute($Sql);
				$row_firmas=& $conn->Execute($Sql_firmas);
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
									
		//   
//************************************************************************
if ((!$row->EOF)AND(!$row_firmas->EOF))
{ 
		///
		
		$bancos=split("-",$row->fields("nombre"));
		///		
		$codigo_director=$row_firmas->fields("codigo_director_ochina");
		$codigo_administracion=$row_firmas->fields("codigo_director_administracion");
		$codigo_jefe_finanzas=$row_firmas->fields("codigo_jefe_finanzas");
		$preparado=$row_firmas->fields("codigo_preparado_por");
		
		$sql_director=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_director";
		$row_director=& $conn->Execute($sql_director);
		$nom_director=$row_director->fields("nombre");
		$ape_director=$row_director->fields("apellido");
		$nombre_director=strtoupper($nom_director."  ". $ape_director);	
		 
		$sql2=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_administracion";
		$row_administrador=& $conn->Execute($sql2);
		$nom_administrador=$row_administrador->fields("nombre");
		$ape_administrador=$row_administrador->fields("apellido");
		$nombre_administrador=strtoupper($nom_administrador."  ". $ape_administrador);
		
		$sql3=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_jefe_finanzas";
		$row_jefe=& $conn->Execute($sql3);
		$nom_jefe=$row_jefe->fields("nombre");
		$ape_jefe=$row_jefe->fields("apellido");
		$nombre_jefe=strtoupper($nom_jefe."  ". $ape_jefe);
		
		$sql4=" SELECT	 nombre,apellido from usuario where id_usuario=".$_SESSION['id_usuario']."";			
		$row_preparado=& $conn->Execute($sql4);
		$nom_preparado=$row_preparado->fields("nombre");
		$ape_preparado=$row_preparado->fields("apellido");
		$nombre_preparado=strtoupper($nom_preparado."  ". $ape_preparado);
		$monto_escrito=numero_to_letras($row->fields("monto_cheque"));
		$monto_escrito=strtoupper($monto_escrito);
				//$monto_escrito=convertir_a_letras($row->fields("monto_cheque"));
				
		class PDF extends FPDF
		{
			//Cabecera de página
			function Header()
			{		
				/*$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
				$this->SetFont('Times','B',9);			
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
				$this->Ln();	
				$this->SetFont('Arial','B',14);
				$this->Ln(3);	
				$this->SetFont('Arial','B',8);
				$this->Ln(3);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				$this->SetTextColor(0);*/
				$this->Ln(40);
			}	
			//Pie de página
			function Footer()
		{
			//Posición: a 2,5 cm del final
			/*$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(65,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(62,3,'Impreso por: '.str_replace('<br />',' ',$_SESSION[name]),0,0,'C');
			$this->Cell(65,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFont('barcode','',6);
			$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
	*/	}
	}
		//************************************************************************
	$total=0;

	$pdf=new PDF('P','mm','Legal');
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','B',10);
	$pdf->SetFillColor(255);
	//$pdf->SetAutoPageBreak(auto,15);	
		/*$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',8);
		$pdf->SetFillColor(255);
		$pdf->SetAutoPageBreak(100);
		*/
		$e=0;
		$i=0;
		$xe=0;
//		set_locale(LC_ALL,"es_ES@euro","es_ES","esp");
		//$pdf->Ln(44);
		$pdf->Ln(48);
		$pdf->Cell(155,4,'**********'.number_format($row->fields("monto_cheque"),2,',','.').'***',0,0,'R');
		$pdf->Ln(10);
		$pdf->Cell(100,4,"********".strtoupper($row->fields("proveedor"))."********",0,0,'R');
		$pdf->Ln(20);
		$pdf->Cell(80,4,"La Guaira $dia2 de $mes   $ano",0,0,'R');
		$pdf->Ln(14);
		$pdf->Cell(150,4,$endosable,0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(155,4,$caducidad,0,0,'R');
		$pdf->Ln(22);
		$pdf->Cell(40,4,"".date("d-m-Y")."",0,0,'R');
		$pdf->Cell(80,4,$n_cheque,0,0,'C');
		$pdf->Ln(20);
		$pdf->SetFont('arial','B',8);
	    $pdf->Cell(80,4,substr($monto_escrito,0,65),0,0,'L');
		$pdf->SetFont('arial','B',9);
		$pdf->Cell(55,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'R');
		$pdf->SetFont('arial','B',8);
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,65,50),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,115,50),0,0,'L');
		$pdf->Ln(5);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$act=0;
		//5 COMENT 310
		if((strlen($row->fields("concepto"))>248)&&(strlen($row->fields("concepto"))<310))
		{
		$ln=4;
		$act=1;
		}
		//4 COMENT 186
		elseif((strlen($row->fields("concepto"))>186)&&(strlen($row->fields("concepto"))<248))
		{
		$ln=8;
		;$act=1;
		}
		//3 COMENT 186
		elseif((strlen($row->fields("concepto"))>124)&&(strlen($row->fields("concepto"))<=186))
		{
		//$ln=7
		$ln=12;
		;$act=1;
		}
		// 2 COMENT 124
		elseif((strlen($row->fields("concepto"))>62)&&(strlen($row->fields("concepto"))<=124))
		{
		//$ln=7
		$ln=18;
		;$act=1;
		}
		//1 COMENT 62
		elseif(strlen($row->fields("concepto"))<=62){
		//$ln=9;
		$ln=24;
		$act=1;}
		//0 COMENT
		elseif(strlen($row->fields("concepto"))==0){
		$ln=24;$act=1;}
		$con=strlen($row->fields("concepto"));
		$pdf->MultiCell(180,4,$row->fields("concepto"),0,'LBR','L');
		//.$con
		if($act==1)$pdf->Ln($ln);
	//	$pdf->Ln(15);
		$pdf->Cell(85,4,substr($bancos[0],0,80),0,0,'C');
    	$pdf->Cell(93,4,substr($row->fields("cuenta_banco"),0,80),0,0,'R');
		$pdf->Ln(27);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(70,4,$nombre_jefe,0,0,'L');
    	$pdf->Cell(60,4,$nombre_director,0,0,'C');
		$pdf->Ln(18);
		$pdf->Cell(70,4,$nombre_preparado,0,0,'L');
		$pdf->Cell(60,4,$nombre_administrador,0,0,'C');
		$pdf->Ln(25);
		$pdf->SetFont('arial','B',6);
		$pdf->Cell(100,4,$ejecutora,0,0,'R');
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(60,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'C');
		$pdf->Ln(6);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(20,4,$proyecto,0,0,'L');
		$pdf->Cell(20,4,$partida,0,0,'L');
		/*$pdf->Cell(150,4,'**********'.number_format($row->fields("monto_cheque"),2,',','.').'***',0,0,'R');
		$pdf->Ln(15);
		$pdf->Cell(100,4,"********".strtoupper($row->fields("proveedor"))."********",0,0,'R');
		$pdf->Ln(20);
		$pdf->Cell(50,4,"La Guaira $dia2 de $mes de $ano",0,0,'L');
		$pdf->Ln(20);
		$pdf->Cell(150,4,$endosable,0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(155,4,$caducidad,0,0,'R');
		$pdf->Ln(20);
		$pdf->Cell(40,4,"".date("d-m-Y")."",0,0,'R');
		$pdf->Cell(80,4,$n_cheque,0,0,'C');
		$pdf->Ln(11);
		$pdf->SetFont('arial','B',9);
	    $pdf->Cell(50,4,substr($monto_escrito,0,65),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,4,substr($monto_escrito,65,50),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(50,4,substr($monto_escrito,115,50),0,0,'L');
		
		//$pdf->Cell(100,4,$monto_escrito,0,0,'L');
		//$pdf->Ln();
		$pdf->SetFont('arial','B',9);
		$pdf->Cell(90,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'R');
		$pdf->Ln(4);
		$pdf->Cell(110,4,substr($row->fields("concepto"),0,90),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(110,4,substr($row->fields("concepto"),90),0,0,'L');
		$pdf->Ln(35);
		$pdf->Cell(60,4,substr($row->fields("nombre"),0,80),0,0,'C');
    	$pdf->Cell(70,4,substr($row->fields("cuenta_banco"),0,80),0,0,'R');
		$pdf->Ln(25);
		$pdf->SetFont('arial','B',10);
		$pdf->Cell(50,4,$nombre_jefe,0,0,'L');
    	$pdf->Cell(110,4,$nombre_director,0,0,'C');
		$pdf->Ln(15);
		$pdf->Cell(50,4,$nombre_preparado,0,0,'L');
		$pdf->Cell(110,4,$nombre_administrador,0,0,'C');
		$pdf->Ln(24);
		$pdf->Cell(100,4,"      Tesoreria   ",0,0,'R');
		$pdf->Cell(40,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'R');
		*/$pdf->Output();		

}else{
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
		$pdf->Cell(40,		6,"No se encuentran los datos",			0,0,'L',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>
