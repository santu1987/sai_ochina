<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT 
				bienes.id_bienes,
				bienes.nombre as bien, 
				valor_compra,
				valor_rescate,
				tipo_bienes.nombre as tipo,
				sitio_fisico.nombre as sitio,
				unidad_ejecutora.nombre as unidad,
				mayor.nombre as mayor,
				vida_util,
				custodio.nombre as custodio,
				descripcion_general as descri,
				marca,
				modelo,
				anobien,
				serial_motor,
				serial_carroceria,
				color,
				placa,
				estatus_bienes as estatus,
				bienes.comentarios as comen,
				codigo_bienes,
				serial_bien,
				bienes.id_tipo_bienes as idtipo,
				bienes.id_sitio_fisico as idsitio,
				bienes.id_custodio as idcustodio,
				bienes.id_unidad_ejecutora as idunidad,
				bienes.id_mayor as idmayor,
				bienes.anobien,
				bienes.calcular_depreciacion as depreciacion,
				bienes.fecha_compra as fecompra
			FROM 
				bienes 
			INNER JOIN
				unidad_ejecutora
			ON
				unidad_ejecutora.id_unidad_ejecutora=bienes.id_unidad_ejecutora
			INNER JOIN
				custodio
			ON
				custodio.id_custodio=bienes.id_custodio
			INNER JOIN
				tipo_bienes
			ON
				tipo_bienes.id_tipo_bienes=bienes.id_tipo_bienes
			INNER JOIN
				sitio_fisico
			ON
				sitio_fisico.id_sitio_fisico=bienes.id_sitio_fisico
			INNER JOIN
				mayor
			ON 
				mayor.id_mayor=bienes.id_mayor
			WHERE
				bienes.id_bienes=$_REQUEST[id_bienes];
			";
$row=& $conn->Execute($sql);
?>
<script language="javascript" type="text/javascript">
	function ver_foto(obj){
		var id_foto='imagenes/bienes/'+obj;
		//alert(id_foto);
		Boxy.ask("<div align='center'><img src="+id_foto+" "+"width='340' height='340' /></div>", ["CERRAR"],
		function(val) { }, {title: "FOTOS DEl ACTIVO"});
    	return false;
	}
</script>
<body>
<table class="cuerpo_formulario">
  <tr >
    <th colspan="6" class="titulo_frame"><?php echo strtoupper($_REQUEST['bien']);?></th>
  </tr>
  <tr>
    <th colspan="6" class="titulo_td"><div align="center">DATOS DEL BIEN</div>
      <form id="form1" name="form1" method="post" action="">
    </form></th>
  </tr>
  <tr>
    <th>Codigo:</th>
    <td colspan="5"><img src="modulos/bienes/bien/db/barcode.php?bdata=<?php echo $row->fields('codigo_bienes');?>" ></td>
  </tr>
  <tr>
    <th width="52">Bien:</th>
    <td width="151"><?php echo $row->fields('bien');?></td>
    <th width="50">Serial:</th>
    <td colspan="3"><?php echo $row->fields('serial_bien');?></td>
  </tr>
  <tr>
    <th>Marca:</th>
    <td><?php echo $row->fields('marca');?></td>
    <th>Modelo:</th>
    <td width="151"><?php echo $row->fields('modelo');?></td>
    <th width="50">Año:</th>
    <td width="275"><?php echo $row->fields('anobien');?></td>
  </tr>
  <tr>
    <th>Unidad:</th>
    <td><?php echo $row->fields('unidad');?></td>
    <th>Sitio Fisico:</th>
    <td><?php echo $row->fields('sitio');?></td>
    <th>Custodio:</th>
    <td><?php echo $row->fields('custodio');?></td>
  </tr>
  <tr>
    <th>Valor Compra:</th>
    <td><?php $valor_compra=$row->fields('valor_compra'); 
			echo substr($valor_compra,1,20)." BsF";?></td>
    <th>Valor Rescate:</th>
    <td><?php $valor_rescate=$row->fields('valor_rescate');
			echo substr($valor_rescate,1,20)." BsF"?></td>
    <th>Fecha Compra:</th>
    <td><?php echo $row->fields('fecompra');?></td>
  </tr>
  <tr>
    <th>Vida Util:</th>
    <td><?php echo $row->fields('vida_util');?> Mes(es)</td>
    <th>Valor Depreciado:</th>
    <td>--</td>
    <th>Tipo de Bien:</th>
    <td><?php echo $row->fields('tipo');?></td>
  </tr>
  <tr>
    <th>Descripcion:</th>
    <td colspan="5"><?php echo $row->fields('descri');?></td>
  </tr>
  <tr>
    <th colspan="6" class="titulo_td"><div align="center">FOTOS</div></th>
  </tr>
  <tr>
    <th colspan="6">
    	<div style="overflow:auto; width:600px; height:120px">
        <?php
		$SQL_count= " SELECT
					count(id_bienes)
				FROM
					fotos_bienes
				WHERE
					fotos_bienes.id_bienes=$_REQUEST[id_bienes]";
		$SQL= " SELECT
					fotos_bienes.nombre
				FROM
					fotos_bienes
				WHERE
					fotos_bienes.id_bienes=$_REQUEST[id_bienes]";
		$ROW=& $conn->Execute($SQL_count);
		$ROW=substr($ROW, 5,7);
		if($ROW==0){ echo "<img id='jjj' src='imagenes/iconos/no_foto.png' width='77' height='92' border='0' style='padding-left:150px;'/>"."Este Activo No Posee Fotos";}
		$ROW2=& $conn->Execute($SQL);
		while(!$ROW2->EOF){
			$foto=$ROW2->fields('nombre');
			echo"<img id='$foto' src='imagenes/bienes/$foto' width='77' height='92' border='1' style='cursor:pointer; border-color: #4c7595;' onclick='ver_foto(this.id);'/>"."&nbsp;"."&nbsp;"."&nbsp;";
			$i++;
			$ROW2->MoveNext();
		}
		//$foto=$ROW2->fields('nombre');
		$vector=split("t",$ROW);
			/*for($i=0;$i<$vector[1];$i++){
				
				echo"<img src='imagenes/bienes/$foto' width='77' height='92' border='1' style='cursor:pointer; border-color: #4c7595;' onclick='ver_foto();'/>"."&nbsp;"."&nbsp;"."&nbsp;";
			}*/
		?>
    	</div>
    </th>
  </tr>
  <tr>
    <td colspan="6" class="bottom_frame">&nbsp;</td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="">
</form>
<p align="center">&nbsp;</p>
</body>
</html>