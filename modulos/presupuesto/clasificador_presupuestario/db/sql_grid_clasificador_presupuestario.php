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
$busq_nombre=strtoupper($_GET["busq_nombre"]);
$where = "WHERE 1=1";
if($busq_partida!='')
	{	
	   if($busq_partida!='')
	{	
	   
			$partida =substr($busq_partida,0,3);
		if ($partida!=FALSE) $where.= " AND partida like '%$partida%'";
		
		$generica =substr($busq_partida,3,2);
		if ($generica!=FALSE) $where.= " AND generica like '%$generica%'";
		
		$especifica=substr($busq_partida,5,2);
		if ($especifica!=FALSE)$where.= " AND especifica like '%$especifica%'";
		
		$sub_especifica =substr($busq_partida,7,2);
		if ($sub_especifica!=FALSE)	$where.= " AND subespecifica like '%$sub_especifica%'";
	}	
			/*$partida =partida.split(".");
			if($partida!=FALSE)
			{
			 	if($partida[0]!=FALSE)$where.= " AND partida like '$partida[0]' ";
			}*/
		
	}
if($busq_nombre!="")
	{
	$where.= " AND upper(denominacion) like  '%$busq_nombre%'";
	
	}	
		
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;

$Sql="
			SELECT 
				count(clasificador_presupuestario.id_clasi_presu) 
			FROM 
				clasificador_presupuestario 
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
				*
			FROM 
				clasificador_presupuestario 
            
			".$where."
			ORDER BY 
				partida, generica, especifica, subespecifica,denominacion
			LIMIT 
				$limit 
			OFFSET 
				$start ;
";
$row=& $conn->Execute($Sql);
// constructing a JSON
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
/////////////////// relacion con cuentas contable
///////////////////////////////////////////////////////////////
$cuenta_contable=$row->fields("cuenta_contable");
	if($cuenta_contable!='')
	{
		$Sql_cuenta_cont="
					SELECT 
					cuenta_contable_contabilidad.nombre as nombre_cuenta,cuenta_contable_contabilidad.id as id_contab
					FROM 
						
						cuenta_contable_contabilidad
					where
						cuenta_contable='$cuenta_contable'
					 ";
		$row_cont=& $conn->Execute($Sql_cuenta_cont);
		if (!$row_cont->EOF) 
			{
				$desc_cuenta=$row_cont->fields("nombre_cuenta");
				$id_cuenta=$row_cont->fields("id_contab");
			}
			else
			{
				$desc_cuenta="";
				$id_cuenta="";
			}
	}
		else
			{
				$desc_cuenta="";
				$id_cuenta="";
			}
///////////////////////////////////////////////////////////////	

/////////////////////////////////////////////////////////////////////	
	
if ($row->fields("grupo")=='3')
	$nombreGrupo = "Recursos";
if ($row->fields("grupo")=='4')
	$nombreGrupo = "Egresos";

$partidas =$row->fields("partida").".".$row->fields("generica").".".$row->fields("especifica").".".$row->fields("subespecifica");
	
	$responce->rows[$i]['id']=$row->fields("id_clasi_presu");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_clasi_presu"),
															$partidas,
															$nombreGrupo,
															$row->fields("denominacion"),
															$row->fields("grupo"),
															$row->fields("partida"),
															$row->fields("generica"),
															$row->fields("especifica"),
															$row->fields("subespecifica"),
															$row->fields("tipo"),
															$row->fields("cuenta_contable"),
															$row->fields("comentario"),
															$desc_cuenta,
															$id_cuenta
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>