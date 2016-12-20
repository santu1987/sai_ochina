<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;
$desde = substr($_POST["nomina_pr_desde"],6,4)."-".substr($_POST["nomina_pr_desde"],3,2)."-".substr($_POST["nomina_pr_desde"],0,2);
$hasta = substr($_POST["nomina_pr_hasta"],6,4)."-".substr($_POST["nomina_pr_hasta"],3,2)."-".substr($_POST["nomina_pr_hasta"],0,2);
//echo "desde: ".$desde." hasta: ".$hasta;

//
//funcion que devuelve el numero de lunes que tiene la nomina
$dias='';;
function lunes($fechaInicio,$fechaFin)
{
	$dias=array();
	$fecha1=date($fechaInicio);
	$fecha2=date($fechaFin);
	$fechaTime=strtotime("-1 day",strtotime($fecha1));//Les resto un dia para que el next sunday pueda evaluarlo en caso de que sea un domingo
	$valor=0;
	$fecha=date("Y-m-d",$fechaTime);
	while($fecha <= $fecha2)
	{
		$proximo_lunes=strtotime("next Monday",$fechaTime);
		$fechaLunes=date("Y-m-d",$proximo_lunes);
		if($fechaLunes <= $fechaFin)
		{	
			$dias[$fechaLunes]=$fechaLunes;
		}
		else
		{
			break;
		}
		$fechaTime=$proximo_lunes;
		$fecha=date("Y-m-d",$proximo_lunes);
		$valor++;
	}
	//echo "lunes: ".$valor;
	return $valor;
}//fin de domingos
$lunes=lunes($desde, $hasta); //creo un array que tendra las fechas
//
//
$sql_suel = "SELECT 
				sueldo_aumento 
			FROM 
				aumento_sueldo
			WHERE
				id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				id_organismo = $_SESSION[id_organismo]
			";
$row=& $conn->Execute($sql_suel);
//echo $row->fields("sueldo_aumento");
$sueldo = split('"',$row->fields("sueldo_aumento"));

for($i=0; $i<=39; $i++){
	$tam = strlen($sueldo[$i]);
	$sueldo[$i] = substr($sueldo[$i],1,$tam);
	if($i % 2 == 1 && $sueldo[$i]!='0,00'){
		$ultimo_sueldo = $sueldo[$i];	
		$ultimo_sueldo = str_replace('.','',$ultimo_sueldo);
		$ultimo_sueldo = str_replace(',','.',$ultimo_sueldo);
	}
}
//
//sql para saber como es la frecuencia de la nomina
$sql_fre = "SELECT
				frecuencia.descripcion
			FROM
				frecuencia
			INNER JOIN
				tipo_nomina
			ON
				frecuencia.id_frecuencia = tipo_nomina.id_frecuencia
			WHERE 
				tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
			AND
				frecuencia.id_organismo = $_SESSION[id_organismo]";
$row=& $conn->Execute($sql_fre);				
if(strtoupper($row->fields("descripcion"))=='QUINCENAL')
	$frec = 2;
elseif(strtoupper($row->fields("descripcion"))=='MENSUAL')
	$frec = 1;
	$ultimo_sueldo=$ultimo_sueldo/$frec;	
//
//
//echo $ultimo_sueldo;

$sql_conceptos = "SELECT
					conceptos.id_concepto,
					conceptos.asignacion_deduccion
				FROM
					conceptos
				WHERE
					conceptos.id_organismo = $_SESSION[id_organismo]
					";
$conceptos=& $conn->Execute($sql_conceptos);

