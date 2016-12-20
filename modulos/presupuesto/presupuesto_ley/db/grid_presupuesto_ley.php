<div class="div_busqueda">
<td align="center"><strong>  Partida:</strong>: </td>                  
	           <input type="text" id="presupuesto_ley_db-consultas-busqueda_partida"  size="10"
			   jVal="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
				jValKey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}" />	 
					<td align="center"><strong>Acci&oacute;n Centralizada/Proyecto:</strong></td>
				<input  type="text"	id="presupuesto_ley_db-consultas-busqueda_accion_proyecto"  size="20"  message="Elija una sola opcion de b&uacute;queda(por partida o por Nombre)"
			   jval="{valid:/^[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]{1,60}$/}"
				jvalkey="{valid:/[a-zA-Z &aacute;&eacute;&iacute;&oacute;&uacute;&Aacute;&Eacute;&Iacute;&Oacute;&Uacute;&ntilde;.]/}"/>	  	
				<td align="center"> <strong>Acci&oacute;n spec&iacute;fica:  </strong></td>
			      <input name="text2" type="text"	id="presupuesto_ley_db-consultas-busqueda_accion"  size="20"  message="Elija una sola opcion de búqueda(por partida o por Nombre)"
			 	  	jval="{valid:/^[a-zA-Z áéíóúÁÉÍÓÚñ.]{1,60}$/}"
					jvalkey="{valid:/[a-zA-Z áéíóúÁÉÍÓÚñ.]/}"/>
			    
		        <input name="button" type="button" id="presupuesto_ley_db-consultas-busqueda_boton_filtro" value="Buscar" />			    </td>
				</div>
<table id="<?=$_POST[id_grid]?>" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="<?=$_POST[id_pager]?>" class="scroll" style="text-align:center;"></div>
		