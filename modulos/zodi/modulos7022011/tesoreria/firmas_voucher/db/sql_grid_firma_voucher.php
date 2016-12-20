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
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(firmas_voucher.id_firmas_voucher) 
			FROM 
				firmas_voucher
			INNER JOIN 
				organismo 
			ON 
				firmas_voucher.id_organismo = organismo.id_organismo
			WHERE
				firmas_voucher.id_organismo=$_SESSION[id_organismo]			
			
	";
	
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
				firmas_voucher.id_firmas_voucher,
				firmas_voucher.codigo_director_ochina,
				firmas_voucher.codigo_director_administracion,
				firmas_voucher.codigo_jefe_finanzas,
				firmas_voucher.codigo_preparado_por,
				firmas_voucher.comentarios,
				firmas_voucher.fecha_firma,
				firmas_voucher.estatus
			FROM 
				firmas_voucher
			INNER JOIN 
				organismo 
			ON 
				firmas_voucher.id_organismo = organismo.id_organismo
			WHERE
				firmas_voucher.id_organismo=$_SESSION[id_organismo]	
";			
 					
$row=& $conn->Execute($Sql);


// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	/*$ayos=$row->fields("ayo_mes");
	$ayos2=split("/",$ayos);
	$ayo_mes=$ayos2[1]."/".$ayos2[0];
	*///
	if($row->fields("estatus")=='1')
	{
		$estatus='ACTIVO';
		
	}else
	if($row->fields("estatus")=='2')
	{
		$estatus='INACTIVO';
		
	}
	//
		$codigo_director=$row->fields("codigo_director_ochina");
		$codigo_administracion=$row->fields("codigo_director_administracion");
		$codigo_jefe_finanzas=$row->fields("codigo_jefe_finanzas");
		$preparado=$row->fields("codigo_preparado_por");
		
		$sql=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_director";
		$row_director=& $conn->Execute($sql);
		$nom_director=$row_director->fields("nombre");
		$ape_director=$row_director->fields("apellido");
		$nombre_director=$nom_director."  ". $ape_director;	
		 
		$sql2=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_administracion";
		$row_administrador=& $conn->Execute($sql2);
		$nom_administrador=$row_administrador->fields("nombre");
		$ape_administrador=$row_administrador->fields("apellido");
		$nombre_administrador=$nom_administrador."  ". $ape_administrador;
		
		$sql3=" SELECT	 nombre,apellido from usuario where id_usuario=$codigo_jefe_finanzas";
		$row_jefe=& $conn->Execute($sql3);
		$nom_jefe=$row_jefe->fields("nombre");
		$ape_jefe=$row_jefe->fields("apellido");
		$nombre_jefe=$nom_jefe."  ". $ape_jefe;
	//
	$responce->rows[$i]['id']=$row->fields("id_firmas_voucher");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_firmas_voucher"),
															$row->fields("codigo_director_ochina"),
															$nombre_director,
															//$row_director->fields("nombre"),
															$row->fields("codigo_director_administracion"),
         													$nombre_administrador,
															//$row_administrador->fields("nombre"),
															$row->fields("codigo_jefe_finanzas"),
															$nombre_jefe,
															$row->fields("comentarios"),
															$row->fields("fecha_firma"),
															$estatus,
																													
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>