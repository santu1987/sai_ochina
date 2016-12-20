<?php
if (!$_SESSION) session_start();
//************************************************************************
//									INCLUYENDO LIBRERIAS Y CONEXION A BD	
require('../../../../utilidades/fpdf153/fpdf.php');
require('../../../../utilidades/pdf_js.php');
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');

$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//$nombre_elabora = $_SESSION[nombre]/*.' '.$_SESSION[apellido]*/;
$precopromiso= $_GET['precopromiso'];
//$unidad = $_GET['unidad'];
//************************************************************************
$Sql="
SELECT 
	id_traspaso_entre_partida,
	(
	SELECT
		codigo_unidad_ejecutora
	FROM
		unidad_ejecutora
	WHERE
		unidad_ejecutora.id_unidad_ejecutora = traspaso_entre_partidas.id_unidad_cedente
	) AS codigo_unidad_cedente  ,
	 (
	SELECT
		codigo_proyecto
	FROM
		proyecto
	WHERE
		proyecto.id_proyecto = traspaso_entre_partidas.id_proyecto_cedente
	) AS codigo_proyecto_cedente  ,
	(
	SELECT
		codigo_accion_central
	FROM
		accion_centralizada
	WHERE
		accion_centralizada.id_accion_central = traspaso_entre_partidas.id_accion_centralizada_cedente
	) AS codigo_accion_centralizada_cedente  ,
	(
	SELECT
		codigo_accion_especifica
	FROM
		accion_especifica
	WHERE
		accion_especifica.id_accion_especifica = traspaso_entre_partidas.id_accion_especifica_cedente
	) AS codigo_accion_especifica_cedente ,
	
	partida_cedente, generica_cedente, especifica_cedente, subespecifica_cedente,
	(
	SELECT
		codigo_unidad_ejecutora
	FROM
		unidad_ejecutora
	WHERE
		unidad_ejecutora.id_unidad_ejecutora = traspaso_entre_partidas.id_unidad_receptora
	) AS codigo_unidad_receptora ,
	 (
	SELECT
		codigo_proyecto
	FROM
		proyecto
	WHERE
		proyecto.id_proyecto = traspaso_entre_partidas.id_proyecto_receptora
	) AS codigo_proyecto_receptora  ,
	(
	SELECT
		codigo_accion_central
	FROM
		accion_centralizada
	WHERE
		accion_centralizada.id_accion_central = traspaso_entre_partidas.id_accion_centralizada_receptora
	) AS codigo_accion_centralizada_receptora ,
	(
	SELECT
		codigo_accion_especifica
	FROM
		accion_especifica
	WHERE
		accion_especifica.id_accion_especifica = traspaso_entre_partidas.id_accion_especifica_receptora
	) AS codigo_accion_especifica_receptora ,
	partida_receptora, generica_receptora, especifica_receptora, subespecifica_receptora,
	secuencia,
	mes_cedente,  
	monto_cedente,
	mes_receptora,  
	monto_receptora, 
	(
	SELECT 
		nombre || ' ' || apellido
	FROM 
		usuario
	where
		id_usuario = traspaso_entre_partidas.ultimo_usuario
	)
	 AS usuario, 
	
	fecha_traspaso, 
	comentario,
	pre_compromiso
FROM 
	traspaso_entre_partidas
WHERE
	pre_compromiso= '".$precopromiso."'

