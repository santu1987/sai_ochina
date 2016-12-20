<?php
require_once('../../../../controladores/db.inc.php');
require_once('../../../../utilidades/adodb/adodb.inc.php');
$conn = &ADONewConnection('postgres');

$db=dbconn("pgsql");

$conn->PConnect($db["host"],$db["user"],$db["password"],$db["dbname"]);

$sql="SELECT *FROM unidad_ejecutora";
$rs_unidad =& $conn->Execute($sql);
while (!$rs_unidad->EOF){
	$opt_unidad.="<option value='".$rs_unidad->fields("id_unidad_ejecutora")."' >".$rs_unidad->fields("nombre")."</option>";
	$rs_unidad->MoveNext();
} 
?>
<div class="div_busqueda">
<td align="center"><strong>Unidad</strong>: </td>                  
	             <select id="sitio_fisico_db_nombre_uni">
	               <option value="">---- Todos ----</option>
	               <?= $opt_unidad?>
    </select>
<td align="center"><strong>Sitio Fisico</strong>: </td>                      
<label>
  <input type="text" id="sitio_fisico_db_nombre" maxlength="30"  
			   jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
</label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>