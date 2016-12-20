<div class="div_busqueda">
<td align="center"><strong>Nº Requisicion</strong>: </td>                  
	           <input type="text" id="requisicion_db_requisicion"  
			   jVal="{valid:/^[0-9]{1,60}$/}"
				jValKey="{valid:/[0-9]/}"/>
                &nbsp;<strong>Asunto: </strong>
                	 
  <label>
                  <input type="text" id="requisicion_db_asunto"
                  jVal="{valid:/^[A-Za-z 0-9]{1,60}$/}"
				  jValKey="{valid:/[A-Za-z 0-9]/}">
  </label>
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
