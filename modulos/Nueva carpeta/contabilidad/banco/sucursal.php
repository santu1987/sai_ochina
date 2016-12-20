<DLCALENDAR tool_tip="Haga click para seleccionar la fecha" input_element_id ="fechanac" click_element_id="img_fecnac"></DLCALENDAR>
<?
	require_once '../MODELOS/Buscar.php';
	require_once '../MODELOS/sucursal.php';
	require_once '../MODELOS/fechas.php';
if ($_POST["id"] != "" && $_POST['operacion'] ==2)
{
	foreach(sucursal::buscartDonde($_POST["id"]) as $obj)
	{
		$id_cliente=$obj-> get("ID_SUCURSAL");
		$apellido = $obj-> get("SUCURSAL");
		$id_telefono=$obj-> get("ID_TELEFONO");
		$id_direccion=$obj-> get("ID_DIRECCION");
		$email =$obj-> get("EMAIL_SU");
		$pag_web =$obj-> get("PERSO_CONTA");
		$estatus =$estatus = $obj-> get("ESTATU");
// echo "<script>alert($id_direccion)/script>;";
		if ($estatus == 1)
		{
			$act="selected";
		}
		if ($estatus == 2)
		{
			$act2="selected";
		}
	}
	foreach(Direccion::buscartDonde($id_direccion) as $obj)
	{
		$id_direccion=$obj-> get("ID_DIRECCION");
		$pais=$obj-> get("ID_PAIS");
		$estado=$obj-> get("ID_ESTADO");
		$ciudad=$obj-> get("ID_CIUDAD");
		$tip_inmu=$obj-> get("ID_TIP_INM");
		$urb=$obj-> get("URBANIZACION");
		$piso=$obj-> get("NRO_PISO");
		$ptoref=$obj-> get("PUNTO_REF");
		$descripcion =$obj-> get("DESCRIPCION");
	}
	foreach(Telefono::buscartDonde($id_telefono) as $obj)
	{
		$id_telefono=$obj-> get("ID_TELEFONO");
		$cod_area=$obj-> get("ID_COD_AREA");
		$cod_cel=$obj-> get("ID_COD_CEL");
		$cod_fax=$obj-> get("ID_COD_AREA_FAX");
		$telefono=$obj-> get("CASA");
		$celular=$obj-> get("CELULAR");
		$fax =$obj-> get("FAX");
	}


}
?>
 <!-- ---------------------------       Java Script     -------------------->
<script>
function checkFieldsAndSubmit() {
var str_error="";
 if (document.clienten.estado.value=="") {
	str_error = str_error + "- Pais - Estado.\n"	
}
if ( str_error != "" ) 
   {
   str_error_show = "Error, el campo (o los campos) siguientes:\n\n" + str_error + "\n no pueden estar vacios..."	
   alert(str_error_show);
   return false
   }
}
var arrItems1 = new Array();
var arrItems2 = new Array();
var arrItemsGrp1 = new Array();
<?	
	foreach(Buscar::buscarOr("estados","ID_ESTADO, ID_PAIS") as $obj){			
	 ?>
		arrItems1[<?= $obj-> get(0)?>] = "<?= $obj-> get(2)?>";
		arrItems2[<?=$obj-> get(0)?>] = <?=$obj-> get(0)?>;
		arrItemsGrp1[<?=$obj-> get(0)?>] = <?=$obj-> get(1)?>;
<?		
	}
?>
function selectChange(control, controlToPopulate, ItemArray,ItemArray2, GroupArray)
{
  var myEle ;
  var x ;
  var g;
   var texto;
   var q=controlToPopulate.options.length;
   var xx = ItemArray.length;
  for (q;q>=0;q--) controlToPopulate.options[q]=null;
  for ( x = 0 ; x < xx  ; x++ )
    {
      if ( GroupArray[x] == control.value )
        {
          myEle = document.createElement("option") ;
          myEle.value = ItemArray2[x];
          myEle.text = ItemArray[x] ;
          controlToPopulate.add(myEle) ;
     }
    }
}
var uno = new Array();
var dos = new Array();
var tres = new Array();
<?	
	foreach(Buscar::buscarOr("CIUDADES"," ID_ESTADO, ID_CIUDAD") as $obj){			
	 ?>
		uno[<?= $obj-> get(0)?>] = "<?= $obj-> get(2)?>";
		dos[<?=$obj-> get(0)?>] = <?=$obj-> get(0)?>;
		tres[<?=$obj-> get(0)?>] = <?=$obj-> get(1)?>;
<?		
	}
