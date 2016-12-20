<div class="div_busqueda">
<td align="center"><strong>  Partida:</strong>: </td>                  
	           <input type="text" id="modificacion_presupuesto_db-consultas-busqueda_partida"  
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
			<? /*<td align="center"><strong> Unidad Ejecutora:</strong></td>
				<input type="text"	id="presupuesto_ley_db-consultas-busqueda_unidad_ejecutora"  message="Elija una sola opcion de búqueda(por partida o por Nombre)" 
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	  	
				<td align="center"><strong> Acción Específica:</strong></td>
				<input type="text"	id="presupuesto_ley_db-consultas-busqueda_accion"  message="Elija una sola opcion de búqueda(por partida o por Nombre)" 
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>	  	
				*/?>
				<input type="button" id="modificacion_presupuesto_db-consultas-busqueda_boton_filtro" value="Buscar" />		
</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		