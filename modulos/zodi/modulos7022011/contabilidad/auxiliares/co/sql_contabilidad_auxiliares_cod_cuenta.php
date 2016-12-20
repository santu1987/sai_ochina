<?php
session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

//************************************************************************
//									HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
	//$fecha=date("d/m/Y");
//	list($dia,$mes,$ayo)=split("/",$fecha,3);
	$where="where	saldo_contable.ano=$ayo";	
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//-

$saldo_auxi_desde=$_GET['fecha_desde'];
$saldo_auxi_hasta=$_GET['fecha_hasta'];
list($dia,$mes,$ayo)=split("/",$saldo_auxi_hasta,3);
list($dia,$mes_ant,$ayo)=split("/",$saldo_auxi_desde,3);
/*$desde_fecha=split("/",$saldo_auxi_desde);				
$hasta_fecha=split("/",$saldo_auxi_hasta);	*/			
/*$mes=$hasta_fecha[3];
$mes_ant=$desde_fecha[3];*/
//-
//************************************************************************
if (isset($_GET['busq_nombre']))
$busq_nombre = strtolower($_GET['busq_nombre']);
if (isset($_GET['busq_cod']))
$busq_cod = strtolower($_GET['busq_cod']);

$where = " WHERE 1=1 ";
if ($busq_nombre!='')
$where.= " AND lower(auxiliares.nombre) like '%$busq_nombre%' ";
if ($busq_cod!='')
$where.= " AND (auxiliares.cuenta_auxiliar) like '%$busq_cod%' ";
$cuenta_contable=$_POST['saldo_aux_cuenta_id'];
if($cuenta_contable!='')
$where.="AND cuenta_contable=$cuenta_contable";

$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(auxiliares.id_auxiliares) 
			FROM 
				auxiliares
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo ".$where;
$row=& $conn->Execute($Sql);
if (!$row->EOF)
{
	$count = $row->fields("count");
}

// calculation of total pages for the query
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} 
else {
	$total_pages = 0;
}

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
// if for some reasons start position is negative set it to 0
// typical case is that the user type 0 for the requested page
if($start <0) $start = 0;

// the actual query for the grid data
$Sql="
			SELECT 
				auxiliares.id_auxiliares,
				auxiliares.cuenta_contable,
				auxiliares.cuenta_auxiliar, 
				auxiliares.id_organismo,
				auxiliares.nombre,
				auxiliares.comentarios,
				saldo_auxiliares.debe,
				saldo_auxiliares.haber,
				saldo_auxiliares.saldo_inicio,
				naturaleza_cuenta.codigo  AS codigo,
				cuenta_contable_contabilidad.nombre AS desc_cuenta,
				cuenta_contable_contabilidad.cuenta_contable as cc
	
			FROM 
				auxiliares 
			INNER JOIN 
				organismo 
			ON 
				auxiliares.id_organismo = organismo.id_organismo
			INNER JOIN
				saldo_auxiliares
			ON
				saldo_auxiliares.cuenta_auxiliar = auxiliares.id_auxiliares
			INNER JOIN
				rel_aux_cont
			ON
				auxiliares.id_auxiliares=rel_aux_cont.id_auxiliar	
			INNER JOIN 
				cuenta_contable_contabilidad
			ON
				rel_aux_cont.id_contab=cuenta_contable_contabilidad.id		
			INNER JOIN
				naturaleza_cuenta
			ON
				cuenta_contable_contabilidad.id_naturaleza_cuenta=naturaleza_cuenta.id	
			".$where;
		//	die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
//$mes=date('m');
//$mes=$mes-1;
while (!$row->EOF) 
{
//////////////////////////////////////-calculos de saldo-//////////////////////////////////////////////////////////////////////////////
	$conter=0;
	//$mes_ant=$mes;
	//$mes_ant=$mes_ant-1;
	$debe_total=0;
	$haber_total=0;
	$total_cuenta_debe_haber="";
	$cuenta_sumas="";
//////////////////////////////////////////////variables
	$med=strlen($row->fields("debe"));
	$med=$med-2;
	$debe=substr($row->fields("debe"),1,$med);
	$debe_vector=split(",",$debe);
	
	$med2=strlen($row->fields("haber"));
	$med2=$med2-2;
	$haber=substr($row->fields("haber"),1,$med2);
	$haber_vector=split(",",$haber);
	$saldo_inicio=$row->fields("saldo_inicio");
	$saldo_vector=split(",",$saldo_inicio);				
	//calculando el monto del saldo anterior	
		while($conter!=$mes_ant)
		{
			$debe_total=$debe_total+$debe_vector[$conter];
			$haber_total=$haber_total+$haber_vector[$conter];
			$conter++;
		}
		if(($row->fields("codigo")=='A   ')||($row->fields("codigo")=='G'))
		{
			$total_cuenta_debe_haber=$debe_total-$haber_total;
			
		}
		else
		if(($row->fields("codigo")=='P   ') ||($row->fields("codigo")=='PAT')or($row->fields("codigo")=='I'))
		{
			$total_cuenta_debe_haber=$haber_total-$debe_total;
		}
					if($total_cuenta_debe_haber==0)
					{
					//
					$conter=0;
					while($conter!=$mes_ant)
							{
								$saldo_inicio=$saldo_inicio+$saldo_vector[$conter];
								$conter++;
							}
							if($saldo_inicio==0)
							{
								$saldo_inicio=$saldo_vector[$mes-1];	
							}	
					$total_cuenta_debe_haber=$saldo_inicio;		
					}
		/////////////////////calculando valores debe/haber
		$conter=0;$debe_total2=0;$haber_total2=0;
		while($conter!=$mes)
		{
			$debe_total2=$debe_total2+$debe_vector[$conter];
			$haber_total2=$haber_total2+$haber_vector[$conter];
			$conter++;
		}
		$saldo_movimientos=$debe_total2-$haber_total2;
		$saldo_actual=$saldo_movimientos-$total_cuenta_debe_haber;
		////////////////////////////////////////////////////			
////////////
	$responce->rows[$i]['id']=$row->fields("id_auxiliares");

	$responce->rows[$i]['cell']=array(	
															
															$row->fields("id_auxiliares"),
															$row->fields("cuenta_auxiliar"),
															$row->fields("nombre"),
															$row->fields("cc"),
															$row->fields("desc_cuenta"),
															number_format($debe_total2,2,',','.'),
															number_format($haber_total2,2,',','.'),																																				number_format($total_cuenta_debe_haber,2,',','.'),
															number_format($saldo_movimientos,2,',','.'),
																														number_format($saldo_actual,2,',','.'),
															$desde_fecha,
															$hasta_fecha															

															);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>