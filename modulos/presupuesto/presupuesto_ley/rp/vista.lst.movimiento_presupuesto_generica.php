<?php
session_start();

//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
$anio = $_GET['ano'];
$unidad = $_GET['unidad'];
$accion = $_GET['id_accion'];
$proyecto = $_GET['id_proyecto'];
$accion_es = $_GET['id_accion_es'];
$partida = $_GET['partida'];
$generica = $_GET['generica'];
$desde = $_GET['desde'];
$hasta = $_GET['hasta'];

//************************************************************************
//************************************************************************
//************************************************************************
if (($desde == 01) || ($desde ==1))
	$desde_mes ='Enero';
if (($desde == 02) || ($desde ==2))
	$desde_mes ='Febrero';
if (($desde == 03) || ($desde ==3))
	$desde_mes ='Marzo';
if (($desde == 04) || ($desde ==4))
	$desde_mes ='Abril';
if (($desde == 05) || ($desde ==5))
	$desde_mes ='Mayo';
if (($desde == 06) || ($desde ==6))
	$desde_mes ='Junio';
if (($desde == 07) || ($desde ==7))
	$desde_mes ='Julio';
if (($desde == 08) || ($desde ==8))
	$desde_mes ='Agosto';
if (($desde == 09) || ($desde ==9))
	$desde_mes =' Septiembre';
if (($desde == 10) || ($desde ==10))
	$desde_mes ='Octubre';
if (($desde == 11) || ($desde ==11))
	$desde_mes ='Noviembre';
if (($desde == 12) || ($desde ==12))
	$desde_mes ='Diciembre';
if (($desde == 01) || ($desde ==1))
//************************************************************************
if (($hasta == 01) || ($hasta ==1))
	$hasta_mes ='Enero';
if (($hasta == 02) || ($hasta ==2))
	$hasta_mes ='Febrero';
if (($hasta == 03) || ($hasta ==3))
	$hasta_mes ='Marzo';
if (($hasta == 04) || ($hasta ==4))
	$hasta ='Abril';
if (($hasta == 05) || ($hasta ==5))
	$hasta_mes ='Mayo';
if (($hasta == 06) || ($hasta ==6))
	$hasta_mes ='Junio';
if (($hasta == 07) || ($hasta ==7))
	$hasta_mes ='Julio';
if (($hasta == 08) || ($hasta ==8))
	$hasta_mes ='Agosto';
if (($hasta == 09) || ($hasta ==9))
	$hasta_mes =' Septiembre';
if (($hasta == 10) || ($hasta ==10))
	$hasta_mes ='Octubre';
if (($hasta == 11) || ($hasta ==11))
	$hasta_mes ='Noviembre';
if (($hasta == 12) || ($hasta ==12))
	$hasta_mes ='Diciembre';	
//die $ano ; 
if($hasta_mes == $desde_mes)
	$texto='Mes de '.$hasta_mes;
else
	$texto='Desde '. $desde_mes.' Hasta '.$hasta_mes;
$ii = 0;
while($desde<=$hasta){
	if ($ii == 0){
		$monoto = "monto_presupuesto [".$desde."]";
		$traspasado = "monto_traspasado [".$desde."]";
		$modificado = "monto_modificado [".$desde."]";
	}else{
		$monoto = $monoto .' + monto_presupuesto ['.$desde.']';
		$traspasado = $traspasado.' + monto_traspasado ['.$desde.']';
		$modificado = $modificado.' + monto_modificado ['.$desde.']';
	}
$ii++;
$desde++;

}
//************************************************************************

