<div class="div_busqueda">
<td align="center"><strong>Ramo</strong>: </td>                  
	           <input type="text" id="ramo_db_nombre" maxlength="30"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	 
</div><!-- la tabla donde se creara el grid con clase 'scroll' -->
<table id="list_grid_<?=$_GET[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<!-- el div donde radicaran los botones de control del grid -->
<div id="pager_grid_<?=$_GET[id_grid]?>" class="scroll" style="text-align:center;"></div>