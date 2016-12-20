<?php
session_start();
//*

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
//require('../../../../utilidades/fpdf153/fpdf.php');
require('../../../../utilidades/rotation.php');
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
	$where.=" AND cheques.cuenta_banco='$ncuenta'";
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
				cheques.comentarios	,
				cheques.fecha_ultima_modificacion,
				cheques.fecha_firma,
				cheques.fecha_cheque		
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
		////////////////////////////////////
		$fecha_firma=substr($row->fields("fecha_firma"),0,10);
		//$fecha_firma=substr($fecha_firma,0,10);
		$Sql_firmas="
			SELECT 
				firmas_voucher.id_firmas_voucher,
				firmas_voucher.codigo_director_ochina,
				firmas_voucher.codigo_director_administracion,
				firmas_voucher.codigo_jefe_finanzas,
				firmas_voucher.codigo_preparado_por,
				firmas_voucher.comentarios,
				firmas_voucher.fecha_firma
			FROM 
				firmas_voucher
			INNER JOIN 
				organismo 
			ON 
				firmas_voucher.id_organismo = organismo.id_organismo
			where
				firmas_voucher.fecha_firma='$fecha_firma'
			";			
				$row_firmas=& $conn->Execute($Sql_firmas);
///////////////////////////////////////////////////////				firmas_voucher.fecha_firma='$fecha_firma'
		/*$ultimo_mod_cheque=$row->fields("fecha_ultima_modificacion");
		$ayo_mes=$row->fields("ayo_mes");
		$vector_ayo_mes_cheque=split("-",$ultimo_mod_cheque);
		$vector_ayo_mes_firma=split("-",$ayo_mes);
		if(($vector_ayo_mes_firma[0]==$vector_ayo_mes_cheque[1])&&($vector_ayo_mes_firma[1]==$vector_ayo_mes_cheque[0]))*/
