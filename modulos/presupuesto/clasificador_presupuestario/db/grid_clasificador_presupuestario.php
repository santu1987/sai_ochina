<div class="div_busqueda">
<td align="center"><strong>  Partida:</strong>: </td>                  
	           <input type="text" id="clasificador_presupuestario_db-consultas-busqueda_partida"   
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"  />	 
<td align="center"><strong> Nombre:</strong></td>
				<input type="text"	id="clasificador_presupuestario_db-consultas-busqueda_nombre"  message="Elija una sola opcion de búqueda(por partida o por Nombre)" 
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	  	
				<!--<input type="button" id="clasificador_presupuestario_db-consultas-busqueda_boton_filtro" value="Buscar" />-->		
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		