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
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$Sql2="
					SELECT 
						count(integracion_contable.id) 
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
					//die($Sql2);
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
				SELECT 
					integracion_contable.id,
					integracion_contable.id_organismo,
					integracion_contable.ano_comprobante,
					integracion_contable.mes_comprobante,
					integracion_contable.id_tipo_comprobante,
					integracion_contable.numero_comprobante,
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
					integracion_contable.id_accion_central,
					integracion_contable.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id as id_cc,
					tipo_comprobante.codigo_tipo_comprobante,
					cuenta_contable_contabilidad.requiere_auxiliar,
					cuenta_contable_contabilidad.requiere_unidad_ejecutora,
					cuenta_contable_contabilidad.requiere_proyecto,
					cuenta_contable_contabilidad.requiere_utilizacion_fondos		
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
				ORDER BY 
					 integracion_contable.id
				LIMIT 
					$limit 
				OFFSET 
					$start ";
			//		die($Sql_int);
	$row_int=& $conn->Execute($Sql_int);
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if (!$row_int->EOF) 
	{
			////////////////codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_uejecutora=$row_int->fields("id_unidad_ejecutora");
							 if($id_uejecutora!=0)
							 {
								 $sql_ut="select nombre as nombre_ue,codigo_unidad_ejecutora  from unidad_ejecutora where id_unidad_ejecutora='$id_uejecutora' AND	unidad_ejecutora.id_organismo = $_SESSION[id_organismo]";									
								 $row_ut=& $conn->Execute($sql_ut);
								 if(!$row_ut->EOF)
								 {
								 	 $codigo_uejecutora=$row_ut->fields("codigo_unidad_ejecutora");
									 $nombre_uejecutora=$row_ut->fields("nombre_ue");
							 	 }	
								else
								{
									$codigo_uejecutora='';	
							 		$nombre_uejecutora='';
								}
							 }else
							 if($id_uejecutora==0)
							 {
							  $codigo_uejecutora='';
							 }
							 
			//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_utf=$row_int->fields("id_utilizacion_fondos");
							 if($id_utf!=0)
							 {
								 $sql_ut2="select nombre as nombre_utf,cuenta_utilizacion_fondos from utilizacion_fondos where id_utilizacion_fondos='$id_utf' AND	utilizacion_fondos.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut2=& $conn->Execute($sql_ut2);
								if(!$row_ut2->EOF)	
								{
								 	$cuenta_utf=$row_ut2->fields("cuenta_utilizacion_fondos");
									$nombre_utf=$row_ut2->fields("nombre_utf");
									
								}
								else
								{
									$cuenta_utf='';
									$nombre_utf='';
							 	}
							 }else
							 {
								 $cuenta_utf='';
								 $nombre_utf='';
							  }		
//////////////// verificando si la partida coincide con el codigo ut//////////////////////////////////////////////////////////
							 $id_auxiliar=$row_int->fields("id_auxiliar");
							 if($id_auxiliar!=0)
							 {
								 $sql_ut3="select nombre as aux_nombre,cuenta_auxiliar from auxiliares where id_auxiliares='$id_auxiliar' AND	id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut3=& $conn->Execute($sql_ut3);
								 //die($sql_ut3);
								 if(!$row_ut3->EOF)
								 {
								 	$cuenta_auxiliar=$row_ut3->fields("cuenta_auxiliar");
									$aux_nombre=$row_ut3->fields("aux_nombre");
							 	 }	
									else
									{
									 	$cuenta_auxiliar='';
										$aux_nombre='';
							         }
							 }else
							 {
							 	$cuenta_auxiliar='';
								$aux_nombre='';
							  }						 
//////////////////////////////////////////////////////////////////
							 $id_proyecto=$row_int->fields("id_proyecto");
							 if($id_proyecto!=0)
							 {
								 $sql_ut3="select nombre as nombre_proyecto,codigo_proyecto from proyecto where id_proyecto='$id_proyecto' AND	proyecto.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut3=& $conn->Execute($sql_ut3);
								if(!$row_ut3->EOF)	
								{
								 	$cod_proyecto=$row_ut3->fields("codigo_proyecto");
									$nombre_proyecto=$row_ut3->fields("nombre_proyecto");
									$cod_acc='0';
									$nombre_acc='NO APLICA';
								}	
									else
									{
										$cod_proyecto='';
										$nombre_proyecto='';
									}	
							 }else
							 {
							 	$cod_proyecto='';
								$nombre_proyecto='';
							}
		//////////////////////////////////////////////////////////////////
							 $id_acc=$row_int->fields("id_accion_central");
							 if($id_acc!=0)
							 {
								 $sql_ut4="select denominacion,codigo_accion_central from accion_centralizada where id_accion_central='$id_acc' AND	accion_centralizada.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut4=& $conn->Execute($sql_ut4);
								if(!$row_ut4->EOF)	
								{
								 	$cod_acc=$row_ut4->fields("codigo_accion_central");
									$nombre_acc=$row_ut4->fields("denominacion");
									$cod_proyecto='0';
									$nombre_proyecto='NO APLICA';
										
								}
								else
								{
									$cod_acc='';
									$nombre_acc='';
								}
							}	
	/*$sql="SELECT numero_comprobante FROM numeracion_comprobante ";
	$rs_comprobante =& $conn->Execute($sql);
	$comprobante=$rs_comprobante->fields("numero_comprobante")+1;*/

		
		$responce->rows[$i]['id']=$row_int->fields("id");
		$responce =$row_int->fields("id")."*".$row_int->fields("id_organismo")."*".$row_int->fields("ano_comprobante")."*".$row_int->fields("mes_comprobante")."*".$row_int->fields("id_tipo_comprobante")."*".$row_int->fields("numero_comprobante")."*".$row_int->fields("secuencia")."*".$row_int->fields("comentarios")."*".$row_int->fields("cuenta_contable")."*".$row_int->fields("descripcion")."*".$row_int->fields("referencia")."*".number_format($row_int->fields("monto_debito"),2,',','.')."*".number_format($row_int->fields("monto_credito"),2,',','.')."*".$row_int->fields("id_auxiliar")."*".$row_int->fields("id_unidad_ejecutora")."*".$row_int->fields("id_proyecto")."*".$row_int->fields("id_utilizacion_fondos")."*".$row_int->fields("id_cc")."*".$row_int->fields("codigo_tipo_comprobante")."*".$codigo_uejecutora."*".$cuenta_utf."*".$cuenta_auxiliar."*".$cod_proyecto."*".$id_acc."*".$cod_acc."*".$row_int->fields("requiere_auxiliar")."*".$row_int->fields("requiere_unidad_ejecutora")."*".$row_int->fields("requiere_proyecto")."*".$row_int->fields("requiere_utilizacion_fondos")."*".$nombre_uejecutora."*".$nombre_utf."*".$aux_nombre."*".$nombre_proyecto."*".$nombre_acc;
		echo ($responce);
	}
	// return the formated data
//echo $json->encode($responce);
?>