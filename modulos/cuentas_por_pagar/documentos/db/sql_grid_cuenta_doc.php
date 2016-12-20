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

if(isset($_GET["busq_partida"]))
$busq_partida =$_GET["busq_partida"];
$where = "WHERE 1=1";

$busq_cuenta=strtoupper($_GET["busq_cuenta"]);
$busq_nom=strtoupper($_GET["busq_nom"]);
$busq_tipo=strtoupper($_GET["busq_tipo"]);


$inner="
		INNER JOIN
					cuenta_contable_contabilidad
				ON
					cuenta_contable_contabilidad.id=rel_doc_cta.id_cta_contable
		INNER JOIN
					tipo_documento_cxp
				ON
					tipo_documento_cxp.id_tipo_documento=rel_doc_cta.id_tipo					
";
if($busq_cuenta!="") $where.= " AND (cuenta_contable_contabilidad.cuenta_contable) like  '%$busq_cuenta%'";
if($busq_nom!="") $where.= " AND upper(cuenta_contable_contabilidad.nombre) like  '%$busq_nom%'";
if($busq_tipo!="") $where.= " AND upper(tipo_documento_cxp.nombre) like  '%$busq_tipo%'";

	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(rel_doc_cta.id) 
			FROM 
				rel_doc_cta 
			$inner 
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

// the actual query for the grid data
$Sql="
			SELECT 
				rel_doc_cta.id AS id_rel,
				cuenta_contable_contabilidad.id AS id_cta,
				cuenta_contable_contabilidad.cuenta_contable,
				cuenta_contable_contabilidad.nombre,
				rel_doc_cta.id_tipo AS id_tipo_doc,
				tipo_documento_cxp.nombre AS tipo
				
			FROM 
				rel_doc_cta  
			$inner		
	 		".$where."
			ORDER BY 
				id_rel 
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
//die($Sql);
$row=& $conn->Execute($Sql);
//die($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
/*NOTA:DEBIDO A QUE NO TODAS LAS CUENTAS CONTABLES ESTABAN RELACIONADAS CON LAS PRESUPUESTARIA CUAND SE HIZO EL CAMBIO TUVE Q BORRAR LAS LINEAS EN ALS Q HACIA UN INNER JOIN POR EL ID DESDE LA TABLA DE CUENTAS CONTABLES EN EL CAMPO DE ID_CUENTA_PRESUPUESTARIA Y HACER ESTAS LINEAS QUE AUNQUE ES MENOS EFICIENTE ES LA UNICA FORMA DE QUE SE MUESTREN TODAS LAS CUENTAS ESTEN O NO RELACIONADo*/
	$responce->rows[$i]['id']=$row->fields("id");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_rel"),
															$row->fields("id_cta"),
															$row->fields("cuenta_contable"),
															$row->fields("nombre"),
															$row->fields("id_tipo_doc"),
															$row->fields("tipo")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>