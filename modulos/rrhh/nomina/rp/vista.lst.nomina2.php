<?php
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD					
session_start();

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
//selecionando la tabla monedas
$id_unidad = '';
$fecha_desde = '';
$fecha_hasta = '';
$id_trabajador = '';
$id_nominas = '';
$numero = $_GET['numero'];
if($_GET['fecha_desde']!='')
	$fecha_desde = $_GET['fecha_desde'];
if($_GET['fecha_hasta']!='')
	$fecha_hasta = $_GET['fecha_hasta'];
$where = " WHERE 1 = 1 ";
if($_GET['id_tipo_nomina']!='')
	$id_tipo_nomina = $_GET['id_tipo_nomina'];
//if($_GET['id_trabajador']!='')
	//$id_trabajador = $_GET['id_trabajador'];
if($_GET['id_nominas']!='')
	$id_nominas = $_GET['id_nominas'];
if($id_tipo_nomina!='')
	$where.= " AND trabajador.id_tipo_nomina = $id_tipo_nomina ";
//if($id_trabajador!='')
	//$where.= " AND trabajador.id_trabajador = $id_trabajador ";
//if($fecha_desde!='' && $fecha_hasta!='')
	//$where.= " AND bienes.fecha_compra BETWEEN '$fecha_desde' AND '$fecha_hasta' ";*/
$Sql="SELECT 
				trabajador.id_trabajador,
				persona.cedula,
				persona.nombre,
				persona.apellido,
				unidad_ejecutora.nombre as unidad,
				cargos.descripcion,
				aumento_sueldo.sueldo_aumento,
				trabajador.fecha_ingreso,
				tipo_nomina.nombre as tipo_nomina,
				anos_servicios
			FROM 
				persona
			INNER JOIN
				trabajador
			ON
				persona.id_persona = trabajador.id_persona
			INNER JOIN
				unidad_ejecutora
			ON
				trabajador.id_unidad=unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
				cargos
			ON	
				trabajador.id_cargo = cargos.id_cargos
			INNER JOIN	
				aumento_sueldo
			ON
				trabajador.id_trabajador = aumento_sueldo.id_trabajador
			INNEr JOIN
				tipo_nomina
			ON
				trabajador.id_tipo_nomina = tipo_nomina.id_tipo_nomina
			".$where."
			AND
				persona.id_organismo = $_SESSION[id_organismo]
		";
$row=& $conn->Execute($Sql);
//
			
			//
$sql_exi = "SELECT 
				count(id_trabajador) as exi
			FROM 
				trabajador
			INNER JOIN
				persona
			ON
				trabajador.id_persona = persona.id_persona
			INNER JOIN
				unidad_ejecutora
			ON
				trabajador.id_unidad = unidad_ejecutora.id_unidad_ejecutora
			INNER JOIN
				cargos
			ON
				trabajador.id_cargo = cargos.id_cargos
			INNER JOIN
				tipo_nomina
			ON
				trabajador.id_tipo_nomina = tipo_nomina.id_tipo_nomina
			".$where."
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
			";
