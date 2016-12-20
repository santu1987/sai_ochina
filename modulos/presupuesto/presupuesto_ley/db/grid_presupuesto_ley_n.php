<div class="div_busqueda">
<td align="center"><strong>  Partida:</strong>: </td>                  
	           <input type="text" id="presupuesto_ley_db-consultas-busqueda_partida"  size="10"
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
				<td align="center">
		        <input name="button" type="button" id="presupuesto_ley_db-consultas-busqueda_boton_filtro" value="Buscar" />			    </td>
				</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		