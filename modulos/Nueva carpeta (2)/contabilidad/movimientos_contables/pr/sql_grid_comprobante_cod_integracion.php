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
//$where.="and integracion_contable.ultimo_usuario='".$_SESSION['id_usuario']."'";
$cuenta_utf=0;
$codigo_uejecutora=0;
$cuenta_auxiliar=0;
$codigo_proyecto=0;
$codigo_acentral=0;
/*** '".$_SESSION['id_usuario']."'";**/
$comprobante=$_POST[contabilidad_comprobante_pr_tipo].$_POST[contabilidad_comprobante_pr_numero_comprobante];
if($comprobante=='')
{
	$comprobante=0;
}
$where.="and integracion_contable.numero_comprobante='$comprobante'";

//************************************************************************
$limit = 15;
if(!$sidx) $sidx =1;
		$sql2="
				SELECT
						count(integracion_contable.numero_comprobante)
				FROM
						integracion_contable
				inner join
						organismo
				on
					integracion_contable.id_organismo=organismo.id_organismo
				where		
						(organismo.id_organismo =".$_SESSION['id_organismo'].")
				$where				
					";
		
		/*$Sql2="
					SELECT 
						count(integracion_contable.id_integracion_contable) 
					FROM 
						integracion_contable 
					inner join
						organismo
					on
						integracion_contable.id_organismo=organismo.id_organismo
								
					where		
							(organismo.id_organismo =".$_SESSION['id_organismo'].")
							$where
					";	 */
				
					$row2=& $conn->Execute($sql2);
				//die($sql2);	
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
/*	$Sql_int="
				SELECT distinct
					integracion_contable.id_integracion_contable,
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
					integracion_contable.codigo_auxiliar,
					integracion_contable.codigo_unidad_ejecutora,
					integracion_contable.codigo_proyecto,
					integracion_contable.codigo_utilizacion_fondos,
					integracion_contable.estatus,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					tipo_comprobante.codigo_tipo_comprobante
								
					
				FROM 
					integracion_contable
				inner join
						organismo
						on
						integracion_contable.id_organismo=organismo.id_organismo
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
					 integracion_contable.id_integracion_contable
				LIMIT 
					$limit 
				OFFSET 
					$start ";*/
		$Sql_int="
				SELECT distinct
			
					integracion_contable.numero_comprobante
					
				FROM 
					integracion_contable
				inner join
					organismo
						on
					integracion_contable.id_organismo=organismo.id_organismo
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
					$resta_debe_haber=$row_sumas->fields("debe")-$row_sumas->fields("haber");
					$resta_debe_haber2=number_format($resta_debe_haber,2,',','.');
					$debe=number_format($row_sumas->fields("debe"),2,',','.');
					$haber=number_format($row_sumas->fields("haber"),2,',','.');
			}
			$sql3=" SELECT
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
					integracion_contable.id_accion_central,
					integracion_contable.id_utilizacion_fondos,
					cuenta_contable_contabilidad.nombre as descripcion_cuenta,
					cuenta_contable_contabilidad.id	as id_cc,
					cuenta_contable_contabilidad.requiere_auxiliar,
					cuenta_contable_contabilidad.requiere_unidad_ejecutora,
					cuenta_contable_contabilidad.requiere_proyecto,
					cuenta_contable_contabilidad.requiere_utilizacion_fondos,
					tipo_comprobante.codigo_tipo_comprobante as codigo_tipo,
					integracion_contable.comentario
				FROM 
					integracion_contable
				inner join
						organismo
						on
						integracion_contable.id_organismo=organismo.id_organismo
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
				AND integracion_contable.numero_comprobante='$numero_comprobante'
				$where
				ORDER BY 
					 integracion_contable.id
				";
		$row3=& $conn->Execute($sql3);
		//	
		//die($sql3);
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
								 if(!$row_ut3->EOF)
								 $cuenta_auxiliar=$row_ut3->fields("cuenta_auxiliar");
							 		else
									$cuenta_auxiliar='0';
							 }else
							 $cuenta_auxiliar='0';				 
		 //////////////////////////////////////////////////////////////////
							 $id_proyecto=$row3->fields("id_proyecto");
							 if($id_proyecto!=0)
							 {
								 $sql_ut4="select codigo_proyecto from proyecto where id_proyecto='$id_proyecto' AND	proyecto.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut4=& $conn->Execute($sql_ut4);
								if(!$row_ut4->EOF)	
								 $cod_proyecto=$row_ut4->fields("codigo_proyecto");
								else
								$cod_proyecto='0';
							 }else{
							 $cod_proyecto='0';}
		 //////////////////////////////////////////////////////////////////
							 $id_acc=$row3->fields("id_accion_central");
					
							 if($id_acc!=0)
							 {
								 $sql_ut5="select codigo_accion_central from accion_centralizada where id_accion_central='$id_acc' AND	accion_centralizada.id_organismo = $_SESSION[id_organismo]									
														";
								 $row_ut5=& $conn->Execute($sql_ut5);
								 //die($sql_ut5);
								if(!$row_ut5->EOF)	
								{ 
									$cod_acc=$row_ut5->fields("codigo_accion_central");
								 //die($cod_acc);
								 }
								else
								$cod_acc='0';
							 }else
							 {
								 $cod_acc='0';
							  	 $id_acc='0';
							}	
		//////////////////////////////////////////////////////////////////	 
						$responce->rows[$i]['id']=$row3->fields("id");
						$responce =$row3->fields("id")."*".$row3->fields("id_organismo")."*".$row3->fields("ano_comprobante")."*".$row3->fields("mes_comprobante")."*".$row3->fields("id_tipo_comprobante")."*".$row_int->fields("numero_comprobante")."*".$row3->fields("secuencia")."*".$row3->fields("comentarios")."*".$row3->fields("cuenta_contable")."*".$row3->fields("descripcion")."*".$row3->fields("referencia")."*".$debe."*".$haber."*".$row3->fields("fecha_comprobante")."*".$id_auxiliar."*".$id_uejecutora."*".$id_proyecto."*".$row3->fields("id_utilizacion_fondos")."*".$row3->fields("codigo_tipo")."*".$row3->fields("id_cc")."*".$cuenta_utf."*".$codigo_uejecutora."*".$cuenta_auxiliar."*".$cod_proyecto."*".$cod_acc."*"."0"."*".$id_acc."*".$row3->fields("requiere_auxiliar")."*".$row3->fields("requiere_unidad_ejecutora")."*".$row3->fields("requiere_proyecto")."*".$row3->fields("requiere_utilizacion_fondos")."*".$resta_debe_haber2;
		
	}
	else
	{
		$responce="vacio";
	}	
echo($responce);

?>