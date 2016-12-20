<div class="div_busqueda">
<td align="center"><strong>Codigo</strong>: </td>                  
	           <input type="text" id="unidad_ejecutora_db_cod"  
			   jVal="{valid:/^[0-9]{1,60}$/}"
				jValKey="{valid:/[0-9]/}"/>	
                <strong>Unidad: </strong>
               <input type="text" id="unidad_ejecutora_db_unidad_solicitante"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚ]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚ]/}"/>	 
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>