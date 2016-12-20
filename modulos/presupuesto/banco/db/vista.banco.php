<script type='text/javascript'>
$("#btn_consultar").click(function() {

	var ventana=new Boxy.load("modulos/administracion_sistema/modulo/db/grid.consulta.php",{unloadOnHide:true,modal:true,show:true,title: "Dialog",cache:true});
	
});

$("#btn_guardar").click(function() {
	$.ajax ({
		url: "modulos/administracion_sistema/banco/db/sql.banco.php",
		data:dataForm('banco'),
		type:'POST',
		cache: false,
		success: function(html)
		{
			if (html=="Ok")
			{
				setBarraEstado("Se Registro Con Exito");
			}
			else
			{
				setBarraEstado(html);
			}
		}
	});
});

</script>
<div id="botonera">
	<img id="btn_consultar" src="../../../administracion_sistema/banco/db/imagenes/null.gif"/>
	<img id="btn_guardar" src="../../../administracion_sistema/banco/db/imagenes/null.gif"  />
</div>

<form method="post" id="banco" name="banco">
<table class="cuerpo_formulario">
	<tr>
		<th class="titulo_frame" colspan="4"><img src="../../../administracion_sistema/banco/db/imagenes/iconos/desktop24x24.png" style="padding-right:5px;" align="absmiddle" />Registrar Banco 

<a class='lnk' title='Banco' id='modulos/administracion_sistema/banco/co/vista.consulta_banco.php' href='javascript:void(0)'><img src='../../../administracion_sistema/banco/db/imagenes/iconos/kappfinder16x16.png' />Banco</a>		
		
		</th>
	</tr>
	<tr>
		<th>Nombre				</th><td ><input name="nombre" id="nombre" type="text" size="50" maxlength="60"></td>
	</tr>	
	<tr>
		<th>Sucursal			</th><td ><input name="sucursal"id="sucursal" type="text" size="50" maxlength="60"></td>
	</tr>
	<tr>
		<th>Direcci&oacute;n	</th><td ><textarea name="direccion" cols="55"></textarea></td>
	</tr>
	<tr>
		<th>Tel&eacute;fono		</th><td ><input type="text" size="5" name="code_area" id="code_area" />-<input name="telefono" id="telefono" type="text" size="30" maxlength="12"></td>
	</tr>
	<tr>
		<th>Fax					</th><td ><input name="fax" id="fax" type="text" size="30" maxlength="12"></td>
	</tr>
	<tr>
		<th>Pagina Banco		</th><td ><input name="pagBan" id="pagBan" type="text" size="50" maxlength="60"></td>
	</tr>
	<tr>
		<th>Contacto			</th><td ><input name="contacto" id="contacto" type="text" size="50" maxlength="60"></td>
	</tr>
	<tr>
		<th>Cargo				</th><td ><input name="cargo" id="cargo" type="text" size="50" maxlength="60"></td>
	</tr>
	<tr>
		<th>Email				</th><td ><input name="email" id="email" type="text" size="50" maxlength="60"></td>
	</tr>
	<tr>
		<td colspan="2" class="bottom_frame">&nbsp;</td>
  </table>
</form>