";
//die ($Sql);
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$id_traspaso =  $row->fields('id_traspaso_entre_partida');
	$codigo_unidad =  $row->fields('codigo_unidad_cedente');
	$codigo_proyecto=  $row->fields('codigo_proyecto_receptora');
	$codigo_accion_centralizada=  $row->fields('codigo_accion_centralizada_cedente');
	$accion_espe_cede =  $row->fields('codigo_accion_especifica_cedente');
	$partida_cede =  $row->fields('partida_cedente').'.'.$row->fields('generica_cedente').'.'.$row->fields('especifica_cedente').'.'.$row->fields('subespecifica_cedente');
	$mes_espe_cede =  $row->fields('mes_cedente');
	
	$accion_espe_rece =  $row->fields('codigo_accion_especifica_receptora');
	$partida_rece =  $row->fields('partida_receptora').'.'.$row->fields('generica_receptora').'.'.$row->fields('especifica_receptora').'.'.$row->fields('subespecifica_receptora');
	$mes_espe_rece =  $row->fields('mes_receptora');

	$usuario =  $row->fields('usuario');
	$monto =  $row->fields('monto_cedente');
	
	if (($codigo_proyecto == 0) || ($codigo_proyecto == ""))
		$codi_pro_accion = $codigo_accion_centralizada;
	else
		$codi_pro_accion = $codigo_proyecto;
	class PDF_AutoPrint extends PDF_JavaScript
		{
		function AutoPrint($dialog=false)
		{
			//Open the print dialog or start printing immediately on the standard printer
			$param=($dialog ? 'true' : 'false');
			$script="print($param);";
			$this->IncludeJS($script);
		}
		
		function AutoPrintToPrinter($server, $printer, $dialog=false)
		{
			//Print on a shared printer (requires at least Acrobat 6)
			$script = "var pp = getPrintParams();";
			if($dialog)
				$script .= "pp.interactive = pp.constants.interactionLevel.full;";
			else
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
			$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
			$script .= "print(pp);";
			$this->IncludeJS($script);
		}
			
			//Cabecera de página
			function Header()
			{
				global $accion_espe_cede, $partida_cede, $mes_espe_cede,$accion_espe_rece, $partida_rece, $mes_espe_rece,$monto;
				global $usuario, $monto, $precopromiso, $codigo_unidad , $codi_pro_accion,$id_traspaso;
				$this->Image("../../../../imagenes/logos/logo_ochina_295x260.jpg",10,10,29);
							
				$this->SetFont('Arial','B',9);
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
				$this->Cell(0,5,'FECHA '.date('d/m/Y'),0,0,'R');*/	
				
			}
		}
	$pdf=new PDF_AutoPrint('P','mm','Letter');	
	//$pdf=new PDF();
	$pdf->AliasNbPages();
	$pdf->AddFont('barcode');
	$pdf->AddPage('');
	$pdf->SetFont('arial','',10);
	$pdf->SetFillColor(255);
	$pdf->SetAutoPageBreak(-110);
	
	
	$pdf->Ln();	
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(0,10,'NUMERO DE TRASPASO '.$id_traspaso,0,0,'C');
	$pdf->Ln(12);	
	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(50,10,'PRECOMPROMISO '.$precopromiso,0,0,'L');
	$pdf->Ln(10);
	$pdf->Cell(60,10,'UNIDAD '.$codigo_unidad,'B',0,'L');
	$pdf->Cell(75,10,'PROYECTO /A.C. '.$codi_pro_accion,'B',0,'R');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(80,10,'ACCION ESPECIFICA CEDENTE: '.$accion_espe_cede,'T',0,'L');
	//$pdf->Ln(10);
	$pdf->Cell(70,10,'PARTIDA CEDENTE: '.$partida_cede,'T',0,'L');
	//$pdf->Ln(10);
	$pdf->Cell(50,10,'MES CEDENTE: '.$mes_espe_cede,'T',0,'R');
	$pdf->Ln(10);
	$pdf->Cell(80,10,'ACCION ESPECIFICA RECEPTORA: '.$accion_espe_rece,'TB',0,'L');
	//$pdf->Ln(10);
	$pdf->Cell(70,10,'PARTIDA RECEPTORA: '.$partida_rece,'TB',0,'L');
	//$pdf->Ln(10);
	$pdf->Cell(50,10,'MES RECEPTOR: '.$mes_espe_rece,'TB',0,'R');
	$pdf->Ln(10);
	$pdf->Cell(190,10,'MONTO DEL TRASPASO: '.number_format($monto,2,',','.').' BsF.',0,0,'L');
	$pdf->Ln(10);
	$pdf->Cell(190,10,'USUARIO QUE LO REALIZA: '. $usuario ,0,0,'L');
	$pdf->Ln(10);
	
	
	$pdf->AutoPrint(true);
	$pdf->Output();
}
?>