$row_exi =& $conn->Execute($sql_exi);
$exi = $row_exi->fields('exi');
//************************************************************************
//if (!$row->EOF)
//{ 
	require('../../../../utilidades/fpdf153/fpdf.php');
	//************************************************************************
	class PDF extends PDF_Code128
	{
		//Cabecera de página
		function Header()
		{	
			global $exi;
			global $fecha_desde;
			global $fecha_hasta;
			global $numero;
			//$this->Image("../../../../imagenes/logos/logo_mpppd_339x397.jpg",10,10,25);
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Times','B',10);
			$this->Cell(0,5,utf8_decode('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'MINISTERIO DEL PODER POPULAR PARA LA DEFENSA',0,0,'C');
			$this->Ln();
			$this->Cell(0,5,'VICEMINSITRO DE SERVICIOS',0,0,'C');
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('DIRECCIÓN GENERAL DE EMPRESAS Y SERVICIOS'),0,0,'C');			
			$this->Ln();			
			$this->Cell(0,5,utf8_decode('OFICINA COORDINADORA DE HIDROGRAFÍA Y NAVEGACIÓN'),0,0,'C');	
			$this->Ln(10);
			if($exi!=0){
			$this->SetFont('Arial','B',16);
			$this->Cell(0,10,'RECIBO DE PAGO',0,1,'C');
			$this->SetFont('Arial','B',10);
			//$this->Cell(24,10,'JEFE DIV. DE ',0,0,'L');
			//$this->Cell(24,10,strtoupper($_SESSION['nom_uni']),0,1,'L');
			//$this->Cell(24,10,'SITIO FISICO ',0,0,'L');
			//$this->Cell(24,10,strtoupper($_SESSION['nom_sit']),0,0,'L');
			//$x = $this->GetX();
			//$y = $this->GetY();
			if($fecha_desde!='' && $fecha_hasta!=''){
				//$this->SetXY($x+130,$y);
				$this->Cell(60,10,utf8_decode("Nº NOMINA: ").$numero,0,0,'C');
				$this->Cell(60,10,"DESDE: ".$fecha_desde,0,0,'C');
				$this->Cell(60,10,"HASTA: ".$fecha_hasta,0,0,'C');
				$this->Ln();
			
			}
			//
			
			
			
			//
			}
		}
		//Pie de página
		function Footer()
		{
			//Posición: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Número de página
			$this->Cell(25,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(80,3,'Impreso por:',0,0,'R');
			$this->Cell(83,3,date("d/m/Y h:m:s"),0,0,'R');					
			$this->Ln();
			$this->SetFillColor(0);		
			$this->Code128(87,286,strtoupper($_SESSION['usuario']),40,6);
			//$this->SetFont('barcode','',6);
			//$this->Cell(0,3,strtoupper("$_SESSION[login]".date("Ymdhms")."-".$_SERVER['REMOTE_ADDR']),0,0,'C');
		}
	}
	//************************************************************************
	$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage();
	$pdf->SetFont('arial','',7);
	$pdf->SetFillColor(255);
	while (!$row->EOF) 
	{
		//
		//
		$df = date("d");
		$mf = date("m");
		$af = date("Y");
		$di = substr($row->fields("fecha_ingreso"),8,2);
		$mi = substr($row->fields("fecha_ingreso"),5,2);
		$ai = substr($row->fields("fecha_ingreso"),0,4);
		$fecha_ini = mktime(0,0,0,$mi,$di,$ai);
		$fecha_fin = mktime(0,0,0,$mf,$df,$af);
		$total_fechas = $fecha_fin - $fecha_ini;
		$total_fechas = $total_fechas / 31536000;
		//
		//
		$total_asignacion = 0;
		$total_deduccion = 0;
		$ultimo_sueldo = 0;
		$suel = $row->fields("sueldo_aumento");
		$suel = str_replace('{','',$suel);
		$suel = str_replace('}','',$suel);
		$sueldo = split(',',$suel);
		for($i=0; $i<=19; $i++){
			if($sueldo[$i]!=0)
				$ultimo_sueldo = $sueldo[$i];  
		}
		$frecuencia = 1;
		if(strtoupper($_GET['frecuencia'])=='SEMANAL')
			$frecuencia = 4;
		if(strtoupper($_GET['frecuencia'])=='QUINCENAL')
			$frecuencia = 2;
		$ultimo_sueldo = $ultimo_sueldo / $frecuencia;
		if($ultimo_sueldo!=0)
			$total_asignacion = $ultimo_sueldo;	
		
		$sql_nomina = "SELECT 
							nomina.asignacion_deduccion,
							nomina.monto_concepto,
							conceptos.id_concepto,
							conceptos.descripcion,
							conceptos.estatus
						FROM
							nomina
						INNER JOIN
							conceptos
						ON
							nomina.id_concepto = conceptos.id_concepto
						WHERE
							nomina.id_tipo_nomina = $id_tipo_nomina
						AND
							nomina.id_trabajador = ".$row->fields("id_trabajador")."		
						AND
							nomina.id_nominas = $id_nominas
						AND
							nomina.id_organismo = $_SESSION[id_organismo]
						ORDER BY conceptos.asignacion_deduccion, conceptos.num_orden	
						";
						
						$row_nomina =& $conn->Execute($sql_nomina);
		$mostrar = $row_nomina->fields("id_concepto"); 				
		if($mostrar!=''){				
		$pdf->Ln(6);
		$pdf->SetFont('Times','B',8);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFillColor(175) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(20,	6,			utf8_decode('CÉDULA'),			0,0,'C',1);
		$pdf->Cell(35,	6,			'NOMBRE',			0,0,'C',1);
		$pdf->Cell(35,	6,			'APELLIDO',			0,0,'C',1);
		$pdf->Cell(42,	6,			'CARGO',			0,0,'C',1);
		$pdf->Cell(42,	6,			'FECHA INGRESO',			0,1,'C',1);
		//$pdf->Ln(6);
		$pdf->SetFillColor(255) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(20,		6,			$row->fields("cedula"),		1,0,'C',1);
		$pdf->Cell(35,		6,			utf8_decode($row->fields("nombre")),		1,0,'C',1);
		$pdf->Cell(35,		6,			utf8_decode($row->fields("apellido")),		1,0,'C',1);
		$pdf->Cell(42,		6,			utf8_decode($row->fields("descripcion")),		1,0,'C',1);
		$pos = strpos($total_fechas,'.');
		if(pos != '')
			$total_fechas = substr($total_fechas,0,$pos);
		$pdf->Cell(42,		6,			substr($row->fields("fecha_ingreso"),8,2)."-".substr($row->fields("fecha_ingreso"),5,2)."-".substr($row->fields("fecha_ingreso"),0,4),		1,1,'C',1);
		$pdf->SetFillColor(175) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(65,	6,			'UNIDAD',			0,0,'C',1);
		$pdf->Cell(50,	6,			utf8_decode('TIPO DE NÓMINA'),			0,0,'C',1);
		$pdf->Cell(29,	6,			'SUELDO BASE',			0,0,'C',1);
		$pdf->Cell(30,	6,			utf8_decode('AÑOS DE SERVICIO'),			0,1,'C',1);
		$pdf->SetFillColor(255) ;
		$pdf->SetTextColor(0);
		$pdf->Cell(65,	6,			utf8_decode($row->fields("unidad")),			1,0,'C',1);
		$pdf->Cell(50,	6,			utf8_decode($row->fields("tipo_nomina")),			1,0,'C',1);
		$sueldo_base = $ultimo_sueldo * $frecuencia;
		$pos = strpos($sueldo_base,'.');
		if($pos=='')
			$sueldo_base.=',00';
		if($pos!=''){
			$tam = strlen($sueldo_base);
			$tam = $tam - $pos;
			if($tam <= 2)
				$sueldo_base.='0';
		}	
		$pdf->Cell(29,	6,			$sueldo_base,			1,0,'C',1);
		$pdf->Cell(30,	6,			$row->fields("anos_servicios") + $total_fechas,			1,1,'C',1);
		$y1=$pdf->Gety();
		//$pdf->SetLineWidth(0.3);
		//
		 
						
						if($row_nomina->fields("descripcion")!=''){
							$pdf->SetFillColor(175) ;
							$pdf->SetTextColor(0);
							$pdf->Cell(60,		6,			"CONCEPTOS ",		0,0,'L',1);
							$pdf->Cell(45,		6,			utf8_decode("ASIGNACIÓN "),		0,0,'L',1);
							$pdf->Cell(45,		6,			utf8_decode("DEDUCCIÓN "),		0,0,'L',1);
							$pdf->Cell(24,		6,			"SALDO ",		0,0,'L',1);
							$pdf->Ln();
							$pdf->SetFillColor(255);
							$pdf->SetTextColor(0);
							//$pdf->Ln(6);
						}
						
						//
						//
						//
						$neto = $ultimo_sueldo;
						$pos = strpos($ultimo_sueldo,'.');
						if($pos=='')
							$ultimo_sueldo.=",00";
						if($pos!=''){
							$ultimo_sueldo = str_replace('.',',',$ultimo_sueldo);
							$tam = strlen($ultimo_sueldo);
							$tam = $tam - $pos;
							if($tam <= 2)
								$ultimo_sueldo.="0";
						}
						if(c==0){	
							$pdf->Cell(54,		6,			'SUELDO',		0,0,'L',1);
							$pdf->SetFont('Times','B',12);
							$pdf->Cell(40,		6,			$ultimo_sueldo,		0,0,'R',1);
							$pdf->Cell(40,		6,			'',		0,1,'R',1);
							//$pdf->Ln();
						}
						$c++;
						
						while(!$row_nomina->EOF){
							//
							//
							$sql_pres = "SELECT
							id_prestamo,
							monto,
							saldo,
							cuota
						FROM
							prestamo
						WHERE
							id_trabajador = ".$row->fields("id_trabajador")."
						AND
							id_concepto = ".$row_nomina->fields('id_concepto')."
						AND
							saldo > 1
						AND
							id_organismo = $_SESSION[id_organismo] ";
			$row_pres =& $conn->Execute($sql_pres);	
			$monto = $row_nomina->fields("monto_concepto");
			//
			//

						
				$pdf->SetFont('Times','B',8);
				//
				//
				$pasa = 0;
				$val = 1;
				if($frecuencia==2 && $row_nomina->fields("estatus")==2 && $_GET['numero']%2!=0)
					$pasa = 0;
				if($frecuencia==2 && $row_nomina->fields("estatus")==2 && $_GET['numero']%2==0){	
					$pasa = 1;
					$val = 2;
				}
				if($frecuencia==2 && $row_nomina->fields("estatus")==1)
					$pasa = 1;
				if($frecuencia==1)	
					$pasa = 1;
				//
				//
				if($pasa==1){
				$pdf->Cell(54,		6,			strtoupper(utf8_decode($row_nomina->fields("descripcion"))),		0,0,'L',1);
				$pdf->SetFont('Times','B',12);
				if($row_nomina->fields("asignacion_deduccion")=='Asignacion'){
				$monto=$monto*$val;	
				$total_asignacion = $total_asignacion + $monto;
				$neto = $neto + $monto;
				$pos = strpos($monto,'.');
				if($pos=='')
					$monto.=".00";
				if($pos!=''){
					$monto = str_replace('.',',',$monto);
					$tam = strlen($monto);
					$tam = $tam - $pos;
					if($tam <= 2)
						$monto.="0";
				}
				$pdf->Cell(40,		6,			$monto,		0,0,'R',1);
				
				}
				else{
					$pdf->Cell(40,		6,			'',		0,0,'l',1);
				}
				if($row_nomina->fields("asignacion_deduccion")=='Deduccion'){
				$monto=$monto*$val;
				$neto = $neto - $monto;
				$total_deduccion = $total_deduccion + $monto;
				$pos = strpos($monto,'.');
				if($pos=='')
					$monto.=".00";
				if($pos!=''){
					$monto = str_replace('.',',',$monto);
					$tam = strlen($monto);
					$tam = $tam - $pos;
					if($tam==2)
						$monto.="0";
				}
				$pdf->Cell(40,		6,			$monto,		0,0,'R',1);
				
				}
				else{
					$pdf->Cell(40,		6,			'',		0,0,'L',1);
				}
				if($row_pres->fields("id_prestamo")!=''){
					$prestamo = $row_pres->fields("saldo") - $row_nomina->fields("monto_concepto");
					$pos = strpos($prestamo,'.');
					if($pos=='')
						$prestamo.=".00"; 
					if($pos!=''){
						$prestamo = str_replace('.',',',$prestamo);
						$tam = strlen($prestamo);
						$tam = $tam - $pos;
						if($tam==2)
							$prestamo.="0";
					}	
					$pdf->Cell(40,		6,			$prestamo,		0,0,'R',1);
				}
				$pdf->Ln();
				}
			$row_nomina->MoveNext();
			
		}
						//
						//
						//
						
		}
		$row->MoveNext();
		//
		//
		//
		if($row_nomina->EOF && $mostrar!=''){
			
			$pdf->SetFillColor(175);
			$pdf->SetTextColor(0);
			//
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(54,		6,			'TOTAL ASIG/DED',		0,0,'C',1);
			
			$pdf->SetFillColor(255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('Times','B',12);
			$pos = strpos($total_asignacion,'.');
					if($pos=='')
						$total_asignacion.=".00"; 
					if($pos!=''){
						$total_asignacion = str_replace('.',',',$total_asignacion);
						$tam = strlen($total_asignacion);
						$tam = $tam - $pos;
						if($tam <= 2)
							$total_asignacion.="0";
					}	
			$pdf->Cell(40,		6,			$total_asignacion,		1,0,'R',1);
			//$pdf->Cell(40,		6,			'',		1,0,'C',1);
			//$pdf->SetFillColor(100);
			//$pdf->SetTextColor(255);
			//$pdf->Cell(40,		6,			'TOTAL DEDUCCION',		1,0,'C',1,1);
			//$pdf->SetFillColor(255);
			//$pdf->SetTextColor(0);
			//$pdf->Cell(40,		6,			'',		1,0,'C',1);
			$pos = strpos($total_deduccion,'.');
					if($pos=='')
						$total_deduccion.=".00"; 
					if($pos!=''){
						$total_deduccion = str_replace('.',',',$total_deduccion);
						$tam = strlen($total_deduccion);
						$tam = $tam - $pos;
						if($tam <= 2)
							$total_deduccion.="0";
					}	
			$pdf->Cell(40,		6,			$total_deduccion,		1,1,'R',1);
			//
			$pdf->SetFillColor(175);
			$pdf->SetTextColor(0);
			$pdf->SetFont('Times','B',8);
			$pdf->Cell(134,		6,			'NETO A COBRAR',		0,0,'C',1);
			$pdf->SetFillColor(255) ;
			$pdf->SetTextColor(0);
			$y2=$pdf->Gety();
			$pdf->SetFont('Times','B',12);
			//$neto = $total_asignacion-$total_deduccion;
			$pos = strpos($neto,'.');
					if($pos=='')
						$neto.=".00"; 
					if($pos!=''){
						$neto = str_replace('.',',',$neto);
						$tam = strlen($neto);
						$tam = $tam - $pos;
						if($tam <= 2)
							$neto.="0";
					}	
			$pdf->Cell(40,		6,			$neto,		1,1,'R',1);
		
		
		$pdf->Line(184,$y1+6,184,$y2);
		$pdf->Line(144,$y1+6,144,$y2);
		$pdf->Line(144,$y2,184,$y2);
		//
		$pdf->Line(104,$y1+6,104,$y2);
		$pdf->Line(64,$y1+6,64,$y2);
		$pdf->Line(10,$y1+6,10,$y2-6);
		//
		$pdf->Line(10,$y1-12,184,$y1-12);
		$pdf->Line(10,$y1,184,$y1);
		$pdf->Line(10,$y1+6,184,$y1+6);
		}
		$pdf->Line(64,$y2,184,$y2);
		$pdf->Line(10,$y2-6,64,$y2-6);
		$pdf->Ln();
		$pdf->SetFont('Times','B',8);
		if($mostrar!='')
		$pdf->Cell(174,		6,			"* VALIDO PARA ASISTENCIA MEDICA DEL SEGURO SOCIAL OBLIGATORIO *",		0,0,'C',1);
		//
		//
		//
		if($mostrar!=''){
		if(!$row->EOF){
		$pdf->AddPage();
		}
		}
		
	}
	
	if($exi==0){
		$pdf->Ln(90);
		$pdf->SetFont('Arial','B',16);
		$pdf->Line(10,90,200,90);
		$pdf->Cell(175,	10,		"NO SE ENCONTRARON DATOS",						0,0,'C',1);
		$pdf->Image("../../../../imagenes/iconos/anulados.jpg",142,105,50);
		$pdf->Line(10,180,200,180);
	}
	$pdf->Output();
//}
?>