$sql = "
SELECT 
	\"presupuesto_ejecutadoR\".id_presupuesto_ejecutador, 
	\"presupuesto_ejecutadoR\".id_unidad_ejecutora, unidad_ejecutora.codigo_unidad_ejecutora, unidad_ejecutora.nombre AS unidad_ejecutora,
	\"presupuesto_ejecutadoR\".id_proyecto, proyecto.codigo_proyecto , proyecto.nombre,
	\"presupuesto_ejecutadoR\".id_accion_centralizada, accion_centralizada.codigo_accion_central , accion_centralizada.denominacion , 
	\"presupuesto_ejecutadoR\".id_accion_especifica, accion_especifica.codigo_accion_especifica , accion_especifica.denominacion AS accion_especifica,
	\"presupuesto_ejecutadoR\".ano, 
	\"presupuesto_ejecutadoR\".partida, 
	\"presupuesto_ejecutadoR\".generica, 
	\"presupuesto_ejecutadoR\".especifica, 
	\"presupuesto_ejecutadoR\".sub_especifica, 
	($monoto)AS monto_presupuesto, 
	($traspasado)AS monto_traspasado,
	($modificado)AS monto_modificado
FROM 
	\"presupuesto_ejecutadoR\"
INNER JOIN
	unidad_ejecutora
ON
	unidad_ejecutora.id_unidad_ejecutora = \"presupuesto_ejecutadoR\".id_unidad_ejecutora
INNER JOIN
	accion_especifica
ON
	accion_especifica.id_accion_especifica = \"presupuesto_ejecutadoR\".id_accion_especifica
LEFT JOIN
	accion_centralizada
ON
	accion_centralizada.id_accion_central = \"presupuesto_ejecutadoR\".id_accion_centralizada
LEFT JOIN
	proyecto
ON
	proyecto.id_proyecto = \"presupuesto_ejecutadoR\".id_proyecto
WHERE
	\"presupuesto_ejecutadoR\".ano = '$anio'
AND
	\"presupuesto_ejecutadoR\".id_unidad_ejecutora = $unidad
AND
	\"presupuesto_ejecutadoR\".id_accion_especifica = $accion_es
AND
	\"presupuesto_ejecutadoR\".partida = '$partida'
AND
	\"presupuesto_ejecutadoR\".generica = '$generica'	
