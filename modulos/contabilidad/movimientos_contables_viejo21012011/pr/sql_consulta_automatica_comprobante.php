<?php session_start();
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');
$db=dbconn("pgsql");
$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
//************************************************************************
//HACIENDO LLAMADO A CONTROLES
//************************************************************************
//************************************************************************
//VARIABLES DE PAGINACION
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$where="and integracion_contable.id='$id'"; 
}
else
$where=" and 1=1";
$where.="and integracion_contable.ultimo_usuario='".$_SESSION['id_usuario']."'";
/*** '".$_SESSION['id_usuario']."'";**/
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$Sql2="		SELECT 
						count(distinct(integracion_contable.numero_comprobante)) 
					FROM 
						integracion_contable 
					inner join
						organismo
					on
						integracion_contable.id_organismo=integracion_contable.id_organismo
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
							$where
					";	 
					$row2=& $conn->Execute($Sql2);
	if (!$row2->EOF)
	{
		$count = $row2->fields("count");
	}
	// calculation of total pages for the query
	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	} 
	else
	{
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
	$Sql_int="
				SELECT distinct
					integracion_contable.numero_comprobante
				FROM 
					integracion_contable
				inner join
						organismo
				on
				integracion_contable.id_organismo=integracion_contable.id_organismo
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				ORDER BY 
					 integracion_contable.numero_comprobante
				LIMIT 
					$limit 
				OFFSET 
					$start ";
	$row_int=& $conn->Execute($Sql_int);
	//die($Sql_int);
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if (!$row_int->EOF) 
	{
			$numero_comprobante=$row_int->fields("numero_comprobante");
			$sql_sumas=" SELECT
							SUM(monto_debito) as debe,
							SUM(monto_credito) as haber
							
						from
							integracion_contable
						where numero_comprobante='$numero_comprobante'";
											
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
			}
		$Sql3="SELECT 
					integracion_contable.id,
					integracion_contable.id_organismo,
					integracion_contable.ano_comprobante,
					integracion_contable.mes_comprobante,
					integracion_contable.id_tipo_comprobante,
					integracion_contable.secuencia,
					integracion_contable.cuenta_contable,
					integracion_contable.descripcion,
					integracion_contable.referencia,
					integracion_contable.debito_credito,
					integracion_contable.monto_debito,
					integracion_contable.monto_credito,
					integracion_contable.fecha_comprobante,
					integracion_contable.id_auxiliar,
					integracion_contable.id_unidad_ejecutora,
					integracion_contable.id_proyecto,
					integracion_contable.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					tipo_comprobante.codigo_tipo_comprobante,
					integracion_contable.comentario		
					
				FROM 
					integracion_contable
				inner join
						organismo
						on
						integracion_contable.id_organismo=integracion_contable.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				integracion_contable.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
				inner join
					tipo_comprobante
				on
					tipo_comprobante.id=integracion_contable.id_tipo_comprobante				
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				AND
					integracion_contable.numero_comprobante='$numero_comprobante'
				ORDER BY 
					 integracion_contable.id,integracion_contable.numero_comprobante
				LIMIT 
					$limit 
				OFFSET 
					$start ";
					//die($Sql3);
		$row3=& $conn->Execute($Sql3);
		////////////////codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_uejecutora=$row3->fields("id_unidad_ejecutora");
							 if($id_uejecutora!=0)
							 {
								 $sql_ut="select codigo_unidad_ejecutora  from unidad_ejecutora where id_unidad_ejecutora='$id_uejecutora' AND	unidad_ejecutora.id_organismo = $_SESSION[id_organismo]";									
								 $row_ut=& $conn->Execute($sql_ut);
								 if(!$row_ut->EOF)
								 	 $codigo_uejecutora=$row_ut->fields("codigo_unidad_ejecutora");
							 	else
									$codigo_uejecutora='0';	
							 }else
							 if($id_uejecutora==0)
							 {
							  $codigo_uejecutora='0';
							 }
							 
							//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_utf=$row3->fields("id_utilizacion_fondos");
							 if($id_utf!=0)
							 {
								 $sql_ut2="select cuenta_utilizacion_fondos from utilizacion_fondos where id_utilizacion_fondos='$id_utf' AND	utilizacion_fondos.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut2=& $conn->Execute($sql_ut2);
								if(!$row_ut2->EOF)	
								 $cuenta_utf=$row_ut2->fields("cuenta_utilizacion_fondos");
								else
								$cuenta_utf='0';
							 }else
							 $cuenta_utf='0';
			//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_auxiliar=$row3->fields("id_auxiliar");
							 if($id_auxiliar!=0)
							 {
								 $sql_ut3="select cuenta_auxiliar from auxiliares where id_auxiliares='$id_auxiliar' AND	id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut3=& $conn->Execute($sql_ut3);
								 //die($sql_ut3);
								 if(!$row_ut3->EOF)
								 $cuenta_auxiliar=$row_ut3->fields("cuenta_auxiliar");
							 		else
									$cuenta_auxiliar='0';
							 }else
							 $cuenta_auxiliar='0';				 
			//////////////////////////////////////////////////////////////////
			$id_proyecto=$row3->fields("id_proyecto");
			$codigo_proyecto=0;
			$codigo_acentral=0;
				$responce->row3[$i]['id']=$row3->fields("id");
	$responce =$row3->fields("id")."*".  $row3->fields("codigo_organismo")."*". $row3->fields("ano_comprobante")."*". $row3->fields("mes_comprobante")."*". $row3->fields("codigo_tipo_comprobante")."*". $row3->fields("numero_comprobante")."*". $row3->fields("secuencia")."*". $row3->fields("comentarios")."*". $row3->fields("cuenta_contable")."*". $row3->fields("descripcion")."*". $row3->fields("referencia")."*". $debe."*". $haber."*". $row3->fields("fecha_comprobante")."*". $row3->fields("id_auxiliar")."*". $row3->fields("id_unidad_ejecutora")."*". $row3->fields("id_proyecto")."*". $row3->fields("id_utilizacion_fondos")."*". $row3->fields("codigo_tipo_comprobante")."*". $row3->fields("id_cc")."*". $codigo_uejecutora."*".$cuenta_utf."*".$cuenta_auxiliar;
				echo ($responce);
		 
	}


	// return the formated data
echo $json->encode($responce);

?>