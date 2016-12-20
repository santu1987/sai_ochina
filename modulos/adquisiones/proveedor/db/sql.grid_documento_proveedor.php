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
			SELECT  DISTINCT
				count(proveedor.id_proveedor) 
			FROM 
				proveedor 
			INNER JOIN 
				organismo 
			ON 
				proveedor.id_organismo = organismo.id_organismo
			WHERE
				(proveedor.id_organismo = ".$_SESSION['id_organismo'].")
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
$id=$_GET['id'];

$where = "WHERE  (id_proveedor='$id') ";

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
			SELECT  DISTINCT
			documento_proveedor.id_documento_proveedor,
			documento_proveedor.estatus
				FROM 
				documento_proveedor 
			INNER JOIN 
				proveedor
			ON 
			documento_proveedor.id_proveedor = proveedor.id_proveedor
     		INNER JOIN 
				documento
			ON 
			documento_proveedor.id_documento = documento.id_documento_proveedor
     	   INNER JOIN 
				organismo 
			ON 
			documento_proveedor.id_organismo = organismo.id_organismo
	    	WHERE
				(documento_proveedor.id_organismo = ".$_SESSION['id_organismo'].")
			AND
				(documento_proveedor.id_proveedor =".$id.")
			ORDER BY 
				documento_proveedor.id_documento_proveedor
";
		/*	SELECT 
				*
			FROM 
				documento 
            ".$where."
	        ORDER BY 
				documento.codigo_documento
			 LIMIT 
				$limit 
			 OFFSET 
				$start 
		  	 ";*/

$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

if(!$row->EOF)
{			
			//$responce->rows[$i]['id']=$row->fields("documento_proveedor.id_documento_proveedor");
			while(!$row->EOF) 
			{
				$responce->rows[$i]['id']=$row->fields("id_documento_proveedor");
				$responce->rows[$i]['cell']=array("*".$row->fields("id_documento_proveedor")."*".$row->fields("estatus")."*");
																	//	);
				//$responce[$i] =$row->fields("documento_proveedor.id_documento_proveedor").".".$row->fields("estatus").".";
				/*$responce->rows[$i]['id']=$row->fields("documento_proveedor_id_documento_proveedor");
				$responce->rows[$i]['cell']=array(	
																		//$row->fields("documento_proveedor_id_documento_proveedor"),
																	$row->fields("estatus")*/
																	//	);
			  $row->MoveNext();
			  $i++;
			  }
	
 //echo($responce);  

 }
 else
  	{  die("error");
		$responce="";
	   }
	  
	echo $json->encode($responce);  

?>