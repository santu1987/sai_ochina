<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

if(!$sidx) $sidx =1;

//
//
//Id de los conceptos SSO, LPH, CAJA, FESP, F, PF
$bus_sso = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'SEGURO SOCIAL OBLIGATORIO' AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_sso =& $conn->Execute($bus_sso);

$bus_lph = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'LEY POLITICA HABITACIONAL' AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_lph =& $conn->Execute($bus_lph);

$bus_caja = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'APORTE CAJA DE AHORROS' AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_caja =& $conn->Execute($bus_caja);
$cadena ="FONDO ESPECIAL DE JUBILACIÓN";
$bus_fesp = "SELECT id_concepto FROM conceptos WHERE lower(descripcion) like lower('%FONDO ESPECIAL DE JUBILACI%') AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_fesp =& $conn->Execute($bus_fesp);

$bus_f = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'APORTE FONDOEFA' AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_f =& $conn->Execute($bus_f);

$bus_pf = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'PARO FORZOSO' AND id_organismo = $_SESSION[id_organismo] ";	
$row_bus_pf =& $conn->Execute($bus_pf);

$bus_pp = "SELECT id_concepto FROM conceptos WHERE lower(descripcion) like lower('%PRIMA PROFESIONALIZACI%') AND id_organismo = $_SESSION[id_organismo] ";
$row_bus_pp =& $conn->Execute($bus_pp); 

$bus_pa = "SELECT id_concepto FROM conceptos WHERE upper(descripcion) like 'PRIMA DE ANTIGUEDAD' AND id_organismo = $_SESSION[id_organismo] ";
$row_bus_pa =& $conn->Execute($bus_pa); 

