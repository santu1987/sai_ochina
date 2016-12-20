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
$where="WHERE 1=1 ";
 if(isset($_GET['busq_cuenta']))
 {
	 $cuenta=strtoupper($_GET['busq_cuenta']);
	if($cuenta!="")
	{
	 $where.=" AND upper(cuenta) like '%$cuenta%'
	 		   AND chequeras.id_organismo=$_SESSION[id_organismo]";	
	}
}
 if(isset($_GET['busq_banco']))
 {
	 if($_GET['busq_banco']!="")
	 {
		 $banco=strtoupper($_GET['busq_banco']);
		 $where.=" AND upper(banco.nombre) like '%$banco%'";
	}	
}
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
$Sql="
			SELECT 
				count(chequeras.id_chequeras) 
			FROM 
				chequeras
			INNER JOIN 
				organismo 
			ON 
				chequeras.id_organismo = organismo.id_organismo
			INNER JOIN 
				banco
			ON 
				chequeras.id_banco = banco.id_banco
			$where

				";
//die($Sql);				
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
				chequeras.id_chequeras,
				chequeras.id_organismo,
				banco.id_banco,
				banco.nombre as banco,
				chequeras.cuenta,
				chequeras.secuencia,
				chequeras.primer_cheque,
				chequeras.ultimo_emitido,
				chequeras.cantidad_cheques,
				chequeras.cantidad_emitidos,
				chequeras.estatus,
				chequeras.comentarios		
			FROM 
				chequeras
			INNER JOIN 
				banco
			ON 
				chequeras.id_banco = banco.id_banco
			INNER JOIN 
				organismo 
			ON 
				chequeras.id_organismo = organismo.id_organismo
			
			$where		 
			ORDER BY
				banco.nombre,chequeras.cuenta,chequeras.estatus							
";
	//die($Sql);			
$row=& $conn->Execute($Sql);
// constructing a JSON
//	(organismo.id_organismo =".$_SESSION['id_organismo'].")
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while (!$row->EOF) 
{
	if ($row->fields("estatus")=="1")
			$estatus="Activo";
	else
		if ($row->fields("estatus")=="2")
				$estatus="Inactivo";
		else
			if ($row->fields("estatus")=="3")
				$estatus="Agotada";		
	$primer=strlen($row->fields("primer_cheque"));
	$ultimo=strlen($row->fields("ultimo_emitido"));
	$primer_c=$row->fields("primer_cheque");
	$ultimo_c=$row->fields("ultimo_emitido");
		
						switch($primer)
									{
										case 1:
										$primer_c='00000'.$primer_c;
										break;
										case 2:
										$primer_c='0000'.$primer_c;
										break;
										case 3:
										$primer_c='000'.$primer_c;
										break;
										case 4:
										$primer_c='00'.$primer_c;
										break;
										case 5:
										$primer_c='0'.$primer_c;
										break;
										case 6:
										$primer_c=$primer_c;
										break;
										
									}
						switch($ultimo)
									{
										case 1:
										$ultimo_c='00000'.$ultimo_c;
										break;
										case 2:
										$ultimo_c='0000'.$ultimo_c;
										break;
										case 3:
										$ultimo_c='000'.$ultimo_c;
										break;
										case 4:
										$ultimo_c='00'.$ultimo_c;
										break;
										case 5:
										$ultimo_c='0'.$ultimo_c;
										break;
										case 6:
										$ultimo_c=$ultimo_c;
										break;
										
									}	
	$responce->rows[$i]['id']=$row->fields("id_chequeras");

	$responce->rows[$i]['cell']=array(	
															$row->fields("id_chequeras"),
															$row->fields("id_organismo"),
															$row->fields("id_banco"),
															$row->fields("banco"),
															$row->fields("cuenta"),	
															$row->fields("secuencia"),	
															$primer_c,													
															$ultimo_c,
															$row->fields("cantidad_cheques"),
															$row->fields("cantidad_emitidos"),
															$estatus,
															$row->fields("comentarios")
														);
	$i++;
	$row->MoveNext();
}
// return the formated data
echo $json->encode($responce);

?>