?>
function selectChanges(control, controlToPopulate, ItemArrayUno,ItemArraydos, GroupArraytres)
{
  var myEle ;
  var x ;
  var g;
   var texto;
   var q=controlToPopulate.options.length;
   var xx = ItemArrayUno.length;
  for (q;q>=0;q--) controlToPopulate.options[q]=null;
  for ( x = 0 ; x < xx  ; x++ )
    {
      if ( GroupArraytres[x] == control.value )
        {
          myEle = document.createElement("option") ;
          myEle.value = ItemArraydos[x];
          myEle.text = ItemArrayUno[x] ;
          controlToPopulate.add(myEle) ;
     }
    }
}

</script>

<link href="../../datos_base/RECURSOS/SEOC.css" rel="stylesheet" type="text/css">
<script src="../../datos_base/RECURSOS/validaciones.js" language="JavaScript"></script> 
<form name="clienten" method="post">
<table align="center">
	<tr>
		<td width="50%" colspan="2">&nbsp;</td>
		<td width="50%" colspan="2" align="right"><?=fechas::formato1();?></td>
	</tr>
	<tr>
		<td colspan="4" class="titulo" align="center" bgcolor="#44439A">Sucursal</td>
	</tr>
	<tr>
		<td width="30%" class="ETIQUETA">C&oacute;digo:</td>
		<td width="30%">&nbsp;<?=$id_cliente;?></td>
		<td width="20%">&nbsp;</td>
		<td width="20%">&nbsp;</td>
	</tr>
	<tr>
		<td class="ETIQUETA">Sucursal</td>
		<td colspan="3"><input name="apellido" type="text"  onKeyPress="return acceptLetra(event);" value="<?=$apellido;?>" size="40"></td>
	</tr>
	<tr>
		<td class="ETIQUETA">Correo electr&oacute;nico</td>
		<td colspan="3"><input name="email" type="text"  onblur="validarEmailNecesario(this);" value="<?=$email;?>" size="40" ></td>
	</tr>
	<tr>
		<td class="ETIQUETA">Persona de Contacto</td>
		<td colspan="3"><input name="pag_web" type="text" value="<?=$pag_web;?>" size="40"></td>
	</tr>
	<tr>
		<td  class="ETIQUETA">Estatus</td>
		<td >
			<select name="estatu" id="estatu">
				<option value="0" selected="selected"></option>
				<option value="1" <?=$act;?>>Activo</option>
				<option value="2" <?=$act2;?>>Inactivo</option>
		  </select>		</td>
		<td >&nbsp;</td>
		<td >&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="titulo" align="center" bgcolor="#44439A">Direcci&oacute;n</td>
	</tr>

	<tr>
		<td  class="ETIQUETA">Pais</td>
		<td >
			<select name="pais"  onchange="selectChange(this, clienten.estado, arrItems1,arrItems2, arrItemsGrp1);" onfocus="selectChange(this, clienten.estado, arrItems1,arrItems2, arrItemsGrp1);">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("paises") as $obj){ ?>
				<?
					if ($pais == $obj-> get(0))
					{
				?>
						<option value="<?= $obj-> get(0)?>" selected="selected"><?= $obj-> get(1)?></option>
				<? 	}else{?>
						<option value="<?= $obj-> get(0)?>"><?= $obj-> get(1)?></option>
				<? }
				} ?>
			</select>		
		</td>
		<td  class="ETIQUETA">Estado</td>
		<td >
			<select name="estado"   onchange="selectChanges(this, clienten.ciudad, uno, dos, tres);" onfocus="selectChanges(this, clienten.ciudad, uno, dos, tres);">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("estados") as $obj){ 
						if ($estado == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(2)?></option>
				<? } ?>
			</select>		
		</td>
	</tr>
	<tr>
		<td class="ETIQUETA">Ciudad</td>
		<td>
			<select name="ciudad">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("ciudades") as $obj){ 
						if ($ciudad == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}				
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(2)?></option>
				<? } ?>
			</select>
		</td>
		<td class="ETIQUETA">Urbanizaci&oacute;n</td>
		<td><input type="text" name="urb" class="campo" value=" <?=$urb;?>"></td>
	</tr>
	<tr>
		<td class="ETIQUETA">Tipo de Inmueble</td>
		<td>
			<select name="tip_inmu" id="tip_inmu">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("tipos_inmuebles") as $obj){ 
						if ($tip_inmu == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}				
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(1)?></option>
				<? } ?>
		  </select>
		</td>
		<td class="ETIQUETA">Piso</td>
		<td><input type="text" name="piso" class="campo" value=" <?=$piso;?>"></td>
	</tr>
	<tr>
		<td class="ETIQUETA">Punto Refenrencia</td>
		<td><input type="text" class="campo" name="ptoref" value=" <?=$ptoref;?>"></td>
		<td class="ETIQUETA">Descripci&oacute;n</td>
		<td class="ETIQUETA"><input type="text" name="descripcion" value=" <?=$descripcion;?>" class="campo"></td>
	</tr>
	<tr>
		<td colspan="4" class="titulo" align="center" bgcolor="#44439A">Tel&eacute;fonos</td>
	</tr>
	<tr>
		<td class="ETIQUETA">Principal</td>
		<td>
			<select name="cod_area">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("codigos_areas") as $obj){ 
						if ($cod_area == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}				
				
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(1)?></option>
				<? } ?>
			</select>
			<input type="text" name="telefono" class="campo" value="<?=$telefono;?>"  onKeyPress="return acceptNumInt(event);"/>
		</td>
		<td class="ETIQUETA">Celular</td>
		<td>
			<select name="cod_cel">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("codigos_celulares") as $obj){ 
						if ($cod_cel == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}				
				
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(1)?></option>
				<? } ?>
			</select>
			<input type="text" name="celular" class="campo" value="<?=$celular;?>"  onKeyPress="return acceptNumInt(event);"/>
		</td>
	</tr>
	<tr>
		<td class="ETIQUETA">Fax</td>
		<td>
			<select name="cod_fax">
				<option value="0"></option>
				<?  foreach(Buscar::buscarSelect("codigos_areas") as $obj){ 
						if ($cod_fax == $obj-> get(0))
						{
							$selec = 'selected="selected"';
						}else{
							$selec = "";
						}				
				
				?>
						<option value="<?= $obj-> get(0)?>" <?=$selec;?>><?= $obj-> get(1)?></option>
				<? } ?>
			</select>
			<input type="text" name="fax" class="campo" value="<?=$fax;?>"  onKeyPress="return acceptNumInt(event);"/>
		</td>
		<td class="ETIQUETA">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td><input type="button" name="Agregar" value="AGREGAR" onclick="agregar();"></td>
		<td><input type="button" name="AGREGARCONTI" value="AGREGAR Y CONTINUAR" onclick="agregar2();"></td>
		<td>&nbsp;</td>
		<td><input type="button" name="REGRESAR" value="REGRESAR"></td>
	</tr>
</table>
<input type="hidden" name="id_cliente" value="<?=$id_cliente;?>"/>
<input type="hidden" name="id_direccion" value="<?=$id_direccion;?>"/>
<input type="hidden" name="id_telefono" value="<?=$id_telefono;?>"/>
<input type="hidden" name="operacion" value="<?=$_POST["operacion"];?>"/>
<input type="hidden" name="formula" />
<input type="hidden" name="boton" />
</form>
<script>
function agregar()
{
	var boton = 1;
	var formula = 8;
	var operacion = document.all.operacion.value;	
	document.all.formula.value = formula;
	document.all.boton.value = boton;
	if (operacion == 2)
	{
		clienten.action="../AJAX/seocac.php";
	}else
	{
		clienten.action="../AJAX/seocin.php";
	}
	clienten.submit();
}
function agregar2()
{
	var boton = 2;
	var formula = 8;
	document.all.formula.value = formula;
	document.all.boton.value = boton;
	clienten.action="../AJAX/seocin.php";
	clienten.submit();
}

</script>
<script language=javascript src="../../datos_base/RECURSOS/dlcalendar.js" type=text/javascript></script>	
