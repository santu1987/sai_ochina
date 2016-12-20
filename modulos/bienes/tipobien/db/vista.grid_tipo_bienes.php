<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM mayor";
$rs_unidad =& $conn->Execute($sql);
while (!$rs_unidad->EOF){
	$opt_unidad.="<option value='".$rs_unidad->fields("id_mayor")."' >".$rs_unidad->fields("nombre")."</option>";
	$rs_unidad->MoveNext();
} 
?>
<div class="div_busqueda">
<td align="center"><strong>Mayor</strong>: </td>
<label>
  <select name="tipo_bienes_db_may" id="tipo_bienes_db_may">
  	<option value="">----TODOS----</option>
  	<?= $opt_unidad;?>
  </select>
</label>
<td align="center"><strong>Nombre</strong>: </td>                  
	           <input type="text" id="tipo_bienes_db_nombre"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>