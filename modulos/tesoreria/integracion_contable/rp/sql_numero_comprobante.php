<?php
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

$busq=strtoupper($_GET["busq"]);
$where = " WHERE 1=1 ";

//if($busq!="") $where.= " AND ano = $busq";
	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
		select  
				count (distinct(integracion_contable.numero_comprobante)) 
		from 
				integracion_contable  
		INNER JOIN
				tipo_comprobante
		ON	
				integracion_contable.id_tipo_comprobante=tipo_comprobante.id		
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
//VERIFICANDO EL ID DEL TIPO DE DOCUMENTO
$sql_tipo_doc="select id from tipo_comprobante where upper(nombre)='EMISION DE CHEQUES' ";

$row_tipo_doc=& $conn->Execute($sql_tipo_doc);
if(!$row_tipo_doc->EOF)
	{
		$id_tipo=$row_tipo_doc->fields("id");
	}
//FIN DE VERIFICACION
$Sql="
		select 
				distinct integracion_contable.numero_comprobante
			from 
				integracion_contable
			INNER JOIN
				tipo_comprobante
		ON	
				integracion_contable.id_tipo_comprobante=tipo_comprobante.id	
		where tipo_comprobante.id='$id_tipo'		
		order by integracion_contable.numero_comprobante
		LIMIT 
				$limit 
			OFFSET 
				$start
";

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															substr($row->fields("numero_comprobante"),8),
															$row->fields("numero_comprobante")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>