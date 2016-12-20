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
$where = "WHERE 1=1";
if($busq!="") $where.= " AND upper(nombre) like  '%$busq%'";
//**********************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(id) 
			FROM 
				tipo_comprobante 
			$where	
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
/////////////////////////////////
$busq_cod=$_GET[busq_cod];
if($busq_cod!="")
{
	$where.=" AND codigo_tipo_comprobante='$busq_cod'";
}
$busq_denom=strtoupper($_GET[busq_denom]);
if($busq_denom!="")
{
	$where.=" AND upper(nombre)like'%$busq_denom%'";
}
/////////////////////////////////
// the actual query for the grid data
$Sql="
			SELECT 
				id,
				codigo_tipo_comprobante,
				nombre,
				numero_comprobante,
				comentario
			FROM 
				tipo_comprobante 
			".$where."
			ORDER BY 
			codigo_tipo_comprobante::integer,
				nombre  
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
//die($Sql);
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
if(($row->fields("numero_comprobante")!="")&&($row->fields("numero_comprobante")!="0000"))
$numero_comprobante=$row->fields("numero_comprobante")+1.00;

if($row->fields("numero_comprobante")=="0000")
$numero_comprobante="0001";

				$valor_medida=strlen($numero_comprobante);													//echo($numero_comprobantex3);

												//	echo($valor_medida);
												if($valor_medida==1)
												{
													$numero_comprobante="000".$numero_comprobante;
												}
												else
												if($valor_medida==2)
												{
													$numero_comprobante="00".$numero_comprobante;
												}
												else	
												if($valor_medida==3)
												{
															$numero_comprobante="0".$numero_comprobante;
												}
												
$med=strlen($row->fields("codigo_tipo_comprobante"));
										if($med==1)
										{
											$codigo_tipo_comprobante="0".$row->fields("codigo_tipo_comprobante");
										}else
										$codigo_tipo_comprobante=$row->fields("codigo_tipo_comprobante");
//
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id"),
															$codigo_tipo_comprobante,
															$row->fields("nombre"),
															$row->fields("comentario"),
															$numero_comprobante	
														);
	$i++;
	$numero_comprobante="";
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>