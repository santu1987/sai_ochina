<div class="div_busqueda">
<td align="center"><strong>  Partida:</strong>: </td>                  
	           <input type="text" id="cuenta_contable_db-consultas-busqueda_partida" size="4" maxlength="4"   
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,100}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"  />	 
<td align="center"><strong> Cuenta Contable:</strong></td>
				<input type="text"	id="cuenta_contable_db-consultas-busqueda_nombre" maxlength="30"  message="Elija una sola opcion de búqueda(por partida o por Nombre)" 
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,100}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	  	
				<!--<input type="button" id="cuenta_contable_db-consultas-busqueda_boton_filtro" value="Buscar" />-->		
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		