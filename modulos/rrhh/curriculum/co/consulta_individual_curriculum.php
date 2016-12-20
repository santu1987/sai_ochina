<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
require_once('../../../../utilidades/jqgrid_demo/JSON.php');
$json=new Services_JSON();
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);
?>
<script language="javascript" type="text/javascript">
	function ver_foto(obj){
		var id_foto='imagenes/curriculos/'+obj;
		//alert(id_foto);
		new Boxy("<div align='center'><img src="+id_foto+" "+"width='500' height='550' /></div>",{title: "FOTO DEL CURRICULUM", modal:true});
		/* Boxy.ask("<div align='center'><img src="+id_foto+" "+"width='350' height='500' /></div>", ["CERRAR"],
		function(val) { }, {title: "FOTOS DEl CURRICULUM"}); */
    	return false;
	}
</script>
<body>
<table class="cuerpo_formulario">
  <tr >
    <th width="729" class="titulo_frame"><?php echo $_REQUEST['curricus']; ?>
    </th>
  </tr>
  <tr>
    <th align="center">
    	<div style="overflow:auto; width:600px; height:120px">
        <?php
		$SQL_count= " SELECT
					count(id_curriculum)
				FROM
					curriculos
				WHERE
					curriculos.id_ramas=$_REQUEST[id_rama]";
		$SQL= " SELECT
					*
				FROM
					curriculos
				WHERE
					curriculos.id_ramas=$_REQUEST[id_rama]";
		$ROW=& $conn->Execute($SQL_count);
		$ROW=substr($ROW, 5,7);
		if($ROW==0){ echo "<img id='jjj' src='imagenes/iconos/no_foto.png' width='77' height='92' border='0' style='padding-left:150px;'/>"."No Existen Curriculum para esta Rama";}
		$ROW2=& $conn->Execute($SQL);
		while(!$ROW2->EOF){
			$foto=$ROW2->fields('imagen');
			echo"<img id='$foto' src='imagenes/curriculos/$foto' width='77' height='92' border='1' style='cursor:pointer; border-color: #4c7595;' onclick='ver_foto(this.id);'/>"."&nbsp;"."&nbsp;"."&nbsp;";
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
    <td class="bottom_frame">&nbsp;</td>
  </tr>
</table>
<form id="form2" name="form2" method="post" action="">
</form>
<p align="center">&nbsp;</p>
</body>
</html>