$bus_val_mon = "SELECT nombre, id_val_moneda, valor_moneda FROM moneda INNER JOIN valor_moneda ON moneda.id_moneda = valor_moneda.id_moneda WHERE upper(nombre) like 'UNIDAD TRIBUTARIA' AND moneda.id_organismo = $_SESSION[id_organismo] ORDER BY id_val_moneda DESC";
$row_val_mon =& $conn->Execute($bus_val_mon);
$uni_tri = $row_val_mon->fields("valor_moneda");
//
//
$sql_tra = "SELECT
					trabajador.id_trabajador,
					persona.cedula,
					persona.nombre,
					persona.apellido,
					aumento_sueldo.sueldo_aumento,
					frecuencia.descripcion,
					tipo_nomina.id_tipo_nomina,
					trabajador.fecha_ingreso,
					trabajador.anos_servicios
				FROM
					persona
				INNER JOIN
					trabajador
				ON
					trabajador.id_persona = persona.id_persona
				INNER JOIN
					aumento_sueldo
				ON
					trabajador.id_trabajador = aumento_sueldo.id_trabajador
				INNER JOIN
					tipo_nomina
				ON
					trabajador.id_tipo_nomina = tipo_nomina.id_tipo_nomina
				INNER JOIN
					frecuencia
				ON
					tipo_nomina.id_frecuencia = frecuencia.id_frecuencia
				WHERE 
					trabajador.id_trabajador = $_POST[nomina_pr_id_trabajador] 
				AND
					trabajador.id_organismo = $_SESSION[id_organismo] ";
		$row_tra =& $conn->Execute($sql_tra);	
	//
	if($row_tra->fields("id_trabajador")!=''){
	//
	//
	$df = date("d");
	$mf = date("m");
	$af = date("Y");
	$di = substr($row_tra->fields("fecha_ingreso"),8,2);
	$mi = substr($row_tra->fields("fecha_ingreso"),5,2);
	$ai = substr($row_tra->fields("fecha_ingreso"),0,4);
	$fecha_ini = mktime(0,0,0,$mi,$di,$ai);
	$fecha_fin = mktime(0,0,0,$mf,$df,$af);
	$total_fechas = $fecha_fin - $fecha_ini;
	$total_fechas = $total_fechas / 31536000;
			
	$pos = strpos($total_fechas,'.');
		if(pos != '')
			$total_fechas = substr($total_fechas,0,$pos);
			
	$total_fechas = $total_fechas + $row_tra->fields("anos_servicios");			
	$sql_nominas = "SELECT
						nominas.id_nominas,
						nominas.numero_nomina,
						nominas.desde,
						nominas.hasta,
						nominas.procesada,
						frecuencia.descripcion
					FROM
						nominas
					INNER JOIN
						tipo_nomina
					ON
						nominas.id_tipo_nomina = tipo_nomina.id_tipo_nomina
					INNER JOIN
						frecuencia
					ON
						tipo_nomina.id_frecuencia = frecuencia.id_frecuencia
					WHERE
						nominas.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
					AND
						nominas.procesada = 0
					AND
						nominas.id_organismo = $_SESSION[id_organismo] 
					ORDER BY id_nominas";
	$row_nominas =& $conn->Execute($sql_nominas);
	//
	if(strtoupper($row_nominas->fields("descripcion"))=='QUINCENAL')
		$frecuencia = 2;
	if(strtoupper($row_nominas->fields("descripcion"))=='SEMANAL')	
		$frecuencia = 4;
	$desde = $row_nominas->fields("desde");
	$hasta = $row_nominas->fields("hasta");
	//
	//
	$ultimo_sueldo = 0;
	$suel = $row_tra->fields("sueldo_aumento");
	$suel = str_replace('{','',$suel);
	$suel = str_replace('}','',$suel);
	$sueldo = split(',',$suel);
	for($i=0; $i<=19; $i++){
		if($sueldo[$i]!=0)
			$ultimo_sueldo = $sueldo[$i];  
		}
	//$ultimo_sueldo = $ultimo_sueldo / $frecuencia;
	//
	//
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
	$sql_con = "SELECT
					conceptos.id_concepto,
					conceptos.descripcion,
					conceptos.asignacion_deduccion,
					conceptos.limite_inf,
					conceptos.limite_sup
				FROM
					conceptos
				WHERE
					conceptos.id_organismo = $_SESSION[id_organismo]
					";
	$row_con =& $conn->Execute($sql_con);
	//
	$total_asig = 0;
	if($ultimo_sueldo!=0)
		$total_asig = $ultimo_sueldo;
	$total_ded = 0;
	$total_asig_sso = 0;
	if($ultimo_sueldo!=0)
		$total_asig_sso = $ultimo_sueldo;
	$total_asig_lph = 0;
	if($ultimo_sueldo!=0)
		$total_asig_lph = $ultimo_sueldo;
	$total_asig_caja = 0;
	if($ultimo_sueldo!=0)
		$total_asig_caja = $ultimo_sueldo;
	$total_asig_fesp = 0;
	if($ultimo_sueldo!=0)
		$total_asig_fesp = $ultimo_sueldo;
	$total_asig_f = 0;
	if($ultimo_sueldo!=0)
		$total_asig_f = $ultimo_sueldo;
	$total_asig_pf = 0;
	if($ultimo_sueldo!=0)
		$total_asig_pf = $ultimo_sueldo;
	$monto = 0;	
	//
	//Eliminacion de los registros de la nomina de la persona
	$sql_del = "DELETE FROM nomina WHERE id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_nominas = ".$row_nominas->fields("id_nominas")." AND id_organismo = $_SESSION[id_organismo] ";
	$conn->Execute($sql_del);
	//
	//
	while(!$row_con->EOF){
		$sql_cf_sso = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'SSO'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";
		$row_cf_sso =& $conn->Execute($sql_cf_sso);						
		$sql_cv_sso = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'SSO'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";
		$row_cv_sso =& $conn->Execute($sql_cv_sso);
		
		$sql_cf_lph = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'LPH'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";						
		$row_cf_lph =& $conn->Execute($sql_cf_lph);
		$sql_cv_lph = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'LPH'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";						
		$row_cv_lph =& $conn->Execute($sql_cv_lph);
		
		$sql_cf_caja = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'CAJA'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";						
		$row_cf_caja =& $conn->Execute($sql_cf_caja);
		$sql_cv_caja = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'CAJA'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";						
		$row_cv_caja =& $conn->Execute($sql_cv_caja);
		
		$sql_cf_fesp = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'FEJP'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";
		$row_cf_fesp =& $conn->Execute($sql_cf_fesp);
		$sql_cv_fesp = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'FEJP'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";
		$row_cv_fesp =& $conn->Execute($sql_cv_fesp);
		
		$sql_cf_f = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'F'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";
		$row_cf_f =& $conn->Execute($sql_cf_f);
		$sql_cv_f = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'F'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";
		$row_cv_f =& $conn->Execute($sql_cv_f);
		$sql_cf_pf = "SELECT
								conceptos_fijos.id_concepto_fijos,
								conceptos_fijos.porcentaje,
								conceptos_fijos.monto,
								codigo
							FROM
								conceptos_fijos
							INNER JOIN
								conceptos
							ON
								conceptos_fijos.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'PF'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";						
		$row_cf_pf =& $conn->Execute($sql_cf_pf);
		$sql_cv_pf = "SELECT
								concepto_variable.id_concepto_variable,
								concepto_variable.porcentaje,
								concepto_variable.monto,
								codigo
							FROM
								concepto_variable
							INNER JOIN
								conceptos
							ON
								concepto_variable.id_concepto = conceptos.id_concepto	
							INNER JOIN
								concep_cal_rrhh
							ON
								conceptos.id_concepto = concep_cal_rrhh.id_conceptos
							INNER JOIN	
								calculo_rrhh
							ON
								concep_cal_rrhh.id_calculo_rrhh = calculo_rrhh.id_calculo_rrhh
							WHERE
								concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
							AND
								concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
							AND
								upper(conceptos.asignacion_deduccion) = 'ASIGNACION'
							AND
								upper(calculo_rrhh.codigo) = 'PF'
							AND
								concep_cal_rrhh.estatu = 1
							AND
								concepto_variable.id_organismo = $_SESSION[id_organismo] ";						
		$row_cv_pf =& $conn->Execute($sql_cv_pf);
		//
		//
		if($row_cv_sso->fields("id_concepto_variable")!='' && $row_cf_sso->fields("id_concepto_fijos")==''){
			
			if($row_cv_sso->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_sso->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_sso->fields("monto")!=0)
			$total_asig_sso = $total_asig_sso + $row_cv_sso->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_sso = $total_asig_sso + $row_cv_sso->fields("monto");
			
			if($row_cv_sso->fields("monto") < $row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_sso = $total_asig_sso + $row_con->fields("limite_inf");
			
			if($row_cv_sso->fields("monto") > $row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_sso = $total_asig_sso + $row_con->fields("limite_sup");
			
		}
		if($row_cv_sso->fields("id_concepto_variable")=='' && $row_cf_sso->fields("id_concepto_fijos")!=''){
			if($row_cf_sso->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_sso->fields("monto")<=$row_con->fields("limite_inf") && $row_cf_sso->fields("monto")!=0)
			$total_asig_sso = $total_asig_sso + $row_cf_sso->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_inf")==0)
			$total_asig_sso = $total_asig_sso + $row_cf_sso->fields("monto");
			
			if($row_cf_sso->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_sso = $total_asig_sso + $row_con->fields("limite_inf");
			
			if($row_cf_sso->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_sso = $total_asig_sso + $row_con->fields("limite_sup");
			
		}
		//	
		if($row_cv_lph->fields("id_concepto_variable")!='' && $row_cf_lph->fields("id_concepto_fijos")==''){
			if($row_cv_lph->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_lph->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_lph->fields("monto")!=0)
			$total_asig_lph = $total_asig_lph + $row_cv_lph->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_lph = $total_asig_lph + $row_cv_lph->fields("monto");
			
			if($row_cv_lph->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_lph = $total_asig_lph + $row_con->fields("limite_inf");
			
			if($row_cv_lph->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_lph = $total_asig_lph + $row_con->fields("limite_sup");
			
		}
		if($row_cv_lph->fields("id_concepto_variable")=='' && $row_cf_lph->fields("id_concepto_fijos")!=''){
			if($row_cf_lph->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_lph->fields("monto")<=$row_con->fields("limite_sup") && $row_cf_lph->fields("monto")!=0)
			$total_asig_lph = $total_asig_lph + $row_cf_lph->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_lph = $total_asig_lph + $row_cf_lph->fields("monto");
			
			if($row_cf_lph->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_sup")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_lph = $total_asig_lph + $row_con->fields("limite_inf");
			
			if($row_cf_lph->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_lph = $total_asig_lph + $row_con->fields("limite_sup");
			
		}
		//	
		if($row_cv_caja->fields("id_concepto_variable")!='' && $row_cf_caja->fields("id_concepto_fijos")==''){	
			if($row_cv_caja->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_caja->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_caja->fields("monto")!=0)
			$total_asig_caja = $total_asig_caja + $row_cv_caja->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_caja = $total_asig_caja + $row_cv_caja->fields("monto");
			
			if($row_cv_caja->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_sup")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_caja = $total_asig_caja + $row_con->fields("limite_inf");
			
			if($row_cv_caja->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_caja = $total_asig_caja + $row_con->fields("limite_sup");
			
		}
		if($row_cv_caja->fields("id_concepto_variable")=='' && $row_cf_caja->fields("id_concepto_fijos")!=''){	
			if($row_cf_caja->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_caja->fields("monto")<=$row_con->fields("limite_sup") && $row_cf_caja->fields("monto")!=0)
			$total_asig_caja = $total_asig_caja + $row_cf_caja->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_caja = $total_asig_caja + $row_cf_caja->fields("monto");
			
			if($row_cf_caja->fields("monto")<=$row_con->fields("limite_inf") && $row_con->fields("limite_sup")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_caja = $total_asig_caja + $row_con->fields("limite_inf");
			
			if($row_cf_caja->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_caja = $total_asig_caja + $row_con->fields("limite_sup");
			
		}
		//
		if($row_cv_fesp->fields("id_concepto_variable")!='' && $row_cf_fesp->fields("id_concepto_fijos")==''){
			if($row_cv_fesp->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_fesp->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_fesp->fields("monto")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_cv_fesp->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_fesp = $total_asig_fesp + $row_cv_fesp->fields("monto");
			
			if($row_cv_fesp->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_con->fields("limite_inf");
			
			if($row_cv_fesp->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_con->fields("limite_sup");
			
		}
		if($row_cv_fesp->fields("id_concepto_variable")=='' && $row_cf_fesp->fields("id_concepto_fijos")!=''){
			if($row_cf_fesp->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_fesp->fields("monto")<=$row_con->fields("limite_sup") && $row_cf_fesp->fields("monto")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_cf_fesp->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_fesp = $total_asig_fesp + $row_cf_fesp->fields("monto");
			
			if($row_cf_fesp->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_con->fields("limite_inf");
			
			if($row_cf_fesp->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_fesp = $total_asig_fesp + $row_con->fields("limite_sup");
			
		}
		//
		if($row_cv_f->fields("id_concepto_variable")!='' && $row_cf_f->fields("id_concepto_fijos")==''){
			if($row_cv_f->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_f->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_f->fields("monto")!=0)
			$total_asig_f = $total_asig_f + $row_cv_f->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_f = $total_asig_f + $row_cv_f->fields("monto");
			
			if($row_cv_f->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_f = $total_asig_f + $row_con->fields("limite_inf");
			
			if($row_cv_f->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_f = $total_asig_f + $row_con->fields("limite_sup");
			
		}
		if($row_cv_f->fields("id_concepto_variable")=='' && $row_cf_f->fields("id_concepto_fijos")!=''){
			if($row_cf_f->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_f->fields("monto")<=$row_con->fields("limite_sup") && $row_cf_f->fields("monto")!=0)
			$total_asig_f = $total_asig_f + $row_cf_f->fields("monto");	
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_f = $total_asig_f + $row_cf_f->fields("monto");	
			
			if($row_cf_f->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_f = $total_asig_f + $row_con->fields("limite_inf");	
			
			if($row_cf_f->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_f = $total_asig_f + $row_con->fields("limite_sup");	
			
		}
		//	
		if($row_cv_pf->fields("id_concepto_variable")!='' && $row_cf_pf->fields("id_concepto_fijos")==''){
			if($row_cv_pf->fields("monto")>=$row_con->fields("limite_inf") && $row_cv_pf->fields("monto")<=$row_con->fields("limite_sup") && $row_cv_pf->fields("monto")!=0)
			$total_asig_pf = $total_asig_pf + $row_cv_pf->fields("monto");
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_pf = $total_asig_pf + $row_cv_pf->fields("monto");
			
			if($row_cv_pf->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_pf = $total_asig_pf + $row_con->fields("limite_inf");
			
			if($row_cv_pf->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_pf = $total_asig_pf + $row_con->fields("limite_sup");
			
		}
		if($row_cv_pf->fields("id_concepto_variable")=='' && $row_cf_pf->fields("id_concepto_fijos")!=''){
			if($row_cf_pf->fields("monto")>=$row_con->fields("limite_inf") && $row_cf_pf->fields("monto")<=$row_con->fields("limite_sup") && $row_cf_pf->fields("monto")!=0)
			$total_asig_pf = $total_asig_pf + $row_cf_pf->fields("monto");	
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0)
			$total_asig_pf = $total_asig_pf + $row_cf_pf->fields("monto");	
			
			if($row_cf_pf->fields("monto")<$row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_pf = $total_asig_pf + $row_con->fields("limite_inf");	
			
			if($row_cf_pf->fields("monto")>$row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0)
			$total_asig_pf = $total_asig_pf + $row_con->fields("limite_sup");	
			
		}
		//
		//
		$row_con->MoveNext();
	}
	
	
	
	
	
	
	$sql_con = "SELECT
					conceptos.id_concepto,
					conceptos.descripcion,
					conceptos.asignacion_deduccion,
					conceptos.limite_inf,
					conceptos.limite_sup
				FROM
					conceptos
				WHERE
					conceptos.id_organismo = $_SESSION[id_organismo]
					";
	$row_con =& $conn->Execute($sql_con);
	while(!$row_con->EOF){
			$sql_cf =" SELECT
							conceptos_fijos.id_concepto_fijos,
							conceptos_fijos.porcentaje,
							conceptos_fijos.monto
						FROM
							conceptos_fijos
						WHERE
							conceptos_fijos.id_trabajador = ".$row_tra->fields("id_trabajador")."
						AND
							conceptos_fijos.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
						AND
							conceptos_fijos.id_concepto = ".$row_con->fields("id_concepto")."
						AND	
							conceptos_fijos.id_organismo = $_SESSION[id_organismo] ";
			$row_cf =& $conn->Execute($sql_cf);			
			$sql_cv =" SELECT
							concepto_variable.id_concepto_variable,
							concepto_variable.porcentaje,
							concepto_variable.monto
						FROM
							concepto_variable
						WHERE
							concepto_variable.id_trabajador = ".$row_tra->fields("id_trabajador")."
						AND
							concepto_variable.id_tipo_nomina = ".$row_tra->fields("id_tipo_nomina")."
						AND
							concepto_variable.id_concepto = ".$row_con->fields("id_concepto")."
						AND
							concepto_variable.id_organismo = $_SESSION[id_organismo] ";
			$row_cv =& $conn->Execute($sql_cv);				
		//
		//
		 if($row_cv->fields("id_concepto_variable")!='' && $row_cf->fields("id_concepto_fijos")=='' && strtoupper($row_con->fields("asignacion_deduccion"))=='ASIGNACION'){
			if($row_cv->fields("monto")>=$row_con->fields("limite_inf") && $row_cv->fields("monto")<=$row_con->fields("limite_sup") && $row_cv->fields("monto")!=0){
				
				$total_asig = $total_asig + $row_cv->fields("monto");
				$monto = $row_cv->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0){
				
				$total_asig = $total_asig + $row_cv->fields("monto");
				$monto = $row_cv->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_cv->fields("monto") < $row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_asig = $total_asig + $row_con->fields("limite_inf");
				$monto = $row_con->fields("limite_inf")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_cv->fields("monto") > $row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_asig = $total_asig + $row_con->fields("limite_sup");
				$monto = $row_con->fields("limite_sup")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			$conn->Execute($sql);
		 }
		 //
		 //
		 if($row_cv->fields("id_concepto_variable")=='' && $row_cf->fields("id_concepto_fijos")!='' && strtoupper($row_con->fields("asignacion_deduccion"))=='ASIGNACION'){
			 
			if($row_cf->fields("monto")>=$row_con->fields("limite_inf") && $row_cf->fields("monto")<=$row_con->fields("limite_sup") && $row_cf->fields("monto")!=0){
				
				$total_asig = $total_asig + $row_cf->fields("monto");
				$monto = $row_cf->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0){
				
				$total_asig = $total_asig + $row_cf->fields("monto");
				$monto = $row_cf->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_cf->fields("monto") < $row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_asig = $total_asig + $row_con->fields("limite_inf");
				$monto = $row_con->fields("limite_inf")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			
			if($row_cf->fields("monto") > $row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_asig = $total_asig + $row_con->fields("limite_sup");
				$monto = $row_con->fields("limite_sup")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
			}
			$conn->Execute($sql);
		 }
		 //
		 //
		  if($row_cv->fields("id_concepto_variable")!='' && $row_cf->fields("id_concepto_fijos")=='' && strtoupper($row_con->fields("asignacion_deduccion"))=='DEDUCCION'){
			 
			if($row_cv->fields("monto")>=$row_con->fields("limite_inf") && $row_cv->fields("monto")<=$row_con->fields("limite_sup") && $row_cv->fields("monto")!=0){
				
				$total_ded = $total_ded + $row_cv->fields("monto");
				$monto = $row_cv->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				
			}
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0){
				
				$total_ded = $total_ded + $row_cv->fields("monto");
				$monto = $row_cv->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				
			}
			
			if($row_cv->fields("monto") < $row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_ded = $total_ded + $row_con->fields("limite_inf");
				$monto = $row_con->fields("limite_inf")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				
			}
			
			if($row_cv->fields("monto") > $row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_ded = $total_ded + $row_con->fields("limite_sup");
				$monto = $row_con->fields("limite_sup")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				
			}
			$conn->Execute($sql);
		 }
		 //
		 //
		  if($row_cv->fields("id_concepto_variable")=='' && $row_cf->fields("id_concepto_fijos")!='' && strtoupper($row_con->fields("asignacion_deduccion"))=='DEDUCCION'){
			 
			if($row_cf->fields("monto")>=$row_con->fields("limite_inf") && $row_cf->fields("monto")<=$row_con->fields("limite_sup") && $row_cf->fields("monto")!=0){
				
				$total_ded = $total_ded + $row_cf->fields("monto");
				$monto = $row_cf->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				//echo " paso1 ".$row_cf->fields("monto").", ";
			}
			
			if($row_con->fields("limite_inf")==0 && $row_con->fields("limite_sup")==0){
				
				$total_ded = $total_ded + $row_cf->fields("monto");
				$monto = $row_cf->fields("monto")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				//echo " paso2 ".$row_cf->fields("monto").", ";
			}
			
			if($row_cf->fields("monto") < $row_con->fields("limite_inf") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_ded = $total_ded + $row_con->fields("limite_inf");
				$monto = $row_con->fields("limite_inf")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				//echo " paso3 ".$row_cf->fields("monto").", ";
			}
			
			if($row_cf->fields("monto") > $row_con->fields("limite_sup") && $row_con->fields("limite_inf")!=0 && $row_con->fields("limite_sup")!=0){
				
				$total_asig = $total_asig + $row_con->fields("limite_sup");
				$monto = $row_con->fields("limite_sup")/$frecuencia;
				$sql = "INSERT INTO nomina(id_organismo, id_tipo_nomina, id_nominas, id_trabajador, id_concepto, asignacion_deduccion, cedula, monto_concepto, ultimo_usuario, fecha_actualizacion) VALUES ($_SESSION[id_organismo], ".$row_tra->fields("id_tipo_nomina").", ".$row_nominas->fields('id_nominas').",".$row_tra->fields('id_trabajador').", ".$row_con->fields('id_concepto').", '".$row_con->fields('asignacion_deduccion')."', '".$row_tra->fields('cedula')."', $monto, $_SESSION[id_usuario], '$_POST[nomina_pr_fechact]')";
				//echo " paso ".$row_cf->fields("monto").", ";
			}
			$conn->Execute($sql);
		 }
		 //
		 //
		$row_con->MoveNext();
	}
	//
	//
	//
	$sql_sso = " SELECT sso($total_asig_sso, $lunes) as result ";
				 $row_sso =& $conn->Execute($sql_sso);
				 $total_sso = $total_sso + $row_sso->fields("result");
				 $total_sso=round($total_sso*100)/100;
				 $total_sso = $total_sso / $frecuencia;

				 if($frecuencia==2)
				 	$total_sso = $total_sso*2;
				if($row_bus_sso->fields("id_concepto")!=''){	
				 $bus_sso = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_sso->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $sso =& $conn->Execute($bus_sso);
				 if($sso->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_sso WHERE id_nomina = ".$sso->fields("id_nomina")." AND id_concepto = ".$row_bus_sso->fields("id_concepto")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}
				}
				 
				 $sql_lph = " SELECT lph($total_asig_lph) as result ";
				 $row_lph =& $conn->Execute($sql_lph);
				 $total_lph = $total_lph + $row_lph->fields("result");
				 $total_lph=round($total_lph*100)/100;
				 $total_lph = $total_lph / $frecuencia;

				 if($row_bus_lph->fields("id_concepto")!=''){
				 $bus_lph = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_lph->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $lph =& $conn->Execute($bus_lph);
				 if($lph->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_lph WHERE id_nomina = ".$lph->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}
				}
				 
				 $sql_caja = " SELECT caja($total_asig_caja) as result ";
				 $row_caja =& $conn->Execute($sql_caja);
				 $total_caja = $total_caja + $row_caja->fields("result");
				 $total_caja=round($total_caja*100)/100;
				 $total_caja = $total_caja / $frecuencia;

				 if($row_bus_caja->fields("id_concepto")!=''){
				 $bus_caja = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_caja->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $caja =& $conn->Execute($bus_caja);
				 if($caja->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_caja WHERE id_nomina = ".$caja->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}
				}
				 
				 $sql_fesp = " SELECT fesp($total_asig_fesp) as result ";
				 $row_fesp =& $conn->Execute($sql_fesp);
				 $total_fesp = $total_fesp + $row_fesp->fields("result");
				 $total_fesp=round($total_fesp*100)/100;
				 $total_fesp = $total_fesp / $frecuencia;

				 if($row_bus_fesp->fields("id_concepto")!=''){
				 $bus_fesp = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_fesp->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $fesp =& $conn->Execute($bus_fesp);
				 if($fesp->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_fesp WHERE id_nomina = ".$fesp->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}
				}
				 
				 $sql_f = " SELECT fondo($total_asig_f) as result ";
				 $row_f =& $conn->Execute($sql_f);
				 $total_f = $total_f + $row_f->fields("result");
				 $total_f=round($total_f*100)/100;
				 $total_f = $total_f / $frecuencia;

				 if($row_bus_f->fields("id_concepto")!=''){
				 $bus_f = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_f->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $f =& $conn->Execute($bus_f);
				 if($f->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_f WHERE id_nomina = ".$f->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}
				}
				
				 $sql_pf = " SELECT pf($total_asig_pf, $lunes) as result ";
				 $row_pf =& $conn->Execute($sql_pf);
				 $total_pf = $total_pf + $row_pf->fields("result");
				 $total_pf=round($total_pf*100)/100;
				 $total_pf = $total_pf / $frecuencia;
				 if($frecuencia==2)
				 	$total_pf = $total_pf*2;
				if($row_bus_pf->fields("id_concepto")!=''){	
				 $bus_pf = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_pf->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $pf =& $conn->Execute($bus_pf);
				 if($pf->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_pf WHERE id_nomina = ".$pf->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}	
				}
				
				/**/
				 $sql_pp = " SELECT prima_pro($ultimo_sueldo) as result ";
				 $row_pp =& $conn->Execute($sql_pp);
				 $total_pp = $total_pp + $row_pp->fields("result");
				 $total_pp=round($total_pp*100)/100;
				 $total_pp=$total_pp/$frecuencia;
				 
				if($row_bus_pp->fields("id_concepto")!=''){	
				 $bus_pp = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_pp->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";	
				 $pp =& $conn->Execute($bus_pp);
				 if($pp->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_pp WHERE id_nomina = ".$pp->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 $conn->Execute($sql);
				}	
				}
				
				$sql_pa = " SELECT prima_antiguedad($uni_tri, $total_fechas) as result ";
				echo $sql_pa."    ";
				 $row_pa =& $conn->Execute($sql_pa);
				 $total_pa = $total_pa + $row_pa->fields("result");
				 $total_pa=round($total_pa*100)/100;
				 $total_pa=$total_pa/$frecuencia;
				
				if($row_bus_pa->fields("id_concepto")!=''){	
				 $bus_pa = "SELECT id_nomina FROM nomina WHERE id_concepto = ".$row_bus_pa->fields("id_concepto")." AND id_trabajador = ".$row_tra->fields("id_trabajador")." AND id_organismo = $_SESSION[id_organismo] ";
				 $pa =& $conn->Execute($bus_pa);
				 if($pa->fields("id_nomina")!=''){
					 $sql = "UPDATE nomina SET monto_concepto = $total_pa WHERE id_nomina = ".$pa->fields("id_nomina")." AND id_organismo = $_SESSION[id_organismo]";
					 
					 $conn->Execute($sql);
				}	
				}
				
				/**/
	//
	//
	//
echo "uni_tri: ".$uni_tri." años: ".$total_fechas." total_asig_sso: ".$total_asig_sso." lph: ".$total_asig_lph." caja: ".$total_asig_caja." fesp: ".$total_asig_fesp." f: ".$total_asig_f." pf: ".$total_asig_pf;
//echo "---- total asig: ".$total_asig." total ded: ".$total_ded;
	}
	$sql_pres = "SELECT
					prestamo.id_prestamo,
					prestamo.monto,
					prestamo.cuota,
					prestamo.saldo,
					frecuencia.descripcion as frecuencia,
					conceptos.id_concepto,
					conceptos.descripcion as concepto
				FROM
					prestamo
				INNER JOIN
					frecuencia
				ON	
					prestamo.id_frecuencia = frecuencia.id_frecuencia
				INNER JOIN	
					conceptos
				ON
					prestamo.id_concepto = conceptos.id_concepto 
				WHERE
					prestamo.id_trabajador = ".$row_tra->fields("id_trabajador")."
				AND
					prestamo.saldo > 1
				AND
					prestamo.id_organismo = $_SESSION[id_organismo] ";			
		$row_pres =& $conn->Execute($sql_pres);			
		while(!$row_pres->EOF){
			$sql_nom_pres = "SELECT 
								nomina.id_nomina,
								nomina.monto_concepto
							FROM
								nomina
							WHERE
								nomina.id_trabajador = ".$row_tra->fields("id_trabajador")."
							AND
								nomina.id_concepto = ".$row_pres->fields("id_concepto")."
							AND
								upper(nomina.asignacion_deduccion) = 'DEDUCCION'
							AND
								nomina.id_nominas = ".$row_nominas->fields("id_nominas")."
							AND
								nomina.id_organismo = $_SESSION[id_organismo]
							";	
			$row_nom_pres =& $conn->Execute($sql_nom_pres);
			if($row_nom_pres->fields("id_nomina")!=''){
			$sql = "UPDATE 
						nomina
					SET 
						monto_concepto = ".$row_pres->fields("cuota")."
					WHERE
						id_nomina = ".$row_nom_pres->fields("id_nomina")."
					AND
						id_organismo = $_SESSION[id_organismo]
					";	
			$conn->Execute($sql);		
			}
		$row_pres->MoveNext();			
	}
?>