//
//
$pos=1;
while(!$conceptos->EOF){
//	
$sso = '';
$pf = '';
$lph = '';
$fesp = '';
$caja = '';
$f = '';
	
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON	
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE 
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND
			confij_tipo_nomina.estatus = 1
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'SSO'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";
		$calculo =& $conn->Execute($SQL); 
		if($calculo->fields("porcentaje")!='')
			$sso = $calculo->fields("porcentaje");
			//echo " seguro: % ".$sso." ";
		//
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE 
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND
			confij_tipo_nomina.estatus = 1 
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'PF'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";
		$calculo =& $conn->Execute($SQL);
		if($calculo->fields("porcentaje")!='')
			$pf = $calculo->fields("porcentaje");
		//
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			confij_tipo_nomina.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND
			confij_tipo_nomina.estatus = 1
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'LPH'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";

		$calculo =& $conn->Execute($SQL);
		if($calculo->fields("porcentaje")!='')
			$lph= $calculo->fields("porcentaje");
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			confij_tipo_nomina.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND	
			confij_tipo_nomina.estatus = 1
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'FESP'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";
		$calculo =& $conn->Execute($SQL);
		if($calculo->fields("porcentaje")!='')
			$fesp = $calculo->fields("porcentaje");
		//
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			confij_tipo_nomina.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND
			confij_tipo_nomina.estatus = 1
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'CAJA'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";
		$calculo =& $conn->Execute($SQL);
		if($calculo->fields("porcentaje")!='')
			$caja = $calculo->fields("porcentaje");
		//
		$SQL = "SELECT
			calculo_rrhh.id_calculo_rrhh,
			codigo,
			conceptos_fijos.porcentaje
		FROM
			calculo_rrhh
		INNER JOIN
			conceptos_fijos
		ON
			calculo_rrhh.id_calculo_rrhh = conceptos_fijos.id_calculo_rrhh
		INNER JOIN
			confij_tipo_nomina
		ON
			confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos 
		INNER JOIN
			tipo_nomina
		ON
			confij_tipo_nomina.id_tipo_nomina = tipo_nomina.id_tipo_nomina
		INNER JOIN
			trabajador
		ON
			confij_tipo_nomina.id_trabajador = trabajador.id_trabajador
		WHERE 
			conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
		AND
			confij_tipo_nomina.id_trabajador = $_POST[nomina_pr_id_trabajador]
		AND
			confij_tipo_nomina.estatus = 1
		AND
			tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
		AND
			upper(calculo_rrhh.codigo) = 'F'
		AND
			calculo_rrhh.id_organismo = $_SESSION[id_organismo]
		";
		$calculo =& $conn->Execute($SQL);
		if($calculo->fields("porcentaje")!='')
			$f = $calculo->fields("porcentaje");
//
//

//echo " sso: ".$sso." pf: ".$pf." lph: ".$lph." fesp: ".$fesp." caja: ".$caja." f: ".$f;

// SQL para buscar todos los conceptos fijos que sean asignaciones
$sql_cfasig = "SELECT 
				trabajador.id_trabajador,
				conceptos_fijos.id_concepto_fijos,
				conceptos_fijos.porcentaje,
				conceptos_fijos.observacion,
				conceptos.limite_inf,
				conceptos.limite_sup,
				conceptos_fijos.id_calculo_rrhh
			FROM 
				trabajador 
			INNER JOIN 
				confij_tipo_nomina
			ON
				trabajador.id_trabajador = confij_tipo_nomina.id_trabajador
			INNER JOIN
				conceptos_fijos
			ON
				confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos
			INNER JOIN
				conceptos
			ON
				conceptos_fijos.id_concepto = conceptos.id_concepto
			WHERE
				conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
			AND
				trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				confij_tipo_nomina.estatus = 1
			AND
				conceptos.asignacion_deduccion LIKE 'Asignacion'
			AND
				conceptos_fijos.id_calculo_rrhh is null  
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
				";	
				
$total_cfasig = 0;				
$row=& $conn->Execute($sql_cfasig);
while (!$row->EOF) 
{	
	//echo "Sueldo: ".$ultimo_sueldo." ";
	//echo "Porcentaje cf: ".$row->fields("porcentaje")." ";
	$cfasig = ($ultimo_sueldo * $row->fields("porcentaje"))/100;
	//echo "Resultado: ".$cfasig." ";
	$tam = strlen($row->fields("limite_inf"));
	$limite_inf = substr($row->fields("limite_inf"),1,$tam);
	$limite_inf = str_replace('.','',$limite_inf);
	$limite_inf = str_replace(',','.',$limite_inf);
	$tam = strlen($row->fields("limite_sup"));
	$limite_sup = substr($row->fields("limite_sup"),1,$tam);
	$limite_sup = str_replace('.','',$limite_sup);
	$limite_sup = str_replace(',','.',$limite_sup);
	if($cfasig < $limite_inf){
		$cfasig = $limite_inf;}
	elseif($cfasig > $limite_sup){
		$cfasig = $limite_sup;	
		}
	$total_cfasig = $total_cfasig + $cfasig;
	$row->MoveNext();
}

//echo $total_cfasig;
//
// SQL para buscar todos los conceptos fijos que sean deducciones
$sql_cfded = "SELECT 
				trabajador.id_trabajador,
				conceptos_fijos.id_concepto_fijos,
				conceptos_fijos.porcentaje,
				conceptos_fijos.observacion,
				conceptos.limite_inf,
				conceptos.limite_sup,
				conceptos_fijos.id_calculo_rrhh
			FROM 
				trabajador 
			INNER JOIN 
				confij_tipo_nomina
			ON
				trabajador.id_trabajador = confij_tipo_nomina.id_trabajador
			INNER JOIN
				conceptos_fijos
			ON
				confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos
			INNER JOIN
				conceptos
			ON
				conceptos_fijos.id_concepto = conceptos.id_concepto
			WHERE
				conceptos_fijos.id_concepto = ".$conceptos->fields("id_concepto")."
			AND
				trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				confij_tipo_nomina.estatus = 1
			AND
				conceptos.asignacion_deduccion LIKE 'Deduccion'
			AND
				conceptos_fijos.id_calculo_rrhh is null
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
				";
$total_cfded = 0;
$row=& $conn->Execute($sql_cfded);
while (!$row->EOF) 
{	
	//echo "Sueldo: ".$ultimo_sueldo." ";
	//echo "Porcentaje: ".$row->fields("porcentaje");
	$cfded = ($ultimo_sueldo * $row->fields("porcentaje"))/100;
	//echo "Resultado: ".$cfasig." ";
	$tam = strlen($row->fields("limite_inf"));
	$limite_inf = substr($row->fields("limite_inf"),1,$tam);
	$limite_inf = str_replace('.','',$limite_inf);
	$limite_inf = str_replace(',','.',$limite_inf);
	$tam = strlen($row->fields("limite_sup"));
	$limite_sup = substr($row->fields("limite_sup"),1,$tam);
	$limite_sup = str_replace('.','',$limite_sup);
	$limite_sup = str_replace(',','.',$limite_sup);
	if($cfded < $limite_inf){
		$cfded = $limite_inf;}
	elseif($cfded > $limite_sup){
		$cfded = $limite_sup;	
		}
	$total_cfded = $total_cfded + $cfded;
	$row->MoveNext();
}
//echo $total_cfded;				
//
// SQL para buscar todos los conceptos variantes que sean asignaciones
$sql_cvasig = "SELECT 
				trabajador.id_trabajador,
				concepto_variable.id_concepto_variable,
				concepto_variable.monto,
				concepto_variable.observacion,
				conceptos.limite_inf,
				conceptos.limite_sup
			FROM 
				trabajador 
			INNER JOIN 
				convar_tipo_nomina
			ON
				trabajador.id_trabajador = convar_tipo_nomina.id_trabajador
			INNER JOIN
				concepto_variable
			ON
				convar_tipo_nomina.id_concepto_variable = concepto_variable.id_concepto_variable
			INNER JOIN
				conceptos
			ON
				concepto_variable.id_concepto = conceptos.id_concepto
			WHERE
				concepto_variable.id_concepto = ".$conceptos->fields("id_concepto")."
			AND
				concepto_variable.id_nominas = ".$_POST['nomina_pr_id_nominas']."
			AND
				trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				convar_tipo_nomina.estatus = 1
			AND
				conceptos.asignacion_deduccion LIKE 'Asignacion'
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
				";
	
$total_cvasig = 0;
$row=& $conn->Execute($sql_cvasig);
while (!$row->EOF) 
{	
	//echo "Sueldo: ".$ultimo_sueldo." ";
	//echo "Monto cv: ".$row->fields("monto")." ";
	$tam = strlen($row->fields("monto"));
	$cvasig = substr($row->fields("monto"),1,$tam);
	$cvasig = str_replace('.','',$cvasig);
	$cvasig = str_replace(',','.',$cvasig);
	//echo "Resultado: ".$cfasig." ";
	$tam = strlen($row->fields("limite_inf"));
	$limite_inf = substr($row->fields("limite_inf"),1,$tam);
	$limite_inf = str_replace('.','',$limite_inf);
	$limite_inf = str_replace(',','.',$limite_inf);
	$tam = strlen($row->fields("limite_sup"));
	$limite_sup = substr($row->fields("limite_sup"),1,$tam);
	$limite_sup = str_replace('.','',$limite_sup);
	$limite_sup = str_replace(',','.',$limite_sup);
	if($cvasig < $limite_inf){
		$cvasig = $limite_inf;}
	elseif($cvasig > $limite_sup){
		$cvasig = $limite_sup;	
		}
	$total_cvasig = $total_cvasig + $cvasig;
	$row->MoveNext();
}

//echo $total_cvasig;				
//
// SQL para buscar todos los conceptos variantes que sean deducciones
$sql_cvded = "SELECT 
				trabajador.id_trabajador,
				concepto_variable.id_concepto_variable,
				concepto_variable.monto,
				concepto_variable.observacion,
				conceptos.limite_inf,
				conceptos.limite_sup
			FROM 
				trabajador 
			INNER JOIN 
				convar_tipo_nomina
			ON
				trabajador.id_trabajador = convar_tipo_nomina.id_trabajador
			INNER JOIN
				concepto_variable
			ON
				convar_tipo_nomina.id_concepto_variable = concepto_variable.id_concepto_variable
			INNER JOIN
				conceptos
			ON
				concepto_variable.id_concepto = conceptos.id_concepto
			WHERE
				concepto_variable.id_concepto = ".$conceptos->fields("id_concepto")."
			AND
				concepto_variable.id_nominas = ".$_POST['nomina_pr_id_nominas']."
			AND
				trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				convar_tipo_nomina.estatus = 1
			AND
				conceptos.asignacion_deduccion LIKE 'Deduccion'
			AND
				trabajador.id_organismo = $_SESSION[id_organismo]
				";
				
$total_cvded = 0;				
$row=& $conn->Execute($sql_cvded);
while (!$row->EOF) 
{	
	//echo "Sueldo: ".$ultimo_sueldo." ";
	//echo "Porcentaje: ".$row->fields("monto")." ";
	$tam = strlen($row->fields("monto"));
	$cvded = substr($row->fields("monto"),1,$tam);
	$cvded = str_replace('.','',$cvded);
	$cvded = str_replace(',','.',$cvded);
	//echo "Resultado: ".$cfasig." ";
	$tam = strlen($row->fields("limite_inf"));
	$limite_inf = substr($row->fields("limite_inf"),1,$tam);
	$limite_inf = str_replace('.','',$limite_inf);
	$limite_inf = str_replace(',','.',$limite_inf);
	$tam = strlen($row->fields("limite_sup"));
	$limite_sup = substr($row->fields("limite_sup"),1,$tam);
	$limite_sup = str_replace('.','',$limite_sup);
	$limite_sup = str_replace(',','.',$limite_sup);
	if($cvded < $limite_inf){
		$cvded = $limite_inf;}
	elseif($cvded > $limite_sup){
		$cvded = $limite_sup;	
		}
	$total_cvded = $total_cvded + $cvded;
	$row->MoveNext();
}
//echo $total_cvded;
//
//calculo de la nomina
//echo " total de asignaciones en conceptos fijos: ".$total_cfasig."<br>";
//echo " total de asignaciones en conceptos variantes: ".$total_cvasig."<br>";
//echo " porcentaje: ".$sso."<br>";
//echo " lunes: ".$lunes;

//calculo del seguro social
$sso = (((($total_cfasig+$total_cvasig)/52)*12)*$sso)*$lunes;

$pf = (((($total_cfasig+$total_cvasig)/52)*12)*$pf)*$lunes;

$lph = ($total_cfasig+$total_cvasig)*$lph;

$fesp = ($total_cfasig+$total_cvasig)*$fesp;

$caja = ($total_cfasig+$total_cvasig)*$caja;

$f = ($total_cfasig+$total_cvasig)*$f;

$total_concepto = (($total_cfasig+$total_cvasig)+((((($sso+$pf)+$lph)+$fesp)+$caja)+$f))-($total_cfded-$total_cvded);

//$total_concepto = $total_concepto-($total_cfded+$total_cvded);
//echo " total cfasig ".$total_cfasig." ";
//echo " total cvasig ".$total_cvasig." ";
//echo " total: ".$total_concepto." ";
//

//
//
/*$Sql="
			SELECT 
				count(id_nomina) 
			FROM 
				nomina
			WHERE
				id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
			AND
				id_trabajador = $_POST[nomina_pr_id_trabajador]
			AND
				id_concepto = $_POST[nomina_pr_id_concepto]
			AND
				id_nominas = $_POST[nomina_pr_id_nominas]
			AND 
				nomina.id_organismo = $_SESSION[id_organismo]	
";
$row=& $conn->Execute($Sql);
$row= substr($row,7,2);

if ($row==0){*/


$sql_compcf = "SELECT
					COUNT(trabajador.id_trabajador) as exi
				FROM	
					trabajador
				INNER JOIN
					confij_tipo_nomina
				ON
					trabajador.id_trabajador = confij_tipo_nomina.id_trabajador
				INNER JOIN
					conceptos_fijos
				ON
					confij_tipo_nomina.id_concepto_fijos = conceptos_fijos.id_concepto_fijos
				INNER JOIN
					conceptos
				ON
					conceptos_fijos.id_concepto = conceptos.id_concepto
				WHERE 
					trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
				AND
					confij_tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
				AND
					conceptos.id_concepto = ".$conceptos->fields("id_concepto")
					;
					
	$compcf=& $conn->Execute($sql_compcf);
	
	$sql_compcv = "SELECT
						COUNT(trabajador.id_trabajador) as exi
					FROM
						trabajador
					INNER JOIN
						convar_tipo_nomina
					ON
						trabajador.id_trabajador = convar_tipo_nomina.id_trabajador
					INNER JOIN
						concepto_variable
					ON
						convar_tipo_nomina.id_concepto_variable = concepto_variable.id_concepto_variable
					INNER JOIN
						conceptos
					ON
						concepto_variable.id_concepto = conceptos.id_concepto
					AND
						trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador]
					AND
						convar_tipo_nomina.id_tipo_nomina = $_POST[nomina_pr_id_tipo_nomina]
					AND
						conceptos.id_concepto = ".$conceptos->fields("id_concepto");
			
			$compcv =& $conn->Execute($sql_compcv);
			
			if($compcf->fields("exi"!=0) || $compcv->fields("exi")!=0){
	$sql = "	
				INSERT INTO 
					nomina 
					(
						id_organismo,
						id_tipo_nomina,
						id_trabajador,
						id_concepto,
						id_nominas,
						cedula,
						monto_concepto,
						asignacion_deduccion,
						ultimo_usuario,
						fecha_actualizacion
					) 
					VALUES
					(
						'$_SESSION[id_organismo]',
						'$_POST[nomina_pr_id_tipo_nomina]',
						'$_POST[nomina_pr_id_trabajador]',
						'".$conceptos->fields("id_concepto")."',
						'$_POST[nomina_pr_id_nominas]',
						'$_POST[nomina_pr_cedula_trabajador]',
						'$total_concepto',
						'".$conceptos->fields("asignacion_deduccion")."',
						'$_SESSION[id_usuario]',
						'$_POST[nomina_pr_fechact]'
					)
			";
			
	$conn->Execute($sql);		
	}
	$conceptos->MoveNext();
}
echo ("Registrado");
/*if ($conn->Execute($sql) == false) {
	echo 'Error al Insertar: '.$conn->ErrorMsg().'<BR>';
}
else
{
	echo ("Registrado");
}
//}
if ($row!=0){
	echo'Existe';
}*/
?>