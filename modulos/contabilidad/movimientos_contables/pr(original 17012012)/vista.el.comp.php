<div id="botonera">
	<img src="imagenes/null.gif" width="31" height="26" class="btn_cancelar" id="contabilidad_auxiliares_db_btn_cancelar"/>
   <!-- <img id="movimientos_contables_db_btn_eliminar" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>-->
	    <img id="movimientos_contables_db_btn_eliminar2" class="btn_eliminar"src="imagenes/null.gif" style="display:none"/>

	<img id="contabilidad_movimientos_pr_btn_guardar" class="btn_guardar"src="imagenes/null.gif" />
	<img id="contabilidad_movimientos_pr_btn_actualizar" class="btn_actualizar"src="imagenes/null.gif" style="display:none"/>
	<img id="contabilidad_movimientos_pr_btn_consultar" class="btn_consultar" src="imagenes/null.gif" />
    <img id="contabilidad_movimientos_contables_rp_imprimir" class="btn_imprimir" src="imagenes/null.gif" style="display:none" />
	<img id="contabilidad_db_comprobante_manual_btn_abrir" src="imagenes/iconos/abrir_orden_cxp.png"  style="display:none"/>
	<img id="contabilidad_db_comprobante_manual_btn_cerrar" src="imagenes/iconos/cerrar_orden_cxp.png"   style="display:none"/>
</div>	
<form method="post" id="form_contabilidad_comprobantes_pr_movimientos" name="form_contabilidad_comprobantes_pr_movimientos">
<input type="hidden"  id="contabilidad_comp_id_comprobante" name="contabilidad_comp_id_comprobante" value="0"/>
<input type="hidden" id="contabilidad_comp_pr_activo" name="contabilidad_comp_pr_activo"  value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo2" name="contabilidad_comp_pr_activo2" value="0"/>
 <input type="hidden" id="contabilidad_comp_pr_activo3" name="contabilidad_comp_pr_activo3" value="0"/>
  <input type="hidden" id="contabilidad_comp_pr_activo4" name="contabilidad_comp_pr_activo4" value="0"/>

  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Anulacion Comprobante</th>
	<input   type="hidden" name="contabilidad_comp_pr_id"  id="contabilidad_comp_pr_id" />

	</tr>
	
	<tr>
		 	<th>Tipo Comprobante :</th>
			<td>
			<ul class="input_con_emergente">
			 <li>
				<input type="text" name="contabilidad_comp_pr_tipo" id="contabilidad_comp_pr_tipo"  size='12' maxlength="12" onchange="consulta_manual_tipo_comprobante()" value="<? echo($codigo_tipo);?>"
				message="Introduzca el tipo de cuenta" 
				jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
		        <input type="hidden" id="contabilidad_comp_pr_tipo_id" name="contabilidad_comp_pr_tipo_id"  value="<? echo($tipo);?>"
				 jval="{valid:/^[0-9]{1,12}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}"/>
<input type="hidden" id="contabilidad_comp_pr_numero_comprobante2" name="contabilidad_comp_pr_numero_comprobante2"  value="<?php echo($comprobante)?>"/>
<!--				<input type="text" name="cuentas_por_pagar_integracion_tipo_nombre" id="cuentas_por_pagar_integracion_tipo_nombre"  size='30' maxlength="30"
				message="Introduzca el tipo de cuenta" />
-->			 </li>
			<li id="contabilidad_comp_btn_consultar_tipo" class="btn_consulta_emergente"></li>
			</ul>			</td>
    </tr>
    <tr >
	<th>N&uacute;mero Comprobante:</th>
		<td>
			<input type="text" id="contabilidad_comp_pr_numero_comprobante" name="contabilidad_comp_pr_numero_comprobante"  onchange="consulta_automatica_comprobante()" onblur="consulta_automatica_comprobante()"  message="Introduzca n comprobante" size="12" maxlength="4"  readonly="readonly"  value="<? echo($comprobante2);?>" 
				/>
				
				
	
    <!--jval="{valid:/^[0-9]{1,4}$/, message:'Tipo Invalido', styleType:'cover'}"
				jvalkey="{valid:/[0-9]/, cFunc:'alert', cArgs:['Codigo: '+$(this).val()]}" -->
    		<input type="text" id="numero_comprobante_cont" name="numero_comprobante_cont" />
			<input type="hidden" id="sumador_comprobante" name="sumador_comprobante" />
		</td>
	</tr>
		<tr>
		<th>
			Fecha:
		</th>
		<td width="124">
		            <input alt="date" type="text" name="contabilidad_comp_pr_fecha" id="contabilidad_comp_pr_fecha" size="7" value="<? echo ($fecha_comprobante);?>" jval="{valid:/^[0-9/-]{1,60}$/, message:'Fecha Invalida', styleType:'cover'}" onchange="v_fecha();"
					jvalkey="{valid:/[0-9/-]/, cFunc:'alert', cArgs:['Fecha Impuesto: '+$(this).val()]}"/>
                    <input type="hidden"  name="contabilidad_comp_pr_fecha_oculto" id="contabilidad_comp_pr_fecha_oculto" value="<? echo ($fecha_comprobante);?>"/>
				  <button type="reset" id="contabilidad_comp_pr_fecha_boton_d">...</button>
				  <script type="text/javascript">
							Calendar.setup({
								inputField     :    "contabilidad_comp_pr_fecha",      // id of the input field
								ifFormat       :    "%d/%m/%Y",       // format of the input field
								showsTime      :    false,            // will display a time selector
								button         :    "contabilidad_comp_pr_fecha_boton_d",   // trigger for the calendar (button ID)
								singleClick    :    true,          // double-click mode
								onUpdate :function(date){
										f1=new Date( getObj("contabilidad_comp_pr_fecha").value.MMDDAAAA() );
										//f2=new Date( getObj("balance_inicial_rp_fecha_hasta").value.MMDDAAAA() );
										
									}
							});
					</script>
		</td>
		</tr>
        <tr>
        		<th>Llevar a:</th>
                <td>
                <select name="opciones_el" id="opciones_el">
                <option id="1" value="1">Integracion</option>
                <option id="2" value="2">Lote de Eliminados</option>
                </select>
                </td>
        </tr>
		<tr>
		
			<th>Descripci&oacute;n:</th>
			 <td>
			 <textarea  name="contabilidad_comp_pr_desc" cols="60" id="contabilidad_comp_pr_desc"  message="Introduzca una Descripci&oacute;n del asiento. Ejem:'Esta cuenta es ...' "   ><?php echo($descripcion_valor);?></textarea>			</td>
		</tr>
	
		<tr>
			<th>Comentarios:</th>
			<td>
				<textarea id="contabilidad_comp_pr_comentarios" name="contabilidad_comp_pr_comentarios" cols="60"/>			</td>
		</tr>	
  </table>
  <input   type="hidden" name="contabilidad_auxiliares_db_id_aux"  id="contabilidad_auxiliares_db_id_aux" />
</form>