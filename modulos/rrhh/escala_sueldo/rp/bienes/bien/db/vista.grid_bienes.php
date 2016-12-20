<div class="div_busqueda">
<form id="form_bien_compra" name="form_bien_compra" action="">
<td align="center"><strong>Nombre</strong>: </td>                  
	           <input type="text" id="bienes_db_nombre" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
&nbsp;<td align="center"><strong>Custodio</strong>: </td>                  
	           <input type="text" id="bienes_db_custodine" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
&nbsp;<td align="center"><strong>Fecha Compra</strong>: </td>                  
	           <input readonly="true" type="text" id="bienes_db_fecha_comp" maxlength="10" size="12"/>
               <a href="modulos/bienes/bien/db/fecha_compra.php" target="fecha_compra" onclick="mostrar_compra();"><img src="utilidades/jscalendar-1.0/img.gif" border="0"/></a>                
</form>               
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
<iframe id="fecha_compra" name="fecha_compra" src="" scrolling="no" height="233px" width="250px" style="visibility:hidden; position:absolute; border:0; top:150px; left:470px;"></iframe>
<script language="javascript">
function mostrar_compra(){
	document.getElementById('fecha_compra').style.visibility='visible';
}
</script>