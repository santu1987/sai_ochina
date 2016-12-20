				


   <div id="botonera"><img id="cuentas_por_pagar_vencido_documentos_db_btn_cancelar" class="btn_cancelar"src="imagenes/null.gif"  /><img id="cuentas_por_pagar_vencido_db_orden_btn_imprimir" class="btn_imprimir" src="imagenes/null.gif"  />	</div>
	
	</div>
<form method="post" id="form_cuentas_por_pagar_vencido_rp_documentos" name="form_cuentas_por_pagar_vencido_db_docuemntos">
<input type="hidden"  id="cuentas_por_pagar_vencido_vista_documentos" name="cuentas_por_pagar_vencido_vista_documentos"/>
  <table   class="cuerpo_formulario">
	<tr>
	<th class="titulo_frame" colspan="4"><img src="imagenes/iconos/desktop24x24.png" style=" padding-right:5px;" align="absmiddle" />Cambio de Numeracion Documentos</th>
	</tr>  
   <tr id="tr_proveedor_cxp_rp">
		<th>Proveedor:</th>
		  <td>
		  <ul class="input_con_emergente">
				<li>
				  <input name="cuentas_por_pagar_vencido_rp_proveedor_codigo" type="text" id="cuentas_por_pagar_vencido_rp_proveedor_codigo"  maxlength="4"
				onchange="consulta_automatica_estado_proveedor_rp_cxp()" 
				message="Introduzca un C&oacute;digo para el proveedor."  size="5"
						jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Código Invalido', styleType:'cover'}"
						jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}"/>
				<input name="cuentas_por_pagar_vencido_rp_proveedor_nombre" type="text" id="cuentas_por_pagar_vencido_rp_proveedor_nombre" size="45" maxlength="60" readonly
				message="Introduzca el nombre del Proveedor."/>
				<input type="hidden" name="cuentas_por_pagar_vencido_rp_proveedor_id" id="cuentas_por_pagar_vencido_rp_proveedor_id" readonly />
				<input type="hidden" name="cuentas_por_pagar_vencido_rp_proveedor_rif" id="cuentas_por_pagar_vencido_rp_proveedor_rif" readonly />
				</li> 
					<li id="cuentas_por_pagar_vencido_rp_btn_consultar_proveedor" class="btn_consulta_emergente"></li>
	  </ul>	  </td>		
	</tr>
	<tr  id="tr_empleado_cxp_rp" style="display:none">
		<th>Empleado:</th>
        <td >		<ul class="input_con_emergente">
	  <li><input name="cuentas_por_pagar_vencido_rp_empleado_codigo" type="text" id="cuentas_por_pagar_vencido_rp_empleado_codigo"
				onchange="consulta_automatica_benef_venc_rp" size="5"  maxlength="4" 
				message="Introduzca un C&oacute;digo para el Empleado."
				jval="{valid:/^[,.-_123456789]{1,6}$/,message:'Código Invalido', styleType:'cover'}"
				jvalkey="{valid:/^[,.-_123456789]{1,6}$/, cFunc:'alert', cArgs:['Código: '+$(this).val()]}" 
				/>
	    <input name="cuentas_por_pagar_vencido_rp_empleado_nombre" type="text" id="cuentas_por_pagar_vencido_rp_empleado_nombre" size="45" maxlength="60"
				message="Introduzca el nombre del Empleado." />
		  <label>		    </label>
	     
	      <input type="hidden" name="textprue3" id="textprue3" />
	  </li> 
	  		<li id="cuentas_por_pagar_vencido_rp_btn_consultar_beneficiario" class="btn_consulta_emergente"></li>
		</ul>      </td>
	</tr>

	
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
	</tr>
</table> 
  <input  name="cuentas_por_pagar_vencido_rp_id" type="hidden" id="cuentas_por_pagar_vencido_rp_id"  />
</form>

   