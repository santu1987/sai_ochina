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
//////////////////////////////////////////////////////////
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$where="and movimientos_contables.id_movimientos_contables='$id'"; 
}
else
$where=" and 1=1";
$where.="and movimientos_contables.estatus!='3'";

//////////////////////////////////////////////////////////
//$where.="and movimientos_contables.ultimo_usuario='".$_SESSION['id_usuario']."'";
/*** '".$_SESSION['id_usuario']."'";**/
//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$Sql2="
					SELECT 
						count(movimientos_contables.id_movimientos_contables) 
					FROM 
						movimientos_contables 
					inner join
						organismo
					on
						movimientos_contables.id_organismo=organismo.id_organismo
								
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
							$where
					";	 
				
					$row2=& $conn->Execute($Sql2);
		//	die($Sql2);			
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
					movimientos_contables.id_movimientos_contables,
					movimientos_contables.id_organismo,
					movimientos_contables.ano_comprobante,
					movimientos_contables.mes_comprobante,
					movimientos_contables.id_tipo_comprobante,
					movimientos_contables.numero_comprobante,
					movimientos_contables.secuencia,
					movimientos_contables.cuenta_contable,
					movimientos_contables.descripcion,
					movimientos_contables.comentario,
					movimientos_contables.referencia,
					movimientos_contables.debito_credito,
					movimientos_contables.monto_debito,
					movimientos_contables.monto_credito,
					movimientos_contables.fecha_comprobante,
					movimientos_contables.id_auxiliar,
					movimientos_contables.id_unidad_ejecutora,
					movimientos_contables.id_proyecto,
					movimientos_contables.id_accion_central,
					movimientos_contables.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					tipo_comprobante.codigo_tipo_comprobante,
					movimientos_contables.estatus,
					cuenta_contable_contabilidad.requiere_auxiliar,
					cuenta_contable_contabilidad.requiere_unidad_ejecutora,
					cuenta_contable_contabilidad.requiere_proyecto,
					cuenta_contable_contabilidad.requiere_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as nombre_cuenta

				FROM 
					movimientos_contables
				inner join
						organismo
						on
						movimientos_contables.id_organismo=organismo.id_organismo
				inner join 
					cuenta_contable_contabilidad 
				on 
				movimientos_contables.cuenta_contable=cuenta_contable_contabilidad.cuenta_contable
				inner join
					tipo_comprobante
				on
					tipo_comprobante.id=movimientos_contables.id_tipo_comprobante
									
				where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")	 
				$where
				ORDER BY 
					 movimientos_contables.id_movimientos_contables
				LIMIT 
					$limit 
				OFFSET 
					$start ";
		
	$row_int=& $conn->Execute($Sql_int);
	// constructing a JSON
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if(!$row_int->EOF) 
	{
			$numero_comprobante=$row_int->fields("numero_comprobante");
			$sql_sumas=" SELECT
							SUM(monto_debito) as debe,
							SUM(monto_credito) as haber
						from
							movimientos_contables
						where numero_comprobante='$numero_comprobante'
						and movimientos_contables.estatus!='3'
														";
			//die($sql_sumas);
			$row_sumas=& $conn->Execute($sql_sumas);
			if(!$row_sumas->EOF)
			{
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
			}
		////////////////codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_uejecutora=$row_int->fields("id_unidad_ejecutora");
							 if($id_uejecutora!=0)
							 {
								 $sql_ut="select nombre as nombre_ue,codigo_unidad_ejecutora  from unidad_ejecutora where id_unidad_ejecutora='$id_uejecutora' AND	unidad_ejecutora.id_organismo = $_SESSION[id_organismo]";									
								 $row_ut=& $conn->Execute($sql_ut);
								 if(!$row_ut->EOF)
								 {
								 	 $codigo_uejecutora=$row_ut->fields("codigo_unidad_ejecutora");
							 	 	 $nombre_ejecutora=$row_ut->fields("nombre_ue");
								 }
								else
								{
									$codigo_uejecutora='';
									$nombre_ejecutora='';	
							 	}
							 }else
							 if($id_uejecutora==0)
							 {
							  		$codigo_uejecutora='';
							  		$nombre_ejecutora='';	
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
									$nombre_utf='';
								 	$cuenta_utf='';
								}
			//////////////// verificando si la partida coincide con el codigo de ut///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							 $id_auxiliar=$row_int->fields("id_auxiliar");
							 if($id_auxiliar!=0)
							 {
								 $sql_ut3="select nombre as aux_nombre,cuenta_auxiliar from auxiliares where id_auxiliares='$id_auxiliar' AND id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut3=& $conn->Execute($sql_ut3);
								 if(!$row_ut3->EOF)
								 {
								 	$cuenta_auxiliar=$row_ut3->fields("cuenta_auxiliar");
								 	$aux_nombre=$row_ut3->fields("aux_nombre");
							 	}	
								else
								{
								  $cuenta_auxiliar="";
								  $aux_nombre="";
							 	}
							 }else
							 {
							 	$cuenta_auxiliar="";
								$aux_nombre="";
							}		
                //////////////////////////////////////////////////////////////////
							 $id_proyecto=$row_int->fields("id_proyecto");
							 if($id_proyecto!=0)
							 {
									 $sql_ut4="select nombre as nombre_proyecto,codigo_proyecto from proyecto where id_proyecto='$id_proyecto' AND	proyecto.id_organismo = $_SESSION[id_organismo]";
									 $row_ut4=& $conn->Execute($sql_ut4);
									if(!$row_ut4->EOF)	
									{
										 $cod_proyecto=$row_ut4->fields("codigo_proyecto");
										 $nombre_proyecto=$row_ut4->fields("nombre_proyecto");
										 $cod_acc='0';
										 $nombre_acc='NO APLICA';
										 //die($nombre_acc);	
									}
									else
									{
										$cod_proyecto='';
										$nombre_poryecto='';
									}
							 }else
							 {
							 	$cod_proyecto='';
								$nombre_poryecto='';
							 }	
				//// si proyecto no es acc

		       //////////////////////////////////////////////////////////////////
							 $id_acc=$row_int->fields("id_accion_central");
							 if($id_acc!=0)
							 {
								 $sql_ut5="select denominacion,codigo_accion_central from accion_centralizada where id_accion_central='$id_acc' AND	accion_centralizada.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut5=& $conn->Execute($sql_ut5);
								if(!$row_ut5->EOF)	
								{
								 	$cod_acc=$row_ut5->fields("codigo_accion_central");
									$nombre_acc=$row_ut5->fields("denominacion");
									$cod_proyecto='0';
									$nombre_proyecto='NO APLICA';
								}
								else
								{
									$cod_acc='';
									$nombre_acc='';
							 	}
							 }				 
			//////////////////////////////////////////////////////////////////
			$responce->rows[$i]['id']=$row_int->fields("id_movimientos_contables");
			$responce =$row_int->fields("id_movimientos_contables")."*".$row_int->fields("id_organismo")."*".$row_int->fields("ano_comprobante")."*".$row_int->fields("mes_comprobante")."*".$row_int->fields("id_tipo_comprobante")."*".substr($row_int->fields("numero_comprobante"),2,4)."*".$row_int->fields("secuencia")."*".$row_int->fields("comentario")."*".$row_int->fields("cuenta_contable")."*".strtoupper($row_int->fields("descripcion"))."*".$row_int->fields("referencia")."*".number_format($row_int->fields("monto_debito"),2,',','.')."*".number_format($row_int->fields("monto_credito"),2,',','.')."*".$row_int->fields("id_auxiliar")."*".$row_int->fields("id_unidad_ejecutora")."*".$row_int->fields("id_proyecto")."*".$row_int->fields("id_utilizacion_fondos")."*".$row_int->fields("id_cc")."*".$debe."*".$haber."*".$row_int->fields("codigo_tipo_comprobante")."*".$row_int->fields("estatus")."*".$codigo_uejecutora."*".$cuenta_utf."*".$cuenta_auxiliar."*". $cod_acc."*". $cod_proyecto."*". $id_acc."*".$row_int->fields("requiere_auxiliar")."*".$row_int->fields("requiere_unidad_ejecutora")."*".$row_int->fields("requiere_proyecto")."*".$row_int->fields("requiere_utilizacion_fondos")."*".$row_int->fields("nombre_cuenta")."*".$nombre_ejecutora."*".$nombre_utf."*".$aux_nombre."*".$nombre_proyecto."*".$nombre_acc;
			echo ($responce);
		
	}


	// return the formated data
//echo $json->encode($responce);

?>