//////////////////////////////////////////////////////		
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
if ((!$row->EOF)&&(!$row_firmas->EOF))
{
///formatos de fechas llevada sal español
// Obtenemos y traducimos el nombre del día
//$dia=date("l");
$dia=substr($row->fields("fecha_cheque"),8,2);
if ($dia=="Monday") $dia="Lunes";
if ($dia=="Tuesday") $dia="Martes";
if ($dia=="Wednesday") $dia="Miércoles";
if ($dia=="Thursday") $dia="Jueves";
if ($dia=="Friday") $dia="Viernes";

if ($dia=="Saturday") $dia="Sabado";
if ($dia=="Sunday") $dia="Domingo";

// Obtenemos el número del día
$dia2=substr($row->fields("fecha_cheque"),8,2);

// Obtenemos y traducimos el nombre del mes

$mes=substr($row->fields("fecha_cheque"),5,2);
if ($mes=="01") $mes="Enero";
if ($mes=="02") $mes="Febrero";
if ($mes=="03") $mes="Marzo";
if ($mes=="04") $mes="Abril";
if ($mes=="05") $mes="Mayo";
if ($mes=="06") $mes="Junio";
if ($mes=="07") $mes="Julio";
if ($mes=="08") $mes="Agosto";
if ($mes=="09") $mes="Setiembre";
if ($mes=="10") $mes="Octubre";
if ($mes=="11") $mes="Noviembre";
if ($mes=="12") $mes="Diciembre";

// Obtenemos el año
$ano=substr($row->fields("fecha_cheque"),0,4);
///			
		$codigo_director=$row_firmas->fields("codigo_director_ochina");
		$codigo_administracion=$row_firmas->fields("codigo_director_administracion");
		$codigo_jefe_finanzas=$row_firmas->fields("codigo_jefe_finanzas");
		$preparado=$row_firmas->fields("codigo_preparado_por");
		///
			$bancos=split("-",$row->fields("nombre"));
		///	
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
//FUNCIONES
function _Line($x, $y) {
        $this->_out(sprintf('%.2F %.2F l', $x * $this->k, ($this->h - $y) * $this->k));
    }
//				
		class PDF extends PDF_Rotate
		{
			function RotatedText($x, $y, $txt, $angle)
				{
					//Text rotated around its origin
					$this->Rotate($angle,$x,$y);
					$this->Text($x,$y,$txt);
					$this->Rotate(0);
				}

			//Cabecera de página
			function Header()
			{		
				
				$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
				$this->SetFont('Times','B',9);			
				$this->Cell(0,5,'República Bolivariana de Venezuela',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Ministerio del Poder Popular para la Defensa',0,0,'C');
				$this->Ln();
				$this->Cell(0,5,'Estado Mayor de la Defensa',0,0,'C');
				$this->Ln();			
				$this->Cell(0,5,'Dirección General de Control de Gestión de Empresas y Servicios',0,0,'C');			
				$this->Ln();			
				$this->Cell(0,5,'Oficina Coordinadora de Hidrografía y Navegación'.$fecha_firma,0,0,'C');
				
				$this->SetTextColor(0);
			   	$this->SetFont('Arial','B',50);
    			//$this->SetTextColor(255,192,203);
				$this->SetTextColor(120,120,120);
    			$this->RotatedText(35,190,'COPIA VOUCHER',45);
				//
				$this->Ln();
				$this->Ln();	
				$this->SetFont('Arial','B',14);
				$this->Ln(3);	
				$this->SetFont('Arial','B',8);
				$this->Ln(3);
				$this->SetFont('Arial','B',11);
				$this->SetLineWidth(0.3);
				$this->SetFillColor(120) ;
				//$this->Ln(40);
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
	$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,20,5,10', 'phase' => 10, 'color' => array(255, 0, 0));
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
		$pdf->SetFont('Arial','B',20);
		//$pdf->Cell(150,4,'COPIA CHEQUE IMPRESO# '.$n_cheque,0,0,'R');
		// $pdf->Ln(40);
		$pdf->SetFont('arial','B',10);
		//
		$pdf->Line(10, 46, 10, 114, $style);
		$pdf->Line(200, 46, 200, 114, $style);
		$pdf->Line(10, 46, 200, 46 , $style);
		$pdf->Line(10, 114, 200, 114 , $style);
		//CUADROS FECHA, NCHEQUE CODIGO CONTABLE
		//LINEAS HORIZONTALES SUPERIOR
		$pdf->Line(10, 120, 76, 120, $style);
		$pdf->Line(78, 120, 144, 120, $style);
		$pdf->Line(146, 120, 200, 120, $style);
		//LINEAS HORIZONTALES INFERIOR
		$pdf->Line(10, 135, 76, 135, $style);
		$pdf->Line(78, 135, 144, 135, $style);
		$pdf->Line(146, 135, 200, 135, $style);
		//	LINEAS VERTICALES DE LOS CUADROS  FECHA, NCHEQUE CODIGO CONTABLE	
		$pdf->Line(10, 120, 10, 135, $style);
		$pdf->Line(76, 120, 76, 135, $style);
		$pdf->Line(78, 120, 78, 135, $style);
		$pdf->Line(144, 120, 144, 135, $style);
		$pdf->Line(146, 120, 146, 135, $style);
		$pdf->Line(200, 120, 200, 135, $style);
		//linea de monto escrito
		//h
		$pdf->Line(10, 138, 200,138, $style);
		$pdf->Line(10, 200, 200,200, $style);

		//BANCO Y CUENTA
		$pdf->Line(35, 198, 70,198, $style);
		$pdf->Line(136, 198, 180,198, $style);		
		//v
		$pdf->Line(10, 138, 10, 200, $style);
		$pdf->Line(200, 138, 200, 200, $style);
		//lineas de FIRMAS director
		//h
		$pdf->Line(10, 205, 200,205, $style);
		$pdf->Line(10, 225, 200,225, $style);
		$pdf->Line(10, 245, 200,245, $style);
		
		//v
		$pdf->Line(10, 205, 10, 245, $style);
		$pdf->Line(200, 205, 200, 245, $style);
		//v2
		$pdf->Line(70, 205, 70, 245, $style);
		$pdf->Line(145, 205, 145, 245, $style);
		// FRIMAS
		$pdf->Line(15, 222,65,222, $style);
		$pdf->Line(75, 222,130,222, $style);	
		$pdf->Line(15, 240,65,240, $style);
		$pdf->Line(75, 240,130,240, $style);
		//unidad ejecutora
		//h
		$pdf->Line(10, 250, 200,250, $style);
		$pdf->Line(10, 265, 200,265, $style);
		//v
		$pdf->Line(10, 250, 10, 265, $style);
		$pdf->Line(80, 250, 80, 265, $style);
		$pdf->Line(120, 250, 120, 265, $style);
		$pdf->Line(155, 250, 155, 265, $style);
		$pdf->Line(200, 250, 200, 265, $style);
		//HORIZONTALES PRESUPUETO
		$pdf->Line(10, 256,80,256, $style);
		//PROGRAMA PARTIDA Y SUB PARTIDA verticales
		$pdf->Line(27, 256,27,265, $style);
		$pdf->Line(47, 256,47,265, $style);
		//horizontal
		$pdf->Line(47, 260,80,260, $style);
		$pdf->Line(10, 270, 155, 270, $style);//1
		$pdf->Line(10, 275, 200, 275, $style);//2
		$pdf->Line(10, 280, 200, 280, $style);//3
		//vertical
		$pdf->Line(10, 260, 10, 280, $style);
		$pdf->Line(80, 260, 80, 280, $style);
		$pdf->Line(120, 260, 120, 280, $style);
		$pdf->Line(155, 260, 155, 280, $style);
		$pdf->Line(200, 260, 200, 280, $style);
		
		/*$pdf->Cell(150,4,'**********'.number_format($row->fields("monto_cheque"),2,',','.').'***','0',0,'R');
		$pdf->Ln();
		$pdf->Cell(120,4,'(DATOS DEL CHEQUE EMITIDO)',0,0,'R');
		$pdf->Ln(15);
		$pdf->Cell(100,4,"********".strtoupper($row->fields("proveedor"))."********",0,0,'R');
		$pdf->Ln(20);
		$pdf->Cell(80,4,"La Guaira $dia2 de $mes de $ano",'0',0,'L');
		$pdf->Ln(20);
		$pdf->Cell(150,4,$endosable,0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(155,4,$caducidad,0,0,'R');
		$pdf->Ln(15);
		$pdf->Cell(50,4,"Fecha:",0,0,'L');
		$pdf->Cell(40,4,"Nº cheque:",0,0,'R');
		$pdf->Cell(80,4,"Cuenta Contable:",0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(40,4,"".date("d-m-Y")."",0,0,'R');
		$pdf->Cell(55,4,$n_cheque,0,0,'R');
		$pdf->Ln(12);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(20,4,"He recibido de la Ofgicina Coordinadora de Hidrografia OCHINA la cantidad de:",0,0,'L');
		$pdf->Ln();
	    $pdf->Cell(80,4,substr($monto_escrito,0,65),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,65,50),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,115,50),0,0,'L');
		
		//$pdf->Cell(100,4,$monto_escrito,0,0,'L');
		//$pdf->Ln();
		$pdf->SetFont('arial','B',9);
		$pdf->Cell(85,4,"(Bs. ".number_format($row->fields("monto_cheque"),2,',','.')."  )por concepto de:",0,0,'R');
		$pdf->Ln(4);
		$pdf->Cell(140,4,substr($row->fields("concepto"),0,90),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(140,4,substr($row->fields("concepto"),90),0,0,'L');
		$pdf->Ln(33);
		$pdf->Cell(24,4,"Banco Girado",0,0,'C');
		$pdf->Cell(80,4,substr($bancos[0],0,80),0,0,'L');
		$pdf->Cell(20,4,"Cta.Cte Nº ",0,0,'C');
    	$pdf->Cell(40,4,substr($row->fields("cuenta_banco"),0,80),0,0,'R');
		$pdf->Ln(13);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(60,4,"  DIRECTOR DE ADMIN Y FINANZAS:",0,0,'C');
		$pdf->Cell(65,4,"  DIRECTOR OCHINA:",0,0,'C');
		$pdf->Cell(65,4,"  RECIBI CONFORME:",0,0,'C');
		$pdf->SetFont('arial','B',6);
		$pdf->Ln(4);
		$pdf->Cell(50,4,$nombre_jefe,0,0,'C');
    	$pdf->Cell(95,4,$nombre_director,0,0,'C');
		$pdf->Ln(14);
		$pdf->Cell(60,4,"  PREPARADO POR:",0,0,'C');
		$pdf->Cell(65,4,"  ADMINISTRADOR OCHINA:",0,0,'C');
		$pdf->Cell(50,4,"  CI:",0,0,'C');
		
		$pdf->Ln(4);
		$pdf->Cell(40,4,$nombre_preparado,0,0,'C');
		$pdf->Cell(100,4,$nombre_administrador,0,0,'C');
		$pdf->Ln(23);
		$pdf->Cell(10,4,"      PRESUPUESTO   ",0,0,'L');
		$pdf->Cell(97,4,"      UNIDAD EJECUTORA   ",0,0,'R');
		$pdf->Cell(30,4,"      CARGO   ",0,0,'R');
		$pdf->Cell(45,4,"      OBSERVACIONES   ",0,0,'R');
		$pdf->Ln();
		$pdf->Cell(15,4,"      PROGRAMA   ",0,0,'C');
		$pdf->Cell(25,4,"      PARTIDA   ",0,0,'C');
		$pdf->Cell(20,4,"      SUBPARTIDA   ",0,0,'R');
		$pdf->Ln();
		$pdf->SetFont('arial','B',5);
		$pdf->Cell(60,4,"      GENERICO ESPECIFICO   ",0,0,'R');
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(40,4,"      Tesoreria   ",0,0,'R');
		$pdf->Cell(33,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'R');
		$pdf->Ln();
		$pdf->Cell(185,4,"      PRESUPUESTO   ".date("Y"),0,0,'R');
*/	
$fecha_cheque = substr($row->fields("fecha_cheque"),0,10);
$fecha_cheque= substr($fecha_cheque,8,2)."/".substr($fecha_cheque,5,2)."/".substr($fecha_cheque,0,4);
	
$pdf->Cell(150,4,'**********'.number_format($row->fields("monto_cheque"),2,',','.').'***','0',0,'R');
		$pdf->Ln();
		$pdf->Cell(120,4,'(DATOS DEL CHEQUE EMITIDO)',0,0,'R');
		$pdf->Ln(15);
		$pdf->Cell(100,4,"********".strtoupper($row->fields("proveedor"))."********",0,0,'R');
		$pdf->Ln(20);
		$pdf->Cell(80,4,"La Guaira $dia2 de $mes de $ano",'0',0,'L');
		$pdf->Ln(20);
		$pdf->Cell(150,4,$endosable,0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(155,4,$caducidad,0,0,'R');
		$pdf->Ln(15);
		$pdf->Cell(50,4,"Fecha:",0,0,'L');
		$pdf->Cell(40,4,"Nº cheque:",0,0,'R');
		$pdf->Cell(80,4,"Cuenta Contable:",0,0,'R');
		$pdf->Ln(5);
		$pdf->Cell(40,4,"".$fecha_firma."",0,0,'R');
		$pdf->Cell(55,4,$n_cheque,0,0,'R');
		$pdf->Ln(12);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(20,4,"He recibido de la Ofgicina Coordinadora de Hidrografia OCHINA la cantidad de:",0,0,'L');
		$pdf->Ln();
	    $pdf->MultiCell(180,4,$monto_escrito,0,'LBR','L');
/*
	    $pdf->Cell(80,4,substr($monto_escrito,0,65),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,65,50),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(80,4,substr($monto_escrito,115,50),0,0,'L');
		
*/		//$pdf->Cell(100,4,$monto_escrito,0,0,'L');
		$pdf->Ln();
		$pdf->SetFont('arial','B',9);
		$pdf->Cell(160,4,"(Bs. ".number_format($row->fields("monto_cheque"),2,',','.')."  )por concepto de:",0,0,'R');
		$pdf->Ln(4);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$act=0;
		//5 COMENT 310
		if((strlen($row->fields("concepto"))>248))
		{
		//&&(strlen($row->fields("concepto"))<310)
		$ln=6;
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
		$ln=20;
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
		$pdf->MultiCell(180,4,$row->fields("concepto").$con,0,'LBR','L');
		if($act==1)$pdf->Ln($ln);
		/*if((strlen($row->fields("concepto"))>194)&&(strlen($row->fields("concepto"))<300))
		{
		$ln=2;
		$act=1;
		}elseif((strlen($row->fields("concepto"))>97)&&(strlen($row->fields("concepto"))<194))
		{
		$ln=3;$act=1;
		}elseif(strlen($row->fields("concepto"))<=97){
		$ln=8;$act=1;}
		$con=strlen($row->fields("concepto"));
		$pdf->MultiCell(140,4,$row->fields("concepto"),0,'LBR','L');
		if($act==1)$pdf->Ln($ln);*/
	/*	$pdf->Ln(20);
		$pdf->Cell(140,4,substr($row->fields("concepto"),0,90),0,0,'L');
		$pdf->Ln();
		$pdf->Cell(140,4,substr($row->fields("concepto"),90),0,0,'L');*/
		$pdf->Ln(8);
		$pdf->Cell(24,4,"Banco Girado",0,0,'C');
		$pdf->Cell(80,4,substr($bancos[0],0,80),0,0,'L');
		$pdf->Cell(20,4,"Cta.Cte Nº ",0,0,'C');
    	$pdf->Cell(40,4,substr($row->fields("cuenta_banco"),0,80),0,0,'R');
		$pdf->Ln(18);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(60,4,"  DIRECTOR DE ADMIN Y FINANZAS:",0,0,'C');
		$pdf->Cell(65,4,"  DIRECTOR OCHINA:",0,0,'C');
		$pdf->Cell(65,4,"  RECIBI CONFORME:",0,0,'C');
	
		$pdf->Ln(4);
		$pdf->SetFont('arial','B',6);
		$pdf->Cell(50,4,$nombre_jefe,0,0,'C');
    	$pdf->Cell(95,4,$nombre_director,0,0,'C');
		$pdf->Ln(14);
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(60,4,"  PREPARADO POR:",0,0,'C');
		$pdf->Cell(65,4,"  ADMINISTRADOR OCHINA:",0,0,'C');
		$pdf->Cell(50,4,"  CI:",0,0,'C');
		
		$pdf->Ln(5);
		$pdf->SetFont('arial','B',6);
		$pdf->Cell(40,4,$nombre_preparado,0,0,'C');
		$pdf->Cell(100,4,$nombre_administrador,0,0,'C');
		$pdf->Ln(20);
		$pdf->Cell(10,4,"      PRESUPUESTO   ",0,0,'L');
		$pdf->Cell(97,4,"      UNIDAD EJECUTORA   ",0,0,'R');
		$pdf->Cell(30,4,"      CARGO   ",0,0,'R');
		$pdf->Cell(45,4,"      OBSERVACIONES   ",0,0,'R');
		$pdf->Ln();
		$pdf->Cell(15,4,"      PROGRAMA   ",0,0,'C');
		$pdf->Cell(25,4,"      PARTIDA   ",0,0,'C');
		$pdf->Cell(20,4,"      SUBPARTIDA   ",0,0,'R');
		$pdf->Ln();
		$pdf->SetFont('arial','B',5);
		$pdf->Cell(60,4,"      GENERICO ESPECIFICO   ",0,0,'R');
		$pdf->SetFont('arial','B',8);
		$pdf->Cell(40,4,"      Tesoreria   ",0,0,'R');
		$pdf->Cell(33,4,number_format($row->fields("monto_cheque"),2,',','.'),0,0,'R');
//$pdf->Ln();
		$pdf->Cell(185,4,"      PRESUPUESTO   ".date("Y"),0,0,'C');
		
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
		$pdf->Cell(40,		6,"No se encuentran los datos".$fecha_firma,			0,0,'L',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

	$pdf->Output();
}

?>
