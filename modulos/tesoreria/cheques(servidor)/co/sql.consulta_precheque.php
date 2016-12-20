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
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
//************************************************************************

if(!$sidx) $sidx =1;
$sql_where = "WHERE
					cheques.numero_cheque < 0	
						
				AND
					cheques.id_organismo = ".$_SESSION["id_organismo"]."";

if(isset($_GET['tesoreria_busqueda_usuario_precheque']))
{
	$busq_usuario=strtoupper($_GET['tesoreria_busqueda_usuario_precheque']);
	$sql_where.= " AND  (upper(usuario.nombre) like '%$busq_usuario%')";
}
if(isset($_GET['tesoreria_busqueda_banco_precheque']))
{
	$busq_banco=strtoupper($_GET['tesoreria_busqueda_banco_precheque']);
	if($busq_banco!='')
	$sql_where.= " AND  (upper(banco.nombre) like '%$busq_banco%')";
}
if(isset($_GET['tesoreria_busqueda_cuenta_precheque']))
{
	$busq_cuenta=strtoupper($_GET['tesoreria_busqueda_cuenta_precheque']);
	if($busq_cuenta!='')
	$sql_where.= " AND  (upper(cheques.cuenta_banco) like '%$busq_cuenta%')";
}

if(isset($_GET['tesoreria_busqueda_proveedor_precheque']))
{
	$busq_proveedor=strtoupper($_GET['tesoreria_busqueda_proveedor_precheque']);
	
	if(($busq_proveedor!=""))
	{		$sql_prove="select id_proveedor from proveedor where (upper(nombre) like '%$busq_proveedor%')
			 ";
			 
			$row_prove=& $conn->Execute($sql_prove);
			if(!$row_prove->EOF)
				{
					$id_proveedor=$row_prove->fields("id_proveedor");	
					$sql_where.= " AND  (cheques.id_proveedor)='$id_proveedor'";
				}
	}
	else
			 $id_proveedor="";	
}
if(isset($_GET['tesoreria_busqueda_beneficiario_precheque']))
{
	$busq_beneficiario=strtoupper($_GET['tesoreria_busqueda_beneficiario_precheque']);
	if($busq_beneficiario!='')
	$sql_where.= " AND  (upper(cheques.nombre_beneficiario) like '%$busq_beneficiario%')";
}
$Sql="
			SELECT 
			    count(id_cheques) 
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			
		$sql_where
			";
	//
$row=& $conn->Execute($Sql);
/*,presupuesto_ley.partida,presupuesto_ley.generica,
				 presupuesto_ley.especifica,presupuesto_ley.sub_especifica*/

if (!$row->EOF)
{
	$count = $row->fields("count");
	
}
$limit = 15;
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
$Sql="  SELECT  distinct
			    cheques.numero_cheque,
				cheques.secuencia,
				cheques.cuenta_banco,
				cheques.id_banco,
				banco.nombre AS banco,
				cheques.monto_cheque,
				cheques.id_proveedor,
				cheques.nombre_beneficiario,
				cheques.cedula_rif_beneficiario,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				cheques.ordenes,
				cheques.tipo_cheque,
				cheques.estatus	 
			FROM 
				cheques
			INNER JOIN 
				organismo 
			ON 
				cheques.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				cheques.id_banco=banco.id_banco
			INNER JOIN 
				usuario 
			ON 
				cheques.usuario_cheque = usuario.id_usuario
			".$sql_where."
			ORDER BY 
				banco.nombre,cheques.cuenta_banco,cheques.secuencia
					LIMIT 
				$limit 
					OFFSET 
				$start 		
			 
			 ";
/*LIMIT 
				$limit 
			OFFSET 
				$start
			/**/
$row=& $conn->Execute($Sql);
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
//////////////////////////////////
	$id_prove=$row->fields("id_proveedor");
	if(($id_prove=="")||($id_prove==NULL)||($id_prove=='0'))
	{	
		$proveedor=$row->fields("nombre_beneficiario");
	}
	else
	{
		$sql_proveedor="select * from proveedor where id_proveedor='$id_prove'";
		$row_proveedor=& $conn->Execute($sql_proveedor);
		$proveedor=$row_proveedor->fields("nombre");
	}
/////////////////////
	$nom=$row->fields("nombre");
	$ape=$row->fields("apellido");
	$nombre=$nom."  ". $ape;
		if ($row->fields("estatus")=="1")
		$estatus="Activo";
	else
	if ($row->fields("estatus")=="2")
			$estatus="Inactivo";	
	$responce->rows[$i]['id']=$row->fields("id_banco_cuentas");

	$responce->rows[$i]['cell']=array(		
											$nombre,
											$proveedor,
											$row->fields("id_banco"),
											$row->fields("banco"),
											$row->fields("cuenta_banco"),
        									$row->fields("numero_cheque"),
											$row->fields("ordenes"),	 
											number_format($row->fields("monto_cheque"),2,',','.')									
											);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);
?>