";
//die($sql); $generica
$row=& $conn->Execute($sql);
//************************************************************************
if (!$row->EOF)
{
$nombre_unidad = $row->fields("codigo_unidad_ejecutora")." ".$row->fields("unidad_ejecutora");
//*************************************************************************************************************
	class PDF extends FPDF
	{
		//Cabecera de p�gina
		function Header()
		{	
		global   $anio,$partida,$generica, $texto, $nombre_unidad ;	
			$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
						
			$this->SetFont('Arial','B',9);
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
			/*$this->Ln();			
			$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
			$this->Ln();	
			$this->SetFont('Arial','B',14);
			$this->Cell(0,10,'RESUMEN DE PRESUPUESTO DE LEY POR PARTIDA Y GENERICA',0,0,'C');
			$this->Ln(7);	
			$this->SetFont('Arial','B',8);
			$this->Cell(0,5,$texto,0,0,'C');
			$this->Ln(5);	
			$this->SetFont('Arial','B',12);
			$this->Cell(50,10,'A�o: '.$anio,0,0,'L');
			$this->SetFont('Arial','B',12);
			$this->Cell(215,10, $nombre_unidad .'.  PARTIDA '.$partida.'.'.$generica ,0,0,'R');	
			$this->Ln(8);
			$this->SetFont('Arial','B',9);
			$this->SetLineWidth(0.3);
			$this->SetFillColor(120) ;
			$this->SetTextColor(0);
			//$this->Cell(50,				10,		'UNIDAD EJECUTORA',					'B',0,'C',1);
			$this->Cell(60,				10,		'PROYECTO / ACCION CENTRALIZADA ','B',0,'C',1);
			$this->Cell(80,				10,		'ACCION ESPECIFICA ',				'B',0,'C',1);
			$this->Cell(28,				10,		'PARTIDA',	'B',0,'C',1);
			$this->Cell(28,				10,		'APROBADO',	'B',0,'C',1);
			$this->Cell(28,				10,		'MODIFICADO',	'B',0,'C',1);
			$this->Cell(28,				10,		'TRASPASADO',	'B',0,'C',1);
			$this->Cell(28,				10,		'TOTAL',	'B',0,'C',1);
			
			$this->Ln(10);
		}
		//Pie de p�gina
		function Footer()
		{
			//Posici�n: a 2,5 cm del final
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//N�mero de p�gina
			$this->Cell(100,3,'Pagina '.$this->PageNo().'/{nb}',0,0,'L');
			$this->Cell(85,3,'Impreso por:  '.$_SESSION[apellido].' '.$_SESSION[nombre],0,0,'C');
			$this->Cell(75,3,date("d/m/Y h:m:s"),0,0,'R');					
		}
	}
	
//*************************************************************************************************************
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddFont('barcode');
		$pdf->AddPage('L');
		$pdf->SetFont('arial','',9);
		$pdf->SetFillColor(255);
		$total=0;
		$monto_presupuesto_t = 0;
		$monto_modificado_t = 0;
		$monto_traspasado_t = 0;
		$total_t = 0;
	while (!$row->EOF)
	{
	$total = $row->fields("monto_presupuesto")+$row->fields("monto_traspasado")+$row->fields("monto_modificado");
		//$pdf->Cell(50,	5,	$row->fields("codigo_unidad_ejecutora")." ".$row->fields("unidad_ejecutora"),								'RLB',0,'L',1);
		if($row->fields("id_proyecto") !=0)
			$pdf->Cell(60,	5,	$row->fields("codigo_proyecto").' '.substr($row->fields("nombre"),0,60),								'LRB',0,'L',1);
		else
			$pdf->Cell(60,	5,	$row->fields("codigo_accion_central").' '.substr($row->fields("denominacion"),0,60),					'LRB',0,'L',1);
		
		$pdf->Cell(80,	5,	$row->fields("codigo_accion_especifica").' '.substr($row->fields("accion_especifica"),0,60),					'LRB',0,'L',1);	
		$pdf->Cell(28,	5,	$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("sub_especifica"),		'RLB',0,'C',1);
		$pdf->Cell(28,	5,	number_format($row->fields("monto_presupuesto"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(28,	5,	number_format($row->fields("monto_modificado"),2,',','.'),																			'LRB',0,'R',1);
		$pdf->Cell(28,	5,	number_format($row->fields("monto_traspasado"),2,',','.'),																			'LB',0,'R',1);
		$pdf->Cell(28,	5,	number_format($total,2,',','.'),		'LRB',0,'R',1);
		$pdf->Ln(5);
		
	$monto_presupuesto_t = $monto_presupuesto_t + $row->fields("monto_presupuesto");
	$monto_modificado_t = $monto_modificado_t + $row->fields("monto_modificado");
	$monto_traspasado_t = $monto_traspasado_t + $row->fields("monto_traspasado");
	$total_t = $total_t + $total;
	
	$row->MoveNext();
	}
	$pdf->SetFont('Arial','B',9);
		$pdf->Cell(168,	5,	Total,			1,0,'R',1);
		$pdf->Cell(28,	5,	number_format($monto_presupuesto_t,2,',','.'),		1,0,'R',1);
		$pdf->Cell(28,	5,	number_format($monto_modificado_t,2,',','.'),		1,0,'R',1);
		$pdf->Cell(28,	5,	number_format($monto_traspasado_t,2,',','.'),		1,0,'R',1);
		$pdf->Cell(28,	5,	number_format($total_t,2,',','.'),		1,0,'R',1);
}else{
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
		$pdf->Cell(190,		6,'No se encontraron datos',			0,0,'C',1);
		$pdf->Ln(16);
		$pdf->Cell(190,		6,'',			'B',0,'C',1);

}
$pdf->Output();
?>